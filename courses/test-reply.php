<?php
// Simple reply test - bypass all complexity
require_once '../config/database.php';

echo "<h2>Reply Test Results</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h3>POST Data Received:</h3>";
    echo "<pre>" . print_r($_POST, true) . "</pre>";
    
    $reply_text = $_POST['reply_text'] ?? '';
    $parent_comment_id = $_POST['parent_comment_id'] ?? '';
    
    echo "<p>Reply Text: '$reply_text'</p>";
    echo "<p>Parent ID: '$parent_comment_id'</p>";
    
    if (!empty($reply_text) && !empty($parent_comment_id)) {
        try {
            // Simple insert - hardcode values for testing
            $stmt = $pdo->prepare("INSERT INTO course_comments (user_id, course_slug, course_name, comment_text, parent_comment_id) VALUES (?, ?, ?, ?, ?)");
            $result = $stmt->execute([1, 'avalon-golf-country-club', 'Avalon Golf & Country Club', $reply_text, $parent_comment_id]);
            
            if ($result) {
                echo "<p style='color: green;'>✅ SUCCESS: Reply saved with ID " . $pdo->lastInsertId() . "</p>";
            } else {
                echo "<p style='color: red;'>❌ FAILED: Database insert failed</p>";
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ ERROR: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Missing data: reply_text or parent_comment_id</p>";
    }
} else {
    echo "<p>No POST data - try submitting a reply form</p>";
}

// Show current comments count
try {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM course_comments WHERE course_slug = 'avalon-golf-country-club'");
    $count = $stmt->fetch()['total'];
    echo "<p>Total comments in database: $count</p>";
} catch (Exception $e) {
    echo "<p>Error counting: " . $e->getMessage() . "</p>";
}
?>