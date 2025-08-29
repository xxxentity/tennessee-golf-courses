<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate, max-age=0');

// Enable CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Simple file-based caching
$cacheFile = __DIR__ . '/weather_cache.json';
$cacheTime = 10 * 60; // 10 minutes

try {
    // Check cache first
    if (file_exists($cacheFile)) {
        $cached = json_decode(file_get_contents($cacheFile), true);
        if ($cached && (time() - $cached['timestamp'] < $cacheTime)) {
            echo json_encode([
                'success' => true,
                'data' => $cached['data'],
                'source' => 'cache'
            ]);
            exit;
        }
    }
    
    // Nashville coordinates and NWS grid point
    // Nashville: 36.1627, -86.7816
    // Grid: OHX office, point 50,57
    
    // Get current observations from Nashville International Airport station
    $stationUrl = 'https://api.weather.gov/stations/KBNA/observations/latest';
    
    $context = stream_context_create([
        'http' => [
            'timeout' => 15,
            'user_agent' => 'TennesseeGolfCourses/1.0 (contact@tennesseegolfcourses.com)',
            'method' => 'GET'
        ]
    ]);
    
    $observationJson = @file_get_contents($stationUrl, false, $context);
    
    if ($observationJson === false) {
        throw new Exception('Failed to fetch current observations');
    }
    
    $observation = json_decode($observationJson, true);
    if (!$observation || !isset($observation['properties'])) {
        throw new Exception('Invalid observation data');
    }
    
    $props = $observation['properties'];
    
    // Get forecast for precipitation probability
    $forecastUrl = 'https://api.weather.gov/gridpoints/OHX/50,57/forecast/hourly';
    $forecastJson = @file_get_contents($forecastUrl, false, $context);
    
    $precipProb = 0;
    if ($forecastJson !== false) {
        $forecast = json_decode($forecastJson, true);
        if ($forecast && isset($forecast['properties']['periods'][0])) {
            // Get precipitation probability for current hour
            $precipProb = $forecast['properties']['periods'][0]['probabilityOfPrecipitation']['value'] ?? 0;
        }
    }
    
    // Convert temperature from Celsius to Fahrenheit
    $tempC = $props['temperature']['value'];
    $tempF = round(($tempC * 9/5) + 32);
    
    // Convert wind speed from m/s to mph
    $windMs = $props['windSpeed']['value'] ?? 0;
    $windMph = round($windMs * 2.237);
    
    $weatherData = [
        'temp' => $tempF,
        'precipProb' => $precipProb,
        'windSpeed' => $windMph,
        'timestamp' => time(),
        'location' => 'Nashville, TN'
    ];
    
    // Save to cache
    $cache = ['timestamp' => time(), 'data' => $weatherData];
    @file_put_contents($cacheFile, json_encode($cache));
    
    echo json_encode([
        'success' => true,
        'data' => $weatherData,
        'source' => 'nws_api'
    ]);
    
} catch (Exception $e) {
    // Fallback data
    echo json_encode([
        'success' => true,
        'data' => [
            'temp' => 75,
            'precipProb' => 20,
            'windSpeed' => 10,
            'timestamp' => time(),
            'location' => 'Nashville, TN'
        ],
        'source' => 'fallback',
        'error' => $e->getMessage()
    ]);
}
?>