<?php
// Include session security
require_once 'includes/session-security.php';

// Start secure session
try {
    SecureSession::start();
} catch (Exception $e) {
    // Session expired or invalid - user not logged in
}

// Check if user is logged in and verified
$is_logged_in = SecureSession::isLoggedIn();
$is_verified = $is_logged_in ? SecureSession::get('email_verified', false) : false;

// Sample contest data (in production, this would come from database)
$active_contest = [
    'id' => 1,
    'title' => 'Best Golf Shot Photo Contest',
    'type' => 'photo',
    'description' => 'Share your most incredible golf shot photo for a chance to win a brand new TaylorMade driver!',
    'prize' => 'TaylorMade Stealth 2 Driver',
    'prize_value' => '$599',
    'prize_image' => '/images/contests/taylormade-driver.webp',
    'end_date' => '2025-09-15',
    'entries' => 127,
    'image' => '/images/contests/photo-contest-hero.webp'
];

$upcoming_contests = [
    [
        'title' => 'PGA Tour Championship Prediction Challenge',
        'type' => 'prediction',
        'start_date' => '2025-09-01',
        'prize' => 'VIP Tournament Tickets'
    ],
    [
        'title' => 'Tennessee Golf Trivia Championship',
        'type' => 'trivia',
        'start_date' => '2025-09-10',
        'prize' => '$500 Pro Shop Credit'
    ]
];

$past_winners = [
    [
        'contest' => 'Summer Hole-in-One Challenge',
        'winner' => 'Mike Johnson',
        'location' => 'Nashville, TN',
        'prize' => 'Callaway Golf Set',
        'image' => '/images/contests/winner1.webp',
        'date' => 'August 2025'
    ],
    [
        'contest' => 'Course Photo Contest',
        'winner' => 'Sarah Davis',
        'location' => 'Memphis, TN',
        'prize' => 'Golf Getaway Package',
        'image' => '/images/contests/winner2.webp',
        'date' => 'July 2025'
    ],
    [
        'contest' => 'Score Challenge',
        'winner' => 'Tom Wilson',
        'location' => 'Knoxville, TN',
        'prize' => 'Titleist Pro V1 Dozen',
        'image' => '/images/contests/winner3.webp',
        'date' => 'June 2025'
    ]
];

// Calculate days remaining
$end_date = new DateTime($active_contest['end_date']);
$today = new DateTime();
$interval = $today->diff($end_date);
$days_remaining = $interval->days;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Golf Contests & Giveaways - Tennessee Golf Courses</title>
    <meta name="description" content="Enter exciting golf contests and win amazing prizes! Photo contests, score challenges, predictions, trivia, and equipment giveaways for Tennessee golfers.">
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
        /* Contest Hero Section */
        .contest-hero {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('/images/contests/golf-contest-hero.webp');
            background-size: cover;
            background-position: center;
            min-height: 85vh;
            display: flex;
            align-items: center;
            color: white;
            position: relative;
            overflow: hidden;
            padding-bottom: 3rem;
        }
        
        .contest-hero-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }
        
        .contest-info h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .contest-type {
            display: inline-block;
            background: var(--secondary-color);
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 1rem;
            text-transform: uppercase;
        }
        
        .contest-description {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }
        
        .prize-showcase {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 15px;
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .prize-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--gold-color);
            margin-bottom: 0.5rem;
        }
        
        .countdown-timer {
            background: rgba(0,0,0,0.3);
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            margin-top: 2rem;
        }
        
        .countdown-label {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            opacity: 0.9;
        }
        
        .countdown-numbers {
            display: flex;
            justify-content: center;
            gap: 2rem;
        }
        
        .countdown-unit {
            text-align: center;
        }
        
        .countdown-number {
            font-size: 3rem;
            font-weight: 700;
            display: block;
            color: var(--gold-color);
        }
        
        .countdown-text {
            font-size: 0.9rem;
            opacity: 0.8;
            text-transform: uppercase;
        }
        
        /* Entry Form Section */
        .entry-form-section {
            background: white;
            padding: 4rem 0;
            position: relative;
            margin-top: 2rem;
            z-index: 10;
            border-radius: 0;
            box-shadow: none;
        }
        
        .entry-form-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .form-header h2 {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .entries-count {
            display: inline-block;
            background: var(--bg-light);
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            color: var(--text-gray);
            font-weight: 600;
        }
        
        /* Authentication Gate */
        .auth-gate {
            background: var(--bg-light);
            padding: 4rem;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        
        .auth-gate-icon {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 2rem;
        }
        
        .auth-gate h3 {
            font-size: 2rem;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }
        
        .auth-gate p {
            color: var(--text-gray);
            font-size: 1.1rem;
            margin-bottom: 2rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .auth-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-auth {
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .btn-login {
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }
        
        .btn-login:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-register {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }
        
        .verify-warning {
            background: #fff3cd;
            color: #856404;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .verify-warning i {
            font-size: 1.5rem;
        }
        
        .entry-form {
            background: var(--bg-light);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        
        .form-group {
            margin-bottom: 2rem;
        }
        
        .form-group label {
            display: block;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.75rem;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-family: inherit;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        .file-upload {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        
        .file-upload input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .file-upload-label {
            display: block;
            padding: 1rem;
            background: white;
            border: 2px dashed var(--primary-color);
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .file-upload-label:hover {
            background: var(--bg-light);
        }
        
        .submit-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.25rem 3rem;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }
        
        /* Contest Rules */
        .contest-rules {
            margin-top: 3rem;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            border: 2px solid var(--bg-light);
        }
        
        .rules-toggle {
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            padding: 1rem;
            background: var(--bg-light);
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        
        .rules-toggle h3 {
            margin: 0;
            color: var(--primary-color);
        }
        
        .rules-content {
            display: none;
            padding: 1rem;
        }
        
        .rules-content.active {
            display: block;
        }
        
        .rules-list {
            list-style: none;
            padding: 0;
        }
        
        .rules-list li {
            padding: 0.75rem 0;
            padding-left: 2rem;
            position: relative;
        }
        
        .rules-list li:before {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            left: 0;
            color: var(--primary-color);
        }
        
        /* Upcoming Contests */
        .upcoming-section {
            background: var(--bg-light);
            padding: 4rem 0;
        }
        
        .upcoming-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .upcoming-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .upcoming-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .contest-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .upcoming-card h3 {
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }
        
        .start-date {
            color: var(--text-gray);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        .upcoming-prize {
            color: var(--gold-color);
            font-weight: 600;
        }
        
        /* Past Winners */
        .winners-section {
            padding: 4rem 0;
            background: white;
        }
        
        .winners-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .winner-card {
            background: var(--bg-light);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .winner-card:hover {
            transform: scale(1.03);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .winner-image {
            height: 200px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            position: relative;
        }
        
        .winner-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--gold-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .winner-info {
            padding: 2rem;
        }
        
        .winner-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }
        
        .winner-location {
            color: var(--text-gray);
            margin-bottom: 1rem;
        }
        
        .winner-contest {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .winner-prize {
            color: var(--gold-color);
            font-weight: 600;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .contest-hero-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .contest-info h1 {
                font-size: 2rem;
            }
            
            .countdown-numbers {
                gap: 1rem;
            }
            
            .countdown-number {
                font-size: 2rem;
            }
            
            .entry-form {
                padding: 2rem;
            }
            
            .auth-buttons {
                flex-direction: column;
            }
            
            .btn-auth {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include 'includes/navigation.php'; ?>

    <!-- Contest Hero Section -->
    <section class="contest-hero">
        <div class="contest-hero-content">
            <div class="contest-info">
                <span class="contest-type"><?php echo $active_contest['type']; ?> Contest</span>
                <h1><?php echo $active_contest['title']; ?></h1>
                <p class="contest-description"><?php echo $active_contest['description']; ?></p>
                
                <div class="prize-showcase">
                    <p style="opacity: 0.9; margin-bottom: 0.5rem;">Grand Prize</p>
                    <div class="prize-value"><?php echo $active_contest['prize_value']; ?></div>
                    <h3 style="font-size: 1.5rem; margin-bottom: 0;"><?php echo $active_contest['prize']; ?></h3>
                </div>
            </div>
            
            <div class="countdown-timer">
                <p class="countdown-label">Contest Ends In</p>
                <div class="countdown-numbers">
                    <div class="countdown-unit">
                        <span class="countdown-number" id="days"><?php echo $days_remaining; ?></span>
                        <span class="countdown-text">Days</span>
                    </div>
                    <div class="countdown-unit">
                        <span class="countdown-number" id="hours">00</span>
                        <span class="countdown-text">Hours</span>
                    </div>
                    <div class="countdown-unit">
                        <span class="countdown-number" id="minutes">00</span>
                        <span class="countdown-text">Minutes</span>
                    </div>
                    <div class="countdown-unit">
                        <span class="countdown-number" id="seconds">00</span>
                        <span class="countdown-text">Seconds</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Entry Form Section -->
    <section class="entry-form-section">
        <div class="entry-form-container">
            <div class="form-header">
                <h2>Enter to Win!</h2>
                <span class="entries-count"><?php echo $active_contest['entries']; ?> Entries So Far</span>
            </div>
            
            <?php if (!$is_logged_in): ?>
                <!-- Not Logged In Gate -->
                <div class="auth-gate">
                    <i class="fas fa-lock auth-gate-icon"></i>
                    <h3>Account Required</h3>
                    <p>You must be logged in with a verified account to enter our contests. Join Tennessee Golf Courses today to participate in exciting giveaways!</p>
                    <div class="auth-buttons">
                        <a href="/login?redirect=contests" class="btn-auth btn-login">Log In</a>
                        <a href="/register?redirect=contests" class="btn-auth btn-register">Create Account</a>
                    </div>
                </div>
            <?php elseif (!$is_verified): ?>
                <!-- Logged In but Not Verified -->
                <div class="auth-gate">
                    <div class="verify-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <strong>Email Verification Required</strong><br>
                            Please verify your email address to enter contests. Check your inbox for the verification link.
                        </div>
                    </div>
                    <i class="fas fa-envelope auth-gate-icon"></i>
                    <h3>Verify Your Email</h3>
                    <p>Contest entries require a verified email address. This helps us contact winners and ensures fair participation.</p>
                    <div class="auth-buttons">
                        <a href="/profile" class="btn-auth btn-login">Go to Profile</a>
                        <button class="btn-auth btn-register" onclick="resendVerification()">Resend Verification Email</button>
                    </div>
                </div>
            <?php else: ?>
                <!-- Logged In and Verified - Show Entry Form -->
                <form class="entry-form" id="contestForm">
                    <input type="hidden" name="contest_id" value="<?php echo $active_contest['id']; ?>">
                    
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" value="<?php echo SecureSession::get('first_name', '') . ' ' . SecureSession::get('last_name', ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" value="<?php echo SecureSession::get('email', ''); ?>" readonly style="background: #f0f0f0;">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone">
                    </div>
                    
                    <div class="form-group">
                        <label for="location">City, State *</label>
                        <input type="text" id="location" name="location" placeholder="e.g., Nashville, TN" required>
                    </div>
                    
                    <?php if ($active_contest['type'] === 'photo'): ?>
                    <div class="form-group">
                        <label for="photo">Upload Your Photo *</label>
                        <div class="file-upload">
                            <input type="file" id="photo" name="photo" accept="image/*" required>
                            <label for="photo" class="file-upload-label">
                                <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: var(--primary-color); display: block; margin-bottom: 0.5rem;"></i>
                                <span>Click to upload or drag and drop</span><br>
                                <small style="color: var(--text-gray);">JPG, PNG or WEBP (MAX. 5MB)</small>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="caption">Photo Caption</label>
                        <textarea id="caption" name="caption" rows="3" placeholder="Tell us about your shot..."></textarea>
                    </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="favorite_course">Favorite Tennessee Golf Course</label>
                        <select id="favorite_course" name="favorite_course">
                            <option value="">Select a course...</option>
                            <option value="belle-meade">Belle Meade Country Club</option>
                            <option value="bear-trace">Bear Trace at Harrison Bay</option>
                            <option value="gaylord-springs">Gaylord Springs Golf Links</option>
                            <option value="hermitage">Hermitage Golf Course</option>
                            <option value="memphis-country-club">Memphis Country Club</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label style="display: flex; align-items: center; cursor: pointer;">
                            <input type="checkbox" name="newsletter" style="width: auto; margin-right: 0.5rem;">
                            Sign me up for the Tennessee Golf Courses newsletter
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label style="display: flex; align-items: flex-start; cursor: pointer;">
                            <input type="checkbox" name="terms" required style="width: auto; margin-right: 0.5rem; margin-top: 0.25rem;">
                            <span>I agree to the contest rules and terms & conditions *</span>
                        </label>
                    </div>
                    
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-trophy"></i> Submit Entry
                    </button>
                </form>
            <?php endif; ?>
            
            <!-- Contest Rules -->
            <div class="contest-rules">
                <div class="rules-toggle" onclick="toggleRules()">
                    <h3>Contest Rules & Terms</h3>
                    <i class="fas fa-chevron-down" id="rulesToggleIcon"></i>
                </div>
                <div class="rules-content" id="rulesContent">
                    <ul class="rules-list">
                        <li>Must have a verified Tennessee Golf Courses account to enter</li>
                        <li>Contest is open to Tennessee residents 18 years or older</li>
                        <li>One entry per person per contest</li>
                        <li>Winners will be selected randomly and notified via email</li>
                        <li>Prize must be claimed within 30 days of notification</li>
                        <li>By entering, you grant Tennessee Golf Courses permission to use your submission</li>
                        <li>Employees and immediate family members are not eligible</li>
                        <li>No purchase necessary to enter or win</li>
                        <li>Void where prohibited by law</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Upcoming Contests -->
    <section class="upcoming-section">
        <div class="container">
            <div class="section-header">
                <h2>Upcoming Contests</h2>
                <p>Don't miss out on these exciting opportunities!</p>
            </div>
            
            <div class="upcoming-grid">
                <?php foreach ($upcoming_contests as $contest): ?>
                <div class="upcoming-card">
                    <div class="contest-icon">
                        <?php
                        $icon = match($contest['type']) {
                            'prediction' => 'fa-chart-line',
                            'trivia' => 'fa-brain',
                            'score' => 'fa-flag',
                            'hole-in-one' => 'fa-golf-ball',
                            default => 'fa-trophy'
                        };
                        ?>
                        <i class="fas <?php echo $icon; ?>"></i>
                    </div>
                    <h3><?php echo $contest['title']; ?></h3>
                    <p class="start-date">Starts: <?php echo date('F j, Y', strtotime($contest['start_date'])); ?></p>
                    <p class="upcoming-prize">Prize: <?php echo $contest['prize']; ?></p>
                </div>
                <?php endforeach; ?>
                
                <div class="upcoming-card" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white;">
                    <div class="contest-icon" style="background: rgba(255,255,255,0.2);">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h3>Get Notified</h3>
                    <p style="opacity: 0.9;">Be the first to know about new contests!</p>
                    <?php if ($is_logged_in): ?>
                        <button class="btn btn-white" style="margin-top: 1rem;">Sign Up for Alerts</button>
                    <?php else: ?>
                        <a href="/register" class="btn btn-white" style="margin-top: 1rem; display: inline-block;">Create Account</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Past Winners -->
    <section class="winners-section">
        <div class="container">
            <div class="section-header">
                <h2>Hall of Winners</h2>
                <p>Congratulations to our recent contest champions!</p>
            </div>
            
            <div class="winners-grid">
                <?php foreach ($past_winners as $winner): ?>
                <div class="winner-card">
                    <div class="winner-image">
                        <i class="fas fa-trophy"></i>
                        <span class="winner-badge">WINNER</span>
                    </div>
                    <div class="winner-info">
                        <h3 class="winner-name"><?php echo $winner['winner']; ?></h3>
                        <p class="winner-location"><i class="fas fa-map-marker-alt"></i> <?php echo $winner['location']; ?></p>
                        <p class="winner-contest"><?php echo $winner['contest']; ?></p>
                        <p class="winner-prize">Won: <?php echo $winner['prize']; ?></p>
                        <p style="color: var(--text-gray); font-size: 0.9rem; margin-top: 0.5rem;"><?php echo $winner['date']; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script src="/weather.js?v=4"></script>
    <script src="/script.js?v=4"></script>
    <script>
        // Countdown Timer
        function updateCountdown() {
            const endDate = new Date('<?php echo $active_contest['end_date']; ?>T23:59:59').getTime();
            const now = new Date().getTime();
            const distance = endDate - now;

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('days').textContent = days.toString().padStart(2, '0');
            document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');

            if (distance < 0) {
                document.querySelector('.countdown-timer').innerHTML = '<p style="font-size: 1.5rem;">Contest Ended</p>';
            }
        }

        setInterval(updateCountdown, 1000);
        updateCountdown();

        // Toggle Rules
        function toggleRules() {
            const content = document.getElementById('rulesContent');
            const icon = document.getElementById('rulesToggleIcon');
            
            content.classList.toggle('active');
            
            if (content.classList.contains('active')) {
                icon.className = 'fas fa-chevron-up';
            } else {
                icon.className = 'fas fa-chevron-down';
            }
        }

        // File Upload Preview
        document.getElementById('photo')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const label = this.nextElementSibling;
            
            if (file) {
                label.innerHTML = `
                    <i class="fas fa-check-circle" style="font-size: 2rem; color: var(--primary-color); display: block; margin-bottom: 0.5rem;"></i>
                    <span>${file.name}</span><br>
                    <small style="color: var(--text-gray);">Click to change</small>
                `;
            }
        });

        // Form Submission
        document.getElementById('contestForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // In production, this would submit to a backend
            alert('Thank you for entering! We\'ll notify winners via email. Good luck!');
            
            // Reset form
            this.reset();
            
            // Reset file upload label
            const fileLabel = document.querySelector('.file-upload-label');
            if (fileLabel) {
                fileLabel.innerHTML = `
                    <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: var(--primary-color); display: block; margin-bottom: 0.5rem;"></i>
                    <span>Click to upload or drag and drop</span><br>
                    <small style="color: var(--text-gray);">JPG, PNG or WEBP (MAX. 5MB)</small>
                `;
            }
        });

        // Resend Verification
        function resendVerification() {
            // In production, this would call the backend
            alert('Verification email has been resent. Please check your inbox.');
        }
    </script>
</body>
</html>