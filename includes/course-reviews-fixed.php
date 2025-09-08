<?php
// Course Reviews Include File - Fixed Version
// This centralized file handles all review functionality for course pages
// Usage: Set $course_slug and $course_name before including this file

// Ensure required variables are set
if (!isset($course_slug) || !isset($course_name)) {
    echo '<!-- Error: course_slug and course_name must be set before including course-reviews.php -->';
    return;
}


// Use existing session and user status (don't reinitialize)
$is_logged_in = isset($is_logged_in) ? $is_logged_in : false;

$success_message = null;
$error_message = null;

// Check for success message from redirect (no form processing here - that should be in main page)
if (isset($_GET['success']) && $_GET['success'] == '1') {
    $success_message = "Your review has been posted successfully!";
}

// Fetch existing comments (simplified - no try/catch complexity)
try {
    // Get main reviews (where parent_comment_id IS NULL)
    $stmt = $pdo->prepare("
        SELECT cc.*, u.username 
        FROM course_comments cc 
        JOIN users u ON cc.user_id = u.id 
        WHERE cc.course_slug = ? AND cc.parent_comment_id IS NULL
        ORDER BY cc.created_at DESC
        LIMIT 5
    ");
    $stmt->execute([$course_slug]);
    $comments = $stmt->fetchAll();
    
    // For each comment, get reply info
    foreach ($comments as &$comment) {
        // Get total reply count
        try {
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
        } catch (PDOException $e) {
            // If reply system doesn't exist yet, just set empty
            $comment['replies'] = [];
            $comment['reply_count'] = 0;
        }
    }
    
    // Calculate average rating (only main reviews, not replies)
    $stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM course_comments WHERE course_slug = ? AND parent_comment_id IS NULL AND rating IS NOT NULL");
    $stmt->execute([$course_slug]);
    $rating_data = $stmt->fetch();
    $avg_rating = $rating_data['avg_rating'] ? round($rating_data['avg_rating'], 1) : null;
    $total_reviews = $rating_data['total_reviews'] ?: 0;
    
} catch (PDOException $e) {
    error_log("Error in course-reviews-fixed.php: " . $e->getMessage());
    $comments = [];
    $avg_rating = null;
    $total_reviews = 0;
}
?>

<style>
    /* Reply system styles */
    .reply-button {
        background: transparent;
        color: #4a7c59;
        border: 1px solid #4a7c59;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 1rem;
    }
    
    .reply-button:hover {
        background: #4a7c59;
        color: white;
    }
    
    .reply-form {
        margin-top: 1rem;
        padding-left: 2rem;
        border-left: 3px solid #e2e8f0;
    }
    
    .replies-container {
        margin-top: 1rem;
        padding-left: 2rem;
        border-left: 3px solid #f3f4f6;
    }
    
    .reply-item {
        background: #f9fafb;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 0.8rem;
    }
</style>

<!-- Reviews Section -->
<section class="reviews-section" style="background: #f8f9fa; padding: 4rem 0;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
        <h2 style="text-align: center; margin-bottom: 3rem; color: #2c5234;">Course Reviews</h2>
        
        <?php if ($is_logged_in): ?>
            <div class="comment-form-container" style="background: white; padding: 2rem; border-radius: 15px; margin-bottom: 3rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <h3 style="color: #2c5234; margin-bottom: 1.5rem;">Share Your Experience</h3>
                
                <?php if (isset($success_message)): ?>
                    <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #c3e6cb;"><?php echo $success_message; ?></div>
                <?php endif; ?>
                
                <?php if (isset($error_message)): ?>
                    <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #f5c6cb;"><?php echo $error_message; ?></div>
                <?php endif; ?>
                
                <form method="POST" class="comment-form">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c5234;">Your Rating:</label>
                        <div class="star-rating-container" style="padding: 10px 0;">
                            <div class="star-display" style="display: flex; gap: 3px; align-items: center; position: relative;">
                                <!-- Visual star display with half-star support -->
                                <span class="star" data-value="1" style="color: #999; font-size: 2rem; cursor: pointer; position: relative; transition: all 0.2s;">
                                    <span class="star-full" style="position: absolute; overflow: hidden; width: 0%; color: #ffd700;">★</span>
                                    <span>★</span>
                                </span>
                                <span class="star" data-value="2" style="color: #999; font-size: 2rem; cursor: pointer; position: relative; transition: all 0.2s;">
                                    <span class="star-full" style="position: absolute; overflow: hidden; width: 0%; color: #ffd700;">★</span>
                                    <span>★</span>
                                </span>
                                <span class="star" data-value="3" style="color: #999; font-size: 2rem; cursor: pointer; position: relative; transition: all 0.2s;">
                                    <span class="star-full" style="position: absolute; overflow: hidden; width: 0%; color: #ffd700;">★</span>
                                    <span>★</span>
                                </span>
                                <span class="star" data-value="4" style="color: #999; font-size: 2rem; cursor: pointer; position: relative; transition: all 0.2s;">
                                    <span class="star-full" style="position: absolute; overflow: hidden; width: 0%; color: #ffd700;">★</span>
                                    <span>★</span>
                                </span>
                                <span class="star" data-value="5" style="color: #999; font-size: 2rem; cursor: pointer; position: relative; transition: all 0.2s;">
                                    <span class="star-full" style="position: absolute; overflow: hidden; width: 0%; color: #ffd700;">★</span>
                                    <span>★</span>
                                </span>
                                <span class="rating-text" style="margin-left: 10px; color: #666; font-size: 1rem;">Click to rate</span>
                            </div>
                            <!-- Hidden input for the actual rating value -->
                            <input type="hidden" name="rating" id="rating-input" value="" required>
                        </div>
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c5234;">Your Review:</label>
                        <textarea name="comment_text" placeholder="Share your thoughts about <?php echo htmlspecialchars($course_name); ?>..." required style="width: 100%; padding: 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-family: inherit; resize: vertical; min-height: 100px;"></textarea>
                    </div>
                    <button type="submit" style="background: #2c5234; color: white; padding: 0.75rem 2rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Post Review</button>
                </form>
            </div>
        <?php else: ?>
            <div style="background: #f8f9fa; padding: 2rem; border-radius: 15px; text-align: center; margin-bottom: 3rem;">
                <p><a href="/login" style="color: #2c5234; font-weight: 600; text-decoration: none;">Log in</a> to share your review of <?php echo htmlspecialchars($course_name); ?></p>
            </div>
        <?php endif; ?>
        
        <?php if (count($comments) > 0): ?>
            <div style="text-align: center; margin-bottom: 2rem; color: #666; font-size: 0.9rem;">
                <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>
                Showing 5 most recent reviews (latest reply shown for each)
            </div>
            <?php foreach ($comments as $comment): ?>
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
                                echo '<i class="far fa-star" style="color: #999;"></i>';
                            }
                        }
                        ?>
                        <span style="margin-left: 8px; color: #666; font-size: 0.9rem;">(<?php echo number_format($comment['rating'], 1); ?>)</span>
                    </div>
                    <p style="line-height: 1.6; color: #333;"><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></p>
                    
                    <?php if ($is_logged_in): ?>
                        <button class="reply-button" onclick="toggleReplyForm(<?php echo $comment['id']; ?>)">
                            <i class="fas fa-reply"></i> Reply
                        </button>
                        
                        <!-- Reply form (hidden by default) -->
                        <div id="reply-form-<?php echo $comment['id']; ?>" class="reply-form" style="display: none;">
                            <form method="POST" action="/courses/process-reply" style="margin-top: 1rem;">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
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
            <?php endforeach; ?>
            
            <!-- Load More Reviews Button -->
            <div style="text-align: center; margin: 3rem 0;">
                <button onclick="loadMoreReviews()" 
                        id="load-more-reviews"
                        style="background: #2c5234; color: white; padding: 0.75rem 2rem; border: none; border-radius: 25px; font-size: 1rem; cursor: pointer; font-weight: 600; box-shadow: 0 3px 10px rgba(44,82,52,0.3);">
                    <i class="fas fa-plus-circle" style="margin-right: 0.5rem;"></i>
                    Load More Reviews
                </button>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 3rem; color: #666;">
                <i class="fas fa-comments" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                <h3>No reviews yet</h3>
                <p>Be the first to share your experience at <?php echo htmlspecialchars($course_name); ?>!</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
    // Enhanced star rating with half-star support
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('rating-input');
    const ratingText = document.querySelector('.rating-text');
    let currentRating = 0;
    
    if (stars.length > 0 && ratingInput) {
        // Rating labels for different values
        const getRatingLabel = (rating) => {
            if (rating <= 1) return 'Poor';
            if (rating <= 2) return 'Fair';
            if (rating <= 3) return 'Good';
            if (rating <= 4) return 'Very Good';
            return 'Excellent';
        };
        
        // Update star display with half-star support
        function updateStarDisplay(rating) {
            stars.forEach((star, index) => {
                const starValue = index + 1;
                const starFull = star.querySelector('.star-full');
                
                if (rating >= starValue) {
                    // Full star
                    starFull.style.width = '100%';
                } else if (rating >= starValue - 0.5) {
                    // Half star
                    starFull.style.width = '50%';
                } else {
                    // Empty star
                    starFull.style.width = '0%';
                }
            });
            
            // Update rating text
            if (ratingText && rating > 0) {
                const displayRating = rating % 1 === 0 ? rating : rating.toFixed(1);
                ratingText.textContent = `${displayRating} star${rating > 1 ? 's' : ''} - ${getRatingLabel(rating)}`;
                ratingText.style.color = '#2c5234';
                ratingText.style.fontWeight = '600';
            } else if (ratingText) {
                ratingText.textContent = 'Click to rate';
                ratingText.style.color = '#666';
                ratingText.style.fontWeight = 'normal';
            }
        }
        
        // Handle star clicks with half-star detection
        stars.forEach((star) => {
            star.addEventListener('click', function(e) {
                const rect = star.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const width = rect.width;
                const starValue = parseFloat(star.dataset.value);
                
                // Determine if it's a half or full star click
                if (x < width / 2) {
                    // Click on left half - half star
                    currentRating = starValue - 0.5;
                } else {
                    // Click on right half - full star
                    currentRating = starValue;
                }
                
                ratingInput.value = currentRating;
                updateStarDisplay(currentRating);
            });
            
            // Handle hover with half-star preview
            star.addEventListener('mousemove', function(e) {
                if (currentRating > 0) return; // Don't show hover if already rated
                
                const rect = star.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const width = rect.width;
                const starValue = parseFloat(star.dataset.value);
                
                let hoverRating;
                if (x < width / 2) {
                    hoverRating = starValue - 0.5;
                } else {
                    hoverRating = starValue;
                }
                
                updateStarDisplay(hoverRating);
            });
        });
        
        // Reset display on mouse leave
        document.querySelector('.star-display').addEventListener('mouseleave', function() {
            updateStarDisplay(currentRating);
        });
    }
    
    // Reply form toggle
    function toggleReplyForm(commentId) {
        const replyForm = document.getElementById('reply-form-' + commentId);
        if (replyForm) {
            replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
        }
    }
    
    // Load More Reviews functionality - define globally for Cloudflare compatibility
    window.currentReviewOffset = 5; // We start by showing 5 reviews
    
    window.loadMoreReviews = function() {
        const button = document.getElementById('load-more-reviews');
        if (!button) return;
        
        button.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 0.5rem;"></i>Loading...';
        button.disabled = true;
        
        fetch('/courses/load-more-reviews?t=' + Date.now(), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Cache-Control': 'no-cache'
            },
            body: 'course_slug=<?php echo $course_slug; ?>&offset=' + window.currentReviewOffset
        })
        .then(response => response.text())
        .then(html => {
            if (html.trim() && html.trim() !== '<!-- Not a POST request -->') {
                // Insert new reviews before the Load More button
                const loadMoreDiv = button.parentElement;
                loadMoreDiv.insertAdjacentHTML('beforebegin', html);
                window.currentReviewOffset += 5;
                
                button.innerHTML = '<i class="fas fa-plus-circle" style="margin-right: 0.5rem;"></i>Load More Reviews';
                button.disabled = false;
            } else {
                // No more reviews
                button.innerHTML = 'No more reviews';
                button.disabled = true;
                button.style.opacity = '0.5';
            }
        })
        .catch(error => {
            console.error('Error loading reviews:', error);
            button.innerHTML = '<i class="fas fa-plus-circle" style="margin-right: 0.5rem;"></i>Load More Reviews';
            button.disabled = false;
        });
    };
    
    // Load More Replies functionality  
    window.loadMoreReplies = function(commentId) {
        const button = document.getElementById('load-more-replies-' + commentId);
        if (!button) return;
        
        button.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 0.5rem;"></i>Loading...';
        button.disabled = true;
        
        fetch('/courses/load-more-replies?t=' + Date.now(), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Cache-Control': 'no-cache'
            },
            body: 'comment_id=' + commentId
        })
        .then(response => response.text())
        .then(html => {
            if (html.trim()) {
                // Insert new replies before the Load More button
                button.parentElement.insertAdjacentHTML('beforebegin', html);
                button.remove(); // Remove the button since we loaded all replies
            } else {
                button.innerHTML = 'No more replies';
                button.disabled = true;
            }
        })
        .catch(error => {
            console.error('Error loading replies:', error);
            button.innerHTML = '<i class="fas fa-comments" style="margin-right: 0.5rem;"></i>Try Again';
            button.disabled = false;
        });
    };
</script>