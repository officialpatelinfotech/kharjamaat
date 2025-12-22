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

        $this->dbforge->add_column('appointments', $fields);
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
