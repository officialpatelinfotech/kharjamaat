<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "Running Database Migrations (Single)...\n";
$user = 'kharjam1_kharjamaat';
$pass = 'khar@2024';
$dbname = 'kharjam1_kharjamaat';
$host = 'localhost';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$queries = [
    "CREATE TABLE IF NOT EXISTS `expense_sources` (
      `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
      `name` VARCHAR(255) NOT NULL,
      `status` VARCHAR(50) NOT NULL DEFAULT 'Active',
      `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    "CREATE TABLE IF NOT EXISTS `expense_items` (
      `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
      `sector_code` VARCHAR(50) DEFAULT NULL,
      `sector_name` VARCHAR(255) NOT NULL,
      `sub_sector_code` VARCHAR(50) DEFAULT NULL,
      `sub_sector_name` VARCHAR(255) NOT NULL,
      `item_code` VARCHAR(50) DEFAULT NULL,
      `item_name` VARCHAR(255) NOT NULL,
      `status` VARCHAR(50) NOT NULL DEFAULT 'Active',
      `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    "CREATE TABLE IF NOT EXISTS `expenses` (
      `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
      `expense_date` DATE NOT NULL,
      `item_id` INT UNSIGNED DEFAULT NULL,
      `amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
      `source_id` INT UNSIGNED NOT NULL,
      `hijri_year` INT NOT NULL,
      `notes` TEXT NULL,
      `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `idx_expense_date` (`expense_date`),
      KEY `idx_hijri_year` (`hijri_year`),
      CONSTRAINT `fk_expenses_source` FOREIGN KEY (`source_id`) REFERENCES `expense_sources` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
      CONSTRAINT `fk_expenses_item` FOREIGN KEY (`item_id`) REFERENCES `expense_items` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
];

foreach ($queries as $i => $q) {
    echo "Executing query $i...\n";
    if (!$conn->query($q)) {
        echo "Error: " . $conn->error . "\n";
    } else {
        echo "Success.\n";
    }
}
echo "Done.\n";
