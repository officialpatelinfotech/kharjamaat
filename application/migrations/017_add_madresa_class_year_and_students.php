<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_madresa_class_year_and_students extends CI_Migration
{
  public function up()
  {
    // Add Hijri year to classes
    if ($this->db->table_exists('madresa_classes') && !$this->db->field_exists('hijri_year', 'madresa_classes')) {
      $this->db->query("ALTER TABLE `madresa_classes` ADD COLUMN `hijri_year` INT NULL AFTER `description`;");
    }

    // Mapping table: class -> student ITS
    if (!$this->db->table_exists('madresa_class_students')) {
      $this->db->query("CREATE TABLE IF NOT EXISTS `madresa_class_students` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `class_id` INT UNSIGNED NOT NULL,
  `student_its_id` BIGINT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_class_student` (`class_id`, `student_its_id`),
  KEY `idx_mcs_student` (`student_its_id`),
  KEY `idx_mcs_class` (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    }
  }

  public function down()
  {
    if ($this->db->table_exists('madresa_class_students')) {
      $this->db->query('DROP TABLE `madresa_class_students`');
    }

    if ($this->db->table_exists('madresa_classes') && $this->db->field_exists('hijri_year', 'madresa_classes')) {
      $this->db->query('ALTER TABLE `madresa_classes` DROP COLUMN `hijri_year`');
    }
  }
}
