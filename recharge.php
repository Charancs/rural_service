<?php
// Start session
ini_set('session.cookie_lifetime', 86400);
ini_set('session.gc_maxlifetime', 86400);
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recharge - AVK E Services</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <button class="nav-toggle" id="navToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="nav-brand">
                <i class="fas fa-building"></i>
                <h1>AVK E Services</h1>
            </div>
            <div class="nav-balance">
                <i class="fas fa-wallet"></i>
                <span>Balance: ₹0.00</span>
            </div>
        </div>
    </nav>

    <!-- Sidebar Menu -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>Dashboard</h3>
            <button class="sidebar-close" id="sidebarClose">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="wallet.php"><i class="fas fa-wallet"></i> Wallet</a></li>
            <li class="menu-section">
                <div class="menu-section-title" onclick="toggleSubmenu(this)">
                    <i class="fas fa-id-card"></i> PAN Services
                    <i class="fas fa-chevron-down submenu-arrow"></i>
                </div>
                <ul class="submenu" style="display: none;">
                    <li><a href="pan-verification.php"><i class="fas fa-check-circle"></i> PAN Verification</a></li>
                    <li><a href="pan-360.php"><i class="fas fa-info-circle"></i> PAN 360</a></li>
                    <li><a href="pan-creation.php"><i class="fas fa-file-alt"></i> PAN Creation</a></li>
                </ul>
            </li>
            <li><a href="recharge.php" class="active"><i class="fas fa-mobile-alt"></i> Recharge</a></li>
            <li><a href="php/logout.php" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main-container">
        <div class="page-header">
            <h2>Mobile & DTH Recharge</h2>
            <p>Quick and easy recharge service</p>
        </div>

        <div class="recharge-tabs">
            <button class="tab-btn active" onclick="switchTab('mobile')">Mobile Recharge</button>
            <button class="tab-btn" onclick="switchTab('dth')">DTH Recharge</button>
        </div>

        <div class="form-container">
            <!-- Mobile Recharge Form -->
            <form id="mobileRechargeForm" class="recharge-form active">
                <div class="form-section">
                    <h3>Mobile Recharge Details</h3>
                    
                    <div class="form-group">
                        <label for="mobileNumber">Mobile Number *</label>
                        <input type="tel" id="mobileNumber" name="mobileNumber" 
                               pattern="[6-9][0-9]{9}" 
                               placeholder="Enter 10 digit mobile number" 
                               maxlength="10"
                               required>
                        <small class="form-help">Recharge plans will be fetched automatically</small>
                    </div>

                    <!-- Hidden fields for operator and circle -->
                    <input type="hidden" id="operator" name="operator">
                    <input type="hidden" id="operatorCode" name="operatorCode">
                    <input type="hidden" id="circle" name="circle">
                    <input type="hidden" id="circleCode" name="circleCode">
                    <input type="hidden" id="amount" name="amount">

                    <div class="quick-amounts">
                        <!-- Plans will be loaded here automatically -->
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="window.location.href='dashboard.html'">Cancel</button>
                    <button type="submit" class="btn-primary">
                        <span id="mobileRechargeBtnText">Proceed to Recharge</span>
                        <span id="mobileRechargeLoader" class="loader" style="display: none;"></span>
                    </button>
                </div>
            </form>

            <!-- DTH Recharge Form -->
            <form id="dthRechargeForm" class="recharge-form">
                <div class="form-section">
                    <h3>DTH Recharge Details</h3>
                    
                    <div class="form-group">
                        <label for="dthNumber">DTH Subscriber ID *</label>
                        <input type="text" id="dthNumber" name="dthNumber" 
                               placeholder="Enter DTH subscriber ID" 
                               required>
                    </div>

                    <div class="form-group">
                        <label for="dthOperator">Select DTH Operator *</label>
                        <select id="dthOperator" name="dthOperator" required>
                            <option value="">Select DTH Operator</option>
                            <option value="airtel-dth">Airtel Digital TV</option>
                            <option value="tata-sky">Tata Play (Tata Sky)</option>
                            <option value="dish-tv">Dish TV</option>
                            <option value="videocon">Videocon D2H</option>
                            <option value="sun-direct">Sun Direct</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="dthAmount">Recharge Amount *</label>
                        <input type="number" id="dthAmount" name="dthAmount" 
                               min="100" 
                               placeholder="Enter amount" 
                               required>
                    </div>

                    <div class="quick-amounts">
                        <button type="button" class="amount-btn" onclick="setDthAmount(299)">₹299</button>
                        <button type="button" class="amount-btn" onclick="setDthAmount(499)">₹499</button>
                        <button type="button" class="amount-btn" onclick="setDthAmount(699)">₹699</button>
                        <button type="button" class="amount-btn" onclick="setDthAmount(999)">₹999</button>
                        <button type="button" class="amount-btn" onclick="setDthAmount(1499)">₹1499</button>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="window.location.href='dashboard.html'">Cancel</button>
                    <button type="submit" class="btn-primary">
                        <span id="dthRechargeBtnText">Proceed to Recharge</span>
                        <span id="dthRechargeLoader" class="loader" style="display: none;"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="rechargeSuccessModal" class="modal">
        <div class="modal-content">
            <div class="modal-icon success">✓</div>
            <h3>Recharge Successful!</h3>
            <p id="rechargeMessage">Your recharge has been processed successfully.</p>
            <p class="reference-number">Transaction ID: <strong id="transactionId"></strong></p>
            <button class="btn-primary" onclick="closeRechargeModal()">OK</button>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2025 GSK E Services - All Rights Reserved</p>
    </footer>

    <script src="js/dashboard.js"></script>
    <script src="js/recharge.js"></script>
</body>
</html>
