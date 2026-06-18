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
echo "=== miqaat_niyaz_amounts ===\n";
$rows = $db->query("SELECT * FROM miqaat_niyaz_amounts");
print_r($rows);

echo "\n=== raza 527 details ===\n";
$rows = $db->query("SELECT * FROM raza WHERE id = 527");
print_r($rows);

if (count($rows) > 0) {
    echo "\n=== miqaat details for miqaat_id = " . $rows[0]['miqaat_id'] . " ===\n";
    $m = $db->query("SELECT * FROM miqaat WHERE id = " . $rows[0]['miqaat_id']);
    print_r($m);
    
    echo "\n=== miqaat_assignments for miqaat_id = " . $rows[0]['miqaat_id'] . " ===\n";
    $ma = $db->query("SELECT * FROM miqaat_assignments WHERE miqaat_id = " . $rows[0]['miqaat_id'] . " AND member_id = " . $rows[0]['user_id']);
    print_r($ma);
    
    echo "\n=== any miqaat_invoice for raza_id = 527 ===\n";
    $mi = $db->query("SELECT * FROM miqaat_invoice WHERE raza_id = 527");
    print_r($mi);
}
