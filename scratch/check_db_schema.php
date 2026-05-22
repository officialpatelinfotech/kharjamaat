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

echo "--- DESC laagat_rent ---\n";
print_r($sdb->query("DESC laagat_rent"));

echo "\n--- DESC raza ---\n";
print_r($sdb->query("DESC raza"));

echo "\n--- DESC laagat_rent_invoices ---\n";
print_r($sdb->query("DESC laagat_rent_invoices"));
