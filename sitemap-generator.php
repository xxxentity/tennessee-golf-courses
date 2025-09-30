<?php
/**
 * Advanced Sitemap Generator
 * Generates XML sitemaps for better search engine indexing
 */

require_once 'includes/init.php';

header('Content-Type: application/xml; charset=utf-8');

// Static pages with their priority and change frequency
$staticPages = [
    '/' => ['priority' => '1.0', 'changefreq' => 'daily'],
    '/courses' => ['priority' => '0.9', 'changefreq' => 'daily'],
    '/about' => ['priority' => '0.7', 'changefreq' => 'monthly'],
    '/contact' => ['priority' => '0.6', 'changefreq' => 'monthly'],
    '/news' => ['priority' => '0.8', 'changefreq' => 'daily'],
    '/reviews' => ['priority' => '0.7', 'changefreq' => 'weekly'],
    '/events' => ['priority' => '0.6', 'changefreq' => 'weekly'],
    '/privacy-policy' => ['priority' => '0.3', 'changefreq' => 'yearly'],
    '/terms-of-service' => ['priority' => '0.3', 'changefreq' => 'yearly']
];

// Golf course pages (from static data)
$golfCourses = [
    'bear-trace-harrison-bay' => 'Bear Trace at Harrison Bay',
    'avalon-golf-country-club' => 'Avalon Golf & Country Club',
    'belle-acres-golf-course' => 'Belle Acres Golf Course',
    'belle-meade-country-club' => 'Belle Meade Country Club',
    'bluegrass-yacht-country-club' => 'Bluegrass Yacht & Country Club',
    'big-creek-golf-club' => 'Big Creek Golf Club',
    'brainerd-golf-course' => 'Brainerd Golf Course',
    'brown-acres-golf-course' => 'Brown Acres Golf Course',
    'cedar-crest-golf-club' => 'Cedar Crest Golf Club',
    'cumberland-cove-golf-course' => 'Cumberland Cove Golf Course',
    'cherokee-country-club' => 'Cherokee Country Club',
    'chattanooga-golf-country-club' => 'Chattanooga Golf & Country Club',
    'council-fire-golf-club' => 'Council Fire Golf Club',
    'cheekwood-golf-club' => 'Cheekwood Golf Club',
    'clarksville-country-club' => 'Clarksville Country Club',
    'colonial-country-club' => 'Colonial Country Club',
    'gaylord-springs-golf-links' => 'Gaylord Springs Golf Links',
    'forrest-crossing-golf-course' => 'Forrest Crossing Golf Course',
    'fox-den-country-club' => 'Fox Den Country Club',
    'greystone-golf-course' => 'Greystone Golf Course',
    'harpeth-hills-golf-course' => 'Harpeth Hills Golf Course',
    'henry-horton-state-park-golf-course' => 'Henry Horton State Park Golf Course',
    'hermitage-golf-course' => 'Hermitage Golf Course',
    'hillwood-country-club' => 'Hillwood Country Club',
    'holston-hills-country-club' => 'Holston Hills Country Club',
    'honky-tonk-national-golf-course' => 'Honky Tonk National Golf Course',
    'island-pointe-golf-club' => 'Island Pointe Golf Club',
    'jackson-country-club' => 'Jackson Country Club',
    'mccabe-golf-course' => 'McCabe Golf Course',
    'mirimichi-golf-course' => 'Mirimichi Golf Course',
    'montgomery-bell-state-park-golf-course' => 'Montgomery Bell State Park Golf Course',
    'nashville-national-golf-links' => 'Nashville National Golf Links',
    'nashville-golf-athletic-club' => 'Nashville Golf & Athletic Club',
    'richland-country-club' => 'Richland Country Club',
    'stones-river-country-club' => 'Stones River Country Club',
    'springhouse-golf-club' => 'Springhouse Golf Club',
    'sweetens-cove-golf-club' => 'Sweetens Cove Golf Club',
    'warriors-path-state-park-golf-course' => 'Warriors Path State Park Golf Course',
    'eagles-landing-golf-club' => 'Eagles Landing Golf Club',
    'sevierville-golf-club' => 'Sevierville Golf Club',
    'pine-oaks-golf-course' => 'Pine Oaks Golf Course',
    'lambert-acres-golf-club' => 'Lambert Acres Golf Club',
    'lake-tansi-golf-course' => 'Lake Tansi Golf Course',
    'egwani-farms-golf-course' => 'Egwani Farms Golf Course',
    'fall-creek-falls-state-park-golf-course' => 'Fall Creek Falls State Park Golf Course',
    'dead-horse-lake-golf-course' => 'Dead Horse Lake Golf Course',
    'bear-trace-cumberland-mountain' => 'Bear Trace at Cumberland Mountain',
    'bear-trace-at-tims-ford' => 'Bear Trace at Tims Ford',
    'laurel-valley-country-club' => 'Laurel Valley Country Club',
    'druid-hills-golf-club' => 'Druid Hills Golf Club',
    'blackthorn-club' => 'Blackthorn Club',
    'old-hickory-country-club' => 'Old Hickory Country Club',
    'old-fort-golf-course' => 'Old Fort Golf Course',
    'paris-landing-state-park-golf-course' => 'Paris Landing State Park Golf Course',
    'percy-warner-golf-course' => 'Percy Warner Golf Course',
    'pickwick-landing-state-park' => 'Pickwick Landing State Park',
    'temple-hills-country-club' => 'Temple Hills Country Club',
    'tanasi-golf-course' => 'Tanasi Golf Course',
    'ted-rhodes-golf-course' => 'Ted Rhodes Golf Course',
    'the-club-at-five-oaks' => 'The Club at Five Oaks',
    'the-club-at-gettysvue' => 'The Club at Gettysvue',
    'the-golf-club-of-tennessee' => 'The Golf Club of Tennessee',
    'the-grove' => 'The Grove',
    'the-governors-club' => 'The Governors Club',
    'the-honors-course' => 'The Honors Course',
    'the-links-at-audubon' => 'The Links at Audubon',
    'the-links-at-fox-meadows' => 'The Links at Fox Meadows',
    'the-links-at-galloway' => 'The Links at Galloway',
    'the-links-at-kahite' => 'The Links at Kahite',
    'the-links-at-whitehaven' => 'The Links at Whitehaven',
    'the-legacy-golf-course' => 'The Legacy Golf Course',
    'tennessee-grasslands-fairvue' => 'Tennessee Grasslands at Fairvue',
    'tennessee-grasslands-foxland' => 'Tennessee Grasslands at Foxland',
    'tennessee-national-golf-club' => 'Tennessee National Golf Club',
    'three-ridges-golf-course' => 'Three Ridges Golf Course',
    'tpc-southwind' => 'TPC Southwind',
    'toqua-golf-course' => 'Toqua Golf Course',
    'troubadour-golf-field-club' => 'Troubadour Golf & Field Club',
    'two-rivers-golf-course' => 'Two Rivers Golf Course',
    'vanderbilt-legends-club' => 'Vanderbilt Legends Club',
    'willow-creek-golf-club' => 'Willow Creek Golf Club',
    'williams-creek-golf-course' => 'Williams Creek Golf Course',
    'windtree-golf-course' => 'Windtree Golf Course',
    'signal-mountain-golf-country-club' => 'Signal Mountain Golf & Country Club',
    'southern-hills-golf-country-club' => 'Southern Hills Golf & Country Club',
    'stonehenge-golf-club' => 'Stonehenge Golf Club',
    'whittle-springs-golf-course' => 'Whittle Springs Golf Course',
    'white-plains-golf-course' => 'White Plains Golf Course',
    'lookout-mountain-club' => 'Lookout Mountain Club',
    'moccasin-bend-golf-course' => 'Moccasin Bend Golf Course',
    'ross-creek-landing-golf-course' => 'Ross Creek Landing Golf Course',
    'overton-park-9' => 'Overton Park 9'
];

// News articles
$newsArticles = [
    'ryder-cup-2025-complete-tournament-recap-bethpage-black' => '2025-09-29',
    'ryder-cup-2025-europe-survives-american-comeback-15-13' => '2025-09-28',
    'ryder-cup-2025-day-2-europe-historic-sweep-bethpage' => '2025-09-27',
    'ryder-cup-2025-day-1-europe-dominates-bethpage-black' => '2025-09-26',
    'ryder-cup-2025-final-preview-bethpage-black-showdown' => '2025-09-25',
    'ryder-cup-2025-complete-preview-team-announcements-bethpage-black' => '2025-08-28',
    'fedex-st-jude-championship-2025-complete-recap-community-impact' => '2025-01-15',
    'rose-captures-thrilling-playoff-victory-fleetwood-heartbreak' => '2024-08-18',
    'fleetwood-maintains-narrow-lead-scheffler-charges' => '2024-08-17',
    'fleetwood-takes-command-weather-halts-play' => '2024-08-16',
    'fedex-st-jude-first-round-bhatia-leads' => '2024-08-15',
    'open-championship-2025-round-1-royal-portrush' => '2024-07-18',
    'scheffler-extends-lead-open-championship-round-3' => '2024-07-20',
    'scheffler-seizes-control-open-championship-round-2' => '2024-07-19',
    'scheffler-wins-2025-british-open-final-round' => '2024-07-21'
];

// Review articles  
$reviewArticles = [
    'top-5-golf-drivers-2025' => '2025-08-21',
    'top-5-golf-balls-2025' => '2025-08-11',
    'top-10-putters-2025-amazon-guide' => '2025-08-06'
];

// Start XML output
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// Add static pages
foreach ($staticPages as $url => $settings) {
    echo "  <url>\n";
    echo "    <loc>https://tennesseegolfcourses.com" . htmlspecialchars($url) . "</loc>\n";
    echo "    <lastmod>" . date('Y-m-d') . "</lastmod>\n";
    echo "    <changefreq>" . $settings['changefreq'] . "</changefreq>\n";
    echo "    <priority>" . $settings['priority'] . "</priority>\n";
    echo "  </url>\n";
}

// Add golf course pages
foreach ($golfCourses as $slug => $name) {
    echo "  <url>\n";
    echo "    <loc>https://tennesseegolfcourses.com/courses/" . htmlspecialchars($slug) . "</loc>\n";
    echo "    <lastmod>" . date('Y-m-d') . "</lastmod>\n";
    echo "    <changefreq>monthly</changefreq>\n";
    echo "    <priority>0.8</priority>\n";
    echo "  </url>\n";
}

// Add news articles
foreach ($newsArticles as $slug => $date) {
    echo "  <url>\n";
    echo "    <loc>https://tennesseegolfcourses.com/news/" . htmlspecialchars($slug) . "</loc>\n";
    echo "    <lastmod>" . $date . "</lastmod>\n";
    echo "    <changefreq>monthly</changefreq>\n";
    echo "    <priority>0.6</priority>\n";
    echo "  </url>\n";
}

// Add review articles
foreach ($reviewArticles as $slug => $date) {
    echo "  <url>\n";
    echo "    <loc>https://tennesseegolfcourses.com/reviews/" . htmlspecialchars($slug) . "</loc>\n";
    echo "    <lastmod>" . $date . "</lastmod>\n";
    echo "    <changefreq>monthly</changefreq>\n";
    echo "    <priority>0.7</priority>\n";
    echo "  </url>\n";
}

echo "</urlset>\n";
?>