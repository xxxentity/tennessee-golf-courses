<?php
/**
 * Database Security Helper
 * Provides additional security utilities for database operations
 */

class DatabaseSecurity {
    
    /**
     * Validate and sanitize input for database operations
     * @param mixed $input The input to validate
     * @param string $type Expected data type (string, int, email, etc.)
     * @param array $options Additional validation options
     * @return mixed Sanitized input
     * @throws InvalidArgumentException if validation fails
     */
    public static function validateInput($input, $type, $options = []) {
        switch ($type) {
            case 'int':
            case 'integer':
                $filtered = filter_var($input, FILTER_VALIDATE_INT);
                if ($filtered === false) {
                    throw new InvalidArgumentException('Invalid integer value');
                }
                
                // Check range if specified
                if (isset($options['min']) && $filtered < $options['min']) {
                    throw new InvalidArgumentException('Value below minimum allowed');
                }
                if (isset($options['max']) && $filtered > $options['max']) {
                    throw new InvalidArgumentException('Value above maximum allowed');
                }
                
                return $filtered;
                
            case 'email':
                $filtered = filter_var($input, FILTER_VALIDATE_EMAIL);
                if ($filtered === false) {
                    throw new InvalidArgumentException('Invalid email format');
                }
                return $filtered;
                
            case 'url':
                $filtered = filter_var($input, FILTER_VALIDATE_URL);
                if ($filtered === false) {
                    throw new InvalidArgumentException('Invalid URL format');
                }
                return $filtered;
                
            case 'string':
                $sanitized = trim($input);
                
                // Check length if specified
                if (isset($options['max_length']) && strlen($sanitized) > $options['max_length']) {
                    throw new InvalidArgumentException('String exceeds maximum length');
                }
                if (isset($options['min_length']) && strlen($sanitized) < $options['min_length']) {
                    throw new InvalidArgumentException('String below minimum length');
                }
                
                // Check for required content
                if (isset($options['required']) && $options['required'] && empty($sanitized)) {
                    throw new InvalidArgumentException('Required field cannot be empty');
                }
                
                return $sanitized;
                
            case 'slug':
                // For URL-safe slugs
                $sanitized = trim($input);
                if (!preg_match('/^[a-z0-9\-]+$/i', $sanitized)) {
                    throw new InvalidArgumentException('Invalid slug format');
                }
                return strtolower($sanitized);
                
            case 'rating':
                $rating = filter_var($input, FILTER_VALIDATE_INT);
                if ($rating === false || $rating < 1 || $rating > 5) {
                    throw new InvalidArgumentException('Rating must be between 1 and 5');
                }
                return $rating;
                
            default:
                throw new InvalidArgumentException('Unknown validation type: ' . $type);
        }
    }
    
    /**
     * Secure query builder to prevent SQL injection
     * @param PDO $pdo Database connection
     * @param string $query Query with placeholders
     * @param array $params Parameters to bind
     * @param string $operation Operation type (SELECT, INSERT, UPDATE, DELETE)
     * @return PDOStatement
     */
    public static function executeSecureQuery(PDO $pdo, $query, $params = [], $operation = 'SELECT') {
        // Validate query structure
        self::validateQueryStructure($query, $operation);
        
        // Log the operation for security auditing
        self::logDatabaseOperation($operation, $query, count($params));
        
        try {
            $stmt = $pdo->prepare($query);
            
            if (!$stmt) {
                throw new Exception('Failed to prepare statement');
            }
            
            $stmt->execute($params);
            return $stmt;
            
        } catch (PDOException $e) {
            // Log error without exposing sensitive information
            error_log("Database operation failed: " . $e->getMessage());
            throw new Exception('Database operation failed');
        }
    }
    
    /**
     * Validate query structure for security
     */
    private static function validateQueryStructure($query, $operation) {
        // Check that query starts with expected operation
        $trimmedQuery = trim(strtoupper($query));
        if (!str_starts_with($trimmedQuery, strtoupper($operation))) {
            throw new InvalidArgumentException('Query does not match expected operation');
        }
        
        // Check for proper parameter placeholders
        $paramCount = substr_count($query, '?');
        $namedParamCount = preg_match_all('/:\w+/', $query);
        
        if ($paramCount === 0 && $namedParamCount === 0) {
            // Static queries are OK (like SELECT 1, CREATE TABLE, etc.)
            return;
        }
        
        // Check for potential injection patterns
        $dangerousPatterns = [
            '/;\s*(?:DROP|DELETE|INSERT|UPDATE|CREATE|ALTER)/i',
            '/UNION\s+(?:ALL\s+)?SELECT/i',
            '/\/\*.*?\*\//s',
            '/--.*$/m'
        ];
        
        foreach ($dangerousPatterns as $pattern) {
            if (preg_match($pattern, $query)) {
                throw new InvalidArgumentException('Query contains potentially dangerous patterns');
            }
        }
    }
    
    /**
     * Log database operations for security auditing
     */
    private static function logDatabaseOperation($operation, $query, $paramCount) {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'operation' => $operation,
            'query_hash' => hash('sha256', $query),
            'param_count' => $paramCount,
            'user_id' => $_SESSION['user_id'] ?? 'anonymous',
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ];
        
        // Log to security audit file
        $logEntry = json_encode($logData) . "\n";
        error_log($logEntry, 3, __DIR__ . '/../security-audit/database-operations.log');
    }
    
    /**
     * Validate and execute a SELECT query safely
     * @param PDO $pdo Database connection
     * @param string $query SELECT query with placeholders
     * @param array $params Parameters to bind
     * @return array Results
     */
    public static function selectQuery(PDO $pdo, $query, $params = []) {
        $stmt = self::executeSecureQuery($pdo, $query, $params, 'SELECT');
        return $stmt->fetchAll();
    }
    
    /**
     * Validate and execute an INSERT query safely
     * @param PDO $pdo Database connection
     * @param string $query INSERT query with placeholders
     * @param array $params Parameters to bind
     * @return int Last insert ID
     */
    public static function insertQuery(PDO $pdo, $query, $params = []) {
        self::executeSecureQuery($pdo, $query, $params, 'INSERT');
        return $pdo->lastInsertId();
    }
    
    /**
     * Validate and execute an UPDATE query safely
     * @param PDO $pdo Database connection
     * @param string $query UPDATE query with placeholders
     * @param array $params Parameters to bind
     * @return int Number of affected rows
     */
    public static function updateQuery(PDO $pdo, $query, $params = []) {
        $stmt = self::executeSecureQuery($pdo, $query, $params, 'UPDATE');
        return $stmt->rowCount();
    }
    
    /**
     * Validate and execute a DELETE query safely
     * @param PDO $pdo Database connection
     * @param string $query DELETE query with placeholders
     * @param array $params Parameters to bind
     * @return int Number of affected rows
     */
    public static function deleteQuery(PDO $pdo, $query, $params = []) {
        $stmt = self::executeSecureQuery($pdo, $query, $params, 'DELETE');
        return $stmt->rowCount();
    }
    
    /**
     * Escape output for safe HTML display
     * @param string $string String to escape
     * @return string Escaped string
     */
    public static function escapeOutput($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Generate a security audit report
     * @return array Security metrics
     */
    public static function generateSecurityReport() {
        $logFile = __DIR__ . '/../security-audit/database-operations.log';
        
        if (!file_exists($logFile)) {
            return ['error' => 'No audit log found'];
        }
        
        $lines = file($logFile, FILE_IGNORE_NEW_LINES);
        $operations = [];
        $userActivity = [];
        $ipActivity = [];
        
        foreach ($lines as $line) {
            $data = json_decode($line, true);
            if ($data) {
                $operations[$data['operation']] = ($operations[$data['operation']] ?? 0) + 1;
                $userActivity[$data['user_id']] = ($userActivity[$data['user_id']] ?? 0) + 1;
                $ipActivity[$data['ip_address']] = ($ipActivity[$data['ip_address']] ?? 0) + 1;
            }
        }
        
        return [
            'total_operations' => count($lines),
            'operations_by_type' => $operations,
            'top_users' => array_slice(arsort($userActivity) ? $userActivity : [], 0, 10, true),
            'top_ips' => array_slice(arsort($ipActivity) ? $ipActivity : [], 0, 10, true),
            'last_24_hours' => self::getRecentActivity($lines, 24)
        ];
    }
    
    /**
     * Get recent activity from logs
     */
    private static function getRecentActivity($lines, $hours) {
        $cutoff = time() - ($hours * 3600);
        $recentCount = 0;
        
        foreach ($lines as $line) {
            $data = json_decode($line, true);
            if ($data && strtotime($data['timestamp']) > $cutoff) {
                $recentCount++;
            }
        }
        
        return $recentCount;
    }
}
?>