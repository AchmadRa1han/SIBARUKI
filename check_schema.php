<?php
try {
    $db = \Config\Database::connect();
    $tables = ['rtlh_penerima', 'rumah_rtlh', 'rtlh_kondisi_rumah'];
    foreach($tables as $t) {
        echo "--- Table: $t ---
";
        $fields = $db->getFieldData($t);
        foreach($fields as $field) {
            echo "{$field->name} ({$field->type})
";
        }
        echo "
";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "
";
}
