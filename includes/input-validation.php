<?php
/**
 * Comprehensive Input Validation and Sanitization System
 * Provides robust protection against various injection attacks
 */

class InputValidator {
    
    // Validation rules
    const EMAIL_PATTERN = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    const PHONE_PATTERN = '/^[\+]?[1-9][\d]{0,15}$/';
    const USERNAME_PATTERN = '/^[a-zA-Z0-9_.-]{3,30}$/';
    const NAME_PATTERN = '/^[a-zA-Z\s\'-]{2,50}$/';
    const URL_PATTERN = '/^https?:\/\/[^\s<>"]{2,2048}$/';
    
    // Dangerous patterns to detect
    const XSS_PATTERNS = [
        '/<script[^>]*>.*?<\/script>/is',
        '/<iframe[^>]*>.*?<\/iframe>/is',
        '/javascript:/i',
        '/vbscript:/i',
        '/onload=/i',
        '/onerror=/i',
        '/onclick=/i',
        '/onmouseover=/i',
        '/<object[^>]*>.*?<\/object>/is',
        '/<embed[^>]*>.*?<\/embed>/is',
        '/<applet[^>]*>.*?<\/applet>/is',
        '/<meta[^>]*>/is',
        '/<link[^>]*>/is'
    ];
    
    const SQL_PATTERNS = [
        '/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|UNION|SCRIPT)\s)/i',
        '/(\b(OR|AND)\s+[\'"]*\d+[\'"]*\s*=\s*[\'"]*\d+[\'"]*)/i',
        '/([\'"]\s*(OR|AND)\s+[\'"]\w+[\'"]\s*=\s*[\'"]\w+[\'"]\s*)/i',
        '/(\-\-\s|\/\*.*\*\/|\#\s)/i', // More specific: require space after -- or # 
        '/(char\s*\(\s*\d+\s*\))/i',
        '/(0x[0-9a-f]+)/i',
        '/([\'"].*[\'"].*=.*[\'"])/i' // Quote-based injection patterns
    ];
    
    /**
     * Sanitize string input for general use
     * @param string $input Raw input
     * @param array $options Sanitization options
     * @return string Sanitized input
     */
    public static function sanitizeString($input, $options = []) {
        if (!is_string($input)) {
            return '';
        }
        
        // Default options
        $defaults = [
            'trim' => true,
            'strip_tags' => true,
            'decode_entities' => true,
            'remove_control_chars' => true,
            'max_length' => 1000,
            'allow_html' => false,
            'preserve_newlines' => false
        ];
        
        $options = array_merge($defaults, $options);
        
        // Trim whitespace
        if ($options['trim']) {
            $input = trim($input);
        }
        
        // Decode HTML entities first
        if ($options['decode_entities']) {
            $input = html_entity_decode($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        
        // Remove or escape HTML tags
        if (!$options['allow_html'] && $options['strip_tags']) {
            $input = strip_tags($input);
        }
        
        // Remove control characters (except newlines if preserved)
        if ($options['remove_control_chars']) {
            if ($options['preserve_newlines']) {
                $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);
            } else {
                $input = preg_replace('/[\x00-\x1F\x7F]/', '', $input);
            }
        }
        
        // Normalize whitespace
        $input = preg_replace('/\s+/', ' ', $input);
        
        // Truncate to max length
        if ($options['max_length'] > 0 && strlen($input) > $options['max_length']) {
            $input = substr($input, 0, $options['max_length']);
        }
        
        return $input;
    }
    
    /**
     * Sanitize HTML content (for rich text areas)
     * @param string $input Raw HTML input
     * @param array $allowedTags Allowed HTML tags
     * @return string Sanitized HTML
     */
    public static function sanitizeHTML($input, $allowedTags = []) {
        if (!is_string($input)) {
            return '';
        }
        
        // Default allowed tags for basic formatting
        $defaultTags = ['p', 'br', 'strong', 'em', 'u', 'ol', 'ul', 'li', 'a', 'h3', 'h4'];
        $allowedTags = !empty($allowedTags) ? $allowedTags : $defaultTags;
        
        // Create allowed tags string for strip_tags
        $allowedTagsString = '<' . implode('><', $allowedTags) . '>';
        
        // Strip disallowed tags
        $input = strip_tags($input, $allowedTagsString);
        
        // Remove dangerous attributes
        $input = preg_replace('/\s(on\w+|javascript:|vbscript:|data:|style)=["\'][^"\']*["\']/i', '', $input);
        
        // Remove script and other dangerous tags completely
        foreach (self::XSS_PATTERNS as $pattern) {
            $input = preg_replace($pattern, '', $input);
        }
        
        return trim($input);
    }
    
    /**
     * Validate email address
     * @param string $email Email to validate
     * @param bool $checkDNS Whether to check DNS records
     * @return array Validation result
     */
    public static function validateEmail($email, $checkDNS = false) {
        $email = self::sanitizeString($email, ['max_length' => 254]);
        
        $result = [
            'valid' => false,
            'email' => $email,
            'errors' => []
        ];
        
        // Basic format validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result['errors'][] = 'Invalid email format';
            return $result;
        }
        
        // Pattern validation
        if (!preg_match(self::EMAIL_PATTERN, $email)) {
            $result['errors'][] = 'Email contains invalid characters';
            return $result;
        }
        
        // Length validation
        list($local, $domain) = explode('@', $email);
        if (strlen($local) > 64 || strlen($domain) > 253) {
            $result['errors'][] = 'Email address too long';
            return $result;
        }
        
        // DNS validation (optional)
        if ($checkDNS) {
            if (!checkdnsrr($domain, 'MX') && !checkdnsrr($domain, 'A')) {
                $result['errors'][] = 'Domain does not exist';
                return $result;
            }
        }
        
        // Check for suspicious patterns
        $suspiciousPatterns = [
            '/\+.*\+/',  // Multiple plus signs
            '/\.{2,}/',  // Multiple consecutive dots
            '/[<>"]/',   // Angle brackets or quotes
        ];
        
        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $email)) {
                $result['errors'][] = 'Email contains suspicious patterns';
                return $result;
            }
        }
        
        $result['valid'] = true;
        return $result;
    }
    
    /**
     * Validate username
     * @param string $username Username to validate
     * @return array Validation result
     */
    public static function validateUsername($username) {
        $username = self::sanitizeString($username, ['max_length' => 30]);
        
        $result = [
            'valid' => false,
            'username' => $username,
            'errors' => []
        ];
        
        // Length check
        if (strlen($username) < 3) {
            $result['errors'][] = 'Username must be at least 3 characters long';
        }
        
        if (strlen($username) > 30) {
            $result['errors'][] = 'Username must be no more than 30 characters long';
        }
        
        // Pattern validation
        if (!preg_match(self::USERNAME_PATTERN, $username)) {
            $result['errors'][] = 'Username can only contain letters, numbers, periods, hyphens, and underscores';
        }
        
        // Reserved words check
        $reservedWords = [
            'admin', 'administrator', 'root', 'system', 'user', 'guest', 'anonymous',
            'null', 'undefined', 'test', 'demo', 'example', 'sample', 'default',
            'api', 'www', 'ftp', 'mail', 'email', 'support', 'help', 'info',
            'login', 'register', 'signup', 'signin', 'logout', 'profile', 'account'
        ];
        
        if (in_array(strtolower($username), $reservedWords)) {
            $result['errors'][] = 'Username is reserved and cannot be used';
        }
        
        // Check for suspicious patterns
        if (preg_match('/^[0-9]+$/', $username)) {
            $result['errors'][] = 'Username cannot be all numbers';
        }
        
        if (empty($result['errors'])) {
            $result['valid'] = true;
        }
        
        return $result;
    }
    
    /**
     * Validate name (first name, last name)
     * @param string $name Name to validate
     * @param string $type Type of name (first, last, full)
     * @return array Validation result
     */
    public static function validateName($name, $type = 'first') {
        $name = self::sanitizeString($name, ['max_length' => 50, 'preserve_newlines' => false]);
        
        $result = [
            'valid' => false,
            'name' => $name,
            'errors' => []
        ];
        
        // Length validation
        if (strlen($name) < 2) {
            $result['errors'][] = ucfirst($type) . ' name must be at least 2 characters long';
        }
        
        if (strlen($name) > 50) {
            $result['errors'][] = ucfirst($type) . ' name must be no more than 50 characters long';
        }
        
        // Pattern validation - allow letters, spaces, hyphens, apostrophes
        if (!preg_match(self::NAME_PATTERN, $name)) {
            $result['errors'][] = ucfirst($type) . ' name can only contain letters, spaces, hyphens, and apostrophes';
        }
        
        // Check for suspicious patterns
        $suspiciousPatterns = [
            '/\s{2,}/',     // Multiple spaces
            '/^[\s\'-]+$/', // Only special characters
            '/[0-9]/',      // Numbers
            '/[<>@#$%^&*()_+={}[\]\\|;:".,?\/]/' // Special characters
        ];
        
        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $name)) {
                $result['errors'][] = ucfirst($type) . ' name contains invalid characters or patterns';
                break;
            }
        }
        
        if (empty($result['errors'])) {
            $result['valid'] = true;
        }
        
        return $result;
    }
    
    /**
     * Validate and sanitize text content (comments, descriptions)
     * @param string $text Text to validate
     * @param int $minLength Minimum length
     * @param int $maxLength Maximum length
     * @param bool $allowHTML Whether to allow HTML
     * @return array Validation result
     */
    public static function validateText($text, $minLength = 1, $maxLength = 5000, $allowHTML = false) {
        $originalText = $text;
        
        if ($allowHTML) {
            $text = self::sanitizeHTML($text);
        } else {
            $text = self::sanitizeString($text, [
                'preserve_newlines' => true,
                'max_length' => $maxLength
            ]);
        }
        
        $result = [
            'valid' => false,
            'text' => $text,
            'original' => $originalText,
            'errors' => []
        ];
        
        // Length validation
        $textLength = strlen(trim(strip_tags($text)));
        
        if ($textLength < $minLength) {
            $result['errors'][] = "Text must be at least {$minLength} characters long";
        }
        
        if ($textLength > $maxLength) {
            $result['errors'][] = "Text must be no more than {$maxLength} characters long";
        }
        
        // Check for potential XSS
        if (self::containsXSS($originalText)) {
            $result['errors'][] = 'Text contains potentially dangerous content';
        }
        
        // Check for potential SQL injection
        if (self::containsSQLInjection($originalText)) {
            $result['errors'][] = 'Text contains potentially dangerous patterns';
        }
        
        if (empty($result['errors'])) {
            $result['valid'] = true;
        }
        
        return $result;
    }
    
    /**
     * Validate URL
     * @param string $url URL to validate
     * @param bool $requireHTTPS Whether to require HTTPS
     * @return array Validation result
     */
    public static function validateURL($url, $requireHTTPS = false) {
        $url = self::sanitizeString($url, ['max_length' => 2048]);
        
        $result = [
            'valid' => false,
            'url' => $url,
            'errors' => []
        ];
        
        // Basic validation
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $result['errors'][] = 'Invalid URL format';
            return $result;
        }
        
        // Pattern validation
        if (!preg_match(self::URL_PATTERN, $url)) {
            $result['errors'][] = 'URL contains invalid characters';
            return $result;
        }
        
        // HTTPS requirement
        if ($requireHTTPS && !preg_match('/^https:\/\//', $url)) {
            $result['errors'][] = 'URL must use HTTPS';
            return $result;
        }
        
        // Check for suspicious patterns
        $suspiciousPatterns = [
            '/javascript:/i',
            '/data:/i',
            '/vbscript:/i',
            '/file:/i',
            '/ftp:/i'
        ];
        
        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $url)) {
                $result['errors'][] = 'URL scheme not allowed';
                return $result;
            }
        }
        
        if (empty($result['errors'])) {
            $result['valid'] = true;
        }
        
        return $result;
    }
    
    /**
     * Check for XSS patterns
     * @param string $input Input to check
     * @return bool True if XSS detected
     */
    public static function containsXSS($input) {
        if (!is_string($input)) {
            return false;
        }
        
        foreach (self::XSS_PATTERNS as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check for SQL injection patterns
     * @param string $input Input to check
     * @return bool True if SQL injection detected
     */
    public static function containsSQLInjection($input) {
        if (!is_string($input)) {
            return false;
        }
        
        foreach (self::SQL_PATTERNS as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Sanitize array of inputs
     * @param array $inputs Array of inputs
     * @param array $rules Validation rules for each field
     * @return array Sanitized inputs with validation results
     */
    public static function validateArray($inputs, $rules = []) {
        $results = [
            'valid' => true,
            'data' => [],
            'errors' => []
        ];
        
        foreach ($inputs as $key => $value) {
            $fieldRules = $rules[$key] ?? ['type' => 'string'];
            
            switch ($fieldRules['type']) {
                case 'email':
                    $validation = self::validateEmail($value, $fieldRules['check_dns'] ?? false);
                    break;
                    
                case 'username':
                    $validation = self::validateUsername($value);
                    break;
                    
                case 'name':
                    $validation = self::validateName($value, $fieldRules['name_type'] ?? 'first');
                    break;
                    
                case 'text':
                    $validation = self::validateText(
                        $value,
                        $fieldRules['min_length'] ?? 1,
                        $fieldRules['max_length'] ?? 5000,
                        $fieldRules['allow_html'] ?? false
                    );
                    break;
                    
                case 'url':
                    $validation = self::validateURL($value, $fieldRules['require_https'] ?? false);
                    break;
                    
                default:
                    $validation = [
                        'valid' => true,
                        'errors' => [],
                        $fieldRules['type'] => self::sanitizeString($value, $fieldRules['options'] ?? [])
                    ];
                    break;
            }
            
            $results['data'][$key] = $validation[$fieldRules['type']] ?? $validation['text'] ?? $validation['name'] ?? $validation['username'] ?? $validation['email'] ?? $validation['url'] ?? $value;
            
            if (!$validation['valid']) {
                $results['valid'] = false;
                $results['errors'][$key] = $validation['errors'];
            }
        }
        
        return $results;
    }
    
    /**
     * Generate CSRF-safe output
     * @param string $content Content to output
     * @param bool $preserveHTML Whether to preserve HTML
     * @return string Safe output
     */
    public static function safeOutput($content, $preserveHTML = false) {
        if (!is_string($content)) {
            return '';
        }
        
        if ($preserveHTML) {
            // For content that should preserve HTML but be XSS-safe
            return self::sanitizeHTML($content);
        } else {
            // For plain text output
            return htmlspecialchars($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
    }
}
?>