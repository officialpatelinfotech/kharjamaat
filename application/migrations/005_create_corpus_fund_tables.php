<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_corpus_fund_tables extends CI_Migration
{
    public function up()
    {
        // Create corpus_fund table if not exists
        if (!$this->db->table_exists('corpus_fund')) {
            $this->db->query("CREATE TABLE `corpus_fund` (\n                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,\n                `title` VARCHAR(150) NOT NULL,\n                `amount` DECIMAL(10,2) NOT NULL DEFAULT 0,\n                `description` TEXT NULL,\n                `created_by` INT NULL,\n                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,\n                PRIMARY KEY (`id`)\n            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }

        // Create corpus_fund_assignment table if not exists
        if (!$this->db->table_exists('corpus_fund_assignment')) {
            $this->db->query("CREATE TABLE `corpus_fund_assignment` (\n                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,\n                `fund_id` INT UNSIGNED NOT NULL,\n                `hof_id` INT NOT NULL,\n                `amount_assigned` DECIMAL(10,2) NOT NULL DEFAULT 0,\n                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,\n                PRIMARY KEY (`id`),\n                KEY `idx_fund_id` (`fund_id`),\n                KEY `idx_hof_id` (`hof_id`),\n                CONSTRAINT `fk_corpus_fund_assignment_fund` FOREIGN KEY (`fund_id`) REFERENCES `corpus_fund`(`id`) ON DELETE CASCADE ON UPDATE CASCADE\n            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }
    }

    public function down()
    {
        // Drop assignment first due to FK
        if ($this->db->table_exists('corpus_fund_assignment')) {
            $this->db->query("DROP TABLE `corpus_fund_assignment`");
        }
        if ($this->db->table_exists('corpus_fund')) {
            $this->db->query("DROP TABLE `corpus_fund`");
        }
    }
}
