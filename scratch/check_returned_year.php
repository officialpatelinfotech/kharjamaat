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
} else {
    echo "Default database config not set.\n";
    exit;
}

try {
    $pdo = new PDO("mysql:host={$conf['hostname']};dbname={$conf['database']}", $conf['username'], $conf['password']);
    
    // Main query inside get_all_member_miqaat_payments
    $sql = "SELECT
      u.ITS_ID,
      u.Full_Name,
      u.HOF_ID,
      u.Sector,
      u.Sub_Sector,
        m.id as miqaat_id,
        m.miqaat_id as miqaat_code,
        m.name as miqaat_name,
        m.type as miqaat_type,
        m.date as miqaat_date,
        m.assigned_to,
        i.id as invoice_id,
        i.amount as invoice_amount,
        i.date as invoice_date,
        i.description,
        i.year as invoice_year,
        i.miqaat_type as invoice_miqaat_type
    FROM user u
    LEFT JOIN miqaat_invoice i ON u.ITS_ID = i.user_id
    LEFT JOIN miqaat m ON m.id = i.miqaat_id
    WHERE u.ITS_ID = 20321805";
    
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Helper function mapping
    $getHijriYear = function ($gregDate) use ($pdo) {
      if (empty($gregDate)) return null;
      $st = $pdo->prepare("SELECT hijri_date FROM hijri_calendar WHERE greg_date = ?");
      $st->execute([$gregDate]);
      $h = $st->fetch(PDO::FETCH_ASSOC);
      if (!$h || empty($h['hijri_date'])) return null;
      $parts = explode('-', (string)$h['hijri_date']); // d-m-Y
      $month = (int)($parts[1] ?? 0);
      $year = (int)($parts[2] ?? 0);
      if ($year === 0 || $month === 0) return null;
      if ($month >= 7 && $month <= 12) {
        return $year . '-' . substr((string)($year + 1), -2);
      } else {
        return ($year - 1) . '-' . substr((string)$year, -2);
      }
    };

    foreach ($rows as $row) {
        $effYear = null;
        if (isset($row['invoice_year']) && (string)$row['invoice_year'] !== '') {
          $yearStr = (string)$row['invoice_year'];
          if (strpos($yearStr, '-') !== false) {
            $effYear = $yearStr;
          } else {
            $single_year = (int)$yearStr;
            $effYear = ($single_year - 1) . '-' . substr((string)$yearStr, -2);
          }
        }
        if ($effYear === null && !empty($row['miqaat_id']) && !empty($row['miqaat_date'])) {
          $effYear = $getHijriYear($row['miqaat_date']);
        }
        if ($effYear === null && !empty($row['invoice_date'])) {
          $effYear = $getHijriYear($row['invoice_date']);
        }
        
        echo "Invoice ID: " . $row['invoice_id'] . ", DB Year: " . $row['invoice_year'] . ", Resolved Year: " . $effYear . ", Date: " . $row['invoice_date'] . ", Miqaat Date: " . $row['miqaat_date'] . "\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
