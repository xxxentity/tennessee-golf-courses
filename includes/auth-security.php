<?php
/**
 * Authentication Security System
 * Enhanced security features for user authentication
 */

class AuthSecurity {
    
    // Security configuration
    const MIN_PASSWORD_LENGTH = 8;
    const MAX_LOGIN_ATTEMPTS = 5;
    const LOCKOUT_DURATION = 900; // 15 minutes
    const PASSWORD_RESET_EXPIRY = 3600; // 1 hour
    const SESSION_LIFETIME = 3600; // 1 hour
    
    /**
     * Validate password strength
     * @param string $password Password to validate
     * @return array Validation result with strength score and feedback
     */
    public static function validatePasswordStrength($password) {
        $score = 0;
        $feedback = [];
        $requirements = [];
        
        // Length check
        if (strlen($password) >= self::MIN_PASSWORD_LENGTH) {
            $score += 20;
            $requirements['length'] = true;
        } else {
            $requirements['length'] = false;
            $feedback[] = 'Password must be at least ' . self::MIN_PASSWORD_LENGTH . ' characters long';
        }
        
        // Uppercase letter
        if (preg_match('/[A-Z]/', $password)) {
            $score += 20;
            $requirements['uppercase'] = true;
        } else {
            $requirements['uppercase'] = false;
            $feedback[] = 'Password must contain at least one uppercase letter';
        }
        
        // Lowercase letter
        if (preg_match('/[a-z]/', $password)) {
            $score += 20;
            $requirements['lowercase'] = true;
        } else {
            $requirements['lowercase'] = false;
            $feedback[] = 'Password must contain at least one lowercase letter';
        }
        
        // Number
        if (preg_match('/[0-9]/', $password)) {
            $score += 20;
            $requirements['number'] = true;
        } else {
            $requirements['number'] = false;
            $feedback[] = 'Password must contain at least one number';
        }
        
        // Special character
        if (preg_match('/[^A-Za-z0-9]/', $password)) {
            $score += 20;
            $requirements['special'] = true;
        } else {
            $requirements['special'] = false;
            $feedback[] = 'Password must contain at least one special character (!@#$%^&*)';
        }
        
        // Additional checks for very strong passwords
        if (strlen($password) >= 12) {
            $score += 10; // Bonus for longer passwords
        }
        
        if (!self::isCommonPassword($password)) {
            $score += 10; // Bonus for not being a common password
        } else {
            $feedback[] = 'Password is too common, please choose a more unique password';
            $score = max(0, $score - 30);
        }
        
        // Determine strength level
        $strength = 'weak';
        if ($score >= 90) {
            $strength = 'very_strong';
        } elseif ($score >= 70) {
            $strength = 'strong';
        } elseif ($score >= 50) {
            $strength = 'medium';
        }
        
        return [
            'valid' => $score >= 80, // Require strong password
            'score' => $score,
            'strength' => $strength,
            'requirements' => $requirements,
            'feedback' => $feedback
        ];
    }
    
    /**
     * Check if password is in common passwords list
     */
    private static function isCommonPassword($password) {
        $commonPasswords = [
            'password', '123456', '123456789', '12345678', '12345',
            'qwerty', 'abc123', 'password123', 'admin', 'letmein',
            'welcome', '1234567', 'password1', '123123', 'dragon',
            'master', 'monkey', 'sunshine', 'princess', 'football'
        ];
        
        return in_array(strtolower($password), $commonPasswords);
    }
    
    /**
     * Generate secure password hash
     * @param string $password Plain text password
     * @return string Hashed password
     */
    public static function hashPassword($password) {
        // Use PHP's password_hash with stronger options
        return password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536, // 64 MB
            'time_cost' => 4,       // 4 iterations
            'threads' => 3          // 3 threads
        ]);
    }
    
    /**
     * Verify password against hash
     * @param string $password Plain text password
     * @param string $hash Stored password hash
     * @return bool True if password is correct
     */
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    /**
     * Check if password hash needs rehashing (security upgrade)
     * @param string $hash Current password hash
     * @return bool True if rehashing is needed
     */
    public static function needsRehash($hash) {
        return password_needs_rehash($hash, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost' => 4,
            'threads' => 3
        ]);
    }
    
    /**
     * Record login attempt
     * @param PDO $pdo Database connection
     * @param string $identifier Username or email
     * @param bool $success Whether login was successful
     * @param string $ipAddress Client IP address
     */
    public static function recordLoginAttempt(PDO $pdo, $identifier, $success, $ipAddress) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO login_attempts (identifier, success, ip_address, user_agent, created_at) 
                VALUES (?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $identifier,
                $success ? 1 : 0,
                $ipAddress,
                $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
            ]);
            
            // Clean up old login attempts (keep last 30 days)
            $cleanupStmt = $pdo->prepare("
                DELETE FROM login_attempts 
                WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)
            ");
            $cleanupStmt->execute();
            
        } catch (PDOException $e) {
            error_log("Failed to record login attempt: " . $e->getMessage());
        }
    }
    
    /**
     * Check for suspicious login activity
     * @param PDO $pdo Database connection
     * @param string $ipAddress Client IP address
     * @return array Analysis result
     */
    public static function analyzeSuspiciousActivity(PDO $pdo, $ipAddress) {
        try {
            // Check failed attempts from this IP in last hour
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as failed_attempts 
                FROM login_attempts 
                WHERE ip_address = ? 
                AND success = 0 
                AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)
            ");
            $stmt->execute([$ipAddress]);
            $recentFailed = $stmt->fetchColumn();
            
            // Check if this IP has been trying multiple accounts
            $stmt = $pdo->prepare("
                SELECT COUNT(DISTINCT identifier) as unique_attempts 
                FROM login_attempts 
                WHERE ip_address = ? 
                AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)
            ");
            $stmt->execute([$ipAddress]);
            $uniqueAttempts = $stmt->fetchColumn();
            
            $suspiciousLevel = 'low';
            $reasons = [];
            
            if ($recentFailed >= 10) {
                $suspiciousLevel = 'high';
                $reasons[] = 'High number of failed login attempts';
            } elseif ($recentFailed >= 5) {
                $suspiciousLevel = 'medium';
                $reasons[] = 'Multiple failed login attempts';
            }
            
            if ($uniqueAttempts >= 5) {
                $suspiciousLevel = 'high';
                $reasons[] = 'Attempting to access multiple accounts';
            }
            
            return [
                'level' => $suspiciousLevel,
                'failed_attempts' => $recentFailed,
                'unique_attempts' => $uniqueAttempts,
                'reasons' => $reasons,
                'should_block' => $suspiciousLevel === 'high'
            ];
            
        } catch (PDOException $e) {
            error_log("Failed to analyze suspicious activity: " . $e->getMessage());
            return ['level' => 'unknown', 'should_block' => false];
        }
    }
    
    /**
     * Generate secure password reset token
     * @param PDO $pdo Database connection
     * @param int $userId User ID
     * @return string Reset token
     */
    public static function generatePasswordResetToken(PDO $pdo, $userId) {
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', time() + self::PASSWORD_RESET_EXPIRY);
        
        try {
            // Invalidate any existing tokens for this user
            $stmt = $pdo->prepare("
                UPDATE password_resets 
                SET used = 1 
                WHERE user_id = ? AND used = 0
            ");
            $stmt->execute([$userId]);
            
            // Create new reset token
            $stmt = $pdo->prepare("
                INSERT INTO password_resets (user_id, token, expires_at, created_at) 
                VALUES (?, ?, ?, NOW())
            ");
            $stmt->execute([$userId, $token, $expiry]);
            
            return $token;
            
        } catch (PDOException $e) {
            error_log("Failed to generate password reset token: " . $e->getMessage());
            throw new Exception("Unable to generate reset token");
        }
    }
    
    /**
     * Validate password reset token
     * @param PDO $pdo Database connection
     * @param string $token Reset token
     * @return array|null User data if token is valid
     */
    public static function validatePasswordResetToken(PDO $pdo, $token) {
        try {
            $stmt = $pdo->prepare("
                SELECT pr.user_id, pr.expires_at, u.email, u.username 
                FROM password_resets pr 
                JOIN users u ON pr.user_id = u.id 
                WHERE pr.token = ? 
                AND pr.used = 0 
                AND pr.expires_at > NOW()
            ");
            $stmt->execute([$token]);
            $result = $stmt->fetch();
            
            if (!$result) {
                return null;
            }
            
            return [
                'user_id' => $result['user_id'],
                'email' => $result['email'],
                'username' => $result['username'],
                'expires_at' => $result['expires_at']
            ];
            
        } catch (PDOException $e) {
            error_log("Failed to validate password reset token: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Mark password reset token as used
     * @param PDO $pdo Database connection
     * @param string $token Reset token
     */
    public static function markTokenAsUsed(PDO $pdo, $token) {
        try {
            $stmt = $pdo->prepare("
                UPDATE password_resets 
                SET used = 1, used_at = NOW() 
                WHERE token = ?
            ");
            $stmt->execute([$token]);
        } catch (PDOException $e) {
            error_log("Failed to mark token as used: " . $e->getMessage());
        }
    }
    
    /**
     * Generate secure email verification token
     * @return string Verification token
     */
    public static function generateEmailVerificationToken() {
        return bin2hex(random_bytes(32));
    }
    
    /**
     * Check account security status
     * @param PDO $pdo Database connection
     * @param int $userId User ID
     * @return array Security status
     */
    public static function getAccountSecurityStatus(PDO $pdo, $userId) {
        try {
            // Get user account details
            $stmt = $pdo->prepare("
                SELECT email_verified, created_at, last_login, login_attempts, 
                       account_locked_until, password_changed_at 
                FROM users 
                WHERE id = ?
            ");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            
            if (!$user) {
                return ['error' => 'User not found'];
            }
            
            // Calculate security score
            $score = 0;
            $recommendations = [];
            
            // Email verification
            if ($user['email_verified']) {
                $score += 20;
            } else {
                $recommendations[] = 'Verify your email address';
            }
            
            // Account age (older accounts are more trusted)
            $accountAge = time() - strtotime($user['created_at']);
            if ($accountAge > 30 * 24 * 3600) { // 30 days
                $score += 15;
            }
            
            // Recent login activity
            if ($user['last_login'] && (time() - strtotime($user['last_login'])) < 7 * 24 * 3600) {
                $score += 10;
            }
            
            // No recent lockouts
            if (!$user['account_locked_until'] || strtotime($user['account_locked_until']) < time()) {
                $score += 15;
            } else {
                $recommendations[] = 'Account is currently locked due to suspicious activity';
            }
            
            // Password age (recommend changing every 90 days)
            $passwordAge = time() - strtotime($user['password_changed_at'] ?? $user['created_at']);
            if ($passwordAge < 90 * 24 * 3600) {
                $score += 20;
            } else {
                $recommendations[] = 'Consider changing your password (last changed ' . 
                    round($passwordAge / (24 * 3600)) . ' days ago)';
            }
            
            // Recent login attempts
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as failed_attempts 
                FROM login_attempts 
                WHERE identifier = (SELECT email FROM users WHERE id = ?) 
                AND success = 0 
                AND created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)
            ");
            $stmt->execute([$userId]);
            $recentFailed = $stmt->fetchColumn();
            
            if ($recentFailed == 0) {
                $score += 20;
            } elseif ($recentFailed < 3) {
                $score += 10;
            } else {
                $recommendations[] = 'Recent failed login attempts detected';
            }
            
            // Determine security level
            $level = 'low';
            if ($score >= 80) {
                $level = 'excellent';
            } elseif ($score >= 60) {
                $level = 'good';
            } elseif ($score >= 40) {
                $level = 'fair';
            }
            
            return [
                'score' => $score,
                'level' => $level,
                'email_verified' => $user['email_verified'],
                'account_locked' => $user['account_locked_until'] && strtotime($user['account_locked_until']) > time(),
                'recent_failed_attempts' => $recentFailed,
                'recommendations' => $recommendations
            ];
            
        } catch (PDOException $e) {
            error_log("Failed to get account security status: " . $e->getMessage());
            return ['error' => 'Unable to fetch security status'];
        }
    }
    
    /**
     * Get client IP address (handles proxies)
     * @return string Client IP address
     */
    public static function getClientIP() {
        $headers = [
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_X_FORWARDED_FOR',      // Proxy
            'HTTP_X_REAL_IP',           // Nginx
            'REMOTE_ADDR'               // Direct connection
        ];
        
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];
                // Handle comma-separated list
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
}
?>