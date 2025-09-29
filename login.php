<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NRB System - Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            position: relative;
        }

        .login-header {
            background: linear-gradient(135deg, #2c5aa0 0%, #1e4080 100%);
            color: white;
            padding: 40px 30px 30px;
            text-align: center;
            position: relative;
        }

        .login-header::before {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 20px solid transparent;
            border-right: 20px solid transparent;
            border-top: 20px solid #1e4080;
        }

        .logo {
            font-size: 3rem;
            margin-bottom: 10px;
            color: white;
        }

        .login-title {
            font-size: 1.8rem;
            font-weight: 300;
            margin-bottom: 5px;
        }

        .login-subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .login-form {
            padding: 40px 30px 30px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e1e8ed;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9ff;
        }

        .form-input:focus {
            outline: none;
            border-color: #2c5aa0;
            background: white;
            box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
        }

        .form-input:valid {
            border-color: #28a745;
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 38px;
            color: #999;
            font-size: 1rem;
            pointer-events: none;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 38px;
            color: #999;
            cursor: pointer;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #2c5aa0;
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #2c5aa0 0%, #1e4080 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(44, 90, 160, 0.3);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .login-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .forgot-password {
            text-align: center;
            margin-bottom: 25px;
        }

        .forgot-password a {
            color: #2c5aa0;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: #1e4080;
            text-decoration: underline;
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
            color: #999;
            font-size: 0.9rem;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e1e8ed;
        }

        .divider span {
            background: white;
            padding: 0 20px;
            position: relative;
        }

        .register-btn {
            width: 100%;
            padding: 15px;
            background: transparent;
            color: #2c5aa0;
            border: 2px solid #2c5aa0;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .register-btn:hover {
            background: #2c5aa0;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(44, 90, 160, 0.2);
        }

        .error-message {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 8px;
            display: none;
            padding: 10px;
            background: #f8d7da;
            border-radius: 5px;
            border-left: 4px solid #dc3545;
        }

        .success-message {
            color: #155724;
            font-size: 0.85rem;
            margin-top: 8px;
            display: none;
            padding: 10px;
            background: #d4edda;
            border-radius: 5px;
            border-left: 4px solid #28a745;
        }

        .form-footer {
            text-align: center;
            padding: 20px 30px;
            background: #f8f9ff;
            color: #666;
            font-size: 0.8rem;
        }

        .form-footer a {
            color: #2c5aa0;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .login-container {
                margin: 10px;
            }

            .login-header {
                padding: 30px 20px 25px;
            }

            .login-form {
                padding: 30px 20px 25px;
            }

            .login-title {
                font-size: 1.5rem;
            }

            .logo {
                font-size: 2.5rem;
            }
        }

        /* Animation for form appearance */
        .login-container {
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Input focus animation */
        .form-group {
            position: relative;
            overflow: hidden;
        }

        .form-input:focus + .input-icon {
            color: #2c5aa0;
        }

        /* Custom checkbox for remember me */
        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            font-size: 0.9rem;
            color: #666;
        }

        .remember-me input[type="checkbox"] {
            margin-right: 8px;
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Header Section -->
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-id-card"></i>
            </div>
            <h1 class="login-title">Welcome Back</h1>
            <p class="login-subtitle">Sign in to your NRB account</p>
        </div>

        <!-- Login Form -->
        <div class="login-form">
            <form id="loginForm" action="process_login.php" method="POST">
                <!-- Username/Email Field -->
                <div class="form-group">
                    <label for="username">Username or Email</label>
                    <input type="text" id="username" name="username" class="form-input" required>
                    <i class="fas fa-user input-icon"></i>
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-input" required>
                    <i class="fas fa-eye password-toggle" id="passwordToggle"></i>
                </div>

                <!-- Remember Me Checkbox -->
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>

                <!-- Error/Success Messages -->
                <div class="error-message" id="errorMessage"></div>
                <div class="success-message" id="successMessage"></div>

                <!-- Login Button -->
                <button type="submit" class="login-btn" id="loginBtn">
                    <div class="loading-spinner" id="loadingSpinner"></div>
                    <span id="loginText">Sign In</span>
                </button>

                <!-- Forgot Password Link -->
                <div class="forgot-password">
                    <a href="forgot_password.php">Forgot your password?</a>
                </div>

                <!-- Divider -->
                <div class="divider">
                    <span>or</span>
                </div>

                <!-- Register Button -->
                <a href="register.php" class="register-btn">
                    <i class="fas fa-user-plus"></i> Create New Account
                </a>
            </form>
        </div>

        <!-- Footer -->
        <div class="form-footer">
            <p>&copy; 2025 National Registration Bureau. All rights reserved.</p>
            <p><a href="privacy.php">Privacy Policy</a> | <a href="terms.php">Terms of Service</a></p>
        </div>
    </div>

    <script>
        class LoginManager {
            constructor() {
                this.form = document.getElementById('loginForm');
                this.passwordToggle = document.getElementById('passwordToggle');
                this.passwordField = document.getElementById('password');
                this.loginBtn = document.getElementById('loginBtn');
                this.loadingSpinner = document.getElementById('loadingSpinner');
                this.loginText = document.getElementById('loginText');
                this.errorMessage = document.getElementById('errorMessage');
                this.successMessage = document.getElementById('successMessage');
                
                this.init();
            }
            
            init() {
                this.bindEvents();
                this.setupValidation();
            }
            
            bindEvents() {
                // Password toggle functionality
                this.passwordToggle.addEventListener('click', () => {
                    this.togglePassword();
                });
                
                // Form submission
                this.form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.handleLogin();
                });
                
                // Input validation on blur
                const inputs = this.form.querySelectorAll('.form-input');
                inputs.forEach(input => {
                    input.addEventListener('blur', () => {
                        this.validateField(input);
                    });
                    
                    input.addEventListener('input', () => {
                        this.clearMessages();
                    });
                });
            }
            
            togglePassword() {
                const type = this.passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                this.passwordField.setAttribute('type', type);
                
                // Toggle icon
                this.passwordToggle.classList.toggle('fa-eye');
                this.passwordToggle.classList.toggle('fa-eye-slash');
            }
            
            validateField(field) {
                const value = field.value.trim();
                
                if (field.id === 'username') {
                    if (!value) {
                        this.showFieldError(field, 'Username or email is required');
                        return false;
                    } else if (value.includes('@') && !this.isValidEmail(value)) {
                        this.showFieldError(field, 'Please enter a valid email address');
                        return false;
                    } else {
                        this.clearFieldError(field);
                        return true;
                    }
                }
                
                if (field.id === 'password') {
                    if (!value) {
                        this.showFieldError(field, 'Password is required');
                        return false;
                    } else if (value.length < 6) {
                        this.showFieldError(field, 'Password must be at least 6 characters');
                        return false;
                    } else {
                        this.clearFieldError(field);
                        return true;
                    }
                }
                
                return true;
            }
            
            isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }
            
            showFieldError(field, message) {
                field.style.borderColor = '#dc3545';
                field.style.backgroundColor = '#fff5f5';
                
                // Remove existing error message
                const existingError = field.parentNode.querySelector('.field-error');
                if (existingError) {
                    existingError.remove();
                }
                
                // Add new error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'field-error';
                errorDiv.style.color = '#dc3545';
                errorDiv.style.fontSize = '0.8rem';
                errorDiv.style.marginTop = '5px';
                errorDiv.textContent = message;
                field.parentNode.appendChild(errorDiv);
            }
            
            clearFieldError(field) {
                field.style.borderColor = '#28a745';
                field.style.backgroundColor = '#f0fff4';
                
                const errorDiv = field.parentNode.querySelector('.field-error');
                if (errorDiv) {
                    errorDiv.remove();
                }
            }
            
            setupValidation() {
                const inputs = this.form.querySelectorAll('.form-input');
                inputs.forEach(input => {
                    input.addEventListener('input', () => {
                        if (input.value.trim()) {
                            input.style.borderColor = '#e1e8ed';
                            input.style.backgroundColor = 'white';
                        }
                    });
                });
            }
            
            async handleLogin() {
                // Clear previous messages
                this.clearMessages();
                
                // Validate all fields
                const usernameValid = this.validateField(document.getElementById('username'));
                const passwordValid = this.validateField(document.getElementById('password'));
                
                if (!usernameValid || !passwordValid) {
                    this.showError('Please fix the errors above');
                    return;
                }
                
                // Show loading state
                this.setLoadingState(true);
                
                // Get form data
                const formData = new FormData(this.form);
                
                try {
                    // Simulate API call (replace with actual login endpoint)
                    const response = await fetch('process_login.php', {
                        method: 'POST',
                        body: formData
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        this.showSuccess('Login successful! Redirecting...');
                        setTimeout(() => {
                            window.location.href = result.redirect || 'dashboard.php';
                        }, 1500);
                    } else {
                        this.showError(result.message || 'Invalid username or password');
                    }
                } catch (error) {
                    console.error('Login error:', error);
                    this.showError('Connection error. Please try again.');
                } finally {
                    this.setLoadingState(false);
                }
            }
            
            setLoadingState(isLoading) {
                if (isLoading) {
                    this.loginBtn.disabled = true;
                    this.loadingSpinner.style.display = 'inline-block';
                    this.loginText.textContent = 'Signing In...';
                } else {
                    this.loginBtn.disabled = false;
                    this.loadingSpinner.style.display = 'none';
                    this.loginText.textContent = 'Sign In';
                }
            }
            
            showError(message) {
                this.errorMessage.textContent = message;
                this.errorMessage.style.display = 'block';
                this.successMessage.style.display = 'none';
            }
            
            showSuccess(message) {
                this.successMessage.textContent = message;
                this.successMessage.style.display = 'block';
                this.errorMessage.style.display = 'none';
            }
            
            clearMessages() {
                this.errorMessage.style.display = 'none';
                this.successMessage.style.display = 'none';
            }
        }
        
        // Initialize login manager when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            new LoginManager();
        });
        
        // Add some visual enhancements
        document.addEventListener('DOMContentLoaded', function() {
            // Add ripple effect to buttons
            const buttons = document.querySelectorAll('.login-btn, .register-btn');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        width: ${size}px;
                        height: ${size}px;
                        left: ${x}px;
                        top: ${y}px;
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(255, 255, 255, 0.3);
                        transform: scale(0);
                        animation: ripple 0.6s ease-out;
                        pointer-events: none;
                    `;
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
            
            // Add CSS for ripple animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(2);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        });
    </script>
</body>
</html>