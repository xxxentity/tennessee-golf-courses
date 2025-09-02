<?php
/**
 * Output Security Helper
 * Provides secure output functions to prevent XSS attacks
 */

class OutputSecurity {
    
    /**
     * Escape HTML output for safe display
     * @param string $content Content to escape
     * @param bool $preserveLineBreaks Whether to convert \n to <br>
     * @return string Escaped content
     */
    public static function escape($content, $preserveLineBreaks = false) {
        if (!is_string($content)) {
            return '';
        }
        
        $escaped = htmlspecialchars($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        if ($preserveLineBreaks) {
            $escaped = nl2br($escaped);
        }
        
        return $escaped;
    }
    
    /**
     * Escape and truncate content for safe display
     * @param string $content Content to escape
     * @param int $maxLength Maximum length
     * @param string $suffix Suffix to add if truncated
     * @return string Escaped and truncated content
     */
    public static function escapeAndTruncate($content, $maxLength = 100, $suffix = '...') {
        if (!is_string($content)) {
            return '';
        }
        
        // First escape, then truncate
        $escaped = self::escape($content);
        
        if (strlen($escaped) > $maxLength) {
            $escaped = substr($escaped, 0, $maxLength - strlen($suffix)) . $suffix;
        }
        
        return $escaped;
    }
    
    /**
     * Escape content for use in HTML attributes
     * @param string $content Content to escape
     * @return string Escaped content
     */
    public static function escapeAttribute($content) {
        if (!is_string($content)) {
            return '';
        }
        
        return htmlspecialchars($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    
    /**
     * Escape content for use in JavaScript
     * @param string $content Content to escape
     * @return string Escaped content
     */
    public static function escapeJS($content) {
        if (!is_string($content)) {
            return '';
        }
        
        // Escape for JavaScript string context
        $content = str_replace(['\\', "'", '"', "\n", "\r", "\t"], 
                              ['\\\\', "\\'", '\\"', '\\n', '\\r', '\\t'], $content);
        
        return $content;
    }
    
    /**
     * Escape content for use in JSON
     * @param mixed $content Content to escape
     * @return string JSON encoded content
     */
    public static function escapeJSON($content) {
        return json_encode($content, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * Sanitize and display user-generated content safely
     * @param string $content User content
     * @param bool $allowBasicHTML Whether to allow basic HTML tags
     * @return string Safe HTML content
     */
    public static function displayUserContent($content, $allowBasicHTML = false) {
        if (!is_string($content)) {
            return '';
        }
        
        if ($allowBasicHTML) {
            // Use InputValidator to sanitize HTML
            require_once __DIR__ . '/input-validation.php';
            return InputValidator::sanitizeHTML($content);
        } else {
            return self::escape($content, true);
        }
    }
    
    /**
     * Create safe HTML for links
     * @param string $url URL to link to
     * @param string $text Link text
     * @param array $attributes Additional attributes
     * @return string Safe HTML link
     */
    public static function createSafeLink($url, $text, $attributes = []) {
        require_once __DIR__ . '/input-validation.php';
        
        // Validate URL
        $urlValidation = InputValidator::validateURL($url, false);
        if (!$urlValidation['valid']) {
            return self::escape($text);
        }
        
        $escapedUrl = self::escapeAttribute($url);
        $escapedText = self::escape($text);
        
        // Build attributes string
        $attrString = '';
        foreach ($attributes as $key => $value) {
            $attrString .= ' ' . self::escapeAttribute($key) . '="' . self::escapeAttribute($value) . '"';
        }
        
        // Add security attributes for external links
        if (!self::isInternalURL($url)) {
            $attrString .= ' rel="noopener noreferrer" target="_blank"';
        }
        
        return '<a href="' . $escapedUrl . '"' . $attrString . '>' . $escapedText . '</a>';
    }
    
    /**
     * Check if URL is internal to this site
     * @param string $url URL to check
     * @return bool True if internal
     */
    private static function isInternalURL($url) {
        $currentHost = $_SERVER['HTTP_HOST'] ?? '';
        $parsedUrl = parse_url($url);
        
        // If no host in URL, it's relative (internal)
        if (!isset($parsedUrl['host'])) {
            return true;
        }
        
        // Check if hosts match
        return $parsedUrl['host'] === $currentHost;
    }
    
    /**
     * Display safe error messages
     * @param string|array $errors Error message(s)
     * @param string $cssClass CSS class for styling
     * @return string Safe HTML error display
     */
    public static function displayErrors($errors, $cssClass = 'error-message') {
        if (empty($errors)) {
            return '';
        }
        
        if (is_string($errors)) {
            $errors = [$errors];
        }
        
        $output = '<div class="' . self::escapeAttribute($cssClass) . '">';
        
        if (count($errors) === 1) {
            $output .= self::escape($errors[0]);
        } else {
            $output .= '<ul>';
            foreach ($errors as $error) {
                $output .= '<li>' . self::escape($error) . '</li>';
            }
            $output .= '</ul>';
        }
        
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Display safe success messages
     * @param string $message Success message
     * @param string $cssClass CSS class for styling
     * @return string Safe HTML success display
     */
    public static function displaySuccess($message, $cssClass = 'success-message') {
        if (empty($message)) {
            return '';
        }
        
        return '<div class="' . self::escapeAttribute($cssClass) . '">' . self::escape($message) . '</div>';
    }
    
    /**
     * Create safe form input with value
     * @param string $type Input type
     * @param string $name Input name
     * @param string $value Input value
     * @param array $attributes Additional attributes
     * @return string Safe HTML input
     */
    public static function createSafeInput($type, $name, $value = '', $attributes = []) {
        $output = '<input type="' . self::escapeAttribute($type) . '"';
        $output .= ' name="' . self::escapeAttribute($name) . '"';
        $output .= ' value="' . self::escapeAttribute($value) . '"';
        
        foreach ($attributes as $key => $val) {
            $output .= ' ' . self::escapeAttribute($key) . '="' . self::escapeAttribute($val) . '"';
        }
        
        $output .= '>';
        
        return $output;
    }
    
    /**
     * Create safe textarea with content
     * @param string $name Textarea name
     * @param string $content Textarea content
     * @param array $attributes Additional attributes
     * @return string Safe HTML textarea
     */
    public static function createSafeTextarea($name, $content = '', $attributes = []) {
        $output = '<textarea name="' . self::escapeAttribute($name) . '"';
        
        foreach ($attributes as $key => $val) {
            $output .= ' ' . self::escapeAttribute($key) . '="' . self::escapeAttribute($val) . '"';
        }
        
        $output .= '>' . self::escape($content) . '</textarea>';
        
        return $output;
    }
    
    /**
     * Create safe select options
     * @param array $options Array of value => text options
     * @param string $selected Currently selected value
     * @return string Safe HTML options
     */
    public static function createSafeOptions($options, $selected = '') {
        $output = '';
        
        foreach ($options as $value => $text) {
            $output .= '<option value="' . self::escapeAttribute($value) . '"';
            
            if ($value == $selected) {
                $output .= ' selected';
            }
            
            $output .= '>' . self::escape($text) . '</option>';
        }
        
        return $output;
    }
}

// Convenience functions for common operations
if (!function_exists('esc')) {
    /**
     * Shorthand function for escaping output
     * @param string $content Content to escape
     * @return string Escaped content
     */
    function esc($content) {
        return OutputSecurity::escape($content);
    }
}

if (!function_exists('esc_attr')) {
    /**
     * Shorthand function for escaping attributes
     * @param string $content Content to escape
     * @return string Escaped content
     */
    function esc_attr($content) {
        return OutputSecurity::escapeAttribute($content);
    }
}

if (!function_exists('esc_js')) {
    /**
     * Shorthand function for escaping JavaScript
     * @param string $content Content to escape
     * @return string Escaped content
     */
    function esc_js($content) {
        return OutputSecurity::escapeJS($content);
    }
}
?>