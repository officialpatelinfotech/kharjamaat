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
echo "=== Search members for '30316528' ===\n";
$rows = $db->query("SELECT ITS_ID, Full_Name FROM user WHERE (Full_Name LIKE ? OR ITS_ID LIKE ?) AND Inactive_Status IS NULL ORDER BY Full_Name ASC LIMIT 10", ['%30316528%', '%30316528%']);
print_r($rows);

echo "=== Search members for 'Murtuzabhai' ===\n";
$rows = $db->query("SELECT ITS_ID, Full_Name FROM user WHERE (Full_Name LIKE ? OR ITS_ID LIKE ?) AND Inactive_Status IS NULL ORDER BY Full_Name ASC LIMIT 10", ['%Murtuzabhai%', '%Murtuzabhai%']);
print_r($rows);
