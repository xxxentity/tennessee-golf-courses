<?php
/**
 * Simple File-Based Caching System
 * Provides fast caching for database queries and expensive operations
 */

class Cache {
    private static $cacheDir = null;
    private static $defaultTTL = 3600; // 1 hour default
    
    /**
     * Initialize cache system
     */
    public static function init($cacheDir = null) {
        if ($cacheDir === null) {
            $cacheDir = __DIR__ . '/../cache';
        }
        
        self::$cacheDir = $cacheDir;
        
        // Create cache directory if it doesn't exist
        if (!is_dir(self::$cacheDir)) {
            mkdir(self::$cacheDir, 0755, true);
            
            // Add .htaccess to prevent direct access
            file_put_contents(self::$cacheDir . '/.htaccess', "Order Deny,Allow\nDeny from all");
        }
    }
    
    /**
     * Generate cache key from data
     */
    private static function generateKey($key) {
        return md5(serialize($key));
    }
    
    /**
     * Get cache file path
     */
    private static function getFilePath($key) {
        if (self::$cacheDir === null) {
            self::init();
        }
        
        $hashedKey = self::generateKey($key);
        return self::$cacheDir . '/' . $hashedKey . '.cache';
    }
    
    /**
     * Store data in cache
     */
    public static function set($key, $data, $ttl = null) {
        if ($ttl === null) {
            $ttl = self::$defaultTTL;
        }
        
        $cacheData = [
            'data' => $data,
            'expires' => time() + $ttl,
            'created' => time()
        ];
        
        $filePath = self::getFilePath($key);
        return file_put_contents($filePath, serialize($cacheData)) !== false;
    }
    
    /**
     * Get data from cache
     */
    public static function get($key, $default = null) {
        $filePath = self::getFilePath($key);
        
        if (!file_exists($filePath)) {
            return $default;
        }
        
        $content = file_get_contents($filePath);
        if ($content === false) {
            return $default;
        }
        
        $cacheData = unserialize($content);
        if ($cacheData === false) {
            // Invalid cache file, delete it
            unlink($filePath);
            return $default;
        }
        
        // Check if expired
        if (time() > $cacheData['expires']) {
            unlink($filePath);
            return $default;
        }
        
        return $cacheData['data'];
    }
    
    /**
     * Check if key exists and is not expired
     */
    public static function has($key) {
        return self::get($key, '__NOT_FOUND__') !== '__NOT_FOUND__';
    }
    
    /**
     * Delete cache entry
     */
    public static function delete($key) {
        $filePath = self::getFilePath($key);
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return true;
    }
    
    /**
     * Clear all cache
     */
    public static function clear() {
        if (self::$cacheDir === null) {
            self::init();
        }
        
        $files = glob(self::$cacheDir . '/*.cache');
        $deleted = 0;
        
        foreach ($files as $file) {
            if (unlink($file)) {
                $deleted++;
            }
        }
        
        return $deleted;
    }
    
    /**
     * Get cache statistics
     */
    public static function getStats() {
        if (self::$cacheDir === null) {
            self::init();
        }
        
        $files = glob(self::$cacheDir . '/*.cache');
        $totalSize = 0;
        $validFiles = 0;
        $expiredFiles = 0;
        
        foreach ($files as $file) {
            $size = filesize($file);
            $totalSize += $size;
            
            $content = file_get_contents($file);
            $cacheData = unserialize($content);
            
            if ($cacheData && time() <= $cacheData['expires']) {
                $validFiles++;
            } else {
                $expiredFiles++;
            }
        }
        
        return [
            'total_files' => count($files),
            'valid_files' => $validFiles,
            'expired_files' => $expiredFiles,
            'total_size' => $totalSize,
            'total_size_mb' => round($totalSize / 1024 / 1024, 2)
        ];
    }
    
    /**
     * Clean up expired cache files
     */
    public static function cleanup() {
        if (self::$cacheDir === null) {
            self::init();
        }
        
        $files = glob(self::$cacheDir . '/*.cache');
        $deleted = 0;
        
        foreach ($files as $file) {
            $content = file_get_contents($file);
            $cacheData = unserialize($content);
            
            if (!$cacheData || time() > $cacheData['expires']) {
                if (unlink($file)) {
                    $deleted++;
                }
            }
        }
        
        return $deleted;
    }
    
    /**
     * Cache a callable function result
     */
    public static function remember($key, $callback, $ttl = null) {
        $cached = self::get($key);
        
        if ($cached !== null) {
            return $cached;
        }
        
        $result = call_user_func($callback);
        self::set($key, $result, $ttl);
        
        return $result;
    }
}

// Initialize cache system
Cache::init();
?>