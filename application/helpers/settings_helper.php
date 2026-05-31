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
  $place = app_setting('jamaat_place', null);
  if ($place !== null && trim((string)$place) !== '') {
    return trim((string)$place);
  }

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

/**
 * Get dynamic list of admin email recipients.
 * Parses comma or newline separated email addresses configured in preferences.
 */
function admin_email_recipients()
{
  $raw = app_setting('admin_emails', null);
  if ($raw === null || trim((string)$raw) === '') {
    // Fallback default admin emails
    return [
      'amilsaheb@kharjamaat.in',
      '3042@carmelnmh.in',
      'kharjamaat@gmail.com',
      'kharamilsaheb@gmail.com',
      'kharjamaat786@gmail.com',
      'khozemtopiwalla@gmail.com',
      'ybookwala@gmail.com'
    ];
  }

  // Split by comma or newline
  $parts = preg_split('/[\n,\r]+/', (string)$raw);
  $emails = [];
  if (is_array($parts)) {
    foreach ($parts as $p) {
      $p = trim($p);
      if ($p !== '' && filter_var($p, FILTER_VALIDATE_EMAIL)) {
        $emails[] = $p;
      }
    }
  }

  return array_values(array_unique($emails));
}

if (!function_exists('should_send_notification')) {
  function should_send_notification($channel, $recipient, $subject = null, $template_name = null) {
    // 1. Identify recipient type: admin or member
    $recipient_type = 'member';
    if ($channel === 'email') {
      $recipient_email = strtolower(trim((string)$recipient));
      $admins = admin_email_recipients();
      $admin_emails = array_map('strtolower', $admins);
      if (in_array($recipient_email, $admin_emails, true)) {
        $recipient_type = 'admin';
      }
    } elseif ($channel === 'whatsapp') {
      $recipient_phone = whatsapp_normalize_india_phone($recipient);
      $admins = admin_whatsapp_recipients();
      if (in_array($recipient_phone, $admins, true)) {
        $recipient_type = 'admin';
      } elseif ($template_name !== null) {
        $tpl = strtolower((string)$template_name);
        if (strpos($tpl, '_admin') !== false || strpos($tpl, '_amil') !== false) {
          $recipient_type = 'admin';
        }
      }
    }

    // 2. Identify notification type
    $subject = (string)$subject;
    $template_name = (string)$template_name;
    
    $notif_type = null;
    $subject_lower = strtolower($subject);
    $template_lower = strtolower($template_name);

    // 13, 14, 15: Event Reminders (3 days, 1 day, event day)
    if (strpos($subject_lower, '3 days before') !== false || strpos($template_lower, 'd3') !== false || strpos($subject_lower, '3 days') !== false) {
      $notif_type = 'amil_3days_before';
    } elseif (strpos($subject_lower, '1 day before') !== false || strpos($subject_lower, '1 day') !== false) {
      $notif_type = 'amil_1day_before';
    } elseif (strpos($subject_lower, 'event day') !== false || strpos($subject_lower, 'eventday') !== false) {
      $notif_type = 'amil_event_day';
    }
    // 1. Miqaat Assignment
    elseif (strpos($subject_lower, 'assignment') !== false || strpos($subject_lower, 'assigned') !== false || strpos($subject_lower, 'appointed group leader') !== false || strpos($template_lower, 'assigned') !== false) {
      $notif_type = 'miqaat_assignment';
    }
    // 2. Miqaat Activation
    elseif (strpos($subject_lower, 'miqaat activated') !== false || strpos($template_lower, 'miqaat_activated') !== false || strpos($subject_lower, 'activated') !== false) {
      $notif_type = 'miqaat_activation';
    }
    // 6. RSVP on Raza Approval
    elseif (strpos($subject_lower, 'rsvp') !== false || strpos($template_lower, 'rsvp') !== false) {
      $notif_type = 'rsvp_on_raza_approval';
    }
    // 3. Raza Submission
    elseif (
      strpos($subject_lower, 'new raza') !== false || 
      strpos($subject_lower, 'raza submission') !== false || 
      strpos($subject_lower, 'raza request submitted') !== false || 
      strpos($subject_lower, 'has submitted') !== false ||
      (strpos($subject_lower, 'raza') !== false && strpos($subject_lower, 'submitted') !== false) ||
      strpos($template_lower, 'submitted') !== false
    ) {
      $notif_type = 'raza_submission';
    }
    // 4. Raza Recommendation
    elseif (strpos($subject_lower, 'raza recommended') !== false || strpos($subject_lower, 'raza recommendation') !== false || strpos($template_lower, 'recommended') !== false) {
      $notif_type = 'raza_recommendation';
    }
    // 5. Raza Approval
    elseif (
      strpos($subject_lower, 'raza approved') !== false || 
      strpos($subject_lower, 'your raza request') !== false || 
      strpos($subject_lower, 'raza status') !== false ||
      strpos($template_lower, 'approved') !== false
    ) {
      $notif_type = 'raza_approval';
    }
    // 7. FMB Thaali Signup everyday at 10am
    elseif (strpos($subject_lower, 'signup') !== false || strpos($template_lower, 'signup') !== false) {
      $notif_type = 'fmb_signup_10am';
    }
    // 8. FMB Thaali Feedback everyday at 10pm
    elseif (strpos($subject_lower, 'feedback') !== false || strpos($template_lower, 'feedback') !== false) {
      $notif_type = 'fmb_feedback_10pm';
    }
    // 9. Finance dues / Sabeel Takhmeen etc.
    elseif (strpos($subject_lower, 'dues') !== false || strpos($subject_lower, 'financial') !== false || strpos($template_lower, 'finance') !== false || strpos($subject_lower, 'sabeel') !== false || strpos($subject_lower, 'takhmeen') !== false) {
      $notif_type = 'monthly_due_reminder';
    }
    // 10. Corpus Funds due reminder every week
    elseif (strpos($subject_lower, 'corpus') !== false || strpos($template_lower, 'corpus') !== false) {
      $notif_type = 'corpus_funds_weekly_reminder';
    }
    // 11. Appointment Schedule
    elseif (
      strpos($subject_lower, 'appointment schedule') !== false || 
      strpos($subject_lower, 'appointment confirm') !== false || 
      strpos($subject_lower, 'appointment book') !== false || 
      strpos($template_lower, 'appointment_confirmed') !== false || 
      strpos($template_lower, 'appointment_booked') !== false
    ) {
      $notif_type = 'appointment_schedule';
    }
    // 12. Appointment Report every night
    elseif (strpos($subject_lower, 'appointment report') !== false || strpos($subject_lower, 'appointments summary') !== false || strpos($template_lower, 'daily_appointments') !== false) {
      $notif_type = 'appointment_report_nightly';
    }

    if ($notif_type === null) {
      return true;
    }

    $CI =& get_instance();
    $CI->load->model('SettingsM');
    $settings = json_decode((string)$CI->SettingsM->get('notification_settings', '{}'), true);
    if (!is_array($settings)) {
      $settings = [];
    }

    $settings_key = $recipient_type . '_' . $channel;
    
    if (!isset($settings[$notif_type])) {
      return true;
    }
    
    // Direct match check
    if (isset($settings[$notif_type][$settings_key])) {
      return (int)$settings[$notif_type][$settings_key] === 1;
    }
    
    // Fallback migration mapping (to handle older JSON schema)
    if ($settings_key === 'member_whatsapp' || $settings_key === 'member_email') {
      return isset($settings[$notif_type]['member']) && (int)$settings[$notif_type]['member'] === 1;
    }
    if ($settings_key === 'admin_whatsapp' || $settings_key === 'admin_email') {
      return isset($settings[$notif_type]['admin']) && (int)$settings[$notif_type]['admin'] === 1;
    }
    
    return false;
  }
}
