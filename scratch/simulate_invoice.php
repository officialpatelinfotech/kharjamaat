<?php
define('ENVIRONMENT', 'development');
define('BASEPATH', '1');
$db_file = __DIR__ . '/../application/config/development/database.php';
require_once($db_file);

class SimpleDB {
    private $pdo;
    public function __construct($conf) {
        $dsn = "mysql:host=" . $conf['hostname'] . ";dbname=" . $conf['database'] . ";charset=" . $conf['char_set'];
        $this->pdo = new PDO($dsn, $conf['username'], $conf['password']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    public function query($sql) {
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function insert($table, $data) {
        $keys = array_keys($data);
        $fields = implode(", ", $keys);
        $placeholders = ":" . implode(", :", $keys);
        $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }
}

$db = new SimpleDB($db['default']);

$miqaat_id = 344;
$userId = 30301518;
$check = 527; // Mock inserted Raza ID

try {
  if (!empty($miqaat_id)) {
    echo "1. miqaat_id is not empty ($miqaat_id)\n";
    $miqaat_details = $db->query("SELECT * FROM miqaat WHERE id = $miqaat_id");
    if (count($miqaat_details) > 0) {
      $miqaat_details = $miqaat_details[0];
      echo "2. miqaat_details found (type: " . $miqaat_details['type'] . ")\n";
      $miqaat_type = $miqaat_details['type'];
      $niyaz_amounts_row = $db->query("SELECT * FROM miqaat_niyaz_amounts WHERE miqaat_type = '$miqaat_type'");
      if (count($niyaz_amounts_row) > 0) {
        $niyaz_amounts_row = $niyaz_amounts_row[0];
        echo "3. niyaz_amounts_row found\n";
        $assignment = $db->query("SELECT * FROM miqaat_assignments WHERE miqaat_id = $miqaat_id AND member_id = $userId");
        $is_group = false;
        if (count($assignment) > 0) {
           $assignment = $assignment[0];
           $is_group = ($assignment['assign_type'] === 'Group');
           echo "4. assignment found, is_group: " . ($is_group ? 'true' : 'false') . "\n";
        }
        
        $amountToCharge = 0;
        $descriptionStr = '';
        
        if ($is_group) {
           $amountToCharge = (float)$niyaz_amounts_row['fala_amount'];
           $descriptionStr = 'Fala ni Niyaz';
        } else {
           $amountToCharge = (float)$niyaz_amounts_row['individual_amount'];
           $descriptionStr = 'Individual Niyaz';
        }
        echo "5. amountToCharge: $amountToCharge\n";
        
        if ($amountToCharge > 0) {
          $existing = $db->query("SELECT * FROM miqaat_invoice WHERE user_id = $userId AND miqaat_id = $miqaat_id AND raza_id = $check");
          if (count($existing) == 0) {
            echo "6. No existing invoice found. Creating...\n";
            $miqaatInvData = [
              'date' => date('Y-m-d'),
              'year' => (int)date('Y'),
              'miqaat_id' => $miqaat_id,
              'miqaat_type' => $miqaat_type,
              'raza_id' => $check,
              'user_id' => $userId,
              'amount' => $amountToCharge,
              'description' => $descriptionStr,
              'status' => 0
            ];
            print_r($miqaatInvData);
            $db->insert('miqaat_invoice', $miqaatInvData);
            echo "7. Created!\n";
          } else {
            echo "6. Existing invoice found!\n";
          }
        }
      } else {
        echo "3. niyaz_amounts_row NOT found\n";
      }
    } else {
      echo "2. miqaat_details NOT found\n";
    }
  }
} catch (Exception $e) {
  echo 'Exception: ' . $e->getMessage();
}
