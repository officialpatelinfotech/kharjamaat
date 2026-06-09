<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_miqaat_type_to_fmb_general_contribution extends CI_Migration
{
    public function up()
    {
        // Add miqaat_type to fmb_general_contribution_master if not exists
        if (!$this->db->field_exists('miqaat_type', 'fmb_general_contribution_master')) {
            $this->db->query("ALTER TABLE `fmb_general_contribution_master` ADD `miqaat_type` VARCHAR(100) NULL DEFAULT NULL AFTER `status`");
        }

        // Add miqaat_type to fmb_general_contribution if not exists
        if (!$this->db->field_exists('miqaat_type', 'fmb_general_contribution')) {
            $this->db->query("ALTER TABLE `fmb_general_contribution` ADD `miqaat_type` VARCHAR(100) NULL DEFAULT NULL AFTER `payment_date`");
        }
    }

    public function down()
    {
        if ($this->db->field_exists('miqaat_type', 'fmb_general_contribution_master')) {
            $this->db->query("ALTER TABLE `fmb_general_contribution_master` DROP COLUMN `miqaat_type`");
        }

        if ($this->db->field_exists('miqaat_type', 'fmb_general_contribution')) {
            $this->db->query("ALTER TABLE `fmb_general_contribution` DROP COLUMN `miqaat_type`");
        }
    }
}
