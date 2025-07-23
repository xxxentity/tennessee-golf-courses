<!DOCTYPE html>
<html>
<head>
    <title>Navigation Debug Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .test-link { 
            display: block; 
            margin: 10px 0; 
            padding: 10px; 
            background: #f0f0f0; 
            text-decoration: none; 
            color: #333;
            border: 1px solid #ccc;
        }
        .test-link:hover { background: #e0e0e0; }
    </style>
</head>
<body>
    <h1>Navigation Debug Test</h1>
    <p>This page tests if basic HTML links work without any JavaScript interference.</p>
    
    <h2>Test Links (should work by clicking):</h2>
    <a href="/index.php" class="test-link">→ Home (index.php)</a>
    <a href="/courses.php" class="test-link">→ Courses (courses.php)</a>
    <a href="/news.php" class="test-link">→ News (news.php)</a>
    <a href="/about.php" class="test-link">→ About (about.php)</a>
    
    <h2>Same Links with nav-link class:</h2>
    <a href="/index.php" class="test-link nav-link">→ Home (with nav-link class)</a>
    <a href="/courses.php" class="test-link nav-link">→ Courses (with nav-link class)</a>
    
    <h2>JavaScript Test:</h2>
    <button onclick="window.location.href='/courses.php'">JS Navigate to Courses</button>
    <button onclick="console.log('Button clicked!')">Console Test</button>
    
    <script>
        console.log('Debug page loaded');
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM ready');
            
            // Test if clicks are being prevented
            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function(e) {
                    console.log('Link clicked:', this.href);
                    console.log('Default prevented?', e.defaultPrevented);
                });
            });
        });
    </script>
</body>
</html>