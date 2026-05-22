<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_venue_to_laagat_rent extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('laagat_rent')) {
            if (!$this->db->field_exists('venue', 'laagat_rent')) {
                $this->db->query("ALTER TABLE `laagat_rent` ADD `venue` VARCHAR(255) NULL DEFAULT NULL AFTER `raza_type_id`;");
            }
        }
    }

    public function down()
    {
        if ($this->db->table_exists('laagat_rent')) {
            if ($this->db->field_exists('venue', 'laagat_rent')) {
                $this->db->query("ALTER TABLE `laagat_rent` DROP COLUMN `venue`;");
            }
        }
    }
}
