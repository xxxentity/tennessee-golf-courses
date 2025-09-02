<?php
/**
 * Admin Area Security System
 * Comprehensive security for administrative functions
 */

class AdminSecurity {
    
    // Admin roles and permissions
    const ROLES = [
        'super_admin' => [
            'user_management', 'system_settings', 'security_settings', 
            'content_management', 'analytics', 'backups', 'logs'
        ],
        'admin' => [
            'user_management', 'content_management', 'analytics'
        ],
        'editor' => [
            'content_management'
        ],
        'moderator' => [
            'content_moderation', 'user_reports'
        ]
    ];
    
    // Security settings
    const MAX_LOGIN_ATTEMPTS = 3;
    const LOCKOUT_DURATION = 1800; // 30 minutes
    const SESSION_TIMEOUT = 3600; // 1 hour
    const REQUIRE_2FA = true;
    const IP_WHITELIST_ENABLED = false;
    
    /**
     * Authenticate admin user with enhanced security
     * @param PDO $pdo Database connection
     * @param string $username Admin username
     * @param string $password Admin password
     * @param string $totpCode TOTP code for 2FA
     * @return array Authentication result
     */
    public static function authenticateAdmin(PDO $pdo, $username, $password, $totpCode = null) {
        $clientIP = self::getClientIP();
        
        // Check if IP is whitelisted (if enabled)
        if (self::IP_WHITELIST_ENABLED && !self::isIPWhitelisted($pdo, $clientIP)) {
            self::logSecurityEvent($pdo, null, 'admin_login_blocked', [
                'reason' => 'IP not whitelisted',
                'ip' => $clientIP,
                'username' => $username
            ]);
            
            return [
                'success' => false,
                'error' => 'Access denied from this location',
                'code' => 'IP_NOT_WHITELISTED'
            ];
        }
        
        // Check for too many failed attempts
        if (self::isAdminLocked($pdo, $username, $clientIP)) {
            return [
                'success' => false,
                'error' => 'Account temporarily locked due to failed login attempts',
                'code' => 'ACCOUNT_LOCKED'
            ];
        }
        
        try {
            // Get admin user
            $stmt = $pdo->prepare("
                SELECT id, username, password_hash, role, is_active, two_factor_secret,
                       last_login, failed_attempts, locked_until, created_at
                FROM admin_users 
                WHERE username = ? AND is_active = 1
            ");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();
            
            if (!$admin) {
                self::recordAdminLoginAttempt($pdo, $username, false, $clientIP, 'Invalid username');
                return [
                    'success' => false,
                    'error' => 'Invalid credentials',
                    'code' => 'INVALID_CREDENTIALS'
                ];
            }
            
            // Verify password
            if (!password_verify($password, $admin['password_hash'])) {
                self::recordAdminLoginAttempt($pdo, $username, false, $clientIP, 'Invalid password');
                return [
                    'success' => false,
                    'error' => 'Invalid credentials',
                    'code' => 'INVALID_CREDENTIALS'
                ];
            }
            
            // Check 2FA if required
            if (self::REQUIRE_2FA && $admin['two_factor_secret']) {
                if (!$totpCode) {
                    return [
                        'success' => false,
                        'error' => 'Two-factor authentication code required',
                        'code' => 'TOTP_REQUIRED',
                        'admin_id' => $admin['id']
                    ];
                }
                
                if (!self::verifyTOTP($admin['two_factor_secret'], $totpCode)) {
                    self::recordAdminLoginAttempt($pdo, $username, false, $clientIP, 'Invalid 2FA code');
                    return [
                        'success' => false,
                        'error' => 'Invalid two-factor authentication code',
                        'code' => 'INVALID_TOTP'
                    ];
                }
            }
            
            // Success - reset failed attempts and update last login
            $stmt = $pdo->prepare("
                UPDATE admin_users 
                SET failed_attempts = 0, locked_until = NULL, last_login = NOW() 
                WHERE id = ?
            ");
            $stmt->execute([$admin['id']]);
            
            // Record successful login
            self::recordAdminLoginAttempt($pdo, $username, true, $clientIP, 'Successful login');
            
            // Log security event
            self::logSecurityEvent($pdo, $admin['id'], 'admin_login_success', [
                'ip' => $clientIP,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
            ]);
            
            return [
                'success' => true,
                'admin' => [
                    'id' => $admin['id'],
                    'username' => $admin['username'],
                    'role' => $admin['role'],
                    'permissions' => self::ROLES[$admin['role']] ?? []
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
        $rolePermissions = self::ROLES[$role] ?? [];
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
     * Check if admin is locked out
     * @param PDO $pdo Database connection
     * @param string $username Admin username
     * @param string $clientIP Client IP address
     * @return bool True if locked
     */
    public static function isAdminLocked(PDO $pdo, $username, $clientIP) {
        try {
            // Check account lockout
            $stmt = $pdo->prepare("
                SELECT locked_until, failed_attempts 
                FROM admin_users 
                WHERE username = ?
            ");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();
            
            if ($admin && $admin['locked_until'] && new DateTime() < new DateTime($admin['locked_until'])) {
                return true;
            }
            
            // Check IP-based lockout
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as failed_count
                FROM admin_login_attempts 
                WHERE ip_address = ? 
                AND success = 0 
                AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)
            ");
            $stmt->execute([$clientIP]);
            $ipFailures = $stmt->fetchColumn();
            
            return $ipFailures >= self::MAX_LOGIN_ATTEMPTS * 2; // IP lockout after double the account limit
            
        } catch (PDOException $e) {
            error_log("Admin lockout check error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Record admin login attempt
     * @param PDO $pdo Database connection
     * @param string $username Username attempted
     * @param bool $success Whether login was successful
     * @param string $clientIP Client IP address
     * @param string $details Additional details
     */
    public static function recordAdminLoginAttempt(PDO $pdo, $username, $success, $clientIP, $details = '') {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO admin_login_attempts 
                (username, success, ip_address, user_agent, details, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $username,
                $success ? 1 : 0,
                $clientIP,
                $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
                $details
            ]);
            
            // Update failed attempts counter for account
            if (!$success) {
                $stmt = $pdo->prepare("
                    UPDATE admin_users 
                    SET failed_attempts = failed_attempts + 1,
                        locked_until = CASE 
                            WHEN failed_attempts + 1 >= ? THEN DATE_ADD(NOW(), INTERVAL ? SECOND)
                            ELSE locked_until 
                        END
                    WHERE username = ?
                ");
                $stmt->execute([self::MAX_LOGIN_ATTEMPTS, self::LOCKOUT_DURATION, $username]);
            }
            
        } catch (PDOException $e) {
            error_log("Failed to record admin login attempt: " . $e->getMessage());
        }
    }
    
    /**
     * Generate TOTP secret for 2FA
     * @return string Base32 encoded secret
     */
    public static function generateTOTPSecret() {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < 32; $i++) {
            $secret .= $chars[random_int(0, 31)];
        }
        return $secret;
    }
    
    /**
     * Verify TOTP code
     * @param string $secret Base32 encoded secret
     * @param string $code 6-digit TOTP code
     * @param int $tolerance Time tolerance in periods
     * @return bool True if valid
     */
    public static function verifyTOTP($secret, $code, $tolerance = 1) {
        if (!$secret || !$code) {
            return false;
        }
        
        $timeSlice = floor(time() / 30);
        
        for ($i = -$tolerance; $i <= $tolerance; $i++) {
            $calculatedCode = self::generateTOTPCode($secret, $timeSlice + $i);
            if (hash_equals($calculatedCode, $code)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Generate TOTP code for given time slice
     * @param string $secret Base32 encoded secret
     * @param int $timeSlice Time slice
     * @return string 6-digit TOTP code
     */
    private static function generateTOTPCode($secret, $timeSlice) {
        $key = self::base32Decode($secret);
        $time = pack('N*', 0) . pack('N*', $timeSlice);
        $hash = hash_hmac('sha1', $time, $key, true);
        $offset = ord($hash[19]) & 0xf;
        $code = (
            ((ord($hash[$offset+0]) & 0x7f) << 24) |
            ((ord($hash[$offset+1]) & 0xff) << 16) |
            ((ord($hash[$offset+2]) & 0xff) << 8) |
            (ord($hash[$offset+3]) & 0xff)
        ) % 1000000;
        
        return str_pad($code, 6, '0', STR_PAD_LEFT);
    }
    
    /**
     * Decode base32 string
     * @param string $input Base32 encoded string
     * @return string Decoded string
     */
    private static function base32Decode($input) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $input = strtoupper($input);
        $output = '';
        $v = 0;
        $vbits = 0;
        
        for ($i = 0, $j = strlen($input); $i < $j; $i++) {
            $v <<= 5;
            $v += strpos($chars, $input[$i]);
            $vbits += 5;
            
            if ($vbits >= 8) {
                $output .= chr($v >> ($vbits - 8));
                $vbits -= 8;
            }
        }
        
        return $output;
    }
    
    /**
     * Check if IP is whitelisted
     * @param PDO $pdo Database connection
     * @param string $ip IP address to check
     * @return bool True if whitelisted
     */
    public static function isIPWhitelisted(PDO $pdo, $ip) {
        try {
            $stmt = $pdo->prepare("
                SELECT COUNT(*) 
                FROM admin_ip_whitelist 
                WHERE ip_address = ? AND is_active = 1
            ");
            $stmt->execute([$ip]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("IP whitelist check error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Log security event
     * @param PDO $pdo Database connection
     * @param int|null $adminId Admin ID (null for system events)
     * @param string $eventType Type of event
     * @param array $details Event details
     */
    public static function logSecurityEvent(PDO $pdo, $adminId, $eventType, $details = []) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO admin_security_log 
                (admin_id, event_type, event_details, ip_address, user_agent, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $adminId,
                $eventType,
                json_encode($details),
                self::getClientIP(),
                $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
            ]);
        } catch (PDOException $e) {
            error_log("Failed to log security event: " . $e->getMessage());
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
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
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
        $_SESSION['admin_role'] = $admin['role'];
        $_SESSION['admin_permissions'] = $admin['permissions'];
        $_SESSION['admin_login_time'] = time();
        $_SESSION['admin_last_activity'] = time();
        $_SESSION['admin_ip'] = self::getClientIP();
        $_SESSION['admin_user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
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
            return false;
        }
        
        // Check session timeout
        if (!isset($_SESSION['admin_last_activity']) || 
            (time() - $_SESSION['admin_last_activity']) > self::SESSION_TIMEOUT) {
            self::destroyAdminSession();
            return false;
        }
        
        // Check IP consistency
        if (isset($_SESSION['admin_ip']) && $_SESSION['admin_ip'] !== self::getClientIP()) {
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
        unset($_SESSION['admin_logged_in']);
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_username']);
        unset($_SESSION['admin_role']);
        unset($_SESSION['admin_permissions']);
        unset($_SESSION['admin_login_time']);
        unset($_SESSION['admin_last_activity']);
        unset($_SESSION['admin_ip']);
        unset($_SESSION['admin_user_agent']);
        
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
            'role' => $_SESSION['admin_role'] ?? null,
            'permissions' => $_SESSION['admin_permissions'] ?? [],
            'login_time' => $_SESSION['admin_login_time'] ?? null,
            'ip' => $_SESSION['admin_ip'] ?? null
        ];
    }
}
?>