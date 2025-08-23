<?php
// Include session security and database
require_once 'includes/session-security.php';
require_once 'config/database.php';

// Start secure session
try {
    SecureSession::start();
} catch (Exception $e) {
    // Session expired or invalid - continue as guest
}

// Check if user is logged in
$is_logged_in = SecureSession::isLoggedIn();

// Featured tournaments (manually curated)
$featured_tournaments = [
    [
        'id' => 1,
        'title' => 'LIV Golf Nashville',
        'type' => 'professional',
        'league' => 'LIV',
        'date_start' => '2025-06-13',
        'date_end' => '2025-06-15',
        'venue' => 'The Grove Golf Course',
        'location' => 'College Grove, TN',
        'prize_money' => '$25,000,000',
        'image' => '/images/logos/logo.webp',
        'status' => 'upcoming'
    ],
    [
        'id' => 2,
        'title' => 'FedEx St. Jude Championship',
        'type' => 'professional',
        'league' => 'PGA',
        'date_start' => '2025-08-14',
        'date_end' => '2025-08-17',
        'venue' => 'TPC Southwind',
        'location' => 'Memphis, TN',
        'prize_money' => '$20,000,000',
        'image' => '/images/logos/logo.webp',
        'status' => 'upcoming'
    ],
    [
        'id' => 3,
        'title' => 'Tennessee State Amateur Championship',
        'type' => 'local',
        'league' => 'TGA',
        'date_start' => '2025-07-21',
        'date_end' => '2025-07-24',
        'venue' => 'Belle Meade Country Club',
        'location' => 'Nashville, TN',
        'prize_money' => null,
        'image' => '/images/logos/logo.webp',
        'status' => 'upcoming'
    ]
];

// Professional tournaments
$professional_tournaments = [
    // LIV Golf Events
    [
        'title' => 'LIV Golf Miami',
        'league' => 'LIV',
        'date_start' => '2025-04-04',
        'date_end' => '2025-04-06',
        'venue' => 'Trump National Doral',
        'location' => 'Miami, FL'
    ],
    [
        'title' => 'LIV Golf Adelaide',
        'league' => 'LIV',
        'date_start' => '2025-04-26',
        'date_end' => '2025-04-28',
        'venue' => 'The Grange Golf Club',
        'location' => 'Adelaide, Australia'
    ],
    [
        'title' => 'LIV Golf Nashville',
        'league' => 'LIV',
        'date_start' => '2025-06-13',
        'date_end' => '2025-06-15',
        'venue' => 'The Grove Golf Course',
        'location' => 'College Grove, TN'
    ],
    
    // PGA Tour Events
    [
        'title' => 'Masters Tournament',
        'league' => 'PGA',
        'date_start' => '2025-04-10',
        'date_end' => '2025-04-13',
        'venue' => 'Augusta National Golf Club',
        'location' => 'Augusta, GA'
    ],
    [
        'title' => 'PGA Championship',
        'league' => 'PGA',
        'date_start' => '2025-05-15',
        'date_end' => '2025-05-18',
        'venue' => 'Quail Hollow Club',
        'location' => 'Charlotte, NC'
    ],
    [
        'title' => 'FedEx St. Jude Championship',
        'league' => 'PGA',
        'date_start' => '2025-08-14',
        'date_end' => '2025-08-17',
        'venue' => 'TPC Southwind',
        'location' => 'Memphis, TN'
    ]
];

// Tennessee local events
$tennessee_events = [
    [
        'title' => 'Tennessee State Amateur Championship',
        'date_start' => '2025-07-21',
        'date_end' => '2025-07-24',
        'venue' => 'Belle Meade Country Club',
        'location' => 'Nashville, TN',
        'organizer' => 'Tennessee Golf Association'
    ],
    [
        'title' => 'Tennessee Four-Ball Championship',
        'date_start' => '2025-06-02',
        'date_end' => '2025-06-04',
        'venue' => 'The Honors Course',
        'location' => 'Chattanooga, TN',
        'organizer' => 'Tennessee Golf Association'
    ],
    [
        'title' => 'Hermitage Classic Charity Tournament',
        'date_start' => '2025-09-15',
        'date_end' => '2025-09-15',
        'venue' => 'Hermitage Golf Course',
        'location' => 'Nashville, TN',
        'organizer' => 'Nashville Golf Foundation'
    ],
    [
        'title' => 'Tennessee Mid-Amateur Championship',
        'date_start' => '2025-08-25',
        'date_end' => '2025-08-27',
        'venue' => 'Nashville Golf & Athletic Club',
        'location' => 'Nashville, TN',
        'organizer' => 'Tennessee Golf Association'
    ],
    [
        'title' => 'Cheekwood Invitational',
        'date_start' => '2025-05-10',
        'date_end' => '2025-05-11',
        'venue' => 'Cheekwood Golf Club',
        'location' => 'Nashville, TN',
        'organizer' => 'Cheekwood Golf Club'
    ]
];

// Sort events by date
usort($professional_tournaments, function($a, $b) {
    return strtotime($a['date_start']) - strtotime($b['date_start']);
});

usort($tennessee_events, function($a, $b) {
    return strtotime($a['date_start']) - strtotime($b['date_start']);
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Golf Tournaments & Events - Tennessee Golf Courses</title>
    <meta name="description" content="Complete guide to LIV Golf, PGA Tour, and Tennessee golf tournaments. Find professional and local golf events, championships, and competitions near you.">
    <link rel="stylesheet" href="styles.css">
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
        .events-hero {
            background: linear-gradient(135deg, rgba(6, 78, 59, 0.9) 0%, rgba(16, 185, 129, 0.7) 50%, rgba(234, 88, 12, 0.5) 100%), 
                        url('https://images.unsplash.com/photo-1535131749006-b7f58c99034b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80');
            background-size: cover;
            background-position: center;
            padding: 5rem 0 4rem;
            text-align: center;
            color: white;
            position: relative;
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
        
        /* Featured Tournaments */
        .featured-section {
            padding: 4rem 0;
            background: white;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .section-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }
        
        .section-header p {
            font-size: 1.1rem;
            color: var(--text-gray);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .featured-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .tournament-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .tournament-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .tournament-image {
            height: 200px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .tournament-image img {
            max-height: 80px;
            max-width: 120px;
            filter: brightness(0) invert(1);
        }
        
        .league-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255,255,255,0.9);
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.8rem;
        }
        
        .league-liv { color: #ff6b35; }
        .league-pga { color: #1e40af; }
        .league-tga { color: #059669; }
        
        .tournament-content {
            padding: 2rem;
        }
        
        .tournament-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }
        
        .tournament-dates {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }
        
        .tournament-venue {
            color: var(--text-gray);
            margin-bottom: 0.5rem;
        }
        
        .tournament-location {
            color: var(--text-gray);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .prize-money {
            background: linear-gradient(135deg, var(--gold-color), #d97706);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            text-align: center;
            margin-top: 1rem;
        }
        
        /* Professional Tournaments */
        .professional-section {
            padding: 4rem 0;
            background: #f8faf9;
        }
        
        .tournaments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .league-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        
        .league-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .league-logo {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            font-size: 1.2rem;
        }
        
        .league-logo.liv { background: #ff6b35; }
        .league-logo.pga { background: #1e40af; }
        
        .league-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
        }
        
        .tournament-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .tournament-item {
            padding: 1rem 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .tournament-item:last-child {
            border-bottom: none;
        }
        
        .tournament-info h4 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }
        
        .tournament-info .venue {
            color: var(--text-gray);
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }
        
        .tournament-info .location {
            color: var(--text-gray);
            font-size: 0.85rem;
        }
        
        .tournament-date {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 0.9rem;
            white-space: nowrap;
        }
        
        /* Tennessee Events */
        .tennessee-section {
            padding: 4rem 0;
            background: white;
        }
        
        .events-list {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .event-card {
            background: white;
            border: 2px solid #f0f0f0;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .event-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .event-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
            gap: 1rem;
        }
        
        .event-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }
        
        .event-date {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1rem;
            white-space: nowrap;
        }
        
        .event-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .event-detail {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-gray);
        }
        
        .event-detail i {
            color: var(--primary-color);
            width: 16px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .events-hero h1 {
                font-size: 2.5rem;
            }
            
            .featured-grid {
                grid-template-columns: 1fr;
            }
            
            .tournaments-grid {
                grid-template-columns: 1fr;
            }
            
            .event-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .event-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <?php include 'includes/navigation.php'; ?>

    <!-- Events Hero Section -->
    <section class="events-hero">
        <div class="container">
            <h1>Tennessee Golf Tournament Central</h1>
            <p>Complete guide to LIV Golf, PGA Tour, and Tennessee golf tournaments & events</p>
        </div>
    </section>

    <!-- Featured Tournaments -->
    <section class="featured-section">
        <div class="container">
            <div class="section-header">
                <h2>Featured Tournaments</h2>
                <p>Don't miss these upcoming major tournaments and championship events</p>
            </div>
            
            <div class="featured-grid">
                <?php foreach ($featured_tournaments as $tournament): ?>
                <div class="tournament-card">
                    <div class="tournament-image">
                        <img src="<?php echo $tournament['image']; ?>" alt="Tournament Logo">
                        <span class="league-badge league-<?php echo strtolower($tournament['league']); ?>">
                            <?php echo $tournament['league']; ?>
                        </span>
                    </div>
                    
                    <div class="tournament-content">
                        <h3 class="tournament-title"><?php echo htmlspecialchars($tournament['title']); ?></h3>
                        
                        <div class="tournament-dates">
                            <?php 
                            $start = date('M j', strtotime($tournament['date_start']));
                            $end = date('j, Y', strtotime($tournament['date_end']));
                            echo $start . ' - ' . $end;
                            ?>
                        </div>
                        
                        <div class="tournament-venue">
                            <i class="fas fa-golf-ball"></i> <?php echo htmlspecialchars($tournament['venue']); ?>
                        </div>
                        
                        <div class="tournament-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo htmlspecialchars($tournament['location']); ?>
                        </div>
                        
                        <?php if ($tournament['prize_money']): ?>
                        <div class="prize-money">
                            Prize Fund: <?php echo $tournament['prize_money']; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Professional Tournaments -->
    <section class="professional-section">
        <div class="container">
            <div class="section-header">
                <h2>Professional Tournaments</h2>
                <p>LIV Golf and PGA Tour events to follow</p>
            </div>
            
            <div class="tournaments-grid">
                <!-- LIV Golf Events -->
                <div class="league-section">
                    <div class="league-header">
                        <div class="league-logo liv">LIV</div>
                        <div>
                            <div class="league-title">LIV Golf Events</div>
                            <p style="color: var(--text-gray); margin: 0;">Professional Golf League</p>
                        </div>
                    </div>
                    
                    <ul class="tournament-list">
                        <?php foreach ($professional_tournaments as $tournament): ?>
                            <?php if ($tournament['league'] === 'LIV'): ?>
                            <li class="tournament-item">
                                <div class="tournament-info">
                                    <h4><?php echo htmlspecialchars($tournament['title']); ?></h4>
                                    <div class="venue"><?php echo htmlspecialchars($tournament['venue']); ?></div>
                                    <div class="location"><?php echo htmlspecialchars($tournament['location']); ?></div>
                                </div>
                                <div class="tournament-date">
                                    <?php 
                                    $start = date('M j', strtotime($tournament['date_start']));
                                    $end = date('j', strtotime($tournament['date_end']));
                                    echo $start . '-' . $end;
                                    ?>
                                </div>
                            </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- PGA Tour Events -->
                <div class="league-section">
                    <div class="league-header">
                        <div class="league-logo pga">PGA</div>
                        <div>
                            <div class="league-title">PGA Tour Events</div>
                            <p style="color: var(--text-gray); margin: 0;">Professional Golfers' Association</p>
                        </div>
                    </div>
                    
                    <ul class="tournament-list">
                        <?php foreach ($professional_tournaments as $tournament): ?>
                            <?php if ($tournament['league'] === 'PGA'): ?>
                            <li class="tournament-item">
                                <div class="tournament-info">
                                    <h4><?php echo htmlspecialchars($tournament['title']); ?></h4>
                                    <div class="venue"><?php echo htmlspecialchars($tournament['venue']); ?></div>
                                    <div class="location"><?php echo htmlspecialchars($tournament['location']); ?></div>
                                </div>
                                <div class="tournament-date">
                                    <?php 
                                    $start = date('M j', strtotime($tournament['date_start']));
                                    $end = date('j', strtotime($tournament['date_end']));
                                    echo $start . '-' . $end;
                                    ?>
                                </div>
                            </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Tennessee Local Events -->
    <section class="tennessee-section">
        <div class="container">
            <div class="section-header">
                <h2>Tennessee Golf Events</h2>
                <p>Local tournaments, championships, and golf events across Tennessee</p>
            </div>
            
            <div class="events-list">
                <?php foreach ($tennessee_events as $event): ?>
                <div class="event-card">
                    <div class="event-header">
                        <div>
                            <h3 class="event-title"><?php echo htmlspecialchars($event['title']); ?></h3>
                            <div class="event-date">
                                <?php 
                                if ($event['date_start'] === $event['date_end']) {
                                    echo date('F j, Y', strtotime($event['date_start']));
                                } else {
                                    $start = date('M j', strtotime($event['date_start']));
                                    $end = date('j, Y', strtotime($event['date_end']));
                                    echo $start . ' - ' . $end;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="event-details">
                        <div class="event-detail">
                            <i class="fas fa-golf-ball"></i>
                            <span><?php echo htmlspecialchars($event['venue']); ?></span>
                        </div>
                        
                        <div class="event-detail">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo htmlspecialchars($event['location']); ?></span>
                        </div>
                        
                        <div class="event-detail">
                            <i class="fas fa-users"></i>
                            <span><?php echo htmlspecialchars($event['organizer']); ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="/weather.js?v=4"></script>
    <script src="/script.js?v=4"></script>
</body>
</html>