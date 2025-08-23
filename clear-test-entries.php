<?php
// Simple script to clear test contest entries for testing purposes
require_once 'includes/session-security.php';
require_once 'config/database.php';

// Start secure session
try {
    SecureSession::start();
} catch (Exception $e) {
    die("Session error: " . $e->getMessage());
}

// Check if user is logged in
if (!SecureSession::isLoggedIn()) {
    die("You must be logged in to clear test entries.");
}

$user_id = SecureSession::get('user_id');

try {
    // Delete test entries for current user
    $stmt = $pdo->prepare("DELETE FROM contest_entries WHERE user_id = ?");
    $deleted = $stmt->execute([$user_id]);
    $count = $stmt->rowCount();
    
    if ($deleted) {
        echo "✅ Cleared $count test contest entries for your account.<br>";
        echo "You can now test the contest form again.<br><br>";
        echo "<a href='/contests'>← Back to Contest Form</a>";
    } else {
        echo "No contest entries found to clear.";
    }
    
} catch (Exception $e) {
    echo "Error clearing entries: " . $e->getMessage();
}
?>