<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_laagat_rent_payments extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('laagat_rent_payments')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `laagat_rent_payments` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `invoice_id` INT UNSIGNED NOT NULL,
                `amount` DECIMAL(15,2) NOT NULL DEFAULT '0.00',
                `payment_date` DATE NOT NULL,
                `payment_method` VARCHAR(50) DEFAULT NULL,
                `remarks` TEXT DEFAULT NULL,
                `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `idx_laagat_rent_payments_invoice` (`invoice_id`),
                CONSTRAINT `fk_laagat_rent_payments_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `laagat_rent_invoices`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    public function down()
    {
        if ($this->db->table_exists('laagat_rent_payments')) {
            $this->db->query("DROP TABLE `laagat_rent_payments` ");
        }
    }
}
