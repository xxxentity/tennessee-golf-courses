<?php
require_once '../includes/init.php';
require_once '../includes/profile-helpers.php';

$article_slug = 'scheffler-extends-lead-open-championship-round-3';
$article_title = 'Scheffler Extends Lead Open Championship Round 3';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheffler Extends Lead to Four Shots with Bogey-Free 67 at Royal Portrush - Tennessee Golf Courses</title>
    <meta name="description" content="Scottie Scheffler fires a bogey-free 67 in Round 3 at Royal Portrush to extend his lead to four shots, while Rory McIlroy charges with eagle on 12th hole.">
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
            font-size: 2.8rem;
            color: var(--text-black);
            margin-bottom: 1.5rem;
            line-height: 1.2;
            font-weight: 700;
        }
        
        .article-meta {
            display: flex;
            gap: 2rem;
            color: var(--text-gray);
            font-size: 0.95rem;
            flex-wrap: wrap;
        }
        
        .article-meta span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .article-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 20px;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-light);
        }
        
        .article-content {
            background: var(--bg-white);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
            margin-bottom: 2rem;
        }
        
        .stat-highlight {
            background: #f0f8f0;
            padding: 1.5rem;
            border-radius: 10px;
            margin: 2rem 0;
            border: 1px solid #4a7c59;
        }
        
        .stat-highlight h4 {
            color: #2c5234;
            margin-bottom: 0.5rem;
        }
        
        .leaderboard {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 10px;
            margin: 2rem 0;
        }
        
        .leaderboard h3 {
            color: #2c5234;
            margin-bottom: 1rem;
        }
        
        .leaderboard-entry {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #eee;
        }
        
        .leaderboard-entry:last-child {
            border-bottom: none;
        }
        
        .leaderboard-entry.leader {
            background: linear-gradient(90deg, #ffd700, transparent);
            margin: 0 -1rem;
            padding: 0.75rem 1rem;
            border-radius: 5px;
            font-weight: 700;
        }
        
        .player-name {
            font-weight: 600;
            color: #2c5234;
        }
        
        .player-score {
            font-weight: 700;
            color: #d32f2f;
        }
        
        .quote-box {
            background: #e8f5e8;
            border-left: 4px solid #4a7c59;
            padding: 1.5rem;
            margin: 2rem 0;
            font-style: italic;
        }
        
        .eagle-box {
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            padding: 1.5rem;
            border-radius: 10px;
            margin: 2rem 0;
            text-align: center;
            color: #2c5234;
            border: 2px solid #e6c200;
        }
        
        .eagle-box h4 {
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
            font-weight: 700;
        }
        
        @media (max-width: 768px) {
            .article-container {
                padding: 1rem;
            }
            
            .article-header, .article-content {
                padding: 1.5rem;
            }
            
            .article-title {
                font-size: 2rem;
            }
            
            .article-image {
                height: 300px;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>

    <div class="article-page">
        <div class="article-container">
            <div class="article-header">
                <span class="article-category">Major Championship</span>
                <h1 class="article-title">Scheffler Extends Lead to Four Shots with Bogey-Free 67</h1>
                <div class="article-meta">
                    <span><i class="fas fa-calendar-alt"></i> July 19, 2025</span>
                    <span><i class="fas fa-user"></i> TGC Editorial Team</span>
                    <span><i class="fas fa-clock"></i> 5 min read</span>
                </div>
            </div>

            <img src="/images/news/open-championship-round-3/scheffler-family.jpg" alt="Scottie Scheffler extends lead at Open Championship" class="article-image">

            <div class="article-content">

        <p>PORTRUSH, Northern Ireland — Scottie Scheffler delivered another clinical display of championship golf on Saturday, firing a bogey-free 4-under 67 to extend his lead to four shots heading into Sunday's final round of the 153rd Open Championship. The world No. 1's third-round masterpiece included an eagle-birdie sequence on holes 7 and 8 that effectively put the Claret Jug within his grasp.</p>

        <div class="stat-highlight">
            <h4><i class="fas fa-chart-line"></i> Scheffler's Saturday Dominance</h4>
            <p><strong>Bogey-Free 67:</strong> Only player in the final eight groups to avoid a bogey. Made eagle on 7th, birdie on 8th, and birdie on the treacherous 16th "Calamity Corner" for the third consecutive day.</p>
        </div>

        <div class="leaderboard">
            <h3><i class="fas fa-trophy"></i> 54-Hole Leaderboard</h3>
            <div class="leaderboard-entry leader">
                <span class="player-name">Scottie Scheffler</span>
                <span class="player-score">-14 (68-64-67)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Haotong Li</span>
                <span class="player-score">-10 (67-67-69)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Matt Fitzpatrick</span>
                <span class="player-score">-9 (67-66-71)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Rory McIlroy</span>
                <span class="player-score">-8 (70-69-66)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Brian Harman</span>
                <span class="player-score">-6 (70-64-70)</span>
            </div>
            <div class="leaderboard-entry">
                <span class="player-name">Tyrrell Hatton</span>
                <span class="player-score">-5 (71-66-68)</span>
            </div>
        </div>

        <p>Playing with the poise of a seasoned major champion, Scheffler navigated Royal Portrush's challenges with remarkable precision. His eagle on the par-5 seventh hole came courtesy of a perfectly struck approach that set up a 10-foot putt, which he rolled home with confidence. Rather than let up, he immediately followed with a 15-foot birdie putt on the eighth, a sequence that showcased the killer instinct that has made him golf's most dominant player.</p>

        <div class="eagle-box">
            <h4><i class="fas fa-trophy"></i> Eagle-Birdie Punch</h4>
            <p>Holes 7-8: Eagle (10-foot putt) followed immediately by birdie (15-foot putt) - a devastating two-hole stretch that effectively put Scheffler in control of the championship.</p>
        </div>

        <p>Perhaps most impressive was Scheffler's performance on the 16th hole, the notorious "Calamity Corner" that has claimed countless victims over the years. For the third consecutive day, he conquered the demanding par-3, hitting a perfectly judged 3-iron to 15 feet below the cup before rolling in yet another birdie putt.</p>

        <div class="quote-box">
            "I need to stay patient and I know what I need to do tomorrow," Scheffler said after his round. "It's just about doing it. Sunday will be a fun and challenging day. It would be fun to win this but that's not what I will be thinking about when I go to sleep tonight or step on the first tee tomorrow. I will just be thinking about trying to execute."
        </div>

        <p>While Scheffler was extending his lead, the day's most electrifying moment belonged to Rory McIlroy. Playing in front of his passionate home supporters, the Northern Irishman provided a moment of magic on the par-5 12th hole, holing a long eagle putt that sent shockwaves through Royal Portrush. The roar from the galleries was deafening as McIlroy pumped his fist, temporarily igniting hopes of a hometown champion.</p>

        <br>

        <p>McIlroy's third-round 66 was a thing of beauty, featuring three early birdies and that memorable eagle. However, even after his impressive charge, he still trails Scheffler by six shots heading into Sunday. "The eagle on 12 was special," McIlroy said. "You could feel the energy from the crowd. I just need to keep pushing tomorrow and see what happens."</p>

        <br>

        <p>China's Haotong Li maintained his position as Scheffler's closest challenger, sitting four shots back after a solid 69. The 29-year-old has shown remarkable composure throughout the week and will tee off alongside Scheffler in Sunday's final group, representing Asia's best hope for a first major championship victory.</p>

        <br>

        <p>Matt Fitzpatrick, the 2022 U.S. Open champion, struggled to keep pace with the leaders, shooting 71 to fall five shots behind. Despite the disappointing round, the Englishman remains within striking distance and knows that Royal Portrush can produce dramatic swings in fortune.</p>

        <div class="stat-highlight">
            <h4><i class="fas fa-history"></i> Historical Context</h4>
            <p>Scheffler enters Sunday with a perfect 9-0 record when holding outright leads after three rounds on the PGA Tour. A victory would give him his first Open Championship and fourth major title, joining elite company before his 30th birthday.</p>
        </div>

        <p>The weather conditions on Saturday were markedly improved from the challenging first two days, with overcast skies giving way to calmer winds and dry conditions. This allowed for better scoring opportunities, though Scheffler was the only player in the late groups to take full advantage with a bogey-free round.</p>

        <br>

        <p>Defending champion Brian Harman had a more challenging day, shooting even-par 71 to fall eight shots behind. The American, who memorably captured his first major at Royal Liverpool last year, will need something extraordinary on Sunday to mount a defense of his title.</p>

        <br>

        <p>As the final round approaches, all eyes will be on Scheffler's pursuit of his first Claret Jug. With his recent track record of converting 54-hole leads and his obvious comfort level at Royal Portrush, the 28-year-old appears destined for glory. However, as Saturday's action proved, this championship still has the potential for dramatic twists and turns.</p>
        
         <br>       

        <p>"Tomorrow is going to be about staying in my process," Scheffler concluded. "I've been in this position before, and I know what it takes. But you still have to go out there and execute. Royal Portrush demands respect, and I'll give it that tomorrow."</p>

        <br>

        <p>Sunday's final round promises to be a compelling conclusion to the 153rd Open Championship, with Scheffler seeking to join the pantheon of Open champions while a talented field behind him hopes to produce one final surge along Northern Ireland's spectacular Causeway Coast.</p>
            </div>
        </div>
    </div>

    <!-- Share Section -->
    <section class="share-section" style="background: var(--bg-white); padding: 2rem; border-radius: 20px; box-shadow: var(--shadow-medium); text-align: center; margin: 2rem auto; max-width: 800px;">
        <h3 style="font-size: 1.3rem; color: var(--text-black); margin-bottom: 1rem;">Share This Article</h3>
        <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/news/' . $article_slug); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #1877f2; color: white;">
                <i class="fab fa-facebook-f"></i> Share on Facebook
            </a>
            <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($article_title); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/news/' . $article_slug); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: #000000; color: white;">
                <strong>𝕏</strong> Share on X
            </a>
            <a href="mailto:?subject=<?php echo urlencode($article_title); ?>&body=<?php echo urlencode('Check out this article: https://tennesseegolfcourses.com/news/' . $article_slug); ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; font-weight: 500; background: var(--primary-color); color: white;">
                <i class="far fa-envelope"></i> Share via Email
            </a>
        </div>
    </section>

    <!-- Comments Section -->
    <section class="comments-section" style="background: #f8f9fa; padding: 4rem 0;">
        <div class="container" style="max-width: 800px; margin: 0 auto; padding: 0 2rem;">
            <div class="section-header" style="text-align: center; margin-bottom: 3rem;">
                <h2 style="color: #2c5234; margin-bottom: 1rem;">Discussion</h2>
                <p>Share your thoughts on this article</p>
            </div>
            
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success" style="background: rgba(34, 197, 94, 0.1); color: #16a34a; padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid rgba(34, 197, 94, 0.2);">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="alert alert-error" style="background: rgba(239, 68, 68, 0.1); color: #dc2626; padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid rgba(239, 68, 68, 0.2);">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <!-- Comment Form (Only for logged in users) -->
            <?php if ($is_logged_in): ?>
                <div class="comment-form-container" style="background: white; padding: 2rem; border-radius: 15px; margin-bottom: 3rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <h3 style="color: #2c5234; margin-bottom: 1.5rem;">Leave a Comment</h3>
                    <form method="POST" class="comment-form">
                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label for="comment_text" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c5234;">Your Comment:</label>
                            <textarea id="comment_text" name="comment_text" rows="4" placeholder="Share your thoughts on this article..." required style="width: 100%; padding: 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-family: inherit; font-size: 14px; resize: vertical; min-height: 100px;"></textarea>
                        </div>
                        <button type="submit" style="background: #2c5234; color: white; padding: 0.75rem 2rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">Post Comment</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="login-prompt" style="background: white; padding: 2rem; border-radius: 15px; text-align: center; margin-bottom: 3rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <p><a href="/login" style="color: #2c5234; font-weight: 600; text-decoration: none;">Login</a> or <a href="/register" style="color: #2c5234; font-weight: 600; text-decoration: none;">Register</a> to join the discussion</p>
                </div>
            <?php endif; ?>
            
            <!-- Display Comments -->
            <div class="comments-container">
                <?php if (empty($comments)): ?>
                    <div class="no-comments" style="text-align: center; padding: 3rem; color: #666;">
                        <i class="fas fa-comments" style="font-size: 3rem; margin-bottom: 1rem; color: #ddd;"></i>
                        <p>No comments yet. Be the first to share your thoughts!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment-card" style="background: white; padding: 2rem; border-radius: 15px; margin-bottom: 2rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                            <div class="comment-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                <div class="commenter-name" style="font-weight: 600; color: #2c5234;"><?php echo htmlspecialchars($comment['username']); ?></div>
                                <div class="comment-date" style="color: #666; font-size: 0.9rem;"><?php echo date('M j, Y • g:i A', strtotime($comment['created_at'])); ?></div>
                            </div>
                            <p style="line-height: 1.6; color: #333;"><?php echo htmlspecialchars($comment['comment_text']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <img src="../images/logos/logo.webp" alt="Tennessee Golf Courses" class="footer-logo-image">
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
                        <li><a href="/#courses">Golf Courses</a></li>
                        <li><a href="/#reviews">Reviews</a></li>
                        <li><a href="/#news">News</a></li>
                        <li><a href="/#about">About Us</a></li>
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
                </div>            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Tennessee Golf Courses. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="../script.js"></script>
    <?php include "../includes/threaded-comments.php"; ?>
    
</body>
</html>