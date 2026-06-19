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
        $stmt = $pdo->prepare("SELECT ITS_ID, HOF_ID, Full_Name, HOF_FM_TYPE FROM user WHERE ITS_ID = ? OR HOF_ID = ?");
        $stmt->execute([20321809, 20321809]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($results, JSON_PRETTY_PRINT) . "\n";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
