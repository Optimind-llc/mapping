<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class RelatedFigureTableSeeder
 */
class RelatedFigureTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $connection = 'press';
        DB::connection($connection)->statement('SET FOREIGN_KEY_CHECKS=0;');

        /**
         * create failure table seeder
         */
        $table_name = 'figures';
        DB::connection($connection)->table($table_name)->truncate();

        $data = [
            [
                'pt_pn'      => '5381212B70',
                'status'     => 1,
                'path'       => '/img/figures/5381212B70-123456789.png',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'pt_pn'      => '5381212B70',
                'status'     => 0,
                'path'       => '/img/figures/5381212B70-123456788.png',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'pt_pn'      => '5381112B70',
                'status'     => 1,
                'path'       => '/img/figures/5381112B70-123456789.png',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        DB::connection($connection)->table($table_name)->insert($data);

        DB::connection($connection)->statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}