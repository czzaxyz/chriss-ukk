<?php
session_start();

// Jika sudah login, redirect ke dashboard sesuai role
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    switch($_SESSION['role']) {
        case 'admin':
            header("Location: ../../index.php");
            exit();
        case 'petugas':
            header("Location: ../../index.php");
            exit();
        case 'peminjam':
            header("Location: ../../index.php");
            exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Login | MoToR Luxury System</title>
    
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        /* Main Card */
        .luxury-card {
            max-width: 460px;
            width: 100%;
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
        
        /* Body */
        .card-body-luxury {
            padding: 30px 40px 40px;
        }
        
        /* Welcome Message */
        .welcome-message {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .welcome-message h3 {
            font-size: 24px;
            font-weight: 600;
            color: white;
            margin-bottom: 8px;
        }
        
        .welcome-message p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.5);
        }
        
        /* Form Groups */
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 8px;
            letter-spacing: 1px;
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
        
        .input-wrapper input {
            width: 100%;
            padding: 16px 50px 16px 50px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            font-size: 15px;
            color: white;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s;
        }
        
        .input-wrapper input:focus {
            outline: none;
            border-color: #ff2d8e;
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 20px rgba(255, 45, 142, 0.2);
        }
        
        .input-wrapper input:focus + i.input-icon {
            color: #ff2d8e;
        }
        
        .input-wrapper input::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }
        
        /* Password Toggle - DI LUAR (sebelah kanan input) */
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
        
        /* Options Row */
        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }
        
        .checkbox-wrapper input {
            width: 18px;
            height: 18px;
            accent-color: #ff2d8e;
            cursor: pointer;
        }
        
        .checkbox-wrapper span {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .forgot-link {
            font-size: 13px;
            color: #ff2d8e;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .forgot-link:hover {
            text-decoration: underline;
            color: #ff6bb5;
        }
        
        /* Login Button */
        .btn-login {
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
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 45, 142, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .btn-login:disabled {
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
        
        /* Register Link */
        .register-link {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .register-link p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.5);
        }
        
        .register-link a {
            color: #ff2d8e;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .register-link a:hover {
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
        
        .alert-warning {
            background: rgba(246, 194, 62, 0.15);
            border: 1px solid rgba(246, 194, 62, 0.3);
            color: #f6c23e;
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
        
        /* Decorative Line */
        .decorative-line {
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, #ff2d8e, #7c3aed);
            margin: 20px auto 0;
            border-radius: 3px;
        }
        
        /* Responsive */
        @media (max-width: 576px) {
            .luxury-card {
                border-radius: 24px;
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
                <h1 class="brand-title">Mo<span style="color: #ff2d8e; -webkit-text-fill-color: #ff2d8e;">To</span>R</h1>
                <p class="brand-subtitle">Luxury Ride Experience</p>
                <div class="decorative-line"></div>
            </div>
            
            <!-- Body -->
            <div class="card-body-luxury">
                <div class="welcome-message" data-aos="fade-up" data-aos-delay="100">
                    <h3>Welcome Back</h3>
                    <p>Sign in to continue your journey</p>
                </div>
                
                <!-- Session Messages -->
                <?php if (isset($_SESSION['register_success'])): ?>
                    <div class="alert-luxury alert-success" data-aos="fade-up" data-aos-delay="150">
                        <i class="fas fa-check-circle"></i>
                        <div class="alert-content">
                            <?php echo $_SESSION['register_success']; unset($_SESSION['register_success']); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['logout_success'])): ?>
                    <div class="alert-luxury alert-success" data-aos="fade-up" data-aos-delay="150">
                        <i class="fas fa-check-circle"></i>
                        <div class="alert-content">
                            <?php echo $_SESSION['logout_success']; unset($_SESSION['logout_success']); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['login_error'])): ?>
                    <div class="alert-luxury alert-danger" data-aos="fade-up" data-aos-delay="150">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div class="alert-content">
                            <?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagal'): ?>
                    <div class="alert-luxury alert-danger" data-aos="fade-up" data-aos-delay="150">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div class="alert-content">Invalid username or password!</div>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'logout'): ?>
                    <div class="alert-luxury alert-success" data-aos="fade-up" data-aos-delay="150">
                        <i class="fas fa-check-circle"></i>
                        <div class="alert-content">You have been logged out successfully!</div>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'belum_login'): ?>
                    <div class="alert-luxury alert-warning" data-aos="fade-up" data-aos-delay="150">
                        <i class="fas fa-exclamation-circle"></i>
                        <div class="alert-content">Please login first!</div>
                    </div>
                <?php endif; ?>
                
                <!-- Login Form -->
                <form action="./login_proses.php" method="POST" id="loginForm">
                    <div class="form-group" data-aos="fade-up" data-aos-delay="200">
                        <label class="form-label">USERNAME</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" name="username" id="username" placeholder="Enter your username" required autocomplete="off">
                        </div>
                    </div>
                    
                    <div class="form-group" data-aos="fade-up" data-aos-delay="250">
                        <label class="form-label">PASSWORD</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" name="password" id="password" placeholder="Enter your password" required>
                            <!-- Toggle password DI LUAR (sebelah kanan) -->
                            <button type="button" class="password-toggle" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-login" id="loginBtn" data-aos="fade-up" data-aos-delay="350">
                        <span id="btnText">Sign In</span>
                        <span id="btnLoading" class="d-none">
                            <div class="spinner"></div>
                        </span>
                        <i class="fas fa-arrow-right" id="btnIcon"></i>
                    </button>
                    
                    <div class="register-link" data-aos="fade-up" data-aos-delay="400">
                        <p>Don't have an account? <a href="register.php">Create Account</a></p>
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
        // PASSWORD TOGGLE - Di luar (sebelah kanan)
        // ============================================
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Change icon
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
        
        // ============================================
        // FORM SUBMISSION - Fix loading tidak ilang
        // ============================================
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        const btnText = document.getElementById('btnText');
        const btnLoading = document.getElementById('btnLoading');
        const btnIcon = document.getElementById('btnIcon');
        
        let isSubmitting = false;
        
        loginForm.addEventListener('submit', function(e) {
            // Cek apakah form valid
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            
            if (!username || !password) {
                e.preventDefault();
                return;
            }
            
            // Cegah double submit
            if (isSubmitting) {
                e.preventDefault();
                return;
            }
            
            // Set flag submitting
            isSubmitting = true;
            
            // Tampilkan loading
            loginBtn.disabled = true;
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
            btnIcon.style.display = 'none';
            
            // Submit form akan tetap berjalan
            // Loading akan hilang jika halaman redirect (tidak perlu dihilangkan manual)
            // TAPI jika terjadi error/redirect gagal, kita perlu reset setelah timeout
            setTimeout(function() {
                if (isSubmitting) {
                    // Reset jika masih dalam state submitting (kemungkinan network error)
                    resetButton();
                    isSubmitting = false;
                }
            }, 10000); // Timeout 10 detik
        });
        
        function resetButton() {
            loginBtn.disabled = false;
            btnText.classList.remove('d-none');
            btnLoading.classList.add('d-none');
            btnIcon.style.display = 'inline-block';
        }
        
        // Auto focus on username
        document.getElementById('username').focus();
        
        // Clear URL parameters on load
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.pathname);
        }
        
        // Add floating label effect
        const inputs = document.querySelectorAll('.input-wrapper input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.01)';
            });
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
        
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
        
        // Enter key support
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !loginBtn.disabled) {
                const username = document.getElementById('username').value.trim();
                const password = document.getElementById('password').value;
                if (username && password) {
                    loginForm.dispatchEvent(new Event('submit'));
                }
            }
        });
    </script>
</body>
</html>