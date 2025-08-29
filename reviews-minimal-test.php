<?php
// Minimal reviews test - strip down to just the essential display logic
require_once 'includes/reviews-data.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reviews Minimal Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .review { border: 2px solid blue; padding: 15px; margin: 15px 0; }
        .debug { background: yellow; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Reviews Minimal Test</h1>
    
    <div class="debug">
        <strong>DEBUG INFO:</strong><br>
        Current time: <?php echo date('Y-m-d H:i:s'); ?><br>
        Reviews variable exists: <?php echo isset($reviews) ? 'YES' : 'NO'; ?><br>
        Reviews count: <?php echo isset($reviews) ? count($reviews) : 'undefined'; ?><br>
    </div>
    
    <?php if (isset($reviews) && !empty($reviews)): ?>
        <h2>All Reviews (<?php echo count($reviews); ?> total):</h2>
        <?php foreach ($reviews as $index => $review): ?>
            <div class="review">
                <h3><?php echo htmlspecialchars($review['title']); ?></h3>
                <p><strong>Slug:</strong> <?php echo htmlspecialchars($review['slug']); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($review['date']); ?></p>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($review['category']); ?></p>
                <p><strong>Featured:</strong> <?php echo $review['featured'] ? 'YES' : 'NO'; ?></p>
                <p><strong>Author:</strong> <?php echo htmlspecialchars($review['author']); ?></p>
                <p><strong>Link:</strong> <a href="reviews/<?php echo htmlspecialchars($review['slug']); ?>">View Article</a></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="color: red;">No reviews found or reviews array is empty!</p>
    <?php endif; ?>
    
</body>
</html>