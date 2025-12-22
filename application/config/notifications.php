<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Contacts
$config['amil_whatsapp'] = '+918452840052';
$config['jamaat_whatsapp_group'] = null; // optional group id/number

// Scheduling defaults
$config['thaali_signup_daily_time'] = '09:00'; // recommended local time (not enforced by code)
$config['thaali_feedback_time'] = '22:00'; // daily feedback reminder time

// Monthly/weekly schedule settings
$config['sabeel_monthly_day'] = 1; // day of month to send pending sabeel reminders
$config['fmb_monthly_day'] = 1; // day of month to send FMB dues reminder
$config['corpus_weekly_weekday'] = 'MON'; // day of week for corpus fund reminders

// Message templates (simple, can be moved to DB templates later)
$config['tpl_thaali_signup'] = "Please complete your Thaali sign-up for today if you wish to receive a thaali. Visit the app to sign up.";
$config['tpl_thaali_feedback'] = "Please submit your Thaali feedback for today. Your feedback helps improve service.";
$config['tpl_pending_sabeel'] = "You have pending Sabeel contributions. Please settle outstanding amounts.";
$config['tpl_fmb_dues'] = "Reminder: Your FMB dues are pending. Please pay at your earliest convenience.";
$config['tpl_corpus_fund'] = "Reminder: Please contribute to the Corpus Fund. Your support is appreciated.";

return $config;
