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
        
        // Let's query recent miqaats and their corresponding invoices
        $sql = "SELECT m.id as miqaat_tbl_id, m.miqaat_id, m.name, m.date, m.type, i.id as invoice_id, i.year as invoice_year, i.description
                FROM miqaat m
                LEFT JOIN miqaat_invoice i ON m.id = i.miqaat_id
                ORDER BY m.id DESC LIMIT 15";
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($results, JSON_PRETTY_PRINT) . "\n";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
