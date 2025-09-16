<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Page 5 Courses Debug</h2>";

try {
    echo "<p>Testing session security...</p>";
    require_once 'includes/session-security.php';
    echo "✅ Session security loaded<br>";

    echo "<p>Testing database connection...</p>";
    require_once 'config/database.php';
    echo "✅ Database connection loaded<br>";

    echo "<p>Testing database query...</p>";
    $stmt = $pdo->query("SELECT 1");
    echo "✅ Database query successful<br>";

    echo "<p>Testing CSRF...</p>";
    require_once 'includes/csrf.php';
    echo "✅ CSRF loaded<br>";

    echo "<p>Testing SEO...</p>";
    require_once 'includes/seo.php';
    echo "✅ SEO loaded<br>";

    echo "<h3>✅ All core components working!</h3>";

} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . "</p>";
    echo "<p>Line: " . $e->getLine() . "</p>";
}
?>