<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_amount_to_laagat_rent_invoices extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('laagat_rent_invoices')) {
            if (!$this->db->field_exists('amount', 'laagat_rent_invoices')) {
                $this->db->query("ALTER TABLE `laagat_rent_invoices` ADD `amount` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `raza_id`;");
                
                // Optional: Populate existing invoices with master_amount
                $this->db->query("UPDATE laagat_rent_invoices i 
                                  JOIN laagat_rent lr ON lr.id = i.laagat_rent_id 
                                  SET i.amount = lr.amount 
                                  WHERE i.amount = 0");
            }
        }
    }

    public function down()
    {
        if ($this->db->table_exists('laagat_rent_invoices')) {
            if ($this->db->field_exists('amount', 'laagat_rent_invoices')) {
                $this->db->query("ALTER TABLE `laagat_rent_invoices` DROP COLUMN `amount`;");
            }
        }
    }
}
