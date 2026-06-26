<?php
// News articles data - no session_start() to avoid conflicts
// Get all news articles (automatically sorted by date and time, newest first)
$articles_raw = [
    [
        'title' => 'Wyndham Clark Wins Second U.S. Open at Shinnecock Hills, Goes Wire-to-Wire Through a Hostile Crowd',
        'slug' => 'us-open-2026-recap',
        'date' => '2026-06-22',
        'time' => '10:00 AM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Wyndham Clark leads from the first round to the last at the 2026 U.S. Open at Shinnecock Hills, surviving a six-shot final-round collapse and a hostile Long Island crowd to win by one over Sam Burns — claiming his second national title and becoming just the ninth player to go wire-to-wire in U.S. Open history.',
        'image' => '/images/news/us-open-2026/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Bud Cauley Wins RBC Canadian Open in His 239th Start, Completes Comeback From Career-Threatening Crash',
        'slug' => 'rbc-canadian-open-2026-recap',
        'date' => '2026-06-16',
        'time' => '10:00 AM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Bud Cauley shoots a final-round 65 to win the 2026 RBC Canadian Open at TPC Toronto at Osprey Valley by two shots over Matt Fitzpatrick, claiming his first PGA Tour title in his 239th start and capping a years-long comeback from a 2018 car accident that nearly ended his career.',
        'image' => '/images/news/rbc-canadian-open-2026/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Vol Golf Caps Historic 2026 Season: Men Match Program Best at NCAAs, Lady Vols Win First-Ever SEC Title',
        'slug' => 'tennessee-vols-golf-2026-ncaa-recap',
        'date' => '2026-06-09',
        'time' => '10:00 AM',
        'category' => 'Tennessee News',
        'excerpt' => 'Tennessee men\'s golf matches its second-best NCAA Championship finish in program history before falling in a four-team playoff for the final match play spots, while the Lady Vols close a historic 2026 season that included the program\'s first-ever SEC Championship.',
        'image' => '/images/news/tennessee-vols-golf-2026-ncaa-recap/main.jpg',
        'featured' => true
    ],
    [
        'title' => 'Tyrrell Hatton Wins LIV Golf Andalucia 2026 at Valderrama, First Title Since Becoming a Father',
        'slug' => 'liv-golf-andalucia-2026-recap',
        'date' => '2026-06-07',
        'time' => '11:00 PM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Tyrrell Hatton leads wire-to-wire at Real Club Valderrama to claim LIV Golf Andalucia 2026 by two shots over teammate Jon Rahm — his first win in 18 months and his first as a new father — while Legion XIII rallies from eight shots back to sweep both individual and team titles.',
        'image' => '/images/news/liv-golf-andalucia-2026/main.jpg',
        'featured' => true
    ],
    [
        'title' => 'J.T. Poston Grinds Through Marathon Sunday to Win 2026 Memorial Tournament in Playoff',
        'slug' => 'memorial-tournament-2026-recap',
        'date' => '2026-06-08',
        'time' => '10:00 AM',
        'category' => 'Tournament Recap',
        'excerpt' => 'J.T. Poston blows a four-shot lead, makes a clutch 7-foot birdie on 18 to force a playoff, then outlasts Ryan Gerard on the second extra hole to win the 2026 Memorial Tournament at Muirfield Village — earning the biggest victory of his career after entering the week without a top-20 finish all season.',
        'image' => '/images/news/memorial-tournament-2026/main.jpg',
        'featured' => true
    ],
    [
        'title' => 'Niemann Makes LIV Golf History With Playoff Win at 2026 LIV Golf Korea',
        'slug' => 'liv-golf-korea-2026-recap',
        'date' => '2026-06-01',
        'time' => '9:00 AM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Joaquin Niemann defeats Talor Gooch in a playoff at Asiad Country Club in Busan to claim his record eighth LIV Golf individual title — the most in league history — while Crushers GC defends their Korea team crown.',
        'image' => '/images/news/liv-golf-korea-2026/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Tennessee Men\'s Golf Returns to NCAA Championships for Third Consecutive Year',
        'slug' => 'ut-vols-ncaa-championships-2026',
        'date' => '2026-05-29',
        'time' => '8:00 AM',
        'category' => 'Tennessee News',
        'excerpt' => 'The No. 18 Vols open stroke play Friday at Omni La Costa in Carlsbad for their third straight NCAA Championship appearance, led by Masters low amateur Jackson Herrington after a dramatic Bryan Regional comeback.',
        'image' => '/images/news/ut-vols-ncaa-championships-2026/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Nashville\'s Blades Brown Earns PGA Tour Membership at 19 After Historic Run of Results',
        'slug' => 'blades-brown-pga-tour-2026',
        'date' => '2026-05-25',
        'time' => '10:14 AM',
        'category' => 'Tennessee News',
        'excerpt' => 'Blades Brown, the 19-year-old Nashville native who broke Bobby Jones\' U.S. Amateur record at 16, earns PGA Tour Special Temporary Membership at the CJ Cup Byron Nelson after a course-record 60 at the American Express and a near-win at the Puerto Rico Open.',
        'image' => '/images/news/blades-brown-pga-tour-2026/main.webp',
        'featured' => true
    ],
    [
        'title' => 'The Honors Course to Host 2026 U.S. Women\'s Amateur This August',
        'slug' => 'honors-course-us-womens-amateur-2026',
        'date' => '2026-01-04',
        'time' => '11:00 AM',
        'category' => 'Tennessee News',
        'excerpt' => 'The Honors Course in Ooltewah, Tennessee will host the 126th U.S. Women\'s Amateur Championship from August 4-9, 2026 — the seventh USGA championship at Pete Dye\'s private masterpiece near Chattanooga, and the first U.S. Women\'s Amateur ever held in Tennessee.',
        'image' => '/images/news/honors-course-us-womens-amateur-2026/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Tennessee Vols Golf Signs No. 1-Ranked Junior in the World, Lands Third-Best Recruiting Class',
        'slug' => 'ut-vols-golf-2026-recruiting',
        'date' => '2025-11-12',
        'time' => '3:45 PM',
        'category' => 'Tennessee News',
        'excerpt' => 'Tennessee men\'s golf coach Brennan Webb announces the signings of Tyler Watts and Pennson Badgett, giving the Vols the third-ranked recruiting class in the country with the top-ranked junior golfer in the world.',
        'image' => '/images/news/ut-vols-golf-2026-recruiting/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Mike Eller and Bob Wolcott Named 2026 Tennessee Golf Hall of Fame Inductees',
        'slug' => 'tennessee-golf-hall-of-fame-2026',
        'date' => '2025-10-17',
        'time' => '2:30 PM',
        'category' => 'Tennessee News',
        'excerpt' => 'The Tennessee Golf Foundation announces Mike Eller, PGA and Bob Wolcott, PGA as the 2026 inductees into the Tennessee Golf Hall of Fame, becoming the 59th and 60th members enshrined in the Hall\'s history.',
        'image' => '/images/news/tennessee-golf-hall-of-fame-2026/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Wyndham Clark Shoots 60 to Win 2026 CJ Cup Byron Nelson in Historic Fashion',
        'slug' => 'cj-cup-byron-nelson-2026-recap',
        'date' => '2026-05-24',
        'time' => '8:19 PM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Wyndham Clark fires a closing 11-under 60 at TPC Craig Ranch — including an eagle on 12 and a 45-foot birdie on 15 — to overtake Si Woo Kim by three shots and win the 2026 CJ Cup Byron Nelson, his first full-event PGA Tour win since the 2023 U.S. Open.',
        'image' => '/images/news/cj-cup-byron-nelson-2026/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Aaron Rai Wins 2026 PGA Championship at Aronimink, First English Winner Since 1919',
        'slug' => 'pga-championship-2026-recap',
        'date' => '2026-05-17',
        'time' => '9:08 PM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Aaron Rai fires a closing 65 at Aronimink Golf Club — including a 69-foot birdie putt on 17 — to win the 2026 PGA Championship by three shots, becoming the first Englishman to claim the Wanamaker Trophy since 1919 and the first player of Indian descent to win a men\'s major.',
        'image' => '/images/news/pga-championship-2026/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Lucas Herbert Goes Wire-to-Wire to Win 2026 LIV Golf Virginia While Battling the Flu',
        'slug' => 'liv-golf-virginia-2026-recap',
        'date' => '2026-05-10',
        'time' => '8:33 PM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Lucas Herbert fires rounds of 64-63 to build an eight-shot lead, then holds off Sergio Garcia\'s back-nine Sunday charge to win MAADEN LIV Golf Virginia by four shots at Trump National Golf Club, earning his first LIV Golf title and a U.S. Open berth.',
        'image' => '/images/news/liv-golf-virginia-2026/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Fitzpatrick Brothers Win 2026 Zurich Classic in Historic Fashion, Earning Alex His PGA Tour Card',
        'slug' => 'zurich-classic-2026-recap',
        'date' => '2026-04-26',
        'time' => '9:17 PM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Matt and Alex Fitzpatrick become the first brothers to win on the PGA Tour, combining for a tournament-record 31-under 257 at TPC Louisiana, with Matt\'s clutch bunker shot to a foot on 18 and Alex\'s winning putt sealing a one-shot victory.',
        'image' => '/images/news/zurich-classic-2026/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Matt Fitzpatrick Beats Scheffler in Playoff to Win 2026 RBC Heritage for Second Time',
        'slug' => 'rbc-heritage-2026-recap',
        'date' => '2026-04-19',
        'time' => '7:54 PM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Matt Fitzpatrick hits a stunning 4-iron to 13 feet and makes birdie to beat Scottie Scheffler in a playoff at the 2026 RBC Heritage at Harbour Town Golf Links, claiming his second title at Hilton Head and second win of the season.',
        'image' => '/images/news/rbc-heritage-2026/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Rory McIlroy Wins Back-to-Back Masters, Joins Nicklaus, Faldo and Woods in History',
        'slug' => 'masters-2026-recap',
        'date' => '2026-04-12',
        'time' => '8:47 PM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Rory McIlroy survives a Saturday collapse and Sunday lead changes to win the 2026 Masters at Augusta National by one shot over Scottie Scheffler, becoming just the fourth player in history to win consecutive green jackets.',
        'image' => '/images/news/masters-2026/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Jon Rahm Ends 539-Day Drought With Dominant Win at 2026 LIV Golf Hong Kong',
        'slug' => 'liv-golf-hong-kong-2026-recap',
        'date' => '2026-03-08',
        'time' => '8:06 PM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Jon Rahm closes with an 8-birdie 64 to win HSBC LIV Golf Hong Kong by three shots over Thomas Detry, snapping a 539-day individual win drought after back-to-back runner-up finishes to open the 2026 LIV season.',
        'image' => '/images/news/liv-golf-hong-kong-2026/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Jacob Bridgeman Holds on at Riviera to Win 2026 Genesis Invitational for First PGA Tour Title',
        'slug' => 'genesis-invitational-2026-recap',
        'date' => '2026-02-22',
        'time' => '9:33 PM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Jacob Bridgeman survives a harrowing final round at Riviera Country Club, making a nervy 3-footer on 18 to hold off Rory McIlroy and Kurt Kitayama by one shot and claim his first PGA Tour victory at the 2026 Genesis Invitational.',
        'image' => '/images/news/genesis-invitational-2026/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Collin Morikawa Birdies 72nd Hole to Win 2026 AT&T Pebble Beach Pro-Am',
        'slug' => 'att-pebble-beach-2026-recap',
        'date' => '2026-02-15',
        'time' => '7:29 PM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Collin Morikawa hits a stunning 4-iron from 235 yards to set up a winning birdie on the 72nd hole, edging Sepp Straka and Min Woo Lee by one shot at the 2026 AT&T Pebble Beach Pro-Am for his seventh career title.',
        'image' => '/images/news/att-pebble-beach-2026/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Chris Gotterup Wins 2026 WM Phoenix Open in Wild Playoff Over Hideki Matsuyama',
        'slug' => 'wm-phoenix-open-2026-recap',
        'date' => '2026-02-08',
        'time' => '8:51 PM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Chris Gotterup claims his second win of 2026 at the WM Phoenix Open, beating Hideki Matsuyama in a dramatic playoff at TPC Scottsdale after Matsuyama\'s drive struck a steel pole and found water on the playoff hole.',
        'image' => '/images/news/wm-phoenix-open-2026/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Anthony Kim Wins LIV Golf Adelaide 2026 in One of Golf\'s Greatest Comebacks',
        'slug' => 'liv-golf-adelaide-2026-recap',
        'date' => '2026-02-15',
        'time' => '9:22 PM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Anthony Kim shoots a final-round 63 to overcome a five-shot deficit and win LIV Golf Adelaide 2026 — his first victory in nearly 16 years — in one of the most emotional wins professional golf has ever seen.',
        'image' => '/images/news/liv-golf-adelaide-2026/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Elvis Smylie Wins LIV Golf Riyadh 2026 in Stunning Debut, Holds Off Jon Rahm',
        'slug' => 'liv-golf-riyadh-2026-recap',
        'date' => '2026-02-07',
        'time' => '8:44 PM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Elvis Smylie wins the 2026 LIV Golf Riyadh in his first-ever LIV event, closing with a 64 to edge Jon Rahm by one shot at the league\'s first-ever 72-hole tournament at Riyadh Golf Club.',
        'image' => '/images/news/liv-golf-riyadh-2026/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Justin Rose Sets Torrey Pines Record to Win 2026 Farmers Insurance Open at Age 45',
        'slug' => 'farmers-insurance-open-2026-recap',
        'date' => '2026-02-01',
        'time' => '7:38 PM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Justin Rose posts a tournament-record 23-under 265 for a wire-to-wire, seven-shot victory at the 2026 Farmers Insurance Open at Torrey Pines, becoming the oldest wire-to-wire winner on the PGA Tour since 2010.',
        'image' => '/images/news/farmers-insurance-open-2026/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Scottie Scheffler Wins 2026 American Express for Historic 20th PGA Tour Title',
        'slug' => 'american-express-2026-recap',
        'date' => '2026-01-25',
        'time' => '9:11 PM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Scottie Scheffler dominates the 2026 American Express at PGA West, closing with a 66 to win by four shots and claim his 20th career PGA Tour victory, earning a lifetime Tour exemption.',
        'image' => '/images/news/american-express-2026/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Chris Gotterup Wins 2026 Sony Open, Claims Third Straight Year With a PGA Tour Title',
        'slug' => 'sony-open-2026-recap',
        'date' => '2026-01-18',
        'time' => '8:23 PM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Chris Gotterup closes with a 6-under 64 to win the 2026 Sony Open at Waialae Country Club, capitalizing on a Davis Riley collapse to claim his third PGA Tour victory and open the 2026 season in style.',
        'image' => '/images/news/sony-open-2026/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Hideki Matsuyama Wins 2025 Hero World Challenge in Playoff Thriller Over Alex Noren',
        'slug' => 'hero-world-challenge-2025-recap',
        'date' => '2025-12-07',
        'time' => '7:42 PM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Hideki Matsuyama closes with a bogey-free 64 and beats Alex Noren in a playoff at the 2025 Hero World Challenge at Albany Golf Club in the Bahamas, bookending his year with two victories.',
        'image' => '/images/news/hero-world-challenge-2025/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Sami Valimaki Wins 2025 RSM Classic, Becomes First Finn to Win on PGA Tour',
        'slug' => 'rsm-classic-2025-recap',
        'date' => '2025-11-23',
        'time' => '8:17 PM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Sami Valimaki closes with a 4-under 66 to win the 2025 RSM Classic at Sea Island by one shot, becoming the first player from Finland to win on the PGA Tour in the final event of the FedExCup Fall.',
        'image' => '/images/news/rsm-classic-2025/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Adam Schenk Wins 2025 Butterfield Bermuda Championship in His 243rd Start',
        'slug' => 'bermuda-championship-2025-recap',
        'date' => '2025-11-16',
        'time' => '9:04 PM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Adam Schenk claims his first PGA Tour victory at the 2025 Butterfield Bermuda Championship at Port Royal Golf Course, surviving 30 mph winds and a one-shot final round to hold off Chandler Phillips at 12-under par.',
        'image' => '/images/news/bermuda-championship-2025/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Ben Griffin Sets Scoring Record at 2025 World Wide Technology Championship',
        'slug' => 'world-wide-technology-championship-2025-recap',
        'date' => '2025-11-09',
        'time' => '7:55 PM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Ben Griffin fires a final-round 63 to win the 2025 World Wide Technology Championship at El Cardonal at Diamante, setting a tournament scoring record at 29-under par for his third PGA Tour victory of the season.',
        'image' => '/images/news/world-wide-technology-championship-2025/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Ryder Cup 2025: Complete Tournament Recap - Europe Defeats USA 15-13 at Bethpage Black',
        'slug' => 'ryder-cup-2025-complete-tournament-recap-bethpage-black',
        'date' => '2025-09-29',
        'time' => '10:10 PM',
        'category' => 'Tournament News',
        'excerpt' => 'Complete recap of the 2025 Ryder Cup at Bethpage Black, where Europe defeated the United States 15-13 in a thrilling comeback victory. Comprehensive coverage of all three days of competition.',
        'image' => '/images/news/ryder-cup-2025-complete-tournament-recap-bethpage-black/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Ryder Cup 2025 Day 3: Europe Survives American Comeback, Wins 15-13 at Bethpage Black',
        'slug' => 'ryder-cup-2025-europe-survives-american-comeback-15-13',
        'date' => '2025-09-28',
        'time' => '8:30 PM',
        'category' => 'Tournament News',
        'excerpt' => 'Europe withstands the greatest comeback in Ryder Cup history as USA wins 8.5 of 12 singles matches, falling just short of completing the miraculous turnaround at Bethpage Black.',
        'image' => '/images/news/ryder-cup-2025-europe-survives-american-comeback-15-13/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Ryder Cup 2025 Day 2: Europe Makes History with Perfect Saturday Sweep at Bethpage',
        'slug' => 'ryder-cup-2025-day-2-europe-historic-sweep-bethpage',
        'date' => '2025-09-27',
        'time' => '9:15 PM',
        'category' => 'Tournament News',
        'excerpt' => 'Team Europe achieves unprecedented perfection with 8-0 Saturday sweep, building largest lead in Ryder Cup history at 11.5-4.5 heading into Sunday singles at Bethpage Black.',
        'image' => '/images/news/ryder-cup-2025-day-2-europe-historic-sweep-bethpage/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Ryder Cup 2025 Day 1: Europe Dominates Opening at Bethpage Black, Takes 5.5-2.5 Lead',
        'slug' => 'ryder-cup-2025-day-1-europe-dominates-bethpage-black',
        'date' => '2025-09-26',
        'time' => '8:45 PM',
        'category' => 'Tournament News',
        'excerpt' => 'Team Europe seizes commanding early advantage with stellar performances from Tommy Fleetwood and Jon Rahm, while Scottie Scheffler struggles in home debut at Bethpage Black.',
        'image' => '/images/news/ryder-cup-2025-day-1-europe-dominates-bethpage-black/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Ryder Cup 2025: Final Preview and Bethpage Black Showdown Between USA and Europe',
        'slug' => 'ryder-cup-2025-final-preview-bethpage-black-showdown',
        'date' => '2025-09-25',
        'time' => '7:30 PM',
        'category' => 'Tournament News',
        'excerpt' => 'Complete preview of the 2025 Ryder Cup at Bethpage Black featuring team analysis, course conditions, weather forecast, and predictions for the epic showdown between USA and Europe.',
        'image' => '/images/news/ryder-cup-2025-final-preview-bethpage-black-showdown/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Ryder Cup 2025: Complete Preview, Team Announcements, and Bethpage Black Preparations',
        'slug' => 'ryder-cup-2025-complete-preview-team-announcements-bethpage-black',
        'date' => '2025-08-28',
        'time' => '9:00 PM',
        'category' => 'Tournament Preview',
        'excerpt' => 'Comprehensive coverage of the 2025 Ryder Cup featuring complete team rosters, captain\'s picks drama, Bethpage Black preparations, and the historic matchup between Team USA and Team Europe.',
        'image' => '/images/news/ryder-cup-2025-complete-preview-team-announcements-bethpage-black/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Tour Championship 2025: Complete Atlanta Tournament Recap and Tommy Fleetwood\'s Historic First Win',
        'slug' => 'tour-championship-2025-atlanta-complete-tournament-recap-fleetwood-first-win',
        'date' => '2025-08-25',
        'time' => '8:00 PM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Complete recap of the 2025 Tour Championship featuring Tommy Fleetwood\'s long-awaited first PGA Tour victory and FedEx Cup triumph at East Lake Golf Club in Atlanta.',
        'image' => '/images/news/tour-championship-2025-atlanta-complete-tournament-recap-fleetwood-first-win/main.webp',
        'featured' => true
    ],
    [
        'title' => 'LIV Golf Michigan 2025: Team Championship Complete Tournament Recap and Playoff Drama',
        'slug' => 'liv-golf-michigan-2025-team-championship-complete-tournament-recap',
        'date' => '2025-08-25',
        'time' => '8:00 PM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Complete recap of the 2025 LIV Golf Team Championship featuring Legion XIII\'s dramatic playoff victory over Crushers GC and the tournament\'s thrilling match play format at The Cardinal.',
        'image' => '/images/news/liv-golf-michigan-2025-team-championship-complete-tournament-recap/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Smith and Narramore Capture 55th Tennessee Four-Ball Championship at The Country Club',
        'slug' => 'smith-narramore-capture-55th-tennessee-four-ball-championship-morristown',
        'date' => '2025-08-23',
        'time' => '9:15 PM',
        'category' => 'Tennessee News',
        'excerpt' => 'Jack Smith and Chas Narramore defeated Lawrence Largent and Jack Rhea 4 and 3 in the championship match at The Country Club in Morristown, with Smith claiming his third state title.',
        'image' => '/images/news/smith-narramore-capture-55th-tennessee-four-ball-championship-morristown/main.webp',
        'featured' => true
    ],
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
    ],
    [
        'title' => 'Scheffler Delivers Stunning Comeback to Win BMW Championship',
        'slug' => 'scheffler-delivers-stunning-comeback-wins-bmw-championship',
        'date' => '2025-08-17',
        'time' => '9:15 PM',
        'category' => 'Tournament News',
        'excerpt' => 'Scottie Scheffler erases four-shot deficit with miraculous chip-in on 17th to defeat Robert MacIntyre and claim fifth victory of 2025 season.',
        'image' => '/images/news/scheffler-delivers-stunning-comeback-wins-bmw-championship/main.webp',
        'featured' => true
    ],
    [
        'title' => 'MacIntyre Weathers Moving Day Storm to Maintain BMW Championship Lead',
        'slug' => 'macintyre-weathers-moving-day-storm-maintains-bmw-championship-lead',
        'date' => '2025-08-16',
        'time' => '8:45 PM',
        'category' => 'Tournament News',
        'excerpt' => 'Robert MacIntyre overcomes hostile crowd and brutal pin placements to maintain four-shot lead over Scottie Scheffler after BMW Championship third round.',
        'image' => '/images/news/macintyre-weathers-moving-day-storm-maintains-bmw-championship-lead/main.webp',
        'featured' => true
    ],
    [
        'title' => 'MacIntyre Extends Commanding Lead as Scheffler Remains in Pursuit at BMW Championship',
        'slug' => 'macintyre-extends-commanding-lead-scheffler-pursuit-bmw-championship',
        'date' => '2025-08-15',
        'time' => '7:30 PM',
        'category' => 'Tournament News',
        'excerpt' => 'Robert MacIntyre fires bogey-free 64 to extend lead to five shots over Scottie Scheffler after BMW Championship second round at Caves Valley Golf Club.',
        'image' => '/images/news/macintyre-extends-commanding-lead-scheffler-pursuit-bmw-championship/main.webp',
        'featured' => true
    ],
    [
        'title' => 'MacIntyre Explodes for Career-Low 62 to Lead BMW Championship Opening Round',
        'slug' => 'macintyre-explodes-for-62-leads-bmw-championship',
        'date' => '2025-08-14',
        'time' => '8:15 PM',
        'category' => 'Tournament News',
        'excerpt' => 'Robert MacIntyre fires career-low 62 with six straight closing birdies to take three-shot lead over Tommy Fleetwood after BMW Championship first round.',
        'image' => '/images/news/macintyre-explodes-for-62-leads-bmw-championship/main.webp',
        'featured' => true
    ],
    [
        'title' => 'FedEx St. Jude Championship 2025: Complete Tournament Recap and Community Impact',
        'slug' => 'fedex-st-jude-championship-2025-complete-recap-community-impact',
        'date' => '2025-08-11',
        'time' => '8:00 PM',
        'category' => 'Tournament Recap',
        'excerpt' => 'Complete recap of the 2025 FedEx St. Jude Championship featuring Justin Rose\'s playoff victory and the tournament\'s extraordinary community impact for St. Jude Children\'s Research Hospital.',
        'image' => '/images/news/fedex-st-jude-championship-2025-complete-recap-community-impact/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Bhatia Leads After Weather-Shortened First Round at FedEx St. Jude Championship',
        'slug' => 'fedex-st-jude-first-round-bhatia-leads',
        'date' => '2025-08-08',
        'time' => '7:30 PM',
        'category' => 'Tournament News',
        'excerpt' => 'Akshay Bhatia shoots 65 to lead by two shots after weather suspends first round play at TPC Southwind in Memphis.',
        'image' => '/images/news/fedex-st-jude-first-round-bhatia-leads/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Rose Captures Thrilling Playoff Victory Over Fleetwood at FedEx St. Jude Championship',
        'slug' => 'rose-captures-thrilling-playoff-victory-fleetwood-heartbreak',
        'date' => '2025-08-10',
        'time' => '9:45 PM',
        'category' => 'Tournament News',
        'excerpt' => 'Justin Rose defeats Tommy Fleetwood in dramatic playoff to win FedEx St. Jude Championship and secure Tour Championship spot.',
        'image' => '/images/news/rose-captures-thrilling-playoff-victory-fleetwood-heartbreak/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Fleetwood Takes Command as Weather Halts Play at FedEx St. Jude Championship',
        'slug' => 'fleetwood-takes-command-weather-halts-play',
        'date' => '2025-08-10',
        'time' => '8:15 PM',
        'category' => 'Tournament News',
        'excerpt' => 'Tommy Fleetwood builds three-shot lead before weather suspension at TPC Southwind with final round set for Monday.',
        'image' => '/images/news/fleetwood-takes-command-weather-halts-play/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Fleetwood Maintains Narrow Lead as Scheffler Charges at FedEx St. Jude Championship',
        'slug' => 'fleetwood-maintains-narrow-lead-scheffler-charges',
        'date' => '2025-08-09',
        'time' => '7:45 PM',
        'category' => 'Tournament News',
        'excerpt' => 'Tommy Fleetwood shoots 66 to maintain one-shot lead over charging Scottie Scheffler after FedEx St. Jude Championship second round.',
        'image' => '/images/news/fleetwood-maintains-narrow-lead-scheffler-charges/main.webp',
        'featured' => true
    ],
    [
        'title' => 'Scheffler Captures First Claret Jug with Dominant Victory',
        'slug' => 'scheffler-wins-2025-british-open-final-round',
        'date' => '2025-07-21',
        'time' => '6:00 PM',
        'category' => 'Major Championship',
        'excerpt' => 'Scottie Scheffler claims his first Open Championship with commanding four-shot victory at Royal Portrush, completing career grand slam.',
        'image' => '/images/news/open-championship-final-2025/scottie-final-round.webp',
        'featured' => true
    ],
    [
        'title' => 'Scheffler Extends Lead to Four Shots with Bogey-Free 67',
        'slug' => 'scheffler-extends-lead-open-championship-round-3',
        'date' => '2025-07-19',
        'time' => '5:15 PM',
        'category' => 'Major Championship',
        'excerpt' => 'Scottie Scheffler fires third consecutive bogey-free round to build commanding four-shot lead heading into Open Championship final round.',
        'image' => '/images/news/open-championship-round-3/scheffler-family.webp',
        'featured' => true
    ],
    [
        'title' => 'Scheffler Seizes Control with Second Round 65 at Royal Portrush',
        'slug' => 'scheffler-seizes-control-open-championship-round-2',
        'date' => '2025-07-18',
        'time' => '4:30 PM',
        'category' => 'Major Championship',
        'excerpt' => 'World No. 1 Scottie Scheffler shoots brilliant 65 to take three-shot lead after Open Championship second round at Royal Portrush.',
        'image' => '/images/news/open-championship-round-2/scheffler-64.webp',
        'featured' => true
    ],
    [
        'title' => 'Five Players Share Lead as Royal Portrush Shows Its Teeth',
        'slug' => 'open-championship-2025-round-1-royal-portrush',
        'date' => '2025-07-17',
        'time' => '6:45 PM',
        'category' => 'Major Championship',
        'excerpt' => 'Championship lead shared by five players at 4-under as challenging conditions test the field in Open Championship first round.',
        'image' => '/images/news/open-championship-2025-round-1/royal-portrush-round1.webp',
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