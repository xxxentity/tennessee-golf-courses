// Centralized Weather System for Tennessee Golf Courses
// This ensures ALL pages show identical weather data

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

        // Fetch new weather data
        try {
            const response = await fetch('https://wttr.in/Nashville,TN?format=j1');
            
            if (!response.ok) {
                throw new Error('Weather API unavailable');
            }
            
            const data = await response.json();
            const current = data.current_condition[0];
            
            this.weatherData = {
                temp: current.temp_F,
                condition: current.weatherDesc[0].value,
                windSpeed: current.windspeedMiles,
                visibility: current.visibility,
                icon: this.getWeatherIcon(current.weatherDesc[0].value.toLowerCase()),
                timestamp: now
            };
            
            // Cache the data
            this.cacheWeather(this.weatherData);
            
            console.log('Weather updated with real API data:', this.weatherData);
            
        } catch (error) {
            console.log('Weather API error, using fallback:', error);
            
            // Use consistent fallback data
            this.weatherData = {
                temp: '72',
                condition: 'Partly Cloudy',
                windSpeed: '8',
                visibility: '10',
                icon: 'fa-cloud-sun',
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

    getWeatherIcon(condition) {
        const weatherIconMap = {
            'sunny': 'fa-sun',
            'clear': 'fa-sun',
            'partly cloudy': 'fa-cloud-sun',
            'cloudy': 'fa-cloud',
            'overcast': 'fa-cloud',
            'mist': 'fa-smog',
            'patchy rain possible': 'fa-cloud-rain',
            'patchy snow possible': 'fa-snowflake',
            'thundery outbreaks possible': 'fa-bolt',
            'fog': 'fa-smog',
            'light drizzle': 'fa-cloud-rain',
            'light rain': 'fa-cloud-rain',
            'moderate rain': 'fa-cloud-rain',
            'heavy rain': 'fa-cloud-rain',
            'light snow': 'fa-snowflake',
            'moderate snow': 'fa-snowflake',
            'heavy snow': 'fa-snowflake',
            'thunderstorms': 'fa-bolt',
            'rain': 'fa-cloud-rain',
            'snow': 'fa-snowflake'
        };
        
        return weatherIconMap[condition] || 'fa-cloud';
    }

    async updateWeatherDisplay() {
        const weather = await this.getWeather();
        
        const tempElement = document.getElementById('weather-temp');
        const windElement = document.getElementById('wind-speed');
        const visibilityElement = document.getElementById('visibility');
        const weatherIcon = document.querySelector('.weather-widget i');
        
        if (tempElement) {
            tempElement.textContent = `${weather.temp}°F - ${weather.condition}`;
        }
        if (windElement) {
            windElement.textContent = `${weather.windSpeed} mph`;
        }
        if (visibilityElement) {
            visibilityElement.textContent = `${weather.visibility} mi`;
        }
        if (weatherIcon) {
            weatherIcon.className = `fas ${weather.icon}`;
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