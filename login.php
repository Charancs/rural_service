<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GSK E Services</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-left">
            <div class="auth-branding">
                <i class="fas fa-bolt"></i>
                <h1>GSK E Services</h1>
                <p>Welcome back! We missed you</p>
            </div>
            <div class="auth-features">
                <div class="auth-feature">
                    <i class="fas fa-bolt"></i>
                    <div>
                        <h3>Lightning Fast</h3>
                        <p>Access all services instantly</p>
                    </div>
                </div>
                <div class="auth-feature">
                    <i class="fas fa-lock"></i>
                    <div>
                        <h3>Bank-Level Security</h3>
                        <p>Your account is protected</p>
                    </div>
                </div>
                <div class="auth-feature">
                    <i class="fas fa-headset"></i>
                    <div>
                        <h3>24/7 Support</h3>
                        <p>We're here to help anytime</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="auth-right">
            <div class="auth-card">
                <div class="auth-header">
                    <h2>Welcome Back!</h2>
                    <p>Login to access your dashboard</p>
                </div>
                
                <div id="alertMessage" class="alert" style="display: none;"></div>
                
                <form id="loginForm">
                    <div class="form-group">
                        <label for="loginEmail">
                            <i class="fas fa-envelope"></i> Email Address
                        </label>
                        <input type="email" id="loginEmail" name="email" placeholder="Enter your email" required autofocus>
                    </div>
                    
                    <div class="form-group">
                        <label for="loginPassword">
                            <i class="fas fa-lock"></i> Password
                        </label>
                        <div class="password-input">
                            <input type="password" id="loginPassword" name="password" placeholder="Enter your password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('loginPassword')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-row-between">
                        <label class="checkbox-label">
                            <input type="checkbox" id="remember" name="remember">
                            <span>Remember me</span>
                        </label>
                        <a href="#" class="forgot-link">Forgot Password?</a>
                    </div>
                    
                    <button type="submit" class="btn-auth-primary">
                        <i class="fas fa-sign-in-alt"></i> Login to Dashboard
                    </button>
                    
                    <div class="auth-divider">
                        <span>OR</span>
                    </div>
                    
                    <div class="auth-footer-link">
                        <p>Don't have an account? <a href="register.php">Register now</a></p>
                    </div>
                </form>
                
                <div class="auth-tips">
                    <h4><i class="fas fa-lightbulb"></i> Quick Tips:</h4>
                    <ul>
                        <li><i class="fas fa-info-circle"></i> Use a strong password to keep your account secure</li>
                        <li><i class="fas fa-info-circle"></i> Never share your password with anyone</li>
                        <li><i class="fas fa-info-circle"></i> Enable "Remember me" only on your personal device</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <script src="js/login.js"></script>
</body>
</html>
