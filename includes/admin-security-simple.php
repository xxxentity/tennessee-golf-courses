<?php
/**
 * Simple Admin Security System
 * Compatible with existing admin_users table structure
 */

class AdminSecurity {
    
    // Admin roles and permissions (simplified for existing system)
    const ROLES = [
        'admin' => [
            'user_management', 'content_management', 'analytics', 'newsletter', 'security', 'logs', 'settings'
        ]
    ];
    
    // Security settings
    const SESSION_TIMEOUT = 3600; // 1 hour
    
    /**
     * Authenticate admin user (simplified for existing table)
     * @param PDO $pdo Database connection
     * @param string $username Admin username
     * @param string $password Admin password
     * @return array Authentication result
     */
    public static function authenticateAdmin(PDO $pdo, $username, $password) {
        $clientIP = self::getClientIP();
        
        try {
            // Get admin user from existing table structure
            $stmt = $pdo->prepare("
                SELECT id, username, email, password_hash, first_name, last_name, is_active
                FROM admin_users 
                WHERE username = ? AND is_active = 1
            ");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();
            
            if (!$admin) {
                error_log("Admin login failed: Invalid username '$username' from IP: $clientIP");
                return [
                    'success' => false,
                    'error' => 'Invalid credentials',
                    'code' => 'INVALID_CREDENTIALS'
                ];
            }
            
            // Verify password
            if (!password_verify($password, $admin['password_hash'])) {
                error_log("Admin login failed: Invalid password for '$username' from IP: $clientIP");
                return [
                    'success' => false,
                    'error' => 'Invalid credentials',
                    'code' => 'INVALID_CREDENTIALS'
                ];
            }
            
            // Success - skip last_login update since column may not exist
            // TODO: Add last_login column to admin_users table if needed
            
            // Log successful login
            error_log("Admin login successful: '$username' from IP: $clientIP");
            
            return [
                'success' => true,
                'admin' => [
                    'id' => $admin['id'],
                    'username' => $admin['username'],
                    'email' => $admin['email'],
                    'first_name' => $admin['first_name'],
                    'last_name' => $admin['last_name'],
                    'role' => 'admin', // Default role for existing system
                    'permissions' => self::ROLES['admin']
                ]
            ];
            
        } catch (PDOException $e) {
            error_log("Admin authentication error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Authentication system error',
                'code' => 'SYSTEM_ERROR'
            ];
        }
    }
    
    /**
     * Check if admin has specific permission
     * @param string $role Admin role
     * @param string $permission Required permission
     * @return bool True if permission granted
     */
    public static function hasPermission($role, $permission) {
        $rolePermissions = self::ROLES[$role] ?? self::ROLES['admin'];
        return in_array($permission, $rolePermissions);
    }
    
    /**
     * Require specific permission or deny access
     * @param string $role Admin role
     * @param string $permission Required permission
     * @param string $redirectUrl URL to redirect on failure
     */
    public static function requirePermission($role, $permission, $redirectUrl = '/admin/login') {
        if (!self::hasPermission($role, $permission)) {
            http_response_code(403);
            header('Location: ' . $redirectUrl . '?error=' . urlencode('Insufficient permissions'));
            exit;
        }
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
     * Start secure admin session
     * @param array $admin Admin user data
     */
    public static function startAdminSession($admin) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Regenerate session ID for security
        session_regenerate_id(true);
        
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['admin_first_name'] = $admin['first_name'];
        $_SESSION['admin_last_name'] = $admin['last_name'];
        $_SESSION['admin_role'] = $admin['role'];
        $_SESSION['admin_permissions'] = $admin['permissions'];
        $_SESSION['admin_login_time'] = time();
        $_SESSION['admin_last_activity'] = time();
        $_SESSION['admin_ip'] = self::getClientIP();
        $_SESSION['admin_user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        
        // Backward compatibility with existing admin system
        $_SESSION['is_admin'] = true;
        $_SESSION['first_name'] = $admin['first_name'];
        $_SESSION['last_name'] = $admin['last_name'];
    }
    
    /**
     * Check if admin session is valid
     * @return bool True if valid
     */
    public static function isValidAdminSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if logged in
        if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
            // Check backward compatibility
            if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
                return true;
            }
            return false;
        }
        
        // Check session timeout
        if (!isset($_SESSION['admin_last_activity']) || 
            (time() - $_SESSION['admin_last_activity']) > self::SESSION_TIMEOUT) {
            self::destroyAdminSession();
            return false;
        }
        
        // Update last activity
        $_SESSION['admin_last_activity'] = time();
        
        return true;
    }
    
    /**
     * Destroy admin session
     */
    public static function destroyAdminSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Clear admin session variables
        $adminKeys = [
            'admin_logged_in', 'admin_id', 'admin_username', 'admin_email',
            'admin_first_name', 'admin_last_name', 'admin_role', 'admin_permissions',
            'admin_login_time', 'admin_last_activity', 'admin_ip', 'admin_user_agent',
            'is_admin', 'first_name', 'last_name'
        ];
        
        foreach ($adminKeys as $key) {
            unset($_SESSION[$key]);
        }
        
        // Regenerate session ID
        session_regenerate_id(true);
    }
    
    /**
     * Require admin authentication
     * @param string $redirectUrl URL to redirect if not authenticated
     */
    public static function requireAdminAuth($redirectUrl = '/admin/login') {
        if (!self::isValidAdminSession()) {
            header('Location: ' . $redirectUrl);
            exit;
        }
    }
    
    /**
     * Get current admin info
     * @return array|null Admin info or null if not logged in
     */
    public static function getCurrentAdmin() {
        if (!self::isValidAdminSession()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['admin_id'] ?? null,
            'username' => $_SESSION['admin_username'] ?? null,
            'email' => $_SESSION['admin_email'] ?? null,
            'first_name' => $_SESSION['admin_first_name'] ?? $_SESSION['first_name'] ?? null,
            'last_name' => $_SESSION['admin_last_name'] ?? $_SESSION['last_name'] ?? null,
            'role' => $_SESSION['admin_role'] ?? 'admin',
            'permissions' => $_SESSION['admin_permissions'] ?? self::ROLES['admin'],
            'login_time' => $_SESSION['admin_login_time'] ?? null,
            'ip' => $_SESSION['admin_ip'] ?? null
        ];
    }
}
?>