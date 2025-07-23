-- Add email verification columns to users table
ALTER TABLE users 
ADD COLUMN email_verification_token VARCHAR(255) NULL,
ADD COLUMN email_verified BOOLEAN DEFAULT 0,
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD INDEX idx_email_token (email_verification_token),
ADD INDEX idx_email_verified (email_verified);