<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_year_to_miqaat_niyaz_amounts extends CI_Migration
{
  public function up()
  {
    $this->dbforge->add_column('miqaat_niyaz_amounts', array(
      'year' => array(
        'type' => 'VARCHAR',
        'constraint' => '15',
        'null' => TRUE,
        'after' => 'miqaat_type'
      )
    ));

    // Update existing records to default to current year '1445'
    $this->db->update('miqaat_niyaz_amounts', array('year' => '1445'));
  }

  public function down()
  {
    $this->dbforge->drop_column('miqaat_niyaz_amounts', 'year');
  }
}
