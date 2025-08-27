<?php
/**
 * Automated Script to Fix Session Conflicts
 * Replaces session_start() with consistent init.php includes across all files
 */

$basePath = __DIR__;
$filesFixed = 0;
$errors = [];

// Get all PHP files with session_start()
$command = "grep -l '^session_start();' " . escapeshellarg($basePath) . "/*.php " . 
           escapeshellarg($basePath) . "/*/*.php " . 
           escapeshellarg($basePath) . "/*/*/*.php 2>/dev/null";

$output = [];
exec($command, $output);

echo "<h2>Session Conflict Fix Script</h2>\n";
echo "<p>Found " . count($output) . " files to fix...</p>\n";

foreach ($output as $filepath) {
    if (!file_exists($filepath)) {
        continue;
    }
    
    $content = file_get_contents($filepath);
    if ($content === false) {
        $errors[] = "Could not read: $filepath";
        continue;
    }
    
    // Determine the correct relative path to init.php based on file location
    $relativePath = '';
    $pathParts = explode('/', str_replace($basePath, '', $filepath));
    $depth = count(array_filter($pathParts)) - 1; // -1 for the filename itself
    
    for ($i = 0; $i < $depth; $i++) {
        $relativePath .= '../';
    }
    $relativePath .= 'includes/init.php';
    
    // Skip admin files and auth files - they have special session handling
    if (strpos($filepath, '/admin/') !== false || strpos($filepath, '/auth/') !== false) {
        echo "<p>Skipping admin/auth file: " . basename($filepath) . "</p>\n";
        continue;
    }
    
    // Replace session_start() with init.php include
    $originalContent = $content;
    
    // Pattern 1: <?php\nsession_start();
    $content = preg_replace(
        '/^<\?php\s*\n\s*session_start\(\);/m',
        '<?php' . "\nrequire_once '$relativePath';",
        $content
    );
    
    // Pattern 2: session_start() at the beginning of a line
    $content = preg_replace(
        '/^session_start\(\);/m',
        "require_once '$relativePath';",
        $content
    );
    
    if ($content !== $originalContent) {
        if (file_put_contents($filepath, $content) !== false) {
            $filesFixed++;
            $relativeFilePath = str_replace($basePath . '/', '', $filepath);
            echo "<p style='color: green;'>✅ Fixed: $relativeFilePath</p>\n";
        } else {
            $errors[] = "Could not write: $filepath";
        }
    } else {
        $relativeFilePath = str_replace($basePath . '/', '', $filepath);
        echo "<p style='color: orange;'>⚠️  No changes needed: $relativeFilePath</p>\n";
    }
}

echo "<h3>Summary:</h3>\n";
echo "<p><strong>Files Fixed:</strong> $filesFixed</p>\n";

if (!empty($errors)) {
    echo "<h4>Errors:</h4>\n";
    foreach ($errors as $error) {
        echo "<p style='color: red;'>❌ $error</p>\n";
    }
}

echo "<p><strong>Done!</strong> All session conflicts should now be resolved.</p>\n";
echo "<p>You can now delete this script file: fix_all_sessions.php</p>\n";
?>