<?php
/**
 * Recharge API Handler
 * Handles mobile recharge with automatic operator detection and plan fetching
 */

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Include required files
require_once 'config.php';
require_once 'api-config.php';

// Set JSON response header
header('Content-Type: application/json');

// Get request method and action
$action = $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'fetch_operator':
            handleFetchOperator();
            break;
        case 'fetch_plans':
            handleFetchPlans();
            break;
        case 'process_recharge':
            handleProcessRecharge();
            break;
        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

/**
 * Fetch operator details for a mobile number
 */
function handleFetchOperator() {
    $mobile = $_POST['mobile'] ?? '';
    
    // Validate mobile number
    if (!isValidMobileFormat($mobile)) {
        throw new Exception('Please enter a valid 10-digit mobile number');
    }
    
    $orderId = generateOrderId('OPR');
    
    // Make API request
    $response = makeEkychubRequest('operator_fetch', [
        'mobile' => $mobile,
        'orderid' => $orderId
    ]);
    
    // Log the transaction
    $conn = getDBConnection();
    logApiTransaction($conn, $_SESSION['user_id'], 'operator_fetch', 
        ['mobile' => $mobile], 
        $response, 
        $orderId
    );
    $conn->close();
    
    if (isset($response['status']) && $response['status'] === 'Success') {
        // Map operator name to code
        $operatorCode = getOperatorCode($response['company'] ?? '');
        
        echo json_encode([
            'success' => true,
            'data' => [
                'operator' => $response['company'] ?? '',
                'operatorCode' => $operatorCode,
                'circle' => $response['circle'] ?? '',
                'circleCode' => $response['circle_code'] ?? '',
                'number' => $response['number'] ?? $mobile
            ],
            'message' => $response['message'] ?? 'Operator fetched successfully'
        ]);
    } else {
        throw new Exception($response['message'] ?? 'Failed to fetch operator details');
    }
}

/**
 * Fetch recharge plans for operator
 */
function handleFetchPlans() {
    $mobile = $_POST['mobile'] ?? '';
    $opcode = $_POST['opcode'] ?? '';
    $circle = $_POST['circle'] ?? '';
    
    // Validate inputs
    if (!isValidMobileFormat($mobile)) {
        throw new Exception('Please enter a valid 10-digit mobile number');
    }
    
    if (empty($opcode)) {
        throw new Exception('Operator code is required');
    }
    
    if (empty($circle)) {
        throw new Exception('Circle code is required');
    }
    
    $orderId = generateOrderId('PLN');
    
    // Make API request
    $response = makeEkychubRequest('operator_plan_fetch', [
        'mobile' => $mobile,
        'opcode' => $opcode,
        'circle' => $circle,
        'orderid' => $orderId
    ]);
    
    // Log the transaction
    $conn = getDBConnection();
    logApiTransaction($conn, $_SESSION['user_id'], 'operator_plan_fetch', 
        ['mobile' => $mobile, 'opcode' => $opcode, 'circle' => $circle], 
        $response, 
        $orderId
    );
    $conn->close();
    
    if (isset($response['status']) && $response['status'] === 'Success') {
        echo json_encode([
            'success' => true,
            'data' => $response['data'] ?? [],
            'operator' => $response['Operator'] ?? '',
            'message' => $response['message'] ?? 'Plans fetched successfully'
        ]);
    } else {
        throw new Exception($response['message'] ?? 'Failed to fetch plans');
    }
}

/**
 * Process recharge transaction
 * Note: Actual recharge API not yet integrated - saving as pending
 */
function handleProcessRecharge() {
    $mobile = $_POST['mobile'] ?? '';
    $operator = $_POST['operator'] ?? '';
    $circle = $_POST['circle'] ?? '';
    $amount = $_POST['amount'] ?? 0;
    
    // Validate inputs
    if (!isValidMobileFormat($mobile)) {
        throw new Exception('Please enter a valid 10-digit mobile number');
    }
    
    if (empty($operator)) {
        throw new Exception('Operator is required');
    }
    
    if ($amount < 10) {
        throw new Exception('Minimum recharge amount is ₹10');
    }
    
    $transactionId = generateOrderId('RCH');
    $userId = $_SESSION['user_id'];
    
    $conn = getDBConnection();
    
    // Insert transaction record as pending (actual API integration pending)
    $stmt = $conn->prepare("
        INSERT INTO recharge_transactions 
        (user_id, transaction_id, type, mobile_number, operator, circle, amount, status, created_at) 
        VALUES (?, ?, 'mobile', ?, ?, ?, ?, 'pending', NOW())
    ");
    
    $stmt->bind_param('issssd', $userId, $transactionId, $mobile, $operator, $circle, $amount);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'transactionId' => $transactionId,
            'message' => 'Recharge request saved. Actual recharge API integration pending.',
            'status' => 'pending'
        ]);
    } else {
        throw new Exception('Failed to save recharge request');
    }
    
    $stmt->close();
    $conn->close();
}

/**
 * Map operator name to code based on API requirements
 * Airtel: A, Vodafone: V, Jio: J, BSNL TOPUP: BT, BSNL Special: BS
 */
function getOperatorCode($operatorName) {
    $operatorName = strtoupper(trim($operatorName));
    $operatorMap = [
        'AIRTEL' => 'A',
        'VODAFONE' => 'V',
        'JIO' => 'J',
        'BSNL TOPUP' => 'BT',
        'BSNL SPECIAL' => 'BS',
        'BSNL' => 'BT',
        'VI' => 'V',
        'IDEA' => 'V'
    ];
    
    foreach ($operatorMap as $key => $code) {
        if (strpos($operatorName, $key) !== false) {
            return $code;
        }
    }
    
    return 'A'; // Default to Airtel
}
?>
