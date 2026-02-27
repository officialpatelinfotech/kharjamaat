<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=kharjamaat", "root", "");
    $razaTypeId = 21;
    
    // Check raza_type
    $stmt = $pdo->prepare("SELECT * FROM raza_type WHERE id = ?");
    $stmt->execute([$razaTypeId]);
    $rt = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Raza Type: " . $rt['name'] . " (Umoor: " . $rt['umoor'] . ")\n";

    $umoor = (string)($rt['umoor'] ?? '');
    $chargeTypesToTry = [];
    if ($umoor === 'Private-Event') {
      $chargeTypesToTry = ['rent', 'laagat'];
    } elseif ($umoor !== 'Public-Event') {
      $chargeTypesToTry = ['laagat', 'rent'];
    }

    $hijriRange = "1447-48"; // System calculated
    echo "Current Hijri Range: $hijriRange\n";

    $row = null;
    $chargeType = null;
    
    echo "Attempt 1: With current year ($hijriRange)...\n";
    foreach ($chargeTypesToTry as $ct) {
        $stmt = $pdo->prepare("SELECT * FROM laagat_rent WHERE charge_type = ? AND (raza_type_id = ? OR id IN (SELECT laagat_rent_id FROM laagat_rent_raza_type_map WHERE raza_type_id = ?)) AND hijri_year = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$ct, $razaTypeId, $razaTypeId, $hijriRange]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $chargeType = $ct;
            break;
        }
    }
    
    if (!$row) {
        echo "Attempt 1 failed. Attempt 2: FALLBACK (without year)...\n";
        foreach ($chargeTypesToTry as $ct) {
            $stmt = $pdo->prepare("SELECT * FROM laagat_rent WHERE charge_type = ? AND (raza_type_id = ? OR id IN (SELECT laagat_rent_id FROM laagat_rent_raza_type_map WHERE raza_type_id = ?)) AND is_active = 1 LIMIT 1");
            $stmt->execute([$ct, $razaTypeId, $razaTypeId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $chargeType = $ct;
                break;
            }
        }
    }

    if ($row) {
        echo "SUCCESS: Found record!\n";
        echo "Amount: " . $row['amount'] . "\n";
        echo "Year: " . $row['hijri_year'] . "\n";
        echo "Type: " . $chargeType . "\n";
    } else {
        echo "FAILURE: Still no record found.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
