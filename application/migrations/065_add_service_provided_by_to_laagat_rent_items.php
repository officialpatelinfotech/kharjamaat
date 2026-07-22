<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_service_provided_by_to_laagat_rent_items extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('laagat_rent_items')) {
            if (!$this->db->field_exists('service_provided_by', 'laagat_rent_items')) {
                $this->dbforge->add_column('laagat_rent_items', array(
                    'service_provided_by' => array(
                        'type' => 'VARCHAR',
                        'constraint' => 50,
                        'default' => 'Jamaat',
                        'after' => 'item_name'
                    )
                ));
            }
        }
    }

    public function down()
    {
        if ($this->db->table_exists('laagat_rent_items')) {
            if ($this->db->field_exists('service_provided_by', 'laagat_rent_items')) {
                $this->dbforge->drop_column('laagat_rent_items', 'service_provided_by');
            }
        }
    }
}
