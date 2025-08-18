<?php
/**
 * API v1 Index - Main API Router
 * Secure API endpoint with authentication, rate limiting, and validation
 */

require_once '../../config/database.php';
require_once '../../includes/api-security.php';

// Initialize API middleware
$middleware = APISecurity::createAPIMiddleware($pdo, [
    'require_auth' => false,  // Public endpoint
    'rate_limit' => true,
    'allowed_methods' => ['GET'],
    'cors_enabled' => true
]);

// Run middleware
$auth = $middleware();

// API Information endpoint
$apiInfo = [
    'name' => 'Tennessee Golf Courses API',
    'version' => '1.0.0',
    'description' => 'RESTful API for Tennessee Golf Courses platform',
    'endpoints' => [
        'GET /api/v1/' => 'API information',
        'GET /api/v1/courses' => 'List golf courses',
        'GET /api/v1/courses/{id}' => 'Get specific course',
        'POST /api/v1/auth/login' => 'User authentication',
        'GET /api/v1/user/profile' => 'User profile (auth required)',
        'POST /api/v1/newsletter/subscribe' => 'Newsletter subscription'
    ],
    'authentication' => [
        'type' => 'Bearer Token',
        'header' => 'Authorization: Bearer {token}',
        'alternative' => 'X-API-Key: {token}'
    ],
    'rate_limits' => [
        'public' => '60 requests/hour',
        'authenticated' => '300 requests/hour',
        'admin' => '1000 requests/hour'
    ],
    'documentation' => '/api/docs',
    'status' => 'active'
];

// Log API access
APISecurity::logSecurityEvent($pdo, 'api_info_accessed', [
    'user_type' => $auth['user_type'],
    'authenticated' => $auth['authenticated']
], 'info');

// Send response
APISecurity::sendAPIResponse([
    'api' => $apiInfo,
    'server_time' => date('c'),
    'authentication' => [
        'authenticated' => $auth['authenticated'],
        'user_type' => $auth['user_type']
    ]
]);
?>