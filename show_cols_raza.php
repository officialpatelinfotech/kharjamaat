<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=kharjamaat", "root", "");
    $stmt = $pdo->prepare("SHOW COLUMNS FROM raza");
    $stmt->execute();
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
