<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_fmb_thaali_day_assignment extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('fmb_thaali_day_assignment')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `fmb_thaali_day_assignment` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `menu_id` INT UNSIGNED NOT NULL,
  `menu_date` DATETIME NOT NULL,
  `user_id` INT NOT NULL,
  `year` VARCHAR(10) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_fmb_thaali_day_assignment_menu_id` (`menu_id`),
  KEY `idx_fmb_thaali_day_assignment_user_year` (`user_id`, `year`),
  KEY `idx_fmb_thaali_day_assignment_year` (`year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    public function down()
    {
        if ($this->db->table_exists('fmb_thaali_day_assignment')) {
            $this->db->query("DROP TABLE `fmb_thaali_day_assignment`");
        }
    }
}
