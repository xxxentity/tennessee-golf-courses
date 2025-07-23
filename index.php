<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tennessee Golf Courses - Discover Premier Golf in Tennessee</title>
    <!-- DEPLOYMENT TEST: Git deployment without .cpanel.yml -->
    <meta name="description" content="Discover the best golf courses in Tennessee. Reviews, course information, tee times, and the latest golf news from across the state.">
    <link rel="stylesheet" href="styles.css?v=7">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Weather Bar -->
    <div class="weather-bar">
        <div class="weather-container">
            <div class="weather-info">
                <div class="current-time">
                    <i class="fas fa-clock"></i>
                    <span id="current-time">Loading...</span>
                </div>
                <div class="weather-widget">
                    <i class="fas fa-cloud-sun"></i>
                    <span id="weather-temp">Perfect Golf Weather</span>
                    <span class="weather-location">Nashville, TN</span>
                </div>
            </div>
            <div class="golf-conditions">
                <div class="condition-item">
                    <i class="fas fa-wind"></i>
                    <span>Wind: <span id="wind-speed">5 mph</span></span>
                </div>
                <div class="condition-item">
                    <i class="fas fa-eye"></i>
                    <span>Visibility: <span id="visibility">10 mi</span></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Dynamic Navigation -->
    <?php include 'includes/navigation.php'; ?>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1 class="hero-title">Discover Tennessee's Premier Golf Courses</h1>
            <p class="hero-subtitle">From the Great Smoky Mountains to the Mississippi River, explore the finest golf destinations in the Volunteer State</p>
            <div class="hero-search">
                <div class="search-container">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search courses by name or city..." class="search-input">
                    <button class="search-btn">Find Courses</button>
                </div>
            </div>
        </div>
        <div class="hero-overlay"></div>
    </section>

    <!-- Stats Bar -->
    <section class="stats-bar">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <i class="fas fa-golf-ball"></i>
                    <div class="stat-number">1</div>
                    <div class="stat-label">Featured Course</div>
                </div>
                <div class="stat-item">
                    <i class="fas fa-star"></i>
                    <div class="stat-number">2,000+</div>
                    <div class="stat-label">Reviews</div>
                </div>
                <div class="stat-item">
                    <i class="fas fa-trophy"></i>
                    <div class="stat-number">150+</div>
                    <div class="stat-label">Tournaments</div>
                </div>
                <div class="stat-item">
                    <i class="fas fa-cloud-sun"></i>
                    <div class="stat-number">Live</div>
                    <div class="stat-label">Weather</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Courses -->
    <section id="courses" class="featured-courses">
        <div class="container">
            <div class="section-header">
                <h2>Featured Golf Course</h2>
                <p>Discover premium golf in Tennessee - more courses coming soon</p>
            </div>
            <div class="courses-grid">
                <div class="course-card">
                    <div class="course-image">
                        <img src="/images/courses/bear-trace-harrison-bay/1.jpeg" alt="Bear Trace at Harrison Bay">
                    </div>
                    <div class="course-content">
                        <h3>Bear Trace at Harrison Bay</h3>
                        <p class="course-location"><i class="fas fa-map-marker-alt"></i> Harrison, TN</p>
                        <p class="course-description">Jack Nicklaus Signature Design with stunning lakefront views</p>
                        <div class="course-features">
                            <span class="feature-tag">18 Holes</span>
                            <span class="feature-tag">Par 72</span>
                            <span class="feature-tag">Nicklaus Design</span>
                        </div>
                        <a href="courses/bear-trace-harrison-bay.php" class="btn-primary">View Details</a>
                    </div>
                </div>

            </div>
            <div class="section-footer">
                <button class="btn-secondary">More Courses Coming Soon</button>
            </div>
        </div>
    </section>


    <!-- Latest News -->
    <section id="news" class="latest-news">
        <div class="container">
            <div class="section-header">
                <h2>Latest Golf News</h2>
                <p>Stay updated with the latest happenings in golf worldwide</p>
            </div>
            <div class="news-grid">
                <article class="news-card">
                    <div class="news-image">
                        <img src="/images/news/open-championship-final-2025/scottie-final-round.png" alt="Scottie Scheffler with Claret Jug">
                    </div>
                    <div class="news-content">
                        <div class="news-meta">
                            <span class="news-date">July 21, 2025</span>
                            <span class="news-category">Major Championship</span>
                        </div>
                        <h3>Scheffler Captures First Claret Jug with Dominant Victory</h3>
                        <p>World No. 1 completes commanding four-shot triumph at Royal Portrush to claim his fourth major championship...</p>
                        <a href="news/scheffler-wins-2025-british-open-final-round.php" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </article>

                <article class="news-card">
                    <div class="news-image">
                        <img src="/images/news/open-championship-round-3/scheffler-family.jpg" alt="Scottie Scheffler celebrating with family">
                    </div>
                    <div class="news-content">
                        <div class="news-meta">
                            <span class="news-date">July 19, 2025</span>
                            <span class="news-category">Major Championship</span>
                        </div>
                        <h3>Scheffler Extends Lead to Four Shots with Bogey-Free 67</h3>
                        <p>World No. 1 Scottie Scheffler fires a bogey-free 67 in Round 3 at Royal Portrush to extend his lead to four shots...</p>
                        <a href="news/scheffler-extends-lead-open-championship-round-3.php" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </article>

                <article class="news-card">
                    <div class="news-image">
                        <img src="/images/news/open-championship-round-2/scheffler-64.jpg" alt="Scottie Scheffler during second round">
                    </div>
                    <div class="news-content">
                        <div class="news-meta">
                            <span class="news-date">July 18, 2025</span>
                            <span class="news-category">Major Championship</span>
                        </div>
                        <h3>Scheffler Seizes Control with Career-Best 64</h3>
                        <p>World No. 1 delivers masterclass performance with 7-under 64 to take Open Championship lead at Royal Portrush...</p>
                        <a href="news/scheffler-seizes-control-open-championship-round-2.php" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </article>
            </div>
            <div class="section-footer">
                <button class="btn-secondary">View All News</button>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section id="reviews" class="reviews-section" style="padding: 80px 0; background: var(--bg-light);">
        <div class="container">
            <div class="section-header">
                <h2>What Golfers Are Saying</h2>
                <p>Read reviews from golfers across Tennessee's premier courses</p>
            </div>
            <div style="text-align: center; padding: 60px 0;">
                <i class="fas fa-comments" style="font-size: 48px; color: var(--primary-color); margin-bottom: 20px;"></i>
                <p style="font-size: 18px; color: var(--text-gray);">Course reviews coming soon! Sign up to be notified when reviews go live.</p>
                <a href="#newsletter" class="btn-primary" style="margin-top: 20px;">Get Notified</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section" style="padding: 80px 0; background: var(--bg-white);">
        <div class="container">
            <div class="section-header">
                <h2>About Tennessee Golf Courses</h2>
                <p>Your trusted guide to golf in the Volunteer State</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px; margin-top: 40px;">
                <div style="text-align: center;">
                    <i class="fas fa-map-marked-alt" style="font-size: 48px; color: var(--primary-color); margin-bottom: 20px;"></i>
                    <h3 style="margin-bottom: 15px;">Comprehensive Directory</h3>
                    <p>Discover golf courses across all regions of Tennessee, from Nashville to Memphis, Chattanooga to Knoxville.</p>
                </div>
                <div style="text-align: center;">
                    <i class="fas fa-star" style="font-size: 48px; color: var(--gold-color); margin-bottom: 20px;"></i>
                    <h3 style="margin-bottom: 15px;">Verified Reviews</h3>
                    <p>Read authentic reviews from real golfers to help you choose the perfect course for your game.</p>
                </div>
                <div style="text-align: center;">
                    <i class="fas fa-calendar-check" style="font-size: 48px; color: var(--secondary-color); margin-bottom: 20px;"></i>
                    <h3 style="margin-bottom: 15px;">Easy Booking</h3>
                    <p>Find tee times, contact information, and book your next round at Tennessee's finest courses.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section id="newsletter" class="newsletter">
        <div class="container">
            <div class="newsletter-content">
                <div class="newsletter-text">
                    <h2>Stay in the Loop</h2>
                    <p>Get weekly updates on new courses, exclusive deals, and the latest Tennessee golf news delivered to your inbox.</p>
                </div>
                <div class="newsletter-form">
                    <div class="form-group">
                        <input type="email" placeholder="Enter your email address" class="newsletter-input">
                        <button class="newsletter-btn">Subscribe</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
                        <li><a href="#courses">Golf Courses</a></li>
                        <li><a href="#reviews">Reviews</a></li>
                        <li><a href="#news">News</a></li>
                        <li><a href="#about">About Us</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Regions</h4>
                    <ul>
                        <li><a href="#">Nashville Area</a></li>
                        <li><a href="#">Chattanooga Area</a></li>
                        <li><a href="#">Knoxville Area</a></li>
                        <li><a href="#">Memphis Area</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact</h4>
                    <ul>
                        <li><i class="fas fa-envelope"></i> info@tennesseegolfcourses.com</li>
                        <li><i class="fas fa-phone"></i> (615) 555-GOLF</li>
                        <li><i class="fas fa-map-marker-alt"></i> Nashville, TN</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Tennessee Golf Courses. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="/script.js?v=3"></script>
</body>
</html>