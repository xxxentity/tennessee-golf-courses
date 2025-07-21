-- Add login security columns to users table
ALTER TABLE users ADD COLUMN login_attempts INT DEFAULT 0 AFTER is_active;
ALTER TABLE users ADD COLUMN account_locked_until DATETIME DEFAULT NULL AFTER login_attempts;
ALTER TABLE users ADD COLUMN password_reset_token VARCHAR(100) DEFAULT NULL AFTER account_locked_until;
ALTER TABLE users ADD COLUMN password_reset_expires DATETIME DEFAULT NULL AFTER password_reset_token;