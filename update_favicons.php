<?php
/**
 * Favicon Update Script
 * Replaces manual favicon versioning with automatic include system
 */

// Get all PHP files that contain favicon references
$files_to_update = [
    'news.php',
    'index.php',
    'auth/login.php',
    'auth/register.php',
    'auth/reset-password.php',
    'auth/registration-success.php',
    'auth/forgot-password.php',
    'courses.php',
    'community.php',
    'admin/login.php',
    'admin/dashboard.php',
    'admin/settings.php',
    'admin/users.php',
    'admin/security.php',
    'events.php',
    'user/security-dashboard.php',
    'user/edit-profile.php',
    'user/profile.php',
    'contact.php',
    'newsletter-unsubscribe.php',
    'privacy-policy.php',
    'terms-of-service.php',
    'reviews.php',
    'about.php',
    'media.php',
    'maps.php'
];

// Get all course files
$course_files = glob('courses/*.php');
foreach ($course_files as $file) {
    $files_to_update[] = $file;
}

// Get all news files
$news_files = glob('news/*.php');
foreach ($news_files as $file) {
    $files_to_update[] = $file;
}

// Get all review files
$review_files = glob('reviews/*.php');
foreach ($review_files as $file) {
    $files_to_update[] = $file;
}

$updated_count = 0;
$error_count = 0;

echo "Starting favicon update...\n";

foreach ($files_to_update as $file) {
    if (!file_exists($file)) {
        echo "Skipping missing file: $file\n";
        continue;
    }
    
    $content = file_get_contents($file);
    if ($content === false) {
        echo "Error reading file: $file\n";
        $error_count++;
        continue;
    }
    
    // Check if file has favicon references
    if (!preg_match('/favicon|tab-logo\.webp/', $content)) {
        continue;
    }
    
    $original_content = $content;
    
    // Replace old favicon links with include
    $patterns = [
        '/\s*<!-- Favicon -->\s*\n\s*<link rel="icon" type="image\/webp" href="\/images\/logos\/tab-logo\.webp\?v=\d+">\s*\n\s*<link rel="shortcut icon" href="\/images\/logos\/tab-logo\.webp\?v=\d+">/m',
        '/\s*<link rel="icon" type="image\/webp" href="\/images\/logos\/tab-logo\.webp\?v=\d+">\s*\n\s*<link rel="shortcut icon" href="\/images\/logos\/tab-logo\.webp\?v=\d+">/m',
        '/\s*<link rel="icon" type="image\/webp" href="\/images\/logos\/tab-logo\.webp\?v=\d+">/m',
        '/\s*<link rel="shortcut icon" href="\/images\/logos\/tab-logo\.webp\?v=\d+">/m'
    ];
    
    // Determine the correct include path based on file location
    $include_path = 'includes/favicon.php';
    if (strpos($file, 'courses/') === 0) {
        $include_path = '../includes/favicon.php';
    } elseif (strpos($file, 'news/') === 0) {
        $include_path = '../includes/favicon.php';
    } elseif (strpos($file, 'reviews/') === 0) {
        $include_path = '../includes/favicon.php';
    } elseif (strpos($file, 'auth/') === 0) {
        $include_path = '../includes/favicon.php';
    } elseif (strpos($file, 'admin/') === 0) {
        $include_path = '../includes/favicon.php';
    } elseif (strpos($file, 'user/') === 0) {
        $include_path = '../includes/favicon.php';
    }
    
    $replacement = "\n    <?php include '$include_path'; ?>";
    
    foreach ($patterns as $pattern) {
        $content = preg_replace($pattern, $replacement, $content);
    }
    
    // Only update if content changed
    if ($content !== $original_content) {
        if (file_put_contents($file, $content) !== false) {
            echo "Updated: $file\n";
            $updated_count++;
        } else {
            echo "Error writing file: $file\n";
            $error_count++;
        }
    }
}

echo "\nFavicon update complete!\n";
echo "Files updated: $updated_count\n";
echo "Errors: $error_count\n";
?>