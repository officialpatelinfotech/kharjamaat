<?php
define('BASEPATH', __DIR__ . '/system/');
define('APPPATH', __DIR__ . '/application/');
define('ENVIRONMENT', 'development');

require_once APPPATH . 'config/development/database.php';
$dbConfig = $db['default'];

$conn = new mysqli($dbConfig['hostname'], $dbConfig['username'], $dbConfig['password'], $dbConfig['database']);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$columns = [
    'deeni_status' => "VARCHAR(255) NULL DEFAULT NULL",
    'health_status' => "VARCHAR(255) NULL DEFAULT NULL",
    'residential_status' => "VARCHAR(255) NULL DEFAULT NULL"
];

foreach ($columns as $col => $def) {
    $res = $conn->query("SHOW COLUMNS FROM user LIKE '$col'");
    if ($res->num_rows == 0) {
        $conn->query("ALTER TABLE user ADD COLUMN $col $def");
        echo "Added column $col\n";
    } else {
        echo "Column $col already exists\n";
    }
}

$conn->close();
