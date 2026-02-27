<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_app_settings extends CI_Migration
{
  public function up()
  {
    $this->db->query(
      "CREATE TABLE IF NOT EXISTS `app_settings` (
        `key` VARCHAR(64) NOT NULL,
        `value` TEXT NULL,
        `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`key`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
    );

    // Seed default site branding.
    $this->db->query(
      "INSERT IGNORE INTO `app_settings` (`key`, `value`) VALUES
        ('jamaat_name', 'Khar Jamaat')"
    );
  }

  public function down()
  {
    $this->db->query("DROP TABLE IF EXISTS `app_settings`");
  }
}
