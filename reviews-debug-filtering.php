<?php
// Debug the exact filtering logic used in reviews.php
require_once 'includes/reviews-data.php';

// Get search query if provided (same as reviews.php)
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

// Filter reviews based on search and category (same as reviews.php)
$filtered_reviews = $reviews;
if (!empty($search_query)) {
    $filtered_reviews = array_filter($filtered_reviews, function($review) use ($search_query) {
        return stripos($review['title'], $search_query) !== false || 
               stripos($review['excerpt'], $search_query) !== false;
    });
}
if (!empty($category_filter)) {
    $filtered_reviews = array_filter($filtered_reviews, function($review) use ($category_filter) {
        return $review['category'] === $category_filter;
    });
}

// Re-index the filtered array to start from 0 (same as reviews.php)
$filtered_reviews = array_values($filtered_reviews);

// Get featured reviews for homepage (same as reviews.php)
$featured_reviews = array_slice(array_filter($reviews, function($review) {
    return isset($review['featured']) && $review['featured'];
}), 0, 3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reviews Filtering Debug</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .debug { background: yellow; padding: 10px; margin: 10px 0; border: 1px solid orange; }
        .section { border: 2px solid green; padding: 15px; margin: 15px 0; }
    </style>
</head>
<body>
    <h1>Reviews Filtering Debug</h1>
    
    <div class="debug">
        <strong>URL PARAMETERS:</strong><br>
        Full Query String: <?php echo $_SERVER['QUERY_STRING'] ?? 'NONE'; ?><br>
        Search Query: '<?php echo htmlspecialchars($search_query); ?>' (empty: <?php echo empty($search_query) ? 'YES' : 'NO'; ?>)<br>
        Category Filter: '<?php echo htmlspecialchars($category_filter); ?>' (empty: <?php echo empty($category_filter) ? 'YES' : 'NO'; ?>)<br>
    </div>

    <div class="debug">
        <strong>ARRAY COUNTS:</strong><br>
        Original reviews: <?php echo isset($reviews) ? count($reviews) : 'undefined'; ?><br>
        Filtered reviews: <?php echo isset($filtered_reviews) ? count($filtered_reviews) : 'undefined'; ?><br>
        Featured reviews: <?php echo isset($featured_reviews) ? count($featured_reviews) : 'undefined'; ?><br>
    </div>
    
    <div class="section">
        <h2>Original Reviews (<?php echo count($reviews); ?>):</h2>
        <?php foreach ($reviews as $i => $review): ?>
            <p><?php echo ($i+1); ?>. <?php echo htmlspecialchars($review['title']); ?> (<?php echo $review['category']; ?>)</p>
        <?php endforeach; ?>
    </div>
    
    <div class="section">
        <h2>Filtered Reviews (<?php echo count($filtered_reviews); ?>):</h2>
        <?php if (!empty($filtered_reviews)): ?>
            <?php foreach ($filtered_reviews as $i => $review): ?>
                <p><?php echo ($i+1); ?>. <?php echo htmlspecialchars($review['title']); ?> (<?php echo $review['category']; ?>)</p>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: red;">NO FILTERED REVIEWS FOUND!</p>
        <?php endif; ?>
    </div>
    
    <div class="section">
        <h2>Featured Reviews (<?php echo count($featured_reviews); ?>):</h2>
        <?php foreach ($featured_reviews as $i => $review): ?>
            <p><?php echo ($i+1); ?>. <?php echo htmlspecialchars($review['title']); ?> (Featured: <?php echo $review['featured'] ? 'YES' : 'NO'; ?>)</p>
        <?php endforeach; ?>
    </div>
    
</body>
</html>