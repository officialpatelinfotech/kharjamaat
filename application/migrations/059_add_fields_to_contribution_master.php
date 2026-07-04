<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_fields_to_contribution_master extends CI_Migration
{
  public function up()
  {
    $this->dbforge->add_column('fmb_general_contribution_master', array(
      'amount' => array(
        'type' => 'DECIMAL',
        'constraint' => '10,2',
        'null' => TRUE,
        'default' => NULL,
        'after' => 'status'
      ),
      'hijri_year' => array(
        'type' => 'VARCHAR',
        'constraint' => '15',
        'null' => TRUE,
        'default' => NULL,
        'after' => 'amount'
      )
    ));
  }

  public function down()
  {
    $this->dbforge->drop_column('fmb_general_contribution_master', 'amount');
    $this->dbforge->drop_column('fmb_general_contribution_master', 'hijri_year');
  }
}
