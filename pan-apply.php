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
    <title>PAN Apply - GSK E Services</title>
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
                <i class="fas fa-bolt"></i>
                <h1>GSK E Services</h1>
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
            <li><a href="pan-apply.php" class="active"><i class="fas fa-id-card"></i> PAN Services</a></li>
            <li><a href="recharge.php"><i class="fas fa-mobile-alt"></i> Recharge</a></li>
            <li><a href="php/logout.php" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main-container">
        <div class="page-header">
            <h2>PAN Card Application</h2>
            <p>Fill in the details to apply for a new PAN card</p>
        </div>

        <div class="form-container">
            <form id="panApplyForm">
                <div class="form-section">
                    <h3>Personal Information</h3>
                    
                    <div class="form-group">
                        <label for="applicantName">Full Name *</label>
                        <input type="text" id="applicantName" name="applicantName" placeholder="As per Aadhaar" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">First Name *</label>
                            <input type="text" id="firstName" name="firstName" required>
                        </div>
                        <div class="form-group">
                            <label for="middleName">Middle Name</label>
                            <input type="text" id="middleName" name="middleName">
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name *</label>
                            <input type="text" id="lastName" name="lastName" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="dob">Date of Birth *</label>
                            <input type="date" id="dob" name="dob" required>
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender *</label>
                            <select id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fatherName">Father's Name *</label>
                        <input type="text" id="fatherName" name="fatherName" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" placeholder="example@email.com" required>
                    </div>

                    <div class="form-group">
                        <label for="mobile">Mobile Number *</label>
                        <input type="tel" id="mobile" name="mobile" pattern="[0-9]{10}" placeholder="10 digit mobile number" required>
                    </div>
                </div>

                <div class="form-section">
                    <h3>Address Details</h3>
                    
                    <div class="form-group">
                        <label for="address">Address Line 1 *</label>
                        <input type="text" id="address" name="address" required>
                    </div>

                    <div class="form-group">
                        <label for="address2">Address Line 2</label>
                        <input type="text" id="address2" name="address2">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">City *</label>
                            <input type="text" id="city" name="city" required>
                        </div>
                        <div class="form-group">
                            <label for="state">State *</label>
                            <select id="state" name="state" required>
                                <option value="">Select State</option>
                                <option value="AN">Andaman and Nicobar Islands</option>
                                <option value="AP">Andhra Pradesh</option>
                                <option value="AR">Arunachal Pradesh</option>
                                <option value="AS">Assam</option>
                                <option value="BR">Bihar</option>
                                <option value="CH">Chandigarh</option>
                                <option value="CT">Chhattisgarh</option>
                                <option value="DN">Dadra and Nagar Haveli</option>
                                <option value="DD">Daman and Diu</option>
                                <option value="DL">Delhi</option>
                                <option value="GA">Goa</option>
                                <option value="GJ">Gujarat</option>
                                <option value="HR">Haryana</option>
                                <option value="HP">Himachal Pradesh</option>
                                <option value="JK">Jammu and Kashmir</option>
                                <option value="JH">Jharkhand</option>
                                <option value="KA">Karnataka</option>
                                <option value="KL">Kerala</option>
                                <option value="LA">Ladakh</option>
                                <option value="LD">Lakshadweep</option>
                                <option value="MP">Madhya Pradesh</option>
                                <option value="MH">Maharashtra</option>
                                <option value="MN">Manipur</option>
                                <option value="ML">Meghalaya</option>
                                <option value="MZ">Mizoram</option>
                                <option value="NL">Nagaland</option>
                                <option value="OR">Odisha</option>
                                <option value="PY">Puducherry</option>
                                <option value="PB">Punjab</option>
                                <option value="RJ">Rajasthan</option>
                                <option value="SK">Sikkim</option>
                                <option value="TN">Tamil Nadu</option>
                                <option value="TG">Telangana</option>
                                <option value="TR">Tripura</option>
                                <option value="UP">Uttar Pradesh</option>
                                <option value="UT">Uttarakhand</option>
                                <option value="WB">West Bengal</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pincode">Pincode *</label>
                        <input type="text" id="pincode" name="pincode" pattern="[0-9]{6}" placeholder="6 digit pincode" required>
                    </div>
                </div>

                <div class="form-section">
                    <h3>Document Upload</h3>
                    
                    <div class="form-group">
                        <label for="aadhaar">Aadhaar Number *</label>
                        <input type="text" id="aadhaar" name="aadhaar" pattern="[0-9]{12}" placeholder="12 digit Aadhaar number" required>
                    </div>

                    <div class="form-group">
                        <label for="photo">Upload Photo *</label>
                        <input type="file" id="photo" name="photo" accept="image/*" required>
                        <small>JPG, PNG (Max 2MB)</small>
                    </div>

                    <div class="form-group">
                        <label for="signature">Upload Signature *</label>
                        <input type="file" id="signature" name="signature" accept="image/*" required>
                        <small>JPG, PNG (Max 1MB)</small>
                    </div>

                    <div class="form-group">
                        <label for="aadhaarDoc">Upload Aadhaar Document *</label>
                        <input type="file" id="aadhaarDoc" name="aadhaarDoc" accept=".pdf,image/*" required>
                        <small>PDF or Image (Max 5MB)</small>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="window.location.href='dashboard.html'">Cancel</button>
                    <button type="submit" class="btn-primary">Submit Application</button>
                </div>
            </form>
        </div>
    </div>

    <div id="successModal" class="modal">
        <div class="modal-content">
            <div class="modal-icon success">✓</div>
            <h3>Application Submitted Successfully!</h3>
            <p>Your PAN application has been submitted.</p>
            <p class="reference-number">Reference Number: <strong id="refNumber"></strong></p>
            <button class="btn-primary" onclick="closeModal()">OK</button>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2025 GSK E Services - All Rights Reserved</p>
    </footer>

    <script src="js/pan-apply.js"></script>
</body>
</html>
