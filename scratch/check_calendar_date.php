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
        // Find first day of 1448
        $stmt = $pdo->query("SELECT * FROM hijri_calendar WHERE hijri_date LIKE '%-1448' ORDER BY greg_date ASC LIMIT 5");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "First days of 1448:\n" . json_encode($results, JSON_PRETTY_PRINT) . "\n";
        
        // Find last days of 1447
        $stmt2 = $pdo->query("SELECT * FROM hijri_calendar WHERE hijri_date LIKE '%-1447' ORDER BY greg_date DESC LIMIT 5");
        $results2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        echo "Last days of 1447:\n" . json_encode($results2, JSON_PRETTY_PRINT) . "\n";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
