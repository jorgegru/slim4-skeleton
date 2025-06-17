-- Create database if not exists
CREATE DATABASE IF NOT EXISTS `slimdb` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Grant all privileges to the slim user
GRANT ALL PRIVILEGES ON `slimdb`.* TO 'slim'@'%';
FLUSH PRIVILEGES;

-- Select the database
USE `slimdb`;

-- Add any initial database schema here
-- For example:
-- CREATE TABLE IF NOT EXISTS `users` (
--   `id` int(11) NOT NULL AUTO_INCREMENT,
--   `username` varchar(50) NOT NULL,
--   `email` varchar(255) NOT NULL,
--   `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
--   PRIMARY KEY (`id`),
--   UNIQUE KEY `email` (`email`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
