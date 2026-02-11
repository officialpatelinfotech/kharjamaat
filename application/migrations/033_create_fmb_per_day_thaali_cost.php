<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_fmb_per_day_thaali_cost extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('fmb_per_day_thaali_cost')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `fmb_per_day_thaali_cost` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `year` VARCHAR(10) NOT NULL,
  `amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_fmb_per_day_thaali_cost_year` (`year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    public function down()
    {
        if ($this->db->table_exists('fmb_per_day_thaali_cost')) {
            $this->db->query("DROP TABLE `fmb_per_day_thaali_cost`");
        }
    }
}
