<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_create_umoor_role_assignments extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('sub_committees')) {
            $this->dbforge->add_field(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'umoor_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                ),
                'name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ),
                'team_lead_its' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => TRUE,
                ),
                'created_at' => array(
                    'type' => 'TIMESTAMP',
                    'null' => TRUE,
                )
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_key('umoor_id');
            $this->dbforge->create_table('sub_committees');
        }

        if (!$this->db->table_exists('umoor_role_assignments')) {
            $this->dbforge->add_field(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'year' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 10,
                    'default' => '1448'
                ),
                'umoor_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                ),
                'sub_committee_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE,
                ),
                'role' => array(
                    'type' => "ENUM('Coordinator', 'Team Lead', 'Team Member')",
                    'default' => 'Team Member'
                ),
                'user_its' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ),
                'assigned_by' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => TRUE,
                ),
                'assigned_at' => array(
                    'type' => 'DATETIME',
                    'null' => TRUE,
                ),
                'status' => array(
                    'type' => "ENUM('Active', 'Transferred', 'Removed')",
                    'default' => 'Active'
                ),
                'created_at' => array(
                    'type' => 'TIMESTAMP',
                    'null' => TRUE,
                )
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_key('year');
            $this->dbforge->add_key('umoor_id');
            $this->dbforge->add_key('sub_committee_id');
            $this->dbforge->add_key('user_its');
            $this->dbforge->create_table('umoor_role_assignments');
        }
    }

    public function down()
    {
        if ($this->db->table_exists('umoor_role_assignments')) {
            $this->dbforge->drop_table('umoor_role_assignments');
        }
    }
}
