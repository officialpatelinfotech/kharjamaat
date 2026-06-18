<?php
define('ENVIRONMENT', 'development');
define('BASEPATH', '1');
$db_file = __DIR__ . '/../application/config/development/database.php';
require_once($db_file);

class SimpleDB {
    private $pdo;
    public function __construct($conf) {
        $dsn = "mysql:host=" . $conf['hostname'] . ";dbname=" . $conf['database'] . ";charset=" . $conf['char_set'];
        $this->pdo = new PDO($dsn, $conf['username'], $conf['password']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    public function query($sql) {
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

$db = new SimpleDB($db['default']);
echo "=== miqaat_invoice schema ===\n";
$schema = $db->query("DESCRIBE miqaat_invoice");
print_r($schema);

echo "\n=== recent miqaat_invoice rows ===\n";
$rows = $db->query("SELECT * FROM miqaat_invoice ORDER BY id DESC LIMIT 5");
print_r($rows);

echo "\n=== recent raza rows ===\n";
$rows = $db->query("SELECT id, raza_id FROM raza ORDER BY id DESC LIMIT 5");
print_r($rows);
