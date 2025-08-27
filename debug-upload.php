<?php
// Temporary debug script to check upload functionality
require_once 'includes/session-security.php';

// Start secure session
try {
    SecureSession::start();
} catch (Exception $e) {
    echo "Session Error: " . $e->getMessage() . "\n";
}

echo "<h2>Upload Debug Information</h2>\n";

// Check if user is logged in
if (!SecureSession::isLoggedIn()) {
    echo "❌ User not logged in\n";
} else {
    echo "✅ User logged in: " . SecureSession::get('username') . "\n";
}

// Check directory permissions
$uploadDir = 'uploads/profile_pictures/';
echo "<h3>Directory Checks:</h3>\n";
echo "Upload directory: " . $uploadDir . "\n";
echo "Directory exists: " . (is_dir($uploadDir) ? '✅ Yes' : '❌ No') . "\n";
echo "Directory writable: " . (is_writable($uploadDir) ? '✅ Yes' : '❌ No') . "\n";

// Check file upload settings
echo "<h3>PHP Upload Settings:</h3>\n";
echo "file_uploads: " . (ini_get('file_uploads') ? '✅ Enabled' : '❌ Disabled') . "\n";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "post_max_size: " . ini_get('post_max_size') . "\n";
echo "max_file_uploads: " . ini_get('max_file_uploads') . "\n";
echo "memory_limit: " . ini_get('memory_limit') . "\n";

// Check required classes
echo "<h3>Class Availability:</h3>\n";
echo "SecureUpload class: " . (class_exists('SecureUpload') ? '❌ Not loaded' : 'Need to include') . "\n";

require_once 'includes/secure-upload.php';
echo "SecureUpload class (after include): " . (class_exists('SecureUpload') ? '✅ Available' : '❌ Still missing') . "\n";

require_once 'includes/csrf.php';
echo "CSRFProtection class: " . (class_exists('CSRFProtection') ? '✅ Available' : '❌ Missing') . "\n";

echo "<h3>Directory Contents:</h3>\n";
if (is_dir($uploadDir)) {
    $files = scandir($uploadDir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "- " . $file . " (" . filesize($uploadDir . $file) . " bytes)\n";
        }
    }
} else {
    echo "Directory does not exist\n";
}

// Check .htaccess in uploads
$htaccessPath = 'uploads/.htaccess';
echo "<h3>Security Files:</h3>\n";
echo "uploads/.htaccess exists: " . (file_exists($htaccessPath) ? '✅ Yes' : '❌ No') . "\n";
if (file_exists($htaccessPath)) {
    echo "Content: " . file_get_contents($htaccessPath) . "\n";
}
?>