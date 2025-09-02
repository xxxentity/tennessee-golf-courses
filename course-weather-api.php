<?php
/**
 * Course-Specific Weather API
 * Fetches weather data for individual golf courses using their coordinates
 */

require_once 'includes/course-weather-data.php';

header('Content-Type: application/json');
header('Cache-Control: max-age=600'); // Cache for 10 minutes

// Get the course slug from the query parameter
$courseSlug = $_GET['course'] ?? '';

if (empty($courseSlug)) {
    http_response_code(400);
    echo json_encode(['error' => 'Course parameter is required']);
    exit;
}

// Get course data
$courseData = getCourseWeatherData($courseSlug);

if (!$courseData) {
    http_response_code(404);
    echo json_encode(['error' => 'Course not found']);
    exit;
}

$coordinates = $courseData['coordinates'];
$longitude = $coordinates[0];
$latitude = $coordinates[1];
$city = $courseData['city'];
$state = $courseData['state'];

try {
    // Step 1: Get the weather station and grid information using the coordinates
    $pointUrl = "https://api.weather.gov/points/{$latitude},{$longitude}";
    $pointContext = stream_context_create([
        'http' => [
            'timeout' => 10,
            'user_agent' => 'TennesseeGolfCourses.com Weather Service'
        ]
    ]);
    
    $pointResponse = @file_get_contents($pointUrl, false, $pointContext);
    
    if ($pointResponse === false) {
        throw new Exception('Failed to get point data');
    }
    
    $pointData = json_decode($pointResponse, true);
    
    if (!$pointData || !isset($pointData['properties'])) {
        throw new Exception('Invalid point data response');
    }
    
    // Extract forecast URLs
    $properties = $pointData['properties'];
    $forecastUrl = $properties['forecast'] ?? null;
    $forecastHourlyUrl = $properties['forecastHourly'] ?? null;
    $gridId = $properties['gridId'] ?? null;
    $gridX = $properties['gridX'] ?? null;
    $gridY = $properties['gridY'] ?? null;
    
    if (!$forecastUrl || !$forecastHourlyUrl) {
        throw new Exception('Forecast URLs not available');
    }
    
    // Step 2: Get current observations from nearest station
    $stationUrl = $properties['forecastOffice'] ?? null;
    $temperature = null;
    $windSpeed = null;
    
    // Try to get observations
    if ($stationUrl) {
        $obsUrl = "https://api.weather.gov/gridpoints/{$gridId}/{$gridX},{$gridY}/stations";
        $stationsResponse = @file_get_contents($obsUrl, false, $pointContext);
        
        if ($stationsResponse) {
            $stationsData = json_decode($stationsResponse, true);
            if (isset($stationsData['features'][0]['properties']['stationIdentifier'])) {
                $stationId = $stationsData['features'][0]['properties']['stationIdentifier'];
                $currentObsUrl = "https://api.weather.gov/stations/{$stationId}/observations/latest";
                
                $obsResponse = @file_get_contents($currentObsUrl, false, $pointContext);
                if ($obsResponse) {
                    $obsData = json_decode($obsResponse, true);
                    if (isset($obsData['properties'])) {
                        $tempC = $obsData['properties']['temperature']['value'] ?? null;
                        $windMps = $obsData['properties']['windSpeed']['value'] ?? null;
                        
                        if ($tempC !== null) {
                            $temperature = round($tempC * 9/5 + 32); // Convert to Fahrenheit
                        }
                        
                        if ($windMps !== null) {
                            $windSpeed = round($windMps * 2.237); // Convert to MPH
                        }
                    }
                }
            }
        }
    }
    
    // Step 3: Get precipitation probability from hourly forecast
    $precipProb = null;
    $hourlyResponse = @file_get_contents($forecastHourlyUrl, false, $pointContext);
    
    if ($hourlyResponse) {
        $hourlyData = json_decode($hourlyResponse, true);
        if (isset($hourlyData['properties']['periods'][0])) {
            $currentHour = $hourlyData['properties']['periods'][0];
            $precipProb = $currentHour['probabilityOfPrecipitation']['value'] ?? null;
        }
    }
    
    // Prepare response data
    $weatherData = [
        'success' => true,
        'location' => "{$city}, {$state}",
        'courseName' => $courseData['name'],
        'temperature' => $temperature,
        'precipProb' => $precipProb,
        'windSpeed' => $windSpeed,
        'timestamp' => time(),
        'coordinates' => $coordinates
    ];
    
    echo json_encode($weatherData);
    
} catch (Exception $e) {
    // Fallback response
    $fallbackData = [
        'success' => false,
        'location' => "{$city}, {$state}",
        'courseName' => $courseData['name'],
        'temperature' => null,
        'precipProb' => null,
        'windSpeed' => null,
        'error' => $e->getMessage(),
        'timestamp' => time()
    ];
    
    echo json_encode($fallbackData);
}
?>