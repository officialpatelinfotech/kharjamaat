<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_laagat_rent_invoices extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('laagat_rent_invoices')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `laagat_rent_invoices` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `user_id` INT UNSIGNED NOT NULL,
                `laagat_rent_id` INT UNSIGNED NOT NULL,
                `raza_id` INT UNSIGNED NOT NULL,
                PRIMARY KEY (`id`),
                KEY `idx_laagat_rent_invoices_user` (`user_id`),
                KEY `idx_laagat_rent_invoices_rent` (`laagat_rent_id`),
                KEY `idx_laagat_rent_invoices_raza` (`raza_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    public function down()
    {
        if ($this->db->table_exists('laagat_rent_invoices')) {
            $this->db->query("DROP TABLE `laagat_rent_invoices`");
        }
    }
}
