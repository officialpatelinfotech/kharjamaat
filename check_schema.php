<?php
$user = 'kharjam1_kharjamaat';
$pass = 'khar@2024';
$dbname = 'kharjam1_kharjamaat';
$host = 'localhost';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$res1 = $conn->query("ALTER TABLE expenses ADD COLUMN item_id INT UNSIGNED DEFAULT NULL AFTER expense_date");
if ($res1) {
    echo "Added item_id column.\n";
    $res2 = $conn->query("ALTER TABLE expenses ADD CONSTRAINT fk_expenses_item FOREIGN KEY (item_id) REFERENCES expense_items(id) ON DELETE RESTRICT ON UPDATE CASCADE");
    if ($res2) {
        echo "Added foreign key constraint.\n";
    } else {
        echo "Error adding constraint: " . $conn->error . "\n";
    }
} else {
    echo "Error adding column: " . $conn->error . "\n";
}
