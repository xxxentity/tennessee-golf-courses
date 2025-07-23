<?php
echo "<h1>Server Debug Information</h1>";
echo "<h2>Current Request:</h2>";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "<br>";
echo "HTTP_HOST: " . $_SERVER['HTTP_HOST'] . "<br>";
echo "SERVER_NAME: " . $_SERVER['SERVER_NAME'] . "<br>";

echo "<h2>All Server Variables:</h2>";
echo "<pre>";
print_r($_SERVER);
echo "</pre>";

echo "<h2>Test Direct File Access:</h2>";
echo "This is debug-server.php - if you can see this, PHP is working<br>";

echo "<h2>File Existence Check:</h2>";
$files = ['index.php', 'courses.php', 'news.php', 'about.php'];
foreach($files as $file) {
    if (file_exists($file)) {
        echo "$file: EXISTS<br>";
    } else {
        echo "$file: MISSING<br>";
    }
}

echo "<h2>Directory Listing:</h2>";
$dir = scandir('.');
foreach($dir as $item) {
    if (substr($item, -4) === '.php') {
        echo "$item<br>";
    }
}
?>