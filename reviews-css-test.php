<?php
// Test reviews with the exact same CSS and HTML structure as reviews.php
require_once 'includes/reviews-data.php';

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
    <title>Reviews CSS Test</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <style>
        /* Copy the exact CSS variables and styles from reviews.php */
        :root {
            --primary-color: #2563eb;
            --secondary-color: #dc2626;
            --text-dark: #1f2937;
            --text-gray: #6b7280;
            --bg-white: #ffffff;
            --bg-light: #f9fafb;
            --border-color: #e5e7eb;
            --shadow-light: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            --shadow-medium: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; 
            margin: 0; 
            padding: 20px;
            background: var(--bg-light);
        }
        
        .debug { 
            background: red; 
            color: white; 
            padding: 20px; 
            margin: 20px; 
            font-size: 20px; 
        }
        
        /* Exact CSS from reviews.php */
        .reviews-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .review-card {
            background: var(--bg-white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-light);
            transition: all 0.3s ease;
        }
        
        .review-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
        }
        
        .review-image {
            height: 200px;
            overflow: hidden;
            position: relative;
        }
        
        .review-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .review-content {
            padding: 1.5rem;
        }
        
        .review-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            flex-wrap: wrap;
        }
        
        .review-date {
            color: var(--text-gray);
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .review-category {
            background: var(--primary-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
        }
        
        .review-title {
            font-size: 1.3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            line-height: 1.4;
        }
        
        .review-excerpt {
            color: var(--text-dark);
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        
        .review-author {
            margin-bottom: 1.5rem;
        }
        
        .author-name {
            color: var(--text-gray);
            font-size: 0.9rem;
            font-style: italic;
        }
    </style>
</head>
<body>
    <h1>Reviews CSS Test - Exact Styling from reviews.php</h1>
    
    <div class="debug">
        <p>🔴 CSS DEBUG MODE</p>
        <p>Filtered reviews: <?php echo count($filtered_reviews); ?></p>
        <p>Testing exact CSS from reviews.php</p>
    </div>
    
    <?php if (!empty($filtered_reviews)): ?>
        <div class="reviews-grid">
            <?php 
            $reviews_per_page = 6;
            foreach ($filtered_reviews as $index => $review): 
                if ($index >= $reviews_per_page) continue; // Only show first 6
            ?>
                <article class="review-card" data-review-index="<?php echo $index; ?>">
                    <div class="review-image">
                        <img src="<?php echo htmlspecialchars($review['image']); ?>" 
                             alt="<?php echo htmlspecialchars($review['title']); ?>"
                             onerror="this.style.display='none'; this.parentNode.innerHTML='<div style=\'padding:50px; text-align:center; background:#f0f0f0;\'>Image: <?php echo htmlspecialchars($review['image']); ?></div>';">
                    </div>
                    <div class="review-content">
                        <div class="review-meta">
                            <span class="review-date">
                                <i class="fas fa-calendar-alt"></i>
                                <?php echo date('M j, Y', strtotime($review['date'])); ?>
                            </span>
                            <span class="review-category"><?php echo htmlspecialchars($review['category']); ?></span>
                        </div>
                        <h3 class="review-title"><?php echo htmlspecialchars($review['title']); ?></h3>
                        <p class="review-excerpt"><?php echo htmlspecialchars($review['excerpt']); ?></p>
                        <div class="review-author">
                            <span class="author-name">By <?php echo htmlspecialchars($review['author']); ?></span>
                        </div>
                        <p style="background: yellow; padding: 5px; font-size: 12px;">
                            <strong>Index:</strong> <?php echo $index; ?> | 
                            <strong>Card should be visible</strong>
                        </p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p style="color: red; font-size: 24px;">NO REVIEWS FOUND!</p>
    <?php endif; ?>
    
    <div style="background: green; color: white; padding: 20px; margin: 20px;">
        <h2>✅ If you can see this and the review cards above, the CSS works fine!</h2>
        <p>This means the issue is elsewhere in the main reviews.php file.</p>
    </div>
    
</body>
</html>