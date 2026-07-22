<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_create_thaali_types extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('thaali_types')) {
            $this->dbforge->add_field(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ),
                'description' => array(
                    'type' => 'TEXT',
                    'null' => TRUE,
                ),
                'amount' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '10,2',
                    'default' => 0.00,
                ),
                'status' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'default' => 'Active',
                ),
                'created_at' => array(
                    'type' => 'TIMESTAMP',
                    'null' => TRUE,
                )
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('thaali_types');

            // Insert some default thaali types
            $default_types = array(
                array('name' => '1 Thaali', 'description' => 'Standard single thaali', 'amount' => 0.00, 'status' => 'Active'),
                array('name' => 'Half Thaali', 'description' => 'Half thaali portion', 'amount' => 0.00, 'status' => 'Active'),
                array('name' => '1.5 Thaali', 'description' => '1.5 thaali portion', 'amount' => 0.00, 'status' => 'Active'),
                array('name' => '2 Thaali', 'description' => 'Double thaali portion', 'amount' => 0.00, 'status' => 'Active'),
            );
            $this->db->insert_batch('thaali_types', $default_types);
        }
    }

    public function down()
    {
        if ($this->db->table_exists('thaali_types')) {
            $this->dbforge->drop_table('thaali_types');
        }
    }
}
