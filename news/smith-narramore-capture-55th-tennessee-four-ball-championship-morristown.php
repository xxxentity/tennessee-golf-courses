<?php
require_once '../includes/init.php';
require_once '../includes/profile-helpers.php';

$article = [
    'title' => 'Smith and Narramore Capture 55th Tennessee Four-Ball Championship at The Country Club',
    'slug' => 'smith-narramore-capture-55th-tennessee-four-ball-championship-morristown',
    'date' => '2025-08-23',
    'time' => '9:15 PM',
    'category' => 'Tennessee News',
    'excerpt' => 'Jack Smith and Chas Narramore defeated Lawrence Largent and Jack Rhea 4 and 3 in the championship match at The Country Club in Morristown, with Smith claiming his third state title.',
    'image' => '/images/news/smith-narramore-capture-55th-tennessee-four-ball-championship-morristown/main.webp',
    'featured' => true,
    'author' => 'TGC Editorial Team',
    'read_time' => '4 min read'
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
    
    <?php include '../includes/favicon.php'; ?>
    
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
            background: var(--bg-white);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
            margin-bottom: 2rem;
        }
        
        .article-category {
            display: inline-block;
            background: var(--secondary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }
        
        .article-title {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            line-height: 1.3;
        }
        
        .article-meta {
            display: flex;
            align-items: center;
            gap: 2rem;
            color: var(--text-gray);
            font-size: 0.95rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        
        .article-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .article-featured-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
            object-position: center;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-small);
        }
        
        .article-content {
            background: var(--bg-white);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
        }
        
        .article-content p {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 1.5rem;
            color: var(--text-gray);
        }
        
        .article-content h2 {
            font-size: 2rem;
            color: var(--primary-color);
            margin: 2.5rem 0 1.5rem;
        }
        
        .article-content h3 {
            font-size: 1.5rem;
            color: var(--text-dark);
            margin: 2rem 0 1rem;
        }
        
        .article-content blockquote {
            border-left: 4px solid var(--secondary-color);
            padding-left: 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            font-size: 1.2rem;
            color: var(--text-dark);
        }
        
        .match-result {
            background: var(--bg-light);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 2rem;
            margin: 2rem 0;
            text-align: center;
        }
        
        .champions {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: white;
            border: none;
        }
        
        .match-score {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
            margin: 1rem 0;
        }
        
        .champions .match-score {
            color: white;
        }
        
        .course-link {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .course-link:hover {
            color: var(--primary-color);
        }
        
        .back-to-news {
            margin-bottom: 2rem;
        }
        
        .back-to-news a {
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .back-to-news a:hover {
            color: var(--secondary-color);
            gap: 0.75rem;
        }
        
        @media screen and (max-width: 768px) {
            .article-title {
                font-size: 2rem;
            }
            
            .article-header {
                padding: 2rem;
            }
            
            .article-content {
                padding: 2rem;
            }
            
            .article-container {
                padding: 1rem;
            }
            
            .match-score {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include '../includes/navigation.php'; ?>

    <div class="article-page">
        <div class="article-container">
            <!-- Back to News -->
            <div class="back-to-news">
                <a href="/news">
                    <i class="fas fa-arrow-left"></i>
                    Back to News
                </a>
            </div>

            <!-- Article Header -->
            <div class="article-header">
                <span class="article-category"><?php echo htmlspecialchars($article['category']); ?></span>
                <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>
                
                <div class="article-meta">
                    <div class="article-meta-item">
                        <i class="fas fa-user"></i>
                        <span><?php echo htmlspecialchars($article['author']); ?></span>
                    </div>
                    <div class="article-meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span><?php echo date('F j, Y', strtotime($article['date'])); ?> at <?php echo $article['time']; ?></span>
                    </div>
                    <div class="article-meta-item">
                        <i class="fas fa-clock"></i>
                        <span><?php echo $article['read_time']; ?></span>
                    </div>
                </div>
                
                <p style="font-size: 1.2rem; color: var(--text-dark); margin: 0;">
                    <?php echo htmlspecialchars($article['excerpt']); ?>
                </p>
            </div>

            <!-- Featured Image -->
            <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="Tennessee Four-Ball Championship at The Country Club" class="article-featured-image">

            <!-- Article Content -->
            <div class="article-content">
                <p>
                    <strong>MORRISTOWN, Tenn.</strong> — Jack Smith and Chas Narramore delivered a commanding performance in the championship match of the 55th Tennessee Four-Ball Championship, defeating Lawrence Largent and Jack Rhea 4 and 3 at <a href="/courses/the-country-club-morristown" class="course-link">The Country Club</a> in Morristown.
                </p>

                <div class="match-result champions">
                    <h3 style="color: white; margin-top: 0;">2025 Tennessee Four-Ball Champions</h3>
                    <div class="match-score">Jack Smith & Chas Narramore</div>
                    <p style="color: white; margin-bottom: 0;">Defeated Largent & Rhea, 4 and 3</p>
                </div>

                <p>
                    The victory marks Smith's third Tennessee Four-Ball Championship title, while Narramore claimed his first Tennessee Golf Association victory since winning the Tennessee Junior Amateur in 2004. The duo entered the championship as the 11th seed but proved dominant throughout match play competition.
                </p>

                <h2>Championship Match Drama</h2>

                <p>
                    Smith and Narramore found themselves in early trouble, trailing by one hole through the first four holes of the championship match. However, the pair displayed the resilience that carried them through the tournament, mounting a spectacular comeback that would define the match.
                </p>

                <p>
                    The turning point came when both players began finding their rhythm with a series of crucial birdies. Smith and Narramore won the final three holes with birdies, showcasing the teamwork and precision that made them champions.
                </p>

                <blockquote>
                    "Winning a state title is pretty meaningful," Smith reflected after the victory.
                </blockquote>

                <p>
                    For Narramore, the team format proved to be the perfect fit for his playing style and competitive nature.
                </p>

                <blockquote>
                    "I'm a team player, which is why I like four-balls," Narramore explained, highlighting the collaborative aspect that made their partnership so effective.
                </blockquote>

                <h2>Road to the Championship</h2>

                <p>
                    Both Smith and Narramore advanced through competitive semifinal matches to reach the championship. Smith and Narramore defeated the team of Cody Johnson and Andrew Hall, while Largent and Rhea earned their spot in the final by defeating Cash Hendon and Alex Hamm.
                </p>

                <p>
                    The tournament showcased the depth of talent in Tennessee amateur golf, with strong team play and competitive match play across multiple rounds leading up to the championship finale.
                </p>

                <h2>Historic Venue</h2>

                <p>
                    <a href="/courses/the-country-club-morristown" class="course-link">The Country Club</a> in Morristown provided an excellent backdrop for the championship, with its challenging layout testing every aspect of the competitors' games throughout the week-long competition.
                </p>

                <p>
                    The 55th Tennessee Four-Ball Championship continues the rich tradition of amateur golf in Tennessee, bringing together the state's top golfers in a format that emphasizes both individual skill and team strategy. Smith and Narramore's victory adds another memorable chapter to the tournament's distinguished history.
                </p>
            </div>
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
                        <li><a href="/courses">Courses</a></li>
                        <li><a href="/reviews">Reviews</a></li>
                        <li><a href="/news">News</a></li>
                        <li><a href="/events">Events</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Regions</h4>
                    <ul>
                        <li><a href="courses?region=Nashville Area">Nashville Area</a></li>
                        <li><a href="courses?region=Chattanooga Area">Chattanooga Area</a></li>
                        <li><a href="courses?region=Knoxville Area">Knoxville Area</a></li>
                        <li><a href="courses?region=Memphis Area">Memphis Area</a></li>
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
    <?
    
    <?php include '../includes/threaded-comments.php'; ?>
    <?php include '../includes/footer.php'; ?>
</body>
</html>