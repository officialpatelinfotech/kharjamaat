<?php
$user = 'kharjam1_kharjamaat';
$pass = 'khar@2024';
$dbname = 'kharjam1_kharjamaat';
$host = 'localhost';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$res = $conn->query("DESCRIBE expenses");
if ($res) {
    while($row = $res->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
} else {
    echo "Error: " . $conn->error;
}
