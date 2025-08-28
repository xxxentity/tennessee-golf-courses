<?php
require_once '../includes/init.php';
require_once '../includes/profile-helpers.php';
require_once '../config/database.php';

$article_slug = 'rose-captures-thrilling-playoff-victory-fleetwood-heartbreak';
$article_title = 'Rose Captures Thrilling Playoff Victory as Fleetwood Suffers More Heartbreak';

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
    <title>Rose Captures Thrilling Playoff Victory as Fleetwood Suffers More Heartbreak - Tennessee Golf Courses</title>
    <meta name="description" content="Justin Rose defeats J.J. Spaun in dramatic three-hole playoff to win FedEx St. Jude Championship as Tommy Fleetwood's quest continues.">
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
        
        .playoff-alert {
            background: linear-gradient(135deg, #dc2626, #991b1b);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin: 2rem 0;
            text-align: center;
        }
        
        .playoff-alert i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .playoff-alert h3 {
            margin: 0.5rem 0;
            font-size: 1.3rem;
        }
        
        .champion-highlight {
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            padding: 2rem;
            border-radius: 15px;
            margin: 2rem 0;
            text-align: center;
            border: 2px solid #e6c200;
        }
        
        .champion-highlight h3 {
            color: var(--text-black);
            margin-bottom: 1rem;
            font-size: 1.5rem;
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
                    <h1 class="article-title">Rose Captures Thrilling Playoff Victory as Fleetwood Suffers More Heartbreak</h1>
                    <div class="article-meta">
                        <span><i class="far fa-calendar"></i> August 10, 2025</span>
                        <span><i class="far fa-clock"></i> 8:15 PM</span>
                        <span><i class="far fa-user"></i> TGC Editorial Team</span>
                    </div>
                </header>
                
                <img src="/images/news/rose-captures-thrilling-playoff-victory-fleetwood-heartbreak/main.jpeg" alt="Justin Rose FedEx St. Jude Championship Playoff Victory" class="article-image">
                
                <div class="article-content">
                    <p><strong>MEMPHIS, Tenn.</strong> — Justin Rose delivered one of the most spectacular finishes in FedExCup Playoffs history on Sunday, rallying from three shots behind with five holes to play before defeating J.J. Spaun in a dramatic three-hole playoff to capture the FedEx St. Jude Championship. Meanwhile, Tommy Fleetwood's agonizing quest for his first PGA Tour victory continued as another late-round collapse left him tied for third.</p>
                    
                    <p>Rose, 45, capped his stunning comeback with four consecutive birdies from holes 14-17, then displayed nerves of steel in the playoff, ultimately sinking a 12-foot birdie putt on the third extra hole to claim his 12th PGA Tour victory and first since 2019. The triumph makes Rose the oldest winner on Tour this season and catapults him into the top five of the FedExCup standings.</p>
                    
                    <div class="champion-highlight">
                        <h3><i class="fas fa-trophy"></i> TOURNAMENT CHAMPION</h3>
                        <p>Justin Rose wins in playoff at 45 years old, becoming the first player in his 40s to win on the PGA Tour this season</p>
                    </div>
                    
                    <blockquote>
                        "To win at this age, against this field, in the playoffs - it's special," Rose said, his voice thick with emotion. "I've been working so hard to get back to this level. This validates everything I've been doing."
                    </blockquote>
                    
                    <h2>Rose's Miraculous Rally</h2>
                    
                    <p>Entering Sunday's final round two shots behind leader Tommy Fleetwood, Rose appeared to be drifting further from contention as he reached the 13th tee trailing by three strokes. But the veteran Englishman saved his best for last, producing one of the most clutch closing stretches in recent memory.</p>
                    
                    <p>The fireworks began at the par-5 14th hole, where Rose rolled in a 25-foot eagle putt that immediately energized both himself and the Memphis galleries. He followed that with a textbook birdie at the 15th, sticking a wedge to eight feet before converting confidently.</p>
                    
                    <p>At the par-3 16th, Rose nearly holed his tee shot, the ball settling just four feet from the cup for another birdie that pulled him within one shot of the lead. Then came the signature moment: at the par-4 17th, Rose's approach from 145 yards checked up beautifully to 10 feet, and he buried the putt for his fourth consecutive birdie.</p>
                    
                    <p>"I felt something special happening," Rose reflected. "When that eagle went in at 14, I just tried to ride the momentum. The crowd was incredible, and I could feel the energy building with every hole."</p>
                    
                    <div class="playoff-alert">
                        <i class="fas fa-flag"></i>
                        <h3>PLAYOFF DRAMA</h3>
                        <p>Three-hole playoff extends over an hour as Rose and Spaun trade clutch shots before Rose prevails on 18th hole</p>
                    </div>
                    
                    <h2>Fleetwood's Devastating Collapse</h2>
                    
                    <p>While Rose was mounting his charge, Tommy Fleetwood was experiencing another heartbreaking Sunday at a PGA Tour event. The 34-year-old Englishman entered the final round with a one-shot lead and appeared poised to finally capture his elusive first Tour victory, holding a two-shot advantage with just three holes remaining.</p>
                    
                    <p>The unraveling began at the par-5 16th hole, where Fleetwood's approach shot found trouble and he managed only a clumsy bogey. Still, he maintained a one-shot lead heading to the crucial 17th hole - the same hole where Rose was making his crucial birdie.</p>
                    
                    <p>Fleetwood's approach at the 17th sailed wide left, his club falling to his side with one hand in visible frustration. His chip ran past the pin to about seven feet, and when his par putt never threatened the hole, his lead had evaporated entirely.</p>
                    
                    <p>"The 17th was the turning point," Fleetwood admitted. "I hit a poor shot and then couldn't make the putt to save par. In these situations, every shot matters, and I just didn't execute when I needed to most."</p>
                    
                    <p>His final chance ended quickly at the 18th tee, where his drive found the right bunker. The birdie he desperately needed never materialized, and he walked off with a final-round 70 and another runner-up showing - his seventh on the PGA Tour.</p>
                    
                    <div class="scoreboard">
                        <h3 class="scoreboard-title">Final Leaderboard</h3>
                        <ul class="scoreboard-list">
                            <li class="scoreboard-item champion">
                                <span class="player-rank">1</span>
                                <span class="player-name">Justin Rose (Playoff)</span>
                                <span class="player-score">-16</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">2</span>
                                <span class="player-name">J.J. Spaun (Playoff)</span>
                                <span class="player-score">-16</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T3</span>
                                <span class="player-name">Tommy Fleetwood</span>
                                <span class="player-score">-15</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T3</span>
                                <span class="player-name">Scottie Scheffler</span>
                                <span class="player-score">-15</span>
                            </li>
                            <li class="scoreboard-item">
                                <span class="player-rank">T5</span>
                                <span class="player-name">Andrew Novak</span>
                                <span class="player-score">-12</span>
                            </li>
                        </ul>
                    </div>
                    
                    <h2>Playoff Drama Unfolds</h2>
                    
                    <p>The playoff between Rose and Spaun proved to be as compelling as the regulation finish, extending over an hour and providing the Memphis crowd with unforgettable theater. Both players showed remarkable composure under extreme pressure, trading clutch shots and demonstrating why they reached golf's biggest stage.</p>
                    
                    <p>The first playoff hole saw both players with makeable birdie putts, but cruel lip-outs kept them tied. Rose's 15-footer caught the edge and spun out, while Spaun's 12-footer did the same, forcing them to continue.</p>
                    
                    <p>On the second playoff hole, both players produced spectacular birdies in what felt like a match-play duel. Spaun rolled in a magnificent 30-foot putt that had the crowd on its feet, only to watch Rose answer immediately with his own birdie from 18 feet.</p>
                    
                    <p>"That putt by J.J. from 30 feet was incredible," Rose said. "I knew I had to match it, and somehow I managed to hole mine too. It was like we were feeding off each other."</p>
                    
                    <p>The decisive third playoff hole saw the pin position changed, adding another layer of strategy. Rose's approach settled 12 feet below the hole, while Spaun found himself seven feet away for birdie. When Rose buried his putt for the third consecutive playoff birdie, the pressure shifted entirely to Spaun.</p>
                    
                    <p>The reigning U.S. Open champion's putt tracked toward the hole but pulled left at the last moment, sealing Rose's victory and sending the veteran into emotional celebrations.</p>
                    
                    <h2>Scheffler's Steady Showing</h2>
                    
                    <p>World No. 1 Scottie Scheffler posted a solid final-round 68 to finish tied for third alongside Fleetwood at 15-under par. While the result wasn't the victory Scheffler hoped for, his consistent play throughout the week further solidified his position atop the FedExCup standings.</p>
                    
                    <p>"I played well this week and gave myself chances," Scheffler said. "Justin played incredible golf down the stretch and in the playoff. Sometimes you tip your cap to great golf, and that's what happened today."</p>
                    
                    <p>The finish keeps Scheffler's remarkable 2025 season on track, as he continues his pursuit of multiple victories in the FedExCup Playoffs. His third-place showing ensures he maintains a significant lead in the season-long points race.</p>
                    
                    <h2>Bubble Drama Reaches Climax</h2>
                    
                    <p>While Rose and Spaun battled for the title, equally dramatic storylines unfolded throughout the field as players fought for their playoff lives. Only the top 50 in FedExCup points would advance to next week's BMW Championship, creating immense pressure for those on the bubble.</p>
                    
                    <p>Rickie Fowler provided the day's most inspiring performance among bubble players, closing with a clutch 1-under 69 for a T6 finish that moved him from 64th to 48th in the standings. The popular veteran's crucial 11-foot birdie at the 15th hole proved to be the difference between extending his season and ending it.</p>
                    
                    <p>"I knew exactly what I needed to do coming down the stretch," Fowler said. "That birdie at 15 felt huge in the moment, and thankfully it was enough. To qualify for all the Signature Events next year means everything."</p>
                    
                    <p>The drama was equally compelling for Jordan Spieth, but with a devastating outcome. The three-time major champion entered the week 48th in points but saw his hopes crumble when his approach shot into the 72nd hole found water.</p>
                    
                    <p>"It's disappointing," Spieth said. "I had control of my own destiny and couldn't get it done. That water ball on 18 pretty much summed up where my game is right now. I'll regroup and come back stronger."</p>
                    
                    <p>Spieth finished 54th in the final standings, missing the BMW Championship for the second consecutive season and ending his 2025 campaign earlier than hoped.</p>
                    
                    <h2>Historic Achievement for Rose</h2>
                    
                    <p>Rose's victory carries special significance beyond the $3.6 million winner's check. At 45 years and 4 months old, he becomes the oldest winner on the PGA Tour this season, surpassing expectations for a player many thought was past his prime.</p>
                    
                    <p>The triumph also marks Rose's return to golf's elite level after several seasons of inconsistent play. His victory moves him into the top five of the FedExCup standings and virtually guarantees his spot in the Tour Championship for the first time since 2019.</p>
                    
                    <p>"I've always believed I could still compete at this level," Rose said. "The work I've put in with my team, the commitment to staying fit and sharp mentally - this validates all of that. Age is just a number if you're willing to put in the work."</p>
                    
                    <blockquote>
                        "This win means I'm back where I belong, competing for the biggest tournaments against the best players in the world. That's all I've ever wanted."
                    </blockquote>
                    
                    <h2>Spaun's Gallant Effort</h2>
                    
                    <p>Despite the playoff defeat, J.J. Spaun's week in Memphis further established him as one of the Tour's emerging stars. The reigning U.S. Open champion showed tremendous composure throughout the final round and playoff, nearly capturing his second victory of 2025.</p>
                    
                    <p>"Obviously I'm disappointed not to win, but I can't be too upset with how I played," Spaun said. "Justin made some incredible putts in the playoff, and you have to respect that. I'll take the positives from this week and keep building."</p>
                    
                    <p>Spaun's runner-up finish moves him solidly into the top 10 of the FedExCup standings and positions him well for the remainder of the playoffs. His performance over the weekend, including Saturday's 65, demonstrated the form that carried him to major championship glory earlier this year.</p>
                    
                    <h2>Looking Ahead</h2>
                    
                    <p>With the first leg of the FedExCup Playoffs complete, attention now turns to next week's BMW Championship, where the field will be cut from 50 to 30 players. Rose's victory not only secured his spot but positioned him as a legitimate contender for the season-long title.</p>
                    
                    <p>For Fleetwood, another close call adds to the narrative of a supremely talented player still searching for his breakthrough moment. His 162nd PGA Tour start without a victory extends one of golf's most compelling storylines, but his consistent excellence suggests that breakthrough may still be coming.</p>
                    
                    <p>The FedEx St. Jude Championship delivered everything the playoffs promised: dramatic finishes, clutch performances, heartbreak, and triumph. Rose's victory provided a masterclass in closing out tournaments, while the bubble drama reminded everyone that every shot matters in professional golf's most pressure-packed moments.</p>
                    
                    <h2>By the Numbers</h2>
                    
                    <p>Rose's victory came via a final-round 67 that featured six birdies and an eagle, including that crucial four-hole stretch from 14-17. His playoff performance was equally impressive, making birdie on all three extra holes while under maximum pressure.</p>
                    
                    <p>The win moves Rose from 38th to 5th in the FedExCup standings, virtually ensuring his participation in the Tour Championship. It also makes him the first player over 45 to win on Tour since Phil Mickelson's 2021 PGA Championship victory.</p>
                    
                    <p>For the 70-player field that began the week in Memphis, only 50 will continue their seasons at the BMW Championship. Among those advancing are surprise qualifiers who played their way into the top 50 with strong weeks, while several established names saw their seasons end earlier than expected.</p>
                    
                    <p>As the FedExCup Playoffs continue, Rose's victory serves as a reminder that experience and composure can triumph over youth and power when the pressure is at its highest. In a sport where mental toughness often determines champions, the veteran Englishman showed that his best golf may still be ahead of him.</p>
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

    <?<?php include "../includes/threaded-comments.php"; ?>php include '../includes/footer.php'; ?>
    
    <script src="/weather.js?v=4"></script>
    <script src="/script.js?v=4"></script>
</body>
</html>