<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['protocol'] = 'smtp';
$config['smtp_host'] = 'mail.kharjamaat.in';
$config['smtp_port'] = '587';
$config['smtp_user'] = 'admin@kharjamaat.in';
$config['smtp_pass'] = 'admin@2024';
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config["mailpath"] = "/usr/sbin/sendmail";
$config["smtp_timeout"] = "7";
$config["smtp_keepalive"] = true;
$config["validate"] = true;
$config["wordwrap"] = true;
// CodeIgniter Email library expects smtp_crypto = 'tls' or 'ssl'
$config['smtp_crypto'] = 'tls';
// Backward compatibility (unused by CI Email)
$config["smtp_encryption"] = "TLS";