<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=kharjamaat", "root", "");
    echo "--- RECORDS WITH AMOUNT 400 ---\n";
    $stmt = $pdo->query("SELECT * FROM laagat_rent WHERE amount = 400");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
