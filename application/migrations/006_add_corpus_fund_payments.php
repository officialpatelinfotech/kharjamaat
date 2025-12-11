<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_corpus_fund_payments extends CI_Migration
{
    public function up()
    {
        // Create payments table if not exists
        if (!$this->db->table_exists('corpus_fund_payment')) {
            $this->db->query("CREATE TABLE `corpus_fund_payment` (\n                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,\n                `fund_id` INT UNSIGNED NOT NULL,\n                `hof_id` INT NOT NULL,\n                `amount_paid` DECIMAL(10,2) NOT NULL DEFAULT 0,\n                `notes` VARCHAR(255) NULL,\n                `received_by` INT NULL,\n                `paid_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,\n                PRIMARY KEY (`id`),\n                KEY `idx_fund_hof` (`fund_id`,`hof_id`),\n                CONSTRAINT `fk_corpus_payment_fund` FOREIGN KEY (`fund_id`) REFERENCES `corpus_fund`(`id`) ON DELETE CASCADE ON UPDATE CASCADE\n            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }
    }

    public function down()
    {
        if ($this->db->table_exists('corpus_fund_payment')) {
            $this->db->query("DROP TABLE `corpus_fund_payment`");
        }
    }
}
