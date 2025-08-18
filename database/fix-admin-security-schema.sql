-- Fix Admin Security Tables with proper foreign keys
-- This version handles existing admin_users table structure

-- Admin login attempts tracking (without foreign key to avoid issues)
CREATE TABLE IF NOT EXISTS admin_login_attempts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    admin_id INT NULL COMMENT 'NULL if login failed',
    username VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    status ENUM('success', 'failed') NOT NULL,
    failure_reason VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_ip (ip_address),
    INDEX idx_created (created_at)
);

-- Admin security log (without foreign key constraint)
CREATE TABLE IF NOT EXISTS admin_security_log (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    admin_id INT NULL,
    event_type VARCHAR(100) NOT NULL,
    event_description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    additional_data JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_admin_id (admin_id),
    INDEX idx_event_type (event_type),
    INDEX idx_created_at (created_at)
);

-- Admin activity log (without foreign key constraint)
CREATE TABLE IF NOT EXISTS admin_activity_log (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    admin_id INT NULL,
    action VARCHAR(255) NOT NULL,
    entity_type VARCHAR(100) COMMENT 'Type of entity affected (user, course, etc)',
    entity_id INT COMMENT 'ID of affected entity',
    old_values JSON COMMENT 'Previous values before change',
    new_values JSON COMMENT 'New values after change',
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_admin_id (admin_id),
    INDEX idx_action (action),
    INDEX idx_entity (entity_type, entity_id),
    INDEX idx_created_at (created_at)
);

-- Admin IP whitelist (without foreign key constraint)
CREATE TABLE IF NOT EXISTS admin_ip_whitelist (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ip_address VARCHAR(45) NOT NULL UNIQUE,
    description VARCHAR(255),
    added_by INT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_ip (ip_address),
    INDEX idx_active (is_active)
);

-- Admin sessions tracking (without foreign key constraint)
CREATE TABLE IF NOT EXISTS admin_sessions (
    id VARCHAR(128) PRIMARY KEY,
    admin_id INT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NOT NULL,
    data JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_admin_id (admin_id),
    INDEX idx_expires (expires_at),
    INDEX idx_last_activity (last_activity)
);

-- Admin-specific settings (without foreign key constraint)
CREATE TABLE IF NOT EXISTS admin_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    admin_id INT NOT NULL UNIQUE,
    two_factor_enabled BOOLEAN DEFAULT FALSE,
    two_factor_secret VARCHAR(255),
    notification_preferences JSON,
    dashboard_preferences JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_admin_id (admin_id)
);

-- Admin notifications (without foreign key constraint)
CREATE TABLE IF NOT EXISTS admin_notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    admin_id INT NULL COMMENT 'NULL for system-wide notifications',
    type VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT,
    data JSON,
    is_read BOOLEAN DEFAULT FALSE,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_admin_id (admin_id),
    INDEX idx_type (type),
    INDEX idx_is_read (is_read),
    INDEX idx_created_at (created_at)
);

-- System-wide security settings
CREATE TABLE IF NOT EXISTS system_security_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_name VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type VARCHAR(50) COMMENT 'string, integer, boolean, json',
    description TEXT,
    updated_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_setting_name (setting_name)
);

-- Insert default security settings
INSERT IGNORE INTO system_security_settings (setting_name, setting_value, setting_type, description) VALUES
('max_login_attempts', '5', 'integer', 'Maximum login attempts before account lockout'),
('lockout_duration', '30', 'integer', 'Account lockout duration in minutes'),
('password_min_length', '8', 'integer', 'Minimum password length'),
('password_require_uppercase', 'true', 'boolean', 'Require uppercase letter in password'),
('password_require_lowercase', 'true', 'boolean', 'Require lowercase letter in password'),
('password_require_numbers', 'true', 'boolean', 'Require number in password'),
('password_require_symbols', 'true', 'boolean', 'Require special character in password'),
('session_timeout', '3600', 'integer', 'Admin session timeout in seconds'),
('enable_2fa', 'false', 'boolean', 'Enable two-factor authentication for admins'),
('enable_ip_whitelist', 'false', 'boolean', 'Enable IP whitelist for admin access');

-- Add indexes for better performance on existing tables
-- These will be skipped if they already exist
CREATE INDEX IF NOT EXISTS idx_admin_users_email ON admin_users(email);
CREATE INDEX IF NOT EXISTS idx_admin_users_username ON admin_users(username);
CREATE INDEX IF NOT EXISTS idx_admin_users_active ON admin_users(is_active);