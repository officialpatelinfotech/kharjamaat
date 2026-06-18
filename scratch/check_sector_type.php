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
$rows = $db->query("SELECT ITS_ID, Sector FROM user WHERE ITS_ID = '30316528'");
var_dump($rows[0]['Sector']);

$rows2 = $db->query("SELECT COUNT(*) as c FROM user WHERE Inactive_Status IS NULL AND Sector IS NOT NULL");
echo "Count Sector IS NOT NULL: " . $rows2[0]['c'] . "\n";

$rows3 = $db->query("SELECT COUNT(*) as c FROM user WHERE Inactive_Status IS NULL AND (Sector IS NOT NULL AND Sector != '')");
echo "Count Sector NOT NULL and NOT empty: " . $rows3[0]['c'] . "\n";
