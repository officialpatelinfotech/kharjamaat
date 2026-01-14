<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Contacts
$config['amil_whatsapp'] = '+918452840052';
$config['jamaat_whatsapp_group'] = null;

// Scheduling defaults
$config['thaali_signup_daily_time'] = '09:00';
$config['thaali_feedback_time'] = '22:00';

// Amil Saheb appointment digest
$config['amilsaheb_appointments_digest_time'] = '22:00';
$config['amilsaheb_appointments_digest_recipients'] = ['kharamilsaheb@gmail.com'];

// Monthly/weekly schedule settings
$config['sabeel_monthly_day'] = 1;
$config['fmb_monthly_day'] = 1;
$config['corpus_weekly_weekday'] = 'MON';

// Message templates (simple, can be moved to DB templates later)
$config['tpl_thaali_signup'] = "Kindly complete your Thaali sign-up for today if you wish to receive a thaali. Please visit accounts/fmbweeklysignup to submit your sign-up.";

$config['tpl_thaali_feedback'] = "Please submit your Thaali feedback for today. Your feedback helps improve service. Visit accounts/fmbfeedback to submit your feedback.";

$config['tpl_pending_sabeel'] = "You have pending Sabeel contributions. Please settle outstanding amounts.";

$config['tpl_fmb_dues'] = "Reminder: Your FMB dues are pending. Please pay at your earliest convenience.";

// Unified finances reminder (single consolidated message)
$config['tpl_finance_dues_subject'] = "Finance Dues Summary";
$config['tpl_finance_dues_intro'] = "This is a consolidated reminder of your current outstanding dues:";
$config['tpl_finance_dues_footer'] = "You may review details and make payments using the links below.";

$config['tpl_corpus_fund'] = "This is a gentle reminder to contribute to the Corpus Fund. Your support is sincerely appreciated.";

// Appointment digest templates
$config['tpl_appointments_digest_subject'] = "Scheduled Appointments Summary";

return $config;
