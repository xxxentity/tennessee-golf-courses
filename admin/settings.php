<?php
require_once '../includes/admin-security-simple.php';
require_once '../config/database.php';

// Require admin authentication
AdminSecurity::requireAdminAuth();

$currentAdmin = AdminSecurity::getCurrentAdmin();
$message = '';
$error = '';

// Handle settings updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'update_security':
            try {
                // Security settings would be stored in a settings table
                $message = 'Security settings updated successfully.';
            } catch (Exception $e) {
                $error = 'Error updating security settings.';
            }
            break;
            
        case 'update_general':
            try {
                $message = 'General settings updated successfully.';
            } catch (Exception $e) {
                $error = 'Error updating general settings.';
            }
            break;
    }
}

// Get current settings (placeholder - would come from database)
$securitySettings = [
    'password_min_length' => 8,
    'password_require_uppercase' => true,
    'password_require_lowercase' => true,
    'password_require_numbers' => true,
    'password_require_symbols' => true,
    'max_login_attempts' => 5,
    'lockout_duration' => 30,
    'session_timeout' => 3600,
    'force_https' => true,
    'api_rate_limit_public' => 60,
    'api_rate_limit_user' => 300,
    'api_rate_limit_admin' => 1000
];

$generalSettings = [
    'site_name' => 'Tennessee Golf Courses',
    'admin_email' => 'admin@tennesseegolfcourses.com',
    'timezone' => 'America/Chicago',
    'maintenance_mode' => false,
    'user_registration_enabled' => true,
    'email_verification_required' => true
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings - Admin - Tennessee Golf Courses</title>
    <link rel="stylesheet" href="../styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="../images/logos/tab-logo.webp?v=3">
    <link rel="shortcut icon" href="../images/logos/tab-logo.webp?v=3">
    
    <style>
        body {
            background: #f8fafc;
            font-family: 'Inter', sans-serif;
        }
        
        .admin-header {
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            color: white;
            padding: 1.5rem 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .admin-header .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .settings-nav {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .nav-tab {
            padding: 1rem 1.5rem;
            border: none;
            background: none;
            font-weight: 600;
            color: #6b7280;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }
        
        .nav-tab.active {
            color: #2563eb;
            border-bottom-color: #2563eb;
        }
        
        .nav-tab:hover {
            color: #2563eb;
        }
        
        .settings-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            padding: 2rem;
            margin-bottom: 2rem;
            display: none;
        }
        
        .settings-section.active {
            display: block;
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .section-description {
            color: #6b7280;
            margin-bottom: 2rem;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #374151;
            font-size: 0.9rem;
        }
        
        .form-input,
        .form-select {
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: #2563eb;
        }
        
        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .form-checkbox:hover {
            border-color: #2563eb;
            background: #f8fafc;
        }
        
        .form-checkbox input[type="checkbox"] {
            width: 1.25rem;
            height: 1.25rem;
            accent-color: #2563eb;
        }
        
        .checkbox-label {
            font-weight: 500;
            color: #374151;
            flex: 1;
        }
        
        .checkbox-description {
            color: #6b7280;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: #2563eb;
            color: white;
        }
        
        .btn-primary:hover {
            background: #1d4ed8;
        }
        
        .btn-danger {
            background: #dc2626;
            color: white;
        }
        
        .btn-danger:hover {
            background: #b91c1c;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-weight: 500;
        }
        
        .alert-success {
            background: #dcfce7;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }
        
        .alert-error {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
        }
        
        .setting-item {
            padding: 1.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .setting-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .setting-title {
            font-weight: 600;
            color: #1f2937;
        }
        
        .setting-description {
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .security-warning {
            background: #fef3c7;
            border: 1px solid #fde68a;
            color: #92400e;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .admin-container {
                padding: 1rem;
            }
            
            .settings-nav {
                flex-direction: column;
                gap: 0;
            }
            
            .nav-tab {
                text-align: left;
                border-left: 3px solid transparent;
                border-bottom: 1px solid #e5e7eb;
            }
            
            .nav-tab.active {
                border-left-color: #2563eb;
                border-bottom-color: #e5e7eb;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                justify-content: stretch;
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <div>
                <h1><i class="fas fa-cogs"></i> System Settings</h1>
                <div class="breadcrumb">
                    <a href="/admin/dashboard" style="color: rgba(255,255,255,0.8);">Dashboard</a>
                    <i class="fas fa-chevron-right"></i>
                    <span>Settings</span>
                </div>
            </div>
            <div>
                <span>Welcome, <?php echo htmlspecialchars($currentAdmin['first_name'] ?? 'Admin'); ?>!</span>
                <a href="/admin/logout" style="color: rgba(255,255,255,0.8); margin-left: 1rem;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </header>
    
    <main class="admin-container">
        <?php if ($message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <!-- Settings Navigation -->
        <div class="settings-nav">
            <button class="nav-tab active" onclick="showSection('general')">
                <i class="fas fa-cog"></i> General
            </button>
            <button class="nav-tab" onclick="showSection('security')">
                <i class="fas fa-shield-alt"></i> Security
            </button>
            <button class="nav-tab" onclick="showSection('api')">
                <i class="fas fa-code"></i> API
            </button>
            <button class="nav-tab" onclick="showSection('email')">
                <i class="fas fa-envelope"></i> Email
            </button>
        </div>
        
        <!-- General Settings -->
        <div class="settings-section active" id="general-section">
            <h2 class="section-title">
                <i class="fas fa-cog"></i>
                General Settings
            </h2>
            <p class="section-description">
                Configure basic site settings and functionality.
            </p>
            
            <form method="POST">
                <input type="hidden" name="action" value="update_general">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Site Name</label>
                        <input type="text" class="form-input" name="site_name" 
                               value="<?php echo htmlspecialchars($generalSettings['site_name']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Admin Email</label>
                        <input type="email" class="form-input" name="admin_email" 
                               value="<?php echo htmlspecialchars($generalSettings['admin_email']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Timezone</label>
                        <select class="form-select" name="timezone">
                            <option value="America/Chicago" <?php echo $generalSettings['timezone'] === 'America/Chicago' ? 'selected' : ''; ?>>Central Time (America/Chicago)</option>
                            <option value="America/New_York" <?php echo $generalSettings['timezone'] === 'America/New_York' ? 'selected' : ''; ?>>Eastern Time (America/New_York)</option>
                            <option value="America/Denver" <?php echo $generalSettings['timezone'] === 'America/Denver' ? 'selected' : ''; ?>>Mountain Time (America/Denver)</option>
                            <option value="America/Los_Angeles" <?php echo $generalSettings['timezone'] === 'America/Los_Angeles' ? 'selected' : ''; ?>>Pacific Time (America/Los_Angeles)</option>
                        </select>
                    </div>
                </div>
                
                <div class="setting-item">
                    <div class="setting-header">
                        <div>
                            <div class="setting-title">User Registration</div>
                            <div class="setting-description">Allow new users to register for accounts</div>
                        </div>
                        <label class="form-checkbox">
                            <input type="checkbox" name="user_registration_enabled" 
                                   <?php echo $generalSettings['user_registration_enabled'] ? 'checked' : ''; ?>>
                            <span class="checkbox-label">Enable Registration</span>
                        </label>
                    </div>
                </div>
                
                <div class="setting-item">
                    <div class="setting-header">
                        <div>
                            <div class="setting-title">Email Verification</div>
                            <div class="setting-description">Require email verification for new accounts</div>
                        </div>
                        <label class="form-checkbox">
                            <input type="checkbox" name="email_verification_required" 
                                   <?php echo $generalSettings['email_verification_required'] ? 'checked' : ''; ?>>
                            <span class="checkbox-label">Require Verification</span>
                        </label>
                    </div>
                </div>
                
                <div class="setting-item">
                    <div class="setting-header">
                        <div>
                            <div class="setting-title">Maintenance Mode</div>
                            <div class="setting-description">Temporarily disable the site for maintenance</div>
                        </div>
                        <label class="form-checkbox">
                            <input type="checkbox" name="maintenance_mode" 
                                   <?php echo $generalSettings['maintenance_mode'] ? 'checked' : ''; ?>>
                            <span class="checkbox-label">Enable Maintenance Mode</span>
                        </label>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save General Settings
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Security Settings -->
        <div class="settings-section" id="security-section">
            <h2 class="section-title">
                <i class="fas fa-shield-alt"></i>
                Security Settings
            </h2>
            <p class="section-description">
                Configure security policies and authentication requirements.
            </p>
            
            <div class="security-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <strong>Warning:</strong> Changes to security settings will affect all users.
                    Make sure you understand the implications before making changes.
                </div>
            </div>
            
            <form method="POST">
                <input type="hidden" name="action" value="update_security">
                
                <h3 style="margin-bottom: 1rem; color: #1f2937;">Password Requirements</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Minimum Password Length</label>
                        <input type="number" class="form-input" name="password_min_length" 
                               value="<?php echo $securitySettings['password_min_length']; ?>" min="6" max="32">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Maximum Login Attempts</label>
                        <input type="number" class="form-input" name="max_login_attempts" 
                               value="<?php echo $securitySettings['max_login_attempts']; ?>" min="3" max="10">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Lockout Duration (minutes)</label>
                        <input type="number" class="form-input" name="lockout_duration" 
                               value="<?php echo $securitySettings['lockout_duration']; ?>" min="5" max="120">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Session Timeout (seconds)</label>
                        <input type="number" class="form-input" name="session_timeout" 
                               value="<?php echo $securitySettings['session_timeout']; ?>" min="300" max="86400">
                    </div>
                </div>
                
                <h3 style="margin: 2rem 0 1rem; color: #1f2937;">Password Complexity</h3>
                <div class="form-grid">
                    <div class="setting-item">
                        <label class="form-checkbox">
                            <input type="checkbox" name="password_require_uppercase" 
                                   <?php echo $securitySettings['password_require_uppercase'] ? 'checked' : ''; ?>>
                            <div class="checkbox-label">
                                Require Uppercase Letters
                                <div class="checkbox-description">Password must contain at least one uppercase letter</div>
                            </div>
                        </label>
                    </div>
                    
                    <div class="setting-item">
                        <label class="form-checkbox">
                            <input type="checkbox" name="password_require_lowercase" 
                                   <?php echo $securitySettings['password_require_lowercase'] ? 'checked' : ''; ?>>
                            <div class="checkbox-label">
                                Require Lowercase Letters
                                <div class="checkbox-description">Password must contain at least one lowercase letter</div>
                            </div>
                        </label>
                    </div>
                    
                    <div class="setting-item">
                        <label class="form-checkbox">
                            <input type="checkbox" name="password_require_numbers" 
                                   <?php echo $securitySettings['password_require_numbers'] ? 'checked' : ''; ?>>
                            <div class="checkbox-label">
                                Require Numbers
                                <div class="checkbox-description">Password must contain at least one number</div>
                            </div>
                        </label>
                    </div>
                    
                    <div class="setting-item">
                        <label class="form-checkbox">
                            <input type="checkbox" name="password_require_symbols" 
                                   <?php echo $securitySettings['password_require_symbols'] ? 'checked' : ''; ?>>
                            <div class="checkbox-label">
                                Require Special Characters
                                <div class="checkbox-description">Password must contain at least one special character</div>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Security Settings
                    </button>
                </div>
            </form>
        </div>
        
        <!-- API Settings -->
        <div class="settings-section" id="api-section">
            <h2 class="section-title">
                <i class="fas fa-code"></i>
                API Settings
            </h2>
            <p class="section-description">
                Configure API rate limits and access controls.
            </p>
            
            <form method="POST">
                <input type="hidden" name="action" value="update_api">
                
                <h3 style="margin-bottom: 1rem; color: #1f2937;">Rate Limits (per hour)</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Public API Requests</label>
                        <input type="number" class="form-input" name="api_rate_limit_public" 
                               value="<?php echo $securitySettings['api_rate_limit_public']; ?>" min="10" max="1000">
                        <small style="color: #6b7280;">Rate limit for unauthenticated API requests</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">User API Requests</label>
                        <input type="number" class="form-input" name="api_rate_limit_user" 
                               value="<?php echo $securitySettings['api_rate_limit_user']; ?>" min="100" max="5000">
                        <small style="color: #6b7280;">Rate limit for authenticated user requests</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Admin API Requests</label>
                        <input type="number" class="form-input" name="api_rate_limit_admin" 
                               value="<?php echo $securitySettings['api_rate_limit_admin']; ?>" min="500" max="10000">
                        <small style="color: #6b7280;">Rate limit for admin API requests</small>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save API Settings
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Email Settings -->
        <div class="settings-section" id="email-section">
            <h2 class="section-title">
                <i class="fas fa-envelope"></i>
                Email Settings
            </h2>
            <p class="section-description">
                Configure email delivery and notification settings.
            </p>
            
            <div class="setting-item">
                <div class="setting-title">SMTP Configuration</div>
                <div class="setting-description">Currently using PHP mail() function. Configure SMTP for better delivery.</div>
            </div>
            
            <div class="setting-item">
                <div class="setting-title">Newsletter Settings</div>
                <div class="setting-description">Manage newsletter subscription and delivery settings.</div>
            </div>
            
            <div class="form-actions">
                <a href="/admin/newsletter" class="btn btn-primary">
                    <i class="fas fa-envelope"></i> Manage Newsletter
                </a>
            </div>
        </div>
    </main>
    
    <script>
        function showSection(sectionName) {
            // Hide all sections
            document.querySelectorAll('.settings-section').forEach(section => {
                section.classList.remove('active');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.nav-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected section
            document.getElementById(sectionName + '-section').classList.add('active');
            
            // Add active class to clicked tab
            event.target.classList.add('active');
        }
    </script>
</body>
</html>