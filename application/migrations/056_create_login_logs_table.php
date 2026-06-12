<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_login_logs_table extends CI_Migration
{
    public function up()
    {
        // Drop the table if it already exists (e.g. created manually or via temp script)
        // Or conditionally create it using Forge if it doesn't exist
        
        if (!$this->db->table_exists('login_logs')) {
            $this->dbforge->add_field([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => FALSE,
                    'auto_increment' => TRUE
                ],
                'its_id' => [
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => TRUE,
                ],
                'name' => [
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => TRUE,
                ],
                'role' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE,
                ],
                'ip_address' => [
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                    'null' => TRUE,
                ],
                'user_agent' => [
                    'type' => 'TEXT',
                    'null' => TRUE,
                ],
                'location' => [
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => TRUE,
                ],
                'login_time' => [
                    'type' => 'DATETIME',
                    'null' => TRUE,
                ]
            ]);

            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_key('its_id');
            $this->dbforge->add_key('login_time');

            $this->dbforge->create_table('login_logs', TRUE, ['ENGINE' => 'InnoDB']);

            // If we want the default timestamp for login_time
            $this->db->query("ALTER TABLE login_logs MODIFY COLUMN login_time DATETIME DEFAULT CURRENT_TIMESTAMP");
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('login_logs', TRUE);
    }
}
