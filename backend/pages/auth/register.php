<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Register | MoToR Luxury System</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
            position: relative;
            overflow-x: hidden;
            padding: 40px 20px;
        }
        
        /* Animated Background */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
        }
        
        .bg-animation .circle {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(255, 45, 142, 0.3), rgba(124, 58, 237, 0.3));
            filter: blur(60px);
            animation: float 20s infinite ease-in-out;
        }
        
        .circle-1 {
            width: 400px;
            height: 400px;
            top: -100px;
            left: -100px;
            animation-delay: 0s;
        }
        
        .circle-2 {
            width: 500px;
            height: 500px;
            bottom: -150px;
            right: -150px;
            animation-delay: 5s;
            background: linear-gradient(135deg, rgba(0, 229, 255, 0.3), rgba(124, 58, 237, 0.3));
        }
        
        .circle-3 {
            width: 300px;
            height: 300px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: 10s;
            background: linear-gradient(135deg, rgba(255, 45, 142, 0.2), rgba(0, 229, 255, 0.2));
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -30px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }
        
        /* Container */
        .container {
            position: relative;
            z-index: 10;
            max-width: 1000px;
            margin: 0 auto;
        }
        
        /* Main Card */
        .luxury-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border-radius: 32px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .luxury-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 35px 60px -15px rgba(0, 0, 0, 0.6);
        }
        
        /* Header */
        .card-header-luxury {
            padding: 40px 40px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .logo-wrapper {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #ff2d8e, #7c3aed);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: rotate(45deg);
            animation: logoSpin 0.5s ease-out;
        }
        
        @keyframes logoSpin {
            from { transform: rotate(0deg) scale(0); opacity: 0; }
            to { transform: rotate(45deg) scale(1); opacity: 1; }
        }
        
        .logo-wrapper i {
            font-size: 40px;
            color: white;
            transform: rotate(-45deg);
        }
        
        .brand-title {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, #fff, #e0e0e0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 2px;
        }
        
        .brand-subtitle {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 8px;
        }
        
        .decorative-line {
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, #ff2d8e, #7c3aed);
            margin: 20px auto 0;
            border-radius: 3px;
        }
        
        /* Body */
        .card-body-luxury {
            padding: 30px 40px 40px;
        }
        
        .form-title {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-title h3 {
            font-size: 24px;
            font-weight: 600;
            color: white;
            margin-bottom: 8px;
        }
        
        .form-title p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.5);
        }
        
        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group.full-width {
            grid-column: span 2;
        }
        
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 8px;
            letter-spacing: 1px;
        }
        
        .form-label.required::after {
            content: '*';
            color: #ff2d8e;
            margin-left: 4px;
        }
        
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .input-wrapper i.input-icon {
            position: absolute;
            left: 18px;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.4);
            transition: all 0.3s;
            z-index: 1;
        }
        
        .input-wrapper input,
        .input-wrapper textarea,
        .input-wrapper select {
            width: 100%;
            padding: 14px 20px 14px 50px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            font-size: 14px;
            color: white;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s;
            resize: vertical;
        }
        
        .input-wrapper textarea {
            padding-top: 14px;
            min-height: 80px;
        }
        
        .input-wrapper select {
            appearance: none;
            cursor: pointer;
        }
        
        .input-wrapper select option {
            background: #1a1a2e;
            color: white;
        }
        
        .input-wrapper input:focus,
        .input-wrapper textarea:focus,
        .input-wrapper select:focus {
            outline: none;
            border-color: #ff2d8e;
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 20px rgba(255, 45, 142, 0.2);
        }
        
        .input-wrapper input:focus + i.input-icon,
        .input-wrapper textarea:focus + i.input-icon,
        .input-wrapper select:focus + i.input-icon {
            color: #ff2d8e;
        }
        
        .input-wrapper input::placeholder,
        .input-wrapper textarea::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }
        
        /* Valid States */
        .input-wrapper input.is-valid,
        .input-wrapper textarea.is-valid,
        .input-wrapper select.is-valid {
            border-color: #1cc88a;
        }
        
        .input-wrapper input.is-invalid,
        .input-wrapper textarea.is-invalid,
        .input-wrapper select.is-invalid {
            border-color: #e74a3b;
        }
        
        .valid-feedback {
            font-size: 11px;
            color: #1cc88a;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .invalid-feedback {
            font-size: 11px;
            color: #e74a3b;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 18px;
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.4);
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .password-toggle:hover {
            color: #ff2d8e;
        }
        
        /* Password Strength */
        .password-strength {
            margin-top: 10px;
            display: flex;
            gap: 8px;
        }
        
        .strength-bar {
            flex: 1;
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
            transition: all 0.3s;
        }
        
        .strength-bar.weak {
            background: linear-gradient(90deg, #e74a3b, #f6c23e);
        }
        
        .strength-bar.medium {
            background: linear-gradient(90deg, #f6c23e, #1cc88a);
        }
        
        .strength-bar.strong {
            background: linear-gradient(90deg, #1cc88a, #4e73df);
        }
        
        .strength-text {
            font-size: 11px;
            margin-top: 5px;
            text-align: right;
        }
        
        .strength-text.weak { color: #e74a3b; }
        .strength-text.medium { color: #f6c23e; }
        .strength-text.strong { color: #1cc88a; }
        
        /* Terms Checkbox */
        .terms-wrapper {
            margin: 20px 0;
            padding: 15px 20px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .checkbox-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }
        
        .checkbox-custom input {
            width: 18px;
            height: 18px;
            accent-color: #ff2d8e;
            cursor: pointer;
        }
        
        .checkbox-custom span {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .checkbox-custom a {
            color: #ff2d8e;
            text-decoration: none;
        }
        
        .checkbox-custom a:hover {
            text-decoration: underline;
        }
        
        /* Register Button */
        .btn-register {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #ff2d8e, #7c3aed);
            border: none;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
        }
        
        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-register:hover::before {
            left: 100%;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 45, 142, 0.4);
        }
        
        .btn-register:active {
            transform: translateY(0);
        }
        
        .btn-register:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        /* Loading Spinner */
        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Login Link */
        .login-link {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .login-link p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.5);
        }
        
        .login-link a {
            color: #ff2d8e;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .login-link a:hover {
            text-decoration: underline;
            color: #ff6bb5;
        }
        
        /* Alert Styles */
        .alert-luxury {
            padding: 16px 20px;
            border-radius: 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.4s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .alert-luxury i {
            font-size: 20px;
        }
        
        .alert-luxury .alert-content {
            flex: 1;
            font-size: 14px;
        }
        
        .alert-success {
            background: rgba(28, 200, 138, 0.15);
            border: 1px solid rgba(28, 200, 138, 0.3);
            color: #1cc88a;
        }
        
        .alert-danger {
            background: rgba(231, 74, 59, 0.15);
            border: 1px solid rgba(231, 74, 59, 0.3);
            color: #e74a3b;
        }
        
        .d-none {
            display: none !important;
        }
        
        /* Particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 5;
        }
        
        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            animation: particleFloat linear infinite;
        }
        
        @keyframes particleFloat {
            from {
                transform: translateY(100vh) translateX(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            to {
                transform: translateY(-20vh) translateX(50px);
                opacity: 0;
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 0;
            }
            
            .form-group.full-width {
                grid-column: span 1;
            }
            
            .card-header-luxury {
                padding: 30px 20px 15px;
            }
            
            .card-body-luxury {
                padding: 25px 25px 35px;
            }
            
            .logo-wrapper {
                width: 60px;
                height: 60px;
            }
            
            .logo-wrapper i {
                font-size: 30px;
            }
            
            .brand-title {
                font-size: 24px;
            }
        }
        
        @media (max-width: 480px) {
            body {
                padding: 20px 15px;
            }
            
            .form-grid {
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-animation">
        <div class="circle circle-1"></div>
        <div class="circle circle-2"></div>
        <div class="circle circle-3"></div>
    </div>
    
    <!-- Particles -->
    <div class="particles" id="particles"></div>
    
    <div class="container">
        <div class="luxury-card" data-aos="fade-up" data-aos-duration="1000">
            <!-- Header -->
            <div class="card-header-luxury">
                <div class="logo-wrapper">
                    <i class="fas fa-motorcycle"></i>
                </div>
                <h1 class="brand-title">Mo<span style="color: #ff2d8e; -webkit-text-fill-color: #ff2d8e;">To</span>R</h1>
                <p class="brand-subtitle">Join the Luxury Ride Experience</p>
                <div class="decorative-line"></div>
            </div>
            
            <!-- Body -->
            <div class="card-body-luxury">
                <div class="form-title" data-aos="fade-up" data-aos-delay="100">
                    <h3>Create Account</h3>
                    <p>Start your journey with MoToR today</p>
                </div>
                
                <!-- Alert Messages -->
                <?php if (isset($_SESSION['register_errors'])): ?>
                    <div class="alert-luxury alert-danger" data-aos="fade-up" data-aos-delay="150">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div class="alert-content">
                            <?php 
                            foreach ($_SESSION['register_errors'] as $error) {
                                echo "<div>• $error</div>";
                            }
                            unset($_SESSION['register_errors']);
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['register_success'])): ?>
                    <div class="alert-luxury alert-success" data-aos="fade-up" data-aos-delay="150">
                        <i class="fas fa-check-circle"></i>
                        <div class="alert-content">
                            <?php 
                            echo $_SESSION['register_success'];
                            unset($_SESSION['register_success']);
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Register Form -->
                <form action="../../../backend/action/auth/register_proses.php" method="POST" id="registerForm">
                    <div class="form-grid">
                        <!-- ID User -->
                        <div class="form-group" data-aos="fade-up" data-aos-delay="200">
                            <label class="form-label required">USER ID</label>
                            <div class="input-wrapper">
                                <i class="fas fa-id-card input-icon"></i>
                                <input type="number" name="id" id="userId" placeholder="Enter user ID" 
                                       value="<?php echo isset($_SESSION['register_data']['id']) ? htmlspecialchars($_SESSION['register_data']['id']) : ''; ?>">
                            </div>
                            <div class="invalid-feedback" id="userIdError"></div>
                        </div>
                        
                        <!-- Username -->
                        <div class="form-group" data-aos="fade-up" data-aos-delay="250">
                            <label class="form-label required">USERNAME</label>
                            <div class="input-wrapper">
                                <i class="fas fa-user input-icon"></i>
                                <input type="text" name="username" id="username" placeholder="Choose a username"
                                       value="<?php echo isset($_SESSION['register_data']['username']) ? htmlspecialchars($_SESSION['register_data']['username']) : ''; ?>">
                            </div>
                            <div class="invalid-feedback" id="usernameError"></div>
                        </div>
                        
                        <!-- Full Name -->
                        <div class="form-group full-width" data-aos="fade-up" data-aos-delay="300">
                            <label class="form-label required">FULL NAME</label>
                            <div class="input-wrapper">
                                <i class="fas fa-user-tag input-icon"></i>
                                <input type="text" name="nama_lengkap" id="fullName" placeholder="Enter your full name"
                                       value="<?php echo isset($_SESSION['register_data']['nama_lengkap']) ? htmlspecialchars($_SESSION['register_data']['nama_lengkap']) : ''; ?>">
                            </div>
                            <div class="invalid-feedback" id="fullNameError"></div>
                        </div>
                        
                        <!-- Email -->
                        <div class="form-group" data-aos="fade-up" data-aos-delay="350">
                            <label class="form-label">EMAIL</label>
                            <div class="input-wrapper">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" name="email" id="email" placeholder="your@email.com"
                                       value="<?php echo isset($_SESSION['register_data']['email']) ? htmlspecialchars($_SESSION['register_data']['email']) : ''; ?>">
                            </div>
                            <div class="invalid-feedback" id="emailError"></div>
                        </div>
                        
                        <!-- Phone Number -->
                        <div class="form-group" data-aos="fade-up" data-aos-delay="400">
                            <label class="form-label">PHONE NUMBER</label>
                            <div class="input-wrapper">
                                <i class="fas fa-phone input-icon"></i>
                                <input type="tel" name="no_telp" id="phone" placeholder="081234567890"
                                       value="<?php echo isset($_SESSION['register_data']['no_telp']) ? htmlspecialchars($_SESSION['register_data']['no_telp']) : ''; ?>">
                            </div>
                            <div class="invalid-feedback" id="phoneError"></div>
                        </div>
                        
                        <!-- Password -->
                        <div class="form-group" data-aos="fade-up" data-aos-delay="450">
                            <label class="form-label required">PASSWORD</label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" name="password" id="password" placeholder="Create a password">
                                <button type="button" class="password-toggle" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="password-strength" id="passwordStrength">
                                <div class="strength-bar"></div>
                                <div class="strength-bar"></div>
                                <div class="strength-bar"></div>
                                <div class="strength-bar"></div>
                            </div>
                            <div class="strength-text" id="strengthText"></div>
                            <div class="invalid-feedback" id="passwordError"></div>
                        </div>
                        
                        <!-- Confirm Password -->
                        <div class="form-group" data-aos="fade-up" data-aos-delay="500">
                            <label class="form-label required">CONFIRM PASSWORD</label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" name="confirm_password" id="confirmPassword" placeholder="Confirm your password">
                                <button type="button" class="password-toggle" id="toggleConfirmPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback" id="confirmPasswordError"></div>
                        </div>
                        
                        <!-- Address -->
                        <div class="form-group full-width" data-aos="fade-up" data-aos-delay="550">
                            <label class="form-label">ADDRESS</label>
                            <div class="input-wrapper">
                                <i class="fas fa-home input-icon"></i>
                                <textarea name="alamat" id="address" placeholder="Your complete address"><?php echo isset($_SESSION['register_data']['alamat']) ? htmlspecialchars($_SESSION['register_data']['alamat']) : ''; ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Terms & Conditions -->
                    <div class="terms-wrapper" data-aos="fade-up" data-aos-delay="600">
                        <label class="checkbox-custom">
                            <input type="checkbox" id="terms" required>
                            <span>I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></span>
                        </label>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn-register" id="registerBtn" data-aos="fade-up" data-aos-delay="650">
                        <span id="btnText">Create Account</span>
                        <span id="btnLoading" class="d-none">
                            <div class="spinner"></div>
                        </span>
                        <i class="fas fa-arrow-right" id="btnIcon"></i>
                    </button>
                    
                    <!-- Login Link -->
                    <div class="login-link" data-aos="fade-up" data-aos-delay="700">
                        <p>Already have an account? <a href="login.php">Sign In</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            easing: 'ease-out-cubic'
        });
        
        // Generate Particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 50;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                const left = Math.random() * 100;
                const duration = 5 + Math.random() * 10;
                const delay = Math.random() * 15;
                const size = 1 + Math.random() * 3;
                
                particle.style.left = left + '%';
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                particle.style.animationDuration = duration + 's';
                particle.style.animationDelay = delay + 's';
                particle.style.opacity = 0.3 + Math.random() * 0.5;
                
                particlesContainer.appendChild(particle);
            }
        }
        
        createParticles();
        
        // ============================================
        // PASSWORD TOGGLE
        // ============================================
        const togglePassword = document.getElementById('togglePassword');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
        
        toggleConfirmPassword.addEventListener('click', function() {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
        
        // ============================================
        // PASSWORD STRENGTH INDICATOR
        // ============================================
        function checkPasswordStrength(password) {
            let strength = 0;
            if (password.length >= 6) strength++;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            return Math.min(strength, 4);
        }
        
        function updateStrengthIndicator() {
            const password = passwordInput.value;
            const strength = checkPasswordStrength(password);
            const bars = document.querySelectorAll('.strength-bar');
            const strengthText = document.getElementById('strengthText');
            
            // Reset all bars
            bars.forEach(bar => {
                bar.classList.remove('weak', 'medium', 'strong');
                bar.style.background = 'rgba(255, 255, 255, 0.1)';
            });
            
            if (password.length === 0) {
                strengthText.textContent = '';
                return;
            }
            
            // Update bars based on strength
            for (let i = 0; i < strength; i++) {
                if (strength <= 2) {
                    bars[i].classList.add('weak');
                } else if (strength === 3) {
                    bars[i].classList.add('medium');
                } else {
                    bars[i].classList.add('strong');
                }
            }
            
            // Update text
            if (strength <= 1) {
                strengthText.textContent = 'Weak password';
                strengthText.className = 'strength-text weak';
            } else if (strength === 2) {
                strengthText.textContent = 'Fair password';
                strengthText.className = 'strength-text medium';
            } else if (strength === 3) {
                strengthText.textContent = 'Good password';
                strengthText.className = 'strength-text medium';
            } else {
                strengthText.textContent = 'Strong password!';
                strengthText.className = 'strength-text strong';
            }
        }
        
        passwordInput.addEventListener('input', updateStrengthIndicator);
        
        // ============================================
        // REAL-TIME VALIDATION
        // ============================================
        function validateUserId() {
            const userId = document.getElementById('userId');
            const error = document.getElementById('userIdError');
            const value = userId.value.trim();
            
            if (!value) {
                userId.classList.add('is-invalid');
                userId.classList.remove('is-valid');
                error.textContent = 'User ID is required';
                return false;
            }
            if (isNaN(value) || value <= 0) {
                userId.classList.add('is-invalid');
                userId.classList.remove('is-valid');
                error.textContent = 'Enter a valid positive number';
                return false;
            }
            userId.classList.remove('is-invalid');
            userId.classList.add('is-valid');
            error.textContent = '';
            return true;
        }
        
        function validateUsername() {
            const username = document.getElementById('username');
            const error = document.getElementById('usernameError');
            const value = username.value.trim();
            
            if (!value) {
                username.classList.add('is-invalid');
                username.classList.remove('is-valid');
                error.textContent = 'Username is required';
                return false;
            }
            if (value.length < 3) {
                username.classList.add('is-invalid');
                username.classList.remove('is-valid');
                error.textContent = 'Username must be at least 3 characters';
                return false;
            }
            username.classList.remove('is-invalid');
            username.classList.add('is-valid');
            error.textContent = '';
            return true;
        }
        
        function validateFullName() {
            const fullName = document.getElementById('fullName');
            const error = document.getElementById('fullNameError');
            const value = fullName.value.trim();
            
            if (!value) {
                fullName.classList.add('is-invalid');
                fullName.classList.remove('is-valid');
                error.textContent = 'Full name is required';
                return false;
            }
            fullName.classList.remove('is-invalid');
            fullName.classList.add('is-valid');
            error.textContent = '';
            return true;
        }
        
        function validateEmail() {
            const email = document.getElementById('email');
            const error = document.getElementById('emailError');
            const value = email.value.trim();
            
            if (value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                email.classList.add('is-invalid');
                email.classList.remove('is-valid');
                error.textContent = 'Enter a valid email address';
                return false;
            }
            email.classList.remove('is-invalid');
            if (value) email.classList.add('is-valid');
            error.textContent = '';
            return true;
        }
        
        function validatePhone() {
            const phone = document.getElementById('phone');
            const error = document.getElementById('phoneError');
            const value = phone.value.trim();
            
            if (value && !/^[0-9]{10,13}$/.test(value)) {
                phone.classList.add('is-invalid');
                phone.classList.remove('is-valid');
                error.textContent = 'Enter a valid phone number (10-13 digits)';
                return false;
            }
            phone.classList.remove('is-invalid');
            if (value) phone.classList.add('is-valid');
            error.textContent = '';
            return true;
        }
        
        function validatePassword() {
            const password = document.getElementById('password');
            const error = document.getElementById('passwordError');
            const value = password.value;
            
            if (!value) {
                password.classList.add('is-invalid');
                password.classList.remove('is-valid');
                error.textContent = 'Password is required';
                return false;
            }
            if (value.length < 6) {
                password.classList.add('is-invalid');
                password.classList.remove('is-valid');
                error.textContent = 'Password must be at least 6 characters';
                return false;
            }
            password.classList.remove('is-invalid');
            password.classList.add('is-valid');
            error.textContent = '';
            return true;
        }
        
        function validateConfirmPassword() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword');
            const error = document.getElementById('confirmPasswordError');
            const value = confirmPassword.value;
            
            if (!value) {
                confirmPassword.classList.add('is-invalid');
                confirmPassword.classList.remove('is-valid');
                error.textContent = 'Please confirm your password';
                return false;
            }
            if (value !== password) {
                confirmPassword.classList.add('is-invalid');
                confirmPassword.classList.remove('is-valid');
                error.textContent = 'Passwords do not match';
                return false;
            }
            confirmPassword.classList.remove('is-invalid');
            confirmPassword.classList.add('is-valid');
            error.textContent = '';
            return true;
        }
        
        // Attach validation events
        document.getElementById('userId').addEventListener('input', validateUserId);
        document.getElementById('username').addEventListener('input', validateUsername);
        document.getElementById('fullName').addEventListener('input', validateFullName);
        document.getElementById('email').addEventListener('input', validateEmail);
        document.getElementById('phone').addEventListener('input', validatePhone);
        document.getElementById('password').addEventListener('input', function() {
            validatePassword();
            validateConfirmPassword();
        });
        document.getElementById('confirmPassword').addEventListener('input', validateConfirmPassword);
        
        // ============================================
        // FORM SUBMISSION
        // ============================================
        const registerForm = document.getElementById('registerForm');
        const registerBtn = document.getElementById('registerBtn');
        const btnText = document.getElementById('btnText');
        const btnLoading = document.getElementById('btnLoading');
        const btnIcon = document.getElementById('btnIcon');
        const termsCheckbox = document.getElementById('terms');
        
        let isSubmitting = false;
        
        registerForm.addEventListener('submit', function(e) {
            // Validate all fields
            const isUserIdValid = validateUserId();
            const isUsernameValid = validateUsername();
            const isFullNameValid = validateFullName();
            const isEmailValid = validateEmail();
            const isPhoneValid = validatePhone();
            const isPasswordValid = validatePassword();
            const isConfirmPasswordValid = validateConfirmPassword();
            const isTermsChecked = termsCheckbox.checked;
            
            if (!isUserIdValid || !isUsernameValid || !isFullNameValid || !isPasswordValid || !isConfirmPasswordValid) {
                e.preventDefault();
                return;
            }
            
            if (!isTermsChecked) {
                e.preventDefault();
                termsCheckbox.style.outline = '2px solid #e74a3b';
                setTimeout(() => {
                    termsCheckbox.style.outline = '';
                }, 2000);
                return;
            }
            
            if (isSubmitting) {
                e.preventDefault();
                return;
            }
            
            isSubmitting = true;
            
            // Show loading
            registerBtn.disabled = true;
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
            btnIcon.style.display = 'none';
            
            // Timeout reset (10 seconds)
            setTimeout(function() {
                if (isSubmitting) {
                    resetButton();
                    isSubmitting = false;
                }
            }, 10000);
        });
        
        function resetButton() {
            registerBtn.disabled = false;
            btnText.classList.remove('d-none');
            btnLoading.classList.add('d-none');
            btnIcon.style.display = 'inline-block';
        }
        
        // Auto focus on first field
        document.getElementById('userId').focus();
        
        // Clear session data
        <?php unset($_SESSION['register_data']); ?>
        
        // Clear URL parameters on load
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.pathname);
        }
        
        // Animated background circles movement
        let mouseX = 0, mouseY = 0;
        let circles = document.querySelectorAll('.circle');
        
        document.addEventListener('mousemove', function(e) {
            mouseX = e.clientX / window.innerWidth;
            mouseY = e.clientY / window.innerHeight;
            
            circles.forEach((circle, index) => {
                const speed = (index + 1) * 20;
                const x = (mouseX - 0.5) * speed;
                const y = (mouseY - 0.5) * speed;
                circle.style.transform = `translate(${x}px, ${y}px)`;
            });
        });
        
        // Fetch last ID
        fetch('../../../backend/action/auth/get_last_id.php')
            .then(response => response.json())
            .then(data => {
                if (data.last_id && data.last_id > 0 && !document.getElementById('userId').value) {
                    document.getElementById('userId').value = parseInt(data.last_id) + 1;
                    validateUserId();
                }
            })
            .catch(error => console.error('Error fetching last ID:', error));
    </script>
</body>
</html>