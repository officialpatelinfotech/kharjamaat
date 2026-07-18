<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_create_umoor_assignments extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('umoor_assignments')) {
            $this->dbforge->add_field(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'user_its' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ),
                'umoor_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                ),
                'created_at' => array(
                    'type' => 'TIMESTAMP',
                    'null' => TRUE,
                )
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_key('user_its');
            $this->dbforge->add_key('umoor_id');
            $this->dbforge->create_table('umoor_assignments');
        }
    }

    public function down()
    {
        if ($this->db->table_exists('umoor_assignments')) {
            $this->dbforge->drop_table('umoor_assignments');
        }
    }
}
