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

try {
    // Get photo paths for non-winner entries before deletion
    $stmt = $pdo->prepare("SELECT photo_path FROM contest_entries WHERE status != 'winner' AND photo_path IS NOT NULL AND photo_path != ''");
    $stmt->execute();
    $photos = $stmt->fetchAll();
    
    // Delete photo files for non-winner entries
    $deleted_photos = 0;
    foreach ($photos as $photo) {
        $photo_path = '../' . $photo['photo_path'];
        if (file_exists($photo_path)) {
            if (unlink($photo_path)) {
                $deleted_photos++;
            }
        }
    }
    
    // Delete only non-winner contest entries (preserve winners)
    $stmt = $pdo->prepare("DELETE FROM contest_entries WHERE status != 'winner'");
    $success = $stmt->execute();
    $deleted_entries = $stmt->rowCount();
    
    if (!$success) {
        throw new Exception('Failed to delete contest entries.');
    }
    
    // Log the bulk deletion
    error_log("Admin deleted non-winner contest entries: {$deleted_entries} entries, {$deleted_photos} photos (winners preserved)");
    
    // Success response
    echo json_encode([
        'success' => true,
        'message' => "Successfully deleted {$deleted_entries} contest entries and {$deleted_photos} photos. Winners were preserved.",
        'deleted_entries' => $deleted_entries,
        'deleted_photos' => $deleted_photos
    ]);
    
} catch (Exception $e) {
    error_log("Bulk contest entry deletion error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while deleting entries.'
    ]);
}
?>