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
    
    // Simulate the controller's call
    $miqaat_type = 'Ashara';
    
    // We want to fetch the member miqaat payments structure returned by AnjumanM
    // Let's write the exact main and FM query from AnjumanM.php and merge them.
    
    // Mock HijriCalendar methods
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
    
    $hijriMonthName = function ($month_id) use ($pdo) {
      $st = $pdo->prepare("SELECT hijri_month FROM hijri_month WHERE id = ?");
      $st->execute([$month_id]);
      $r = $st->fetch(PDO::FETCH_ASSOC);
      return $r ? $r['hijri_month'] : '';
    };
    
    $get_hijri_date = function($greg_date) use ($pdo) {
      $st = $pdo->prepare("SELECT * FROM hijri_calendar WHERE greg_date = ?");
      $st->execute([$greg_date]);
      return $st->fetch(PDO::FETCH_ASSOC);
    };

    // 1. Main query
    $sql1 = "SELECT 
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
      i.raza_id as invoice_raza_id,
      i.amount as invoice_amount,
      i.date as invoice_date,
      i.description,
      i.year as invoice_year,
      i.miqaat_type as invoice_miqaat_type
    FROM user u
    LEFT JOIN miqaat_invoice i ON u.ITS_ID = i.user_id
    LEFT JOIN miqaat m ON m.id = i.miqaat_id
    WHERE u.HOF_FM_TYPE = 'HOF' AND u.Inactive_Status IS NULL AND u.ITS_ID = 20321805";
    
    $stmt1 = $pdo->query($sql1);
    $invoiceResult = $stmt1->fetchAll(PDO::FETCH_ASSOC);
    
    $groupedMembers = [];
    foreach ($invoiceResult as $row) {
      $ITS_ID = $row['ITS_ID'];
      if (!isset($groupedMembers[$ITS_ID])) {
        $groupedMembers[$ITS_ID] = [
          'ITS_ID' => $row['ITS_ID'],
          'Full_Name' => $row['Full_Name'],
          'HOF_ID' => $row['HOF_ID'],
          'Sector' => $row['Sector'],
          'Sub_Sector' => $row['Sub_Sector'],
          'miqaat_invoices' => []
        ];
      }
      
      if (!empty($row['invoice_id'])) {
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
        
        $hijriLabel = null;
        if (!empty($row['miqaat_id']) && !empty($row['miqaat_date'])) {
          $h = $get_hijri_date($row['miqaat_date']);
          if ($h && !empty($h['hijri_date'])) {
            $parts = explode('-', (string)$h['hijri_date']);
            $day = $parts[0] ?? '';
            $month_id = $parts[1] ?? '';
            $hy = $parts[2] ?? '';
            $month_name = $hijriMonthName($month_id);
            $hijriLabel = trim($day . ' ' . $month_name . ' ' . $hy);
          }
        }
        
        if (!empty($row['miqaat_id'])) {
          $group_key   = "M#" . $row['miqaat_code'];
          $miqaat_id   = $row['miqaat_code'];
          $miqaat_name = $row['miqaat_name'];
        } else {
          $group_key   = $row['invoice_miqaat_type'] . " " . $effYear;
          $miqaat_id   = null;
          $miqaat_name = "Fala ni Niyaz " . $effYear;
        }
        
        $groupedMembers[$ITS_ID]['miqaat_invoices'][] = [
          'miqaat_group'   => $group_key,
          'miqaat_id'      => $miqaat_id,
          'miqaat_name'    => $miqaat_name,
          'miqaat_date'    => $row['miqaat_date'] ?? null,
          'hijri_date'     => $hijriLabel,
          'assigned_to'    => !empty($row['miqaat_id']) ? ($row['assigned_to'] ?? '') : 'Fala ni Niyaz',
          'invoice_id'     => $row['invoice_id'],
          'invoice_year'   => $effYear,
          'invoice_amount' => (float)$row['invoice_amount'],
          'paid_amount'    => 0,
          'due_amount'     => (float)$row['invoice_amount'],
          'invoice_date'   => $row['invoice_date'],
          'description'    => $row['description']
        ];
      }
    }
    
    // Now simulate the view logic
    $members = array_values($groupedMembers);
    $rows = [];
    foreach ($members as $m) {
      $its = $m['ITS_ID'];
      $name = $m['Full_Name'];
      $sector = $m['Sector'];
      $subSector = $m['Sub_Sector'];
      
      if (isset($m['miqaat_invoices']) && is_array($m['miqaat_invoices'])) {
        foreach ($m['miqaat_invoices'] as $inv) {
          $rows[] = [
            'invoice_id'   => $inv['invoice_id'],
            'its_id'       => $its,
            'full_name'    => $name,
            'sector'       => $sector,
            'sub_sector'   => $subSector,
            'invoice_year' => isset($inv['invoice_year']) ? (string)$inv['invoice_year'] : '',
            'miqaat_name'  => $inv['miqaat_name'],
            'amount'       => $inv['invoice_amount'],
          ];
        }
      }
    }
    
    echo "Rendered row data for user 20321805:\n";
    echo json_encode($rows, JSON_PRETTY_PRINT) . "\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
