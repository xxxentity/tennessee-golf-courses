<?php
/**
 * API Security System
 * Comprehensive security for API endpoints including authentication, rate limiting, and validation
 */

class APISecurity {
    
    // API Security constants
    const API_TOKEN_LENGTH = 64;
    const API_TOKEN_EXPIRY = 3600; // 1 hour
    const DEFAULT_RATE_LIMIT = 100; // requests per hour
    const RATE_LIMIT_WINDOW = 3600; // 1 hour in seconds
    
    // Rate limiting tiers
    const RATE_LIMITS = [
        'public' => 60,     // 60 requests per hour for public endpoints
        'user' => 300,      // 300 requests per hour for authenticated users
        'admin' => 1000,    // 1000 requests per hour for admin users
        'internal' => 0     // No limit for internal API calls
    ];
    
    // API response codes
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_METHOD_NOT_ALLOWED = 405;
    const HTTP_TOO_MANY_REQUESTS = 429;
    const HTTP_INTERNAL_ERROR = 500;
    
    /**
     * Generate secure API token
     * @return string Generated API token
     */
    public static function generateAPIToken() {
        return bin2hex(random_bytes(self::API_TOKEN_LENGTH / 2));
    }
    
    /**
     * Authenticate API request
     * @param PDO $pdo Database connection
     * @param string $token API token
     * @return array Authentication result with user info
     */
    public static function authenticateAPIRequest(PDO $pdo, $token = null) {
        // Get token from header or query parameter
        if (!$token) {
            $token = self::getAPITokenFromRequest();
        }
        
        if (!$token) {
            return [
                'authenticated' => false,
                'user_type' => 'public',
                'user_id' => null,
                'error' => 'No API token provided'
            ];
        }
        
        try {
            // Check for valid user API token
            $stmt = $pdo->prepare("
                SELECT u.id, u.username, u.email, u.is_active, 
                       at.token, at.expires_at, at.last_used, at.permissions
                FROM api_tokens at
                JOIN users u ON at.user_id = u.id
                WHERE at.token = ? AND at.expires_at > NOW() AND at.is_active = 1 AND u.is_active = 1
            ");
            $stmt->execute([$token]);
            $userToken = $stmt->fetch();
            
            if ($userToken) {
                // Update last used timestamp
                $updateStmt = $pdo->prepare("UPDATE api_tokens SET last_used = NOW(), usage_count = usage_count + 1 WHERE token = ?");
                $updateStmt->execute([$token]);
                
                return [
                    'authenticated' => true,
                    'user_type' => 'user',
                    'user_id' => $userToken['id'],
                    'username' => $userToken['username'],
                    'email' => $userToken['email'],
                    'permissions' => json_decode($userToken['permissions'] ?? '[]', true),
                    'token' => $token
                ];
            }
            
            // Check for admin API token
            $stmt = $pdo->prepare("
                SELECT a.id, a.username, a.email, a.first_name, a.last_name, a.is_active,
                       at.token, at.expires_at, at.last_used, at.permissions
                FROM admin_api_tokens at
                JOIN admin_users a ON at.admin_id = a.id
                WHERE at.token = ? AND at.expires_at > NOW() AND at.is_active = 1 AND a.is_active = 1
            ");
            $stmt->execute([$token]);
            $adminToken = $stmt->fetch();
            
            if ($adminToken) {
                // Update last used timestamp
                $updateStmt = $pdo->prepare("UPDATE admin_api_tokens SET last_used = NOW(), usage_count = usage_count + 1 WHERE token = ?");
                $updateStmt->execute([$token]);
                
                return [
                    'authenticated' => true,
                    'user_type' => 'admin',
                    'user_id' => $adminToken['id'],
                    'username' => $adminToken['username'],
                    'email' => $adminToken['email'],
                    'first_name' => $adminToken['first_name'],
                    'last_name' => $adminToken['last_name'],
                    'permissions' => json_decode($adminToken['permissions'] ?? '[]', true),
                    'token' => $token
                ];
            }
            
            return [
                'authenticated' => false,
                'user_type' => 'public',
                'user_id' => null,
                'error' => 'Invalid or expired API token'
            ];
            
        } catch (PDOException $e) {
            error_log("API authentication error: " . $e->getMessage());
            return [
                'authenticated' => false,
                'user_type' => 'public',
                'user_id' => null,
                'error' => 'Authentication system error'
            ];
        }
    }
    
    /**
     * Get API token from request headers or query parameters
     * @return string|null API token or null if not found
     */
    public static function getAPITokenFromRequest() {
        // Check Authorization header (Bearer token)
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $matches[1];
        }
        
        // Check X-API-Key header
        if (isset($_SERVER['HTTP_X_API_KEY'])) {
            return $_SERVER['HTTP_X_API_KEY'];
        }
        
        // Check query parameter
        return $_GET['api_key'] ?? null;
    }
    
    /**
     * Check rate limits for API requests
     * @param PDO $pdo Database connection
     * @param string $identifier Client identifier (IP, user ID, token)
     * @param string $userType Type of user (public, user, admin, internal)
     * @return array Rate limit check result
     */
    public static function checkRateLimit(PDO $pdo, $identifier, $userType = 'public') {
        $limit = self::RATE_LIMITS[$userType] ?? self::DEFAULT_RATE_LIMIT;
        
        // No rate limiting for internal calls
        if ($userType === 'internal' || $limit === 0) {
            return [
                'allowed' => true,
                'limit' => $limit,
                'remaining' => $limit,
                'reset_time' => time() + self::RATE_LIMIT_WINDOW
            ];
        }
        
        $windowStart = date('Y-m-d H:i:s', time() - self::RATE_LIMIT_WINDOW);
        
        try {
            // Count requests in current window
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as request_count
                FROM api_rate_limits 
                WHERE identifier = ? AND created_at >= ?
            ");
            $stmt->execute([$identifier, $windowStart]);
            $currentCount = $stmt->fetchColumn();
            
            // Record this request
            $stmt = $pdo->prepare("
                INSERT INTO api_rate_limits (identifier, user_type, endpoint, ip_address, created_at) 
                VALUES (?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $identifier,
                $userType,
                $_SERVER['REQUEST_URI'] ?? '',
                self::getClientIP()
            ]);
            
            $remaining = max(0, $limit - ($currentCount + 1));
            $allowed = ($currentCount < $limit);
            
            return [
                'allowed' => $allowed,
                'limit' => $limit,
                'remaining' => $remaining,
                'reset_time' => time() + self::RATE_LIMIT_WINDOW,
                'current_count' => $currentCount + 1
            ];
            
        } catch (PDOException $e) {
            error_log("Rate limit check error: " . $e->getMessage());
            // Allow request on error to prevent service disruption
            return [
                'allowed' => true,
                'limit' => $limit,
                'remaining' => $limit,
                'reset_time' => time() + self::RATE_LIMIT_WINDOW,
                'error' => 'Rate limit system error'
            ];
        }
    }
    
    /**
     * Validate API request data
     * @param array $data Request data
     * @param array $rules Validation rules
     * @return array Validation result
     */
    public static function validateAPIRequest($data, $rules) {
        $errors = [];
        $sanitized = [];
        
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            $fieldRules = is_array($rule) ? $rule : [$rule];
            
            foreach ($fieldRules as $fieldRule) {
                switch ($fieldRule) {
                    case 'required':
                        if (empty($value)) {
                            $errors[$field][] = "Field '$field' is required";
                        }
                        break;
                        
                    case 'email':
                        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$field][] = "Field '$field' must be a valid email address";
                        }
                        break;
                        
                    case 'numeric':
                        if (!empty($value) && !is_numeric($value)) {
                            $errors[$field][] = "Field '$field' must be numeric";
                        }
                        break;
                        
                    case 'url':
                        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
                            $errors[$field][] = "Field '$field' must be a valid URL";
                        }
                        break;
                        
                    case 'alpha':
                        if (!empty($value) && !preg_match('/^[a-zA-Z]+$/', $value)) {
                            $errors[$field][] = "Field '$field' must contain only letters";
                        }
                        break;
                        
                    case 'alphanumeric':
                        if (!empty($value) && !preg_match('/^[a-zA-Z0-9]+$/', $value)) {
                            $errors[$field][] = "Field '$field' must contain only letters and numbers";
                        }
                        break;
                        
                    default:
                        // Handle min/max length rules
                        if (preg_match('/^min:(\d+)$/', $fieldRule, $matches)) {
                            $min = (int)$matches[1];
                            if (!empty($value) && strlen($value) < $min) {
                                $errors[$field][] = "Field '$field' must be at least $min characters long";
                            }
                        } elseif (preg_match('/^max:(\d+)$/', $fieldRule, $matches)) {
                            $max = (int)$matches[1];
                            if (!empty($value) && strlen($value) > $max) {
                                $errors[$field][] = "Field '$field' must not exceed $max characters";
                            }
                        }
                        break;
                }
            }
            
            // Sanitize value if no errors
            if (empty($errors[$field]) && $value !== null) {
                $sanitized[$field] = self::sanitizeAPIValue($value);
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'sanitized' => $sanitized
        ];
    }
    
    /**
     * Sanitize API input value
     * @param mixed $value Input value
     * @return mixed Sanitized value
     */
    public static function sanitizeAPIValue($value) {
        if (is_string($value)) {
            // Remove null bytes
            $value = str_replace("\0", '', $value);
            // Trim whitespace
            $value = trim($value);
            // Basic XSS prevention
            $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
        
        return $value;
    }
    
    /**
     * Send API response with proper headers
     * @param array $data Response data
     * @param int $statusCode HTTP status code
     * @param array $headers Additional headers
     */
    public static function sendAPIResponse($data, $statusCode = self::HTTP_OK, $headers = []) {
        // Set response code
        http_response_code($statusCode);
        
        // Set security headers
        header('Content-Type: application/json; charset=UTF-8');
        header('X-Content-Type-Options: nosniff');
        // header('X-Frame-Options: DENY'); // Disabled to allow Google Maps iframes
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        
        // Set additional headers
        foreach ($headers as $name => $value) {
            header("$name: $value");
        }
        
        // Add timestamp and request ID for debugging
        $response = [
            'timestamp' => date('c'),
            'request_id' => uniqid('req_', true),
            'status' => $statusCode >= 200 && $statusCode < 300 ? 'success' : 'error'
        ];
        
        // Merge with provided data
        $response = array_merge($response, $data);
        
        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }
    
    /**
     * Send API error response
     * @param string $message Error message
     * @param int $statusCode HTTP status code
     * @param array $details Additional error details
     */
    public static function sendAPIError($message, $statusCode = self::HTTP_BAD_REQUEST, $details = []) {
        $errorData = [
            'error' => [
                'message' => $message,
                'code' => $statusCode,
                'details' => $details
            ]
        ];
        
        self::sendAPIResponse($errorData, $statusCode);
    }
    
    /**
     * Get client IP address
     * @return string Client IP
     */
    public static function getClientIP() {
        $headers = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'REMOTE_ADDR'
        ];
        
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    /**
     * Log API security event
     * @param PDO $pdo Database connection
     * @param string $event Event type
     * @param array $details Event details
     * @param string $severity Event severity (info, warning, error, critical)
     */
    public static function logSecurityEvent(PDO $pdo, $event, $details = [], $severity = 'info') {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO api_security_logs (event_type, severity, ip_address, user_agent, details, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $event,
                $severity,
                self::getClientIP(),
                $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
                json_encode($details)
            ]);
        } catch (PDOException $e) {
            error_log("Failed to log API security event: " . $e->getMessage());
        }
    }
    
    /**
     * Create API middleware for endpoint protection
     * @param PDO $pdo Database connection
     * @param array $options Middleware options
     * @return callable Middleware function
     */
    public static function createAPIMiddleware(PDO $pdo, $options = []) {
        return function() use ($pdo, $options) {
            // Default options
            $options = array_merge([
                'require_auth' => false,
                'require_admin' => false,
                'rate_limit' => true,
                'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],
                'cors_enabled' => true
            ], $options);
            
            // Check HTTP method
            $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
            if (!in_array($method, $options['allowed_methods'])) {
                self::sendAPIError('Method not allowed', self::HTTP_METHOD_NOT_ALLOWED);
            }
            
            // Handle CORS preflight
            if ($options['cors_enabled'] && $method === 'OPTIONS') {
                header('Access-Control-Allow-Origin: *');
                header('Access-Control-Allow-Methods: ' . implode(', ', $options['allowed_methods']));
                header('Access-Control-Allow-Headers: Content-Type, Authorization, X-API-Key');
                header('Access-Control-Max-Age: 86400');
                http_response_code(200);
                exit;
            }
            
            // Authenticate request
            $auth = self::authenticateAPIRequest($pdo);
            
            // Check authentication requirements
            if ($options['require_auth'] && !$auth['authenticated']) {
                self::logSecurityEvent($pdo, 'unauthorized_api_access', [
                    'endpoint' => $_SERVER['REQUEST_URI'] ?? '',
                    'method' => $method
                ], 'warning');
                self::sendAPIError('Authentication required', self::HTTP_UNAUTHORIZED);
            }
            
            if ($options['require_admin'] && $auth['user_type'] !== 'admin') {
                self::logSecurityEvent($pdo, 'insufficient_api_privileges', [
                    'endpoint' => $_SERVER['REQUEST_URI'] ?? '',
                    'user_type' => $auth['user_type'],
                    'user_id' => $auth['user_id']
                ], 'warning');
                self::sendAPIError('Admin privileges required', self::HTTP_FORBIDDEN);
            }
            
            // Check rate limits
            if ($options['rate_limit']) {
                $identifier = $auth['authenticated'] ? $auth['user_id'] : self::getClientIP();
                $rateLimit = self::checkRateLimit($pdo, $identifier, $auth['user_type']);
                
                // Add rate limit headers
                header("X-RateLimit-Limit: {$rateLimit['limit']}");
                header("X-RateLimit-Remaining: {$rateLimit['remaining']}");
                header("X-RateLimit-Reset: {$rateLimit['reset_time']}");
                
                if (!$rateLimit['allowed']) {
                    self::logSecurityEvent($pdo, 'rate_limit_exceeded', [
                        'identifier' => $identifier,
                        'user_type' => $auth['user_type'],
                        'limit' => $rateLimit['limit'],
                        'current_count' => $rateLimit['current_count']
                    ], 'warning');
                    
                    self::sendAPIError(
                        'Rate limit exceeded',
                        self::HTTP_TOO_MANY_REQUESTS,
                        [
                            'limit' => $rateLimit['limit'],
                            'reset_time' => $rateLimit['reset_time']
                        ]
                    );
                }
            }
            
            // Set CORS headers if enabled
            if ($options['cors_enabled']) {
                header('Access-Control-Allow-Origin: *');
                header('Access-Control-Allow-Headers: Content-Type, Authorization, X-API-Key');
            }
            
            // Return authentication info for use in endpoint
            return $auth;
        };
    }
}
?>