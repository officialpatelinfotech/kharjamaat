<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_updated_at_to_wajebaat extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('wajebaat')) {
            // Add updated_at column if not exists
            $columns = $this->db->query("SHOW COLUMNS FROM `wajebaat` LIKE 'updated_at'")->result_array();
            if (empty($columns)) {
                $this->db->query("ALTER TABLE `wajebaat` 
                    ADD COLUMN `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`
                ");
            }
        }
    }

    public function down()
    {
        if ($this->db->table_exists('wajebaat')) {
            $columns = $this->db->query("SHOW COLUMNS FROM `wajebaat` LIKE 'updated_at'")->result_array();
            if (!empty($columns)) {
                $this->db->query("ALTER TABLE `wajebaat` DROP COLUMN `updated_at`");
            }
        }
    }
}
