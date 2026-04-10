<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Register | Sistem Peminjaman Motor</title>

    <link rel="icon" type="image/png" href="/chriss-ukk/frontend/templates/assets/img/favicon.png" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">

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
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
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
            background: linear-gradient(135deg, rgba(0, 180, 219, 0.3), rgba(0, 131, 176, 0.3));
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
            background: linear-gradient(135deg, rgba(0, 150, 255, 0.3), rgba(0, 200, 255, 0.3));
        }

        .circle-3 {
            width: 300px;
            height: 300px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: 10s;
            background: linear-gradient(135deg, rgba(0, 180, 219, 0.2), rgba(0, 230, 255, 0.2));
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            33% {
                transform: translate(30px, -30px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
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
            background: linear-gradient(135deg, #00b4db, #0083b0);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: rotate(45deg);
            animation: logoSpin 0.5s ease-out;
        }

        @keyframes logoSpin {
            from {
                transform: rotate(0deg) scale(0);
                opacity: 0;
            }

            to {
                transform: rotate(45deg) scale(1);
                opacity: 1;
            }
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

        .brand-title span {
            background: linear-gradient(135deg, #00b4db, #0083b0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-subtitle {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 8px;
        }

        .decorative-line {
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, #00b4db, #0083b0);
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
            color: #00b4db;
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
        .input-wrapper textarea {
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

        .input-wrapper input:focus,
        .input-wrapper textarea:focus {
            outline: none;
            border-color: #00b4db;
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 20px rgba(0, 180, 219, 0.2);
        }

        .input-wrapper input:focus+i.input-icon,
        .input-wrapper textarea:focus+i.input-icon {
            color: #00b4db;
        }

        .input-wrapper input::placeholder,
        .input-wrapper textarea::placeholder {
            color: rgba(255, 255, 255, 0.3);
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
        }

        .password-toggle:hover {
            color: #00b4db;
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
            background: linear-gradient(90deg, #1cc88a, #00b4db);
        }

        .strength-text {
            font-size: 11px;
            margin-top: 5px;
            text-align: right;
        }

        .strength-text.weak {
            color: #e74a3b;
        }

        .strength-text.medium {
            color: #f6c23e;
        }

        .strength-text.strong {
            color: #1cc88a;
        }

        /* Register Button */
        .btn-register {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #00b4db, #0083b0);
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
            margin-top: 20px;
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
            box-shadow: 0 10px 30px rgba(0, 180, 219, 0.4);
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
            color: #00b4db;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .login-link a:hover {
            text-decoration: underline;
            color: #4dd0ff;
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

        .alert-danger {
            background: rgba(231, 74, 59, 0.15);
            border: 1px solid rgba(231, 74, 59, 0.3);
            color: #e74a3b;
        }

        .alert-success {
            background: rgba(28, 200, 138, 0.15);
            border: 1px solid rgba(28, 200, 138, 0.3);
            color: #1cc88a;
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
                <h1 class="brand-title">Mo<span>To</span>R</h1>
                <p class="brand-subtitle">Join the Blue Ocean Ride Experience</p>
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
                <form action="./auth/register" method="POST" id="registerForm">
                    <div class="form-grid">
                        <!-- Username -->
                        <div class="form-group" data-aos="fade-up" data-aos-delay="200">
                            <label class="form-label required">USERNAME</label>
                            <div class="input-wrapper">
                                <i class="fas fa-user input-icon"></i>
                                <input type="text" name="username" id="username" placeholder="Choose a username" required>
                            </div>
                        </div>

                        <!-- Full Name -->
                        <div class="form-group" data-aos="fade-up" data-aos-delay="250">
                            <label class="form-label required">FULL NAME</label>
                            <div class="input-wrapper">
                                <i class="fas fa-user-tag input-icon"></i>
                                <input type="text" name="nama_lengkap" id="fullName" placeholder="Enter your full name" required>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="form-group" data-aos="fade-up" data-aos-delay="300">
                            <label class="form-label">EMAIL</label>
                            <div class="input-wrapper">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" name="email" id="email" placeholder="your@email.com">
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="form-group" data-aos="fade-up" data-aos-delay="350">
                            <label class="form-label">PHONE NUMBER</label>
                            <div class="input-wrapper">
                                <i class="fas fa-phone input-icon"></i>
                                <input type="tel" name="no_telp" id="phone" placeholder="081234567890">
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="form-group" data-aos="fade-up" data-aos-delay="400">
                            <label class="form-label required">PASSWORD</label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" name="password" id="password" placeholder="Create a password" required>
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
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group" data-aos="fade-up" data-aos-delay="450">
                            <label class="form-label required">CONFIRM PASSWORD</label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" name="confirm_password" id="confirmPassword" placeholder="Confirm your password" required>
                                <button type="button" class="password-toggle" id="toggleConfirmPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="form-group full-width" data-aos="fade-up" data-aos-delay="500">
                            <label class="form-label">ADDRESS</label>
                            <div class="input-wrapper">
                                <i class="fas fa-home input-icon"></i>
                                <textarea name="alamat" id="address" rows="3" placeholder="Your complete address"></textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-register" id="registerBtn" data-aos="fade-up" data-aos-delay="550">
                        <i class="fas fa-user-plus"></i> Create Account
                        <i class="fas fa-arrow-right"></i>
                    </button>

                    <!-- Login Link -->
                    <div class="login-link" data-aos="fade-up" data-aos-delay="600">
                        <p>Already have an account? <a href="login">Sign In</a></p>
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
                particle.style.left = Math.random() * 100 + '%';
                particle.style.width = (1 + Math.random() * 3) + 'px';
                particle.style.height = particle.style.width;
                particle.style.animationDuration = (5 + Math.random() * 10) + 's';
                particle.style.animationDelay = Math.random() * 15 + 's';
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

            bars.forEach(bar => {
                bar.classList.remove('weak', 'medium', 'strong');
                bar.style.background = 'rgba(255, 255, 255, 0.1)';
            });

            if (password.length === 0) {
                strengthText.textContent = '';
                return;
            }

            for (let i = 0; i < strength; i++) {
                if (strength <= 2) {
                    bars[i].classList.add('weak');
                } else if (strength === 3) {
                    bars[i].classList.add('medium');
                } else {
                    bars[i].classList.add('strong');
                }
            }

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

        // Auto focus on first field
        document.getElementById('username').focus();

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
    </script>
</body>

</html>