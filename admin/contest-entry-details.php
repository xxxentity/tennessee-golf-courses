<?php
require_once '../includes/admin-security-simple.php';
require_once '../config/database.php';

// Require admin authentication
AdminSecurity::requireAdminAuth();

$entry_id = (int)($_GET['id'] ?? 0);

if ($entry_id <= 0) {
    header('Location: /admin/contest-entries');
    exit;
}

// Get entry details with user info
$stmt = $pdo->prepare("
    SELECT ce.*, u.username 
    FROM contest_entries ce 
    LEFT JOIN users u ON ce.user_id = u.id 
    WHERE ce.id = ?
");
$stmt->execute([$entry_id]);
$entry = $stmt->fetch();

if (!$entry) {
    header('Location: /admin/contest-entries');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contest Entry #<?php echo $entry['id']; ?> - Admin Panel</title>
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <style>
        .admin-page {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            background: #f8f9fa;
            min-height: 100vh;
        }
        
        .admin-header {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .admin-title {
            color: var(--primary-color);
            margin: 0;
            font-size: 2rem;
        }
        
        .entry-details {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .entry-header {
            background: var(--primary-color);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .entry-id {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .entry-status {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        
        .status-pending { background: #fff3cd; color: #856404; }
        .status-approved { background: #d4edda; color: #155724; }
        .status-rejected { background: #f8d7da; color: #721c24; }
        .status-winner { background: #ffd700; color: #b8860b; }
        
        .entry-content {
            padding: 2rem;
        }
        
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .detail-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
        }
        
        .detail-section h3 {
            color: var(--primary-color);
            margin: 0 0 1rem 0;
            font-size: 1.2rem;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e1e5e9;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #666;
        }
        
        .detail-value {
            color: #333;
            text-align: right;
            max-width: 60%;
            word-break: break-word;
        }
        
        .photo-section {
            text-align: center;
            margin: 2rem 0;
        }
        
        .entry-photo {
            max-width: 100%;
            max-height: 500px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            cursor: pointer;
        }
        
        .photo-caption {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 10px;
            margin-top: 1rem;
            font-style: italic;
        }
        
        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-approve { background: #28a745; color: white; }
        .btn-reject { background: #dc3545; color: white; }
        .btn-winner { background: #ffc107; color: #212529; }
        .btn-secondary { background: #6c757d; color: white; }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        @media (max-width: 768px) {
            .admin-page {
                padding: 1rem;
            }
            
            .detail-grid {
                grid-template-columns: 1fr;
            }
            
            .detail-row {
                flex-direction: column;
                gap: 0.25rem;
            }
            
            .detail-value {
                text-align: left;
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="admin-page">
        <div class="admin-header">
            <h1 class="admin-title">Contest Entry Details</h1>
            <div>
                <a href="/admin/contest-entries" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Entries
                </a>
            </div>
        </div>

        <div class="entry-details">
            <div class="entry-header">
                <div class="entry-id">#<?php echo $entry['id']; ?></div>
                <div class="entry-status status-<?php echo $entry['status']; ?>">
                    <?php echo ucfirst($entry['status']); ?>
                </div>
            </div>
            
            <div class="entry-content">
                <div class="detail-grid">
                    <!-- Participant Information -->
                    <div class="detail-section">
                        <h3><i class="fas fa-user"></i> Participant Information</h3>
                        <div class="detail-row">
                            <span class="detail-label">Full Name:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($entry['full_name']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Email:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($entry['email']); ?></span>
                        </div>
                        <?php if (!empty($entry['phone'])): ?>
                        <div class="detail-row">
                            <span class="detail-label">Phone:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($entry['phone']); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="detail-row">
                            <span class="detail-label">Location:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($entry['city'] . ', ' . $entry['state']); ?></span>
                        </div>
                        <?php if (!empty($entry['favorite_course'])): ?>
                        <div class="detail-row">
                            <span class="detail-label">Favorite Course:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($entry['favorite_course']); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="detail-row">
                            <span class="detail-label">Newsletter:</span>
                            <span class="detail-value"><?php echo $entry['newsletter_signup'] ? 'Yes' : 'No'; ?></span>
                        </div>
                        <?php if (!empty($entry['username'])): ?>
                        <div class="detail-row">
                            <span class="detail-label">Username:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($entry['username']); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Contest Information -->
                    <div class="detail-section">
                        <h3><i class="fas fa-trophy"></i> Contest Information</h3>
                        <div class="detail-row">
                            <span class="detail-label">Contest ID:</span>
                            <span class="detail-value"><?php echo $entry['contest_id']; ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Status:</span>
                            <span class="detail-value">
                                <span class="entry-status status-<?php echo $entry['status']; ?>">
                                    <?php echo ucfirst($entry['status']); ?>
                                </span>
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Submitted:</span>
                            <span class="detail-value"><?php echo date('M j, Y \a\t g:i A', strtotime($entry['created_at'])); ?></span>
                        </div>
                        <?php if (!empty($entry['updated_at'])): ?>
                        <div class="detail-row">
                            <span class="detail-label">Last Updated:</span>
                            <span class="detail-value"><?php echo date('M j, Y \a\t g:i A', strtotime($entry['updated_at'])); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="detail-row">
                            <span class="detail-label">Entry IP:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($entry['entry_ip'] ?? 'Unknown'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Photo Section -->
                <?php if (!empty($entry['photo_path']) && file_exists('../' . $entry['photo_path'])): ?>
                <div class="photo-section">
                    <h3><i class="fas fa-camera"></i> Submitted Photo</h3>
                    <img src="/<?php echo htmlspecialchars($entry['photo_path']); ?>" 
                         alt="Contest entry photo" 
                         class="entry-photo"
                         onclick="window.open('/<?php echo htmlspecialchars($entry['photo_path']); ?>', '_blank')">
                    
                    <?php if (!empty($entry['photo_caption'])): ?>
                    <div class="photo-caption">
                        <strong>Caption:</strong> <?php echo htmlspecialchars($entry['photo_caption']); ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Actions -->
                <div class="actions">
                    <?php if ($entry['status'] === 'pending'): ?>
                    <button onclick="updateStatus(<?php echo $entry['id']; ?>, 'approved')" 
                            class="btn btn-approve">
                        <i class="fas fa-check"></i> Approve Entry
                    </button>
                    <button onclick="updateStatus(<?php echo $entry['id']; ?>, 'rejected')" 
                            class="btn btn-reject">
                        <i class="fas fa-times"></i> Reject Entry
                    </button>
                    <?php endif; ?>
                    
                    <?php if ($entry['status'] === 'approved'): ?>
                    <button onclick="updateStatus(<?php echo $entry['id']; ?>, 'winner')" 
                            class="btn btn-winner">
                        <i class="fas fa-trophy"></i> Mark as Winner
                    </button>
                    <button onclick="updateStatus(<?php echo $entry['id']; ?>, 'rejected')" 
                            class="btn btn-reject">
                        <i class="fas fa-times"></i> Reject Entry
                    </button>
                    <?php endif; ?>
                    
                    <?php if ($entry['status'] === 'rejected'): ?>
                    <button onclick="updateStatus(<?php echo $entry['id']; ?>, 'approved')" 
                            class="btn btn-approve">
                        <i class="fas fa-check"></i> Approve Entry
                    </button>
                    <?php endif; ?>
                    
                    <?php if ($entry['status'] === 'winner'): ?>
                    <button onclick="updateStatus(<?php echo $entry['id']; ?>, 'approved')" 
                            class="btn btn-approve">
                        <i class="fas fa-undo"></i> Remove Winner Status
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateStatus(entryId, newStatus) {
            const statusNames = {
                'approved': 'approved',
                'rejected': 'rejected', 
                'winner': 'winner'
            };
            
            if (!confirm(`Are you sure you want to mark this entry as ${statusNames[newStatus]}?`)) {
                return;
            }
            
            fetch('/admin/update-contest-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    entry_id: entryId,
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error updating status: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the status.');
            });
        }
    </script>
</body>
</html>