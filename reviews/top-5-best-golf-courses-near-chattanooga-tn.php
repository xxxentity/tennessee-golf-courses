<?php
session_start();
require_once '../includes/seo.php';

// Article data for SEO
$article_data = [
    'title' => 'Top 5 Best Golf Courses Near Chattanooga, Tennessee 2025',
    'description' => 'Discover the top 5 highest-rated golf courses near Chattanooga, Tennessee. Based on expert rankings, player reviews, and championship pedigree from leading golf publications.',
    'image' => '/images/reviews/top-5-best-golf-courses-near-chattanooga-tn/0.webp',
    'type' => 'article',
    'author' => 'Michael Travers',
    'date' => '2025-09-10',
    'category' => 'Course Reviews'
];

SEO::setupArticlePage($article_data);

// Article metadata
$article = [
    'title' => 'Top 5 Best Golf Courses Near Chattanooga, Tennessee 2025',
    'slug' => 'top-5-best-golf-courses-near-chattanooga-tn',
    'date' => 'Sep 10, 2025',
    'time' => '3:45 PM',
    'category' => 'Course Reviews',
    'excerpt' => 'Discover the top 5 highest-rated golf courses near Chattanooga, Tennessee. Based on expert rankings, player reviews, and championship pedigree from leading golf publications.',
    'image' => '/images/reviews/top-5-best-golf-courses-near-chattanooga-tn/0.webp',
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
                <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="Top Golf Courses Near Chattanooga Tennessee 2025" class="article-hero-image">
                <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>
                <div class="article-meta">
                    <span><i class="far fa-calendar"></i> <?php echo htmlspecialchars($article['date']); ?></span>
                    <span><i class="far fa-folder"></i> <?php echo htmlspecialchars($article['category']); ?></span>
                    <span><a href="/profile?username=michael-travers" style="text-decoration: none; color: inherit; display: inline-flex; align-items: center;"><img src="/uploads/profile_pictures/michael-travers.webp" alt="Michael Travers" style="width: 32px; height: 32px; border-radius: 50%; margin-right: 8px; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"><span style="text-decoration: underline;"><?php echo htmlspecialchars($article['author']); ?></span></a></span>
                </div>
            </header>

            <!-- Article Content -->
            <article class="article-content">
                <h2>Top 5 Best Golf Courses Near Chattanooga, Tennessee 2025</h2>
                <ol style="list-style: none; padding: 0; margin: 2rem 0;">
                    <li style="margin-bottom: 0.75rem;"><a href="#course-5" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 1.1rem;">5. Bear Trace at Harrison Bay</a></li>
                    <li style="margin-bottom: 0.75rem;"><a href="#course-4" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 1.1rem;">4. Chattanooga Golf & Country Club</a></li>
                    <li style="margin-bottom: 0.75rem;"><a href="#course-3" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 1.1rem;">3. Sweetens Cove Golf Club</a></li>
                    <li style="margin-bottom: 0.75rem;"><a href="#course-2" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 1.1rem;">2. Black Creek Club</a></li>
                    <li style="margin-bottom: 0.75rem;"><a href="#course-1" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 1.1rem;">1. The Honors Course</a></li>
                </ol>

<!-- #5 Course -->
                <div class="course-item" id="course-5">
                    <div class="course-rank">#5</div>
                    <h3 class="course-name">Bear Trace at Harrison Bay</h3>
                    <img src="/images/reviews/top-5-best-golf-courses-near-chattanooga-tn/5.webp" alt="Bear Trace Harrison Bay Chattanooga Tennessee Jack Nicklaus design public golf course state park" class="course-image">
                    <div class="course-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        Rated #18 Public Golf Course in Tennessee
                    </div>
                    <p>Bear Trace at Harrison Bay is the second Bear Trace course to open and is designed by legendary Jack Nicklaus. Located approximately 20 minutes north of downtown Chattanooga, this championship course is surrounded by both water and heavily-wooded land, offering extraordinarily fair rates for a Jack Nicklaus-designed public course. The layout showcases Nicklaus's strategic design philosophy, with water and woodland creating natural hazards that demand precision and course management. As part of Tennessee State Parks, Bear Trace at Harrison Bay provides world-class golf in a stunning natural setting.</p>

                    <div class="course-details">
                        <div class="detail-item">
                            <span>Location:</span>
                            <span><strong>Harrison, TN</strong></span>
                        </div>
                        <div class="detail-item">
                            <span>Designer:</span>
                            <span>Jack Nicklaus</span>
                        </div>
                        <div class="detail-item">
                            <span>Type:</span>
                            <span>Public (State Park)</span>
                        </div>
                        <div class="detail-item">
                            <span>Setting:</span>
                            <span>Water & Woodland</span>
                        </div>
                    </div>

                    <div class="pros-cons">
                        <div class="pros">
                            <h4>Pros</h4>
                            <ul>
                                <li>Jack Nicklaus championship design</li>
                                <li>Extraordinarily fair rates for quality</li>
                                <li>Stunning water and woodland setting</li>
                                <li>Rated #18 public course in Tennessee</li>
                            </ul>
                        </div>
                        <div class="cons">
                            <h4>Cons</h4>
                            <ul>
                                <li>20 minutes north of downtown Chattanooga</li>
                                <li>Challenging layout for higher handicaps</li>
                            </ul>
                        </div>
                    </div>

                    <a href="/courses/bear-trace-harrison-bay" class="visit-link">
                        <i class="fas fa-golf-ball"></i> View Course Details
                    </a>
                </div>

<!-- #4 Course -->
                <div class="course-item" id="course-4">
                    <div class="course-rank">#4</div>
                    <h3 class="course-name">Chattanooga Golf & Country Club</h3>
                    <img src="/images/reviews/top-5-best-golf-courses-near-chattanooga-tn/4.webp" alt="Chattanooga Golf Country Club Tennessee Donald Ross design Tennessee River private golf championship" class="course-image">
                    <div class="course-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        Ranked Top 10 in Tennessee Since 2007
                    </div>
                    <p>Founded in 1896 on the banks of the Tennessee River, Chattanooga Golf & Country Club engaged Donald Ross in the early 1920s to set out the course that's still in play today. The modern layout was extensively renovated by Bill Bergin in 2005, preserving Ross's original design intent while updating the course to modern standards. The course extends to 6,694 yards from the back markers and plays to a par of 71, with only three par fives. Four holes offer magnificent views of the Tennessee River, and the water is very much in play, creating both scenic beauty and strategic challenge.</p>

                    <div class="course-details">
                        <div class="detail-item">
                            <span>Location:</span>
                            <span><strong>Chattanooga, TN</strong></span>
                        </div>
                        <div class="detail-item">
                            <span>Designer:</span>
                            <span>Donald Ross (1920s)</span>
                        </div>
                        <div class="detail-item">
                            <span>Renovation:</span>
                            <span>Bill Bergin (2005)</span>
                        </div>
                        <div class="detail-item">
                            <span>Yardage:</span>
                            <span>6,694 yards, Par 71</span>
                        </div>
                    </div>

                    <div class="pros-cons">
                        <div class="pros">
                            <h4>Pros</h4>
                            <ul>
                                <li>Classic Donald Ross design from 1920s</li>
                                <li>Magnificent Tennessee River views</li>
                                <li>Top 10 Tennessee ranking since 2007</li>
                                <li>Bill Bergin renovation excellence</li>
                            </ul>
                        </div>
                        <div class="cons">
                            <h4>Cons</h4>
                            <ul>
                                <li>Private club membership required</li>
                                <li>River comes into play on multiple holes</li>
                            </ul>
                        </div>
                    </div>

                    <a href="/courses/chattanooga-golf-country-club" class="visit-link">
                        <i class="fas fa-golf-ball"></i> View Course Details
                    </a>
                </div>

<!-- #3 Course -->
                <div class="course-item" id="course-3">
                    <div class="course-rank">#3</div>
                    <h3 class="course-name">Sweetens Cove Golf Club</h3>
                    <img src="/images/reviews/top-5-best-golf-courses-near-chattanooga-tn/3.webp" alt="Sweetens Cove Golf Club Chattanooga Tennessee top 100 world golf course public access Sequatchie Valley" class="course-image">
                    <div class="course-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        #21 Best Course You Can Play - Golfweek
                    </div>
                    <p>Sweetens Cove Golf Club is an award-winning public golf course consistently ranked one of the best Tennessee golf courses and a top 100 Golf Course in the World. Featured in The New York Times and ranked the 49th Modern Golf Course and 21st Best Course You Can Play in the United States by Golfweek, this unique layout is located just 25 minutes from Chattanooga in the heart of the Sequatchie Valley. The course features wildly contoured greens, elaborate bunkering, and immense, tightly mown fairways that create a distinctive and memorable golf experience unlike anywhere else in the region.</p>

                    <div class="course-details">
                        <div class="detail-item">
                            <span>Location:</span>
                            <span><strong>South Pittsburg, TN</strong></span>
                        </div>
                        <div class="detail-item">
                            <span>Ranking:</span>
                            <span>Top 100 World Golf Course</span>
                        </div>
                        <div class="detail-item">
                            <span>Golfweek:</span>
                            <span>#21 Best You Can Play</span>
                        </div>
                        <div class="detail-item">
                            <span>Setting:</span>
                            <span>Sequatchie Valley</span>
                        </div>
                    </div>

                    <div class="pros-cons">
                        <div class="pros">
                            <h4>Pros</h4>
                            <ul>
                                <li>Top 100 Golf Course in the World</li>
                                <li>Golfweek #21 "Best You Can Play"</li>
                                <li>Unique wildly contoured greens</li>
                                <li>Public access to world-class golf</li>
                            </ul>
                        </div>
                        <div class="cons">
                            <h4>Cons</h4>
                            <ul>
                                <li>Extremely challenging for average golfers</li>
                                <li>25 minutes from downtown Chattanooga</li>
                            </ul>
                        </div>
                    </div>

                    <a href="/courses/sweetens-cove-golf-club" class="visit-link">
                        <i class="fas fa-golf-ball"></i> View Course Details
                    </a>
                </div>

<!-- #2 Course -->
                <div class="course-item" id="course-2">
                    <div class="course-rank">#2</div>
                    <h3 class="course-name">Black Creek Club</h3>
                    <img src="/images/reviews/top-5-best-golf-courses-near-chattanooga-tn/2.webp" alt="Black Creek Club Chattanooga Tennessee Brian Silva design golf digest top ranked private championship" class="course-image">
                    <div class="course-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        Golf Digest #12 in Tennessee 2025-26
                    </div>
                    <p>Black Creek Club has been ranked in Golf Digest's Best in State rankings consistently, appearing in the top 10 from 2003-2020 and 2023-2024, with a current 2025-26 ranking of 12th in Tennessee. Designed by Brian Silva, this championship course stretches 7,204 yards and is the only top 100 residential course in the state. The course features Silva's masterful interpretations of Seth Raynor and C.B. Macdonald's "ideal holes," creating strategic challenges that reward thoughtful play while testing golfers of all skill levels with its demanding length and sophisticated design elements.</p>

                    <div class="course-details">
                        <div class="detail-item">
                            <span>Location:</span>
                            <span><strong>Chattanooga, TN</strong></span>
                        </div>
                        <div class="detail-item">
                            <span>Designer:</span>
                            <span>Brian Silva</span>
                        </div>
                        <div class="detail-item">
                            <span>Yardage:</span>
                            <span>7,204 yards</span>
                        </div>
                        <div class="detail-item">
                            <span>Recognition:</span>
                            <span>Only Top 100 Residential Course TN</span>
                        </div>
                    </div>

                    <div class="pros-cons">
                        <div class="pros">
                            <h4>Pros</h4>
                            <ul>
                                <li>Golf Digest Top 15 ranking consistently</li>
                                <li>Brian Silva's masterful design</li>
                                <li>Only top 100 residential course in TN</li>
                                <li>Raynor/Macdonald "ideal holes" concepts</li>
                            </ul>
                        </div>
                        <div class="cons">
                            <h4>Cons</h4>
                            <ul>
                                <li>Private club membership required</li>
                                <li>Extremely long at 7,204 yards</li>
                            </ul>
                        </div>
                    </div>

                    <a href="/courses/black-creek-club" class="visit-link">
                        <i class="fas fa-golf-ball"></i> View Course Details
                    </a>
                </div>

<!-- #1 Course -->
                <div class="course-item" id="course-1">
                    <div class="course-rank">#1</div>
                    <h3 class="course-name">The Honors Course</h3>
                    <img src="/images/reviews/top-5-best-golf-courses-near-chattanooga-tn/1.webp" alt="The Honors Course Chattanooga Tennessee Pete Dye design championship golf course US Amateur NCAA venue" class="course-image">
                    <div class="course-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        #1 Ranked Golf Course in Tennessee
                    </div>
                    <p>The Honors Course stands as Tennessee's premier golf destination, ranked #1 in the state by Golf Digest and widely considered "the top spot in Chattanooga golf." Designed by Pete Dye and opened in 1983, this private course is arguably Pete Dye's best design and most universally appealing creation. Located in Ooltewah, just northeast of Chattanooga, the course has hosted prestigious championships including the 1991 U.S. Amateur (won by Mitch Voges), 1996 NCAA Championships (where Tiger Woods won the individual crown), and the 2005 U.S. Mid-Amateur. Recent restoration work by Gil Hanse has enhanced this masterpiece while preserving Dye's original design intent.</p>

                    <div class="course-details">
                        <div class="detail-item">
                            <span>Location:</span>
                            <span><strong>Ooltewah, TN</strong></span>
                        </div>
                        <div class="detail-item">
                            <span>Designer:</span>
                            <span>Pete Dye (1983)</span>
                        </div>
                        <div class="detail-item">
                            <span>Restoration:</span>
                            <span>Gil Hanse (Recent)</span>
                        </div>
                        <div class="detail-item">
                            <span>Championships:</span>
                            <span>U.S. Amateur, NCAA, U.S. Mid-Am</span>
                        </div>
                    </div>

                    <div class="pros-cons">
                        <div class="pros">
                            <h4>Pros</h4>
                            <ul>
                                <li>#1 ranked golf course in Tennessee</li>
                                <li>Arguably Pete Dye's best design</li>
                                <li>Hosted U.S. Amateur and NCAA Championships</li>
                                <li>Recent Gil Hanse restoration excellence</li>
                            </ul>
                        </div>
                        <div class="cons">
                            <h4>Cons</h4>
                            <ul>
                                <li>Extremely exclusive private membership</li>
                                <li>Pete Dye design can be punishing</li>
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
                        <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Lookout Mountain Club</h4>
                        <img src="/images/reviews/top-5-best-golf-courses-near-chattanooga-tn/6.webp" alt="Lookout Mountain Club Georgia Tennessee Seth Raynor design classical golf course championship venue" style="width: 100%; max-width: 400px; height: 250px; object-fit: contain; background: white; border-radius: 10px; margin: 1rem 0; box-shadow: var(--shadow-light); padding: 1rem;">
                        <p style="margin-bottom: 0.5rem;"><strong>Location:</strong> Lookout Mountain, GA</p>
                        <p>Designed by the renowned architect Seth Raynor in 1925, this championship course stands as a world-class gem, earning recognition from Golfweek magazine as one of America's top classical golf courses. Following major renovations in 2022, the club finally garnered the resources to produce the course Raynor envisioned, making it one of the best golf courses in Georgia, right outside Chattanooga, Tennessee.</p>
                    </div>

                    <div>
                        <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Council Fire Golf Club</h4>
                        <img src="/images/reviews/top-5-best-golf-courses-near-chattanooga-tn/7.webp" alt="Council Fire Golf Club Chattanooga Tennessee Robert Cupp design private golf championship tournament venue" style="width: 100%; max-width: 400px; height: 250px; object-fit: contain; background: white; border-radius: 10px; margin: 1rem 0; box-shadow: var(--shadow-light); padding: 1rem;">
                        <p style="margin-bottom: 0.5rem;"><strong>Location:</strong> Chattanooga, TN</p>
                        <p>Built in 1992 by world-renowned architect Bob Cupp, Council Fire Golf Club features 6,999 yards of golf over 114 acres of pristine Bermuda surfaces. The course has hosted The Chattanooga Classic PGA Tour event, Senior Tour, NCAA Championship, and numerous USGA and State championships. With Mini Verde Bermuda Grass greens and excellent course conditions, Council Fire maintains its reputation as one of Tennessee's premier golf destinations.</p>
                    </div>
                </div>

                <p>The Chattanooga golf scene offers an extraordinary collection of championship courses, from Pete Dye's masterpiece at <strong>The Honors Course</strong> to the world-renowned <strong>Sweetens Cove Golf Club</strong> and classic Donald Ross design at <strong>Chattanooga Golf & Country Club</strong>. Whether seeking exclusive private club experiences or accessible public championship golf, these five courses represent the pinnacle of southeastern golf excellence in one of America's most scenic regions.</p>

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