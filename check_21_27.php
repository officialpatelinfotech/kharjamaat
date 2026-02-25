<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=kharjamaat", "root", "");
    echo "--- RAZA TYPES 21 & 27 ---\n";
    $stmt = $pdo->query("SELECT id, name, umoor FROM raza_type WHERE id IN (21, 27)");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
