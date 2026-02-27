<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_laagat_rent_table extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('laagat_rent')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `laagat_rent` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `hijri_year` VARCHAR(10) NOT NULL,
  `charge_type` VARCHAR(10) NOT NULL,
  `amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `raza_type_id` INT UNSIGNED NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_laagat_rent_year` (`hijri_year`),
  KEY `idx_laagat_rent_charge_type` (`charge_type`),
  KEY `idx_laagat_rent_active` (`is_active`),
  KEY `idx_laagat_rent_raza_type_id` (`raza_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    public function down()
    {
        if ($this->db->table_exists('laagat_rent')) {
            $this->db->query("DROP TABLE `laagat_rent`");
        }
    }
}
