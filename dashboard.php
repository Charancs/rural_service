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
    <title>Dashboard - GSK E Services</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <i class="fas fa-bolt"></i>
                <h1>GSK E Services</h1>
            </div>
            <button class="nav-toggle" id="navToggle">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="nav-menu" id="navMenu">
                <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="wallet.php"><i class="fas fa-wallet"></i> Wallet</a></li>
                <li><a href="pan-apply.php"><i class="fas fa-id-card"></i> PAN Services</a></li>
                <li><a href="recharge.php"><i class="fas fa-mobile-alt"></i> Recharge</a></li>
                <li><a href="php/logout.php" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="main-container">
        <!-- Profile Summary Card -->
        <div class="profile-summary-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="profile-info">
                    <h3><?php echo htmlspecialchars($userName); ?></h3>
                    <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($userEmail); ?></p>
                </div>
            </div>
            <div class="wallet-balance">
                <div class="balance-label">Wallet Balance</div>
                <div class="balance-amount">₹0.00</div>
                <a href="wallet.php" class="btn-add-money">
                    <i class="fas fa-plus-circle"></i> Add Money
                </a>
            </div>
        </div>

        <div class="welcome-section">
            <h2>Welcome Back, <?php echo htmlspecialchars(explode(' ', $userName)[0]); ?>! 🎉</h2>
            <p style="margin-top: 0.5rem; opacity: 0.95;">
                <i class="fas fa-info-circle"></i> 
                Access all your services from this dashboard. We're here to make your digital life easier!
            </p>
        </div>

        <div class="services-grid">
            <div class="service-card" onclick="navigateToService('pan-apply')">
                <div class="service-icon" style="background: var(--gradient-purple);">📝</div>
                <h3>PAN Apply</h3>
                <p>Apply for new PAN card online</p>
                <small style="color: var(--success-color);"><i class="fas fa-check-circle"></i> Fast approval in 7-10 days</small>
            </div>

            <div class="service-card" onclick="navigateToService('pan-verify')">
                <div class="service-icon" style="background: var(--gradient-blue);">✅</div>
                <h3>PAN Verification</h3>
                <p>Verify existing PAN card details</p>
                <small style="color: var(--success-color);"><i class="fas fa-check-circle"></i> Instant verification results</small>
            </div>

            <div class="service-card" onclick="navigateToService('recharge')">
                <div class="service-icon" style="background: var(--gradient-green);">💳</div>
                <h3>Recharge</h3>
                <p>Mobile & DTH recharge services</p>
                <small style="color: var(--success-color);"><i class="fas fa-check-circle"></i> Get cashback on every recharge</small>
            </div>
        </div>

        <div class="dashboard-info">
            <div class="info-card" style="background: linear-gradient(135deg, rgba(168, 85, 247, 0.1), rgba(236, 72, 153, 0.1)); border-left: 4px solid var(--purple);">
                <i class="fas fa-shield-alt" style="color: var(--purple);"></i>
                <div>
                    <h4>100% Secure Transactions</h4>
                    <p>All your transactions are encrypted and protected with bank-level security protocols.</p>
                </div>
            </div>
            
            <div class="info-card" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.1)); border-left: 4px solid var(--blue);">
                <i class="fas fa-headset" style="color: var(--blue);"></i>
                <div>
                    <h4>24/7 Customer Support</h4>
                    <p>Need help? Our support team is available round the clock to assist you with any queries.</p>
                </div>
            </div>
            
            <div class="info-card" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(34, 197, 94, 0.1)); border-left: 4px solid var(--green);">
                <i class="fas fa-bolt" style="color: var(--green);"></i>
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
        .profile-summary-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            box-shadow: var(--shadow-lg);
        }
        
        .profile-header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .profile-avatar {
            font-size: 4rem;
            opacity: 0.9;
        }
        
        .profile-info h3 {
            margin: 0 0 0.5rem 0;
            font-size: 1.5rem;
        }
        
        .profile-info p {
            margin: 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }
        
        .wallet-balance {
            text-align: center;
        }
        
        .balance-label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }
        
        .balance-amount {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }
        
        .btn-add-money {
            display: inline-block;
            background: white;
            color: var(--primary-color);
            padding: 0.75rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }
        
        .btn-add-money:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
        }
        
        .dashboard-info {
            margin-top: 3rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .info-card {
            padding: 2rem;
            border-radius: var(--border-radius);
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
            box-shadow: var(--shadow-sm);
        }
        
        .info-card i {
            font-size: 2.5rem;
        }
        
        .info-card h4 {
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }
        
        .info-card p {
            color: var(--text-secondary);
            margin: 0;
            line-height: 1.6;
        }
        
        /* Mobile responsive */
        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }
            
            .profile-avatar {
                font-size: 3rem;
            }
            
            .balance-amount {
                font-size: 2rem;
            }
        }
    </style>
</body>
</html>
