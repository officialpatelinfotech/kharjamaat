<?php
header('Content-Type: text/plain');
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "Diagnosing Anjuman Dashboard Queries on Live...\n\n";

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

$dbConf = $db['default'];
$host = $dbConf['hostname'];
$user = $dbConf['username'];
$pass = $dbConf['password'];
$name = $dbConf['database'];

// If localhost forces CLI check, we should make sure we connect to localhost / 127.0.0.1 as per web environment.
// Since we are running over web on production, $_SERVER['HTTP_HOST'] is set.
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

echo "Database connection info:\n";
echo "Host: $dbHostOverride\n";
echo "User: $dbUserOverride\n";
echo "Database: $dbNameOverride\n\n";

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
        echo "=> Success! Found " . count($res) . " rows.\n\n";
        return $res;
    } catch (PDOException $e) {
        echo "=> FAILED: " . $e->getMessage() . "\n\n";
        return null;
    }
}

// Now test all major dashboard queries
// 1. Sabeel summary
$sabeel_sql = "SELECT 
  SUM(est_grade.amount + COALESCE(res_grade.amount * 12, 0)) as total_sabeel,
  SUM(COALESCE(est_paid.total_paid, 0) + COALESCE(res_paid.total_paid, 0)) as total_paid,
  (SUM(est_grade.amount + COALESCE(res_grade.amount * 12, 0)) - 
   SUM(COALESCE(est_paid.total_paid, 0) + COALESCE(res_paid.total_paid, 0))) as outstanding
FROM user u
LEFT JOIN sabeel_takhmeen st ON st.user_id = u.ITS_ID 
  AND st.year = (SELECT MAX(year) FROM sabeel_takhmeen WHERE user_id = u.ITS_ID)
LEFT JOIN sabeel_takhmeen_grade est_grade ON est_grade.id = st.establishment_grade
LEFT JOIN sabeel_takhmeen_grade res_grade ON res_grade.id = st.residential_grade
LEFT JOIN (
  SELECT user_id, SUM(amount) as total_paid 
  FROM sabeel_takhmeen_payments 
  WHERE type = 'establishment' 
  GROUP BY user_id
) est_paid ON est_paid.user_id = u.ITS_ID
LEFT JOIN (
  SELECT user_id, SUM(amount) as total_paid 
  FROM sabeel_takhmeen_payments 
  WHERE type = 'residential' 
  GROUP BY user_id
) res_paid ON res_paid.user_id = u.ITS_ID
WHERE u.HOF_FM_TYPE = 'HOF' AND u.Inactive_Status IS NULL";
testQuery($pdo, "Sabeel Summary", $sabeel_sql);

// 2. Thaali summary
$thaali_sql = "SELECT 
    (SELECT SUM(ft.total_amount)
       FROM fmb_takhmeen ft
      WHERE ft.year = (SELECT MAX(year) FROM fmb_takhmeen)
    ) AS total_thaali,
    (SELECT SUM(p.amount)
       FROM fmb_takhmeen_payments p
      WHERE p.user_id IN (
            SELECT DISTINCT ft2.user_id
              FROM fmb_takhmeen ft2
             WHERE ft2.year = (SELECT MAX(year) FROM fmb_takhmeen)
      )
    ) AS total_paid";
testQuery($pdo, "Thaali Summary", $thaali_sql);

// 3. FMB General Contribution summary
$fmb_sql = "SELECT 
  SUM(amount) as total_amount,
  SUM(CASE WHEN payment_status = 1 THEN amount ELSE 0 END) as paid_amount,
  (SUM(amount) - SUM(CASE WHEN payment_status = 1 THEN amount ELSE 0 END)) as outstanding
FROM fmb_general_contribution";
testQuery($pdo, "FMB Contribution Summary", $fmb_sql);

// 4. Raza summary
$raza_sql = "SELECT 
  SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) AS pending,
  SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) AS approved,
  SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) AS rejected
FROM raza
WHERE active = 1";
testQuery($pdo, "Raza Summary", $raza_sql);

// 5. Miqaat finance summary
$miqaat_sql = "SELECT 
  COALESCE(SUM(i.amount), 0) AS total_invoiced,
  (
    SELECT COALESCE(SUM(p.amount), 0) FROM miqaat_payment p
  ) AS total_paid
FROM miqaat_invoice i";
testQuery($pdo, "Miqaat Finance Summary", $miqaat_sql);

// 6. Hijri calendar bounds for 1447 (or standard)
$hijri_year = 1447;
$hijri_cal_sql = "SELECT MIN(greg_date) AS min_d, MAX(greg_date) AS max_d FROM hijri_calendar WHERE hijri_date LIKE ?";
testQuery($pdo, "Hijri Calendar Bounds", $hijri_cal_sql, ['%-' . $hijri_year]);

// 7. Wajebaat summary
$wajebaat_sql = "SELECT COUNT(*) AS cnt, SUM(amount) AS total_amount, SUM(due) AS total_due, SUM(CASE WHEN amount > due THEN (amount - due) ELSE 0 END) AS total_received FROM wajebaat";
testQuery($pdo, "Wajebaat Summary", $wajebaat_sql);

// 8. Sector distribution / Stats
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
