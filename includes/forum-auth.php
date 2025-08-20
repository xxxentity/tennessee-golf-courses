<?php
// Forum password protection - under construction
session_start();

// Forum password (change this to your desired password)
$forum_password = 'golf2025';

// Check if password is already verified in session
if (isset($_SESSION['forum_access']) && $_SESSION['forum_access'] === true) {
    return; // Access granted, continue loading page
}

// Handle password submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forum_password'])) {
    $entered_password = $_POST['forum_password'];
    
    if ($entered_password === $forum_password) {
        $_SESSION['forum_access'] = true;
        // Redirect to remove POST data and reload page
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    } else {
        $error_message = 'Incorrect password. Please try again.';
    }
}

// Show password form if not authenticated
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum Access - Tennessee Golf Courses</title>
    <link rel="stylesheet" href="/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <?php 
    // Determine the correct path to includes based on current directory
    $includes_path = (basename(dirname($_SERVER['SCRIPT_NAME'])) === 'forum') ? '../includes' : 'includes';
    include $includes_path . '/favicon.php'; 
    ?>
    
    <style>
        .forum-auth-page {
            padding-top: 90px;
            min-height: 80vh;
            background: linear-gradient(135deg, var(--bg-light), var(--bg-white));
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .auth-container {
            background: var(--bg-white);
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            padding: 3rem;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        
        .auth-container h1 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 2rem;
            font-weight: 700;
        }
        
        .auth-container p {
            color: var(--text-gray);
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .password-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .password-input {
            padding: 1rem;
            border: 2px solid var(--bg-light);
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .password-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(6, 78, 59, 0.1);
        }
        
        .submit-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }
        
        .error-message {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            border: 1px solid #fca5a5;
            color: #991b1b;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        
        .construction-notice {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border: 1px solid #f59e0b;
            color: #92400e;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <div class="forum-auth-page">
        <!-- Dynamic Navigation -->
        <?php include $includes_path . '/navigation.php'; ?>
        
        <div class="auth-container">
            <h1><i class="fas fa-construction"></i> Forum Access</h1>
            
            <div class="construction-notice">
                <strong><i class="fas fa-tools"></i> Under Construction</strong><br>
                The forum is currently being developed and tested. Access is restricted during this phase.
            </div>
            
            <p>Please enter the access password to view the forum pages:</p>
            
            <?php if (isset($error_message)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="password-form">
                <input type="password" 
                       name="forum_password" 
                       class="password-input" 
                       placeholder="Enter forum password" 
                       required 
                       autofocus>
                <button type="submit" class="submit-btn">
                    <i class="fas fa-unlock"></i> Access Forum
                </button>
            </form>
            
            <p style="margin-top: 2rem; font-size: 0.9rem;">
                <a href="/" style="color: var(--primary-color); text-decoration: none;">
                    <i class="fas fa-arrow-left"></i> Back to Homepage
                </a>
            </p>
        </div>
    </div>
    
    <!-- Dynamic Footer -->
    <?php include $includes_path . '/footer.php'; ?>
</body>
</html>
<?php
exit; // Stop execution if not authenticated
?>