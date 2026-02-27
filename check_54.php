<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=kharjamaat", "root", "");
    echo "--- RAZA TYPE ID 54 ---\n";
    $stmt = $pdo->query("SELECT * FROM raza_type WHERE id = 54");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
