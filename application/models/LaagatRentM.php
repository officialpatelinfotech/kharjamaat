<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class LaagatRentM extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all($filters = [])
    {
        $filters = is_array($filters) ? $filters : [];
        $title = trim((string)($filters['title'] ?? ''));
        $hijriYear = trim((string)($filters['hijri_year'] ?? ''));
        $chargeType = strtolower(trim((string)($filters['charge_type'] ?? '')));
        $razaCategory = trim((string)($filters['raza_category'] ?? ''));

        $this->db->from('laagat_rent lr');

        if ($this->db->table_exists('laagat_rent_raza_type_map')) {
            $this->db->select("lr.*, COALESCE(GROUP_CONCAT(DISTINCT rt_map.name ORDER BY rt_map.name SEPARATOR ', '), rt_old.name) AS raza_type_name");
            $this->db->join('laagat_rent_raza_type_map lrrtm', 'lrrtm.laagat_rent_id = lr.id', 'left');
            $this->db->join('raza_type rt_map', 'rt_map.id = lrrtm.raza_type_id', 'left');
            $this->db->join('raza_type rt_old', 'rt_old.id = lr.raza_type_id', 'left');
            $this->db->group_by('lr.id');
        } else {
            $this->db->select('lr.*, rt.name AS raza_type_name, rt.umoor AS raza_type_umoor');
            $this->db->join('raza_type rt', 'rt.id = lr.raza_type_id', 'left');
        }

        if ($title !== '') {
            $this->db->like('lr.title', $title);
        }
        if ($hijriYear !== '') {
            $this->db->where('lr.hijri_year', $hijriYear);
        }
        if (in_array($chargeType, ['laagat', 'rent'], true)) {
            $this->db->where('lr.charge_type', $chargeType);
        }
        if ($razaCategory !== '') {
            $like = $this->db->escape_like_str($razaCategory);
            if ($this->db->table_exists('laagat_rent_raza_type_map')) {
                $this->db->where("(rt_map.name LIKE '%{$like}%' OR rt_old.name LIKE '%{$like}%')", null, false);
            } else {
                $this->db->like('rt.name', $razaCategory);
            }
        }

        $this->db->order_by('lr.hijri_year DESC');
        $this->db->order_by('lr.charge_type ASC');
        $this->db->order_by('lr.id DESC');
        return $this->db->get()->result_array();
    }

    public function get_by_id($id)
    {
        $id = (int)$id;
        if ($id <= 0) return null;

        $this->db->from('laagat_rent lr');

        if ($this->db->table_exists('laagat_rent_raza_type_map')) {
            $this->db->select("lr.*, COALESCE(GROUP_CONCAT(DISTINCT rt_map.name ORDER BY rt_map.name SEPARATOR ', '), rt_old.name) AS raza_type_name");
            $this->db->join('laagat_rent_raza_type_map lrrtm', 'lrrtm.laagat_rent_id = lr.id', 'left');
            $this->db->join('raza_type rt_map', 'rt_map.id = lrrtm.raza_type_id', 'left');
            $this->db->join('raza_type rt_old', 'rt_old.id = lr.raza_type_id', 'left');
            $this->db->group_by('lr.id');
        } else {
            $this->db->select('lr.*, rt.name AS raza_type_name, rt.umoor AS raza_type_umoor');
            $this->db->join('raza_type rt', 'rt.id = lr.raza_type_id', 'left');
        }

        $this->db->where('lr.id', $id);
        $row = $this->db->get()->row_array();
        return $row ? $row : null;
    }

    public function get_raza_types_for_record($laagatRentId)
    {
        $laagatRentId = (int)$laagatRentId;
        if ($laagatRentId <= 0) return [];

        // Preferred path: mapping table
        if ($this->db->table_exists('laagat_rent_raza_type_map')) {
            $rows = $this->db
                ->select('rt.id, rt.name')
                ->from('laagat_rent_raza_type_map m')
                ->join('raza_type rt', 'rt.id = m.raza_type_id', 'inner')
                ->where('m.laagat_rent_id', $laagatRentId)
                ->order_by('rt.name ASC')
                ->get()
                ->result_array();

            $out = [];
            foreach ($rows as $r) {
                $out[] = ['id' => (int)$r['id'], 'name' => (string)$r['name']];
            }
            if (!empty($out)) {
                return $out;
            }
        }

        // Fallback: single column on laagat_rent
        $row = $this->db
            ->select('lr.raza_type_id, rt.name')
            ->from('laagat_rent lr')
            ->join('raza_type rt', 'rt.id = lr.raza_type_id', 'left')
            ->where('lr.id', $laagatRentId)
            ->get()
            ->row_array();

        if ($row && (int)$row['raza_type_id'] > 0) {
            return [['id' => (int)$row['raza_type_id'], 'name' => (string)($row['name'] ?? '')]];
        }

        return [];
    }

    private function get_raza_type_ids_for_record($laagatRentId)
    {
        $laagatRentId = (int)$laagatRentId;
        if ($laagatRentId <= 0) return [];

        $ids = [];

        if ($this->db->table_exists('laagat_rent_raza_type_map')) {
            $rows = $this->db
                ->select('raza_type_id')
                ->from('laagat_rent_raza_type_map')
                ->where('laagat_rent_id', $laagatRentId)
                ->get()
                ->result_array();

            foreach ($rows as $r) {
                $rid = (int)($r['raza_type_id'] ?? 0);
                if ($rid > 0) {
                    $ids[$rid] = $rid;
                }
            }
        }

        if (empty($ids)) {
            $row = $this->db
                ->select('raza_type_id')
                ->from('laagat_rent')
                ->where('id', $laagatRentId)
                ->get()
                ->row_array();
            $rid = (int)($row['raza_type_id'] ?? 0);
            if ($rid > 0) {
                $ids[$rid] = $rid;
            }
        }

        $out = array_values($ids);
        sort($out);
        return $out;
    }

    private function normalize_raza_type_ids($razaTypeIds)
    {
        $ids = [];
        if (is_array($razaTypeIds)) {
            foreach ($razaTypeIds as $rid) {
                $rid = (int)$rid;
                if ($rid > 0) {
                    $ids[$rid] = $rid;
                }
            }
        }
        $out = array_values($ids);
        sort($out);
        return $out;
    }

    private function normalize_title($title)
    {
        $title = trim((string)$title);
        // Collapse whitespace and compare case-insensitively
        $title = preg_replace('/\s+/', ' ', $title);
        return strtolower((string)$title);
    }

    private function has_any_common_raza_type($aIds, $bIds)
    {
        $a = $this->normalize_raza_type_ids($aIds);
        $b = $this->normalize_raza_type_ids($bIds);
        if (empty($a) || empty($b)) return false;

        // Two-pointer intersection check (arrays are sorted)
        $i = 0;
        $j = 0;
        $na = count($a);
        $nb = count($b);
        while ($i < $na && $j < $nb) {
            if ($a[$i] === $b[$j]) {
                return true;
            }
            if ($a[$i] < $b[$j]) {
                $i++;
            } else {
                $j++;
            }
        }
        return false;
    }

    private function same_raza_type_set($aIds, $bIds)
    {
        $a = $this->normalize_raza_type_ids($aIds);
        $b = $this->normalize_raza_type_ids($bIds);
        if (count($a) !== count($b)) return false;
        $n = count($a);
        for ($i = 0; $i < $n; $i++) {
            if ($a[$i] !== $b[$i]) return false;
        }
        return true;
    }

    private function find_duplicate_id($hijriYear, $chargeType, $title, $razaTypeIds, $excludeId = 0)
    {
        $hijriYear = trim((string)$hijriYear);
        $chargeType = strtolower(trim((string)$chargeType));
        $excludeId = (int)$excludeId;

        // Duplicate definition:
        // Consider it duplicate when ALL of these match:
        //  - Charge Type
        //  - Any Applicable Raza Category overlaps (across ANY Hijri Year)
        // Title is NOT part of uniqueness.
        $needleIds = $this->normalize_raza_type_ids($razaTypeIds);

        // Normalize stored values on-the-fly to avoid duplicates slipping through due to
        // legacy data inconsistencies (extra spaces / casing differences).
        $q = $this->db->select('id')->from('laagat_rent');
        $q->where('LOWER(TRIM(charge_type)) = ' . $this->db->escape($chargeType), null, false);
        if ($excludeId > 0) $q->where('id !=', $excludeId);
        $rows = $q->get()->result_array();

        foreach ($rows as $r) {
            $rid = (int)($r['id'] ?? 0);
            if ($rid <= 0) continue;
            $existingIds = $this->get_raza_type_ids_for_record($rid);
            if ($this->has_any_common_raza_type($existingIds, $needleIds)) {
                return $rid;
            }
        }

        return 0;
    }

    private function duplicate_error_message($chargeType)
    {
        $chargeType = strtolower(trim((string)$chargeType));
        if ($chargeType === 'laagat') {
            return 'Raza already exist. You can\'t create same Raza.';
        }
        if ($chargeType === 'rent') {
            return 'Raza already exist. You can\'t create same Raza.';
        }
        return 'Raza already exist. You can\'t create same Raza.';
    }

    public function check_duplicate_overlap($hijriYear, $chargeType, $razaTypeIds, $excludeId = 0)
    {
        return (int)$this->find_duplicate_id($hijriYear, $chargeType, '', $razaTypeIds, $excludeId);
    }

    public function create($payload)
    {
        $razaTypeIds = [];
        if (isset($payload['raza_type_ids']) && is_array($payload['raza_type_ids'])) {
            foreach ($payload['raza_type_ids'] as $rid) {
                $rid = (int)$rid;
                if ($rid > 0) {
                    $razaTypeIds[$rid] = $rid;
                }
            }
        }
        $razaTypeIds = array_values($razaTypeIds);
        if (empty($razaTypeIds)) {
            $single = (int)($payload['raza_type_id'] ?? 0);
            if ($single > 0) {
                $razaTypeIds = [$single];
            }
        }

        if (count($razaTypeIds) > 1 && !$this->db->table_exists('laagat_rent_raza_type_map')) {
            return ['success' => false, 'error' => 'Database migration pending for multiple Applicable Raza Categories. Please run migrations and try again.'];
        }

        $dupId = $this->find_duplicate_id((string)($payload['hijri_year'] ?? ''), (string)($payload['charge_type'] ?? ''), (string)($payload['title'] ?? ''), $razaTypeIds, 0);
        if ($dupId > 0) {
            return ['success' => false, 'error' => $this->duplicate_error_message((string)($payload['charge_type'] ?? '')), 'existing_id' => $dupId];
        }

        $data = [
            'title' => (string)($payload['title'] ?? ''),
            'hijri_year' => (string)($payload['hijri_year'] ?? ''),
            'charge_type' => (string)($payload['charge_type'] ?? ''),
            'amount' => (float)($payload['amount'] ?? 0),
            // Keep legacy single column populated with first selected id.
            'raza_type_id' => !empty($razaTypeIds) ? (int)$razaTypeIds[0] : (int)($payload['raza_type_id'] ?? 0),
            // Do not auto-activate on create; activation should be explicit from Manage screen.
            'is_active' => isset($payload['is_active']) ? ((int)$payload['is_active'] ? 1 : 0) : 1,
        ];

        $this->db->trans_start();

        $this->db->insert('laagat_rent', $data);
        $insertId = (int)$this->db->insert_id();

        if ($insertId > 0 && $this->db->table_exists('laagat_rent_raza_type_map')) {
            foreach ($razaTypeIds as $rid) {
                $this->db->insert('laagat_rent_raza_type_map', [
                    'laagat_rent_id' => $insertId,
                    'raza_type_id' => (int)$rid,
                ]);
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE || $insertId <= 0) {
            return ['success' => false, 'error' => 'Unable to create record'];
        }

        return ['success' => true, 'id' => $insertId];
    }

    public function update($id, $payload)
    {
        $id = (int)$id;
        if ($id <= 0) return ['success' => false, 'error' => 'Invalid ID'];

        $razaTypeIds = [];
        if (isset($payload['raza_type_ids']) && is_array($payload['raza_type_ids'])) {
            foreach ($payload['raza_type_ids'] as $rid) {
                $rid = (int)$rid;
                if ($rid > 0) {
                    $razaTypeIds[$rid] = $rid;
                }
            }
        }
        $razaTypeIds = array_values($razaTypeIds);
        if (empty($razaTypeIds)) {
            $single = (int)($payload['raza_type_id'] ?? 0);
            if ($single > 0) {
                $razaTypeIds = [$single];
            }
        }

        if (count($razaTypeIds) > 1 && !$this->db->table_exists('laagat_rent_raza_type_map')) {
            return ['success' => false, 'error' => 'Database migration pending for multiple Applicable Raza Categories. Please run migrations and try again.'];
        }

        $dupId = $this->find_duplicate_id((string)($payload['hijri_year'] ?? ''), (string)($payload['charge_type'] ?? ''), (string)($payload['title'] ?? ''), $razaTypeIds, $id);
        if ($dupId > 0) {
            return ['success' => false, 'error' => $this->duplicate_error_message((string)($payload['charge_type'] ?? '')), 'existing_id' => $dupId];
        }

        $data = [
            'title' => (string)($payload['title'] ?? ''),
            'hijri_year' => (string)($payload['hijri_year'] ?? ''),
            'charge_type' => (string)($payload['charge_type'] ?? ''),
            'amount' => (float)($payload['amount'] ?? 0),
            // Keep legacy single column populated with first selected id.
            'raza_type_id' => !empty($razaTypeIds) ? (int)$razaTypeIds[0] : (int)($payload['raza_type_id'] ?? 0),
        ];

        $this->db->trans_start();

        $this->db->where('id', $id);
        $this->db->update('laagat_rent', $data);

        if ($this->db->table_exists('laagat_rent_raza_type_map')) {
            $this->db->where('laagat_rent_id', $id);
            $this->db->delete('laagat_rent_raza_type_map');
            foreach ($razaTypeIds as $rid) {
                $this->db->insert('laagat_rent_raza_type_map', [
                    'laagat_rent_id' => $id,
                    'raza_type_id' => (int)$rid,
                ]);
            }
        }

        // If this record is active, keep only one active per year + charge_type.
        $row = $this->db->select('hijri_year, charge_type, is_active')
            ->from('laagat_rent')
            ->where('id', $id)
            ->get()
            ->row_array();

        if ($row && (int)$row['is_active'] === 1) {
            $this->db->where('hijri_year', (string)$row['hijri_year']);
            $this->db->where('charge_type', (string)$row['charge_type']);
            $this->db->where('id !=', $id);
            $this->db->where('is_active', 1);
            $this->db->update('laagat_rent', ['is_active' => 0]);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return ['success' => false, 'error' => 'Unable to update record'];
        }
        return ['success' => true];
    }

    public function delete($id)
    {
        $id = (int)$id;
        if ($id <= 0) return false;
        $this->db->where('id', $id);
        $this->db->delete('laagat_rent');
        return $this->db->affected_rows() > 0;
    }

    public function has_invoices($id)
    {
        $id = (int)$id;
        if ($id <= 0) return false;
        $count = $this->db->where('laagat_rent_id', $id)->count_all_results('laagat_rent_invoices');
        return $count > 0;
    }

    public function toggle_active($id)
    {
        $id = (int)$id;
        if ($id <= 0) return ['success' => false, 'error' => 'Invalid ID'];

        $row = $this->db->select('is_active, hijri_year, charge_type')->from('laagat_rent')->where('id', $id)->get()->row_array();
        if (!$row) return ['success' => false, 'error' => 'Not found'];

        $newVal = ((int)$row['is_active'] === 1) ? 0 : 1;

        $this->db->trans_start();

        if ($newVal === 1) {
            // Deactivate other active records for the same Hijri Year + Charge Type.
            $this->db->where('hijri_year', (string)$row['hijri_year']);
            $this->db->where('charge_type', (string)$row['charge_type']);
            $this->db->where('id !=', $id);
            $this->db->where('is_active', 1);
            $this->db->update('laagat_rent', ['is_active' => 0]);
        }

        $this->db->where('id', $id);
        $this->db->update('laagat_rent', ['is_active' => $newVal]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return ['success' => false, 'error' => 'Unable to update'];
        }

        return ['success' => true, 'is_active' => $newVal, 'charge_type' => $row['charge_type']];
    }

    public function search_raza_categories($chargeType, $term)
    {
        $chargeType = strtolower(trim((string)$chargeType));
        $term = trim((string)$term);

        $this->db->select('id, name, umoor');
        $this->db->from('raza_type');
        $this->db->where('active', 1);

        if ($chargeType === 'rent') {
            $this->db->where('umoor', 'Private-Event');
        } else if ($chargeType === 'laagat') {
            $this->db->where_not_in('umoor', ['Public-Event', 'Private-Event']);
        }

        if ($term !== '') {
            $this->db->like('name', $term);
        }

        $this->db->order_by('name ASC');
        $this->db->limit(50);
        return $this->db->get()->result_array();
    }

    /**
     * Fetch the active laagat/rent row for a given raza_type_id.
     *
     * - Uses mapping table laagat_rent_raza_type_map when present.
     * - Falls back to legacy laagat_rent.raza_type_id otherwise.
     * - If $hijriYear is provided, filters to that year.
     * - When $fallbackToMaster is true, falls back to any active master for charge type.
     */
    public function get_active_for_raza_type($chargeType, $razaTypeId, $hijriYear = null, $fallbackToMaster = true)
    {
        $chargeType = strtolower(trim((string)$chargeType));
        $razaTypeId = (int)$razaTypeId;
        $hijriYear = $hijriYear !== null ? trim((string)$hijriYear) : null;
        $fallbackToMaster = (bool)$fallbackToMaster;

        if ($razaTypeId <= 0) return null;
        if (!in_array($chargeType, ['laagat', 'rent'], true)) return null;

        // 1) Try category-specific match
        $this->db->select('lr.id, lr.title, lr.hijri_year, lr.charge_type, lr.amount');
        $this->db->from('laagat_rent lr');
        $this->db->where('lr.charge_type', $chargeType);
        $this->db->where('lr.is_active', 1);

        if ($this->db->table_exists('laagat_rent_raza_type_map')) {
            $this->db->join('laagat_rent_raza_type_map m', 'm.laagat_rent_id = lr.id', 'inner');
            $this->db->where('m.raza_type_id', $razaTypeId);
        } else {
            $this->db->where('lr.raza_type_id', $razaTypeId);
        }

        if ($hijriYear !== null && $hijriYear !== '') {
            $this->db->where('lr.hijri_year', $hijriYear);
        }

        $this->db->order_by('lr.hijri_year DESC');
        $this->db->order_by('lr.id DESC');
        $this->db->limit(1);
        $row = $this->db->get()->row_array();
        if ($row) return $row;

        if (!$fallbackToMaster) return null;

        // 2) Fallback: any active master for this charge type (+year if provided)
        return $this->get_active_master($chargeType, $hijriYear);
    }

    /**
     * Fetch the active master row for a charge type, optionally limited to a Hijri year range.
     * This is used as a fallback when no category mapping exists.
     */
    public function get_active_master($chargeType, $hijriYear = null)
    {
        $chargeType = strtolower(trim((string)$chargeType));
        $hijriYear = $hijriYear !== null ? trim((string)$hijriYear) : null;

        if (!in_array($chargeType, ['laagat', 'rent'], true)) return null;

        $this->db->select('id, title, hijri_year, charge_type, amount');
        $this->db->from('laagat_rent');
        $this->db->where('charge_type', $chargeType);
        $this->db->where('is_active', 1);
        if ($hijriYear !== null && $hijriYear !== '') {
            $this->db->where('hijri_year', $hijriYear);
        }
        $this->db->order_by('hijri_year DESC');
        $this->db->order_by('id DESC');
        $this->db->limit(1);
        $row = $this->db->get()->row_array();
        return $row ? $row : null;
    }

    /* --- INVOICE AND PAYMENT METHODS --- */

    public function get_invoices($filters = [])
    {
        $this->db->select('i.*, u.Full_Name, u.ITS_ID, u.Sector, u.Sub_Sector, lr.title, lr.charge_type, lr.hijri_year, i.amount as master_amount, rr.raza_id as generated_raza_id, rt.name as raza_type_name');
        $this->db->from('laagat_rent_invoices i');
        $this->db->join('user u', 'u.ITS_ID = i.user_id', 'left');
        $this->db->join('laagat_rent lr', 'lr.id = i.laagat_rent_id', 'left');
        $this->db->join('raza rr', 'rr.id = i.raza_id', 'left');
        $this->db->join('raza_type rt', 'rt.id = rr.razaType', 'left');

        if (!empty($filters['year'])) {
            $this->db->where('lr.hijri_year', $filters['year']);
        }
        if (!empty($filters['its_id'])) {
            $this->db->where('i.user_id', $filters['its_id']);
        }
        if (!empty($filters['charge_type'])) {
            $this->db->where('lr.charge_type', $filters['charge_type']);
        }

        $this->db->select('COALESCE(SUM(p.amount), 0) as paid_amount', false);
        $this->db->join('laagat_rent_payments p', 'p.invoice_id = i.id', 'left');
        $this->db->group_by('i.id');

        $this->db->order_by('i.id DESC');
        return $this->db->get()->result_array();
    }

    public function get_invoice_by_id($id)
    {
        $this->db->select('i.*, u.Full_Name, u.ITS_ID, u.Sector, u.Sub_Sector, lr.title, lr.charge_type, lr.hijri_year, i.amount as master_amount, rr.raza_id as generated_raza_id');
        $this->db->from('laagat_rent_invoices i');
        $this->db->join('user u', 'u.ITS_ID = i.user_id', 'left');
        $this->db->join('laagat_rent lr', 'lr.id = i.laagat_rent_id', 'left');
        $this->db->join('raza rr', 'rr.id = i.raza_id', 'left');
        $this->db->where('i.id', $id);
        $row = $this->db->get()->row_array();
        if ($row) {
            $row['payments'] = $this->get_payments_by_invoice($id);
            $row['paid_amount'] = 0;
            foreach ($row['payments'] as $p) {
                $row['paid_amount'] += (float)$p['amount'];
            }
        }
        return $row;
    }

    public function get_payments_by_invoice($invoiceId)
    {
        return $this->db->from('laagat_rent_payments')
            ->where('invoice_id', $invoiceId)
            ->order_by('payment_date DESC, id DESC')
            ->get()->result_array();
    }

    public function add_payment($data)
    {
        $this->db->insert('laagat_rent_payments', $data);
        return $this->db->insert_id();
    }

    public function delete_payment($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('laagat_rent_payments');
    }

    public function delete_invoice($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('laagat_rent_invoices');
    }

    public function update_invoice($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('laagat_rent_invoices', $data);
    }

    public function get_payment_by_id($id)
    {
        $this->db->select('p.*, i.user_id, i.laagat_rent_id, i.raza_id, lr.title, lr.charge_type, lr.hijri_year, u.Full_Name, u.ITS_ID, r.raza_id as generated_raza_id');
        $this->db->from('laagat_rent_payments p');
        $this->db->join('laagat_rent_invoices i', 'i.id = p.invoice_id', 'left');
        $this->db->join('laagat_rent lr', 'lr.id = i.laagat_rent_id', 'left');
        $this->db->join('user u', 'u.ITS_ID = i.user_id', 'left');
        $this->db->join('raza r', 'r.id = i.raza_id', 'left');
        $this->db->where('p.id', $id);
        return $this->db->get()->row_array();
    }
}
