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
echo "=== Users with empty Full_Name ===\n";
$rows = $db->query("SELECT ITS_ID, First_Name, Surname, Full_Name FROM user WHERE Full_Name IS NULL OR Full_Name = '' LIMIT 10");
print_r($rows);
