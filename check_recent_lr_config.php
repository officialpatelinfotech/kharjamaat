<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=kharjamaat", "root", "");
    $razaTypeIds = [30, 54, 73, 44]; // From recent check
    $placeholders = implode(',', array_fill(0, count($razaTypeIds), '?'));
    
    $sql = "SELECT lr.* FROM laagat_rent lr 
            WHERE (lr.raza_type_id IN ($placeholders) 
            OR lr.id IN (SELECT laagat_rent_id FROM laagat_rent_raza_type_map WHERE raza_type_id IN ($placeholders)))
            AND lr.is_active = 1";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_merge($razaTypeIds, $razaTypeIds));
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
