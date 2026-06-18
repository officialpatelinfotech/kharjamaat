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
    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

$db = new SimpleDB($db['default']);
echo "=== Distinct Inactive_Status ===\n";
$rows = $db->query("SELECT DISTINCT Inactive_Status FROM user");
print_r($rows);

echo "=== Total Users ===\n";
$rows = $db->query("SELECT count(*) as cnt FROM user");
print_r($rows);
