<?php
// Simple log viewer for debugging
require_once 'includes/session-security.php';

// Start secure session
try {
    SecureSession::start();
} catch (Exception $e) {
    die("Session error");
}

// Check if user is logged in (basic security)
if (!SecureSession::isLoggedIn()) {
    die("You must be logged in to view logs.");
}

echo "<h2>Recent Error Log Entries</h2>";
echo "<p><em>Showing recent error log entries containing 'Photo' or 'Contest'</em></p>";

// Try common log file locations
$log_files = [
    ini_get('error_log'),
    '/var/log/php_errors.log',
    '/var/log/apache2/error.log',
    '/var/log/httpd/error.log',
    './error.log',
    '../logs/error.log'
];

$found_logs = false;

foreach ($log_files as $log_file) {
    if ($log_file && file_exists($log_file) && is_readable($log_file)) {
        echo "<h3>Log file: $log_file</h3>";
        $found_logs = true;
        
        // Read last 100 lines and filter for relevant entries
        $lines = file($log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $recent_lines = array_slice($lines, -100);
        
        $relevant_lines = array_filter($recent_lines, function($line) {
            return stripos($line, 'Photo') !== false || 
                   stripos($line, 'Contest') !== false ||
                   stripos($line, 'upload') !== false;
        });
        
        if (empty($relevant_lines)) {
            echo "<p>No recent photo/contest related entries found.</p>";
        } else {
            echo "<pre style='background: #f5f5f5; padding: 10px; overflow-x: auto;'>";
            foreach (array_reverse($relevant_lines) as $line) {
                echo htmlspecialchars($line) . "\n";
            }
            echo "</pre>";
        }
        
        break; // Use first available log file
    }
}

if (!$found_logs) {
    echo "<p><strong>No accessible log files found.</strong></p>";
    echo "<p>Tried locations:</p>";
    echo "<ul>";
    foreach ($log_files as $log_file) {
        if ($log_file) {
            echo "<li>" . htmlspecialchars($log_file) . " - " . (file_exists($log_file) ? 'exists but not readable' : 'not found') . "</li>";
        }
    }
    echo "</ul>";
    
    echo "<p><strong>PHP Error Log Setting:</strong> " . (ini_get('error_log') ?: 'not set') . "</p>";
    echo "<p><strong>Log Errors Enabled:</strong> " . (ini_get('log_errors') ? 'YES' : 'NO') . "</p>";
}

echo "<br><a href='/contests'>← Back to Contest Form</a>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2, h3 { color: #333; }
pre { font-size: 12px; }
</style>