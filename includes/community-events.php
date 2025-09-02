<?php
/**
 * Community Events and Contests System
 * Dynamically generates events and contests for the community page
 */

// Get current date info
$current_month = date('n');
$current_year = date('Y');
$current_date = date('Y-m-d');

// Define recurring annual tournaments and events in Tennessee
$annual_events = [
    [
        'name' => 'Tennessee State Amateur Championship',
        'month' => 6,
        'description' => 'The premier amateur golf championship in Tennessee',
        'location' => 'Various Tennessee courses',
        'type' => 'tournament',
        'website' => 'https://www.tngolf.org/'
    ],
    [
        'name' => 'Tennessee Four-Ball Championship',
        'month' => 8,
        'description' => 'Annual four-ball championship featuring the best amateur teams',
        'location' => 'Premium Tennessee courses',
        'type' => 'tournament',
        'website' => 'https://www.tngolf.org/'
    ],
    [
        'name' => 'FedEx St. Jude Championship',
        'month' => 8,
        'description' => 'PGA Tour event at TPC Southwind in Memphis',
        'location' => 'TPC Southwind, Memphis',
        'type' => 'professional',
        'website' => 'https://www.pgatour.com/'
    ],
    [
        'name' => 'Tennessee Mid-Amateur Championship',
        'month' => 9,
        'description' => 'Championship for amateur golfers 25 and older',
        'location' => 'Various Tennessee courses',
        'type' => 'tournament',
        'website' => 'https://www.tngolf.org/'
    ],
    [
        'name' => 'Spring Golf Season Kickoff',
        'month' => 3,
        'description' => 'Celebrate the start of golf season across Tennessee',
        'location' => 'Statewide',
        'type' => 'community',
        'website' => null
    ],
    [
        'name' => 'Tennessee Junior Golf Championships',
        'month' => 7,
        'description' => 'Junior golf championships across multiple age divisions',
        'location' => 'Various Tennessee courses',
        'type' => 'tournament',
        'website' => 'https://www.tnjgt.org/'
    ]
];

// Define ongoing contests and challenges
$ongoing_contests = [
    [
        'name' => 'Tennessee Course Review Challenge',
        'description' => 'Review 5 different Tennessee courses this year and win prizes',
        'deadline' => 'December 31, ' . $current_year,
        'prize' => 'Free round at premium Tennessee course',
        'type' => 'contest',
        'status' => 'active'
    ],
    [
        'name' => 'Hidden Gem Discovery',
        'description' => 'Share photos and reviews of lesser-known Tennessee courses',
        'deadline' => 'Ongoing',
        'prize' => 'Monthly featured course spotlight',
        'type' => 'contest',
        'status' => 'active'
    ],
    [
        'name' => 'Best Course Photo Contest',
        'description' => 'Submit your best Tennessee golf course photography',
        'deadline' => 'End of each quarter',
        'prize' => 'Golf equipment prizes and website feature',
        'type' => 'contest',
        'status' => 'active'
    ]
];

// Generate upcoming events (next 6 months)
function getUpcomingEvents($annual_events, $current_month, $current_year) {
    $upcoming = [];
    
    for ($i = 0; $i < 12; $i++) {
        $check_month = $current_month + $i;
        $check_year = $current_year;
        
        if ($check_month > 12) {
            $check_month -= 12;
            $check_year++;
        }
        
        foreach ($annual_events as $event) {
            if ($event['month'] == $check_month) {
                $event['date'] = date('F Y', mktime(0, 0, 0, $check_month, 1, $check_year));
                $event['sort_date'] = $check_year . '-' . sprintf('%02d', $check_month);
                $upcoming[] = $event;
            }
        }
        
        // Only show next 6 months
        if ($i >= 5) break;
    }
    
    // Sort by date
    usort($upcoming, function($a, $b) {
        return strcmp($a['sort_date'], $b['sort_date']);
    });
    
    return $upcoming;
}

// Get current events
$upcoming_events = getUpcomingEvents($annual_events, $current_month, $current_year);

// Get current month events
$current_month_events = array_filter($upcoming_events, function($event) use ($current_month, $current_year) {
    return strpos($event['date'], date('F Y')) !== false;
});

// Get seasonal message
function getSeasonalMessage($current_month) {
    switch($current_month) {
        case 12:
        case 1:
        case 2:
            return "Winter golf season - perfect time to plan your spring rounds and review your favorite courses!";
        case 3:
        case 4:
        case 5:
            return "Spring golf season is here! Courses are opening up and tournament season begins.";
        case 6:
        case 7:
        case 8:
            return "Peak golf season in Tennessee - tournaments, perfect weather, and courses in prime condition.";
        case 9:
        case 10:
        case 11:
            return "Beautiful fall golf season - ideal weather and stunning course scenery across Tennessee.";
        default:
            return "Great time to explore Tennessee's amazing golf courses!";
    }
}

$seasonal_message = getSeasonalMessage($current_month);
?>

<div class="events-contests-system">
    <!-- Current Season Message -->
    <div class="seasonal-banner">
        <div class="seasonal-content">
            <i class="fas fa-calendar-alt"></i>
            <p><?php echo $seasonal_message; ?></p>
        </div>
    </div>

    <!-- Upcoming Events -->
    <div class="upcoming-events">
        <h3><i class="fas fa-trophy"></i> Upcoming Events & Tournaments</h3>
        
        <?php if (!empty($current_month_events)): ?>
            <div class="current-month-highlight">
                <h4>This Month - <?php echo date('F Y'); ?></h4>
                <?php foreach ($current_month_events as $event): ?>
                    <div class="event-card current-month">
                        <div class="event-type <?php echo $event['type']; ?>">
                            <?php 
                            switch($event['type']) {
                                case 'tournament': echo '<i class="fas fa-trophy"></i>'; break;
                                case 'professional': echo '<i class="fas fa-star"></i>'; break;
                                case 'community': echo '<i class="fas fa-users"></i>'; break;
                                default: echo '<i class="fas fa-calendar"></i>';
                            }
                            ?>
                        </div>
                        <div class="event-details">
                            <h5><?php echo htmlspecialchars($event['name']); ?></h5>
                            <p class="event-description"><?php echo htmlspecialchars($event['description']); ?></p>
                            <p class="event-location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['location']); ?></p>
                            <?php if ($event['website']): ?>
                                <a href="<?php echo htmlspecialchars($event['website']); ?>" target="_blank" class="event-link">Learn More</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="upcoming-events-grid">
            <?php 
            $shown_events = 0;
            foreach ($upcoming_events as $event): 
                if (strpos($event['date'], date('F Y')) === false && $shown_events < 6):
                    $shown_events++;
            ?>
                <div class="event-card">
                    <div class="event-date">
                        <span><?php echo $event['date']; ?></span>
                    </div>
                    <div class="event-type <?php echo $event['type']; ?>">
                        <?php 
                        switch($event['type']) {
                            case 'tournament': echo '<i class="fas fa-trophy"></i>'; break;
                            case 'professional': echo '<i class="fas fa-star"></i>'; break;
                            case 'community': echo '<i class="fas fa-users"></i>'; break;
                            default: echo '<i class="fas fa-calendar"></i>';
                        }
                        ?>
                    </div>
                    <div class="event-details">
                        <h5><?php echo htmlspecialchars($event['name']); ?></h5>
                        <p class="event-description"><?php echo htmlspecialchars($event['description']); ?></p>
                        <p class="event-location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['location']); ?></p>
                        <?php if ($event['website']): ?>
                            <a href="<?php echo htmlspecialchars($event['website']); ?>" target="_blank" class="event-link">Learn More</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php 
                endif;
            endforeach; 
            ?>
        </div>
    </div>

    <!-- Active Contests -->
    <div class="active-contests">
        <h3><i class="fas fa-gift"></i> Active Contests & Challenges</h3>
        <div class="contests-grid">
            <?php foreach ($ongoing_contests as $contest): ?>
                <div class="contest-card">
                    <div class="contest-header">
                        <h5><?php echo htmlspecialchars($contest['name']); ?></h5>
                        <span class="contest-status active">Active</span>
                    </div>
                    <div class="contest-details">
                        <p><?php echo htmlspecialchars($contest['description']); ?></p>
                        <div class="contest-info">
                            <div class="contest-deadline">
                                <i class="fas fa-clock"></i>
                                <span>Deadline: <?php echo htmlspecialchars($contest['deadline']); ?></span>
                            </div>
                            <div class="contest-prize">
                                <i class="fas fa-gift"></i>
                                <span><?php echo htmlspecialchars($contest['prize']); ?></span>
                            </div>
                        </div>
                        <a href="/contact" class="participate-btn">How to Participate</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Community Stats -->
    <div class="community-stats">
        <h3><i class="fas fa-chart-line"></i> Community Activity</h3>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">97+</div>
                <div class="stat-label">Tennessee Courses Featured</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo date('Y'); ?></div>
                <div class="stat-label">Events & Tournaments This Year</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">Active</div>
                <div class="stat-label">Community Growing Daily</div>
            </div>
        </div>
    </div>
</div>

<style>
.events-contests-system {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.seasonal-banner {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 1.5rem;
    border-radius: 15px;
    margin-bottom: 3rem;
    text-align: center;
}

.seasonal-content {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.seasonal-content i {
    font-size: 1.5rem;
}

.seasonal-content p {
    margin: 0;
    font-size: 1.1rem;
}

.upcoming-events, .active-contests, .community-stats {
    margin-bottom: 3rem;
}

.upcoming-events h3, .active-contests h3, .community-stats h3 {
    color: var(--primary-color);
    font-size: 1.8rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.current-month-highlight {
    background: var(--bg-light);
    padding: 2rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    border-left: 4px solid var(--gold-color);
}

.current-month-highlight h4 {
    color: var(--primary-color);
    font-size: 1.3rem;
    margin-bottom: 1.5rem;
}

.upcoming-events-grid, .contests-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.event-card, .contest-card {
    background: var(--bg-white);
    padding: 1.5rem;
    border-radius: 15px;
    box-shadow: var(--shadow-light);
    border-left: 4px solid var(--primary-color);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.event-card:hover, .contest-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-medium);
}

.event-card.current-month {
    border-left-color: var(--gold-color);
    background: linear-gradient(135deg, #fff9e6, white);
}

.event-type {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-bottom: 1rem;
    font-size: 1.2rem;
}

.event-type.tournament {
    background: var(--primary-color);
    color: white;
}

.event-type.professional {
    background: var(--gold-color);
    color: white;
}

.event-type.community {
    background: var(--secondary-color);
    color: white;
}

.event-date {
    background: var(--primary-color);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
    display: inline-block;
    margin-bottom: 1rem;
}

.event-details h5, .contest-header h5 {
    color: var(--primary-color);
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.event-description {
    color: var(--text-gray);
    margin-bottom: 0.8rem;
    line-height: 1.5;
}

.event-location {
    color: var(--text-gray);
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.event-location i {
    color: var(--primary-color);
}

.event-link, .participate-btn {
    background: var(--primary-color);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    display: inline-block;
    transition: background 0.3s ease;
}

.event-link:hover, .participate-btn:hover {
    background: var(--secondary-color);
}

.contest-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.contest-status {
    background: #28a745;
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}

.contest-details p {
    color: var(--text-gray);
    margin-bottom: 1rem;
    line-height: 1.5;
}

.contest-info {
    margin-bottom: 1.5rem;
}

.contest-deadline, .contest-prize {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    color: var(--text-gray);
}

.contest-deadline i, .contest-prize i {
    color: var(--primary-color);
    width: 16px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.stat-card {
    background: var(--bg-white);
    padding: 2rem;
    border-radius: 15px;
    box-shadow: var(--shadow-light);
    text-align: center;
    border-top: 4px solid var(--primary-color);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.stat-label {
    color: var(--text-gray);
    font-weight: 500;
}

@media (max-width: 768px) {
    .seasonal-content {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .upcoming-events-grid, .contests-grid, .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .contest-header {
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>