<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - AVK E Services and Technologies</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 50%, #e0f2fe 100%);
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .register-container {
            width: 100%;
            max-width: 550px;
        }

        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .register-logo i {
            font-size: 2.5rem;
            color: #0d9488;
        }

        .register-logo h1 {
            font-size: 1.75rem;
            color: #0f766e;
            font-weight: 800;
        }

        .register-header p {
            color: #64748b;
            font-size: 1rem;
        }

        .register-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 2.5rem 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .register-card h2 {
            font-size: 1.5rem;
            color: #0f766e;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .register-card > p {
            color: #64748b;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: none;
        }

        .alert.success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        .alert.error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #475569;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .form-group label i {
            color: #0d9488;
            margin-right: 0.25rem;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #0d9488;
            box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.1);
        }

        .form-group small {
            display: block;
            margin-top: 0.5rem;
            color: #64748b;
            font-size: 0.85rem;
        }

        .password-input {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            font-size: 1rem;
            padding: 0.5rem;
        }

        .toggle-password:hover {
            color: #0d9488;
        }

        .password-strength {
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            margin-top: 0.5rem;
            overflow: hidden;
        }

        .strength-bar {
            height: 100%;
            width: 0;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .checkbox-group {
            margin-bottom: 1.5rem;
        }

        .checkbox-label {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            cursor: pointer;
            font-size: 0.9rem;
            color: #475569;
        }

        .checkbox-label input {
            width: 18px;
            height: 18px;
            cursor: pointer;
            margin-top: 0.1rem;
            flex-shrink: 0;
        }

        .checkbox-label a {
            color: #0d9488;
            text-decoration: none;
        }

        .checkbox-label a:hover {
            text-decoration: underline;
        }

        .btn-auth-primary {
            width: 100%;
            padding: 1rem;
            background: #0d9488;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-auth-primary:hover {
            background: #0f766e;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(13, 148, 136, 0.4);
        }

        .auth-divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }

        .auth-divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #e5e7eb;
        }

        .auth-divider span {
            position: relative;
            background: white;
            padding: 0 1rem;
            color: #94a3b8;
            font-size: 0.85rem;
        }

        .auth-footer-link {
            text-align: center;
        }

        .auth-footer-link p {
            color: #64748b;
            font-size: 0.95rem;
        }

        .auth-footer-link a {
            color: #0d9488;
            text-decoration: none;
            font-weight: 600;
        }

        .auth-footer-link a:hover {
            text-decoration: underline;
        }

        .back-home {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-home a {
            color: #64748b;
            text-decoration: none;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .back-home a:hover {
            color: #0d9488;
        }

        @media (max-width: 640px) {
            .register-card {
                padding: 2rem 1.5rem;
            }

            .register-logo h1 {
                font-size: 1.5rem;
            }

            .form-row-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <div class="register-logo">
                <i class="fas fa-building"></i>
                <h1>AVK E Services</h1>
            </div>
            <p>Join our digital government services platform</p>
        </div>

        <div class="register-card">
            <h2>Create Your Account</h2>
            <p>Start your journey with us today!</p>
            
            <div id="alertMessage" class="alert"></div>
            
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
                </div>
                
                <div class="form-group">
                    <label for="mobile">
                        <i class="fas fa-phone"></i> Mobile Number *
                    </label>
                    <input type="tel" id="mobile" name="mobile" pattern="[0-9]{10}" placeholder="10 digit mobile number" maxlength="10" required>
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
                    <small>Must be at least 8 characters</small>
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
        </div>

        <div class="back-home">
            <a href="landing.html">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
        </div>
    </div>
    
    <script src="js/register.js"></script>
</body>
</html>
