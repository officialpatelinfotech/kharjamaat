<?php
define('BASEPATH', '1');
define('ENVIRONMENT', 'development');
$db = [];
if (file_exists(__DIR__ . '/../application/config/development/database.php')) {
    require_once(__DIR__ . '/../application/config/development/database.php');
} elseif (file_exists(__DIR__ . '/../application/config/production/database.php')) {
    require_once(__DIR__ . '/../application/config/production/database.php');
} else {
    echo "Database config file not found.\n";
    exit;
}

if (isset($db['default'])) {
    $conf = $db['default'];
    try {
        $pdo = new PDO("mysql:host={$conf['hostname']};dbname={$conf['database']}", $conf['username'], $conf['password']);
        $stmt = $pdo->query("SELECT id, user_id, miqaat_id, miqaat_type, year, date, amount, description, raza_id FROM miqaat_invoice WHERE user_id = 20321805 OR description LIKE '%Kagalwala%' ORDER BY id DESC LIMIT 20");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($results, JSON_PRETTY_PRINT) . "\n";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
