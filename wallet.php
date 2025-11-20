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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wallet - GSK E Services</title>
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
                <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="wallet.php" class="active"><i class="fas fa-wallet"></i> Wallet</a></li>
                <li><a href="pan-apply.html"><i class="fas fa-id-card"></i> PAN Services</a></li>
                <li><a href="recharge.html"><i class="fas fa-mobile-alt"></i> Recharge</a></li>
                <li><a href="php/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="main-container">
        <div class="page-header">
            <h2><i class="fas fa-wallet"></i> My Wallet</h2>
            <p>Manage your wallet balance and transactions</p>
        </div>

        <div class="wallet-grid">
            <!-- Wallet Balance Card -->
            <div class="wallet-balance-card">
                <div class="balance-header">
                    <i class="fas fa-wallet"></i>
                    <h3>Wallet Balance</h3>
                </div>
                <div class="balance-display">
                    <span class="currency">₹</span>
                    <span class="amount">0.00</span>
                </div>
                <button class="btn-add-money-large" onclick="showAddMoneyModal()">
                    <i class="fas fa-plus-circle"></i> Add Money
                </button>
                <p class="balance-note">
                    <i class="fas fa-info-circle"></i> Use wallet balance for faster transactions
                </p>
            </div>

            <!-- Add Money Form -->
            <div class="add-money-card">
                <h3><i class="fas fa-plus-circle"></i> Add Money to Wallet</h3>
                <form id="addMoneyForm">
                    <div class="form-group">
                        <label>Enter Amount</label>
                        <input type="number" id="amount" placeholder="Enter amount" min="10" max="50000" required>
                        <small>Minimum: ₹10 | Maximum: ₹50,000</small>
                    </div>

                    <div class="quick-amounts">
                        <button type="button" class="quick-amt" onclick="setAmount(100)">₹100</button>
                        <button type="button" class="quick-amt" onclick="setAmount(500)">₹500</button>
                        <button type="button" class="quick-amt" onclick="setAmount(1000)">₹1000</button>
                        <button type="button" class="quick-amt" onclick="setAmount(2000)">₹2000</button>
                    </div>

                    <div class="form-group">
                        <label>Payment Method</label>
                        <select id="paymentMethod" required>
                            <option value="">Select Payment Method</option>
                            <option value="upi">UPI</option>
                            <option value="netbanking">Net Banking</option>
                            <option value="card">Credit/Debit Card</option>
                            <option value="wallet">Other Wallets</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-primary">
                        <i class="fas fa-lock"></i> Proceed to Payment
                    </button>
                </form>

                <div class="payment-features">
                    <div class="feature-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>100% Secure Payment</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-clock"></i>
                        <span>Instant Credit</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-tag"></i>
                        <span>No Extra Charges</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction History -->
        <div class="transactions-section">
            <h3><i class="fas fa-history"></i> Recent Transactions</h3>
            <div class="transactions-list">
                <div class="no-transactions">
                    <i class="fas fa-inbox"></i>
                    <p>No transactions yet</p>
                    <small>Your transaction history will appear here</small>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2025 GSK E Services - All Rights Reserved</p>
    </footer>

    <script src="js/dashboard.js"></script>
    <script>
        function setAmount(amount) {
            document.getElementById('amount').value = amount;
        }

        function showAddMoneyModal() {
            document.getElementById('amount').focus();
        }

        document.getElementById('addMoneyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const amount = document.getElementById('amount').value;
            const paymentMethod = document.getElementById('paymentMethod').value;
            
            if (!paymentMethod) {
                alert('Please select a payment method');
                return;
            }
            
            if (amount < 10 || amount > 50000) {
                alert('Amount must be between ₹10 and ₹50,000');
                return;
            }
            
            alert('Payment gateway integration pending. Amount: ₹' + amount + ' via ' + paymentMethod);
        });
    </script>
    
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

        .wallet-grid {
            display: grid;
            grid-template-columns: 400px 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .wallet-balance-card {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 2.5rem;
            border-radius: var(--border-radius);
            color: white;
            box-shadow: var(--shadow-xl);
        }

        .balance-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
        }

        .balance-header i {
            font-size: 1.5rem;
        }

        .balance-header h3 {
            margin: 0;
            font-size: 1.2rem;
            opacity: 0.95;
        }

        .balance-display {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 2rem;
            text-align: center;
        }

        .currency {
            opacity: 0.9;
        }

        .btn-add-money-large {
            width: 100%;
            background: white;
            color: var(--green);
            border: none;
            padding: 1rem;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-add-money-large:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
        }

        .balance-note {
            margin-top: 1rem;
            opacity: 0.9;
            text-align: center;
            font-size: 0.9rem;
        }

        .add-money-card {
            background: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
        }

        .add-money-card h3 {
            margin-top: 0;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }

        .quick-amounts {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .quick-amt {
            background: var(--bg-color);
            border: 2px solid var(--border-color);
            padding: 0.75rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .quick-amt:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .payment-features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .feature-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            text-align: center;
        }

        .feature-item i {
            font-size: 1.5rem;
            color: var(--green);
        }

        .feature-item span {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .transactions-section {
            background: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
        }

        .transactions-section h3 {
            margin-top: 0;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }

        .no-transactions {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
        }

        .no-transactions i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        .no-transactions p {
            margin: 0.5rem 0;
            font-weight: 600;
        }

        @media (max-width: 992px) {
            .wallet-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .balance-display {
                font-size: 2.5rem;
            }

            .quick-amounts {
                grid-template-columns: repeat(2, 1fr);
            }

            .payment-features {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
