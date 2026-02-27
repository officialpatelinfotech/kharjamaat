<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=kharjamaat", "root", "");
    $stmt = $pdo->prepare("DESCRIBE laagat_rent_invoices");
    $stmt->execute();
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
