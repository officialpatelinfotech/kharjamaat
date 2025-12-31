<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('dashboard_log')) {

  function dashboard_log($message, $context = [])
  {
    $CI =& get_instance();

    $log_dir = APPPATH . 'logs/dashboard/';
    if (!is_dir($log_dir)) {
      mkdir($log_dir, 0755, true);
    }

    $file = $log_dir . 'dashboard-' . date('Y-m-d') . '.log';

    $log = '[' . date('Y-m-d H:i:s') . '] ';
    $log .= $message;

    if (!empty($context)) {
      $log .= ' | ' . json_encode($context, JSON_UNESCAPED_UNICODE);
    }

    $log .= PHP_EOL;

    file_put_contents($file, $log, FILE_APPEND);
  }
}
