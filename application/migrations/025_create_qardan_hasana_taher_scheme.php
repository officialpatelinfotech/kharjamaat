<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_qardan_hasana_taher_scheme extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('qardan_hasana_taher_scheme')) {
            $this->db->query("CREATE TABLE `qardan_hasana_taher_scheme` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `ITS` VARCHAR(20) NOT NULL,
                `unit` DECIMAL(10,2) NOT NULL DEFAULT 215.00,
                `units` INT UNSIGNED NOT NULL DEFAULT 0,
                `miqaat_name` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`),
                KEY `idx_its` (`ITS`),
                KEY `idx_miqaat_name` (`miqaat_name`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }
    }

    public function down()
    {
        if ($this->db->table_exists('qardan_hasana_taher_scheme')) {
            $this->db->query('DROP TABLE `qardan_hasana_taher_scheme`');
        }
    }
}
