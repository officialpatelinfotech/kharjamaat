<?php
define('BASEPATH', 'dummy'); 
require_once 'application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$result = $mysqli->query("SELECT version FROM migrations");

if ($result) {
    echo "Current Migration Version: ";
    while ($row = $result->fetch_assoc()) {
        echo $row['version'];
    }
} else {
    echo "Error: " . $mysqli->error . ". (Maybe migrations table doesn't exist)";
}

$mysqli->close();
?>
