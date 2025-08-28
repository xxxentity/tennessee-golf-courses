<?php
require_once '../includes/init.php';
require_once '../includes/profile-helpers.php';
require_once '../config/database.php';

$article_slug = 'scheffler-delivers-stunning-comeback-wins-bmw-championship';
$article_title = 'Scheffler Delivers Stunning Comeback to Win BMW Championship';

// Check if user is logged in
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_logged_in) {
    $comment_text = trim($_POST['comment_text']);
    $user_id = $_SESSION['user_id'];
    
    if (!empty($comment_text)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO news_comments (user_id, article_slug, article_title, comment_text) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $article_slug, $article_title, $comment_text]);
            $success_message = "Your comment has been posted successfully!";
        } catch (PDOException $e) {
            $error_message = "Error posting comment. Please try again.";
        }
    } else {
        $error_message = "Please write a comment.";
    }
}

// Get existing comments
try {
    $stmt = $pdo->prepare("
        SELECT nc.*, u.username 
        FROM news_comments nc 
        JOIN users u ON nc.user_id = u.id 
        WHERE nc.article_slug = ? AND nc.is_approved = TRUE
        ORDER BY nc.created_at DESC
    ");
    $stmt->execute([$article_slug]);
    $comments = $stmt->fetchAll();
    
} catch (PDOException $e) {
    $comments = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheffler Delivers Stunning Comeback to Win BMW Championship - Tennessee Golf Courses</title>
    <meta name="description" content="Scottie Scheffler erases four-shot deficit with miraculous chip-in on 17th to defeat Robert MacIntyre and claim fifth victory of 2025 season.">
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
            color: var(--text-black);
            font-size: 2.8rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }
        
        .article-meta {
            display: flex;
            gap: 2rem;
            color: var(--text-gray);
            font-size: 0.9rem;
        }
        
        .article-meta span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .article-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 15px;
            margin-bottom: 3rem;
        }
        
        .article-content {
            background: var(--bg-white);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
            margin-bottom: 2rem;
        }
        
        .article-content p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
        }
        
        .article-content h2 {
            color: var(--primary-color);
            font-size: 1.8rem;
            font-weight: 600;
            margin: 2.5rem 0 1.5rem 0;
            border-left: 4px solid var(--primary-color);
            padding-left: 1rem;
        }
        
        .article-content blockquote {
            background: var(--bg-light);
            border-left: 4px solid var(--gold-color);
            margin: 2rem 0;
            padding: 1.5rem;
            border-radius: 10px;
            font-style: italic;
            color: var(--text-dark);
            font-size: 1.05rem;
        }
        
        .scoreboard {
            background: var(--bg-light);
            padding: 2rem;
            border-radius: 15px;
            margin: 2rem 0;
        }
        
        .scoreboard-title {
            color: var(--primary-color);
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .scoreboard-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .scoreboard-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: white;
            margin-bottom: 0.5rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .scoreboard-item:hover {
            transform: translateX(5px);
            box-shadow: var(--shadow-light);
        }
        
        .scoreboard-item.champion {
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            font-weight: 600;
            border: 2px solid #e6c200;
        }
        
        .player-rank {
            font-weight: 600;
            color: var(--text-gray);
            margin-right: 1rem;
            min-width: 30px;
        }
        
        .player-name {
            flex: 1;
            font-weight: 500;
            color: var(--text-black);
        }
        
        .player-score {
            font-weight: 600;
            color: var(--primary-color);
            margin-left: 1rem;
        }
        
        .chip-in-highlight {
            background: linear-gradient(135deg, #10b981, #047857);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin: 2rem 0;
            text-align: center;
        }
        
        .chip-in-highlight i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .historical-achievement {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin: 2rem 0;
            text-align: center;
        }
        
        .historical-achievement i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .share-section {
            background: var(--bg-white);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .share-title {
            color: var(--text-black);
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .share-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        
        .share-button {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .share-twitter {
            background: #1da1f2;
            color: white;
        }
        
        .share-facebook {
            background: #4267b2;
            color: white;
        }
        
        .share-linkedin {
            background: #0077b5;
            color: white;
        }
        
        .share-button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }
        
        .comments-section {
            background: var(--bg-white);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
            margin-bottom: 2rem;
        }
        
        .comments-header {
            font-size: 1.8rem;
            color: var(--text-black);
            margin-bottom: 2rem;
            font-weight: 600;
        }
        
        .comment-form {
            margin-bottom: 3rem;
            padding: 2rem;
            background: var(--bg-light);
            border-radius: 15px;
        }
        
        .comment-form h3 {
            margin-bottom: 1rem;
            color: var(--primary-color);
        }
        
        .comment-textarea {
            width: 100%;
            min-height: 120px;
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-family: inherit;
            font-size: 1rem;
            resize: vertical;
            transition: border-color 0.3s ease;
        }
        
        .comment-textarea:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        .comment-submit {
            background: var(--primary-color);
            color: white;
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        
        .comment-submit:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .comment {
            padding: 1.5rem;
            background: var(--bg-light);
            border-radius: 15px;
            margin-bottom: 1rem;
        }
        
        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .comment-author {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .comment-date {
            font-size: 0.85rem;
            color: var(--text-gray);
        }
        
        .comment-text {
            color: var(--text-dark);
            line-height: 1.6;
        }
        
        .login-prompt {
            text-align: center;
            padding: 2rem;
            background: var(--bg-light);
            border-radius: 15px;
        }
        
        .login-prompt a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #047857;
            border: 1px solid #10b981;
        }
        
        .alert-error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #ef4444;
        }
        
        @media (max-width: 768px) {
            .article-container {
                padding: 1rem;
            }
            
            .article-header, .article-content, .comments-section {
                padding: 1.5rem;
            }
            
            .article-title {
                font-size: 2rem;
            }
            
            .article-image {
                height: 300px;
            }
            
            .share-buttons {
                flex-direction: column;
            }
            
            .share-button {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/navigation.php'; ?>

    <div class="article-page">
        <div class="article-container">
            <article>
                <header class="article-header">
                    <span class="article-category">Tournament Coverage</span>
                    <h1 class="article-title">Scheffler Delivers Stunning Comeback to Win BMW Championship</h1>
                    <div class="article-meta">
                        <span><i class="far fa-calendar"></i> August 17, 2025</span>
                        <span><i class="far fa-clock"></i> 9:15 PM</span>
                        <span><i class="far fa-user"></i> TGC Editorial Team</span>
                    </div>
                </header>
                
                <img src="/images/news/scheffler-delivers-stunning-comeback-wins-bmw-championship/main.webp" alt="Scottie Scheffler BMW Championship Victory" class="article-image">
                
                <div class="article-content">
                    <p><strong>OWINGS MILLS, Md.</strong> — In a performance that will be remembered as one of the defining moments of his remarkable career, Scottie Scheffler erased a four-shot deficit with ruthless efficiency Sunday afternoon to claim the BMW Championship at Caves Valley Golf Club. The world No. 1's stunning comeback reached its crescendo with a miraculous 81-foot chip-in for birdie on the par-3 17th hole, delivering the knockout blow to Robert MacIntyre's championship hopes and securing his fifth victory of the 2025 season.</p>
                    
                    <p>Scheffler's final-round 67 was a masterclass in championship golf, combining strategic patience with clutch shot-making when it mattered most. By the seventh hole, MacIntyre's seemingly commanding overnight lead had evaporated entirely, with Scheffler seizing control and never relinquishing it as he marched toward a two-shot victory that netted him $3.6 million in prize money and a $5 million FedEx Cup bonus.</p>
                    
                    <div class="chip-in-highlight">
                        <i class="fas fa-magic"></i>
                        <h3>MIRACULOUS CHIP-IN</h3>
                        <p>Scheffler holes 81-foot chip shot from thick rough on 17th hole to seal victory in stunning fashion.</p>
                    </div>
                    
                    <blockquote>
                        "When he's pitched that in on 17 and then he's hit the perfect tee shot on 18, it's pretty much game over just then," MacIntyre said. "You're playing for second place at that point."
                    </blockquote>
                    
                    <h2>MacIntyre's Early Collapse Opens the Door</h2>
                    
                    <p>What had promised to be a coronation for Robert MacIntyre quickly turned into a nightmare scenario as the Scottish star stumbled out of the gate with back-to-back bogeys on the opening two holes. MacIntyre, who had been virtually flawless through three rounds with just one bogey in 54 holes, suddenly found himself battling not only Scheffler but his own nerves as the magnitude of the moment seemed to overwhelm him.</p>
                    
                    <p>The early struggles were symptomatic of a broader pattern that would define MacIntyre's disappointing final round. After making 18 birdies in his first 45 holes of the tournament, the Scotsman managed just two over the final 27 holes, including only one birdie in Sunday's crucial final round until a late rally attempt on the 16th hole.</p>
                    
                    <p>MacIntyre's driving accuracy, which had been a strength throughout the week, deserted him when he needed it most. He hit only one fairway on the front nine, leaving himself in difficult positions and forcing him to play defensively when he needed to maintain his aggressive mindset that had carried him to the 54-hole lead.</p>
                    
                    <p>The third bogey at the fifth hole dropped MacIntyre to 3-over for the day and sent a clear signal to the chasing pack that the door was wide open. For a player of Scheffler's caliber, that was all the invitation he needed to begin his methodical pursuit of his first BMW Championship title.</p>
                    
                    <h2>Scheffler's Relentless Pursuit</h2>
                    
                    <p>While MacIntyre battled his demons, Scottie Scheffler displayed the kind of cold-blooded efficiency that has made him the world's premier player. The American's ability to capitalize on his opponent's mistakes while maintaining his own high standard of play demonstrated why he has been virtually unstoppable in these situations throughout his career.</p>
                    
                    <p>Scheffler's front nine was a study in controlled aggression, as he methodically picked apart the challenging Caves Valley layout while avoiding the big numbers that had plagued MacIntyre. His putting, which had shown brief signs of vulnerability earlier in the week, returned to championship form when it mattered most.</p>
                    
                    <p>The momentum shift was complete by the seventh hole, where Scheffler officially took the lead for the first time in the tournament. From that moment forward, the question was not whether he would win, but by how much, as he continued to separate himself from a field that seemed powerless to match his level of play.</p>
                    
                    <p>Even when Scheffler showed brief moments of vulnerability with bogeys on the 12th and 14th holes, he responded with the kind of resilience that champions possess. Rather than allowing those mistakes to compound, he regrouped and delivered when the tournament was on the line.</p>
                    
                    <div class="scoreboard">
                        <h3 class="scoreboard-title">Final Leaderboard</h3>
                        <ul class="scoreboard-list">
                            <li class="scoreboard-item champion">
                                <span class="player-rank">1</span>
                                <span class="player-name">Scottie Scheffler</span>
                                <span class="player-score">-15</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">2</span>
                                <span class="player-name">Robert MacIntyre</span>
                                <span class="player-score">-13</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">3</span>
                                <span class="player-name">Maverick McNealy</span>
                                <span class="player-score">-11</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T4</span>
                                <span class="player-name">Tommy Fleetwood</span>
                                <span class="player-score">-9</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T4</span>
                                <span class="player-name">Sam Burns</span>
                                <span class="player-score">-9</span>
                            </li>
                        </ul>
                    </div>
                    
                    <h2>The Defining Moment at 17</h2>
                    
                    <p>If there was any doubt about the outcome heading to the 17th tee, Scheffler erased it with one of the most spectacular shots of his career. After MacIntyre had finally shown signs of life with a crucial birdie at the 16th hole to pull within one shot, the stage was set for a dramatic conclusion to what had been a compelling final round.</p>
                    
                    <p>MacIntyre's tee shot at the treacherous par-3 17th looked perfect in flight, drawing toward the flag tucked on the back right corner of the green over water. However, the ball carried just a few yards too far and trickled off the back of the green, leaving him with a delicate chip shot from a difficult lie.</p>
                    
                    <p>Scheffler, who had openly expressed his dislike for the 17th hole earlier in the week, took a much more conservative approach. His tee shot found the thick rough left of the green, a position that would typically be considered unfavorable. What happened next defied logic and expectation.</p>
                    
                    <p>From 81 feet away, standing on an uneven lie in heavy rough, Scheffler executed a chip shot that seemed to have eyes for the hole. The ball rolled across the undulating green, maintaining perfect pace as it tracked toward the cup before disappearing for one of the most improbable birdies in BMW Championship history.</p>
                    
                    <p>"Part of me wanted to go at the pin," Scheffler said afterward. "Anytime you hole a chip like that, it's pretty nice." The understated reaction was vintage Scheffler, but the significance of the moment was not lost on anyone who witnessed it.</p>
                    
                    <div class="historical-achievement">
                        <i class="fas fa-crown"></i>
                        <h3>HISTORIC ACHIEVEMENT</h3>
                        <p>Scheffler becomes first player since Tiger Woods (2006-07) to win 5+ tournaments in consecutive seasons.</p>
                    </div>
                    
                    <h2>Historical Context and Significance</h2>
                    
                    <p>Scheffler's victory carries profound historical significance, as he becomes the first player since Tiger Woods to win at least five tournaments in consecutive PGA Tour seasons. Woods accomplished this feat during his most dominant periods from 1999-2003 and again in 2005-07, cementing Scheffler's place among the game's all-time greats.</p>
                    
                    <p>The achievement represents more than just statistical excellence; it demonstrates a level of sustained dominance that has been rarely seen in professional golf. With five victories already in 2025, including two major championships, Scheffler has established himself as the clear standard-bearer for the current generation of players.</p>
                    
                    <p>His 12 victories over the past two seasons, combined with his consistent excellence in major championships and biggest events, places him in truly elite company. The comparison to Woods' peak years is not made lightly, but Scheffler's body of work increasingly supports such lofty parallels.</p>
                    
                    <p>The BMW Championship victory also secured Scheffler's position atop the FedEx Cup standings heading into next week's Tour Championship, where he will enter with a significant points advantage over his nearest competitors. The $5 million bonus that comes with leading the standings represents just one of many rewards for his exceptional season.</p>
                    
                    <h2>Tour Championship Bubble Drama Reaches Climax</h2>
                    
                    <p>While Scheffler's victory commanded most of the attention, equally compelling drama unfolded throughout the field as players battled for the final spots in the Tour Championship field. Only the top 30 players in FedEx Cup points would advance to East Lake Golf Club, creating intense pressure for those on the bubble.</p>
                    
                    <p>Harry Hall provided one of the tournament's most inspiring storylines, moving from 45th in the standings to secure the 26th position and his first Tour Championship berth. Hall's strong play throughout the week demonstrated the kind of clutch performance required to succeed under extreme pressure.</p>
                    
                    <p>The drama reached its peak with Rickie Fowler's heartbreaking collapse over the final holes. After briefly moving into the 30th position with a birdie on the seventh hole, Fowler suffered a devastating bogey-double bogey finish on holes 14-15 that dropped him to 32nd in the final standings and ended his season.</p>
                    
                    <p>Chris Gotterup's survival at 29th position provided another compelling narrative, as the young professional managed to hold on despite struggling throughout the week. His Scottish Open victory earlier in the season proved to be the lifeline that kept his season alive despite a disappointing showing at Caves Valley.</p>
                    
                    <p>Akshay Bhatia's remarkable hole-in-one on Saturday proved crucial to his Tour Championship qualification, as he claimed the final spot at 30th position. The dramatic finish showcased how every shot can carry season-defining implications in the high-stakes environment of the FedEx Cup Playoffs.</p>
                    
                    <h2>MacIntyre's Silver Lining</h2>
                    
                    <p>Despite the disappointment of letting a commanding lead slip away, Robert MacIntyre's week at the BMW Championship provided numerous positive takeaways that should serve him well moving forward. His runner-up finish earned him $2.16 million and demonstrated his ability to compete at the highest level of professional golf.</p>
                    
                    <p>Perhaps more importantly, MacIntyre's performance through three rounds showcased the kind of sustained excellence that wins major championships. His 194 total through 54 holes represented a career-low mark and suggested that his breakthrough victory is a matter of when, not if.</p>
                    
                    <p>The Scottish star's ability to handle the pressure of leading a high-profile tournament, including the crowd dynamics and media attention, demonstrated significant personal growth from earlier in his career. While Sunday's result was disappointing, the experience should prove invaluable in future opportunities.</p>
                    
                    <p>MacIntyre's European Ryder Cup position was also solidified by his strong showing, as he continued to build momentum heading into the team competition at Bethpage Black. His ability to perform under pressure will be crucial in the match-play format.</p>
                    
                    <h2>Looking Ahead to East Lake</h2>
                    
                    <p>As the BMW Championship concluded with Scheffler hoisting the trophy, attention immediately turned to next week's Tour Championship at East Lake Golf Club in Atlanta. The season-ending event will feature the top 30 players competing for the FedEx Cup title and its $25 million first prize.</p>
                    
                    <p>Scheffler enters the Tour Championship with a commanding points advantage that will be difficult for any player to overcome. His consistent excellence throughout the season has positioned him as the overwhelming favorite to claim his second FedEx Cup title.</p>
                    
                    <p>However, the unique format of the Tour Championship, which features starting strokes based on FedEx Cup positioning, ensures that multiple players will have realistic chances to claim the season-long title. Players like Rory McIlroy, Jon Rahm, and Viktor Hovland will all enter with mathematical possibilities of overtaking Scheffler.</p>
                    
                    <p>The field that has qualified for East Lake represents the cream of professional golf, with major champions, Ryder Cup stars, and rising talents all competing for the sport's most lucrative individual prize. The stage is set for a compelling conclusion to what has been an exceptional season of golf.</p>
                    
                    <blockquote>
                        "Obviously it's disappointing not to get the win, but I can take a lot of positives from this week," MacIntyre said. "I know I can compete at this level, and hopefully next time I'll be able to close it out."
                    </blockquote>
                    
                    <h2>The Scheffler Standard</h2>
                    
                    <p>Scottie Scheffler's victory at the BMW Championship represents more than just another win in an already remarkable season. It demonstrates a level of mental toughness and clutch performance that separates truly great players from merely very good ones.</p>
                    
                    <p>His ability to remain calm under pressure, execute difficult shots in crucial moments, and capitalize on opponents' mistakes has become the standard by which current players are measured. The chip-in at the 17th hole will be remembered as one of the defining shots of the 2025 season.</p>
                    
                    <p>As Scheffler prepares for the Tour Championship with momentum firmly on his side, the rest of the professional golf world continues to chase a player who seems to reach new heights with each passing tournament. His combination of physical talent, mental strength, and competitive fire has established him as the clear leader of his generation.</p>
                    
                    <p>The BMW Championship victory serves as yet another reminder that in golf's biggest moments, Scottie Scheffler has become the player that others measure themselves against. His pursuit of greatness continues to elevate the entire sport and provides inspiration for future generations of golfers.</p>
                </div>
                
                <div class="share-section">
                    <h3 class="share-title">Share This Story</h3>
                    <div class="share-buttons">
                        <a href="https://twitter.com/intent/tweet?text=Scheffler%20Delivers%20Stunning%20Comeback%20to%20Win%20BMW%20Championship&url=https://tennesseegolfcourses.com/news/scheffler-delivers-stunning-comeback-wins-bmw-championship" class="share-button share-twitter" target="_blank">
                            <i class="fab fa-twitter"></i>
                            Tweet
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=https://tennesseegolfcourses.com/news/scheffler-delivers-stunning-comeback-wins-bmw-championship" class="share-button share-facebook" target="_blank">
                            <i class="fab fa-facebook-f"></i>
                            Share
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=https://tennesseegolfcourses.com/news/scheffler-delivers-stunning-comeback-wins-bmw-championship" class="share-button share-linkedin" target="_blank">
                            <i class="fab fa-linkedin-in"></i>
                            Share
                        </a>
                    </div>
                </div>
            </article>
            
            <section class="comments-section">
                <h2 class="comments-header"><i class="fas fa-comments"></i> Discussion</h2>
                
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-error"><?php echo $error_message; ?></div>
                <?php endif; ?>
                
                <?php if ($is_logged_in): ?>
                    <div class="comment-form">
                        <h3>Join the Discussion</h3>
                        <form method="POST">
                            <textarea name="comment_text" class="comment-textarea" placeholder="Share your thoughts on Scheffler's miraculous comeback victory..." required></textarea>
                            <button type="submit" class="comment-submit">
                                <i class="fas fa-paper-plane"></i> Post Comment
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="login-prompt">
                        <p><strong>Join the conversation!</strong> <a href="/login">Login</a> or <a href="/register">create an account</a> to share your thoughts on this incredible championship finish.</p>
                    </div>
                <?php endif; ?>
                
                <div class="comments-list">
                    <?php if (!empty($comments)): ?>
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment">
                                <div class="comment-header">
                                    <span class="comment-author"><?php echo htmlspecialchars($comment['username']); ?></span>
                                    <span class="comment-date"><?php echo date('M j, Y g:i A', strtotime($comment['created_at'])); ?></span>
                                </div>
                                <div class="comment-text"><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="text-align: center; color: var(--text-gray); font-style: italic;">Be the first to comment on this story!</p>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
    
    <?<?
    
    <?php include '../includes/threaded-comments.php'; ?>
    <?php include '../includes/footer.php'; ?>
</body>
</html>