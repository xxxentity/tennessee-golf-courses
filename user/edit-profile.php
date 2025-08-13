<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: /login?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($first_name) || empty($last_name) || empty($email)) {
        $error = 'First name, last name, and email are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            // Check if email is already taken by another user
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$email, $user_id]);
            if ($stmt->fetch()) {
                $error = 'This email address is already in use by another account.';
            } else {
                // Start building the update query
                $update_fields = ['first_name = ?', 'last_name = ?', 'email = ?'];
                $update_values = [$first_name, $last_name, $email];
                
                // Handle password change if requested
                if (!empty($new_password)) {
                    if (empty($current_password)) {
                        $error = 'Please enter your current password to change your password.';
                    } elseif (strlen($new_password) < 6) {
                        $error = 'New password must be at least 6 characters long.';
                    } elseif ($new_password !== $confirm_password) {
                        $error = 'New password and confirmation do not match.';
                    } else {
                        // Verify current password
                        $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
                        $stmt->execute([$user_id]);
                        $user = $stmt->fetch();
                        
                        if (!password_verify($current_password, $user['password_hash'])) {
                            $error = 'Current password is incorrect.';
                        } else {
                            // Add password to update
                            $update_fields[] = 'password_hash = ?';
                            $update_values[] = password_hash($new_password, PASSWORD_DEFAULT);
                        }
                    }
                }
                
                // Perform update if no errors
                if (empty($error)) {
                    $update_values[] = $user_id;
                    $sql = "UPDATE users SET " . implode(', ', $update_fields) . " WHERE id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($update_values);
                    
                    // Update session data
                    $_SESSION['first_name'] = $first_name;
                    $_SESSION['last_name'] = $last_name;
                    $_SESSION['email'] = $email;
                    
                    $success = 'Profile updated successfully!';
                }
            }
        } catch (PDOException $e) {
            $error = 'Update failed. Please try again.';
        }
    }
}

// Get current user data
try {
    $stmt = $pdo->prepare("SELECT username, email, first_name, last_name FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    if (!$user) {
        header('Location: /logout');
        exit;
    }
} catch (PDOException $e) {
    $error = 'Failed to load user data.';
    $user = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Tennessee Golf Courses</title>
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../images/logos/tab-logo.webp?v=2">
    <link rel="shortcut icon" href="../images/logos/tab-logo.webp?v=2">
    <style>
        body {
            background: linear-gradient(135deg, var(--bg-light) 0%, var(--bg-white) 100%);
            min-height: 100vh;
        }
        
        /* Weather bar and navbar use default positioning from styles.css */
        
        body {
            padding-top: 140px; /* Account for weather bar + header */
        }
        
        .profile-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 20px 40px;
            margin-top: 0;
        }
        
        .profile-container {
            max-width: 600px;
            width: 100%;
            background: var(--bg-white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-heavy);
            overflow: hidden;
            border: 1px solid var(--border-color);
        }
        
        .profile-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--text-light);
            padding: 40px 40px 30px;
            text-align: center;
            position: relative;
        }
        
        .profile-header h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }
        
        .profile-header p {
            opacity: 0.9;
            font-size: 1.1rem;
            position: relative;
            z-index: 1;
        }
        
        .profile-body {
            padding: 40px;
        }
        
        .form-group {
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .form-group label {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            min-width: 140px;
            text-align: right;
            line-height: 1.2;
        }
        
        .form-group input {
            width: 300px;
            padding: 18px 16px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 16px;
            font-family: inherit;
            transition: all 0.3s ease;
            background: var(--bg-white);
            color: var(--text-dark);
            box-sizing: border-box;
            height: 56px;
            line-height: 1.4;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(6, 78, 59, 0.1);
            transform: translateY(-1px);
        }
        
        .form-group input:hover {
            border-color: var(--secondary-color);
        }
        
        .password-section {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid var(--border-color);
        }
        
        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 20px;
            text-align: center;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--text-light);
            padding: 16px 32px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: var(--shadow-medium);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
            transform: translateY(-2px);
            box-shadow: var(--shadow-heavy);
        }
        
        .btn-secondary {
            background: transparent;
            color: var(--text-gray);
            padding: 16px 32px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
        }
        
        .btn-secondary:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-1px);
        }
        
        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-weight: 500;
            border: 1px solid;
        }
        
        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border-color: rgba(239, 68, 68, 0.2);
        }
        
        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
            border-color: rgba(34, 197, 94, 0.2);
        }
        
        .disabled-field {
            background: var(--bg-light) !important;
            color: var(--text-gray) !important;
            cursor: not-allowed;
        }
        
        .profile-picture-section {
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 2px solid var(--border-color);
            text-align: center;
        }
        
        .current-avatar {
            margin: 20px 0;
        }
        
        .current-profile-picture {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--border-color);
            box-shadow: var(--shadow-medium);
        }
        
        .default-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            border: 4px solid var(--border-color);
            box-shadow: var(--shadow-medium);
        }
        
        .upload-controls {
            margin-top: 20px;
        }
        
        .btn-upload {
            background: linear-gradient(135deg, var(--gold-color), #f59e0b);
            color: var(--text-light);
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: var(--shadow-light);
        }
        
        .btn-upload:hover {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            transform: translateY(-1px);
            box-shadow: var(--shadow-medium);
        }
        
        .upload-status {
            margin-top: 12px;
            font-size: 14px;
            min-height: 20px;
        }
        
        .upload-status.success {
            color: #16a34a;
        }
        
        .upload-status.error {
            color: #dc2626;
        }
        
        .upload-status.loading {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <!-- Weather Bar -->
    <div class="weather-bar">
        <div class="weather-container">
            <div class="weather-info">
                <div class="current-time">
                    <i class="fas fa-clock"></i>
                    <span id="current-time">Loading...</span>
                </div>
                <div class="weather-widget">
                    <i class="fas fa-cloud-sun"></i>
                    <span id="weather-temp">Perfect Golf Weather</span>
                    <span class="weather-location">Nashville, TN</span>
                </div>
            </div>
            <div class="golf-conditions">
                <div class="condition-item">
                    <i class="fas fa-wind"></i>
                    <span>Light Breeze</span>
                </div>
                <div class="condition-item">
                    <i class="fas fa-eye"></i>
                    <span>Clear</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Dynamic Navigation -->
    <?php include '../includes/navigation.php'; ?>

    <main class="profile-page">
        <div class="profile-container">
            <div class="profile-header">
                <h2>Edit Profile</h2>
                <p>Update your account information</p>
            </div>
            
            <div class="profile-body">
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>

                <div class="profile-picture-section">
                    <div class="section-title">Profile Picture</div>
                    <div class="current-avatar">
                        <?php if (!empty($user['profile_picture']) && file_exists('../' . $user['profile_picture'])): ?>
                            <img src="../<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Current Profile Picture" class="current-profile-picture" id="currentPicture">
                        <?php else: ?>
                            <div class="default-avatar" id="currentPicture">
                                <i class="fas fa-user"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="upload-controls">
                        <input type="file" id="profilePictureInput" accept="image/*" style="display: none;">
                        <button type="button" class="btn-upload" onclick="document.getElementById('profilePictureInput').click()">
                            <i class="fas fa-camera"></i>
                            Choose Picture
                        </button>
                        <div id="uploadStatus" class="upload-status"></div>
                    </div>
                </div>

                <form action="edit-profile" method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" disabled class="disabled-field">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required>
                    </div>

                    <div class="password-section">
                        <div class="section-title">Change Password (Optional)</div>
                        
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" placeholder="Enter current password">
                        </div>

                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" placeholder="Enter new password">
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password">
                        </div>
                    </div>

                    <button type="button" class="btn-secondary" onclick="window.location.href='/profile'">
                        <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>
                        Back to Profile
                    </button>
                    
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save" style="margin-right: 8px;"></i>
                        Update Profile
                    </button>
                </form>
            </div>
        </div>
    </main>
    
    <script>
        document.getElementById('profilePictureInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            const statusDiv = document.getElementById('uploadStatus');
            const currentPicture = document.getElementById('currentPicture');
            
            // Validate file
            if (!file.type.match(/^image\/(jpeg|jpg|png|gif)$/)) {
                statusDiv.className = 'upload-status error';
                statusDiv.textContent = 'Please select a valid image file (JPG, PNG, or GIF)';
                return;
            }
            
            if (file.size > 5 * 1024 * 1024) {
                statusDiv.className = 'upload-status error';
                statusDiv.textContent = 'File size must be less than 5MB';
                return;
            }
            
            // Show loading
            statusDiv.className = 'upload-status loading';
            statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
            
            // Create form data
            const formData = new FormData();
            formData.append('profile_picture', file);
            
            // Upload file
            fetch('upload-profile-picture.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    statusDiv.className = 'upload-status success';
                    statusDiv.innerHTML = '<i class="fas fa-check"></i> ' + data.message;
                    
                    // Update the displayed image
                    if (currentPicture.tagName === 'IMG') {
                        currentPicture.src = '../' + data.image_url + '?t=' + Date.now();
                    } else {
                        // Replace default avatar with image
                        currentPicture.outerHTML = '<img src="../' + data.image_url + '" alt="Current Profile Picture" class="current-profile-picture" id="currentPicture">';
                    }
                } else {
                    statusDiv.className = 'upload-status error';
                    statusDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + data.error;
                }
            })
            .catch(error => {
                statusDiv.className = 'upload-status error';
                statusDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Upload failed. Please try again.';
            });
        });
    </script>
    
    <script src="../script.js"></script>
</body>
</html>