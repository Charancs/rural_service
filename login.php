<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AVK E Services and Technologies</title>
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

        .login-container {
            width: 100%;
            max-width: 450px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .login-logo i {
            font-size: 2.5rem;
            color: #0d9488;
        }

        .login-logo h1 {
            font-size: 1.75rem;
            color: #0f766e;
            font-weight: 800;
        }

        .login-header p {
            color: #64748b;
            font-size: 1rem;
        }

        .login-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 2.5rem 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .login-card h2 {
            font-size: 1.5rem;
            color: #0f766e;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .login-card > p {
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

        .form-group input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-group input:focus {
            outline: none;
            border-color: #0d9488;
            box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.1);
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

        .form-row-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            font-size: 0.9rem;
            color: #475569;
        }

        .checkbox-label input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .forgot-link {
            color: #0d9488;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .forgot-link:hover {
            color: #0f766e;
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
            .login-card {
                padding: 2rem 1.5rem;
            }

            .login-logo h1 {
                font-size: 1.5rem;
            }

            .form-row-between {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="login-logo">
                <i class="fas fa-building"></i>
                <h1>AVK E Services</h1>
            </div>
            <p>Welcome back! Access your e-services dashboard</p>
        </div>

        <div class="login-card">
            <h2>Login to Your Account</h2>
            <p>Enter your credentials to continue</p>
            
            <div id="alertMessage" class="alert"></div>
            
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
        </div>

        <div class="back-home">
            <a href="landing.html">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
        </div>
    </div>
    
    <script src="js/login.js"></script>
</body>
</html>
