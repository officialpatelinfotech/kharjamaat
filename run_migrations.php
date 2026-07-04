<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "Running Database Migrations...\n";

try {
    $envFile = __DIR__ . '/.env';
    if (is_readable($envFile)) {
        $lines = @file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '#') === 0) continue;
            $eqPos = strpos($line, '=');
            if ($eqPos !== false) {
                $k = trim(substr($line, 0, $eqPos));
                $v = trim(substr($line, $eqPos + 1));
                putenv("$k=$v");
                $_ENV[$k] = $v;
            }
        }
    }
    
    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $user = getenv('DB_USERNAME') ?: 'kharjam1_kharjamaat';
    $pass = getenv('DB_PASSWORD') ?: 'khar@2024';
    $dbname = getenv('DB_DATABASE') ?: 'kharjam1_kharjamaat';
    
    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "
    -- Create table for Expense Source of Funds
    CREATE TABLE IF NOT EXISTS `expense_sources` (
      `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
      `name` VARCHAR(255) NOT NULL,
      `status` VARCHAR(50) NOT NULL DEFAULT 'Active',
      `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    
    -- Create table for Expense Items classification
    CREATE TABLE IF NOT EXISTS `expense_items` (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    
    -- Create table for Expense records
    CREATE TABLE IF NOT EXISTS `expenses` (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    if ($conn->multi_query($sql)) {
        do {
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->more_results() && $conn->next_result());
        echo "Migrations executed successfully.\n";
    } else {
        echo "Error executing migrations: " . $conn->error . "\n";
    }
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
