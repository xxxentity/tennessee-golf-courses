<?php
require_once '../includes/init.php';
require_once '../includes/profile-helpers.php';

$article_slug = 'tennessee-herrington-historic-run-125th-us-amateur-runner-up';
$article_title = 'Tennessee\'s Herrington Makes Historic Run to U.S. Amateur Final, Earns Major Championship Invitations';

// Check if user is logged in using secure session
$is_logged_in = SecureSession::isLoggedIn();

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_logged_in) {
    $comment_text = trim($_POST['comment_text']);
    $parent_id = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
    $user_id = SecureSession::get('user_id');
    
    if (!empty($comment_text)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO news_comments (user_id, article_slug, article_title, comment_text, parent_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $article_slug, $article_title, $comment_text, $parent_id]);
            $success_message = $parent_id ? "Your reply has been posted successfully! (Parent: $parent_id)" : "Your comment has been posted successfully!";
        } catch (PDOException $e) {
            $error_message = "Error posting comment: " . $e->getMessage();
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
        ORDER BY nc.created_at ASC
    ");
    $stmt->execute([$article_slug]);
    $all_comments = $stmt->fetchAll();
    
    // Organize comments into threaded structure
    $comments = [];
    $replies = [];
    
    foreach ($all_comments as $comment) {
        if ($comment['parent_id'] === null || $comment['parent_id'] === '' || $comment['parent_id'] === 0) {
            $comments[] = $comment;
        } else {
            if (!isset($replies[$comment['parent_id']])) {
                $replies[$comment['parent_id']] = [];
            }
            $replies[$comment['parent_id']][] = $comment;
        }
    }
    
    // Debug output (remove after testing)
    if ($_GET['debug'] === '1') {
        echo "<pre>Debug Info:\n";
        echo "Total comments: " . count($all_comments) . "\n";
        echo "Top-level comments: " . count($comments) . "\n";
        echo "Replies array: ";
        print_r($replies);
        echo "\nAll comments data:\n";
        foreach ($all_comments as $c) {
            echo "ID: {$c['id']}, Parent: {$c['parent_id']}, Text: " . substr($c['comment_text'], 0, 50) . "...\n";
        }
        echo "</pre>";
    }
    
} catch (PDOException $e) {
    $comments = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tennessee's Herrington Makes Historic Run to U.S. Amateur Final, Earns Major Championship Invitations - Tennessee Golf Courses</title>
    <meta name="description" content="Dickson native Jackson Herrington becomes first Tennessee golfer since 2013 to reach U.S. Amateur final, earning spots in 2026 Masters and U.S. Open while making family history.">
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
        
        .scoreboard-item.runner-up {
            background: linear-gradient(135deg, #c0c0c0, #a8a8a8);
            font-weight: 600;
            border: 2px solid #808080;
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
        
        .player-result {
            font-weight: 600;
            color: var(--primary-color);
            margin-left: 1rem;
        }
        
        .tennessee-highlight {
            background: linear-gradient(135deg, #ff8500, #e6720d);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin: 2rem 0;
            text-align: center;
        }
        
        .tennessee-highlight i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .family-legacy {
            background: linear-gradient(135deg, #58595b, #414042);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin: 2rem 0;
            text-align: center;
        }
        
        .family-legacy i {
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
        
        .comment-actions {
            margin-top: 1rem;
            display: flex;
            gap: 1rem;
        }
        
        .reply-btn {
            background: none;
            border: none;
            color: var(--primary-color);
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            transition: background 0.2s ease;
        }
        
        .reply-btn:hover {
            background: var(--bg-light);
        }
        
        .reply-form {
            margin-top: 1rem;
            padding: 1rem;
            background: var(--bg-white);
            border-radius: 10px;
            border: 1px solid var(--border-color);
            display: none;
        }
        
        .reply-form.active {
            display: block;
        }
        
        .reply-form textarea {
            width: 100%;
            min-height: 80px;
            padding: 0.8rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.95rem;
            resize: vertical;
        }
        
        .reply-form .form-actions {
            margin-top: 0.8rem;
            display: flex;
            gap: 0.8rem;
        }
        
        .reply-form button {
            padding: 0.6rem 1.5rem;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .reply-submit {
            background: var(--primary-color);
            color: white;
        }
        
        .reply-submit:hover {
            background: var(--secondary-color);
        }
        
        .reply-cancel {
            background: var(--border-color);
            color: var(--text-gray);
        }
        
        .reply-cancel:hover {
            background: var(--text-gray);
            color: white;
        }
        
        .comment-replies {
            margin-top: 1.5rem;
            margin-left: 2rem;
            border-left: 3px solid var(--border-color);
            padding-left: 1.5rem;
        }
        
        .comment-reply {
            padding: 1rem;
            background: var(--bg-white);
            border-radius: 10px;
            margin-bottom: 1rem;
            border: 1px solid var(--border-color);
        }
        
        .comment-reply:last-child {
            margin-bottom: 0;
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
                    <span class="article-category">Tennessee News</span>
                    <h1 class="article-title">Tennessee's Herrington Makes Historic Run to U.S. Amateur Final, Earns Major Championship Invitations</h1>
                    <div class="article-meta">
                        <span><i class="far fa-calendar"></i> August 17, 2025</span>
                        <span><i class="far fa-clock"></i> 9:30 PM</span>
                        <span><i class="far fa-user"></i> TGC Editorial Team</span>
                    </div>
                </header>
                
                <img src="/images/news/tennessee-herrington-historic-run-125th-us-amateur-runner-up/main.webp" alt="Jackson Herrington U.S. Amateur Runner-Up" class="article-image">
                
                <div class="article-content">
                    <p><strong>SAN FRANCISCO, Calif.</strong> — In one of the most remarkable achievements by a Tennessee golfer in recent memory, University of Tennessee sophomore Jackson Herrington capped off a historic week at the 125th U.S. Amateur Championship with a runner-up finish that has earned him invitations to both the 2026 Masters Tournament and the 2026 U.S. Open. The 19-year-old from Dickson became just the third Vol golfer in program history to reach the championship final, joining an elite group while making his mark on amateur golf's most prestigious stage.</p>
                    
                    <p>Herrington's journey to the final at The Olympic Club's Lake Course was nothing short of spectacular, featuring clutch performances under pressure and a memorable semifinal victory that showcased the competitive fire that has defined his young career. Though he ultimately fell to 18-year-old Mason Howell of Georgia by a score of 7&6 in Sunday's 36-hole championship match, the Dickson native's achievement represents the highest finish by a Tennessee golfer at the U.S. Amateur since Oliver Goss reached the final in 2013.</p>
                    
                    <div class="tennessee-highlight">
                        <i class="fas fa-graduation-cap"></i>
                        <h3>VOLUNTEER EXCELLENCE</h3>
                        <p>First Tennessee golfer to reach U.S. Amateur final since 2013 • Earns spots in 2026 Masters and U.S. Open</p>
                    </div>
                    
                    <blockquote>
                        "This has been an incredible week that I'll never forget," Herrington said after the final. "To represent Tennessee and my family on this stage means everything to me. Getting to play in the Masters and U.S. Open next year is a dream come true."
                    </blockquote>
                    
                    <h2>A Historic Path to the Final</h2>
                    
                    <p>Herrington's road to the championship match began with solid stroke play qualifying, where he posted a 2-over 144 total to earn the No. 37 seed in the match play bracket. The long-hitting lefty then methodically worked his way through the field, demonstrating the kind of mental toughness and clutch play that separates champions from contenders.</p>
                    
                    <p>His first major test came in the round of 16, where he faced Caleb Bond in a marathon match that extended to 20 holes before Herrington finally prevailed. That victory set up a quarterfinal showdown with Jimmy Abdo on Friday, August 15, where the Tennessee sophomore delivered a commanding 4&2 victory to advance to the semifinals.</p>
                    
                    <p>The quarterfinal triumph made Herrington the first Vol golfer to reach the U.S. Amateur semifinals since Oliver Goss accomplished the feat in 2013, but the Dickson native wasn't finished writing his chapter in Tennessee golf history. His semifinal opponent, North Carolina's Niall Shiels Donegan of Scotland, provided the tournament's most dramatic moment.</p>
                    
                    <p>After falling behind early in the semifinal match, Herrington rallied to take a 2-up lead through 15 holes. However, Donegan fought back to square the match heading to the decisive 18th hole, setting up a moment that will be remembered in Tennessee golf lore for years to come.</p>
                    
                    <div class="scoreboard">
                        <h3 class="scoreboard-title">Championship Match Result</h3>
                        <ul class="scoreboard-list">
                            <li class="scoreboard-item champion">
                                <span class="player-rank">1</span>
                                <span class="player-name">Mason Howell (Georgia)</span>
                                <span class="player-result">Winner 7&6</span>
                            </li>
                            <li class="scoreboard-item runner-up">
                                <span class="player-rank">2</span>
                                <span class="player-name">Jackson Herrington (Tennessee)</span>
                                <span class="player-result">Runner-Up</span>
                            </li>
                        </ul>
                    </div>
                    
                    <h2>The Defining Moment on 18</h2>
                    
                    <p>With the semifinal match tied and his U.S. Amateur dreams hanging in the balance, Herrington stepped to the 18th tee at The Olympic Club knowing he needed something special. What followed was the kind of moment that defines amateur golf careers and creates lasting memories.</p>
                    
                    <p>After finding the fairway with his drive, Herrington hit his approach shot to within 5-6 feet of the flag, setting up a birdie putt that would either send him to the championship final or force sudden-death playoff holes. With the pressure of a lifetime opportunity weighing on his shoulders, the Tennessee sophomore calmly rolled the putt into the center of the cup, sending him into the final and triggering an emotional celebration.</p>
                    
                    <p>"I knew I could do it," Herrington said about playing the crucial 18th hole. "That's why I practice those situations. When it mattered most, I was able to step up and deliver." The birdie victory over Donegan made Herrington just the third Tennessee golfer to reach the U.S. Amateur final, joining Eric Rebmann (1987) and Oliver Goss (2013) in the program's history books.</p>
                    
                    <p>The semifinal victory also earned Herrington a unique distinction in U.S. Amateur history, as he became the first left-handed player to compete in the championship final since Phil Mickelson claimed the title at Cherry Hills in 1990. This milestone added another layer of significance to an already historic achievement for the young Volunteer.</p>
                    
                    <h2>Championship Final Challenge</h2>
                    
                    <p>Sunday's 36-hole championship match pitted Herrington against 18-year-old Mason Howell of Thomasville, Georgia, creating the youngest final in U.S. Amateur history. The teenage showdown at The Olympic Club drew national attention as two of amateur golf's brightest young stars battled for the sport's most prestigious amateur title.</p>
                    
                    <p>Despite starting strong and briefly taking an early lead, Herrington found himself facing a determined opponent who gradually took control of the match. Howell's consistent play and clutch putting proved to be the difference, as the Georgia teenager built a commanding advantage that ultimately resulted in a 7&6 victory.</p>
                    
                    <p>While the final result wasn't what Herrington had hoped for, his performance throughout the week demonstrated the kind of championship-level golf that has made him one of the most promising young players in the amateur ranks. His ability to handle pressure situations and deliver clutch shots when it mattered most showcased the mental strength that will serve him well in future competitions.</p>
                    
                    <p>The runner-up finish ties the best result by a Tennessee golfer in U.S. Amateur history, matching Oliver Goss's achievement from 2013 and cementing Herrington's place among the elite performers in program history.</p>
                    
                    <div class="family-legacy">
                        <i class="fas fa-university"></i>
                        <h3>EIGHT-GENERATION LEGACY</h3>
                        <p>Herrington represents the eighth consecutive generation of his family to attend the University of Tennessee</p>
                    </div>
                    
                    <h2>Family Tradition and Tennessee Roots</h2>
                    
                    <p>Herrington's achievement carries special significance beyond just his individual success, as he represents the eighth consecutive generation of his family to attend the University of Tennessee. Seven generations before him pursued medical studies at UT, making Jackson's path as a student-athlete a unique chapter in a remarkable family legacy.</p>
                    
                    <p>Growing up in Dickson County, Herrington honed his skills at Greystone Golf Club, where he developed the powerful swing that has become his trademark. Known for his exceptional driving distance, the left-handed bomber has consistently impressed observers with his ability to generate clubhead speed and distance that sets him apart from his peers.</p>
                    
                    <p>"I know I hit it long compared to the average 16-, 17-year-old," Herrington said earlier in his career. "I turned 17 in April, and I'm hitting it 315, 320 in the air." That power advantage, combined with improved short game skills and course management, has helped make him one of the most promising young golfers in the country.</p>
                    
                    <p>His connection to Tennessee runs deep, with family roots that extend back generations and a genuine pride in representing the Volunteer State on golf's biggest stages. The U.S. Amateur runner-up finish represents not just personal achievement, but also a continuation of the family's commitment to excellence and service to their community.</p>
                    
                    <h2>Major Championship Opportunities Await</h2>
                    
                    <p>Perhaps the most significant reward for Herrington's historic week comes in the form of automatic invitations to two of golf's most prestigious championships. By reaching the U.S. Amateur final, he has earned spots in both the 2026 Masters Tournament at Augusta National Golf Club and the 2026 U.S. Open, providing him with opportunities to compete against the world's best professional golfers.</p>
                    
                    <p>The Masters invitation is particularly special, as it will mark Herrington's debut at golf's most exclusive tournament. The opportunity to play Augusta National in competition represents a dream come true for the young amateur and will provide invaluable experience as he continues his development as a player.</p>
                    
                    <p>Similarly, the U.S. Open invitation gives Herrington a chance to compete in another major championship, testing his skills against the deepest field in professional golf. These opportunities will serve as important learning experiences as he considers his future path in the game.</p>
                    
                    <p>Both invitations represent significant milestones in Herrington's young career and validate his status as one of amateur golf's elite performers. The experience of competing in major championships will undoubtedly accelerate his development and provide memories that will last a lifetime.</p>
                    
                    <h2>Tennessee Golf Program Pride</h2>
                    
                    <p>Herrington's achievement reflects positively not just on his individual talents, but also on the strength of the University of Tennessee golf program under head coach Brennan Webb. The program's ability to develop elite amateur talent and compete at the highest levels of collegiate golf is demonstrated by Herrington's success on the national stage.</p>
                    
                    <p>His journey from a promising recruit to U.S. Amateur finalist showcases the kind of development and support that makes Tennessee an attractive destination for top junior golfers. The program's emphasis on both competitive excellence and personal growth has clearly played a role in Herrington's remarkable achievement.</p>
                    
                    <p>The runner-up finish also provides momentum for the Tennessee golf program as it looks toward the upcoming collegiate season. Having a player of Herrington's caliber and experience in the lineup gives the Volunteers a significant advantage as they compete against the nation's top programs.</p>
                    
                    <p>For young golfers across Tennessee, Herrington's success serves as inspiration and proof that dreams can become reality with dedication, hard work, and the right opportunities. His achievement at the U.S. Amateur will undoubtedly inspire the next generation of Tennessee golfers to pursue their own championship aspirations.</p>
                    
                    <h2>Looking Ahead to Future Success</h2>
                    
                    <p>As Herrington returns to Knoxville to prepare for his sophomore season with the Volunteers, he carries with him the confidence and experience gained from competing at amateur golf's highest level. The lessons learned during his historic U.S. Amateur run will serve him well as he continues his development as both a student and athlete.</p>
                    
                    <p>The upcoming collegiate season takes on added significance as Herrington brings major championship experience and national recognition to the Tennessee lineup. His presence will undoubtedly elevate the program's profile and provide leadership for younger players looking to follow in his footsteps.</p>
                    
                    <p>Beyond the immediate future, Herrington's U.S. Amateur success positions him as one of the most promising amateur golfers in the country. His combination of power, skill, and mental toughness suggests that this historic week at The Olympic Club may be just the beginning of a remarkable career in golf.</p>
                    
                    <p>Whether he chooses to remain in the amateur ranks or eventually pursue professional golf, Herrington has already established himself as one of Tennessee's greatest amateur golfers. His achievement at the 125th U.S. Amateur will be remembered as a watershed moment for both the player and the program he represents.</p>
                    
                    <blockquote>
                        "Jackson has shown what's possible when talent meets opportunity and hard work," said Tennessee head coach Brennan Webb. "His achievement this week is something our entire program can be proud of, and it sets the bar high for everyone who follows."
                    </blockquote>
                    
                    <p>As the golf world looks ahead to the 2026 Masters and U.S. Open, Jackson Herrington's name will be among those to watch. The kid from Dickson who grew up hitting balls at Greystone Golf Club has proven he belongs on golf's biggest stages, and his journey is just beginning.</p>
                </div>
                
                <div class="share-section">
                    <h3 class="share-title">Share This Story</h3>
                    <div class="share-buttons">
                        <a href="https://twitter.com/intent/tweet?text=Tennessee's%20Herrington%20Makes%20Historic%20Run%20to%20U.S.%20Amateur%20Final&url=https://tennesseegolfcourses.com/news/tennessee-herrington-historic-run-125th-us-amateur-runner-up" class="share-button share-twitter" target="_blank">
                            <i class="fab fa-twitter"></i>
                            Tweet
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=https://tennesseegolfcourses.com/news/tennessee-herrington-historic-run-125th-us-amateur-runner-up" class="share-button share-facebook" target="_blank">
                            <i class="fab fa-facebook-f"></i>
                            Share
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=https://tennesseegolfcourses.com/news/tennessee-herrington-historic-run-125th-us-amateur-runner-up" class="share-button share-linkedin" target="_blank">
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
                            <textarea name="comment_text" class="comment-textarea" placeholder="Share your thoughts on Jackson Herrington's historic achievement..." required></textarea>
                            <button type="submit" class="comment-submit">
                                <i class="fas fa-paper-plane"></i> Post Comment
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="login-prompt">
                        <p><strong>Join the conversation!</strong> <a href="/login">Login</a> or <a href="/register">create an account</a> to share your thoughts on this historic Tennessee golf achievement.</p>
                    </div>
                <?php endif; ?>
                
                <div class="comments-list">
                    <?php if (!empty($comments)): ?>
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment" id="comment-<?php echo $comment['id']; ?>">
                                <div class="comment-header">
                                    <?php echo getCommentAuthorLink($comment); ?>
                                    <span class="comment-date"><?php echo date('M j, Y g:i A', strtotime($comment['created_at'])); ?></span>
                                </div>
                                <div class="comment-text"><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></div>
                                
                                <?php if ($is_logged_in): ?>
                                <div class="comment-actions">
                                    <button class="reply-btn" onclick="toggleReplyForm(<?php echo $comment['id']; ?>)">
                                        <i class="fas fa-reply"></i> Reply
                                    </button>
                                </div>
                                
                                <div class="reply-form" id="reply-form-<?php echo $comment['id']; ?>">
                                    <form method="POST" action="">
                                        <input type="hidden" name="parent_id" value="<?php echo $comment['id']; ?>">
                                        <textarea name="comment_text" placeholder="Write your reply..." required></textarea>
                                        <div class="form-actions">
                                            <button type="submit" class="reply-submit">Post Reply</button>
                                            <button type="button" class="reply-cancel" onclick="toggleReplyForm(<?php echo $comment['id']; ?>)">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (isset($replies[$comment['id']])): ?>
                                <div class="comment-replies">
                                    <?php foreach ($replies[$comment['id']] as $reply): ?>
                                        <div class="comment-reply" id="comment-<?php echo $reply['id']; ?>">
                                            <div class="comment-header">
                                                <?php echo getCommentAuthorLink($reply); ?>
                                                <span class="comment-date"><?php echo date('M j, Y g:i A', strtotime($reply['created_at'])); ?></span>
                                            </div>
                                            <div class="comment-text"><?php echo nl2br(htmlspecialchars($reply['comment_text'])); ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="text-align: center; color: var(--text-gray); font-style: italic;">Be the first to comment on this historic achievement!</p>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
    
    <script>
    function toggleReplyForm(commentId) {
        const form = document.getElementById('reply-form-' + commentId);
        if (form) {
            if (form.classList.contains('active')) {
                form.classList.remove('active');
            } else {
                // Hide any other open reply forms
                const allForms = document.querySelectorAll('.reply-form.active');
                allForms.forEach(f => f.classList.remove('active'));
                
                // Show this form
                form.classList.add('active');
                
                // Focus on the textarea
                const textarea = form.querySelector('textarea');
                if (textarea) {
                    textarea.focus();
                }
            }
        }
    }
    </script>
</body>
</html>