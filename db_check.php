<?php
define('BASEPATH', '1');
// Mocking CI environment for a simple script
class MockDB {
    public $hostname;
    public $username;
    public $password;
    public $database;
    public function __construct($conf) {
        $this->hostname = $conf['hostname'];
        $this->username = $conf['username'];
        $this->password = $conf['password'];
        $this->database = $conf['database'];
    }
    public function query($sql) {
        try {
            $pdo = new PDO("mysql:host={$this->hostname};dbname={$this->database}", $this->username, $this->password);
            $stmt = $pdo->query($sql);
            if (!$stmt) return ["error" => "Query failed"];
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }
}

$db_file = __DIR__ . '/application/config/development/database.php';
if (file_exists($db_file)) {
    require_once($db_file);
    if (isset($db['default'])) {
        $mdb = new MockDB($db['default']);
        echo "--- RAZA TYPES (GFS) ---\n";
        print_r($mdb->query("SELECT id, name, umoor FROM raza_type WHERE name LIKE '%gfs%'"));
        echo "\n--- LAAGAT RENT RECORDS ---\n";
        print_r($mdb->query("SELECT id, title, amount, charge_type, hijri_year, is_active, raza_type_id FROM laagat_rent"));
        $tables = $mdb->query("SHOW TABLES LIKE 'laagat_rent_raza_type_map'");
        if (!empty($tables)) {
            echo "\n--- LAAGAT RENT MAP ---\n";
            print_r($mdb->query("SELECT * FROM laagat_rent_raza_type_map"));
        }
    }
} else {
    echo "Database config not found at $db_file";
}
