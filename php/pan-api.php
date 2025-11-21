<?php
/**
 * PAN API Handler
 * Handles all PAN-related API requests (Verification, 360, Creation)
 */

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

require_once 'config.php';
require_once 'api-config.php';

header('Content-Type: application/json');

// Get request parameters
$action = $_GET['action'] ?? $_POST['action'] ?? '';
$userId = $_SESSION['user_id'];

// Get database connection
$conn = getDBConnection();

// Route to appropriate handler
switch ($action) {
    case 'pan_verification':
        handlePanVerification($conn, $userId);
        break;
    
    case 'pan_360':
        handlePan360($conn, $userId);
        break;
    
    case 'pan_creation':
        handlePanCreation($conn, $userId);
        break;
    
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}

$conn->close();

/**
 * Handle PAN Verification API
 */
function handlePanVerification($conn, $userId) {
    $pan = strtoupper(trim($_POST['pan'] ?? ''));
    
    // Validate PAN format
    if (!isValidPanFormat($pan)) {
        echo json_encode([
            'success' => false,
            'status' => 'Failure',
            'message' => 'Invalid PAN format. Expected format: ABCDE1234F'
        ]);
        return;
    }
    
    // Generate unique order ID
    $orderId = generateOrderId('PAN_VER_');
    
    // Make API request
    $params = [
        'pan' => $pan,
        'orderid' => $orderId
    ];
    
    $response = makeEkychubRequest('pan_verification', $params);
    
    // Log transaction
    logApiTransaction($conn, $userId, 'pan_verification', $params, $response, $orderId);
    
    // Save to pan_verifications table if successful
    if ($response['success']) {
        $stmt = $conn->prepare("
            INSERT INTO pan_verifications 
            (user_id, pan_number, name, type, status, order_id, verified_at) 
            VALUES (?, ?, ?, ?, 'verified', ?, NOW())
        ");
        
        $panNumber = $response['pan'] ?? $pan;
        $name = $response['registered_name'] ?? '';
        $type = $response['type'] ?? '';
        
        $stmt->bind_param("issss", $userId, $panNumber, $name, $type, $orderId);
        $stmt->execute();
        $stmt->close();
    }
    
    echo json_encode($response);
}

/**
 * Handle PAN 360 API
 */
function handlePan360($conn, $userId) {
    $pan = strtoupper(trim($_POST['pan'] ?? ''));
    
    // Validate PAN format
    if (!isValidPanFormat($pan)) {
        echo json_encode([
            'success' => false,
            'status' => 'Failure',
            'message' => 'Invalid PAN format. Expected format: ABCDE1234F'
        ]);
        return;
    }
    
    // Generate unique order ID
    $orderId = generateOrderId('PAN_360_');
    
    // Make API request
    $params = [
        'pan' => $pan,
        'orderid' => $orderId
    ];
    
    $response = makeEkychubRequest('pan_360', $params);
    
    // Log transaction
    logApiTransaction($conn, $userId, 'pan_360', $params, $response, $orderId);
    
    // Save to pan_360_records table if successful
    if ($response['success']) {
        $stmt = $conn->prepare("
            INSERT INTO pan_360_records 
            (user_id, pan_number, name, type, gender, date_of_birth, masked_aadhaar, aadhaar_linked, order_id, verified_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        
        $panNumber = $response['pan'] ?? $pan;
        $name = $response['registered_name'] ?? '';
        $type = $response['type'] ?? '';
        $gender = $response['gender'] ?? '';
        $dob = $response['date_of_birth'] ?? null;
        $maskedAadhaar = $response['masked_aadhaar_number'] ?? '';
        $aadhaarLinked = $response['aadhaar_linked'] ?? false;
        $aadhaarLinkedInt = $aadhaarLinked ? 1 : 0;
        
        $stmt->bind_param("issssssss", $userId, $panNumber, $name, $type, $gender, $dob, $maskedAadhaar, $aadhaarLinkedInt, $orderId);
        $stmt->execute();
        $stmt->close();
    }
    
    echo json_encode($response);
}

/**
 * Handle PAN Creation/Redirection API
 */
function handlePanCreation($conn, $userId) {
    $mobile = trim($_POST['mobile_number'] ?? '');
    
    // Validate mobile format
    if (!isValidMobileFormat($mobile)) {
        echo json_encode([
            'success' => false,
            'status' => 'Failure',
            'message' => 'Invalid mobile number. Must be a 10-digit number starting with 6-9'
        ]);
        return;
    }
    
    // Generate unique order ID
    $orderId = generateOrderId('PAN_CRT_');
    
    // Check if order ID already exists for this user
    $checkStmt = $conn->prepare("SELECT id FROM pan_creation_requests WHERE user_id = ? AND order_id = ?");
    $checkStmt->bind_param("is", $userId, $orderId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows > 0) {
        // Generate a new order ID
        $orderId = generateOrderId('PAN_CRT_' . rand(100, 999) . '_');
    }
    $checkStmt->close();
    
    // Make API request
    $params = [
        'mobile_number' => $mobile,
        'orderid' => $orderId
    ];
    
    $response = makeEkychubRequest('pan_redirection', $params);
    
    // Log transaction
    logApiTransaction($conn, $userId, 'pan_creation', $params, $response, $orderId);
    
    // Save to pan_creation_requests table
    $status = $response['success'] ? 'initiated' : 'failed';
    $redirectUrl = $response['redirect_url'] ?? null;
    
    $stmt = $conn->prepare("
        INSERT INTO pan_creation_requests 
        (user_id, mobile_number, order_id, redirect_url, status, created_at) 
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    
    $stmt->bind_param("issss", $userId, $mobile, $orderId, $redirectUrl, $status);
    $stmt->execute();
    $stmt->close();
    
    echo json_encode($response);
}
?>
