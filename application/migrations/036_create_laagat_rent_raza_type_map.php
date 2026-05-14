<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_laagat_rent_raza_type_map extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('laagat_rent_raza_type_map')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `laagat_rent_raza_type_map` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `laagat_rent_id` INT UNSIGNED NOT NULL,
  `raza_type_id` INT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_lr_rt` (`laagat_rent_id`, `raza_type_id`),
  KEY `idx_lr_map_lr_id` (`laagat_rent_id`),
  KEY `idx_lr_map_rt_id` (`raza_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }

        // Backfill: convert existing single raza_type_id into mapping rows.
        if ($this->db->table_exists('laagat_rent')) {
            $this->db->query("INSERT IGNORE INTO `laagat_rent_raza_type_map` (`laagat_rent_id`, `raza_type_id`)
                SELECT `id`, `raza_type_id`
                FROM `laagat_rent`
                WHERE `raza_type_id` IS NOT NULL AND `raza_type_id` > 0");
        }
    }

    public function down()
    {
        if ($this->db->table_exists('laagat_rent_raza_type_map')) {
            $this->db->query('DROP TABLE `laagat_rent_raza_type_map`');
        }
    }
}
