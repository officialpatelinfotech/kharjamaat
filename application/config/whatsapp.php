<?php
defined('BASEPATH') or exit('No direct script access allowed');

// ExprezBot WhatsApp notification API
// Configure secrets via .env (loaded in index.php).
//
// Required:
//   EXPREZBOT_WHATSAPP_TOKEN=...
// Optional:
//   EXPREZBOT_WHATSAPP_API_URL=https://bot.exprezbot.com/api/send-notification
//   EXPREZBOT_WHATSAPP_DEFAULT_LANGUAGE=en
//   EXPREZBOT_WHATSAPP_TEXT_TEMPLATE_NAME= (template to use when a plain text body is queued)
//   EXPREZBOT_WHATSAPP_TEXT_TEMPLATE_LANGUAGE=en

$config['api_url'] = getenv('EXPREZBOT_WHATSAPP_API_URL') ?: ($_ENV['EXPREZBOT_WHATSAPP_API_URL'] ?? 'https://bot.exprezbot.com/api/send-notification');
$config['token'] = getenv('EXPREZBOT_WHATSAPP_TOKEN') ?: ($_ENV['EXPREZBOT_WHATSAPP_TOKEN'] ?? '');

$config['default_language'] = getenv('EXPREZBOT_WHATSAPP_DEFAULT_LANGUAGE') ?: ($_ENV['EXPREZBOT_WHATSAPP_DEFAULT_LANGUAGE'] ?? 'en');

// If you queue whatsapp notifications with a simple string body (legacy),
// this template (if set) will be used with the body as the first variable.
$config['text_template_name'] = getenv('EXPREZBOT_WHATSAPP_TEXT_TEMPLATE_NAME') ?: ($_ENV['EXPREZBOT_WHATSAPP_TEXT_TEMPLATE_NAME'] ?? '');
$config['text_template_language'] = getenv('EXPREZBOT_WHATSAPP_TEXT_TEMPLATE_LANGUAGE') ?: ($_ENV['EXPREZBOT_WHATSAPP_TEXT_TEMPLATE_LANGUAGE'] ?? $config['default_language']);

// Template variable mapping (order matters for ExprezBot API `body` array)
// You can adjust the order/count here to match the template placeholders.
// Supported keys in vars map: name, its, raza_id, razaname, todayDate, link
$config['templates'] = [
	'raza_application_submitted_v2' => [
		'language' => 'en',
		'vars' => [
			'name',
			'raza_details',
			'mobile_no',
			'raza_id',
			'date_submitted',
		],
	],
	// Admin alert when a member submits a Raza.
	// Template placeholders are expected to match the order below.
	'raza_application_submitted_admin' => [
		'language' => 'en',
		'vars' => [
			'name',
			'raza_details',
			'mobile_no',
			'raza_id',
			'date_submitted',
		],
	],
	// Raza recommended notification to admins
	// *Member:* {{1}} *Raza ID:* {{2}} *Raza Details:* {{3}}
	'raza_recommended_admin' => [
		'language' => 'en',
		'vars' => [
			'member',
			'raza_id',
			'raza_details',
		],
	],
	// Raza recommended notification to member
	// Hello *{{1}}* ... *Raza ID:* {{2}} *Raza Details:* {{3}} ... {{4}} (Amil contact)
	'raza_recommended_member' => [
		'language' => 'en',
		'vars' => [
			'name',
			'raza_id',
			'raza_details',
			'amil_contact',
		],
	],
	// Raza approved notification to admins
	// *Member:* {{1}} *ITS:* {{2}} *Raza ID:* {{3}} *Raza Details:* {{4}}
	'raza_approved_admin' => [
		'language' => 'en',
		'vars' => [
			'member',
			'its',
			'raza_id',
			'raza_details',
		],
	],
	// Raza approved notification to member
	// Hello *{{1}}* ... *Raza ID:* {{2}} *Raza Details:* {{3}}
	'raza_approved_member' => [
		'language' => 'en',
		'vars' => [
			'name',
			'raza_id',
			'raza_details',
		],
	],
	// RSVP open notification to member (v2)
	// Hello *{{1}}*, ... *Miqaat:* {{2}} *Miqaat ID:* {{3}} *Date:* {{4}}
	'rsvp_open_member_v2' => [
		'language' => 'en',
		'vars' => [
			'name',
			'miqaat',
			'miqaat_id',
			'date',
		],
	],
	// Thaali signup reminder to member
	// Hello *{{1}}* ... (button URL is static, no extra placeholders)
	'thaali_signup_reminder_member' => [
		'language' => 'en',
		'vars' => [
			'name',
		],
	],
	// Thaali feedback reminder to member
	// Hello *{{1}}* ... (button URL is static, no extra placeholders)
	'thaali_feedback_reminder_member' => [
		'language' => 'en',
		'vars' => [
			'name',
		],
	],
	// Finance dues summary to member
	// Hello *{{1}}* ... *Finance Details:* {{2}} *Total Due:* {{3}}
	'finance_dues_summary_member' => [
		'language' => 'en',
		'vars' => [
			'name',
			'finance_details',
			'total_due',
		],
	],
	// Corpus fund reminder to member
	// Hello *{{1}}* ... *Total Corpus Fund Due:* {{2}}
	'corpus_fund_reminder_member' => [
		'language' => 'en',
		'vars' => [
			'name',
			'total_corpus_due',
		],
	],
	// Appointment confirmed to member
	// Hello *{{1}}* ... *Date:* {{2}} *Time:* {{3}} *Purpose:* {{4}} *Details:* {{5}}
	'appointment_confirmed_member' => [
		'language' => 'en',
		'vars' => [
			'name',
			'date',
			'time',
			'purpose',
			'details',
		],
	],
	// Appointment booked notification to admin
	// *Member:* {{1}} *Date:* {{2}} *Time:* {{3}} *Purpose:* {{4}} *Details:* {{5}}
	'appointment_booked_admin' => [
		'language' => 'en',
		'vars' => [
			'member',
			'date',
			'time',
			'purpose',
			'details',
		],
	],
	// Daily appointments summary to Amil
	// Below is the scheduled appointments summary for tomorrow: {{1}}
	'daily_appointments_summary_amil' => [
		'language' => 'en',
		'vars' => [
			'summary',
		],
	],
	// Event reminder (D-3/D-1/D0) to Amil (template shown as event_reminder_d3_member)
	// *Event Type:* {{1}} *Event Date:* {{2}} *Raza ID:* {{3}} *Miqaat ID:* {{4}} *Miqaat Name:* {{5}} *Requested By:* {{6}}
	'event_reminder_d3_member' => [
		'language' => 'en',
		'vars' => [
			'event_type',
			'event_date',
			'raza_id',
			'miqaat_id',
			'miqaat_name',
			'requested_by',
		],
	],
	// Miqaat assignment notification to member/leader (v2)
	// Hello {{1}}, ... *Miqaat:* {{2}} *Miqaat ID:* {{3}} *Type:* {{4}} *Date:* {{5}} *Assigned to:* {{6}}
	'miqaat_assigned_member_v2' => [
		'language' => 'en',
		'vars' => [
			'name',
			'miqaat',
			'miqaat_id',
			'type',
			'date',
			'assigned_to',
		],
	],
	// Miqaat assignment recorded notification to admins
	// {{1}} member/leader label, {{2}} miqaat, {{3}} miqaat id, {{4}} type, {{5}} date, {{6}} assignment
	// Miqaat assignment recorded notification to admins (v2)
	// *Member:* {{1}} *Miqaat:* {{2}} *Miqaat ID:* {{3}} *Type:* {{4}} *Date:* {{5}} *Assignment:* {{6}}
	'miqaat_assigned_admin_v2' => [
		'language' => 'en',
		'vars' => [
			'member',
			'miqaat',
			'miqaat_id',
			'type',
			'date',
			'assignment',
		],
	],
	// Miqaat activated notification to member/leader
	// Hello {{1}}, ... *Miqaat:* {{2}} *Miqaat ID:* {{3}} *Type:* {{4}} *Date:* {{5}} *Assignment:* {{6}}
	'miqaat_activated_member' => [
		'language' => 'en',
		'vars' => [
			'name',
			'miqaat',
			'miqaat_id',
			'type',
			'date',
			'assignment',
		],
	],
	// Miqaat activated notification to admins
	// *Miqaat:* {{1}} *Miqaat ID:* {{2}} *Type:* {{3}} *Date:* {{4}} *Assigned To:* {{5}}
	'miqaat_activated_admin' => [
		'language' => 'en',
		'vars' => [
			'miqaat',
			'miqaat_id',
			'type',
			'date',
			'assigned_to',
		],
	],
];

return $config;
