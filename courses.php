<?php
session_start();
require_once 'config/database.php';

// Get filters from URL parameters
$region_filter = isset($_GET['region']) ? $_GET['region'] : '';
$price_filter = isset($_GET['price']) ? $_GET['price'] : '';
$difficulty_filter = isset($_GET['difficulty']) ? $_GET['difficulty'] : '';
$amenities_filter = isset($_GET['amenities']) ? $_GET['amenities'] : '';
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'name';

// Pagination parameters
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$courses_per_page = 10;
$offset = ($page - 1) * $courses_per_page;

// Static course data with real ratings from database (alphabetical order)
$courses_static = [
    [
        'id' => 1,
        'name' => 'Bear Trace at Harrison Bay',
        'location' => 'Harrison, TN',
        'region' => 'Chattanooga Area',
        'description' => 'Jack Nicklaus Signature Design with stunning lakefront views',
        'image' => '/images/courses/bear-trace-harrison-bay/1.jpeg',
        'price_range' => '$50-75',
        'difficulty' => 'Intermediate',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Jack Nicklaus',
        'amenities' => ['Pro Shop', 'Restaurant', 'Driving Range', 'Putting Green'],
        'slug' => 'bear-trace-harrison-bay'
    ],
    [
        'id' => 29,
        'name' => 'Avalon Golf & Country Club',
        'location' => 'Lenoir City, TN',
        'region' => 'Knoxville Area',
        'description' => 'Joseph L. Lee designed semi-private course with Primo Zoysia greens',
        'image' => '/images/courses/avalon-golf-country-club/1.jpeg',
        'price_range' => '$50-85',
        'difficulty' => 'Intermediate',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Joseph L. Lee',
        'amenities' => ['Pro Shop', 'Restaurant', 'Driving Range', 'Golf Lessons'],
        'slug' => 'avalon-golf-country-club'
    ],
    [
        'id' => 38,
        'name' => 'Bluegrass Yacht & Country Club',
        'location' => 'Hendersonville, TN',
        'region' => 'Nashville Area',
        'description' => 'Unique private club combining Robert Bruce Harris golf design with yacht marina facilities since 1951',
        'image' => '/images/courses/bluegrass-yacht-country-club/1.jpeg',
        'price_range' => 'Private Club',
        'difficulty' => 'Advanced',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Robert Bruce Harris',
        'amenities' => ['Championship Golf', 'Yacht Marina', 'Tennis Courts', 'Fine Dining', 'Swimming Pools', 'Spa Services', 'Youth Programs', 'Event Hosting'],
        'slug' => 'bluegrass-yacht-country-club'
    ],
    [
        'id' => 2,
        'name' => 'Belle Meade Country Club',
        'location' => 'Nashville, TN',
        'region' => 'Nashville Area',
        'description' => 'Historic Donald Ross design since 1921 in exclusive Belle Meade neighborhood',
        'image' => '/images/courses/belle-meade-country-club/1.jpeg',
        'price_range' => 'Private Club',
        'difficulty' => 'Advanced',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Donald Ross',
        'amenities' => ['Championship Golf', 'Historic Clubhouse', 'Fine Dining', 'Tennis Courts', 'Swimming Pool', 'USGA Championships'],
        'slug' => 'belle-meade-country-club'
    ],
    [
        'id' => 28,
        'name' => 'Big Creek Golf Club',
        'location' => 'Millington, TN',
        'region' => 'Memphis Area',
        'description' => 'Historic B.G. Mitchell design from 1976. Semi-private course that challenged golfers with strategic bunkering and water hazards. PERMANENTLY CLOSED - preserved for historical reference.',
        'image' => '/images/courses/big-creek-golf-club/1.jpeg',
        'price_range' => 'CLOSED',
        'difficulty' => 'Advanced',
        'holes' => 18,
        'par' => 'Unknown',
        'designer' => 'B.G. Mitchell',
        'amenities' => ['Historical Reference', 'Formerly Semi-Private', 'Had Pro Shop', 'Had Putting Green', 'Walking Allowed'],
        'slug' => 'big-creek-golf-club'
    ],
    [
        'id' => 3,
        'name' => 'Cedar Crest Golf Club',
        'location' => 'Murfreesboro, TN',
        'region' => 'Nashville Area',
        'description' => 'John Floyd designed public course on rolling farmland, Golf Advisor Most Improved 2016',
        'image' => '/images/courses/cedar-crest-golf-club/1.jpeg',
        'price_range' => '$28-32',
        'difficulty' => 'Intermediate',
        'holes' => 18,
        'par' => 72,
        'designer' => 'John Floyd',
        'amenities' => ['Pro Shop', 'Restaurant', 'Driving Range', 'Putting Green', 'Tournament Hosting', 'Online Tee Times'],
        'slug' => 'cedar-crest-golf-club'
    ],
    [
        'id' => 32,
        'name' => 'Cherokee Country Club',
        'location' => 'Knoxville, TN',
        'region' => 'Knoxville Area',
        'description' => 'Historic 1907 private club featuring Donald Ross classic links-style design. Founded to promote and elevate golf with century-old tradition.',
        'image' => '/images/courses/cherokee-country-club/1.jpeg',
        'price_range' => 'Private Club',
        'difficulty' => 'Advanced',
        'holes' => 18,
        'par' => 70,
        'designer' => 'Donald Ross',
        'amenities' => ['Private Club', 'Donald Ross Design', 'Links-style Course', 'Family Club', 'Historic 1907 Foundation', 'Member Events'],
        'slug' => 'cherokee-country-club'
    ],
    [
        'id' => 4,
        'name' => 'Gaylord Springs Golf Links',
        'location' => 'Nashville, TN',
        'region' => 'Nashville Area',
        'description' => 'Scottish links-style course by Larry Nelson at Gaylord Opryland Resort',
        'image' => '/images/courses/gaylord-springs-golf-links/1.jpeg',
        'price_range' => 'Dynamic Pricing',
        'difficulty' => 'Intermediate',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Larry Nelson',
        'amenities' => ['43,000 sq ft Clubhouse', 'Pro Shop', 'Restaurant', 'Golf Institute', 'Driving Range', 'Practice Facilities', 'Event Space'],
        'slug' => 'gaylord-springs-golf-links'
    ],
    [
        'id' => 5,
        'name' => 'Forrest Crossing Golf Course',
        'location' => 'Franklin, TN',
        'region' => 'Nashville Area',
        'description' => 'Gary Roger Baird championship design featuring Harpeth River and island green',
        'image' => '/images/courses/forrest-crossing-golf-course/1.jpeg',
        'price_range' => 'Moderate',
        'difficulty' => 'Advanced',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Gary Roger Baird',
        'amenities' => ['Pro Shop', 'Grill Room', '8-Acre Driving Range', 'Putting Greens', 'Practice Bunker', 'Tournament Hosting', 'Wedding Venue'],
        'slug' => 'forrest-crossing-golf-course'
    ],
    [
        'id' => 35,
        'name' => 'Fox Den Country Club',
        'location' => 'Knoxville, TN',
        'region' => 'Knoxville Area',
        'description' => 'Premier private golf club with championship 18-hole layout. Seven tee options ranging from 4,718 to 7,110 yards accommodate all skill levels.',
        'image' => '/images/courses/fox-den-country-club/1.jpeg',
        'price_range' => 'Private Club',
        'difficulty' => 'Advanced',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Championship Design',
        'amenities' => ['Private Club', 'Championship Golf', 'Practice Facilities', 'Clubhouse Dining', 'Member Events', 'Professional Staff', 'Multiple Tees'],
        'slug' => 'fox-den-country-club'
    ],
    [
        'id' => 6,
        'name' => 'Greystone Golf Course',
        'location' => 'Dickson, TN',
        'region' => 'Nashville Area',
        'description' => 'Mark McCumber championship design, 10-time Tennessee State Open host',
        'image' => '/images/courses/greystone-golf-course/1.jpeg',
        'price_range' => '$35-65',
        'difficulty' => 'Advanced',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Mark McCumber',
        'amenities' => ['Pro Shop', 'Restaurant', 'Driving Range', 'Practice Facilities', 'Tournament Hosting', 'Golf Instruction'],
        'slug' => 'greystone-golf-course'
    ],
    [
        'id' => 7,
        'name' => 'Harpeth Hills Golf Course',
        'location' => 'Nashville, TN',
        'region' => 'Nashville Area',
        'description' => 'Premier municipal golf course through scenic Percy Warner Park',
        'image' => '/images/courses/harpeth-hills-golf-course/1.jpeg',
        'price_range' => '$25-45',
        'difficulty' => 'Intermediate',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Municipal Design',
        'amenities' => ['Pro Shop', 'Snack Bar', 'Driving Range', 'Practice Green', 'Golf Instruction', 'Cart Rental'],
        'slug' => 'harpeth-hills-golf-course'
    ],
    [
        'id' => 8,
        'name' => 'Hermitage Golf Course',
        'location' => 'Old Hickory, TN',
        'region' => 'Nashville Area',
        'description' => 'Two championship courses including Golf Digest Top 10 President\'s Reserve',
        'image' => '/images/courses/hermitage-golf-course/1.jpeg',
        'price_range' => '$38-59',
        'difficulty' => 'Intermediate',
        'holes' => 36,
        'par' => 72,
        'designer' => 'Denis Griffiths & Gary Roger Baird',
        'amenities' => ['Pro Shop', 'Restaurant', 'Golf Instruction', 'Driving Range', 'Practice Greens', 'Cottages', 'Event Venue'],
        'slug' => 'hermitage-golf-course'
    ],
    [
        'id' => 9,
        'name' => 'Hillwood Country Club',
        'location' => 'Nashville, TN',
        'region' => 'Nashville Area',
        'description' => 'Elite private club since 1957 with Dick Wilson design, ranked 14th in Tennessee',
        'image' => '/images/courses/hillwood-country-club/1.jpeg',
        'price_range' => 'Private Club',
        'difficulty' => 'Advanced',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Dick Wilson',
        'amenities' => ['Championship Golf', 'Historic Clubhouse', 'Fine Dining', 'Tennis Courts', 'Swimming Pool', 'Elite Greens'],
        'slug' => 'hillwood-country-club'
    ],
    [
        'id' => 10,
        'name' => 'Holston Hills Country Club',
        'location' => 'Knoxville, TN',
        'region' => 'Knoxville Area',
        'description' => 'Historic 1927 Donald Ross design, one of America\'s purest Ross courses',
        'image' => '/images/courses/holston-hills-country-club/1.jpeg',
        'price_range' => 'Private Club',
        'difficulty' => 'Advanced',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Donald Ross',
        'amenities' => ['Championship Golf', 'Fine Dining', 'Member Events', 'Tournament Hosting', 'Walking Friendly'],
        'slug' => 'holston-hills-country-club'
    ],
    [
        'id' => 11,
        'name' => 'Island Pointe Golf Club',
        'location' => 'Kodak, TN',
        'region' => 'Knoxville Area',
        'description' => 'Arthur Hills masterpiece with 3 island holes on the French Broad River',
        'image' => '/images/courses/island-pointe-golf-club/1.jpeg',
        'price_range' => 'Under $50',
        'difficulty' => 'Advanced',
        'holes' => 18,
        'par' => 'Championship',
        'designer' => 'Arthur Hills',
        'amenities' => ['Championship Course', 'Practice Facilities', 'PGA Instruction', 'Bar & Grill', 'River Views'],
        'slug' => 'island-pointe-golf-club'
    ],
    [
        'id' => 12,
        'name' => 'McCabe Golf Course',
        'location' => 'Nashville, TN',
        'region' => 'Nashville Area',
        'description' => 'Nashville\'s premier municipal course since 1942, voted "best place to play"',
        'image' => '/images/courses/mccabe-golf-course/1.jpeg',
        'price_range' => '$17-44',
        'difficulty' => 'Beginner-Friendly',
        'holes' => 27,
        'par' => 70,
        'designer' => 'Gary Roger Baird (South)',
        'amenities' => ['27-Hole Golf', 'Driving Range', 'PGA Instruction', 'Pro Shop', 'Practice Facilities', 'Golf Associations'],
        'slug' => 'mccabe-golf-course'
    ],
    [
        'id' => 39,
        'name' => 'Mirimichi Golf Course',
        'location' => 'Millington, TN',
        'region' => 'Memphis Area',
        'description' => 'Championship 18-hole golf course featuring over 7,400 yards with elevated greens, deep bunkers, and scenic water features. Name means "place of happy retreat" in Native American.',
        'image' => '/images/courses/mirimichi-golf-course/1.jpeg',
        'price_range' => '$40-65',
        'difficulty' => 'Advanced',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Championship Design',
        'amenities' => ['Championship Golf', 'Elevated Greens', 'Water Features', 'Strategic Bunkers', 'Multiple Tees', 'Natural Beauty'],
        'slug' => 'mirimichi-golf-course'
    ],
    [
        'id' => 13,
        'name' => 'Nashville National Golf Links',
        'location' => 'Joelton, TN',
        'region' => 'Nashville Area',
        'description' => 'Family-owned course featuring limestone bluffs, Sycamore Creek, and natural Tennessee beauty',
        'image' => '/images/courses/nashville-national-golf-links/1.jpeg',
        'price_range' => 'Moderate',
        'difficulty' => 'Intermediate',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Unknown',
        'amenities' => ['Pro Shop', 'Restaurant', 'Covered Driving Range', 'Practice Green', 'Natural Setting', 'Family Owned'],
        'slug' => 'nashville-national-golf-links'
    ],
    [
        'id' => 14,
        'name' => 'Nashville Golf & Athletic Club',
        'location' => 'Belle Meade, TN',
        'region' => 'Nashville Area',
        'description' => 'Historic private club since 1901 with Donald Ross design heritage',
        'image' => '/images/courses/nashville-golf-athletic-club/1.jpeg',
        'price_range' => 'Private Club',
        'difficulty' => 'Advanced',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Donald Ross',
        'amenities' => ['Championship Golf', 'Tennis Courts', 'Swimming Pool', 'Fine Dining', 'Fitness Center', 'Member Events'],
        'slug' => 'nashville-golf-athletic-club'
    ],
    [
        'id' => 15,
        'name' => 'Richland Country Club',
        'location' => 'Nashville, TN',
        'region' => 'Nashville Area',
        'description' => 'Historic club since 1901 with Jack Nicklaus design and Byron Nelson/Ben Hogan legacy',
        'image' => '/images/courses/richland-country-club/1.jpeg',
        'price_range' => 'Private Club',
        'difficulty' => 'Advanced',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Jack Nicklaus',
        'amenities' => ['Championship Golf', 'Historic Clubhouse', 'Fine Dining', 'Tennis Courts', 'Swimming Pool', 'Champions Heritage'],
        'slug' => 'richland-country-club'
    ],
    [
        'id' => 16,
        'name' => 'Stones River Country Club',
        'location' => 'Murfreesboro, TN',
        'region' => 'Nashville Area',
        'description' => 'Premier private country club since 1949 along historic Stones River',
        'image' => '/images/courses/stones-river-country-club/1.jpeg',
        'price_range' => 'Private Club',
        'difficulty' => 'Advanced',
        'holes' => 18,
        'par' => 71,
        'designer' => 'Bob Renaud',
        'amenities' => ['Championship Golf', 'Tennis Courts', 'Swimming Pool', 'Fine Dining', 'Fitness Center', 'Event Hosting'],
        'slug' => 'stones-river-country-club'
    ],
    [
        'id' => 30,
        'name' => 'Springhouse Golf Club',
        'location' => 'Nashville, TN',
        'region' => 'Nashville Area',
        'description' => 'Former Nashville public golf course permanently closed. Located at 18 Springhouse Lane. PERMANENTLY CLOSED - preserved for historical reference.',
        'image' => '/images/courses/springhouse-golf-club/1.jpeg',
        'price_range' => 'CLOSED',
        'difficulty' => 'Unknown',
        'holes' => 18,
        'par' => 'Unknown',
        'designer' => 'Unknown',
        'amenities' => ['Historical Reference', 'Formerly Public', 'Nashville Golf History'],
        'slug' => 'springhouse-golf-club'
    ],
    [
        'id' => 17,
        'name' => 'Old Hickory Country Club',
        'location' => 'Old Hickory, TN',
        'region' => 'Nashville Area',
        'description' => 'Historic 1926 private club originally built by DuPont for employees',
        'image' => '/images/courses/old-hickory-country-club/1.jpeg',
        'price_range' => 'Private Club',
        'difficulty' => 'Intermediate',
        'holes' => 18,
        'par' => 71,
        'designer' => 'George Livingstone',
        'amenities' => ['Championship Golf', 'Olympic Pool', 'Pickleball Courts', 'Fitness Center', '1926 Bar & Grill', 'Banquet Facilities'],
        'slug' => 'old-hickory-country-club'
    ],
    [
        'id' => 29,
        'name' => 'Percy Warner Golf Course',
        'location' => 'Nashville, TN',
        'region' => 'Nashville Area',
        'description' => 'Historic 1937 municipal course nestled in scenic Percy Warner Park. 9-hole layout through tree-lined fairways and small undulating greens.',
        'image' => '/images/courses/percy-warner-golf-course/1.jpeg',
        'price_range' => '$17-30',
        'difficulty' => 'Beginner-Friendly',
        'holes' => 9,
        'par' => 34,
        'designer' => 'Municipal Design',
        'amenities' => ['Municipal Course', 'Park Setting', 'Cart Rental', 'Club Rental', 'Walking Friendly', 'Senior Rates'],
        'slug' => 'percy-warner-golf-course'
    ],
    [
        'id' => 18,
        'name' => 'The Golf Club of Tennessee',
        'location' => 'Kingston Springs, TN',
        'region' => 'Nashville Area',
        'description' => 'Tom Fazio masterpiece, Golf Digest\'s #2 ranked course in Tennessee',
        'image' => '/images/courses/the-golf-club-of-tennessee/1.jpeg',
        'price_range' => 'Private Club',
        'difficulty' => 'Advanced',
        'holes' => 18,
        'par' => 71,
        'designer' => 'Tom Fazio',
        'amenities' => ['Championship Golf', 'Luxury Clubhouse', 'Fine Dining', 'Practice Facilities', 'Tournament Hosting', 'Guest Accommodations'],
        'slug' => 'the-golf-club-of-tennessee'
    ],
    [
        'id' => 18,
        'name' => 'The Grove',
        'location' => 'College Grove, TN',
        'region' => 'Nashville Area',
        'description' => 'Greg Norman Signature Design hosting Korn Ferry Tour and LIV Golf on 7,438 championship yards',
        'image' => '/images/courses/the-grove/1.jpeg',
        'price_range' => 'Private Club',
        'difficulty' => 'Advanced',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Greg Norman',
        'amenities' => ['Tournament Golf', 'Korn Ferry Tour Host', 'LIV Golf Venue', 'Championship Conditioning', 'Luxury Community', 'Tour Facilities'],
        'slug' => 'the-grove'
    ],
    [
        'id' => 20,
        'name' => 'The Governors Club',
        'location' => 'Brentwood, TN',
        'region' => 'Nashville Area',
        'description' => 'Arnold Palmer Signature Design on historic Winstead farmland in exclusive Brentwood',
        'image' => '/images/courses/the-governors-club/1.jpeg',
        'price_range' => 'Private Club',
        'difficulty' => 'Advanced',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Arnold Palmer',
        'amenities' => ['Championship Golf', 'Luxury Community', 'Fine Dining', 'Tennis Courts', 'Swimming Pool', 'Gated Security'],
        'slug' => 'the-governors-club'
    ],
    [
        'id' => 21,
        'name' => 'Temple Hills Country Club',
        'location' => 'Franklin, TN',
        'region' => 'Nashville Area',
        'description' => 'Prestigious private club with 27 holes designed by Leon Howard since 1972',
        'image' => '/images/courses/temple-hills-country-club/1.jpeg',
        'price_range' => 'Private Club',
        'difficulty' => 'Advanced',
        'holes' => 27,
        'par' => 72,
        'designer' => 'Leon Howard',
        'amenities' => ['27-Hole Golf', 'Junior Olympic Pool', 'Tennis Complex', 'Pickleball Courts', 'Fine Dining', 'Event Facilities'],
        'slug' => 'temple-hills-country-club'
    ],
    [
        'id' => 31,
        'name' => 'Ted Rhodes Golf Course',
        'location' => 'Nashville, TN',
        'region' => 'Nashville Area',
        'description' => 'Historic 1953 municipal course honoring golf pioneer Ted Rhodes. Links-style 18-hole layout on Cumberland River with challenging play for all skill levels.',
        'image' => '/images/courses/ted-rhodes-golf-course/1.jpeg',
        'price_range' => '$17-44',
        'difficulty' => 'Intermediate',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Municipal Design',
        'amenities' => ['Municipal Course', 'Links-style', 'Driving Range', 'Practice Facilities', 'Walking Friendly', 'Club Rental', 'Multiple Tees'],
        'slug' => 'ted-rhodes-golf-course'
    ],
    [
        'id' => 34,
        'name' => 'The Club at Gettysvue',
        'location' => 'Knoxville, TN',
        'region' => 'Knoxville Area',
        'description' => 'Premier private club in the foothills of the Great Smoky Mountains. Bland Pittman championship design with comprehensive amenities and multiple membership categories.',
        'image' => '/images/courses/the-club-at-gettysvue/1.jpeg',
        'price_range' => 'Private Club',
        'difficulty' => 'Advanced',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Bland Pittman',
        'amenities' => ['Private Club', 'Championship Golf', 'Practice Facilities', 'Clubhouse Dining', 'Aquatic Center', 'Fitness Center', 'Racquet Sports', 'Golf Shop'],
        'slug' => 'the-club-at-gettysvue'
    ],
    [
        'id' => 22,
        'name' => 'The Legacy Golf Course',
        'location' => 'Springfield, TN',
        'region' => 'Nashville Area',
        'description' => 'Raymond Floyd championship design ranked #2 public course in Tennessee',
        'image' => '/images/courses/the-legacy-golf-course/1.jpeg',
        'price_range' => '$33-73',
        'difficulty' => 'Advanced',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Raymond Floyd',
        'amenities' => ['Pro Shop', 'Restaurant', 'Driving Range', 'Four-Level Putting Green', 'Short Game Complex', 'PGA Instruction'],
        'slug' => 'the-legacy-golf-course'
    ],
    [
        'id' => 36,
        'name' => 'Three Ridges Golf Course',
        'location' => 'Knoxville, TN',
        'region' => 'Knoxville Area',
        'description' => 'Golf Digest Best Places To Play. Municipal course designed by Ault, Clark & Associates in the shadows of the Great Smoky Mountains with 63 strategically placed bunkers.',
        'image' => '/images/courses/three-ridges-golf-course/1.jpeg',
        'price_range' => '$30-55',
        'difficulty' => 'Advanced',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Ault, Clark & Associates',
        'amenities' => ['Municipal Course', 'Bentgrass Greens', 'Bermuda Fairways', '63 Bunkers', 'Tournament Host', 'Corporate Events', 'Knox County Amateur Championship'],
        'slug' => 'three-ridges-golf-course'
    ],
    [
        'id' => 23,
        'name' => 'TPC Southwind',
        'location' => 'Memphis, TN',
        'region' => 'Memphis Area',
        'description' => 'Championship PGA Tour venue with challenging water hazards',
        'image' => '/images/courses/tpc-southwind/1.jpeg',
        'price_range' => 'Private Club',
        'difficulty' => 'Advanced',
        'holes' => 18,
        'par' => 70,
        'designer' => 'Ron Prichard',
        'amenities' => ['Pro Shop', 'Fine Dining', 'Tennis Courts', 'Swimming Pool', 'Fitness Center'],
        'slug' => 'tpc-southwind'
    ],
    [
        'id' => 27,
        'name' => 'Troubadour Golf & Field Club',
        'location' => 'College Grove, TN',
        'region' => 'Nashville Area',
        'description' => 'Tom Fazio masterpiece featuring championship 18-hole design thoughtfully crafted for leisure and challenge. Private residential community with relaxed atmosphere and no tee times required.',
        'image' => '/images/courses/troubadour-golf-field-club/1.jpeg',
        'price_range' => 'Private Club',
        'difficulty' => 'Advanced',
        'holes' => 18,
        'par' => 71,
        'designer' => 'Tom Fazio',
        'amenities' => ['Practice Facility', 'TrackMan Range', 'Private Lessons', 'Residential Community', 'Relaxed Dress Code'],
        'slug' => 'troubadour-golf-field-club'
    ],
    [
        'id' => 24,
        'name' => 'Two Rivers Golf Course',
        'location' => 'Nashville, TN',
        'region' => 'Nashville Area',
        'description' => 'Municipal golf course at the confluence of Cumberland and Stones Rivers since 1973',
        'image' => '/images/courses/two-rivers-golf-course/1.jpeg',
        'price_range' => 'Municipal Rates',
        'difficulty' => 'Intermediate',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Leon Howard (1991 Redesign)',
        'amenities' => ['Pro Shop', 'Restaurant', 'Driving Range', 'Putting Green', 'Skyline Views', 'Municipal Pricing'],
        'slug' => 'two-rivers-golf-course'
    ],
    [
        'id' => 25,
        'name' => 'Vanderbilt Legends Club',
        'location' => 'Franklin, TN',
        'region' => 'Nashville Area',
        'description' => '36-hole championship facility by Tom Kite & Bob Cupp, home to Vanderbilt golf teams',
        'image' => '/images/courses/vanderbilt-legends-club/1.jpeg',
        'price_range' => 'Private Club',
        'difficulty' => 'Advanced',
        'holes' => 36,
        'par' => 72,
        'designer' => 'Tom Kite & Bob Cupp',
        'amenities' => ['36-Hole Golf', '19-Acre Practice Facility', 'Fine Dining', 'University Teams', 'Tournament Venue', 'Korn Ferry Tour Host'],
        'slug' => 'vanderbilt-legends-club'
    ],
    [
        'id' => 26,
        'name' => 'Willow Creek Golf Club',
        'location' => 'Knoxville, TN',
        'region' => 'Knoxville Area',
        'description' => 'Best public course in Knoxville with upscale country club experience',
        'image' => '/images/courses/willow-creek-golf-club/1.jpeg',
        'price_range' => 'Public',
        'difficulty' => 'Intermediate',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Bill Oliphant',
        'amenities' => ['Pro Shop', 'Bar & Grill', 'Golf Lessons', 'Tournament Hosting', 'Club Repair'],
        'slug' => 'willow-creek-golf-club'
    ],
    [
        'id' => 33,
        'name' => 'Whittle Springs Golf Course',
        'location' => 'Knoxville, TN',
        'region' => 'Knoxville Area',
        'description' => 'Knoxville\'s first public golf course since 1932. Historic municipal course by Morton & Sweetser with unique bunker-free design and small, fast greens.',
        'image' => '/images/courses/whittle-springs-golf-course/1.jpeg',
        'price_range' => 'Public',
        'difficulty' => 'Intermediate',
        'holes' => 18,
        'par' => 70,
        'designer' => 'Morton & Sweetser',
        'amenities' => ['Municipal Course', 'Historic 1932', 'No Sand Bunkers', 'Mobile App', 'City Amateur Host', 'Memberships Available'],
        'slug' => 'whittle-springs-golf-course'
    ],
    [
        'id' => 37,
        'name' => 'White Plains Golf Course',
        'location' => 'Cookeville, TN',
        'region' => 'Cookeville Area',
        'description' => 'Daily-fee golf course open year-round. 18-hole par 72 layout with four tee options and comprehensive amenities including driving range, lessons, and tournament hosting.',
        'image' => '/images/courses/white-plains-golf-course/1.jpeg',
        'price_range' => '$35-55',
        'difficulty' => 'Intermediate',
        'holes' => 18,
        'par' => 72,
        'designer' => 'Traditional Design',
        'amenities' => ['Daily-Fee Course', 'Driving Range', 'Golf Lessons', 'Dining Options', 'Golf Shop', 'Tournament Hosting', 'Beverage Cart', 'Year-Round Play'],
        'slug' => 'white-plains-golf-course'
    ],
    [
        'id' => 29,
        'name' => 'Cheekwood Golf Club',
        'location' => 'Franklin, TN',
        'region' => 'Nashville Area',
        'description' => 'Executive 9-hole course nestled within Franklin\'s beautifully wooded residential landscape. Features a challenging yet accessible par-33 composition with four par-4s, four par-3s, and one strategic par-5.',
        'image' => 'images/courses/cheekwood-golf-club/1.jpeg',
        'price_range' => '$20-25',
        'difficulty' => 'Beginner to Intermediate',
        'holes' => 9,
        'par' => 33,
        'designer' => 'Unknown',
        'amenities' => ['Pro Shop', 'Practice Area', 'Putting Green', 'Food & Beverages'],
        'slug' => 'cheekwood-golf-club'
    ],
    [
        'id' => 42,
        'name' => 'Sweetens Cove Golf Club',
        'location' => 'South Pittsburg, TN',
        'region' => 'Chattanooga Area',
        'description' => 'Handcrafted masterpiece with wildly contoured greens and elaborate bunkering in scenic Sequatchie Valley. One of the most audacious 9-hole designs in the Southeast.',
        'image' => '/images/courses/sweetens-cove-golf-club/1.jpeg',
        'price_range' => '$15-135',
        'difficulty' => 'Advanced',
        'holes' => 9,
        'par' => 36,
        'designer' => 'King Collins Dormer',
        'amenities' => ['Pro Shop', 'Practice Area', 'Cart Rentals', 'All-Day Play', 'Unique Design'],
        'slug' => 'sweetens-cove-golf-club'
    ],
    [
        'id' => 43,
        'name' => 'Warrior\'s Path State Park Golf Course',
        'location' => 'Kingsport, TN',
        'region' => 'Tri-Cities Area',
        'description' => 'George Cobb designed championship course on Fort Patrick Henry Lake with scenic mountain views. One of the most popular courses in the Tennessee State Parks system.',
        'image' => '/images/courses/warriors-path-state-park-golf-course/1.jpeg',
        'price_range' => '$22-27',
        'difficulty' => 'Intermediate',
        'holes' => 18,
        'par' => 72,
        'designer' => 'George Cobb',
        'amenities' => ['Driving Range', 'Practice Green', 'Pro Shop', 'Snack Bar', 'Cart Rentals', 'PGA Lessons', 'Practice Bunker', 'Event Hosting'],
        'slug' => 'warriors-path-state-park-golf-course'
    ],
    [
        'id' => 44,
        'name' => 'Eagle\'s Landing Golf Club',
        'location' => 'Sevierville, TN',
        'region' => 'Smoky Mountains Area',
        'description' => 'D.J. DeVictor designed championship municipal course in the heart of the Great Smoky Mountains. Recently named Best Public Golf Course by Tennessee Turfgrass Association.',
        'image' => '/images/courses/eagles-landing-golf-club/1.jpeg',
        'price_range' => '$38-79',
        'difficulty' => 'Intermediate',
        'holes' => 18,
        'par' => 72,
        'designer' => 'D.J. DeVictor',
        'amenities' => ['Driving Range', 'Putting Green', 'Pro Shop', 'Restaurant', 'Cart Included', 'PGA Instruction', 'Practice Bunkers', 'Banquet Facility'],
        'slug' => 'eagles-landing-golf-club'
    ],
    [
        'id' => 45,
        'name' => 'Sevierville Golf Club',
        'location' => 'Sevierville, TN',
        'region' => 'Smoky Mountains Area',
        'description' => 'Premier championship golf facility with two 18-hole courses (River & Highlands) plus 9-hole option. Recognized by Golf Advisor as one of top 50 US Courses.',
        'image' => '/images/courses/sevierville-golf-club/1.jpeg',
        'price_range' => '$29-79',
        'difficulty' => 'Intermediate',
        'holes' => '36 + 9',
        'par' => '70/72',
        'designer' => 'D.J. DeVictor',
        'amenities' => ['Extensive Driving Range', 'Putting Green', 'Pro Shop', 'Restaurant', 'Cart Included', 'PGA Instruction', 'Chipping Area', 'Online Booking', 'Event Dining'],
        'slug' => 'sevierville-golf-club'
    ]
];

// Get real ratings from database for each course
$courses = [];
foreach ($courses_static as $course) {
    try {
        $stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM course_comments WHERE course_slug = ?");
        $stmt->execute([$course['slug']]);
        $rating_data = $stmt->fetch();
        
        $course['avg_rating'] = $rating_data['avg_rating'] ? round($rating_data['avg_rating'], 1) : null;
        $course['review_count'] = $rating_data['total_reviews'] ?: 0;
        
    } catch (PDOException $e) {
        $course['avg_rating'] = null;
        $course['review_count'] = 0;
    }
    
    $courses[] = $course;
}

// Apply filters to courses
$filtered_courses = [];
foreach ($courses as $course) {
    // Region filter
    if ($region_filter && $course['region'] !== $region_filter) {
        continue;
    }
    
    // Price filter
    if ($price_filter) {
        $price_match = false;
        switch ($price_filter) {
            case 'budget':
                $price_match = (strpos($course['price_range'], '$') !== false && 
                              (strpos($course['price_range'], 'Under') !== false ||
                               preg_match('/\$\d+-\d+/', $course['price_range']) && 
                               (int)preg_replace('/[^\d]/', '', $course['price_range']) < 50));
                break;
            case 'moderate':
                $price_match = (strpos($course['price_range'], '$') !== false && 
                              preg_match('/\$\d+-\d+/', $course['price_range']) && 
                              (int)preg_replace('/[^\d]/', '', $course['price_range']) >= 50 && 
                              (int)preg_replace('/[^\d]/', '', $course['price_range']) <= 100);
                break;
            case 'premium':
                $price_match = ($course['price_range'] === 'Private Club' || 
                              (strpos($course['price_range'], '$') !== false && 
                               preg_match('/\$\d+-\d+/', $course['price_range']) && 
                               (int)preg_replace('/[^\d]/', '', $course['price_range']) > 100));
                break;
        }
        if (!$price_match) continue;
    }
    
    // Difficulty filter
    if ($difficulty_filter && strpos($course['difficulty'], $difficulty_filter) === false) {
        continue;
    }
    
    // Amenities filter
    if ($amenities_filter && is_array($amenities_filter)) {
        $has_amenity = false;
        foreach ($amenities_filter as $amenity) {
            if (in_array($amenity, $course['amenities'])) {
                $has_amenity = true;
                break;
            }
        }
        if (!$has_amenity) continue;
    }
    
    $filtered_courses[] = $course;
}

// Apply sorting
switch ($sort_by) {
    case 'rating':
        usort($filtered_courses, function($a, $b) {
            if ($a['avg_rating'] === null && $b['avg_rating'] === null) return 0;
            if ($a['avg_rating'] === null) return 1;
            if ($b['avg_rating'] === null) return -1;
            return $b['avg_rating'] <=> $a['avg_rating'];
        });
        break;
    case 'name':
        usort($filtered_courses, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
        break;
    case 'price_low':
    case 'price_high':
        usort($filtered_courses, function($a, $b) use ($sort_by) {
            $price_a = $a['price_range'] === 'Private Club' ? 999 : (int)preg_replace('/[^\d]/', '', $a['price_range']);
            $price_b = $b['price_range'] === 'Private Club' ? 999 : (int)preg_replace('/[^\d]/', '', $b['price_range']);
            return $sort_by === 'price_low' ? $price_a <=> $price_b : $price_b <=> $price_a;
        });
        break;
}

// Pagination calculations
$total_courses = count($filtered_courses);
$total_pages = ceil($total_courses / $courses_per_page);
$page = max(1, min($page, $total_pages)); // Ensure page is valid
$offset = ($page - 1) * $courses_per_page;

// Get courses for current page
$paginated_courses = array_slice($filtered_courses, $offset, $courses_per_page);

// Get top 2 rated courses for featured section (from all courses, not filtered)
$featured_courses = array_slice(array_filter($courses, function($course) {
    return $course['avg_rating'] !== null;
}), 0, 2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Golf Courses in Tennessee - Find Your Perfect Course</title>
    <meta name="description" content="Discover the best golf courses in Tennessee. Browse by location, price, difficulty and amenities. Read reviews and book tee times.">
    <link rel="stylesheet" href="/styles.css?v=5">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/logos/tab-logo.png?v=2">
    <link rel="shortcut icon" href="/images/logos/tab-logo.png?v=2">
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-7VPNPCDTBP');
    </script>
    
    <style>
        .courses-page {
            padding-top: 0px;
            min-height: 40vh;
            background: var(--bg-light);
        }
        
        .page-header {
            background: var(--bg-white);
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-color);
            margin-top: 80px;
        }
        
        .page-title {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .page-subtitle {
            font-size: 1.1rem;
            color: var(--text-gray);
        }
        
        .courses-container {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 2rem;
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .filters-sidebar {
            background: var(--bg-white);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            max-height: calc(100vh - 150px);
            overflow-y: auto;
            position: sticky;
            top: 110px;
            min-width: 280px;
        }
        
        /* Custom scrollbar for sidebar */
        .filters-sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .filters-sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        
        .filters-sidebar::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 3px;
        }
        
        .filters-sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-color);
        }
        
        .filter-group {
            margin-bottom: 2rem;
        }
        
        .filter-title {
            font-size: 1.8rem !important;
            font-weight: 700 !important;
            color: var(--primary-color) !important;
            margin-bottom: 2rem !important;
            padding: 1rem 0 !important;
        }
        
        .filter-option {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .filter-option input {
            margin-right: 0.5rem;
        }
        
        .filter-option label {
            font-size: 0.9rem;
            color: var(--text-dark);
            cursor: pointer;
        }
        
        /* New Sort Design - Button Based */
        .new-sort-container {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 4rem 2rem;
            border-radius: 15px;
            margin-bottom: 3rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            min-height: 300px;
        }
        
        .new-sort-title {
            color: white;
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 3rem;
            text-align: center;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .new-sort-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            row-gap: 1rem;
        }
        
        .new-sort-btn {
            background: white;
            color: var(--primary-color);
            border: none;
            padding: 1.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            min-height: 45px;
        }
        
        .new-sort-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            background: #f8f9fa;
        }
        
        .new-sort-btn.active {
            background: var(--gold-color);
            color: var(--text-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 215, 0, 0.3);
        }
        
        .new-sort-btn i {
            font-size: 1rem;
        }
        
        .courses-content {
            background: var(--bg-white);
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            overflow: hidden;
        }
        
        .featured-section {
            padding: 2rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .featured-title {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .all-courses-section {
            padding: 2rem;
        }
        
        .section-title {
            font-size: 1.3rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }
        
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
        }
        
        .course-card {
            background: var(--bg-white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-light);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
            border-color: var(--primary-color);
        }
        
        .course-image {
            position: relative;
            height: 200px;
            overflow: hidden;
        }
        
        .course-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .course-card:hover .course-image img {
            transform: scale(1.05);
        }
        
        .course-rating {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.95);
            padding: 0.5rem 0.75rem;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-weight: 600;
            color: var(--gold-color);
        }
        
        .course-content {
            padding: 1.5rem;
        }
        
        .course-content h3 {
            font-size: 1.3rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .course-location {
            color: var(--text-gray);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .course-description {
            color: var(--text-dark);
            margin-bottom: 1rem;
            line-height: 1.5;
        }
        
        .course-features {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .feature-tag {
            background: var(--bg-light);
            color: var(--primary-color);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .course-amenities {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .amenity-icon {
            color: var(--secondary-color);
            font-size: 1.1rem;
            padding: 0.25rem;
        }
        
        .course-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
        }
        
        .course-price {
            font-weight: 600;
            color: var(--secondary-color);
            font-size: 1.1rem;
        }
        
        .view-course-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .view-course-btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-medium);
        }
        
        .no-results {
            text-align: center;
            padding: 3rem;
            color: var(--text-gray);
        }
        
        /* Pagination Styles */
        .pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 3rem;
            padding: 2rem 0;
            gap: 0.5rem;
        }
        
        .pagination-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border: 2px solid var(--border-color);
            background: var(--bg-white);
            color: var(--primary-color);
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .pagination-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-1px);
        }
        
        .pagination-btn.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .pagination-btn.disabled {
            opacity: 0.5;
            pointer-events: none;
        }
        
        .pagination-info {
            margin: 0 1rem;
            color: var(--text-gray);
            font-size: 0.9rem;
        }
        
        .results-summary {
            background: var(--bg-light);
            padding: 1rem 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            color: var(--text-dark);
            font-weight: 500;
        }
        
        @media screen and (max-width: 1024px) {
            .courses-container {
                grid-template-columns: 1fr;
                gap: 1rem;
                padding: 1rem;
            }
            
            .filters-sidebar {
                position: static;
                height: auto;
                max-height: none;
                margin-bottom: 1rem;
            }
            
            .new-sort-container {
                padding: 1.8rem;
            }
            
            .new-sort-title {
                font-size: 1.8rem;
                margin-bottom: 1.8rem;
            }
            
            .new-sort-btn {
                padding: 1.4rem 1.2rem;
                font-size: 1.2rem;
            }
        }
        
        @media screen and (max-width: 768px) {
            .courses-page {
                padding-top: 0px;
            }
            
            .page-header {
                padding: 0.5rem 0;
                margin-top: 70px;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .courses-grid {
                grid-template-columns: 1fr;
            }
            
            .course-card {
                margin-bottom: 1rem;
            }
            
            .filters-sidebar {
                padding: 1rem;
            }
            
            .new-sort-container {
                padding: 2rem;
                margin-bottom: 3rem;
            }
            
            .new-sort-title {
                font-size: 2rem;
                margin-bottom: 2rem;
            }
            
            .new-sort-buttons {
                grid-template-columns: 1fr;
                gap: 1.2rem;
            }
            
            .new-sort-btn {
                padding: 1.6rem 1.4rem;
                font-size: 1.3rem;
            }
            
            .pagination-btn {
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
            }
            
            .pagination-info {
                font-size: 0.8rem;
            }
        }
        
        @media screen and (max-width: 480px) {
            .pagination-container {
                flex-wrap: wrap;
                gap: 0.25rem;
            }
            
            .pagination-btn {
                width: 32px;
                height: 32px;
                font-size: 0.8rem;
            }
            
            .new-sort-container {
                padding: 2.2rem;
                margin-bottom: 3.5rem;
            }
            
            .new-sort-title {
                font-size: 2.2rem;
                margin-bottom: 2.5rem;
            }
            
            .new-sort-buttons {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .new-sort-btn {
                padding: 1.8rem 1.6rem;
                font-size: 1.4rem;
                font-weight: 700;
            }
        }
        
        /* Search Bar Styles */
        .search-bar-container {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .search-bar-wrapper {
            position: relative;
        }
        
        .search-bar {
            width: 100%;
            padding: 12px 40px 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .search-bar:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(44, 82, 52, 0.1);
        }
        
        .search-bar-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }
        
        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            max-height: 400px;
            overflow-y: auto;
            z-index: 100;
            display: none;
            margin-top: 4px;
        }
        
        .search-result-item {
            padding: 12px 16px;
            border-bottom: 1px solid #f3f4f6;
            cursor: pointer;
            transition: background 0.2s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .search-result-item:hover {
            background: #f9fafb;
        }
        
        .search-result-item:last-child {
            border-bottom: none;
        }
        
        .search-result-name {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 15px;
        }
        
        .search-result-location {
            color: var(--text-gray);
            font-size: 13px;
            margin-top: 2px;
        }
        
        .search-no-results {
            padding: 20px;
            text-align: center;
            color: var(--text-gray);
        }
    </style>
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include 'includes/navigation.php'; ?>

    <div class="courses-page">
        <!-- Page Header -->
        <section class="page-header">
            <div class="container">
                <h1 class="page-title">Tennessee Golf Courses</h1>
                <p class="page-subtitle">Discover premier golf destinations across the Volunteer State</p>
            </div>
        </section>

        <!-- Main Content -->
        <div class="courses-container">
            <!-- Filters Sidebar -->
            <aside class="filters-sidebar">
                <form method="GET" id="filters-form">
                    <!-- Search Bar -->
                    <div class="search-bar-container">
                        <div class="search-bar-wrapper">
                            <input type="text" class="search-bar" id="courses-search" placeholder="Search courses..." autocomplete="off">
                            <i class="fas fa-search search-bar-icon"></i>
                            <div class="search-results" id="courses-search-results"></div>
                        </div>
                    </div>
                    
                    <!-- Sort Options - Completely New Design -->
                    <div class="new-sort-container">
                        <h3 class="new-sort-title">Sort Golf Courses</h3>
                        <div class="new-sort-buttons">
                            <button type="submit" name="sort" value="name" class="new-sort-btn <?php echo $sort_by === 'name' ? 'active' : ''; ?>">
                                <i class="fas fa-sort-alpha-down"></i>
                                Name A-Z
                            </button>
                            <button type="submit" name="sort" value="rating" class="new-sort-btn <?php echo $sort_by === 'rating' ? 'active' : ''; ?>">
                                <i class="fas fa-star"></i>
                                Top Rated
                            </button>
                            <button type="submit" name="sort" value="price_low" class="new-sort-btn <?php echo $sort_by === 'price_low' ? 'active' : ''; ?>">
                                <i class="fas fa-dollar-sign"></i>
                                Price Low
                            </button>
                            <button type="submit" name="sort" value="price_high" class="new-sort-btn <?php echo $sort_by === 'price_high' ? 'active' : ''; ?>">
                                <i class="fas fa-coins"></i>
                                Price High
                            </button>
                        </div>
                    </div>

                    <!-- Region Filter -->
                    <div class="filter-group">
                        <div class="filter-title">Region</div>
                        <div class="filter-option">
                            <input type="radio" name="region" value="" id="region_all" <?php echo $region_filter === '' ? 'checked' : ''; ?>>
                            <label for="region_all">All Regions</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="region" value="Nashville Area" id="region_nash" <?php echo $region_filter === 'Nashville Area' ? 'checked' : ''; ?>>
                            <label for="region_nash">Nashville Area</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="region" value="Chattanooga Area" id="region_chat" <?php echo $region_filter === 'Chattanooga Area' ? 'checked' : ''; ?>>
                            <label for="region_chat">Chattanooga Area</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="region" value="Knoxville Area" id="region_knox" <?php echo $region_filter === 'Knoxville Area' ? 'checked' : ''; ?>>
                            <label for="region_knox">Knoxville Area</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="region" value="Memphis Area" id="region_mem" <?php echo $region_filter === 'Memphis Area' ? 'checked' : ''; ?>>
                            <label for="region_mem">Memphis Area</label>
                        </div>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="filter-group">
                        <div class="filter-title">Price Range</div>
                        <div class="filter-option">
                            <input type="radio" name="price" value="" id="price_all" <?php echo $price_filter === '' ? 'checked' : ''; ?>>
                            <label for="price_all">All Prices</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="price" value="budget" id="price_budget" <?php echo $price_filter === 'budget' ? 'checked' : ''; ?>>
                            <label for="price_budget">Under $50</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="price" value="moderate" id="price_mod" <?php echo $price_filter === 'moderate' ? 'checked' : ''; ?>>
                            <label for="price_mod">$50 - $100</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="price" value="premium" id="price_prem" <?php echo $price_filter === 'premium' ? 'checked' : ''; ?>>
                            <label for="price_prem">$100+</label>
                        </div>
                    </div>

                    <!-- Difficulty Filter -->
                    <div class="filter-group">
                        <div class="filter-title">Difficulty</div>
                        <div class="filter-option">
                            <input type="radio" name="difficulty" value="" id="diff_all" <?php echo $difficulty_filter === '' ? 'checked' : ''; ?>>
                            <label for="diff_all">All Levels</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="difficulty" value="Beginner" id="diff_beg" <?php echo $difficulty_filter === 'Beginner' ? 'checked' : ''; ?>>
                            <label for="diff_beg">Beginner</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="difficulty" value="Intermediate" id="diff_int" <?php echo $difficulty_filter === 'Intermediate' ? 'checked' : ''; ?>>
                            <label for="diff_int">Intermediate</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="difficulty" value="Advanced" id="diff_adv" <?php echo $difficulty_filter === 'Advanced' ? 'checked' : ''; ?>>
                            <label for="diff_adv">Advanced</label>
                        </div>
                    </div>

                    <!-- Amenities Filter -->
                    <div class="filter-group">
                        <div class="filter-title">Amenities</div>
                        <div class="filter-option">
                            <input type="checkbox" name="amenities[]" value="Pro Shop" id="amenity_pro" <?php echo strpos($amenities_filter, 'Pro Shop') !== false ? 'checked' : ''; ?>>
                            <label for="amenity_pro">Pro Shop</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" name="amenities[]" value="Restaurant" id="amenity_rest" <?php echo strpos($amenities_filter, 'Restaurant') !== false ? 'checked' : ''; ?>>
                            <label for="amenity_rest">Restaurant</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" name="amenities[]" value="Driving Range" id="amenity_range" <?php echo strpos($amenities_filter, 'Driving Range') !== false ? 'checked' : ''; ?>>
                            <label for="amenity_range">Driving Range</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" name="amenities[]" value="Putting Green" id="amenity_putting" <?php echo strpos($amenities_filter, 'Putting Green') !== false ? 'checked' : ''; ?>>
                            <label for="amenity_putting">Putting Green</label>
                        </div>
                    </div>
                </form>
            </aside>

            <!-- Courses Content -->
            <main class="courses-content">
                <!-- Featured Courses -->
                <section class="featured-section">
                    <h2 class="featured-title">
                        <i class="fas fa-star"></i>
                        Top Rated Courses
                    </h2>
                    <div class="courses-grid">
                        <?php foreach ($featured_courses as $course): ?>
                            <div class="course-card">
                                <div class="course-image">
                                    <img src="<?php echo htmlspecialchars($course['image']); ?>" alt="<?php echo htmlspecialchars($course['name']); ?>">
                                    <div class="course-rating">
                                        <?php if ($course['avg_rating'] !== null): ?>
                                            <i class="fas fa-star"></i>
                                            <?php echo number_format($course['avg_rating'], 1); ?>
                                            <span style="color: var(--text-gray); font-size: 0.9rem;">(<?php echo $course['review_count']; ?>)</span>
                                        <?php else: ?>
                                            <i class="fas fa-star" style="color: #ddd;"></i>
                                            <span style="color: #666;">No ratings yet</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="course-content">
                                    <h3><?php echo htmlspecialchars($course['name']); ?></h3>
                                    <p class="course-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <?php echo htmlspecialchars($course['location']); ?>
                                    </p>
                                    <p class="course-description"><?php echo htmlspecialchars($course['description']); ?></p>
                                    <div class="course-features">
                                        <span class="feature-tag"><?php echo $course['holes']; ?> Holes</span>
                                        <span class="feature-tag">Par <?php echo $course['par']; ?></span>
                                        <span class="feature-tag"><?php echo htmlspecialchars($course['designer']); ?> Design</span>
                                    </div>
                                    <div class="course-amenities">
                                        <?php foreach ($course['amenities'] as $amenity): ?>
                                            <i class="fas fa-check amenity-icon" title="<?php echo htmlspecialchars($amenity); ?>"></i>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="course-footer">
                                        <span class="course-price"><?php echo htmlspecialchars($course['price_range']); ?></span>
                                        <a href="courses/<?php echo htmlspecialchars($course['slug']); ?>" class="view-course-btn">View Details</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- All Courses -->
                <section class="all-courses-section">
                    <div class="results-summary">
                        <?php if ($total_courses > 0): ?>
                            Showing <?php echo $offset + 1; ?> - <?php echo min($offset + $courses_per_page, $total_courses); ?> of <?php echo $total_courses; ?> golf courses
                        <?php else: ?>
                            No courses found matching your criteria
                        <?php endif; ?>
                    </div>
                    
                    <h2 class="section-title">All Tennessee Golf Courses</h2>
                    
                    <div class="courses-grid">
                        <?php if (!empty($paginated_courses)): ?>
                            <?php foreach ($paginated_courses as $course): ?>
                                <div class="course-card">
                                    <div class="course-image">
                                        <img src="<?php echo htmlspecialchars($course['image']); ?>" alt="<?php echo htmlspecialchars($course['name']); ?>">
                                        <div class="course-rating">
                                            <?php if ($course['avg_rating'] !== null): ?>
                                                <i class="fas fa-star"></i>
                                                <?php echo number_format($course['avg_rating'], 1); ?>
                                                <span style="color: var(--text-gray); font-size: 0.9rem;">(<?php echo $course['review_count']; ?>)</span>
                                            <?php else: ?>
                                                <i class="fas fa-star" style="color: #ddd;"></i>
                                                <span style="color: #666; font-size: 0.8rem;">No ratings</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="course-content">
                                        <h3><?php echo htmlspecialchars($course['name']); ?></h3>
                                        <p class="course-location">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?php echo htmlspecialchars($course['location']); ?>
                                        </p>
                                        <p class="course-description"><?php echo htmlspecialchars($course['description']); ?></p>
                                        <div class="course-features">
                                            <span class="feature-tag"><?php echo $course['holes']; ?> Holes</span>
                                            <span class="feature-tag">Par <?php echo $course['par']; ?></span>
                                            <span class="feature-tag"><?php echo htmlspecialchars($course['designer']); ?> Design</span>
                                        </div>
                                        <div class="course-amenities">
                                            <?php foreach (array_slice($course['amenities'], 0, 4) as $amenity): ?>
                                                <i class="fas fa-check amenity-icon" title="<?php echo htmlspecialchars($amenity); ?>"></i>
                                            <?php endforeach; ?>
                                            <?php if (count($course['amenities']) > 4): ?>
                                                <span style="color: var(--text-gray); font-size: 0.8rem;">+<?php echo count($course['amenities']) - 4; ?> more</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="course-footer">
                                            <span class="course-price"><?php echo htmlspecialchars($course['price_range']); ?></span>
                                            <a href="courses/<?php echo htmlspecialchars($course['slug']); ?>" class="view-course-btn">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="no-results">
                                <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 1rem; color: var(--text-gray);"></i>
                                <h3>No courses found</h3>
                                <p>Try adjusting your filters to see more results.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="pagination-container">
                            <!-- Previous Button -->
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => max(1, $page - 1)])); ?>" 
                               class="pagination-btn <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                            
                            <!-- Page Numbers -->
                            <?php
                            $start_page = max(1, $page - 2);
                            $end_page = min($total_pages, $page + 2);
                            
                            // Show first page if not in range
                            if ($start_page > 1): ?>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => 1])); ?>" class="pagination-btn">1</a>
                                <?php if ($start_page > 2): ?>
                                    <span class="pagination-btn disabled">...</span>
                                <?php endif; ?>
                            <?php endif;
                            
                            // Show page range
                            for ($i = $start_page; $i <= $end_page; $i++): ?>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>" 
                                   class="pagination-btn <?php echo $i == $page ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor;
                            
                            // Show last page if not in range
                            if ($end_page < $total_pages): ?>
                                <?php if ($end_page < $total_pages - 1): ?>
                                    <span class="pagination-btn disabled">...</span>
                                <?php endif; ?>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $total_pages])); ?>" class="pagination-btn"><?php echo $total_pages; ?></a>
                            <?php endif; ?>
                            
                            <!-- Next Button -->
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => min($total_pages, $page + 1)])); ?>" 
                               class="pagination-btn <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                            
                            <span class="pagination-info">
                                Page <?php echo $page; ?> of <?php echo $total_pages; ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </section>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <img src="/images/logos/logo.png" alt="Tennessee Golf Courses" class="footer-logo-image">
                    </div>
                    <p>Your premier destination for discovering the best golf courses across Tennessee.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="courses.php">Golf Courses</a></li>
                        <li><a href="reviews.php">Reviews</a></li>
                        <li><a href="news.php">News</a></li>
                        <li><a href="about.php">About Us</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Regions</h4>
                    <ul>
                        <li><a href="courses.php?region=Nashville Area">Nashville Area</a></li>
                        <li><a href="courses.php?region=Chattanooga Area">Chattanooga Area</a></li>
                        <li><a href="courses.php?region=Knoxville Area">Knoxville Area</a></li>
                        <li><a href="courses.php?region=Memphis Area">Memphis Area</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="/privacy-policy">Privacy Policy</a></li>
                        <li><a href="/terms-of-service">Terms of Service</a></li>
                        <li><a href="/contact">Contact Us</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Tennessee Golf Courses. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="/weather.js?v=4"></script>
    <script src="/script.js?v=4"></script>
    <script>
        // Auto-submit form when filters change
        document.addEventListener('DOMContentLoaded', function() {
            const filterInputs = document.querySelectorAll('input[type="radio"], input[type="checkbox"]');
            filterInputs.forEach(input => {
                input.addEventListener('change', function() {
                    document.getElementById('filters-form').submit();
                });
            });
            
            // Search functionality
            const coursesSearchInput = document.getElementById('courses-search');
            const coursesSearchResults = document.getElementById('courses-search-results');
            let searchTimeout;

            coursesSearchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value.trim();
                
                if (query.length < 2) {
                    coursesSearchResults.style.display = 'none';
                    return;
                }
                
                searchTimeout = setTimeout(() => {
                    fetchCourses(query);
                }, 300);
            });

            function fetchCourses(query) {
                fetch(`/search-courses.php?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        displayResults(data);
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                    });
            }

            function displayResults(courses) {
                coursesSearchResults.innerHTML = '';
                
                if (courses.length === 0) {
                    coursesSearchResults.innerHTML = '<div class="search-no-results">No courses found</div>';
                    coursesSearchResults.style.display = 'block';
                    return;
                }
                
                courses.forEach(course => {
                    const item = document.createElement('div');
                    item.className = 'search-result-item';
                    item.innerHTML = `
                        <div>
                            <div class="search-result-name">${course.name}</div>
                            <div class="search-result-location">${course.location}</div>
                        </div>
                        <i class="fas fa-chevron-right" style="color: #ccc; font-size: 12px;"></i>
                    `;
                    
                    item.addEventListener('click', () => {
                        window.location.href = `/courses/${course.slug}`;
                    });
                    
                    coursesSearchResults.appendChild(item);
                });
                
                coursesSearchResults.style.display = 'block';
            }

            // Close results when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.search-bar-container')) {
                    coursesSearchResults.style.display = 'none';
                }
            });

            // Handle Enter key in search input
            coursesSearchInput.addEventListener('keypress', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    const query = this.value.trim();
                    if (query) {
                        window.location.href = `/courses?search=${encodeURIComponent(query)}`;
                    }
                }
            });
        });
    </script>
</body>
</html>