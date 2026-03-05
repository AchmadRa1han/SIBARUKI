<?php
$db = \Config\Database::connect();
$aset = $db->table('aset_tanah')->where('id', 1)->get()->getRowArray();
echo "Raw tgl_terbit: [" . $aset['tgl_terbit'] . "]
";
echo "Type: " . gettype($aset['tgl_terbit']) . "
";
echo "StrtoTime: " . strtotime($aset['tgl_terbit']) . "
";
echo "Formatted: " . ($aset['tgl_terbit'] ? date('d F Y', strtotime($aset['tgl_terbit'])) : '-') . "
";
