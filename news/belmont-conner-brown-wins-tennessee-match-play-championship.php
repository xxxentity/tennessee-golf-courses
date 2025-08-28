<?php
require_once '../includes/init.php';
require_once '../includes/profile-helpers.php';

$article_slug = 'belmont-conner-brown-wins-tennessee-match-play-championship';
$article_title = 'Belmont's Conner Brown Captures First TGA Title at Tennessee Match Play Championship';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Belmont's Conner Brown Captures First TGA Title at Tennessee Match Play Championship - Tennessee Golf Courses</title>
    <meta name="description" content="Shelbyville native Conner Brown defeats Trenton Johnson 4 & 3 to win his first Tennessee Golf Association title at the 26th Tennessee Match Play Championship at Vanderbilt Legends Club.">
    <link rel="stylesheet" href="/styles.css?v=5">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="/images/logos/tab-logo.webp?v=5">
    <link rel="shortcut icon" href="/images/logos/tab-logo.webp?v=5">
    
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
            background: var(--primary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }
        
        .article-title {
            font-size: 2.2rem;
            color: var(--text-dark);
            margin-bottom: 1rem;
            font-weight: 700;
            line-height: 1.3;
        }
        
        .article-meta {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            color: var(--text-gray);
            font-size: 0.95rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        
        .article-image {
            width: 100%;
            max-width: 800px;
            height: 400px;
            object-fit: cover;
            border-radius: 15px;
            margin: 2rem 0;
            box-shadow: var(--shadow-light);
        }
        
        .article-content {
            background: var(--bg-white);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
            margin-bottom: 2rem;
            line-height: 1.8;
            font-size: 1.1rem;
        }
        
        .article-content p {
            margin-bottom: 1.5rem;
        }
        
        .article-content h3 {
            color: var(--primary-color);
            margin: 2rem 0 1rem 0;
            font-size: 1.4rem;
        }
        
        .highlight-box {
            background: var(--bg-light);
            padding: 2rem;
            border-radius: 15px;
            border-left: 5px solid var(--primary-color);
            margin: 2rem 0;
        }
        
        .quote {
            font-style: italic;
            font-size: 1.2rem;
            color: var(--primary-color);
            border-left: 4px solid var(--secondary-color);
            padding-left: 1.5rem;
            margin: 2rem 0;
        }
        
        .comments-section {
            background: var(--bg-white);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            margin-top: 2rem;
        }
        
        .comments-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .comment-form {
            background: var(--bg-light);
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        
        .comment-form textarea {
            width: 100%;
            min-height: 100px;
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-family: inherit;
            resize: vertical;
            margin-bottom: 1rem;
        }
        
        .comment-form textarea:focus {
            border-color: var(--primary-color);
            outline: none;
        }
        
        .comment-form button {
            background: var(--primary-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
        }
        
        .comment-form button:hover {
            background: var(--secondary-color);
        }
        
        .comment {
            background: var(--bg-light);
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        
        .comment-author {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .comment-date {
            font-size: 0.9rem;
            color: var(--text-gray);
            margin-bottom: 0.5rem;
        }
        
        .login-prompt {
            text-align: center;
            padding: 2rem;
            color: var(--text-gray);
        }
        
        .login-prompt a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-prompt a:hover {
            text-decoration: underline;
        }
        
        @media screen and (max-width: 768px) {
            .article-title {
                font-size: 1.8rem;
            }
            
            .article-container {
                padding: 1rem;
            }
            
            .article-header, .article-content {
                padding: 2rem;
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
                <div class="article-category">Tennessee News</div>
                <h1 class="article-title">Belmont's Conner Brown Captures First TGA Title at Tennessee Match Play Championship</h1>
                <div class="article-meta">
                    <span><i class="far fa-calendar"></i> August 19, 2025</span>
                    <span><i class="far fa-clock"></i> 11:30 AM</span>
                    <span><i class="far fa-user"></i> TGC Editorial Team</span>
                    <span><i class="far fa-map-marker-alt"></i> Franklin, TN</span>
                </div>
            </header>

            <!-- Article Image -->
            <img src="/images/news/belmont-conner-brown-wins-tennessee-match-play-championship/main.webp" alt="Conner Brown celebrates his Tennessee Match Play Championship victory at Vanderbilt Legends Club" class="article-image">

            <!-- Article Content -->
            <article class="article-content">
                <p><strong>FRANKLIN, Tenn.</strong> – Belmont University golfer Conner Brown achieved a career milestone this week, capturing his first Tennessee Golf Association title at the 26th Tennessee Match Play Championship held at Vanderbilt Legends Club's South Course.</p>

                <p>The Shelbyville, Tennessee native defeated Trenton Johnson by a decisive 4 & 3 margin in Friday's championship match, closing out the victory on the 15th hole without needing to play the final three holes. The triumph marked Brown's first TGA championship in what has been an impressive collegiate career for the Belmont Bruins golfer.</p>

                <h3>Championship Match Details</h3>

                <p>The tournament, which ran from August 12-15, featured the state's top amateur golfers competing on Vanderbilt Legends Club's challenging South Course layout. The 7,100-yard, par-71 course designed by Bob Cupp and Tom Kite provided a stern test throughout the week, but Brown navigated the conditions expertly to reach the championship match.</p>

                <div class="highlight-box">
                    <p><strong>Tournament Facts:</strong> The Tennessee Match Play Championship was held at Vanderbilt Legends Club's South Course in Franklin, featuring match play format over four days. Brown's victory came on the 15th hole, marking his first Tennessee Golf Association title.</p>
                </div>

                <p>Brown's path to the championship demonstrated consistent excellence throughout the week. The 18th hole at Vanderbilt Legends Club's South Course had become a place of comfort for Brown during the tournament, serving as the backdrop for several memorable moments. However, his dominance in the final match meant he never needed to reach the closing hole against Johnson.</p>

                <h3>Belmont University Connection</h3>

                <p>As a key member of the Belmont University men's golf team, Brown brought his collegiate experience to bear in the championship match. During the 2024-25 season, he competed in 30 rounds across 11 events and ranked second on the team in scoring average, showcasing the consistency that served him well in match play format.</p>

                <p>Earlier in 2025, Brown demonstrated his abilities on the national stage when he led Belmont after shooting a 71 in the opening round of The Peoples Championship in March, further establishing his credentials as one of Tennessee's top collegiate golfers.</p>

                <div class="quote">
                    "This victory represents the culmination of years of hard work and dedication to competitive golf. Capturing a Tennessee Golf Association title is a significant achievement for any amateur golfer in the state."
                </div>

                <h3>Tournament Significance</h3>

                <p>The Tennessee Match Play Championship stands as one of 21 TGA Championships scheduled for 2025, representing one of the state's most prestigious amateur golf competitions. The match play format tests players' strategic thinking and mental toughness differently than stroke play events, requiring adaptability and competitive fire throughout each match.</p>

                <p>Vanderbilt Legends Club provided an ideal venue for the championship, with its South Course offering a challenging yet fair test of golf. The Franklin facility has hosted numerous important championships throughout its history, adding to the prestige of Brown's victory.</p>

                <p>Brown's triumph not only marks a personal milestone but also highlights the strength of Tennessee collegiate golf programs. His success adds to the growing list of achievements by Tennessee-based golfers on both the amateur and professional levels throughout 2025.</p>

                <p>The victory positions Brown well for future competitive opportunities and demonstrates the high level of play being produced by Tennessee's golf programs. As he continues his collegiate career at Belmont University, this TGA title serves as validation of his potential and competitive abilities at the highest amateur levels.</p>
            </article>

            <!-- Comments Section -->
            <section class="comments-section">
                <div class="comments-header">
                    <i class="fas fa-comments"></i>
                    <span>Comments (<?php echo count($comments); ?>)</span>
                </div>

                <?php if (isset($success_message)): ?>
                    <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <?php echo htmlspecialchars($success_message); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($error_message)): ?>
                    <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <?php if ($is_logged_in): ?>
                    </article><?php include '../includes/threaded-comments.php'; ?>
    <?php include '../includes/footer.php'; ?>
</body>
</html>