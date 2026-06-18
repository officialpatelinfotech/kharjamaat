<?php
define('ENVIRONMENT', 'development');
define('BASEPATH', '1');
$db_file = __DIR__ . '/../application/config/development/database.php';
require_once($db_file);

$dsn = "mysql:host=" . $db['default']['hostname'] . ";dbname=" . $db['default']['database'] . ";charset=" . $db['default']['char_set'];
$pdo = new PDO($dsn, $db['default']['username'], $db['default']['password']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "--- miqaat_invoice ---\n";
$stmt = $pdo->query("DESCRIBE miqaat_invoice");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

echo "--- miqaat_niyaz_amounts ---\n";
$stmt = $pdo->query("DESCRIBE miqaat_niyaz_amounts");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
