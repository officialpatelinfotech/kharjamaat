<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'kharjamaat';

$mysqli = @new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    fwrite(STDERR, "connect fail: {$mysqli->connect_error}\n");
    exit(1);
}

$sql = "SELECT id, ITS, unit, units, miqaat_name, collection_amount, uploaded_date FROM qardan_hasana_taher_scheme ORDER BY id DESC LIMIT 10";
$res = $mysqli->query($sql);
if (!$res) {
    fwrite(STDERR, "query fail: {$mysqli->error}\n");
    exit(1);
}

while ($row = $res->fetch_assoc()) {
    echo json_encode($row, JSON_UNESCAPED_UNICODE) . "\n";
}
