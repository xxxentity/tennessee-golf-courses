<?php
session_start();
require_once '../includes/seo.php';

// Article data for SEO
$article_data = [
    'title' => 'Top 5 Best Golf Courses Near Memphis, Tennessee 2025',
    'description' => 'Discover the top 5 highest-rated golf courses near Memphis, Tennessee. Based on expert rankings, player reviews, and championship pedigree from leading golf publications.',
    'image' => '/images/reviews/top-5-golf-courses-near-memphis-tn/0.webp',
    'type' => 'article',
    'author' => 'Michael Travers',
    'date' => '2025-08-19',
    'category' => 'Course Reviews'
];

SEO::setupArticlePage($article_data);

// Article metadata
$article = [
    'title' => 'Top 5 Best Golf Courses Near Memphis, Tennessee 2025',
    'slug' => 'top-5-golf-courses-near-memphis-tn',
    'date' => 'Aug 19, 2025',
    'time' => '3:45 PM',
    'category' => 'Course Reviews',
    'excerpt' => 'Discover the top 5 highest-rated golf courses near Memphis, Tennessee. Based on expert rankings, player reviews, and championship pedigree from leading golf publications.',
    'image' => '/images/reviews/top-5-golf-courses-near-memphis-tn/0.webp',
    'featured' => true,
    'author' => 'Michael Travers',
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo SEO::generateMetaTags(); ?>
    <link rel="stylesheet" href="/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/logos/tab-logo.webp?v=2">
    <link rel="shortcut icon" href="/images/logos/tab-logo.webp?v=2">
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-7VPNPCDTBP');
    </script>
    
    <style>
        .article-page {
            padding-top: 0px;
            min-height: 100vh;
            background: var(--bg-light);
        }
        
        .article-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .article-header {
            text-align: center;
            margin-bottom: 3rem;
            background: var(--bg-white);
            padding: 3rem 2rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
        }
        
        .article-hero-image {
            width: 100%;
            max-width: 800px;
            height: 400px;
            object-fit: contain;
            object-position: center;
            background: white;
            border-radius: 15px;
            margin-bottom: 2rem;
            padding: 1rem;
            box-shadow: var(--shadow-light);
        }
        
        .article-title {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-weight: 700;
            line-height: 1.2;
        }
        
        .article-meta {
            display: flex;
            justify-content: center;
            gap: 2rem;
            color: var(--text-gray);
            font-size: 0.95rem;
            flex-wrap: wrap;
        }
        
        .article-content {
            background: var(--bg-white);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
            line-height: 1.7;
            font-size: 1.1rem;
        }
        
        .course-item {
            background: var(--bg-light);
            margin: 2rem 0;
            padding: 2rem;
            border-radius: 15px;
            border-left: 5px solid var(--primary-color);
        }
        
        .course-image {
            width: 100%;
            max-width: 500px;
            height: 300px;
            object-fit: contain;
            object-position: center;
            background: white;
            border-radius: 10px;
            margin: 1rem 0;
            box-shadow: var(--shadow-light);
            padding: 1rem;
        }
        
        .course-rank {
            background: var(--primary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
        }
        
        .course-name {
            font-size: 1.4rem;
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .course-rating {
            color: var(--secondary-color);
            margin-bottom: 1rem;
            font-weight: 500;
        }
        
        .course-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin: 1rem 0;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            background: var(--bg-white);
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }
        
        .visit-link {
            background: var(--secondary-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .visit-link:hover {
            background: var(--primary-color);
            transform: translateY(-2px);
        }
        
        .pros-cons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin: 1.5rem 0;
        }
        
        .pros, .cons {
            background: var(--bg-white);
            padding: 1rem;
            border-radius: 10px;
        }
        
        .pros h4 {
            color: #28a745;
            margin-bottom: 0.5rem;
        }
        
        .cons h4 {
            color: #dc3545;
            margin-bottom: 0.5rem;
        }
        
        .pros ul, .cons ul {
            list-style: none;
            padding: 0;
        }
        
        .pros li:before {
            content: "✓ ";
            color: #28a745;
            font-weight: bold;
        }
        
        .cons li:before {
            content: "✗ ";
            color: #dc3545;
            font-weight: bold;
        }
        
        .buying-guide {
            background: var(--bg-light);
            padding: 2rem;
            border-radius: 15px;
            margin: 3rem 0;
            border: 2px solid var(--primary-color);
        }
        
        .buying-guide h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        @media screen and (max-width: 768px) {
            .article-title {
                font-size: 2rem;
            }
            
            .article-meta {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .course-details {
                grid-template-columns: 1fr;
            }
            
            .pros-cons {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include '../includes/navigation.php'; ?>

    <div class="article-page">
        <div class="article-container">
            <!-- Article Header -->
            <header class="article-header">
                <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="Top Golf Courses Near Memphis Tennessee 2025" class="article-hero-image">
                <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>
                <div class="article-meta">
                    <span><i class="far fa-calendar"></i> <?php echo htmlspecialchars($article['date']); ?></span>
                    <span><i class="far fa-folder"></i> <?php echo htmlspecialchars($article['category']); ?></span>
                    <span><a href="/profile?username=michael-travers" style="text-decoration: none; color: inherit; display: inline-flex; align-items: center;"><img src="/uploads/profile_pictures/michael-travers.webp" alt="Michael Travers" style="width: 32px; height: 32px; border-radius: 50%; margin-right: 8px; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"><span style="text-decoration: underline;"><?php echo htmlspecialchars($article['author']); ?></span></a></span>
                </div>
            </header>

            <!-- Article Content -->
            <article class="article-content">
                <h2>Top 5 Best Golf Courses Near Memphis, Tennessee 2025</h2>
                <ol style="list-style: none; padding: 0; margin: 2rem 0;">
                    <li style="margin-bottom: 0.75rem;"><a href="#course-5" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 1.1rem;">5. The Links at Galloway</a></li>
                    <li style="margin-bottom: 0.75rem;"><a href="#course-4" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 1.1rem;">4. Windyke Country Club</a></li>
                    <li style="margin-bottom: 0.75rem;"><a href="#course-3" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 1.1rem;">3. Chickasaw Country Club</a></li>
                    <li style="margin-bottom: 0.75rem;"><a href="#course-2" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 1.1rem;">2. Memphis Country Club</a></li>
                    <li style="margin-bottom: 0.75rem;"><a href="#course-1" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 1.1rem;">1. TPC Southwind</a></li>
                </ol>

<!-- #5 Course -->
                <div class="course-item" id="course-5">
                    <div class="course-rank">#5</div>
                    <h3 class="course-name">The Links at Galloway</h3>
                    <img src="/images/reviews/top-5-golf-courses-near-memphis-tn/5.webp" alt="The Links at Galloway" class="course-image">
                    <div class="course-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        Best Public Golf Course in Memphis
                    </div>
                    <p>Recognized as Memphis's premier public golf course, The Links at Galloway offers exceptional value and challenging play for golfers of all skill levels. This Kevin Tucker design from 2002 features 6,013 yards from the longest tees with a par of 70. Despite appearing modest in length, the course challenges players with consistently fast greens rolling at 6-7 speed, numerous trees, and well-designed holes that demand strategic thinking and precise shot-making.</p>
                    
                    <div class="course-details">
                        <div class="detail-item">
                            <span>Location:</span>
                            <span><strong>Memphis, TN</strong></span>
                        </div>
                        <div class="detail-item">
                            <span>Designer:</span>
                            <span>Kevin Tucker</span>
                        </div>
                        <div class="detail-item">
                            <span>Opened:</span>
                            <span>2002</span>
                        </div>
                        <div class="detail-item">
                            <span>Yardage:</span>
                            <span>6,013 yards</span>
                        </div>
                    </div>

                    <div class="pros-cons">
                        <div class="pros">
                            <h4>Pros</h4>
                            <ul>
                                <li>Recognized as best public course in Memphis</li>
                                <li>Exceptional course conditions year-round</li>
                                <li>Consistently fast and true greens</li>
                                <li>Outstanding value for quality of play</li>
                            </ul>
                        </div>
                        <div class="cons">
                            <h4>Cons</h4>
                            <ul>
                                <li>Can be crowded due to popularity</li>
                                <li>Difficult to secure tee times</li>
                            </ul>
                        </div>
                    </div>
                    
                    <a href="/courses/the-links-at-galloway" class="visit-link">
                        <i class="fas fa-golf-ball"></i> View Course Details
                    </a>
                </div>

<!-- #4 Course -->
                <div class="course-item" id="course-4">
                    <div class="course-rank">#4</div>
                    <h3 class="course-name">Windyke Country Club</h3>
                    <img src="/images/reviews/top-5-golf-courses-near-memphis-tn/4.webp" alt="Windyke Country Club" class="course-image">
                    <div class="course-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        4.8/5 stars (276 reviews)
                    </div>
                    <p>Windyke Country Club stands as one of Memphis's premier golf destinations, featuring three championship courses that cater to players of every skill level. The East Course, spanning 7,063 yards and par 72, is considered one of the best golf courses in Memphis with treacherous greens, narrow fairways, and numerous hazards that have hosted the majority of local and state championships. The West Course offers a more member-friendly experience at 6,826 yards, consistently regarded as having some of the best fairways in the area.</p>
                    
                    <div class="course-details">
                        <div class="detail-item">
                            <span>Location:</span>
                            <span><strong>Memphis, TN</strong></span>
                        </div>
                        <div class="detail-item">
                            <span>Courses:</span>
                            <span>East, West, Par 3</span>
                        </div>
                        <div class="detail-item">
                            <span>East Course:</span>
                            <span>7,063 yards, Par 72</span>
                        </div>
                        <div class="detail-item">
                            <span>West Course:</span>
                            <span>6,826 yards, Par 72</span>
                        </div>
                    </div>

                    <div class="pros-cons">
                        <div class="pros">
                            <h4>Pros</h4>
                            <ul>
                                <li>Three championship courses available</li>
                                <li>Consistently excellent fairway conditions</li>
                                <li>Family-oriented club atmosphere</li>
                                <li>Hosts numerous championship events</li>
                            </ul>
                        </div>
                        <div class="cons">
                            <h4>Cons</h4>
                            <ul>
                                <li>Private club membership required</li>
                                <li>East Course extremely challenging for average players</li>
                            </ul>
                        </div>
                    </div>
                    
                    <a href="/courses/windyke-country-club" class="visit-link">
                        <i class="fas fa-golf-ball"></i> View Course Details
                    </a>
                </div>

<!-- #3 Course -->
                <div class="course-item" id="course-3">
                    <div class="course-rank">#3</div>
                    <h3 class="course-name">Chickasaw Country Club</h3>
                    <img src="/images/reviews/top-5-golf-courses-near-memphis-tn/3.webp" alt="Chickasaw Country Club" class="course-image">
                    <div class="course-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        Historic Golden Age Design Restored
                    </div>
                    <p>Founded in 1922, Chickasaw Country Club opened its William Langford-designed course in 1924, representing classic Golden Age architecture at its finest. The course gained historical significance as the site where Byron Nelson's eleven-tournament winning streak ended at the 1945 Memphis Invitational. Following a comprehensive 2018 renovation by Bill Bergin, the 6,608-yard, par-72 layout has been restored to its Golden Age roots with conversion from bentgrass to TifEagle Bermuda grass greens, allowing surfaces to play fast and firm throughout the calendar year.</p>
                    
                    <div class="course-details">
                        <div class="detail-item">
                            <span>Location:</span>
                            <span><strong>Memphis, TN</strong></span>
                        </div>
                        <div class="detail-item">
                            <span>Founded:</span>
                            <span>1922</span>
                        </div>
                        <div class="detail-item">
                            <span>Designer:</span>
                            <span>William Langford (1924)</span>
                        </div>
                        <div class="detail-item">
                            <span>Renovation:</span>
                            <span>Bill Bergin (2018)</span>
                        </div>
                    </div>

                    <div class="pros-cons">
                        <div class="pros">
                            <h4>Pros</h4>
                            <ul>
                                <li>Historic Golden Age Langford design</li>
                                <li>Site of Byron Nelson streak ending</li>
                                <li>Recently renovated to modern standards</li>
                                <li>TifEagle greens play consistently year-round</li>
                            </ul>
                        </div>
                        <div class="cons">
                            <h4>Cons</h4>
                            <ul>
                                <li>Private club membership required</li>
                                <li>Limited public access opportunities</li>
                            </ul>
                        </div>
                    </div>
                    
                    <a href="/courses/chickasaw-country-club" class="visit-link">
                        <i class="fas fa-golf-ball"></i> View Course Details
                    </a>
                </div>

<!-- #2 Course -->
                <div class="course-item" id="course-2">
                    <div class="course-rank">#2</div>
                    <h3 class="course-name">Memphis Country Club</h3>
                    <img src="/images/reviews/top-5-golf-courses-near-memphis-tn/2.webp" alt="Memphis Country Club" class="course-image">
                    <div class="course-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        1948 U.S. Amateur Championship Host
                    </div>
                    <p>Established in 1905, Memphis Country Club features a classic Donald Ross-designed 18-hole layout from 1917, representing one of his signature rectangular routings on a 105-acre property. This championship venue earned its place in golf history by hosting the 1948 U.S. Amateur, establishing its credentials among America's premier private clubs. The course has been carefully maintained to preserve Ross's original design intent, with specialist Kris Spence retained to reclaim lost architectural features and restore the layout to its historical prominence.</p>
                    
                    <div class="course-details">
                        <div class="detail-item">
                            <span>Location:</span>
                            <span><strong>Memphis, TN</strong></span>
                        </div>
                        <div class="detail-item">
                            <span>Founded:</span>
                            <span>1905</span>
                        </div>
                        <div class="detail-item">
                            <span>Designer:</span>
                            <span>Donald Ross (1917)</span>
                        </div>
                        <div class="detail-item">
                            <span>Championship:</span>
                            <span>1948 U.S. Amateur</span>
                        </div>
                    </div>

                    <div class="pros-cons">
                        <div class="pros">
                            <h4>Pros</h4>
                            <ul>
                                <li>Classic Donald Ross design from 1917</li>
                                <li>Hosted 1948 U.S. Amateur Championship</li>
                                <li>Recent restoration by Ross specialist</li>
                                <li>Prestigious Memphis golf heritage</li>
                            </ul>
                        </div>
                        <div class="cons">
                            <h4>Cons</h4>
                            <ul>
                                <li>Extremely exclusive membership</li>
                                <li>Very limited guest access</li>
                            </ul>
                        </div>
                    </div>
                    
                    <a href="/courses/memphis-country-club" class="visit-link">
                        <i class="fas fa-golf-ball"></i> View Course Details
                    </a>
                </div>

<!-- #1 Course -->
                <div class="course-item" id="course-1">
                    <div class="course-rank">#1</div>
                    <h3 class="course-name">TPC Southwind</h3>
                    <img src="/images/reviews/top-5-golf-courses-near-memphis-tn/1.webp" alt="TPC Southwind" class="course-image">
                    <div class="course-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        Host of 2025 FedEx St. Jude Championship
                    </div>
                    <p>TPC Southwind stands as the crown jewel of Memphis golf, serving as the PGA Tour's only private golf club in Tennessee and host to the prestigious FedEx St. Jude Championship. Justin Rose captured the 2025 tournament in dramatic fashion, making two birdies in a playoff to claim victory in the first leg of the FedExCup Playoffs. This Ron Prichard design, opened in 1988, features 7,244 yards of championship golf from the longest tees with a par of 70. The layout showcases narrow Zoysia fairways, the third smallest greens on tour, and water hazards on 11 holes, creating target golf in its most extreme form.</p>
                    
                    <div class="course-details">
                        <div class="detail-item">
                            <span>Location:</span>
                            <span><strong>Memphis, TN</strong></span>
                        </div>
                        <div class="detail-item">
                            <span>Designer:</span>
                            <span>Ron Prichard</span>
                        </div>
                        <div class="detail-item">
                            <span>Tournament:</span>
                            <span>FedEx St. Jude Championship</span>
                        </div>
                        <div class="detail-item">
                            <span>Yardage:</span>
                            <span>7,244 yards</span>
                        </div>
                    </div>

                    <div class="pros-cons">
                        <div class="pros">
                            <h4>Pros</h4>
                            <ul>
                                <li>Host of 2025 FedEx St. Jude Championship</li>
                                <li>Only PGA Tour private club in Tennessee</li>
                                <li>Championship-level course conditioning</li>
                                <li>Signature par-3 11th island green</li>
                            </ul>
                        </div>
                        <div class="cons">
                            <h4>Cons</h4>
                            <ul>
                                <li>Front nine routed through office park</li>
                                <li>Extremely challenging for average golfers</li>
                            </ul>
                        </div>
                    </div>
                    
                    <a href="/courses/tpc-southwind" class="visit-link">
                        <i class="fas fa-golf-ball"></i> View Course Details
                    </a>
                </div>

                <!-- Honorable Mentions -->
                <div style="background: var(--bg-light); padding: 2rem; border-radius: 15px; margin: 3rem 0; border: 2px solid var(--secondary-color);">
                    <h3 style="color: var(--secondary-color); margin-bottom: 1.5rem;"><i class="fas fa-trophy"></i> Honorable Mentions</h3>
                    
                    <div style="margin-bottom: 2rem;">
                        <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">The Links at Audubon</h4>
                        <img src="/images/reviews/top-5-golf-courses-near-memphis-tn/6.webp" alt="The Links at Audubon" style="width: 100%; max-width: 400px; height: 250px; object-fit: contain; background: white; border-radius: 10px; margin: 1rem 0; box-shadow: var(--shadow-light); padding: 1rem;">
                        <p style="margin-bottom: 0.5rem;"><strong>Location:</strong> Memphis, TN</p>
                        <p>The Links at Audubon underwent a major overhaul overseen by course designer Bill Bergin in 2023, earning recognition as one of the "Best New or Remodeled Adorable Courses" by leading golf publications for 2025. The reimagined landscape features deep bunkers, enlarged undulating greens, and a newly added 10-acre driving range. Management has maintained exceptional conditions, with greens playing as well as they have in over a decade, making this public course a standout value with greens fees ranging from $18-$45.</p>
                    </div>
                    
                    <div>
                        <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Mirimichi Golf Course</h4>
                        <img src="/images/reviews/top-5-golf-courses-near-memphis-tn/7.webp" alt="Mirimichi Golf Course" style="width: 100%; max-width: 400px; height: 250px; object-fit: contain; background: white; border-radius: 10px; margin: 1rem 0; box-shadow: var(--shadow-light); padding: 1rem;">
                        <p style="margin-bottom: 0.5rem;"><strong>Location:</strong> Millington, TN</p>
                        <p>Mirimichi Golf Course offers a premium public golf experience on a challenging layout less than 15 miles north of Memphis. This former Justin Timberlake-owned course can play over 7,400 yards from the tips, defending itself with meandering streams, deep-faced bunkers, and numerous lakes. The course features five different tee boxes to accommodate all skill levels, velvet fairways, scenic vistas, four waterfalls, and two creeks. Mirimichi holds an Audubon Society seal of approval, demonstrating its commitment to environmental stewardship.</p>
                    </div>
                </div>

                <p>The Memphis golf scene offers exceptional variety, from PGA Tour championship golf at <strong>TPC Southwind</strong> to classic Golden Age designs and outstanding public facilities. Whether seeking tournament-level challenges or accessible public golf, these five courses represent the pinnacle of Memphis golf excellence, with the added excitement of having witnessed Justin Rose's dramatic 2025 FedEx St. Jude Championship victory.</p>

                <p><em>Rankings based on comprehensive analysis from multiple golf publications, player reviews, and championship pedigree for 2025.</em></p>
            </article>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <img src="/images/logos/logo.webp" alt="Tennessee Golf Courses" class="footer-logo-image">
                    </div>
                    <p>Your premier destination for discovering the best golf courses across Tennessee.</p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/profile.php?id=61579553544749" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook"></i></a>
                        <a href="https://x.com/TNGolfCourses" target="_blank" rel="noopener noreferrer"><i class="fab fa-x-twitter"></i></a>
                        <a href="https://www.instagram.com/tennesseegolfcourses/" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.youtube.com/@TennesseeGolf" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="/courses">Golf Courses</a></li>
                        <li><a href="/reviews">Reviews</a></li>
                        <li><a href="/news">News</a></li>
                        <li><a href="/about">About Us</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Regions</h4>
                    <ul>
                        <li><a href="/courses?region=Nashville Area">Nashville Area</a></li>
                        <li><a href="/courses?region=Chattanooga Area">Chattanooga Area</a></li>
                        <li><a href="/courses?region=Knoxville Area">Knoxville Area</a></li>
                        <li><a href="/courses?region=Memphis Area">Memphis Area</a></li>
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
</body>
</html>