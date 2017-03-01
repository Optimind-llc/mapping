<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// Models
use App\Models\Vehicle950A\InspectionResult;

class CheckConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkConfig';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check configs';

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
        $TPS_ip = config('socket.'.config('app.server_place').'.ip');
        $TPS_port = config('socket.'.config('app.server_place').'.port');

        $this->info('TPS IP: '.$TPS_ip);
        $this->info('TPS port: '.$TPS_port);
    }
}
