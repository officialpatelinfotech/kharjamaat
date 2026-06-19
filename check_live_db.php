<?php
define('BASEPATH', 1);
define('ENVIRONMENT', 'production');
$db = [];
include 'application/config/production/database.php';
$conf = $db['default'];
try {
    $pdo = new PDO("mysql:host={$conf['hostname']};dbname={$conf['database']}", $conf['username'], $conf['password']);
    
    // Check calendar entries for 2026-06-18 and 2026-06-19
    $st = $pdo->prepare("SELECT * FROM hijri_calendar WHERE greg_date IN ('2026-06-18', '2026-06-19')");
    $st->execute();
    $cal = $st->fetchAll(PDO::FETCH_ASSOC);
    
    // Check recent invoices 2106, 2107, 2108
    $st2 = $pdo->prepare("SELECT * FROM miqaat_invoice WHERE id IN (2106, 2107, 2108)");
    $st2->execute();
    $invoices = $st2->fetchAll(PDO::FETCH_ASSOC);
    
    // Check recent miqaats 326, 327
    $st3 = $pdo->prepare("SELECT * FROM miqaat WHERE id IN (326, 327)");
    $st3->execute();
    $miqaats = $st3->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode([
        'calendar' => $cal,
        'invoices' => $invoices,
        'miqaats' => $miqaats
    ], JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
