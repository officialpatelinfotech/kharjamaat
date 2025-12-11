<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_year_to_ashara_attendance extends CI_Migration {
    public function up()
    {
        // Add year column if it doesn't exist
        if (!$this->db->field_exists('year', 'ashara_attendance')) {
            $this->load->dbforge();
            $fields = [
                'year' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE,
                    'default' => NULL,
                ],
            ];
            $this->dbforge->add_column('ashara_attendance', $fields);
        }

        // Backfill existing rows with current Hijri year when NULL
        $current_hijri_year = null;
        // Try to read hijri year from hijri_calendar for today's greg date
        $row = $this->db->query("SELECT hijri_date FROM hijri_calendar WHERE greg_date = CURDATE() LIMIT 1")->row_array();
        if ($row && !empty($row['hijri_date'])) {
            // hijri_date expected format: d-m-Y (Y is hijri year)
            $parts = explode('-', $row['hijri_date']);
            if (count($parts) === 3) {
                $current_hijri_year = (int)$parts[2];
            }
        }
        if ($current_hijri_year) {
            $this->db->query("UPDATE ashara_attendance SET year = ? WHERE year IS NULL", [$current_hijri_year]);
        }

        // Add composite unique index on (ITS, year) if not exists
        // Check existing indexes
        $idx = $this->db->query("SHOW INDEX FROM ashara_attendance WHERE Key_name = 'uniq_attendance_its_year'")->result_array();
        if (empty($idx)) {
            // Some MySQL variants require both columns to be NOT NULL to enforce UNIQUE strictly; here year may be NULL for new rows prior to code update.
            $this->db->query("ALTER TABLE ashara_attendance ADD UNIQUE KEY uniq_attendance_its_year (ITS, year)");
        }
    }

    public function down()
    {
        // Drop unique key if exists
        $idx = $this->db->query("SHOW INDEX FROM ashara_attendance WHERE Key_name = 'uniq_attendance_its_year'")->result_array();
        if (!empty($idx)) {
            $this->db->query("ALTER TABLE ashara_attendance DROP INDEX uniq_attendance_its_year");
        }

        // Drop year column if exists
        if ($this->db->field_exists('year', 'ashara_attendance')) {
            $this->load->dbforge();
            $this->dbforge->drop_column('ashara_attendance', 'year');
        }
    }
}
