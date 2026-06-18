<?php
define('ENVIRONMENT', 'development');
define('BASEPATH', '1');
$db_file = __DIR__ . '/../application/config/development/database.php';
require_once($db_file);

$dsn = "mysql:host=" . $db['default']['hostname'] . ";dbname=" . $db['default']['database'] . ";charset=" . $db['default']['char_set'];
$pdo = new PDO($dsn, $db['default']['username'], $db['default']['password']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Query all miqaat_invoice entries that look like Fala ni Niyaz
$stmt = $pdo->query("SELECT id, year, miqaat_type, description, amount, date FROM miqaat_invoice WHERE description LIKE '%Niyaz Fund%'");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Total Niyaz Fund invoices: " . count($rows) . "\n";
$groups = [];
foreach ($rows as $r) {
    $key = $r['year'] . ' | ' . $r['miqaat_type'] . ' | ' . $r['description'] . ' | ' . $r['date'] . ' | ' . $r['amount'];
    if (!isset($groups[$key])) {
        $groups[$key] = 0;
    }
    $groups[$key]++;
}

foreach ($groups as $key => $count) {
    echo "Group: $key => Count: $count\n";
}
