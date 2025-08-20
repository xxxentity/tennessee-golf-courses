<?php
// Include session security
require_once 'includes/session-security.php';

// Start secure session
try {
    SecureSession::start();
} catch (Exception $e) {
    // Session expired or invalid - user not logged in
}

// Check if user is logged in
$is_logged_in = SecureSession::isLoggedIn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contests - Tennessee Golf Courses</title>
    <meta name="description" content="Golf contests, competitions, and challenges for Tennessee golfers. Win prizes and compete with fellow golfers.">
    <link rel="stylesheet" href="/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <?php include 'includes/favicon.php'; ?>
    
    <style>
        .contests-page {
            padding-top: 90px;
            min-height: 80vh;
            background: linear-gradient(135deg, var(--bg-light), var(--bg-white));
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 4rem 0;
            text-align: center;
            margin-bottom: 3rem;
            border-radius: 15px;
        }
        
        .hero-section h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .hero-section p {
            font-size: 1.3rem;
            opacity: 0.9;
        }
        
        .coming-soon {
            background: var(--bg-white);
            padding: 4rem;
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            text-align: center;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .coming-soon i {
            font-size: 5rem;
            color: var(--secondary-color);
            margin-bottom: 2rem;
        }
        
        .coming-soon h2 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 2.5rem;
        }
        
        .coming-soon p {
            color: var(--text-gray);
            font-size: 1.2rem;
            line-height: 1.8;
            margin-bottom: 2rem;
        }
        
        .back-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1rem 2rem;
            border-radius: 30px;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }
    </style>
</head>

<body>
    <div class="contests-page">
        <!-- Dynamic Navigation -->
        <?php include 'includes/navigation.php'; ?>
        
        <div class="container">
            <div class="hero-section">
                <h1><i class="fas fa-trophy"></i> Golf Contests & Competitions</h1>
                <p>Compete, Win Prizes, and Showcase Your Skills</p>
            </div>
            
            <div class="coming-soon">
                <i class="fas fa-hammer"></i>
                <h2>Coming Soon!</h2>
                <p>
                    We're working on exciting golf contests and competitions for Tennessee golfers. 
                    Soon you'll be able to compete in various challenges, win prizes, and show off your skills 
                    to the Tennessee golf community.
                </p>
                <p>
                    Stay tuned for monthly contests, seasonal tournaments, and special challenges!
                </p>
                <a href="/community" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Community
                </a>
            </div>
        </div>
    </div>
    
    <!-- Dynamic Footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>