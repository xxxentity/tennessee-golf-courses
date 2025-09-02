<?php
/**
 * Database Caching Layer
 * Adds intelligent caching to database queries for improved performance
 */

require_once 'cache.php';
require_once 'performance.php';

class DatabaseCache {
    private $pdo;
    private $cacheEnabled = true;
    private $defaultTTL = 1800; // 30 minutes
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Execute a cached SELECT query
     */
    public function cachedQuery($sql, $params = [], $ttl = null) {
        if (!$this->cacheEnabled || !$this->isCacheable($sql)) {
            return $this->executeQuery($sql, $params);
        }
        
        if ($ttl === null) {
            $ttl = $this->defaultTTL;
        }
        
        // Generate cache key from SQL and parameters
        $cacheKey = [
            'type' => 'query',
            'sql' => $sql,
            'params' => $params
        ];
        
        return Cache::remember($cacheKey, function() use ($sql, $params) {
            return $this->executeQuery($sql, $params);
        }, $ttl);
    }
    
    /**
     * Execute query and track performance
     */
    private function executeQuery($sql, $params = []) {
        $startTime = microtime(true);
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $executionTime = microtime(true) - $startTime;
            Performance::trackQuery($sql, $executionTime, count($results));
            
            return $results;
            
        } catch (PDOException $e) {
            $executionTime = microtime(true) - $startTime;
            Performance::trackQuery($sql, $executionTime, 0);
            throw $e;
        }
    }
    
    /**
     * Check if query is cacheable
     */
    private function isCacheable($sql) {
        $sql = strtoupper(trim($sql));
        
        // Only cache SELECT queries
        if (strpos($sql, 'SELECT') !== 0) {
            return false;
        }
        
        // Don't cache queries with certain functions
        $nonCacheableFunctions = ['NOW()', 'RAND()', 'CURRENT_TIMESTAMP', 'UUID()'];
        foreach ($nonCacheableFunctions as $func) {
            if (strpos($sql, $func) !== false) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Get all golf courses with caching
     */
    public function getAllCourses($ttl = 3600) {
        return $this->cachedQuery("
            SELECT * FROM golf_courses 
            WHERE status = 'active' 
            ORDER BY name ASC
        ", [], $ttl);
    }
    
    /**
     * Get course by slug with caching
     */
    public function getCourseBySlug($slug, $ttl = 3600) {
        $results = $this->cachedQuery("
            SELECT * FROM golf_courses 
            WHERE slug = ? AND status = 'active'
            LIMIT 1
        ", [$slug], $ttl);
        
        return $results ? $results[0] : null;
    }
    
    /**
     * Get recent news articles with caching
     */
    public function getRecentNews($limit = 10, $ttl = 1800) {
        return $this->cachedQuery("
            SELECT * FROM news_articles 
            WHERE status = 'published' 
            ORDER BY published_date DESC 
            LIMIT ?
        ", [$limit], $ttl);
    }
    
    /**
     * Get user by ID with caching
     */
    public function getUserById($userId, $ttl = 600) {
        $results = $this->cachedQuery("
            SELECT id, username, email, first_name, last_name, is_active 
            FROM users 
            WHERE id = ? AND is_active = 1
            LIMIT 1
        ", [$userId], $ttl);
        
        return $results ? $results[0] : null;
    }
    
    /**
     * Search courses with caching
     */
    public function searchCourses($searchTerm, $limit = 20, $ttl = 1800) {
        return $this->cachedQuery("
            SELECT * FROM golf_courses 
            WHERE (name LIKE ? OR location LIKE ? OR description LIKE ?) 
            AND status = 'active'
            ORDER BY name ASC 
            LIMIT ?
        ", ["%$searchTerm%", "%$searchTerm%", "%$searchTerm%", $limit], $ttl);
    }
    
    /**
     * Get course statistics with caching
     */
    public function getCourseStats($ttl = 7200) {
        return $this->cachedQuery("
            SELECT 
                COUNT(*) as total_courses,
                AVG(rating) as avg_rating,
                COUNT(DISTINCT location) as total_locations
            FROM golf_courses 
            WHERE status = 'active'
        ", [], $ttl);
    }
    
    /**
     * Invalidate cache for specific patterns
     */
    public function invalidateCache($pattern = null) {
        if ($pattern === null) {
            return Cache::clear();
        }
        
        // For now, just clear all cache
        // Could be enhanced to selectively clear based on patterns
        return Cache::clear();
    }
    
    /**
     * Get cache statistics
     */
    public function getCacheStats() {
        return Cache::getStats();
    }
    
    /**
     * Clean up expired cache
     */
    public function cleanupCache() {
        return Cache::cleanup();
    }
    
    /**
     * Enable/disable caching
     */
    public function setCacheEnabled($enabled) {
        $this->cacheEnabled = $enabled;
    }
    
    /**
     * Set default TTL
     */
    public function setDefaultTTL($ttl) {
        $this->defaultTTL = $ttl;
    }
}
?>