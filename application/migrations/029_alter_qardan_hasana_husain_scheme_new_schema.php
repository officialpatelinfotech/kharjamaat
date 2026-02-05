<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Alter_qardan_hasana_husain_scheme_new_schema extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('qardan_hasana_husain_scheme')) {
            // Create fresh table if it doesn't exist
            $this->db->query("CREATE TABLE `qardan_hasana_husain_scheme` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `uploaded_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `ITS` VARCHAR(20) NOT NULL,
                `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `deposit_date` DATE NULL,
                `maturity_date` DATE NULL,
                `duration` VARCHAR(32) NOT NULL DEFAULT '',
                PRIMARY KEY (`id`),
                KEY `idx_its` (`ITS`),
                KEY `idx_deposit_date` (`deposit_date`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            return;
        }

        // Ensure base columns exist
        if (!$this->db->field_exists('uploaded_date', 'qardan_hasana_husain_scheme')) {
            $this->db->query("ALTER TABLE `qardan_hasana_husain_scheme` ADD COLUMN `uploaded_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `id`");
        }

        if (!$this->db->field_exists('ITS', 'qardan_hasana_husain_scheme')) {
            $this->db->query("ALTER TABLE `qardan_hasana_husain_scheme` ADD COLUMN `ITS` VARCHAR(20) NOT NULL AFTER `uploaded_date`");
            $this->db->query("ALTER TABLE `qardan_hasana_husain_scheme` ADD KEY `idx_its` (`ITS`)");
        }

        if (!$this->db->field_exists('amount', 'qardan_hasana_husain_scheme')) {
            $this->db->query("ALTER TABLE `qardan_hasana_husain_scheme` ADD COLUMN `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER `ITS`");
        }

        if (!$this->db->field_exists('deposit_date', 'qardan_hasana_husain_scheme')) {
            $this->db->query("ALTER TABLE `qardan_hasana_husain_scheme` ADD COLUMN `deposit_date` DATE NULL AFTER `amount`");
            $this->db->query("ALTER TABLE `qardan_hasana_husain_scheme` ADD KEY `idx_deposit_date` (`deposit_date`)");
        }

        if (!$this->db->field_exists('maturity_date', 'qardan_hasana_husain_scheme')) {
            $this->db->query("ALTER TABLE `qardan_hasana_husain_scheme` ADD COLUMN `maturity_date` DATE NULL AFTER `deposit_date`");
        }

        if (!$this->db->field_exists('duration', 'qardan_hasana_husain_scheme')) {
            $this->db->query("ALTER TABLE `qardan_hasana_husain_scheme` ADD COLUMN `duration` VARCHAR(32) NOT NULL DEFAULT '' AFTER `maturity_date`");
        }

        // Drop legacy columns (from mohammedi-like schema) if present
        $legacy = ['miqaat_name', 'hijri_date', 'eng_date', 'collection_amount'];
        foreach ($legacy as $col) {
            if ($this->db->field_exists($col, 'qardan_hasana_husain_scheme')) {
                $this->db->query("ALTER TABLE `qardan_hasana_husain_scheme` DROP COLUMN `{$col}`");
            }
        }
    }

    public function down()
    {
        // No safe rollback.
    }
}
