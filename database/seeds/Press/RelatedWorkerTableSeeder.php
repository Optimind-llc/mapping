<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class InspectorTableSeeder
 */
class RelatedWorkerTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $connection = 'press';
        DB::connection($connection)->statement('SET FOREIGN_KEY_CHECKS=0;');

        /*
         * Create choku table seeder
         */
        $table_name = 'chokus';
        DB::connection($connection)->table($table_name)->truncate();

        $data = [
            [
                'name'       => '黄直',
                'code'       => 'Y',
                'status'     => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '白直',
                'code'       => 'W',
                'status'     => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '黒直',
                'code'       => 'B',
                'status'     => 0,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        DB::connection($connection)->table($table_name)->insert($data);

        /*
         * Create worker table seeder
         */
        $table_name = 'workers';
        DB::connection($connection)->table($table_name)->truncate();

        $data = [
            [
                'name'       => '田村 良二',
                'yomi'       => 'タムラリョウジ',
                'sort'       => 1,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '高木 洋一',
                'yomi'       => 'タカギヨウイチ',
                'sort'       => 2,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '山本 佳祐',
                'yomi'       => 'ヤマシタケイスケ',
                'sort'       => 3,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '森下 和哉',
                'yomi'       => 'モリシタカズヤ',
                'sort'       => 4,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '浅田 英幸',
                'yomi'       => 'アサダヒデユキ',
                'sort'       => 5,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '青木 匠',
                'yomi'       => 'アオキタクミ',
                'sort'       => 6,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '高須 信吾',
                'yomi'       => 'タカスシンゴ',
                'sort'       => 7,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '矢澤 鉱一',
                'yomi'       => 'ヤザワコウイチ',
                'sort'       => 8,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '大園 博美',
                'yomi'       => 'オオゾノヒロミ',
                'sort'       => 9,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '川畑 英義',
                'yomi'       => 'カワバタヒデヨシ',
                'sort'       => 10,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '金谷 達弘',
                'yomi'       => 'カナヤタツヒロ',
                'sort'       => 11,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '阿部 哲士',
                'yomi'       => 'アベテツシ',
                'sort'       => 12,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '黒崎 将平',
                'yomi'       => 'クロサキ ショウヘイ',
                'sort'       => 13,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '小川 晟央',
                'yomi'       => 'オガワマサオ',
                'sort'       => 14,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '古井 康真',
                'yomi'       => 'フルイヤスシ',
                'sort'       => 15,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '大塚 絢貴',
                'yomi'       => 'オオツカアヤタカ',
                'sort'       => 16,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '濱田 政雄',
                'yomi'       => 'ハマダマサオ',
                'sort'       => 17,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '岡本 崇弘',
                'yomi'       => 'オカモトタカヒロ',
                'sort'       => 18,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '戸上 憲一',
                'yomi'       => 'トガミケンイチ',
                'sort'       => 19,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '西嶋 慎吾',
                'yomi'       => 'ニシジマシンゴ',
                'sort'       => 20,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '橋本 高浩',
                'yomi'       => 'ハシモトタカヒロ',
                'sort'       => 21,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '西川 佳孝',
                'yomi'       => 'ニシカワヨシタカ',
                'sort'       => 22,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '嶋津 直樹',
                'yomi'       => 'シマズナオキ',
                'sort'       => 23,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '梅田 雄司',
                'yomi'       => 'ウメダユウジ',
                'sort'       => 24,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '山尾 祐介',
                'yomi'       => 'ヤマオユウスケ',
                'sort'       => 25,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '白波瀬 忍',
                'yomi'       => 'シロナミセシノブ',
                'sort'       => 26,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '中田 将吾',
                'yomi'       => 'ナカダショウゴ',
                'sort'       => 27,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '井坂 光雄',
                'yomi'       => 'イサカミツオ',
                'sort'       => 28,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '大寺 真悟',
                'yomi'       => 'オオデラシンゴ',
                'sort'       => 29,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '大谷 直人',
                'yomi'       => 'オオタニナオト',
                'sort'       => 30,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '児嶋 誠',
                'yomi'       => 'コジママコト',
                'sort'       => 31,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '吉武 伸浩',
                'yomi'       => 'ヨシタケノブヒロ',
                'sort'       => 32,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '加藤 徹',
                'yomi'       => 'カトウトオル',
                'sort'       => 33,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '瀬川 瑛志',
                'yomi'       => 'セガワエイシ',
                'sort'       => 34,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '宗像 徹郎',
                'yomi'       => 'ムナカタテツロウ',
                'sort'       => 35,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '田村 豊',
                'yomi'       => 'タムラユタカ',
                'sort'       => 36,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '伊藤 伸一',
                'yomi'       => 'イトウシンイチ',
                'sort'       => 37,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '中本 直樹',
                'yomi'       => 'ナカモトナオキ',
                'sort'       => 38,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '尾前 裕也',
                'yomi'       => 'オマエユウヤ',
                'sort'       => 39,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '蘭 龍二',
                'yomi'       => 'ランリュウジ',
                'sort'       => 40,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '渡辺 孝司',
                'yomi'       => 'ワタナベコウジ',
                'sort'       => 41,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '大石 峰志',
                'yomi'       => 'オオイシタカシ',
                'sort'       => 42,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '土居 豊',
                'yomi'       => 'ツチイ ユタカ',
                'sort'       => 43,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '龍田 憲太',
                'yomi'       => 'タツタケンタ',
                'sort'       => 44,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '小林 徹也',
                'yomi'       => 'コバヤシテツヤ',
                'sort'       => 45,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '渡久地 政通',
                'yomi'       => 'トグチマサトシ',
                'sort'       => 46,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '浅野 秀明',
                'yomi'       => 'アサノヒデアキ',
                'sort'       => 47,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '蜂谷 拓也',
                'yomi'       => 'ハチヤタクヤ',
                'sort'       => 48,
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '瀬畑 長正',
                'yomi'       => 'セバタナガマサ',
                'sort'       => 49,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '長畑 学',
                'yomi'       => 'ナガハタマナブ',
                'sort'       => 50,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '吉川 慎吾',
                'yomi'       => 'ヨシカワ シンゴ',
                'sort'       => 51,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '東野 博',
                'yomi'       => 'ヒガシノヒロシ',
                'sort'       => 52,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '藤川 徹',
                'yomi'       => 'フジカワトオル',
                'sort'       => 53,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '萩野 隆',
                'yomi'       => 'ハギノタカシ',
                'sort'       => 54,
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        DB::connection($connection)->table($table_name)->insert($data);

        /*
         * Create worker related table seeder
         */
        $table_name = 'worker_related';
        DB::connection($connection)->table($table_name)->truncate();

        $data = [
            //ATR18_白
            [
                'line_code' => 'ATR18',
                'worker_id' => 1,
                'sort'      => 1
            ],[
                'line_code' => 'ATR18',
                'worker_id' => 2,
                'sort'      => 2
            ],
            //ATR18_黄
            [
                'line_code' => 'ATR18',
                'worker_id' => 9,
                'sort'      => 1
            ],[
                'line_code' => 'ATR18',
                'worker_id' => 10,
                'sort'      => 2
            ],


            //22A_白
            [
                'line_code' => '22A',
                'worker_id' => 3,
                'sort'      => 1
            ],[
                'line_code' => '22A',
                'worker_id' => 4,
                'sort'      => 2
            ],
            //22A_黄
            [
                'line_code' => '22A',
                'worker_id' => 11,
                'sort'      => 1
            ],[
                'line_code' => '22A',
                'worker_id' => 12,
                'sort'      => 2
            ],


            //10B_白
            [
                'line_code' => '10B',
                'worker_id' => 5,
                'sort'      => 1
            ],[
                'line_code' => '10B',
                'worker_id' => 6,
                'sort'      => 2
            ],
            //10B_黄
            [
                'line_code' => '10B',
                'worker_id' => 13,
                'sort'      => 1
            ],[
                'line_code' => '10B',
                'worker_id' => 14,
                'sort'      => 2
            ],


            //6A_白
            [
                'line_code' => '6A',
                'worker_id' => 7,
                'sort'      => 1
            ],[
                'line_code' => '6A',
                'worker_id' => 8,
                'sort'      => 2
            ],
            //6A_黄
            [
                'line_code' => '6A',
                'worker_id' => 15,
                'sort'      => 1
            ],[
                'line_code' => '6A',
                'worker_id' => 16,
                'sort'      => 2
            ],
        ];

        DB::connection($connection)->table($table_name)->insert($data);

        DB::connection($connection)->statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
