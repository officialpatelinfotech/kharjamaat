<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Alter_qardan_hasana_taher_scheme_add_columns extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('qardan_hasana_taher_scheme')) {
            return;
        }

        // Add new Taher-scheme columns if missing.
        // Note: keep legacy columns to avoid destructive changes.
        if (!$this->db->field_exists('ITS', 'qardan_hasana_taher_scheme')) {
            // Allow NULL to avoid failing if legacy rows already exist.
            $this->db->query("ALTER TABLE `qardan_hasana_taher_scheme` ADD COLUMN `ITS` VARCHAR(20) NULL AFTER `id`");
            $this->db->query("ALTER TABLE `qardan_hasana_taher_scheme` ADD KEY `idx_its` (`ITS`)");
        }

        if (!$this->db->field_exists('unit', 'qardan_hasana_taher_scheme')) {
            $this->db->query("ALTER TABLE `qardan_hasana_taher_scheme` ADD COLUMN `unit` DECIMAL(10,2) NOT NULL DEFAULT 215.00 AFTER `ITS`");
        }

        if (!$this->db->field_exists('units', 'qardan_hasana_taher_scheme')) {
            $this->db->query("ALTER TABLE `qardan_hasana_taher_scheme` ADD COLUMN `units` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `unit`");
        }

        if (!$this->db->field_exists('miqaat_name', 'qardan_hasana_taher_scheme')) {
            $this->db->query("ALTER TABLE `qardan_hasana_taher_scheme` ADD COLUMN `miqaat_name` VARCHAR(255) NOT NULL DEFAULT ''");
            $this->db->query("ALTER TABLE `qardan_hasana_taher_scheme` ADD KEY `idx_miqaat_name` (`miqaat_name`)");
        }
    }

    public function down()
    {
        if (!$this->db->table_exists('qardan_hasana_taher_scheme')) {
            return;
        }

        // Rollback only the newly introduced columns; keep legacy columns.
        if ($this->db->field_exists('units', 'qardan_hasana_taher_scheme')) {
            $this->db->query("ALTER TABLE `qardan_hasana_taher_scheme` DROP COLUMN `units`");
        }
        if ($this->db->field_exists('unit', 'qardan_hasana_taher_scheme')) {
            $this->db->query("ALTER TABLE `qardan_hasana_taher_scheme` DROP COLUMN `unit`");
        }
        if ($this->db->field_exists('ITS', 'qardan_hasana_taher_scheme')) {
            // Drop index if it exists (safe to try)
            $this->db->query("ALTER TABLE `qardan_hasana_taher_scheme` DROP INDEX `idx_its`");
            $this->db->query("ALTER TABLE `qardan_hasana_taher_scheme` DROP COLUMN `ITS`");
        }
    }
}
