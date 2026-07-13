<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_remove_source_of_funds extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('expenses')) {
            // Drop foreign key fk_expenses_source
            $this->db->query("ALTER TABLE `expenses` DROP FOREIGN KEY `fk_expenses_source`");
            // Drop column source_id
            $this->dbforge->drop_column('expenses', 'source_id');
        }

        // Drop expense_sources table
        $this->dbforge->drop_table('expense_sources', TRUE);
    }

    public function down()
    {
        // Re-create expense_sources table
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'status' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => 'Active',
            ),
            'created_at' => array(
                'type' => 'TIMESTAMP',
                'null' => TRUE,
                'default' => NULL
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('expense_sources', TRUE);

        // Re-add source_id column to expenses
        if ($this->db->table_exists('expenses')) {
            $this->dbforge->add_column('expenses', array(
                'source_id' => array(
                    'type' => 'INT',
                    'constraint' => 10,
                    'unsigned' => TRUE,
                    'after' => 'amount'
                )
            ));

            // Re-add FK constraint
            $this->db->query("ALTER TABLE `expenses` ADD CONSTRAINT `fk_expenses_source` FOREIGN KEY (`source_id`) REFERENCES `expense_sources` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE");
        }
    }
}
