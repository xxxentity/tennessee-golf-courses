<?php
// Simple test endpoint to debug contest submission issues
header('Content-Type: application/json');

echo json_encode([
    'success' => true,
    'message' => 'Test endpoint is working',
    'method' => $_SERVER['REQUEST_METHOD'],
    'post_data' => $_POST,
    'files' => $_FILES,
    'session_status' => session_status(),
    'timestamp' => date('Y-m-d H:i:s')
]);
?>