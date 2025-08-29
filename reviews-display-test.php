<?php
// Test the exact HTML display logic from reviews.php
require_once 'includes/reviews-data.php';

// Same filtering logic as reviews.php
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
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
$filtered_reviews = array_values($filtered_reviews);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reviews Display Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .debug { background: red; color: white; padding: 20px; margin: 20px; font-size: 16px; }
        .reviews-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem; margin-bottom: 3rem; }
        .review-card { background: white; border: 2px solid blue; border-radius: 15px; overflow: hidden; margin: 10px; padding: 15px; }
        .review-image { height: 200px; overflow: hidden; background: #f0f0f0; display: flex; align-items: center; justify-content: center; }
        .review-content { padding: 1rem; }
        .review-title { font-size: 1.2rem; margin: 10px 0; color: blue; }
        .review-excerpt { margin: 10px 0; }
        .review-meta { margin: 10px 0; font-size: 0.9rem; }
    </style>
</head>
<body>
    <h1>Reviews Display Test - Using Exact HTML Logic</h1>
    
    <!-- Exact debug section from reviews.php -->
    <div class="debug">
        <p>🔴 DEBUG MODE ACTIVE</p>
        <p>Current time: <?php echo date('Y-m-d H:i:s'); ?></p>
        <p>Reviews variable exists: <?php echo isset($reviews) ? 'YES' : 'NO'; ?></p>
        <p>Reviews count: <?php echo isset($reviews) ? count($reviews) : 'undefined'; ?></p>
        <p>Filtered reviews count: <?php echo isset($filtered_reviews) ? count($filtered_reviews) : 'undefined'; ?></p>
    </div>
    
    <!-- Test the conditional logic -->
    <?php if (!empty($filtered_reviews)): ?>
        <h2>✅ Conditional passed - filtered_reviews is NOT empty</h2>
        <div class="reviews-grid">
            <?php 
            $reviews_per_page = 6;
            $total_reviews = count($filtered_reviews);
            $initial_reviews = array_slice($filtered_reviews, 0, $reviews_per_page);
            ?>
            <p><strong>Reviews per page:</strong> <?php echo $reviews_per_page; ?></p>
            <p><strong>Total reviews:</strong> <?php echo $total_reviews; ?></p>
            <p><strong>Initial reviews count:</strong> <?php echo count($initial_reviews); ?></p>
            
            <!-- Exact foreach loop from reviews.php -->
            <?php foreach ($filtered_reviews as $index => $review): ?>
                <article class="review-card" data-review-index="<?php echo $index; ?>" style="<?php echo $index >= $reviews_per_page ? 'display: none;' : ''; ?>">
                    <div class="review-image">
                        <p>IMAGE: <?php echo htmlspecialchars($review['image']); ?></p>
                    </div>
                    <div class="review-content">
                        <div class="review-meta">
                            <span>📅 <?php echo date('M j, Y', strtotime($review['date'])); ?></span>
                            <span>📁 <?php echo htmlspecialchars($review['category']); ?></span>
                        </div>
                        <h3 class="review-title"><?php echo htmlspecialchars($review['title']); ?></h3>
                        <p class="review-excerpt"><?php echo htmlspecialchars($review['excerpt']); ?></p>
                        <p><strong>Index:</strong> <?php echo $index; ?> | <strong>Display:</strong> <?php echo $index >= $reviews_per_page ? 'HIDDEN' : 'VISIBLE'; ?></p>
                        <p><strong>Author:</strong> <?php echo htmlspecialchars($review['author']); ?></p>
                        <p><strong>Link:</strong> reviews/<?php echo htmlspecialchars($review['slug']); ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        
        <!-- Load More Button logic -->
        <?php if ($total_reviews > $reviews_per_page): ?>
        <div style="text-align: center; margin: 20px; background: green; color: white; padding: 10px;">
            <p>✅ Load More Button Should Show (<?php echo $total_reviews; ?> > <?php echo $reviews_per_page; ?>)</p>
        </div>
        <?php else: ?>
        <div style="text-align: center; margin: 20px; background: orange; color: white; padding: 10px;">
            <p>⚠️ Load More Button Should NOT Show (<?php echo $total_reviews; ?> <= <?php echo $reviews_per_page; ?>)</p>
        </div>
        <?php endif; ?>
        
    <?php else: ?>
        <div style="background: red; color: white; padding: 20px;">
            <h2>❌ Conditional FAILED - filtered_reviews is empty!</h2>
            <p>This should NOT happen based on our previous tests!</p>
        </div>
    <?php endif; ?>
    
</body>
</html>