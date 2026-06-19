<?php
define('BASEPATH', '1');
define('ENVIRONMENT', 'development');
$db = [];
if (file_exists(__DIR__ . '/../application/config/development/database.php')) {
    require_once(__DIR__ . '/../application/config/development/database.php');
} elseif (file_exists(__DIR__ . '/../application/config/production/database.php')) {
    require_once(__DIR__ . '/../application/config/production/database.php');
} else {
    echo "Database config file not found.\n";
    exit;
}

if (isset($db['default'])) {
    $conf = $db['default'];
    try {
        $pdo = new PDO("mysql:host={$conf['hostname']};dbname={$conf['database']}", $conf['username'], $conf['password']);
        $sql = "SELECT DISTINCT
          CASE
            WHEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(hijri_date,'-',2),'-',-1) AS UNSIGNED) BETWEEN 7 AND 12
              THEN CONCAT(
                CAST(SUBSTRING_INDEX(hijri_date,'-',-1) AS UNSIGNED),
                '-',
                LPAD(RIGHT(CAST(SUBSTRING_INDEX(hijri_date,'-',-1) AS UNSIGNED) + 1, 2), 2, '0')
              )
            ELSE CONCAT(
              CAST(SUBSTRING_INDEX(hijri_date,'-',-1) AS UNSIGNED) - 1,
              '-',
              LPAD(RIGHT(CAST(SUBSTRING_INDEX(hijri_date,'-',-1) AS UNSIGNED), 2), 2, '0')
            )
          END AS fy
        FROM hijri_calendar
        ORDER BY fy DESC";
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo json_encode($results, JSON_PRETTY_PRINT) . "\n";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
