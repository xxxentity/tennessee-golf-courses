<?php
require_once '../includes/init.php';
require_once '../includes/profile-helpers.php';
require_once '../config/database.php';

$article_slug = 'macintyre-weathers-moving-day-storm-maintains-bmw-championship-lead';
$article_title = 'MacIntyre Weathers Moving Day Storm to Maintain BMW Championship Lead';

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
    <title>MacIntyre Weathers Moving Day Storm to Maintain BMW Championship Lead - Tennessee Golf Courses</title>
    <meta name="description" content="Robert MacIntyre overcomes hostile crowd and brutal pin placements to maintain four-shot lead over Scottie Scheffler after BMW Championship third round.">
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
        
        .crowd-alert {
            background: linear-gradient(135deg, #dc2626, #991b1b);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin: 2rem 0;
            text-align: center;
        }
        
        .crowd-alert i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .hole-in-one-highlight {
            background: linear-gradient(135deg, #10b981, #047857);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin: 2rem 0;
            text-align: center;
        }
        
        .hole-in-one-highlight i {
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
                    <h1 class="article-title">MacIntyre Weathers Moving Day Storm to Maintain BMW Championship Lead</h1>
                    <div class="article-meta">
                        <span><i class="far fa-calendar"></i> August 16, 2025</span>
                        <span><i class="far fa-clock"></i> 8:45 PM</span>
                        <span><i class="far fa-user"></i> TGC Editorial Team</span>
                    </div>
                </header>
                
                <img src="/images/news/macintyre-weathers-moving-day-storm-maintains-bmw-championship-lead/main.webp" alt="Robert MacIntyre BMW Championship Third Round" class="article-image">
                
                <div class="article-content">
                    <p><strong>OWINGS MILLS, Md.</strong> — In a tension-filled Moving Day that felt more like a Ryder Cup preview than a regular Tour event, Robert MacIntyre displayed remarkable composure and grit to maintain his commanding position atop the BMW Championship leaderboard. The Scottish star weathered hostile crowd reactions, brutal pin placements, and relentless pressure from world No. 1 Scottie Scheffler to shoot 2-under 68 and preserve a four-shot lead heading into Sunday's finale at Caves Valley Golf Club.</p>
                    
                    <p>MacIntyre's third-round performance was defined not by the low scoring that had characterized his first two days, but by mental fortitude and clutch putting under extreme pressure. Playing in the final pairing alongside Scheffler, the 29-year-old from Oban faced increasingly vocal crowd support for his American playing partner while navigating what he described as "absolutely brutal" pin placements that tested every aspect of his game.</p>
                    
                    <div class="crowd-alert">
                        <i class="fas fa-volume-up"></i>
                        <h3>CROWD CONFRONTATION</h3>
                        <p>MacIntyre responds to hostile fan with iconic "shush" gesture after crucial par save on 14th hole.</p>
                    </div>
                    
                    <blockquote>
                        "I totally expected to be in this situation today when I'm in this position," MacIntyre said after his round. "It's going to be the exact same tomorrow. I'll give as good back as I get."
                    </blockquote>
                    
                    <h2>The Defining Moment at 14</h2>
                    
                    <p>The tournament's most memorable moment came at the par-4 14th hole, where MacIntyre faced a crucial seven-foot par putt after Scheffler had just made birdie to cut the lead to three shots. As the Scottish golfer stood over his putt, a fan shouted that he had missed it, creating exactly the kind of distraction that can derail championship hopes.</p>
                    
                    <p>Instead, MacIntyre calmly rolled the putt into the heart of the cup, then turned toward the offending spectator and delivered a defiant "shush" gesture with his index finger to his lips, followed by an emphatic fist pump that sent a clear message about his mental state.</p>
                    
                    <p>"He was just shouting I missed it, he's pushed it," MacIntyre explained with a wry smile. "Pushed it right in the middle of the hole, I guess." The moment encapsulated not just MacIntyre's confidence, but his willingness to embrace the role of visiting villain in what had become an increasingly partisan atmosphere.</p>
                    
                    <p>The crowd reaction had been building throughout the day, with MacIntyre hearing supportive chants for Scheffler from the moment he walked from the driving range to the first tee. Rather than being intimidated, the Scottish star seemed to feed off the energy, using it as fuel for what would prove to be his most mentally demanding round of the week.</p>
                    
                    <h2>Brutal Pin Placements Test the Field</h2>
                    
                    <p>Saturday's third round featured the most challenging pin positions of the week, with tournament organizers setting up Caves Valley to test every aspect of the players' games. MacIntyre was particularly vocal about the difficulty, describing the pin placements as creating nearly impossible scoring opportunities.</p>
                    
                    <p>"The pins were absolutely brutal," MacIntyre said. "You want to be underneath the holes, and where they cut the pins today, you just couldn't get underneath them and you were standing there with 15 feet coming down a steep slope, a lot of time with a lot of break."</p>
                    
                    <p>The opening hole provided a perfect example of the day's challenges. MacIntyre's approach shot found a greenside bunker, leaving him with a delicate chip to a pin position that he estimated was "on 4 degrees of slope." From 15 feet, the AimPoint reading showed he needed to aim a full three feet outside the hole, demonstrating the precision required to score on such challenging pin positions.</p>
                    
                    <p>Scheffler echoed MacIntyre's assessment, noting that the first hole pin was "on quite a bit of slope" and represented the kind of setup that separated championship-level players from the rest of the field. The challenging conditions would prove to be a preview of what both players could expect in Sunday's final round.</p>
                    
                    <div class="scoreboard">
                        <h3 class="scoreboard-title">Leaderboard After Third Round</h3>
                        <ul class="scoreboard-list">
                            <li class="scoreboard-item">
                                <span class="player-rank">1</span>
                                <span class="player-name">Robert MacIntyre</span>
                                <span class="player-score">-16</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">2</span>
                                <span class="player-name">Scottie Scheffler</span>
                                <span class="player-score">-12</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">3</span>
                                <span class="player-name">Ludvig Åberg</span>
                                <span class="player-score">-10</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T4</span>
                                <span class="player-name">Sam Burns</span>
                                <span class="player-score">-9</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T4</span>
                                <span class="player-name">Harry Hall</span>
                                <span class="player-score">-9</span>
                            </li>
                        </ul>
                    </div>
                    
                    <h2>Scheffler's Relentless Pursuit</h2>
                    
                    <p>While MacIntyre battled the course and crowd, Scottie Scheffler continued to demonstrate why he's the world's top-ranked player with a steady 3-under 67 that kept him within striking distance. The reigning Masters champion's round featured four birdies against just one bogey, with his only significant mistake coming when he left his approach shot in the bunker short of the 12th green.</p>
                    
                    <p>Scheffler's performance was marked by his characteristic patience and precision, as he methodically worked his way around the challenging setup while remaining largely oblivious to the crowd dynamics that were affecting his playing partner. The American's ability to stay focused on his own game while in contention showcased the mental strength that has defined his remarkable season.</p>
                    
                    <p>"I didn't see any of that," Scheffler said regarding the crowd incident at the 14th hole. "People have a tendency to say things that are dumb. If you're a fan, it's only going to fire the guy up more." His mature perspective on the situation demonstrated the kind of professionalism that has made him such a respected figure in professional golf.</p>
                    
                    <p>The final pairing dynamic created compelling theater throughout the afternoon, with both players trading momentum shifts and demonstrating different approaches to handling pressure. While MacIntyre embraced the confrontational aspects of the situation, Scheffler remained in his own bubble, focused solely on executing his game plan.</p>
                    
                    <h2>The Crucial 18th Hole Rally</h2>
                    
                    <p>Just when it appeared that Scheffler might be gaining serious momentum, MacIntyre delivered the day's most crucial shot with a spectacular 41-foot birdie putt on the 18th green. The lengthy putt not only restored his four-shot advantage but sent a powerful message about his mental state heading into the final round.</p>
                    
                    <p>The putt was vintage MacIntyre, combining his exceptional putting ability with perfect timing under pressure. As the ball rolled toward the hole, the Scottish star could sense its importance, delivering an emphatic fist pump when it dropped that rivaled any celebration from his career.</p>
                    
                    <p>"That putt on 18 was massive," MacIntyre acknowledged. "You could feel the momentum in the match, and to be able to extend the lead back to four shots going into tomorrow gives me a lot of confidence." The birdie capped what had been a masterful display of mental toughness on one of the most challenging days of his professional career.</p>
                    
                    <div class="hole-in-one-highlight">
                        <i class="fas fa-bullseye"></i>
                        <h3>BHATIA'S MAGICAL MOMENT</h3>
                        <p>Akshay Bhatia makes first PGA Tour hole-in-one on 17th hole, winning BMW and boosting Tour Championship hopes.</p>
                    </div>
                    
                    <h2>Bhatia's Tournament-Defining Ace</h2>
                    
                    <p>While MacIntyre and Scheffler commanded most of the attention, the day's most spectacular single shot belonged to Akshay Bhatia, who recorded his first PGA Tour hole-in-one on the 227-yard par-3 17th hole. The ace came at a crucial time for the 23-year-old, who entered the week in 29th place in the FedEx Cup standings, right on the bubble for Tour Championship qualification.</p>
                    
                    <p>Bhatia's shot with a 5-iron landed approximately 10 feet short of the pin and rolled directly into the right side of the cup, sending the Caves Valley galleries into a frenzy. The moment was made even more special by the presence of a BMW iX M70 positioned next to the tee, which Bhatia won as part of the hole-in-one prize.</p>
                    
                    <p>"When that golf ball goes in, it was the craziest thing in the world," Bhatia said afterward. "I couldn't even feel my body. Still even going to 18 tee was pretty nuts how much adrenaline I had." The ace helped propel Bhatia to a 4-under 66 for the day and moved him into the projected 30th spot for Tour Championship qualification.</p>
                    
                    <p>In a classy gesture, Bhatia indicated he would either give the car to his caddie or donate it to charity, saying, "I don't really necessarily need a new car. I'm pretty happy with what I've got." The hole-in-one also triggered a $125,000 donation from BMW to the Evans Scholars Foundation, which supports caddie scholarships.</p>
                    
                    <h2>Ryder Cup Implications Intensify</h2>
                    
                    <p>Saturday's action carried significant implications for both the American and European Ryder Cup teams, with several bubble players using the stage to make compelling cases for selection. Harry Hall's strong 67 moved him into a tie for fourth place and continued his late-season surge that has created interesting decisions for European captain Luke Donald.</p>
                    
                    <p>Hall's performance, combined with his projected advancement to the Tour Championship, has put him in contention for one of the final European team spots. His steady play throughout the FedEx Cup Playoffs has demonstrated the kind of form that could translate well to the match-play format at Bethpage Black.</p>
                    
                    <p>On the American side, Cameron Young continued his own push with solid play that improved his Ryder Cup prospects, while other bubble players like Chris Gotterup and Keegan Bradley faced mounting pressure to produce strong finishes. The tournament's outcome could significantly influence several final team selections.</p>
                    
                    <p>For MacIntyre, a victory would not only represent his first PGA Tour triumph but would also solidify his position on the European Ryder Cup team. His performance under pressure this week has demonstrated the kind of mental toughness that translates perfectly to team competition.</p>
                    
                    <h2>Tour Championship Bubble Drama</h2>
                    
                    <p>Beyond the individual tournament storylines, Saturday's round intensified the drama surrounding the final Tour Championship spots, with only the top 30 FedEx Cup point earners advancing to East Lake Golf Club. Several players found themselves in precarious positions as the weekend unfolded.</p>
                    
                    <p>Rickie Fowler and Michael Kim remained on the outside looking in, needing strong Sunday performances to extend their seasons. Both veterans understand the significance of making the Tour Championship, not just for the immediate benefits but for the impact on their 2026 status and Signature Event eligibility.</p>
                    
                    <p>Viktor Hovland and Tommy Fleetwood, both former world top-10 players, found themselves needing to maintain their positions within the top 30 to secure their spots. The European stars' presence at East Lake could be crucial for their respective Ryder Cup aspirations.</p>
                    
                    <p>The bubble drama added an extra layer of intensity to an already charged atmosphere, with multiple players' seasons hanging in the balance as they prepared for Sunday's finale.</p>
                    
                    <h2>Sunday's Stage Set</h2>
                    
                    <p>As Saturday's play concluded with MacIntyre maintaining his four-shot advantage, the stage was perfectly set for a compelling final round that promises to deliver championship-level drama. The Scottish star will attempt to close out his first PGA Tour victory while Scheffler seeks to add another comeback story to his already impressive 2025 résumé.</p>
                    
                    <p>The final pairing will once again feature the tournament's two best players, with MacIntyre's aggressive, emotional style contrasting sharply with Scheffler's methodical, calculated approach. The crowd dynamics that emerged on Saturday figure to be even more pronounced on Sunday, creating the kind of atmosphere that can either elevate or overwhelm players at the crucial moment.</p>
                    
                    <p>Course setup for the final round is expected to be similarly challenging, with tournament officials likely to maintain the demanding pin positions that have separated the field throughout the week. Both MacIntyre and Scheffler have demonstrated their ability to perform under such conditions, setting up what should be a memorable conclusion to the BMW Championship.</p>
                    
                    <blockquote>
                        "Four shots sounds like a lot, but it's really not," Scheffler noted. "I've been in this position before, and I know what it takes. I need to go out there tomorrow and play my game, and hopefully that will be enough."
                    </blockquote>
                    
                    <h2>Historical Context and Expectations</h2>
                    
                    <p>MacIntyre's position heading into Sunday represents the culmination of a remarkable career trajectory that has seen him evolve from a promising European Tour player into a genuine PGA Tour contender. His 194 total through three rounds represents a career-low 54-hole score, besting even his breakthrough Scottish Open victory from 2024.</p>
                    
                    <p>The Scottish star's experience in high-pressure situations, including his runner-up finish at the U.S. Open, should serve him well as he attempts to close out his first PGA Tour victory. His demonstrated ability to handle adversity, as evidenced by Saturday's crowd confrontations, suggests he possesses the mental fortitude required for championship moments.</p>
                    
                    <p>For Scheffler, Sunday's final round represents an opportunity to further cement his status as the world's premier player while adding another comeback victory to his collection. His track record in similar situations has been extraordinary, with multiple victories coming after trailing by significant margins entering the final round.</p>
                    
                    <p>As the BMW Championship heads toward its conclusion, all the elements are in place for a classic finale that could define careers and influence major championships to come. With FedEx Cup points, Tour Championship spots, and Ryder Cup implications all hanging in the balance, Sunday's final round promises to deliver the kind of drama that makes professional golf compelling television.</p>
                </div>
                
                <div class="share-section">
                    <h3 class="share-title">Share This Story</h3>
                    <div class="share-buttons">
                        <a href="https://twitter.com/intent/tweet?text=MacIntyre%20Weathers%20Moving%20Day%20Storm%20to%20Maintain%20BMW%20Championship%20Lead&url=https://tennesseegolfcourses.com/news/macintyre-weathers-moving-day-storm-maintains-bmw-championship-lead" class="share-button share-twitter" target="_blank">
                            <i class="fab fa-twitter"></i>
                            Tweet
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=https://tennesseegolfcourses.com/news/macintyre-weathers-moving-day-storm-maintains-bmw-championship-lead" class="share-button share-facebook" target="_blank">
                            <i class="fab fa-facebook-f"></i>
                            Share
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=https://tennesseegolfcourses.com/news/macintyre-weathers-moving-day-storm-maintains-bmw-championship-lead" class="share-button share-linkedin" target="_blank">
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
                            <textarea name="comment_text" class="comment-textarea" placeholder="Share your thoughts on MacIntyre's confrontation with the crowd..." required></textarea>
                            <button type="submit" class="comment-submit">
                                <i class="fas fa-paper-plane"></i> Post Comment
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="login-prompt">
                        <p><strong>Join the conversation!</strong> <a href="/login">Login</a> or <a href="/register">create an account</a> to share your thoughts on the BMW Championship drama.</p>
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
    
    
    
    <?php include '../includes/threaded-comments.php'; ?>
    <?php include '../includes/footer.php'; ?>
</body>
</html>