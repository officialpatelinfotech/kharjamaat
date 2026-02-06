<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function app_setting($key, $default = null)
{
  static $cache = [];
  $cacheKey = (string)$key;

  if (array_key_exists($cacheKey, $cache)) {
    return $cache[$cacheKey];
  }

  $CI = &get_instance();
  $CI->load->model('SettingsM');
  $cache[$cacheKey] = $CI->SettingsM->get($key, $default);
  return $cache[$cacheKey];
}

function jamaat_name()
{
  return app_setting('jamaat_name', 'Khar Jamaat');
}

function jamaat_place()
{
  $name = trim((string)jamaat_name());
  if ($name === '') return 'Khar';

  // If the configured name is like "Khar Jamaat", expose "Khar" for place-based labels.
  $suffix = ' jamaat';
  if (strlen($name) > strlen($suffix) && strtolower(substr($name, -strlen($suffix))) === $suffix) {
    $name = trim(substr($name, 0, -strlen($suffix)));
  }

  return $name !== '' ? $name : 'Khar';
}
