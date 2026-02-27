<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=kharjamaat", "root", "");
    $stmt = $pdo->prepare("SELECT i.*, u.Full_Name, lr.title 
            FROM laagat_rent_invoices i 
            LEFT JOIN user u ON u.ITS_ID = i.user_id 
            LEFT JOIN laagat_rent lr ON lr.id = i.laagat_rent_id");
    $stmt->execute();
    $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "All Invoices in System:\n";
    foreach ($invoices as $inv) {
        echo "[ID:" . $inv['id'] . "] " . ($inv['title'] ?? 'NO TITLE') . " for " . ($inv['Full_Name'] ?? 'UNKNOWN') . " (" . $inv['user_id'] . ") - Amt: " . $inv['amount'] . " - Created: " . $inv['created_at'] . "\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
