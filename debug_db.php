<?php
$mysqli = new mysqli('localhost', 'root', '', 'kharjamaat');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$result = $mysqli->query("SELECT version FROM migrations");

if ($result) {
    echo "Current Migration Version: ";
    $row = $result->fetch_assoc();
    echo $row['version'];
} else {
    echo "Error: " . $mysqli->error . ". (Maybe migrations table doesn't exist)";
}

echo "\n\nChecking madresa_class schema:\n";
$result = $mysqli->query("DESCRIBE madresa_class");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
} else {
    echo "madresa_class table error: " . $mysqli->error . "\n";
}

$mysqli->close();
?>
