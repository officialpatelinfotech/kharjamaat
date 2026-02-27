<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=kharjamaat", "root", "");
    
    $sql = "SELECT r.id, r.user_id, r.razaType, rt.umoor, rt.name as type_name 
            FROM raza r 
            JOIN raza_type rt ON rt.id = r.razaType 
            LEFT JOIN laagat_rent_invoices i ON i.raza_id = r.id 
            WHERE i.id IS NULL AND rt.umoor != 'Public-Event' AND r.user_id = '20321841'
            ORDER BY r.id DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $missing = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Razas for user 20321841 missing invoices:\n";
    foreach ($missing as $m) {
        $razaId = $m['id'];
        $razaTypeId = $m['razaType'];
        $umoor = $m['umoor'];
        $chargeType = ($umoor === 'Private-Event') ? 'rent' : 'laagat';
        
        // Check if config exists
        $stmt2 = $pdo->prepare("SELECT id FROM laagat_rent WHERE charge_type = ? AND (raza_type_id = ? OR id IN (SELECT laagat_rent_id FROM laagat_rent_raza_type_map WHERE raza_type_id = ?)) AND is_active = 1 LIMIT 1");
        $stmt2->execute([$chargeType, $razaTypeId, $razaTypeId]);
        $lr = $stmt2->fetch(PDO::FETCH_ASSOC);
        
        if ($lr) {
            echo "[CONFIG OK] Raza $razaId (Type: " . $m['type_name'] . ")\n";
        } else {
            echo "[NO CONFIG] Raza $razaId (Type: " . $m['type_name'] . ")\n";
        }
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
