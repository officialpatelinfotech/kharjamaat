<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_laagat_rent_thaal_ranges extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('laagat_rent_thaal_ranges')) {
            $this->dbforge->add_field(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'laagat_rent_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => FALSE
                ),
                'thaal_min' => array(
                    'type' => 'INT',
                    'constraint' => 11
                ),
                'thaal_max' => array(
                    'type' => 'INT',
                    'constraint' => 11
                ),
                'rent_sabeel' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '10,2',
                    'default' => '0.00'
                ),
                'deposit_sabeel' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '10,2',
                    'default' => '0.00'
                ),
                'rent_non_sabeel' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '10,2',
                    'default' => '0.00'
                ),
                'deposit_non_sabeel' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '10,2',
                    'default' => '0.00'
                )
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_key('laagat_rent_id');
            $this->dbforge->create_table('laagat_rent_thaal_ranges');
        }
    }

    public function down()
    {
        if ($this->db->table_exists('laagat_rent_thaal_ranges')) {
            $this->dbforge->drop_table('laagat_rent_thaal_ranges');
        }
    }
}
