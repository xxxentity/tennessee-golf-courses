-- Add email verification columns to users table (safe - checks for existing columns)
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS email_verification_token VARCHAR(255) NULL,
ADD COLUMN IF NOT EXISTS email_verified BOOLEAN DEFAULT 0,
ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Add indexes (these will be skipped if they already exist)
CREATE INDEX IF NOT EXISTS idx_email_token ON users(email_verification_token);
CREATE INDEX IF NOT EXISTS idx_email_verified ON users(email_verified);