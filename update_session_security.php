<?php
/**
 * Update all PHP files to use secure sessions
 * This script will replace session_start() with secure session initialization
 */

$directories = [
    'courses',
    'auth',
    'user',
    'admin',
    'news',
    'reviews'
];

$updatedFiles = [];
$skippedFiles = [];

// Process each directory
foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        continue;
    }
    
    $files = glob($dir . '/*.php');
    
    foreach ($files as $file) {
        $content = file_get_contents($file);
        $originalContent = $content;
        
        // Skip if already using secure session
        if (strpos($content, 'session-security.php') !== false) {
            $skippedFiles[] = $file;
            continue;
        }
        
        // Replace session_start() at the beginning of files
        if (preg_match('/^<\?php\s*\n\s*session_start\(\);/m', $content)) {
            // Replace with secure session include
            $content = preg_replace(
                '/^(<\?php)\s*\n\s*session_start\(\);/m',
                '$1' . "\nrequire_once " . (strpos($file, 'courses/') === 0 ? "'../includes/init.php'" : "'includes/init.php'") . ";",
                $content
            );
            
            // Also update session variable access
            $content = str_replace('$_SESSION[\'logged_in\']', '$is_logged_in', $content);
            $content = str_replace('isset($_SESSION[\'logged_in\']) && $_SESSION[\'logged_in\'] === true', '$is_logged_in', $content);
            $content = str_replace('$_SESSION[\'user_id\']', '$user_id', $content);
            $content = str_replace('$_SESSION[\'username\']', '$username', $content);
            $content = str_replace('$_SESSION[\'first_name\']', '$first_name', $content);
            $content = str_replace('$_SESSION[\'last_name\']', '$last_name', $content);
            $content = str_replace('$_SESSION[\'email\']', '$email', $content);
            
            // Write back if changed
            if ($content !== $originalContent) {
                file_put_contents($file, $content);
                $updatedFiles[] = $file;
            }
        }
    }
}

echo "Session Security Update Complete!\n\n";
echo "Updated files (" . count($updatedFiles) . "):\n";
foreach ($updatedFiles as $file) {
    echo "  - $file\n";
}

echo "\nSkipped files (already updated) (" . count($skippedFiles) . "):\n";
foreach ($skippedFiles as $file) {
    echo "  - $file\n";
}

echo "\nNote: You may need to manually review and update some files that have complex session handling.\n";
?>