<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = (int)($_POST['comment_id'] ?? 0);
    
    if ($comment_id <= 0) {
        exit('');
    }
    
    try {
        // Get all replies except the latest one (which is already shown)
        $stmt = $pdo->prepare("
            SELECT cc.*, u.username 
            FROM course_comments cc 
            JOIN users u ON cc.user_id = u.id 
            WHERE cc.parent_comment_id = ?
            ORDER BY cc.created_at ASC
        ");
        $stmt->execute([$comment_id]);
        $all_replies = $stmt->fetchAll();
        
        // Remove the latest reply since it's already displayed
        if (count($all_replies) > 1) {
            array_pop($all_replies); // Remove the last (latest) reply
        } else {
            exit(''); // No additional replies to show
        }
        
        // Output HTML for the additional replies
        foreach ($all_replies as $reply): ?>
            <div class="reply-item">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <span style="font-weight: 600; color: #2c5234; font-size: 0.9rem;"><?php echo htmlspecialchars($reply['username']); ?></span>
                    <span style="color: #888; font-size: 0.8rem;"><?php echo date('M j, Y', strtotime($reply['created_at'])); ?></span>
                </div>
                <p style="margin: 0; font-size: 0.95rem; line-height: 1.5;"><?php echo nl2br(htmlspecialchars($reply['comment_text'])); ?></p>
            </div>
        <?php endforeach;
        
    } catch (PDOException $e) {
        error_log("Error loading more replies: " . $e->getMessage());
        exit('');
    }
} else {
    exit('');
}
?>