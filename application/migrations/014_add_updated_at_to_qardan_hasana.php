<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_updated_at_to_qardan_hasana extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('qardan_hasana')) {
            $columns = $this->db->query("SHOW COLUMNS FROM `qardan_hasana` LIKE 'updated_at'")->result_array();
            if (empty($columns)) {
                $this->db->query("ALTER TABLE `qardan_hasana`
                    ADD COLUMN `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`
                ");
            }
        }
    }

    public function down()
    {
        if ($this->db->table_exists('qardan_hasana')) {
            $columns = $this->db->query("SHOW COLUMNS FROM `qardan_hasana` LIKE 'updated_at'")->result_array();
            if (!empty($columns)) {
                $this->db->query("ALTER TABLE `qardan_hasana` DROP COLUMN `updated_at`");
            }
        }
    }
}
