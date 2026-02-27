<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=kharjamaat", "root", "");
    $stmt = $pdo->prepare("SELECT r.id, r.razaType, rt.name FROM raza r JOIN raza_type rt ON rt.id = r.razaType WHERE r.user_id = '20321841' ORDER BY r.id DESC LIMIT 10");
    $stmt->execute();
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
