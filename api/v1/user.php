<?php
/**
 * User Profile API Endpoint
 * Authenticated endpoint for user profile management
 */

require_once '../../config/database.php';
require_once '../../includes/api-security.php';
require_once '../../includes/input-validation.php';
require_once '../../includes/output-security.php';

// Initialize API middleware
$middleware = APISecurity::createAPIMiddleware($pdo, [
    'require_auth' => true,   // Authentication required
    'rate_limit' => true,
    'allowed_methods' => ['GET', 'PUT'],
    'cors_enabled' => true
]);

// Run middleware
$auth = $middleware();

// Only allow access to user accounts (not admin)
if ($auth['user_type'] !== 'user') {
    APISecurity::sendAPIError('This endpoint is for user accounts only', APISecurity::HTTP_FORBIDDEN);
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        // Get user profile
        $stmt = $pdo->prepare("
            SELECT id, username, email, first_name, last_name, phone, city, state, 
                   email_notifications, sms_notifications, created_at, last_login
            FROM users 
            WHERE id = ? AND is_active = 1
        ");
        $stmt->execute([$auth['user_id']]);
        $user = $stmt->fetch();
        
        if (!$user) {
            APISecurity::sendAPIError('User not found', APISecurity::HTTP_NOT_FOUND);
        }
        
        // Get user's favorite courses
        $stmt = $pdo->prepare("
            SELECT gc.id, gc.name, gc.city, gc.state, gc.image_url
            FROM user_favorites uf
            JOIN golf_courses gc ON uf.course_id = gc.id
            WHERE uf.user_id = ? AND gc.status = 'active'
            ORDER BY uf.created_at DESC
        ");
        $stmt->execute([$auth['user_id']]);
        $favorites = $stmt->fetchAll();
        
        // Safely output data
        $user['first_name'] = esc($user['first_name']);
        $user['last_name'] = esc($user['last_name']);
        $user['city'] = esc($user['city']);
        
        foreach ($favorites as &$favorite) {
            $favorite['name'] = esc($favorite['name']);
            $favorite['city'] = esc($favorite['city']);
        }
        
        APISecurity::sendAPIResponse([
            'user' => $user,
            'favorites' => $favorites,
            'statistics' => [
                'total_favorites' => count($favorites),
                'member_since' => date('Y-m-d', strtotime($user['created_at']))
            ]
        ]);
        
    } elseif ($method === 'PUT') {
        // Update user profile
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            APISecurity::sendAPIError('Invalid JSON data', APISecurity::HTTP_BAD_REQUEST);
        }
        
        // Validate input
        $validation = APISecurity::validateAPIRequest($input, [
            'first_name' => ['max:50'],
            'last_name' => ['max:50'],
            'phone' => ['max:20'],
            'city' => ['max:100'],
            'state' => ['max:50'],
            'email_notifications' => [],
            'sms_notifications' => []
        ]);
        
        if (!$validation['valid']) {
            APISecurity::sendAPIError('Validation failed', APISecurity::HTTP_BAD_REQUEST, [
                'validation_errors' => $validation['errors']
            ]);
        }
        
        // Build update query
        $updateFields = [];
        $params = [];
        
        $allowedFields = ['first_name', 'last_name', 'phone', 'city', 'state', 'email_notifications', 'sms_notifications'];
        
        foreach ($allowedFields as $field) {
            if (isset($validation['sanitized'][$field])) {
                $updateFields[] = "$field = ?";
                
                // Handle boolean fields
                if (in_array($field, ['email_notifications', 'sms_notifications'])) {
                    $params[] = $validation['sanitized'][$field] ? 1 : 0;
                } else {
                    $params[] = $validation['sanitized'][$field];
                }
            }
        }
        
        if (empty($updateFields)) {
            APISecurity::sendAPIError('No valid fields to update', APISecurity::HTTP_BAD_REQUEST);
        }
        
        $updateFields[] = "updated_at = NOW()";
        $params[] = $auth['user_id'];
        
        $sql = "UPDATE users SET " . implode(', ', $updateFields) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        // Get updated user data
        $stmt = $pdo->prepare("
            SELECT id, username, email, first_name, last_name, phone, city, state, 
                   email_notifications, sms_notifications, updated_at
            FROM users 
            WHERE id = ?
        ");
        $stmt->execute([$auth['user_id']]);
        $updatedUser = $stmt->fetch();
        
        // Safely output data
        $updatedUser['first_name'] = esc($updatedUser['first_name']);
        $updatedUser['last_name'] = esc($updatedUser['last_name']);
        $updatedUser['city'] = esc($updatedUser['city']);
        
        // Log profile update
        APISecurity::logSecurityEvent($pdo, 'user_profile_updated', [
            'user_id' => $auth['user_id'],
            'updated_fields' => array_keys($validation['sanitized'])
        ], 'info');
        
        APISecurity::sendAPIResponse([
            'message' => 'Profile updated successfully',
            'user' => $updatedUser
        ]);
    }
    
} catch (PDOException $e) {
    error_log("User API error: " . $e->getMessage());
    
    APISecurity::logSecurityEvent($pdo, 'api_database_error', [
        'endpoint' => '/api/v1/user',
        'method' => $method,
        'error' => $e->getMessage(),
        'user_id' => $auth['user_id']
    ], 'error');
    
    APISecurity::sendAPIError(
        'Unable to process request at this time',
        APISecurity::HTTP_INTERNAL_ERROR
    );
}
?>