<?php
/**
 * Session Security Configuration
 * Implements secure session handling to prevent hijacking and fixation attacks
 */

class SecureSession {
    
    /**
     * Start a secure session with proper configuration
     */
    public static function start() {
        // Check if session is already started
        if (session_status() === PHP_SESSION_ACTIVE) {
            return true;
        }
        
        // Set secure session parameters before starting
        $currentCookieParams = session_get_cookie_params();
        
        // Check if we're on HTTPS or localhost for development
        $isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
        $isLocalhost = in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1', '::1']);
        
        session_set_cookie_params([
            'lifetime' => 0,                    // Session cookie (expires on browser close)
            'path' => '/',
            'domain' => '',                     // Current domain only
            'secure' => $isSecure && !$isLocalhost, // HTTPS only (except localhost)
            'httponly' => true,                 // Prevent JavaScript access
            'samesite' => 'Strict'             // CSRF protection
        ]);
        
        // Use secure session name
        session_name('TGC_SECURE_SESSION');
        
        // Start the session
        if (!session_start()) {
            throw new Exception('Failed to start secure session');
        }
        
        // Regenerate session ID if this is a new session
        if (!isset($_SESSION['session_initialized'])) {
            session_regenerate_id(true);
            $_SESSION['session_initialized'] = true;
            $_SESSION['session_created'] = time();
            $_SESSION['session_last_activity'] = time();
            $_SESSION['session_user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
            $_SESSION['session_ip'] = self::getClientIP();
        }
        
        // Check session validity
        self::validateSession();
        
        return true;
    }
    
    /**
     * Validate current session for security
     */
    private static function validateSession() {
        $maxLifetime = 3600; // 1 hour
        $maxInactivity = 1800; // 30 minutes
        
        // Check session age
        if (isset($_SESSION['session_created']) && 
            (time() - $_SESSION['session_created']) > $maxLifetime) {
            self::destroy();
            throw new Exception('Session expired due to age');
        }
        
        // Check inactivity
        if (isset($_SESSION['session_last_activity']) && 
            (time() - $_SESSION['session_last_activity']) > $maxInactivity) {
            self::destroy();
            throw new Exception('Session expired due to inactivity');
        }
        
        // Check for user agent change (possible hijacking)
        if (isset($_SESSION['session_user_agent']) && 
            $_SESSION['session_user_agent'] !== ($_SERVER['HTTP_USER_AGENT'] ?? '')) {
            self::destroy();
            throw new Exception('Session validation failed: User agent mismatch');
        }
        
        // Check for suspicious IP change (optional - can be strict)
        $currentIP = self::getClientIP();
        if (isset($_SESSION['session_ip']) && $_SESSION['session_ip'] !== $currentIP) {
            // Log suspicious activity but don't destroy session (IPs can change legitimately)
            error_log("Warning: Session IP changed from {$_SESSION['session_ip']} to {$currentIP}");
        }
        
        // Update last activity time
        $_SESSION['session_last_activity'] = time();
    }
    
    /**
     * Regenerate session ID (call after login/privilege change)
     */
    public static function regenerate() {
        // Delete old session
        $oldSession = $_SESSION;
        session_regenerate_id(true);
        
        // Restore session data
        $_SESSION = $oldSession;
        $_SESSION['session_created'] = time();
        $_SESSION['session_user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $_SESSION['session_ip'] = self::getClientIP();
    }
    
    /**
     * Destroy session securely
     */
    public static function destroy() {
        // Clear session array
        $_SESSION = [];
        
        // Delete session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destroy session
        session_destroy();
    }
    
    /**
     * Get client IP address
     */
    private static function getClientIP() {
        // Check for proxy headers (in order of trust)
        $headers = [
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_X_FORWARDED_FOR',      // Proxy
            'HTTP_X_REAL_IP',           // Nginx
            'REMOTE_ADDR'               // Direct connection
        ];
        
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];
                // Handle comma-separated list (X-Forwarded-For)
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                // Validate IP
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    /**
     * Set secure session variable
     */
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    /**
     * Get session variable
     */
    public static function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Check if session variable exists
     */
    public static function has($key) {
        return isset($_SESSION[$key]);
    }
    
    /**
     * Remove session variable
     */
    public static function remove($key) {
        unset($_SESSION[$key]);
    }
    
    /**
     * Check if user is logged in
     */
    public static function isLoggedIn() {
        return self::has('logged_in') && self::get('logged_in') === true;
    }
    
    /**
     * Login user (with session regeneration)
     */
    public static function login($userId, $username) {
        // Regenerate session ID to prevent fixation
        self::regenerate();
        
        // Set login session variables
        self::set('logged_in', true);
        self::set('user_id', $userId);
        self::set('username', $username);
        self::set('login_time', time());
    }
    
    /**
     * Logout user
     */
    public static function logout() {
        self::destroy();
    }
}
?>