<?php
define('BASEPATH', 'c:/xampp/htdocs/kharjamaat/');
require_once 'c:/xampp/htdocs/kharjamaat/application/models/HijriCalendar.php';

class Mock {
    public $db;
    public function __construct() {
        // Just enough to satisfy the model if it uses CI db, but HijriCalendar seems standalone or uses helper
    }
}

// In CI, many things are loaded differently. Let's just try to call the function directly if possible or check the logic.
// The logic in Accounts.php was:
/*
  $todayHijri = $this->HijriCalendar->get_hijri_date(date('Y-m-d'));
  $hijriYearNum = null;
  if (isset($todayHijri['hijri_date'])) {
    $parts = explode('-', (string)$todayHijri['hijri_date']);
    if (count($parts) === 3 && is_numeric($parts[2])) {
      $hijriYearNum = (int)$parts[2];
    }
  }
*/

// Let's just compute it simply to see what it would be.
$d = date('Y-m-d');
echo "Today: $d\n";
// Februrary 2026 is likely 1447AH.
// Let's check a few months.
// Feb 2026 -> Ramadan 1447 approx.

// If it is 1447, then hijriRange is 1447-48.
// But database records are 1446-47.
