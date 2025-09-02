<?php
require_once '../includes/admin-security-simple.php';
require_once '../config/database.php';

// Require admin authentication
AdminSecurity::requireAdminAuth();

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid JSON input.']);
    exit;
}

$entry_id = (int)($input['entry_id'] ?? 0);
$new_status = $input['status'] ?? '';

// Validate input
if ($entry_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid entry ID.']);
    exit;
}

$allowed_statuses = ['pending', 'approved', 'rejected', 'winner'];
if (!in_array($new_status, $allowed_statuses)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid status.']);
    exit;
}

try {
    // Check if entry exists
    $stmt = $pdo->prepare("SELECT id, status FROM contest_entries WHERE id = ?");
    $stmt->execute([$entry_id]);
    $entry = $stmt->fetch();
    
    if (!$entry) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Contest entry not found.']);
        exit;
    }
    
    // Update the status
    $stmt = $pdo->prepare("
        UPDATE contest_entries 
        SET status = ?, updated_at = CURRENT_TIMESTAMP 
        WHERE id = ?
    ");
    
    $success = $stmt->execute([$new_status, $entry_id]);
    
    if (!$success) {
        throw new Exception('Failed to update contest entry status.');
    }
    
    // Log the status change
    error_log("Contest entry #{$entry_id} status changed from '{$entry['status']}' to '{$new_status}' by admin");
    
    // Success response
    echo json_encode([
        'success' => true,
        'message' => 'Contest entry status updated successfully.',
        'entry_id' => $entry_id,
        'new_status' => $new_status
    ]);
    
} catch (Exception $e) {
    error_log("Contest status update error: " . $e->getMessage() . " | Entry ID: $entry_id");
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while updating the status.'
    ]);
}
?>