<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_status_options_table extends CI_Migration
{
    public function up()
    {
        // 1. Create status_options table
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `status_options` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `type` VARCHAR(20) NOT NULL COMMENT 'deeni | health | residential',
                `status_key` VARCHAR(100) NOT NULL,
                `status_label` VARCHAR(150) NOT NULL,
                `is_inactive_trigger` TINYINT(1) NOT NULL DEFAULT 0,
                UNIQUE KEY `idx_type_key` (`type`, `status_key`),
                KEY `idx_type_key_trigger` (`type`, `status_key`, `is_inactive_trigger`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // 2. Seed initial status options
        $initial_options = [
            // Deeni status options
            ['type' => 'deeni', 'status_key' => 'Normal', 'status_label' => 'Normal (Active)', 'is_inactive_trigger' => 0],
            ['type' => 'deeni', 'status_key' => 'Deen Badli Lidu che', 'status_label' => 'Deen Badli Lidu che (Inactive)', 'is_inactive_trigger' => 1],
            ['type' => 'deeni', 'status_key' => 'Married Outside', 'status_label' => 'Married Outside (Inactive)', 'is_inactive_trigger' => 1],
            ['type' => 'deeni', 'status_key' => 'Misaq Not Given', 'status_label' => 'Not given Misaq to Syedna Mufaddal Saifuddin AQA tus after Takht Nashini (Inactive)', 'is_inactive_trigger' => 1],
            ['type' => 'deeni', 'status_key' => 'Mustajeeb', 'status_label' => 'Mustajeeb (Inactive)', 'is_inactive_trigger' => 1],
            ['type' => 'deeni', 'status_key' => 'No Ashara / LQ', 'status_label' => 'No Ashara / LQ attended for past 3 years (Inactive)', 'is_inactive_trigger' => 1],
            ['type' => 'deeni', 'status_key' => 'No Vajebaat / Sabeel', 'status_label' => 'Not paid Sila Fitra / Vajeebaat / Sabeel for last 3 years (Inactive)', 'is_inactive_trigger' => 1],
            ['type' => 'deeni', 'status_key' => 'Zero Days Scanned in Ashara Mubaraka', 'status_label' => 'Zero Days Scanned in Ashara Mubaraka (Inactive)', 'is_inactive_trigger' => 1],

            // Health status options
            ['type' => 'health', 'status_key' => 'Healthy', 'status_label' => 'Fit & Healthy (Active)', 'is_inactive_trigger' => 0],
            ['type' => 'health', 'status_key' => 'Medically Unfit', 'status_label' => 'Handicapped Medically Unfit (Active)', 'is_inactive_trigger' => 0],
            ['type' => 'health', 'status_key' => 'Hospitalised', 'status_label' => 'Major Disease Patient (Active)', 'is_inactive_trigger' => 0],
            ['type' => 'health', 'status_key' => 'Lazimul Firash', 'status_label' => 'Lazimul Firash / Bedridden (Active)', 'is_inactive_trigger' => 0],
            ['type' => 'health', 'status_key' => 'Wafaat', 'status_label' => 'Wafaat (Inactive)', 'is_inactive_trigger' => 1],

            // Residential status options
            ['type' => 'residential', 'status_key' => 'Residing in Khar', 'status_label' => 'Residing in Khar (Active)', 'is_inactive_trigger' => 0],
            ['type' => 'residential', 'status_key' => 'Madresa in Khar', 'status_label' => 'Madresa in Khar (Active)', 'is_inactive_trigger' => 0],
            ['type' => 'residential', 'status_key' => 'FMB Thaali in Khar', 'status_label' => 'FMB Thaali in Khar (Active)', 'is_inactive_trigger' => 0],
            ['type' => 'residential', 'status_key' => 'Moved for Job', 'status_label' => 'Moved for Job (Inactive)', 'is_inactive_trigger' => 1],
            ['type' => 'residential', 'status_key' => 'Moved for Studies', 'status_label' => 'Moved for Studies (Inactive)', 'is_inactive_trigger' => 1],
            ['type' => 'residential', 'status_key' => 'Moved after Marriage', 'status_label' => 'Permanently moved after Marriage (Inactive)', 'is_inactive_trigger' => 1],
            ['type' => 'residential', 'status_key' => 'Permanently Migrated', 'status_label' => 'Permanently Migrated (Inactive)', 'is_inactive_trigger' => 1],
            ['type' => 'residential', 'status_key' => 'Unknown or Not Traceable', 'status_label' => 'Unknown or Not Traceable (Inactive)', 'is_inactive_trigger' => 1],

            // Trigger-only options (from previous triggers)
            ['type' => 'residential', 'status_key' => 'Moved Permanently but not taken transfer', 'status_label' => 'Moved Permanently but not taken transfer (Inactive)', 'is_inactive_trigger' => 1],
            ['type' => 'residential', 'status_key' => 'Permanently moved but ITS not Transferred', 'status_label' => 'Permanently moved but ITS not Transferred (Inactive)', 'is_inactive_trigger' => 1],
            ['type' => 'residential', 'status_key' => 'Permanently Moved and ITS also Transferred', 'status_label' => 'Permanently Moved and ITS also Transferred (Inactive)', 'is_inactive_trigger' => 1],
        ];

        foreach ($initial_options as $option) {
            $exists = $this->db->where([
                'type' => $option['type'],
                'status_key' => $option['status_key']
            ])->get('status_options')->row_array();
            if (!$exists) {
                $this->db->insert('status_options', $option);
            }
        }

        // 3. Drop existing triggers
        $this->db->query("DROP TRIGGER IF EXISTS trg_auto_activity_status_insert");
        $this->db->query("DROP TRIGGER IF EXISTS trg_auto_activity_status");

        // 4. Create new dynamic triggers referencing status_options
        $sql_insert = "
        CREATE TRIGGER trg_auto_activity_status_insert
        BEFORE INSERT ON user
        FOR EACH ROW
        BEGIN
            IF (EXISTS (SELECT 1 FROM status_options WHERE type = 'deeni' AND status_key = NEW.deeni_status AND is_inactive_trigger = 1)
                OR EXISTS (SELECT 1 FROM status_options WHERE type = 'health' AND status_key = NEW.health_status AND is_inactive_trigger = 1)
                OR EXISTS (SELECT 1 FROM status_options WHERE type = 'residential' AND status_key = NEW.residential_status AND is_inactive_trigger = 1)) THEN
                SET NEW.activity_status = 'inactive';
            ELSE
                SET NEW.activity_status = 'active';
            END IF;
        END;
        ";
        $this->db->query($sql_insert);

        $sql_update = "
        CREATE TRIGGER trg_auto_activity_status
        BEFORE UPDATE ON user
        FOR EACH ROW
        BEGIN
            IF (EXISTS (SELECT 1 FROM status_options WHERE type = 'deeni' AND status_key = NEW.deeni_status AND is_inactive_trigger = 1)
                OR EXISTS (SELECT 1 FROM status_options WHERE type = 'health' AND status_key = NEW.health_status AND is_inactive_trigger = 1)
                OR EXISTS (SELECT 1 FROM status_options WHERE type = 'residential' AND status_key = NEW.residential_status AND is_inactive_trigger = 1)) THEN
                SET NEW.activity_status = 'inactive';
            ELSE
                SET NEW.activity_status = 'active';
            END IF;
        END;
        ";
        $this->db->query($sql_update);

        // 5. Trigger update on all users to re-apply the triggers
        $this->db->query("UPDATE user SET ITS_ID = ITS_ID");
    }

    public function down()
    {
        // Fallback triggers from migration 52
        $this->db->query("DROP TRIGGER IF EXISTS trg_auto_activity_status_insert");
        $this->db->query("DROP TRIGGER IF EXISTS trg_auto_activity_status");

        $sql_insert = "
        CREATE TRIGGER trg_auto_activity_status_insert
        BEFORE INSERT ON user
        FOR EACH ROW
        BEGIN
            IF (NEW.deeni_status IN ('Deen Badli Lidu che', 'Married Outside', 'Misaq Not Given', 'Mustajeeb', 'No Ashara / LQ', 'No Vajebaat / Sabeel', 'Zero Days Scanned in Ashara Mubaraka')
                OR NEW.health_status IN ('Wafaat')
                OR NEW.residential_status IN ('Moved for Job', 'Moved for Studies', 'Moved after Marriage', 'Moved Permanently but not taken transfer', 'Permanently moved but ITS not Transferred', 'Permanently Moved and ITS also Transferred', 'Permanently Migrated', 'Unknown or Not Traceable')) THEN
                SET NEW.activity_status = 'inactive';
            ELSE
                SET NEW.activity_status = 'active';
            END IF;
        END;
        ";
        $this->db->query($sql_insert);

        $sql_update = "
        CREATE TRIGGER trg_auto_activity_status
        BEFORE UPDATE ON user
        FOR EACH ROW
        BEGIN
            IF (NEW.deeni_status IN ('Deen Badli Lidu che', 'Married Outside', 'Misaq Not Given', 'Mustajeeb', 'No Ashara / LQ', 'No Vajebaat / Sabeel', 'Zero Days Scanned in Ashara Mubaraka')
                OR NEW.health_status IN ('Wafaat')
                OR NEW.residential_status IN ('Moved for Job', 'Moved for Studies', 'Moved after Marriage', 'Moved Permanently but not taken transfer', 'Permanently moved but ITS not Transferred', 'Permanently Moved and ITS also Transferred', 'Permanently Migrated', 'Unknown or Not Traceable')) THEN
                SET NEW.activity_status = 'inactive';
            ELSE
                SET NEW.activity_status = 'active';
            END IF;
        END;
        ";
        $this->db->query($sql_update);

        $this->db->query("DROP TABLE IF EXISTS `status_options`");
    }
}
