<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_is_per_thaal_to_laagat_rent extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('laagat_rent')) {
            $this->dbforge->add_column('laagat_rent', array(
                'is_per_thaal' => array(
                    'type' => 'TINYINT',
                    'constraint' => '1',
                    'null' => FALSE,
                    'default' => '0',
                    'after' => 'charge_type'
                )
            ));
        }
    }

    public function down()
    {
        if ($this->db->table_exists('laagat_rent')) {
            $this->dbforge->drop_column('laagat_rent', 'is_per_thaal');
        }
    }
}
