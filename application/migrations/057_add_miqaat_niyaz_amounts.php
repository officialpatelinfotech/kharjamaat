<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_miqaat_niyaz_amounts extends CI_Migration
{
  public function up()
  {
    $this->dbforge->add_field(array(
      'id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'unsigned' => TRUE,
        'auto_increment' => TRUE
      ),
      'miqaat_type' => array(
        'type' => 'VARCHAR',
        'constraint' => '255',
      ),
      'individual_amount' => array(
        'type' => 'DECIMAL',
        'constraint' => '10,2',
        'default' => '0.00',
      ),
      'fala_amount' => array(
        'type' => 'DECIMAL',
        'constraint' => '10,2',
        'default' => '0.00',
      ),
      'created_at' => array(
        'type' => 'TIMESTAMP',
        'null' => TRUE,
      ),
      'updated_at' => array(
        'type' => 'TIMESTAMP',
        'null' => TRUE,
      ),
    ));
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table('miqaat_niyaz_amounts', TRUE);

    // Seed default data if table is empty
    if ($this->db->count_all('miqaat_niyaz_amounts') == 0) {
      $data = array(
        array('miqaat_type' => 'General', 'individual_amount' => 0.00, 'fala_amount' => 0.00),
        array('miqaat_type' => 'Ashara', 'individual_amount' => 0.00, 'fala_amount' => 0.00),
        array('miqaat_type' => 'Shehrullah', 'individual_amount' => 0.00, 'fala_amount' => 0.00),
        array('miqaat_type' => 'Ladies', 'individual_amount' => 0.00, 'fala_amount' => 0.00),
      );
      $this->db->insert_batch('miqaat_niyaz_amounts', $data);
    }
  }

  public function down()
  {
    $this->dbforge->drop_table('miqaat_niyaz_amounts');
  }
}
