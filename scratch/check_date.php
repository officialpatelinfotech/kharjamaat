<?php
define('ENVIRONMENT', 'development');
define('BASEPATH', '1');
$db_file = __DIR__ . '/../application/config/development/database.php';
require_once($db_file);

$dsn = "mysql:host=" . $db['default']['hostname'] . ";dbname=" . $db['default']['database'] . ";charset=" . $db['default']['char_set'];
$pdo = new PDO($dsn, $db['default']['username'], $db['default']['password']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $pdo->prepare("SELECT * FROM hijri_calendar WHERE greg_date = '2026-06-18'");
$stmt->execute();
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
