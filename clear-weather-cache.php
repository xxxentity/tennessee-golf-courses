<?php
// Clear weather cache to force fresh data

$cacheFile = __DIR__ . '/weather_cache.json';

if (file_exists($cacheFile)) {
    if (unlink($cacheFile)) {
        echo "✅ Weather cache cleared successfully!<br>";
    } else {
        echo "❌ Failed to clear weather cache<br>";
    }
} else {
    echo "ℹ️ No cache file found<br>";
}

// Now test the API with fresh data
echo "<h2>Testing fresh API call:</h2>";
echo "<a href='/weather-api.php'>Click here to test weather-api.php</a><br><br>";

// Or test directly
$apiUrl = 'http' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/weather-api.php';
$result = file_get_contents($apiUrl);
echo "<h3>Fresh API Response:</h3>";
echo "<pre>";
echo htmlspecialchars($result);
echo "</pre>";

// Check if it has the new structure
$data = json_decode($result, true);
if ($data && isset($data['data'])) {
    echo "<h3>Data Structure Check:</h3>";
    if (isset($data['data']['precipProb'])) {
        echo "✅ NEW structure detected (has precipProb)<br>";
    } else {
        echo "❌ OLD structure detected (missing precipProb)<br>";
    }
    
    echo "<p>Keys in data: " . implode(', ', array_keys($data['data'])) . "</p>";
}
?>