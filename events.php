<?php
// Include session security and database
require_once 'includes/session-security.php';
require_once 'includes/seo.php';
require_once 'config/database.php';

// Set up SEO for events page
SEO::setupEventsPage();

// Start secure session
try {
    SecureSession::start();
} catch (Exception $e) {
    // Session expired or invalid - continue as guest
}

// Check if user is logged in
$is_logged_in = SecureSession::isLoggedIn();

// Professional tournaments (Major tournaments)
$professional_tournaments = [
    // LIV Golf 2025 Season
    [
        'title' => 'LIV Golf Riyadh',
        'league' => 'LIV',
        'date_start' => '2025-02-06',
        'date_end' => '2025-02-08',
        'venue' => 'Riyadh Golf Club',
        'location' => 'Riyadh, Saudi Arabia'
    ],
    [
        'title' => 'LIV Golf Adelaide',
        'league' => 'LIV',
        'date_start' => '2025-02-14',
        'date_end' => '2025-02-16',
        'venue' => 'The Grange Golf Club',
        'location' => 'Adelaide, Australia'
    ],
    [
        'title' => 'LIV Golf Hong Kong',
        'league' => 'LIV',
        'date_start' => '2025-03-07',
        'date_end' => '2025-03-09',
        'venue' => 'Hong Kong Golf Club at Fanling',
        'location' => 'Hong Kong'
    ],
    [
        'title' => 'LIV Golf Singapore',
        'league' => 'LIV',
        'date_start' => '2025-03-14',
        'date_end' => '2025-03-16',
        'venue' => 'Sentosa Golf Club',
        'location' => 'Singapore'
    ],
    [
        'title' => 'LIV Golf Miami',
        'league' => 'LIV',
        'date_start' => '2025-04-04',
        'date_end' => '2025-04-06',
        'venue' => 'Trump National Doral',
        'location' => 'Miami, FL'
    ],
    [
        'title' => 'Masters Tournament',
        'league' => 'PGA',
        'date_start' => '2025-04-10',
        'date_end' => '2025-04-13',
        'venue' => 'Augusta National Golf Club',
        'location' => 'Augusta, GA'
    ],
    [
        'title' => 'LIV Golf Mexico City',
        'league' => 'LIV',
        'date_start' => '2025-04-25',
        'date_end' => '2025-04-27',
        'venue' => 'Club de Golf Chapultepec',
        'location' => 'Mexico City, Mexico'
    ],
    [
        'title' => 'LIV Golf Korea',
        'league' => 'LIV',
        'date_start' => '2025-05-02',
        'date_end' => '2025-05-04',
        'venue' => 'Jack Nicklaus Golf Club Korea',
        'location' => 'Incheon, South Korea'
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
        'title' => 'LIV Golf Virginia',
        'league' => 'LIV',
        'date_start' => '2025-06-06',
        'date_end' => '2025-06-08',
        'venue' => 'Robert Trent Jones Golf Club',
        'location' => 'Virginia, USA'
    ],
    [
        'title' => 'U.S. Open',
        'league' => 'PGA',
        'date_start' => '2025-06-19',
        'date_end' => '2025-06-22',
        'venue' => 'Oakmont Country Club',
        'location' => 'Oakmont, PA'
    ],
    [
        'title' => 'LIV Golf Dallas',
        'league' => 'LIV',
        'date_start' => '2025-06-27',
        'date_end' => '2025-06-29',
        'venue' => 'Maridoe Golf Club',
        'location' => 'Dallas, TX'
    ],
    [
        'title' => 'LIV Golf Andalucia',
        'league' => 'LIV',
        'date_start' => '2025-07-11',
        'date_end' => '2025-07-13',
        'venue' => 'Real Club Valderrama',
        'location' => 'Andalucia, Spain'
    ],
    [
        'title' => 'The Open Championship',
        'league' => 'PGA',
        'date_start' => '2025-07-17',
        'date_end' => '2025-07-20',
        'venue' => 'Royal Portrush',
        'location' => 'Northern Ireland'
    ],
    [
        'title' => 'LIV Golf UK',
        'league' => 'LIV',
        'date_start' => '2025-07-25',
        'date_end' => '2025-07-27',
        'venue' => 'JCB Golf & Country Club',
        'location' => 'England, UK'
    ],
    [
        'title' => 'LIV Golf Chicago',
        'league' => 'LIV',
        'date_start' => '2025-08-08',
        'date_end' => '2025-08-10',
        'venue' => 'Bolingbrook Golf Club',
        'location' => 'Chicago, IL'
    ],
    [
        'title' => 'FedEx St. Jude Championship',
        'league' => 'PGA',
        'date_start' => '2025-08-14',
        'date_end' => '2025-08-17',
        'venue' => 'TPC Southwind',
        'location' => 'Memphis, TN'
    ],
    [
        'title' => 'LIV Golf Indianapolis',
        'league' => 'LIV',
        'date_start' => '2025-08-15',
        'date_end' => '2025-08-17',
        'venue' => 'The Club at Chatham Hills',
        'location' => 'Indianapolis, IN'
    ],
    [
        'title' => 'LIV Golf Team Championship',
        'league' => 'LIV',
        'date_start' => '2025-08-22',
        'date_end' => '2025-08-24',
        'venue' => 'The Cardinal at Saint John\'s',
        'location' => 'Michigan, USA'
    ]
];

// Tennessee local events
$tennessee_events = [
    // 2025 TGA Championships
    [
        'title' => 'Mid-Amateur Four-Ball Championship',
        'date_start' => '2025-06-07',
        'date_end' => '2025-06-08',
        'venue' => 'Sevierville Golf Club - River Course',
        'location' => 'Sevierville, TN',
        'organizer' => 'Tennessee Golf Association'
    ],
    [
        'title' => 'Parent-Child Championship',
        'date_start' => '2025-06-14',
        'date_end' => '2025-06-15',
        'venue' => 'Stonehenge Golf Club',
        'location' => 'Fairfield Glade, TN',
        'organizer' => 'Tennessee Golf Association'
    ],
    [
        'title' => 'Tennessee Junior Amateur Championship',
        'date_start' => '2025-06-16',
        'date_end' => '2025-06-18',
        'venue' => 'GreyStone Golf Club',
        'location' => 'Dickson, TN',
        'organizer' => 'Tennessee Golf Association'
    ],
    [
        'title' => 'Tennessee Girls Junior Championship',
        'date_start' => '2025-06-16',
        'date_end' => '2025-06-18',
        'venue' => 'GreyStone Golf Club',
        'location' => 'Dickson, TN',
        'organizer' => 'Tennessee Golf Association'
    ],
    [
        'title' => 'Tennessee State Amateur Championship',
        'date_start' => '2025-06-24',
        'date_end' => '2025-06-27',
        'venue' => 'Holston Hills Country Club',
        'location' => 'Knoxville, TN',
        'organizer' => 'Tennessee Golf Association'
    ],
    [
        'title' => 'Tennessee Women\'s Amateur Championship',
        'date_start' => '2025-07-08',
        'date_end' => '2025-07-10',
        'venue' => 'Chattanooga Golf & Country Club',
        'location' => 'Chattanooga, TN',
        'organizer' => 'Tennessee Golf Association'
    ],
    [
        'title' => 'Tennessee State Open Championship',
        'date_start' => '2025-07-09',
        'date_end' => '2025-07-11',
        'venue' => 'Grasslands Club - Foxland Course',
        'location' => 'Gallatin, TN',
        'organizer' => 'Tennessee Golf Association'
    ],
    [
        'title' => 'Senior & Super Senior Match Play',
        'date_start' => '2025-07-15',
        'date_end' => '2025-07-18',
        'venue' => 'Oak Ridge Country Club',
        'location' => 'Oak Ridge, TN',
        'organizer' => 'Tennessee Golf Association'
    ],
    [
        'title' => 'Tennessee Women\'s Open Championship',
        'date_start' => '2025-07-24',
        'date_end' => '2025-07-26',
        'venue' => 'Stonehenge Golf Club',
        'location' => 'Fairfield Glade, TN',
        'organizer' => 'Tennessee Golf Association'
    ],
    [
        'title' => 'Senior & Super Senior Amateur Championship',
        'date_start' => '2025-08-05',
        'date_end' => '2025-08-07',
        'venue' => 'Belle Meade Country Club',
        'location' => 'Nashville, TN',
        'organizer' => 'Tennessee Golf Association'
    ],
    [
        'title' => 'Women\'s Senior & Mid-Amateur Championship',
        'date_start' => '2025-08-12',
        'date_end' => '2025-08-13',
        'venue' => 'Johnson City Country Club',
        'location' => 'Johnson City, TN',
        'organizer' => 'Tennessee Golf Association'
    ],
    [
        'title' => 'Men\'s Match Play Championship',
        'date_start' => '2025-08-12',
        'date_end' => '2025-08-15',
        'venue' => 'Vanderbilt Legends Club - South Course',
        'location' => 'Franklin, TN',
        'organizer' => 'Tennessee Golf Association'
    ],
    [
        'title' => 'Tennessee Mid-Amateur Championship',
        'date_start' => '2025-08-25',
        'date_end' => '2025-08-27',
        'venue' => 'Tennessee National Golf Club',
        'location' => 'Loudon, TN',
        'organizer' => 'Tennessee Golf Association'
    ],
    [
        'title' => '57th TN PGA Professional Championship',
        'date_start' => '2025-08-25',
        'date_end' => '2025-08-27',
        'venue' => 'Links at Kahite',
        'location' => 'Vonore, TN',
        'organizer' => 'Tennessee PGA'
    ],
    [
        'title' => 'Women\'s Four-Ball Championship',
        'date_start' => '2025-08-27',
        'date_end' => '2025-08-28',
        'venue' => 'Hermitage Golf Course - General\'s Retreat',
        'location' => 'Old Hickory, TN',
        'organizer' => 'Tennessee Golf Association'
    ],
    // Junior Golf Events (Sneds Tour)
    [
        'title' => 'Elite @ Old Fort Junior Tournament',
        'date_start' => '2025-09-06',
        'date_end' => '2025-09-07',
        'venue' => 'Old Fort Golf Course',
        'location' => 'Murfreesboro, TN',
        'organizer' => 'Sneds Tour / Tennessee Golf Foundation'
    ],
    [
        'title' => 'Tennessee Junior Cup',
        'date_start' => '2025-09-27',
        'date_end' => '2025-09-28',
        'venue' => 'The Grove',
        'location' => 'College Grove, TN',
        'organizer' => 'Sneds Tour / Tennessee Golf Foundation'
    ],
    [
        'title' => 'Open @ Montgomery Bell Junior Tournament',
        'date_start' => '2025-10-04',
        'date_end' => '2025-10-05',
        'venue' => 'Montgomery Bell State Park Golf Course',
        'location' => 'Burns, TN',
        'organizer' => 'Sneds Tour / Tennessee Golf Foundation'
    ]
];

// Combine all events and organize by month
$all_events = [];

// Add professional events
foreach ($professional_tournaments as $tournament) {
    $all_events[] = array_merge($tournament, ['type' => 'professional']);
}

// Add Tennessee events
foreach ($tennessee_events as $tournament) {
    $all_events[] = array_merge($tournament, ['type' => 'local']);
}

// Sort all events by date
usort($all_events, function($a, $b) {
    return strtotime($a['date_start']) - strtotime($b['date_start']);
});

// Group events by month
$events_by_month = [];
foreach ($all_events as $event) {
    $month_year = date('F Y', strtotime($event['date_start']));
    if (!isset($events_by_month[$month_year])) {
        $events_by_month[$month_year] = ['professional' => [], 'local' => []];
    }
    $events_by_month[$month_year][$event['type']][] = $event;
}

// Add empty months for full year view
$months_2025 = [
    'January 2025', 'February 2025', 'March 2025', 'April 2025', 
    'May 2025', 'June 2025', 'July 2025', 'August 2025',
    'September 2025', 'October 2025', 'November 2025', 'December 2025'
];

// Ensure all months are represented
foreach ($months_2025 as $month) {
    if (!isset($events_by_month[$month])) {
        $events_by_month[$month] = ['professional' => [], 'local' => []];
    }
}

// Sort months chronologically
$sorted_events = [];
foreach ($months_2025 as $month) {
    if (isset($events_by_month[$month])) {
        $sorted_events[$month] = $events_by_month[$month];
    }
}
$events_by_month = $sorted_events;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php echo SEO::generateMetaTags(); ?>
    <?php echo SEO::generateNewsKeywords(['golf tournaments', 'LIV Golf', 'PGA Tour', 'Tennessee', 'events', 'championships', 'sports']); ?>
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
        
        /* Main Schedule Section */
        .schedule-section {
            padding: 4rem 0;
            background: white;
        }
        
        .schedule-container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .month-section {
            margin-bottom: 4rem;
        }
        
        .month-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .month-title {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }
        
        .tournament-section {
            margin-bottom: 2.5rem;
        }
        
        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .section-icon {
            font-size: 1.1rem;
        }
        
        .major-tournaments .section-title {
            color: #1e40af;
        }
        
        .local-tournaments .section-title {
            color: var(--primary-color);
        }
        
        .tournament-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .tournament-item {
            display: flex;
            align-items: center;
            padding: 1.25rem 0;
            border-bottom: 1px solid #f0f4f3;
            transition: all 0.2s ease;
        }
        
        .tournament-item:last-child {
            border-bottom: none;
        }
        
        .tournament-item:hover {
            background: #f8faf9;
            padding-left: 1rem;
            border-radius: 8px;
        }
        
        .tournament-date {
            min-width: 120px;
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1rem;
        }
        
        .tournament-info {
            flex: 1;
            margin-left: 1.5rem;
        }
        
        .tournament-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }
        
        .tournament-venue {
            color: var(--text-gray);
            font-size: 0.95rem;
        }
        
        .tournament-location {
            color: var(--text-gray);
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }
        
        .league-badge {
            background: #f3f4f6;
            color: #6b7280;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-left: 1rem;
        }
        
        .league-badge.liv {
            background: #fef3f2;
            color: #dc2626;
        }
        
        .league-badge.pga {
            background: #eff6ff;
            color: #2563eb;
        }
        
        .league-badge.tn {
            background: #fff7ed;
            color: #ea580c;
        }
        
        .no-events {
            text-align: center;
            color: var(--text-gray);
            font-style: italic;
            padding: 2rem;
            background: #f8faf9;
            border-radius: 8px;
            margin: 1rem 0;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .events-hero h1 {
                font-size: 2.5rem;
            }
            
            .tournament-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }
            
            .tournament-date {
                min-width: auto;
                font-size: 0.9rem;
            }
            
            .tournament-info {
                margin-left: 0;
            }
            
            .league-badge {
                margin-left: 0;
                margin-top: 0.5rem;
            }
        }
    </style>
    
    <?php echo SEO::generateStructuredData(); ?>
</head>

<body>
    <?php include 'includes/navigation.php'; ?>

    <!-- Events Hero Section -->
    <section class="events-hero">
        <div class="container">
            <h1>Golf Tournament Schedule</h1>
            <p>LIV Golf, PGA Tour, and Tennessee tournament calendar</p>
        </div>
    </section>

    <!-- Tournament Schedule -->
    <section class="schedule-section">
        <div class="container">
            <div class="schedule-container">
                <?php if (empty($events_by_month)): ?>
                    <div class="no-events">
                        <p>No tournaments scheduled at this time. Check back soon for updates!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($events_by_month as $month => $events): ?>
                    <div class="month-section">
                        <div class="month-header">
                            <h2 class="month-title">📅 <?php echo $month; ?></h2>
                        </div>
                        
                        <!-- Major Tournaments -->
                        <?php if (!empty($events['professional'])): ?>
                        <div class="tournament-section major-tournaments">
                            <h3 class="section-title">
                                <i class="fas fa-trophy section-icon"></i>
                                Major Tournaments
                            </h3>
                            
                            <ul class="tournament-list">
                                <?php foreach ($events['professional'] as $tournament): ?>
                                <li class="tournament-item">
                                    <div class="tournament-date">
                                        <?php 
                                        if ($tournament['date_start'] === $tournament['date_end']) {
                                            echo date('M j', strtotime($tournament['date_start']));
                                        } else {
                                            $start = date('M j', strtotime($tournament['date_start']));
                                            $end = date('j', strtotime($tournament['date_end']));
                                            echo $start . '-' . $end;
                                        }
                                        ?>
                                    </div>
                                    
                                    <div class="tournament-info">
                                        <div class="tournament-name"><?php echo htmlspecialchars($tournament['title']); ?></div>
                                        <div class="tournament-venue"><?php echo htmlspecialchars($tournament['venue']); ?></div>
                                        <div class="tournament-location"><?php echo htmlspecialchars($tournament['location']); ?></div>
                                    </div>
                                    
                                    <?php if (isset($tournament['league'])): ?>
                                    <span class="league-badge <?php echo strtolower($tournament['league']); ?>">
                                        <?php echo $tournament['league']; ?>
                                    </span>
                                    <?php endif; ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Tennessee Events -->
                        <?php if (!empty($events['local'])): ?>
                        <div class="tournament-section local-tournaments">
                            <h3 class="section-title">
                                <i class="fas fa-golf-ball section-icon"></i>
                                Tennessee Events
                            </h3>
                            
                            <ul class="tournament-list">
                                <?php foreach ($events['local'] as $tournament): ?>
                                <li class="tournament-item">
                                    <div class="tournament-date">
                                        <?php 
                                        if ($tournament['date_start'] === $tournament['date_end']) {
                                            echo date('M j', strtotime($tournament['date_start']));
                                        } else {
                                            $start = date('M j', strtotime($tournament['date_start']));
                                            $end = date('j', strtotime($tournament['date_end']));
                                            echo $start . '-' . $end;
                                        }
                                        ?>
                                    </div>
                                    
                                    <div class="tournament-info">
                                        <div class="tournament-name"><?php echo htmlspecialchars($tournament['title']); ?></div>
                                        <div class="tournament-venue"><?php echo htmlspecialchars($tournament['venue']); ?></div>
                                        <div class="tournament-location"><?php echo htmlspecialchars($tournament['location']); ?></div>
                                    </div>
                                    
                                    <span class="league-badge tn">
                                        TN
                                    </span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Show message if no events in month -->
                        <?php if (empty($events['professional']) && empty($events['local'])): ?>
                        <div class="no-events">
                            <p>No tournaments scheduled for <?php echo $month; ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <!-- Weather scripts now loaded centrally via navigation.php -->

</body>
</html>