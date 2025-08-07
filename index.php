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
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9809687352497578"
     crossorigin="anonymous"></script>
    
    <style>
        .search-container {
            position: relative;
            z-index: 1000;
        }
        
        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            max-height: 400px;
            overflow-y: auto;
            z-index: 9999;
            display: none;
            margin-top: 4px;
        }
        
        .search-result-item {
            padding: 12px 20px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background 0.2s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .search-result-item:hover {
            background: #f5f5f5;
        }
        
        .search-result-item:last-child {
            border-bottom: none;
        }
        
        .search-result-name {
            font-weight: 600;
            color: #333;
        }
        
        .search-result-location {
            color: #666;
            font-size: 0.9rem;
        }
        
        .search-no-results {
            padding: 20px;
            text-align: center;
            color: #666;
        }
        
        .hero-content {
            position: relative;
            z-index: 100;
        }
        
        .hero-search {
            position: relative;
            z-index: 200;
        }
    </style>
</head>
<body>

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
                    <input type="text" placeholder="Search courses by name or city..." class="search-input" id="hero-search-input">
                    <button class="search-btn">Find Courses</button>
                    <div class="search-results" id="hero-search-results"></div>
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
                    <div class="stat-number">4</div>
                    <div class="stat-label">Featured Courses</div>
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
                <h2>Featured Golf Courses</h2>
                <p>Discover premium golf destinations across Tennessee</p>
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
                        <a href="courses/bear-trace-harrison-bay" class="btn-primary">View Details</a>
                    </div>
                </div>

                <div class="course-card">
                    <div class="course-image">
                        <img src="/images/courses/tpc-southwind/1.jpeg" alt="TPC Southwind">
                    </div>
                    <div class="course-content">
                        <h3>TPC Southwind</h3>
                        <p class="course-location"><i class="fas fa-map-marker-alt"></i> Memphis, TN</p>
                        <p class="course-description">Championship PGA Tour venue with challenging water hazards</p>
                        <div class="course-features">
                            <span class="feature-tag">18 Holes</span>
                            <span class="feature-tag">Par 70</span>
                            <span class="feature-tag">PGA Tour Host</span>
                        </div>
                        <a href="courses/tpc-southwind" class="btn-primary">View Details</a>
                    </div>
                </div>


            </div>
            <div class="section-footer">
                <a href="courses" class="btn-secondary">View All Courses</a>
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
                        <a href="news/scheffler-wins-2025-british-open-final-round" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
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
                        <a href="news/scheffler-extends-lead-open-championship-round-3" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
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
                        <a href="news/scheffler-seizes-control-open-championship-round-2" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
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
                        <input type="email" id="newsletter-email" placeholder="Enter your email address" class="newsletter-input" required>
                        <button type="button" id="newsletter-submit" class="newsletter-btn">Subscribe</button>
                    </div>
                    <div id="newsletter-message" style="margin-top: 12px; padding: 8px; border-radius: 4px; display: none;"></div>
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

    <script src="/script.js?v=4"></script>
    
    <script>
        // Autocomplete functionality for hero search
        const heroSearchInput = document.getElementById('hero-search-input');
        const heroSearchResults = document.getElementById('hero-search-results');
        let searchTimeout;

        heroSearchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                heroSearchResults.style.display = 'none';
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
            heroSearchResults.innerHTML = '';
            
            if (courses.length === 0) {
                heroSearchResults.innerHTML = '<div class="search-no-results">No courses found</div>';
                heroSearchResults.style.display = 'block';
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
                    <i class="fas fa-chevron-right" style="color: #ccc;"></i>
                `;
                
                item.addEventListener('click', () => {
                    window.location.href = `/courses/${course.slug}`;
                });
                
                heroSearchResults.appendChild(item);
            });
            
            heroSearchResults.style.display = 'block';
        }

        // Close results when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.search-container')) {
                heroSearchResults.style.display = 'none';
            }
        });

        // Handle search button click
        document.querySelector('.search-btn').addEventListener('click', function() {
            const query = heroSearchInput.value.trim();
            if (query) {
                window.location.href = `/courses?search=${encodeURIComponent(query)}`;
            }
        });

        // Handle Enter key in search input
        heroSearchInput.addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                const query = this.value.trim();
                if (query) {
                    window.location.href = `/courses?search=${encodeURIComponent(query)}`;
                }
            }
        });
    </script>
</body>
</html>