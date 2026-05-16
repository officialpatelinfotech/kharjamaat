<?php
define('BASEPATH', dirname(__FILE__) . '/system/');
define('APPPATH', dirname(__FILE__) . '/application/');
define('ENVIRONMENT', 'development');
require_once BASEPATH . 'core/CodeIgniter.php'; // This is hard to fake but we can just use a controller or write a simple query.
