<?php
session_start();
require_once 'config/database.php';

// Get search query
$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

// Course data array (same as in courses.php)
$courses = [
    ['name' => 'Bear Trace at Harrison Bay', 'location' => 'Harrison, TN', 'slug' => 'bear-trace-harrison-bay'],
    ['name' => 'Avalon Golf & Country Club', 'location' => 'Lenoir City, TN', 'slug' => 'avalon-golf-country-club'],
    ['name' => 'Belle Acres Golf Course', 'location' => 'Cookeville, TN', 'slug' => 'belle-acres-golf-course'],
    ['name' => 'Bluegrass Yacht & Country Club', 'location' => 'Hendersonville, TN', 'slug' => 'bluegrass-yacht-country-club'],
    ['name' => 'Belle Meade Country Club', 'location' => 'Nashville, TN', 'slug' => 'belle-meade-country-club'],
    ['name' => 'Big Creek Golf Club', 'location' => 'Millington, TN', 'slug' => 'big-creek-golf-club'],
    ['name' => 'Cedar Crest Golf Club', 'location' => 'Murfreesboro, TN', 'slug' => 'cedar-crest-golf-club'],
    ['name' => 'Cumberland Cove Golf Course', 'location' => 'Monterey, TN', 'slug' => 'cumberland-cove-golf-course'],
    ['name' => 'Cherokee Country Club', 'location' => 'Knoxville, TN', 'slug' => 'cherokee-country-club'],
    ['name' => 'Gaylord Springs Golf Links', 'location' => 'Nashville, TN', 'slug' => 'gaylord-springs-golf-links'],
    ['name' => 'Forrest Crossing Golf Course', 'location' => 'Franklin, TN', 'slug' => 'forrest-crossing-golf-course'],
    ['name' => 'Fox Den Country Club', 'location' => 'Knoxville, TN', 'slug' => 'fox-den-country-club'],
    ['name' => 'Greystone Golf Course', 'location' => 'Dickson, TN', 'slug' => 'greystone-golf-course'],
    ['name' => 'Harpeth Hills Golf Course', 'location' => 'Nashville, TN', 'slug' => 'harpeth-hills-golf-course'],
    ['name' => 'Hermitage Golf Course', 'location' => 'Old Hickory, TN', 'slug' => 'hermitage-golf-course'],
    ['name' => 'Hillwood Country Club', 'location' => 'Nashville, TN', 'slug' => 'hillwood-country-club'],
    ['name' => 'Holston Hills Country Club', 'location' => 'Knoxville, TN', 'slug' => 'holston-hills-country-club'],
    ['name' => 'Island Pointe Golf Club', 'location' => 'Kodak, TN', 'slug' => 'island-pointe-golf-club'],
    ['name' => 'McCabe Golf Course', 'location' => 'Nashville, TN', 'slug' => 'mccabe-golf-course'],
    ['name' => 'Mirimichi Golf Course', 'location' => 'Millington, TN', 'slug' => 'mirimichi-golf-course'],
    ['name' => 'Nashville National Golf Links', 'location' => 'Joelton, TN', 'slug' => 'nashville-national-golf-links'],
    ['name' => 'Nashville Golf & Athletic Club', 'location' => 'Nashville, TN', 'slug' => 'nashville-golf-athletic-club'],
    ['name' => 'Richland Country Club', 'location' => 'Nashville, TN', 'slug' => 'richland-country-club'],
    ['name' => 'Stones River Country Club', 'location' => 'Murfreesboro, TN', 'slug' => 'stones-river-country-club'],
    ['name' => 'Springhouse Golf Club', 'location' => 'Nashville, TN', 'slug' => 'springhouse-golf-club'],
    ['name' => 'Old Hickory Country Club', 'location' => 'Old Hickory, TN', 'slug' => 'old-hickory-country-club'],
    ['name' => 'Percy Warner Golf Course', 'location' => 'Nashville, TN', 'slug' => 'percy-warner-golf-course'],
    ['name' => 'The Golf Club of Tennessee', 'location' => 'Kingston Springs, TN', 'slug' => 'the-golf-club-of-tennessee'],
    ['name' => 'The Grove', 'location' => 'College Grove, TN', 'slug' => 'the-grove'],
    ['name' => 'The Governors Club', 'location' => 'Brentwood, TN', 'slug' => 'the-governors-club'],
    ['name' => 'Temple Hills Country Club', 'location' => 'Franklin, TN', 'slug' => 'temple-hills-country-club'],
    ['name' => 'Ted Rhodes Golf Course', 'location' => 'Nashville, TN', 'slug' => 'ted-rhodes-golf-course'],
    ['name' => 'The Club at Five Oaks', 'location' => 'Lebanon, TN', 'slug' => 'the-club-at-five-oaks'],
    ['name' => 'The Club at Gettysvue', 'location' => 'Knoxville, TN', 'slug' => 'the-club-at-gettysvue'],
    ['name' => 'The Legacy Golf Course', 'location' => 'Springfield, TN', 'slug' => 'the-legacy-golf-course'],
    ['name' => 'Three Ridges Golf Course', 'location' => 'Knoxville, TN', 'slug' => 'three-ridges-golf-course'],
    ['name' => 'TPC Southwind', 'location' => 'Memphis, TN', 'slug' => 'tpc-southwind'],
    ['name' => 'Troubadour Golf & Field Club', 'location' => 'College Grove, TN', 'slug' => 'troubadour-golf-field-club'],
    ['name' => 'Two Rivers Golf Course', 'location' => 'Nashville, TN', 'slug' => 'two-rivers-golf-course'],
    ['name' => 'Vanderbilt Legends Club', 'location' => 'Franklin, TN', 'slug' => 'vanderbilt-legends-club'],
    ['name' => 'Willow Creek Golf Club', 'location' => 'Knoxville, TN', 'slug' => 'willow-creek-golf-club'],
    ['name' => 'Williams Creek Golf Course', 'location' => 'Knoxville, TN', 'slug' => 'williams-creek-golf-course'],
    ['name' => 'Windtree Golf Course', 'location' => 'Mount Juliet, TN', 'slug' => 'windtree-golf-course'],
    ['name' => 'Whittle Springs Golf Course', 'location' => 'Knoxville, TN', 'slug' => 'whittle-springs-golf-course'],
    ['name' => 'White Plains Golf Course', 'location' => 'Memphis, TN', 'slug' => 'white-plains-golf-course'],
    ['name' => 'Cheekwood Golf Club', 'location' => 'Nashville, TN', 'slug' => 'cheekwood-golf-club'],
    ['name' => 'Sweetens Cove Golf Club', 'location' => 'South Pittsburg, TN', 'slug' => 'sweetens-cove-golf-club'],
    ['name' => 'Warrior\'s Path State Park Golf Course', 'location' => 'Kingsport, TN', 'slug' => 'warriors-path-state-park-golf-course'],
    ['name' => 'Eagle\'s Landing Golf Club', 'location' => 'Sevierville, TN', 'slug' => 'eagles-landing-golf-club'],
    ['name' => 'Sevierville Golf Club', 'location' => 'Sevierville, TN', 'slug' => 'sevierville-golf-club'],
    ['name' => 'Pine Oaks Golf Course', 'location' => 'Johnson City, TN', 'slug' => 'pine-oaks-golf-course'],
    ['name' => 'Lambert Acres Golf Club', 'location' => 'Maryville, TN', 'slug' => 'lambert-acres-golf-club'],
    ['name' => 'Lake Tansi Golf Course', 'location' => 'Crossville, TN', 'slug' => 'lake-tansi-golf-course'],
    ['name' => 'Egwani Farms Golf Course', 'location' => 'Knoxville, TN', 'slug' => 'egwani-farms-golf-course'],
    ['name' => 'Dead Horse Lake Golf Course', 'location' => 'Louisville, TN', 'slug' => 'dead-horse-lake-golf-course'],
    ['name' => 'Bear Trace at Cumberland Mountain', 'location' => 'Crossville, TN', 'slug' => 'bear-trace-cumberland-mountain'],
    ['name' => 'Laurel Valley Country Club', 'location' => 'Townsend, TN', 'slug' => 'laurel-valley-country-club'],
    ['name' => 'Druid Hills Golf Club', 'location' => 'Fairfield Glade, TN', 'slug' => 'druid-hills-golf-club'],
    ['name' => 'Southern Hills Golf & Country Club', 'location' => 'Cookeville, TN', 'slug' => 'southern-hills-golf-country-club'],
    ['name' => 'Blackthorn Club', 'location' => 'Jonesborough, TN', 'slug' => 'blackthorn-club'],
    ['name' => 'Chattanooga Golf & Country Club', 'location' => 'Chattanooga, TN', 'slug' => 'chattanooga-golf-country-club']
];

// Search for matches (case-insensitive)
$results = [];
$searchTerm = strtolower($query);

foreach ($courses as $course) {
    if (stripos($course['name'], $searchTerm) !== false || stripos($course['location'], $searchTerm) !== false) {
        $results[] = $course;
    }
}

// Limit results to 10
$results = array_slice($results, 0, 10);

// Return JSON response
header('Content-Type: application/json');
echo json_encode($results);
?>