<?php
/**
 * Simple environment variable loader
 * Loads environment variables from .env file
 */

function loadEnv($file = '.env') {
    // Get the root directory (one level up from config)
    $envPath = dirname(__DIR__) . '/' . $file;
    
    if (!file_exists($envPath)) {
        throw new Exception('.env file not found at: ' . $envPath);
    }
    
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // Skip comments
        if (strpos($line, '#') === 0) {
            continue;
        }
        
        // Parse KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes if present
            if (preg_match('/^["\'](.*)["\'](.*)/s', $value, $matches)) {
                $value = $matches[1] . $matches[2];
            }
            
            // Set environment variable if not already set
            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
}

/**
 * Get environment variable with optional default
 */
function env($key, $default = null) {
    return $_ENV[$key] ?? getenv($key) ?: $default;
}

// Load environment variables
try {
    loadEnv();
} catch (Exception $e) {
    // In production, you might want to handle this differently
    error_log('Failed to load .env file: ' . $e->getMessage());
}
?>