<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_properties_table extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('properties')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `properties` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(255) NOT NULL,
                `created_at` DATETIME NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    public function down()
    {
        if ($this->db->table_exists('properties')) {
            $this->db->query("DROP TABLE `properties`;");
        }
    }
}
