<?php
/**
 * CSRF Protection System
 * Generates and validates CSRF tokens for form security
 */

class CSRFProtection {
    
    /**
     * Generate a new CSRF token
     * @return string The generated token
     */
    public static function generateToken() {
        // Ensure session is started (compatible with SecureSession)
        if (session_status() === PHP_SESSION_NONE) {
            try {
                // Try to use SecureSession if available
                if (class_exists('SecureSession')) {
                    SecureSession::start();
                } else {
                    session_start();
                }
            } catch (Exception $e) {
                // Fallback to regular session
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
            }
        }
        
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        $_SESSION['csrf_token_time'] = time();
        
        return $token;
    }
    
    /**
     * Get the current CSRF token (generate if doesn't exist)
     * @return string The current token
     */
    public static function getToken() {
        // Ensure session is started (compatible with SecureSession)
        if (session_status() === PHP_SESSION_NONE) {
            try {
                if (class_exists('SecureSession')) {
                    SecureSession::start();
                } else {
                    session_start();
                }
            } catch (Exception $e) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
            }
        }
        
        // Generate new token if doesn't exist or is expired (4 hours - increased timeout)
        if (!isset($_SESSION['csrf_token']) || 
            !isset($_SESSION['csrf_token_time']) || 
            (time() - $_SESSION['csrf_token_time']) > 14400) {
            return self::generateToken();
        }
        
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Validate CSRF token
     * @param string $token The token to validate
     * @return bool True if valid, false otherwise
     */
    public static function validateToken($token) {
        // Empty token is always invalid
        if (empty($token)) {
            return false;
        }
        // Ensure session is started (compatible with SecureSession)
        if (session_status() === PHP_SESSION_NONE) {
            try {
                if (class_exists('SecureSession')) {
                    SecureSession::start();
                } else {
                    session_start();
                }
            } catch (Exception $e) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
            }
        }
        
        // Check if token exists and is not expired (4 hours - increased timeout)
        if (!isset($_SESSION['csrf_token']) || 
            !isset($_SESSION['csrf_token_time']) || 
            (time() - $_SESSION['csrf_token_time']) > 14400) {
            return false;
        }
        
        // Use hash_equals to prevent timing attacks
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Generate CSRF input field for forms
     * @return string HTML input field
     */
    public static function getTokenField() {
        $token = self::getToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
    
    /**
     * Validate request and die if invalid
     * @param string $token The token from the form
     * @param string $redirect_url URL to redirect to on failure
     */
    public static function validateOrDie($token, $redirect_url = '/') {
        if (!self::validateToken($token)) {
            $_SESSION['error'] = 'Security token expired or invalid. Please try again.';
            header('Location: ' . $redirect_url);
            exit();
        }
    }
}
?>