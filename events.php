<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Golf Events & Tournaments - Tennessee Golf Courses</title>
    <meta name="description" content="Upcoming golf tournaments, charity events, and competitions at Tennessee golf courses. Find local golf events, scrambles, and championship tournaments near you.">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="/images/logos/tab-logo.webp?v=3">
    <link rel="shortcut icon" href="/images/logos/tab-logo.webp?v=3">
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-7VPNPCDTBP');
    </script>
    
    <style>
        .events-hero {
            background: linear-gradient(135deg, rgba(6, 78, 59, 0.9) 0%, rgba(16, 185, 129, 0.7) 50%, rgba(234, 88, 12, 0.5) 100%), 
                        url('https://images.unsplash.com/photo-1535131749006-b7f58c99034b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80');
            background-size: cover;
            background-position: center;
            padding: 50px 0 40px;
            text-align: center;
            color: white;
            position: relative;
            margin-top: 0px;
        }
        
        .events-hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .events-hero p {
            font-size: 1.3rem;
            opacity: 0.95;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .coming-soon-section {
            padding: 40px 0;
            background: #f8faf9;
        }
        
        .coming-soon-container {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }
        
        .coming-soon-icon {
            font-size: 5rem;
            color: var(--primary-color);
            margin-bottom: 2rem;
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .coming-soon-content h2 {
            font-size: 2.5rem;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            font-weight: 700;
        }
        
        .coming-soon-content p {
            font-size: 1.2rem;
            color: var(--text-gray);
            line-height: 1.8;
            margin-bottom: 2rem;
        }
        
        .features-preview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }
        
        .feature-card i {
            font-size: 2.5rem;
            color: var(--gold-color);
            margin-bottom: 1rem;
        }
        
        .feature-card h3 {
            font-size: 1.3rem;
            color: var(--text-dark);
            margin-bottom: 0.8rem;
            font-weight: 600;
        }
        
        .feature-card p {
            color: var(--text-gray);
            font-size: 1rem;
            line-height: 1.6;
        }
        
        .notification-signup {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            margin-top: 4rem;
        }
        
        .notification-signup h3 {
            font-size: 1.8rem;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }
        
        .notification-form {
            display: flex;
            gap: 1rem;
            max-width: 500px;
            margin: 2rem auto 0;
        }
        
        .notification-form input {
            flex: 1;
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
        }
        
        .notification-form button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .notification-form button:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .events-hero h1 {
                font-size: 2.5rem;
            }
            
            .events-hero p {
                font-size: 1.1rem;
            }
            
            .coming-soon-content h2 {
                font-size: 2rem;
            }
            
            .notification-form {
                flex-direction: column;
            }
            
            .notification-form button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/navigation.php'; ?>

    <!-- Events Hero Section -->
    <section class="events-hero">
        <div class="container">
            <h1>Golf Events & Tournaments</h1>
            <p>Your gateway to Tennessee's premier golf competitions and community events</p>
        </div>
    </section>

    <!-- Coming Soon Section -->
    <section class="coming-soon-section">
        <div class="container">
            <div class="coming-soon-container">
                <div class="coming-soon-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                
                <div class="coming-soon-content">
                    <h2>Events Calendar Coming Soon</h2>
                    <p>We're working diligently to bring you Tennessee's most comprehensive golf events platform. Soon, you'll have access to tournaments, charity scrambles, member-guest events, and competitive championships across the Volunteer State.</p>
                    
                    <div class="features-preview">
                        <div class="feature-card">
                            <i class="fas fa-trophy"></i>
                            <h3>Tournament Listings</h3>
                            <p>Browse upcoming amateur and professional tournaments at courses throughout Tennessee</p>
                        </div>
                        
                        <div class="feature-card">
                            <i class="fas fa-heart"></i>
                            <h3>Charity Events</h3>
                            <p>Find and participate in charity golf scrambles supporting local causes and organizations</p>
                        </div>
                        
                        <div class="feature-card">
                            <i class="fas fa-users"></i>
                            <h3>Social Competitions</h3>
                            <p>Connect with member-guest events, couples tournaments, and social golf gatherings</p>
                        </div>
                    </div>
                    
                    <div class="notification-signup">
                        <h3>Be the First to Know</h3>
                        <p>Register for updates and we'll notify you when our events calendar launches</p>
                        <form class="notification-form" onsubmit="handleNotificationSignup(event)">
                            <input type="email" placeholder="Enter your email address" required>
                            <button type="submit">Notify Me</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="/weather.js?v=4"></script>
    <script src="/script.js?v=4"></script>
    
    <script>
        function handleNotificationSignup(event) {
            event.preventDefault();
            alert('Thank you for your interest! We\'ll notify you when our events calendar launches.');
            event.target.reset();
        }
    </script>
</body>
</html>