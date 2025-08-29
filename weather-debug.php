<!DOCTYPE html>
<html>
<head>
    <title>Weather Bar Debug</title>
    <style>
        body { margin: 0; font-family: Arial; }
        .debug-bar {
            background: linear-gradient(135deg, #064e3b 0%, #059669 100%);
            color: white;
            padding: 8px 0;
            font-size: 14px;
            width: 100%;
            text-align: center;
            border: 2px solid red;
        }
        .debug-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            border: 2px solid yellow;
        }
        .debug-content {
            display: flex;
            gap: 20px;
            align-items: center;
            justify-content: center;
            border: 2px solid blue;
            padding: 10px;
        }
    </style>
</head>
<body>
    <h1>Weather Bar Debug Test</h1>
    
    <h2>Simple Centered Test:</h2>
    <div class="debug-bar">
        <div class="debug-container">
            <div class="debug-content">
                <span>Tuesday, January 28, 2025 at 10:45 AM</span>
                <span>Nashville, TN: 60°F</span>
                <span>Wind: 12 mph</span>
            </div>
        </div>
    </div>
    
    <h2>Current Weather API Test:</h2>
    <div id="api-test">Loading weather data...</div>
    
    <script>
    // Test the weather API
    fetch('/weather-api.php')
        .then(response => response.json())
        .then(data => {
            console.log('Weather API data:', data);
            document.getElementById('api-test').innerHTML = 
                '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
        })
        .catch(error => {
            console.error('API Error:', error);
            document.getElementById('api-test').innerHTML = 
                '<p style="color: red;">API Error: ' + error + '</p>';
        });
    </script>
</body>
</html>