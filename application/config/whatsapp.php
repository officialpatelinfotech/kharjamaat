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
];

return $config;