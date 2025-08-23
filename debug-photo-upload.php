<?php
// Debug script to identify the photo upload issue
require_once 'includes/session-security.php';
require_once 'config/database.php';

// Start secure session
try {
    SecureSession::start();
} catch (Exception $e) {
    die("Session error");
}

// Check if user is logged in
if (!SecureSession::isLoggedIn()) {
    die("You must be logged in to test photo upload.");
}

echo "<h2>Photo Upload Debug Analysis</h2>";

// Check recent contest entries
echo "<h3>Recent Contest Entries:</h3>";
$stmt = $pdo->prepare("SELECT id, full_name, photo_path, created_at FROM contest_entries ORDER BY created_at DESC LIMIT 5");
$stmt->execute();
$entries = $stmt->fetchAll();

if (empty($entries)) {
    echo "<p>No contest entries found in database.</p>";
} else {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Name</th><th>Photo Path</th><th>File Exists</th><th>Created</th></tr>";
    
    foreach ($entries as $entry) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($entry['id']) . "</td>";
        echo "<td>" . htmlspecialchars($entry['full_name']) . "</td>";
        echo "<td>" . htmlspecialchars($entry['photo_path'] ?: 'NULL') . "</td>";
        
        $file_exists = 'N/A';
        if (!empty($entry['photo_path'])) {
            $file_exists = file_exists($entry['photo_path']) ? 'YES' : 'NO';
        }
        echo "<td>" . $file_exists . "</td>";
        echo "<td>" . htmlspecialchars($entry['created_at']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Check upload directory
echo "<h3>Upload Directory Status:</h3>";
$upload_dir = 'uploads/contest_photos/';
echo "<p><strong>Directory:</strong> " . realpath($upload_dir ?: '.') . "</p>";
echo "<p><strong>Exists:</strong> " . (is_dir($upload_dir) ? 'YES' : 'NO') . "</p>";
echo "<p><strong>Writable:</strong> " . (is_writable($upload_dir) ? 'YES' : 'NO') . "</p>";

if (is_dir($upload_dir)) {
    $files = scandir($upload_dir);
    $files = array_diff($files, ['.', '..']);
    echo "<p><strong>Files in directory:</strong> " . count($files) . "</p>";
    if (!empty($files)) {
        echo "<ul>";
        foreach ($files as $file) {
            echo "<li>" . htmlspecialchars($file) . " (" . filesize($upload_dir . $file) . " bytes)</li>";
        }
        echo "</ul>";
    }
}

// Check PHP settings
echo "<h3>PHP Upload Settings:</h3>";
echo "<p><strong>file_uploads:</strong> " . (ini_get('file_uploads') ? 'Enabled' : 'Disabled') . "</p>";
echo "<p><strong>upload_max_filesize:</strong> " . ini_get('upload_max_filesize') . "</p>";
echo "<p><strong>post_max_size:</strong> " . ini_get('post_max_size') . "</p>";
echo "<p><strong>upload_tmp_dir:</strong> " . (ini_get('upload_tmp_dir') ?: 'System default') . "</p>";

echo "<br><a href='/contests'>← Back to Contest Form</a>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2, h3 { color: #333; }
table { margin: 10px 0; }
th, td { padding: 8px; text-align: left; }
th { background-color: #f5f5f5; }
</style>