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
        if ($row) {
            $row['thaal_ranges'] = $this->db
                ->from('laagat_rent_thaal_ranges')
                ->where('laagat_rent_id', $id)
                ->order_by('thaal_min ASC')
                ->get()
                ->result_array();

            $row['items'] = $this->db
                ->from('laagat_rent_items')
                ->where('laagat_rent_id', $id)
                ->order_by('id ASC')
                ->get()
                ->result_array();
        }
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

    private function find_duplicate_id($hijriYear, $chargeType, $title, $razaTypeIds, $excludeId = 0, $venue = null)
    {
        $hijriYear = trim((string)$hijriYear);
        $chargeType = strtolower(trim((string)$chargeType));
        $excludeId = (int)$excludeId;
        $venue = $venue !== null ? trim((string)$venue) : '';

        // Consider it duplicate when ALL of these match:
        //  - Charge Type
        //  - Venue (case-insensitively, treating NULL/empty as equivalent)
        //  - Any Applicable Raza Category overlaps
        $needleIds = $this->normalize_raza_type_ids($razaTypeIds);

        // Normalize stored values on-the-fly to avoid duplicates slipping through due to
        // legacy data inconsistencies (extra spaces / casing differences).
        $q = $this->db->select('id, venue')->from('laagat_rent');
        $q->where('hijri_year', $hijriYear);
        $q->where('LOWER(TRIM(charge_type)) = ' . $this->db->escape($chargeType), null, false);
        if ($excludeId > 0) $q->where('id !=', $excludeId);
        $rows = $q->get()->result_array();

        foreach ($rows as $r) {
            $rid = (int)($r['id'] ?? 0);
            if ($rid <= 0) continue;

            $existingVenue = isset($r['venue']) ? trim((string)$r['venue']) : '';
            if (strcasecmp($existingVenue, $venue) !== 0) {
                continue;
            }

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
        return 'Raza already exist. You can\'t create same Raza.';
    }

    public function check_duplicate_overlap($hijriYear, $chargeType, $razaTypeIds, $excludeId = 0, $venue = null)
    {
        return (int)$this->find_duplicate_id($hijriYear, $chargeType, '', $razaTypeIds, $excludeId, $venue);
    }

    public function create($payload)
    {
        if (isset($payload['charge_type']) && $payload['charge_type'] === 'rent') {
            $payload['grade_amounts'] = [];
            $payload['grade_jamaat_amounts'] = [];
            $payload['grade_sarkaar_amounts'] = [];
        }

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

        $dupId = $this->find_duplicate_id((string)($payload['hijri_year'] ?? ''), (string)($payload['charge_type'] ?? ''), (string)($payload['title'] ?? ''), $razaTypeIds, 0, $payload['venue'] ?? null);
        if ($dupId > 0) {
            return ['success' => false, 'error' => $this->duplicate_error_message((string)($payload['charge_type'] ?? '')), 'existing_id' => $dupId];
        }

        $data = [
            'title' => (string)($payload['title'] ?? ''),
            'hijri_year' => (string)($payload['hijri_year'] ?? ''),
            'charge_type' => (string)($payload['charge_type'] ?? ''),
            'amount' => (isset($payload['charge_type']) && $payload['charge_type'] === 'rent') ? (float)($payload['rent_non_sabeel'] ?? $payload['amount'] ?? 0) : (float)($payload['amount'] ?? 0),
            'rent_sabeel' => (isset($payload['charge_type']) && $payload['charge_type'] === 'rent') ? (float)($payload['rent_sabeel'] ?? 0) : 0.00,
            'rent_non_sabeel' => (isset($payload['charge_type']) && $payload['charge_type'] === 'rent') ? (float)($payload['rent_non_sabeel'] ?? 0) : 0.00,
            'deposit_sabeel' => (isset($payload['charge_type']) && $payload['charge_type'] === 'rent') ? (float)($payload['deposit_sabeel'] ?? 0) : 0.00,
            'deposit_non_sabeel' => (isset($payload['charge_type']) && $payload['charge_type'] === 'rent') ? (float)($payload['deposit_non_sabeel'] ?? 0) : 0.00,
            // Keep legacy single column populated with first selected id.
            'raza_type_id' => !empty($razaTypeIds) ? (int)$razaTypeIds[0] : (int)($payload['raza_type_id'] ?? 0),
            'venue' => (isset($payload['charge_type']) && $payload['charge_type'] === 'rent' && isset($payload['venue'])) ? (string)$payload['venue'] : null,
            'is_per_thaal' => (isset($payload['charge_type']) && $payload['charge_type'] === 'rent') ? (int)($payload['is_per_thaal'] ?? 0) : 0,
            // Do not auto-activate on create; activation should be explicit from Manage screen.
            'is_active' => isset($payload['is_active']) ? ((int)$payload['is_active'] ? 1 : 0) : 1,
        ];

        $this->db->trans_start();

        $this->db->insert('laagat_rent', $data);
        $insertId = (int)$this->db->insert_id();

        if ($insertId > 0 && isset($payload['thaal_ranges']) && is_array($payload['thaal_ranges'])) {
            foreach ($payload['thaal_ranges'] as $r) {
                $this->db->insert('laagat_rent_thaal_ranges', [
                    'laagat_rent_id' => $insertId,
                    'thaal_min' => (int)($r['thaal_min'] ?? 0),
                    'thaal_max' => (int)($r['thaal_max'] ?? 0),
                    'rent_sabeel' => (float)($r['rent_sabeel'] ?? 0),
                    'deposit_sabeel' => (float)($r['deposit_sabeel'] ?? 0),
                    'rent_non_sabeel' => (float)($r['rent_non_sabeel'] ?? 0),
                    'deposit_non_sabeel' => (float)($r['deposit_non_sabeel'] ?? 0)
                ]);
            }
        }

        if ($insertId > 0 && isset($payload['items']) && is_array($payload['items'])) {
            foreach ($payload['items'] as $item) {
                if (trim((string)($item['item_name'] ?? '')) !== '') {
                    $this->db->insert('laagat_rent_items', [
                        'laagat_rent_id' => $insertId,
                        'item_name' => trim((string)$item['item_name']),
                        'service_provided_by' => trim((string)($item['service_provided_by'] ?? 'Jamaat')),
                        'rent_sabeel' => (float)($item['rent_sabeel'] ?? 0),
                        'deposit_sabeel' => (float)($item['deposit_sabeel'] ?? 0),
                        'rent_non_sabeel' => (float)($item['rent_non_sabeel'] ?? 0),
                        'deposit_non_sabeel' => (float)($item['deposit_non_sabeel'] ?? 0),
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }

        if ($insertId > 0 && $this->db->table_exists('laagat_rent_raza_type_map')) {
            foreach ($razaTypeIds as $rid) {
                $this->db->insert('laagat_rent_raza_type_map', [
                    'laagat_rent_id' => $insertId,
                    'raza_type_id' => (int)$rid,
                ]);
            }
        }

        if ($insertId > 0 && isset($payload['grade_amounts']) && is_array($payload['grade_amounts'])) {
            foreach ($payload['grade_amounts'] as $gradeId => $amount) {
                if ($amount !== '' && $amount !== null) {
                    $this->db->insert('laagat_rent_grade_amounts', [
                        'laagat_rent_id' => $insertId,
                        'sabeel_takhmeen_grade_id' => (int)$gradeId,
                        'amount' => (float)$amount,
                        'jamaat_amount' => isset($payload['grade_jamaat_amounts'][$gradeId]) ? (float)$payload['grade_jamaat_amounts'][$gradeId] : (float)$amount,
                        'sarkaar_amount' => isset($payload['grade_sarkaar_amounts'][$gradeId]) ? (float)$payload['grade_sarkaar_amounts'][$gradeId] : 0.00,
                    ]);
                }
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

        if (isset($payload['charge_type']) && $payload['charge_type'] === 'rent') {
            $payload['grade_amounts'] = [];
            $payload['grade_jamaat_amounts'] = [];
            $payload['grade_sarkaar_amounts'] = [];
        }

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

        $dupId = $this->find_duplicate_id((string)($payload['hijri_year'] ?? ''), (string)($payload['charge_type'] ?? ''), (string)($payload['title'] ?? ''), $razaTypeIds, $id, $payload['venue'] ?? null);
        if ($dupId > 0) {
            return ['success' => false, 'error' => $this->duplicate_error_message((string)($payload['charge_type'] ?? '')), 'existing_id' => $dupId];
        }

        $data = [
            'title' => (string)($payload['title'] ?? ''),
            'hijri_year' => (string)($payload['hijri_year'] ?? ''),
            'charge_type' => (string)($payload['charge_type'] ?? ''),
            'amount' => (isset($payload['charge_type']) && $payload['charge_type'] === 'rent') ? (float)($payload['rent_non_sabeel'] ?? $payload['amount'] ?? 0) : (float)($payload['amount'] ?? 0),
            'rent_sabeel' => (isset($payload['charge_type']) && $payload['charge_type'] === 'rent') ? (float)($payload['rent_sabeel'] ?? 0) : 0.00,
            'rent_non_sabeel' => (isset($payload['charge_type']) && $payload['charge_type'] === 'rent') ? (float)($payload['rent_non_sabeel'] ?? 0) : 0.00,
            'deposit_sabeel' => (isset($payload['charge_type']) && $payload['charge_type'] === 'rent') ? (float)($payload['deposit_sabeel'] ?? 0) : 0.00,
            'deposit_non_sabeel' => (isset($payload['charge_type']) && $payload['charge_type'] === 'rent') ? (float)($payload['deposit_non_sabeel'] ?? 0) : 0.00,
            // Keep legacy single column populated with first selected id.
            'raza_type_id' => !empty($razaTypeIds) ? (int)$razaTypeIds[0] : (int)($payload['raza_type_id'] ?? 0),
            'venue' => (isset($payload['charge_type']) && $payload['charge_type'] === 'rent' && isset($payload['venue'])) ? (string)$payload['venue'] : null,
            'is_per_thaal' => (isset($payload['charge_type']) && $payload['charge_type'] === 'rent') ? (int)($payload['is_per_thaal'] ?? 0) : 0,
        ];

        $this->db->trans_start();

        $this->db->where('id', $id);
        $this->db->update('laagat_rent', $data);

        $this->db->where('laagat_rent_id', $id);
        $this->db->delete('laagat_rent_thaal_ranges');
        if (isset($payload['thaal_ranges']) && is_array($payload['thaal_ranges'])) {
            foreach ($payload['thaal_ranges'] as $r) {
                $this->db->insert('laagat_rent_thaal_ranges', [
                    'laagat_rent_id' => $id,
                    'thaal_min' => (int)($r['thaal_min'] ?? 0),
                    'thaal_max' => (int)($r['thaal_max'] ?? 0),
                    'rent_sabeel' => (float)($r['rent_sabeel'] ?? 0),
                    'deposit_sabeel' => (float)($r['deposit_sabeel'] ?? 0),
                    'rent_non_sabeel' => (float)($r['rent_non_sabeel'] ?? 0),
                    'deposit_non_sabeel' => (float)($r['deposit_non_sabeel'] ?? 0)
                ]);
            }
        }

        $this->db->where('laagat_rent_id', $id);
        $this->db->delete('laagat_rent_items');
        if (isset($payload['items']) && is_array($payload['items'])) {
            foreach ($payload['items'] as $item) {
                if (trim((string)($item['item_name'] ?? '')) !== '') {
                    $this->db->insert('laagat_rent_items', [
                        'laagat_rent_id' => $id,
                        'item_name' => trim((string)$item['item_name']),
                        'service_provided_by' => trim((string)($item['service_provided_by'] ?? 'Jamaat')),
                        'rent_sabeel' => (float)($item['rent_sabeel'] ?? 0),
                        'deposit_sabeel' => (float)($item['deposit_sabeel'] ?? 0),
                        'rent_non_sabeel' => (float)($item['rent_non_sabeel'] ?? 0),
                        'deposit_non_sabeel' => (float)($item['deposit_non_sabeel'] ?? 0),
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }

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

        $this->db->where('laagat_rent_id', $id);
        $this->db->delete('laagat_rent_grade_amounts');
        if (isset($payload['grade_amounts']) && is_array($payload['grade_amounts'])) {
            foreach ($payload['grade_amounts'] as $gradeId => $amount) {
                if ($amount !== '' && $amount !== null) {
                    $this->db->insert('laagat_rent_grade_amounts', [
                        'laagat_rent_id' => $id,
                        'sabeel_takhmeen_grade_id' => (int)$gradeId,
                        'amount' => (float)$amount,
                        'jamaat_amount' => isset($payload['grade_jamaat_amounts'][$gradeId]) ? (float)$payload['grade_jamaat_amounts'][$gradeId] : (float)$amount,
                        'sarkaar_amount' => isset($payload['grade_sarkaar_amounts'][$gradeId]) ? (float)$payload['grade_sarkaar_amounts'][$gradeId] : 0.00,
                    ]);
                }
            }
        }

        // If this record is active, deactivate conflicting records (same Year + Charge Type + Venue + overlapping category / both master).
        $row = $this->db->select('hijri_year, charge_type, is_active, venue')
            ->from('laagat_rent')
            ->where('id', $id)
            ->get()
            ->row_array();

        if ($row && (int)$row['is_active'] === 1) {
            $this->deactivate_conflicting_records($id, $row['hijri_year'], $row['charge_type'], $row['venue']);
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

        $row = $this->db->select('is_active, hijri_year, charge_type, venue')->from('laagat_rent')->where('id', $id)->get()->row_array();
        if (!$row) return ['success' => false, 'error' => 'Not found'];

        $newVal = ((int)$row['is_active'] === 1) ? 0 : 1;

        $this->db->trans_start();

        if ($newVal === 1) {
            $this->deactivate_conflicting_records($id, $row['hijri_year'], $row['charge_type'], $row['venue']);
        }

        $this->db->where('id', $id);
        $this->db->update('laagat_rent', ['is_active' => $newVal]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return ['success' => false, 'error' => 'Unable to update'];
        }

        return ['success' => true, 'is_active' => $newVal, 'charge_type' => $row['charge_type']];
    }

    private function deactivate_conflicting_records($id, $hijriYear, $chargeType, $venue)
    {
        $id = (int)$id;
        $hijriYear = trim((string)$hijriYear);
        $chargeType = strtolower(trim((string)$chargeType));
        $venue = $venue !== null ? trim((string)$venue) : '';

        $aIds = $this->get_raza_type_ids_for_record($id);
        $isAMaster = empty($aIds);

        $this->db->select('id');
        $this->db->from('laagat_rent');
        $this->db->where('hijri_year', $hijriYear);
        $this->db->where('charge_type', $chargeType);
        $this->db->where('id !=', $id);
        $this->db->where('is_active', 1);

        if ($chargeType === 'rent' && $venue !== '') {
            $this->db->where('venue', $venue);
        } else {
            $this->db->where('(venue IS NULL OR venue = \'\')', null, false);
        }

        $otherActive = $this->db->get()->result_array();

        foreach ($otherActive as $r) {
            $otherId = (int)$r['id'];
            $bIds = $this->get_raza_type_ids_for_record($otherId);
            $isBMaster = empty($bIds);

            $shouldDeactivate = false;
            if ($isAMaster && $isBMaster) {
                $shouldDeactivate = true;
            } else if ($this->has_any_common_raza_type($aIds, $bIds)) {
                $shouldDeactivate = true;
            }

            if ($shouldDeactivate) {
                $this->db->where('id', $otherId);
                $this->db->update('laagat_rent', ['is_active' => 0]);
            }
        }
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
    public function get_active_for_raza_type($chargeType, $razaTypeId, $hijriYear = null, $fallbackToMaster = true, $venue = null)
    {
        $chargeType = strtolower(trim((string)$chargeType));
        $razaTypeId = (int)$razaTypeId;
        $hijriYear = $hijriYear !== null ? trim((string)$hijriYear) : null;
        $fallbackToMaster = (bool)$fallbackToMaster;
        $venue = $venue !== null ? trim((string)$venue) : '';

        if ($razaTypeId <= 0) return null;
        if (!in_array($chargeType, ['laagat', 'rent'], true)) return null;

        // 1) Try category-specific match with specific venue first (if charge_type is rent and venue is provided)
        if ($chargeType === 'rent' && $venue !== '') {
            $this->db->select('lr.id, lr.title, lr.hijri_year, lr.charge_type, lr.amount, lr.rent_sabeel, lr.rent_non_sabeel, lr.deposit_sabeel, lr.deposit_non_sabeel');
            $this->db->from('laagat_rent lr');
            $this->db->where('lr.charge_type', $chargeType);
            $this->db->where('lr.is_active', 1);
            $this->db->where('lr.venue', $venue);

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
        }

        // 2) Try category-specific match with NULL/empty venue
        $this->db->select('lr.id, lr.title, lr.hijri_year, lr.charge_type, lr.amount, lr.rent_sabeel, lr.rent_non_sabeel, lr.deposit_sabeel, lr.deposit_non_sabeel');
        $this->db->from('laagat_rent lr');
        $this->db->where('lr.charge_type', $chargeType);
        $this->db->where('lr.is_active', 1);
        $this->db->where('(lr.venue IS NULL OR lr.venue = \'\')', null, false);

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

        // 2.5) Fallback for category match with ANY venue (useful when the form doesn't have a Venue field, but there's a venue-specific rent config)
        if ($venue === '') {
            $this->db->select('lr.id, lr.title, lr.hijri_year, lr.charge_type, lr.amount, lr.rent_sabeel, lr.rent_non_sabeel, lr.deposit_sabeel, lr.deposit_non_sabeel');
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
        }

        if (!$fallbackToMaster) return null;

        // 3) Fallback to active master
        return $this->get_active_master($chargeType, $hijriYear, $venue);
    }

    public function get_active_master($chargeType, $hijriYear = null, $venue = null)
    {
        $chargeType = strtolower(trim((string)$chargeType));
        $hijriYear = $hijriYear !== null ? trim((string)$hijriYear) : null;
        $venue = $venue !== null ? trim((string)$venue) : '';

        if (!in_array($chargeType, ['laagat', 'rent'], true)) return null;

        // Try specific venue master first (if charge_type is rent and venue is provided)
        if ($chargeType === 'rent' && $venue !== '') {
            $this->db->select('id, title, hijri_year, charge_type, amount, rent_sabeel, rent_non_sabeel, deposit_sabeel, deposit_non_sabeel');
            $this->db->from('laagat_rent');
            $this->db->where('charge_type', $chargeType);
            $this->db->where('is_active', 1);
            $this->db->where('venue', $venue);
            if ($hijriYear !== null && $hijriYear !== '') {
                $this->db->where('hijri_year', $hijriYear);
            }
            $this->db->order_by('hijri_year DESC');
            $this->db->order_by('id DESC');
            $this->db->limit(1);
            $row = $this->db->get()->row_array();
            if ($row) return $row;
        }

        // Try NULL/empty venue master
        $this->db->select('id, title, hijri_year, charge_type, amount, rent_sabeel, rent_non_sabeel, deposit_sabeel, deposit_non_sabeel');
        $this->db->from('laagat_rent');
        $this->db->where('charge_type', $chargeType);
        $this->db->where('is_active', 1);
        $this->db->where('(venue IS NULL OR venue = \'\')', null, false);
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
        $this->db->select('i.*, u.Full_Name, u.ITS_ID, u.Sector, u.Sub_Sector, lr.title, lr.charge_type, lr.hijri_year, i.amount as master_amount, rr.raza_id as generated_raza_id, rt.name as raza_type_name, rr.`Janab-status` AS janab_status');
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
            if ($filters['charge_type'] === 'deposit') {
                $this->db->where('lr.charge_type', 'rent');
                $this->db->where('i.deposit_amount >', 0.0001);
                $this->db->where('i.amount <=', 0.0001);
            } elseif ($filters['charge_type'] === 'rent') {
                $this->db->where('lr.charge_type', 'rent');
                $this->db->where('i.amount >', 0.0001);
            } else {
                $this->db->where('lr.charge_type', $filters['charge_type']);
            }
        }

        // Avoid ONLY_FULL_GROUP_BY by joining a pre-aggregated payments subquery.
        $paymentsAggSql = '(SELECT invoice_id, SUM(amount) AS paid_amount FROM laagat_rent_payments GROUP BY invoice_id) p';
        $this->db->join($paymentsAggSql, 'p.invoice_id = i.id', 'left', false);
        $this->db->select('COALESCE(p.paid_amount, 0) as paid_amount', false);

        $this->db->order_by('i.id DESC');
        return $this->db->get()->result_array();
    }

    public function get_invoice_by_id($id)
    {
        $this->db->select('i.*, u.Full_Name, u.ITS_ID, u.Sector, u.Sub_Sector, lr.title, lr.charge_type, lr.hijri_year, i.amount as master_amount, rr.raza_id as generated_raza_id, rr.`Janab-status` AS janab_status');
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

    public function get_grade_amounts_for_year($year, $laagatRentId = 0)
    {
        $this->db->from('sabeel_takhmeen_grade g');
        if ($laagatRentId > 0) {
            $this->db->select('g.id as sabeel_takhmeen_grade_id, g.grade, g.amount as default_amount, ga.amount as saved_amount, ga.jamaat_amount as saved_jamaat_amount, ga.sarkaar_amount as saved_sarkaar_amount');
            $this->db->join('laagat_rent_grade_amounts ga', 'ga.sabeel_takhmeen_grade_id = g.id AND ga.laagat_rent_id = ' . (int)$laagatRentId, 'left');
        } else {
            $this->db->select('g.id as sabeel_takhmeen_grade_id, g.grade, g.amount as default_amount, NULL as saved_amount, NULL as saved_jamaat_amount, NULL as saved_sarkaar_amount', false);
        }
        $this->db->where('g.type', 'Residential');
        $this->db->where('g.year', $year);
        $this->db->order_by('g.grade ASC');
        return $this->db->get()->result_array();
    }

    public function get_amount_for_user($laagatRentId, $userId)
    {
        $laagatRentId = (int)$laagatRentId;
        $userId = (int)$userId;
        if ($laagatRentId <= 0 || $userId <= 0) return 0.00;

        $lr = $this->db->select('charge_type, hijri_year, amount, rent_sabeel, rent_non_sabeel, deposit_sabeel, deposit_non_sabeel')->from('laagat_rent')->where('id', $laagatRentId)->get()->row_array();
        if (!$lr) return 0.00;

        $hijriYear = $lr['hijri_year'];
        $masterAmount = (float)$lr['amount'];
        $chargeType = $lr['charge_type'];

        if ($chargeType === 'rent') {
            $hasSabeel = false;
            $takhmeen = $this->db->select('id')
                ->from('sabeel_takhmeen')
                ->where('user_id', $userId)
                ->where('year', $hijriYear)
                ->get()
                ->row_array();
            if ($takhmeen) {
                $hasSabeel = true;
            }
            $rentAmt = $hasSabeel ? (float)$lr['rent_sabeel'] : (float)$lr['rent_non_sabeel'];
            if ($rentAmt == 0.00) {
                $rentAmt = $masterAmount;
            }
            return $rentAmt;
        }

        if ($chargeType !== 'rent') {
            $takhmeen = $this->db->select('residential_grade')
                ->from('sabeel_takhmeen')
                ->where('user_id', $userId)
                ->where('year', $hijriYear)
                ->get()
                ->row_array();

            if ($takhmeen && !empty($takhmeen['residential_grade'])) {
                $gradeId = (int)$takhmeen['residential_grade'];

                $gradeAmountRow = $this->db->select('amount')
                    ->from('laagat_rent_grade_amounts')
                    ->where('laagat_rent_id', $laagatRentId)
                    ->where('sabeel_takhmeen_grade_id', $gradeId)
                    ->get()
                    ->row_array();

                if ($gradeAmountRow && $gradeAmountRow['amount'] !== null) {
                    return (float)$gradeAmountRow['amount'];
                }
            }
        }

        return $masterAmount;
    }

    public function get_amounts_breakdown_for_user($laagatRentId, $userId, $thaalCount = 1, $itemQuantities = [])
    {
        $laagatRentId = (int)$laagatRentId;
        $userId = (int)$userId;
        $thaalCount = (int)$thaalCount;
        if ($thaalCount <= 0) {
            $thaalCount = 1;
        }
        if ($laagatRentId <= 0 || $userId <= 0) {
            return [
                'jamaat_amount' => 0.00,
                'sarkaar_amount' => 0.00,
                'amount' => 0.00
            ];
        }

        $lr = $this->db->select('charge_type, hijri_year, amount, rent_sabeel, rent_non_sabeel, deposit_sabeel, deposit_non_sabeel, is_per_thaal')->from('laagat_rent')->where('id', $laagatRentId)->get()->row_array();
        if (!$lr) {
            return [
                'jamaat_amount' => 0.00,
                'sarkaar_amount' => 0.00,
                'amount' => 0.00,
                'deposit_amount' => 0.00
            ];
        }

        $hijriYear = $lr['hijri_year'];
        $masterAmount = (float)$lr['amount'];
        $chargeType = $lr['charge_type'];

        if ($chargeType === 'rent') {
            // Check sabeel status using the pre-computed its_sabeel_match column.
            // Both 'its_sabeel_both_khar' (ITS & Sabeel both in Khar) and
            // 'sabeel_khar_its_out' (Sabeel in Khar, ITS not in Khar) qualify
            // as Khar Sabeel Holders and get the lower rent_sabeel rate.
            $hasSabeel = false;
            $userRow = $this->db->select('its_sabeel_match')
                ->from('user')
                ->where('ITS_ID', $userId)
                ->get()
                ->row_array();
            if ($userRow) {
                $match = $userRow['its_sabeel_match'] ?? '';
                $hasSabeel = in_array($match, ['its_sabeel_both_khar', 'sabeel_khar_its_out'], true);
            }

            // Fallback: check sabeel_takhmeen directly if its_sabeel_match is not set
            if (!$hasSabeel) {
                $takhmeen = $this->db->select('id')
                    ->from('sabeel_takhmeen')
                    ->where('user_id', $userId)
                    ->where('year', $hijriYear)
                    ->get()
                    ->row_array();
                if ($takhmeen) {
                    $hasSabeel = true;
                }
            }

            // Fallback: check HOF's status if member doesn't have it
            if (!$hasSabeel) {
                $this->load->model('AccountM');
                $hofId = $this->AccountM->get_hof_id_for_member($userId);
                if ($hofId > 0 && $hofId != $userId) {
                    $hofRow = $this->db->select('its_sabeel_match')
                        ->from('user')
                        ->where('ITS_ID', $hofId)
                        ->get()
                        ->row_array();
                    if ($hofRow) {
                        $match = $hofRow['its_sabeel_match'] ?? '';
                        $hasSabeel = in_array($match, ['its_sabeel_both_khar', 'sabeel_khar_its_out'], true);
                    }

                    if (!$hasSabeel) {
                        $takhmeen = $this->db->select('id')
                            ->from('sabeel_takhmeen')
                            ->where('user_id', $hofId)
                            ->where('year', $hijriYear)
                            ->get()
                            ->row_array();
                        if ($takhmeen) {
                            $hasSabeel = true;
                        }
                    }
                }
            }

            if ($hasSabeel) {
                $rentAmt = (float)$lr['rent_sabeel'];
                $depositAmt = (float)$lr['deposit_sabeel'];
            } else {
                $rentAmt = (float)$lr['rent_non_sabeel'];
                $depositAmt = (float)$lr['deposit_non_sabeel'];
            }

            // Fallback for compatibility
            if ($rentAmt == 0.00) {
                $rentAmt = $masterAmount;
            }

            if ((int)($lr['is_per_thaal'] ?? 0) === 1) {
                $range = $this->db
                    ->from('laagat_rent_thaal_ranges')
                    ->where('laagat_rent_id', $laagatRentId)
                    ->where('thaal_min <=', $thaalCount)
                    ->where('thaal_max >=', $thaalCount)
                    ->limit(1)
                    ->get()
                    ->row_array();

                if (!$range) {
                    // Fallback: try finding the range with the maximum thaal_max
                    $range = $this->db
                        ->from('laagat_rent_thaal_ranges')
                        ->where('laagat_rent_id', $laagatRentId)
                        ->order_by('thaal_max DESC')
                        ->limit(1)
                        ->get()
                        ->row_array();
                }

                if ($range) {
                    if ($hasSabeel) {
                        $rentAmt = (float)$range['rent_sabeel'];
                        $depositAmt = (float)$range['deposit_sabeel'];
                    } else {
                        $rentAmt = (float)$range['rent_non_sabeel'];
                        $depositAmt = (float)$range['deposit_non_sabeel'];
                    }
                }
            }

            if ((int)($lr['is_per_thaal'] ?? 0) === 2) {
                $rentAmt = $rentAmt * $thaalCount;
                $depositAmt = $depositAmt * $thaalCount;
            }

            // Calculate rent items cost
            $itemsRent = 0.00;
            if (!empty($itemQuantities) && is_array($itemQuantities)) {
                $itemIds = array_keys($itemQuantities);
                if (!empty($itemIds)) {
                    $dbItems = $this->db
                        ->from('laagat_rent_items')
                        ->where('laagat_rent_id', $laagatRentId)
                        ->where_in('id', $itemIds)
                        ->get()
                        ->result_array();
                    foreach ($dbItems as $item) {
                        $qty = isset($itemQuantities[$item['id']]) ? (int)$itemQuantities[$item['id']] : 0;
                        if ($qty > 0) {
                            $itemsRent += ((float)($item['rent_sabeel'] ?? 0.00)) * $qty;
                        }
                    }
                }
            }
            // Rent invoice should be created by considering Rent items
            $rentAmt += $itemsRent;

            return [
                'jamaat_amount' => $rentAmt,
                'sarkaar_amount' => 0.00,
                'amount' => $rentAmt,
                'deposit_amount' => $depositAmt
            ];
        }

        if ($chargeType !== 'rent') {
            $takhmeen = $this->db->select('residential_grade')
                ->from('sabeel_takhmeen')
                ->where('user_id', $userId)
                ->where('year', $hijriYear)
                ->get()
                ->row_array();

            if (!$takhmeen || empty($takhmeen['residential_grade'])) {
                // Try HOF ID
                $this->load->model('AccountM');
                $hofId = $this->AccountM->get_hof_id_for_member($userId);
                if ($hofId > 0 && $hofId != $userId) {
                    $takhmeen = $this->db->select('residential_grade')
                        ->from('sabeel_takhmeen')
                        ->where('user_id', $hofId)
                        ->where('year', $hijriYear)
                        ->get()
                        ->row_array();
                }
            }

            if ($takhmeen && !empty($takhmeen['residential_grade'])) {
                $gradeId = (int)$takhmeen['residential_grade'];

                $gradeAmountRow = $this->db->select('amount, jamaat_amount, sarkaar_amount')
                    ->from('laagat_rent_grade_amounts')
                    ->where('laagat_rent_id', $laagatRentId)
                    ->where('sabeel_takhmeen_grade_id', $gradeId)
                    ->get()
                    ->row_array();

                if ($gradeAmountRow) {
                    $jAmt = (float)$gradeAmountRow['jamaat_amount'];
                    $sAmt = (float)$gradeAmountRow['sarkaar_amount'];
                    $totalAmt = (float)$gradeAmountRow['amount'];

                    // Fallback for legacy database rows where columns were added but not populated
                    if ($jAmt == 0.00 && $sAmt == 0.00 && $totalAmt > 0.00) {
                        $jAmt = $totalAmt;
                    }

                    return [
                        'jamaat_amount' => $jAmt,
                        'sarkaar_amount' => $sAmt,
                        'amount' => $totalAmt
                    ];
                }
            }
        }

        return [
            'jamaat_amount' => $masterAmount,
            'sarkaar_amount' => 0.00,
            'amount' => $masterAmount
        ];
    }

    public function get_all_venues_from_raza_forms()
    {
        $rows = $this->db->select('name')
            ->from('properties')
            ->order_by('name', 'ASC')
            ->get()
            ->result_array();

        $out = [];
        foreach ($rows as $r) {
            $out[] = $r['name'];
        }
        return $out;
    }

    public function get_venue_name_by_id($razaTypeId, $optionId)
    {
        // 1) First check properties table
        $prop = $this->db->select('name')
            ->from('properties')
            ->where('id', (int)$optionId)
            ->get()
            ->row_array();
        if ($prop) {
            return $prop['name'];
        }

        // 2) Fallback to legacy scan of fields column in raza_type
        $razaTypeId = (int)$razaTypeId;
        $row = $this->db->select('fields')->from('raza_type')->where('id', $razaTypeId)->get()->row_array();
        if (!$row || empty($row['fields'])) return '';
        $fields = json_decode($row['fields'], true);
        if (isset($fields['fields']) && is_array($fields['fields'])) {
            foreach ($fields['fields'] as $f) {
                if (isset($f['name']) && strcasecmp(trim($f['name']), 'venue') === 0) {
                    if (isset($f['options']) && is_array($f['options'])) {
                        foreach ($f['options'] as $o) {
                            if (isset($o['id']) && (string)$o['id'] === (string)$optionId) {
                                return isset($o['name']) ? trim($o['name']) : '';
                            }
                        }
                    }
                }
            }
        }
        return '';
    }

    public function get_rent_items_bifurcation($invoices)
    {
        $totals = [
            'Jamaat' => 0.00,
            'Ladies' => 0.00,
            'Other' => 0.00
        ];

        if (empty($invoices)) {
            return $totals;
        }

        // Gather all invoices that are rent and have a raza_id
        $rentInvoices = [];
        foreach ($invoices as $inv) {
            if ($inv['charge_type'] === 'rent' && !empty($inv['raza_id'])) {
                $rentInvoices[] = $inv;
            }
        }

        if (empty($rentInvoices)) {
            return $totals;
        }

        // Fetch all rent items in one query to avoid N+1 queries
        $laagatRentIds = array_unique(array_column($rentInvoices, 'laagat_rent_id'));
        $dbItems = [];
        if (!empty($laagatRentIds)) {
            $itemsRows = $this->db
                ->from('laagat_rent_items')
                ->where_in('laagat_rent_id', $laagatRentIds)
                ->get()
                ->result_array();
            foreach ($itemsRows as $row) {
                $dbItems[(int)$row['id']] = $row;
            }
        }

        // Loop through each invoice and calculate
        foreach ($rentInvoices as $inv) {
            $raza = $this->db->select('razadata')->get_where('raza', ['id' => $inv['raza_id']])->row_array();
            if ($raza && !empty($raza['razadata'])) {
                $razadata = json_decode($raza['razadata'], true);
                if (isset($razadata['item_qty']) && is_array($razadata['item_qty'])) {
                    foreach ($razadata['item_qty'] as $itemId => $qty) {
                        $qty = (int)$qty;
                        if ($qty > 0 && isset($dbItems[(int)$itemId])) {
                            $item = $dbItems[(int)$itemId];
                            $cost = ((float)$item['rent_sabeel']) * $qty;
                            $provider = trim((string)($item['service_provided_by'] ?? 'Jamaat'));
                            if ($provider === 'Ladies') {
                                $totals['Ladies'] += $cost;
                            } elseif ($provider === 'Jamaat') {
                                $totals['Jamaat'] += $cost;
                            } else {
                                $totals['Other'] += $cost;
                            }
                        }
                    }
                }
            }
        }

        return $totals;
    }
}
