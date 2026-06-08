<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_confidential_comments_table extends CI_Migration
{
    public function up()
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `confidential_comments` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `its_id` INT NOT NULL,
                `comment` TEXT NOT NULL,
                `created_by` VARCHAR(100) NOT NULL,
                `created_by_name` VARCHAR(250) NOT NULL,
                `created_at` DATETIME NOT NULL,
                KEY `idx_its_id` (`its_id`),
                KEY `idx_created_at` (`created_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }

    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS `confidential_comments`");
    }
}
