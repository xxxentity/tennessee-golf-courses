<?php
// Rate limiting functionality
class RateLimiter {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->createTableIfNotExists();
    }
    
    private function createTableIfNotExists() {
        try {
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS rate_limits (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    ip_address VARCHAR(45) NOT NULL,
                    action_type VARCHAR(50) NOT NULL,
                    attempt_count INT DEFAULT 1,
                    first_attempt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    last_attempt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    INDEX idx_ip_action (ip_address, action_type),
                    INDEX idx_last_attempt (last_attempt)
                )
            ");
        } catch (PDOException $e) {
            error_log("Rate limiter table creation failed: " . $e->getMessage());
        }
    }
    
    public function isAllowed($action_type, $max_attempts = 3, $time_window_hours = 1) {
        $ip_address = $this->getClientIP();
        
        try {
            // Clean old entries
            $this->cleanOldEntries($time_window_hours);
            
            // Check current attempts
            $stmt = $this->pdo->prepare("
                SELECT attempt_count, first_attempt 
                FROM rate_limits 
                WHERE ip_address = ? AND action_type = ? 
                AND last_attempt >= DATE_SUB(NOW(), INTERVAL ? HOUR)
            ");
            $stmt->execute([$ip_address, $action_type, $time_window_hours]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                // First attempt
                $this->recordAttempt($ip_address, $action_type);
                return true;
            }
            
            if ($result['attempt_count'] >= $max_attempts) {
                return false;
            }
            
            // Increment attempt count
            $this->recordAttempt($ip_address, $action_type);
            return true;
            
        } catch (PDOException $e) {
            error_log("Rate limiter check failed: " . $e->getMessage());
            return true; // Allow on error
        }
    }
    
    private function recordAttempt($ip_address, $action_type) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO rate_limits (ip_address, action_type, attempt_count) 
                VALUES (?, ?, 1)
                ON DUPLICATE KEY UPDATE 
                attempt_count = attempt_count + 1,
                last_attempt = CURRENT_TIMESTAMP
            ");
            $stmt->execute([$ip_address, $action_type]);
        } catch (PDOException $e) {
            error_log("Rate limiter record failed: " . $e->getMessage());
        }
    }
    
    private function cleanOldEntries($hours = 24) {
        try {
            $stmt = $this->pdo->prepare("
                DELETE FROM rate_limits 
                WHERE last_attempt < DATE_SUB(NOW(), INTERVAL ? HOUR)
            ");
            $stmt->execute([$hours]);
        } catch (PDOException $e) {
            error_log("Rate limiter cleanup failed: " . $e->getMessage());
        }
    }
    
    public function getRemainingAttempts($action_type, $max_attempts = 3, $time_window_hours = 1) {
        $ip_address = $this->getClientIP();
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT attempt_count 
                FROM rate_limits 
                WHERE ip_address = ? AND action_type = ? 
                AND last_attempt >= DATE_SUB(NOW(), INTERVAL ? HOUR)
            ");
            $stmt->execute([$ip_address, $action_type, $time_window_hours]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                return $max_attempts;
            }
            
            return max(0, $max_attempts - $result['attempt_count']);
            
        } catch (PDOException $e) {
            error_log("Rate limiter remaining check failed: " . $e->getMessage());
            return $max_attempts;
        }
    }
    
    private function getClientIP() {
        $ip_keys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
        
        foreach ($ip_keys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = trim(explode(',', $_SERVER[$key])[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }
}
?>