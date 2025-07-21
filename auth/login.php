<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tennessee Golf Courses</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        body {
            background: linear-gradient(135deg, var(--bg-light) 0%, var(--bg-white) 100%);
            min-height: 100vh;
        }
        
        /* Hide weather bar on auth pages */
        .weather-bar {
            display: none;
        }
        
        .auth-page .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            background: var(--bg-white);
            box-shadow: var(--shadow-light);
        }
        
        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 80px 20px 40px;
        }
        
        .auth-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn-primary {
            background: #2c5934;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }
        .btn-primary:hover {
            background: #1e3f26;
        }
        .error {
            color: red;
            margin-bottom: 15px;
            padding: 10px;
            background: #ffebee;
            border-radius: 5px;
        }
        .success {
            color: green;
            margin-bottom: 15px;
            padding: 10px;
            background: #e8f5e8;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-container">
            <a href="../index.html" class="logo">Tennessee Golf Courses</a>
            <ul class="nav-menu">
                <li><a href="../index.html">Home</a></li>
                <li><a href="login.php" class="active">Login</a></li>
                <li><a href="register.php">Register</a></li>
            </ul>
        </div>
    </nav>

    <main class="auth-page">
        <div class="auth-container">
            <h2>Welcome Back</h2>
            <p>Sign in to your account</p>

            <?php
            if (isset($_GET['error'])) {
                echo '<div class="error">' . htmlspecialchars($_GET['error']) . '</div>';
            }
            if (isset($_GET['success'])) {
                echo '<div class="success">' . htmlspecialchars($_GET['success']) . '</div>';
            }
            ?>

            <form action="login-process.php" method="POST">
                <div class="form-group">
                    <label for="username">Username or Email</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn-primary">Sign In</button>
            </form>

            <p style="text-align: center; margin-top: 20px;">
                Don't have an account? <a href="register.php">Create one here</a>
            </p>
        </div>
    </main>
</body>
</html>