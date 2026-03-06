<?php
header('Content-Type: text/plain');
echo "PHP Version: " . phpversion() . "\n";
echo "SAPI: " . php_sapi_name() . "\n";
echo "PgSQL extension loaded: " . (extension_loaded('pgsql') ? 'YES' : 'NO') . "\n";
echo "PDO PgSQL extension loaded: " . (extension_loaded('pdo_pgsql') ? 'YES' : 'NO') . "\n";
echo "Loaded config file: " . php_ini_loaded_file() . "\n";
echo "Extension dir: " . ini_get('extension_dir') . "\n";
?>
