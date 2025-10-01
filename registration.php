<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NRB System - Create Account</title>
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

        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            display: flex;
            min-height: 600px;
        }

        .left-section {
            flex: 1;
            background: linear-gradient(rgba(25, 11, 2, 0.96), rgba(25, 11, 2, 0.96)), url('images/bg2.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .left-section img {
            width: 150px;
            margin-bottom: 30px;
        }

        .left-section h1 {
            font-size: 1.2rem;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .left-section h2 {
            font-size: 2.5rem;
            color: #F7CE5F;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .left-section p {
            font-size: 1rem;
            font-weight: 600;
        }

        .right-section {
            flex: 1.2;
            padding: 50px 40px;
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-header h3 {
            color: #190B02;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .form-header hr {
            width: 80px;
            height: 4px;
            background: #2c5aa0;
            border: none;
            margin: 0 auto;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            background: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
        }

        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .form-group {
            flex: 1;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 0.9rem;
            color: #333;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9ff;
        }

        .form-control:focus {
            outline: none;
            border-color: #2c5aa0;
            background: white;
            box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
        }

        .form-control:valid {
            border-color: #28a745;
        }

        .password-toggle {
            position: relative;
        }

        .password-toggle input {
            padding-right: 45px;
        }

        .password-toggle .toggle-icon {
            position: absolute;
            right: 15px;
            top: 38px;
            cursor: pointer;
            color: #999;
            font-size: 1rem;
        }

        .password-toggle .toggle-icon:hover {
            color: #2c5aa0;
        }

        .submit-btn {
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
            margin-top: 10px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(44, 90, 160, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .login-link a {
            color: #2c5aa0;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .password-strength {
            margin-top: 8px;
            font-size: 0.85rem;
        }

        .strength-bar {
            height: 4px;
            background: #e1e8ed;
            border-radius: 2px;
            margin-top: 5px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
        }

        .strength-weak { width: 33%; background: #dc3545; }
        .strength-medium { width: 66%; background: #ffc107; }
        .strength-strong { width: 100%; background: #28a745; }

        @media (max-width: 768px) {
            .register-container {
                flex-direction: column;
            }

            .left-section {
                padding: 30px 20px;
            }

            .left-section h2 {
                font-size: 2rem;
            }

            .right-section {
                padding: 30px 20px;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
            }

            .form-header h3 {
                font-size: 1.5rem;
            }
        }

        .animation-slide-in {
            animation: slideIn 0.6s ease-out;
        }

        @keyframes slideIn {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="register-container animation-slide-in">
        <!-- Left Section -->
        <div class="left-section">
            <img src="images/emblem.png" alt="NRB Logo">
            <h1>NATIONAL REGISTRATION BUREAU</h1>
            <h2>IDENTITY CARD</h2>
            <p>APPLICATION MANAGEMENT SYSTEM</p>
        </div>

        <!-- Right Section -->
        <div class="right-section">
            <div class="form-header">
                <h3>CREATE USER ACCOUNT</h3>
                <hr>
            </div>

            <?php
            if (isset($_SESSION['status'])) {
                echo "<div class='alert'><i class='fas fa-check-circle'></i>".$_SESSION['status']."</div>";
                unset($_SESSION['status']);
            }
            ?>

            <form name="tilowe" method="post" action="controllers/user_reg.php" id="registerForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" class="form-control" name="firstname" id="firstname" placeholder="First Name" required>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Last Name" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email Address" required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select class="form-control" name="gender" id="gender" required>
                            <option value="" disabled selected>Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group password-toggle">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                        <i class="fas fa-eye toggle-icon" id="togglePassword"></i>
                        <div class="password-strength" id="passwordStrength" style="display: none;">
                            <span id="strengthText">Password strength: </span>
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthBar"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group password-toggle">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
                        <i class="fas fa-eye toggle-icon" id="toggleConfirmPassword"></i>
                    </div>
                </div>

                <button type="submit" name="btn_submit" class="submit-btn">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>

                <div class="login-link">
                    <p>Already have an account? <a href="login.php">Log In</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Password visibility toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPasswordField = document.getElementById('confirm_password');

        togglePassword.addEventListener('click', function() {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        toggleConfirmPassword.addEventListener('click', function() {
            const type = confirmPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordField.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // Password strength indicator
        passwordField.addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            const strengthDiv = document.getElementById('passwordStrength');

            if (password.length === 0) {
                strengthDiv.style.display = 'none';
                return;
            }

            strengthDiv.style.display = 'block';

            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            strengthBar.className = 'strength-fill';
            
            if (strength <= 2) {
                strengthBar.classList.add('strength-weak');
                strengthText.textContent = 'Password strength: Weak';
            } else if (strength === 3) {
                strengthBar.classList.add('strength-medium');
                strengthText.textContent = 'Password strength: Medium';
            } else {
                strengthBar.classList.add('strength-strong');
                strengthText.textContent = 'Password strength: Strong';
            }
        });

        // Form validation
        const form = document.getElementById('registerForm');
        
        form.addEventListener('submit', function(e) {
            const password = passwordField.value;
            const confirmPassword = confirmPasswordField.value;

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                confirmPasswordField.focus();
                return false;
            }

            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long!');
                passwordField.focus();
                return false;
            }
        });

        // Input validation feedback
        const inputs = form.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value.trim() === '' && this.hasAttribute('required')) {
                    this.style.borderColor = '#dc3545';
                } else if (this.value.trim() !== '') {
                    this.style.borderColor = '#28a745';
                }
            });

            input.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    this.style.borderColor = '#2c5aa0';
                }
            });
        });
    </script>
</body>
</html>