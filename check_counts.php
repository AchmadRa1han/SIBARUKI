<?php
// Simple script to count records in wilayah_kumuh table
require 'app/Config/Paths.php';
$paths = new \Config\Paths();

// Minimal CI4 bootstrap to get DB access
require $paths->systemDirectory . '/Common.php';
require 'app/Config/Autoload.php';
require 'app/Config/Services.php';

$db = \Config\Database::connect();
$query = $db->query("SELECT COUNT(*) as total FROM wilayah_kumuh");
$row = $query->getRow();
echo "Total records in database: " . $row->total . "
";

// Also count CSV lines
$csvFile = 'output.csv';
$lineCount = 0;
if (file_exists($csvFile)) {
    $handle = fopen($csvFile, "r");
    while(!feof($handle)){
      $line = fgets($handle);
      if ($line != "") $lineCount++;
    }
    fclose($handle);
}
echo "Total rows in output.csv (including header): " . $lineCount . "
";
