// Simplified Weather System for Tennessee Golf Courses
// Uses National Weather Service API

class WeatherManager {
    constructor() {
        this.weatherData = null;
        this.lastUpdate = 0;
        this.updateInterval = 10 * 60 * 1000; // 10 minutes
        this.storageKey = 'tgc_weather_data';
        this.storageTimeKey = 'tgc_weather_time';
    }

    async getWeather() {
        const now = Date.now();
        
        // Check if we have recent cached data
        const cachedData = this.getCachedWeather();
        if (cachedData && (now - cachedData.timestamp < this.updateInterval)) {
            this.weatherData = cachedData.data;
            return this.weatherData;
        }

        // Fetch new weather data from our API
        try {
            const response = await fetch('/weather-api.php');
            
            if (!response.ok) {
                throw new Error('Weather API unavailable');
            }
            
            const result = await response.json();
            
            if (!result.success) {
                throw new Error('Weather API returned error');
            }
            
            this.weatherData = {
                temp: result.data.temp,
                precipProb: result.data.precipProb,
                windSpeed: result.data.windSpeed,
                timestamp: now
            };
            
            // Cache the data
            this.cacheWeather(this.weatherData);
            
        } catch (error) {
            console.error('Weather API error:', error);
            
            // Use fallback data
            this.weatherData = {
                temp: 75,
                precipProb: 20,
                windSpeed: 10,
                timestamp: now
            };
            
            // Cache fallback data for shorter time
            this.cacheWeather(this.weatherData, 5 * 60 * 1000); // 5 minutes
        }
        
        return this.weatherData;
    }

    getCachedWeather() {
        try {
            const cachedData = localStorage.getItem(this.storageKey);
            const cachedTime = localStorage.getItem(this.storageTimeKey);
            
            if (cachedData && cachedTime) {
                return {
                    data: JSON.parse(cachedData),
                    timestamp: parseInt(cachedTime)
                };
            }
        } catch (error) {
            console.log('Error reading cached weather:', error);
        }
        return null;
    }

    cacheWeather(data, customDuration = null) {
        try {
            const timestamp = customDuration ? Date.now() - this.updateInterval + customDuration : data.timestamp;
            localStorage.setItem(this.storageKey, JSON.stringify(data));
            localStorage.setItem(this.storageTimeKey, timestamp.toString());
        } catch (error) {
            console.log('Error caching weather:', error);
        }
    }

    async updateWeatherDisplay() {
        // Check if we're on a page with weather elements
        const tempElement = document.getElementById('weather-temp');
        const precipElement = document.getElementById('weather-precip');
        const windElement = document.getElementById('weather-wind');
        
        if (!tempElement) {
            console.log('Weather elements not found on this page');
            return;
        }
        
        const weather = await this.getWeather();
        
        if (tempElement) {
            tempElement.textContent = `${weather.temp}°F`;
        }
        if (precipElement) {
            precipElement.textContent = `${weather.precipProb}%`;
        }
        if (windElement) {
            windElement.textContent = `${weather.windSpeed} mph`;
        }
    }
}

// Create global weather manager instance
window.weatherManager = new WeatherManager();

// Initialize weather when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Update weather immediately
    window.weatherManager.updateWeatherDisplay();
    
    // Update weather every 10 minutes
    setInterval(() => {
        window.weatherManager.updateWeatherDisplay();
    }, 10 * 60 * 1000);
});