<?php
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['SERVER_ADDR'] = '127.0.0.1';
define('ENVIRONMENT', 'development');
chdir(__DIR__ . '/..');
require 'index.php';
$CI =& get_instance();
$CI->load->library('migration');
if ($CI->migration->current() === FALSE) {
    echo $CI->migration->error_string() . "\n";
} else {
    echo "Migration successful.\n";
}
