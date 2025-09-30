<?php
session_start();
require_once '../includes/seo.php';

// Article data for SEO
$article_data = [
    'title' => 'Top 5 Best Golf Courses Near Nashville, Tennessee 2025',
    'description' => 'Discover the top 5 highest-rated golf courses near Nashville, Tennessee. Based on expert rankings, player reviews, and championship pedigree from leading golf publications.',
    'image' => '/images/reviews/top-5-best-golf-courses-near-nashville-tn/0.webp',
    'type' => 'article',
    'author' => 'Michael Travers',
    'date' => '2025-09-05',
    'category' => 'Course Reviews'
];

SEO::setupArticlePage($article_data);

// Article metadata
$article = [
    'title' => 'Top 5 Best Golf Courses Near Nashville, Tennessee 2025',
    'slug' => 'top-5-best-golf-courses-near-nashville-tn',
    'date' => 'Sep 5, 2025',
    'time' => '3:45 PM',
    'category' => 'Course Reviews',
    'excerpt' => 'Discover the top 5 highest-rated golf courses near Nashville, Tennessee. Based on expert rankings, player reviews, and championship pedigree from leading golf publications.',
    'image' => '/images/reviews/top-5-best-golf-courses-near-nashville-tn/0.webp',
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
                <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="Top Golf Courses Near Nashville Tennessee 2025" class="article-hero-image">
                <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>
                <div class="article-meta">
                    <span><i class="far fa-calendar"></i> <?php echo htmlspecialchars($article['date']); ?></span>
                    <span><i class="far fa-folder"></i> <?php echo htmlspecialchars($article['category']); ?></span>
                    <span><a href="/profile?username=michael-travers" style="text-decoration: none; color: inherit; display: inline-flex; align-items: center;"><img src="/uploads/profile_pictures/michael-travers.webp" alt="Michael Travers" style="width: 32px; height: 32px; border-radius: 50%; margin-right: 8px; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"><span style="text-decoration: underline;"><?php echo htmlspecialchars($article['author']); ?></span></a></span>
                </div>
            </header>

            <!-- Article Content -->
            <article class="article-content">
                <h2>Top 5 Best Golf Courses Near Nashville, Tennessee 2025</h2>
                <ol style="list-style: none; padding: 0; margin: 2rem 0;">
                    <li style="margin-bottom: 0.75rem;"><a href="#course-5" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 1.1rem;">5. Vanderbilt Legends Club</a></li>
                    <li style="margin-bottom: 0.75rem;"><a href="#course-4" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 1.1rem;">4. GreyStone Golf Club</a></li>
                    <li style="margin-bottom: 0.75rem;"><a href="#course-3" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 1.1rem;">3. Gaylord Springs Golf Links</a></li>
                    <li style="margin-bottom: 0.75rem;"><a href="#course-2" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 1.1rem;">2. Hermitage Golf Course</a></li>
                    <li style="margin-bottom: 0.75rem;"><a href="#course-1" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 1.1rem;">1. Belle Meade Country Club</a></li>
                </ol>

<!-- #5 Course -->
                <div class="course-item" id="course-5">
                    <div class="course-rank">#5</div>
                    <h3 class="course-name">Vanderbilt Legends Club</h3>
                    <img src="/images/reviews/top-5-best-golf-courses-near-nashville-tn/5.webp" alt="Vanderbilt Legends Club Franklin Tennessee championship golf course Korn Ferry Tour venue" class="course-image">
                    <div class="course-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        Host of 2024 Simmons Bank Open for the Snedeker Foundation
                    </div>
                    <p>Founded in 1992, Vanderbilt Legends Club is a 36-hole golf club located in Franklin, just 17 miles south of downtown Nashville. Designed by Bob Cupp and Tom Kite, the club features two championship courses with the North course measuring 7,200 yards par-72 and the South course playing 7,100 yards par 71. The courses, along with their pristine 19-acre practice facility, are widely recognized among the finest in Tennessee. Beginning in 2024, the club became the host venue for the Simmons Bank Open for the Snedeker Foundation on the Korn Ferry Tour, played on the North Course.</p>

                    <div class="course-details">
                        <div class="detail-item">
                            <span>Location:</span>
                            <span><strong>Franklin, TN</strong></span>
                        </div>
                        <div class="detail-item">
                            <span>Designers:</span>
                            <span>Bob Cupp & Tom Kite</span>
                        </div>
                        <div class="detail-item">
                            <span>Founded:</span>
                            <span>1992</span>
                        </div>
                        <div class="detail-item">
                            <span>Tournament:</span>
                            <span>Korn Ferry Tour</span>
                        </div>
                    </div>

                    <div class="pros-cons">
                        <div class="pros">
                            <h4>Pros</h4>
                            <ul>
                                <li>Two championship courses available</li>
                                <li>Host venue for Korn Ferry Tour event</li>
                                <li>Pristine 19-acre practice facility</li>
                                <li>Designed by Bob Cupp and Tom Kite</li>
                            </ul>
                        </div>
                        <div class="cons">
                            <h4>Cons</h4>
                            <ul>
                                <li>Private club membership required</li>
                                <li>Located 17 miles south of Nashville</li>
                            </ul>
                        </div>
                    </div>

                    <a href="/courses/vanderbilt-legends-club" class="visit-link">
                        <i class="fas fa-golf-ball"></i> View Course Details
                    </a>
                </div>

<!-- #4 Course -->
                <div class="course-item" id="course-4">
                    <div class="course-rank">#4</div>
                    <h3 class="course-name">GreyStone Golf Club</h3>
                    <img src="/images/reviews/top-5-best-golf-courses-near-nashville-tn/4.webp" alt="GreyStone Golf Club Dickson Tennessee public championship golf course Tennessee State Open" class="course-image">
                    <div class="course-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        4.7 stars (279 Google reviews)
                    </div>
                    <p>GreyStone Golf Club is an acclaimed public-access venue designed by Mark McCumber that offers a value-focused golfing experience with challenging terrain and scenic views. The course has hosted multiple Tennessee State Opens and other higher-profile competitive golf tournaments, establishing its credentials as a championship venue. GreyStone maintains an exceptional 4.7-star rating based on 279 Google user reviews and is scheduled to host the 2025 Tennessee State Junior Amateur. The course intentionally aims to be a value-brand of first-class public golf courses while maintaining championship-level conditions.</p>

                    <div class="course-details">
                        <div class="detail-item">
                            <span>Location:</span>
                            <span><strong>Dickson, TN</strong></span>
                        </div>
                        <div class="detail-item">
                            <span>Designer:</span>
                            <span>Mark McCumber</span>
                        </div>
                        <div class="detail-item">
                            <span>Rating:</span>
                            <span>4.7/5 stars (279 reviews)</span>
                        </div>
                        <div class="detail-item">
                            <span>Championships:</span>
                            <span>Multiple Tennessee State Opens</span>
                        </div>
                    </div>

                    <div class="pros-cons">
                        <div class="pros">
                            <h4>Pros</h4>
                            <ul>
                                <li>Mark McCumber championship design</li>
                                <li>Hosted multiple Tennessee State Opens</li>
                                <li>Exceptional value for quality of play</li>
                                <li>Outstanding 4.7-star Google rating</li>
                            </ul>
                        </div>
                        <div class="cons">
                            <h4>Cons</h4>
                            <ul>
                                <li>Located 40 minutes west of Nashville</li>
                                <li>Challenging terrain for beginners</li>
                            </ul>
                        </div>
                    </div>

                    <a href="/courses/greystone-golf-course" class="visit-link">
                        <i class="fas fa-golf-ball"></i> View Course Details
                    </a>
                </div>

<!-- #3 Course -->
                <div class="course-item" id="course-3">
                    <div class="course-rank">#3</div>
                    <h3 class="course-name">Gaylord Springs Golf Links</h3>
                    <img src="/images/reviews/top-5-best-golf-courses-near-nashville-tn/3.webp" alt="Gaylord Springs Golf Links Nashville Tennessee Scottish links Cumberland River Larry Nelson design" class="course-image">
                    <div class="course-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        "Best Courses You Can Play" - Golfweek Magazine
                    </div>
                    <p>Located just 10 minutes from Nashville International Airport and five minutes from Gaylord Opryland Resort, Gaylord Springs Golf Links offers an unparalleled Scottish links-style, par-72 layout. Designed by former US Open and PGA Champion Larry Nelson, this 18-hole golf course is carved from the banks of the Cumberland River and bordered by breathtaking limestone bluffs and wetlands. The course features rolling fairways, strategic bunkering, and undulating greens that have earned numerous accolades over the years, including being named one of the "Best Courses You Can Play" by Golfweek magazine.</p>

                    <div class="course-details">
                        <div class="detail-item">
                            <span>Location:</span>
                            <span><strong>Nashville, TN</strong></span>
                        </div>
                        <div class="detail-item">
                            <span>Designer:</span>
                            <span>Larry Nelson</span>
                        </div>
                        <div class="detail-item">
                            <span>Style:</span>
                            <span>Scottish Links</span>
                        </div>
                        <div class="detail-item">
                            <span>Setting:</span>
                            <span>Cumberland River</span>
                        </div>
                    </div>

                    <div class="pros-cons">
                        <div class="pros">
                            <h4>Pros</h4>
                            <ul>
                                <li>Designed by Major Champion Larry Nelson</li>
                                <li>Unique Scottish links style in Tennessee</li>
                                <li>Stunning Cumberland River setting</li>
                                <li>Golfweek "Best Courses You Can Play"</li>
                            </ul>
                        </div>
                        <div class="cons">
                            <h4>Cons</h4>
                            <ul>
                                <li>Premium pricing for public access</li>
                                <li>Windy conditions can be challenging</li>
                            </ul>
                        </div>
                    </div>

                    <a href="/courses/gaylord-springs-golf-links" class="visit-link">
                        <i class="fas fa-golf-ball"></i> View Course Details
                    </a>
                </div>

<!-- #2 Course -->
                <div class="course-item" id="course-2">
                    <div class="course-rank">#2</div>
                    <h3 class="course-name">Hermitage Golf Course</h3>
                    <img src="/images/reviews/top-5-best-golf-courses-near-nashville-tn/2.webp" alt="Hermitage Golf Course Nashville Tennessee Presidents Reserve Generals Retreat public championship" class="course-image">
                    <div class="course-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        Voted #1 Public Golf Course in Nashville
                    </div>
                    <p>Hermitage Golf Course is voted #1 Public Golf Course in Nashville and consistently ranks among the best public golf courses in Tennessee. The facility boasts not one, but two championship courses that offer a diverse range of challenges. The President's Reserve has been recognized as one of the top 10 courses in Tennessee by Golf Digest, while the General's Retreat is set along the Cumberland River and played host to an LPGA event from 1988 to 1999. Both courses provide challenging tree-lined fairways and strategically placed hazards, ensuring an exciting and rewarding round of golf for players of all abilities.</p>

                    <div class="course-details">
                        <div class="detail-item">
                            <span>Location:</span>
                            <span><strong>Nashville, TN</strong></span>
                        </div>
                        <div class="detail-item">
                            <span>Courses:</span>
                            <span>President's Reserve & General's Retreat</span>
                        </div>
                        <div class="detail-item">
                            <span>Recognition:</span>
                            <span>Golf Digest Top 10 TN</span>
                        </div>
                        <div class="detail-item">
                            <span>Tournament:</span>
                            <span>Former LPGA Venue (1988-1999)</span>
                        </div>
                    </div>

                    <div class="pros-cons">
                        <div class="pros">
                            <h4>Pros</h4>
                            <ul>
                                <li>Voted #1 public course in Nashville</li>
                                <li>Two championship courses available</li>
                                <li>Golf Digest Top 10 Tennessee ranking</li>
                                <li>Former LPGA tournament venue</li>
                            </ul>
                        </div>
                        <div class="cons">
                            <h4>Cons</h4>
                            <ul>
                                <li>Popular course can be crowded</li>
                                <li>Challenging for high handicap players</li>
                            </ul>
                        </div>
                    </div>

                    <a href="/courses/hermitage-golf-course" class="visit-link">
                        <i class="fas fa-golf-ball"></i> View Course Details
                    </a>
                </div>

<!-- #1 Course -->
                <div class="course-item" id="course-1">
                    <div class="course-rank">#1</div>
                    <h3 class="course-name">Belle Meade Country Club</h3>
                    <img src="/images/reviews/top-5-best-golf-courses-near-nashville-tn/1.webp" alt="Belle Meade Country Club Nashville Tennessee Donald Ross private championship golf course" class="course-image">
                    <div class="course-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        Golf Digest Top Golf Courses in Tennessee 2005-06
                    </div>
                    <p>Belle Meade Country Club stands as Nashville's premier golf destination, ranked among Golf Digest's top golf courses in Tennessee for 2005-06 and widely regarded as one of the best golf courses in Tennessee. This historic club features a classic Donald Ross-designed Bermuda grass golf course that first opened in 1901, representing over a century of Nashville golf excellence. The par-72 course plays to 6,885 yards with a course rating of 74.6 and a slope of 139. With its beautiful historic architecture coupled with modern amenities, Belle Meade Country Club is known as one of Nashville's best golf courses, consistently maintaining excellent course conditions.</p>

                    <div class="course-details">
                        <div class="detail-item">
                            <span>Location:</span>
                            <span><strong>Nashville, TN</strong></span>
                        </div>
                        <div class="detail-item">
                            <span>Designer:</span>
                            <span>Donald Ross</span>
                        </div>
                        <div class="detail-item">
                            <span>Opened:</span>
                            <span>1901</span>
                        </div>
                        <div class="detail-item">
                            <span>Yardage:</span>
                            <span>6,885 yards, Par 72</span>
                        </div>
                    </div>

                    <div class="pros-cons">
                        <div class="pros">
                            <h4>Pros</h4>
                            <ul>
                                <li>Classic Donald Ross design from 1901</li>
                                <li>Golf Digest Top Tennessee ranking</li>
                                <li>Excellent course conditions year-round</li>
                                <li>Historic Nashville golf heritage</li>
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

                    <a href="/courses/belle-meade-country-club" class="visit-link">
                        <i class="fas fa-golf-ball"></i> View Course Details
                    </a>
                </div>

                <!-- Honorable Mentions -->
                <div style="background: var(--bg-light); padding: 2rem; border-radius: 15px; margin: 3rem 0; border: 2px solid var(--secondary-color);">
                    <h3 style="color: var(--secondary-color); margin-bottom: 1.5rem;"><i class="fas fa-trophy"></i> Honorable Mentions</h3>

                    <div style="margin-bottom: 2rem;">
                        <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Old Hickory Country Club</h4>
                        <img src="/images/reviews/top-5-best-golf-courses-near-nashville-tn/6.webp" alt="Old Hickory Country Club Nashville Tennessee private golf course championship conditions" style="width: 100%; max-width: 400px; height: 250px; object-fit: contain; background: white; border-radius: 10px; margin: 1rem 0; box-shadow: var(--shadow-light); padding: 1rem;">
                        <p style="margin-bottom: 0.5rem;"><strong>Location:</strong> Old Hickory, TN</p>
                        <p>Built in 1926, Old Hickory Country Club features one of Nashville's oldest and best golf courses. At 6,617 yards and par 71, the course boasts some of the best Bermuda greens in the area that are quick, smooth, and consistent. Once a Web.com qualifying course, Old Hickory is known for its championship-level conditions and is truly an underrated gem of a golf course with excellent course conditions throughout the year.</p>
                    </div>

                    <div>
                        <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Two Rivers Golf Course</h4>
                        <img src="/images/reviews/top-5-best-golf-courses-near-nashville-tn/7.webp" alt="Two Rivers Golf Course Nashville Tennessee municipal public golf affordable well maintained" style="width: 100%; max-width: 400px; height: 250px; object-fit: contain; background: white; border-radius: 10px; margin: 1rem 0; box-shadow: var(--shadow-light); padding: 1rem;">
                        <p style="margin-bottom: 0.5rem;"><strong>Location:</strong> Nashville, TN</p>
                        <p>Two Rivers Golf Course is a municipal course favorite located in the heart of Donelson, east of downtown Nashville. Built in 1973 with greens and tees redesigned in 1991, the course underwent a major greens renovation in Summer 2016, upgrading to Ultradwarf Bermuda and placing Two Rivers greens among the best in the state. As one of 7 Nashville city courses, Two Rivers is very well maintained with greens covered in winter and consistently in great shape year-round.</p>
                    </div>
                </div>

                <p>The Nashville golf scene offers exceptional variety, from classic Donald Ross designs at <strong>Belle Meade Country Club</strong> to championship public golf at <strong>Hermitage Golf Course</strong> and unique Scottish links at <strong>Gaylord Springs</strong>. Whether seeking private club exclusivity or accessible public golf, these five courses represent the pinnacle of Nashville golf excellence, each offering distinct challenges and memorable experiences.</p>

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