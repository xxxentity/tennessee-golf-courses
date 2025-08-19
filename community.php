<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community - Tennessee Golf Courses</title>
    <meta name="description" content="Join the Tennessee Golf Courses community - connect with fellow golfers, share experiences, and discover new courses across the Volunteer State.">
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
        .community-page {
            padding-top: 90px;
            min-height: 80vh;
            background: linear-gradient(135deg, var(--bg-light), var(--bg-white));
        }
        
        .community-hero {
            text-align: center;
            padding: 80px 0;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--text-light);
            margin-bottom: 80px;
            margin-top: -140px;
        }
        
        .community-hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .community-hero p {
            font-size: 1.4rem;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
        }
        
        .construction-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
            text-align: center;
        }
        
        .construction-icon {
            font-size: 8rem;
            color: var(--primary-color);
            margin-bottom: 2rem;
            opacity: 0.8;
        }
        
        .construction-message {
            background: var(--bg-white);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
            margin-bottom: 3rem;
        }
        
        .construction-message h2 {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        
        .construction-message p {
            font-size: 1.2rem;
            line-height: 1.8;
            color: var(--text-gray);
            margin-bottom: 1.5rem;
        }
        
        .coming-soon-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .feature-card {
            background: var(--bg-white);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
        }
        
        .feature-card .icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .feature-card h3 {
            font-size: 1.3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .feature-card p {
            color: var(--text-gray);
            line-height: 1.6;
        }
        
        .notify-section {
            background: var(--bg-light);
            padding: 3rem;
            border-radius: 20px;
            text-align: center;
            margin-top: 3rem;
        }
        
        .notify-section h3 {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .notify-section p {
            font-size: 1.1rem;
            color: var(--text-gray);
            margin-bottom: 2rem;
        }
        
        .notify-btn {
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
        
        .notify-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }
        
        @media (max-width: 768px) {
            .community-hero h1 {
                font-size: 2.5rem;
            }
            
            .community-hero p {
                font-size: 1.2rem;
            }
            
            .construction-icon {
                font-size: 6rem;
            }
            
            .construction-message h2 {
                font-size: 2rem;
            }
            
            .construction-message p {
                font-size: 1.1rem;
            }
            
            .coming-soon-features {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="community-page">
        <!-- Dynamic Navigation -->
        <?php include 'includes/navigation.php'; ?>
        
        <!-- Community Hero Section -->
        <div class="community-hero">
            <h1>Tennessee Golf Community</h1>
            <p>Connecting golfers across the Volunteer State</p>
        </div>
        
        <!-- Construction Content -->
        <div class="construction-content">
            <div class="construction-icon">
                <i class="fas fa-tools"></i>
            </div>
            
            <div class="construction-message">
                <h2>Under Construction</h2>
                <p>We're working hard to build something amazing for the Tennessee golf community. Our new Community section will be the hub for golfers to connect, share experiences, and discover new opportunities across the state.</p>
                <p>Stay tuned for exciting features that will enhance your golfing experience in Tennessee!</p>
            </div>
            
            <!-- Coming Soon Features -->
            <div class="coming-soon-features">
                <div class="feature-card">
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Golfer Connections</h3>
                    <p>Connect with fellow golfers in your area and find playing partners for your next round.</p>
                </div>
                
                <div class="feature-card">
                    <div class="icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3>Events & Tournaments</h3>
                    <p>Discover local tournaments, golf events, and community gatherings across Tennessee.</p>
                </div>
                
                <div class="feature-card">
                    <div class="icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3>Course Discussions</h3>
                    <p>Share tips, course reviews, and engage in discussions about your favorite Tennessee courses.</p>
                </div>
                
                <div class="feature-card">
                    <div class="icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3>Achievements</h3>
                    <p>Track your golf achievements and see how you stack up against other Tennessee golfers.</p>
                </div>
            </div>
            
            <!-- Notification Section -->
            <div class="notify-section">
                <h3>Be the First to Know</h3>
                <p>Want to be notified when our Community features launch? Sign up for our newsletter to stay updated.</p>
                <a href="/newsletter-subscribe.php" class="notify-btn">
                    <i class="fas fa-bell"></i>
                    Get Notified
                </a>
            </div>
        </div>
    </div>
    
    <!-- Dynamic Footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>