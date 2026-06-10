<?php
defined('BASEPATH') or exit('No direct script access allowed');

class WajebaatM extends CI_Model
{
    public function upsert($payload)
    {
        $its = isset($payload['ITS_ID']) ? trim((string)$payload['ITS_ID']) : '';
        if ($its === '') {
            return ['action' => 'skipped', 'id' => null];
        }

        $year = isset($payload['year']) ? (int)$payload['year'] : 0;
        if ($year <= 0) {
            $this->load->model('HijriCalendar');
            $today_hijri = $this->HijriCalendar->get_hijri_date(date('Y-m-d'));
            $parts = explode('-', $today_hijri['hijri_date']);
            $year = (int)$parts[2];
            $payload['year'] = $year;
        }

        $data = $payload;
        unset($data['ITS_ID']);
        unset($data['year']);

        $existing = $this->db->select('id')
            ->from('wajebaat')
            ->where('ITS_ID', $its)
            ->where('year', $year)
            ->get()
            ->row_array();

        if (!empty($existing) && isset($existing['id'])) {
            $this->db->where('ITS_ID', $its)->where('year', $year)->update('wajebaat', $data);
            return ['action' => 'updated', 'id' => (int)$existing['id']];
        }

        $this->db->insert('wajebaat', $payload);
        return ['action' => 'inserted', 'id' => (int)$this->db->insert_id()];
    }

    public function get_all($year = null)
    {
        $this->db->select('wajebaat.*, `user`.Full_Name');
        $this->db->from('wajebaat');
        $this->db->join('user', 'user.ITS_ID = wajebaat.ITS_ID', 'left');
        if ($year !== null) {
            $this->db->where('wajebaat.year', (int)$year);
        }
        $this->db->order_by('wajebaat.id', 'ASC');
        return $this->db->get()->result_array();
    }

    public function create($payload)
    {
        $res = $this->upsert($payload);
        return $res['id'];
    }

    public function get_by_id($id)
    {
        $this->db->select('wajebaat.*, `user`.Full_Name');
        $this->db->from('wajebaat');
        $this->db->join('user', 'user.ITS_ID = wajebaat.ITS_ID', 'left');
        $this->db->where('wajebaat.id', $id);
        return $this->db->get()->row_array();
    }

    public function get_by_its($its_id, $year = null)
    {
        $this->db->select('wajebaat.*, `user`.Full_Name');
        $this->db->from('wajebaat');
        $this->db->join('user', 'user.ITS_ID = wajebaat.ITS_ID', 'left');
        $this->db->where('wajebaat.ITS_ID', $its_id);
        if ($year !== null) {
            $this->db->where('wajebaat.year', (int)$year);
        }
        $this->db->order_by('wajebaat.year', 'DESC');
        $this->db->limit(1);
        return $this->db->get()->row_array();
    }

    public function get_years()
    {
        $this->load->model('HijriCalendar');
        $years = $this->HijriCalendar->get_distinct_hijri_years();
        rsort($years);
        return $years;
    }
}
