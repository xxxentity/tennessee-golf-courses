<?php
/**
 * Threaded Comment System - Reusable Include
 * Usage: include this file in any news article after setting $article_slug and $article_title
 */

if (!isset($article_slug) || !isset($article_title)) {
    die('Error: article_slug and article_title must be set before including threaded-comments.php');
}

// Check if user is logged in using secure session
$is_logged_in = SecureSession::isLoggedIn();

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_logged_in && isset($_POST['comment_text'])) {
    $comment_text = trim($_POST['comment_text']);
    $parent_id = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
    $user_id = SecureSession::get('user_id');
    
    if (!empty($comment_text)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO news_comments (user_id, article_slug, article_title, comment_text, parent_id) VALUES (?, ?, ?, ?, ?)");
            $result = $stmt->execute([$user_id, $article_slug, $article_title, $comment_text, $parent_id]);
            
            if ($result) {
                $success_message = $parent_id ? "Your reply has been posted successfully!" : "Your comment has been posted successfully!";
                // Don't redirect - let comment appear immediately
            } else {
                $error_message = "Failed to post comment. Please try again.";
            }
        } catch (PDOException $e) {
            $error_message = "Error posting comment. Please try again.";
        }
    } else {
        $error_message = "Please write a comment.";
    }
}

// Get existing comments
try {
    $stmt = $pdo->prepare("
        SELECT nc.*, u.username 
        FROM news_comments nc 
        JOIN users u ON nc.user_id = u.id 
        WHERE nc.article_slug = ? AND nc.is_approved = TRUE
        ORDER BY nc.created_at ASC
    ");
    $stmt->execute([$article_slug]);
    $all_comments = $stmt->fetchAll();
    
    // Organize comments into threaded structure
    $comments = [];
    $replies = [];
    
    foreach ($all_comments as $comment) {
        if ($comment['parent_id'] === null || $comment['parent_id'] === '' || $comment['parent_id'] === 0) {
            $comments[] = $comment;
        } else {
            if (!isset($replies[$comment['parent_id']])) {
                $replies[$comment['parent_id']] = [];
            }
            $replies[$comment['parent_id']][] = $comment;
        }
    }
    
} catch (PDOException $e) {
    $comments = [];
    $replies = [];
}
?>

<!-- Comments Section HTML -->
<section class="comments-section">
    <h2 class="comments-header"><i class="fas fa-comments"></i> Discussion</h2>
    
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>
    
    <?php if (isset($error_message)): ?>
        <div class="alert alert-error"><?php echo $error_message; ?></div>
    <?php endif; ?>
    
    <?php if ($is_logged_in): ?>
        <div class="comment-form">
            <h3>Join the Discussion</h3>
            <form method="POST">
                <textarea name="comment_text" class="comment-textarea" placeholder="Share your thoughts..." required></textarea>
                <button type="submit" class="comment-submit">
                    <i class="fas fa-paper-plane"></i> Post Comment
                </button>
            </form>
        </div>
    <?php else: ?>
        <div class="login-prompt">
            <p><strong>Join the conversation!</strong> <a href="/login">Login</a> or <a href="/register">create an account</a> to share your thoughts.</p>
        </div>
    <?php endif; ?>
    
    <div class="comments-list">
        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment" id="comment-<?php echo $comment['id']; ?>">
                    <div class="comment-header">
                        <?php echo getProfileLink($comment['username'], $comment['username'], 'comment-author'); ?>
                        <span class="comment-date"><?php echo date('M j, Y g:i A', strtotime($comment['created_at'])); ?></span>
                    </div>
                    <div class="comment-text"><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></div>
                    
                    <?php if ($is_logged_in): ?>
                    <div class="comment-actions">
                        <button class="reply-btn" onclick="toggleReplyForm(<?php echo $comment['id']; ?>)">
                            <i class="fas fa-reply"></i> Reply
                        </button>
                    </div>
                    
                    <div class="reply-form" id="reply-form-<?php echo $comment['id']; ?>">
                        <form method="POST" action="">
                            <input type="hidden" name="parent_id" value="<?php echo $comment['id']; ?>">
                            <textarea name="comment_text" placeholder="Write your reply..." required></textarea>
                            <div class="form-actions">
                                <button type="submit" class="reply-submit">Post Reply</button>
                                <button type="button" class="reply-cancel" onclick="toggleReplyForm(<?php echo $comment['id']; ?>)">Cancel</button>
                            </div>
                        </form>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($replies[$comment['id']])): ?>
                    <div class="comment-replies">
                        <?php foreach ($replies[$comment['id']] as $reply): ?>
                            <div class="comment-reply" id="comment-<?php echo $reply['id']; ?>">
                                <div class="comment-header">
                                    <?php echo getProfileLink($reply['username'], $reply['username'], 'comment-author'); ?>
                                    <span class="comment-date"><?php echo date('M j, Y g:i A', strtotime($reply['created_at'])); ?></span>
                                </div>
                                <div class="comment-text"><?php echo nl2br(htmlspecialchars($reply['comment_text'])); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; color: var(--text-gray); font-style: italic;">Be the first to comment!</p>
        <?php endif; ?>
    </div>
</section>

<style>
.comments-section {
    background: var(--bg-white);
    padding: 3rem;
    border-radius: 20px;
    box-shadow: var(--shadow-medium);
    margin-bottom: 2rem;
}

.comments-header {
    font-size: 2rem;
    color: var(--primary-color);
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 12px;
}

.comment-form {
    margin-bottom: 3rem;
    padding: 2rem;
    background: var(--bg-light);
    border-radius: 15px;
}

.comment-form h3 {
    margin-bottom: 1rem;
    color: var(--primary-color);
}

.comment-textarea {
    width: 100%;
    min-height: 120px;
    padding: 1rem;
    border: 2px solid var(--border-color);
    border-radius: 10px;
    font-family: inherit;
    font-size: 1rem;
    resize: vertical;
    transition: border-color 0.3s ease;
}

.comment-textarea:focus {
    outline: none;
    border-color: var(--primary-color);
}

.comment-submit {
    background: var(--primary-color);
    color: white;
    padding: 0.8rem 2rem;
    border: none;
    border-radius: 50px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 1rem;
}

.comment-submit:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
}

.comment {
    padding: 1.5rem;
    background: var(--bg-light);
    border-radius: 15px;
    margin-bottom: 1.5rem;
    transition: box-shadow 0.3s ease;
}

.comment:hover {
    box-shadow: var(--shadow-light);
}

.comment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.8rem;
}

.comment-author {
    font-weight: 600;
    color: var(--primary-color);
    text-decoration: none;
}

.comment-author:hover {
    text-decoration: underline;
}

.comment-date {
    font-size: 0.85rem;
    color: var(--text-gray);
}

.comment-text {
    color: var(--text-dark);
    line-height: 1.6;
}

.comment-actions {
    margin-top: 1rem;
    display: flex;
    gap: 1rem;
}

.reply-btn {
    background: none;
    border: none;
    color: var(--primary-color);
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: 500;
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    transition: background 0.2s ease;
}

.reply-btn:hover {
    background: var(--bg-light);
}

.reply-form {
    margin-top: 1rem;
    padding: 1rem;
    background: var(--bg-white);
    border-radius: 10px;
    border: 1px solid var(--border-color);
    display: none;
}

.reply-form.active {
    display: block;
}

.reply-form textarea {
    width: 100%;
    min-height: 80px;
    padding: 0.8rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-family: inherit;
    font-size: 0.95rem;
    resize: vertical;
}

.reply-form .form-actions {
    margin-top: 0.8rem;
    display: flex;
    gap: 0.8rem;
}

.reply-form button {
    padding: 0.6rem 1.5rem;
    border: none;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.reply-submit {
    background: var(--primary-color);
    color: white;
}

.reply-submit:hover {
    background: var(--secondary-color);
}

.reply-cancel {
    background: var(--border-color);
    color: var(--text-gray);
}

.reply-cancel:hover {
    background: var(--text-gray);
    color: white;
}

.comment-replies {
    margin-top: 1.5rem;
    margin-left: 2rem;
    border-left: 3px solid var(--border-color);
    padding-left: 1.5rem;
}

.comment-reply {
    padding: 1rem;
    background: var(--bg-white);
    border-radius: 10px;
    margin-bottom: 1rem;
    border: 1px solid var(--border-color);
}

.comment-reply:last-child {
    margin-bottom: 0;
}

.login-prompt {
    text-align: center;
    padding: 2rem;
    background: var(--bg-light);
    border-radius: 15px;
    margin-bottom: 2rem;
}

.login-prompt a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
}

.alert {
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1rem;
}

.alert-success {
    background: #d1fae5;
    color: #047857;
    border: 1px solid #10b981;
}

.alert-error {
    background: #fef2f2;
    color: #b91c1c;
    border: 1px solid #ef4444;
}

@media (max-width: 768px) {
    .comments-section {
        padding: 1.5rem;
    }
    
    .comment-replies {
        margin-left: 1rem;
        padding-left: 1rem;
    }
}
</style>

<script>
function toggleReplyForm(commentId) {
    const form = document.getElementById('reply-form-' + commentId);
    if (form) {
        if (form.classList.contains('active')) {
            form.classList.remove('active');
        } else {
            // Hide any other open reply forms
            const allForms = document.querySelectorAll('.reply-form.active');
            allForms.forEach(f => f.classList.remove('active'));
            
            // Show this form
            form.classList.add('active');
            
            // Focus on the textarea
            const textarea = form.querySelector('textarea');
            if (textarea) {
                textarea.focus();
            }
        }
    }
}
</script>