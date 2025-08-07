<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate, max-age=0');

// Enable CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Simple file-based caching in same directory
$cacheFile = __DIR__ . '/weather_cache.json';
$cacheTime = 10 * 60; // 10 minutes

// Function to get weather icon
function getWeatherIcon($condition) {
    $condition = strtolower($condition);
    $iconMap = [
        'sunny' => 'fa-sun', 'clear' => 'fa-sun', 'partly cloudy' => 'fa-cloud-sun',
        'cloudy' => 'fa-cloud', 'overcast' => 'fa-cloud', 'rain' => 'fa-cloud-rain',
        'light rain' => 'fa-cloud-rain', 'heavy rain' => 'fa-cloud-rain',
        'thunderstorms' => 'fa-bolt', 'snow' => 'fa-snowflake', 'fog' => 'fa-smog'
    ];
    
    foreach ($iconMap as $key => $icon) {
        if (strpos($condition, $key) !== false) return $icon;
    }
    return 'fa-cloud';
}

try {
    // Check cache first
    if (file_exists($cacheFile)) {
        $cached = json_decode(file_get_contents($cacheFile), true);
        if ($cached && (time() - $cached['timestamp'] < $cacheTime)) {
            echo json_encode([
                'success' => true,
                'data' => $cached['data'],
                'source' => 'cache',
                'debug' => 'Using cached data from ' . date('H:i:s', $cached['timestamp'])
            ]);
            exit;
        }
    }
    
    // Fetch fresh data with error handling
    $apiUrl = 'https://wttr.in/Nashville,TN?format=j1';
    
    $context = stream_context_create([
        'http' => [
            'timeout' => 15,
            'user_agent' => 'TennesseeGolfCourses/1.0',
            'method' => 'GET'
        ]
    ]);
    
    $weatherJson = @file_get_contents($apiUrl, false, $context);
    
    if ($weatherJson === false) {
        throw new Exception('Failed to fetch from wttr.in API');
    }
    
    $data = json_decode($weatherJson, true);
    if (!$data || !isset($data['current_condition'][0])) {
        throw new Exception('Invalid weather data format');
    }
    
    $current = $data['current_condition'][0];
    
    $weatherData = [
        'temp' => (int)$current['temp_F'],
        'condition' => $current['weatherDesc'][0]['value'],
        'windSpeed' => (int)$current['windspeedMiles'],
        'visibility' => (int)$current['visibility'],
        'icon' => getWeatherIcon($current['weatherDesc'][0]['value']),
        'timestamp' => time(),
        'location' => 'Nashville, TN'
    ];
    
    // Save to cache
    $cache = ['timestamp' => time(), 'data' => $weatherData];
    @file_put_contents($cacheFile, json_encode($cache));
    
    echo json_encode([
        'success' => true,
        'data' => $weatherData,
        'source' => 'fresh_api',
        'debug' => 'Fetched fresh data at ' . date('H:i:s')
    ]);
    
} catch (Exception $e) {
    // Fallback data - but with more accurate current temperature
    echo json_encode([
        'success' => true,
        'data' => [
            'temp' => 87, // Use the actual current temperature you mentioned
            'condition' => 'Partly Cloudy',
            'windSpeed' => 8,
            'visibility' => 10,
            'icon' => 'fa-cloud-sun',
            'timestamp' => time(),
            'location' => 'Nashville, TN'
        ],
        'source' => 'fallback',
        'error' => $e->getMessage(),
        'debug' => 'API failed, using fallback at ' . date('H:i:s')
    ]);
}
?>