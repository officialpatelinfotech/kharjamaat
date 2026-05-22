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

$types = $sdb->query("SELECT id, name, umoor, fields FROM raza_type WHERE active = 1");
foreach ($types as $t) {
    echo "ID: {$t['id']}, Name: {$t['name']}, Umoor: {$t['umoor']}\n";
    $fields = json_decode($t['fields'], true);
    if (isset($fields['fields'])) {
        foreach ($fields['fields'] as $f) {
            if (stripos($f['name'], 'venue') !== false) {
                echo "  Field: {$f['name']}, Type: {$f['type']}\n";
                if (isset($f['options'])) {
                    foreach ($f['options'] as $o) {
                        echo "    Option: ID={$o['id']}, Name={$o['name']}\n";
                    }
                }
            }
        }
    }
}
