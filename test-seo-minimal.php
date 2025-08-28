<?php
// Minimal SEO test - just basic meta tags, no complex classes
error_reporting(E_ALL);
ini_set('display_errors', 1);

$article_title = 'Test Article with Basic SEO';
$article_description = 'This is a test article to verify basic SEO implementation works without breaking anything.';
$article_image = '/images/news/test/main.webp';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Basic SEO Meta Tags -->
    <title><?php echo htmlspecialchars($article_title); ?> - Tennessee Golf Courses</title>
    <meta name="description" content="<?php echo htmlspecialchars($article_description); ?>">
    <meta name="keywords" content="golf news, Tennessee golf, test article">
    <link rel="canonical" href="https://tennesseegolfcourses.com/test-seo-minimal">
    
    <!-- Open Graph Tags -->
    <meta property="og:type" content="article">
    <meta property="og:title" content="<?php echo htmlspecialchars($article_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($article_description); ?>">
    <meta property="og:url" content="https://tennesseegolfcourses.com/test-seo-minimal">
    <meta property="og:image" content="https://tennesseegolfcourses.com<?php echo htmlspecialchars($article_image); ?>">
    <meta property="og:site_name" content="Tennessee Golf Courses">
    
    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($article_title); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($article_description); ?>">
    <meta name="twitter:image" content="https://tennesseegolfcourses.com<?php echo htmlspecialchars($article_image); ?>">
    
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
    <h1>🧪 Minimal SEO Test</h1>
    <p><strong>Title:</strong> <?php echo htmlspecialchars($article_title); ?></p>
    <p><strong>Description:</strong> <?php echo htmlspecialchars($article_description); ?></p>
    <p><strong>Status:</strong> If you can see this, basic SEO meta tags are working!</p>
    
    <h2>What This Tests:</h2>
    <ul>
        <li>✅ Basic meta tags (title, description, keywords)</li>
        <li>✅ Open Graph tags for social media</li>
        <li>✅ Twitter Card tags</li>
        <li>✅ Canonical URL</li>
        <li>✅ No complex PHP classes or includes</li>
    </ul>
    
    <p><em>This approach is safe and won't break existing functionality.</em></p>
</body>
</html>