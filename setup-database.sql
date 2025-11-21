-- GSK E Services Database Setup
-- Run this SQL in MySQL Workbench or phpMyAdmin

-- Create database (skip if already created by hosting provider)
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

-- User Sessions table
CREATE TABLE IF NOT EXISTS user_sessions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    session_token VARCHAR(255) NOT NULL UNIQUE,
    ip_address VARCHAR(50),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_token (session_token),
    INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table for API transaction logs (PAN Services)
CREATE TABLE IF NOT EXISTS api_transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    api_type VARCHAR(50) NOT NULL,
    order_id VARCHAR(50) NOT NULL UNIQUE,
    request_data TEXT,
    response_data TEXT,
    status VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_order_id (order_id),
    INDEX idx_api_type (api_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Update pan_verifications table to include more fields for PAN API
ALTER TABLE pan_verifications 
ADD COLUMN IF NOT EXISTS type VARCHAR(50) AFTER name,
ADD COLUMN IF NOT EXISTS status VARCHAR(20) DEFAULT 'verified' AFTER dob,
ADD COLUMN IF NOT EXISTS order_id VARCHAR(50) AFTER pan_number,
ADD COLUMN IF NOT EXISTS verified_at_timestamp TIMESTAMP NULL AFTER is_valid,
ADD INDEX IF NOT EXISTS idx_order_id (order_id);

-- Table for PAN 360 records (Comprehensive PAN data)
CREATE TABLE IF NOT EXISTS pan_360_records (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    pan_number VARCHAR(10) NOT NULL,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50),
    gender VARCHAR(10),
    date_of_birth VARCHAR(20),
    masked_aadhaar VARCHAR(20),
    aadhaar_linked BOOLEAN DEFAULT FALSE,
    order_id VARCHAR(50) NOT NULL,
    verified_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_pan (pan_number),
    INDEX idx_order_id (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table for PAN creation/correction requests
CREATE TABLE IF NOT EXISTS pan_creation_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    mobile_number VARCHAR(15) NOT NULL,
    order_id VARCHAR(50) NOT NULL UNIQUE,
    redirect_url TEXT,
    status VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_order_id (order_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert test admin user (password: Admin@123)
INSERT INTO users (first_name, last_name, email, mobile, password, state) 
VALUES ('Admin', 'User', 'admin@gskservices.com', '9999999999', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Maharashtra')
ON DUPLICATE KEY UPDATE id=id;

-- Verify tables created
SHOW TABLES;

SELECT 'All database tables created successfully! Total: 8 tables' as status;
