<?php
/**
 * Performance Optimization Utilities
 * Handles output compression, minification, and performance monitoring
 */

class Performance {
    private static $startTime = null;
    private static $startMemory = null;
    
    /**
     * Start performance monitoring
     */
    public static function start() {
        self::$startTime = microtime(true);
        self::$startMemory = memory_get_usage(true);
    }
    
    /**
     * Get current performance metrics
     */
    public static function getMetrics() {
        $currentTime = microtime(true);
        $currentMemory = memory_get_usage(true);
        $peakMemory = memory_get_peak_usage(true);
        
        return [
            'execution_time' => $currentTime - self::$startTime,
            'memory_used' => $currentMemory - self::$startMemory,
            'memory_current' => $currentMemory,
            'memory_peak' => $peakMemory,
            'memory_used_mb' => round(($currentMemory - self::$startMemory) / 1024 / 1024, 2),
            'memory_current_mb' => round($currentMemory / 1024 / 1024, 2),
            'memory_peak_mb' => round($peakMemory / 1024 / 1024, 2)
        ];
    }
    
    /**
     * Enable output compression
     */
    public static function enableCompression() {
        if (!ob_get_level() && extension_loaded('zlib')) {
            ob_start('ob_gzhandler');
        }
    }
    
    /**
     * Minify HTML output
     */
    public static function minifyHTML($html) {
        // Remove HTML comments (but preserve IE conditional comments)
        $html = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $html);
        
        // Remove whitespace between HTML tags
        $html = preg_replace('/>\s+</', '><', $html);
        
        // Remove extra whitespace
        $html = preg_replace('/\s+/', ' ', $html);
        
        // Remove whitespace around specific tags
        $html = preg_replace('/\s*(<\/?(html|head|body|title|meta|link|script|style)[^>]*>)\s*/i', '$1', $html);
        
        return trim($html);
    }
    
    /**
     * Minify CSS
     */
    public static function minifyCSS($css) {
        // Remove comments
        $css = preg_replace('/\/\*.*?\*\//s', '', $css);
        
        // Remove extra whitespace
        $css = preg_replace('/\s+/', ' ', $css);
        
        // Remove whitespace around specific characters
        $css = preg_replace('/\s*([{}:;,>+~])\s*/', '$1', $css);
        
        // Remove trailing semicolons
        $css = preg_replace('/;}/', '}', $css);
        
        return trim($css);
    }
    
    /**
     * Minify JavaScript
     */
    public static function minifyJS($js) {
        // Remove single-line comments (but preserve URLs)
        $js = preg_replace('/(?<!:)\/\/.*$/m', '', $js);
        
        // Remove multi-line comments
        $js = preg_replace('/\/\*.*?\*\//s', '', $js);
        
        // Remove extra whitespace
        $js = preg_replace('/\s+/', ' ', $js);
        
        // Remove whitespace around operators and punctuation
        $js = preg_replace('/\s*([{}();,=+\-*\/&|!<>?:])\s*/', '$1', $js);
        
        return trim($js);
    }
    
    /**
     * Generate performance headers
     */
    public static function sendPerformanceHeaders() {
        // Cache control headers
        header('Cache-Control: public, max-age=3600, stale-while-revalidate=86400');
        header('Vary: Accept-Encoding');
        
        // Security headers for performance
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        
        // Performance timing header
        if (self::$startTime) {
            $executionTime = microtime(true) - self::$startTime;
            header('X-Execution-Time: ' . round($executionTime * 1000, 2) . 'ms');
        }
    }
    
    /**
     * Preload critical resources
     */
    public static function preloadResources($resources) {
        foreach ($resources as $resource) {
            $type = $resource['type'] ?? 'script';
            $href = $resource['href'];
            $as = $resource['as'] ?? $type;
            
            header("Link: <{$href}>; rel=preload; as={$as}", false);
        }
    }
    
    /**
     * Add performance monitoring script
     */
    public static function getPerformanceScript() {
        return "
        <script>
        // Performance monitoring
        window.addEventListener('load', function() {
            if (window.performance && window.performance.timing) {
                var timing = window.performance.timing;
                var loadTime = timing.loadEventEnd - timing.navigationStart;
                var domReady = timing.domContentLoadedEventEnd - timing.navigationStart;
                
                // Log to console in development
                console.log('Page Load Time: ' + loadTime + 'ms');
                console.log('DOM Ready Time: ' + domReady + 'ms');
                
                // Send to analytics if needed
                // gtag('event', 'timing_complete', {
                //     'name': 'load',
                //     'value': loadTime
                // });
            }
        });
        </script>";
    }
    
    /**
     * Database query performance tracking
     */
    public static function trackQuery($query, $executionTime, $resultCount = null) {
        if (!isset($_SESSION['query_log'])) {
            $_SESSION['query_log'] = [];
        }
        
        $_SESSION['query_log'][] = [
            'query' => substr($query, 0, 200) . (strlen($query) > 200 ? '...' : ''),
            'time' => $executionTime,
            'results' => $resultCount,
            'timestamp' => microtime(true)
        ];
        
        // Keep only last 20 queries
        if (count($_SESSION['query_log']) > 20) {
            $_SESSION['query_log'] = array_slice($_SESSION['query_log'], -20);
        }
    }
    
    /**
     * Get query performance stats
     */
    public static function getQueryStats() {
        $queries = $_SESSION['query_log'] ?? [];
        
        if (empty($queries)) {
            return null;
        }
        
        $totalTime = array_sum(array_column($queries, 'time'));
        $slowestQuery = max(array_column($queries, 'time'));
        $fastestQuery = min(array_column($queries, 'time'));
        $avgTime = $totalTime / count($queries);
        
        return [
            'total_queries' => count($queries),
            'total_time' => round($totalTime, 4),
            'avg_time' => round($avgTime, 4),
            'slowest_time' => round($slowestQuery, 4),
            'fastest_time' => round($fastestQuery, 4),
            'queries' => $queries
        ];
    }
}

// Auto-start performance monitoring
Performance::start();
?>