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
$config["validate"] = true;
$config["wordwrap"] = true;
$config["smtp_encryption"] = "TLS";