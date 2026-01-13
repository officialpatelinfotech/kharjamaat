<?php
$host='127.0.0.1'; $user='root'; $pass=''; $db='kharjamaat';
$mysqli = new mysqli($host,$user,$pass,$db);
if($mysqli->connect_errno){ echo "CONNECT_ERR: ".$mysqli->connect_error.PHP_EOL; exit(1); }
$name = 'test_' . time();
$status = 'Active';
$sql = "INSERT INTO `expense_sources` (`name`,`status`) VALUES (?,?)";
$stmt = $mysqli->prepare($sql);
if(!$stmt){ echo "PREPARE_ERR: ".$mysqli->error.PHP_EOL; exit(1); }
$stmt->bind_param('ss',$name,$status);
$ok = $stmt->execute();
if($ok){ echo "OK: inserted id=".$stmt->insert_id.PHP_EOL; } else { echo "EXEC_ERR: ".$stmt->error.PHP_EOL; }
$stmt->close();
$mysqli->close();
