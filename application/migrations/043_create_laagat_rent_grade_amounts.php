<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_laagat_rent_grade_amounts extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('laagat_rent_grade_amounts')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `laagat_rent_grade_amounts` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `laagat_rent_id` INT UNSIGNED NOT NULL,
                `sabeel_takhmeen_grade_id` INT NOT NULL,
                `amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
                PRIMARY KEY (`id`),
                UNIQUE KEY `uq_lr_stg` (`laagat_rent_id`, `sabeel_takhmeen_grade_id`),
                CONSTRAINT `fk_lr_grade_lr` FOREIGN KEY (`laagat_rent_id`) REFERENCES `laagat_rent`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk_lr_grade_stg` FOREIGN KEY (`sabeel_takhmeen_grade_id`) REFERENCES `sabeel_takhmeen_grade`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    public function down()
    {
        if ($this->db->table_exists('laagat_rent_grade_amounts')) {
            $this->db->query("DROP TABLE `laagat_rent_grade_amounts` ");
        }
    }
}
