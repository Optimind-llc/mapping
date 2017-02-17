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
                'sort'       => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'code'       => '6A',
                'sort'       => 2,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'code'       => '22A',
                'sort'       => 3,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'code'       => '10B',
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
                'vehicle_code' => '132A',
                'line_code'    => 'ATR18',
                'pt_pn'        => '5381212B70',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'vehicle_code' => '132A',
                'line_code'    => 'ATR18',
                'pt_pn'        => '5381112B70',
                'created_at'   => $now,
                'updated_at'   => $now
            ],
        ];

        DB::connection($connection)->table($table_name)->insert($data);

        DB::connection($connection)->statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}