<?php
session_start();

// Article metadata
$article = [
    'title' => 'Top 5 Best Golf Courses in Tennessee: Championship Destinations',
    'slug' => 'top-5-golf-courses-tennessee',
    'date' => 'Aug 21, 2025',
    'time' => '6:45 PM',
    'category' => 'Course Reviews',
    'excerpt' => 'Discover the top 5 highest-rated golf courses in Tennessee. Based on expert rankings, championship pedigree, and architectural excellence from leading golf publications.',
    'image' => '/images/reviews/top-5-golf-courses-tennessee/0.webp',
    'featured' => true,
    'author' => 'TGC Editorial Team',
    'read_time' => '8 min read'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?> - Tennessee Golf Courses</title>
    <meta name="description" content="<?php echo htmlspecialchars($article['excerpt']); ?>">
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
                <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="Top Golf Courses Tennessee 2025" class="article-hero-image">
                <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>
                <div class="article-meta">
                    <span><i class="far fa-calendar"></i> <?php echo htmlspecialchars($article['date']); ?></span>
                    <span><i class="far fa-clock"></i> <?php echo htmlspecialchars($article['read_time']); ?></span>
                    <span><i class="far fa-user"></i> <?php echo htmlspecialchars($article['author']); ?></span>
                    <span><i class="far fa-folder"></i> <?php echo htmlspecialchars($article['category']); ?></span>
                </div>
            </header>

            <!-- Article Content -->
            <article class="article-content">
                <h2>Top 5 Best Golf Courses in Tennessee</h2>
                <ol style="list-style: none; padding: 0; margin: 2rem 0;">
                    <li style="margin-bottom: 0.75rem;"><a href="#course-5" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 1.1rem;">5. Fall Creek Falls State Park Golf Course</a></li>
                    <li style="margin-bottom: 0.75rem;"><a href="#course-4" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 1.1rem;">4. TPC Southwind</a></li>
                    <li style="margin-bottom: 0.75rem;"><a href="#course-3" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 1.1rem;">3. The Golf Club of Tennessee</a></li>
                    <li style="margin-bottom: 0.75rem;"><a href="#course-2" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 1.1rem;">2. Troubadour Golf & Field Club</a></li>
                    <li style="margin-bottom: 0.75rem;"><a href="#course-1" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 1.1rem;">1. The Honors Course</a></li>
                </ol>

<!-- #5 Course -->
                <div class="course-item" id="course-5">
                    <div class="course-rank">#5</div>
                    <h3 class="course-name">Fall Creek Falls State Park Golf Course</h3>
                    <img src="/images/reviews/top-5-golf-courses-tennessee/5.webp" alt="Fall Creek Falls State Park Golf Course" class="course-image">
                    <div class="course-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        Three-Time Golf Digest Top 100 Public Places to Play
                    </div>
                    <p>A three-time selection by Golf Digest as one of the Top 100 Public Places to Play, this Joseph L. Lee design opened in 1972 and remains a challenging championship layout. The course features 6,669 yards of golf from the longest tees with a par of 72, carved through Tennessee's natural landscape with Bermuda grass fairways and greens. The course is a Certified Audubon Cooperative Sanctuary, showcasing exceptional environmental stewardship.</p>
                    
                    <div class="course-details">
                        <div class="detail-item">
                            <span>Location:</span>
                            <span><strong>Spencer, TN</strong></span>
                        </div>
                        <div class="detail-item">
                            <span>Designer:</span>
                            <span>Joseph L. Lee</span>
                        </div>
                        <div class="detail-item">
                            <span>Opened:</span>
                            <span>1972</span>
                        </div>
                        <div class="detail-item">
                            <span>Yardage:</span>
                            <span>6,669 yards</span>
                        </div>
                    </div>

                    <div class="pros-cons">
                        <div class="pros">
                            <h4>Pros</h4>
                            <ul>
                                <li>Three-time Golf Digest Top 100 Public Places</li>
                                <li>Certified Audubon Cooperative Sanctuary</li>
                                <li>Challenging championship layout</li>
                                <li>Excellent value for quality golf</li>
                            </ul>
                        </div>
                        <div class="cons">
                            <h4>Cons</h4>
                            <ul>
                                <li>Remote location requires travel</li>
                                <li>Limited amenities compared to resorts</li>
                            </ul>
                        </div>
                    </div>
                    
                    <a href="/courses/fall-creek-falls-state-park-golf-course" class="visit-link">
                        <i class="fas fa-golf-ball"></i> View Course Details
                    </a>
                </div>

<!-- #4 Course -->
                <div class="course-item" id="course-4">
                    <div class="course-rank">#4</div>
                    <h3 class="course-name">TPC Southwind</h3>
                    <img src="/images/reviews/top-5-golf-courses-tennessee/4.webp" alt="TPC Southwind Golf Course" class="course-image">
                    <div class="course-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        Host of FedEx St. Jude Championship
                    </div>
                    <p>Located 30 minutes from downtown Memphis, TPC Southwind hosts the annual FedEx St. Jude Championship and has been a PGA Tour venue since 1989. The Ron Prichard design features consultation from PGA Tour players Hubert Green and Fuzzy Zoeller. This par-70, 7,244-yard championship layout showcases undulating zoysia fairways, champion Bermuda greens, and numerous lakes, streams, and ponds creating strategic challenges throughout.</p>
                    
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
                                <li>Annual PGA Tour host since 1989</li>
                                <li>Championship-level conditioning</li>
                                <li>Strategic water features throughout</li>
                                <li>Audubon Cooperative Sanctuary certified</li>
                            </ul>
                        </div>
                        <div class="cons">
                            <h4>Cons</h4>
                            <ul>
                                <li>Premium pricing during tournament season</li>
                                <li>Extremely challenging for average golfers</li>
                            </ul>
                        </div>
                    </div>
                    
                    <a href="/courses/tpc-southwind" class="visit-link">
                        <i class="fas fa-golf-ball"></i> View Course Details
                    </a>
                </div>

<!-- #3 Course -->
                <div class="course-item" id="course-3">
                    <div class="course-rank">#3</div>
                    <h3 class="course-name">The Golf Club of Tennessee</h3>
                    <img src="/images/reviews/top-5-golf-courses-tennessee/3.webp" alt="The Golf Club of Tennessee" class="course-image">
                    <div class="course-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        Golf Digest Second 100 Greatest - 199th Nationally
                    </div>
                    <p>Ranked 199th nationally in Golf Digest's Second 100 Greatest courses for 2025-26, this Tom Fazio masterpiece maintains its position as Tennessee's second-ranked course. The Kingston Springs layout showcases Fazio's signature modern design philosophy with excellent variety and strategic options. The course offers nice variety and modern architecture that appeals to golfers seeking a contemporary championship experience in Tennessee.</p>
                    
                    <div class="course-details">
                        <div class="detail-item">
                            <span>Location:</span>
                            <span><strong>Kingston Springs, TN</strong></span>
                        </div>
                        <div class="detail-item">
                            <span>Designer:</span>
                            <span>Tom Fazio</span>
                        </div>
                        <div class="detail-item">
                            <span>National Ranking:</span>
                            <span>199th (Golf Digest)</span>
                        </div>
                        <div class="detail-item">
                            <span>State Ranking:</span>
                            <span>2nd in Tennessee</span>
                        </div>
                    </div>

                    <div class="pros-cons">
                        <div class="pros">
                            <h4>Pros</h4>
                            <ul>
                                <li>Top 200 course nationally by Golf Digest</li>
                                <li>Tom Fazio's signature design excellence</li>
                                <li>Modern architecture with strategic variety</li>
                                <li>Consistently excellent course conditioning</li>
                            </ul>
                        </div>
                        <div class="cons">
                            <h4>Cons</h4>
                            <ul>
                                <li>Private club with limited access</li>
                                <li>Premium membership requirements</li>
                            </ul>
                        </div>
                    </div>
                    
                    <a href="/courses/the-golf-club-of-tennessee" class="visit-link">
                        <i class="fas fa-golf-ball"></i> View Course Details
                    </a>
                </div>

<!-- #2 Course -->
                <div class="course-item" id="course-2">
                    <div class="course-rank">#2</div>
                    <h3 class="course-name">Troubadour Golf & Field Club</h3>
                    <img src="/images/reviews/top-5-golf-courses-tennessee/2.webp" alt="Troubadour Golf & Field Club" class="course-image">
                    <div class="course-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        Ranked 3rd in State as of 2024
                    </div>
                    <p>This Tom Fazio design enters the state rankings at 3rd as of 2024 and represents modern golf architecture at its finest. Built on elevated tees with panoramic views of central Tennessee foothills, the redesign eliminated previous severity and created more accessible, graceful layouts. The course showcases Fazio's ability to blend challenging golf with stunning natural beauty, making it one of Tennessee's premier modern golf destinations.</p>
                    
                    <div class="course-details">
                        <div class="detail-item">
                            <span>Location:</span>
                            <span><strong>Nashville Area, TN</strong></span>
                        </div>
                        <div class="detail-item">
                            <span>Designer:</span>
                            <span>Tom Fazio</span>
                        </div>
                        <div class="detail-item">
                            <span>State Ranking:</span>
                            <span>3rd in Tennessee (2024)</span>
                        </div>
                        <div class="detail-item">
                            <span>Setting:</span>
                            <span>Elevated with Panoramic Views</span>
                        </div>
                    </div>

                    <div class="pros-cons">
                        <div class="pros">
                            <h4>Pros</h4>
                            <ul>
                                <li>Tom Fazio's masterful modern design</li>
                                <li>Spectacular panoramic views</li>
                                <li>Redesigned for enhanced playability</li>
                                <li>Premier modern golf destination</li>
                            </ul>
                        </div>
                        <div class="cons">
                            <h4>Cons</h4>
                            <ul>
                                <li>Exclusive private club membership</li>
                                <li>Limited public access opportunities</li>
                            </ul>
                        </div>
                    </div>
                    
                    <a href="/courses/troubadour-golf-field-club" class="visit-link">
                        <i class="fas fa-golf-ball"></i> View Course Details
                    </a>
                </div>

<!-- #1 Course -->
                <div class="course-item" id="course-1">
                    <div class="course-rank">#1</div>
                    <h3 class="course-name">The Honors Course</h3>
                    <img src="/images/reviews/top-5-golf-courses-tennessee/1.webp" alt="The Honors Course" class="course-image">
                    <div class="course-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        #1 Tennessee Course Since 1983
                    </div>
                    <p>The venerable championship layout designed by Pete Dye has been ranked No. 1 in Tennessee since it opened in 1983 and remains a mainstay in the top third of national rankings. Considered radical in the early 1980s with acres of tall native-grass rough, durable zoysia fairways, and terrifying greens perched atop bulkheads of rock, The Honors Course represents a well-preserved example of Pete Dye's death-or-glory architecture that continues to challenge and inspire golfers today.</p>
                    
                    <div class="course-details">
                        <div class="detail-item">
                            <span>Location:</span>
                            <span><strong>Chattanooga, TN</strong></span>
                        </div>
                        <div class="detail-item">
                            <span>Designer:</span>
                            <span>Pete Dye</span>
                        </div>
                        <div class="detail-item">
                            <span>Opened:</span>
                            <span>1983</span>
                        </div>
                        <div class="detail-item">
                            <span>Status:</span>
                            <span>#1 in Tennessee Since Opening</span>
                        </div>
                    </div>

                    <div class="pros-cons">
                        <div class="pros">
                            <h4>Pros</h4>
                            <ul>
                                <li>Tennessee's #1 course for over 40 years</li>
                                <li>Top third of national golf rankings</li>
                                <li>Iconic Pete Dye death-or-glory design</li>
                                <li>Well-preserved architectural integrity</li>
                            </ul>
                        </div>
                        <div class="cons">
                            <h4>Cons</h4>
                            <ul>
                                <li>Extremely challenging for average players</li>
                                <li>Exclusive private club access only</li>
                            </ul>
                        </div>
                    </div>
                    
                    <a href="/courses/the-honors-course" class="visit-link">
                        <i class="fas fa-golf-ball"></i> View Course Details
                    </a>
                </div>

                <!-- Honorable Mentions -->
                <div style="background: var(--bg-light); padding: 2rem; border-radius: 15px; margin: 3rem 0; border: 2px solid var(--secondary-color);">
                    <h3 style="color: var(--secondary-color); margin-bottom: 1.5rem;"><i class="fas fa-trophy"></i> Honorable Mentions</h3>
                    
                    <div style="margin-bottom: 2rem;">
                        <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Sweetens Cove Golf Club</h4>
                        <img src="/images/reviews/top-5-golf-courses-tennessee/6.webp" alt="Sweetens Cove Golf Club" style="width: 100%; max-width: 400px; height: 250px; object-fit: contain; background: white; border-radius: 10px; margin: 1rem 0; box-shadow: var(--shadow-light); padding: 1rem;">
                        <p style="margin-bottom: 0.5rem;"><strong>Location:</strong> South Pittsburg, TN</p>
                        <p>The nine-holer 30 miles west of Chattanooga is probably the buzziest nine-hole course in the U.S. Designed by King-Collins with financial backing from Peyton Manning, this inland links layout offers numerous alternative routings and is consistently ranked one of the best Tennessee golf courses and a top 100 golf course in the world. The laidback atmosphere encourages casual dress and even allows dogs on the course.</p>
                    </div>
                    
                    <div>
                        <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Gaylord Springs Golf Links</h4>
                        <img src="/images/reviews/top-5-golf-courses-tennessee/7.webp" alt="Gaylord Springs Golf Links" style="width: 100%; max-width: 400px; height: 250px; object-fit: contain; background: white; border-radius: 10px; margin: 1rem 0; box-shadow: var(--shadow-light); padding: 1rem;">
                        <p style="margin-bottom: 0.5rem;"><strong>Location:</strong> Nashville, TN</p>
                        <p>Located 10 minutes from Nashville International Airport, this 1991 Jeff Brauer/Larry Nelson design once hosted the Champions Tour from 1994-2003. The course features wetlands, limestone bluffs, and the Cumberland River among its handsome hazards, offering golfers a classic layout that emphasizes accuracy over length for a bold and rewarding adventure.</p>
                    </div>
                </div>

                <p>Tennessee's golf landscape showcases exceptional variety, from Pete Dye's intimidating masterpiece at <strong>The Honors Course</strong> to modern Tom Fazio designs and championship PGA Tour venues. Whether seeking the ultimate golf challenge or scenic beauty, these five courses represent the pinnacle of Tennessee golf excellence.</p>

                <p><em>Rankings based on comprehensive analysis from multiple golf publications including Golf Digest, GOLF Magazine, Golfweek, and Top 100 Golf Courses.</em></p>
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