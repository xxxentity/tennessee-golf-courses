-- Add profile picture column to users table
ALTER TABLE users ADD COLUMN profile_picture VARCHAR(255) DEFAULT NULL AFTER email;

-- Create uploads directory structure if not exists
-- Note: This will be handled by PHP file creation