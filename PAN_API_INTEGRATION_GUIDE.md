# PAN API Services Integration Guide

## 📋 Overview

This project now includes full integration with eKYCHub's PAN verification and creation APIs. The implementation includes:

- **PAN Verification** - Basic PAN card verification
- **PAN 360** - Comprehensive PAN details with Aadhaar linkage
- **PAN Creation** - Apply for new PAN or make corrections

---

## 🗄️ Database Setup

All required tables are included in the main `setup-database.sql` file.

If you've already run the initial database setup, all PAN API tables are created automatically.

### PAN-Related Tables:

1. **`api_transactions`** - Logs all API requests and responses
2. **`pan_360_records`** - Stores PAN 360 verification data  
3. **`pan_creation_requests`** - Tracks PAN creation/correction requests
4. **`pan_verifications`** - Basic PAN verification history (enhanced with API fields)
5. **`pan_applications`** - PAN application records

---

## 🔐 API Configuration

### Update API Credentials

Edit `php/api-config.php` and update with your eKYCHub credentials:

```php
define('EKYCHUB_USERNAME', 'your_username_here');
define('EKYCHUB_TOKEN', 'your_token_here');
```

**Current credentials in file:**
- Username: `9019977330`
- Token: `4cdc6d5954d983ae205b4f0d5ac816b0`

---

## 📁 New Files Added

### PHP Backend Files:
- `php/api-config.php` - API configuration and helper functions
- `php/pan-api.php` - Main API handler for all PAN services

### Frontend Pages:
- `pan-verification.php` - PAN verification page
- `pan-360.php` - PAN 360 details page
- `pan-creation.php` - PAN creation/correction page

### JavaScript Files:
- `js/pan-verification.js` - Handles PAN verification API calls
- `js/pan-360.js` - Handles PAN 360 API calls
- `js/pan-creation.js` - Handles PAN creation API calls

### Database:
- `setup-pan-api-tables.sql` - SQL script for new tables

---

## 🚀 How It Works

### 1. PAN Verification

**User Flow:**
1. User enters PAN number (format: ABCDE1234F)
2. JavaScript sends POST request to `php/pan-api.php?action=pan_verification`
3. Backend validates format and calls eKYCHub API
4. Response shows: PAN number, registered name, and type
5. Data is saved to `pan_verifications` table

**API Endpoint:** `GET https://connect.ekychub.in/v3/verification/pan_verification`

**Success Response:**
```json
{
  "status": "Success",
  "pan": "ABCDE1234F",
  "type": "Individual",
  "registered_name": "RAHUL KUMAR",
  "message": "PAN verified successfully"
}
```

### 2. PAN 360

**User Flow:**
1. User enters PAN number
2. JavaScript sends POST request to `php/pan-api.php?action=pan_360`
3. Backend calls eKYCHub PAN 360 API
4. Response shows comprehensive details including Aadhaar linkage
5. Data is saved to `pan_360_records` table

**API Endpoint:** `GET https://connect.ekychub.in/v3/verification/pan_360`

**Success Response:**
```json
{
  "status": "Success",
  "pan": "DSXXX1687F",
  "type": "Individual or Person",
  "registered_name": "MUTTXXHODY XXX SAMEER",
  "gender": "MALE",
  "date_of_birth": "30-05-1991",
  "masked_aadhaar_number": "XXXXXXXX5228",
  "aadhaar_linked": true,
  "message": "PAN verified successfully"
}
```

### 3. PAN Creation

**User Flow:**
1. User enters mobile number
2. JavaScript sends POST request to `php/pan-api.php?action=pan_creation`
3. Backend generates unique order ID and calls API
4. User receives redirect URL to complete application
5. Data is saved to `pan_creation_requests` table

**API Endpoint:** `GET https://connect.ekychub.in/v3/verification/pan_redirection`

**Success Response:**
```json
{
  "status": "Success",
  "message": "Pan Redirection URL Created",
  "redirect_url": "https://connect.ekychub.in/v3/verification/pan_redirect?data=..."
}
```

---

## 🎨 New UI Features

### Sidebar Navigation with Submenu

The sidebar now includes a collapsible **PAN Services** section with:
- PAN Verification
- PAN 360
- PAN Creation
- PAN Application (existing form)

### Responsive Design

- Mobile-optimized sidebar with overlay
- Touch-friendly buttons and inputs
- Smooth animations and transitions

### Result Display Cards

- Success/Error status indicators
- Detailed information grid
- Copy-to-clipboard functionality (PAN Creation)
- "Verify Another" quick reset buttons

---

## 🔧 Helper Functions

### In `php/api-config.php`:

```php
// Generate unique order ID
generateOrderId($prefix = 'ORD')

// Make API request to eKYCHub
makeEkychubRequest($endpoint, $params = [])

// Log API transaction to database
logApiTransaction($conn, $userId, $apiType, $request, $response, $orderId)

// Validate PAN format
isValidPanFormat($pan)

// Validate mobile format
isValidMobileFormat($mobile)
```

---

## 📊 Database Schema

### api_transactions
```sql
id (INT, PRIMARY KEY)
user_id (INT, FOREIGN KEY)
api_type (VARCHAR) - 'pan_verification', 'pan_360', 'pan_creation'
order_id (VARCHAR, UNIQUE)
request_data (TEXT) - JSON
response_data (TEXT) - JSON
status (VARCHAR) - 'Success', 'Failure', 'Error'
created_at (TIMESTAMP)
```

### pan_360_records
```sql
id (INT, PRIMARY KEY)
user_id (INT, FOREIGN KEY)
pan_number (VARCHAR)
name (VARCHAR)
type (VARCHAR)
gender (VARCHAR)
date_of_birth (VARCHAR)
masked_aadhaar (VARCHAR)
aadhaar_linked (BOOLEAN)
order_id (VARCHAR)
verified_at (TIMESTAMP)
```

### pan_creation_requests
```sql
id (INT, PRIMARY KEY)
user_id (INT, FOREIGN KEY)
mobile_number (VARCHAR)
order_id (VARCHAR, UNIQUE)
redirect_url (TEXT)
status (VARCHAR) - 'initiated', 'failed'
created_at (TIMESTAMP)
completed_at (TIMESTAMP, NULL)
```

---

## 🧪 Testing

### Test PAN Numbers (for development):

You can use these formats for testing:
- **Valid Format:** ABCDE1234F (5 letters, 4 digits, 1 letter)
- **Individual PAN:** Starts with alphabet A-Z
- **Company PAN:** Different first letter indicates type

### Test Mobile Numbers:

- Must be 10 digits
- Must start with 6, 7, 8, or 9
- Example: 9876543210

---

## 🛡️ Security Features

1. **Session Validation** - All API endpoints check user login
2. **Input Validation** - PAN and mobile formats validated
3. **SQL Injection Prevention** - Prepared statements used
4. **cURL with SSL** - Secure API communication
5. **Error Logging** - All API transactions logged to database
6. **Unique Order IDs** - Prevents duplicate requests

---

## 🐛 Error Handling

### Common Errors:

**"Invalid PAN format"**
- Ensure PAN follows format: ABCDE1234F (5 letters, 4 digits, 1 letter)

**"Invalid mobile number"**
- Must be 10 digits starting with 6-9

**"Order ID already exists"**
- System auto-generates unique IDs with timestamps
- If error persists, check `pan_creation_requests` table

**"API Request Failed"**
- Check internet connection
- Verify API credentials in `php/api-config.php`
- Check if eKYCHub service is available

---

## 📝 Usage Example

### Frontend (JavaScript):
```javascript
// PAN Verification
const formData = new FormData();
formData.append('pan', 'ABCDE1234F');

const response = await fetch('php/pan-api.php?action=pan_verification', {
    method: 'POST',
    body: formData
});

const data = await response.json();
if (data.success) {
    console.log('PAN Verified:', data.registered_name);
}
```

### Backend (PHP):
```php
// The API handler automatically:
// 1. Validates input
// 2. Calls eKYCHub API
// 3. Logs transaction
// 4. Saves to database
// 5. Returns JSON response
```

---

## 🔄 Navigation Updates

### Dashboard Service Cards

Updated to show 5 services:
1. **PAN Verification** - Instant verification
2. **PAN 360** - Complete details
3. **PAN Creation** - Apply/correct PAN
4. **PAN Application** - Full application form
5. **Recharge** - Mobile & DTH

### Sidebar Menu Structure

```
Dashboard
├── Home
├── Profile
├── Wallet
├── PAN Services (Submenu)
│   ├── PAN Verification
│   ├── PAN 360
│   ├── PAN Creation
│   └── PAN Application
├── Recharge
└── Logout
```

---

## 🎯 Next Steps

1. **Run Database Migration:**
   ```bash
   mysql -u root -p gsk_services < setup-pan-api-tables.sql
   ```

2. **Update API Credentials:**
   Edit `php/api-config.php` with your credentials

3. **Test Each Service:**
   - Login to dashboard
   - Navigate to each PAN service
   - Test with valid data

4. **Monitor Transactions:**
   Check `api_transactions` table for logs

---

## 📞 Support

For API-related issues:
- Check `api_transactions` table for error logs
- Verify credentials in `php/api-config.php`
- Ensure all required tables exist
- Check PHP error logs

---

## ✅ Checklist

- [ ] Database tables created (run `setup-pan-api-tables.sql`)
- [ ] API credentials updated in `php/api-config.php`
- [ ] Test PAN Verification service
- [ ] Test PAN 360 service
- [ ] Test PAN Creation service
- [ ] Verify data is being saved to database
- [ ] Check API transaction logs

---

**Created:** November 21, 2025  
**Version:** 1.0  
**Last Updated:** November 21, 2025
