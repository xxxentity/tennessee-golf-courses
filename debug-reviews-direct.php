<?php
// Direct debug test - bypass all other code
echo "<h1>Direct Reviews Data Test</h1>";
echo "<p>Server time: " . date('Y-m-d H:i:s') . "</p>";

// Test if the file exists
$dataFile = 'includes/reviews-data.php';
echo "<p>File exists: " . (file_exists($dataFile) ? 'YES' : 'NO') . "</p>";

// Include and output the data
require_once $dataFile;

echo "<h2>Reviews Array Contents:</h2>";
echo "<p>Reviews count: " . count($reviews) . "</p>";

foreach ($reviews as $index => $review) {
    echo "<div style='border: 2px solid red; padding: 10px; margin: 10px;'>";
    echo "<h3>Review #" . ($index + 1) . "</h3>";
    echo "<p><strong>Title:</strong> " . htmlspecialchars($review['title']) . "</p>";
    echo "<p><strong>Slug:</strong> " . htmlspecialchars($review['slug']) . "</p>";
    echo "<p><strong>Date:</strong> " . htmlspecialchars($review['date']) . "</p>";
    echo "<p><strong>Category:</strong> " . htmlspecialchars($review['category']) . "</p>";
    echo "<p><strong>Featured:</strong> " . ($review['featured'] ? 'true' : 'false') . "</p>";
    echo "<p><strong>Author:</strong> " . htmlspecialchars($review['author']) . "</p>";
    echo "</div>";
}
?>