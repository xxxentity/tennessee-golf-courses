<?php
// Test the remaining files that the news article uses
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Testing Remaining Files</h1>";

// Load the working files first
require_once 'includes/init.php';
echo "<p>✓ init.php loaded</p>";

// Test profile-helpers.php
echo "<p>1. Testing profile-helpers.php...</p>";
try {
    require_once 'includes/profile-helpers.php';
    echo "<p>✓ profile-helpers.php loaded</p>";
} catch (Throwable $e) {
    echo "<p>✗ ERROR in profile-helpers.php: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . " Line: " . $e->getLine() . "</p>";
    exit;
}

// Test seo.php
echo "<p>2. Testing seo.php...</p>";
try {
    require_once 'includes/seo.php';
    echo "<p>✓ seo.php loaded</p>";
} catch (Throwable $e) {
    echo "<p>✗ ERROR in seo.php: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . " Line: " . $e->getLine() . "</p>";
    exit;
}

// Test SEO setup
echo "<p>3. Testing SEO setup...</p>";
try {
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
    echo "<p>✓ SEO setup completed</p>";
} catch (Throwable $e) {
    echo "<p>✗ ERROR in SEO setup: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . " Line: " . $e->getLine() . "</p>";
    exit;
}

// Test threaded comments variables
echo "<p>4. Testing threaded comments setup...</p>";
try {
    $article_slug = 'test-article';
    $article_title = 'Test Article Title';
    echo "<p>✓ Article variables set</p>";
    
    // Test if we can include threaded comments
    ob_start(); // Capture output to prevent HTML from showing
    include 'includes/threaded-comments.php';
    $comments_output = ob_get_clean();
    echo "<p>✓ threaded-comments.php included successfully</p>";
    
} catch (Throwable $e) {
    echo "<p>✗ ERROR in threaded comments: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . " Line: " . $e->getLine() . "</p>";
    exit;
}

// Test footer
echo "<p>5. Testing footer...</p>";
try {
    ob_start();
    include 'includes/footer.php';
    $footer_output = ob_get_clean();
    echo "<p>✓ footer.php included successfully</p>";
} catch (Throwable $e) {
    echo "<p>✗ ERROR in footer: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . " Line: " . $e->getLine() . "</p>";
    exit;
}

echo "<h2>All files loaded successfully!</h2>";
echo "<p>The issue might be something else entirely. Let me test the actual news file structure...</p>";

// Test navigation include
echo "<p>6. Testing navigation.php...</p>";
try {
    ob_start();
    include 'includes/navigation.php';
    $nav_output = ob_get_clean();
    echo "<p>✓ navigation.php included successfully</p>";
} catch (Throwable $e) {
    echo "<p>✗ ERROR in navigation: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . " Line: " . $e->getLine() . "</p>";
    exit;
}

echo "<h2>EVERYTHING WORKS! The issue might be in the HTML structure or output buffering.</h2>";
?>