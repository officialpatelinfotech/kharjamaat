-- Create table for Expense Source of Funds
CREATE TABLE IF NOT EXISTS `expense_sources` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'Active',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create table for Expense Area of Spend
CREATE TABLE IF NOT EXISTS `expense_areas` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'Active',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create table for Expense records
CREATE TABLE IF NOT EXISTS `expenses` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `expense_date` DATE NOT NULL,
  `area_id` INT UNSIGNED DEFAULT NULL,
  `amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `source_id` INT UNSIGNED NOT NULL,
  `hijri_year` INT NOT NULL,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_expense_date` (`expense_date`),
  KEY `idx_hijri_year` (`hijri_year`),
  CONSTRAINT `fk_expenses_source` FOREIGN KEY (`source_id`) REFERENCES `expense_sources` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_expenses_area` FOREIGN KEY (`area_id`) REFERENCES `expense_areas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
