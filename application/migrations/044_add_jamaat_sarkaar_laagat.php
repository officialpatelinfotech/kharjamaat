<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_jamaat_sarkaar_laagat extends CI_Migration
{
    public function up()
    {
        // 1. Alter laagat_rent_grade_amounts
        if ($this->db->table_exists('laagat_rent_grade_amounts')) {
            if (!$this->db->field_exists('jamaat_amount', 'laagat_rent_grade_amounts')) {
                $this->db->query("ALTER TABLE `laagat_rent_grade_amounts` ADD `jamaat_amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00' AFTER `sabeel_takhmeen_grade_id`;");
                // Backfill existing grade amounts (jamaat_amount = amount)
                $this->db->query("UPDATE `laagat_rent_grade_amounts` SET `jamaat_amount` = `amount` WHERE `jamaat_amount` = 0.00");
            }
            if (!$this->db->field_exists('sarkaar_amount', 'laagat_rent_grade_amounts')) {
                $this->db->query("ALTER TABLE `laagat_rent_grade_amounts` ADD `sarkaar_amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00' AFTER `jamaat_amount`;");
            }
        }

        // 2. Alter laagat_rent_invoices
        if ($this->db->table_exists('laagat_rent_invoices')) {
            if (!$this->db->field_exists('jamaat_amount', 'laagat_rent_invoices')) {
                $this->db->query("ALTER TABLE `laagat_rent_invoices` ADD `jamaat_amount` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `amount`;");
                // Backfill existing invoices (jamaat_amount = amount)
                $this->db->query("UPDATE `laagat_rent_invoices` SET `jamaat_amount` = `amount` WHERE `jamaat_amount` = 0.00");
            }
            if (!$this->db->field_exists('sarkaar_amount', 'laagat_rent_invoices')) {
                $this->db->query("ALTER TABLE `laagat_rent_invoices` ADD `sarkaar_amount` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `jamaat_amount`;");
            }
        }
    }

    public function down()
    {
        // 1. Alter laagat_rent_grade_amounts
        if ($this->db->table_exists('laagat_rent_grade_amounts')) {
            if ($this->db->field_exists('jamaat_amount', 'laagat_rent_grade_amounts')) {
                $this->db->query("ALTER TABLE `laagat_rent_grade_amounts` DROP COLUMN `jamaat_amount`;");
            }
            if ($this->db->field_exists('sarkaar_amount', 'laagat_rent_grade_amounts')) {
                $this->db->query("ALTER TABLE `laagat_rent_grade_amounts` DROP COLUMN `sarkaar_amount`;");
            }
        }

        // 2. Alter laagat_rent_invoices
        if ($this->db->table_exists('laagat_rent_invoices')) {
            if ($this->db->field_exists('jamaat_amount', 'laagat_rent_invoices')) {
                $this->db->query("ALTER TABLE `laagat_rent_invoices` DROP COLUMN `jamaat_amount`;");
            }
            if ($this->db->field_exists('sarkaar_amount', 'laagat_rent_invoices')) {
                $this->db->query("ALTER TABLE `laagat_rent_invoices` DROP COLUMN `sarkaar_amount`;");
            }
        }
    }
}
