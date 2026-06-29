<?php
require_once 'includes/seo.php';

SEO::setupEventsPage();

// Professional tournaments
$professional_tournaments = [
    // 2026 Season — LIV Golf
    ['title' => 'LIV Golf Riyadh',            'league' => 'LIV', 'date_start' => '2026-02-04', 'date_end' => '2026-02-07', 'venue' => 'Riyadh Golf Club',                        'location' => 'Riyadh, Saudi Arabia'],
    ['title' => 'LIV Golf Adelaide',           'league' => 'LIV', 'date_start' => '2026-02-12', 'date_end' => '2026-02-15', 'venue' => 'The Grange Golf Club',                    'location' => 'Adelaide, Australia'],
    ['title' => 'LIV Golf Hong Kong',          'league' => 'LIV', 'date_start' => '2026-03-05', 'date_end' => '2026-03-08', 'venue' => 'Hong Kong Golf Club at Fanling',          'location' => 'Hong Kong'],
    ['title' => 'LIV Golf Singapore',          'league' => 'LIV', 'date_start' => '2026-03-12', 'date_end' => '2026-03-15', 'venue' => 'Sentosa Golf Club',                       'location' => 'Singapore'],
    ['title' => 'LIV Golf South Africa',       'league' => 'LIV', 'date_start' => '2026-03-19', 'date_end' => '2026-03-22', 'venue' => 'The Club at Steyn City',                  'location' => 'Johannesburg, South Africa'],
    ['title' => 'LIV Golf Mexico City',        'league' => 'LIV', 'date_start' => '2026-04-16', 'date_end' => '2026-04-19', 'venue' => 'Club de Golf Chapultepec',                'location' => 'Mexico City, Mexico'],
    ['title' => 'LIV Golf Virginia',           'league' => 'LIV', 'date_start' => '2026-05-07', 'date_end' => '2026-05-10', 'venue' => 'Robert Trent Jones Golf Club',            'location' => 'Virginia, USA'],
    ['title' => 'LIV Golf Korea',              'league' => 'LIV', 'date_start' => '2026-05-28', 'date_end' => '2026-05-31', 'venue' => 'Asiad Country Club',                      'location' => 'Busan, South Korea'],
    ['title' => 'LIV Golf Andalucia',          'league' => 'LIV', 'date_start' => '2026-06-04', 'date_end' => '2026-06-07', 'venue' => 'Real Club Valderrama',                    'location' => 'Andalucia, Spain'],
    ['title' => 'LIV Golf Louisiana',          'league' => 'LIV', 'date_start' => '2026-06-25', 'date_end' => '2026-06-28', 'venue' => 'Bayou Oaks at City Park',                 'location' => 'New Orleans, LA'],
    ['title' => 'LIV Golf UK',                 'league' => 'LIV', 'date_start' => '2026-07-23', 'date_end' => '2026-07-26', 'venue' => 'JCB Golf & Country Club',                 'location' => 'England, UK'],
    ['title' => 'LIV Golf New York',           'league' => 'LIV', 'date_start' => '2026-08-06', 'date_end' => '2026-08-09', 'venue' => 'Liberty National Golf Club',              'location' => 'Jersey City, NJ'],
    ['title' => 'LIV Golf Indianapolis',       'league' => 'LIV', 'date_start' => '2026-08-20', 'date_end' => '2026-08-23', 'venue' => 'The Club at Chatham Hills',               'location' => 'Indianapolis, IN'],
    ['title' => 'LIV Golf Team Championship',  'league' => 'LIV', 'date_start' => '2026-08-27', 'date_end' => '2026-08-30', 'venue' => "The Cardinal at Saint John's",            'location' => 'Michigan, USA'],
    // 2026 Season — PGA Tour Signature Events
    ['title' => 'AT&T Pebble Beach Pro-Am',    'league' => 'PGA', 'date_start' => '2026-02-12', 'date_end' => '2026-02-15', 'venue' => 'Pebble Beach Golf Links',                 'location' => 'Pebble Beach, CA'],
    ['title' => 'Genesis Invitational',        'league' => 'PGA', 'date_start' => '2026-02-19', 'date_end' => '2026-02-22', 'venue' => 'Riviera Country Club',                    'location' => 'Pacific Palisades, CA'],
    ['title' => 'Arnold Palmer Invitational',  'league' => 'PGA', 'date_start' => '2026-03-05', 'date_end' => '2026-03-08', 'venue' => 'Bay Hill Club & Lodge',                   'location' => 'Orlando, FL'],
    ['title' => 'RBC Heritage',                'league' => 'PGA', 'date_start' => '2026-04-16', 'date_end' => '2026-04-19', 'venue' => 'Harbour Town Golf Links',                 'location' => 'Hilton Head Island, SC'],
    ['title' => 'Cadillac Championship',       'league' => 'PGA', 'date_start' => '2026-04-30', 'date_end' => '2026-05-03', 'venue' => 'Trump National Doral Blue Monster',       'location' => 'Miami, FL'],
    ['title' => 'Truist Championship',         'league' => 'PGA', 'date_start' => '2026-05-07', 'date_end' => '2026-05-10', 'venue' => 'Quail Hollow Club',                       'location' => 'Charlotte, NC'],
    ['title' => 'Memorial Tournament',         'league' => 'PGA', 'date_start' => '2026-06-04', 'date_end' => '2026-06-07', 'venue' => 'Muirfield Village Golf Club',              'location' => 'Dublin, OH'],
    ['title' => 'Travelers Championship',      'league' => 'PGA', 'date_start' => '2026-06-25', 'date_end' => '2026-06-28', 'venue' => 'TPC River Highlands',                     'location' => 'Cromwell, CT'],
    // 2026 Season — PGA Tour Other Notable Events
    ['title' => 'RBC Canadian Open',           'league' => 'PGA', 'date_start' => '2026-06-11', 'date_end' => '2026-06-14', 'venue' => 'TPC Toronto at Osprey Valley',            'location' => 'Caledon, ON'],
    // 2026 Season — Majors
    ['title' => 'Masters Tournament',          'league' => 'PGA', 'date_start' => '2026-04-09', 'date_end' => '2026-04-12', 'venue' => 'Augusta National Golf Club',              'location' => 'Augusta, GA'],
    ['title' => 'PGA Championship',            'league' => 'PGA', 'date_start' => '2026-05-14', 'date_end' => '2026-05-17', 'venue' => 'Aronimink Golf Club',                     'location' => 'Newtown Square, PA'],
    ['title' => 'U.S. Open',                   'league' => 'PGA', 'date_start' => '2026-06-18', 'date_end' => '2026-06-21', 'venue' => 'Shinnecock Hills Golf Club',              'location' => 'Southampton, NY'],
    ['title' => 'The Open Championship',       'league' => 'PGA', 'date_start' => '2026-07-16', 'date_end' => '2026-07-19', 'venue' => 'Royal Birkdale',                          'location' => 'Southport, England'],
    // 2026 Season — FedEx Cup Playoffs
    ['title' => 'FedEx St. Jude Championship', 'league' => 'PGA', 'date_start' => '2026-08-13', 'date_end' => '2026-08-16', 'venue' => 'TPC Southwind',                           'location' => 'Memphis, TN'],
    ['title' => 'BMW Championship',            'league' => 'PGA', 'date_start' => '2026-08-20', 'date_end' => '2026-08-23', 'venue' => 'Bellerive Country Club',                  'location' => 'St. Louis, MO'],
    ['title' => 'Tour Championship',           'league' => 'PGA', 'date_start' => '2026-08-27', 'date_end' => '2026-08-30', 'venue' => 'East Lake Golf Club',                     'location' => 'Atlanta, GA'],
    // 2025 Season
    ['title' => 'LIV Golf Riyadh', 'league' => 'LIV', 'date_start' => '2025-02-06', 'date_end' => '2025-02-08', 'venue' => 'Riyadh Golf Club', 'location' => 'Riyadh, Saudi Arabia'],
    ['title' => 'LIV Golf Adelaide', 'league' => 'LIV', 'date_start' => '2025-02-14', 'date_end' => '2025-02-16', 'venue' => 'The Grange Golf Club', 'location' => 'Adelaide, Australia'],
    ['title' => 'LIV Golf Hong Kong', 'league' => 'LIV', 'date_start' => '2025-03-07', 'date_end' => '2025-03-09', 'venue' => 'Hong Kong Golf Club at Fanling', 'location' => 'Hong Kong'],
    ['title' => 'LIV Golf Singapore', 'league' => 'LIV', 'date_start' => '2025-03-14', 'date_end' => '2025-03-16', 'venue' => 'Sentosa Golf Club', 'location' => 'Singapore'],
    ['title' => 'LIV Golf Miami', 'league' => 'LIV', 'date_start' => '2025-04-04', 'date_end' => '2025-04-06', 'venue' => 'Trump National Doral', 'location' => 'Miami, FL'],
    ['title' => 'Masters Tournament', 'league' => 'PGA', 'date_start' => '2025-04-10', 'date_end' => '2025-04-13', 'venue' => 'Augusta National Golf Club', 'location' => 'Augusta, GA'],
    ['title' => 'LIV Golf Mexico City', 'league' => 'LIV', 'date_start' => '2025-04-25', 'date_end' => '2025-04-27', 'venue' => 'Club de Golf Chapultepec', 'location' => 'Mexico City, Mexico'],
    ['title' => 'LIV Golf Korea', 'league' => 'LIV', 'date_start' => '2025-05-02', 'date_end' => '2025-05-04', 'venue' => 'Jack Nicklaus Golf Club Korea', 'location' => 'Incheon, South Korea'],
    ['title' => 'PGA Championship', 'league' => 'PGA', 'date_start' => '2025-05-15', 'date_end' => '2025-05-18', 'venue' => 'Quail Hollow Club', 'location' => 'Charlotte, NC'],
    ['title' => 'LIV Golf Virginia', 'league' => 'LIV', 'date_start' => '2025-06-06', 'date_end' => '2025-06-08', 'venue' => 'Robert Trent Jones Golf Club', 'location' => 'Virginia, USA'],
    ['title' => 'U.S. Open', 'league' => 'PGA', 'date_start' => '2025-06-19', 'date_end' => '2025-06-22', 'venue' => 'Oakmont Country Club', 'location' => 'Oakmont, PA'],
    ['title' => 'LIV Golf Dallas', 'league' => 'LIV', 'date_start' => '2025-06-27', 'date_end' => '2025-06-29', 'venue' => 'Maridoe Golf Club', 'location' => 'Dallas, TX'],
    ['title' => 'LIV Golf Andalucia', 'league' => 'LIV', 'date_start' => '2025-07-11', 'date_end' => '2025-07-13', 'venue' => 'Real Club Valderrama', 'location' => 'Andalucia, Spain'],
    ['title' => 'The Open Championship', 'league' => 'PGA', 'date_start' => '2025-07-17', 'date_end' => '2025-07-20', 'venue' => 'Royal Portrush', 'location' => 'Northern Ireland'],
    ['title' => 'LIV Golf UK', 'league' => 'LIV', 'date_start' => '2025-07-25', 'date_end' => '2025-07-27', 'venue' => 'JCB Golf & Country Club', 'location' => 'England, UK'],
    ['title' => 'LIV Golf Chicago', 'league' => 'LIV', 'date_start' => '2025-08-08', 'date_end' => '2025-08-10', 'venue' => 'Bolingbrook Golf Club', 'location' => 'Chicago, IL'],
    ['title' => 'FedEx St. Jude Championship', 'league' => 'PGA', 'date_start' => '2025-08-14', 'date_end' => '2025-08-17', 'venue' => 'TPC Southwind', 'location' => 'Memphis, TN'],
    ['title' => 'LIV Golf Indianapolis', 'league' => 'LIV', 'date_start' => '2025-08-15', 'date_end' => '2025-08-17', 'venue' => 'The Club at Chatham Hills', 'location' => 'Indianapolis, IN'],
    ['title' => 'BMW Championship', 'league' => 'PGA', 'date_start' => '2025-08-21', 'date_end' => '2025-08-24', 'venue' => 'Caves Valley Golf Club', 'location' => 'Owings Mills, MD'],
    ['title' => 'LIV Golf Team Championship', 'league' => 'LIV', 'date_start' => '2025-08-22', 'date_end' => '2025-08-24', 'venue' => 'The Cardinal at Saint John\'s', 'location' => 'Michigan, USA'],
    ['title' => 'Tour Championship', 'league' => 'PGA', 'date_start' => '2025-08-28', 'date_end' => '2025-08-31', 'venue' => 'East Lake Golf Club', 'location' => 'Atlanta, GA'],
    ['title' => 'Ryder Cup', 'league' => 'PGA', 'date_start' => '2025-09-26', 'date_end' => '2025-09-28', 'venue' => 'Bethpage Black', 'location' => 'Bethpage, NY'],
];

// Tennessee local events — 2026
$tennessee_events_2026 = [
    ['title' => 'Mid-Amateur Four-Ball Championship',        'date_start' => '2026-06-06', 'date_end' => '2026-06-07', 'venue' => 'Sevierville Golf Club - River Course',        'location' => 'Sevierville, TN',     'organizer' => 'Tennessee Golf Association'],
    ['title' => 'Parent-Child Championship',                 'date_start' => '2026-06-13', 'date_end' => '2026-06-14', 'venue' => 'Stonehenge Golf Club',                        'location' => 'Fairfield Glade, TN', 'organizer' => 'Tennessee Golf Association'],
    ['title' => 'Tennessee Junior Amateur Championship',     'date_start' => '2026-06-15', 'date_end' => '2026-06-17', 'venue' => 'GreyStone Golf Club',                         'location' => 'Dickson, TN',         'organizer' => 'Tennessee Golf Association'],
    ['title' => 'Tennessee Girls Junior Championship',       'date_start' => '2026-06-15', 'date_end' => '2026-06-17', 'venue' => 'GreyStone Golf Club',                         'location' => 'Dickson, TN',         'organizer' => 'Tennessee Golf Association'],
    ['title' => 'Tennessee State Amateur Championship',      'date_start' => '2026-06-23', 'date_end' => '2026-06-26', 'venue' => 'Holston Hills Country Club',                  'location' => 'Knoxville, TN',       'organizer' => 'Tennessee Golf Association'],
    ['title' => "Tennessee Women's Amateur Championship",    'date_start' => '2026-07-07', 'date_end' => '2026-07-09', 'venue' => 'Chattanooga Golf & Country Club',             'location' => 'Chattanooga, TN',     'organizer' => 'Tennessee Golf Association'],
    ['title' => 'Tennessee State Open Championship',         'date_start' => '2026-07-08', 'date_end' => '2026-07-10', 'venue' => 'Grasslands Club - Foxland Course',           'location' => 'Gallatin, TN',        'organizer' => 'Tennessee Golf Association'],
    ['title' => 'Senior & Super Senior Match Play',          'date_start' => '2026-07-14', 'date_end' => '2026-07-17', 'venue' => 'Oak Ridge Country Club',                     'location' => 'Oak Ridge, TN',       'organizer' => 'Tennessee Golf Association'],
    ['title' => "Tennessee Women's Open Championship",       'date_start' => '2026-07-22', 'date_end' => '2026-07-24', 'venue' => 'Stonehenge Golf Club',                       'location' => 'Fairfield Glade, TN', 'organizer' => 'Tennessee Golf Association'],
    ['title' => 'Senior & Super Senior Amateur Championship','date_start' => '2026-08-04', 'date_end' => '2026-08-06', 'venue' => 'Belle Meade Country Club',                   'location' => 'Nashville, TN',       'organizer' => 'Tennessee Golf Association'],
    ['title' => "Women's Senior & Mid-Amateur Championship", 'date_start' => '2026-08-11', 'date_end' => '2026-08-12', 'venue' => 'Johnson City Country Club',                  'location' => 'Johnson City, TN',    'organizer' => 'Tennessee Golf Association'],
    ['title' => "Men's Match Play Championship",             'date_start' => '2026-08-11', 'date_end' => '2026-08-14', 'venue' => 'Vanderbilt Legends Club - South Course',     'location' => 'Franklin, TN',        'organizer' => 'Tennessee Golf Association'],
    ['title' => 'Tennessee Mid-Amateur Championship',        'date_start' => '2026-08-24', 'date_end' => '2026-08-26', 'venue' => 'Tennessee National Golf Club',               'location' => 'Loudon, TN',          'organizer' => 'Tennessee Golf Association'],
    ['title' => '58th TN PGA Professional Championship',    'date_start' => '2026-08-24', 'date_end' => '2026-08-26', 'venue' => 'Links at Kahite',                            'location' => 'Vonore, TN',          'organizer' => 'Tennessee PGA'],
    ['title' => "Women's Four-Ball Championship",            'date_start' => '2026-08-26', 'date_end' => '2026-08-27', 'venue' => "Hermitage Golf Course - General's Retreat",  'location' => 'Old Hickory, TN',     'organizer' => 'Tennessee Golf Association'],
    ['title' => 'Elite @ Old Fort Junior Tournament',        'date_start' => '2026-09-05', 'date_end' => '2026-09-06', 'venue' => 'Old Fort Golf Course',                       'location' => 'Murfreesboro, TN',    'organizer' => 'Sneds Tour / Tennessee Golf Foundation'],
    ['title' => 'Tennessee Junior Cup',                      'date_start' => '2026-09-26', 'date_end' => '2026-09-27', 'venue' => 'The Grove',                                  'location' => 'College Grove, TN',   'organizer' => 'Sneds Tour / Tennessee Golf Foundation'],
    ['title' => 'Open @ Montgomery Bell Junior Tournament',  'date_start' => '2026-10-03', 'date_end' => '2026-10-04', 'venue' => 'Montgomery Bell State Park Golf Course',     'location' => 'Burns, TN',           'organizer' => 'Sneds Tour / Tennessee Golf Foundation'],
];

// Tennessee local events — 2025
$tennessee_events = [
    ['title' => 'Mid-Amateur Four-Ball Championship', 'date_start' => '2025-06-07', 'date_end' => '2025-06-08', 'venue' => 'Sevierville Golf Club - River Course', 'location' => 'Sevierville, TN', 'organizer' => 'Tennessee Golf Association'],
    ['title' => 'Parent-Child Championship', 'date_start' => '2025-06-14', 'date_end' => '2025-06-15', 'venue' => 'Stonehenge Golf Club', 'location' => 'Fairfield Glade, TN', 'organizer' => 'Tennessee Golf Association'],
    ['title' => 'Tennessee Junior Amateur Championship', 'date_start' => '2025-06-16', 'date_end' => '2025-06-18', 'venue' => 'GreyStone Golf Club', 'location' => 'Dickson, TN', 'organizer' => 'Tennessee Golf Association'],
    ['title' => 'Tennessee Girls Junior Championship', 'date_start' => '2025-06-16', 'date_end' => '2025-06-18', 'venue' => 'GreyStone Golf Club', 'location' => 'Dickson, TN', 'organizer' => 'Tennessee Golf Association'],
    ['title' => 'Tennessee State Amateur Championship', 'date_start' => '2025-06-24', 'date_end' => '2025-06-27', 'venue' => 'Holston Hills Country Club', 'location' => 'Knoxville, TN', 'organizer' => 'Tennessee Golf Association'],
    ['title' => 'Tennessee Women\'s Amateur Championship', 'date_start' => '2025-07-08', 'date_end' => '2025-07-10', 'venue' => 'Chattanooga Golf & Country Club', 'location' => 'Chattanooga, TN', 'organizer' => 'Tennessee Golf Association'],
    ['title' => 'Tennessee State Open Championship', 'date_start' => '2025-07-09', 'date_end' => '2025-07-11', 'venue' => 'Grasslands Club - Foxland Course', 'location' => 'Gallatin, TN', 'organizer' => 'Tennessee Golf Association'],
    ['title' => 'Senior & Super Senior Match Play', 'date_start' => '2025-07-15', 'date_end' => '2025-07-18', 'venue' => 'Oak Ridge Country Club', 'location' => 'Oak Ridge, TN', 'organizer' => 'Tennessee Golf Association'],
    ['title' => 'Tennessee Women\'s Open Championship', 'date_start' => '2025-07-24', 'date_end' => '2025-07-26', 'venue' => 'Stonehenge Golf Club', 'location' => 'Fairfield Glade, TN', 'organizer' => 'Tennessee Golf Association'],
    ['title' => 'Senior & Super Senior Amateur Championship', 'date_start' => '2025-08-05', 'date_end' => '2025-08-07', 'venue' => 'Belle Meade Country Club', 'location' => 'Nashville, TN', 'organizer' => 'Tennessee Golf Association'],
    ['title' => 'Women\'s Senior & Mid-Amateur Championship', 'date_start' => '2025-08-12', 'date_end' => '2025-08-13', 'venue' => 'Johnson City Country Club', 'location' => 'Johnson City, TN', 'organizer' => 'Tennessee Golf Association'],
    ['title' => 'Men\'s Match Play Championship', 'date_start' => '2025-08-12', 'date_end' => '2025-08-15', 'venue' => 'Vanderbilt Legends Club - South Course', 'location' => 'Franklin, TN', 'organizer' => 'Tennessee Golf Association'],
    ['title' => 'Tennessee Mid-Amateur Championship', 'date_start' => '2025-08-25', 'date_end' => '2025-08-27', 'venue' => 'Tennessee National Golf Club', 'location' => 'Loudon, TN', 'organizer' => 'Tennessee Golf Association'],
    ['title' => '57th TN PGA Professional Championship', 'date_start' => '2025-08-25', 'date_end' => '2025-08-27', 'venue' => 'Links at Kahite', 'location' => 'Vonore, TN', 'organizer' => 'Tennessee PGA'],
    ['title' => 'Women\'s Four-Ball Championship', 'date_start' => '2025-08-27', 'date_end' => '2025-08-28', 'venue' => 'Hermitage Golf Course - General\'s Retreat', 'location' => 'Old Hickory, TN', 'organizer' => 'Tennessee Golf Association'],
    ['title' => 'Elite @ Old Fort Junior Tournament', 'date_start' => '2025-09-06', 'date_end' => '2025-09-07', 'venue' => 'Old Fort Golf Course', 'location' => 'Murfreesboro, TN', 'organizer' => 'Sneds Tour / Tennessee Golf Foundation'],
    ['title' => 'Tennessee Junior Cup', 'date_start' => '2025-09-27', 'date_end' => '2025-09-28', 'venue' => 'The Grove', 'location' => 'College Grove, TN', 'organizer' => 'Sneds Tour / Tennessee Golf Foundation'],
    ['title' => 'Open @ Montgomery Bell Junior Tournament', 'date_start' => '2025-10-04', 'date_end' => '2025-10-05', 'venue' => 'Montgomery Bell State Park Golf Course', 'location' => 'Burns, TN', 'organizer' => 'Sneds Tour / Tennessee Golf Foundation'],
];

// Build per-year grouped data
function build_year_data(array $pro, array $local, string $year): array {
    $all = [];
    foreach ($pro   as $t) { if (str_starts_with($t['date_start'], $year)) $all[] = array_merge($t, ['type' => 'professional']); }
    foreach ($local as $t) { if (str_starts_with($t['date_start'], $year)) $all[] = array_merge($t, ['type' => 'local']); }
    usort($all, fn($a, $b) => strtotime($a['date_start']) - strtotime($b['date_start']));
    $by_month = [];
    foreach ($all as $event) {
        $key = date('F Y', strtotime($event['date_start']));
        $by_month[$key]['professional'] = $by_month[$key]['professional'] ?? [];
        $by_month[$key]['local']        = $by_month[$key]['local'] ?? [];
        $by_month[$key][$event['type']][] = $event;
    }
    return $by_month;
}

$events_2026 = build_year_data($professional_tournaments, $tennessee_events_2026, '2026');
$events_2025 = build_year_data($professional_tournaments, $tennessee_events, '2025');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo SEO::generateMetaTags(); ?>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <?php include 'includes/favicon.php'; ?>

    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-7VPNPCDTBP');
    </script>

    <style>
        .events-hero {
            background: linear-gradient(135deg, rgba(6,78,59,0.9) 0%, rgba(16,185,129,0.7) 50%, rgba(234,88,12,0.5) 100%),
                        url('https://images.unsplash.com/photo-1535131749006-b7f58c99034b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80');
            background-size: cover; background-position: center;
            padding: 5rem 0 4rem; text-align: center; color: white;
        }
        .events-hero h1 { font-size: 3.5rem; font-weight: 700; margin-bottom: 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); }
        .events-hero p { font-size: 1.3rem; opacity: 0.95; max-width: 600px; margin: 0 auto; }

        .schedule-section { padding: 4rem 0; background: white; }
        .schedule-container { max-width: 1000px; margin: 0 auto; padding: 0 2rem; }

        .year-heading {
            font-size: 1.6rem; font-weight: 700; color: #2c5234;
            border-bottom: 3px solid #2c5234; padding-bottom: 0.5rem;
            margin-bottom: 2.5rem;
        }

        .month-section { margin-bottom: 3rem; }
        .month-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white; padding: 1.25rem 2rem; border-radius: 10px;
            margin-bottom: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .month-title { font-size: 1.7rem; font-weight: 700; margin: 0; }

        .tournament-section { margin-bottom: 2rem; }
        .section-title {
            font-size: 1.2rem; font-weight: 700; margin-bottom: 1.25rem;
            display: flex; align-items: center; gap: 0.75rem;
        }
        .major-tournaments .section-title { color: #1e40af; }
        .local-tournaments .section-title  { color: var(--primary-color); }

        .tournament-list { list-style: none; padding: 0; margin: 0; }
        .tournament-item {
            display: flex; align-items: center; padding: 1.1rem 0;
            border-bottom: 1px solid #f0f4f3; transition: all 0.2s ease;
        }
        .tournament-item:last-child { border-bottom: none; }
        .tournament-item:hover { background: #f8faf9; padding-left: 0.75rem; border-radius: 8px; }

        .tournament-date { min-width: 110px; font-weight: 700; color: var(--primary-color); font-size: 0.95rem; }
        .tournament-info { flex: 1; margin-left: 1.25rem; }
        .tournament-name { font-size: 1.05rem; font-weight: 600; color: var(--text-dark); margin-bottom: 0.2rem; }
        .tournament-venue    { color: var(--text-gray); font-size: 0.9rem; }
        .tournament-location { color: var(--text-gray); font-size: 0.85rem; margin-top: 0.2rem; }

        .league-badge {
            background: #f3f4f6; color: #6b7280;
            padding: 0.2rem 0.65rem; border-radius: 20px;
            font-size: 0.72rem; font-weight: 600; text-transform: uppercase;
            margin-left: 1rem; white-space: nowrap;
        }
        .league-badge.liv { background: #fef3f2; color: #dc2626; }
        .league-badge.pga { background: #eff6ff; color: #2563eb; }
        .league-badge.tn  { background: #fff7ed; color: #ea580c; }

        /* Collapsible 2025 archive */
        .archive-toggle {
            width: 100%; background: none; border: 2px solid #d1d5db;
            border-radius: 10px; padding: 1.25rem 1.75rem;
            display: flex; justify-content: space-between; align-items: center;
            cursor: pointer; font-size: 1.2rem; font-weight: 600; color: #4b5563;
            margin-top: 3rem; transition: all 0.2s ease;
        }
        .archive-toggle:hover { border-color: #2c5234; color: #2c5234; }
        .archive-toggle .chevron { transition: transform 0.3s ease; font-size: 1rem; }
        .archive-toggle.open .chevron { transform: rotate(180deg); }
        .archive-body { display: none; margin-top: 2rem; }
        .archive-body.open { display: block; }

        @media (max-width: 768px) {
            .events-hero h1 { font-size: 2.5rem; }
            .tournament-item { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
            .tournament-date { min-width: auto; }
            .tournament-info { margin-left: 0; }
            .league-badge { margin-left: 0; margin-top: 0.25rem; }
        }
    </style>
</head>

<body>
    <?php include 'includes/navigation.php'; ?>

    <section class="events-hero">
        <div class="container">
            <h1>Golf Tournament Schedule</h1>
            <p>LIV Golf, PGA Tour, and Tennessee tournament calendar</p>
        </div>
    </section>

    <section class="schedule-section">
        <div class="container">
            <div class="schedule-container">

                <!-- 2026 (current) -->
                <h2 class="year-heading">2026 Season</h2>
                <?php foreach ($events_2026 as $month => $events): ?>
                <div class="month-section">
                    <div class="month-header">
                        <h3 class="month-title">📅 <?php echo $month; ?></h3>
                    </div>
                    <?php if (!empty($events['professional'])): ?>
                    <div class="tournament-section major-tournaments">
                        <h4 class="section-title"><i class="fas fa-trophy"></i> Major &amp; Pro Tournaments</h4>
                        <ul class="tournament-list">
                            <?php foreach ($events['professional'] as $t): ?>
                            <li class="tournament-item">
                                <div class="tournament-date"><?php
                                    $s = date('M j', strtotime($t['date_start']));
                                    $e = date('j',   strtotime($t['date_end']));
                                    echo $t['date_start'] === $t['date_end'] ? $s : "$s–$e";
                                ?></div>
                                <div class="tournament-info">
                                    <div class="tournament-name"><?php echo htmlspecialchars($t['title']); ?></div>
                                    <div class="tournament-venue"><?php echo htmlspecialchars($t['venue']); ?></div>
                                    <div class="tournament-location"><?php echo htmlspecialchars($t['location']); ?></div>
                                </div>
                                <span class="league-badge <?php echo strtolower($t['league']); ?>"><?php echo $t['league']; ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($events['local'])): ?>
                    <div class="tournament-section local-tournaments">
                        <h4 class="section-title"><i class="fas fa-golf-ball"></i> Tennessee Events</h4>
                        <ul class="tournament-list">
                            <?php foreach ($events['local'] as $t): ?>
                            <li class="tournament-item">
                                <div class="tournament-date"><?php
                                    $s = date('M j', strtotime($t['date_start']));
                                    $e = date('j',   strtotime($t['date_end']));
                                    echo $t['date_start'] === $t['date_end'] ? $s : "$s–$e";
                                ?></div>
                                <div class="tournament-info">
                                    <div class="tournament-name"><?php echo htmlspecialchars($t['title']); ?></div>
                                    <div class="tournament-venue"><?php echo htmlspecialchars($t['venue']); ?></div>
                                    <div class="tournament-location"><?php echo htmlspecialchars($t['location']); ?></div>
                                </div>
                                <span class="league-badge tn">TN</span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>

                <!-- 2025 archive (collapsed) -->
                <button class="archive-toggle" id="archiveToggle" onclick="toggleArchive()">
                    <span>📁 2025 Season Archive</span>
                    <i class="fas fa-chevron-down chevron"></i>
                </button>
                <div class="archive-body" id="archiveBody">
                    <?php foreach ($events_2025 as $month => $events): ?>
                    <div class="month-section">
                        <div class="month-header">
                            <h3 class="month-title">📅 <?php echo $month; ?></h3>
                        </div>
                        <?php if (!empty($events['professional'])): ?>
                        <div class="tournament-section major-tournaments">
                            <h4 class="section-title"><i class="fas fa-trophy"></i> Major &amp; Pro Tournaments</h4>
                            <ul class="tournament-list">
                                <?php foreach ($events['professional'] as $t): ?>
                                <li class="tournament-item">
                                    <div class="tournament-date"><?php
                                        $s = date('M j', strtotime($t['date_start']));
                                        $e = date('j',   strtotime($t['date_end']));
                                        echo $t['date_start'] === $t['date_end'] ? $s : "$s–$e";
                                    ?></div>
                                    <div class="tournament-info">
                                        <div class="tournament-name"><?php echo htmlspecialchars($t['title']); ?></div>
                                        <div class="tournament-venue"><?php echo htmlspecialchars($t['venue']); ?></div>
                                        <div class="tournament-location"><?php echo htmlspecialchars($t['location']); ?></div>
                                    </div>
                                    <span class="league-badge <?php echo strtolower($t['league']); ?>"><?php echo $t['league']; ?></span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($events['local'])): ?>
                        <div class="tournament-section local-tournaments">
                            <h4 class="section-title"><i class="fas fa-golf-ball"></i> Tennessee Events</h4>
                            <ul class="tournament-list">
                                <?php foreach ($events['local'] as $t): ?>
                                <li class="tournament-item">
                                    <div class="tournament-date"><?php
                                        $s = date('M j', strtotime($t['date_start']));
                                        $e = date('j',   strtotime($t['date_end']));
                                        echo $t['date_start'] === $t['date_end'] ? $s : "$s–$e";
                                    ?></div>
                                    <div class="tournament-info">
                                        <div class="tournament-name"><?php echo htmlspecialchars($t['title']); ?></div>
                                        <div class="tournament-venue"><?php echo htmlspecialchars($t['venue']); ?></div>
                                        <div class="tournament-location"><?php echo htmlspecialchars($t['location']); ?></div>
                                    </div>
                                    <span class="league-badge tn">TN</span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script>
        function toggleArchive() {
            const btn  = document.getElementById('archiveToggle');
            const body = document.getElementById('archiveBody');
            btn.classList.toggle('open');
            body.classList.toggle('open');
        }
    </script>
</body>
</html>
