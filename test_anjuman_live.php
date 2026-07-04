<?php
header('Content-Type: text/plain');
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "Listing all tables on production database...\n\n";

// 1. Load env variables
$envFile = __DIR__ . '/.env';
if (is_readable($envFile)) {
    $lines = @file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (is_array($lines)) {
        foreach ($lines as $line) {
            $line = trim((string)$line);
            if ($line === '' || strpos($line, '#') === 0) continue;
            if (stripos($line, 'export ') === 0) {
                $line = trim(substr($line, 7));
            }
            $eqPos = strpos($line, '=');
            if ($eqPos === false) continue;
            $key = trim(substr($line, 0, $eqPos));
            $val = trim(substr($line, $eqPos + 1));
            if ($key === '') continue;
            $len = strlen($val);
            if ($len >= 2) {
                $first = $val[0];
                $last = $val[$len - 1];
                if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                    $val = substr($val, 1, -1);
                }
            }
            @putenv($key . '=' . $val);
            $_ENV[$key] = $val;
            $_SERVER[$key] = $val;
        }
    }
}

// 2. Load Production Database configuration
define('BASEPATH', '1');
define('ENVIRONMENT', 'production');
require_once 'application/config/production/database.php';

$currentHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'kharjamaat.in';
$defaultDbUser = 'kharjam1_kharjamaat';
$defaultDbPass = 'khar@2024';
$defaultDbName = 'kharjam1_kharjamaat';

if (strpos($currentHost, 'tanzeem.in') !== false) {
    $defaultDbUser = 'kharjam1_tanzeem';
    $defaultDbPass = 'kharjam1_tanzeem';
    $defaultDbName = 'kharjam1_tanzeem';
} elseif (strpos($currentHost, 'jamalipoona.in') !== false) {
    $defaultDbUser = 'kharjam1_jamalipoona';
    $defaultDbPass = 'kharjam1_jamalipoona';
    $defaultDbName = 'kharjam1_jamalipoona';
}

$dbUserOverride = (getenv('DB_USER') !== false && getenv('DB_USER') !== '') ? getenv('DB_USER') : $defaultDbUser;
$dbPassOverride = (getenv('DB_PASS') !== false) ? getenv('DB_PASS') : $defaultDbPass;
$dbNameOverride = (getenv('DB_NAME') !== false && getenv('DB_NAME') !== '') ? getenv('DB_NAME') : $defaultDbName;
$dbHostOverride = (getenv('DB_HOST') !== false && getenv('DB_HOST') !== '') ? getenv('DB_HOST') : 'localhost';

try {
    $pdo = new PDO("mysql:host={$dbHostOverride};dbname={$dbNameOverride}", $dbUserOverride, $dbPassOverride);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connection successful!\n\n";
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage() . "\n");
}

$stmt = $pdo->query("SHOW TABLES");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo "Tables in database:\n";
foreach ($tables as $t) {
    echo "- $t\n";
}

echo "\nCompleted listing.\n";
