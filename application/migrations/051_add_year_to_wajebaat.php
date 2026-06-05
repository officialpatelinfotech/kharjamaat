<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_year_to_wajebaat extends CI_Migration {
    public function up()
    {
        if ($this->db->table_exists('wajebaat') && !$this->db->field_exists('year', 'wajebaat')) {
            $this->load->dbforge();
            $fields = [
                'year' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE,
                    'default' => NULL,
                ],
            ];
            $this->dbforge->add_column('wajebaat', $fields);
            
            // Backfill year
            $current_hijri_year = null;
            $row = $this->db->query("SELECT hijri_date FROM hijri_calendar WHERE greg_date = CURDATE() LIMIT 1")->row_array();
            if ($row && !empty($row['hijri_date'])) {
                $parts = explode('-', $row['hijri_date']);
                if (count($parts) === 3) {
                    $current_hijri_year = (int)$parts[2];
                }
            }
            if ($current_hijri_year) {
                $this->db->query("UPDATE wajebaat SET year = ? WHERE year IS NULL", [$current_hijri_year]);
            }
            
            // Drop old index idx_its_id
            $idx = $this->db->query("SHOW INDEX FROM wajebaat WHERE Key_name = 'idx_its_id'")->result_array();
            if (!empty($idx)) {
                $this->db->query("ALTER TABLE wajebaat DROP INDEX idx_its_id");
            }
            
            // Add composite unique index on (ITS_ID, year)
            $this->db->query("ALTER TABLE wajebaat ADD UNIQUE KEY uniq_wajebaat_its_year (ITS_ID, year)");
        }
    }

    public function down()
    {
        if ($this->db->table_exists('wajebaat')) {
            $idx = $this->db->query("SHOW INDEX FROM wajebaat WHERE Key_name = 'uniq_wajebaat_its_year'")->result_array();
            if (!empty($idx)) {
                $this->db->query("ALTER TABLE wajebaat DROP INDEX uniq_wajebaat_its_year");
            }
            
            // Re-create idx_its_id
            $idx = $this->db->query("SHOW INDEX FROM wajebaat WHERE Key_name = 'idx_its_id'")->result_array();
            if (empty($idx)) {
                $this->db->query("ALTER TABLE wajebaat ADD KEY idx_its_id (ITS_ID)");
            }

            if ($this->db->field_exists('year', 'wajebaat')) {
                $this->load->dbforge();
                $this->dbforge->drop_column('wajebaat', 'year');
            }
        }
    }
}
