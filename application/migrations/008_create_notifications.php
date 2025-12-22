<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_notifications extends CI_Migration {

    public function up()
    {
        // notifications table
        if (!$this->db->table_exists('notifications')) {
            $this->db->query("CREATE TABLE `notifications` (
                `id` int unsigned NOT NULL AUTO_INCREMENT,
                `channel` varchar(32) NOT NULL COMMENT 'email|whatsapp|sms',
                `recipient` varchar(255) DEFAULT NULL COMMENT 'recipient identifier or phone/email',
                `recipient_type` varchar(32) DEFAULT NULL COMMENT 'member|jamaat|amil',
                `subject` varchar(255) DEFAULT NULL,
                `body` text DEFAULT NULL,
                `status` varchar(32) NOT NULL DEFAULT 'pending',
                `attempts` tinyint unsigned NOT NULL DEFAULT 0,
                `scheduled_at` datetime DEFAULT NULL,
                `sent_at` datetime DEFAULT NULL,
                `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                INDEX (`status`),
                INDEX (`scheduled_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
        }

        // templates table for reusable messages
        if (!$this->db->table_exists('notification_templates')) {
            $this->db->query("CREATE TABLE `notification_templates` (
                `id` int unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(128) NOT NULL,
                `channel` varchar(32) NOT NULL,
                `subject` varchar(255) DEFAULT NULL,
                `body` text DEFAULT NULL,
                `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `u_name_channel` (`name`,`channel`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
        }
    }

    public function down()
    {
        if ($this->db->table_exists('notifications')) {
            $this->db->query('DROP TABLE `notifications`');
        }
        if ($this->db->table_exists('notification_templates')) {
            $this->db->query('DROP TABLE `notification_templates`');
        }
    }
}
