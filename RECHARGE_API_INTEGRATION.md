# Recharge API Integration Guide

## 📋 Overview

Mobile recharge feature now includes automatic operator detection and plan fetching using eKYCHub APIs. The user experience is seamless - they only need to enter their mobile number and select an amount.

---

## 🔄 How It Works

### User Flow:
1. User enters mobile number (10 digits)
2. **Automatic operator detection** happens on blur
3. **Operator and circle are auto-filled**
4. **Recharge plans are automatically fetched** and displayed as quick amount buttons
5. User selects amount (from suggested plans or custom)
6. User clicks "Proceed to Recharge"
7. Transaction is processed and saved

### API Flow:
```
User enters mobile → operator_fetch API → Auto-fill operator/circle
                   ↓
              operator_plan_fetch API → Display popular plans
                   ↓
User selects amount → process_recharge → Save transaction
```

---

## 🗄️ Files Modified/Created

### New Files:
1. **`php/recharge-api.php`** - Main API handler with 3 actions:
   - `fetch_operator` - Detects operator from mobile number
   - `fetch_plans` - Fetches recharge plans
   - `process_recharge` - Processes recharge transaction

### Modified Files:
1. **`js/recharge.js`** - Updated with:
   - Auto-detect operator on mobile number blur
   - Fetch and display recharge plans
   - Process recharge with API integration
   - Show notifications for success/error

2. **`recharge.php`** - Updated form:
   - Removed manual operator/circle selection dropdowns
   - Auto-filled operator and circle (read-only)
   - Added help text for user guidance

3. **`css/style.css`** - Added:
   - `.form-help` styling for helper text
   - Notification animations (slideIn/slideOut)

---

## 🔐 API Credentials

Configured in `php/api-config.php`:
- **Username:** `9019977330`
- **Token:** `4cdc6d5954d983ae205b4f0d5ac816b0`
- **Base URL:** `https://connect.ekychub.in/v3/verification/`

---

## 📡 API Endpoints Used

### 1. Operator Fetch API
**Endpoint:** `operator_fetch`

**Request:**
```
GET https://connect.ekychub.in/v3/verification/operator_fetch
?username=9019977330
&token=4cdc6d5954d983ae205b4f0d5ac816b0
&mobile=9468455xxx
&orderid=OPRxxxxxxxxx
```

**Success Response:**
```json
{
  "status": "Success",
  "number": "9468455xxx",
  "company": "BSNL",
  "circle": "Haryana",
  "circle_code": "96",
  "message": "Operator fetched Successfully"
}
```

### 2. Operator Plan Fetch API
**Endpoint:** `operator_plan_fetch`

**Request:**
```
GET https://connect.ekychub.in/v3/verification/operator_plan_fetch
?username=9019977330
&token=4cdc6d5954d983ae205b4f0d5ac816b0
&mobile=9468455xxx
&opcode=BT
&circle=96
&orderid=PLNxxxxxxxxx
```

**Success Response:**
```json
{
  "status": "Success",
  "Operator": "BSNL TOPUP",
  "message": "Operator Plan Successfully",
  "data": {
    "TOPUP": [...],
    "DATA": [...],
    "SMS": null,
    "Roaming": [...],
    "COMBO": null,
    "FRC": null,
    "STV": [...]
  }
}
```

---

## 🔑 Operator Codes

| Operator | Code |
|----------|------|
| Airtel | A |
| Vodafone/Vi | V |
| Jio | J |
| BSNL Topup | BT |
| BSNL Special | BS |

---

## 💾 Database Integration

Recharge transactions are saved to `recharge_transactions` table:

**Table Schema:**
```sql
CREATE TABLE recharge_transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    transaction_id VARCHAR(20) NOT NULL UNIQUE,
    type ENUM('mobile', 'dth') NOT NULL,
    mobile_number VARCHAR(15),
    operator VARCHAR(50),
    circle VARCHAR(50),
    amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'success', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)
```

---

## 🎯 Features

### 1. **Auto Operator Detection**
- User enters mobile number
- On blur, API call fetches operator details
- Operator and circle auto-filled
- Plans automatically fetched and displayed

### 2. **Smart Plan Display**
- Fetches all available plans from API
- Displays up to 6 popular plans as quick buttons
- Includes plans from TOPUP, COMBO, STV, and DATA categories
- User can click plan button or enter custom amount

### 3. **Real-time Notifications**
- Success/error notifications with animation
- Auto-dismiss after 3 seconds
- Color-coded (green for success, red for error)

### 4. **Transaction Logging**
- All API calls logged in `api_transactions` table
- Recharge transactions saved with status
- Unique transaction IDs generated

---

## 🧪 Testing

### Test Flow:
1. Login to the system
2. Go to Recharge page
3. Enter a valid 10-digit mobile number
4. Wait for operator auto-detection
5. Verify operator and circle are auto-filled
6. Check that plan buttons are updated with real plans
7. Select/enter amount
8. Click "Proceed to Recharge"
9. Verify success message with transaction ID

### Test Mobile Numbers:
- Use any valid 10-digit Indian mobile number
- The API will return actual operator and circle information

---

## 🚀 Deployment Notes

1. **Database Setup:**
   - Run `setup-database.sql` (includes `recharge_transactions` table)
   - Run `setup-pan-api-tables.sql` (includes `api_transactions` table)

2. **API Credentials:**
   - Update `php/api-config.php` with production credentials if needed

3. **Testing:**
   - Test with various mobile numbers from different operators
   - Verify operator detection accuracy
   - Check plan fetching for different circles

---

## 📝 User Experience

**Before:** User had to manually select operator and circle from long dropdowns

**After:** 
- Enter mobile number → Everything else is automatic
- See relevant plans → Click to select
- One-click recharge → Done!

**Benefits:**
- ✅ Faster recharge process (fewer steps)
- ✅ No operator selection errors
- ✅ Real plans from actual API
- ✅ Better user experience
- ✅ Automatic circle detection

---

## 🔧 Maintenance

### Adding New Operators:
Update `getOperatorCode()` function in `php/recharge-api.php`:
```php
$operatorMap = [
    'AIRTEL' => 'A',
    'VODAFONE' => 'V',
    'JIO' => 'J',
    'BSNL TOPUP' => 'BT',
    'BSNL SPECIAL' => 'BS',
    // Add new operators here
];
```

### Logging:
All API transactions are logged with:
- User ID
- API type
- Order ID
- Request/response data
- Status

---

## 📞 Support

For API-related issues:
- Check `api_transactions` table for error logs
- Verify API credentials in `php/api-config.php`
- Check browser console for JavaScript errors
- Review network tab for API response details

---

**Last Updated:** November 21, 2025
