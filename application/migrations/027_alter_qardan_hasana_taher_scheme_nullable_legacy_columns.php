<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Alter_qardan_hasana_taher_scheme_nullable_legacy_columns extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('qardan_hasana_taher_scheme')) {
            return;
        }

        // Legacy columns from earlier schema can block Taher imports if they are NOT NULL.
        // Make them nullable so Taher inserts that only include ITS/unit/units/miqaat_name succeed.
        if ($this->db->field_exists('hijri_date', 'qardan_hasana_taher_scheme')) {
            $this->db->query("ALTER TABLE `qardan_hasana_taher_scheme` MODIFY COLUMN `hijri_date` VARCHAR(32) NULL");
        }

        if ($this->db->field_exists('eng_date', 'qardan_hasana_taher_scheme')) {
            $this->db->query("ALTER TABLE `qardan_hasana_taher_scheme` MODIFY COLUMN `eng_date` DATE NULL");
        }
    }

    public function down()
    {
        // No safe rollback: reverting to NOT NULL could break if NULLs exist.
    }
}
