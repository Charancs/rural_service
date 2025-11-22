<?php
// Start session with proper settings
ini_set('session.cookie_lifetime', 86400); // 24 hours
ini_set('session.gc_maxlifetime', 86400);
session_start();

require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get POST data
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

// Validation
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Valid email is required']);
    exit;
}

if (empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Password is required']);
    exit;
}

// Check for dummy admin credentials first
if ($email === 'admin@gmail.com' && $password === 'admin123') {
    // Create admin session
    $_SESSION['user_id'] = 999999;
    $_SESSION['user_name'] = 'Admin User';
    $_SESSION['user_email'] = 'admin@gmail.com';
    $_SESSION['user_mobile'] = '9999999999';
    $_SESSION['user_state'] = 'Admin';
    
    // Force session save
    session_write_close();
    session_start();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Login successful! Redirecting to dashboard...',
        'redirect' => 'dashboard.php'
    ]);
    exit;
}

try {
    $conn = getDBConnection();
    
    // Get user by email
    $stmt = $conn->prepare("SELECT id, first_name, last_name, email, password, status FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
        exit;
    }
    
    $user = $result->fetch_assoc();
    $stmt->close();
    
    // Verify password
    if (!password_verify($password, $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
        exit;
    }
    
    // Check if user is active
    if ($user['status'] !== 'active') {
        echo json_encode(['success' => false, 'message' => 'Your account is ' . $user['status'] . '. Please contact support.']);
        exit;
    }
    
    // Update last login
    $stmt = $conn->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->bind_param("i", $user['id']);
    $stmt->execute();
    $stmt->close();
    
    // Get additional user info
    $stmt = $conn->prepare("SELECT mobile, state FROM users WHERE id = ?");
    $stmt->bind_param("i", $user['id']);
    $stmt->execute();
    $extraInfo = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    // Create session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_mobile'] = $extraInfo['mobile'] ?? '';
    $_SESSION['user_state'] = $extraInfo['state'] ?? '';
    
    // Force session save
    session_write_close();
    session_start();
    
    // Set remember me cookie if checked
    if ($remember) {
        $token = bin2hex(random_bytes(32));
        setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', false, true);
        
        // Store token in database
        $expiresAt = date('Y-m-d H:i:s', time() + (30 * 24 * 60 * 60));
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        
        $stmt = $conn->prepare("INSERT INTO user_sessions (user_id, session_token, ip_address, user_agent, expires_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $user['id'], $token, $ipAddress, $userAgent, $expiresAt);
        $stmt->execute();
        $stmt->close();
    }
    
    $conn->close();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Login successful! Redirecting to dashboard...',
        'redirect' => 'dashboard.php'
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
