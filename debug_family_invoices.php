<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=kharjamaat", "root", "");
    $itsId = '20321841';
    
    // Get user info
    $stmt = $pdo->prepare("SELECT * FROM user WHERE ITS_ID = ?");
    $stmt->execute([$itsId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Current User: " . $user['Full_Name'] . " (HOF: " . $user['HOF_ID'] . ")\n";

    $hofId = $user['HOF_ID'];
    
    // Get all family members
    $stmt = $pdo->prepare("SELECT ITS_ID, Full_Name FROM user WHERE HOF_ID = ? OR ITS_ID = ?");
    $stmt->execute([$hofId, $hofId]);
    $family = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Family Members:\n";
    $memberIds = [];
    foreach ($family as $f) {
        echo "- " . $f['Full_Name'] . " (" . $f['ITS_ID'] . ")\n";
        $memberIds[] = (string)$f['ITS_ID'];
    }
    $memberIds = array_unique($memberIds);

    // Get all invoices for these members from DB directly
    echo "\nInvoices in DB for family:\n";
    $placeholders = implode(',', array_fill(0, count($memberIds), '?'));
    $sql = "SELECT i.*, u.Full_Name, lr.title, lr.charge_type 
            FROM laagat_rent_invoices i 
            LEFT JOIN user u ON u.ITS_ID = i.user_id
            LEFT JOIN laagat_rent lr ON lr.id = i.laagat_rent_id
            WHERE i.user_id IN ($placeholders)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($memberIds);
    $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($invoices as $inv) {
        echo "[ID:" . $inv['id'] . "] " . $inv['title'] . " for " . $inv['Full_Name'] . " (" . $inv['user_id'] . ") - Amt: " . $inv['amount'] . " - Type: " . $inv['charge_type'] . "\n";
    }
    
    echo "\nTotal Invoices found: " . count($invoices) . "\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
