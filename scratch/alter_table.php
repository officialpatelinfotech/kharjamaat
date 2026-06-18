<?php
define('ENVIRONMENT', 'development');
define('BASEPATH', '1');
$db_file = __DIR__ . '/../application/config/development/database.php';
require_once($db_file);

$dsn = "mysql:host=" . $db['default']['hostname'] . ";dbname=" . $db['default']['database'] . ";charset=" . $db['default']['char_set'];
$pdo = new PDO($dsn, $db['default']['username'], $db['default']['password']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    echo "Altering table miqaat_invoice year column to varchar(15)...\n";
    $pdo->exec("ALTER TABLE miqaat_invoice MODIFY COLUMN year VARCHAR(15) NULL DEFAULT NULL");
    echo "Altered successfully!\n";

    echo "Updating Fala ni Niyaz invoice year fields based on description...\n";
    $stmt1 = $pdo->exec("UPDATE miqaat_invoice SET year = '1447-48' WHERE description LIKE '%1447-48%'");
    echo "Updated $stmt1 rows to 1447-48.\n";

    $stmt2 = $pdo->exec("UPDATE miqaat_invoice SET year = '1446-47' WHERE description LIKE '%1446-47%'");
    echo "Updated $stmt2 rows to 1446-47.\n";

    // Let's print the groupings again to verify
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
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
