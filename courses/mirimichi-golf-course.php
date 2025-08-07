<?php
session_start();
require_once '../config/database.php';

$course_slug = 'mirimichi-golf-course';
$course_name = 'Mirimichi Golf Course';

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
    <title>Mirimichi Golf Course - Tennessee Golf Courses</title>
    <meta name="description" content="Mirimichi Golf Course - Championship 18-hole golf course in Millington, TN. Features over 7,400 yards of challenging golf with elevated greens, deep bunkers, and scenic water features.">
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../images/logos/tab-logo.png?v=2">
    <link rel="shortcut icon" href="../images/logos/tab-logo.png?v=2">
    
    <!-- Google tag (gtag.js) -->
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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/mirimichi-golf-course/1.jpeg');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-top: 20px;
        }
        
        .course-hero-content h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .course-hero-content p {
            font-size: 1.3rem;
            opacity: 0.95;
            max-width: 600px;
        }
        
        .quick-info {
            background: white;
            padding: 2rem 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .info-item {
            text-align: center;
        }
        
        .info-item i {
            font-size: 2rem;
            color: var(--gold-color);
            margin-bottom: 0.5rem;
        }
        
        .info-item h3 {
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }
        
        .info-item p {
            color: var(--text-gray);
            font-size: 0.95rem;
        }
        
        .course-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 4rem 2rem;
        }
        
        .description-section {
            margin-bottom: 4rem;
        }
        
        .description-section h2 {
            color: var(--text-dark);
            font-size: 2.2rem;
            margin-bottom: 2rem;
            font-weight: 700;
            text-align: center;
        }
        
        .description-text {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--text-gray);
            text-align: justify;
            columns: 2;
            column-gap: 3rem;
            column-rule: 1px solid #e5e7eb;
        }
        
        .features-section {
            background: #f8faf9;
            padding: 4rem 2rem;
            margin: 4rem 0;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }
        
        .feature-card i {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .feature-card h3 {
            color: var(--text-dark);
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }
        
        .feature-card p {
            color: var(--text-gray);
            line-height: 1.6;
        }
        
        .contact-section {
            background: var(--text-dark);
            color: white;
            padding: 4rem 2rem;
            text-align: center;
        }
        
        .contact-section h2 {
            font-size: 2.2rem;
            margin-bottom: 2rem;
        }
        
        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }
        
        .contact-item i {
            font-size: 1.5rem;
            color: var(--gold-color);
        }
        
        .reviews-section {
            padding: 4rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .reviews-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .reviews-header h2 {
            color: var(--text-dark);
            font-size: 2.2rem;
            margin-bottom: 1rem;
        }
        
        .average-rating {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .rating-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--gold-color);
        }
        
        .stars {
            font-size: 1.5rem;
            color: var(--gold-color);
        }
        
        .total-reviews {
            color: var(--text-gray);
            font-size: 1.1rem;
        }
        
        @media (max-width: 768px) {
            .course-hero-content h1 {
                font-size: 2.5rem;
            }
            
            .description-text {
                columns: 1;
            }
            
            .info-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
            
            .contact-info {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>

    <!-- Course Hero Section -->
    <section class="course-hero">
        <div class="course-hero-content">
            <h1>Mirimichi Golf Course</h1>
            <p>Where championship golf meets natural beauty in Millington, Tennessee</p>
        </div>
    </section>

    <!-- Quick Info Section -->
    <section class="quick-info">
        <div class="info-grid">
            <div class="info-item">
                <i class="fas fa-map-marker-alt"></i>
                <h3>Location</h3>
                <p>Millington, TN</p>
            </div>
            <div class="info-item">
                <i class="fas fa-golf-ball"></i>
                <h3>Par</h3>
                <p>72</p>
            </div>
            <div class="info-item">
                <i class="fas fa-ruler"></i>
                <h3>Yardage</h3>
                <p>7,400+ yards</p>
            </div>
            <div class="info-item">
                <i class="fas fa-users"></i>
                <h3>Type</h3>
                <p>Public</p>
            </div>
            <div class="info-item">
                <i class="fas fa-star"></i>
                <h3>Rating</h3>
                <p>75.7 / 136 (Black Tees)</p>
            </div>
        </div>
    </section>

    <!-- Course Description -->
    <section class="course-content">
        <div class="description-section">
            <h2>Experience Championship Golf</h2>
            <div class="description-text">
                <p>Mirimichi Golf Course, whose name means "place of happy retreat" in Native American tradition, offers an exceptional championship golf experience in the heart of Millington, Tennessee. This meticulously designed 18-hole course stretches over 7,400 yards of challenging terrain, providing golfers of all skill levels with an unforgettable round of golf.</p>
                
                <p>The course features strategically placed deep bunkers and elevated greens that test precision and course management skills. Natural water features, including scenic creeks and waterfalls, weave throughout the layout, creating both visual beauty and strategic challenges. Each hole presents unique obstacles and rewards, making every round a new adventure.</p>
                
                <p>With multiple tee boxes accommodating players from beginners to scratch golfers, Mirimichi offers a fair yet challenging experience for everyone. The black tees provide a true championship test at 75.7 course rating and 136 slope, while forward tees ensure accessibility without sacrificing the enjoyment of the game.</p>
                
                <p>The course's design emphasizes the natural topography of the Tennessee landscape, incorporating rolling hills, mature trees, and native vegetation. This harmonious integration creates an immersive golf experience where players feel connected to the natural environment while navigating the strategic challenges of each hole.</p>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-mountain"></i>
                <h3>Elevated Greens</h3>
                <p>Strategically elevated putting surfaces that reward precision approach shots and test short game skills</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-water"></i>
                <h3>Water Features</h3>
                <p>Scenic creeks and waterfalls that enhance both the beauty and strategic challenge of the course</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-trophy"></i>
                <h3>Championship Layout</h3>
                <p>Over 7,400 yards of championship golf designed to challenge and inspire players of all levels</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-chart-line"></i>
                <h3>Multiple Tees</h3>
                <p>Various tee options ensuring an appropriate challenge and enjoyable experience for every golfer</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-leaf"></i>
                <h3>Natural Beauty</h3>
                <p>Mature trees and native vegetation create a pristine natural setting for your golf experience</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-bullseye"></i>
                <h3>Strategic Design</h3>
                <p>Deep bunkers and thoughtful hazard placement reward course management and strategic thinking</p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <h2>Plan Your Visit</h2>
        <div class="contact-info">
            <div class="contact-item">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <strong>Address</strong><br>
                    6195 Woodstock Cuba Rd<br>
                    Millington, TN 38053
                </div>
            </div>
            <div class="contact-item">
                <i class="fas fa-phone"></i>
                <div>
                    <strong>Phone</strong><br>
                    (901) 259-3800
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="reviews-section">
        <div class="reviews-header">
            <h2>Player Reviews</h2>
            <?php if ($avg_rating && $total_reviews > 0): ?>
                <div class="average-rating">
                    <span class="rating-number"><?php echo $avg_rating; ?></span>
                    <div class="stars">
                        <?php 
                        $full_stars = floor($avg_rating);
                        $half_star = ($avg_rating - $full_stars) >= 0.5;
                        
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $full_stars) {
                                echo '<i class="fas fa-star"></i>';
                            } elseif ($i == $full_stars + 1 && $half_star) {
                                echo '<i class="fas fa-star-half-alt"></i>';
                            } else {
                                echo '<i class="far fa-star"></i>';
                            }
                        }
                        ?>
                    </div>
                    <span class="total-reviews">Based on <?php echo $total_reviews; ?> review<?php echo $total_reviews !== 1 ? 's' : ''; ?></span>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($is_logged_in): ?>
            <div class="review-form">
                <h3>Share Your Experience</h3>
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-error"><?php echo $error_message; ?></div>
                <?php endif; ?>
                
                <form method="POST" class="comment-form">
                    <div class="form-group">
                        <label for="rating">Rating:</label>
                        <div class="star-rating">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" required>
                                <label for="star<?php echo $i; ?>" class="star">★</label>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="comment_text">Your Review:</label>
                        <textarea id="comment_text" name="comment_text" rows="4" required placeholder="Share your experience playing at Mirimichi Golf Course..."></textarea>
                    </div>
                    <button type="submit" class="submit-btn">Submit Review</button>
                </form>
            </div>
        <?php else: ?>
            <div class="login-prompt">
                <p>Please <a href="../login.php">log in</a> to leave a review.</p>
            </div>
        <?php endif; ?>

        <?php if (!empty($comments)): ?>
            <div class="reviews-list">
                <h3>Recent Reviews</h3>
                <?php foreach ($comments as $comment): ?>
                    <div class="review-item">
                        <div class="review-header">
                            <div class="review-author">
                                <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                                <div class="review-rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span class="star <?php echo $i <= $comment['rating'] ? 'filled' : ''; ?>">★</span>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div class="review-date"><?php echo date('M j, Y', strtotime($comment['created_at'])); ?></div>
                        </div>
                        <div class="review-content">
                            <?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <?php include '../includes/footer.php'; ?>

    <script src="../script.js"></script>
</body>
</html>