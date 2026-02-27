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

/**
 * Normalize a phone number for WhatsApp API usage (digits only).
 * - Extracts digits
 * - Takes last 10 digits and prefixes India country code (91)
 *
 * Returns '' if empty/invalid.
 */
function whatsapp_normalize_india_phone($phone)
{
  $digits = preg_replace('/\D+/', '', (string)$phone);
  $digits = $digits !== null ? $digits : '';
  if ($digits === '') return '';

  if (strlen($digits) >= 10) {
    $digits = substr($digits, -10);
  }
  if (strlen($digits) !== 10) return '';

  return '91' . $digits;
}

/**
 * Fetch Amil Saheb WhatsApp number from the database.
 * Priority:
 * 1) app_settings key `amil_whatsapp` (if configured)
 * 2) user mapped to role `Amilsaheb` (via roles tables)
 * 3) notifications.php config `amil_whatsapp` (fallback)
 */
function amilsaheb_whatsapp_number()
{
  // DB override (still DB-backed)
  $override = (string)app_setting('amil_whatsapp', '');
  $overrideNorm = whatsapp_normalize_india_phone($override);
  if ($overrideNorm !== '') return $overrideNorm;

  $CI = &get_instance();
  $CI->load->model('AdminM');

  $rows = $CI->AdminM->get_user_by_role('Amilsaheb');
  if (is_array($rows)) {
    foreach ($rows as $row) {
      $raw = (string)($row['Registered_Family_Mobile'] ?? $row['Mobile'] ?? $row['WhatsApp_No'] ?? '');
      $norm = whatsapp_normalize_india_phone($raw);
      if ($norm !== '') return $norm;
    }
  }

  $CI->config->load('notifications', true);
  return whatsapp_normalize_india_phone((string)$CI->config->item('amil_whatsapp', 'notifications'));
}

/**
 * Admin WhatsApp recipients (normalized), always including Amil Saheb.
 */
function admin_whatsapp_recipients()
{
  $CI = &get_instance();
  $CI->config->load('notifications', true);
  $waAdmins = $CI->config->item('admin_whatsapp_recipients', 'notifications');
  $waAdmins = is_array($waAdmins) ? $waAdmins : [];

  $amil = amilsaheb_whatsapp_number();
  if ($amil !== '') $waAdmins[] = $amil;

  $out = [];
  foreach ($waAdmins as $m) {
    $norm = whatsapp_normalize_india_phone($m);
    if ($norm !== '') $out[] = $norm;
  }
  return array_values(array_unique($out));
}
