<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_payment_mode_to_expenses extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('expenses')) {
            $this->dbforge->add_column('expenses', array(
                'payment_mode' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '50',
                    'null' => TRUE,
                    'default' => NULL,
                    'after' => 'amount'
                )
            ));
        }
    }

    public function down()
    {
        if ($this->db->table_exists('expenses')) {
            $this->dbforge->drop_column('expenses', 'payment_mode');
        }
    }
}
