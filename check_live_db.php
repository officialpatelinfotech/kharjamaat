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
    $schema = $st->fetchAll(PDO::FETCH_ASSOC);
    
    // Check recent invoices 2106, 2107, 2108
    $st2 = $pdo->prepare("SELECT * FROM miqaat_invoice WHERE id IN (2106, 2107, 2108)");
    $st2->execute();
    $invoices = $st2->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode([
        'schema' => $schema,
        'invoices' => $invoices
    ], JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
