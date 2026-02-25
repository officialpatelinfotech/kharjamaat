<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=kharjamaat", "root", "");
    echo "--- ALL RAZA TYPES ---\n";
    $stmt = $pdo->query("SELECT id, name, umoor FROM raza_type");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
