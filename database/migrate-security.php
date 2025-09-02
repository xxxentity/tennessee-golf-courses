<?php
/**
 * Database Migration Script for Enhanced Security Features
 * 
 * This script will create the necessary database tables and columns
 * for the enhanced authentication security system.
 * 
 * Run this script ONCE after implementing the security features.
 */

require_once '../config/database.php';

function runMigration($pdo) {
    $migrations = [];
    $errors = [];
    
    try {
        echo "🚀 Starting Enhanced Authentication Security Database Migration...\n\n";
        
        // Migration 1: Create login_attempts table
        echo "📋 Creating login_attempts table...\n";
        $sql = "
        CREATE TABLE IF NOT EXISTS login_attempts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            identifier VARCHAR(255) NOT NULL,
            success BOOLEAN NOT NULL DEFAULT FALSE,
            ip_address VARCHAR(45) NOT NULL,
            user_agent TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            
            INDEX idx_identifier (identifier),
            INDEX idx_ip_address (ip_address),
            INDEX idx_created_at (created_at),
            INDEX idx_success (success)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($sql);
        $migrations[] = "✅ login_attempts table created successfully";
        
        // Migration 2: Create password_resets table
        echo "📋 Creating password_resets table...\n";
        $sql = "
        CREATE TABLE IF NOT EXISTS password_resets (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            token VARCHAR(64) NOT NULL UNIQUE,
            expires_at TIMESTAMP NOT NULL,
            used BOOLEAN DEFAULT FALSE,
            used_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_token (token),
            INDEX idx_user_id (user_id),
            INDEX idx_expires_at (expires_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($sql);
        $migrations[] = "✅ password_resets table created successfully";
        
        // Migration 3: Create security_audit_log table
        echo "📋 Creating security_audit_log table...\n";
        $sql = "
        CREATE TABLE IF NOT EXISTS security_audit_log (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NULL,
            event_type VARCHAR(50) NOT NULL,
            event_details JSON,
            ip_address VARCHAR(45) NOT NULL,
            user_agent TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
            INDEX idx_user_id (user_id),
            INDEX idx_event_type (event_type),
            INDEX idx_created_at (created_at),
            INDEX idx_ip_address (ip_address)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($sql);
        $migrations[] = "✅ security_audit_log table created successfully";
        
        // Migration 4: Create user_security_settings table
        echo "📋 Creating user_security_settings table...\n";
        $sql = "
        CREATE TABLE IF NOT EXISTS user_security_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL UNIQUE,
            login_notifications BOOLEAN DEFAULT TRUE,
            security_alerts BOOLEAN DEFAULT TRUE,
            session_timeout INT DEFAULT 3600,
            password_reminder_days INT DEFAULT 90,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($sql);
        $migrations[] = "✅ user_security_settings table created successfully";
        
        // Migration 5: Create trusted_devices table
        echo "📋 Creating trusted_devices table...\n";
        $sql = "
        CREATE TABLE IF NOT EXISTS trusted_devices (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            device_fingerprint VARCHAR(64) NOT NULL,
            device_name VARCHAR(100),
            last_used TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            trusted_until TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_user_id (user_id),
            INDEX idx_fingerprint (device_fingerprint),
            INDEX idx_trusted_until (trusted_until)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($sql);
        $migrations[] = "✅ trusted_devices table created successfully";
        
        // Migration 6: Add security columns to users table
        echo "📋 Adding security columns to users table...\n";
        
        // Check if columns exist before adding them
        $columns_to_add = [
            'password_changed_at' => 'TIMESTAMP NULL AFTER password_hash',
            'login_attempts' => 'INT DEFAULT 0 AFTER is_active',
            'account_locked_until' => 'TIMESTAMP NULL AFTER login_attempts',
            'last_login' => 'TIMESTAMP NULL AFTER account_locked_until',
            'security_questions_set' => 'BOOLEAN DEFAULT FALSE AFTER email_verified',
            'two_factor_enabled' => 'BOOLEAN DEFAULT FALSE AFTER security_questions_set'
        ];
        
        foreach ($columns_to_add as $column => $definition) {
            try {
                // Check if column exists
                $stmt = $pdo->prepare("SHOW COLUMNS FROM users LIKE ?");
                $stmt->execute([$column]);
                
                if (!$stmt->fetch()) {
                    $sql = "ALTER TABLE users ADD COLUMN $column $definition";
                    $pdo->exec($sql);
                    $migrations[] = "✅ Added column '$column' to users table";
                } else {
                    $migrations[] = "ℹ️  Column '$column' already exists in users table";
                }
            } catch (PDOException $e) {
                $errors[] = "❌ Failed to add column '$column': " . $e->getMessage();
            }
        }
        
        // Migration 7: Add indexes for better performance
        echo "📋 Adding performance indexes...\n";
        $indexes = [
            'idx_email_verified' => 'email_verified',
            'idx_account_locked_until' => 'account_locked_until',
            'idx_last_login' => 'last_login'
        ];
        
        foreach ($indexes as $index_name => $column) {
            try {
                $sql = "ALTER TABLE users ADD INDEX $index_name ($column)";
                $pdo->exec($sql);
                $migrations[] = "✅ Added index '$index_name' on column '$column'";
            } catch (PDOException $e) {
                if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
                    $migrations[] = "ℹ️  Index '$index_name' already exists";
                } else {
                    $errors[] = "❌ Failed to add index '$index_name': " . $e->getMessage();
                }
            }
        }
        
        // Migration 8: Set password_changed_at for existing users
        echo "📋 Updating existing user data...\n";
        $sql = "UPDATE users SET password_changed_at = created_at WHERE password_changed_at IS NULL";
        $stmt = $pdo->exec($sql);
        $migrations[] = "✅ Updated password_changed_at for existing users (affected: $stmt rows)";
        
        // Migration 9: Create default security settings for existing users
        $sql = "
        INSERT INTO user_security_settings (user_id)
        SELECT id FROM users 
        WHERE id NOT IN (SELECT user_id FROM user_security_settings)
        ";
        $stmt = $pdo->exec($sql);
        $migrations[] = "✅ Created default security settings for existing users (affected: $stmt rows)";
        
        // Migration 10: Clean up old data
        echo "📋 Cleaning up old data...\n";
        
        // Clean up old password reset tokens (older than 24 hours)
        $sql = "DELETE FROM password_resets WHERE expires_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)";
        $stmt = $pdo->exec($sql);
        $migrations[] = "✅ Cleaned up old password reset tokens (removed: $stmt tokens)";
        
        // Clean up old login attempts (older than 30 days)
        $sql = "DELETE FROM login_attempts WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)";
        $stmt = $pdo->exec($sql);
        $migrations[] = "✅ Cleaned up old login attempts (removed: $stmt attempts)";
        
        echo "\n🎉 Migration completed successfully!\n\n";
        
        // Display summary
        echo "📊 MIGRATION SUMMARY:\n";
        echo str_repeat("=", 50) . "\n";
        foreach ($migrations as $migration) {
            echo "$migration\n";
        }
        
        if (!empty($errors)) {
            echo "\n⚠️  ERRORS ENCOUNTERED:\n";
            echo str_repeat("=", 50) . "\n";
            foreach ($errors as $error) {
                echo "$error\n";
            }
        }
        
        echo "\n✨ Enhanced Authentication Security is now ready!\n";
        echo "🔐 Features enabled:\n";
        echo "   • Strong password requirements\n";
        echo "   • Login attempt tracking\n";
        echo "   • Account lockout protection\n";
        echo "   • Secure password reset\n";
        echo "   • Security audit logging\n";
        echo "   • User security dashboard\n";
        echo "   • Enhanced session security\n\n";
        
    } catch (PDOException $e) {
        throw new Exception("Migration failed: " . $e->getMessage());
    }
}

// Check if script is being run from command line
if (php_sapi_name() === 'cli') {
    try {
        runMigration($pdo);
        exit(0);
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
        exit(1);
    }
} else {
    // If accessed via web, require admin authentication
    session_start();
    require_once '../includes/init.php';
    
    // Check if user is admin (you may need to adjust this check)
    if (!$is_logged_in || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        die('Access denied. Admin privileges required.');
    }
    
    try {
        ob_start();
        runMigration($pdo);
        $output = ob_get_clean();
        
        echo "<!DOCTYPE html>";
        echo "<html><head><title>Database Migration</title>";
        echo "<style>body{font-family:monospace;padding:20px;background:#f5f5f5;}";
        echo "pre{background:white;padding:20px;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,0.1);}</style>";
        echo "</head><body>";
        echo "<h1>Database Migration Results</h1>";
        echo "<pre>" . htmlspecialchars($output) . "</pre>";
        echo "<p><a href='../'>← Back to Application</a></p>";
        echo "</body></html>";
        
    } catch (Exception $e) {
        echo "<h1>Migration Error</h1>";
        echo "<p style='color:red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p><a href='../'>← Back to Application</a></p>";
    }
}
?>