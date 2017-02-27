-- Adminer 4.2.5 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `chokus`;
CREATE TABLE `chokus` (
  `code` varchar(1) NOT NULL,
  `name` varchar(16) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`code`),
  UNIQUE KEY `chokus_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `chokus` (`code`, `name`, `status`, `created_at`, `updated_at`) VALUES
('B',	'黒直',	0,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42'),
('W',	'白直',	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42'),
('Y',	'黄直',	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42');

DROP TABLE IF EXISTS `combinations`;
CREATE TABLE `combinations` (
  `line_code` varchar(16) NOT NULL,
  `vehicle_code` varchar(16) NOT NULL,
  `pt_pn` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`line_code`,`vehicle_code`,`pt_pn`),
  KEY `combinations_vehicle_code_foreign` (`vehicle_code`),
  KEY `combinations_pt_pn_foreign` (`pt_pn`),
  CONSTRAINT `combinations_line_code_foreign` FOREIGN KEY (`line_code`) REFERENCES `lines` (`code`) ON UPDATE CASCADE,
  CONSTRAINT `combinations_pt_pn_foreign` FOREIGN KEY (`pt_pn`) REFERENCES `part_types` (`pn`) ON UPDATE CASCADE,
  CONSTRAINT `combinations_vehicle_code_foreign` FOREIGN KEY (`vehicle_code`) REFERENCES `vehicles` (`code`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `combinations` (`line_code`, `vehicle_code`, `pt_pn`, `created_at`, `updated_at`) VALUES
('ATR18',	'030A',	'6714347040',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'030A',	'6714447040',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'030A',	'Saito-test',	'2017-02-23 11:08:41',	'2017-02-23 11:08:41'),
('ATR18',	'132A',	'5381112B70',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'132A',	'5381212B70',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'132A',	'6111112590',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'132A',	'6111212530',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'132A',	'6311112A00',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'132A',	'6711112730',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'132A',	'6711212720',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'132A',	'6711812210',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'132A',	'6711812240',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'132A',	'6711912060',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'520A',	'5381148210',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'520A',	'5381248190',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'520A',	'6111148070',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'520A',	'6111248060',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'520A',	'6311148190',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'520A',	'6311148220',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'520A',	'6711148070',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'520A',	'6711248070',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'520A',	'6711348070',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'520A',	'6711448070',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'520A',	'6714148060',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'520A',	'6714248060',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'530A',	'5821148080',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'610A',	'5821178010',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'745L',	'5381121140',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'745L',	'5381221160',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'745L',	'6111121150',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'745L',	'6111221130',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'963A',	'6311142231',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'963A',	'6311142241',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'963A',	'6311142904',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10'),
('ATR18',	'963A',	'6311142905',	'2017-02-23 07:22:10',	'2017-02-23 07:22:10');

DROP TABLE IF EXISTS `failures`;
CREATE TABLE `failures` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int(10) unsigned NOT NULL,
  `ir_id` int(10) unsigned NOT NULL,
  `figure_id` int(10) unsigned DEFAULT NULL,
  `x1` int(10) unsigned NOT NULL DEFAULT '0',
  `y1` int(10) unsigned NOT NULL DEFAULT '0',
  `x2` int(10) unsigned NOT NULL DEFAULT '0',
  `y2` int(10) unsigned NOT NULL DEFAULT '0',
  `f_qty` int(10) unsigned NOT NULL DEFAULT '0',
  `m_qty` int(10) unsigned DEFAULT NULL,
  `responsible_for` tinyint(3) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `failures_type_id_foreign` (`type_id`),
  KEY `failures_ir_id_foreign` (`ir_id`),
  KEY `failures_figure_id_foreign` (`figure_id`),
  CONSTRAINT `failures_figure_id_foreign` FOREIGN KEY (`figure_id`) REFERENCES `figures` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `failures_ir_id_foreign` FOREIGN KEY (`ir_id`) REFERENCES `inspection_results` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `failures_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `failure_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `failures` (`id`, `type_id`, `ir_id`, `figure_id`, `x1`, `y1`, `x2`, `y2`, `f_qty`, `m_qty`, `responsible_for`, `created_at`, `updated_at`) VALUES
(5,	3,	6,	35,	101,	101,	201,	201,	3,	2,	1,	'2017-02-23 11:09:16',	'2017-02-23 11:12:49'),
(6,	4,	6,	35,	102,	102,	202,	202,	3,	1,	2,	'2017-02-23 11:09:16',	'2017-02-23 11:12:49'),
(7,	10,	6,	35,	103,	103,	203,	203,	3,	0,	3,	'2017-02-23 11:09:16',	'2017-02-23 11:12:49'),
(9,	1,	6,	35,	100,	100,	200,	200,	3,	2,	1,	'2017-02-23 11:12:49',	'2017-02-23 11:12:49'),
(10,	3,	7,	35,	101,	101,	201,	201,	3,	NULL,	0,	'2017-02-23 11:14:34',	'2017-02-23 11:14:34'),
(11,	4,	7,	35,	102,	102,	202,	202,	2,	NULL,	1,	'2017-02-23 11:14:34',	'2017-02-23 11:14:34'),
(12,	10,	7,	35,	103,	103,	203,	203,	1,	NULL,	2,	'2017-02-23 11:14:34',	'2017-02-23 11:14:34'),
(13,	1,	7,	35,	100,	100,	200,	200,	3,	2,	1,	'2017-02-23 13:51:16',	'2017-02-23 13:51:16');

DROP TABLE IF EXISTS `failure_types`;
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
(1,	'凸',	1,	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42'),
(2,	'凹',	2,	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42'),
(3,	'油歪凸',	3,	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42'),
(4,	'油歪凹',	4,	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42'),
(5,	'型当凸',	5,	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42'),
(6,	'型当凹',	6,	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42'),
(7,	'カジリ',	7,	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42'),
(8,	'カップ歪',	8,	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42'),
(9,	'スクラ押',	9,	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42'),
(10,	'パウダリ',	10,	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42'),
(11,	'変形',	11,	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42'),
(12,	'バリ',	12,	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42'),
(13,	'キズ',	13,	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42'),
(14,	'マクレ',	14,	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42'),
(15,	'その他',	99,	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42');

DROP TABLE IF EXISTS `figures`;
CREATE TABLE `figures` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pt_pn` varchar(10) NOT NULL,
  `path` varchar(255) NOT NULL,
  `status` int(10) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `figures_pt_pn_foreign` (`pt_pn`),
  CONSTRAINT `figures_pt_pn_foreign` FOREIGN KEY (`pt_pn`) REFERENCES `part_types` (`pn`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `figures` (`id`, `pt_pn`, `path`, `status`, `created_at`, `updated_at`) VALUES
(1,	'5381112B70',	'/img/figures/5381112B70-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(2,	'5381212B70',	'/img/figures/5381212B70-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(3,	'6711812210',	'/img/figures/6711812210-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(4,	'6711812240',	'/img/figures/6711812240-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(5,	'6711912060',	'/img/figures/6711912060-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(6,	'6311142904',	'/img/figures/6311142904-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(7,	'6111112590',	'/img/figures/6111112590-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(8,	'6111212530',	'/img/figures/6111212530-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(9,	'6311112A00',	'/img/figures/6311112A00-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(10,	'6711112730',	'/img/figures/6711112730-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(11,	'6711212720',	'/img/figures/6711212720-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(12,	'6311142905',	'/img/figures/6311142905-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(13,	'6714347040',	'/img/figures/6714347040-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(14,	'6714447040',	'/img/figures/6714447040-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(15,	'6311142241',	'/img/figures/6311142241-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(16,	'6311142231',	'/img/figures/6311142231-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(17,	'5381148210',	'/img/figures/5381148210-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(18,	'5381248190',	'/img/figures/5381248190-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(19,	'6111148070',	'/img/figures/6111148070-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(20,	'6111248060',	'/img/figures/6111248060-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(21,	'6311148190',	'/img/figures/6311148190-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(22,	'6311148220',	'/img/figures/6311148220-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(23,	'6711148070',	'/img/figures/6711148070-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(24,	'6711248070',	'/img/figures/6711248070-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(25,	'6714148060',	'/img/figures/6714148060-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(26,	'6714248060',	'/img/figures/6714248060-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(27,	'6711348070',	'/img/figures/6711348070-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(28,	'6711448070',	'/img/figures/6711448070-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(29,	'5821178010',	'/img/figures/5821178010-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(30,	'5381121140',	'/img/figures/5381121140-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(31,	'5381221160',	'/img/figures/5381221160-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(32,	'6111121150',	'/img/figures/6111121150-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(33,	'6111221130',	'/img/figures/6111221130-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(34,	'5821148080',	'/img/figures/5821148080-1234567899.png',	1,	'2017-02-23 07:20:09',	'2017-02-23 07:20:09'),
(35,	'Saito-test',	'/Saito-test',	1,	NULL,	NULL);

DROP TABLE IF EXISTS `inspection_results`;
CREATE TABLE `inspection_results` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `QRcode` varchar(134) NOT NULL,
  `line_code` varchar(16) NOT NULL,
  `vehicle_code` varchar(16) NOT NULL,
  `pt_pn` varchar(10) NOT NULL,
  `figure_id` int(10) unsigned DEFAULT NULL,
  `mold_type_num` varchar(4) DEFAULT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `f_keep` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `m_keep` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `discarded` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `inspected_choku` varchar(8) NOT NULL,
  `modificated_choku` varchar(8) DEFAULT NULL,
  `updated_choku` varchar(8) DEFAULT NULL,
  `inspected_by` varchar(8) NOT NULL,
  `modificated_by` varchar(8) DEFAULT NULL,
  `updated_by` varchar(8) DEFAULT NULL,
  `palet_num` int(10) unsigned NOT NULL,
  `palet_max` int(10) unsigned NOT NULL,
  `ft_ids` varchar(512) DEFAULT NULL,
  `f_comment` varchar(255) DEFAULT NULL,
  `m_comment` varchar(255) DEFAULT NULL,
  `processed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `inspected_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modificated_at` timestamp NULL DEFAULT NULL,
  `picked_at` timestamp NULL DEFAULT NULL,
  `exported_at` timestamp NULL DEFAULT NULL,
  `latest` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `control_num` int(11) DEFAULT NULL,
  `inspected_iPad_id` varchar(255) NOT NULL,
  `modificated_iPad_id` varchar(255) DEFAULT NULL,
  `updated_iPad_id` varchar(255) DEFAULT NULL,
  `tpsResponce` varchar(255) DEFAULT NULL,
  `tpsResponceStatus` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inspection_results_qrcode_unique` (`QRcode`),
  UNIQUE KEY `inspection_results_inspected_choku_control_num_unique` (`inspected_choku`,`control_num`),
  KEY `inspection_results_line_code_foreign` (`line_code`),
  KEY `inspection_results_vehicle_code_foreign` (`vehicle_code`),
  KEY `inspection_results_pt_pn_foreign` (`pt_pn`),
  KEY `inspection_results_figure_id_foreign` (`figure_id`),
  CONSTRAINT `inspection_results_figure_id_foreign` FOREIGN KEY (`figure_id`) REFERENCES `figures` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `inspection_results_line_code_foreign` FOREIGN KEY (`line_code`) REFERENCES `lines` (`code`) ON UPDATE CASCADE,
  CONSTRAINT `inspection_results_pt_pn_foreign` FOREIGN KEY (`pt_pn`) REFERENCES `part_types` (`pn`) ON UPDATE CASCADE,
  CONSTRAINT `inspection_results_vehicle_code_foreign` FOREIGN KEY (`vehicle_code`) REFERENCES `vehicles` (`code`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `inspection_results` (`id`, `QRcode`, `line_code`, `vehicle_code`, `pt_pn`, `figure_id`, `mold_type_num`, `status`, `f_keep`, `m_keep`, `discarded`, `inspected_choku`, `modificated_choku`, `updated_choku`, `inspected_by`, `modificated_by`, `updated_by`, `palet_num`, `palet_max`, `ft_ids`, `f_comment`, `m_comment`, `processed_at`, `inspected_at`, `modificated_at`, `picked_at`, `exported_at`, `latest`, `control_num`, `inspected_iPad_id`, `modificated_iPad_id`, `updated_iPad_id`, `tpsResponce`, `tpsResponceStatus`, `created_at`, `updated_at`) VALUES
(6,	'PLK02JT   KJT1TP 01H6T10  Saito-test00300104 00180HOB2-E-9          201702101916552017020923591000620300000000214487012017020917180600',	'ATR18',	'030A',	'Saito-test',	35,	'10',	1,	0,	0,	0,	'Y',	'Y',	NULL,	'斉東 志ー',	'斉東 志ー',	NULL,	25,	30,	'a:15:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;i:6;i:7;i:7;i:8;i:8;i:9;i:9;i:10;i:10;i:11;i:11;i:12;i:12;i:13;i:13;i:14;i:14;i:15;}',	'255文字までのコメント',	NULL,	'2017-02-23 11:17:05',	'2017-02-23 11:09:16',	NULL,	NULL,	NULL,	1,	1,	'0001',	'0021',	NULL,	NULL,	NULL,	'2017-02-23 11:09:16',	'2017-02-23 11:12:49'),
(7,	'PLK02JT   KJT1TP 01H6T10  Saito-test00300104 00180HOB2-E-9          201702101916552017020923591100620300000000214487012017020917180600',	'ATR18',	'030A',	'Saito-test',	35,	'10',	1,	0,	0,	1,	'Y',	'Y',	NULL,	'山田 太郎',	'斉東 志二',	NULL,	25,	30,	'a:15:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;i:6;i:7;i:7;i:8;i:8;i:9;i:9;i:10;i:10;i:11;i:11;i:12;i:12;i:13;i:13;i:14;i:14;i:15;}',	'255文字までのコメント',	'手直しでつけた255文字までのコメント',	'2017-02-23 13:51:16',	'2017-02-23 11:14:34',	'2017-02-23 13:51:16',	NULL,	NULL,	1,	2,	'0001',	'0021',	NULL,	NULL,	NULL,	'2017-02-23 11:14:34',	'2017-02-23 13:51:16');

DROP TABLE IF EXISTS `jobs`;
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


DROP TABLE IF EXISTS `lines`;
CREATE TABLE `lines` (
  `code` varchar(16) NOT NULL,
  `code_inQR` varchar(16) NOT NULL,
  `sort` int(10) unsigned NOT NULL DEFAULT '1',
  `status` int(10) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`code`),
  UNIQUE KEY `lines_code_inqr_unique` (`code_inQR`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `lines` (`code`, `code_inQR`, `sort`, `status`, `created_at`, `updated_at`) VALUES
('10B',	'E7T',	4,	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42'),
('22',	'I2A',	3,	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42'),
('6A',	'E6T',	2,	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42'),
('ATR18',	'H6T',	1,	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42');

DROP TABLE IF EXISTS `memofailures`;
CREATE TABLE `memofailures` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int(10) unsigned NOT NULL,
  `ir_id` int(10) unsigned NOT NULL,
  `figure_id` int(10) unsigned DEFAULT NULL,
  `x1` int(10) unsigned NOT NULL DEFAULT '0',
  `y1` int(10) unsigned NOT NULL DEFAULT '0',
  `x2` int(10) unsigned NOT NULL DEFAULT '0',
  `y2` int(10) unsigned NOT NULL DEFAULT '0',
  `palet_first` int(10) unsigned NOT NULL DEFAULT '0',
  `palet_last` int(10) unsigned DEFAULT NULL,
  `modificated_at` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `memofailures_type_id_foreign` (`type_id`),
  KEY `memofailures_ir_id_foreign` (`ir_id`),
  KEY `memofailures_figure_id_foreign` (`figure_id`),
  CONSTRAINT `memofailures_figure_id_foreign` FOREIGN KEY (`figure_id`) REFERENCES `figures` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `memofailures_ir_id_foreign` FOREIGN KEY (`ir_id`) REFERENCES `inspection_results` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `memofailures_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `failure_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `memos`;
CREATE TABLE `memos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `line_code` varchar(16) NOT NULL,
  `vehicle_code` varchar(16) NOT NULL,
  `pt_pn` varchar(10) NOT NULL,
  `figure_id` int(10) unsigned DEFAULT NULL,
  `keep` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `comment` varchar(255) DEFAULT NULL,
  `ft_ids` varchar(512) DEFAULT NULL,
  `created_choku` varchar(8) NOT NULL,
  `updated_choku` varchar(8) DEFAULT NULL,
  `created_by` varchar(8) NOT NULL,
  `updated_by` varchar(8) DEFAULT NULL,
  `created_iPad_id` varchar(255) NOT NULL,
  `updated_iPad_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `memos_line_code_foreign` (`line_code`),
  KEY `memos_vehicle_code_foreign` (`vehicle_code`),
  KEY `memos_pt_pn_foreign` (`pt_pn`),
  KEY `memos_figure_id_foreign` (`figure_id`),
  CONSTRAINT `memos_figure_id_foreign` FOREIGN KEY (`figure_id`) REFERENCES `figures` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `memos_line_code_foreign` FOREIGN KEY (`line_code`) REFERENCES `lines` (`code`) ON UPDATE CASCADE,
  CONSTRAINT `memos_pt_pn_foreign` FOREIGN KEY (`pt_pn`) REFERENCES `part_types` (`pn`) ON UPDATE CASCADE,
  CONSTRAINT `memos_vehicle_code_foreign` FOREIGN KEY (`vehicle_code`) REFERENCES `vehicles` (`code`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `memos` (`id`, `line_code`, `vehicle_code`, `pt_pn`, `figure_id`, `keep`, `comment`, `ft_ids`, `created_choku`, `updated_choku`, `created_by`, `updated_by`, `created_iPad_id`, `updated_iPad_id`, `created_at`, `updated_at`) VALUES
(10,	'ATR18',	'963A',	'5381212B70',	2,	1,	'255文字までのコメント',	'a:15:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;i:6;i:7;i:7;i:8;i:8;i:9;i:9;i:10;i:10;i:11;i:11;i:12;i:12;i:13;i:13;i:14;i:14;i:15;}',	'Y',	NULL,	'山田 太郎',	NULL,	'0001',	'',	'2017-02-23 10:56:30',	'2017-02-23 10:56:30'),
(11,	'ATR18',	'963A',	'6311142231',	16,	1,	'255文字までのコメント',	'a:15:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;i:6;i:7;i:7;i:8;i:8;i:9;i:9;i:10;i:10;i:11;i:11;i:12;i:12;i:13;i:13;i:14;i:14;i:15;}',	'Y',	NULL,	'山田 太郎',	NULL,	'0001',	'',	'2017-02-23 10:57:35',	'2017-02-23 10:57:35');

DROP TABLE IF EXISTS `memo_failures`;
CREATE TABLE `memo_failures` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int(10) unsigned NOT NULL,
  `memo_id` int(10) unsigned NOT NULL,
  `figure_id` int(10) unsigned DEFAULT NULL,
  `x1` int(10) unsigned NOT NULL DEFAULT '0',
  `y1` int(10) unsigned NOT NULL DEFAULT '0',
  `x2` int(10) unsigned NOT NULL DEFAULT '0',
  `y2` int(10) unsigned NOT NULL DEFAULT '0',
  `palet_first` int(10) unsigned NOT NULL DEFAULT '0',
  `palet_last` int(10) unsigned DEFAULT NULL,
  `modificated_at` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `memo_failures_type_id_foreign` (`type_id`),
  KEY `memo_failures_memo_id_foreign` (`memo_id`),
  KEY `memo_failures_figure_id_foreign` (`figure_id`),
  CONSTRAINT `memo_failures_figure_id_foreign` FOREIGN KEY (`figure_id`) REFERENCES `figures` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `memo_failures_memo_id_foreign` FOREIGN KEY (`memo_id`) REFERENCES `memos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `memo_failures_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `failure_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `memo_failures` (`id`, `type_id`, `memo_id`, `figure_id`, `x1`, `y1`, `x2`, `y2`, `palet_first`, `palet_last`, `modificated_at`, `created_at`, `updated_at`) VALUES
(7,	1,	10,	2,	101,	101,	201,	201,	1,	10,	'0000-00-00',	'2017-02-23 10:56:30',	'2017-02-23 10:56:30'),
(8,	2,	10,	2,	102,	102,	202,	202,	1,	10,	'0000-00-00',	'2017-02-23 10:56:30',	'2017-02-23 10:56:30'),
(9,	3,	10,	2,	103,	103,	203,	203,	1,	10,	'0000-00-00',	'2017-02-23 10:56:30',	'2017-02-23 10:56:30'),
(10,	1,	11,	16,	101,	101,	201,	201,	1,	10,	'0000-00-00',	'2017-02-23 10:57:35',	'2017-02-23 10:57:35'),
(11,	2,	11,	16,	102,	102,	202,	202,	1,	10,	'0000-00-00',	'2017-02-23 10:57:35',	'2017-02-23 10:57:35'),
(12,	3,	11,	16,	103,	103,	203,	203,	1,	10,	'0000-00-00',	'2017-02-23 10:57:35',	'2017-02-23 10:57:35');

DROP TABLE IF EXISTS `migrations`;
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

DROP TABLE IF EXISTS `part_types`;
CREATE TABLE `part_types` (
  `pn` varchar(10) NOT NULL,
  `name` varchar(32) DEFAULT NULL,
  `capacity` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `en` varchar(32) DEFAULT NULL,
  `sort` int(10) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`pn`),
  UNIQUE KEY `part_types_name_unique` (`name`),
  UNIQUE KEY `part_types_en_unique` (`en`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `part_types` (`pn`, `name`, `capacity`, `en`, `sort`, `created_at`, `updated_at`) VALUES
('5381112B70',	'5381112B70',	0,	'5381112B70',	96,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('5381121140',	'5381121140',	0,	'5381121140',	125,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('5381148210',	'5381148210',	0,	'5381148210',	112,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('5381212B70',	'5381212B70',	13,	'5381212B70',	97,	'2017-02-23 07:19:54',	'2017-02-23 08:33:51'),
('5381221160',	'5381221160',	0,	'5381221160',	126,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('5381248190',	'5381248190',	0,	'5381248190',	113,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('5821148080',	'5821148080',	0,	'5821148080',	129,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('5821178010',	'5821178010',	0,	'5821178010',	124,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('6111112590',	'6111112590',	0,	'6111112590',	102,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('6111121150',	'6111121150',	0,	'6111121150',	127,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('6111148070',	'6111148070',	0,	'6111148070',	114,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('6111212530',	'6111212530',	0,	'6111212530',	103,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('6111221130',	'6111221130',	0,	'6111221130',	128,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('6111248060',	'6111248060',	14,	'6111248060',	115,	'2017-02-23 07:19:54',	'2017-02-23 08:03:53'),
('6311112A00',	'6311112A00',	0,	'6311112A00',	104,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('6311142231',	'6311142231',	0,	'6311142231',	111,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('6311142241',	'6311142241',	0,	'6311142241',	110,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('6311142904',	'6311142904',	0,	'6311142904',	101,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('6311142905',	'6311142905',	0,	'6311142905',	107,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('6311148190',	'6311148190',	0,	'6311148190',	116,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('6311148220',	'6311148220',	0,	'6311148220',	117,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('6711112730',	'6711112730',	0,	'6711112730',	105,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('6711148070',	'6711148070',	0,	'6711148070',	118,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('6711212720',	'6711212720',	0,	'6711212720',	106,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('6711248070',	'6711248070',	0,	'6711248070',	119,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('6711348070',	'6711348070',	0,	'6711348070',	122,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('6711448070',	'6711448070',	0,	'6711448070',	123,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('6711812210',	'6711812210',	0,	'6711812210',	98,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('6711812240',	'6711812240',	0,	'6711812240',	99,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('6711912060',	'6711912060',	0,	'6711912060',	100,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('6714148060',	'6714148060',	0,	'6714148060',	120,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('6714248060',	'6714248060',	0,	'6714248060',	121,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('6714347040',	'6714347040',	0,	'6714347040',	108,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('6714447040',	'6714447040',	0,	'6714447040',	109,	'2017-02-23 07:19:54',	'2017-02-23 07:19:54'),
('Saito-test',	'test',	41,	'41',	1,	'2017-02-23 11:08:00',	'2017-02-23 11:08:00');

DROP TABLE IF EXISTS `part_type_pair`;
CREATE TABLE `part_type_pair` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `left_pn` varchar(10) NOT NULL,
  `right_pn` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `part_type_pair_left_pn_foreign` (`left_pn`),
  KEY `part_type_pair_right_pn_foreign` (`right_pn`),
  CONSTRAINT `part_type_pair_left_pn_foreign` FOREIGN KEY (`left_pn`) REFERENCES `part_types` (`pn`) ON UPDATE CASCADE,
  CONSTRAINT `part_type_pair_right_pn_foreign` FOREIGN KEY (`right_pn`) REFERENCES `part_types` (`pn`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `part_type_pair` (`id`, `left_pn`, `right_pn`) VALUES
(1,	'5381212B70',	'5381112B70'),
(2,	'6711212720',	'6711112730'),
(3,	'6714447040',	'6714347040'),
(4,	'5381248190',	'5381148210'),
(5,	'6711248070',	'6711148070'),
(6,	'6714248060',	'6714148060'),
(7,	'6711448070',	'6711348070'),
(8,	'5381221160',	'5381121140');

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `users`;
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


DROP TABLE IF EXISTS `vehicles`;
CREATE TABLE `vehicles` (
  `code` varchar(16) NOT NULL,
  `sort` int(10) unsigned NOT NULL DEFAULT '1',
  `status` int(10) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `vehicles` (`code`, `sort`, `status`, `created_at`, `updated_at`) VALUES
('030A',	4,	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42'),
('132A',	7,	1,	'2017-02-23 07:05:29',	'2017-02-23 07:05:29'),
('410A',	3,	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42'),
('520A',	2,	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42'),
('530A',	9,	1,	'2017-02-23 07:06:26',	'2017-02-23 07:06:26'),
('610A',	8,	1,	'2017-02-23 07:06:05',	'2017-02-23 07:06:05'),
('660L',	6,	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42'),
('745L',	5,	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42'),
('963A',	1,	1,	'2017-02-22 09:44:42',	'2017-02-22 09:44:42');

DROP TABLE IF EXISTS `workers`;
CREATE TABLE `workers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(16) NOT NULL,
  `yomi` varchar(16) NOT NULL,
  `sort` int(10) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `choku_code` varchar(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `workers_choku_code_foreign` (`choku_code`),
  CONSTRAINT `workers_choku_code_foreign` FOREIGN KEY (`choku_code`) REFERENCES `chokus` (`code`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `workers` (`id`, `name`, `yomi`, `sort`, `status`, `choku_code`, `created_at`, `updated_at`) VALUES
(1,	'神田　大輔',	'コウダ ダイスケ',	1,	1,	'W',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(2,	'山口　博之',	'ヤマグチ ヒロユキ',	2,	1,	'W',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(3,	'吉田　尚史',	'ヨシダ ナオフミ',	3,	1,	'W',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(4,	'甲斐　幹矢',	'カイ ミキヤ',	4,	1,	'W',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(5,	'高橋　幸裕',	'タカハシ ヒロユキ',	5,	1,	'W',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(6,	'齋藤　正治',	'サイトウ ショウジ',	6,	1,	'W',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(7,	'牧野　繁則',	'マキノ シゲノリ',	7,	1,	'W',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(8,	'嘉ノ海　学',	'カノミ マナブ',	8,	1,	'W',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(9,	'廣尾　寿人',	'ヒロオ ヒサト',	9,	1,	'W',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(10,	'貞包　和紀',	'サダガネ カズノリ',	10,	1,	'W',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(11,	'天野　明人',	'',	11,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(12,	'岡本　一路',	'',	12,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(13,	'北川　渉',	'',	13,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(14,	'山田　直樹',	'',	14,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(15,	'平井　雅人',	'',	15,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(16,	'福永　篤嗣',	'',	16,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(17,	'前野　和広',	'',	17,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(18,	'谷　直喜',	'',	18,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(19,	'荒木　良太',	'',	19,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(20,	'椎葉　健太',	'',	20,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(21,	'内野　春喜',	'',	21,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(22,	'星野　輝男',	'',	22,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(23,	'松岡　民紘',	'',	23,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(24,	'堀端　哲郎',	'',	24,	1,	'W',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(25,	'前田　昇吾',	'',	25,	1,	'W',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(26,	'小関　一真',	'',	26,	1,	'W',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(27,	'藤春　佑太朗',	'',	27,	1,	'W',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(28,	'中川　信',	'',	28,	1,	'W',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(29,	'田村　竜一',	'',	29,	1,	'W',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(30,	'秀島 義史',	'',	30,	1,	'W',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(31,	'久保　穣士',	'',	31,	1,	'W',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(32,	'中村　徳隆',	'ナカムラ　ノリタカ',	32,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(33,	'花岡　進',	'ハナオカ　ススム',	33,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(34,	'西本　博周',	'ニシモト　ヒロノリ',	34,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(35,	'大平　巧真',	'オオヒラ　タクマ',	35,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(36,	'高松　慶紀',	'タカマツ　ヨシノリ',	36,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(37,	'冨士本　洋二',	'フジモト　ヨウジ',	37,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(38,	'仲山　邦親',	'ナカヤマ　クニチカ',	38,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(39,	'岸皮　一男',	'ガンピ　カズオ',	39,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(40,	'山岸　則武',	'ヤマギシ　ノリタケ',	40,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(41,	'吉富　大介',	'ヨシトミ ダイスケ',	41,	1,	'W',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(42,	'脇田　俊宏',	'ワキタ トシヒロ',	42,	1,	'W',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(43,	'奥本　隆広',	'',	43,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(44,	'田中　康史',	'',	44,	1,	'W',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(45,	'山下　未来',	'',	45,	1,	'W',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(46,	'有冨　秀和',	'',	46,	1,	'W',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(47,	'加藤　梓',	'カトウ　アズサ',	47,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(48,	'吉田　隆光',	'ヨシダ　タカミツ',	48,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(49,	'中川　琴音',	'ナカガワ　コトネ',	49,	1,	'Y',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10'),
(50,	'渡部　真',	'ワタナベ　マコト',	50,	1,	'W',	'2017-02-23 07:37:10',	'2017-02-23 07:37:10');

-- 2017-02-23 16:44:14