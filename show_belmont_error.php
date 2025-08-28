<?php
// Turn on error display
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Belmont Article Error Check</h2>";

// Try to include the belmont file and catch any errors
try {
    ob_start();
    include 'news/belmont-conner-brown-wins-tennessee-match-play-championship.php';
    $output = ob_get_clean();
    echo "<h3>SUCCESS - Article loaded without errors</h3>";
    echo $output;
} catch (ParseError $e) {
    echo "<h3 style='color: red;'>PARSE ERROR:</h3>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    echo "<p>File: " . $e->getFile() . "</p>";
    echo "<p>Line: " . $e->getLine() . "</p>";
} catch (Error $e) {
    echo "<h3 style='color: red;'>FATAL ERROR:</h3>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    echo "<p>File: " . $e->getFile() . "</p>";
    echo "<p>Line: " . $e->getLine() . "</p>";
} catch (Exception $e) {
    echo "<h3 style='color: orange;'>EXCEPTION:</h3>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    echo "<p>File: " . $e->getFile() . "</p>";
    echo "<p>Line: " . $e->getLine() . "</p>";
}
?>