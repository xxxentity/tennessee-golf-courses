<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media - Tennessee Golf Courses</title>
    <meta name="description" content="Tennessee Golf Courses Media Hub - Your source for golf news, course reviews, and media content covering golf across the Volunteer State.">
    <link rel="stylesheet" href="/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="/images/logos/tab-logo.webp?v=6">
    <link rel="shortcut icon" href="/images/logos/tab-logo.webp?v=6">
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-7VPNPCDTBP');
    </script>
    
    <style>
        .media-page {
            padding-top: 90px;
            min-height: 80vh;
            background: linear-gradient(135deg, var(--bg-light), var(--bg-white));
        }
        
        .media-hero {
            text-align: center;
            padding: 80px 0;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--text-light);
            margin-bottom: 80px;
            margin-top: -140px;
        }
        
        .media-hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .media-hero p {
            font-size: 1.4rem;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
        }
        
        .media-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .media-sections {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 3rem;
            margin-bottom: 4rem;
        }
        
        .media-section {
            background: var(--bg-white);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .media-section:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-large);
        }
        
        .media-section .icon {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 2rem;
        }
        
        .media-section h2 {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        
        .media-section p {
            color: var(--text-gray);
            line-height: 1.8;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }
        
        .media-btn {
            background: var(--primary-color);
            color: var(--text-light);
            padding: 1rem 2rem;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .media-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }
        
        .media-btn.secondary {
            background: var(--secondary-color);
        }
        
        .media-btn.secondary:hover {
            background: var(--accent-color);
        }
        
        .stats-section {
            background: var(--bg-light);
            padding: 4rem 3rem;
            border-radius: 20px;
            text-align: center;
            margin-bottom: 4rem;
        }
        
        .stats-section h3 {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 2rem;
            font-weight: 600;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary-color);
            display: block;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: var(--text-gray);
            font-size: 1.1rem;
            font-weight: 500;
        }
        
        .featured-content {
            background: var(--bg-white);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
            text-align: center;
        }
        
        .featured-content h3 {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }
        
        .featured-content p {
            color: var(--text-gray);
            margin-bottom: 2rem;
            font-size: 1.1rem;
            line-height: 1.8;
        }
        
        @media (max-width: 768px) {
            .media-hero h1 {
                font-size: 2.5rem;
            }
            
            .media-hero p {
                font-size: 1.2rem;
            }
            
            .media-sections {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .media-section {
                padding: 2rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="media-page">
        <!-- Dynamic Navigation -->
        <?php include 'includes/navigation.php'; ?>
        
        <!-- Media Hero Section -->
        <div class="media-hero">
            <h1>Tennessee Golf Media Hub</h1>
            <p>Your source for golf news, reviews, and media content</p>
        </div>
        
        <!-- Media Content -->
        <div class="media-content">
            <!-- Media Sections -->
            <div class="media-sections">
                <div class="media-section">
                    <div class="icon">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <h2>Golf News</h2>
                    <p>Stay up-to-date with the latest news from Tennessee's golf scene. Tournament results, course updates, professional news, and everything happening in the Volunteer State's golf community.</p>
                    <a href="/news" class="media-btn">
                        <i class="fas fa-arrow-right"></i>
                        Browse News
                    </a>
                </div>
                
                <div class="media-section">
                    <div class="icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h2>Course Reviews</h2>
                    <p>Read honest reviews from fellow golfers about Tennessee's best courses. Share your own experiences and help others discover their next favorite place to play.</p>
                    <a href="/reviews" class="media-btn secondary">
                        <i class="fas fa-arrow-right"></i>
                        Read Reviews
                    </a>
                </div>
            </div>
            
            <!-- Stats Section -->
            <div class="stats-section">
                <h3>Tennessee Golf by the Numbers</h3>
                <p>Discover the scope of golf content we cover across the Volunteer State</p>
                
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="stat-number">90+</span>
                        <span class="stat-label">Golf Courses</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">500+</span>
                        <span class="stat-label">Course Reviews</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">50+</span>
                        <span class="stat-label">News Articles</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">1000+</span>
                        <span class="stat-label">Photos</span>
                    </div>
                </div>
            </div>
            
            <!-- Featured Content -->
            <div class="featured-content">
                <h3>Share Your Story</h3>
                <p>Have a great golf story, course recommendation, or news tip? We'd love to hear from you! Join our community of Tennessee golf enthusiasts and help us share the best of what the Volunteer State has to offer.</p>
                <a href="/contact" class="media-btn">
                    <i class="fas fa-paper-plane"></i>
                    Get in Touch
                </a>
            </div>
        </div>
    </div>
    
    <!-- Dynamic Footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>