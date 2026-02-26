<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_missing_status_to_madresa_class extends CI_Migration
{
  public function up()
  {
    if ($this->db->table_exists('madresa_class') && !$this->db->field_exists('status', 'madresa_class')) {
      $this->db->query("ALTER TABLE `madresa_class` ADD COLUMN `status` VARCHAR(50) NOT NULL DEFAULT 'Active' AFTER `fees`;");
      $this->db->query("CREATE INDEX `idx_madresa_class_status` ON `madresa_class` (`status`);");
    }
  }

  public function down()
  {
    if ($this->db->table_exists('madresa_class') && $this->db->field_exists('status', 'madresa_class')) {
      // Drop index first if it exists
      $idx = $this->db->query("SHOW INDEX FROM `madresa_class` WHERE Key_name = 'idx_madresa_class_status'")->row_array();
      if (!empty($idx)) {
        $this->db->query("DROP INDEX `idx_madresa_class_status` ON `madresa_class` ");
      }
      $this->db->query('ALTER TABLE `madresa_class` DROP COLUMN `status`');
    }
  }
}
