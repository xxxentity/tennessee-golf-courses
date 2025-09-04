<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/database.php';

// Prevent any caching of this AJAX endpoint
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_slug = $_POST['course_slug'] ?? '';
    $offset = (int)($_POST['offset'] ?? 0);
    
    if (empty($course_slug) || $offset < 0) {
        exit('');
    }
    
    try {
        // Debug: Log what we're looking for
        error_log("Load more reviews - Course: $course_slug, Offset: $offset");
        
        // First check total count with different approaches
        $stmt = $pdo->prepare("SELECT COUNT(*) as total_all FROM course_comments WHERE course_slug = ?");
        $stmt->execute([$course_slug]);
        $total_all = $stmt->fetch()['total_all'];
        
        $stmt = $pdo->prepare("SELECT COUNT(*) as total_null FROM course_comments WHERE course_slug = ? AND parent_comment_id IS NULL");
        $stmt->execute([$course_slug]);
        $total_null = $stmt->fetch()['total_null'];
        
        $stmt = $pdo->prepare("SELECT COUNT(*) as total_zero FROM course_comments WHERE course_slug = ? AND parent_comment_id = 0");
        $stmt->execute([$course_slug]);
        $total_zero = $stmt->fetch()['total_zero'];
        
        $stmt = $pdo->prepare("SELECT COUNT(*) as total_main FROM course_comments WHERE course_slug = ? AND (parent_comment_id IS NULL OR parent_comment_id = 0)");
        $stmt->execute([$course_slug]);
        $total_main = $stmt->fetch()['total_main'];
        
        error_log("Review counts - All: $total_all, NULL: $total_null, Zero: $total_zero, Main: $total_main");
        
        // Get ALL comments first to debug
        $stmt = $pdo->prepare("
            SELECT cc.*, u.username 
            FROM course_comments cc 
            JOIN users u ON cc.user_id = u.id 
            WHERE cc.course_slug = ?
            ORDER BY cc.created_at DESC
        ");
        $stmt->execute([$course_slug]);
        $all_comments = $stmt->fetchAll();
        
        error_log("Total comments fetched from DB: " . count($all_comments));
        
        // Debug each comment
        foreach ($all_comments as $idx => $comment) {
            error_log("Comment $idx: ID={$comment['id']}, parent_id=" . 
                     (isset($comment['parent_comment_id']) ? $comment['parent_comment_id'] : 'NOT SET') . 
                     ", user={$comment['username']}");
        }
        
        // Filter to get only main reviews (not replies)
        $main_reviews = [];
        foreach ($all_comments as $comment) {
            $parent_id = isset($comment['parent_comment_id']) ? $comment['parent_comment_id'] : null;
            error_log("Checking comment ID {$comment['id']}: parent_comment_id = " . var_export($parent_id, true));
            
            if ($parent_id === null || $parent_id == 0 || $parent_id == '0' || empty($parent_id)) {
                $main_reviews[] = $comment;
                error_log("  -> Added as main review");
            } else {
                error_log("  -> Skipped as reply (parent_id: $parent_id)");
            }
        }
        
        error_log("Total main reviews after filtering: " . count($main_reviews));
        error_log("Requesting offset: $offset, limit: 5");
        
        // Now slice to get the requested page
        $comments = array_slice($main_reviews, $offset, 5);
        
        error_log("After slice - returning " . count($comments) . " reviews");
        
        error_log("Found " . count($comments) . " reviews for offset $offset");
        
        if (empty($comments)) {
            error_log("No more comments found - checking if this is really true");
            
            // Emergency failsafe - just try to get ANY comments as a test
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM course_comments WHERE course_slug = ?");
            $stmt->execute([$course_slug]);
            $emergency_count = $stmt->fetch()['total'];
            error_log("EMERGENCY CHECK: Total comments in DB for this course: $emergency_count");
            
            // If we have comments but can't load them, there's a serious issue
            if ($emergency_count > 5) {
                error_log("ERROR: We have $emergency_count comments but can't load them at offset $offset!");
                // Return debug message instead of empty
                echo "<!-- Debug: Found $emergency_count total comments but pagination failed at offset $offset -->";
            }
            
            exit(''); // No more comments
        }
        
        // Fetch latest reply and reply count for each comment
        foreach ($comments as &$comment) {
            // Get total reply count
            $stmt = $pdo->prepare("SELECT COUNT(*) as reply_count FROM course_comments WHERE parent_comment_id = ?");
            $stmt->execute([$comment['id']]);
            $comment['reply_count'] = $stmt->fetch()['reply_count'];
            
            // Get latest reply only
            $stmt = $pdo->prepare("
                SELECT cc.*, u.username 
                FROM course_comments cc 
                JOIN users u ON cc.user_id = u.id 
                WHERE cc.parent_comment_id = ?
                ORDER BY cc.created_at DESC
                LIMIT 1
            ");
            $stmt->execute([$comment['id']]);
            $latest_reply = $stmt->fetch();
            $comment['replies'] = $latest_reply ? [$latest_reply] : [];
        }
        
        // Start session once before outputting HTML
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $is_logged_in = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
        
        // Output HTML for the new reviews
        foreach ($comments as $comment): ?>
            <div style="background: white; padding: 2rem; border-radius: 15px; margin-bottom: 2rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <div style="font-weight: 600; color: #2c5234;"><?php echo htmlspecialchars($comment['username']); ?></div>
                    <div style="color: #666; font-size: 0.9rem;"><?php echo date('M j, Y', strtotime($comment['created_at'])); ?></div>
                </div>
                <div style="color: #ffd700; margin-bottom: 1rem;">
                    <?php 
                    $full_stars = floor($comment['rating']);
                    $half_star = ($comment['rating'] - $full_stars) >= 0.5;
                    
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $full_stars) {
                            echo '<i class="fas fa-star" style="color: #ffd700;"></i>';
                        } elseif ($i == $full_stars + 1 && $half_star) {
                            echo '<i class="fas fa-star-half-alt" style="color: #ffd700;"></i>';
                        } else {
                            echo '<i class="far fa-star" style="color: #ddd;"></i>';
                        }
                    }
                    ?>
                    <span style="margin-left: 0.5rem; color: #666; font-size: 0.9rem;"><?php echo $comment['rating']; ?>/5</span>
                </div>
                <p style="margin-bottom: 1.5rem; line-height: 1.6;"><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></p>
                
                <!-- Reply Button (only for logged in users) -->
                <?php if ($is_logged_in): ?>
                    <button onclick="toggleReplyForm(<?php echo $comment['id']; ?>)" 
                            style="background: #f8f9fa; color: #4a7c59; border: 2px solid #e2e8f0; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.85rem; cursor: pointer; margin-bottom: 1rem; font-weight: 500;">
                        <i class="fas fa-reply" style="margin-right: 0.5rem;"></i>
                        Reply
                    </button>
                    
                    <!-- Reply form (hidden by default) -->
                    <div id="reply-form-<?php echo $comment['id']; ?>" class="reply-form" style="display: none;">
                        <form method="POST" action="process-reply" style="margin-top: 1rem;">
                            <?php require_once '../includes/csrf.php'; echo CSRFProtection::getTokenField(); ?>
                            <input type="hidden" name="parent_comment_id" value="<?php echo $comment['id']; ?>">
                            <input type="hidden" name="course_slug" value="<?php echo $course_slug; ?>">
                            <textarea name="reply_text" placeholder="Write your reply..." required style="width: 100%; padding: 0.8rem; border: 2px solid #e5e7eb; border-radius: 8px; font-family: inherit; resize: vertical; min-height: 80px;"></textarea>
                            <div style="display: flex; gap: 10px; margin-top: 0.5rem;">
                                <button type="submit" style="background: #4a7c59; color: white; padding: 0.5rem 1.5rem; border: none; border-radius: 20px; font-size: 0.9rem; cursor: pointer;">Post Reply</button>
                                <button type="button" onclick="toggleReplyForm(<?php echo $comment['id']; ?>)" style="background: #e5e7eb; color: #666; padding: 0.5rem 1.5rem; border: none; border-radius: 20px; font-size: 0.9rem; cursor: pointer;">Cancel</button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
                
                <!-- Display replies if any -->
                <?php if (!empty($comment['replies'])): ?>
                    <div class="replies-container">
                        <?php foreach ($comment['replies'] as $reply): ?>
                            <div class="reply-item">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                    <span style="font-weight: 600; color: #2c5234; font-size: 0.9rem;"><?php echo htmlspecialchars($reply['username']); ?></span>
                                    <span style="color: #888; font-size: 0.8rem;"><?php echo date('M j, Y', strtotime($reply['created_at'])); ?></span>
                                </div>
                                <p style="margin: 0; font-size: 0.95rem; line-height: 1.5;"><?php echo nl2br(htmlspecialchars($reply['comment_text'])); ?></p>
                            </div>
                        <?php endforeach; ?>
                        
                        <!-- Show more replies button if there are more than 1 reply -->
                        <?php if ($comment['reply_count'] > 1): ?>
                            <div style="text-align: center; margin-top: 1rem;">
                                <button onclick="loadMoreReplies(<?php echo $comment['id']; ?>)" 
                                        id="load-more-replies-<?php echo $comment['id']; ?>"
                                        style="background: #f8f9fa; color: #4a7c59; border: 2px solid #e2e8f0; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.85rem; cursor: pointer; font-weight: 500;">
                                    <i class="fas fa-comments" style="margin-right: 0.5rem;"></i>
                                    Show <?php echo ($comment['reply_count'] - 1); ?> more repl<?php echo ($comment['reply_count'] - 1) > 1 ? 'ies' : 'y'; ?>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach;
        
    } catch (PDOException $e) {
        error_log("Error loading more reviews: " . $e->getMessage());
        exit('');
    }
} else {
    exit('');
}
?>