<?php
// Insert a test ekram fund, assignment and a payment row for diagnostics
$host = 'localhost';
$user = 'kharjam1_kharjamaat';
$pass = 'khar@2024';
$db   = 'kharjam1_kharjamaat';

$mysqli = mysqli_connect($host, $user, $pass, $db);
if (!$mysqli) {
    echo json_encode(['success'=>false,'error'=>'DB connect failed: '.mysqli_connect_error()]);
    exit(1);
}

mysqli_set_charset($mysqli, 'utf8');

// Create a test fund
$title = 'Test Ekram Fund';
$amount = 1000.00;
$hijri_year = 1447;
$description = 'Automated test fund';
$created_by = null;

$ins1 = "INSERT INTO ekram_fund (`title`,`amount`,`hijri_year`,`description`,`created_by`) VALUES ('".mysqli_real_escape_string($mysqli,$title)."',".floatval($amount).",".intval($hijri_year).",'".mysqli_real_escape_string($mysqli,$description)."',".($created_by===null?"NULL":intval($created_by)).")";
if (!mysqli_query($mysqli, $ins1)) {
    echo json_encode(['success'=>false,'error'=>'Insert fund failed: '.mysqli_error($mysqli),'query'=>$ins1]);
    exit(1);
}
$fund_id = mysqli_insert_id($mysqli);

// Assignment (hof_id arbitrary)
$hof_id = 999999; // test HOF id
$assign_amount = 1000.00;
$ins2 = "INSERT INTO ekram_fund_assignment (fund_id, hof_id, amount_assigned, created_at) VALUES (".intval($fund_id).",".intval($hof_id).",".floatval($assign_amount).",NOW())";
if (!mysqli_query($mysqli, $ins2)) {
    echo json_encode(['success'=>false,'error'=>'Insert assignment failed: '.mysqli_error($mysqli),'query'=>$ins2]);
    exit(1);
}

// Payment
$paid = 500.00;
$notes = 'Test payment via assistant';
$method = 'Cash';
$ins3 = "INSERT INTO ekram_fund_payment (fund_id, hof_id, amount_paid, notes, payment_method, received_by, paid_at) VALUES (".intval($fund_id).",".intval($hof_id).",".floatval($paid).",'".mysqli_real_escape_string($mysqli,$notes)."','".mysqli_real_escape_string($mysqli,$method)."',NULL,NOW())";
if (!mysqli_query($mysqli, $ins3)) {
    echo json_encode(['success'=>false,'error'=>'Insert payment failed: '.mysqli_error($mysqli),'query'=>$ins3]);
    exit(1);
}
$payment_id = mysqli_insert_id($mysqli);

echo json_encode(['success'=>true,'fund_id'=>$fund_id,'assignment_hof'=>$hof_id,'payment_id'=>$payment_id]);

mysqli_close($mysqli);
