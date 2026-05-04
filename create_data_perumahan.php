<?php
require 'vendor/autoload.php';

$appConfig = new \Config\App();
$db = \Config\Database::connect();

$db->query("CREATE TABLE IF NOT EXISTS `data_perumahan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `desa_id` varchar(50) DEFAULT NULL,
  `jumlah_rumah` int(11) DEFAULT 0,
  `jumlah_rlh` int(11) DEFAULT 0,
  `jumlah_backlog` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

echo "Table created successfully.\n";
