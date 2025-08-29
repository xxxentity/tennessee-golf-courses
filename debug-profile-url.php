<?php
// Debug profile page URL detection
require_once 'includes/navigation.php'; // This will run the same logic

echo "<h1>Profile URL Debug</h1>";
echo "<p>Current REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NONE') . "</p>";
echo "<p>Current \$current_page: " . $current_page . "</p>";

// Test the conditions
echo "<h2>URL Detection Tests:</h2>";
echo "<p>strpos(\$current_page, '/user') === 0: " . (strpos($current_page, '/user') === 0 ? 'TRUE' : 'FALSE') . "</p>";
echo "<p>\$is_main_page: " . ($is_main_page ? 'TRUE' : 'FALSE') . "</p>";

// Individual course detection
echo "<p>\$is_individual_course: " . ($is_individual_course ? 'TRUE' : 'FALSE') . "</p>";

// Check all conditions
echo "<h2>All Main Page Conditions:</h2>";
$conditions = [
    "current_page === '/'" => ($current_page === '/'),
    "strpos('/courses') === 0 && !individual" => (strpos($current_page, '/courses') === 0 && !$is_individual_course),
    "strpos('/media') === 0" => (strpos($current_page, '/media') === 0),
    "strpos('/reviews') === 0" => (strpos($current_page, '/reviews') === 0),
    "strpos('/news') === 0" => (strpos($current_page, '/news') === 0),
    "strpos('/events') === 0" => (strpos($current_page, '/events') === 0),
    "strpos('/community') === 0" => (strpos($current_page, '/community') === 0),
    "strpos('/maps') === 0" => (strpos($current_page, '/maps') === 0),
    "strpos('/about') === 0" => (strpos($current_page, '/about') === 0),
    "strpos('/contact') === 0" => (strpos($current_page, '/contact') === 0),
    "strpos('/contests') === 0" => (strpos($current_page, '/contests') === 0),
    "strpos('/handicap') === 0" => (strpos($current_page, '/handicap') === 0),
    "strpos('/user') === 0" => (strpos($current_page, '/user') === 0)
];

foreach ($conditions as $condition => $result) {
    echo "<p>$condition: " . ($result ? 'TRUE' : 'FALSE') . "</p>";
}
?>