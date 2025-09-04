<?php
// Direct test of load-more-reviews.php logic
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Direct Test of Load More Reviews Logic</h1>";
echo "<p>Time: " . date('Y-m-d H:i:s') . "</p>";

require_once '../config/database.php';

$course_slug = 'avalon-golf-country-club';
$offset = 5;

echo "<h2>Testing with offset: $offset</h2>";

try {
    // Get ALL comments first
    $stmt = $pdo->prepare("
        SELECT cc.*, u.username 
        FROM course_comments cc 
        JOIN users u ON cc.user_id = u.id 
        WHERE cc.course_slug = ?
        ORDER BY cc.created_at DESC
    ");
    $stmt->execute([$course_slug]);
    $all_comments = $stmt->fetchAll();
    
    echo "<p>Step 1: Fetched " . count($all_comments) . " total comments</p>";
    
    // Filter to get only main reviews (not replies)
    $main_reviews = [];
    foreach ($all_comments as $comment) {
        $parent_id = isset($comment['parent_comment_id']) ? $comment['parent_comment_id'] : null;
        if ($parent_id === null || $parent_id == 0 || $parent_id == '0' || empty($parent_id)) {
            $main_reviews[] = $comment;
        }
    }
    
    echo "<p>Step 2: Found " . count($main_reviews) . " main reviews after filtering</p>";
    
    // Now slice to get the requested page
    $comments = array_slice($main_reviews, $offset, 5);
    
    echo "<p>Step 3: array_slice($offset, 5) returned " . count($comments) . " reviews</p>";
    
    if (empty($comments)) {
        echo "<h3 style='color: red;'>❌ No comments to display (empty array)</h3>";
    } else {
        echo "<h3 style='color: green;'>✅ Found " . count($comments) . " comments to display!</h3>";
        
        // Test if we can access the comment data
        foreach ($comments as $idx => $comment) {
            echo "<h4>Comment $idx:</h4>";
            echo "<ul>";
            echo "<li>ID: " . $comment['id'] . "</li>";
            echo "<li>Username: " . $comment['username'] . "</li>";
            echo "<li>Rating: " . $comment['rating'] . "</li>";
            echo "<li>Text: " . substr($comment['comment_text'], 0, 50) . "...</li>";
            echo "</ul>";
            
            // Test fetching replies for this comment
            echo "<p>Testing reply fetch for comment ID " . $comment['id'] . "...</p>";
            
            $stmt2 = $pdo->prepare("SELECT COUNT(*) as reply_count FROM course_comments WHERE parent_comment_id = ?");
            $stmt2->execute([$comment['id']]);
            $reply_count = $stmt2->fetch()['reply_count'];
            echo "<p>Reply count: $reply_count</p>";
            
            // Test session handling
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
                echo "<p>Session started successfully</p>";
            } else {
                echo "<p>Session already active</p>";
            }
            
            // Test CSRF token loading
            $csrf_file = '../includes/csrf.php';
            if (file_exists($csrf_file)) {
                echo "<p>CSRF file exists: YES</p>";
                // Don't actually require it yet in case it causes issues
            } else {
                echo "<p style='color: red;'>CSRF file exists: NO - this could cause problems!</p>";
            }
        }
        
        echo "<h3>Now attempting to generate HTML output...</h3>";
        
        // Try to generate the HTML
        ob_start();
        ?>
        <div style="background: white; padding: 2rem; border-radius: 15px; margin-bottom: 2rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <p>Test HTML output - if you see this, HTML generation works!</p>
        </div>
        <?php
        $html_test = ob_get_clean();
        echo "<p>HTML generation test: " . (strlen($html_test) > 0 ? "✅ SUCCESS" : "❌ FAILED") . "</p>";
        echo "<div style='background: #f0f0f0; padding: 10px;'>$html_test</div>";
        
        // Now test with actual comment data
        echo "<h3>Testing full HTML generation with real data...</h3>";
        
        ob_start();
        foreach ($comments as $comment) {
            ?>
            <div style="background: white; padding: 2rem; border-radius: 15px; margin-bottom: 2rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <div style="font-weight: 600; color: #2c5234;"><?php echo htmlspecialchars($comment['username']); ?></div>
                    <div style="color: #666; font-size: 0.9rem;"><?php echo date('M j, Y', strtotime($comment['created_at'])); ?></div>
                </div>
                <p><?php echo htmlspecialchars($comment['comment_text']); ?></p>
            </div>
            <?php
        }
        $full_html = ob_get_clean();
        
        echo "<p>Full HTML length: " . strlen($full_html) . " characters</p>";
        
        if (strlen($full_html) > 0) {
            echo "<h3 style='color: green;'>✅ HTML generated successfully!</h3>";
            echo "<div style='border: 2px solid green; padding: 10px;'>";
            echo $full_html;
            echo "</div>";
        } else {
            echo "<h3 style='color: red;'>❌ HTML generation failed!</h3>";
        }
    }
    
} catch (Exception $e) {
    echo "<h3 style='color: red;'>ERROR: " . $e->getMessage() . "</h3>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

// Test if the actual file can be included
echo "<h2>Testing actual load-more-reviews.php file...</h2>";

$test_post = $_POST;
$_POST = ['course_slug' => 'avalon-golf-country-club', 'offset' => 5];

ob_start();
$_SERVER['REQUEST_METHOD'] = 'POST';
include 'load-more-reviews.php';
$actual_output = ob_get_clean();

$_POST = $test_post; // Restore

echo "<p>Actual file output length: " . strlen($actual_output) . " characters</p>";

if (strlen($actual_output) > 0) {
    echo "<h3>Output from load-more-reviews.php:</h3>";
    echo "<div style='border: 2px solid blue; padding: 10px; max-height: 400px; overflow: auto;'>";
    echo htmlspecialchars($actual_output);
    echo "</div>";
} else {
    echo "<h3 style='color: red;'>No output from load-more-reviews.php</h3>";
}
?>