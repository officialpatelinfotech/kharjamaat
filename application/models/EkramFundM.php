<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EkramFundM extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    private function ensure_payment_table()
    {
        if (!$this->db->table_exists('ekram_fund_payment')) {
            $this->db->query("CREATE TABLE `ekram_fund_payment` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `fund_id` INT UNSIGNED NOT NULL,
                `hof_id` INT NOT NULL,
                `amount_paid` DECIMAL(10,2) NOT NULL DEFAULT 0,
                `notes` VARCHAR(255) NULL,
                `payment_method` VARCHAR(50) NULL,
                `received_by` INT NULL,
                `paid_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `idx_fund_hof` (`fund_id`,`hof_id`),
                CONSTRAINT `fk_ekram_payment_fund` FOREIGN KEY (`fund_id`) REFERENCES `ekram_fund`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            return;
        }
        $fields = $this->db->list_fields('ekram_fund_payment');
        if (!in_array('amount_paid', $fields)) {
            if (in_array('amount', $fields)) {
                $this->db->query("ALTER TABLE `ekram_fund_payment` ADD COLUMN `amount_paid` DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER `hof_id`");
                $this->db->query("UPDATE `ekram_fund_payment` SET amount_paid = amount");
            } else {
                $this->db->query("ALTER TABLE `ekram_fund_payment` ADD COLUMN `amount_paid` DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER `hof_id`");
            }
        }
    }

    public function create_fund($title, $amount, $description, $created_by, $hijri_year = null)
    {
        if (!$this->db->table_exists('ekram_fund')) {
            log_message('error', 'ekram_fund table missing. Creating fallback table.');
            $this->db->query("CREATE TABLE `ekram_fund` (
                                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                                `title` VARCHAR(150) NOT NULL,
                                `amount` DECIMAL(10,2) NOT NULL DEFAULT 0,
                                `hijri_year` INT DEFAULT NULL,
                                `description` TEXT NULL,
                                `created_by` INT NULL,
                                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }
        if (!$this->db->table_exists('ekram_fund_assignment')) {
            log_message('error', 'ekram_fund_assignment table missing. Creating fallback table.');
            $this->db->query("CREATE TABLE `ekram_fund_assignment` (
                                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                                `fund_id` INT UNSIGNED NOT NULL,
                                `hof_id` INT NOT NULL,
                                `amount_assigned` DECIMAL(10,2) NOT NULL DEFAULT 0,
                                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                PRIMARY KEY (`id`),
                                KEY `idx_fund_id` (`fund_id`),
                                KEY `idx_hof_id` (`hof_id`),
                                CONSTRAINT `fk_ekram_fund_assignment_fund` FOREIGN KEY (`fund_id`) REFERENCES `ekram_fund`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }
        $data = [
            'title' => $title,
            'amount' => $amount,
            'description' => $description,
            'created_by' => $created_by,
            'created_at' => date('Y-m-d H:i:s')
        ];
        if ($hijri_year !== null) $data['hijri_year'] = (int)$hijri_year;
        $ok = $this->db->insert('ekram_fund', $data);
        if (!$ok) {
            $err = $this->db->error();
            log_message('error', 'Failed to insert ekram fund: ' . $this->db->last_query() . ' DB Error: ' . json_encode($err));
            return ['success' => false, 'error' => $err];
        }
        return ['success' => true, 'id' => $this->db->insert_id()];
    }

    public function assign_to_all_hofs($fund_id, $amount)
    {
        $hofs = $this->db->query(
            "SELECT DISTINCT HOF_ID FROM user 
            WHERE HOF_ID IS NOT NULL AND HOF_ID <> 0
                AND (Inactive_Status IS NULL OR Inactive_Status = 0)
                AND (Sector IS NOT NULL AND TRIM(Sector) <> '')
                AND (Sub_Sector IS NOT NULL AND TRIM(Sub_Sector) <> '')
                AND HOF_FM_TYPE = 'HOF'"
        )->result_array();
        if (empty($hofs)) return 0;
        $batch = [];
        foreach ($hofs as $h) {
            $hof_id = (int)$h['HOF_ID'];
            $exists = $this->db->get_where('ekram_fund_assignment', ['fund_id' => $fund_id, 'hof_id' => $hof_id])->row_array();
            if (!$exists) {
                $batch[] = ['fund_id' => $fund_id, 'hof_id' => $hof_id, 'amount_assigned' => $amount, 'created_at' => date('Y-m-d H:i:s')];
            }
        }
        if (!empty($batch)) $this->db->insert_batch('ekram_fund_assignment', $batch);
        return count($batch);
    }

    public function get_funds()
    {
        return $this->db->order_by('id', 'DESC')->get('ekram_fund')->result_array();
    }

    public function get_assignments($fund_id)
    {
        $this->db->where('fund_id', $fund_id);
        return $this->db->get('ekram_fund_assignment')->result_array();
    }

    public function get_all_assignments_with_payments($filters = [])
    {
        $this->ensure_payment_table();
        $params = [];
        $sql = "SELECT a.id AS assignment_id, a.fund_id, f.title, f.hijri_year, a.hof_id,
                            u.ITS_ID AS its_id, u.Full_Name AS hof_name, u.Sector AS sector, u.Sub_Sector AS sub_sector, a.amount_assigned,
                            COALESCE(SUM(p.amount_paid),0) AS amount_paid,
                            (a.amount_assigned - COALESCE(SUM(p.amount_paid),0)) AS amount_due
                        FROM ekram_fund_assignment a
                        INNER JOIN ekram_fund f ON f.id = a.fund_id
                        LEFT JOIN ekram_fund_payment p ON p.fund_id = a.fund_id AND p.hof_id = a.hof_id
                        LEFT JOIN user u ON u.HOF_ID = a.hof_id AND u.HOF_FM_TYPE = 'HOF'
                        WHERE 1=1";
        if (!empty($filters['its_id'])) {
            $sql .= " AND (u.ITS_ID = ? OR a.hof_id = ? )";
            $params[] = $filters['its_id'];
            $params[] = (int)$filters['its_id'];
        }
        if (!empty($filters['sector'])) { $sql .= " AND u.Sector = ?"; $params[] = $filters['sector']; }
        if (!empty($filters['sub_sector'])) { $sql .= " AND u.Sub_Sector = ?"; $params[] = $filters['sub_sector']; }
        if (!empty($filters['fund_id'])) { $sql .= " AND a.fund_id = ?"; $params[] = (int)$filters['fund_id']; }
        if (!empty($filters['hijri_year'])) { $sql .= " AND f.hijri_year = ?"; $params[] = (int)$filters['hijri_year']; }
        $sql .= " GROUP BY a.id, a.fund_id, f.title, f.hijri_year, a.hof_id, u.ITS_ID, u.Full_Name, u.Sector, u.Sub_Sector, a.amount_assigned
                        ORDER BY u.Sector, u.Sub_Sector, u.Full_Name, a.hof_id, a.fund_id";
        if (empty($params)) return $this->db->query($sql)->result_array();
        return $this->db->query($sql, $params)->result_array();
    }

    public function get_payments_for_assignment($fund_id, $hof_id)
    {
        $this->ensure_payment_table();
        $fund_id = (int)$fund_id; $hof_id = (int)$hof_id;
        if ($fund_id <=0 || $hof_id <=0) return [];
        return $this->db->order_by('paid_at','DESC')->get_where('ekram_fund_payment', ['fund_id'=>$fund_id,'hof_id'=>$hof_id])->result_array();
    }

    public function record_payment($fund_id, $hof_id, $amount, $received_by = null, $notes = null, $paid_at = null, $payment_method = null)
    {
        $this->ensure_payment_table();
        $fund_id = (int)$fund_id; $hof_id = (int)$hof_id; $amount = (float)$amount;
        if ($fund_id <= 0 || $hof_id <= 0) { return ['success'=>false,'error'=>'Invalid fund or HOF']; }
        if ($amount <= 0) { return ['success'=>false,'error'=>'Amount must be positive']; }
        // Ensure assignment exists
        $assign = $this->db->get_where('ekram_fund_assignment', ['fund_id'=>$fund_id,'hof_id'=>$hof_id])->row_array();
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
        $ok = $this->db->insert('ekram_fund_payment', $data);
        if (!$ok) {
            $err = $this->db->error();
            return ['success'=>false,'error'=>$err['message'] ?? 'Insert failed'];
        }
        return ['success'=>true,'id'=>$this->db->insert_id()];
    }

    public function get_payment_detail($payment_id)
    {
        $this->ensure_payment_table();
        $pid = (int)$payment_id; if ($pid <= 0) return null;
        $sql = "SELECT p.*, f.title AS fund_title, f.hijri_year AS fund_year, u.Full_Name AS hof_name
                        FROM ekram_fund_payment p
                        LEFT JOIN ekram_fund f ON f.id = p.fund_id
                        LEFT JOIN user u ON u.HOF_ID = p.hof_id AND u.HOF_FM_TYPE = 'HOF'
                        WHERE p.id = ?";
        return $this->db->query($sql, [$pid])->row_array();
    }

    public function delete_payment($payment_id)
    {
        $pid = (int)$payment_id; if ($pid <= 0) return ['success'=>false,'error'=>'Invalid payment id'];
        $this->db->where('id', $pid)->delete('ekram_fund_payment');
        if ($this->db->error()['code']) return ['success'=>false,'error'=>$this->db->error()['message']];
        return ['success' => ($this->db->affected_rows() > 0)];
    }

    public function get_assignments_with_payments_by_hof($hof_id)
    {
        $this->ensure_payment_table();
        $hof_id = (int)$hof_id; if ($hof_id <= 0) return [];
        $sql = "SELECT a.id AS assignment_id, a.fund_id, f.title, f.hijri_year, a.hof_id,
                            u.Full_Name AS hof_name, a.amount_assigned,
                            COALESCE(SUM(p.amount_paid),0) AS amount_paid,
                            (a.amount_assigned - COALESCE(SUM(p.amount_paid),0)) AS amount_due
                        FROM ekram_fund_assignment a
                        INNER JOIN ekram_fund f ON f.id = a.fund_id
                        LEFT JOIN ekram_fund_payment p ON p.fund_id = a.fund_id AND p.hof_id = a.hof_id
                        LEFT JOIN user u ON u.HOF_ID = a.hof_id AND u.HOF_FM_TYPE = 'HOF'
                        WHERE a.hof_id = ?
                        GROUP BY a.id, a.fund_id, f.title, f.hijri_year, a.hof_id, u.Full_Name, a.amount_assigned
                        ORDER BY f.hijri_year, f.title";
        return $this->db->query($sql, [$hof_id])->result_array();
    }

    public function get_due_for_assignment($fund_id, $hof_id)
    {
        $this->ensure_payment_table();
        $fund_id = (int)$fund_id; $hof_id = (int)$hof_id; if ($fund_id <= 0 || $hof_id <= 0) return 0.0;
        $row = $this->db->query(
            "SELECT a.amount_assigned - COALESCE(SUM(p.amount_paid),0) AS amount_due
             FROM ekram_fund_assignment a
             LEFT JOIN ekram_fund_payment p ON p.fund_id = a.fund_id AND p.hof_id = a.hof_id
             WHERE a.fund_id = ? AND a.hof_id = ?
             GROUP BY a.amount_assigned",
             [$fund_id, $hof_id]
        )->row_array();
        return isset($row['amount_due']) ? (float)$row['amount_due'] : 0.0;
    }
}
