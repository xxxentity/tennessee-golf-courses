<?php
// One-time script to clean up test contest entries
require_once 'includes/admin-security-simple.php';
require_once 'config/database.php';

// Require admin authentication
AdminSecurity::requireAdminAuth();

echo "<h2>Contest Entries Cleanup</h2>";

try {
    // Get all contest entries first
    $stmt = $pdo->query("SELECT id, full_name, photo_path, status, created_at FROM contest_entries ORDER BY created_at DESC");
    $entries = $stmt->fetchAll();
    
    echo "<h3>Current Contest Entries:</h3>";
    if (empty($entries)) {
        echo "<p>No contest entries found.</p>";
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 20px 0;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Status</th><th>Photo</th><th>Date</th><th>Action</th></tr>";
        
        foreach ($entries as $entry) {
            echo "<tr>";
            echo "<td>" . $entry['id'] . "</td>";
            echo "<td>" . htmlspecialchars($entry['full_name']) . "</td>";
            echo "<td>" . $entry['status'] . "</td>";
            echo "<td>" . ($entry['photo_path'] ? 'Yes' : 'No') . "</td>";
            echo "<td>" . $entry['created_at'] . "</td>";
            echo "<td><a href='?delete=" . $entry['id'] . "' onclick='return confirm(\"Delete this entry?\")' style='color: red;'>Delete</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Handle deletion
    if (isset($_GET['delete'])) {
        $delete_id = (int)$_GET['delete'];
        
        // Get entry details for cleanup
        $stmt = $pdo->prepare("SELECT photo_path FROM contest_entries WHERE id = ?");
        $stmt->execute([$delete_id]);
        $entry = $stmt->fetch();
        
        if ($entry) {
            // Delete photo file if exists
            if (!empty($entry['photo_path']) && file_exists($entry['photo_path'])) {
                unlink($entry['photo_path']);
                echo "<p style='color: green;'>Deleted photo file: " . $entry['photo_path'] . "</p>";
            }
            
            // Delete database entry
            $stmt = $pdo->prepare("DELETE FROM contest_entries WHERE id = ?");
            $success = $stmt->execute([$delete_id]);
            
            if ($success) {
                echo "<p style='color: green;'><strong>Successfully deleted entry #$delete_id</strong></p>";
                echo "<script>setTimeout(() => location.reload(), 1500);</script>";
            } else {
                echo "<p style='color: red;'>Failed to delete entry #$delete_id</p>";
            }
        } else {
            echo "<p style='color: red;'>Entry #$delete_id not found</p>";
        }
    }
    
    // Bulk delete all entries
    if (isset($_GET['deleteall']) && $_GET['deleteall'] === 'confirm') {
        // Delete all photo files
        $stmt = $pdo->query("SELECT photo_path FROM contest_entries WHERE photo_path IS NOT NULL");
        $photos = $stmt->fetchAll();
        
        foreach ($photos as $photo) {
            if (file_exists($photo['photo_path'])) {
                unlink($photo['photo_path']);
            }
        }
        
        // Delete all entries
        $stmt = $pdo->query("DELETE FROM contest_entries");
        $deleted_count = $stmt->rowCount();
        
        echo "<p style='color: green; font-weight: bold;'>Deleted all $deleted_count contest entries and their photos</p>";
        echo "<script>setTimeout(() => location.reload(), 2000);</script>";
    }
    
    if (!empty($entries)) {
        echo "<div style='margin-top: 2rem; padding: 1rem; background: #ffeeee; border: 1px solid #ffcccc; border-radius: 5px;'>";
        echo "<h3 style='color: #cc0000;'>Danger Zone</h3>";
        echo "<a href='?deleteall=confirm' onclick='return confirm(\"Are you sure you want to delete ALL contest entries? This cannot be undone!\")' style='background: #cc0000; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Delete All Entries</a>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<br><br><a href='/admin/contest-entries'>← Back to Admin Contest Entries</a>";
echo "<br><a href='/contests'>← Back to Contest Page</a>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2, h3 { color: #333; }
table { border-collapse: collapse; }
th, td { padding: 8px 12px; text-align: left; border: 1px solid #ddd; }
th { background-color: #f5f5f5; }
</style>