<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class FailureTableSeeder
 */
class RelatedFailureTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $connection = 'press';
        DB::connection($connection)->statement('SET FOREIGN_KEY_CHECKS=0;');

        /**
         * create failure table seeder
         */
        $table_name = 'failure_types';
        DB::connection($connection)->table($table_name)->truncate();

        $data = [
            [
                'name'       => '凸',
                'label'      => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '凹',
                'label'      => 2,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '油歪凸',
                'label'      => 3,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '油歪凹',
                'label'      => 4,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '型当凸',
                'label'      => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '型当凹',
                'label'      => 6,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'カジリ',
                'label'      => 7,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'カップ歪',
                'label'      => 8,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'スクラ押',
                'label'      => 9,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'パウダリ',
                'label'      => 10,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '変形',
                'label'      => 11,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'バリ',
                'label'      => 12,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'キズ',
                'label'      => 13,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'マクレ',
                'label'      => 14,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'その他',
                'label'      => 99,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        DB::connection($connection)->table($table_name)->insert($data);

        DB::connection($connection)->statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}