<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_create_sanstha_tables extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('sanstha')) {
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
                'status' => array(
                    'type' => "ENUM('Active', 'Inactive')",
                    'default' => 'Active'
                ),
                'created_at' => array(
                    'type' => 'TIMESTAMP',
                    'null' => TRUE,
                ),
                'updated_at' => array(
                    'type' => 'TIMESTAMP',
                    'null' => TRUE,
                )
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('sanstha');
        }

        if (!$this->db->table_exists('sanstha_members')) {
            $this->dbforge->add_field(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'sanstha_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                ),
                'user_its' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ),
                'created_at' => array(
                    'type' => 'TIMESTAMP',
                    'null' => TRUE,
                )
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_key('sanstha_id');
            $this->dbforge->add_key('user_its');
            $this->dbforge->create_table('sanstha_members');
        }
    }

    public function down()
    {
        if ($this->db->table_exists('sanstha_members')) {
            $this->dbforge->drop_table('sanstha_members');
        }
        if ($this->db->table_exists('sanstha')) {
            $this->dbforge->drop_table('sanstha');
        }
    }
}
