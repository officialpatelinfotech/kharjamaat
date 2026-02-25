<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=kharjamaat", "root", "");
    echo "--- RAZA TYPES (GFS) ---\n";
    $stmt = $pdo->query("SELECT id, name, umoor FROM raza_type WHERE name LIKE '%gfs%'");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    
    echo "\n--- LAAGAT RENT RECORDS ---\n";
    $stmt = $pdo->query("SELECT id, title, amount, charge_type, hijri_year, is_active, raza_type_id FROM laagat_rent");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    
    $stmt = $pdo->query("SHOW TABLES LIKE 'laagat_rent_raza_type_map'");
    $tables = $stmt->fetchAll();
    if (!empty($tables)) {
        echo "\n--- LAAGAT RENT MAP ---\n";
        $stmt = $pdo->query("SELECT * FROM laagat_rent_raza_type_map");
        print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
