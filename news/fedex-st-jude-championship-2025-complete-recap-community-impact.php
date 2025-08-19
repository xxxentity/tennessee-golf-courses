<?php
session_start();
require_once '../config/database.php';

$article_slug = 'fedex-st-jude-championship-2025-complete-recap-community-impact';
$article_title = 'FedEx St. Jude Championship 2025: Complete Tournament Recap and Community Impact';

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
    <title>FedEx St. Jude Championship 2025: Complete Tournament Recap and Community Impact - Tennessee Golf Courses</title>
    <meta name="description" content="Complete recap of the 2025 FedEx St. Jude Championship week featuring Justin Rose's playoff victory and the tournament's extraordinary community impact for St. Jude Children's Research Hospital.">
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
        
        .article-content p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
        }
        
        .article-content h2 {
            font-size: 2rem;
            color: var(--primary-color);
            margin: 2.5rem 0 1.5rem;
            font-weight: 600;
        }
        
        .article-content h3 {
            font-size: 1.5rem;
            color: var(--text-black);
            margin: 2rem 0 1rem;
            font-weight: 600;
        }
        
        .article-content blockquote {
            background: var(--bg-light);
            border-left: 4px solid var(--primary-color);
            padding: 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            border-radius: 8px;
        }
        
        .article-content ul, .article-content ol {
            margin: 1.5rem 0;
            padding-left: 2rem;
        }
        
        .article-content li {
            margin-bottom: 0.5rem;
            line-height: 1.6;
        }
        
        .scoreboard {
            background: var(--bg-light);
            padding: 2rem;
            border-radius: 15px;
            margin: 2rem 0;
        }
        
        .scoreboard-title {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        
        .scoreboard-list {
            list-style: none;
            padding: 0;
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
        
        .st-jude-highlight {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin: 2rem 0;
            text-align: center;
        }
        
        .st-jude-highlight i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: block;
        }
        
        .st-jude-highlight h3 {
            margin: 0.5rem 0 1rem;
            font-size: 1.5rem;
            color: white;
        }
        
        .tournament-timeline {
            background: var(--bg-light);
            padding: 2rem;
            border-radius: 15px;
            margin: 2rem 0;
        }
        
        .timeline-day {
            padding: 1.5rem;
            background: white;
            margin-bottom: 1rem;
            border-radius: 10px;
            border-left: 4px solid var(--primary-color);
        }
        
        .timeline-day h4 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
        }
        
        .community-impact {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin: 2rem 0;
        }
        
        .community-impact h3 {
            color: white;
            margin-bottom: 1rem;
        }
        
        .impact-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        
        .stat-box {
            background: rgba(255,255,255,0.1);
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #ffd700;
            display: block;
        }
        
        .share-section {
            background: var(--bg-white);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .share-title {
            font-size: 1.3rem;
            color: var(--text-black);
            margin-bottom: 1rem;
        }
        
        .share-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
        
        .share-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .share-button.facebook {
            background: #1877f2;
            color: white;
        }
        
        .share-button.twitter {
            background: #000000;
            color: white;
        }
        
        .share-button.email {
            background: var(--primary-color);
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
        
        .login-prompt p {
            margin-bottom: 1rem;
            color: var(--text-gray);
        }
        
        .login-button {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .login-button:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .success-message, .error-message {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            text-align: center;
            font-weight: 500;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
            
            .impact-stats {
                grid-template-columns: 1fr;
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
                    <span class="article-category">Tournament Recap</span>
                    <h1 class="article-title">FedEx St. Jude Championship 2025: Complete Tournament Recap and Community Impact</h1>
                    <div class="article-meta">
                        <span><i class="far fa-calendar"></i> August 11, 2025</span>
                        <span><i class="far fa-clock"></i> 10:00 AM</span>
                        <span><i class="far fa-user"></i> TGC Editorial Team</span>
                    </div>
                </header>
                
                <img src="/images/news/fedex-st-jude-championship-2025-complete-recap-community-impact/main.jpeg" alt="FedEx St. Jude Championship 2025 Complete Recap" class="article-image">
                
                <div class="article-content">
                    <p><strong>MEMPHIS, Tenn.</strong> — As the echoes of cheers from Sunday's dramatic playoff finale fade away from TPC Southwind, the 2025 FedEx St. Jude Championship stands as one of the most compelling and impactful tournaments in recent PGA Tour memory. Beyond Justin Rose's inspiring playoff victory, this week showcased the beautiful intersection of world-class golf and meaningful charitable impact that has defined this event for over five decades.</p>
                    
                    <p>From Akshay Bhatia's opening-round fireworks to Rose's emotional triumph, the tournament delivered four days of unforgettable drama while continuing its legendary partnership with St. Jude Children's Research Hospital. As Memphis bids farewell to another championship week, the lasting impact extends far beyond the golf course and deep into the community that has embraced this event as its own.</p>
                    
                    <div class="st-jude-highlight">
                        <i class="fas fa-heart"></i>
                        <h3>TOURNAMENT FOR A CAUSE</h3>
                        <p>More than $80 million raised since 1970 • Continuing the legacy of hope for children battling cancer</p>
                    </div>
                    
                    <h2>Four Days of Championship Drama</h2>
                    
                    <p>The 2025 FedEx St. Jude Championship provided a masterclass in tournament golf, with each day bringing its own compelling storylines and memorable moments. What began with Akshay Bhatia's spectacular opening statement evolved into one of the most gripping playoffs in recent Tour history.</p>
                    
                    <div class="tournament-timeline">
                        <div class="timeline-day">
                            <h4>Thursday, August 7 - Opening Fireworks</h4>
                            <p>Akshay Bhatia blazed to a career-best 8-under 62, navigating brutal Memphis heat and humidity to seize the first-round lead. The 22-year-old American's bogey-free performance set the tone for a week of spectacular scoring, while bubble players like Rickie Fowler began their crucial campaigns to secure playoff advancement.</p>
                        </div>
                        
                        <div class="timeline-day">
                            <h4>Friday, August 8 - Weather and Fleetwood's Surge</h4>
                            <p>Tommy Fleetwood emerged from Friday's weather-disrupted second round with a commanding four-shot lead at 13-under par. Severe thunderstorms suspended play at 6:15 PM ET, but not before the Englishman delivered another stellar round that positioned him perfectly for his long-awaited first PGA Tour victory attempt.</p>
                        </div>
                        
                        <div class="timeline-day">
                            <h4>Saturday, August 9 - Moving Day Shuffle</h4>
                            <p>Fleetwood weathered a challenging Moving Day to maintain his lead by the narrowest of margins, while Scottie Scheffler's brilliant 65 moved him into prime position just two shots back. The leaderboard compressed dramatically as multiple major champions positioned themselves for Sunday's finale.</p>
                        </div>
                        
                        <div class="timeline-day">
                            <h4>Sunday, August 10 - Playoff Magic</h4>
                            <p>Justin Rose delivered one of the most spectacular closing stretches in FedExCup Playoffs history, rallying with four consecutive birdies before defeating J.J. Spaun in a thrilling three-hole playoff. Tommy Fleetwood's quest for his first Tour victory ended in heartbreak once again, but Rose's triumph provided a perfect cap to an unforgettable week.</p>
                        </div>
                    </div>
                    
                    <blockquote>
                        "This tournament represents everything that's great about professional golf – world-class competition combined with meaningful impact for children who need it most," said Rose after his victory. "To win here, in this setting, for this cause, makes it extra special."
                    </blockquote>
                    
                    <h2>Rose's Remarkable Journey to Victory</h2>
                    
                    <p>At 45 years old, Justin Rose's playoff victory represented far more than just another tournament win. His triumph made him the oldest winner on the PGA Tour this season and marked a remarkable return to golf's elite level after several seasons of inconsistent play.</p>
                    
                    <p>Rose's final-round rally – featuring that incredible four-birdie stretch from holes 14-17 – will be remembered as one of the clutch performances in recent memory. His ability to handle the pressure of the three-hole playoff, making birdie on all three extra holes, demonstrated the mental fortitude that has defined his distinguished career.</p>
                    
                    <p>The victory catapults Rose into the top five of the FedExCup standings and virtually guarantees his return to the Tour Championship for the first time since 2019. More importantly, it validates his belief that age is just a number when combined with dedication and proper preparation.</p>
                    
                    <div class="scoreboard">
                        <h3 class="scoreboard-title">Final Tournament Results</h3>
                        <ul class="scoreboard-list">
                            <li class="scoreboard-item champion">
                                <span class="player-rank">1</span>
                                <span class="player-name">Justin Rose (Playoff Winner)</span>
                                <span class="player-score">-16 ($3.6M)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">2</span>
                                <span class="player-name">J.J. Spaun (Playoff)</span>
                                <span class="player-score">-16 ($2.16M)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T3</span>
                                <span class="player-name">Tommy Fleetwood</span>
                                <span class="player-score">-15 ($1.16M)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T3</span>
                                <span class="player-name">Scottie Scheffler</span>
                                <span class="player-score">-15 ($1.16M)</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">5</span>
                                <span class="player-name">Andrew Novak</span>
                                <span class="player-score">-12 ($800K)</span>
                            </li>
                        </ul>
                    </div>
                    
                    <h2>Fleetwood's Continued Quest</h2>
                    
                    <p>Perhaps no story tugged at the heartstrings more than Tommy Fleetwood's continued pursuit of his first PGA Tour victory. The 34-year-old Englishman's collapse on Sunday – particularly his crucial missed putt at the 17th hole – extended one of golf's most compelling narratives.</p>
                    
                    <p>Fleetwood now has 162 PGA Tour starts without a victory, accumulating seven runner-up finishes along the way. Yet his consistent excellence and gracious demeanor in defeat continue to endear him to fans worldwide. His time will surely come, and when it does, the celebration will be all the sweeter for the journey he's endured.</p>
                    
                    <h2>Bubble Drama and Playoff Advancement</h2>
                    
                    <p>The tournament's conclusion determined which 50 players would advance to next week's BMW Championship, creating intense drama throughout the field. Rickie Fowler provided one of the week's most inspiring performances, playing his way from 64th to 48th in the FedExCup standings with a clutch final round.</p>
                    
                    <p>Jordan Spieth's season ended in heartbreak when his approach shot on the 72nd hole found water, dropping him to 54th in the standings and ending his playoff campaign. The contrasting emotions of advancement and elimination added another layer of compelling drama to an already captivating week.</p>
                    
                    <h2>Extraordinary Community Impact</h2>
                    
                    <p>While the golf provided endless entertainment, the tournament's true legacy lies in its unprecedented impact on the Memphis community and St. Jude Children's Research Hospital. This year marked another milestone in what has become one of professional sports' most meaningful partnerships.</p>
                    
                    <div class="community-impact">
                        <h3><i class="fas fa-hands-helping"></i> Community Impact Highlights</h3>
                        <div class="impact-stats">
                            <div class="stat-box">
                                <span class="stat-number">$80M+</span>
                                <span>Total Raised Since 1970</span>
                            </div>
                            <div class="stat-box">
                                <span class="stat-number">30+</span>
                                <span>St. Jude Patients Participated</span>
                            </div>
                            <div class="stat-box">
                                <span class="stat-number">80%</span>
                                <span>Childhood Cancer Survival Rate</span>
                            </div>
                            <div class="stat-box">
                                <span class="stat-number">0</span>
                                <span>Bills Sent to Families</span>
                            </div>
                        </div>
                        <p>The 2025 championship continued the tournament's tradition of bringing hope to families facing childhood cancer while showcasing the incredible work of St. Jude Children's Research Hospital to a global audience.</p>
                    </div>
                    
                    <h3>St. Jude Patient Participation</h3>
                    
                    <p>One of the tournament's most heartwarming aspects was the extensive involvement of St. Jude patients throughout the week. Nearly 30 young patients participated in various activities, creating unforgettable memories while inspiring everyone they encountered.</p>
                    
                    <p>Among the standout stories was 11-year-old Misheel, who learned to make balloon animals during her seven-month treatment and now uses this skill to brighten the days of fellow patients. Six St. Jude patients served as honorary pin flag caddies for the final groups, greeting players as they completed their rounds on the 18th green.</p>
                    
                    <p>The patient golfers also received behind-the-scenes tours of the TaylorMade equipment truck and were custom-fitted for their own golf clubs – experiences that brought smiles and created lasting memories for children who have already shown incredible courage in their young lives.</p>
                    
                    <h3>Expanding Community Reach</h3>
                    
                    <p>The 2025 championship introduced the Positively Memphis Community Fund, a new initiative providing grants ranging from $1,000 to $25,000 to local nonprofits making meaningful impacts in the Mid-South region. This year's focus on supporting military members, veterans, and first responders demonstrates the tournament's commitment to serving the broader Memphis community.</p>
                    
                    <p>This expansion of the tournament's charitable footprint ensures that the benefits extend beyond St. Jude to touch multiple facets of the local community, creating a ripple effect of positive impact throughout the region.</p>
                    
                    <h2>St. Jude's Mission and Global Impact</h2>
                    
                    <p>The partnership between the PGA Tour and St. Jude Children's Research Hospital represents one of professional sports' most successful charitable collaborations. Since 1970, this Memphis tournament has raised over $80 million for the hospital, directly contributing to groundbreaking research and treatment advances.</p>
                    
                    <p>When St. Jude opened in 1962, childhood cancer was largely considered incurable, with survival rates around 20%. Today, thanks in part to funding from tournaments like the FedEx St. Jude Championship, that survival rate has increased to over 80% in the United States. The hospital's commitment to sharing research freely with institutions worldwide amplifies this impact globally.</p>
                    
                    <blockquote>
                        "The FedEx St. Jude Championship is more than a golf tournament - it's a legacy of partnership, philanthropy and hope that advances the St. Jude mission on a global stage," said Ike Anand, President and Chief Executive Officer at ALSAC, the fundraising organization for St. Jude.
                    </blockquote>
                    
                    <h3>No Family Ever Receives a Bill</h3>
                    
                    <p>Perhaps most importantly, the tournament's fundraising efforts ensure that families never receive a bill from St. Jude for treatment, travel, housing, or food. This policy allows families to focus entirely on what matters most – helping their child fight for their life.</p>
                    
                    <p>The tournament's contribution to St. Jude's ongoing $12.9 billion, six-year strategic plan will help the hospital triple its global investment and impact more of the 400,000 children worldwide who are diagnosed with cancer each year. This represents hope on a scale that extends far beyond Memphis to children and families around the globe.</p>
                    
                    <h2>A Tournament's Legacy</h2>
                    
                    <p>As the 2025 FedEx St. Jude Championship enters the history books, it stands as a testament to what can be achieved when world-class athletic competition combines with meaningful charitable purpose. The week provided everything golf fans could want – dramatic finishes, compelling storylines, breakthrough performances, and heartbreaking near-misses.</p>
                    
                    <p>More importantly, it continued a tradition of giving that has literally saved lives and provided hope to thousands of families facing unimaginable challenges. In an era where professional sports sometimes seem disconnected from their communities, this tournament stands as a shining example of the positive impact that can be achieved.</p>
                    
                    <p>The Memphis community embraced another championship with their characteristic warmth and enthusiasm, while the global golf audience witnessed both spectacular athleticism and meaningful philanthropy. For the children at St. Jude Children's Research Hospital, the tournament represents something even more precious – continued hope for a cure and the promise that they will never face their battle alone.</p>
                    
                    <p>As Justin Rose hoisted the trophy on Sunday evening, surrounded by his family and with St. Jude patients looking on, the moment perfectly encapsulated what makes this tournament special. It's not just about crowning a champion – it's about celebrating the resilience of the human spirit and the power of community to create lasting change.</p>
                    
                    <p>The 2025 FedEx St. Jude Championship will be remembered for Rose's remarkable victory and Fleetwood's continued quest, but its true legacy lies in the hope it provides to children and families who need it most. In a world that often seems divided, this tournament united an entire community around a simple but powerful message: no child should face cancer alone.</p>
                    
                    <p>As the golf world turns its attention to next week's BMW Championship, the impact of this Memphis week will continue to resonate in the halls of St. Jude Children's Research Hospital, where researchers work tirelessly to ensure that one day, no child will die from cancer. That's a victory worth celebrating long after the final putt drops.</p>
                </div>
                
                <div class="share-section">
                    <h3 class="share-title">Share This Article</h3>
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tennesseegolfcourses.com/news/' . $article_slug); ?>" target="_blank" class="share-button facebook">
                            <i class="fab fa-facebook-f"></i> Share on Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($article_title); ?>&url=<?php echo urlencode('https://tennesseegolfcourses.com/news/' . $article_slug); ?>" target="_blank" class="share-button twitter">
                            <strong>𝕏</strong> Share on X
                        </a>
                        <a href="mailto:?subject=<?php echo urlencode($article_title); ?>&body=<?php echo urlencode('Check out this article: https://tennesseegolfcourses.com/news/' . $article_slug); ?>" class="share-button email">
                            <i class="far fa-envelope"></i> Share via Email
                        </a>
                    </div>
                </div>
                
                <div class="comments-section">
                    <h2 class="comments-header">Comments</h2>
                    
                    <?php if ($is_logged_in): ?>
                        <div class="comment-form">
                            <h3>Leave a Comment</h3>
                            <?php if (isset($success_message)): ?>
                                <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
                            <?php endif; ?>
                            <?php if (isset($error_message)): ?>
                                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
                            <?php endif; ?>
                            <form method="POST" action="">
                                <textarea name="comment_text" class="comment-textarea" placeholder="Share your thoughts..." required></textarea>
                                <button type="submit" class="comment-submit">Post Comment</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="login-prompt">
                            <p>Please log in to leave a comment.</p>
                            <a href="/login" class="login-button">Log In</a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="comments-list">
                        <?php if (empty($comments)): ?>
                            <p style="text-align: center; color: var(--text-gray); padding: 2rem;">No comments yet. Be the first to share your thoughts!</p>
                        <?php else: ?>
                            <?php foreach ($comments as $comment): ?>
                                <div class="comment">
                                    <div class="comment-header">
                                        <span class="comment-author"><?php echo htmlspecialchars($comment['username']); ?></span>
                                        <span class="comment-date"><?php echo date('M j, Y \a\t g:i A', strtotime($comment['created_at'])); ?></span>
                                    </div>
                                    <p class="comment-text"><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    
    <script src="/weather.js?v=4"></script>
    <script src="/script.js?v=4"></script>
</body>
</html>