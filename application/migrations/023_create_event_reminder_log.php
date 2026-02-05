<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_event_reminder_log extends CI_Migration {

    public function up()
    {
        if (!$this->db->table_exists('event_reminder_log')) {
            $this->db->query("CREATE TABLE `event_reminder_log` (
                `id` int unsigned NOT NULL AUTO_INCREMENT,
                `event_umoor` varchar(32) NOT NULL COMMENT 'Public-Event|Private-Event',
                `raza_id` int unsigned NOT NULL,
                `trigger_key` varchar(32) NOT NULL COMMENT 'd3_1300|d1_2100|d0_0900',
                `recipient` varchar(255) NOT NULL,
                `notification_id` int unsigned DEFAULT NULL,
                `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `u_event_trigger_recipient` (`event_umoor`,`raza_id`,`trigger_key`,`recipient`),
                INDEX `idx_raza_id` (`raza_id`),
                INDEX `idx_notification_id` (`notification_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
        }
    }

    public function down()
    {
        if ($this->db->table_exists('event_reminder_log')) {
            $this->db->query('DROP TABLE `event_reminder_log`');
        }
    }
}
