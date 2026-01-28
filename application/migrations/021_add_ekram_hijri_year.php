<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_ekram_hijri_year extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('ekram_fund') && !$this->db->field_exists('hijri_year', 'ekram_fund')) {
            $this->db->query("ALTER TABLE `ekram_fund` ADD COLUMN `hijri_year` INT NULL AFTER `amount`;");
        }
    }

    public function down()
    {
        if ($this->db->table_exists('ekram_fund') && $this->db->field_exists('hijri_year', 'ekram_fund')) {
            $this->db->query("ALTER TABLE `ekram_fund` DROP COLUMN `hijri_year`;");
        }
    }
}
