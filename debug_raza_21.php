<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=kharjamaat", "root", "");
    $razaTypeId = 21;
    
    // Check raza_type
    $stmt = $pdo->prepare("SELECT * FROM raza_type WHERE id = ?");
    $stmt->execute([$razaTypeId]);
    $rt = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Raza Type:\n";
    print_r($rt);

    $umoor = (string)($rt['umoor'] ?? '');
    $chargeTypesToTry = [];
    if ($umoor === 'Private-Event') {
      $chargeTypesToTry = ['rent', 'laagat'];
    } elseif ($umoor !== 'Public-Event') {
      $chargeTypesToTry = ['laagat', 'rent'];
    }
    echo "Charge Types to Try: " . implode(', ', $chargeTypesToTry) . "\n";

    // Simulate current year logic from controller
    $hijriYearNum = 1447; // Assuming Feb 2026 is 1447
    $hijriRange = $hijriYearNum . '-' . substr((string)($hijriYearNum + 1), -2);
    echo "Hijri Range: $hijriRange\n";

    foreach ($chargeTypesToTry as $ct) {
        echo "Trying $ct...\n";
        $stmt = $pdo->prepare("SELECT * FROM laagat_rent WHERE charge_type = ? AND (raza_type_id = ? OR id IN (SELECT laagat_rent_id FROM laagat_rent_raza_type_map WHERE raza_type_id = ?)) AND hijri_year = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$ct, $razaTypeId, $razaTypeId, $hijriRange]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            echo "Found Row:\n";
            print_r($row);
            break;
        } else {
            echo "Not found for year $hijriRange\n";
        }
    }
    
    // Check for ANY active record for this raza type regardless of year
    echo "\nChecking ANY active record for raza_type_id $razaTypeId:\n";
    $stmt = $pdo->prepare("SELECT id, title, hijri_year, amount, charge_type FROM laagat_rent WHERE (raza_type_id = ? OR id IN (SELECT laagat_rent_id FROM laagat_rent_raza_type_map WHERE raza_type_id = ?)) AND is_active = 1");
    $stmt->execute([$razaTypeId, $razaTypeId]);
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
