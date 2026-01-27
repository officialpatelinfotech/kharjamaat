<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_madresa_classes extends CI_Migration
{
  public function up()
  {
    if (!$this->db->table_exists('madresa_classes')) {
      $this->db->query("CREATE TABLE IF NOT EXISTS `madresa_classes` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `class_name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'Active',
  `created_by_its_id` BIGINT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_madresa_classes_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    }
  }

  public function down()
  {
    if ($this->db->table_exists('madresa_classes')) {
      $this->db->query('DROP TABLE `madresa_classes`');
    }
  }
}
