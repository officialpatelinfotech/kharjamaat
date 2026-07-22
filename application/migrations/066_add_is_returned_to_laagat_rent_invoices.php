<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_is_returned_to_laagat_rent_invoices extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('laagat_rent_invoices')) {
            if (!$this->db->field_exists('is_returned', 'laagat_rent_invoices')) {
                $this->dbforge->add_column('laagat_rent_invoices', array(
                    'is_returned' => array(
                        'type' => 'TINYINT',
                        'constraint' => 1,
                        'default' => 0,
                        'after' => 'sarkaar_amount'
                    )
                ));
            }
        }
    }

    public function down()
    {
        if ($this->db->table_exists('laagat_rent_invoices')) {
            if ($this->db->field_exists('is_returned', 'laagat_rent_invoices')) {
                $this->dbforge->drop_column('laagat_rent_invoices', 'is_returned');
            }
        }
    }
}
