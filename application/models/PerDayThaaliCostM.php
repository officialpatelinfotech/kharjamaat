<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class PerDayThaaliCostM extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        // Sort by starting hijri year numerically, descending
        $this->db->from('fmb_per_day_thaali_cost');
        $this->db->order_by("CAST(SUBSTRING_INDEX(`year`, '-', 1) AS UNSIGNED)", 'DESC', false);
        $this->db->order_by('id', 'DESC');
        $rows = $this->db->get()->result_array();
        return is_array($rows) ? $rows : [];
    }

    public function get_by_id($id)
    {
        $this->db->from('fmb_per_day_thaali_cost');
        $this->db->where('id', (int)$id);
        $row = $this->db->get()->row_array();
        return $row ?: null;
    }

    public function get_by_year($year)
    {
        $this->db->from('fmb_per_day_thaali_cost');
        $this->db->where('year', $year);
        $row = $this->db->get()->row_array();
        return $row ?: null;
    }

    public function save($id, $year, $amount)
    {
        $duplicateMsg = "You can't add another amount for this year. It has already been created. You can either edit it or delete it and then create.";
        $data = [
            'year' => $year,
            'amount' => (float)$amount,
        ];

        if ($id) {
            $existing = $this->get_by_year($year);
            if ($existing && (int)$existing['id'] !== (int)$id) {
                return ['success' => false, 'error' => $duplicateMsg];
            }

            $this->db->where('id', (int)$id);
            $this->db->update('fmb_per_day_thaali_cost', $data);
            return ['success' => ($this->db->affected_rows() >= 0)];
        }

        $existing = $this->get_by_year($year);
        if ($existing) {
            return ['success' => false, 'error' => $duplicateMsg];
        }

        $this->db->insert('fmb_per_day_thaali_cost', $data);
        return ['success' => ($this->db->affected_rows() > 0), 'id' => $this->db->insert_id()];
    }

    public function delete($id)
    {
        $this->db->where('id', (int)$id);
        $this->db->delete('fmb_per_day_thaali_cost');
        return $this->db->affected_rows() > 0;
    }
}
