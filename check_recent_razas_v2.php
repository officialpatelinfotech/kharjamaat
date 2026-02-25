<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=kharjamaat", "root", "");
    $stmt = $pdo->prepare("SELECT id, raza_id, user_id, razaType, timestamp FROM raza ORDER BY id DESC LIMIT 10");
    $stmt->execute();
    $razas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Recent Razas:\n";
    foreach ($razas as $r) {
        $stmt2 = $pdo->prepare("SELECT name FROM raza_type WHERE id = ?");
        $stmt2->execute([$r['razaType']]);
        $rt = $stmt2->fetch(PDO::FETCH_ASSOC);
        echo "[ID:" . $r['id'] . "] human_id: " . $r['raza_id'] . " for user: " . $r['user_id'] . " Type: " . ($rt['name'] ?? 'UNKNOWN') . " at " . $r['timestamp'] . "\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
