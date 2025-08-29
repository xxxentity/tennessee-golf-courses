<?php
require_once '../includes/init.php';
require_once '../includes/profile-helpers.php';
require_once '../includes/seo.php';

$article_data = [
    'title' => 'Test Article',
    'description' => 'Test description',
    'image' => '/test.jpg',
    'type' => 'article',
    'author' => 'Test Author',
    'date' => '2025-08-25',
    'category' => 'Tournament News'
];

SEO::setupArticlePage($article_data);
$article_slug = 'test-article';
$article_title = 'Test Article';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Article</title>
</head>
<body>
    <h1>Test Article Works!</h1>
    <p>This is a fresh, simple news article page.</p>
    
    <?php include '../includes/navigation.php'; ?>
    
    <p>Navigation included successfully!</p>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>