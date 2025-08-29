<?php
// Disable all caching
header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');

require_once '../includes/init.php';
require_once '../includes/profile-helpers.php';
require_once '../includes/seo.php';

$article_data = [
    'title' => 'Tour Championship 2025: Complete Atlanta Tournament Recap and Tommy Fleetwood\'s Historic First Win',
    'description' => 'Complete recap of the 2025 Tour Championship.',
    'image' => '/images/news/tour-championship-2025-atlanta-complete-tournament-recap-fleetwood-first-win/main.webp',
    'type' => 'article',
    'author' => 'Cole Harrington',
    'date' => '2025-08-25',
    'category' => 'Tournament News'
];

SEO::setupArticlePage($article_data);
$article_slug = 'tour-championship-2025-atlanta-complete-tournament-recap-fleetwood-first-win';
$article_title = 'Tour Championship 2025: Complete Atlanta Tournament Recap and Tommy Fleetwood\'s Historic First Win';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour Championship 2025</title>
</head>
<body>
    <h1>NEW FILE - PHP WORKS!</h1>
    <p>Timestamp: <?php echo date('Y-m-d H:i:s'); ?></p>
    <p>Article title: <?php echo htmlspecialchars($article_title); ?></p>
    <p>This is a completely new file that bypasses all caching.</p>
</body>
</html>