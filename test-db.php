<?php
require_once 'config/database.php';

$course_slug = 'bear-trace-harrison-bay';

echo "<h3>Database Test for Bear Trace Harrison Bay</h3>";

try {
    // Test basic connection
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM course_comments WHERE course_slug = ?");
    $stmt->execute([$course_slug]);
    $result = $stmt->fetch();
    echo "<p>Total comments for '$course_slug': " . $result['count'] . "</p>";
    
    // Show actual comments
    $stmt = $pdo->prepare("SELECT cc.id, cc.rating, cc.comment_text, cc.created_at, u.username FROM course_comments cc JOIN users u ON cc.user_id = u.id WHERE cc.course_slug = ? ORDER BY cc.created_at DESC LIMIT 5");
    $stmt->execute([$course_slug]);
    $comments = $stmt->fetchAll();
    
    if ($comments) {
        echo "<h4>Recent Comments:</h4>";
        foreach ($comments as $comment) {
            echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
            echo "<strong>" . htmlspecialchars($comment['username']) . "</strong> - " . $comment['rating'] . " stars<br>";
            echo htmlspecialchars($comment['comment_text']) . "<br>";
            echo "<small>" . $comment['created_at'] . "</small>";
            echo "</div>";
        }
    } else {
        echo "<p>No comments found</p>";
    }
    
    // Test with parent_comment_id column
    echo "<h4>Testing parent_comment_id column:</h4>";
    try {
        $stmt = $pdo->prepare("SELECT cc.id, cc.parent_comment_id FROM course_comments cc WHERE cc.course_slug = ? LIMIT 1");
        $stmt->execute([$course_slug]);
        $test = $stmt->fetch();
        echo "<p>parent_comment_id column exists. Sample value: " . ($test ? $test['parent_comment_id'] ?? 'NULL' : 'No data') . "</p>";
    } catch (PDOException $e) {
        echo "<p>parent_comment_id column does NOT exist: " . $e->getMessage() . "</p>";
    }
    
} catch (PDOException $e) {
    echo "<p>Database error: " . $e->getMessage() . "</p>";
}
?>