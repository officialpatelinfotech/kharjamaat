<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_create_sub_committees extends CI_Migration
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
    }

    public function down()
    {
        if ($this->db->table_exists('sub_committees')) {
            $this->dbforge->drop_table('sub_committees');
        }
    }
}
