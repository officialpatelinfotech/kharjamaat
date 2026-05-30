<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class PropertyM extends CI_Model
{
    protected $table = 'properties';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        return $this->db->order_by('name', 'ASC')->get($this->table)->result_array();
    }

    public function get_by_id($id)
    {
        return $this->db->where('id', (int)$id)->get($this->table)->row_array();
    }

    public function exists($name, $exclude_id = null)
    {
        $name = trim((string)$name);
        $this->db->where('LOWER(TRIM(name)) =', strtolower($name));
        if ($exclude_id !== null) {
            $this->db->where('id !=', (int)$exclude_id);
        }
        return $this->db->from($this->table)->count_all_results() > 0;
    }

    public function create($name)
    {
        $payload = [
            'name' => trim((string)$name),
            'created_at' => date('Y-m-d H:i:s')
        ];
        $ok = $this->db->insert($this->table, $payload);
        if ($ok) {
            return (int)$this->db->insert_id();
        }
        return false;
    }

    public function update($id, $name)
    {
        $payload = [
            'name' => trim((string)$name)
        ];
        $this->db->where('id', (int)$id)->update($this->table, $payload);
        return ($this->db->affected_rows() >= 0);
    }

    public function delete($id)
    {
        $this->db->where('id', (int)$id)->delete($this->table);
        return ($this->db->affected_rows() > 0);
    }
}
