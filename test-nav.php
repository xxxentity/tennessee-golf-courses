<!DOCTYPE html>
<html>
<head>
    <title>Navigation Test</title>
    <style>
        body { margin: 0; padding: 20px; font-family: Arial, sans-serif; }
        .test-nav { background: #f0f0f0; padding: 20px; margin-bottom: 20px; }
        .test-nav a { 
            display: inline-block; 
            margin: 0 10px; 
            padding: 10px 20px; 
            background: #007cba; 
            color: white; 
            text-decoration: none; 
            border-radius: 5px;
        }
        .test-nav a:hover { background: #005a8a; }
    </style>
</head>
<body>
    <div class="test-nav">
        <h2>Simple Navigation Test</h2>
        <a href="/index.php">Home</a>
        <a href="/courses.php">Courses</a>
        <a href="/news.php">News</a>
        <a href="/about.php">About</a>
        <a href="/contact.php">Contact</a>
        <a href="/reviews.php">Reviews</a>
    </div>
    
    <h1>Current Page: <?php echo $_SERVER['REQUEST_URI']; ?></h1>
    <p>If you can click these links above and navigate between pages, then the .htaccess clean URLs work fine.</p>
    <p>If they don't work, then there's still an .htaccess issue.</p>
    
    <h2>JavaScript Test</h2>
    <button onclick="console.log('JavaScript works'); alert('JS working');">Test JS</button>
    
    <h2>Debug Info</h2>
    <pre>
Script Name: <?php echo $_SERVER['SCRIPT_NAME']; ?>
Request URI: <?php echo $_SERVER['REQUEST_URI']; ?>
Query String: <?php echo $_SERVER['QUERY_STRING']; ?>
    </pre>
</body>
</html>