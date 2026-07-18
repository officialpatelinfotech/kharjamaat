<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_jaman_type_to_miqaat extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('miqaat')) {
            if (!$this->db->field_exists('jaman_type', 'miqaat')) {
                $fields = array(
                    'jaman_type' => array(
                        'type' => 'VARCHAR',
                        'constraint' => 255,
                        'null' => TRUE,
                        'default' => NULL
                    )
                );
                $this->dbforge->add_column('miqaat', $fields);
            }
        }
    }

    public function down()
    {
        if ($this->db->table_exists('miqaat')) {
            if ($this->db->field_exists('jaman_type', 'miqaat')) {
                $this->dbforge->drop_column('miqaat', 'jaman_type');
            }
        }
    }
}
