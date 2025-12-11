<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_general_guest_rsvp extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('general_guest_rsvp')) {
            $this->db->query("CREATE TABLE `general_guest_rsvp` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `hof_id` INT NOT NULL,
                `miqaat_id` INT NOT NULL,
                `gents` INT UNSIGNED NOT NULL DEFAULT 0,
                `ladies` INT UNSIGNED NOT NULL DEFAULT 0,
                `children` INT UNSIGNED NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`),
                KEY `idx_miqaat_hof` (`miqaat_id`,`hof_id`),
                KEY `idx_hof` (`hof_id`),
                CONSTRAINT `fk_guest_rsvp_miqaat` FOREIGN KEY (`miqaat_id`) REFERENCES `miqaat`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }
    }

    public function down()
    {
        if ($this->db->table_exists('general_guest_rsvp')) {
            $this->db->query("DROP TABLE `general_guest_rsvp`");
        }
    }
}
