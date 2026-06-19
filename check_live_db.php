<?php
define('BASEPATH', 1);
define('ENVIRONMENT', 'production');
$db = [];
include 'application/config/production/database.php';
$conf = $db['default'];
try {
    $pdo = new PDO("mysql:host={$conf['hostname']};dbname={$conf['database']}", $conf['username'], $conf['password']);
    
    // Describe miqaat_invoice table
    $st = $pdo->query("DESCRIBE miqaat_invoice");
    $results = $st->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($results, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
