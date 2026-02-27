<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=kharjamaat", "root", "");
    
    // Get HOF of Karim
    $stmt = $pdo->prepare("SELECT HOF_ID FROM user WHERE ITS_ID = '20321841'");
    $stmt->execute();
    $hofId = $stmt->fetchColumn();
    echo "HOF ID for Karim: $hofId\n";

    // All members with this HOF
    $stmt = $pdo->prepare("SELECT ITS_ID, Full_Name FROM user WHERE HOF_ID = ? OR ITS_ID = ?");
    $stmt->execute([$hofId, $hofId]);
    $family = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Family Members in user table:\n";
    $itsIds = [];
    foreach ($family as $f) {
        echo "- " . $f['Full_Name'] . " (" . $f['ITS_ID'] . ")\n";
        $itsIds[] = $f['ITS_ID'];
    }

    // Check IF there are ANY invoices in the table for ANY of these IDs
    $placeholders = implode(',', array_fill(0, count($itsIds), '?'));
    $sql = "SELECT i.*, u.Full_Name FROM laagat_rent_invoices i JOIN user u ON u.ITS_ID = i.user_id WHERE i.user_id IN ($placeholders)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($itsIds);
    $allInFamilyInvoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "\nInvoices found for family members in DB:\n";
    foreach ($allInFamilyInvoices as $inv) {
        echo "[ID:" . $inv['id'] . "] for " . $inv['Full_Name'] . " (" . $inv['user_id'] . ") - Amt: " . $inv['amount'] . "\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
