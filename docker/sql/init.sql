-- Create database if not exists
CREATE DATABASE IF NOT EXISTS crm_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use the database
USE crm_db;

-- Create customers table
CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_name (name),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data
INSERT INTO customers (name, email, phone) VALUES
('Ján Novák', 'jan.novak@example.com', '+421 901 234 567'),
('Mária Horváthová', 'maria.horvathova@example.com', '+421 902 345 678'),
('Peter Kováč', 'peter.kovac@example.com', '+421 903 456 789'),
('Eva Tóthová', 'eva.tothova@example.com', '+421 904 567 890'),
('Martin Szabó', 'martin.szabo@example.com', '+421 905 678 901');
