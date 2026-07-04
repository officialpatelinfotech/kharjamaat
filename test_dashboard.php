<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$output = "Testing Wajebaat Query:\n";
try {
    $envFile = __DIR__ . '/.env';
    if (is_readable($envFile)) {
        $lines = @file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '#') === 0) continue;
            $eqPos = strpos($line, '=');
            if ($eqPos !== false) {
                $k = trim(substr($line, 0, $eqPos));
                $v = trim(substr($line, $eqPos + 1));
                putenv("$k=$v");
                $_ENV[$k] = $v;
            }
        }
    }
    
    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $user = getenv('DB_USERNAME') ?: 'root';
    $pass = getenv('DB_PASSWORD') ?: 'root';
    $dbname = getenv('DB_DATABASE') ?: 'kharjamaat';
    
    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    $wajebaat_sql = "SELECT COUNT(*) AS cnt, SUM(amount) AS total_amount, SUM(due) AS total_due, SUM(CASE WHEN amount > due THEN (amount - due) ELSE 0 END) AS total_received FROM wajebaat";
    
    $result = $conn->query($wajebaat_sql);
    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }
    
    $res = $result->fetch_all(MYSQLI_ASSOC);
    $output .= json_encode($res, JSON_PRETTY_PRINT);
} catch (Throwable $e) {
    $output .= "ERROR: " . $e->getMessage() . "\n";
}

file_put_contents(__DIR__ . '/assets/test_dashboard_out.txt', base64_encode($output));
echo "Done";
