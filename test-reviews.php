<?php
// Test script to debug reviews data
require_once 'includes/reviews-data.php';

echo "Total reviews: " . count($reviews) . "<br><br>";

foreach ($reviews as $index => $review) {
    echo "Review #" . ($index + 1) . ":<br>";
    echo "Title: " . htmlspecialchars($review['title']) . "<br>";
    echo "Slug: " . htmlspecialchars($review['slug']) . "<br>";
    echo "Date: " . htmlspecialchars($review['date']) . "<br>";
    echo "Category: " . htmlspecialchars($review['category']) . "<br>";
    echo "Image: " . htmlspecialchars($review['image']) . "<br>";
    echo "Featured: " . ($review['featured'] ? 'true' : 'false') . "<br>";
    echo "Author: " . htmlspecialchars($review['author']) . "<br>";
    echo "<hr>";
}
?>