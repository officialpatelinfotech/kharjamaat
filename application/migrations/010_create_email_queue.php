<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_email_queue extends CI_Migration
{
    public function up()
    {
        // Create email_queue table
        $this->db->query(
            "CREATE TABLE IF NOT EXISTS `email_queue` (
              `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
              `recipient` TEXT NOT NULL,
              `bcc` TEXT DEFAULT NULL,
              `subject` VARCHAR(255) DEFAULT NULL,
              `message` LONGTEXT DEFAULT NULL,
              `mailtype` VARCHAR(16) DEFAULT 'html',
              `status` VARCHAR(16) NOT NULL DEFAULT 'pending',
              `attempts` INT NOT NULL DEFAULT 0,
              `last_error` TEXT DEFAULT NULL,
              `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `send_after` DATETIME DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        );
    }

    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS `email_queue`");
    }
}
