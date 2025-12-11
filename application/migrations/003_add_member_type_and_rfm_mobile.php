<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_member_type_and_rfm_mobile extends CI_Migration
{
    public function up()
    {
        $table = 'user';
        $cols = $this->db->query("SHOW COLUMNS FROM `$table`")->result_array();
        $existing = [];
        foreach($cols as $c){ $existing[$c['Field']] = true; }
        if(!isset($existing['member_type'])){
            // VARCHAR(100) to store descriptive values
            $this->db->query("ALTER TABLE `$table` ADD `member_type` VARCHAR(100) NULL AFTER `HOF_FM_TYPE`");
        }
        if(!isset($existing['Registered_Family_Mobile'])){
            $this->db->query("ALTER TABLE `$table` ADD `Registered_Family_Mobile` VARCHAR(20) NULL AFTER `WhatsApp_No`");
        }
    }

    public function down()
    {
        // Non destructive by default; uncomment to drop
        // $this->db->query("ALTER TABLE `user` DROP COLUMN `member_type`");
        // $this->db->query("ALTER TABLE `user` DROP COLUMN `Registered_Family_Mobile`");
    }
}
