<?php
/**
 * Authentication API Endpoint
 * Handles user login and API token generation
 */

require_once '../../config/database.php';
require_once '../../includes/api-security.php';
require_once '../../includes/input-validation.php';

// Initialize API middleware
$middleware = APISecurity::createAPIMiddleware($pdo, [
    'require_auth' => false,  // Authentication endpoint
    'rate_limit' => true,
    'allowed_methods' => ['POST'],
    'cors_enabled' => true
]);

// Run middleware
$auth = $middleware();

// Get request data
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    APISecurity::sendAPIError('Invalid JSON data', APISecurity::HTTP_BAD_REQUEST);
}

// Validate input
$validation = APISecurity::validateAPIRequest($input, [
    'email' => ['required', 'email'],
    'password' => ['required', 'min:6'],
    'token_name' => ['max:100'] // Optional name for the API token
]);

if (!$validation['valid']) {
    APISecurity::sendAPIError('Validation failed', APISecurity::HTTP_BAD_REQUEST, [
        'validation_errors' => $validation['errors']
    ]);
}

$email = $validation['sanitized']['email'];
$password = $input['password']; // Don't sanitize password
$tokenName = $validation['sanitized']['token_name'] ?? 'API Access';

try {
    // Check user credentials
    $stmt = $pdo->prepare("
        SELECT id, username, email, password_hash, first_name, last_name, is_active
        FROM users 
        WHERE email = ? AND is_active = 1
    ");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if (!$user || !password_verify($password, $user['password_hash'])) {
        // Log failed login attempt
        APISecurity::logSecurityEvent($pdo, 'api_login_failed', [
            'email' => $email,
            'ip' => APISecurity::getClientIP()
        ], 'warning');
        
        APISecurity::sendAPIError('Invalid credentials', APISecurity::HTTP_UNAUTHORIZED);
    }
    
    // Generate API token
    $token = APISecurity::generateAPIToken();
    $expiresAt = date('Y-m-d H:i:s', time() + APISecurity::API_TOKEN_EXPIRY);
    
    // Default permissions for user API tokens
    $permissions = [
        'read_profile',
        'update_profile',
        'read_courses',
        'manage_favorites',
        'newsletter_subscription'
    ];
    
    // Insert API token
    $stmt = $pdo->prepare("
        INSERT INTO api_tokens (user_id, token, name, permissions, expires_at)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $user['id'],
        $token,
        $tokenName,
        json_encode($permissions),
        $expiresAt
    ]);
    
    // Update user last login
    $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
    $stmt->execute([$user['id']]);
    
    // Log successful login
    APISecurity::logSecurityEvent($pdo, 'api_login_success', [
        'user_id' => $user['id'],
        'email' => $email,
        'token_name' => $tokenName
    ], 'info');
    
    // Return token and user info
    APISecurity::sendAPIResponse([
        'message' => 'Authentication successful',
        'token' => $token,
        'expires_at' => $expiresAt,
        'token_name' => $tokenName,
        'permissions' => $permissions,
        'user' => [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name']
        ]
    ], APISecurity::HTTP_OK);
    
} catch (PDOException $e) {
    error_log("API authentication error: " . $e->getMessage());
    
    APISecurity::logSecurityEvent($pdo, 'api_auth_error', [
        'error' => $e->getMessage(),
        'email' => $email
    ], 'error');
    
    APISecurity::sendAPIError(
        'Authentication system error',
        APISecurity::HTTP_INTERNAL_ERROR
    );
}
?>