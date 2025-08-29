// Simple Course-Specific Weather System
// This file is only loaded on individual course pages
// It gets the weather data from a specific API endpoint

class CourseWeatherManager {
    constructor(courseSlug) {
        this.courseSlug = courseSlug;
        this.weatherData = null;
        this.lastUpdate = 0;
        this.updateInterval = 10 * 60 * 1000; // 10 minutes
        this.apiUrl = `/course-weather-api.php?course=${courseSlug}`;
        // Use course-specific cache keys to avoid conflict with Nashville weather
        this.storageKey = `course_weather_data_${courseSlug}`;
        this.storageTimeKey = `course_weather_time_${courseSlug}`;
    }

    async getWeather() {
        const now = Date.now();
        
        // Check if we have recent cached data
        const cachedData = this.getCachedWeather();
        if (cachedData && (now - cachedData.timestamp < this.updateInterval)) {
            this.weatherData = cachedData.data;
            return this.weatherData;
        }

        try {
            console.log('DEBUG COURSE: Fetching weather for course:', this.courseSlug);
            console.log('DEBUG COURSE: API URL:', this.apiUrl);
            
            const response = await fetch(this.apiUrl);
            
            if (!response.ok) {
                throw new Error(`API responded with ${response.status}`);
            }
            
            const result = await response.json();
            console.log('DEBUG COURSE: API response:', result);
            
            if (!result.success) {
                throw new Error(result.error || 'API returned error');
            }
            
            this.weatherData = {
                temp: result.temperature || 75,
                precipProb: result.precipProb || 20,
                windSpeed: result.windSpeed || 10,
                location: result.location || 'Location, TN',
                timestamp: now
            };
            
            this.lastUpdate = now;
            console.log('DEBUG COURSE: Final weather data:', this.weatherData);
            
            // Cache the data
            this.cacheWeather(this.weatherData);
            
        } catch (error) {
            console.error('Course weather API error:', error);
            
            // Use fallback data for this course
            this.weatherData = {
                temp: 75,
                precipProb: 20,
                windSpeed: 10,
                location: 'Course Location, TN',
                timestamp: now
            };
            this.lastUpdate = now;
            
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
            console.log('Error reading cached course weather:', error);
        }
        return null;
    }

    cacheWeather(data, customDuration = null) {
        try {
            const timestamp = customDuration ? Date.now() - this.updateInterval + customDuration : data.timestamp;
            localStorage.setItem(this.storageKey, JSON.stringify(data));
            localStorage.setItem(this.storageTimeKey, timestamp.toString());
        } catch (error) {
            console.log('Error caching course weather:', error);
        }
    }

    async updateWeatherDisplay() {
        const tempElement = document.getElementById('weather-temp');
        const precipElement = document.getElementById('weather-precip');
        const windElement = document.getElementById('weather-wind');
        const locationElement = document.getElementById('weather-location');
        
        if (!tempElement) {
            console.log('Weather elements not found on this page');
            return;
        }
        
        const weather = await this.getWeather();
        console.log('DEBUG COURSE: Updating display with:', weather);
        
        if (tempElement) {
            tempElement.textContent = `${weather.temp}°F`;
        }
        if (precipElement) {
            precipElement.textContent = `${weather.precipProb}%`;
        }
        if (windElement) {
            windElement.textContent = `${weather.windSpeed} mph`;
        }
        if (locationElement && weather.location) {
            locationElement.textContent = `${weather.location}:`;
        }
    }
}

// This will be initialized by the navigation.php when needed
window.initializeCourseWeather = function(courseSlug) {
    console.log('DEBUG COURSE: Initializing course weather for:', courseSlug);
    window.courseWeatherManager = new CourseWeatherManager(courseSlug);
    
    // Update weather immediately
    window.courseWeatherManager.updateWeatherDisplay();
    
    // Update weather every 10 minutes
    setInterval(() => {
        window.courseWeatherManager.updateWeatherDisplay();
    }, 10 * 60 * 1000);
};