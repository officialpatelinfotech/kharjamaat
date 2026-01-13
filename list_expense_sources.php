<?php
$host='127.0.0.1'; $user='root'; $pass=''; $db='kharjamaat';
$mysqli = new mysqli($host,$user,$pass,$db);
if($mysqli->connect_errno){ echo "CONNECT_ERR: ".$mysqli->connect_error.PHP_EOL; exit(1); }
$res = $mysqli->query("SELECT id,name,status,created_at FROM expense_sources ORDER BY id ASC");
if(!$res){ echo "QUERY_ERR: ".$mysqli->error.PHP_EOL; exit(1); }
$rows = [];
while($r = $res->fetch_assoc()) $rows[] = $r;
if(empty($rows)) { echo "NO_ROWS\n"; } else { foreach($rows as $r) echo implode(' | ', $r)."\n"; }
$mysqli->close();
