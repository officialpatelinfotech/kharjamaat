<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Payment configuration with environment fallbacks
$config['ccavenue_working_key'] = getenv('CCAVENUE_WORKING_KEY') ?: '';
$config['ccavenue_access_code'] = getenv('CCAVENUE_ACCESS_CODE') ?: '';

// Optional: name of log table to store gateway responses
$config['payments_log_table'] = 'payments_log';
