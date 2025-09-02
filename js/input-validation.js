/**
 * Client-side Input Validation Helper
 * Provides real-time validation feedback for forms
 */

class FormValidator {
    constructor(formElement, options = {}) {
        this.form = formElement;
        this.options = {
            validateOnInput: true,
            showErrors: true,
            errorClass: 'error',
            successClass: 'success',
            ...options
        };
        
        this.validators = {
            email: this.validateEmail.bind(this),
            username: this.validateUsername.bind(this),
            name: this.validateName.bind(this),
            password: this.validatePassword.bind(this),
            text: this.validateText.bind(this),
            url: this.validateURL.bind(this)
        };
        
        this.init();
    }
    
    init() {
        if (this.options.validateOnInput) {
            this.attachInputListeners();
        }
        
        this.form.addEventListener('submit', this.handleSubmit.bind(this));
    }
    
    attachInputListeners() {
        const inputs = this.form.querySelectorAll('input, textarea, select');
        
        inputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => this.clearFieldError(input));
        });
    }
    
    handleSubmit(event) {
        const isValid = this.validateForm();
        
        if (!isValid) {
            event.preventDefault();
            this.showFormErrors();
        }
    }
    
    validateForm() {
        const inputs = this.form.querySelectorAll('input, textarea, select');
        let isValid = true;
        
        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });
        
        return isValid;
    }
    
    validateField(field) {
        const validationType = field.dataset.validate;
        const value = field.value.trim();
        
        // Check required fields
        if (field.hasAttribute('required') && !value) {
            this.showFieldError(field, 'This field is required');
            return false;
        }
        
        // Skip validation for empty optional fields
        if (!value && !field.hasAttribute('required')) {
            this.clearFieldError(field);
            return true;
        }
        
        // Run specific validation
        if (validationType && this.validators[validationType]) {
            const result = this.validators[validationType](value, field);
            
            if (!result.valid) {
                this.showFieldError(field, result.error);
                return false;
            }
        }
        
        this.showFieldSuccess(field);
        return true;
    }
    
    validateEmail(email) {
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        
        if (!emailRegex.test(email)) {
            return { valid: false, error: 'Please enter a valid email address' };
        }
        
        if (email.length > 254) {
            return { valid: false, error: 'Email address is too long' };
        }
        
        return { valid: true };
    }
    
    validateUsername(username) {
        if (username.length < 3) {
            return { valid: false, error: 'Username must be at least 3 characters long' };
        }
        
        if (username.length > 30) {
            return { valid: false, error: 'Username must be no more than 30 characters long' };
        }
        
        const usernameRegex = /^[a-zA-Z0-9_.-]+$/;
        if (!usernameRegex.test(username)) {
            return { valid: false, error: 'Username can only contain letters, numbers, periods, hyphens, and underscores' };
        }
        
        const reservedWords = [
            'admin', 'administrator', 'root', 'system', 'user', 'guest', 
            'anonymous', 'null', 'undefined', 'test', 'demo', 'example'
        ];
        
        if (reservedWords.includes(username.toLowerCase())) {
            return { valid: false, error: 'Username is reserved and cannot be used' };
        }
        
        return { valid: true };
    }
    
    validateName(name) {
        if (name.length < 2) {
            return { valid: false, error: 'Name must be at least 2 characters long' };
        }
        
        if (name.length > 50) {
            return { valid: false, error: 'Name must be no more than 50 characters long' };
        }
        
        const nameRegex = /^[a-zA-Z\s\'-]+$/;
        if (!nameRegex.test(name)) {
            return { valid: false, error: 'Name can only contain letters, spaces, hyphens, and apostrophes' };
        }
        
        return { valid: true };
    }
    
    validatePassword(password) {
        const requirements = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[^A-Za-z0-9]/.test(password)
        };
        
        const errors = [];
        
        if (!requirements.length) errors.push('at least 8 characters');
        if (!requirements.uppercase) errors.push('one uppercase letter');
        if (!requirements.lowercase) errors.push('one lowercase letter');
        if (!requirements.number) errors.push('one number');
        if (!requirements.special) errors.push('one special character');
        
        if (errors.length > 0) {
            return { 
                valid: false, 
                error: `Password must contain ${errors.join(', ')}` 
            };
        }
        
        return { valid: true };
    }
    
    validateText(text, field) {
        const minLength = parseInt(field.dataset.minLength) || 1;
        const maxLength = parseInt(field.dataset.maxLength) || 5000;
        
        if (text.length < minLength) {
            return { 
                valid: false, 
                error: `Text must be at least ${minLength} characters long` 
            };
        }
        
        if (text.length > maxLength) {
            return { 
                valid: false, 
                error: `Text must be no more than ${maxLength} characters long` 
            };
        }
        
        return { valid: true };
    }
    
    validateURL(url) {
        try {
            new URL(url);
        } catch {
            return { valid: false, error: 'Please enter a valid URL' };
        }
        
        const urlRegex = /^https?:\/\/[^\s<>"]{2,2048}$/;
        if (!urlRegex.test(url)) {
            return { valid: false, error: 'URL contains invalid characters' };
        }
        
        return { valid: true };
    }
    
    showFieldError(field, message) {
        this.clearFieldError(field);
        
        field.classList.add(this.options.errorClass);
        field.classList.remove(this.options.successClass);
        
        if (this.options.showErrors) {
            const errorElement = document.createElement('div');
            errorElement.className = 'validation-error';
            errorElement.textContent = message;
            errorElement.style.cssText = `
                color: #dc2626;
                font-size: 0.875rem;
                margin-top: 0.25rem;
                display: block;
            `;
            
            field.parentNode.appendChild(errorElement);
        }
    }
    
    showFieldSuccess(field) {
        this.clearFieldError(field);
        
        field.classList.remove(this.options.errorClass);
        field.classList.add(this.options.successClass);
    }
    
    clearFieldError(field) {
        field.classList.remove(this.options.errorClass, this.options.successClass);
        
        const errorElement = field.parentNode.querySelector('.validation-error');
        if (errorElement) {
            errorElement.remove();
        }
    }
    
    showFormErrors() {
        const firstErrorField = this.form.querySelector(`.${this.options.errorClass}`);
        if (firstErrorField) {
            firstErrorField.focus();
            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
}

// Password strength indicator
class PasswordStrengthIndicator {
    constructor(passwordField, options = {}) {
        this.passwordField = passwordField;
        this.options = {
            showIndicator: true,
            showRequirements: true,
            container: null,
            ...options
        };
        
        this.init();
    }
    
    init() {
        if (this.options.showIndicator) {
            this.createIndicator();
        }
        
        this.passwordField.addEventListener('input', () => this.updateStrength());
    }
    
    createIndicator() {
        const container = this.options.container || this.passwordField.parentNode;
        
        this.indicatorElement = document.createElement('div');
        this.indicatorElement.className = 'password-strength-indicator';
        this.indicatorElement.innerHTML = `
            <div class="strength-meter">
                <div class="strength-bar"></div>
            </div>
            <div class="strength-text"></div>
            ${this.options.showRequirements ? this.createRequirementsList() : ''}
        `;
        
        this.addIndicatorStyles();
        container.appendChild(this.indicatorElement);
    }
    
    createRequirementsList() {
        return `
            <ul class="password-requirements">
                <li data-requirement="length">At least 8 characters</li>
                <li data-requirement="uppercase">One uppercase letter</li>
                <li data-requirement="lowercase">One lowercase letter</li>
                <li data-requirement="number">One number</li>
                <li data-requirement="special">One special character</li>
            </ul>
        `;
    }
    
    addIndicatorStyles() {
        if (document.querySelector('#password-strength-styles')) return;
        
        const styles = document.createElement('style');
        styles.id = 'password-strength-styles';
        styles.textContent = `
            .password-strength-indicator {
                margin-top: 0.5rem;
                font-size: 0.875rem;
            }
            
            .strength-meter {
                height: 4px;
                background: #e5e7eb;
                border-radius: 2px;
                overflow: hidden;
                margin-bottom: 0.5rem;
            }
            
            .strength-bar {
                height: 100%;
                transition: width 0.3s ease, background-color 0.3s ease;
                width: 0%;
                background: #ef4444;
            }
            
            .strength-text {
                font-weight: 500;
                margin-bottom: 0.5rem;
            }
            
            .password-requirements {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            
            .password-requirements li {
                padding: 0.125rem 0;
                position: relative;
                padding-left: 1rem;
                color: #6b7280;
            }
            
            .password-requirements li::before {
                content: "✗";
                position: absolute;
                left: 0;
                color: #ef4444;
                font-weight: bold;
            }
            
            .password-requirements li.met::before {
                content: "✓";
                color: #22c55e;
            }
            
            .password-requirements li.met {
                color: #22c55e;
            }
        `;
        
        document.head.appendChild(styles);
    }
    
    updateStrength() {
        const password = this.passwordField.value;
        const strength = this.calculateStrength(password);
        
        this.updateIndicator(strength);
        this.updateRequirements(password);
    }
    
    calculateStrength(password) {
        let score = 0;
        const checks = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[^A-Za-z0-9]/.test(password)
        };
        
        Object.values(checks).forEach(check => {
            if (check) score += 20;
        });
        
        if (password.length >= 12) score += 10;
        if (password.length >= 16) score += 10;
        
        return {
            score: Math.min(score, 100),
            level: this.getStrengthLevel(score),
            checks
        };
    }
    
    getStrengthLevel(score) {
        if (score < 40) return 'weak';
        if (score < 60) return 'fair';
        if (score < 80) return 'good';
        if (score < 100) return 'strong';
        return 'excellent';
    }
    
    updateIndicator(strength) {
        const bar = this.indicatorElement.querySelector('.strength-bar');
        const text = this.indicatorElement.querySelector('.strength-text');
        
        const colors = {
            weak: '#ef4444',
            fair: '#f59e0b',
            good: '#3b82f6',
            strong: '#22c55e',
            excellent: '#16a34a'
        };
        
        bar.style.width = `${strength.score}%`;
        bar.style.backgroundColor = colors[strength.level];
        text.textContent = `Password strength: ${strength.level} (${strength.score}%)`;
        text.style.color = colors[strength.level];
    }
    
    updateRequirements(password) {
        if (!this.options.showRequirements) return;
        
        const requirements = this.indicatorElement.querySelectorAll('[data-requirement]');
        const checks = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[^A-Za-z0-9]/.test(password)
        };
        
        requirements.forEach(req => {
            const requirement = req.dataset.requirement;
            req.classList.toggle('met', checks[requirement]);
        });
    }
}

// Auto-initialize validators
document.addEventListener('DOMContentLoaded', () => {
    // Auto-initialize forms with data-validate attribute
    document.querySelectorAll('form[data-validate]').forEach(form => {
        new FormValidator(form);
    });
    
    // Auto-initialize password strength indicators
    document.querySelectorAll('input[type="password"][data-strength]').forEach(field => {
        new PasswordStrengthIndicator(field);
    });
});

// Export for manual initialization
window.FormValidator = FormValidator;
window.PasswordStrengthIndicator = PasswordStrengthIndicator;