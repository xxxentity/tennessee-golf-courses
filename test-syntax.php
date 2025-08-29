<?php
// Test if reviews-data.php has syntax errors
try {
    require_once 'includes/reviews-data.php';
    echo "SUCCESS: reviews-data.php loaded without syntax errors\n";
    echo "Reviews count: " . count($reviews) . "\n";
    echo "First review title: " . $reviews[0]['title'] . "\n";
} catch (ParseError $e) {
    echo "SYNTAX ERROR in reviews-data.php: " . $e->getMessage() . "\n";
} catch (Error $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
}
?>