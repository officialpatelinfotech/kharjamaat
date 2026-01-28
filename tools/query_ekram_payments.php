<?php
$cfg = [
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '',
    'db'   => 'kharjamaat',
];
$mysqli = new mysqli($cfg['host'], $cfg['user'], $cfg['pass'], $cfg['db']);
if ($mysqli->connect_errno) {
    echo json_encode(['error' => 'Connect failed: ' . $mysqli->connect_error]);
    exit(1);
}
$out = [];
// Query 1: overall counts
$q1 = "SELECT COUNT(*) AS cnt, COALESCE(SUM(amount_paid),0) AS total_paid FROM ekram_fund_payment";
$res = $mysqli->query($q1);
$out['overall'] = $res ? $res->fetch_assoc() : null;
// Query 2: grouped by hof_id
$q2 = "SELECT hof_id, COUNT(*) AS cnt, COALESCE(SUM(amount_paid),0) AS total_paid FROM ekram_fund_payment GROUP BY hof_id ORDER BY total_paid DESC LIMIT 50";
$res = $mysqli->query($q2);
$out['by_hof'] = [];
if ($res) {
    while ($r = $res->fetch_assoc()) $out['by_hof'][] = $r;
}
// Query 3: recent payments
$q3 = "SELECT id, fund_id, hof_id, amount_paid, notes, payment_method, received_by, paid_at FROM ekram_fund_payment ORDER BY paid_at DESC LIMIT 50";
$res = $mysqli->query($q3);
$out['recent'] = [];
if ($res) {
    while ($r = $res->fetch_assoc()) $out['recent'][] = $r;
}
echo json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
$mysqli->close();
