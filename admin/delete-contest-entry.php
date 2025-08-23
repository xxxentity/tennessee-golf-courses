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

// Validate input
if ($entry_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid entry ID.']);
    exit;
}

try {
    // Get entry details before deletion (for photo cleanup)
    $stmt = $pdo->prepare("SELECT photo_path FROM contest_entries WHERE id = ?");
    $stmt->execute([$entry_id]);
    $entry = $stmt->fetch();
    
    if (!$entry) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Contest entry not found.']);
        exit;
    }
    
    // Delete photo file if it exists
    if (!empty($entry['photo_path']) && file_exists('../' . $entry['photo_path'])) {
        unlink('../' . $entry['photo_path']);
    }
    
    // Delete the database entry
    $stmt = $pdo->prepare("DELETE FROM contest_entries WHERE id = ?");
    $success = $stmt->execute([$entry_id]);
    
    if (!$success) {
        throw new Exception('Failed to delete contest entry.');
    }
    
    // Log the deletion
    error_log("Contest entry #{$entry_id} deleted by admin");
    
    // Success response
    echo json_encode([
        'success' => true,
        'message' => 'Contest entry deleted successfully.',
        'entry_id' => $entry_id
    ]);
    
} catch (Exception $e) {
    error_log("Contest entry deletion error: " . $e->getMessage() . " | Entry ID: $entry_id");
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while deleting the entry.'
    ]);
}
?>