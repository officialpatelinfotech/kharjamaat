<?php
header('Content-Type: text/plain');
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "Diagnosing Anjuman Dashboard Queries on Live (Optimized)...\n\n";

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

function testQuery($pdo, $name, $sql, $params = []) {
    echo "Testing query: $name...\n";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "=> Success! Found " . count($res) . " rows.\n";
        if (!empty($res)) {
            print_r($res[0]);
        }
        echo "\n";
        return $res;
    } catch (PDOException $e) {
        echo "=> FAILED: " . $e->getMessage() . "\n\n";
        return null;
    }
}

// Fetch MAX(year) from fmb_takhmeen first
echo "Fetching MAX(year) from fmb_takhmeen...\n";
$stmt = $pdo->query("SELECT MAX(year) AS max_year FROM fmb_takhmeen");
$max_year_row = $stmt->fetch(PDO::FETCH_ASSOC);
$max_year = $max_year_row ? $max_year_row['max_year'] : null;
echo "MAX Year: " . ($max_year ?: 'NULL') . "\n\n";

if ($max_year) {
    // 2. Optimized Thaali summary
    $thaali_opt_sql = "SELECT 
        (SELECT SUM(total_amount) FROM fmb_takhmeen WHERE year = :year) AS total_thaali,
        (SELECT SUM(amount) FROM fmb_takhmeen_payments WHERE user_id IN (
            SELECT DISTINCT user_id FROM fmb_takhmeen WHERE year = :year
         )) AS total_paid";
    testQuery($pdo, "Optimized Thaali Summary", $thaali_opt_sql, ['year' => $max_year]);
}

// Test other queries
$wajebaat_sql = "SELECT COUNT(*) AS cnt, SUM(amount) AS total_amount, SUM(due) AS total_due, SUM(CASE WHEN amount > due THEN (amount - due) ELSE 0 END) AS total_received FROM wajebaat";
testQuery($pdo, "Wajebaat Summary", $wajebaat_sql);

$sector_sql = "SELECT
  u.Sector,
  COUNT(*) AS total,
  SUM(CASE WHEN u.HOF_FM_TYPE = 'HOF' THEN 1 ELSE 0 END) AS hof_count,
  SUM(CASE WHEN u.HOF_FM_TYPE = 'FM' THEN 1 ELSE 0 END) AS fm_count
FROM user u
WHERE u.sector IS NOT NULL AND u.sub_sector IS NOT NULL 
GROUP BY u.Sector
ORDER BY u.Sector";
testQuery($pdo, "Sector Stats", $sector_sql);

echo "\nDiagnostic completed.\n";
