<?php
// Test the NWS API directly
header('Content-Type: text/html');

echo "<h1>Weather API Test</h1>";
echo "<p>Testing National Weather Service API access...</p>";

// Test 1: Check if we can reach the NWS API
echo "<h2>Test 1: Current Observations from KBNA</h2>";
$stationUrl = 'https://api.weather.gov/stations/KBNA/observations/latest';

$context = stream_context_create([
    'http' => [
        'timeout' => 15,
        'user_agent' => 'TennesseeGolfCourses/1.0 (contact@tennesseegolfcourses.com)',
        'method' => 'GET',
        'header' => "Accept: application/json\r\n"
    ]
]);

$observationJson = @file_get_contents($stationUrl, false, $context);

if ($observationJson === false) {
    echo "<p style='color: red;'>❌ Failed to fetch from NWS API</p>";
    $error = error_get_last();
    echo "<p>Error: " . htmlspecialchars($error['message']) . "</p>";
} else {
    echo "<p style='color: green;'>✅ Successfully fetched data</p>";
    $observation = json_decode($observationJson, true);
    
    if ($observation && isset($observation['properties'])) {
        $props = $observation['properties'];
        
        // Temperature
        $tempC = $props['temperature']['value'] ?? null;
        if ($tempC !== null) {
            $tempF = round(($tempC * 9/5) + 32);
            echo "<p>Temperature: {$tempF}°F</p>";
        } else {
            echo "<p>Temperature: Not available</p>";
        }
        
        // Wind
        $windMs = $props['windSpeed']['value'] ?? null;
        if ($windMs !== null) {
            $windMph = round($windMs * 2.237);
            echo "<p>Wind Speed: {$windMph} mph</p>";
        } else {
            echo "<p>Wind Speed: Not available</p>";
        }
        
        echo "<details><summary>Raw observation data</summary><pre>";
        echo htmlspecialchars(json_encode($props, JSON_PRETTY_PRINT));
        echo "</pre></details>";
    }
}

// Test 2: Check forecast API
echo "<h2>Test 2: Forecast for Precipitation</h2>";
$forecastUrl = 'https://api.weather.gov/gridpoints/OHX/50,57/forecast/hourly';
$forecastJson = @file_get_contents($forecastUrl, false, $context);

if ($forecastJson === false) {
    echo "<p style='color: red;'>❌ Failed to fetch forecast</p>";
    $error = error_get_last();
    echo "<p>Error: " . htmlspecialchars($error['message']) . "</p>";
} else {
    echo "<p style='color: green;'>✅ Successfully fetched forecast</p>";
    $forecast = json_decode($forecastJson, true);
    
    if ($forecast && isset($forecast['properties']['periods'][0])) {
        $period = $forecast['properties']['periods'][0];
        $precipProb = $period['probabilityOfPrecipitation']['value'] ?? 0;
        echo "<p>Precipitation Probability: {$precipProb}%</p>";
        
        echo "<details><summary>First period data</summary><pre>";
        echo htmlspecialchars(json_encode($period, JSON_PRETTY_PRINT));
        echo "</pre></details>";
    }
}

// Test 3: Check our weather-api.php endpoint
echo "<h2>Test 3: Our weather-api.php endpoint</h2>";
$apiUrl = '/weather-api.php';
echo "<p>Testing: <a href='{$apiUrl}'>{$apiUrl}</a></p>";

// Use JavaScript to test since it's a same-origin request
?>

<script>
fetch('/weather-api.php')
    .then(response => response.json())
    .then(data => {
        console.log('Weather API response:', data);
        document.write('<p style="color: green;">✅ JavaScript fetch succeeded</p>');
        document.write('<pre>' + JSON.stringify(data, null, 2) + '</pre>');
    })
    .catch(error => {
        console.error('Weather API error:', error);
        document.write('<p style="color: red;">❌ JavaScript fetch failed: ' + error + '</p>');
    });
</script>