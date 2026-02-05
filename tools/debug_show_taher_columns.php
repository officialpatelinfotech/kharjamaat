<?php
// Debug helper: show columns for qardan_hasana_taher_scheme
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'kharjamaat';

$mysqli = @new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    fwrite(STDERR, "connect fail: {$mysqli->connect_error}\n");
    exit(1);
}

$res = $mysqli->query('SHOW COLUMNS FROM qardan_hasana_taher_scheme');
if (!$res) {
    fwrite(STDERR, "query fail: {$mysqli->error}\n");
    exit(1);
}

while ($row = $res->fetch_assoc()) {
    $null = isset($row['Null']) ? $row['Null'] : '';
    $def = array_key_exists('Default', $row) ? $row['Default'] : null;
    $defStr = $def === null ? 'NULL' : (string)$def;
    echo $row['Field'] . "\t" . $row['Type'] . "\tNULL=" . $null . "\tDEFAULT=" . $defStr . "\n";
}
