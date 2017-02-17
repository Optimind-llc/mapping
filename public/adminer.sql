-- Adminer 4.2.5 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `mapping_takaoka_press` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `mapping_takaoka_press`;

CREATE TABLE `chokus` (
  `code` varchar(16) NOT NULL,
  `name` varchar(16) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`code`),
  UNIQUE KEY `chokus_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `chokus` (`code`, `name`, `status`, `created_at`, `updated_at`) VALUES
('B',	'黒直',	0,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
('W',	'白直',	1,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
('Y',	'黄直',	1,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32');

CREATE TABLE `combinations` (
  `vehicle_code` varchar(16) NOT NULL,
  `line_code` varchar(16) NOT NULL,
  `pt_code` varchar(16) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`vehicle_code`,`line_code`,`pt_code`),
  KEY `combinations_line_code_foreign` (`line_code`),
  KEY `combinations_pt_code_foreign` (`pt_code`),
  CONSTRAINT `combinations_line_code_foreign` FOREIGN KEY (`line_code`) REFERENCES `lines` (`code`) ON UPDATE CASCADE,
  CONSTRAINT `combinations_pt_code_foreign` FOREIGN KEY (`pt_code`) REFERENCES `part_types` (`code`) ON UPDATE CASCADE,
  CONSTRAINT `combinations_vehicle_code_foreign` FOREIGN KEY (`vehicle_code`) REFERENCES `vehicles` (`code`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `combinations` (`vehicle_code`, `line_code`, `pt_code`, `created_at`, `updated_at`) VALUES
('132A',	'ATR18',	'5381112B70',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
('132A',	'ATR18',	'5381212B70',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32');

CREATE TABLE `failures` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `x` int(10) unsigned NOT NULL DEFAULT '0',
  `y` int(10) unsigned NOT NULL DEFAULT '0',
  `sub_x` int(10) unsigned NOT NULL DEFAULT '0',
  `sub_y` int(10) unsigned NOT NULL DEFAULT '0',
  `type_id` int(10) unsigned NOT NULL,
  `ir_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `failures_type_id_foreign` (`type_id`),
  KEY `failures_ir_id_foreign` (`ir_id`),
  CONSTRAINT `failures_ir_id_foreign` FOREIGN KEY (`ir_id`) REFERENCES `inspection_results` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `failures_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `failure_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `failure_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(16) NOT NULL,
  `label` int(10) unsigned NOT NULL DEFAULT '1',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failure_types_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `failure_types` (`id`, `name`, `label`, `status`, `created_at`, `updated_at`) VALUES
(1,	'凸',	1,	1,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(2,	'凹',	2,	1,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(3,	'油歪凸',	3,	1,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(4,	'油歪凹',	4,	1,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(5,	'型当凸',	5,	1,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(6,	'型当凹',	6,	1,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(7,	'カジリ',	7,	1,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(8,	'カップ歪',	8,	1,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(9,	'スクラ押',	9,	1,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(10,	'パウダリ',	10,	1,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(11,	'変形',	11,	1,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(12,	'バリ',	12,	1,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(13,	'キズ',	13,	1,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(14,	'マクレ',	14,	1,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(15,	'その他',	99,	1,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32');

CREATE TABLE `inspection_results` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vehicle_code` varchar(16) NOT NULL,
  `line_code` varchar(16) NOT NULL,
  `pt_code` varchar(16) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `discarded` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created_choku` varchar(8) NOT NULL,
  `updated_choku` varchar(8) DEFAULT NULL,
  `created_by` varchar(8) NOT NULL,
  `updated_by` varchar(8) DEFAULT NULL,
  `palet_num` int(10) unsigned NOT NULL,
  `palet_max` int(10) unsigned NOT NULL,
  `ft_ids` varchar(512) DEFAULT NULL,
  `f_comment` varchar(255) DEFAULT NULL,
  `m_comment` varchar(255) DEFAULT NULL,
  `processed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `inspected_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modificated_at` timestamp NULL DEFAULT NULL,
  `exported_at` timestamp NULL DEFAULT NULL,
  `latest` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inspection_results_vehicle_code_foreign` (`vehicle_code`),
  KEY `inspection_results_line_code_foreign` (`line_code`),
  KEY `inspection_results_pt_code_foreign` (`pt_code`),
  CONSTRAINT `inspection_results_line_code_foreign` FOREIGN KEY (`line_code`) REFERENCES `lines` (`code`) ON UPDATE CASCADE,
  CONSTRAINT `inspection_results_pt_code_foreign` FOREIGN KEY (`pt_code`) REFERENCES `part_types` (`code`) ON UPDATE CASCADE,
  CONSTRAINT `inspection_results_vehicle_code_foreign` FOREIGN KEY (`vehicle_code`) REFERENCES `vehicles` (`code`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_reserved_reserved_at_index` (`queue`,`reserved`,`reserved_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `lines` (
  `code` varchar(16) NOT NULL,
  `sort` int(10) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `lines` (`code`, `sort`, `created_at`, `updated_at`) VALUES
('10B',	4,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
('22A',	3,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
('6A',	2,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
('ATR18',	1,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32');

CREATE TABLE `migrations` (
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_000000_create_users_table',	1),
('2014_10_12_100000_create_password_resets_table',	1),
('2016_06_21_094134_create_jobs_table',	1),
('2016_09_01_000000_create_base_tables_for_press',	1),
('2016_09_01_000001_create_result_tables_for_press',	1);

CREATE TABLE `part_types` (
  `code` varchar(10) NOT NULL,
  `name` varchar(32) DEFAULT NULL,
  `en` varchar(32) DEFAULT NULL,
  `sort` int(10) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`code`),
  UNIQUE KEY `part_types_name_unique` (`name`),
  UNIQUE KEY `part_types_en_unique` (`en`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `part_types` (`code`, `name`, `en`, `sort`, `created_at`, `updated_at`) VALUES
('5381112B70',	'パネル フロントフェンダR',	'panel frontFenderR',	2,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
('5381212B70',	'パネル フロントフェンダL',	'panel frontFenderL',	1,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32');

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `password` varchar(60) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `vehicles` (
  `code` varchar(16) NOT NULL,
  `sort` int(10) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `vehicles` (`code`, `sort`, `created_at`, `updated_at`) VALUES
('030A',	4,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
('410A',	3,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
('520A',	2,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
('660L',	6,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
('745L',	5,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
('963A',	1,	'2017-02-15 09:06:32',	'2017-02-15 09:06:32');

CREATE TABLE `workers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(16) NOT NULL,
  `yomi` varchar(16) NOT NULL,
  `code` varchar(16) DEFAULT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `choku_code` varchar(16) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `workers_code_unique` (`code`),
  KEY `workers_choku_code_foreign` (`choku_code`),
  CONSTRAINT `workers_choku_code_foreign` FOREIGN KEY (`choku_code`) REFERENCES `chokus` (`code`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `workers` (`id`, `name`, `yomi`, `code`, `status`, `choku_code`, `created_at`, `updated_at`) VALUES
(1,	'田村 良二',	'タムラリョウジ',	NULL,	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(2,	'高木 洋一',	'タカギヨウイチ',	NULL,	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(3,	'山本 佳祐',	'ヤマシタケイスケ',	'0003',	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(4,	'森下 和哉',	'モリシタカズヤ',	'0004',	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(5,	'浅田 英幸',	'アサダヒデユキ',	'0005',	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(6,	'青木 匠',	'アオキタクミ',	'0006',	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(7,	'高須 信吾',	'タカスシンゴ',	'0007',	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(8,	'矢澤 鉱一',	'ヤザワコウイチ',	'0008',	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(9,	'大園 博美',	'オオゾノヒロミ',	'0009',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(10,	'川畑 英義',	'カワバタヒデヨシ',	'0010',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(11,	'金谷 達弘',	'カナヤタツヒロ',	'0011',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(12,	'阿部 哲士',	'アベテツシ',	'0012',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(13,	'黒崎 将平',	'クロサキ ショウヘイ',	'0013',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(14,	'小川 晟央',	'オガワマサオ',	'0014',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(15,	'古井 康真',	'フルイヤスシ',	'0015',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(16,	'大塚 絢貴',	'オオツカアヤタカ',	'0016',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(17,	'濱田 政雄',	'ハマダマサオ',	'0017',	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(18,	'岡本 崇弘',	'オカモトタカヒロ',	'0018',	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(19,	'戸上 憲一',	'トガミケンイチ',	'0019',	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(20,	'西嶋 慎吾',	'ニシジマシンゴ',	'0020',	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(21,	'橋本 高浩',	'ハシモトタカヒロ',	'0021',	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(22,	'西川 佳孝',	'ニシカワヨシタカ',	'0022',	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(23,	'嶋津 直樹',	'シマズナオキ',	'0023',	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(24,	'梅田 雄司',	'ウメダユウジ',	'0024',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(25,	'山尾 祐介',	'ヤマオユウスケ',	'0025',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(26,	'白波瀬 忍',	'シロナミセシノブ',	'0026',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(27,	'中田 将吾',	'ナカダショウゴ',	'0027',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(28,	'井坂 光雄',	'イサカミツオ',	'0028',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(29,	'大寺 真悟',	'オオデラシンゴ',	'0029',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(30,	'大谷 直人',	'オオタニナオト',	'0030',	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(31,	'児嶋 誠',	'コジママコト',	'0031',	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(32,	'吉武 伸浩',	'ヨシタケノブヒロ',	'0032',	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(33,	'加藤 徹',	'カトウトオル',	'0033',	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(34,	'瀬川 瑛志',	'セガワエイシ',	'0034',	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(35,	'宗像 徹郎',	'ムナカタテツロウ',	'0035',	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(36,	'田村 豊',	'タムラユタカ',	'0036',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(37,	'伊藤 伸一',	'イトウシンイチ',	'0037',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(38,	'中本 直樹',	'ナカモトナオキ',	'0038',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(39,	'尾前 裕也',	'オマエユウヤ',	'0039',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(40,	'蘭 龍二',	'ランリュウジ',	'0040',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(41,	'渡辺 孝司',	'ワタナベコウジ',	'0041',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(42,	'大石 峰志',	'オオイシタカシ',	'0042',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(43,	'土居 豊',	'ツチイ ユタカ',	'0043',	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(44,	'龍田 憲太',	'タツタケンタ',	'0044',	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(45,	'小林 徹也',	'コバヤシテツヤ',	'0045',	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(46,	'渡久地 政通',	'トグチマサトシ',	'0046',	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(47,	'浅野 秀明',	'アサノヒデアキ',	'0047',	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(48,	'蜂谷 拓也',	'ハチヤタクヤ',	'0048',	1,	'W',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(49,	'瀬畑 長正',	'セバタナガマサ',	'0049',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(50,	'長畑 学',	'ナガハタマナブ',	'0050',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(51,	'吉川 慎吾',	'ヨシカワ シンゴ',	'0051',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(52,	'東野 博',	'ヒガシノヒロシ',	'0052',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(53,	'藤川 徹',	'フジカワトオル',	'0053',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32'),
(54,	'萩野 隆',	'ハギノタカシ',	'0054',	1,	'Y',	'2017-02-15 09:06:32',	'2017-02-15 09:06:32');

-- 2017-02-15 09:13:44
