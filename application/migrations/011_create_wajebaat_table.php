<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_wajebaat_table extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('wajebaat')) {
            $this->db->query("CREATE TABLE `wajebaat` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `ITS_ID` VARCHAR(32) NOT NULL,
                `amount` DECIMAL(10,2) NOT NULL DEFAULT 0,
                `due` DECIMAL(10,2) NOT NULL DEFAULT 0,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `idx_its_id` (`ITS_ID`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }
    }

    public function down()
    {
        if ($this->db->table_exists('wajebaat')) {
            $this->db->query("DROP TABLE `wajebaat`");
        }
    }
}
