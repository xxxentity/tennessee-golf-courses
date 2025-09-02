-- Contest Entries Table
-- Stores all contest submissions with user data and entry details

CREATE TABLE IF NOT EXISTS contest_entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    contest_id INT NOT NULL,
    
    -- User Information
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    city VARCHAR(100) NOT NULL,
    state VARCHAR(50) NOT NULL,
    
    -- Contest Entry Data
    favorite_course VARCHAR(255),
    photo_path VARCHAR(500),
    photo_caption TEXT,
    newsletter_signup BOOLEAN DEFAULT FALSE,
    
    -- Entry Metadata
    entry_ip VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Status and Admin Fields
    status ENUM('pending', 'approved', 'rejected', 'winner') DEFAULT 'pending',
    admin_notes TEXT,
    
    -- Foreign Key Constraints
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    
    -- Indexes for performance
    INDEX idx_contest_user (contest_id, user_id),
    INDEX idx_email (email),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);

-- Add some sample contest data for testing
INSERT IGNORE INTO contest_entries (
    user_id, contest_id, full_name, email, phone, city, state, 
    favorite_course, photo_caption, newsletter_signup, entry_ip, status
) VALUES 
(1, 1, 'Test User', 'test@example.com', '615-555-0123', 'Nashville', 'TN', 
 'Belle Meade Country Club', 'Amazing shot from the 18th tee!', TRUE, '127.0.0.1', 'pending');