<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class KanbanCsvImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'KanbanCsvImport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'insert csv data to arrivals';

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
        $csvDir = base_path('FailureMapping/kanban/from').DIRECTORY_SEPARATOR;
        $toDir = base_path('FailureMapping/kanban/to').DIRECTORY_SEPARATOR;

        $this->info($csvDir);
        $this->info($toDir);

        //ファイル移動
        $files = array();
        $handle = opendir($csvDir);
        while ($file = readdir($handle)) {
            $filePath = $csvDir . $file;
            $extension = pathinfo($file)["extension"];
            if (is_file($filePath) && $extension == 'csv') {
                \File::move($csvDir . $file, $toDir . $file);
                array_push($files, $file);
            }else{
                \File::delete($file);
            }
        }
        closedir($handle);

        if (count($files) != 0) {
            $readFile = 'XXXXX_00000000000000.csv';
            foreach ($files as $file) {
                if (substr($readFile, 6, 14) < substr($file, 6, 14)) {
                    $readFile = $file;
                    $this->info($readFile);
                } else {
                    \File::delete($file);
                }
            }

            $file = new \SplFileObject($toDir . $readFile);
            $file->setFlags(\SplFileObject::READ_CSV);
            foreach ($file as $row) {
                $this->info($row[26]);
                if ($row[26] != '' && $row[27] != '' && $row[76] != ''
                 && $row[78] != '' && $row[80] != '') {
                    $storeOutDateTime = $row[76];
                    $arrivalDiv = $row[79];

                    if ($arrivalDiv == '') {
                        // $arrivalDiv = DB::table('arrivals')
                        //                 ->where('code', $row[78])
                        //                 ->where('dir', $row[80])->first();
                    }
                    $this->info($storeOutDateTime);
                    $this->info($arrivalDiv);
                    // InspectionResult::where('re_print_sec', $row[26])
                    //     ->where('LEFT(inspected_at, 4)', substr($row[27], 0, 4))
                    //     ->update([
                    //       'picked_at' => $storeOutDateTime,
                    //       'picked_by' => $arrivalDiv
                    //     ]);

                    DB::connection('press')->table('inspection_results')
                        ->where('re_print_sec', $row[26])
                        ->where('LEFT(inspected_at, 4)', substr($row[27], 0, 4))
                        ->update([
                          'picked_at' => $storeOutDateTime,
                          'picked_by' => $arrivalDiv
                        ]);
                }
            }
            closedir($handle);
            \File::delete($toDir . $readFile);
        }
    }
}
