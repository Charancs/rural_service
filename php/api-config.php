<?php
/**
 * eKYCHub API Configuration
 * 
 * This file contains API credentials and helper functions for eKYCHub services
 */

// eKYCHub API Credentials
define('EKYCHUB_USERNAME', '9019977330');
define('EKYCHUB_TOKEN', '4cdc6d5954d983ae205b4f0d5ac816b0');
define('EKYCHUB_BASE_URL', 'https://connect.ekychub.in/v3/verification/');

/**
 * Generate a unique order ID for API requests
 * @param string $prefix Prefix for the order ID
 * @return string Unique order ID
 */
function generateOrderId($prefix = 'ORD') {
    return $prefix . time() . rand(1000, 9999);
}

/**
 * Make API request to eKYCHub
 * @param string $endpoint API endpoint (e.g., 'pan_verification')
 * @param array $params Query parameters
 * @return array Response from API
 */
function makeEkychubRequest($endpoint, $params = []) {
    // Add default credentials
    $params['username'] = EKYCHUB_USERNAME;
    $params['token'] = EKYCHUB_TOKEN;
    
    // Build URL
    $url = EKYCHUB_BASE_URL . $endpoint . '?' . http_build_query($params);
    
    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    // Execute request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    // Handle errors
    if ($error) {
        return [
            'success' => false,
            'status' => 'Error',
            'message' => 'API Request Failed: ' . $error
        ];
    }
    
    if ($httpCode !== 200) {
        return [
            'success' => false,
            'status' => 'Error',
            'message' => 'API returned HTTP ' . $httpCode
        ];
    }
    
    // Decode response
    $data = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        return [
            'success' => false,
            'status' => 'Error',
            'message' => 'Invalid JSON response from API'
        ];
    }
    
    // Add success flag based on status
    $data['success'] = ($data['status'] ?? 'Failure') === 'Success';
    
    return $data;
}

/**
 * Log API transaction to database
 * @param mysqli $conn Database connection
 * @param int $userId User ID
 * @param string $apiType Type of API (pan_verification, pan_360, pan_creation)
 * @param array $request Request parameters
 * @param array $response API response
 * @param string $orderId Order ID
 */
function logApiTransaction($conn, $userId, $apiType, $request, $response, $orderId) {
    $stmt = $conn->prepare("
        INSERT INTO api_transactions 
        (user_id, api_type, order_id, request_data, response_data, status, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");
    
    $requestJson = json_encode($request);
    $responseJson = json_encode($response);
    $status = $response['status'] ?? 'Error';
    
    $stmt->bind_param("isssss", $userId, $apiType, $orderId, $requestJson, $responseJson, $status);
    $stmt->execute();
    $stmt->close();
}

/**
 * Validate PAN number format
 * @param string $pan PAN number
 * @return bool True if valid format
 */
function isValidPanFormat($pan) {
    return preg_match('/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', strtoupper($pan));
}

/**
 * Validate mobile number format
 * @param string $mobile Mobile number
 * @return bool True if valid format
 */
function isValidMobileFormat($mobile) {
    return preg_match('/^[6-9][0-9]{9}$/', $mobile);
}
?>
