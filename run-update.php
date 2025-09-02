<!DOCTYPE html>
<html>
<head>
    <title>Server Update Script</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #000; color: #00ff00; }
        pre { white-space: pre-wrap; }
        .button { background: #007cba; color: white; padding: 10px 20px; margin: 10px; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h2>🔧 Server Update - Threaded Comments System</h2>
    
    <?php if (isset($_GET['action'])): ?>
        <h3>Executing Update...</h3>
        <pre>
        <?php
        if ($_GET['action'] === 'download') {
            echo "=== DOWNLOADING UPDATED FILES ===\n";
            
            $github_raw = "https://raw.githubusercontent.com/xxxentity/tennessee-golf-courses/main";
            
            // Create includes directory if needed
            if (!is_dir('includes')) {
                mkdir('includes', 0755, true);
                echo "Created includes directory\n";
            }
            
            // Download threaded comments system
            echo "Downloading threaded-comments.php...\n";
            $content = file_get_contents($github_raw . '/includes/threaded-comments.php');
            if ($content) {
                file_put_contents('includes/threaded-comments.php', $content);
                echo "✅ threaded-comments.php downloaded\n";
            } else {
                echo "❌ Failed to download threaded-comments.php\n";
            }
            
            // List of files to download
            $files = [
                'news/2025-tour-championship-atlanta-predictions.php',
                'news/belmont-conner-brown-wins-tennessee-match-play-championship.php',
                'news/bmw-championship-2025-complete-tournament-recap-community-impact.php',
                'news/etsu-gavin-tiernan-amateur-championship-runner-up.php',
                'news/fedex-st-jude-championship-2025-complete-recap-community-impact.php',
                'news/fedex-st-jude-first-round-bhatia-leads.php',
                'news/fleetwood-maintains-narrow-lead-scheffler-charges.php',
                'news/fleetwood-takes-command-weather-halts-play.php',
                'news/liv-golf-indianapolis-2025-complete-tournament-recap-entertainment.php',
                'news/liv-golf-michigan-2025-championship-legion-xiii-playoff-victory.php',
                'news/liv-golf-michigan-2025-semifinals-thriller.php',
                'news/liv-golf-michigan-2025-team-championship-complete-tournament-recap.php',
                'news/liv-golf-michigan-team-championship-2025-quarterfinals.php',
                'news/macintyre-explodes-for-62-leads-bmw-championship.php',
                'news/macintyre-extends-commanding-lead-scheffler-pursuit-bmw-championship.php',
                'news/macintyre-weathers-moving-day-storm-maintains-bmw-championship-lead.php',
                'news/open-championship-2025-round-1-royal-portrush.php',
                'news/rose-captures-thrilling-playoff-victory-fleetwood-heartbreak.php',
                'news/scheffler-delivers-stunning-comeback-wins-bmw-championship.php',
                'news/scheffler-extends-lead-open-championship-round-3.php',
                'news/scheffler-seizes-control-open-championship-round-2.php',
                'news/scheffler-wins-2025-british-open-final-round.php',
                'news/smith-narramore-capture-55th-tennessee-four-ball-championship-morristown.php',
                'news/tennessee-four-ball-championship-2025-country-club-morristown.php',
                'news/tennessee-herrington-historic-run-125th-us-amateur-runner-up.php',
                'news/tour-championship-2025-atlanta-complete-tournament-recap-fleetwood-first-win.php',
                'news/tour-championship-2025-final-round-fleetwood-historic-win.php',
                'news/tour-championship-2025-first-round-henley-leads.php',
                'news/tour-championship-2025-round-2-fleetwood-henley-share-lead.php',
                'news/tour-championship-2025-round-3-cantlay-fleetwood-tied.php'
            ];
            
            $success = 0;
            $total = count($files);
            
            foreach ($files as $file) {
                echo "Downloading $file...\n";
                $content = file_get_contents($github_raw . '/' . $file);
                if ($content) {
                    file_put_contents($file, $content);
                    echo "✅ $file updated\n";
                    $success++;
                } else {
                    echo "❌ Failed to download $file\n";
                }
            }
            
            echo "\n=== UPDATE COMPLETE ===\n";
            echo "Successfully updated: $success/$total files\n";
            echo "🎉 Threaded comments system is now active!\n";
            echo "\nTest at: tennessee-golf-courses.com/news/tennessee-herrington-historic-run-125th-us-amateur-runner-up\n";
            
        } else {
            echo "Invalid action specified.\n";
        }
        ?>
        </pre>
        
        <p><strong>Update complete!</strong> You can now delete this file (run-update.php) for security.</p>
        <p><a href="/news/tennessee-herrington-historic-run-125th-us-amateur-runner-up">🧪 Test Threaded Comments</a></p>
        
    <?php else: ?>
        <p>This will download all updated news articles with threaded comments directly from GitHub.</p>
        <p><strong>⚠️ This will overwrite existing news article files on your server.</strong></p>
        
        <a href="?action=download" class="button">🚀 Download Updated Files</a>
        
        <h3>What this will do:</h3>
        <ul>
            <li>✅ Download the threaded-comments.php system</li>
            <li>✅ Update all 29 news articles with threaded comments</li>
            <li>✅ Replace files with working versions from GitHub</li>
            <li>✅ Enable reply functionality and clickable usernames</li>
        </ul>
    <?php endif; ?>
    
</body>
</html>