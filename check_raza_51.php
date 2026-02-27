<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=kharjamaat", "root", "");
    $stmt = $pdo->prepare("SELECT * FROM raza WHERE raza_id = '1447-51'");
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($res);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
