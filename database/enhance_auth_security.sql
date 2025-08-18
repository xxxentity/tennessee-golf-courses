-- Enhanced Authentication Security Database Schema
-- Add tables for login tracking, password resets, and security features

-- Login attempts tracking table
CREATE TABLE IF NOT EXISTS login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    identifier VARCHAR(255) NOT NULL,  -- username or email
    success BOOLEAN NOT NULL DEFAULT FALSE,
    ip_address VARCHAR(45) NOT NULL,   -- Supports IPv6
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_identifier (identifier),
    INDEX idx_ip_address (ip_address),
    INDEX idx_created_at (created_at),
    INDEX idx_success (success)
);

-- Password reset tokens table
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
);

-- Add security-related columns to users table if they don't exist
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS password_changed_at TIMESTAMP NULL AFTER password_hash,
ADD COLUMN IF NOT EXISTS login_attempts INT DEFAULT 0 AFTER is_active,
ADD COLUMN IF NOT EXISTS account_locked_until TIMESTAMP NULL AFTER login_attempts,
ADD COLUMN IF NOT EXISTS last_login TIMESTAMP NULL AFTER account_locked_until,
ADD COLUMN IF NOT EXISTS security_questions_set BOOLEAN DEFAULT FALSE AFTER email_verified,
ADD COLUMN IF NOT EXISTS two_factor_enabled BOOLEAN DEFAULT FALSE AFTER security_questions_set;

-- Security audit log table
CREATE TABLE IF NOT EXISTS security_audit_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    event_type VARCHAR(50) NOT NULL,  -- login, logout, password_change, etc.
    event_details JSON,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_event_type (event_type),
    INDEX idx_created_at (created_at),
    INDEX idx_ip_address (ip_address)
);

-- Account security settings table
CREATE TABLE IF NOT EXISTS user_security_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    login_notifications BOOLEAN DEFAULT TRUE,
    security_alerts BOOLEAN DEFAULT TRUE,
    session_timeout INT DEFAULT 3600,  -- seconds
    password_reminder_days INT DEFAULT 90,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Trusted devices table (for future use)
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
);

-- Set password_changed_at for existing users who don't have it set
UPDATE users 
SET password_changed_at = created_at 
WHERE password_changed_at IS NULL;

-- Create default security settings for existing users
INSERT INTO user_security_settings (user_id)
SELECT id FROM users 
WHERE id NOT IN (SELECT user_id FROM user_security_settings);

-- Add indexes for better performance
ALTER TABLE users ADD INDEX IF NOT EXISTS idx_email_verified (email_verified);
ALTER TABLE users ADD INDEX IF NOT EXISTS idx_account_locked_until (account_locked_until);
ALTER TABLE users ADD INDEX IF NOT EXISTS idx_last_login (last_login);

-- Clean up old password reset tokens (older than 24 hours)
DELETE FROM password_resets 
WHERE expires_at < DATE_SUB(NOW(), INTERVAL 24 HOUR);

-- Clean up old login attempts (older than 30 days)
DELETE FROM login_attempts 
WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY);