<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Tennessee Golf Courses</title>
    <meta name="description" content="Learn about Tennessee Golf Courses - a community-driven platform built by golfers, for golfers to discover the best courses across the Volunteer State.">
    <link rel="stylesheet" href="/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/logos/tab-logo.webp?v=2">
    <link rel="shortcut icon" href="/images/logos/tab-logo.webp?v=2">
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-7VPNPCDTBP');
    </script>
    
    <style>
        .about-page {
            padding-top: 90px;
            min-height: 40vh;
            background: linear-gradient(135deg, var(--bg-light), var(--bg-white));
        }
        
        .about-hero {
            text-align: center;
            padding: 60px 0;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--text-light);
            margin-bottom: 80px;
            margin-top: -140px;
        }
        
        .about-hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .about-hero p {
            font-size: 1.3rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .about-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .about-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            margin-bottom: 80px;
            align-items: center;
        }
        
        .about-section.reverse {
            grid-template-columns: 1fr 1fr;
        }
        
        .about-section.reverse .about-text {
            order: 2;
        }
        
        .about-section.reverse .about-visual {
            order: 1;
        }
        
        .about-text h2 {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        
        .about-text p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--text-gray);
            margin-bottom: 1.5rem;
        }
        
        .about-visual {
            background: var(--bg-white);
            padding: 40px;
            border-radius: 16px;
            box-shadow: var(--shadow-medium);
            border: 1px solid rgba(6, 78, 59, 0.1);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }
        
        .stat-item {
            text-align: center;
            padding: 24px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--text-light);
            border-radius: 12px;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            margin: 80px 0;
        }
        
        .feature-item {
            text-align: center;
            padding: 40px 24px;
            background: var(--bg-white);
            border-radius: 16px;
            box-shadow: var(--shadow-medium);
            border: 1px solid rgba(6, 78, 59, 0.1);
            transition: all 0.3s ease;
        }
        
        .feature-item:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-heavy);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            color: var(--text-light);
            font-size: 2rem;
        }
        
        .feature-item h3 {
            font-size: 1.3rem;
            color: var(--primary-color);
            margin-bottom: 16px;
            font-weight: 600;
        }
        
        .feature-item p {
            color: var(--text-gray);
            line-height: 1.6;
        }
        
        .mission-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--text-light);
            padding: 80px 0;
            margin: 80px 0;
            text-align: center;
        }
        
        .mission-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .mission-section h2 {
            font-size: 2.5rem;
            margin-bottom: 2rem;
            font-weight: 700;
        }
        
        .mission-section p {
            font-size: 1.2rem;
            line-height: 1.8;
            opacity: 0.9;
            margin-bottom: 2rem;
        }
        
        .cta-button {
            background: var(--text-light);
            color: var(--primary-color);
            padding: 16px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .cta-button:hover {
            background: var(--bg-light);
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }
        
        .values-section {
            margin: 80px 0;
        }
        
        .values-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
        }
        
        .value-item {
            display: flex;
            gap: 20px;
            padding: 32px;
            background: var(--bg-white);
            border-radius: 16px;
            box-shadow: var(--shadow-light);
            border: 1px solid rgba(6, 78, 59, 0.1);
        }
        
        .value-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        
        .value-content h3 {
            color: var(--primary-color);
            margin-bottom: 12px;
            font-weight: 600;
        }
        
        .value-content p {
            color: var(--text-gray);
            line-height: 1.4;
            font-size: 0.95rem;
        }
        
        .diff-features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
        }
        
        .diff-feature-item {
            text-align: center;
            padding: 40px 24px;
            background: var(--bg-white);
            border-radius: 16px;
            box-shadow: var(--shadow-medium);
            border: 1px solid rgba(6, 78, 59, 0.1);
            transition: all 0.3s ease;
        }
        
        .diff-feature-item:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-heavy);
        }
        
        .diff-feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            color: var(--text-light);
            font-size: 2rem;
        }
        
        .diff-feature-item h3 {
            font-size: 1.3rem;
            color: var(--primary-color);
            margin-bottom: 16px;
            font-weight: 600;
        }
        
        .diff-feature-item p {
            color: var(--text-gray);
            line-height: 1.6;
        }
        
        .title-sections {
            display: flex;
            flex-direction: column;
            gap: 32px;
        }
        
        .title-section {
            background: var(--bg-white);
            padding: 24px;
            border-radius: 12px;
            box-shadow: var(--shadow-light);
            border: 1px solid rgba(6, 78, 59, 0.1);
        }
        
        .title-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }
        
        .title-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        
        .title-header h3 {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1.2rem;
            margin: 0;
        }
        
        .title-section p {
            color: var(--text-gray);
            line-height: 1.6;
            margin: 0;
        }
        
        @media (max-width: 768px) {
            .about-section,
            .features-grid,
            .values-grid,
            .diff-features-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            
            .about-hero h1 {
                font-size: 2rem;
            }
            
            .about-text h2 {
                font-size: 2rem;
            }
            
            .about-section.reverse .about-text,
            .about-section.reverse .about-visual {
                order: unset;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .value-item {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include 'includes/navigation.php'; ?>

    <div class="about-page">
        <!-- About Hero -->
        <section class="about-hero">
            <div class="container">
                <h1>About Tennessee Golf Courses</h1>
                <p>Built by golfers, for golfers - helping you discover the best courses across the Volunteer State</p>
            </div>
        </section>

        <!-- About Content -->
        <section class="about-content">
            <!-- Our Story Section -->
            <div class="about-section">
                <div class="about-text">
                    <h2>Our Story</h2>
                    <p>Tennessee Golf Courses was born from a simple idea: every golfer deserves to discover their next great round. As passionate golfers ourselves, we knew the frustration of not knowing which courses were worth the drive or the green fees.</p>
                    <p>We created this platform to be the definitive resource for Tennessee golf - a place where real golfers share honest reviews, discover hidden gems, and connect with courses that match their style and skill level.</p>
                    <p>From the rolling hills of East Tennessee to the river courses along the Mississippi, we're here to help you explore every fairway the Volunteer State has to offer.</p>
                </div>
                <div class="about-visual">
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-number">150+</div>
                            <div class="stat-label">Golf Courses</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">500+</div>
                            <div class="stat-label">Reviews</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">1000+</div>
                            <div class="stat-label">Golfers</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">95</div>
                            <div class="stat-label">Counties</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3>Course Discovery</h3>
                    <p>Find courses by location, difficulty, or amenities. From championship layouts to beginner-friendly tracks, we help you find the perfect match.</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Real Reviews</h3>
                    <p>Read honest reviews from fellow golfers who've played the courses. No fake ratings - just authentic experiences from the community.</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3>Quality Focus</h3>
                    <p>We carefully curate courses based on quality, maintenance, and overall experience. Every course in our directory meets our standards.</p>
                </div>
            </div>

            <!-- What Makes Us Different -->
            <div class="about-section reverse">
                <div class="about-text">
                    <h2>What Makes Us Different</h2>
                    <p><strong>Local Expertise:</strong> We focus exclusively on Tennessee golf, giving you deep knowledge of local courses, conditions, and insider tips you won't find anywhere else.</p>
                    <p><strong>Community-Driven:</strong> Our reviews come from real golfers, not paid promotions. When you read a review on our site, you're getting honest feedback from someone who's walked those fairways.</p>
                    <p><strong>Golfer-First Approach:</strong> We're not trying to sell you anything - we're here to help you find great golf. No booking fees, no hidden agendas, just good information from golfers who care.</p>
                </div>
                <div class="about-visual">
                    <div class="title-sections">
                        <div class="title-section">
                            <div class="title-header">
                                <div class="title-icon">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <h3>Passion</h3>
                            </div>
                            <p>Built by golfers who understand the joy of discovering a new favorite course. We know the excitement of finding that perfect track where every hole challenges and delights you.</p>
                        </div>
                        <div class="title-section">
                            <div class="title-header">
                                <div class="title-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <h3>Trust</h3>
                            </div>
                            <p>Honest reviews and recommendations you can count on. Every review comes from real golfers sharing genuine experiences to help you make the best choice for your next round.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Mission Section -->
        <section class="mission-section">
            <div class="mission-content">
                <h2>Our Mission</h2>
                <p>To connect Tennessee golfers with exceptional golf experiences while supporting local courses and growing the golf community across the Volunteer State.</p>
                <p>We believe that great golf is about more than just low scores - it's about beautiful settings, well-maintained courses, and the camaraderie that comes from sharing the game we love.</p>
                <a href="/contact" class="cta-button">
                    <i class="fas fa-envelope" style="margin-right: 8px;"></i>
                    Get In Touch
                </a>
            </div>
        </section>

        <!-- Values Section -->
        <section class="about-content">
            <div class="values-section">
                <h2 style="text-align: center; color: var(--primary-color); margin-bottom: 3rem; font-size: 2.5rem;">Our Values</h2>
                <div class="values-grid">
                    <div class="value-item">
                        <div class="value-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div class="value-content">
                            <h3>Community First</h3>
                            <p>We're part of the Tennessee golf community, not just observers. Your success on the course is our success.</p>
                        </div>
                    </div>
                    <div class="value-item">
                        <div class="value-icon">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <div class="value-content">
                            <h3>Honest & Fair</h3>
                            <p>We provide unbiased information to help you make informed decisions about where to play your next round.</p>
                        </div>
                    </div>
                    <div class="value-item">
                        <div class="value-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <div class="value-content">
                            <h3>Supporting Local</h3>
                            <p>Every course we feature is part of Tennessee's golf heritage. We're proud to support local businesses and golf traditions.</p>
                        </div>
                    </div>
                    <div class="value-item">
                        <div class="value-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <div class="value-content">
                            <h3>Always Improving</h3>
                            <p>We're constantly enhancing our platform based on feedback from golfers like you. Your experience drives our innovation.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="footer" style="margin-top: 80px;">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <img src="/images/logos/logo.webp" alt="Tennessee Golf Courses" class="footer-logo-image">
                    </div>
                    <p>Your premier destination for discovering the best golf courses across Tennessee.</p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/profile.php?id=61579553544749" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="/courses">Courses</a></li>
                        <li><a href="/reviews">Reviews</a></li>
                        <li><a href="/news">News</a></li>
                        <li><a href="/events">Events</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Regions</h4>
                    <ul>
                        <li><a href="/courses?region=Nashville Area">Nashville Area</a></li>
                        <li><a href="/courses?region=Chattanooga Area">Chattanooga Area</a></li>
                        <li><a href="/courses?region=Knoxville Area">Knoxville Area</a></li>
                        <li><a href="/courses?region=Memphis Area">Memphis Area</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="/privacy-policy">Privacy Policy</a></li>
                        <li><a href="/terms-of-service">Terms of Service</a></li>
                        <li><a href="/contact">Contact Us</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Tennessee Golf Courses. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="/weather.js?v=4"></script>
    <script src="/script.js?v=4"></script>
</body>
</html>