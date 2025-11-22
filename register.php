<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - AVK E Services and Technologies</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-left">
            <div class="auth-branding">
                <i class="fas fa-building"></i>
                <h1>AVK E Services</h1>
                <p>Join our digital government services platform</p>
            </div>
            <div class="auth-features">
                <div class="auth-feature">
                    <i class="fas fa-rocket"></i>
                    <div>
                        <h3>Quick & Easy</h3>
                        <p>Register in just 2 minutes</p>
                    </div>
                </div>
                <div class="auth-feature">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <h3>100% Secure</h3>
                        <p>Your data is safe with us</p>
                    </div>
                </div>
                <div class="auth-feature">
                    <i class="fas fa-gift"></i>
                    <div>
                        <h3>Welcome Bonus</h3>
                        <p>Get ₹50 cashback on first transaction</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="auth-right">
            <div class="auth-card">
                <div class="auth-header">
                    <h2>Create Your Account</h2>
                    <p>Start your journey with us today!</p>
                </div>
                
                <div id="alertMessage" class="alert" style="display: none;"></div>
                
                <form id="registerForm" method="POST" action="php/register.php">
                    <div class="form-row-2">
                        <div class="form-group">
                            <label for="firstName">
                                <i class="fas fa-user"></i> First Name *
                            </label>
                            <input type="text" id="firstName" name="firstName" placeholder="Enter first name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="lastName">
                                <i class="fas fa-user"></i> Last Name *
                            </label>
                            <input type="text" id="lastName" name="lastName" placeholder="Enter last name" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Email Address *
                        </label>
                        <input type="email" id="email" name="email" placeholder="your.email@example.com" required>
                        <small>We'll send important updates to this email</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="mobile">
                            <i class="fas fa-phone"></i> Mobile Number *
                        </label>
                        <input type="tel" id="mobile" name="mobile" pattern="[0-9]{10}" placeholder="10 digit mobile number" maxlength="10" required>
                        <small>Your mobile number will be verified via OTP</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-lock"></i> Password *
                        </label>
                        <div class="password-input">
                            <input type="password" id="password" name="password" placeholder="Create a strong password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar" id="strengthBar"></div>
                        </div>
                        <small>Must be at least 8 characters with uppercase, lowercase & number</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmPassword">
                            <i class="fas fa-lock"></i> Confirm Password *
                        </label>
                        <div class="password-input">
                            <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Re-enter your password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('confirmPassword')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="state">
                            <i class="fas fa-map-marker-alt"></i> State *
                        </label>
                        <select id="state" name="state" required>
                            <option value="">Select your state</option>
                            <option value="Andhra Pradesh">Andhra Pradesh</option>
                            <option value="Bihar">Bihar</option>
                            <option value="Delhi">Delhi</option>
                            <option value="Gujarat">Gujarat</option>
                            <option value="Karnataka">Karnataka</option>
                            <option value="Kerala">Kerala</option>
                            <option value="Maharashtra">Maharashtra</option>
                            <option value="Tamil Nadu">Tamil Nadu</option>
                            <option value="Telangana">Telangana</option>
                            <option value="Uttar Pradesh">Uttar Pradesh</option>
                            <option value="West Bengal">West Bengal</option>
                        </select>
                    </div>
                    
                    <div class="form-group checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="terms" name="terms" required>
                            <span>I agree to the <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a></span>
                        </label>
                    </div>
                    
                    <button type="submit" class="btn-auth-primary">
                        <i class="fas fa-user-plus"></i> Create Account
                    </button>
                    
                    <div class="auth-divider">
                        <span>OR</span>
                    </div>
                    
                    <div class="auth-footer-link">
                        <p>Already have an account? <a href="login.php">Login here</a></p>
                    </div>
                </form>
                
                <div class="auth-benefits">
                    <h4><i class="fas fa-star"></i> What you'll get:</h4>
                    <ul>
                        <li><i class="fas fa-check-circle"></i> Instant PAN card services</li>
                        <li><i class="fas fa-check-circle"></i> Quick mobile & DTH recharge</li>
                        <li><i class="fas fa-check-circle"></i> Secure transaction history</li>
                        <li><i class="fas fa-check-circle"></i> 24/7 customer support</li>
                        <li><i class="fas fa-check-circle"></i> Exclusive cashback offers</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <script src="js/register.js"></script>
</body>
</html>
