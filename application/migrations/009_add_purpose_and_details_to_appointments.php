<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_purpose_and_details_to_appointments extends CI_Migration {

    public function up()
    {
        $this->load->dbforge();

        $fields = array(
            'purpose' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
                'default' => NULL,
                'comment' => 'Appointment purpose'
            ),
            'other_details' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => NULL,
                'comment' => 'Optional extra details provided by user'
            ),
        );

        // Only add columns that do not already exist to avoid duplicate column errors
        $to_add = array();
        foreach (array_keys($fields) as $col) {
            if (!$this->db->field_exists($col, 'appointments')) {
                $to_add[$col] = $fields[$col];
            }
        }

        if (!empty($to_add)) {
            $this->dbforge->add_column('appointments', $to_add);
        }
    }

    public function down()
    {
        $this->load->dbforge();

        // drop columns if exist
        if ($this->db->field_exists('purpose', 'appointments')) {
            $this->dbforge->drop_column('appointments', 'purpose');
        }

        if ($this->db->field_exists('other_details', 'appointments')) {
            $this->dbforge->drop_column('appointments', 'other_details');
        }
    }
}
