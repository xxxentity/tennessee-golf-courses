-- Add login security columns to users table (safe - checks for existing columns)
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS login_attempts INT DEFAULT 0 AFTER is_active,
ADD COLUMN IF NOT EXISTS account_locked_until DATETIME DEFAULT NULL AFTER login_attempts,
ADD COLUMN IF NOT EXISTS password_reset_token VARCHAR(100) DEFAULT NULL AFTER account_locked_until,
ADD COLUMN IF NOT EXISTS password_reset_expires DATETIME DEFAULT NULL AFTER password_reset_token;