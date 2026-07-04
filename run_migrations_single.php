<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "Running Database Migrations (Single)...\n";
$user = 'kharjam1_kharjamaat';
$pass = 'khar@2024';
$dbname = 'kharjam1_kharjamaat';
$host = 'localhost';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$queries = ["SHOW TABLES"];

foreach ($queries as $i => $q) {
    echo "Executing query $i...\n";
    if ($result = $conn->query($q)) {
        while ($row = $result->fetch_row()) {
            echo "- " . $row[0] . "\n";
        }
    } else {
        echo "Error: " . $conn->error . "\n";
    }
}
echo "Done.\n";
