<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_in_its_csv_column extends CI_Migration
{
    private $table = 'user';

    public function up()
    {
        $existing = $this->_existing_columns();

        if (!isset($existing['in_its_csv'])) {
            $this->db->query(
                "ALTER TABLE `{$this->table}`
                 ADD COLUMN `in_its_csv` TINYINT(1) NOT NULL DEFAULT 1
                 COMMENT '1 if present in latest ITS CSV import, 0 otherwise'
                 AFTER `its_sabeel_match`"
            );
        }
    }

    public function down()
    {
        // Non-destructive by default.
    }

    private function _existing_columns(): array
    {
        $rows = $this->db->query("SHOW COLUMNS FROM `{$this->table}`")->result_array();
        $map  = [];
        foreach ($rows as $r) {
            $map[$r['Field']] = true;
        }
        return $map;
    }
}
