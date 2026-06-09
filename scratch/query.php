<?php
define('BASEPATH', '1');
define('ENVIRONMENT', 'development');
require_once('/Applications/MAMP/htdocs/kharjamaat/application/config/development/database.php');

if (isset($db['default'])) {
    $conf = $db['default'];
    try {
        $pdo = new PDO("mysql:host={$conf['hostname']};dbname={$conf['database']}", $conf['username'], $conf['password']);
        $sql = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : "SELECT 1";
        $stmt = $pdo->query($sql);
        if ($stmt) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($results, JSON_PRETTY_PRINT) . "\n";
        } else {
            echo "Query failed.\n";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
