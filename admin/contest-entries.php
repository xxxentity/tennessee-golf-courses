<?php
require_once '../includes/admin-security.php';
require_once '../config/database.php';

// Check admin authentication
AdminSecurity::requireAuth();

// Get filters
$status_filter = $_GET['status'] ?? 'all';
$contest_filter = (int)($_GET['contest'] ?? 0);
$search = $_GET['search'] ?? '';

// Build WHERE clause
$where_conditions = [];
$params = [];

if ($status_filter !== 'all') {
    $where_conditions[] = "ce.status = ?";
    $params[] = $status_filter;
}

if ($contest_filter > 0) {
    $where_conditions[] = "ce.contest_id = ?";
    $params[] = $contest_filter;
}

if (!empty($search)) {
    $where_conditions[] = "(ce.full_name LIKE ? OR ce.email LIKE ? OR ce.city LIKE ?)";
    $search_param = '%' . $search . '%';
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
}

$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Get entries with user info
$sql = "
    SELECT ce.*, u.username 
    FROM contest_entries ce 
    LEFT JOIN users u ON ce.user_id = u.id 
    {$where_clause}
    ORDER BY ce.created_at DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$entries = $stmt->fetchAll();

// Get counts by status
$count_sql = "SELECT status, COUNT(*) as count FROM contest_entries GROUP BY status";
$count_stmt = $pdo->query($count_sql);
$status_counts = [];
while ($row = $count_stmt->fetch()) {
    $status_counts[$row['status']] = $row['count'];
}
$total_entries = array_sum($status_counts);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contest Entries - Admin Panel</title>
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <style>
        .admin-page {
            padding: 2rem;
            max-width: 1400px;
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
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            display: block;
        }
        
        .stat-label {
            color: #666;
            text-transform: uppercase;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .filters {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .filter-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .filter-group label {
            font-weight: 600;
            color: #333;
        }
        
        .filter-group select,
        .filter-group input {
            padding: 0.5rem;
            border: 2px solid #e1e5e9;
            border-radius: 5px;
            font-family: inherit;
        }
        
        .entries-table {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .entries-table table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .entries-table th {
            background: var(--primary-color);
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }
        
        .entries-table td {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: top;
        }
        
        .entries-table tr:hover {
            background: #f8f9fa;
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-approved {
            background: #d4edda;
            color: #155724;
        }
        
        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status-winner {
            background: #ffd700;
            color: #b8860b;
        }
        
        .photo-thumbnail {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-sm {
            padding: 0.25rem 0.75rem;
            font-size: 0.8rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .btn-approve {
            background: #28a745;
            color: white;
        }
        
        .btn-reject {
            background: #dc3545;
            color: white;
        }
        
        .btn-winner {
            background: #ffc107;
            color: #212529;
        }
        
        .btn-view {
            background: #007bff;
            color: white;
        }
        
        .no-entries {
            text-align: center;
            padding: 3rem;
            color: #666;
        }
        
        @media (max-width: 768px) {
            .admin-page {
                padding: 1rem;
            }
            
            .filters {
                flex-direction: column;
                align-items: stretch;
            }
            
            .entries-table {
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>
    <div class="admin-page">
        <div class="admin-header">
            <h1 class="admin-title">Contest Entries</h1>
            <div>
                <a href="/admin/dashboard" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-number"><?php echo $total_entries; ?></span>
                <span class="stat-label">Total Entries</span>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?php echo $status_counts['pending'] ?? 0; ?></span>
                <span class="stat-label">Pending</span>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?php echo $status_counts['approved'] ?? 0; ?></span>
                <span class="stat-label">Approved</span>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?php echo $status_counts['winner'] ?? 0; ?></span>
                <span class="stat-label">Winners</span>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters">
            <form method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; width: 100%;">
                <div class="filter-group">
                    <label for="status">Status:</label>
                    <select name="status" id="status">
                        <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>All Status</option>
                        <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="approved" <?php echo $status_filter === 'approved' ? 'selected' : ''; ?>>Approved</option>
                        <option value="rejected" <?php echo $status_filter === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                        <option value="winner" <?php echo $status_filter === 'winner' ? 'selected' : ''; ?>>Winner</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="search">Search:</label>
                    <input type="text" name="search" id="search" placeholder="Name, email, city..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Filter
                </button>
                
                <?php if (!empty($_GET)): ?>
                <a href="/admin/contest-entries" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Entries Table -->
        <div class="entries-table">
            <?php if (empty($entries)): ?>
                <div class="no-entries">
                    <i class="fas fa-inbox" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
                    <p>No contest entries found.</p>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Entry #</th>
                            <th>Participant</th>
                            <th>Contest</th>
                            <th>Location</th>
                            <th>Photo</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entries as $entry): ?>
                        <tr>
                            <td>
                                <strong>#<?php echo $entry['id']; ?></strong>
                            </td>
                            <td>
                                <div>
                                    <strong><?php echo htmlspecialchars($entry['full_name']); ?></strong><br>
                                    <small style="color: #666;"><?php echo htmlspecialchars($entry['email']); ?></small>
                                    <?php if (!empty($entry['phone'])): ?>
                                    <br><small style="color: #666;"><?php echo htmlspecialchars($entry['phone']); ?></small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>Contest #<?php echo $entry['contest_id']; ?></td>
                            <td><?php echo htmlspecialchars($entry['city'] . ', ' . $entry['state']); ?></td>
                            <td>
                                <?php if (!empty($entry['photo_path']) && file_exists($entry['photo_path'])): ?>
                                    <img src="/<?php echo htmlspecialchars($entry['photo_path']); ?>" 
                                         alt="Entry photo" 
                                         class="photo-thumbnail"
                                         onclick="window.open('/<?php echo htmlspecialchars($entry['photo_path']); ?>', '_blank')">
                                <?php else: ?>
                                    <span style="color: #999;">No photo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo $entry['status']; ?>">
                                    <?php echo ucfirst($entry['status']); ?>
                                </span>
                            </td>
                            <td>
                                <small><?php echo date('M j, Y \a\t g:i A', strtotime($entry['created_at'])); ?></small>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="/admin/contest-entry-details?id=<?php echo $entry['id']; ?>" 
                                       class="btn-sm btn-view" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <?php if ($entry['status'] === 'pending'): ?>
                                    <button onclick="updateStatus(<?php echo $entry['id']; ?>, 'approved')" 
                                            class="btn-sm btn-approve" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button onclick="updateStatus(<?php echo $entry['id']; ?>, 'rejected')" 
                                            class="btn-sm btn-reject" title="Reject">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <?php endif; ?>
                                    
                                    <?php if ($entry['status'] === 'approved'): ?>
                                    <button onclick="updateStatus(<?php echo $entry['id']; ?>, 'winner')" 
                                            class="btn-sm btn-winner" title="Mark as Winner">
                                        <i class="fas fa-trophy"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function updateStatus(entryId, newStatus) {
            if (!confirm(`Are you sure you want to mark this entry as ${newStatus}?`)) {
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