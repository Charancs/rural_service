# Quick Start Guide - No XAMPP Required!

## Method 1: Using PHP Built-in Server (Recommended for Development)

### Step 1: Install PHP
**Windows:**
1. Download PHP from: https://windows.php.net/download/
2. Extract to `C:\php`
3. Add `C:\php` to system PATH

**OR use Chocolatey:**
```bash
choco install php
```

**Check installation:**
```bash
php -v
```

### Step 2: Setup Remote MySQL Database

**Option A: Free db4free.net**
1. Visit: https://db4free.net/signup.php
2. Fill the form:
   - Database Name: `gsk_services` (or any name)
   - Username: (choose username)
   - Password: (choose password)
3. Note down your credentials

**Option B: FreeSQLDatabase**
1. Visit: https://www.freesqldatabase.com/
2. Register and create database
3. Note down credentials

### Step 3: Update Database Configuration

Edit `php/config.php`:

```php
// Example for db4free.net
define('DB_HOST', 'db4free.net');
define('DB_PORT', '3306');
define('DB_USER', 'your_username');      // Your db4free username
define('DB_PASS', 'your_password');      // Your db4free password
define('DB_NAME', 'your_database_name'); // Your database name
```

### Step 4: Create Database Tables

**Using MySQL Workbench:**
1. Download: https://dev.mysql.com/downloads/workbench/
2. Create new connection:
   - Hostname: db4free.net (or your host)
   - Port: 3306
   - Username: (your username)
   - Password: (your password)
3. Run the SQL script from `DATABASE_SETUP.md`

**Or use online phpMyAdmin** (if provided by your host)

### Step 5: Run the Website

Open terminal in project folder:
```bash
cd C:\Users\charanc\Desktop\My_project
php -S localhost:8000
```

Open browser: `http://localhost:8000/landing.html`

---

## Method 2: Deploy to Free Hosting (Production Ready)

### Recommended Free Hosts:

**1. InfinityFree** (Best Option)
- Website: https://infinityfree.net/
- Free MySQL database included
- Free subdomain
- No ads

**Steps:**
1. Sign up at infinityfree.net
2. Create new account/website
3. Note MySQL credentials from control panel
4. Upload project files via File Manager or FTP
5. Update `php/config.php` with provided MySQL details
6. Access via your free subdomain

**2. 000webhost**
- Website: https://www.000webhost.com/
- Free hosting + MySQL
- Steps similar to InfinityFree

**3. Netlify + Free MySQL**
- Deploy frontend to Netlify (free)
- Use db4free.net for MySQL
- Update config with remote MySQL URL

---

## Quick Test (Without Database)

To test the UI without database setup:

1. Open `landing.html` directly in browser:
   ```
   C:\Users\charanc\Desktop\My_project\landing.html
   ```

2. For PHP pages, run:
   ```bash
   php -S localhost:8000
   ```
   Then visit: `http://localhost:8000/landing.html`

---

## Troubleshooting

**"php is not recognized"**
- Install PHP from https://windows.php.net/download/
- Add PHP folder to system PATH

**"Cannot connect to database"**
- Verify database credentials in `php/config.php`
- Check if remote database allows external connections
- Test connection using MySQL Workbench first

**"Port 8000 already in use"**
```bash
php -S localhost:8080
```
(Use different port)

---

## Example Database Connection Strings

**db4free.net:**
```php
define('DB_HOST', 'db4free.net');
define('DB_PORT', '3306');
define('DB_USER', 'myusername');
define('DB_PASS', 'mypassword');
define('DB_NAME', 'gsk_services');
```

**AWS RDS:**
```php
define('DB_HOST', 'mydb.123456.us-east-1.rds.amazonaws.com');
define('DB_PORT', '3306');
define('DB_USER', 'admin');
define('DB_PASS', 'mypassword');
define('DB_NAME', 'gsk_services');
```

**Azure MySQL:**
```php
define('DB_HOST', 'myserver.mysql.database.azure.com');
define('DB_PORT', '3306');
define('DB_USER', 'admin@myserver');
define('DB_PASS', 'mypassword');
define('DB_NAME', 'gsk_services');
```

---

## Summary

✅ **No XAMPP/WAMP needed** - Use PHP built-in server
✅ **Remote MySQL** - Use free cloud database
✅ **Free hosting available** - Deploy to InfinityFree
✅ **Easy setup** - Just update config.php

**Fastest way to get started:**
1. Get free database from db4free.net (2 minutes)
2. Update php/config.php with credentials
3. Run: `php -S localhost:8000`
4. Open: `http://localhost:8000/landing.html`

Done! 🚀
