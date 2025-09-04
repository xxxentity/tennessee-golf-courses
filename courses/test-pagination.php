<?php
require_once '../config/database.php';

// No caching at all
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

echo "<h1>Pagination Debug Test</h1>";
echo "<p>Time: " . date('Y-m-d H:i:s') . "</p>";

$course_slug = 'avalon-golf-country-club';
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 5;

echo "<h2>Testing offset: $offset</h2>";

try {
    // Count total comments
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM course_comments WHERE course_slug = ?");
    $stmt->execute([$course_slug]);
    $total = $stmt->fetch()['total'];
    echo "<p>Total comments in database: <strong>$total</strong></p>";
    
    // Get ALL comments to analyze
    $stmt = $pdo->prepare("
        SELECT cc.*, u.username 
        FROM course_comments cc 
        JOIN users u ON cc.user_id = u.id 
        WHERE cc.course_slug = ?
        ORDER BY cc.created_at DESC
    ");
    $stmt->execute([$course_slug]);
    $all_comments = $stmt->fetchAll();
    
    echo "<h3>All Comments:</h3>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Username</th><th>Parent ID</th><th>Type</th><th>Text Preview</th></tr>";
    
    $main_reviews = [];
    foreach ($all_comments as $comment) {
        $parent_id = $comment['parent_comment_id'] ?? 'NULL';
        $is_reply = !empty($comment['parent_comment_id']) && $comment['parent_comment_id'] > 0;
        $type = $is_reply ? 'REPLY' : 'MAIN REVIEW';
        
        if (!$is_reply) {
            $main_reviews[] = $comment;
        }
        
        echo "<tr>";
        echo "<td>{$comment['id']}</td>";
        echo "<td>{$comment['username']}</td>";
        echo "<td>$parent_id</td>";
        echo "<td style='background: " . ($is_reply ? '#ffcccc' : '#ccffcc') . "'>$type</td>";
        echo "<td>" . substr($comment['comment_text'], 0, 50) . "...</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h3>Summary:</h3>";
    echo "<p>Total main reviews: <strong>" . count($main_reviews) . "</strong></p>";
    echo "<p>Total replies: <strong>" . (count($all_comments) - count($main_reviews)) . "</strong></p>";
    
    // Test pagination
    echo "<h3>Pagination Test (offset=$offset, limit=5):</h3>";
    $paginated = array_slice($main_reviews, $offset, 5);
    echo "<p>Should return <strong>" . count($paginated) . "</strong> reviews</p>";
    
    if (count($paginated) > 0) {
        echo "<p style='color: green;'>✅ PAGINATION WORKS! Found " . count($paginated) . " reviews at offset $offset</p>";
        echo "<h4>Reviews that should load:</h4>";
        echo "<ol>";
        foreach ($paginated as $review) {
            echo "<li>ID {$review['id']} by {$review['username']}: " . substr($review['comment_text'], 0, 50) . "...</li>";
        }
        echo "</ol>";
    } else {
        echo "<p style='color: red;'>❌ NO REVIEWS at offset $offset</p>";
        echo "<p>This means you've loaded all reviews already.</p>";
    }
    
    // Test different offsets
    echo "<h3>Test Other Offsets:</h3>";
    echo "<ul>";
    for ($i = 0; $i <= 20; $i += 5) {
        $test_slice = array_slice($main_reviews, $i, 5);
        $count = count($test_slice);
        echo "<li><a href='?offset=$i'>Offset $i</a>: Would return $count reviews</li>";
    }
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>Database Error: " . $e->getMessage() . "</p>";
}
?>

<h3>Test Load More Button:</h3>
<button onclick="testLoadMore()">Test Load More Reviews (Offset 5)</button>
<div id="test-result"></div>

<script>
function testLoadMore() {
    console.log('Testing load more with offset 5...');
    fetch('load-more-reviews.php?nocache=' + Date.now(), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'course_slug=avalon-golf-country-club&offset=5'
    })
    .then(response => response.text())
    .then(html => {
        console.log('Response:', html);
        document.getElementById('test-result').innerHTML = 
            '<h4>Response from load-more-reviews.php:</h4>' +
            '<pre style="background: #f0f0f0; padding: 10px; max-height: 300px; overflow: auto;">' + 
            (html || '[EMPTY RESPONSE]') + 
            '</pre>' +
            '<p>Response length: ' + html.length + ' characters</p>';
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('test-result').innerHTML = '<p style="color: red;">Error: ' + error + '</p>';
    });
}
</script>