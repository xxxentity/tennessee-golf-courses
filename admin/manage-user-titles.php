<?php
require_once '../includes/admin-security-simple.php';
require_once '../config/database.php';

// Require admin authentication
AdminSecurity::requireAdminAuth();

$currentAdmin = AdminSecurity::getCurrentAdmin();

// Handle form submission
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id']) && isset($_POST['user_title'])) {
    $user_id = intval($_POST['user_id']);
    $user_title = trim($_POST['user_title']);
    
    // Common titles for quick selection
    $allowedTitles = [
        'Verified User',
        'Site Administrator', 
        'Editor-in-Chief',
        'News Editor',
        'Reviews Editor',
        'Contributing Writer',
        'Guest Contributor'
    ];
    
    // Allow custom titles but sanitize them
    if (!in_array($user_title, $allowedTitles)) {
        $user_title = substr(htmlspecialchars($user_title), 0, 50);
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE users SET user_title = ? WHERE id = ?");
        $stmt->execute([$user_title, $user_id]);
        $message = "User title updated successfully!";
        $messageType = 'success';
    } catch (PDOException $e) {
        $message = "Error updating user title: " . $e->getMessage();
        $messageType = 'error';
    }
}

// Get all users
try {
    $stmt = $pdo->query("SELECT id, username, email, first_name, last_name, user_title, created_at FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    $users = [];
    $message = "Error loading users: " . $e->getMessage();
    $messageType = 'error';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage User Titles - Admin Dashboard</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 2rem;
        }
        
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e5e5e5;
        }
        
        .admin-header h1 {
            color: #064E3B;
            margin: 0;
        }
        
        .back-link {
            color: #064E3B;
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .message {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .users-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .users-table th {
            background: #064E3B;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 500;
        }
        
        .users-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e5e5;
        }
        
        .users-table tr:hover {
            background: #f9f9f9;
        }
        
        .title-form {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        
        .title-select {
            padding: 6px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .btn-update {
            background: #064E3B;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn-update:hover {
            background: #0a5c47;
        }
        
        .current-title {
            background: #f0f9ff;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 500;
            color: #064E3B;
        }
        
        .quick-titles {
            margin-bottom: 2rem;
            padding: 1rem;
            background: #f9f9f9;
            border-radius: 8px;
        }
        
        .quick-titles h3 {
            margin-top: 0;
            color: #064E3B;
        }
        
        .title-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-right: 8px;
            background: #e7f5e7;
            color: #064E3B;
        }
        
        @media (max-width: 768px) {
            .users-table {
                font-size: 14px;
            }
            
            .users-table th, .users-table td {
                padding: 8px;
            }
            
            .title-form {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>Manage User Titles</h1>
            <a href="/admin/dashboard" class="back-link">← Back to Dashboard</a>
        </div>
        
        <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="quick-titles">
            <h3>Available Titles:</h3>
            <span class="title-badge">Verified User</span>
            <span class="title-badge">Site Administrator</span>
            <span class="title-badge">Editor-in-Chief</span>
            <span class="title-badge">News Editor</span>
            <span class="title-badge">Reviews Editor</span>
            <span class="title-badge">Contributing Writer</span>
            <span class="title-badge">Guest Contributor</span>
        </div>
        
        <table class="users-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Current Title</th>
                    <th>Update Title</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                    <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <span class="current-title">
                            <?php echo htmlspecialchars($user['user_title'] ?? 'Verified User'); ?>
                        </span>
                    </td>
                    <td>
                        <form method="POST" class="title-form">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <select name="user_title" class="title-select">
                                <option value="Verified User" <?php echo ($user['user_title'] ?? 'Verified User') == 'Verified User' ? 'selected' : ''; ?>>Verified User</option>
                                <option value="Site Administrator" <?php echo $user['user_title'] == 'Site Administrator' ? 'selected' : ''; ?>>Site Administrator</option>
                                <option value="Editor-in-Chief" <?php echo $user['user_title'] == 'Editor-in-Chief' ? 'selected' : ''; ?>>Editor-in-Chief</option>
                                <option value="News Editor" <?php echo $user['user_title'] == 'News Editor' ? 'selected' : ''; ?>>News Editor</option>
                                <option value="Reviews Editor" <?php echo $user['user_title'] == 'Reviews Editor' ? 'selected' : ''; ?>>Reviews Editor</option>
                                <option value="Contributing Writer" <?php echo $user['user_title'] == 'Contributing Writer' ? 'selected' : ''; ?>>Contributing Writer</option>
                                <option value="Guest Contributor" <?php echo $user['user_title'] == 'Guest Contributor' ? 'selected' : ''; ?>>Guest Contributor</option>
                            </select>
                            <button type="submit" class="btn-update">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>