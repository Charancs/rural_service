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
    <title>PAN 360 - AVK E Services</title>
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
                <ul class="submenu" style="display: block;">
                    <li><a href="pan-verification.php"><i class="fas fa-check-circle"></i> PAN Verification</a></li>
                    <li><a href="pan-360.php" class="active"><i class="fas fa-info-circle"></i> PAN 360</a></li>
                    <li><a href="pan-creation.php"><i class="fas fa-file-alt"></i> PAN Creation</a></li>
                </ul>
            </li>
            <li><a href="recharge.php"><i class="fas fa-mobile-alt"></i> Recharge</a></li>
            <li><a href="php/logout.php" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main-container">
        <div class="page-header">
            <h2><i class="fas fa-info-circle"></i> PAN 360 - Complete Details</h2>
            <p>Get comprehensive PAN card information including Aadhaar linkage</p>
        </div>

        <div class="service-info-banner">
            <i class="fas fa-info-circle"></i>
            <div>
                <h4>About PAN 360</h4>
                <p>This advanced verification service provides comprehensive details including name, gender, date of birth, masked Aadhaar number, and Aadhaar linkage status.</p>
            </div>
        </div>

        <div class="form-container">
            <form id="pan360Form">
                <div class="form-section">
                    <h3>Enter PAN Number</h3>
                    
                    <div class="form-group">
                        <label for="panNumber">PAN Number *</label>
                        <input type="text" id="panNumber" name="pan" 
                               pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" 
                               placeholder="ABCDE1234F" 
                               maxlength="10"
                               style="text-transform: uppercase"
                               required>
                        <small>Format: 5 letters, 4 digits, 1 letter (e.g., ABCDE1234F)</small>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="window.location.href='dashboard.php'">Cancel</button>
                    <button type="submit" class="btn-primary" id="verifyBtn">
                        <i class="fas fa-search"></i> Get Complete Details
                    </button>
                </div>
            </form>

            <!-- Result Section -->
            <div id="verificationResult" class="result-container" style="display: none;">
                <div class="result-card success">
                    <div class="result-header">
                        <i class="fas fa-check-circle"></i>
                        <h3>PAN 360 - Complete Information</h3>
                    </div>
                    <div class="result-body" id="resultBody">
                        <!-- Results will be populated here -->
                    </div>
                    <div class="result-footer">
                        <button class="btn-primary" onclick="resetForm()">
                            <i class="fas fa-redo"></i> Check Another PAN
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Checks -->
        <div class="recent-section" id="recentSection" style="display: none;">
            <h3><i class="fas fa-history"></i> Recent PAN 360 Checks</h3>
            <div id="recentList" class="recent-list">
                <!-- Recent items will be populated here -->
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2025 GSK E Services - All Rights Reserved</p>
    </footer>

    <script src="js/dashboard.js"></script>
    <script src="js/pan-360.js"></script>
</body>
</html>
