<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_fmbgc_payment_fields extends CI_Migration
{
    public function up()
    {
        // Detect existing columns; add if missing.
        $table = 'fmb_general_contribution';
        $existing = [];
        $cols = $this->db->query("SHOW COLUMNS FROM `$table`")->result_array();
        foreach ($cols as $c) {
            $existing[$c['Field']] = true;
        }

        if (!isset($existing['payment_method'])) {
            $this->db->query("ALTER TABLE `$table` ADD `payment_method` VARCHAR(50) NULL AFTER `amount`");
        }
        if (!isset($existing['payment_received_amount'])) {
            $this->db->query("ALTER TABLE `$table` ADD `payment_received_amount` DECIMAL(10,2) NULL AFTER `payment_method`");
        }
        if (!isset($existing['payment_remarks'])) {
            $this->db->query("ALTER TABLE `$table` ADD `payment_remarks` TEXT NULL AFTER `payment_received_amount`");
        }
        if (!isset($existing['updated_at'])) {
            $this->db->query("ALTER TABLE `$table` ADD `updated_at` DATETIME NULL AFTER `payment_remarks`");
        }

        // Backfill payment_received_amount with amount where already fully paid but no value
        $this->db->query("UPDATE `$table` SET payment_received_amount = amount WHERE payment_status = 1 AND (payment_received_amount IS NULL OR payment_received_amount = 0)");
    }

    public function down()
    {
        // Non-destructive: leave columns in place (data retention). Uncomment if removal desired.
        // $this->db->query("ALTER TABLE `fmb_general_contribution` DROP COLUMN `payment_method`");
        // $this->db->query("ALTER TABLE `fmb_general_contribution` DROP COLUMN `payment_received_amount`");
        // $this->db->query("ALTER TABLE `fmb_general_contribution` DROP COLUMN `payment_remarks`");
        // $this->db->query("ALTER TABLE `fmb_general_contribution` DROP COLUMN `updated_at`");
    }
}
