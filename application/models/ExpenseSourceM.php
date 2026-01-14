<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class ExpenseSourceM extends CI_Model
{
    protected $table = 'expense_sources';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        return $this->db->order_by('id','ASC')->get($this->table)->result_array();
    }

    public function get($id)
    {
        return $this->db->where('id', (int)$id)->get($this->table)->row_array();
    }

    public function create($data)
    {
        $payload = [
            'name' => isset($data['name']) ? $data['name'] : '',
            'status' => isset($data['status']) ? $data['status'] : 'Active',
        ];
        $ok = $this->db->insert($this->table, $payload);
        if ($ok) {
            $id = (int)$this->db->insert_id();
            if ($id > 0) {
                return $id;
            }
            // Fallback: attempt to look up the latest row for this name
            $row = $this->db->select('id')
                ->from($this->table)
                ->where('name', $payload['name'])
                ->order_by('id', 'DESC')
                ->limit(1)
                ->get()->row_array();
            if (!empty($row['id'])) {
                return (int)$row['id'];
            }
            return false;
        }
            $err = $this->db->error();
            if (!empty($err['message'])) {
                log_message('error', 'ExpenseSourceM::create DB error: ' . $err['message']);
            }
            return false;
    }

    public function update($id, $data)
    {
        $payload = [];
        if (isset($data['name'])) $payload['name'] = $data['name'];
        if (isset($data['status'])) $payload['status'] = $data['status'];
        if (empty($payload)) return false;
        $this->db->where('id', (int)$id)->update($this->table, $payload);
        return ($this->db->affected_rows() >= 0);
    }

    public function delete($id)
    {
        $this->db->where('id', (int)$id)->delete($this->table);
        return ($this->db->affected_rows() > 0);
    }

    /**
     * Check if any expenses are linked to this source id.
     */
    public function has_expenses($id)
    {
        $id = (int)$id;
        if ($id <= 0) {
            return false;
        }
        return $this->db
            ->where('source_id', $id)
            ->from('expenses')
            ->count_all_results() > 0;
    }

    /**
     * Delete a source and all related expenses in a transaction.
     */
    public function delete_with_expenses($id)
    {
        $id = (int)$id;
        if ($id <= 0) {
            return false;
        }

        $this->db->trans_start();
        // First remove expenses that reference this source to satisfy FK constraint
        $this->db->where('source_id', $id)->delete('expenses');
        // Then delete the source itself
        $this->db->where('id', $id)->delete($this->table);
        $this->db->trans_complete();

        return $this->db->trans_status();
    }
}
