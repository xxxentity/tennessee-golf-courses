<?php
/**
 * Forum Rate Limiting and Spam Protection
 * Limits users to 5 topics per day and 10 posts per day
 */

class ForumRateLimit {
    private static $limits = [
        'topics_per_day' => 5,
        'posts_per_day' => 10,
        'min_time_between_posts' => 5 // seconds (reduced for testing)
    ];
    
    /**
     * Check if user can create a new topic
     */
    public static function canCreateTopic($user_id) {
        if (!$user_id) {
            return ['allowed' => false, 'reason' => 'User not logged in'];
        }
        
        // In real implementation, query database for user's topics today
        // For now, using session-based tracking
        $today = date('Y-m-d');
        $session_key = "topics_created_$today";
        
        if (!isset($_SESSION[$session_key])) {
            $_SESSION[$session_key] = 0;
        }
        
        if ($_SESSION[$session_key] >= self::$limits['topics_per_day']) {
            return [
                'allowed' => false, 
                'reason' => 'Daily topic limit reached (' . self::$limits['topics_per_day'] . ' topics per day)'
            ];
        }
        
        return ['allowed' => true];
    }
    
    /**
     * Check if user can create a new post/reply
     */
    public static function canCreatePost($user_id) {
        if (!$user_id) {
            return ['allowed' => false, 'reason' => 'User not logged in'];
        }
        
        // Check daily post limit
        $today = date('Y-m-d');
        $session_key = "posts_created_$today";
        
        if (!isset($_SESSION[$session_key])) {
            $_SESSION[$session_key] = 0;
        }
        
        if ($_SESSION[$session_key] >= self::$limits['posts_per_day']) {
            return [
                'allowed' => false, 
                'reason' => 'Daily post limit reached (' . self::$limits['posts_per_day'] . ' posts per day)'
            ];
        }
        
        // Check time between posts
        $last_post_key = 'last_post_time';
        if (isset($_SESSION[$last_post_key])) {
            $time_since_last = time() - $_SESSION[$last_post_key];
            if ($time_since_last < self::$limits['min_time_between_posts']) {
                $wait_time = self::$limits['min_time_between_posts'] - $time_since_last;
                return [
                    'allowed' => false, 
                    'reason' => "Please wait $wait_time seconds between posts"
                ];
            }
        }
        
        return ['allowed' => true];
    }
    
    /**
     * Record that user created a topic
     */
    public static function recordTopicCreated($user_id) {
        $today = date('Y-m-d');
        $session_key = "topics_created_$today";
        
        if (!isset($_SESSION[$session_key])) {
            $_SESSION[$session_key] = 0;
        }
        
        $_SESSION[$session_key]++;
        $_SESSION['last_post_time'] = time();
        
        // In real implementation, also update database
        /*
        $stmt = $pdo->prepare("
            INSERT INTO forum_user_rate_limits (user_id, action_type, action_date, count) 
            VALUES (?, 'topic', ?, 1)
            ON DUPLICATE KEY UPDATE count = count + 1
        ");
        $stmt->execute([$user_id, $today]);
        */
    }
    
    /**
     * Record that user created a post
     */
    public static function recordPostCreated($user_id) {
        $today = date('Y-m-d');
        $session_key = "posts_created_$today";
        
        if (!isset($_SESSION[$session_key])) {
            $_SESSION[$session_key] = 0;
        }
        
        $_SESSION[$session_key]++;
        $_SESSION['last_post_time'] = time();
        
        // In real implementation, also update database
        /*
        $stmt = $pdo->prepare("
            INSERT INTO forum_user_rate_limits (user_id, action_type, action_date, count) 
            VALUES (?, 'post', ?, 1)
            ON DUPLICATE KEY UPDATE count = count + 1
        ");
        $stmt->execute([$user_id, $today]);
        */
    }
    
    /**
     * Get user's remaining limits for the day
     */
    public static function getRemainingLimits($user_id) {
        if (!$user_id) {
            return ['topics' => 0, 'posts' => 0];
        }
        
        $today = date('Y-m-d');
        $topics_used = $_SESSION["topics_created_$today"] ?? 0;
        $posts_used = $_SESSION["posts_created_$today"] ?? 0;
        
        return [
            'topics' => max(0, self::$limits['topics_per_day'] - $topics_used),
            'posts' => max(0, self::$limits['posts_per_day'] - $posts_used),
            'topics_used' => $topics_used,
            'posts_used' => $posts_used,
            'topics_limit' => self::$limits['topics_per_day'],
            'posts_limit' => self::$limits['posts_per_day']
        ];
    }
    
    /**
     * Simple content spam detection
     */
    public static function isSpamContent($content) {
        $spam_indicators = [
            // Excessive caps
            'excessive_caps' => (strlen($content) > 20 && preg_match_all('/[A-Z]/', $content) / strlen($content) > 0.7),
            
            // Excessive repeated characters
            'repeated_chars' => preg_match('/(.)\1{4,}/', $content),
            
            // Too many URLs
            'excessive_urls' => preg_match_all('/https?:\/\/[^\s]+/', $content) > 3,
            
            // Common spam phrases
            'spam_phrases' => preg_match('/\b(buy now|click here|make money|free money|viagra|casino|poker)\b/i', $content),
            
            // Excessive punctuation
            'excessive_punctuation' => preg_match('/[!?]{3,}/', $content)
        ];
        
        $spam_score = array_sum($spam_indicators);
        
        return [
            'is_spam' => $spam_score >= 2,
            'score' => $spam_score,
            'reasons' => array_keys(array_filter($spam_indicators))
        ];
    }
    
    /**
     * Check if user is temporarily banned
     */
    public static function isUserBanned($user_id) {
        // In real implementation, check database for bans
        // For now, just return false
        return false;
    }
    
    /**
     * Clear rate limit timers (for testing/debugging)
     */
    public static function clearRateLimits($user_id = null) {
        unset($_SESSION['last_post_time']);
        
        // Clear daily counters
        $today = date('Y-m-d');
        unset($_SESSION["topics_created_$today"]);
        unset($_SESSION["posts_created_$today"]);
    }
}

/**
 * Database schema for rate limiting (add to forum_schema.sql)
 */
/*
CREATE TABLE forum_user_rate_limits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action_type ENUM('topic', 'post') NOT NULL,
    action_date DATE NOT NULL,
    count INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_action_date (user_id, action_type, action_date),
    INDEX idx_user_date (user_id, action_date)
);

CREATE TABLE forum_user_bans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    banned_by INT NOT NULL,
    reason TEXT,
    banned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (banned_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_active (user_id, is_active),
    INDEX idx_expires (expires_at)
);
*/
?>