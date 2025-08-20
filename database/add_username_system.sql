-- Add username system to users table
-- This migration adds username support for privacy

-- Add username column
ALTER TABLE users 
ADD COLUMN username VARCHAR(50) UNIQUE AFTER email,
ADD COLUMN display_real_name BOOLEAN DEFAULT 0 COMMENT 'Whether to show real name on profile';

-- Create index for faster username lookups
CREATE INDEX idx_username ON users(username);

-- Generate default usernames for existing users
-- This uses first name + last initial + user ID for uniqueness
UPDATE users 
SET username = LOWER(CONCAT(
    SUBSTRING(first_name, 1, 1), 
    SUBSTRING(last_name, 1, 1),
    '_user',
    id
))
WHERE username IS NULL;

-- Make username required for new users
ALTER TABLE users MODIFY username VARCHAR(50) NOT NULL;

-- Add username validation constraints
ALTER TABLE users 
ADD CONSTRAINT username_length CHECK (LENGTH(username) >= 3),
ADD CONSTRAINT username_format CHECK (username REGEXP '^[a-zA-Z0-9_]+$');

-- Update any foreign key references to show usernames instead of names
-- This is handled in application code, not database

-- Create a view for public user display
CREATE VIEW public_user_profiles AS
SELECT 
    id,
    username,
    CASE 
        WHEN display_real_name = 1 THEN CONCAT(first_name, ' ', last_name)
        ELSE NULL
    END as display_name,
    email_verified,
    created_at
FROM users;