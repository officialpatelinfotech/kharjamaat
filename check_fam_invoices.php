<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=kharjamaat", "root", "");
    $familyItsIds = ['30413068', '30413088', '30413075'];
    foreach ($familyItsIds as $fid) {
        $stmt = $pdo->prepare("SELECT i.*, u.Full_Name FROM laagat_rent_invoices i JOIN user u ON u.ITS_ID = i.user_id WHERE i.user_id = ?");
        $stmt->execute([$fid]);
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($res)) {
            echo "Invoices for $fid (" . $res[0]['Full_Name'] . "):\n";
            print_r($res);
        } else {
            echo "No invoices for $fid.\n";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
