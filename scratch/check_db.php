<?php
define('ENVIRONMENT', 'development');
define('BASEPATH', '1');
$db_file = __DIR__ . '/../application/config/development/database.php';
require_once($db_file);

class SimpleDB {
    private $pdo;
    public function __construct($conf) {
        $dsn = "mysql:host={$conf['hostname']};dbname={$conf['database']};charset=utf8mb4";
        $this->pdo = new PDO($dsn, $conf['username'], $conf['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }
    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}

$db_conf = $db['default'];
$sdb = new SimpleDB($db_conf);

echo "--- DESC sabeel_takhmeen_grade ---\n";
print_r($sdb->query("DESC sabeel_takhmeen_grade"));

echo "\n--- DISTINCT YEARS AND TYPES IN sabeel_takhmeen_grade ---\n";
print_r($sdb->query("SELECT DISTINCT type, year FROM sabeel_takhmeen_grade ORDER BY year DESC, type ASC"));

echo "\n--- SAMPLE ROWS IN sabeel_takhmeen_grade ---\n";
print_r($sdb->query("SELECT * FROM sabeel_takhmeen_grade LIMIT 10"));
