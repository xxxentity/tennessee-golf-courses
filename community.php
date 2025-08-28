<?php
require_once 'includes/init.php';
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
    
    <?php include 'includes/favicon.php'; ?>
    
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
        
        <!-- Dynamic Community Events and Contests -->
        <?php 
        echo '<div style="padding: 2rem; background: white; margin: 2rem; border-radius: 10px;">';
        echo '<h2>Testing PHP...</h2>';
        echo '<p>Current date: ' . date('F j, Y') . '</p>';
        echo '</div>';
        
        include 'includes/community-events.php'; 
        ?>
    </div>
    
    <!-- Dynamic Footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>