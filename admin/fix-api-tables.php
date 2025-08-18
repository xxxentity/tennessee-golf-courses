<?php
require_once '../includes/admin-security-simple.php';
require_once '../config/database.php';

// Require admin authentication
AdminSecurity::requireAdminAuth();

$currentAdmin = AdminSecurity::getCurrentAdmin();

$results = [];

try {
    // Create api_requests table
    $sql = "CREATE TABLE IF NOT EXISTS api_requests (
        id BIGINT PRIMARY KEY AUTO_INCREMENT,
        request_id VARCHAR(32) NOT NULL UNIQUE,
        endpoint VARCHAR(500) NOT NULL,
        method VARCHAR(10) NOT NULL,
        ip_address VARCHAR(45) NOT NULL,
        user_agent TEXT,
        user_id INT NULL,
        admin_id INT NULL,
        status_code INT NOT NULL,
        response_time_ms INT,
        request_data JSON,
        response_data JSON,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_request_id (request_id),
        INDEX idx_endpoint (endpoint),
        INDEX idx_ip_address (ip_address),
        INDEX idx_user_id (user_id),
        INDEX idx_admin_id (admin_id),
        INDEX idx_created_at (created_at)
    )";
    
    $pdo->exec($sql);
    $results[] = ['table' => 'api_requests', 'status' => 'success'];
    
} catch (Exception $e) {
    $results[] = ['table' => 'api_requests', 'status' => 'error', 'message' => $e->getMessage()];
}

try {
    // Create api_blacklist table
    $sql = "CREATE TABLE IF NOT EXISTS api_blacklist (
        id INT PRIMARY KEY AUTO_INCREMENT,
        type ENUM('ip', 'user_agent', 'token') NOT NULL,
        value VARCHAR(500) NOT NULL,
        reason VARCHAR(255) NOT NULL,
        added_by INT NULL COMMENT 'Admin who added this entry',
        expires_at TIMESTAMP NULL COMMENT 'NULL for permanent ban',
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_type_value (type, value),
        INDEX idx_is_active (is_active),
        INDEX idx_expires_at (expires_at)
    )";
    
    $pdo->exec($sql);
    $results[] = ['table' => 'api_blacklist', 'status' => 'success'];
    
} catch (Exception $e) {
    $results[] = ['table' => 'api_blacklist', 'status' => 'error', 'message' => $e->getMessage()];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fix API Tables - Admin - Tennessee Golf Courses</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        body {
            background: #f8fafc;
            font-family: 'Inter', sans-serif;
            padding: 2rem;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 2rem;
        }
        
        .result-item {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .result-success {
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            color: #16a34a;
        }
        
        .result-error {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Fix API Tables</h1>
        <p>Adding missing API tables to complete security system setup.</p>
        
        <?php foreach ($results as $result): ?>
            <div class="result-item result-<?php echo $result['status']; ?>">
                <strong><?php echo $result['table']; ?></strong>
                <span><?php echo strtoupper($result['status']); ?></span>
            </div>
            <?php if (isset($result['message'])): ?>
                <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.5rem;">
                    <?php echo htmlspecialchars($result['message']); ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        
        <a href="/admin/test-security" class="btn">Test Security System</a>
        <a href="/admin/dashboard" class="btn" style="background: #6b7280; margin-left: 1rem;">Back to Dashboard</a>
    </div>
</body>
</html>