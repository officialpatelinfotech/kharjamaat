<?php
define('BASEPATH', __DIR__ . '/system/');
define('APPPATH', __DIR__ . '/application/');
define('ENVIRONMENT', 'development');

require_once BASEPATH . 'core/Common.php';

// A minimal CI bootstrap to run migrations is tricky. 
// It's easier to create a controller or just modify an existing one to hit it via cli.
