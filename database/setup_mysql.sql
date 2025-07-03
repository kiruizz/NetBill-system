-- Network Billing System Database Setup
-- Run this script in your MySQL client (phpMyAdmin, MySQL Workbench, etc.)

-- Create database
CREATE DATABASE IF NOT EXISTS network_billing 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Use the database
USE network_billing;

-- Create a dedicated user for the application (optional but recommended)
-- Replace 'your_password' with a secure password
-- CREATE USER IF NOT EXISTS 'network_billing_user'@'localhost' IDENTIFIED BY 'your_password';
-- GRANT ALL PRIVILEGES ON network_billing.* TO 'network_billing_user'@'localhost';
-- FLUSH PRIVILEGES;

-- Show databases to confirm creation
SHOW DATABASES LIKE 'network_billing';
