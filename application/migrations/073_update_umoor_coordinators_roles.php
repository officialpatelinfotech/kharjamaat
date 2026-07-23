<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_update_umoor_coordinators_roles extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('umoor_role_assignments')) {
            $this->db->query("ALTER TABLE umoor_role_assignments MODIFY role VARCHAR(50) NOT NULL DEFAULT 'Team Member';");
        }
    }

    public function down()
    {
        if ($this->db->table_exists('umoor_role_assignments')) {
            $this->db->query("ALTER TABLE umoor_role_assignments MODIFY role ENUM('Coordinator', 'Male Coordinator', 'Female Coordinator', 'Team Lead', 'Team Member') NOT NULL DEFAULT 'Team Member';");
        }
    }
}
