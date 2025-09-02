-- Newsletter Subscribers Table
CREATE TABLE newsletter_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    first_name VARCHAR(100),
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT 1,
    unsubscribe_token VARCHAR(255) UNIQUE,
    ip_address VARCHAR(45),
    user_agent TEXT,
    confirmed BOOLEAN DEFAULT 0,
    confirmed_at TIMESTAMP NULL,
    INDEX idx_email (email),
    INDEX idx_active (is_active),
    INDEX idx_token (unsubscribe_token)
);

-- Newsletter Campaigns Table (for tracking sent newsletters)
CREATE TABLE newsletter_campaigns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_sent INT DEFAULT 0,
    created_by VARCHAR(100),
    status ENUM('draft', 'sent', 'sending') DEFAULT 'draft'
);