<?php
// Start session with proper settings
ini_set('session.cookie_lifetime', 86400);
ini_set('session.gc_maxlifetime', 86400);
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userName = $_SESSION['user_name'] ?? 'User';
$userEmail = $_SESSION['user_email'] ?? '';
$userMobile = $_SESSION['user_mobile'] ?? '';
$userState = $_SESSION['user_state'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - GSK E Services</title>
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
                <li><a href="dashboard.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="profile.php" class="active"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="wallet.php"><i class="fas fa-wallet"></i> Wallet</a></li>
                <li><a href="pan-apply.html"><i class="fas fa-id-card"></i> PAN Services</a></li>
                <li><a href="recharge.html"><i class="fas fa-mobile-alt"></i> Recharge</a></li>
                <li><a href="php/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="main-container">
        <div class="page-header">
            <h2><i class="fas fa-user-circle"></i> My Profile</h2>
            <p>Manage your personal information and account settings</p>
        </div>

        <div class="profile-container">
            <div class="profile-card">
                <div class="profile-avatar-large">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h3><?php echo htmlspecialchars($userName); ?></h3>
                <p class="profile-email"><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($userEmail); ?></p>
                <button class="btn-edit-profile" onclick="enableEdit()">
                    <i class="fas fa-edit"></i> Edit Profile
                </button>
            </div>

            <div class="profile-details">
                <h3>Profile Information</h3>
                <form id="profileForm">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Full Name</label>
                        <input type="text" value="<?php echo htmlspecialchars($userName); ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email Address</label>
                        <input type="email" value="<?php echo htmlspecialchars($userEmail); ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-phone"></i> Mobile Number</label>
                        <input type="tel" value="<?php echo htmlspecialchars($userMobile); ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-map-marker-alt"></i> State</label>
                        <input type="text" value="<?php echo htmlspecialchars($userState); ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-calendar"></i> Member Since</label>
                        <input type="text" value="<?php echo date('F Y'); ?>" disabled>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        To update your profile information, please contact our support team.
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2025 GSK E Services - All Rights Reserved</p>
    </footer>

    <script src="js/dashboard.js"></script>
    
    <style>
        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h2 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .page-header p {
            color: var(--text-secondary);
            margin: 0;
        }

        .profile-container {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 2rem;
        }

        .profile-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 3rem 2rem;
            border-radius: var(--border-radius);
            text-align: center;
            color: white;
            box-shadow: var(--shadow-lg);
        }

        .profile-avatar-large {
            font-size: 8rem;
            margin-bottom: 1rem;
            opacity: 0.9;
        }

        .profile-card h3 {
            margin: 0 0 0.5rem 0;
            font-size: 1.5rem;
        }

        .profile-email {
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .btn-edit-profile {
            background: white;
            color: var(--primary-color);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-edit-profile:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
        }

        .profile-details {
            background: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
        }

        .profile-details h3 {
            margin-top: 0;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .form-group input {
            background: var(--bg-color);
        }

        .alert-info {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.1));
            border-left: 4px solid var(--blue);
            padding: 1rem;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .alert-info i {
            color: var(--blue);
            font-size: 1.5rem;
        }

        @media (max-width: 992px) {
            .profile-container {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .profile-avatar-large {
                font-size: 5rem;
            }
        }
    </style>
</body>
</html>
