-- Admin Security Database Schema
-- Tables for admin authentication, roles, and security logging

-- Admin users table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('super_admin', 'admin', 'editor', 'moderator') NOT NULL DEFAULT 'editor',
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    two_factor_secret VARCHAR(32) NULL,
    two_factor_enabled BOOLEAN DEFAULT FALSE,
    failed_attempts INT DEFAULT 0,
    locked_until TIMESTAMP NULL,
    last_login TIMESTAMP NULL,
    password_changed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT NULL,
    
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_is_active (is_active),
    INDEX idx_locked_until (locked_until),
    INDEX idx_last_login (last_login),
    
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin login attempts table
CREATE TABLE IF NOT EXISTS admin_login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    success BOOLEAN NOT NULL DEFAULT FALSE,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    details VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_username (username),
    INDEX idx_success (success),
    INDEX idx_ip_address (ip_address),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin security event log
CREATE TABLE IF NOT EXISTS admin_security_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NULL,
    event_type VARCHAR(50) NOT NULL,
    event_details JSON,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_admin_id (admin_id),
    INDEX idx_event_type (event_type),
    INDEX idx_ip_address (ip_address),
    INDEX idx_created_at (created_at),
    
    FOREIGN KEY (admin_id) REFERENCES admin_users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin activity log (for tracking admin actions)
CREATE TABLE IF NOT EXISTS admin_activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    resource_type VARCHAR(50) NULL,
    resource_id INT NULL,
    old_values JSON NULL,
    new_values JSON NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_admin_id (admin_id),
    INDEX idx_action (action),
    INDEX idx_resource (resource_type, resource_id),
    INDEX idx_created_at (created_at),
    
    FOREIGN KEY (admin_id) REFERENCES admin_users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin IP whitelist (optional security feature)
CREATE TABLE IF NOT EXISTS admin_ip_whitelist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL UNIQUE,
    description VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_ip_address (ip_address),
    INDEX idx_is_active (is_active),
    
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin sessions table (for tracking active sessions)
CREATE TABLE IF NOT EXISTS admin_sessions (
    id VARCHAR(128) PRIMARY KEY,
    admin_id INT NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    
    INDEX idx_admin_id (admin_id),
    INDEX idx_expires_at (expires_at),
    INDEX idx_is_active (is_active),
    INDEX idx_last_activity (last_activity),
    
    FOREIGN KEY (admin_id) REFERENCES admin_users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin settings table (for storing admin-specific settings)
CREATE TABLE IF NOT EXISTS admin_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    setting_key VARCHAR(100) NOT NULL,
    setting_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY unique_admin_setting (admin_id, setting_key),
    INDEX idx_admin_id (admin_id),
    INDEX idx_setting_key (setting_key),
    
    FOREIGN KEY (admin_id) REFERENCES admin_users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin notifications table
CREATE TABLE IF NOT EXISTS admin_notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NULL, -- NULL for all admins
    type ENUM('security', 'system', 'user', 'content') NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    severity ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    is_read BOOLEAN DEFAULT FALSE,
    action_url VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    read_at TIMESTAMP NULL,
    
    INDEX idx_admin_id (admin_id),
    INDEX idx_type (type),
    INDEX idx_severity (severity),
    INDEX idx_is_read (is_read),
    INDEX idx_created_at (created_at),
    
    FOREIGN KEY (admin_id) REFERENCES admin_users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- System security settings
CREATE TABLE IF NOT EXISTS system_security_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    updated_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_setting_key (setting_key),
    INDEX idx_is_active (is_active),
    
    FOREIGN KEY (updated_by) REFERENCES admin_users(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default security settings
INSERT INTO system_security_settings (setting_key, setting_value, description, updated_by) VALUES
('admin_session_timeout', '3600', 'Admin session timeout in seconds', 1),
('admin_max_login_attempts', '3', 'Maximum failed login attempts before lockout', 1),
('admin_lockout_duration', '1800', 'Account lockout duration in seconds', 1),
('admin_require_2fa', 'true', 'Require two-factor authentication for all admins', 1),
('admin_ip_whitelist_enabled', 'false', 'Enable IP whitelist for admin access', 1),
('admin_password_min_length', '12', 'Minimum password length for admin accounts', 1),
('admin_password_require_special', 'true', 'Require special characters in admin passwords', 1),
('admin_password_expire_days', '90', 'Password expiration period in days (0 = never)', 1),
('admin_log_retention_days', '365', 'How long to keep admin logs in days', 1)
ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP;

-- Create a default super admin user (password should be changed immediately)
-- Default password: TennesseeGolf2024! (hash of this)
INSERT INTO admin_users (
    username, 
    email, 
    password_hash, 
    role, 
    first_name, 
    last_name,
    password_changed_at
) VALUES (
    'superadmin',
    'admin@tennesseegolfcourses.com',
    '$argon2id$v=19$m=65536,t=4,p=3$WVNocWJHVnliM05wYm1WdGNBPT0$+5t+zNp8C1KvN2KjBKJh5w5FcHKUXm7LZYBqAXlkOe8',
    'super_admin',
    'Super',
    'Admin',
    NULL  -- Force password change on first login
) ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP;

-- Clean up old data (run as maintenance)
-- Delete old login attempts (older than 30 days)
DELETE FROM admin_login_attempts WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY);

-- Delete old security logs based on retention setting
DELETE FROM admin_security_log 
WHERE created_at < DATE_SUB(NOW(), INTERVAL (
    SELECT CAST(setting_value AS UNSIGNED) 
    FROM system_security_settings 
    WHERE setting_key = 'admin_log_retention_days'
) DAY);

-- Delete expired sessions
DELETE FROM admin_sessions WHERE expires_at < NOW() OR is_active = FALSE;