<?php
session_start();
require_once '../config/database.php';

$course_slug = 'white-plains-golf-course';
$course_name = 'White Plains Golf Course';

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
    <title>White Plains Golf Course - Tennessee Golf Courses</title>
    <meta name="description" content="White Plains Golf Course - Daily-fee golf course in Cookeville/Algood, TN. 18-hole par 72 course with four tee options ranging from 4,547 to 6,354 yards.">
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
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../images/courses/white-plains-golf-course/1.jpeg');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-top: 120px;
        }
        
        .course-hero-content h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .course-hero-content p {
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto;
            opacity: 0.9;
        }
        
        .course-info {
            padding: 80px 0;
            background: #f8f9fa;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .info-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .info-card h3 {
            color: #2c5530;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .info-card h3 i {
            color: #4a7c59;
        }
        
        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .amenity-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .amenity-card i {
            font-size: 2rem;
            color: #4a7c59;
            margin-bottom: 1rem;
        }
        
        .amenity-card h4 {
            color: #2c5530;
            margin-bottom: 0.5rem;
        }
        
        .tee-information {
            background: white;
            padding: 60px 0;
        }
        
        .tee-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .tee-card {
            background: #f8f9fa;
            border-left: 5px solid;
            padding: 1.5rem;
            border-radius: 8px;
        }
        
        .tee-card.blue { border-left-color: #1976d2; }
        .tee-card.white { border-left-color: #ffffff; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .tee-card.gold { border-left-color: #ffd700; }
        .tee-card.red { border-left-color: #d32f2f; }
        
        .tee-card h3 {
            margin-bottom: 1rem;
            color: #2c5530;
        }
        
        .tee-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
        }
        
        .tee-stat {
            display: flex;
            justify-content: space-between;
            padding: 0.25rem 0;
        }
        
        .gallery-section {
            padding: 60px 0;
            background: #f8f9fa;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .gallery-item {
            position: relative;
            aspect-ratio: 16/9;
            border-radius: 8px;
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
        
        .reviews-section {
            padding: 60px 0;
            background: white;
        }
        
        .review-form {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 3rem;
        }
        
        .review-form h3 {
            margin-bottom: 1.5rem;
            color: #2c5530;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
        }
        
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: inherit;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .btn {
            background: #4a7c59;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }
        
        .btn:hover {
            background: #3d6b4a;
        }
        
        .reviews-list {
            space-y: 1.5rem;
        }
        
        .review-item {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .review-rating {
            color: #ffc107;
        }
        
        .review-text {
            color: #555;
            line-height: 1.6;
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
            position: relative;
            margin: auto;
            padding: 20px;
            width: 90%;
            max-width: 800px;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .modal-content img {
            width: 100%;
            height: auto;
            border-radius: 8px;
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
        
        .close:hover {
            color: #ccc;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .course-hero-content h1 {
                font-size: 2.5rem;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .tee-cards {
                grid-template-columns: 1fr;
            }
            
            .gallery-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="../" class="nav-logo">
                <img src="../images/logos/tgc-logo.png" alt="Tennessee Golf Courses">
            </a>
            <div class="nav-menu">
                <a href="../" class="nav-link">Home</a>
                <a href="../courses" class="nav-link">Courses</a>
                <a href="../news" class="nav-link">News</a>
                <a href="../about" class="nav-link">About</a>
                <a href="../contact" class="nav-link">Contact</a>
                <?php if ($is_logged_in): ?>
                    <a href="../profile" class="nav-link">Profile</a>
                    <a href="../logout" class="nav-link">Logout</a>
                <?php else: ?>
                    <a href="../login" class="nav-link">Login</a>
                    <a href="../register" class="nav-link">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="course-hero">
        <div class="course-hero-content">
            <h1>White Plains Golf Course</h1>
            <p>Daily-fee golf course in the heart of Middle Tennessee</p>
        </div>
    </section>

    <!-- Course Information -->
    <section class="course-info">
        <div class="container">
            <div class="info-grid">
                <div class="info-card">
                    <h3><i class="fas fa-info-circle"></i> Course Details</h3>
                    <p><strong>Location:</strong> 4000 Plantation Drive, Cookeville, TN 38506</p>
                    <p><strong>Type:</strong> Daily-Fee/Semi-Private</p>
                    <p><strong>Holes:</strong> 18</p>
                    <p><strong>Par:</strong> 72</p>
                    <p><strong>Yardage:</strong> 4,547 - 6,354 yards</p>
                    <p><strong>Phone:</strong> (931) 537-6397</p>
                    <p><strong>Email:</strong> gm@whiteplainsgolfcourse.com</p>
                </div>
                
                <div class="info-card">
                    <h3><i class="fas fa-calendar-alt"></i> Year-Round Golf</h3>
                    <p>White Plains Golf Course is open year-round, weather permitting, providing consistent golfing opportunities throughout all seasons. The course encourages advance tee time bookings to ensure your preferred playing times.</p>
                </div>
                
                <div class="info-card">
                    <h3><i class="fas fa-trophy"></i> Tournaments & Events</h3>
                    <p>Home to the annual White Plains Open and various league play opportunities including the Spark League. The course regularly hosts tournaments and special events for golfers of all skill levels.</p>
                </div>
            </div>

            <!-- Amenities Grid -->
            <div class="amenities-grid">
                <div class="amenity-card">
                    <i class="fas fa-golf-ball"></i>
                    <h4>Championship Golf</h4>
                    <p>18-hole par 72 daily-fee course with four tee options for all skill levels</p>
                </div>
                
                <div class="amenity-card">
                    <i class="fas fa-dumbbell"></i>
                    <h4>Driving Range</h4>
                    <p>Practice facilities to warm up and improve your game</p>
                </div>
                
                <div class="amenity-card">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h4>Golf Lessons</h4>
                    <p>Professional instruction available to help improve your golf game</p>
                </div>
                
                <div class="amenity-card">
                    <i class="fas fa-utensils"></i>
                    <h4>Dining Options</h4>
                    <p>On-course dining facilities and beverage cart service</p>
                </div>
                
                <div class="amenity-card">
                    <i class="fas fa-shopping-bag"></i>
                    <h4>Golf Shop</h4>
                    <p>Pro shop with equipment, apparel, and gift certificates</p>
                </div>
                
                <div class="amenity-card">
                    <i class="fas fa-users"></i>
                    <h4>Memberships & Events</h4>
                    <p>Various membership levels and rental space for special events</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tee Information -->
    <section class="tee-information">
        <div class="container">
            <h2 style="text-align: center; margin-bottom: 2rem; color: #2c5530;">Tee Information</h2>
            <div class="tee-cards">
                <div class="tee-card blue">
                    <h3>Blue Tees (Championship)</h3>
                    <div class="tee-stats">
                        <div class="tee-stat">
                            <span>Yardage:</span>
                            <span>6,354 yards</span>
                        </div>
                        <div class="tee-stat">
                            <span>Par:</span>
                            <span>72</span>
                        </div>
                        <div class="tee-stat">
                            <span>Course Rating:</span>
                            <span>70.6</span>
                        </div>
                        <div class="tee-stat">
                            <span>Slope Rating:</span>
                            <span>115</span>
                        </div>
                    </div>
                </div>
                
                <div class="tee-card white">
                    <h3>White Tees (Men's)</h3>
                    <div class="tee-stats">
                        <div class="tee-stat">
                            <span>Yardage:</span>
                            <span>5,923 yards</span>
                        </div>
                        <div class="tee-stat">
                            <span>Par:</span>
                            <span>72</span>
                        </div>
                        <div class="tee-stat">
                            <span>Course Rating:</span>
                            <span>68.5</span>
                        </div>
                        <div class="tee-stat">
                            <span>Slope Rating:</span>
                            <span>113</span>
                        </div>
                    </div>
                </div>
                
                <div class="tee-card gold">
                    <h3>Gold Tees (Senior)</h3>
                    <div class="tee-stats">
                        <div class="tee-stat">
                            <span>Yardage:</span>
                            <span>5,055 yards</span>
                        </div>
                        <div class="tee-stat">
                            <span>Par:</span>
                            <span>72</span>
                        </div>
                        <div class="tee-stat">
                            <span>Course Rating:</span>
                            <span>64.0</span>
                        </div>
                        <div class="tee-stat">
                            <span>Slope Rating:</span>
                            <span>105</span>
                        </div>
                    </div>
                </div>
                
                <div class="tee-card red">
                    <h3>Red Tees (Forward)</h3>
                    <div class="tee-stats">
                        <div class="tee-stat">
                            <span>Yardage:</span>
                            <span>4,547 yards</span>
                        </div>
                        <div class="tee-stat">
                            <span>Par:</span>
                            <span>72</span>
                        </div>
                        <div class="tee-stat">
                            <span>Course Rating:</span>
                            <span>65.8</span>
                        </div>
                        <div class="tee-stat">
                            <span>Slope Rating:</span>
                            <span>105</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Photo Gallery -->
    <section class="gallery-section">
        <div class="container">
            <h2 style="text-align: center; margin-bottom: 2rem; color: #2c5530;">Photo Gallery</h2>
            <div class="gallery-grid">
                <!-- Gallery images will be populated here -->
                <?php for ($i = 1; $i <= 25; $i++): ?>
                <div class="gallery-item" onclick="openModal('../images/courses/white-plains-golf-course/<?php echo $i; ?>.jpeg')">
                    <img src="../images/courses/white-plains-golf-course/<?php echo $i; ?>.jpeg" alt="White Plains Golf Course Photo <?php echo $i; ?>" loading="lazy">
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="reviews-section">
        <div class="container">
            <h2 style="text-align: center; margin-bottom: 2rem; color: #2c5530;">
                Course Reviews 
                <?php if ($avg_rating): ?>
                    <span style="color: #ffc107; font-size: 0.8em;">
                        (<?php echo $avg_rating; ?>/5 - <?php echo $total_reviews; ?> review<?php echo $total_reviews != 1 ? 's' : ''; ?>)
                    </span>
                <?php endif; ?>
            </h2>
            
            <?php if ($is_logged_in): ?>
            <form method="POST" class="review-form">
                <h3>Share Your Experience</h3>
                
                <?php if (isset($success_message)): ?>
                    <div class="success-message"><?php echo $success_message; ?></div>
                <?php endif; ?>
                
                <?php if (isset($error_message)): ?>
                    <div class="error-message"><?php echo $error_message; ?></div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="rating">Rating:</label>
                    <select name="rating" id="rating" required>
                        <option value="">Select a rating</option>
                        <option value="5">5 Stars - Excellent</option>
                        <option value="4">4 Stars - Very Good</option>
                        <option value="3">3 Stars - Good</option>
                        <option value="2">2 Stars - Fair</option>
                        <option value="1">1 Star - Poor</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="comment_text">Your Review:</label>
                    <textarea name="comment_text" id="comment_text" placeholder="Share your thoughts about White Plains Golf Course..." required></textarea>
                </div>
                
                <button type="submit" class="btn">Submit Review</button>
            </form>
            <?php else: ?>
            <div class="review-form">
                <p style="text-align: center; margin: 0;">
                    <a href="../login" style="color: #4a7c59; text-decoration: none; font-weight: 500;">Login</a> 
                    or 
                    <a href="../register" style="color: #4a7c59; text-decoration: none; font-weight: 500;">Register</a> 
                    to leave a review
                </p>
            </div>
            <?php endif; ?>
            
            <div class="reviews-list">
                <?php if (empty($comments)): ?>
                    <p style="text-align: center; color: #666; font-style: italic;">
                        No reviews yet. Be the first to share your experience!
                    </p>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                    <div class="review-item">
                        <div class="review-header">
                            <div>
                                <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                                <div class="review-rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star<?php echo $i <= $comment['rating'] ? '' : '-o'; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <small style="color: #666;">
                                <?php echo date('M j, Y', strtotime($comment['created_at'])); ?>
                            </small>
                        </div>
                        <div class="review-text">
                            <?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Image Modal -->
    <div id="imageModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <img id="modalImage" src="" alt="White Plains Golf Course">
        </div>
    </div>

    <script>
        // Image modal functionality
        function openModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            modal.style.display = 'block';
            modalImg.src = imageSrc;
        }

        // Close modal when clicking the X or outside the image
        document.querySelector('.close').onclick = function() {
            document.getElementById('imageModal').style.display = 'none';
        }

        document.getElementById('imageModal').onclick = function(event) {
            if (event.target === this) {
                this.style.display = 'none';
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.getElementById('imageModal').style.display = 'none';
            }
        });
    </script>
</body>
</html>