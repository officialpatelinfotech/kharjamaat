<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_sabeel_rent_deposit_columns extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('laagat_rent')) {
            if (!$this->db->field_exists('rent_sabeel', 'laagat_rent')) {
                $this->db->query("ALTER TABLE `laagat_rent` ADD `rent_sabeel` DECIMAL(12,2) NOT NULL DEFAULT '0.00' AFTER `amount`;");
            }
            if (!$this->db->field_exists('rent_non_sabeel', 'laagat_rent')) {
                $this->db->query("ALTER TABLE `laagat_rent` ADD `rent_non_sabeel` DECIMAL(12,2) NOT NULL DEFAULT '0.00' AFTER `rent_sabeel`;");
            }
            if (!$this->db->field_exists('deposit_sabeel', 'laagat_rent')) {
                $this->db->query("ALTER TABLE `laagat_rent` ADD `deposit_sabeel` DECIMAL(12,2) NOT NULL DEFAULT '0.00' AFTER `rent_non_sabeel`;");
            }
            if (!$this->db->field_exists('deposit_non_sabeel', 'laagat_rent')) {
                $this->db->query("ALTER TABLE `laagat_rent` ADD `deposit_non_sabeel` DECIMAL(12,2) NOT NULL DEFAULT '0.00' AFTER `deposit_sabeel`;");
            }
        }

        if ($this->db->table_exists('laagat_rent_invoices')) {
            if (!$this->db->field_exists('deposit_amount', 'laagat_rent_invoices')) {
                $this->db->query("ALTER TABLE `laagat_rent_invoices` ADD `deposit_amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00' AFTER `amount`;");
            }
        }
    }

    public function down()
    {
        if ($this->db->table_exists('laagat_rent')) {
            if ($this->db->field_exists('rent_sabeel', 'laagat_rent')) {
                $this->db->query("ALTER TABLE `laagat_rent` DROP COLUMN `rent_sabeel`;");
            }
            if ($this->db->field_exists('rent_non_sabeel', 'laagat_rent')) {
                $this->db->query("ALTER TABLE `laagat_rent` DROP COLUMN `rent_non_sabeel`;");
            }
            if ($this->db->field_exists('deposit_sabeel', 'laagat_rent')) {
                $this->db->query("ALTER TABLE `laagat_rent` DROP COLUMN `deposit_sabeel`;");
            }
            if ($this->db->field_exists('deposit_non_sabeel', 'laagat_rent')) {
                $this->db->query("ALTER TABLE `laagat_rent` DROP COLUMN `deposit_non_sabeel`;");
            }
        }

        if ($this->db->table_exists('laagat_rent_invoices')) {
            if ($this->db->field_exists('deposit_amount', 'laagat_rent_invoices')) {
                $this->db->query("ALTER TABLE `laagat_rent_invoices` DROP COLUMN `deposit_amount`;");
            }
        }
    }
}
