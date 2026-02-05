<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['protocol'] = getenv('SMTP_PROTOCOL') ?: ($_ENV['SMTP_PROTOCOL'] ?? 'smtp');

// Allow overriding SMTP settings via .env without code changes.
// Examples:
// - Implicit SSL: SMTP_HOST=ssl://mail.example.com SMTP_PORT=465 SMTP_CRYPTO=
// - STARTTLS:      SMTP_HOST=mail.example.com SMTP_PORT=587 SMTP_CRYPTO=tls
$config['smtp_host'] = getenv('SMTP_HOST') ?: ($_ENV['SMTP_HOST'] ?? 'mail.kharjamaat.in');
$config['smtp_port'] = getenv('SMTP_PORT') ?: ($_ENV['SMTP_PORT'] ?? '465');
$config['smtp_user'] = getenv('SMTP_USER') ?: ($_ENV['SMTP_USER'] ?? 'admin@kharjamaat.in');
$config['smtp_pass'] = getenv('SMTP_PASS') ?: ($_ENV['SMTP_PASS'] ?? 'admin@2024');
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['crlf'] = "\r\n";
$config["mailpath"] = "/usr/sbin/sendmail";
$config["smtp_timeout"] = "7";
$config["smtp_keepalive"] = false;
$config["validate"] = true;
$config["wordwrap"] = true;
// If SMTP_CRYPTO isn't explicitly set, infer a safe default from the port.
// - 465: implicit SSL
// - 587: STARTTLS
$smtpCrypto = getenv('SMTP_CRYPTO') ?: ($_ENV['SMTP_CRYPTO'] ?? '');
$smtpPort = (string)$config['smtp_port'];
if ($smtpCrypto === '') {
	if ($smtpPort === '465') $smtpCrypto = 'ssl';
	else if ($smtpPort === '587') $smtpCrypto = 'tls';
}
$config['smtp_crypto'] = $smtpCrypto;