<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Migration 041: Add Member Status & ITS-Sabeel Match Columns to `user` table
 *
 * Adds the following columns (all idempotent — safe to run multiple times):
 *
 *   its_sabeel_match  VARCHAR(40)  — Auto-calculated on CSV import / Sabeel changes.
 *                                    Tracks the relationship between ITS and Sabeel records.
 *                                    Values: its_sabeel_both_khar | its_khar_sabeel_out |
 *                                            sabeel_khar_its_out  | both_not_khar
 *
 *   activity_status   VARCHAR(20)  — Manually set by Admin.
 *                                    Values: active | inactive | temporary
 *
 *   deeni_status      VARCHAR(100) — Manually set by Admin.
 *                                    Free-text / enum label (e.g. "Wafaat", "Mustajeeb").
 *
 *   residential_status VARCHAR(60) — Manually set by Admin.
 *                                    Values: Residing in Khar | Moved for Job | Moved for Studies |
 *                                            Moved but not taken transfer | Shifted Permanently |
 *                                            Abroad | Unknown / Not traceable
 *
 *   health_status     VARCHAR(60)  — Manually set by Admin.
 *                                    Values: Healthy | Lazimul Firash | Medically Unfit |
 *                                            Hospitalised | Elderly / Needs care
 */
class Migration_Add_member_status_columns extends CI_Migration
{
    private $table = 'user';

    public function up()
    {
        $existing = $this->_existing_columns();

        // 1. ITS–Sabeel match (auto-calculated on import)
        if (!isset($existing['its_sabeel_match'])) {
            $this->db->query(
                "ALTER TABLE `{$this->table}`
                 ADD COLUMN `its_sabeel_match` VARCHAR(40) NULL DEFAULT NULL
                 COMMENT 'Auto: its_sabeel_both_khar | its_khar_sabeel_out | sabeel_khar_its_out | both_not_khar'
                 AFTER `Member_Type`"
            );
        }

        // 2. Activity / member status (manual, admin-controlled)
        if (!isset($existing['activity_status'])) {
            $this->db->query(
                "ALTER TABLE `{$this->table}`
                 ADD COLUMN `activity_status` VARCHAR(20) NULL DEFAULT NULL
                 COMMENT 'Manual: active | inactive | temporary'
                 AFTER `its_sabeel_match`"
            );
        }

        // 3. Deeni status (manual)
        if (!isset($existing['deeni_status'])) {
            $this->db->query(
                "ALTER TABLE `{$this->table}`
                 ADD COLUMN `deeni_status` VARCHAR(120) NULL DEFAULT NULL
                 COMMENT 'Manual: Wafaat, Mustajeeb, No Vajebaat / Sabeel, etc.'
                 AFTER `activity_status`"
            );
        }

        // 4. Residential status (manual)
        if (!isset($existing['residential_status'])) {
            $this->db->query(
                "ALTER TABLE `{$this->table}`
                 ADD COLUMN `residential_status` VARCHAR(80) NULL DEFAULT NULL
                 COMMENT 'Manual: Residing in Khar, Moved for Job, Shifted Permanently, etc.'
                 AFTER `deeni_status`"
            );
        }

        // 5. Health status (manual)
        if (!isset($existing['health_status'])) {
            $this->db->query(
                "ALTER TABLE `{$this->table}`
                 ADD COLUMN `health_status` VARCHAR(60) NULL DEFAULT NULL
                 COMMENT 'Manual: Healthy, Lazimul Firash, Medically Unfit, etc.'
                 AFTER `residential_status`"
            );
        }

        // Seed activity_status = 'active' for any rows that have inactive_status IS NULL
        // (safe default so existing members are not treated as inactive on first run)
        $this->db->query(
            "UPDATE `{$this->table}`
             SET    `activity_status` = 'active'
             WHERE  (`inactive_status` IS NULL OR `inactive_status` = '')
               AND  (`activity_status` IS NULL OR `activity_status` = '')"
        );
    }

    public function down()
    {
        // Non-destructive by default.
        // Uncomment to fully reverse:
        // $cols = ['health_status','residential_status','deeni_status','activity_status','its_sabeel_match'];
        // foreach ($cols as $col) {
        //     if (isset($this->_existing_columns()[$col])) {
        //         $this->db->query("ALTER TABLE `{$this->table}` DROP COLUMN `$col`");
        //     }
        // }
    }

    // ── helpers ──────────────────────────────────────────────────────────────

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
