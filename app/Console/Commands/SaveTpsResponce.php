<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// Models
use App\Models\Press\InspectionResult;
//Services
use App\Services\TpsConnect;

class SaveTpsResponce extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SaveTpsResponce';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'connect TPS and save respnce to DB';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today = Carbon::today();
        $limit = $today->subDays(10)->toDateTimeString();

        $irs = InspectionResult::whereNull('tpsResponceStatus')
            ->where('created_at', '>', $limit)
            ->select(['id', 'QRcode', 'inspected_iPad_id as iPadId'])
            ->get();

        $tpsConnect = new TpsConnect;
        foreach ($irs as $ir) {
            $tpsConnect->setPostData($ir->QRcode, $ir->iPadId);
            // $tpsConnect->putSocket();
            $tpsConnect->putSocketDummy();
            $responceData = $tpsConnect->getResponceData();

            if ($responceData !== null) {
                $ir->tpsResponce = $responceData;
                $ir->tpsResponceStatus = substr($responceData, 47, 1);
                $ir->save();
            }
        }

        $this->info('ok');
    }
}
