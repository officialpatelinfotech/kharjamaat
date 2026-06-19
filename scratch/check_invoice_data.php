<?php
define('BASEPATH', '1');
define('ENVIRONMENT', 'development');

require_once(__DIR__ . '/../system/core/Model.php');
// Simple stub of CI model load if needed, or we can just fetch PDO raw
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
        
        // Fetch raza records
        $stmt = $pdo->prepare("
            SELECT id, raza_id, user_id, razaType, razadata, miqaat_id
            FROM raza
            WHERE id IN (535, 536, 537)
        ");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "Raw Invoice Records:\n";
        echo json_encode($rows, JSON_PRETTY_PRINT) . "\n\n";
        
        // Simulate effectiveYear logic
        foreach ($rows as $row) {
            $miqaat_date = $row['miqaat_date'];
            $effectiveYear = null;
            if (!empty($miqaat_date)) {
                // Calendar lookup simulation
                $stmtCal = $pdo->prepare("SELECT * FROM hijri_calendar WHERE greg_date = ?");
                $stmtCal->execute([$miqaat_date]);
                $h = $stmtCal->fetch(PDO::FETCH_ASSOC);
                if ($h && !empty($h['hijri_date'])) {
                    $parts = explode('-', $h['hijri_date']);
                    $effectiveYear = $parts[2] ?? null;
                    echo "Cal match for Date {$miqaat_date}: {$h['hijri_date']} -> Year {$effectiveYear}\n";
                } else {
                    echo "Cal NO MATCH for Date {$miqaat_date}\n";
                }
            } else {
                $effectiveYear = $row['invoice_year'];
                echo "No miqaat_date, using invoice_year: {$effectiveYear}\n";
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
