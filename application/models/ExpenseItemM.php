<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class ExpenseItemM extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all()
    {
        $this->db->select('*');
        $this->db->from('expense_items');
        $this->db->order_by('id DESC');
        return $this->db->get()->result_array();
    }

    public function get_all_active()
    {
        $this->db->select('*');
        $this->db->from('expense_items');
        $this->db->where('status', 'Active');
        $this->db->order_by('id DESC');
        return $this->db->get()->result_array();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('expense_items', ['id' => (int)$id])->row_array();
    }

    public function create($data)
    {
        $this->db->insert('expense_items', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', (int)$id);
        return $this->db->update('expense_items', $data);
    }

    public function delete_with_expenses($id)
    {
        $id = (int)$id;
        $this->db->trans_start();
        // Delete any expenses referencing this item
        $this->db->where('item_id', $id)->delete('expenses');
        // Delete the item itself
        $this->db->where('id', $id)->delete('expense_items');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}
