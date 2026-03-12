<?php
// Script to check specific NIK details in DB
require 'app/Config/Database.php';
// ... actually I can just use spark db:verify-ttl again but I'll make it more detailed.
echo "Checking DB for Salma...\n";
exec('php spark db:verify-ttl 7307087112710055', $output);
echo implode("\n", $output);
