<?php
defined('BASEPATH') or exit('No direct script access allowed');

class QardanHasanaM extends CI_Model
{
    public function upsert($payload)
    {
        $its = isset($payload['ITS_ID']) ? trim((string)$payload['ITS_ID']) : '';
        if ($its === '') {
            return ['action' => 'skipped', 'id' => null];
        }

        $data = $payload;
        unset($data['ITS_ID']);

        $existing = $this->db->select('id')->from('qardan_hasana')->where('ITS_ID', $its)->get()->row_array();
        if (!empty($existing) && isset($existing['id'])) {
            $this->db->where('ITS_ID', $its)->update('qardan_hasana', $data);
            return ['action' => 'updated', 'id' => (int)$existing['id']];
        }

        $this->db->insert('qardan_hasana', $payload);
        return ['action' => 'inserted', 'id' => (int)$this->db->insert_id()];
    }

    public function get_all()
    {
        $this->db->select('qardan_hasana.*, `user`.Full_Name');
        $this->db->from('qardan_hasana');
        $this->db->join('user', 'user.ITS_ID = qardan_hasana.ITS_ID', 'left');
        $this->db->order_by('qardan_hasana.id', 'ASC');
        return $this->db->get()->result_array();
    }

    public function create($payload)
    {
        $res = $this->upsert($payload);
        return $res['id'];
    }

    public function get_by_id($id)
    {
        $this->db->select('qardan_hasana.*, `user`.Full_Name');
        $this->db->from('qardan_hasana');
        $this->db->join('user', 'user.ITS_ID = qardan_hasana.ITS_ID', 'left');
        $this->db->where('qardan_hasana.id', $id);
        return $this->db->get()->row_array();
    }

    public function get_by_its($its_id)
    {
        $this->db->select('qardan_hasana.*, `user`.Full_Name');
        $this->db->from('qardan_hasana');
        $this->db->join('user', 'user.ITS_ID = qardan_hasana.ITS_ID', 'left');
        $this->db->where('qardan_hasana.ITS_ID', $its_id);
        return $this->db->get()->row_array();
    }
}
