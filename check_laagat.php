<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=kharjamaat", "root", "");
    echo "--- LAAGAT RECORDS ---\n";
    $stmt = $pdo->query("SELECT * FROM laagat_rent WHERE charge_type = 'laagat' AND is_active = 1");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
