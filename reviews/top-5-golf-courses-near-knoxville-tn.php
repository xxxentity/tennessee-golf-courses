<?php
session_start();

// Article metadata
$article = [
    'title' => 'Top 5 Best Golf Courses Near Knoxville, Tennessee 2025',
    'slug' => 'top-5-golf-courses-near-knoxville-tn',
    'date' => 'Aug 18, 2025',
    'time' => '2:15 PM',
    'category' => 'Course Reviews',
    'excerpt' => 'Discover the top 5 highest-rated golf courses near Knoxville, Tennessee. Based on expert rankings, player reviews, and championship pedigree from leading golf publications.',
    'image' => '/images/reviews/top-5-golf-courses-near-knoxville-tn/0.webp',
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
                <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="Top Golf Courses Near Knoxville Tennessee 2025" class="article-hero-image">
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
                <h2>Top 5 Best Golf Courses Near Knoxville, Tennessee 2025</h2>
                <ol style="list-style: none; padding: 0; margin: 2rem 0;">
                    <li style="margin-bottom: 0.75rem;"><a href="#course-5" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 1.1rem;">5. Williams Creek Golf Course</a></li>
                    <li style="margin-bottom: 0.75rem;"><a href="#course-4" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 1.1rem;">4. Whittle Springs Golf Course</a></li>
                    <li style="margin-bottom: 0.75rem;"><a href="#course-3" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 1.1rem;">3. WindRiver Golf Club</a></li>
                    <li style="margin-bottom: 0.75rem;"><a href="#course-2" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 1.1rem;">2. Cherokee Country Club</a></li>
                    <li style="margin-bottom: 0.75rem;"><a href="#course-1" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 1.1rem;">1. Holston Hills Country Club</a></li>
                </ol>

<!-- #5 Course -->
                <div class="course-item" id="course-5">
                    <div class="course-rank">#5</div>
                    <h3 class="course-name">Williams Creek Golf Course</h3>
                    <img src="/images/reviews/top-5-golf-courses-near-knoxville-tn/5.webp" alt="Williams Creek Golf Course" class="course-image">
                    <div class="course-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        4.51/5 stars (581 reviews)
                    </div>
                    <p>Williams Creek Golf Course stands out as the highest-rated public course in our rankings with an impressive 4.51 out of 5 stars based on 581 reviews. Located off James White Parkway on Dandridge Road, this par-3 course offers golfers an excellent 18-hole experience that can be completed in approximately 2 hours. Recent players consistently praise the course for its excellent fairway and green conditions, making it an ideal choice for both practice and casual rounds.</p>
                    
                    <div class="course-details">
                        <div class="detail-item">
                            <span>Location:</span>
                            <span><strong>Knoxville, TN</strong></span>
                        </div>
                        <div class="detail-item">
                            <span>Course Type:</span>
                            <span>Par-3 Public Course</span>
                        </div>
                        <div class="detail-item">
                            <span>Round Time:</span>
                            <span>Approximately 2 Hours</span>
                        </div>
                        <div class="detail-item">
                            <span>Player Rating:</span>
                            <span>4.51/5 (581 reviews)</span>
                        </div>
                    </div>

                    <div class="pros-cons">
                        <div class="pros">
                            <h4>Pros</h4>
                            <ul>
                                <li>Highest rating among public courses (4.51/5)</li>
                                <li>Excellent fairway and green conditions</li>
                                <li>Quick 2-hour round perfect for practice</li>
                                <li>Consistently positive player reviews</li>
                            </ul>
                        </div>
                        <div class="cons">
                            <h4>Cons</h4>
                            <ul>
                                <li>Par-3 only, limited to short game practice</li>
                                <li>May not challenge experienced golfers</li>
                            </ul>
                        </div>
                    </div>
                    
                    <a href="/courses/williams-creek-golf-course" class="visit-link">
                        <i class="fas fa-golf-ball"></i> View Course Details
                    </a>
                </div>

<!-- #4 Course -->
                <div class="course-item" id="course-4">
                    <div class="course-rank">#4</div>
                    <h3 class="course-name">Whittle Springs Golf Course</h3>
                    <img src="/images/reviews/top-5-golf-courses-near-knoxville-tn/4.webp" alt="Whittle Springs Golf Course" class="course-image">
                    <div class="course-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                        Knoxville's First Public Golf Course
                    </div>
                    <p>Whittle Springs Golf Course holds the distinction of being Knoxville's first public golf course and continues to host annual amateur golf tournaments. This 18-hole, par-70 course is operated by Purple Horse Hospitality and ranks as the 6th best golf course in Knoxville according to local rankings. With a rating of 3.86 out of 5 stars based on 398 reviews, Whittle Springs offers golfers a historic and well-maintained golfing experience that has served the community for decades.</p>
                    
                    <div class="course-details">
                        <div class="detail-item">
                            <span>Location:</span>
                            <span><strong>Knoxville, TN</strong></span>
                        </div>
                        <div class="detail-item">
                            <span>Course Layout:</span>
                            <span>18 Holes, Par 70</span>
                        </div>
                        <div class="detail-item">
                            <span>Historic Significance:</span>
                            <span>First Public Course in Knoxville</span>
                        </div>
                        <div class="detail-item">
                            <span>Management:</span>
                            <span>Purple Horse Hospitality</span>
                        </div>
                    </div>

                    <div class="pros-cons">
                        <div class="pros">
                            <h4>Pros</h4>
                            <ul>
                                <li>Historic significance as first public course</li>
                                <li>Hosts annual amateur tournaments</li>
                                <li>Professional management by Purple Horse</li>
                                <li>Full 18-hole championship layout</li>
                            </ul>
                        </div>
                        <div class="cons">
                            <h4>Cons</h4>
                            <ul>
                                <li>Lower rating compared to newer courses</li>
                                <li>May show age in some facilities</li>
                            </ul>
                        </div>
                    </div>
                    
                    <a href="/courses/whittle-springs-golf-course" class="visit-link">
                        <i class="fas fa-golf-ball"></i> View Course Details
                    </a>
                </div>

<!-- #3 Course -->
                <div class="course-item" id="course-3">
                    <div class="course-rank">#3</div>
                    <h3 class="course-name">WindRiver Golf Club</h3>
                    <img src="/images/reviews/top-5-golf-courses-near-knoxville-tn/3.webp" alt="WindRiver Golf Club" class="course-image">
                    <div class="course-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        Bob Cupp Championship Design
                    </div>
                    <p>WindRiver Golf Club is a prestigious private golf club and residential community featuring an 18-hole championship golf course designed by renowned architect Bob Cupp. Located in Lenoir City, this club offers a challenging yet fair course with well-maintained fairways, challenging layout, and true sandy greens. The facility includes comprehensive practice facilities, tennis courts, swimming pools, and dining venues, making it a complete luxury golf destination.</p>
                    
                    <div class="course-details">
                        <div class="detail-item">
                            <span>Location:</span>
                            <span><strong>Lenoir City, TN</strong></span>
                        </div>
                        <div class="detail-item">
                            <span>Designer:</span>
                            <span>Bob Cupp</span>
                        </div>
                        <div class="detail-item">
                            <span>Course Type:</span>
                            <span>Private Championship Course</span>
                        </div>
                        <div class="detail-item">
                            <span>Amenities:</span>
                            <span>Full Resort Facilities</span>
                        </div>
                    </div>

                    <div class="pros-cons">
                        <div class="pros">
                            <h4>Pros</h4>
                            <ul>
                                <li>Bob Cupp championship design</li>
                                <li>Well-maintained fairways and greens</li>
                                <li>Comprehensive resort amenities</li>
                                <li>Challenging yet fair layout</li>
                            </ul>
                        </div>
                        <div class="cons">
                            <h4>Cons</h4>
                            <ul>
                                <li>Private club with membership requirements</li>
                                <li>Premium pricing for non-members</li>
                            </ul>
                        </div>
                    </div>
                    
                    <a href="/courses/windtree-golf-course" class="visit-link">
                        <i class="fas fa-golf-ball"></i> View Course Details
                    </a>
                </div>

<!-- #2 Course -->
                <div class="course-item" id="course-2">
                    <div class="course-rank">#2</div>
                    <h3 class="course-name">Cherokee Country Club</h3>
                    <img src="/images/reviews/top-5-golf-courses-near-knoxville-tn/2.webp" alt="Cherokee Country Club" class="course-image">
                    <div class="course-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        #1 Ranked Course in Knoxville (Local Rankings)
                    </div>
                    <p>Cherokee Country Club stands as the #1 ranked golf course in Knoxville according to local rankings, featuring an 18-hole championship golf course designed by the legendary Donald Ross. This private golf club offers an 18-hole, par-70 course with tree-lined fairways, bluegrass and bent grass greens, plus comprehensive amenities including tennis courts, swimming pool, and dining facilities. The North Course is particularly recognized in state-wide golf rankings alongside other prestigious Tennessee clubs.</p>
                    
                    <div class="course-details">
                        <div class="detail-item">
                            <span>Location:</span>
                            <span><strong>Knoxville, TN</strong></span>
                        </div>
                        <div class="detail-item">
                            <span>Designer:</span>
                            <span>Donald Ross</span>
                        </div>
                        <div class="detail-item">
                            <span>Course Layout:</span>
                            <span>18 Holes, Par 70</span>
                        </div>
                        <div class="detail-item">
                            <span>Local Ranking:</span>
                            <span>#1 in Knoxville</span>
                        </div>
                    </div>

                    <div class="pros-cons">
                        <div class="pros">
                            <h4>Pros</h4>
                            <ul>
                                <li>#1 ranked course in Knoxville</li>
                                <li>Classic Donald Ross design</li>
                                <li>Complete club amenities</li>
                                <li>Tree-lined fairways and premium greens</li>
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
                    
                    <a href="/courses/cherokee-country-club" class="visit-link">
                        <i class="fas fa-golf-ball"></i> View Course Details
                    </a>
                </div>

<!-- #1 Course -->
                <div class="course-item" id="course-1">
                    <div class="course-rank">#1</div>
                    <h3 class="course-name">Holston Hills Country Club</h3>
                    <img src="/images/reviews/top-5-golf-courses-near-knoxville-tn/1.webp" alt="Holston Hills Country Club" class="course-image">
                    <div class="course-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        #7 Golf Course in Tennessee by Golfweek 2024
                    </div>
                    <p>Holston Hills Country Club represents the pinnacle of Knoxville golf, ranking as the #7 golf course in Tennessee according to Golfweek 2024 rankings and earning recognition as the #1 private golf club in Tennessee by Golfweek for 2022. This historic 18-hole, par-72 course features a Donald Ross design from the 1920s that has remained largely unaltered since its original layout. The course showcases some consider the best-preserved Donald Ross design in the USA, maintaining its classic architecture and strategic challenges.</p>
                    
                    <div class="course-details">
                        <div class="detail-item">
                            <span>Location:</span>
                            <span><strong>Knoxville, TN</strong></span>
                        </div>
                        <div class="detail-item">
                            <span>Designer:</span>
                            <span>Donald Ross (1920s)</span>
                        </div>
                        <div class="detail-item">
                            <span>State Ranking:</span>
                            <span>#7 in Tennessee (Golfweek)</span>
                        </div>
                        <div class="detail-item">
                            <span>Course Layout:</span>
                            <span>18 Holes, Par 72</span>
                        </div>
                    </div>

                    <div class="pros-cons">
                        <div class="pros">
                            <h4>Pros</h4>
                            <ul>
                                <li>#7 course in Tennessee state rankings</li>
                                <li>Best-preserved Donald Ross design</li>
                                <li>Historic 1920s layout maintained</li>
                                <li>Recognized as top private club in state</li>
                            </ul>
                        </div>
                        <div class="cons">
                            <h4>Cons</h4>
                            <ul>
                                <li>Exclusive private club membership</li>
                                <li>Extremely limited public access</li>
                            </ul>
                        </div>
                    </div>
                    
                    <a href="/courses/holston-hills-country-club" class="visit-link">
                        <i class="fas fa-golf-ball"></i> View Course Details
                    </a>
                </div>

                <!-- Honorable Mentions -->
                <div style="background: var(--bg-light); padding: 2rem; border-radius: 15px; margin: 3rem 0; border: 2px solid var(--secondary-color);">
                    <h3 style="color: var(--secondary-color); margin-bottom: 1.5rem;"><i class="fas fa-trophy"></i> Honorable Mentions</h3>
                    
                    <div style="margin-bottom: 2rem;">
                        <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Avalon Golf & Country Club</h4>
                        <img src="/images/reviews/top-5-golf-courses-near-knoxville-tn/6.webp" alt="Avalon Golf & Country Club" style="width: 100%; max-width: 400px; height: 250px; object-fit: contain; background: white; border-radius: 10px; margin: 1rem 0; box-shadow: var(--shadow-light); padding: 1rem;">
                        <p style="margin-bottom: 0.5rem;"><strong>Location:</strong> Lenoir City, TN</p>
                        <p>Avalon Golf & Country Club is an 18-hole, par-72 course with an impressive 4.34 out of 5 stars rating based on 671 reviews. This public course offers excellent value and consistently high player satisfaction, making it one of the most popular golf destinations in the greater Knoxville area. The course provides a challenging yet accessible layout suitable for golfers of all skill levels.</p>
                    </div>
                    
                    <div>
                        <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Dead Horse Lake Golf Course</h4>
                        <img src="/images/reviews/top-5-golf-courses-near-knoxville-tn/7.webp" alt="Dead Horse Lake Golf Course" style="width: 100%; max-width: 400px; height: 250px; object-fit: contain; background: white; border-radius: 10px; margin: 1rem 0; box-shadow: var(--shadow-light); padding: 1rem;">
                        <p style="margin-bottom: 0.5rem;"><strong>Location:</strong> Knoxville, TN</p>
                        <p>Dead Horse Lake Golf Course ranks as the #1 best public golf course in Knoxville according to local rankings. This 18-hole, par-72 public course offers golfers an excellent championship layout with a 4.25 out of 5 stars rating. The course provides outstanding value for public golf in the Knoxville area, featuring well-maintained conditions and challenging play that attracts both locals and visitors.</p>
                    </div>
                </div>

                <p>The Knoxville area offers exceptional golf diversity, from Donald Ross classics at <strong>Holston Hills Country Club</strong> and <strong>Cherokee Country Club</strong> to modern championship designs and excellent public facilities. Whether seeking prestigious private club experiences or outstanding public golf values, these five courses represent the finest golf destinations within easy reach of Knoxville.</p>

                <p><em>Rankings based on comprehensive analysis from Local Golf Spot, GolfPass, Golfweek, Tripadvisor, and multiple local golf publications for 2025.</em></p>
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