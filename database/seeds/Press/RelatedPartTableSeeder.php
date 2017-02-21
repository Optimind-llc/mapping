<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/*
 * Class FigureTableSeeder
 */
class RelatedPartTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $connection = 'press';
        DB::connection($connection)->statement('SET FOREIGN_KEY_CHECKS=0;');

        /*
         * Create vehicles table seeder
         */
        $table_name = 'vehicles';
        DB::connection($connection)->table($table_name)->truncate();

        $data = [
            [
                'code'       => '963A',
                'sort'       => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'code'       => '520A',
                'sort'       => 2,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'code'       => '410A',
                'sort'       => 3,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'code'       => '030A',
                'sort'       => 4,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'code'       => '745L',
                'sort'       => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'code'       => '660L',
                'sort'       => 6,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        DB::connection($connection)->table($table_name)->insert($data);

        /*
         * Create lines table seeder
         */
        $table_name = 'lines';
        DB::connection($connection)->table($table_name)->truncate();

        $data = [
            [
                'code'       => 'ATR18',
                'code_inQR'  => 'H6T',
                'sort'       => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'code'       => '6A',
                'code_inQR'  => 'E6T',
                'sort'       => 2,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'code'       => '22A',
                'code_inQR'  => 'I2A',
                'sort'       => 3,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'code'       => '10B',
                'code_inQR'  => 'E7T',
                'sort'       => 4,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        DB::connection($connection)->table($table_name)->insert($data);

        /*
         * Create part_types table seeder
         */
        $table_name = 'part_types';
        DB::connection($connection)->table($table_name)->truncate();

        $data = [
            [
                'pn'         => '5381212B70',
                'name'       => 'パネル フロントフェンダL',
                'en'         => 'panel frontFenderL',
                'sort'       => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'pn'         => '5381112B70',
                'name'       => 'パネル フロントフェンダR',
                'en'         => 'panel frontFenderR',
                'sort'       => 2,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'pn'         => '1111111111',
                'name'       => 'ダミー1',
                'en'         => 'dummy1',
                'sort'       => 3,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'pn'         => '2222222222',
                'name'       => 'ダミー2',
                'en'         => 'dummy2',
                'sort'       => 4,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'pn'         => '3333333333',
                'name'       => 'ダミー3',
                'en'         => 'dummy3',
                'sort'       => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'pn'         => '4444444444',
                'name'       => 'ダミー4',
                'en'         => 'dummy4',
                'sort'       => 6,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        DB::connection($connection)->table($table_name)->insert($data);

        /*
         * Create combinations table seeder
         */
        $table_name = 'combinations';
        DB::connection($connection)->table($table_name)->truncate();

        $data = [
            [
                'line_code'    => 'ATR18',
                'vehicle_code' => '963A',
                'pt_pn'        => '5381212B70',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'line_code'    => 'ATR18',
                'vehicle_code' => '963A',
                'pt_pn'        => '5381112B70',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'line_code'    => 'ATR18',
                'vehicle_code' => '520A',
                'pt_pn'        => '1111111111',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'line_code'    => 'ATR18',
                'vehicle_code' => '410A',
                'pt_pn'        => '2222222222',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'line_code'    => 'ATR18',
                'vehicle_code' => '030A',
                'pt_pn'        => '3333333333',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'line_code'    => 'ATR18',
                'vehicle_code' => '745L',
                'pt_pn'        => '3333333333',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'line_code'    => 'ATR18',
                'vehicle_code' => '660L',
                'pt_pn'        => '4444444444',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'line_code'    => '6A',
                'vehicle_code' => '660L',
                'pt_pn'        => '4444444444',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'line_code'    => '22A',
                'vehicle_code' => '660L',
                'pt_pn'        => '4444444444',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'line_code'    => '10B',
                'vehicle_code' => '660L',
                'pt_pn'        => '4444444444',
                'created_at'   => $now,
                'updated_at'   => $now
            ],
        ];

        DB::connection($connection)->table($table_name)->insert($data);

        DB::connection($connection)->statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}