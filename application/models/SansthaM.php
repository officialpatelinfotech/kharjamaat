<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class SansthaM extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->ensure_tables_exist();
    }

    public function ensure_tables_exist()
    {
        if (!$this->db->table_exists('sanstha')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS sanstha (
              id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              name VARCHAR(255) NOT NULL,
              description TEXT NULL,
              status ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active',
              created_at DATETIME NULL,
              updated_at DATETIME NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }

        if (!$this->db->table_exists('sanstha_members')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS sanstha_members (
              id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              sanstha_id INT(11) NOT NULL,
              user_its VARCHAR(50) NOT NULL,
              year VARCHAR(10) NOT NULL DEFAULT '1448',
              created_at DATETIME NULL,
              INDEX (sanstha_id),
              INDEX (user_its),
              INDEX (year),
              UNIQUE KEY sanstha_user_year_unique (sanstha_id, user_its, year)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        } else {
            if (!$this->db->field_exists('year', 'sanstha_members')) {
                $this->db->query("ALTER TABLE sanstha_members ADD COLUMN year VARCHAR(10) NOT NULL DEFAULT '1448' AFTER user_its;");
                @$this->db->query("ALTER TABLE sanstha_members DROP INDEX sanstha_user_unique;");
                @$this->db->query("ALTER TABLE sanstha_members ADD UNIQUE KEY sanstha_user_year_unique (sanstha_id, user_its, year);");
            }
        }
    }

    public function get_all_sansthas($filters = [])
    {
        $year = !empty($filters['year']) ? trim((string)$filters['year']) : '1448';
        $only_with_members = !empty($filters['only_with_members']);

        $this->db->select('s.*');
        $this->db->from('sanstha s');

        if (!empty($filters['status']) && $filters['status'] !== 'All') {
            $this->db->where('s.status', $filters['status']);
        }

        if (!empty($filters['q'])) {
            $q = trim($filters['q']);
            $this->db->group_start();
            $this->db->like('s.name', $q);
            $this->db->or_like('s.description', $q);
            $this->db->group_end();
        }

        $this->db->order_by('s.name', 'ASC');
        $rows = $this->db->get()->result_array();

        // Attach member counts for the specific Hijri year
        foreach ($rows as $k => $r) {
            $this->db->from('sanstha_members');
            $this->db->where('sanstha_id', (int)$r['id']);
            $this->db->where('year', $year);
            $rows[$k]['members_count'] = $this->db->count_all_results();
            $rows[$k]['year'] = $year;
        }

        if ($only_with_members) {
            $rows = array_values(array_filter($rows, function($r) {
                return (int)($r['members_count'] ?? 0) > 0;
            }));
        }

        return $rows;
    }

    public function get_sanstha_by_id($id, $year = '1448')
    {
        $year = trim((string)$year) ?: '1448';
        $row = $this->db->where('id', (int)$id)->get('sanstha')->row_array();
        if ($row) {
            $this->db->from('sanstha_members');
            $this->db->where('sanstha_id', (int)$id);
            $this->db->where('year', $year);
            $row['members_count'] = $this->db->count_all_results();
            $row['year'] = $year;
        }
        return $row;
    }

    public function create_sanstha($data)
    {
        $name = trim((string)($data['name'] ?? ''));
        if ($name === '') {
            return ['success' => false, 'message' => 'Sanstha Name is required.'];
        }

        // Check for duplicate name
        $dup = $this->db->where('LOWER(TRIM(name))', strtolower($name))->get('sanstha')->row_array();
        if ($dup) {
            return ['success' => false, 'message' => 'A Sanstha with this name already exists.'];
        }

        $payload = [
            'name' => $name,
            'description' => trim((string)($data['description'] ?? '')),
            'status' => in_array(($data['status'] ?? 'Active'), ['Active', 'Inactive']) ? $data['status'] : 'Active',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('sanstha', $payload);
        return ['success' => true, 'id' => $this->db->insert_id(), 'message' => 'Sanstha created successfully.'];
    }

    public function update_sanstha($id, $data)
    {
        $id = (int)$id;
        $existing = $this->get_sanstha_by_id($id);
        if (!$existing) {
            return ['success' => false, 'message' => 'Sanstha not found.'];
        }

        $name = trim((string)($data['name'] ?? ''));
        if ($name === '') {
            return ['success' => false, 'message' => 'Sanstha Name is required.'];
        }

        // Check for duplicate name excluding current record
        $dup = $this->db->where('LOWER(TRIM(name))', strtolower($name))->where('id !=', $id)->get('sanstha')->row_array();
        if ($dup) {
            return ['success' => false, 'message' => 'A Sanstha with this name already exists.'];
        }

        $payload = [
            'name' => $name,
            'description' => trim((string)($data['description'] ?? '')),
            'status' => in_array(($data['status'] ?? 'Active'), ['Active', 'Inactive']) ? $data['status'] : 'Active',
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('id', $id)->update('sanstha', $payload);
        return ['success' => true, 'message' => 'Sanstha updated successfully.'];
    }

    public function toggle_status($id)
    {
        $id = (int)$id;
        $row = $this->get_sanstha_by_id($id);
        if (!$row) {
            return ['success' => false, 'message' => 'Sanstha not found.'];
        }

        $new_status = ($row['status'] === 'Active') ? 'Inactive' : 'Active';
        $this->db->where('id', $id)->update('sanstha', [
            'status' => $new_status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return ['success' => true, 'status' => $new_status, 'message' => 'Sanstha status updated to ' . $new_status . '.'];
    }

    public function delete_sanstha($id)
    {
        $id = (int)$id;
        $row = $this->get_sanstha_by_id($id);
        if (!$row) {
            return ['success' => false, 'message' => 'Sanstha not found.'];
        }

        $total_assigned = $this->db->where('sanstha_id', $id)->from('sanstha_members')->count_all_results();

        if ($total_assigned > 0) {
            return ['success' => false, 'message' => 'Cannot delete Sanstha because members are currently assigned to it. Please remove all member assignments first or set status to Inactive.'];
        }

        $this->db->where('id', $id)->delete('sanstha');
        return ['success' => true, 'message' => 'Sanstha deleted successfully.'];
    }

    public function get_sanstha_members($sanstha_id, $q = '', $gender = '', $status = '', $year = '1448', $limit = 10, $page = 1)
    {
        $sanstha_id = (int)$sanstha_id;
        $year = trim((string)$year) ?: '1448';
        $offset = max(0, ($page - 1) * $limit);

        $this->db->from('sanstha_members sm');
        $this->db->join('user u', 'u.ITS_ID = sm.user_its', 'left');
        $this->db->where('sm.sanstha_id', $sanstha_id);
        $this->db->where('sm.year', $year);

        if (!empty($q)) {
            $q_clean = trim($q);
            $this->db->group_start();
            $this->db->like('u.Full_Name', $q_clean);
            $this->db->or_like('u.ITS_ID', $q_clean);
            $this->db->or_like('u.Mobile', $q_clean);
            $this->db->or_like('u.Email', $q_clean);
            $this->db->group_end();
        }

        if (!empty($gender) && $gender !== 'All') {
            $this->db->where('LOWER(u.Gender)', strtolower($gender));
        }

        if (!empty($status) && $status !== 'All') {
            if (strtolower($status) === 'active') {
                $this->db->group_start();
                $this->db->where('u.Inactive_Status IS NULL');
                $this->db->or_where('u.Inactive_Status', '');
                $this->db->or_where('u.Inactive_Status', 'Active');
                $this->db->group_end();
            } else {
                $this->db->group_start();
                $this->db->where('u.Inactive_Status IS NOT NULL');
                $this->db->where('u.Inactive_Status !=', '');
                $this->db->where('u.Inactive_Status !=', 'Active');
                $this->db->group_end();
            }
        }

        $count_db = clone $this->db;
        $total = $count_db->count_all_results();

        $this->db->select("sm.id as assignment_id, sm.year, sm.created_at as assigned_at, u.ITS_ID, u.Full_Name, u.Mobile as Mobile_No, u.Email, u.Gender, COALESCE(u.Father_Name, u.Husband_Name, '') as HOF_Name, u.Inactive_Status");
        $this->db->order_by('u.Full_Name', 'ASC');
        $this->db->limit($limit, $offset);
        $members = $this->db->get()->result_array();

        foreach ($members as $k => $m) {
            $st = strtolower($m['Inactive_Status'] ?? '');
            $members[$k]['status_label'] = ($st !== '' && $st !== 'active') ? 'Inactive' : 'Active';
        }

        return [
            'members' => $members,
            'total' => $total,
            'page' => $page,
            'year' => $year,
            'pages' => ceil($total / max(1, $limit))
        ];
    }

    public function search_available_members($sanstha_id, $q = '', $gender = '', $status = '', $year = '1448', $limit = 10, $page = 1)
    {
        $sanstha_id = (int)$sanstha_id;
        $year = trim((string)$year) ?: '1448';
        $offset = max(0, ($page - 1) * $limit);

        // Get list of existing assigned ITS IDs for this Sanstha in specified year
        $assigned = $this->db->select('user_its')->where('sanstha_id', $sanstha_id)->where('year', $year)->get('sanstha_members')->result_array();
        $assigned_its = array_column($assigned, 'user_its');

        $this->db->from('user');

        if (!empty($q)) {
            $q_clean = trim($q);
            $this->db->group_start();
            $this->db->like('Full_Name', $q_clean);
            $this->db->or_like('ITS_ID', $q_clean);
            $this->db->or_like('Mobile', $q_clean);
            $this->db->or_like('Email', $q_clean);
            $this->db->group_end();
        }

        if (!empty($gender) && $gender !== 'All') {
            $this->db->where('LOWER(Gender)', strtolower($gender));
        }

        if (!empty($status) && $status !== 'All') {
            if (strtolower($status) === 'active') {
                $this->db->group_start();
                $this->db->where('Inactive_Status IS NULL');
                $this->db->or_where('Inactive_Status', '');
                $this->db->or_where('Inactive_Status', 'Active');
                $this->db->group_end();
            } else {
                $this->db->group_start();
                $this->db->where('Inactive_Status IS NOT NULL');
                $this->db->where('Inactive_Status !=', '');
                $this->db->where('Inactive_Status !=', 'Active');
                $this->db->group_end();
            }
        }

        $count_db = clone $this->db;
        $total = $count_db->count_all_results();

        $this->db->select("ITS_ID, Full_Name, Mobile as Mobile_No, Email, Gender, COALESCE(Father_Name, Husband_Name, '') as HOF_Name, Inactive_Status");
        $this->db->order_by('Full_Name', 'ASC');
        $this->db->limit($limit, $offset);
        $members = $this->db->get()->result_array();

        foreach ($members as $k => $m) {
            $st = strtolower($m['Inactive_Status'] ?? '');
            $members[$k]['status_label'] = ($st !== '' && $st !== 'active') ? 'Inactive' : 'Active';
            $members[$k]['is_assigned'] = in_array($m['ITS_ID'], $assigned_its);
        }

        return [
            'members' => $members,
            'total' => $total,
            'page' => $page,
            'year' => $year,
            'pages' => ceil($total / max(1, $limit))
        ];
    }

    public function add_members_to_sanstha($sanstha_id, $its_array, $year = '1448')
    {
        $sanstha_id = (int)$sanstha_id;
        $year = trim((string)$year) ?: '1448';

        $sanstha = $this->get_sanstha_by_id($sanstha_id, $year);
        if (!$sanstha) {
            return ['success' => false, 'message' => 'Sanstha not found.'];
        }

        if (empty($its_array)) {
            return ['success' => false, 'message' => 'No members selected.'];
        }

        if (!is_array($its_array)) {
            $its_array = [$its_array];
        }

        $now = date('Y-m-d H:i:s');
        $added = 0;

        foreach ($its_array as $its_raw) {
            $its = trim($its_raw);
            if (!$its) continue;

            $exists = $this->db->where('sanstha_id', $sanstha_id)
                               ->where('user_its', $its)
                               ->where('year', $year)
                               ->get('sanstha_members')
                               ->row_array();
            if (!$exists) {
                $this->db->insert('sanstha_members', [
                    'sanstha_id' => $sanstha_id,
                    'user_its' => $its,
                    'year' => $year,
                    'created_at' => $now
                ]);
                $added++;
            }
        }

        return ['success' => true, 'message' => "Successfully added {$added} member(s) to " . $sanstha['name'] . " for {$year} Hijri."];
    }

    public function remove_member_from_sanstha($sanstha_id, $user_its, $year = '1448')
    {
        $sanstha_id = (int)$sanstha_id;
        $user_its = trim($user_its);
        $year = trim((string)$year) ?: '1448';

        $this->db->where('sanstha_id', $sanstha_id)
                 ->where('user_its', $user_its)
                 ->where('year', $year)
                 ->delete('sanstha_members');
        return ['success' => true, 'message' => "Member removed from Sanstha for {$year} Hijri successfully."];
    }

    public function copy_previous_year_members($sanstha_id, $from_year, $to_year)
    {
        $sanstha_id = (int)$sanstha_id;
        $from_year = trim((string)$from_year);
        $to_year = trim((string)$to_year);

        if (!$from_year || !$to_year || $from_year === $to_year) {
            return ['success' => false, 'message' => 'Invalid source or target year specified.'];
        }

        $prev_members = $this->db->where('sanstha_id', $sanstha_id)
                                 ->where('year', $from_year)
                                 ->get('sanstha_members')
                                 ->result_array();

        if (empty($prev_members)) {
            return ['success' => false, 'message' => "No member assignments found in {$from_year} Hijri to copy."];
        }

        $its_array = array_column($prev_members, 'user_its');
        return $this->add_members_to_sanstha($sanstha_id, $its_array, $to_year);
    }

    public function get_member_sansthas($user_its, $year = null)
    {
        $user_its = trim((string)$user_its);
        if (!$user_its) {
            return [];
        }

        $this->db->select('s.*, sm.year, sm.created_at as assigned_at');
        $this->db->from('sanstha_members sm');
        $this->db->join('sanstha s', 's.id = sm.sanstha_id', 'inner');
        $this->db->where('sm.user_its', $user_its);
        $this->db->where('s.status', 'Active');

        if (!empty($year)) {
            $this->db->where('sm.year', trim((string)$year));
        }

        $this->db->order_by('sm.year', 'DESC');
        $this->db->order_by('s.name', 'ASC');
        return $this->db->get()->result_array();
    }
}
