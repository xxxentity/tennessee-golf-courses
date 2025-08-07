<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate, max-age=0');

// Enable CORS for our domain
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Cache file path
$cacheFile = __DIR__ . '/../cache/weather_cache.json';
$cacheTime = 10 * 60; // 10 minutes in seconds

// Create cache directory if it doesn't exist
$cacheDir = dirname($cacheFile);
if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0755, true);
}

// Function to get cached weather data
function getCachedWeather($cacheFile, $cacheTime) {
    if (file_exists($cacheFile)) {
        $cached = json_decode(file_get_contents($cacheFile), true);
        if ($cached && (time() - $cached['timestamp'] < $cacheTime)) {
            return $cached['data'];
        }
    }
    return false;
}

// Function to save weather data to cache
function saveWeatherCache($cacheFile, $data) {
    $cache = [
        'timestamp' => time(),
        'data' => $data
    ];
    file_put_contents($cacheFile, json_encode($cache));
}

// Function to map weather conditions to icons
function getWeatherIcon($condition) {
    $condition = strtolower($condition);
    $weatherIconMap = [
        'sunny' => 'fa-sun',
        'clear' => 'fa-sun',
        'partly cloudy' => 'fa-cloud-sun',
        'cloudy' => 'fa-cloud',
        'overcast' => 'fa-cloud',
        'mist' => 'fa-smog',
        'patchy rain possible' => 'fa-cloud-rain',
        'patchy snow possible' => 'fa-snowflake',
        'thundery outbreaks possible' => 'fa-bolt',
        'fog' => 'fa-smog',
        'light drizzle' => 'fa-cloud-rain',
        'light rain' => 'fa-cloud-rain',
        'moderate rain' => 'fa-cloud-rain',
        'heavy rain' => 'fa-cloud-rain',
        'light snow' => 'fa-snowflake',
        'moderate snow' => 'fa-snowflake',
        'heavy snow' => 'fa-snowflake',
        'thunderstorms' => 'fa-bolt',
        'rain' => 'fa-cloud-rain',
        'snow' => 'fa-snowflake'
    ];
    
    // Check for partial matches
    foreach ($weatherIconMap as $key => $icon) {
        if (strpos($condition, $key) !== false) {
            return $icon;
        }
    }
    
    return 'fa-cloud'; // Default icon
}

try {
    // Check for cached data first
    $cachedWeather = getCachedWeather($cacheFile, $cacheTime);
    if ($cachedWeather) {
        echo json_encode([
            'success' => true,
            'data' => $cachedWeather,
            'source' => 'cache'
        ]);
        exit;
    }

    // Fetch fresh weather data
    $context = stream_context_create([
        'http' => [
            'timeout' => 10,
            'user_agent' => 'Tennessee Golf Courses Weather Bot'
        ]
    ]);
    
    $apiUrl = 'https://wttr.in/Nashville,TN?format=j1';
    $weatherJson = file_get_contents($apiUrl, false, $context);
    
    if ($weatherJson === false) {
        throw new Exception('Failed to fetch weather data');
    }
    
    $weatherData = json_decode($weatherJson, true);
    if (!$weatherData || !isset($weatherData['current_condition'][0])) {
        throw new Exception('Invalid weather data format');
    }
    
    $current = $weatherData['current_condition'][0];
    
    $processedWeather = [
        'temp' => (int)$current['temp_F'],
        'condition' => $current['weatherDesc'][0]['value'],
        'windSpeed' => (int)$current['windspeedMiles'],
        'visibility' => (int)$current['visibility'],
        'icon' => getWeatherIcon($current['weatherDesc'][0]['value']),
        'timestamp' => time(),
        'location' => 'Nashville, TN'
    ];
    
    // Save to cache
    saveWeatherCache($cacheFile, $processedWeather);
    
    echo json_encode([
        'success' => true,
        'data' => $processedWeather,
        'source' => 'api'
    ]);
    
} catch (Exception $e) {
    // Return fallback weather data with current time
    $fallbackWeather = [
        'temp' => 75, // More reasonable default
        'condition' => 'Partly Cloudy',
        'windSpeed' => 8,
        'visibility' => 10,
        'icon' => 'fa-cloud-sun',
        'timestamp' => time(),
        'location' => 'Nashville, TN'
    ];
    
    echo json_encode([
        'success' => true,
        'data' => $fallbackWeather,
        'source' => 'fallback',
        'error' => $e->getMessage()
    ]);
}
?>