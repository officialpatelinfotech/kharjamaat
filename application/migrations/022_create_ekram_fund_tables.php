<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_ekram_fund_tables extends CI_Migration
{
    public function up()
    {
        // Create ekram_fund table if not exists
        if (!$this->db->table_exists('ekram_fund')) {
            $this->db->query("CREATE TABLE `ekram_fund` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(150) NOT NULL,
                `amount` DECIMAL(10,2) NOT NULL DEFAULT 0,
                `hijri_year` INT DEFAULT NULL,
                `description` TEXT NULL,
                `created_by` INT NULL,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }

        // Create ekram_fund_assignment table if not exists
        if (!$this->db->table_exists('ekram_fund_assignment')) {
            $this->db->query("CREATE TABLE `ekram_fund_assignment` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `fund_id` INT UNSIGNED NOT NULL,
                `hof_id` INT NOT NULL,
                `amount_assigned` DECIMAL(10,2) NOT NULL DEFAULT 0,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `idx_fund_id` (`fund_id`),
                KEY `idx_hof_id` (`hof_id`),
                CONSTRAINT `fk_ekram_fund_assignment_fund` FOREIGN KEY (`fund_id`) REFERENCES `ekram_fund`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }

        // Create ekram_fund_payment table if not exists
        if (!$this->db->table_exists('ekram_fund_payment')) {
            $this->db->query("CREATE TABLE `ekram_fund_payment` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `fund_id` INT UNSIGNED NOT NULL,
                `hof_id` INT NOT NULL,
                `amount_paid` DECIMAL(10,2) NOT NULL DEFAULT 0,
                `notes` VARCHAR(255) NULL,
                `payment_method` VARCHAR(50) NULL,
                `received_by` INT NULL,
                `paid_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `idx_fund_hof` (`fund_id`,`hof_id`),
                CONSTRAINT `fk_ekram_payment_fund` FOREIGN KEY (`fund_id`) REFERENCES `ekram_fund`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }
    }

    public function down()
    {
        if ($this->db->table_exists('ekram_fund_payment')) {
            $this->db->query("DROP TABLE `ekram_fund_payment`");
        }
        if ($this->db->table_exists('ekram_fund_assignment')) {
            $this->db->query("DROP TABLE `ekram_fund_assignment`");
        }
        if ($this->db->table_exists('ekram_fund')) {
            $this->db->query("DROP TABLE `ekram_fund`");
        }
    }
}
