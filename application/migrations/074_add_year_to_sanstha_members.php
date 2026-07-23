<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_year_to_sanstha_members extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('sanstha_members')) {
            if (!$this->db->field_exists('year', 'sanstha_members')) {
                $fields = array(
                    'year' => array(
                        'type' => 'VARCHAR',
                        'constraint' => 10,
                        'default' => '1448',
                        'after' => 'user_its'
                    )
                );
                $this->dbforge->add_column('sanstha_members', $fields);
                $this->db->query("ALTER TABLE sanstha_members DROP INDEX sanstha_user_unique;");
                $this->db->query("ALTER TABLE sanstha_members ADD UNIQUE KEY sanstha_user_year_unique (sanstha_id, user_its, year);");
            }
        }
    }

    public function down()
    {
        if ($this->db->table_exists('sanstha_members')) {
            if ($this->db->field_exists('year', 'sanstha_members')) {
                $this->dbforge->drop_column('sanstha_members', 'year');
            }
        }
    }
}
