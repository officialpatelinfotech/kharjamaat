<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=kharjamaat", "root", "");
    echo "--- MASTER RECORDS (raza_type_id = 0 or NULL) ---\n";
    $stmt = $pdo->query("SELECT * FROM laagat_rent WHERE (raza_type_id = 0 OR raza_type_id IS NULL) AND is_active = 1");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
