<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Reviews - Tennessee Golf Courses</title>
    <meta name="description" content="Read authentic reviews of golf equipment, accessories, and products to help you make informed purchasing decisions.">
    <link rel="stylesheet" href="/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .coming-soon-page {
            padding-top: 140px;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--bg-light), var(--bg-white));
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .coming-soon-container {
            text-align: center;
            max-width: 600px;
            padding: 3rem 2rem;
            background: var(--bg-white);
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
        }
        
        .coming-soon-icon {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 2rem;
        }
        
        .coming-soon-title {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .coming-soon-subtitle {
            font-size: 1.2rem;
            color: var(--text-gray);
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .back-home-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .back-home-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }
        
        .feature-preview {
            background: var(--bg-light);
            padding: 1.5rem;
            border-radius: 10px;
            margin: 2rem 0;
            border-left: 4px solid var(--secondary-color);
        }
        
        .feature-list {
            text-align: left;
            color: var(--text-dark);
            margin-top: 1rem;
        }
        
        .feature-list li {
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .feature-list i {
            color: var(--secondary-color);
        }
    </style>
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include 'includes/navigation.php'; ?>

    <div class="coming-soon-page">
        <div class="coming-soon-container">
            <i class="fas fa-star coming-soon-icon"></i>
            <h1 class="coming-soon-title">Product Reviews</h1>
            <p class="coming-soon-subtitle">
                We're building a comprehensive product review section where you'll find honest, detailed reviews of golf equipment, accessories, and gear.
            </p>
            
            <div class="feature-preview">
                <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Coming Soon Features:</h3>
                <ul class="feature-list">
                    <li><i class="fas fa-golf-ball"></i> Golf Club Reviews & Comparisons</li>
                    <li><i class="fas fa-tshirt"></i> Apparel & Accessories Reviews</li>
                    <li><i class="fas fa-shopping-cart"></i> Equipment Buying Guides</li>
                    <li><i class="fas fa-users"></i> Community Ratings & Reviews</li>
                    <li><i class="fas fa-tags"></i> Best Deals & Recommendations</li>
                </ul>
            </div>
            
            <a href="index.php" class="back-home-btn">
                <i class="fas fa-home"></i> Back to Home
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <img src="images/logos/logo.png" alt="Tennessee Golf Courses" class="footer-logo-image">
                    </div>
                    <p>Your premier destination for discovering the best golf courses across Tennessee.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="courses.php">Golf Courses</a></li>
                        <li><a href="reviews.php">Reviews</a></li>
                        <li><a href="news.php">News</a></li>
                        <li><a href="about.php">About Us</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Regions</h4>
                    <ul>
                        <li><a href="courses.php?region=Nashville Area">Nashville Area</a></li>
                        <li><a href="courses.php?region=Chattanooga Area">Chattanooga Area</a></li>
                        <li><a href="courses.php?region=Knoxville Area">Knoxville Area</a></li>
                        <li><a href="courses.php?region=Memphis Area">Memphis Area</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact</h4>
                    <ul>
                        <li><i class="fas fa-envelope"></i> info@tennesseegolfcourses.com</li>
                        <li><i class="fas fa-phone"></i> (615) 555-GOLF</li>
                        <li><i class="fas fa-map-marker-alt"></i> Nashville, TN</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Tennessee Golf Courses. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>