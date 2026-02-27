<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=kharjamaat", "root", "");
    $stmt = $pdo->prepare("SHOW COLUMNS FROM raza");
    $stmt->execute();
    $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cols as $idx => $col) {
        echo "[$idx] " . $col['Field'] . " (" . $col['Type'] . ")\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
