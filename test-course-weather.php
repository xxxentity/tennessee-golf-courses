<!DOCTYPE html>
<html>
<head>
    <title>Course Weather API Test</title>
</head>
<body>
    <h1>Testing Course Weather API</h1>
    
    <h2>Avalon Golf & Country Club</h2>
    <div id="avalon-result">Loading...</div>
    
    <h2>Bear Trace at Cumberland Mountain</h2>
    <div id="bear-trace-result">Loading...</div>
    
    <script>
    // Test Avalon
    fetch('/course-weather-api.php?course=avalon-golf-country-club')
        .then(response => response.json())
        .then(data => {
            document.getElementById('avalon-result').innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
        })
        .catch(error => {
            document.getElementById('avalon-result').innerHTML = 'Error: ' + error;
        });
    
    // Test Bear Trace
    fetch('/course-weather-api.php?course=bear-trace-cumberland-mountain')
        .then(response => response.json())
        .then(data => {
            document.getElementById('bear-trace-result').innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
        })
        .catch(error => {
            document.getElementById('bear-trace-result').innerHTML = 'Error: ' + error;
        });
    </script>
</body>
</html>