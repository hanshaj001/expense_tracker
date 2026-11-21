-- ============================================================================
-- EXPENSE TRACKER DATABASE SCHEMA & SEED DATA
-- Optimized for high performance, indexing, and referential integrity
-- Character Set: utf8mb4 (supports emojis and international characters)
-- ============================================================================

CREATE DATABASE IF NOT EXISTS `expense_tracker` 
  CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

USE `expense_tracker`;

-- ----------------------------------------------------------------------------
-- 1. Table: users
-- Stores registered user accounts with encrypted passwords
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 2. Table: categories
-- System-defined and customizable categories for income and expenses
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `type` ENUM('income', 'expense') NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_cat_type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 3. Table: transactions
-- Records every income and expense entry with cascade delete on user/category
-- Optimized with composite indexes for dashboard analytics and search filtering
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `category_id` INT(11) NOT NULL,
  `type` ENUM('income', 'expense') NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL CHECK (`amount` > 0),
  `notes` TEXT DEFAULT NULL,
  `created_at` DATE NOT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  -- Foreign Keys
  CONSTRAINT `fk_transactions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_transactions_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  -- Optimized Performance Indexes
  INDEX `idx_tx_user_date` (`user_id`, `created_at` DESC),
  INDEX `idx_tx_user_type_date` (`user_id`, `type`, `created_at` DESC),
  INDEX `idx_tx_user_cat` (`user_id`, `category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 4. Table: weekly_limits
-- Stores user-defined weekly spending budgets per category
-- Enforces one limit per user per category via UNIQUE KEY
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `weekly_limits` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `category_id` INT(11) NOT NULL,
  `weekly_limit` DECIMAL(10,2) NOT NULL CHECK (`weekly_limit` >= 0),
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_weekly_limits_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_weekly_limits_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  UNIQUE KEY `uq_user_category` (`user_id`, `category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Default Seed Categories
-- ----------------------------------------------------------------------------
INSERT INTO `categories` (`id`, `name`, `type`) VALUES
  -- Income Categories (1 - 10)
  (1, 'Salary', 'income'),
  (2, 'Business Income', 'income'),
  (3, 'Freelance Work', 'income'),
  (4, 'Investment Returns', 'income'),
  (5, 'Rental Income', 'income'),
  (6, 'Bonus', 'income'),
  (7, 'Interest Income', 'income'),
  (8, 'Gifts Received', 'income'),
  (9, 'Dividends', 'income'),
  (10, 'Other Income', 'income'),

  -- Expense Categories (11 - 20)
  (11, 'Food & Groceries', 'expense'),
  (12, 'Rent', 'expense'),
  (13, 'Transportation', 'expense'),
  (14, 'Utilities', 'expense'),
  (15, 'Internet & Mobile', 'expense'),
  (16, 'Education', 'expense'),
  (17, 'Healthcare', 'expense'),
  (18, 'Entertainment', 'expense'),
  (19, 'Shopping', 'expense'),
  (20, 'Other Expenses', 'expense')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `type` = VALUES(`type`);
