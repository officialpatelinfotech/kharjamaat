<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=kharjamaat", "root", "");
    $stmt = $pdo->prepare("SELECT i.*, lr.title, lr.charge_type, lr.hijri_year, rt.name as raza_name 
            FROM laagat_rent_invoices i 
            LEFT JOIN laagat_rent lr ON lr.id = i.laagat_rent_id
            LEFT JOIN raza r ON r.id = i.raza_id
            LEFT JOIN raza_type rt ON rt.id = r.razaType
            WHERE i.user_id = '20321841'");
    $stmt->execute();
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
