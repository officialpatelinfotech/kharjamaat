<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class UmoorHRM extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->ensure_tables_exist();
    }

    public function ensure_tables_exist()
    {
        if (!$this->db->table_exists('sub_committees')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS sub_committees (
              id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              umoor_id INT(11) NOT NULL,
              name VARCHAR(255) NOT NULL,
              team_lead_its VARCHAR(50) NULL,
              created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
              INDEX (umoor_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }

        if (!$this->db->table_exists('umoor_role_assignments')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS umoor_role_assignments (
              id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              year VARCHAR(10) NOT NULL DEFAULT '1448',
              umoor_id INT(11) NOT NULL,
              sub_committee_id INT(11) NULL,
              role ENUM('Coordinator', 'Team Lead', 'Team Member') NOT NULL,
              user_its VARCHAR(50) NOT NULL,
              assigned_by VARCHAR(100) NULL,
              assigned_at DATETIME NULL,
              status ENUM('Active', 'Transferred', 'Removed') NOT NULL DEFAULT 'Active',
              created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
              INDEX (year),
              INDEX (umoor_id),
              INDEX (sub_committee_id),
              INDEX (user_its),
              INDEX (role)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }
    }

    public function get_umoor_list()
    {
        return [
            1 => 'Umoor Deeniyah',
            2 => 'Umoor Talimiyah',
            3 => 'Umoor Kharijiyah',
            4 => 'Umoor Dakheliyah',
            5 => 'Umoor Maliyah',
            6 => 'Umoor Iqtesadiyah',
            7 => 'Umoor Sehat',
            8 => 'Umoor Faisala',
            9 => 'Umoor Ikram',
            10 => 'Umoor Dawat',
            11 => 'Umoor Mawaid',
            12 => 'Umoor Amlak'
        ];
    }

    public function get_sub_committees($umoor_id = null, $year = '1448')
    {
        $this->db->select('s.*, u.Full_Name as team_lead_name, u.Mobile as team_lead_mobile');
        $this->db->from('sub_committees s');
        $this->db->join('user u', 'u.ITS_ID = s.team_lead_its', 'left');
        if (!empty($umoor_id)) {
            $this->db->where('s.umoor_id', (int)$umoor_id);
        }
        $this->db->order_by('s.umoor_id ASC, s.name ASC');
        $rows = $this->db->get()->result_array();

        // Attach member counts for each team in the given year
        foreach ($rows as $k => $row) {
            $this->db->from('umoor_role_assignments');
            $this->db->where('sub_committee_id', $row['id']);
            $this->db->where('year', $year);
            $this->db->where('status', 'Active');
            $rows[$k]['members_count'] = $this->db->count_all_results();
        }

        return $rows;
    }

    public function search_members($q = '', $gender = '', $status = '', $limit = 10, $page = 1)
    {
        $offset = max(0, ($page - 1) * $limit);

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

        // Clone query for total count
        $count_db = clone $this->db;
        $total = $count_db->count_all_results();

        $this->db->select("ITS_ID, Full_Name, Mobile as Mobile_No, Email, Gender, COALESCE(Father_Name, Husband_Name, '') as HOF_Name, Inactive_Status");
        $this->db->order_by('Full_Name', 'ASC');
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
            'pages' => ceil($total / max(1, $limit))
        ];
    }

    public function get_assigned_members($year, $umoor_id, $sub_committee_id = null, $role = null)
    {
        $this->db->select("a.*, u.Full_Name as member_name, u.Mobile as Mobile_No, u.Email, u.Gender, COALESCE(u.Father_Name, u.Husband_Name, '') as HOF_Name");
        $this->db->from('umoor_role_assignments a');
        $this->db->join('user u', 'u.ITS_ID = a.user_its', 'left');
        $this->db->where('a.year', $year);
        $this->db->where('a.umoor_id', (int)$umoor_id);
        $this->db->where('a.status', 'Active');

        if ($sub_committee_id !== null && $sub_committee_id !== '') {
            $this->db->where('a.sub_committee_id', (int)$sub_committee_id);
        }

        if ($role !== null && $role !== '') {
            $this->db->where('a.role', $role);
        }

        $this->db->order_by('a.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function assign_role($year, $umoor_id, $sub_committee_id, $role, $user_its_array, $assigned_by = 'Admin')
    {
        if (empty($user_its_array)) {
            return ['success' => false, 'message' => 'No members selected for assignment.'];
        }

        if (!is_array($user_its_array)) {
            $user_its_array = [$user_its_array];
        }

        $now = date('Y-m-d H:i:s');
        $umoor_id = (int)$umoor_id;
        $sub_committee_id = $sub_committee_id ? (int)$sub_committee_id : null;

        if ($role === 'Coordinator') {
            // Rules: Only 1 member per Umoor per Year
            $its = trim($user_its_array[0]);
            
            // Deactivate previous coordinator for this year & umoor
            $this->db->where('year', $year)
                     ->where('umoor_id', $umoor_id)
                     ->where('role', 'Coordinator')
                     ->where('status', 'Active')
                     ->update('umoor_role_assignments', ['status' => 'Transferred']);

            // Insert new active coordinator
            $this->db->insert('umoor_role_assignments', [
                'year' => $year,
                'umoor_id' => $umoor_id,
                'sub_committee_id' => null,
                'role' => 'Coordinator',
                'user_its' => $its,
                'assigned_by' => $assigned_by,
                'assigned_at' => $now,
                'status' => 'Active',
                'created_at' => $now
            ]);

            return ['success' => true, 'message' => 'Umoor Coordinator assigned successfully.'];
        }
        else if ($role === 'Team Lead') {
            // Rules: Only 1 member per Team per Year
            if (!$sub_committee_id) {
                return ['success' => false, 'message' => 'Sub-Committee / Team must be selected for Team Lead.'];
            }
            $its = trim($user_its_array[0]);

            // Deactivate previous team lead for this team & year
            $this->db->where('year', $year)
                     ->where('sub_committee_id', $sub_committee_id)
                     ->where('role', 'Team Lead')
                     ->where('status', 'Active')
                     ->update('umoor_role_assignments', ['status' => 'Transferred']);

            // Insert new active team lead
            $this->db->insert('umoor_role_assignments', [
                'year' => $year,
                'umoor_id' => $umoor_id,
                'sub_committee_id' => $sub_committee_id,
                'role' => 'Team Lead',
                'user_its' => $its,
                'assigned_by' => $assigned_by,
                'assigned_at' => $now,
                'status' => 'Active',
                'created_at' => $now
            ]);

            // Sync sub_committees table team_lead_its for convenience
            $this->db->where('id', $sub_committee_id)->update('sub_committees', ['team_lead_its' => $its]);

            return ['success' => true, 'message' => 'Team Lead assigned successfully.'];
        }
        else if ($role === 'Team Member') {
            // Rules: Multiple members allowed
            if (!$sub_committee_id) {
                return ['success' => false, 'message' => 'Sub-Committee / Team must be selected for Team Members.'];
            }

            $added = 0;
            foreach ($user_its_array as $its_raw) {
                $its = trim($its_raw);
                if (!$its) continue;

                // Check if already active
                $exists = $this->db->where('year', $year)
                                   ->where('sub_committee_id', $sub_committee_id)
                                   ->where('role', 'Team Member')
                                   ->where('user_its', $its)
                                   ->where('status', 'Active')
                                   ->get('umoor_role_assignments')
                                   ->row_array();

                if (!$exists) {
                    $this->db->insert('umoor_role_assignments', [
                        'year' => $year,
                        'umoor_id' => $umoor_id,
                        'sub_committee_id' => $sub_committee_id,
                        'role' => 'Team Member',
                        'user_its' => $its,
                        'assigned_by' => $assigned_by,
                        'assigned_at' => $now,
                        'status' => 'Active',
                        'created_at' => $now
                    ]);
                    $added++;
                }
            }

            return ['success' => true, 'message' => "Successfully assigned {$added} member(s)."];
        }

        return ['success' => false, 'message' => 'Invalid role specified.'];
    }

    public function remove_assignment($id, $removed_by = 'Admin')
    {
        $assignment = $this->db->where('id', (int)$id)->get('umoor_role_assignments')->row_array();
        if (!$assignment) {
            return ['success' => false, 'message' => 'Assignment not found.'];
        }

        $this->db->where('id', (int)$id)->update('umoor_role_assignments', ['status' => 'Removed']);

        // If removed assignment was a Team Lead, check if sub_committees needs clear
        if ($assignment['role'] === 'Team Lead' && !empty($assignment['sub_committee_id'])) {
            $sc = $this->db->where('id', $assignment['sub_committee_id'])->get('sub_committees')->row_array();
            if ($sc && $sc['team_lead_its'] === $assignment['user_its']) {
                $this->db->where('id', $assignment['sub_committee_id'])->update('sub_committees', ['team_lead_its' => null]);
            }
        }

        return ['success' => true, 'message' => 'Assignment removed successfully.'];
    }

    public function get_assignment_history($year = '1448', $umoor_id = null, $sub_committee_id = null, $role = null)
    {
        $this->db->select('a.*, u.Full_Name as member_name, s.name as sub_committee_name');
        $this->db->from('umoor_role_assignments a');
        $this->db->join('user u', 'u.ITS_ID = a.user_its', 'left');
        $this->db->join('sub_committees s', 's.id = a.sub_committee_id', 'left');
        
        if (!empty($year)) {
            $this->db->where('a.year', $year);
        }
        if (!empty($umoor_id)) {
            $this->db->where('a.umoor_id', (int)$umoor_id);
        }
        if (!empty($sub_committee_id)) {
            $this->db->where('a.sub_committee_id', (int)$sub_committee_id);
        }
        if (!empty($role)) {
            $this->db->where('a.role', $role);
        }

        $this->db->order_by('a.id', 'DESC');
        $this->db->limit(30);
        return $this->db->get()->result_array();
    }

    public function get_full_hierarchy($year = '1448')
    {
        $umoor_list = $this->get_umoor_list();
        $hierarchy = [];

        // Fetch all coordinators for the year
        $coordinators = $this->db->select('a.*, u.Full_Name, u.Mobile as Mobile_No, u.Email')
                                 ->from('umoor_role_assignments a')
                                 ->join('user u', 'u.ITS_ID = a.user_its', 'left')
                                 ->where('a.year', $year)
                                 ->where('a.role', 'Coordinator')
                                 ->where('a.status', 'Active')
                                 ->get()->result_array();
        $coord_map = [];
        foreach ($coordinators as $c) {
            $coord_map[$c['umoor_id']] = $c;
        }

        // Fetch all sub-committees & team leads
        $sub_committees = $this->get_sub_committees(null, $year);
        $sc_map = [];
        foreach ($sub_committees as $sc) {
            $sc_map[$sc['umoor_id']][] = $sc;
        }

        // Build 12 Umoor tree
        foreach ($umoor_list as $uid => $uname) {
            $coord = $coord_map[$uid] ?? null;
            $teams = $sc_map[$uid] ?? [];

            $hierarchy[] = [
                'umoor_id' => $uid,
                'umoor_name' => $uname,
                'coordinator' => $coord ? [
                    'its' => $coord['user_its'],
                    'name' => $coord['Full_Name'] ?? $coord['user_its'],
                    'mobile' => $coord['Mobile_No'] ?? '',
                    'email' => $coord['Email'] ?? ''
                ] : null,
                'teams' => $teams
            ];
        }

        return $hierarchy;
    }
}
