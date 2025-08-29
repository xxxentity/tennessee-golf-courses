<?php
// Test ONLY the PHP portion of the news article
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Testing News Article PHP Only</h1>";

try {
    echo "<p>1. Loading includes...</p>";
    require_once 'includes/init.php';
    require_once 'includes/profile-helpers.php';
    require_once 'includes/seo.php';
    echo "<p>✓ All includes loaded</p>";

    echo "<p>2. Setting article data...</p>";
    $article_data = [
        'title' => 'Tour Championship 2025: Complete Atlanta Tournament Recap and Tommy Fleetwood\'s Historic First Win',
        'description' => 'Complete recap of the 2025 Tour Championship featuring Tommy Fleetwood\'s long-awaited first PGA Tour victory and FedEx Cup triumph at East Lake Golf Club in Atlanta.',
        'image' => '/images/news/tour-championship-2025-atlanta-complete-tournament-recap-fleetwood-first-win/main.webp',
        'type' => 'article',
        'author' => 'Cole Harrington',
        'date' => '2025-08-25',
        'category' => 'Tournament News'
    ];
    echo "<p>✓ Article data set</p>";

    echo "<p>3. Setting up SEO...</p>";
    SEO::setupArticlePage($article_data);
    echo "<p>✓ SEO setup complete</p>";

    echo "<p>4. Setting article variables...</p>";
    $article_slug = 'tour-championship-2025-atlanta-complete-tournament-recap-fleetwood-first-win';
    $article_title = 'Tour Championship 2025: Complete Atlanta Tournament Recap and Tommy Fleetwood\'s Historic First Win';
    echo "<p>✓ Article variables set</p>";

    echo "<p>5. Testing SEO meta tag generation...</p>";
    $metaTags = SEO::generateMetaTags();
    echo "<p>✓ Meta tags generated (length: " . strlen($metaTags) . " chars)</p>";
    
    echo "<h2>ALL PHP PROCESSING SUCCESSFUL!</h2>";
    echo "<p>The issue is not in the PHP logic.</p>";
    
} catch (Throwable $e) {
    echo "<p><strong>ERROR:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>File:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>