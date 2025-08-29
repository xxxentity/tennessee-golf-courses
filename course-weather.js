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
    }

    async getWeather() {
        const now = Date.now();
        
        // Check if we have recent cached data
        if (this.weatherData && (now - this.lastUpdate < this.updateInterval)) {
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
        }
        
        return this.weatherData;
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