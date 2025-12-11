<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Fix_miqaat_payment_fk extends CI_Migration
{
    public function up()
    {
        // Use plain queries for broader MySQL compatibility
        // 1) Find and drop wrong FK if it exists
        $fkName = null;
        $query = $this->db->query("SHOW CREATE TABLE miqaat_payment");
        $row = $query->row_array();
        if ($row && isset($row['Create Table'])) {
            $create = $row['Create Table'];
            // Look for any FK on `miqaat_invoice_id` referencing `miqaat` table
            if (preg_match('/CONSTRAINT `([^`]+)` FOREIGN KEY \(`miqaat_invoice_id`\) REFERENCES `miqaat` \(`id`\)/', $create, $m)) {
                $fkName = $m[1];
            }
        }
        if ($fkName) {
            $this->db->query("ALTER TABLE miqaat_payment DROP FOREIGN KEY `{$fkName}`");
        }

        // 2) Ensure index exists on miqaat_invoice_id (required for FK)
        $hasIndex = false;
        $idxQuery = $this->db->query("SHOW INDEX FROM miqaat_payment WHERE Column_name = 'miqaat_invoice_id'");
        if ($idxQuery && $idxQuery->num_rows() > 0) {
            $hasIndex = true;
        }
        if (!$hasIndex) {
            $this->db->query("ALTER TABLE miqaat_payment ADD INDEX `idx_miqaat_invoice_id` (`miqaat_invoice_id`)");
        }

        // 3) Clean up any invalid values that would violate the new FK
        // Detect if column is nullable
        $colInfo = $this->db->query("SHOW COLUMNS FROM miqaat_payment LIKE 'miqaat_invoice_id'")->row_array();
        $isNullable = true;
        if ($colInfo && isset($colInfo['Null'])) {
            $isNullable = (strtoupper($colInfo['Null']) === 'YES');
        }
        if ($isNullable) {
            // Set invalid references to NULL
            $this->db->query("UPDATE miqaat_payment p LEFT JOIN miqaat_invoice i ON i.id = p.miqaat_invoice_id SET p.miqaat_invoice_id = NULL WHERE p.miqaat_invoice_id IS NOT NULL AND i.id IS NULL");
        } else {
            // Remove rows with invalid references to avoid FK violation
            $this->db->query("DELETE p FROM miqaat_payment p LEFT JOIN miqaat_invoice i ON i.id = p.miqaat_invoice_id WHERE p.miqaat_invoice_id IS NOT NULL AND i.id IS NULL");
        }

        // 4) Add correct FK to miqaat_invoice(id) if not already present
        $query = $this->db->query("SHOW CREATE TABLE miqaat_payment");
        $row = $query->row_array();
        $hasCorrectFk = false;
        if ($row && isset($row['Create Table'])) {
            $create = $row['Create Table'];
            if (preg_match('/FOREIGN KEY \(`miqaat_invoice_id`\) REFERENCES `miqaat_invoice` \(`id`\)/', $create)) {
                $hasCorrectFk = true;
            }
        }
        if (!$hasCorrectFk) {
            $this->db->query("ALTER TABLE miqaat_payment ADD CONSTRAINT `fk_miqaat_payment_invoice` FOREIGN KEY (`miqaat_invoice_id`) REFERENCES `miqaat_invoice`(`id`) ON DELETE CASCADE ON UPDATE CASCADE");
        }
    }

    public function down()
    {
        // Safely drop the added FK if it exists
        $fkToDrop = null;
        $query = $this->db->query("SHOW CREATE TABLE miqaat_payment");
        $row = $query->row_array();
        if ($row && isset($row['Create Table'])) {
            $create = $row['Create Table'];
            if (preg_match('/CONSTRAINT `([^`]+)` FOREIGN KEY \(`miqaat_invoice_id`\) REFERENCES `miqaat_invoice` \(`id`\)/', $create, $m)) {
                $fkToDrop = $m[1];
            }
        }
        if ($fkToDrop) {
            $this->db->query("ALTER TABLE miqaat_payment DROP FOREIGN KEY `{$fkToDrop}`");
        }

        // Not re-adding the old incorrect FK to avoid reintroducing the problem.
    }
}
