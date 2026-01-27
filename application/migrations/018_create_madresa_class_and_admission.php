<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_madresa_class_and_admission extends CI_Migration
{
  public function up()
  {
    // New table: Madresa_class
    if (!$this->db->table_exists('madresa_class')) {
      $this->db->query("CREATE TABLE IF NOT EXISTS `madresa_class` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `name` VARCHAR(255) NOT NULL,
  `year` INT NOT NULL,
  `fees` DECIMAL(10,2) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_madresa_class_year` (`year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    }

    // New table: Madresa_class_admission
    if (!$this->db->table_exists('madresa_class_admission')) {
      $this->db->query("CREATE TABLE IF NOT EXISTS `madresa_class_admission` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `students_its_id` BIGINT NOT NULL,
  `m_class_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_madresa_admission` (`m_class_id`, `students_its_id`),
  KEY `idx_madresa_admission_student` (`students_its_id`),
  KEY `idx_madresa_admission_class` (`m_class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    }

    // Best-effort data migration from legacy tables (if present)
    if ($this->db->table_exists('madresa_classes')) {
      $newCount = (int)$this->db->count_all('madresa_class');
      if ($newCount === 0) {
        // Preserve legacy IDs so existing links keep working.
        // Fees were previously stored in `madresa_classes.description`.
        $this->db->query("INSERT INTO `madresa_class` (`id`, `created_at`, `name`, `year`, `fees`)
          SELECT
            c.`id`,
            c.`created_at`,
            c.`class_name`,
            COALESCE(c.`hijri_year`, 0),
            CAST(NULLIF(TRIM(c.`description`), '') AS DECIMAL(10,2))
          FROM `madresa_classes` c");

        // Ensure auto-increment continues after the highest migrated id
        $maxIdRow = $this->db->query('SELECT MAX(`id`) AS max_id FROM `madresa_class`')->row_array();
        $maxId = !empty($maxIdRow['max_id']) ? (int)$maxIdRow['max_id'] : 0;
        if ($maxId > 0) {
          $this->db->query('ALTER TABLE `madresa_class` AUTO_INCREMENT = ' . ((int)$maxId + 1));
        }
      }
    }

    if ($this->db->table_exists('madresa_class_students')) {
      $newAdmCount = (int)$this->db->count_all('madresa_class_admission');
      if ($newAdmCount === 0) {
        $this->db->query("INSERT IGNORE INTO `madresa_class_admission` (`created_at`, `students_its_id`, `m_class_id`)
          SELECT
            cs.`created_at`,
            cs.`student_its_id`,
            cs.`class_id`
          FROM `madresa_class_students` cs");
      }
    }
  }

  public function down()
  {
    if ($this->db->table_exists('madresa_class_admission')) {
      $this->db->query('DROP TABLE `madresa_class_admission`');
    }

    if ($this->db->table_exists('madresa_class')) {
      $this->db->query('DROP TABLE `madresa_class`');
    }
  }
}
