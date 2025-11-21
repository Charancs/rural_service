# GSK E Services - Complete Setup Guide

> **🆕 NEW: PAN API Services Integration!**  
> This project now includes full integration with eKYCHub PAN verification APIs.  
> See [PAN_API_INTEGRATION_GUIDE.md](PAN_API_INTEGRATION_GUIDE.md) for details.

## Method 1: Using XAMPP (Recommended for Local Development)

### Step 1: Install XAMPP
1. Download XAMPP from https://www.apachefriends.org/
2. Install to `C:\xampp` (default location)
3. Open **XAMPP Control Panel**

### Step 2: Fix Port Conflicts (if needed)
If you see errors like "Port 80/3306 in use", run PowerShell as Administrator:

```powershell
# Stop IIS (Internet Information Services) - Port 80
net stop was /y
net stop w3svc

# Stop any existing MySQL service - Port 3306
net stop MySQL
net stop MySQL80
```

**OR change XAMPP ports:**
- **Apache Port 80 → 8080:**
  - XAMPP Control Panel → Config (next to Apache) → httpd.conf
  - Change `Listen 80` to `Listen 8080`
  - Change `ServerName localhost:80` to `ServerName localhost:8080`
  
- **MySQL Port 3306 → 3307:**
  - XAMPP Control Panel → Config (next to MySQL) → my.ini
  - Change `port=3306` to `port=3307`

### Step 3: Start Services
1. In XAMPP Control Panel, click **Start** next to **Apache**
2. Click **Start** next to **MySQL**
3. Both should show green "Running" status
4. Note the ports being used (displayed next to service names)

### Step 4: Copy Project to XAMPP

**Using Command Prompt (cmd):**
```cmd
xcopy /E /I /Y "C:\Users\charanc\Desktop\My_project" "C:\xampp\htdocs\My_project"
```

**OR manually:**
1. Open File Explorer
2. Copy `C:\Users\charanc\Desktop\My_project` folder
3. Paste into `C:\xampp\htdocs\`

### Step 5: Create Database

#### Method A - Using MySQL Command Line (Recommended)

```powershell
# Open PowerShell or Command Prompt
cd C:\xampp\mysql\bin

# Login to MySQL (use your port - check XAMPP Control Panel)
# If MySQL is on port 3307:
.\mysql.exe -u root -P 3307

# If MySQL is on port 3306:
.\mysql.exe -u root -P 3306

# Or just (will use default port):
.\mysql.exe -u root
```

Press Enter when asked for password (default is empty).

Then run these SQL commands:

```sql
CREATE DATABASE IF NOT EXISTS gsk_services;
USE gsk_services;
```

Now copy **ALL** the SQL from `setup-database.sql` file and paste it into the MySQL prompt, then press Enter.

**⚠️ IMPORTANT - Fix for MariaDB Users:**

If you get this error:
```
ERROR 1067 (42000): Invalid default value for 'expires_at'
```

Run this command separately:
```sql
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
```

Verify tables were created:
```sql
SHOW TABLES;
```

You should see 5 tables. Type `exit` to quit MySQL.

#### Method B - Using phpMyAdmin

**Fix phpMyAdmin for custom MySQL port:**

If your MySQL is running on port **3307** (not default 3306), phpMyAdmin won't connect. Fix it:

1. Open file: `C:\xampp\phpMyAdmin\config.inc.php` in Notepad
2. Find this line:
   ```php
   $cfg['Servers'][$i]['port'] = '';
   ```
3. Change to:
   ```php
   $cfg['Servers'][$i]['port'] = '3307';
   ```
4. Save and close

**Now access phpMyAdmin:**

1. Open browser: `http://localhost:8080/phpmyadmin` (or port 80 if Apache is on 80)
2. Click **"New"** in left sidebar
3. Database name: `gsk_services`
4. Click **"Create"**
5. Click on `gsk_services` database in left sidebar
6. Click **"SQL"** tab at the top
7. Copy all contents from `setup-database.sql` file
8. Paste into the SQL textarea
9. Click **"Go"** button
10. You should see success message and 5 tables created

### Step 6: Update Database Configuration

Open `C:\xampp\htdocs\rural_service\php\config.php` and verify/update:

```php
define('DB_HOST', 'localhost');
define('DB_PORT', '3307');  // Change to '3306' if your MySQL is on 3306
define('DB_USER', 'root');
define('DB_PASS', '');      // Empty for XAMPP default
define('DB_NAME', 'gsk_services');
```

**Important:** Make sure `DB_PORT` matches your MySQL port in XAMPP!

### Step 7: Access Your Website

Open browser and visit:

- **Main Site:** `http://localhost:8080/My_project/` (or `:80` if Apache is on port 80)
- **Landing Page:** `http://localhost:8080/My_project/landing.html`
- **Register:** `http://localhost:8080/My_project/register.php`
- **Login:** `http://localhost:8080/My_project/login.php`
- **Dashboard:** `http://localhost:8080/My_project/dashboard.php` (after login)

### Test Account

You can login with pre-created admin account:
- **Email:** `admin@gskservices.com`
- **Password:** `Admin@123`

---

## Method 2: Using PHP Built-in Server (Without XAMPP)

### Step 1: Install PHP
1. Download PHP from: https://windows.php.net/download/
2. Extract to `C:\php`
3. Add `C:\php` to system PATH
4. Or use Chocolatey: `choco install php`

### Step 2: Get Remote MySQL Database

**Option A: db4free.net (Free)**
1. Visit: https://db4free.net/signup.php
2. Register and create database
3. Note: Host: `db4free.net`, Port: `3306`, Username, Password, Database name

**Option B: FreeSQLDatabase.com**
1. Visit: https://www.freesqldatabase.com/
2. Sign up and get credentials

### Step 3: Update Configuration

Edit `php/config.php`:
```php
define('DB_HOST', 'db4free.net');  // Your remote host
define('DB_PORT', '3306');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'your_database');
```

### Step 4: Create Tables Remotely

Use MySQL Workbench or phpMyAdmin provided by your hosting to run `setup-database.sql`.

### Step 5: Start Server

```powershell
cd C:\Users\charanc\Desktop\My_project
php -S localhost:8000
```

Access: `http://localhost:8000/`

---

## Troubleshooting

### Registration works but login fails with "user_sessions doesn't exist"

The `user_sessions` table failed to create. Fix:

```powershell
cd C:\xampp\mysql\bin
.\mysql.exe -u root -P 3307  # Use your MySQL port
```

```sql
USE gsk_services;

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
```

### phpMyAdmin shows "Cannot connect" errors

Update the port in `C:\xampp\phpMyAdmin\config.inc.php` to match your MySQL port.

### Port conflicts in XAMPP

- Check which programs are using ports 80/443/3306
- Stop IIS/Skype/other services
- Or change XAMPP ports as described in Step 2

### Page not found errors

Make sure:
1. Project is in `C:\xampp\htdocs\My_project\`
2. Apache is running (green in XAMPP)
3. Using correct URL with Apache port

---

## Features

✅ Beautiful, colorful, mobile-first responsive UI
✅ MySQL database integration
✅ User registration and login system
✅ Session management with security
✅ Password hashing (bcrypt)
✅ Form validation (client & server-side)
✅ PAN Apply service interface
✅ PAN Verification service interface
✅ Mobile & DTH Recharge interface
✅ Helpful messages throughout
✅ Smooth animations and transitions

## Project Structure

```
My_project/
├── landing.html         # Landing page
├── register.php         # Registration page
├── login.php           # Login page
├── dashboard.php       # Dashboard (protected)
├── pan-apply.html      # PAN application
├── pan-verify.html     # PAN verification
├── recharge.html       # Recharge services
├── index.html          # Redirects to landing
├── setup-database.sql  # Database setup script
├── fix-sessions-table.sql  # Fix for user_sessions table
├── css/
│   └── style.css       # Responsive styles
├── js/
│   ├── landing.js
│   ├── register.js
│   ├── login.js
│   ├── dashboard.js
│   └── [other JS files]
└── php/
    ├── config.php      # Database config
    ├── register.php    # Registration handler
    ├── login.php       # Login handler
    └── logout.php      # Logout handler
```

## Security Notes

- Passwords are hashed using PHP's `password_hash()` (bcrypt)
- SQL injection protection via prepared statements
- Session management with secure tokens
- Input validation on client and server side

## PAN API Configuration

After completing the database setup, configure your API credentials:

### Configure API Credentials
Open `php/api-config.php` and update with your eKYCHub credentials:
```php
define('EKYC_USERNAME', 'your_username_here');
define('EKYC_TOKEN', 'your_api_token_here');
```

### Test PAN Services
After login, test each service from the dashboard:
1. **PAN Verification** - Verify existing PAN card
2. **PAN 360** - Get comprehensive PAN details
3. **PAN Creation** - Apply for new PAN card

📖 **Complete Guide:** See [PAN_API_INTEGRATION_GUIDE.md](PAN_API_INTEGRATION_GUIDE.md) for detailed API documentation.

## Next Steps

1. Customize colors in `css/style.css`
2. ✅ ~~Add real API integration for PAN services~~ (Completed - eKYCHub API integrated)
3. Add payment gateway for recharge
4. Add email verification
5. Add forgot password functionality
6. Deploy to production hosting

## Support

For issues, check:
1. Apache and MySQL are running (XAMPP Control Panel)
2. Database tables created successfully (`SHOW TABLES;`)
3. Correct port numbers in config.php
4. PHP errors in Apache error log (XAMPP → Apache → Logs)
5. Browser console for JavaScript errors (F12)
