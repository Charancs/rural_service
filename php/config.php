<?php
/*
 * Database Configuration File
 * 
 * REMOTE MYSQL DATABASE SETUP:
 * Replace the values below with your remote MySQL database credentials.
 * 
 * You can use:
 * - Free MySQL hosting: db4free.net, freemysqlhosting.net
 * - Cloud databases: AWS RDS, Azure Database, Google Cloud SQL
 * - Shared hosting MySQL database
 * 
 * Example connection strings:
 * - db4free.net: db4free.net:3306
 * - AWS RDS: your-instance.region.rds.amazonaws.com:3306
 * - Azure: yourserver.mysql.database.azure.com:3306
 */

// Database configuration - XAMPP Local Database
define('DB_HOST', 'localhost');                  // XAMPP MySQL host
define('DB_PORT', '3307');                       // MySQL port (usually 3306)
define('DB_USER', 'root');                       // XAMPP default username
define('DB_PASS', '');                           // XAMPP default password (empty)
define('DB_NAME', 'gsk_services');               // Your database name

// Create database connection
function getDBConnection() {
    try {
        // Connect with port if specified
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        $conn->set_charset("utf8mb4");
        return $conn;
    } catch (Exception $e) {
        // Log error for debugging (remove in production)
        error_log("Database connection error: " . $e->getMessage());
        die("Unable to connect to database. Please check your database configuration.");
    }
}

/*
 * SQL SCRIPT TO CREATE TABLES IN MYSQL WORKBENCH:
 * 
 * Copy and paste the following SQL commands in MySQL Workbench:
 */

/*

-- Create database
CREATE DATABASE IF NOT EXISTS gsk_services;
USE gsk_services;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mobile VARCHAR(15) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    state VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    INDEX idx_email (email),
    INDEX idx_mobile (mobile)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PAN Applications table
CREATE TABLE IF NOT EXISTS pan_applications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    reference_number VARCHAR(20) NOT NULL UNIQUE,
    applicant_name VARCHAR(100) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50),
    last_name VARCHAR(50) NOT NULL,
    father_name VARCHAR(100) NOT NULL,
    dob DATE NOT NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    email VARCHAR(100) NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(50) NOT NULL,
    state VARCHAR(50) NOT NULL,
    pincode VARCHAR(10) NOT NULL,
    aadhaar VARCHAR(12) NOT NULL,
    status ENUM('pending', 'processing', 'approved', 'rejected') DEFAULT 'pending',
    applied_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    processed_date TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_reference (reference_number),
    INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PAN Verification History table
CREATE TABLE IF NOT EXISTS pan_verifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    pan_number VARCHAR(10) NOT NULL,
    name VARCHAR(100) NOT NULL,
    dob DATE NOT NULL,
    is_valid BOOLEAN NOT NULL,
    verified_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_pan (pan_number),
    INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Recharge Transactions table
CREATE TABLE IF NOT EXISTS recharge_transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    transaction_id VARCHAR(20) NOT NULL UNIQUE,
    type ENUM('mobile', 'dth') NOT NULL,
    mobile_number VARCHAR(15),
    operator VARCHAR(50),
    circle VARCHAR(50),
    subscriber_id VARCHAR(50),
    amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'success', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_transaction (transaction_id),
    INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- User Sessions table (for security)
CREATE TABLE IF NOT EXISTS user_sessions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    session_token VARCHAR(255) NOT NULL UNIQUE,
    ip_address VARCHAR(50),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_token (session_token),
    INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create a default admin user (password: Admin@123)
INSERT INTO users (first_name, last_name, email, mobile, password, state) 
VALUES ('Admin', 'User', 'admin@gskservices.com', '9999999999', 
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Karnataka')
ON DUPLICATE KEY UPDATE id=id;

*/

?>
