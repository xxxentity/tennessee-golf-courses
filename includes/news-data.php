<?php
// News articles data - no session_start() to avoid conflicts
// Get all news articles (automatically sorted by date and time, newest first)
$articles_raw = [
    [
        'title' => 'Legion XIII Captures LIV Golf Team Championship in Thrilling Playoff Victory Over Crushers GC',
        'slug' => 'liv-golf-michigan-2025-championship-legion-xiii-playoff-victory',
        'date' => '2025-08-24',
        'time' => '11:00 PM',
        'category' => 'Tournament News',
        'excerpt' => 'Jon Rahm\'s Legion XIII defeated Bryson DeChambeau\'s Crushers GC in the first team championship playoff, claiming the $14 million prize at The Cardinal at Saint John\'s in Plymouth, Michigan.',
        'image' => '/images/news/liv-golf-michigan-2025-championship-legion-xiii-playoff-victory/main.webp',
        'featured' => true
    ],
    [
        'title' => 'LIV Golf Michigan Team Championship Semifinals: Legion XIII, Crushers, Stinger Advance in Playoff Drama',
        'slug' => 'liv-golf-michigan-2025-semifinals-thriller',
        'date' => '2025-08-23',
        'time' => '7:00 PM',
        'category' => 'Tournament News',
        'excerpt' => 'Three teams survived intense semifinals action at The Cardinal at Saint John\'s in Plymouth, Michigan, setting up Sunday\'s stroke-play finale for the $14 million prize.',
        'image' => '/images/news/liv-golf-michigan-2025-semifinals-thriller/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Fleetwood Captures Historic First PGA Tour Win at Tour Championship',
        'slug' => 'tour-championship-2025-final-round-fleetwood-historic-win',
        'date' => '2025-08-24',
        'time' => '10:00 PM',
        'category' => 'Tournament News',
        'excerpt' => 'Tommy Fleetwood wins Tour Championship and FedEx Cup with final round 68 for 18-under total. Englishman claims first PGA Tour victory and $10 million prize at East Lake.',
        'image' => '/images/news/tour-championship-2025-final-round-fleetwood-historic-win/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Cantlay Charges to Share Lead with Fleetwood After Tour Championship Third Round',
        'slug' => 'tour-championship-2025-round-3-cantlay-fleetwood-tied',
        'date' => '2025-08-23',
        'time' => '8:00 PM',
        'category' => 'Tournament News',
        'excerpt' => 'Patrick Cantlay fires 64 with late surge to tie Tommy Fleetwood at 16-under. Keegan Bradley posts day\'s best 63 while Russell Henley sits two back at East Lake.',
        'image' => '/images/news/tour-championship-2025-round-3-cantlay-fleetwood-tied/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Legion XIII and Crushers GC Advance as LIV Golf Michigan Team Championship Begins',
        'slug' => 'liv-golf-michigan-team-championship-2025-quarterfinals',
        'date' => '2025-08-22',
        'time' => '8:15 PM',
        'category' => 'Tournament News',
        'excerpt' => 'Six teams advance to semifinals at LIV Golf Michigan Team Championship after dramatic quarterfinal matches. HyFlyers GC stuns 4Aces in biggest upset of the day.',
        'image' => '/images/news/liv-golf-michigan-team-championship-2025-quarterfinals/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Fleetwood and Henley Share Tour Championship Lead After Second Round',
        'slug' => 'tour-championship-2025-round-2-fleetwood-henley-share-lead',
        'date' => '2025-08-22',
        'time' => '7:30 PM',
        'category' => 'Tournament News',
        'excerpt' => 'Tommy Fleetwood fires 63 while Russell Henley posts 66 to tie for the lead at 13-under at East Lake Golf Club. Cameron Young shoots low round of 62 to move into third place.',
        'image' => '/images/news/tour-championship-2025-round-2-fleetwood-henley-share-lead/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Russell Henley Fires Spectacular 61 to Lead Tour Championship Opening Round',
        'slug' => 'tour-championship-2025-first-round-henley-leads',
        'date' => '2025-08-21',
        'time' => '8:45 PM',
        'category' => 'Tournament News',
        'excerpt' => 'Russell Henley shoots spectacular 9-under 61 to take two-stroke lead over Scottie Scheffler in opening round of 2025 Tour Championship at East Lake Golf Club in Atlanta.',
        'image' => '/images/news/tour-championship-2025-first-round-henley-leads/main.webp',
        'featured' => true
    ],
    [
        'title' => '55th Tennessee Four-Ball Championship Underway at The Country Club in Morristown',
        'slug' => 'tennessee-four-ball-championship-2025-country-club-morristown',
        'date' => '2025-08-21',
        'time' => '2:45 PM',
        'category' => 'Tennessee News',
        'excerpt' => 'The 55th Tennessee Four-Ball Championship kicks off at The Country Club in Morristown with 36 teams competing for the state title through stroke play qualifying and match play rounds.',
        'image' => '/images/news/tennessee-four-ball-championship-2025-country-club-morristown/main.webp',
        'featured' => true
    ],
    [
        'title' => '2025 Tour Championship Atlanta Preview: Scottie Scheffler Favored for Historic Back-to-Back Titles',
        'slug' => '2025-tour-championship-atlanta-predictions',
        'date' => '2025-08-20',
        'time' => '4:15 PM',
        'category' => 'Tournament News',
        'excerpt' => 'Complete preview of the 2025 Tour Championship at East Lake Golf Club featuring predictions, betting favorites, and analysis of Scottie Scheffler\'s chances for historic back-to-back titles.',
        'image' => '/images/news/2025-tour-championship-atlanta-predictions/main.webp',
        'featured' => true
    ],
    [
        'title' => 'ETSU Golfer Gavin Tiernan Finishes Runner-Up at Prestigious Amateur Championship',
        'slug' => 'etsu-gavin-tiernan-amateur-championship-runner-up',
        'date' => '2025-08-20',
        'time' => '2:30 PM',
        'category' => 'Tennessee News',
        'excerpt' => 'East Tennessee State University men\'s golfer Gavin Tiernan finished as runner-up at The 130th Amateur Championship at Royal St. George\'s in England this past June.',
        'image' => '/images/news/gavin-tiernan-amateur/tiernan-amateur-championship.webp',
        'featured' => true
    ],
    [
        'title' => 'Belmont\'s Conner Brown Captures First TGA Title at Tennessee Match Play Championship',
        'slug' => 'belmont-conner-brown-wins-tennessee-match-play-championship',
        'date' => '2025-08-19',
        'time' => '11:30 AM',
        'category' => 'Tennessee News',
        'excerpt' => 'Shelbyville native Conner Brown defeats Trenton Johnson 4 & 3 to win his first Tennessee Golf Association title at the 26th Tennessee Match Play Championship at Vanderbilt Legends Club.',
        'image' => '/images/news/belmont-conner-brown-wins-tennessee-match-play-championship/main.webp',
        'featured' => true
    ],
    [
        'title' => 'LIV Golf Indianapolis 2025: Complete Tournament Recap and Entertainment Spectacle',
        'slug' => 'liv-golf-indianapolis-2025-complete-tournament-recap-entertainment',
        'date' => '2025-08-18',
        'time' => '7:45 PM',
        'category' => 'Tournament News',
        'excerpt' => 'Complete recap of LIV Golf Indianapolis 2025 featuring Sebastian Munoz\'s maiden victory, Jon Rahm\'s Individual Championship defense, and spectacular entertainment from Riley Green and Jason Derulo.',
        'image' => '/images/news/liv-golf-indianapolis-2025-complete-tournament-recap-entertainment/main.webp',
        'featured' => true
    ],
    [
        'title' => 'BMW Championship 2025: Complete Tournament Recap and Community Impact',
        'slug' => 'bmw-championship-2025-complete-tournament-recap-community-impact',
        'date' => '2025-08-18',
        'time' => '8:00 PM',
        'category' => 'Tournament News',
        'excerpt' => 'Complete recap of the 2025 BMW Championship featuring Scottie Scheffler\'s miraculous comeback victory and the tournament\'s extraordinary community impact for the Evans Scholars Foundation.',
        'image' => '/images/news/bmw-championship-2025-complete-tournament-recap-community-impact/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Tennessee\'s Herrington Makes Historic Run to U.S. Amateur Final, Earns Major Championship Invitations',
        'slug' => 'tennessee-herrington-historic-run-125th-us-amateur-runner-up',
        'date' => '2025-08-17',
        'time' => '9:30 PM',
        'category' => 'Tennessee News',
        'excerpt' => 'Dickson native Jackson Herrington becomes first Tennessee golfer since 2013 to reach U.S. Amateur final, earning spots in 2026 Masters and U.S. Open while making family history.',
        'image' => '/images/news/tennessee-herrington-historic-run-125th-us-amateur-runner-up/main.webp',
        'featured' => true
    ]
];

// Automatically sort articles by date and time (newest first)
// This ensures articles always appear in chronological order regardless of array order
usort($articles_raw, function($a, $b) {
    // Convert date and time to comparable timestamps
    $timestamp_a = strtotime($a['date'] . ' ' . $a['time']);
    $timestamp_b = strtotime($b['date'] . ' ' . $b['time']);
    
    // Sort in descending order (newest first)
    return $timestamp_b - $timestamp_a;
});

// Make the sorted array available as $articles (maintains backward compatibility)
$articles = $articles_raw;
?>