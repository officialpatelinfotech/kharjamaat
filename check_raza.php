<?php
define('BASEPATH', '1');
require_once('application/config/database.php');
$dsn = $db['default']['dsn'];
if (isset($db['default'])) {
    $conf = $db['default'];
    try {
        $pdo = new PDO("mysql:host={$conf['hostname']};dbname={$conf['database']}", $conf['username'], $conf['password']);
        $stmt = $pdo->query("SELECT id, name, umoor FROM raza_type WHERE name LIKE '%gfs%'");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($results, JSON_PRETTY_PRINT);
    } catch (PDOException $e) {
        echo "Error: " + $e->getMessage();
    }
}
