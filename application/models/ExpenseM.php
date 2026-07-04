<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class ExpenseM extends CI_Model
{
    protected $table = 'expenses';

    public function __construct()
    {
        parent::__construct();
    }

    public function get($id)
    {
        return $this->db->where('id', (int)$id)->get($this->table)->row_array();
    }

    /**
     * Get list of expenses with optional filters.
     * Filters keys: item (string), sof (int source_id), hijri_year (int),
     * date_from (Y-m-d), date_to (Y-m-d).
     */
    public function get_list($filters = [])
    {
            $limit = isset($filters['limit']) ? (int)$filters['limit'] : null;

            $this->db->select('e.*, s.name AS source_name, i.sector_name, i.sector_code, i.sub_sector_name, i.sub_sector_code, i.item_name, i.item_code')
                ->from($this->table . ' AS e')
                ->join('expense_sources AS s', 's.id = e.source_id', 'left')
                ->join('expense_items AS i', 'i.id = e.item_id', 'left');

        if (!empty($filters['item'])) {
            $this->db->group_start();
            $this->db->like('i.item_name', $filters['item']);
            $this->db->or_like('i.sector_name', $filters['item']);
            $this->db->or_like('i.sub_sector_name', $filters['item']);
            $this->db->group_end();
        }
        if (!empty($filters['sof'])) {
            $this->db->where('e.source_id', (int)$filters['sof']);
        }
        if (!empty($filters['hijri_year'])) {
            $this->db->where('e.hijri_year', (int)$filters['hijri_year']);
        }
        if (!empty($filters['date_from'])) {
            $this->db->where('e.expense_date >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $this->db->where('e.expense_date <=', $filters['date_to']);
        }

        $this->db->order_by('e.expense_date', 'DESC');
        $this->db->order_by('e.id', 'DESC');

        if ($limit && $limit > 0) {
            $this->db->limit($limit);
        }

        return $this->db->get()->result_array();
    }

    /**
     * Create a new expense record.
     */
    public function create($data)
    {
        $payload = [
            'expense_date' => isset($data['expense_date']) ? $data['expense_date'] : date('Y-m-d'),
            'item_id'      => !empty($data['item_id']) ? (int)$data['item_id'] : null,
            'amount'       => isset($data['amount']) ? (float)$data['amount'] : 0,
            'source_id'    => isset($data['source_id']) ? (int)$data['source_id'] : 0,
            'hijri_year'   => isset($data['hijri_year']) ? (int)$data['hijri_year'] : 0,
            'notes'        => isset($data['notes']) ? $data['notes'] : null,
        ];

        $ok = $this->db->insert($this->table, $payload);
        if ($ok) {
            return (int)$this->db->insert_id();
        }
        return false;
    }

    public function update($id, $data)
    {
        $payload = [];
        if (isset($data['expense_date'])) $payload['expense_date'] = $data['expense_date'];
        if (array_key_exists('item_id', $data)) $payload['item_id'] = ($data['item_id'] !== '' && $data['item_id'] !== null) ? (int)$data['item_id'] : null;
        if (isset($data['amount'])) $payload['amount'] = (float)$data['amount'];
        if (isset($data['source_id'])) $payload['source_id'] = (int)$data['source_id'];
        if (isset($data['hijri_year'])) $payload['hijri_year'] = (int)$data['hijri_year'];
        if (array_key_exists('notes', $data)) $payload['notes'] = $data['notes'];

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
     * Distinct Hijri years from existing expenses, newest first.
     */
    public function get_distinct_hijri_years()
    {
        $this->load->model('HijriCalendar');
        $years = $this->HijriCalendar->get_distinct_hijri_years();
        rsort($years);
        return $years;
    }
}
