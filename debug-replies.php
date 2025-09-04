<?php
require_once 'config/database.php';

echo "<h2>Debug: Course Comments and Replies for Avalon Golf</h2>";

try {
    // Get all comments for Avalon Golf
    $stmt = $pdo->prepare("
        SELECT cc.*, u.username, 
        CASE WHEN cc.parent_comment_id IS NULL OR cc.parent_comment_id = 0 THEN 'MAIN REVIEW' ELSE 'REPLY' END as comment_type
        FROM course_comments cc 
        JOIN users u ON cc.user_id = u.id 
        WHERE cc.course_slug = 'avalon-golf-country-club'
        ORDER BY cc.parent_comment_id ASC, cc.created_at DESC
    ");
    $stmt->execute();
    $all_comments = $stmt->fetchAll();
    
    echo "<h3>All Comments in Database:</h3>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Type</th><th>Parent ID</th><th>Username</th><th>Rating</th><th>Comment Text</th><th>Created</th></tr>";
    
    foreach ($all_comments as $comment) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($comment['id']) . "</td>";
        echo "<td>" . htmlspecialchars($comment['comment_type']) . "</td>";
        echo "<td>" . htmlspecialchars($comment['parent_comment_id'] ?? 'NULL') . "</td>";
        echo "<td>" . htmlspecialchars($comment['username']) . "</td>";
        echo "<td>" . htmlspecialchars($comment['rating'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars(substr($comment['comment_text'], 0, 100)) . "...</td>";
        echo "<td>" . htmlspecialchars($comment['created_at']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Count main reviews vs replies
    $stmt = $pdo->prepare("SELECT 
        COUNT(CASE WHEN parent_comment_id IS NULL OR parent_comment_id = 0 THEN 1 END) as main_reviews,
        COUNT(CASE WHEN parent_comment_id IS NOT NULL AND parent_comment_id > 0 THEN 1 END) as replies
        FROM course_comments WHERE course_slug = 'avalon-golf-country-club'");
    $stmt->execute();
    $counts = $stmt->fetch();
    
    echo "<h3>Summary:</h3>";
    echo "<p>Main Reviews: " . $counts['main_reviews'] . "</p>";
    echo "<p>Replies: " . $counts['replies'] . "</p>";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>