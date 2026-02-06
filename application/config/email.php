<?php
defined('BASEPATH') or exit('No direct script access allowed');

$env = function ($key, $default = null) {
	$val = getenv($key);
	if ($val === false && isset($_ENV[$key])) $val = $_ENV[$key];
	if ($val === false || $val === null) return $default;
	$val = is_string($val) ? trim($val) : $val;
	return ($val === '' ? $default : $val);
};

$config['protocol'] = $env('SMTP_PROTOCOL', 'smtp');

// Allow overriding SMTP settings via .env without code changes.
// Note: CodeIgniter's Email library automatically prefixes the host with
// "ssl://" when smtp_crypto=ssl is set. So store smtp_host WITHOUT ssl://.
$smtpHostRaw = (string)$env('SMTP_HOST', 'mail.kharjamaat.in');
$smtpPort = (int)$env('SMTP_PORT', 465);
$smtpUser = (string)$env('SMTP_USER', 'admin@kharjamaat.in');
$smtpPass = (string)$env('SMTP_PASS', 'admin@2024');

// Normalize host: strip ssl:// if someone provided it.
$smtpHost = $smtpHostRaw;
$smtpCrypto = (string)$env('SMTP_CRYPTO', '');
if (stripos($smtpHost, 'ssl://') === 0) {
	$smtpHost = substr($smtpHost, 6);
	if ($smtpCrypto === '') $smtpCrypto = 'ssl';
}

// If SMTP_CRYPTO isn't explicitly set, infer a safe default from the port.
// - 465: implicit SSL
// - 587: STARTTLS
if ($smtpCrypto === '') {
	if ((string)$smtpPort === '465') $smtpCrypto = 'ssl';
	else if ((string)$smtpPort === '587') $smtpCrypto = 'tls';
}

$config['smtp_host'] = $smtpHost;
$config['smtp_port'] = $smtpPort;
$config['smtp_user'] = $smtpUser;
$config['smtp_pass'] = $smtpPass;
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['crlf'] = "\r\n";
$config["mailpath"] = "/usr/sbin/sendmail";
$config["smtp_timeout"] = 10;
$config["smtp_keepalive"] = false;
$config["validate"] = true;
$config["wordwrap"] = true;
$config['smtp_crypto'] = $smtpCrypto;