<?php
// Start session with proper settings
ini_set('session.cookie_lifetime', 86400); // 24 hours
ini_set('session.gc_maxlifetime', 86400);
session_start();

// Debug: Check if session exists (remove in production)
error_log("Session check - User ID: " . ($_SESSION['user_id'] ?? 'NOT SET'));

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userName = $_SESSION['user_name'] ?? 'User';
$userEmail = $_SESSION['user_email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - AVK E Services</title>
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
                <img src="public/logo.png" alt="AVK E Services Logo" style="height: 40px; width: auto; margin-right: 0.5rem;">
                <h1>AVK E Services</h1>
            </div>
            <div class="nav-profile">
                <div class="nav-profile-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="nav-profile-info">
                    <div class="nav-profile-name"><?php echo htmlspecialchars($userName); ?></div>
                    <div class="nav-profile-email"><?php echo htmlspecialchars($userEmail); ?></div>
                </div>
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
            <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> Home</a></li>
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
            <li><a href="recharge.php"><i class="fas fa-mobile-alt"></i> Recharge</a></li>
            <li><a href="php/logout.php" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main-container">
        <!-- Compact Wallet Card -->
        <div class="compact-wallet-card">
            <div class="wallet-info">
                <div class="wallet-label">Wallet Balance</div>
                <div class="wallet-amount">₹0.00</div>
            </div>
            <a href="wallet.php" class="btn-add-money-compact">
                <i class="fas fa-plus"></i> Add Money
            </a>
        </div>

        <div class="welcome-section">
            <h2>Welcome Back, <?php echo htmlspecialchars(explode(' ', $userName)[0]); ?>! 🎉</h2>
            <p style="margin-top: 0.5rem; opacity: 0.95;">
                <i class="fas fa-info-circle"></i> 
                Access all your services from this dashboard. We're here to make your digital life easier!
            </p>
        </div>

        <div class="services-grid">
            <div class="service-card" onclick="navigateToService('pan-verification')">
                <div class="service-icon" style="background: var(--gradient-blue);">✅</div>
                <h3>PAN Verification</h3>
                <p>Verify existing PAN card instantly</p>
                <small style="color: var(--success-color);"><i class="fas fa-check-circle"></i> Instant verification results</small>
            </div>

            <div class="service-card" onclick="navigateToService('pan-360')">
                <div class="service-icon" style="background: var(--gradient-purple);">🔍</div>
                <h3>PAN 360</h3>
                <p>Complete PAN details with Aadhaar status</p>
                <small style="color: var(--success-color);"><i class="fas fa-check-circle"></i> Comprehensive information</small>
            </div>

            <div class="service-card" onclick="navigateToService('pan-creation')">
                <div class="service-icon" style="background: var(--gradient-orange);">📄</div>
                <h3>PAN Creation</h3>
                <p>Apply for new PAN or corrections</p>
                <small style="color: var(--success-color);"><i class="fas fa-check-circle"></i> Physical & e-PAN options</small>
            </div>

            <div class="service-card" onclick="navigateToService('recharge')">
                <div class="service-icon" style="background: var(--gradient-green);">💳</div>
                <h3>Recharge</h3>
                <p>Mobile & DTH recharge services</p>
                <small style="color: var(--success-color);"><i class="fas fa-check-circle"></i> Get cashback on every recharge</small>
            </div>
        </div>

        <div class="dashboard-info">
            <div class="info-card">
                <i class="fas fa-shield-alt" style="color: #0d9488;"></i>
                <div>
                    <h4>100% Secure Transactions</h4>
                    <p>All your transactions are encrypted and protected with bank-level security protocols.</p>
                </div>
            </div>
            
            <div class="info-card">
                <i class="fas fa-headset" style="color: #3b82f6;"></i>
                <div>
                    <h4>24/7 Customer Support</h4>
                    <p>Need help? Our support team is available round the clock to assist you with any queries.</p>
                </div>
            </div>
            
            <div class="info-card">
                <i class="fas fa-bolt" style="color: #f59e0b;"></i>
                <div>
                    <h4>Lightning Fast Service</h4>
                    <p>Experience instant service delivery with our optimized processing system.</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2025 GSK E Services - All Rights Reserved | <i class="fas fa-heart" style="color: red;"></i> Made with care for you</p>
    </footer>

    <script src="js/dashboard.js"></script>
    
    <style>
        /* Navbar Profile Styles */
        .nav-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: rgba(13, 148, 136, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            margin-left: auto;
        }
        
        .nav-profile-avatar {
            font-size: 2rem;
            color: #0d9488;
        }
        
        .nav-profile-info {
            display: flex;
            flex-direction: column;
            gap: 0.1rem;
        }
        
        .nav-profile-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: #0f766e;
            line-height: 1.2;
        }
        
        .nav-profile-email {
            font-size: 0.75rem;
            color: #475569;
            line-height: 1.2;
        }
        
        /* Compact Wallet Card */
        .compact-wallet-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 450px;
        }
        
        .wallet-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .wallet-label {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 500;
        }
        
        .wallet-amount {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0d9488;
        }
        
        .btn-add-money-compact {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: #0d9488;
            color: white;
            padding: 0.65rem 1.25rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(13, 148, 136, 0.3);
            white-space: nowrap;
        }
        
        .btn-add-money-compact:hover {
            transform: translateY(-2px);
            background: #0f766e;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.4);
        }
        
        .dashboard-info {
            margin-top: 3rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        
        .info-card {
            background: white;
            border: 1px solid #e5e7eb;
            padding: 2rem;
            border-radius: 16px;
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }
        
        .info-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }
        
        .info-card i {
            font-size: 2.5rem;
        }
        
        .info-card h4 {
            margin-bottom: 0.5rem;
            color: #0f766e;
            font-weight: 700;
        }
        
        .info-card p {
            color: #475569;
            margin: 0;
            line-height: 1.6;
        }
        
        /* Mobile responsive */
        @media (max-width: 768px) {
            .nav-profile-info {
                display: none;
            }
            
            .nav-profile {
                padding: 0.5rem;
            }
            
            .compact-wallet-card {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
                max-width: 100%;
            }
            
            .wallet-amount {
                font-size: 1.5rem;
            }
        }
    </style>
</body>
</html>
