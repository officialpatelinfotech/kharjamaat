<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['protocol'] = 'smtp';
$config['smtp_host'] = 'ssl://mail.kharjamaat.in';
$config['smtp_port'] = '465';
$config['smtp_user'] = 'admin@kharjamaat.in';
$config['smtp_pass'] = 'admin@2024';
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config["mailpath"] = "/usr/sbin/sendmail";
$config["smtp_timeout"] = "7";
$config["smtp_keepalive"] = false;
$config["validate"] = true;
$config["wordwrap"] = true;
// For port 465 (implicit SSL), do not use STARTTLS.
// Using ssl:// in smtp_host is enough; keep smtp_crypto empty.
$config['smtp_crypto'] = '';