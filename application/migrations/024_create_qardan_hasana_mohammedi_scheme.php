<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_qardan_hasana_mohammedi_scheme extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('qardan_hasana_mohammedi_scheme')) {
            $this->db->query("CREATE TABLE `qardan_hasana_mohammedi_scheme` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `uploaded_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `miqaat_name` VARCHAR(255) NOT NULL,
                `hijri_date` VARCHAR(32) NOT NULL,
                `eng_date` DATE NOT NULL,
                `collection_amount` DECIMAL(10,2) NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`),
                KEY `idx_miqaat_name` (`miqaat_name`),
                KEY `idx_eng_date` (`eng_date`),
                KEY `idx_hijri_date` (`hijri_date`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }
    }

    public function down()
    {
        if ($this->db->table_exists('qardan_hasana_mohammedi_scheme')) {
            $this->db->query('DROP TABLE `qardan_hasana_mohammedi_scheme`');
        }
    }
}
