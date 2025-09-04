<?php
/**
 * Database Migration: Add Reply System Support
 * 
 * This script adds the necessary database changes to support:
 * 1. Reply system (parent_comment_id column)
 * 2. Half-star ratings (DECIMAL rating column)
 * 
 * Run this once to enable the new rating/review features.
 */

require_once '../config/database.php';

echo "=== Tennessee Golf Courses - Reply System Migration ===\n\n";

try {
    // Start transaction
    $pdo->beginTransaction();
    
    // Check if parent_comment_id column already exists
    $stmt = $pdo->query("SHOW COLUMNS FROM course_comments LIKE 'parent_comment_id'");
    if ($stmt->rowCount() == 0) {
        echo "Adding parent_comment_id column...\n";
        $pdo->exec("ALTER TABLE course_comments ADD COLUMN parent_comment_id INT NULL DEFAULT NULL AFTER id");
        echo "✓ parent_comment_id column added successfully\n";
    } else {
        echo "✓ parent_comment_id column already exists\n";
    }
    
    // Add foreign key constraint
    try {
        $pdo->exec("ALTER TABLE course_comments ADD CONSTRAINT fk_parent_comment FOREIGN KEY (parent_comment_id) REFERENCES course_comments(id) ON DELETE CASCADE");
        echo "✓ Foreign key constraint added\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "✓ Foreign key constraint already exists\n";
        } else {
            throw $e;
        }
    }
    
    // Add index for performance
    try {
        $pdo->exec("CREATE INDEX idx_parent_comment_id ON course_comments(parent_comment_id)");
        echo "✓ Index on parent_comment_id created\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "✓ Index already exists\n";
        } else {
            throw $e;
        }
    }
    
    // Modify rating column to support decimals (half-stars)
    echo "Updating rating column to support half-stars...\n";
    $pdo->exec("ALTER TABLE course_comments MODIFY COLUMN rating DECIMAL(2,1) NULL");
    echo "✓ Rating column updated to support decimal values\n";
    
    // Commit transaction
    $pdo->commit();
    
    echo "\n=== Migration Completed Successfully! ===\n";
    echo "Features now available:\n";
    echo "• Half-star ratings (1.5, 2.5, 3.5, 4.5 stars)\n";
    echo "• Reply system for threaded discussions\n";
    echo "• Improved database performance with indexes\n\n";
    
    // Show updated table structure
    echo "Updated table structure:\n";
    $stmt = $pdo->query("DESCRIBE course_comments");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "• {$row['Field']}: {$row['Type']} {$row['Null']} {$row['Key']} {$row['Default']}\n";
    }
    
} catch (PDOException $e) {
    // Rollback on error
    $pdo->rollBack();
    echo "❌ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n🚀 Ready to test the new rating/review system!\n";
?>