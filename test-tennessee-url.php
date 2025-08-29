<?php
echo "<h1>Tennessee Golf Guide URL Test</h1>";
echo "<p>Current time: " . date('Y-m-d H:i:s') . "</p>";

// Test the exact URL path
echo "<h2>File Tests:</h2>";
echo "<p>File exists: " . (file_exists('reviews/complete-guide-tennessee-golf-2025.php') ? 'YES' : 'NO') . "</p>";
echo "<p>File readable: " . (is_readable('reviews/complete-guide-tennessee-golf-2025.php') ? 'YES' : 'NO') . "</p>";

// Test the URL
echo "<h2>URL Tests:</h2>";
echo "<p><a href='/reviews/complete-guide-tennessee-golf-2025'>Test Clean URL: /reviews/complete-guide-tennessee-golf-2025</a></p>";
echo "<p><a href='reviews/complete-guide-tennessee-golf-2025'>Test Relative URL: reviews/complete-guide-tennessee-golf-2025</a></p>";
echo "<p><a href='/reviews/complete-guide-tennessee-golf-2025.php'>Test Direct PHP: /reviews/complete-guide-tennessee-golf-2025.php</a></p>";

// Test include
echo "<h2>Include Test:</h2>";
try {
    $content = file_get_contents('reviews/complete-guide-tennessee-golf-2025.php', false, null, 0, 500);
    echo "<p>✅ File content preview: " . htmlspecialchars(substr($content, 0, 100)) . "...</p>";
} catch (Exception $e) {
    echo "<p>❌ Error reading file: " . $e->getMessage() . "</p>";
}
?>