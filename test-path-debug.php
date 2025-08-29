<?php
echo "<h1>Path Debug Test</h1>";

// Check current working directory
echo "<p>Current working directory: " . getcwd() . "</p>";

// Check if includes directory exists
echo "<p>includes/ directory exists: " . (is_dir('includes') ? 'YES' : 'NO') . "</p>";

// Check specific files
$files = [
    'includes/reviews-data.php',
    'includes/reviews-data-test.php',
    'includes/reviews-data-backup.php'
];

foreach ($files as $file) {
    $exists = file_exists($file);
    $readable = $exists ? is_readable($file) : false;
    echo "<p>$file - Exists: " . ($exists ? 'YES' : 'NO') . ", Readable: " . ($readable ? 'YES' : 'NO') . "</p>";
}

// Try to include and get basic info
echo "<h2>Testing includes/reviews-data.php:</h2>";
if (file_exists('includes/reviews-data.php')) {
    try {
        include_once 'includes/reviews-data.php';
        if (isset($reviews)) {
            echo "<p>SUCCESS: \$reviews variable is set with " . count($reviews) . " items</p>";
        } else {
            echo "<p>ERROR: \$reviews variable not set after including file</p>";
        }
    } catch (Exception $e) {
        echo "<p>EXCEPTION: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>File does not exist</p>";
}
?>