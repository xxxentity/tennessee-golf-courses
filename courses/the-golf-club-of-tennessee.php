<?php
session_start();
require_once '../config/database.php';

$course_slug = 'the-golf-club-of-tennessee';
$course_name = 'The Golf Club of Tennessee';

// Check if user is logged in
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_logged_in) {
    $rating = (int)$_POST['rating'];
    $comment_text = trim($_POST['comment_text']);
    $user_id = $_SESSION['user_id'];
    
    if ($rating >= 1 && $rating <= 5 && !empty($comment_text)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO course_comments (user_id, course_slug, course_name, rating, comment_text) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $course_slug, $course_name, $rating, $comment_text]);
            $success_message = "Your review has been posted successfully!";
        } catch (PDOException $e) {
            $error_message = "Error posting review. Please try again.";
        }
    } else {
        $error_message = "Please provide a valid rating and comment.";
    }
}

// Get existing comments
try {
    $stmt = $pdo->prepare("
        SELECT cc.*, u.username 
        FROM course_comments cc 
        JOIN users u ON cc.user_id = u.id 
        WHERE cc.course_slug = ? 
        ORDER BY cc.created_at DESC
    ");
    $stmt->execute([$course_slug]);
    $comments = $stmt->fetchAll();
    
    // Calculate average rating
    $stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM course_comments WHERE course_slug = ?");
    $stmt->execute([$course_slug]);
    $rating_data = $stmt->fetch();
    $avg_rating = $rating_data['avg_rating'] ? round($rating_data['avg_rating'], 1) : null;
    $total_reviews = $rating_data['total_reviews'] ?: 0;
    
} catch (PDOException $e) {
    $comments = [];
    $avg_rating = null;
    $total_reviews = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Golf Club of Tennessee - Tom Fazio Design | Tennessee Golf Courses</title>
    <meta name="description" content="Experience The Golf Club of Tennessee, a Tom Fazio masterpiece in Kingston Springs. Golf Digest's 2nd ranked course in Tennessee with championship golf.">
    <link rel="canonical" href="https://tennesseegolfcourses.com/courses/the-golf-club-of-tennessee">
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-7VPNPCDTBP');
    </script>
    
    <style>
        .course-hero {
            height: 60vh;
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/the-golf-club-of-tennessee/1.jpeg');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
        }
        
        .course-hero-content h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
        }
        
        .course-hero-content p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.7);
        }
        
        .course-rating {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .rating-stars {
            color: #ffd700;
            font-size: 1.5rem;
        }
        
        .rating-text {
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .course-details {
            padding: 4rem 0;
            background-color: #f8f9fa;
        }
        
        .course-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .course-info-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .course-info-card h3 {
            color: #2c5234;
            margin-bottom: 1rem;
            font-size: 1.3rem;
            border-bottom: 2px solid #228B22;
            padding-bottom: 0.5rem;
        }
        
        .course-specs {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .spec-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        
        .spec-label {
            font-weight: 600;
            color: #555;
        }
        
        .spec-value {
            color: #2c5234;
            font-weight: 600;
        }
        
        .course-description {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }
        
        .course-description h2 {
            color: #2c5234;
            margin-bottom: 1.5rem;
            font-size: 2rem;
        }
        
        .course-description p {
            line-height: 1.8;
            margin-bottom: 1.5rem;
            color: #333;
        }
        
        .signature-holes {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }
        
        .signature-holes h2 {
            color: #2c5234;
            margin-bottom: 2rem;
            font-size: 2rem;
        }
        
        .hole-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        
        .hole-card {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            border-left: 4px solid #228B22;
        }
        
        .hole-number {
            font-size: 1.2rem;
            font-weight: 700;
            color: #228B22;
            margin-bottom: 0.5rem;
        }
        
        .hole-description {
            color: #555;
            line-height: 1.6;
        }
        
        .photo-gallery {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }
        
        .photo-gallery h2 {
            color: #2c5234;
            margin-bottom: 2rem;
            font-size: 2rem;
            text-align: center;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
        }
        
        .gallery-item {
            position: relative;
            height: 200px;
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        
        .gallery-item:hover {
            transform: scale(1.05);
        }
        
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.9);
        }
        
        .modal-content {
            margin: auto;
            display: block;
            width: 90%;
            max-width: 900px;
            max-height: 80%;
            margin-top: 5%;
        }
        
        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .reviews-section {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .reviews-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .reviews-title {
            color: #2c5234;
            font-size: 2rem;
            margin: 0;
        }
        
        .overall-rating {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .rating-display {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .stars {
            color: #ffd700;
        }
        
        .review-form {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }
        
        .star-rating {
            display: flex;
            gap: 0.25rem;
            margin-bottom: 1rem;
        }
        
        .star-rating input {
            display: none;
        }
        
        .star-rating label {
            font-size: 1.5rem;
            color: #ddd;
            cursor: pointer;
            margin-bottom: 0;
        }
        
        .star-rating input:checked ~ label,
        .star-rating label:hover {
            color: #ffd700;
        }
        
        .form-group textarea {
            width: 100%;
            min-height: 100px;
            padding: 1rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-family: inherit;
            resize: vertical;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, #2c5234, #228B22);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
        }
        
        .reviews-list {
            space-y: 1.5rem;
        }
        
        .review-card {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
        
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .reviewer-name {
            font-weight: 600;
            color: #2c5234;
        }
        
        .review-date {
            color: #888;
            font-size: 0.9rem;
        }
        
        .review-rating {
            color: #ffd700;
            margin-bottom: 0.5rem;
        }
        
        .review-text {
            color: #555;
            line-height: 1.6;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .login-prompt {
            text-align: center;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .login-link {
            color: #2c5234;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .course-hero-content h1 {
                font-size: 2.5rem;
            }
            
            .course-info-grid {
                grid-template-columns: 1fr;
            }
            
            .course-specs {
                grid-template-columns: 1fr;
            }
            
            .hole-grid {
                grid-template-columns: 1fr;
            }
            
            .reviews-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>

    <!-- Hero Section -->
    <section class="course-hero">
        <div class="course-hero-content">
            <h1>The Golf Club of Tennessee</h1>
            <p>Tom Fazio Masterpiece in Kingston Springs</p>
            
            <div class="course-rating">
                <?php if ($avg_rating !== null): ?>
                    <div class="rating-stars">
                        <?php
                        $rating = $avg_rating;
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= floor($rating)) {
                                echo '<i class="fas fa-star"></i>';
                            } elseif ($i <= ceil($rating)) {
                                echo '<i class="fas fa-star-half-alt"></i>';
                            } else {
                                echo '<i class="far fa-star"></i>';
                            }
                        }
                        ?>
                    </div>
                    <div class="rating-text">
                        <?php echo number_format($avg_rating, 1); ?>/5 (<?php echo $total_reviews; ?> reviews)
                    </div>
                <?php else: ?>
                    <div class="rating-text">No ratings yet - Be the first to review!</div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Course Details -->
    <section class="course-details">
        <div class="container">
            <!-- Course Information Cards -->
            <div class="course-info-grid">
                <div class="course-info-card">
                    <h3><i class="fas fa-info-circle"></i> Course Information</h3>
                    <div class="course-specs">
                        <div class="spec-item">
                            <span class="spec-label">Par:</span>
                            <span class="spec-value">71</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Yardage:</span>
                            <span class="spec-value">7,184 yards</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Course Rating:</span>
                            <span class="spec-value">74.8</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Slope Rating:</span>
                            <span class="spec-value">144</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Designer:</span>
                            <span class="spec-value">Tom Fazio</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Year Opened:</span>
                            <span class="spec-value">1991</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-trophy"></i> Accolades & Rankings</h3>
                    <div class="course-specs single-column">
                        <div class="spec-item">
                            <span class="spec-label">Golf Digest Ranking:</span>
                            <span class="spec-value">#199 America's Greatest</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Tennessee Ranking:</span>
                            <span class="spec-value">#2 in State</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Property Size:</span>
                            <span class="spec-value">317 acres</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Greens:</span>
                            <span class="spec-value">A1 Bent Grass</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Fairways:</span>
                            <span class="spec-value">Zoysia</span>
                        </div>
                    </div>
                </div>

                <div class="course-info-card">
                    <h3><i class="fas fa-calendar-alt"></i> Tournament History</h3>
                    <div class="course-specs single-column">
                        <div class="spec-item">
                            <span class="spec-label">2018:</span>
                            <span class="spec-value">US Women's Amateur</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">2006:</span>
                            <span class="spec-value">Tennessee State Open</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">2001:</span>
                            <span class="spec-value">Tennessee State Amateur</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Membership:</span>
                            <span class="spec-value">Private Club</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Clubhouse:</span>
                            <span class="spec-value">31,000 sq ft</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Description -->
            <div class="course-description">
                <h2>About The Golf Club of Tennessee</h2>
                <p>The Golf Club of Tennessee stands as one of America's premier golf destinations, designed by the legendary Tom Fazio and opened in 1991. Founded by Nashville businessman Bronson Ingram, this exclusive private club was created with the vision of designing "the very best golf course" possible on the spectacular 317-acre property in Kingston Springs.</p>
                
                <p>Fazio masterfully utilized the rolling Tennessee terrain, incorporating dense forests, rocky ridges, and the winding Brush Creek to create a golf course that Golf Digest consistently ranks among America's Greatest Golf Courses. Currently ranked #199 nationally and #2 in Tennessee, the course showcases Fazio's exceptional ability to blend challenging golf with natural beauty.</p>
                
                <p>The course features pristine A1 Bent grass greens, Zoysia fairways, and Bermuda roughs across its 7,184 yards. With a course rating of 74.8 and slope of 144, it provides a stern test for golfers of all skill levels. The layout includes what many consider "a wonder set of 5 par 3s" that showcase incredible variety and strategic design elements.</p>
                
                <p>The Golf Club of Tennessee has hosted prestigious tournaments including the 2018 US Women's Amateur Championship and multiple Tennessee state championships. The club's 31,000 square-foot antebellum-style clubhouse provides luxury amenities befitting one of the nation's top private golf clubs.</p>
            </div>

            <!-- Signature Holes -->
            <div class="signature-holes">
                <h2>Signature Holes</h2>
                <div class="hole-grid">
                    <div class="hole-card">
                        <div class="hole-number">4th Hole - Par 3</div>
                        <div class="hole-description">A dramatic 200-yard par 3 requiring a precise carry over a deep chasm that drops to the river below. This hole exemplifies Fazio's use of natural terrain features.</div>
                    </div>
                    <div class="hole-card">
                        <div class="hole-number">8th Hole - Par 3</div>
                        <div class="hole-description">Another stunning par 3 that plays directly over the river, demanding both accuracy and nerve to find the green surrounded by water.</div>
                    </div>
                    <div class="hole-card">
                        <div class="hole-number">9th Hole - Par 4</div>
                        <div class="hole-description">A 465-yard dogleg-right par 4 that hugs the rocky edge of Brush Creek, providing both beauty and strategic challenge as players navigate the turn.</div>
                    </div>
                    <div class="hole-card">
                        <div class="hole-number">10th Hole - Par 3</div>
                        <div class="hole-description">The course's most photographed hole - a 200-yard par 3 in a box-canyon setting, playing across Brush Creek to a green backdropped by waterfalls and towering shale outcroppings.</div>
                    </div>
                    <div class="hole-card">
                        <div class="hole-number">18th Hole - Par 4</div>
                        <div class="hole-description">A memorable 456-yard finishing hole where Brush Creek provides a stunning backdrop to the home green, creating a dramatic conclusion to the round.</div>
                    </div>
                </div>
            </div>

            <!-- Photo Gallery -->
            <div class="photo-gallery">
                <h2>Course Gallery</h2>
                <div class="gallery-grid">
                    <?php for ($i = 2; $i <= 25; $i++): ?>
                        <div class="gallery-item" onclick="openModal('../images/courses/the-golf-club-of-tennessee/<?php echo $i; ?>.jpeg')">
                            <img src="../images/courses/the-golf-club-of-tennessee/<?php echo $i; ?>.jpeg" alt="The Golf Club of Tennessee - Photo <?php echo $i; ?>">
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="reviews-section">
                <div class="reviews-header">
                    <h2 class="reviews-title">Course Reviews</h2>
                    <?php if ($total_reviews > 0): ?>
                        <div class="overall-rating">
                            <div class="rating-display">
                                <div class="stars">
                                    <?php
                                    $rating = $avg_rating;
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= floor($rating)) {
                                            echo '<i class="fas fa-star"></i>';
                                        } elseif ($i <= ceil($rating)) {
                                            echo '<i class="fas fa-star-half-alt"></i>';
                                        } else {
                                            echo '<i class="far fa-star"></i>';
                                        }
                                    }
                                    ?>
                                </div>
                                <span><?php echo number_format($avg_rating, 1); ?>/5</span>
                                <span class="review-count">(<?php echo $total_reviews; ?> reviews)</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($is_logged_in): ?>
                    <!-- Review Form -->
                    <div class="review-form">
                        <h3>Share Your Experience</h3>
                        
                        <?php if (isset($success_message)): ?>
                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                        <?php endif; ?>
                        
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-error"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="form-group">
                                <label>Your Rating:</label>
                                <div class="star-rating">
                                    <input type="radio" name="rating" value="5" id="star5">
                                    <label for="star5">★</label>
                                    <input type="radio" name="rating" value="4" id="star4">
                                    <label for="star4">★</label>
                                    <input type="radio" name="rating" value="3" id="star3">
                                    <label for="star3">★</label>
                                    <input type="radio" name="rating" value="2" id="star2">
                                    <label for="star2">★</label>
                                    <input type="radio" name="rating" value="1" id="star1">
                                    <label for="star1">★</label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="comment_text">Your Review:</label>
                                <textarea name="comment_text" id="comment_text" placeholder="Share your thoughts about The Golf Club of Tennessee..." required></textarea>
                            </div>
                            
                            <button type="submit" class="submit-btn">Submit Review</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="login-prompt">
                        <p><a href="../login" class="login-link">Login</a> or <a href="../register" class="login-link">Register</a> to leave a review</p>
                    </div>
                <?php endif; ?>

                <!-- Existing Reviews -->
                <?php if (!empty($comments)): ?>
                    <div class="reviews-list">
                        <?php foreach ($comments as $comment): ?>
                            <div class="review-card">
                                <div class="review-header">
                                    <div class="reviewer-name"><?php echo htmlspecialchars($comment['username']); ?></div>
                                    <div class="review-date"><?php echo date('M j, Y', strtotime($comment['created_at'])); ?></div>
                                </div>
                                <div class="review-rating">
                                    <?php
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $comment['rating']) {
                                            echo '<i class="fas fa-star"></i>';
                                        } else {
                                            echo '<i class="far fa-star"></i>';
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="review-text"><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-reviews">
                        <p>No reviews yet. Be the first to share your experience!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Photo Modal -->
    <div id="photoModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

    <script>
        // Photo gallery modal functionality
        function openModal(imageSrc) {
            document.getElementById('photoModal').style.display = 'block';
            document.getElementById('modalImage').src = imageSrc;
        }

        function closeModal() {
            document.getElementById('photoModal').style.display = 'none';
        }

        // Close modal when clicking outside the image
        window.onclick = function(event) {
            const modal = document.getElementById('photoModal');
            if (event.target === modal) {
                closeModal();
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        // Star rating functionality
        const starInputs = document.querySelectorAll('.star-rating input');
        const starLabels = document.querySelectorAll('.star-rating label');

        starLabels.forEach((label, index) => {
            label.addEventListener('mouseover', () => {
                highlightStars(index);
            });
            
            label.addEventListener('click', () => {
                starInputs[index].checked = true;
            });
        });

        document.querySelector('.star-rating').addEventListener('mouseleave', () => {
            const checkedIndex = Array.from(starInputs).findIndex(input => input.checked);
            if (checkedIndex !== -1) {
                highlightStars(checkedIndex);
            } else {
                clearStars();
            }
        });

        function highlightStars(index) {
            starLabels.forEach((label, i) => {
                if (i >= index) {
                    label.style.color = '#ffd700';
                } else {
                    label.style.color = '#ddd';
                }
            });
        }

        function clearStars() {
            starLabels.forEach(label => {
                label.style.color = '#ddd';
            });
        }
    </script>
</body>
</html>