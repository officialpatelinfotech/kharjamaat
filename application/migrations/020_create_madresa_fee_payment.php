<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_madresa_fee_payment extends CI_Migration
{
  public function up()
  {
    if (!$this->db->table_exists('madresa_fee_payment')) {
      $this->db->query("CREATE TABLE IF NOT EXISTS `madresa_fee_payment` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `m_class_id` INT UNSIGNED NOT NULL,
  `students_its_id` BIGINT NULL DEFAULT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `paid_on` DATE NULL DEFAULT NULL,
  `payment_mode` VARCHAR(50) NULL DEFAULT NULL,
  `reference` VARCHAR(100) NULL DEFAULT NULL,
  `notes` TEXT NULL,
  `created_by` VARCHAR(50) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_madresa_fee_payment_class` (`m_class_id`),
  KEY `idx_madresa_fee_payment_student` (`students_its_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    }
  }

  public function down()
  {
    if ($this->db->table_exists('madresa_fee_payment')) {
      $this->db->query('DROP TABLE `madresa_fee_payment`');
    }
  }
}
