<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=kharjamaat", "root", "");
    
    // Find razas that don't have an invoice in laagat_rent_invoices
    // Only Razas that are NOT Public-Event should ideally have a charge.
    $sql = "SELECT r.id, r.user_id, r.razaType, rt.umoor, rt.name as type_name 
            FROM raza r 
            JOIN raza_type rt ON rt.id = r.razaType 
            LEFT JOIN laagat_rent_invoices i ON i.raza_id = r.id 
            WHERE i.id IS NULL AND rt.umoor != 'Public-Event'
            ORDER BY r.id DESC LIMIT 50";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $missing = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($missing) . " razas missing invoices.\n";
    
    $hijriYearNum = 1447; // Current approx
    $hijriRange = "1447-48";
    
    foreach ($missing as $m) {
        $razaId = $m['id'];
        $userId = $m['user_id'];
        $razaTypeId = $m['razaType'];
        $umoor = $m['umoor'];
        
        $chargeType = null;
        if ($umoor === 'Private-Event') {
            $chargeType = 'rent';
        } else {
            $chargeType = 'laagat';
        }
        
        // Try current year
        $stmt2 = $pdo->prepare("SELECT id, amount FROM laagat_rent WHERE charge_type = ? AND (raza_type_id = ? OR id IN (SELECT laagat_rent_id FROM laagat_rent_raza_type_map WHERE raza_type_id = ?)) AND hijri_year = ? AND is_active = 1 LIMIT 1");
        $stmt2->execute([$chargeType, $razaTypeId, $razaTypeId, $hijriRange]);
        $lr = $stmt2->fetch(PDO::FETCH_ASSOC);
        
        if (!$lr) {
            // Fallback
            $stmt2 = $pdo->prepare("SELECT id, amount FROM laagat_rent WHERE charge_type = ? AND (raza_type_id = ? OR id IN (SELECT laagat_rent_id FROM laagat_rent_raza_type_map WHERE raza_type_id = ?)) AND is_active = 1 ORDER BY hijri_year DESC LIMIT 1");
            $stmt2->execute([$chargeType, $razaTypeId, $razaTypeId]);
            $lr = $stmt2->fetch(PDO::FETCH_ASSOC);
        }
        
        if ($lr) {
            echo "Creating invoice for Raza $razaId (User: $userId, Type: " . $m['type_name'] . ") - Amt: " . $lr['amount'] . "\n";
            $stmt3 = $pdo->prepare("INSERT INTO laagat_rent_invoices (user_id, laagat_rent_id, raza_id, amount, created_at) VALUES (?, ?, ?, ?, ?)");
            $stmt3->execute([$userId, $lr['id'], $razaId, $lr['amount'], date('Y-m-d H:i:s')]);
        }
    }
    
    echo "Backfill complete.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
