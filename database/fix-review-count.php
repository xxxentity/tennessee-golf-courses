<?php
require_once '../config/database.php';

echo "<h2>Checking Review Count Issue</h2>";

try {
    // Check if parent_comment_id column exists
    $stmt = $pdo->prepare("SHOW COLUMNS FROM course_comments LIKE 'parent_comment_id'");
    $stmt->execute();
    $column_exists = $stmt->fetch() !== false;
    
    if ($column_exists) {
        echo "✅ parent_comment_id column exists<br><br>";
        
        // Get stats for Avalon course
        echo "<h3>Avalon Golf Course Stats:</h3>";
        
        // Count total entries
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM course_comments WHERE course_slug = 'avalon-golf-country-club'");
        $stmt->execute();
        $total = $stmt->fetch()['total'];
        echo "Total entries: $total<br>";
        
        // Count reviews (parent_comment_id IS NULL)
        $stmt = $pdo->prepare("SELECT COUNT(*) as reviews FROM course_comments WHERE course_slug = 'avalon-golf-country-club' AND (parent_comment_id IS NULL OR parent_comment_id = 0) AND rating IS NOT NULL");
        $stmt->execute();
        $reviews = $stmt->fetch()['reviews'];
        echo "Reviews (with ratings): $reviews<br>";
        
        // Count replies
        $stmt = $pdo->prepare("SELECT COUNT(*) as replies FROM course_comments WHERE course_slug = 'avalon-golf-country-club' AND parent_comment_id > 0");
        $stmt->execute();
        $replies = $stmt->fetch()['replies'];
        echo "Replies: $replies<br><br>";
        
        // Calculate correct average
        $stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating FROM course_comments WHERE course_slug = 'avalon-golf-country-club' AND (parent_comment_id IS NULL OR parent_comment_id = 0) AND rating IS NOT NULL");
        $stmt->execute();
        $avg = $stmt->fetch()['avg_rating'];
        echo "Correct average rating: " . round($avg, 1) . "<br>";
        echo "Correct review count to display: $reviews<br><br>";
        
        // Show sample data
        echo "<h3>Sample Data (first 5 entries):</h3>";
        $stmt = $pdo->prepare("SELECT id, user_id, rating, parent_comment_id, LEFT(comment_text, 50) as comment_preview FROM course_comments WHERE course_slug = 'avalon-golf-country-club' ORDER BY created_at DESC LIMIT 5");
        $stmt->execute();
        $samples = $stmt->fetchAll();
        
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>User</th><th>Rating</th><th>Parent ID</th><th>Comment Preview</th><th>Type</th></tr>";
        foreach ($samples as $sample) {
            $type = ($sample['parent_comment_id'] > 0) ? 'Reply' : 'Review';
            echo "<tr>";
            echo "<td>{$sample['id']}</td>";
            echo "<td>{$sample['user_id']}</td>";
            echo "<td>{$sample['rating']}</td>";
            echo "<td>{$sample['parent_comment_id']}</td>";
            echo "<td>{$sample['comment_preview']}...</td>";
            echo "<td><strong>$type</strong></td>";
            echo "</tr>";
        }
        echo "</table>";
        
    } else {
        echo "❌ parent_comment_id column does NOT exist<br>";
        echo "This is why replies are being counted as reviews!<br><br>";
        echo "Run the migration script: /database/migrate-reply-system.php";
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>