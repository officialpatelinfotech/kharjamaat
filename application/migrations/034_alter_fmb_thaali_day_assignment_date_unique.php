<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Alter_fmb_thaali_day_assignment_date_unique extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('fmb_thaali_day_assignment')) {
            return;
        }

        // 1) Allow NULL menu_id so we can pre-assign dates even before a menu row exists.
        $col = $this->db->query("SHOW COLUMNS FROM `fmb_thaali_day_assignment` LIKE 'menu_id'")->row_array();
        if (!empty($col) && isset($col['Null']) && strtoupper((string)$col['Null']) === 'NO') {
            // Keep UNSIGNED, just make it nullable.
            $this->db->query("ALTER TABLE `fmb_thaali_day_assignment` MODIFY `menu_id` INT UNSIGNED NULL");
        }

        // 2) Enforce: one member per date per FY.
        $idx = $this->db->query("SHOW INDEX FROM `fmb_thaali_day_assignment` WHERE Key_name = 'uk_fmb_thaali_day_assignment_year_menu_date'")->result_array();
        if (empty($idx)) {
            $this->db->query("ALTER TABLE `fmb_thaali_day_assignment` ADD UNIQUE KEY `uk_fmb_thaali_day_assignment_year_menu_date` (`year`, `menu_date`) ");
        }

        // Helpful index for date range lookups
        $idx2 = $this->db->query("SHOW INDEX FROM `fmb_thaali_day_assignment` WHERE Key_name = 'idx_fmb_thaali_day_assignment_menu_date'")->result_array();
        if (empty($idx2)) {
            $this->db->query("ALTER TABLE `fmb_thaali_day_assignment` ADD KEY `idx_fmb_thaali_day_assignment_menu_date` (`menu_date`) ");
        }
    }

    public function down()
    {
        if (!$this->db->table_exists('fmb_thaali_day_assignment')) {
            return;
        }

        // Best-effort rollback: remove the unique key and index (keep menu_id nullable to avoid breaking existing data).
        $idx = $this->db->query("SHOW INDEX FROM `fmb_thaali_day_assignment` WHERE Key_name = 'uk_fmb_thaali_day_assignment_year_menu_date'")->result_array();
        if (!empty($idx)) {
            $this->db->query("ALTER TABLE `fmb_thaali_day_assignment` DROP INDEX `uk_fmb_thaali_day_assignment_year_menu_date`");
        }

        $idx2 = $this->db->query("SHOW INDEX FROM `fmb_thaali_day_assignment` WHERE Key_name = 'idx_fmb_thaali_day_assignment_menu_date'")->result_array();
        if (!empty($idx2)) {
            $this->db->query("ALTER TABLE `fmb_thaali_day_assignment` DROP INDEX `idx_fmb_thaali_day_assignment_menu_date`");
        }
    }
}
