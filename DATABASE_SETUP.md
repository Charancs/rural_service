# GSK E Services - Remote MySQL Database Setup

## Option 1: Free MySQL Hosting Services

### Using db4free.net (Free Forever)
1. Go to https://db4free.net/signup.php
2. Register for a free account
3. You'll receive:
   - Database Name: (your chosen name)
   - Username: (your chosen username)
   - Password: (your chosen password)
   - Host: db4free.net
   - Port: 3306

### Using FreeSQLDatabase.com
1. Go to https://www.freesqldatabase.com/
2. Sign up and create a database
3. Get your connection details

### Using InfinityFree or 000webhost
1. Sign up for free hosting
2. Access phpMyAdmin from control panel
3. Create database and note credentials

## Option 2: Cloud Database Services

### AWS RDS (Free Tier Available)
1. Create AWS account
2. Create RDS MySQL instance
3. Note endpoint URL and credentials

### Azure Database for MySQL
1. Create Azure account
2. Create MySQL flexible server
3. Note server name and credentials

### Google Cloud SQL
1. Create Google Cloud account
2. Create MySQL instance
3. Note connection details

## Setup Steps

### Step 1: Get Your Remote MySQL Database

Choose one of the services above and get your:
- Database Host (e.g., db4free.net)
- Port (usually 3306)
- Username
- Password
- Database Name

### Step 2: Update Configuration File

Open `php/config.php` and update these values:

```php
define('DB_HOST', 'your-database-host.com');     // e.g., db4free.net
define('DB_PORT', '3306');
define('DB_USER', 'your-username');
define('DB_PASS', 'your-password');
define('DB_NAME', 'your-database-name');
```

### Step 3: Create Database Tables

Connect to your remote database using:
- **MySQL Workbench**: Add new connection with your remote details
- **phpMyAdmin**: If provided by your hosting service
- **Online SQL Editor**: Use your hosting provider's interface

Run the following SQL commands:

```sql
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
```

### Step 3: Configure Database Connection

1. Open `php/config.php`
2. Update these constants if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');  // Your MySQL username
   define('DB_PASS', '');      // Your MySQL password
   define('DB_NAME', 'gsk_services');
   ```

### Step 4: Run PHP Built-in Server (No XAMPP/WAMP Required!)

1. **Install PHP** (if not already installed):
   - Windows: Download from https://windows.php.net/download/
   - Or use Chocolatey: `choco install php`

2. **Open Command Prompt/Terminal** in your project folder:
   ```bash
   cd C:\Users\charanc\Desktop\My_project
   ```

3. **Start PHP Development Server**:
   ```bash
   php -S localhost:8000
   ```

4. **Open browser and visit**:
   - Landing Page: `http://localhost:8000/landing.html`
   - Register: `http://localhost:8000/register.php`
   - Login: `http://localhost:8000/login.php`

### Alternative: Deploy to Free Hosting

**Deploy to InfinityFree, 000webhost, or similar:**
1. Upload all project files via FTP/File Manager
2. Use their provided MySQL database
3. Update `php/config.php` with their database details
4. Access via your free subdomain (e.g., yoursite.infinityfreeapp.com)

## Project Structure

```
My_project/
├── landing.html        (Beautiful landing page)
├── register.php        (Registration with database)
├── login.php          (Login with authentication)
├── dashboard.php      (User dashboard - requires login)
├── pan-apply.html     (PAN application form)
├── pan-verify.html    (PAN verification)
├── recharge.html      (Mobile & DTH recharge)
├── css/
│   └── style.css      (Colorful responsive styles)
├── js/
│   ├── landing.js     (Landing page scripts)
│   ├── register.js    (Registration logic)
│   ├── login.js       (Login logic)
│   ├── dashboard.js   (Dashboard functionality)
│   ├── pan-apply.js
│   ├── pan-verify.js
│   └── recharge.js
└── php/
    ├── config.php     (Database configuration)
    ├── register.php   (Registration handler)
    ├── login.php      (Login handler)
    └── logout.php     (Logout handler)
```

## Features

✅ Beautiful, colorful, and modern UI design
✅ Fully responsive (mobile-first approach)
✅ MySQL database integration
✅ User registration and login system
✅ Session management
✅ Password hashing for security
✅ Form validation
✅ PAN Apply service
✅ PAN Verification service
✅ Mobile & DTH Recharge service
✅ Helpful messages and instructions throughout
✅ Smooth animations and transitions

## Default Test Account

After running the SQL script, you can login with:
- Email: `admin@gskservices.com`
- Password: `Admin@123`

## Next Steps

1. Customize the color scheme in `css/style.css`
2. Add real API integration for PAN and recharge services
3. Add email verification for registration
4. Add forgot password functionality
5. Add transaction history pages

## Support

For any issues, check:
1. Apache and MySQL are running in XAMPP
2. Database is created successfully
3. PHP errors in Apache error logs
4. Browser console for JavaScript errors
