<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class ExpenseAreaM extends CI_Model
{
    protected $table = 'expense_areas';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_active()
    {
        // Treat numeric 1 or string 'Active' as active
        $this->db->from($this->table);
        $this->db->order_by('name', 'ASC');
        $rows = $this->db->get()->result_array();
        $out = [];
        foreach ($rows as $r) {
            $st = isset($r['status']) ? $r['status'] : 'Active';
            $isActive = false;
            if (is_numeric($st)) {
                $isActive = ((int)$st) === 1;
            } else {
                $isActive = strtolower(trim((string)$st)) === 'active';
            }
            if ($isActive) {
                $out[] = $r;
            }
        }
        return $out;
    }

    public function get_or_create_by_name($name)
    {
        $name = trim((string)$name);
        if ($name === '') {
            return null;
        }

        // Try case-insensitive match on name
        $row = $this->db->from($this->table)
            ->where('LOWER(name) =', strtolower($name))
            ->limit(1)
            ->get()->row_array();
        if ($row && isset($row['id'])) {
            return (int)$row['id'];
        }

        $payload = [
            'name' => $name,
            'status' => 'Active',
        ];
        $ok = $this->db->insert($this->table, $payload);
        if ($ok) {
            return (int)$this->db->insert_id();
        }
        return null;
    }
}
