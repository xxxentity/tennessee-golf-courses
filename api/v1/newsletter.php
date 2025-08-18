<?php
/**
 * Newsletter API Endpoint
 * Public endpoint for newsletter subscription management
 */

require_once '../../config/database.php';
require_once '../../includes/api-security.php';
require_once '../../includes/input-validation.php';

// Initialize API middleware
$middleware = APISecurity::createAPIMiddleware($pdo, [
    'require_auth' => false,  // Public endpoint
    'rate_limit' => true,
    'allowed_methods' => ['POST', 'DELETE'],
    'cors_enabled' => true
]);

// Run middleware
$auth = $middleware();

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'POST') {
        // Subscribe to newsletter
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            APISecurity::sendAPIError('Invalid JSON data', APISecurity::HTTP_BAD_REQUEST);
        }
        
        // Validate input
        $validation = APISecurity::validateAPIRequest($input, [
            'email' => ['required', 'email'],
            'first_name' => ['max:50'],
            'last_name' => ['max:50'],
            'preferences' => [] // Optional array of subscription preferences
        ]);
        
        if (!$validation['valid']) {
            APISecurity::sendAPIError('Validation failed', APISecurity::HTTP_BAD_REQUEST, [
                'validation_errors' => $validation['errors']
            ]);
        }
        
        $email = $validation['sanitized']['email'];
        $firstName = $validation['sanitized']['first_name'] ?? '';
        $lastName = $validation['sanitized']['last_name'] ?? '';
        $preferences = $input['preferences'] ?? [];
        
        // Check if already subscribed
        $stmt = $pdo->prepare("SELECT id, confirmed FROM newsletter_subscribers WHERE email = ?");
        $stmt->execute([$email]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            if ($existing['confirmed']) {
                APISecurity::sendAPIResponse([
                    'message' => 'Email is already subscribed to our newsletter',
                    'status' => 'already_subscribed'
                ]);
            } else {
                // Resend confirmation
                $confirmationToken = bin2hex(random_bytes(32));
                $stmt = $pdo->prepare("
                    UPDATE newsletter_subscribers 
                    SET confirmation_token = ?, first_name = ?, last_name = ?, preferences = ?, updated_at = NOW()
                    WHERE id = ?
                ");
                $stmt->execute([
                    $confirmationToken,
                    $firstName,
                    $lastName,
                    json_encode($preferences),
                    $existing['id']
                ]);
                
                APISecurity::sendAPIResponse([
                    'message' => 'Confirmation email resent. Please check your email to confirm subscription.',
                    'status' => 'confirmation_resent',
                    'confirmation_token' => $confirmationToken // For testing - remove in production
                ]);
            }
        } else {
            // New subscription
            $confirmationToken = bin2hex(random_bytes(32));
            
            $stmt = $pdo->prepare("
                INSERT INTO newsletter_subscribers (email, first_name, last_name, preferences, confirmation_token, ip_address)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $email,
                $firstName,
                $lastName,
                json_encode($preferences),
                $confirmationToken,
                APISecurity::getClientIP()
            ]);
            
            // Log subscription
            APISecurity::logSecurityEvent($pdo, 'newsletter_subscription', [
                'email' => $email,
                'ip' => APISecurity::getClientIP(),
                'preferences' => $preferences
            ], 'info');
            
            APISecurity::sendAPIResponse([
                'message' => 'Subscription successful! Please check your email to confirm.',
                'status' => 'pending_confirmation',
                'confirmation_token' => $confirmationToken // For testing - remove in production
            ], APISecurity::HTTP_CREATED);
        }
        
    } elseif ($method === 'DELETE') {
        // Unsubscribe from newsletter
        $email = $_GET['email'] ?? '';
        $token = $_GET['token'] ?? '';
        
        if (empty($email)) {
            APISecurity::sendAPIError('Email address is required', APISecurity::HTTP_BAD_REQUEST);
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            APISecurity::sendAPIError('Invalid email address', APISecurity::HTTP_BAD_REQUEST);
        }
        
        // If token provided, use it for verification (more secure)
        if (!empty($token)) {
            $stmt = $pdo->prepare("
                DELETE FROM newsletter_subscribers 
                WHERE email = ? AND (confirmation_token = ? OR unsubscribe_token = ?)
            ");
            $stmt->execute([$email, $token, $token]);
        } else {
            // Direct unsubscribe (less secure but more user-friendly)
            $stmt = $pdo->prepare("DELETE FROM newsletter_subscribers WHERE email = ?");
            $stmt->execute([$email]);
        }
        
        if ($stmt->rowCount() > 0) {
            // Log unsubscription
            APISecurity::logSecurityEvent($pdo, 'newsletter_unsubscribe', [
                'email' => $email,
                'ip' => APISecurity::getClientIP(),
                'token_used' => !empty($token)
            ], 'info');
            
            APISecurity::sendAPIResponse([
                'message' => 'Successfully unsubscribed from newsletter',
                'status' => 'unsubscribed'
            ]);
        } else {
            APISecurity::sendAPIResponse([
                'message' => 'Email not found in subscription list',
                'status' => 'not_found'
            ], APISecurity::HTTP_NOT_FOUND);
        }
    }
    
} catch (PDOException $e) {
    error_log("Newsletter API error: " . $e->getMessage());
    
    APISecurity::logSecurityEvent($pdo, 'api_database_error', [
        'endpoint' => '/api/v1/newsletter',
        'method' => $method,
        'error' => $e->getMessage()
    ], 'error');
    
    APISecurity::sendAPIError(
        'Unable to process newsletter request at this time',
        APISecurity::HTTP_INTERNAL_ERROR
    );
}
?>