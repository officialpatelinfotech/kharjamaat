<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CorpusFundM extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Ensure corpus_fund_payment table exists and has amount_paid column.
   * Adds column if missing (legacy installs) and migrates any existing `amount` values.
   */
  private function ensure_payment_table()
  {
    if (!$this->db->table_exists('corpus_fund_payment')) {
      $this->db->query("CREATE TABLE `corpus_fund_payment` (\n        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,\n        `fund_id` INT UNSIGNED NOT NULL,\n        `hof_id` INT NOT NULL,\n        `amount_paid` DECIMAL(10,2) NOT NULL DEFAULT 0,\n        `notes` VARCHAR(255) NULL,\n        `payment_method` VARCHAR(50) NULL,\n        `received_by` INT NULL,\n        `paid_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,\n        PRIMARY KEY (`id`),\n        KEY `idx_fund_hof` (`fund_id`,`hof_id`),\n        CONSTRAINT `fk_corpus_payment_fund` FOREIGN KEY (`fund_id`) REFERENCES `corpus_fund`(`id`) ON DELETE CASCADE ON UPDATE CASCADE\n      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
      return;
    }
    $fields = $this->db->list_fields('corpus_fund_payment');
    if (!in_array('amount_paid', $fields)) {
      if (in_array('amount', $fields)) {
        $this->db->query("ALTER TABLE `corpus_fund_payment` ADD COLUMN `amount_paid` DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER `hof_id`");
        $this->db->query("UPDATE `corpus_fund_payment` SET amount_paid = amount");
      } else {
        $this->db->query("ALTER TABLE `corpus_fund_payment` ADD COLUMN `amount_paid` DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER `hof_id`");
      }
    }
    // Add payment_method column if missing

  }

  public function create_fund($title, $amount, $description, $admin_id)
  {
    // Fallback: ensure tables exist if migrations failed
    if (!$this->db->table_exists('corpus_fund')) {
      log_message('error', 'corpus_fund table missing. Creating fallback table.');
      $this->db->query("CREATE TABLE `corpus_fund` (\n                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,\n                `title` VARCHAR(150) NOT NULL,\n                `amount` DECIMAL(10,2) NOT NULL DEFAULT 0,\n                `description` TEXT NULL,\n                `created_by` INT NULL,\n                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,\n                PRIMARY KEY (`id`)\n            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }
    if (!$this->db->table_exists('corpus_fund_assignment')) {
      log_message('error', 'corpus_fund_assignment table missing. Creating fallback table.');
      $this->db->query("CREATE TABLE `corpus_fund_assignment` (\n                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,\n                `fund_id` INT UNSIGNED NOT NULL,\n                `hof_id` INT NOT NULL,\n                `amount_assigned` DECIMAL(10,2) NOT NULL DEFAULT 0,\n                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,\n                PRIMARY KEY (`id`),\n                KEY `idx_fund_id` (`fund_id`),\n                KEY `idx_hof_id` (`hof_id`),\n                CONSTRAINT `fk_corpus_fund_assignment_fund` FOREIGN KEY (`fund_id`) REFERENCES `corpus_fund`(`id`) ON DELETE CASCADE ON UPDATE CASCADE\n            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }
    $data = [
      'title' => $title,
      'amount' => $amount,
      'description' => $description,
      'created_at' => date('Y-m-d H:i:s')
    ];
    $ok = $this->db->insert('corpus_fund', $data);
    if (!$ok) {
      $err = $this->db->error();
      log_message('error', 'Failed to insert corpus fund: ' . $this->db->last_query() . ' DB Error: ' . json_encode($err));
      return ['success' => false, 'error' => $err];
    }
    $id = $this->db->insert_id();
    if (!$id) {
      $err = ['code' => 0, 'message' => 'Insert ID missing'];
      log_message('error', 'Insert ID missing after corpus fund insert.');
      return ['success' => false, 'error' => $err];
    }
    return ['success' => true, 'id' => $id];
  }

  public function assign_to_all_hofs($fund_id, $amount)
  {
    // Identify all HOFs: assumption each distinct HOF_ID where ITS_ID == HOF_ID
    $hofs = $this->db->query(
      "SELECT DISTINCT HOF_ID FROM user 
      WHERE HOF_ID IS NOT NULL AND HOF_ID <> 0
        AND (Inactive_Status IS NULL OR Inactive_Status = 0)
        AND (Sector IS NOT NULL AND TRIM(Sector) <> '')
        AND (Sub_Sector IS NOT NULL AND TRIM(Sub_Sector) <> '')
        AND HOF_FM_TYPE = 'HOF'"
    )->result_array();
    if (empty($hofs)) {
      return 0;
    }
    $batch = [];
    foreach ($hofs as $h) {
      $hof_id = (int)$h['HOF_ID'];
      // Avoid duplicates
      $exists = $this->db->get_where('corpus_fund_assignment', ['fund_id' => $fund_id, 'hof_id' => $hof_id])->row_array();
      if (!$exists) {
        $batch[] = [
          'fund_id' => $fund_id,
          'hof_id' => $hof_id,
          'amount_assigned' => $amount,
          'created_at' => date('Y-m-d H:i:s')
        ];
      }
    }
    if (!empty($batch)) {
      $this->db->insert_batch('corpus_fund_assignment', $batch);
    }
    return count($batch);
  }

  public function get_funds()
  {
    return $this->db->order_by('id', 'DESC')->get('corpus_fund')->result_array();
  }

  public function get_assignments($fund_id)
  {
    $this->db->where('fund_id', $fund_id);
    return $this->db->get('corpus_fund_assignment')->result_array();
  }

  public function get_active_hofs()
  {
    $sql = "SELECT ITS_ID, Full_Name, Sector, Sub_Sector, HOF_ID
        FROM user
        WHERE HOF_FM_TYPE = 'HOF'
          AND (Inactive_Status IS NULL OR Inactive_Status = 0)
          AND (Sector IS NOT NULL AND TRIM(Sector) <> '')
          AND (Sub_Sector IS NOT NULL AND TRIM(Sub_Sector) <> '')
        ORDER BY Sector, Sub_Sector, Full_Name";
    return $this->db->query($sql)->result_array();
  }

  public function get_active_hofs_with_totals()
  {
    $sql = "SELECT u.ITS_ID, u.Full_Name, u.Sector, u.Sub_Sector, u.HOF_ID,
              COALESCE(SUM(a.amount_assigned),0) AS corpus_total,
              COUNT(a.id) AS corpus_count
            FROM user u
            LEFT JOIN corpus_fund_assignment a ON a.hof_id = u.HOF_ID
            WHERE u.HOF_FM_TYPE = 'HOF'
              AND (u.Inactive_Status IS NULL OR u.Inactive_Status = 0)
              AND (u.Sector IS NOT NULL AND TRIM(u.Sector) <> '')
              AND (u.Sub_Sector IS NOT NULL AND TRIM(u.Sub_Sector) <> '')
            GROUP BY u.HOF_ID, u.ITS_ID, u.Full_Name, u.Sector, u.Sub_Sector
            ORDER BY u.Sector, u.Sub_Sector, u.Full_Name";
    return $this->db->query($sql)->result_array();
  }

  public function get_all_hof_fund_details()
  {
    $sql = "SELECT a.hof_id, f.id AS fund_id, f.title, a.amount_assigned, a.created_at
            FROM corpus_fund_assignment a
            INNER JOIN corpus_fund f ON f.id = a.fund_id
            ORDER BY a.hof_id, f.id";
    $rows = $this->db->query($sql)->result_array();
    $map = [];
    foreach ($rows as $r) {
      $hid = (int)$r['hof_id'];
      if (!isset($map[$hid])) { $map[$hid] = []; }
      $map[$hid][] = [
        'fund_id' => (int)$r['fund_id'],
        'title' => $r['title'],
        'amount' => $r['amount_assigned'],
        'created_at' => $r['created_at']
      ];
    }
    return $map;
  }

  public function update_hof_fund_assignments($hof_id, $assignments)
  {
    // $assignments expected array of ['fund_id' => new_amount,...]
    if (!is_array($assignments) || empty($assignments)) { return ['updated'=>0,'errors'=>['Empty assignments']]; }
    $updated = 0; $errors = [];
    foreach ($assignments as $fund_id => $amount) {
      $fund_id = (int)$fund_id; $amount = (float)$amount;
      if ($fund_id <= 0) { $errors[] = 'Invalid fund id '.$fund_id; continue; }
      if ($amount < 0) { $errors[] = 'Negative amount for fund '.$fund_id; continue; }
      $this->db->where('hof_id', $hof_id)->where('fund_id', $fund_id)->update('corpus_fund_assignment', ['amount_assigned'=>$amount]);
      $err = $this->db->error();
      if ($err['code']) {
        $errors[] = 'DB error fund '.$fund_id.': '.$err['message'];
        continue;
      }
      if ($this->db->affected_rows() > 0) { $updated++; }
    }
    // Calculate new total after updates
    $totalRow = $this->db->select('COALESCE(SUM(amount_assigned),0) AS total')
      ->from('corpus_fund_assignment')
      ->where('hof_id', $hof_id)
      ->get()->row_array();
    $newTotal = isset($totalRow['total']) ? (float)$totalRow['total'] : 0.0;
    return ['updated'=>$updated,'errors'=>$errors,'new_total'=>$newTotal];
  }

  public function delete_hof_fund_assignment($hof_id, $fund_id)
  {
    $hof_id = (int)$hof_id; $fund_id = (int)$fund_id;
    if ($hof_id <= 0 || $fund_id <= 0) { return ['success'=>false,'error'=>'Invalid identifiers']; }
    $this->db->where('hof_id', $hof_id)->where('fund_id', $fund_id)->delete('corpus_fund_assignment');
    if ($this->db->error()['code']) {
      return ['success'=>false,'error'=>$this->db->error()['message']];
    }
    return ['success'=> ($this->db->affected_rows() > 0), 'deleted'=>$this->db->affected_rows()];
  }

  public function update_fund($fund_id, $new_amount, $title = null, $description = null, $propagate = true)
  {
    $fund_id = (int)$fund_id; $new_amount = (float)$new_amount;
    if ($fund_id <= 0) return ['success'=>false,'error'=>'Invalid fund id'];
    if ($new_amount < 0) return ['success'=>false,'error'=>'Negative amount'];
    $updateData = ['amount' => $new_amount];
    if ($title !== null && trim($title) !== '') { $updateData['title'] = trim($title); }
    if ($description !== null) { $updateData['description'] = $description; }
    $this->db->where('id', $fund_id)->update('corpus_fund', $updateData);
    $err = $this->db->error();
    if ($err['code']) return ['success'=>false,'error'=>$err['message']];
    $affectedFund = $this->db->affected_rows() > 0;
    $affectedAssignments = 0;
    if ($propagate) {
      $this->db->where('fund_id', $fund_id)->update('corpus_fund_assignment', ['amount_assigned' => $new_amount]);
      $err2 = $this->db->error();
      if ($err2['code']) return ['success'=>false,'error'=>'Assignments update failed: '.$err2['message']];
      $affectedAssignments = $this->db->affected_rows();
    }
    return [
      'success' => true,
      'fund_updated' => $affectedFund,
      'assignments_updated' => $affectedAssignments
    ];
  }

  public function delete_fund($fund_id)
  {
    $fund_id = (int)$fund_id;
    if ($fund_id <= 0) return ['success'=>false,'error'=>'Invalid fund id'];
    // Check existence early
    $exists = $this->db->get_where('corpus_fund', ['id'=>$fund_id])->row_array();
    if (!$exists) {
      return ['success'=>false,'error'=>'Fund not found'];
    }
    $this->db->trans_begin();
    $assignments_count = $this->db->where('fund_id', $fund_id)->count_all_results('corpus_fund_assignment');
    $this->db->where('fund_id', $fund_id)->delete('corpus_fund_assignment');
    $this->db->where('id', $fund_id)->delete('corpus_fund');
    // Capture affected rows BEFORE commit (commit may reset it)
    $fund_deleted_rows = $this->db->affected_rows();
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return ['success'=>false,'error'=>'Database transaction failed'];
    }
    $this->db->trans_commit();
    $fund_deleted = $fund_deleted_rows > 0;
    return [
      'success' => $fund_deleted,
      'fund_deleted' => $fund_deleted ? 1 : 0,
      'assignments_deleted' => $assignments_count
    ];
  }

  /* ===================== Payments ===================== */
  public function record_payment($fund_id, $hof_id, $amount, $received_by = null, $notes = null, $paid_at = null, $payment_method = null)
  {
    $this->ensure_payment_table();
    $fund_id = (int)$fund_id; $hof_id = (int)$hof_id; $amount = (float)$amount;
    if ($fund_id <= 0 || $hof_id <= 0) { return ['success'=>false,'error'=>'Invalid fund or HOF']; }
    if ($amount <= 0) { return ['success'=>false,'error'=>'Amount must be positive']; }
    // Ensure assignment exists
    $assign = $this->db->get_where('corpus_fund_assignment', ['fund_id'=>$fund_id,'hof_id'=>$hof_id])->row_array();
    if (!$assign) { return ['success'=>false,'error'=>'Assignment not found']; }
    // Normalize paid_at
    if ($paid_at) {
      $ts = strtotime($paid_at);
      $paid_at = $ts ? date('Y-m-d H:i:s', $ts) : date('Y-m-d H:i:s');
    } else {
      $paid_at = date('Y-m-d H:i:s');
    }
    // Normalize payment method
    $payment_method = $payment_method !== null ? trim($payment_method) : null;
    if ($payment_method !== null && $payment_method !== '') {
      $payment_method = substr($payment_method, 0, 50);
    } else {
      $payment_method = null;
    }
    $data = [
      'fund_id' => $fund_id,
      'hof_id' => $hof_id,
      'amount_paid' => $amount,
      'notes' => $notes,
      'received_by' => $received_by,
      'paid_at' => $paid_at,
      'payment_method' => $payment_method
    ];
    $ok = $this->db->insert('corpus_fund_payment', $data);
    if (!$ok) {
      $err = $this->db->error();
      return ['success'=>false,'error'=>$err['message'] ?? 'Insert failed'];
    }
    return ['success'=>true,'id'=>$this->db->insert_id()];
  }

  public function get_all_assignments_with_payments()
  {
    // Aggregated payments per assignment
    $this->ensure_payment_table();
    $sql = "SELECT a.id AS assignment_id, a.fund_id, f.title, a.hof_id,
              u.Full_Name AS hof_name, u.Sector AS sector, u.Sub_Sector AS sub_sector, a.amount_assigned,
              COALESCE(SUM(p.amount_paid),0) AS amount_paid,
              (a.amount_assigned - COALESCE(SUM(p.amount_paid),0)) AS amount_due
            FROM corpus_fund_assignment a
            INNER JOIN corpus_fund f ON f.id = a.fund_id
            LEFT JOIN corpus_fund_payment p ON p.fund_id = a.fund_id AND p.hof_id = a.hof_id
            LEFT JOIN user u ON u.HOF_ID = a.hof_id AND u.HOF_FM_TYPE = 'HOF'
            GROUP BY a.id, a.fund_id, f.title, a.hof_id, u.Full_Name, u.Sector, u.Sub_Sector, a.amount_assigned
            ORDER BY u.Sector, u.Sub_Sector, u.Full_Name, a.hof_id, a.fund_id";
    return $this->db->query($sql)->result_array();
  }

  public function get_payments_for_assignment($fund_id, $hof_id)
  {
    $this->ensure_payment_table();
    $fund_id = (int)$fund_id; $hof_id = (int)$hof_id;
    if ($fund_id <=0 || $hof_id <=0) { return []; }
    return $this->db->order_by('paid_at','DESC')
      ->get_where('corpus_fund_payment', ['fund_id'=>$fund_id,'hof_id'=>$hof_id])
      ->result_array();
  }

  public function get_payment_detail($payment_id)
  {
    $this->ensure_payment_table();
    $pid = (int)$payment_id;
    if ($pid <= 0) return null;
    $sql = "SELECT p.*, f.title AS fund_title, u.Full_Name AS hof_name
            FROM corpus_fund_payment p
            LEFT JOIN corpus_fund f ON f.id = p.fund_id
            LEFT JOIN user u ON u.HOF_ID = p.hof_id AND u.HOF_FM_TYPE = 'HOF'
            WHERE p.id = ?";
    return $this->db->query($sql, [$pid])->row_array();
  }

  public function delete_payment($payment_id)
  {
    $pid = (int)$payment_id;
    if ($pid <= 0) return ['success'=>false,'error'=>'Invalid payment id'];
    $this->db->where('id', $pid)->delete('corpus_fund_payment');
    if ($this->db->error()['code']) {
      return ['success'=>false,'error'=>$this->db->error()['message']];
    }
    return ['success' => ($this->db->affected_rows() > 0)];
  }

  public function get_assignments_with_payments_by_hof($hof_id)
  {
    $this->ensure_payment_table();
    $hof_id = (int)$hof_id;
    if ($hof_id <= 0) { return []; }
    $sql = "SELECT a.id AS assignment_id, a.fund_id, f.title, a.hof_id,
              u.Full_Name AS hof_name, a.amount_assigned,
              COALESCE(SUM(p.amount_paid),0) AS amount_paid,
              (a.amount_assigned - COALESCE(SUM(p.amount_paid),0)) AS amount_due
            FROM corpus_fund_assignment a
            INNER JOIN corpus_fund f ON f.id = a.fund_id
            LEFT JOIN corpus_fund_payment p ON p.fund_id = a.fund_id AND p.hof_id = a.hof_id
            LEFT JOIN user u ON u.HOF_ID = a.hof_id AND u.HOF_FM_TYPE = 'HOF'
            WHERE a.hof_id = ?
            GROUP BY a.id, a.fund_id, f.title, a.hof_id, u.Full_Name, a.amount_assigned
            ORDER BY f.title";
    return $this->db->query($sql, [$hof_id])->result_array();
  }

  public function get_due_for_assignment($fund_id, $hof_id)
  {
    $this->ensure_payment_table();
    $fund_id = (int)$fund_id; $hof_id = (int)$hof_id;
    if ($fund_id <= 0 || $hof_id <= 0) { return 0.0; }
    $row = $this->db->query(
      "SELECT a.amount_assigned - COALESCE(SUM(p.amount_paid),0) AS amount_due
       FROM corpus_fund_assignment a
       LEFT JOIN corpus_fund_payment p ON p.fund_id = a.fund_id AND p.hof_id = a.hof_id
       WHERE a.fund_id = ? AND a.hof_id = ?
       GROUP BY a.amount_assigned",
       [$fund_id, $hof_id]
    )->row_array();
    $due = isset($row['amount_due']) ? (float)$row['amount_due'] : 0.0;
    return $due < 0 ? 0.0 : $due;
  }
}
