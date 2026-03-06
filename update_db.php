<?php
// Load CodeIgniter Bootstrap
require 'vendor/autoload.php';
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
$app = require 'app/Config/Paths.php';
require 'system/Bootstrap.php';

$db = \Config\Database::connect();
$db->table('m_bpjs_config')->where('id', 1)->update(['env' => 'Trial']);
echo "Updated ID 1 to Trial\n";
