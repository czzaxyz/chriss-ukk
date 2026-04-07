<?php
session_start();

// Include koneksi database
include '../../app.php';

// Query untuk mengambil data motor dari database
$queryMotor = "SELECT b.*, k.nama_kategori 
               FROM barang b 
               LEFT JOIN kategori k ON b.kategori_id = k.id 
               WHERE b.status = 'tersedia' 
               ORDER BY b.id DESC 
               LIMIT 6";
$resultMotor = mysqli_query($connect, $queryMotor);

$motors = [];
if ($resultMotor && mysqli_num_rows($resultMotor) > 0) {
    while ($row = mysqli_fetch_assoc($resultMotor)) {
        $motors[] = $row;
    }
}

// Query untuk mengambil statistik motor
$queryStats = "SELECT 
                    COUNT(*) as total_motor,
                    SUM(CASE WHEN status = 'tersedia' THEN 1 ELSE 0 END) as tersedia,
                    SUM(CASE WHEN status = 'dipinjam' THEN 1 ELSE 0 END) as dipinjam,
                    SUM(CASE WHEN status = 'rusak' THEN 1 ELSE 0 END) as rusak,
                    SUM(CASE WHEN status = 'hilang' THEN 1 ELSE 0 END) as hilang
               FROM barang";
$resultStats = mysqli_query($connect, $queryStats);
$stats = mysqli_fetch_assoc($resultStats);

// Query untuk mengambil kategori
$queryKategori = "SELECT * FROM kategori ORDER BY id LIMIT 4";
$resultKategori = mysqli_query($connect, $queryKategori);
$kategoris = [];
if ($resultKategori && mysqli_num_rows($resultKategori) > 0) {
    while ($row = mysqli_fetch_assoc($resultKategori)) {
        $kategoris[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>MoToR Luxe | Sewa Motor Premium</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Swiper JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <style>
        /* ============================================
           LANDING PAGE CSS - WHITE & PURPLE EDITION
           ============================================ */
        :root {
            --primary: #6c3bd8;
            --primary-dark: #5a2bc0;
            --primary-light: #8b5cf6;
            --primary-soft: #ede8fc;
            --secondary: #a78bfa;
            --accent: #c4b5fd;
            --dark: #1a1a2e;
            --dark-100: #16213e;
            --dark-200: #0f3460;
            --gray: #f3f4f6;
            --gray-light: #f9fafb;
            --text: #374151;
            --text-light: #6b7280;
            --white: #ffffff;
            --gradient-primary: linear-gradient(135deg, #6c3bd8 0%, #8b5cf6 100%);
            --gradient-soft: linear-gradient(135deg, #ede8fc 0%, #f5f3ff 100%);
            --shadow-sm: 0 4px 20px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 8px 40px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 20px 60px rgba(0, 0, 0, 0.1);
            --shadow-glow: 0 0 30px rgba(108, 59, 216, 0.2);
            --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            overflow-x: hidden;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--white);
            color: var(--text);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Custom Cursor - Light Version */
        .cursor {
            position: fixed;
            width: 8px;
            height: 8px;
            background: var(--primary);
            border-radius: 50%;
            pointer-events: none;
            z-index: 10000;
            transition: transform 0.1s ease;
        }

        .cursor-follower {
            position: fixed;
            width: 40px;
            height: 40px;
            border: 2px solid rgba(108, 59, 216, 0.3);
            border-radius: 50%;
            pointer-events: none;
            z-index: 9999;
            transition: all 0.2s ease;
        }

        /* Preloader */
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--white);
            z-index: 20000;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s, visibility 0.5s;
        }

        #preloader.hide {
            opacity: 0;
            visibility: hidden;
        }

        .preloader-content {
            text-align: center;
        }

        .preloader-logo {
            font-size: 48px;
            font-weight: 800;
            letter-spacing: 4px;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
        }

        .preloader-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(108, 59, 216, 0.2);
            border-top-color: var(--primary);
            border-radius: 50%;
            margin: 20px auto;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Hero Section - Light Background */
        .hero-section {
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f3ff 0%, #ffffff 50%, #fae8ff 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-bg-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.05;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%236c3bd8' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            background-repeat: repeat;
        }

        /* Navigation */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 20px 0;
            transition: var(--transition);
            background: transparent;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            padding: 15px 0;
            box-shadow: var(--shadow-sm);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
        }

        .logo-icon {
            font-size: 28px;
            font-weight: 800;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .logo-text {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark);
            letter-spacing: 1px;
        }

        .logo-text span {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-menu {
            display: flex;
            gap: 40px;
        }

        .nav-link {
            text-decoration: none;
            color: var(--text);
            font-weight: 500;
            transition: var(--transition);
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gradient-primary);
            transition: width 0.3s;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary);
        }

        .nav-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .btn-login {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: transparent;
            border: 1px solid var(--primary-light);
            border-radius: 40px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-login:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: var(--white);
        }

        .btn-primary {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            background: var(--gradient-primary);
            border: none;
            border-radius: 40px;
            color: var(--white);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-glow);
        }

        .menu-toggle {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
        }

        .menu-toggle span {
            width: 25px;
            height: 2px;
            background: var(--dark);
            transition: var(--transition);
        }

        /* Mobile Menu */
        .mobile-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 100%;
            max-width: 400px;
            height: 100vh;
            background: var(--white);
            z-index: 2000;
            padding: 30px;
            transition: right 0.4s;
            box-shadow: var(--shadow-lg);
        }

        .mobile-menu.active {
            right: 0;
        }

        .mobile-menu-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--gray);
        }

        .mobile-logo {
            font-size: 28px;
            font-weight: 800;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .mobile-menu-close {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gray);
            border-radius: 50%;
            cursor: pointer;
        }

        .mobile-menu-links {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 40px;
        }

        .mobile-nav-link {
            text-decoration: none;
            color: var(--text);
            font-size: 20px;
            font-weight: 500;
            transition: var(--transition);
        }

        .mobile-nav-link:hover {
            color: var(--primary);
            transform: translateX(10px);
        }

        .mobile-menu-footer {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .mobile-btn-login,
        .mobile-btn-primary {
            text-decoration: none;
            text-align: center;
            padding: 15px;
            border-radius: 40px;
            font-weight: 600;
        }

        .mobile-btn-login {
            background: var(--gray);
            color: var(--primary);
        }

        .mobile-btn-primary {
            background: var(--gradient-primary);
            color: var(--white);
        }

        /* Hero Content */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
            padding: 120px 0 80px;
        }

        .hero-content {
            text-align: center;
            max-width: 900px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--primary-soft);
            padding: 8px 20px;
            border-radius: 40px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 30px;
            color: var(--primary);
        }

        .badge-pulse {
            width: 8px;
            height: 8px;
            background: var(--primary);
            border-radius: 50%;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.5); }
        }

        .hero-title {
            font-size: 72px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 20px;
            color: var(--dark);
        }

        .gradient-text {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 18px;
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto 40px;
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-bottom: 60px;
        }

        .btn-hero-primary {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            background: var(--gradient-primary);
            border-radius: 50px;
            color: var(--white);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-glow);
        }

        .btn-hero-secondary {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            background: transparent;
            border: 2px solid var(--primary);
            border-radius: 50px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-hero-secondary:hover {
            background: var(--primary-soft);
            transform: translateY(-3px);
        }

        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 60px;
        }

        .hero-stat {
            text-align: center;
        }

        .hero-stat .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary);
        }

        .hero-stat .stat-label {
            font-size: 14px;
            color: var(--text-light);
        }

        .scroll-indicator {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
        }

        .scroll-mouse {
            width: 26px;
            height: 40px;
            border: 2px solid var(--text-light);
            border-radius: 20px;
            margin: 0 auto 10px;
            position: relative;
        }

        .scroll-wheel {
            width: 4px;
            height: 8px;
            background: var(--primary);
            border-radius: 2px;
            position: absolute;
            top: 8px;
            left: 50%;
            transform: translateX(-50%);
            animation: scroll 2s infinite;
        }

        @keyframes scroll {
            0% { opacity: 1; transform: translateX(-50%) translateY(0); }
            100% { opacity: 0; transform: translateX(-50%) translateY(15px); }
        }

        .scroll-text {
            font-size: 12px;
            letter-spacing: 2px;
            color: var(--text-light);
        }

        /* Marquee Banner */
        .marquee {
            background: var(--primary-soft);
            padding: 15px 0;
            overflow: hidden;
            white-space: nowrap;
        }

        .marquee-content {
            display: inline-block;
            animation: marquee 20s linear infinite;
        }

        .marquee-content span {
            display: inline-block;
            margin: 0 30px;
            font-weight: 500;
            letter-spacing: 2px;
            color: var(--primary);
        }

        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
        }

        section {
            padding: 100px 0;
            position: relative;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-tag {
            display: inline-block;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 3px;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .section-title {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--dark);
        }

        .section-subtitle {
            font-size: 18px;
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Stats Section */
        .stats-section {
            background: var(--gray-light);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
        }

        .stat-card {
            background: var(--white);
            border-radius: 20px;
            padding: 40px 20px;
            text-align: center;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }

        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-glow);
        }

        .stat-icon {
            font-size: 40px;
            color: var(--primary);
            margin-bottom: 20px;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--dark);
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Features Section */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .feature-card {
            background: var(--white);
            border-radius: 20px;
            padding: 40px 30px;
            transition: var(--transition);
            position: relative;
            box-shadow: var(--shadow-sm);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--gradient-soft);
            opacity: 0;
            transition: var(--transition);
            border-radius: 20px;
            z-index: -1;
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-md);
        }

        .feature-icon {
            font-size: 48px;
            color: var(--primary);
            margin-bottom: 25px;
        }

        .feature-card h3 {
            font-size: 24px;
            margin-bottom: 15px;
            color: var(--dark);
        }

        .feature-card p {
            color: var(--text-light);
            margin-bottom: 20px;
        }

        .feature-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            cursor: pointer;
        }

        .feature-link:hover {
            gap: 12px;
        }

        /* Models Section */
        .models-section {
            background: var(--gray-light);
        }

        .model-card {
            background: var(--white);
            border-radius: 24px;
            overflow: hidden;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }

        .model-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-glow);
        }

        .model-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            background: var(--gradient-primary);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            z-index: 1;
            color: var(--white);
        }

        .model-image {
            overflow: hidden;
            height: 250px;
        }

        .model-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .model-card:hover .model-image img {
            transform: scale(1.1);
        }

        .model-info {
            padding: 25px;
        }

        .model-info h3 {
            font-size: 22px;
            margin-bottom: 10px;
            color: var(--dark);
        }

        .model-desc {
            color: var(--text-light);
            font-size: 14px;
            margin-bottom: 20px;
        }

        .model-specs {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .model-specs span {
            font-size: 12px;
            color: var(--text-light);
        }

        .model-specs i {
            color: var(--primary);
            margin-right: 5px;
        }

        .model-price {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--primary);
        }

        .model-price span {
            font-size: 12px;
            font-weight: 400;
            color: var(--text-light);
        }

        .btn-model {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: transparent;
            border: 1px solid var(--primary);
            border-radius: 40px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-model:hover {
            background: var(--primary);
            color: var(--white);
            gap: 12px;
        }

        /* Swiper Custom */
        .swiper-button-next,
        .swiper-button-prev {
            color: var(--primary);
            background: var(--white);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            box-shadow: var(--shadow-sm);
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 18px;
        }

        .swiper-pagination-bullet {
            background: var(--text-light);
        }

        .swiper-pagination-bullet-active {
            background: var(--primary);
        }

        /* Testimonials Section */
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .testimonial-card {
            background: var(--white);
            border-radius: 20px;
            padding: 30px;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }

        .testimonial-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-glow);
        }

        .testimonial-quote {
            font-size: 32px;
            color: var(--primary);
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .testimonial-text {
            font-size: 16px;
            margin-bottom: 25px;
            line-height: 1.7;
            color: var(--text);
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .testimonial-author img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .author-info h4 {
            font-size: 16px;
            margin-bottom: 5px;
            color: var(--dark);
        }

        .author-rating i {
            color: #fbbf24;
            font-size: 12px;
        }

        /* CTA Section */
        .cta-section {
            background: var(--gradient-soft);
        }

        .cta-content {
            text-align: center;
            max-width: 700px;
            margin: 0 auto;
        }

        .cta-content h2 {
            font-size: 48px;
            margin-bottom: 20px;
            color: var(--dark);
        }

        .cta-content p {
            font-size: 18px;
            color: var(--text-light);
            margin-bottom: 40px;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
        }

        .btn-cta-primary,
        .btn-cta-secondary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-cta-primary {
            background: var(--gradient-primary);
            color: var(--white);
        }

        .btn-cta-primary:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-glow);
        }

        .btn-cta-secondary {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-cta-secondary:hover {
            background: var(--primary-soft);
            transform: translateY(-3px);
        }

        /* Partners Section */
        .partners-section {
            padding: 60px 0;
            background: var(--white);
        }

        .partners-title {
            text-align: center;
            font-size: 14px;
            letter-spacing: 2px;
            color: var(--text-light);
            margin-bottom: 40px;
        }

        .partners-grid {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 60px;
            flex-wrap: wrap;
        }

        .partner-logo {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-light);
            transition: var(--transition);
        }

        .partner-logo:hover {
            color: var(--primary);
            transform: scale(1.1);
        }

        /* Footer */
        .footer {
            background: var(--dark);
            padding: 80px 0 30px;
            color: var(--gray);
        }

        .footer-content {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 50px;
            margin-bottom: 60px;
        }

        .footer-logo {
            font-size: 36px;
            font-weight: 800;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
        }

        .footer-brand p {
            color: var(--text-light);
            margin-bottom: 25px;
        }

        .social-links {
            display: flex;
            gap: 15px;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            color: var(--gray);
            transition: var(--transition);
        }

        .social-links a:hover {
            background: var(--primary);
            color: var(--white);
            transform: translateY(-3px);
        }

        .footer-links h4 {
            font-size: 18px;
            margin-bottom: 25px;
            color: var(--white);
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            text-decoration: none;
            color: var(--text-light);
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: var(--primary);
            transform: translateX(5px);
            display: inline-block;
        }

        .footer-newsletter h4 {
            font-size: 18px;
            margin-bottom: 15px;
            color: var(--white);
        }

        .footer-newsletter p {
            color: var(--text-light);
            margin-bottom: 20px;
        }

        .newsletter-form {
            display: flex;
            gap: 10px;
        }

        .newsletter-form input {
            flex: 1;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 40px;
            color: var(--white);
            outline: none;
            transition: var(--transition);
        }

        .newsletter-form input:focus {
            border-color: var(--primary);
        }

        .newsletter-form button {
            width: 44px;
            height: 44px;
            background: var(--gradient-primary);
            border: none;
            border-radius: 50%;
            color: var(--white);
            cursor: pointer;
            transition: var(--transition);
        }

        .newsletter-form button:hover {
            transform: scale(1.1);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            color: var(--text-light);
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .hero-title { font-size: 56px; }
            .section-title { font-size: 40px; }
            .features-grid,
            .testimonials-grid { grid-template-columns: repeat(2, 1fr); }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .footer-content { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 992px) {
            .nav-menu { display: none; }
            .menu-toggle { display: flex; }
            .hero-title { font-size: 48px; }
            .hero-stats { flex-wrap: wrap; gap: 30px; }
            .container { padding: 0 20px; }
        }

        @media (max-width: 768px) {
            .hero-title { font-size: 36px; }
            .section-title { font-size: 32px; }
            .features-grid,
            .testimonials-grid { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: 1fr; }
            .footer-content { grid-template-columns: 1fr; }
            .hero-buttons { flex-direction: column; align-items: center; }
            .cta-buttons { flex-direction: column; align-items: center; }
            .cursor, .cursor-follower { display: none; }
            body { cursor: auto; }
            .nav-actions .btn-login,
            .nav-actions .btn-primary { display: none; }
        }

        @media (max-width: 576px) {
            section { padding: 60px 0; }
            .hero-title { font-size: 28px; }
            .section-title { font-size: 28px; }
            .stat-value { font-size: 28px; }
            .model-price { font-size: 22px; }
        }
    </style>
</head>
<body>
    <!-- Preloader -->
    <div id="preloader">
        <div class="preloader-content">
            <div class="preloader-logo">M<span>R</span></div>
            <div class="preloader-spinner"></div>
            <p style="color: #6c3bd8;">Experience Luxury</p>
        </div>
    </div>

    <!-- Custom Cursor -->
    <div class="cursor"></div>
    <div class="cursor-follower"></div>

    <!-- Hero Section with Light Background -->
    <div class="hero-section">
        <div class="hero-bg-pattern"></div>

        <!-- Navigation -->
        <nav class="navbar" id="navbar">
            <div class="nav-container">
                <a href="#" class="logo">
                    <span class="logo-icon">M</span>
                    <span class="logo-text">o<span>To</span>R</span>
                </a>
                
                <div class="nav-menu">
                    <a href="#home" class="nav-link active">Home</a>
                    <a href="#features" class="nav-link">Features</a>
                    <a href="#models" class="nav-link">Models</a>
                    <a href="#reviews" class="nav-link">Reviews</a>
                    <a href="#about" class="nav-link">About</a>
                </div>
                
                <div class="nav-actions">
                    <a href="../../../pages/auth/login.php" class="btn-login">
                        <i class="fas fa-user-circle"></i>
                        <span>Sign In</span>
                    </a>
                    <a href="../../../pages/auth/login.php" class="btn-primary">
                        <span>Get Started</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    <div class="menu-toggle" id="menuToggle">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Mobile Menu -->
        <div class="mobile-menu" id="mobileMenu">
            <div class="mobile-menu-header">
                <div class="mobile-logo">M<span>R</span></div>
                <div class="mobile-menu-close" id="menuClose">
                    <i class="fas fa-times"></i>
                </div>
            </div>
            <div class="mobile-menu-links">
                <a href="#home" class="mobile-nav-link">Home</a>
                <a href="#features" class="mobile-nav-link">Features</a>
                <a href="#models" class="mobile-nav-link">Models</a>
                <a href="#reviews" class="mobile-nav-link">Reviews</a>
                <a href="#about" class="mobile-nav-link">About</a>
            </div>
            <div class="mobile-menu-footer">
                <a href="../../../pages/auth/login.php" class="mobile-btn-login">Sign In</a>
                <a href="../../../pages/auth/login.php" class="mobile-btn-primary">Get Started</a>
            </div>
        </div>

        <!-- Hero Content -->
        <section class="hero" id="home">
            <div class="hero-content">
                <div class="hero-badge" data-aos="fade-up" data-aos-delay="100">
                    <span class="badge-pulse"></span>
                    Limited Edition 2025
                </div>
                <h1 class="hero-title" data-aos="fade-up" data-aos-delay="200">
                    Upgrade Your<br>
                    <span class="gradient-text">Ride Today.</span>
                </h1>
                <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="300">
                    Experience the pinnacle of electric mobility with MoToR's latest innovation. 
                    Unmatched performance, timeless design, and sustainable power.
                </p>
                <div class="hero-buttons" data-aos="fade-up" data-aos-delay="400">
                    <a href="../../../pages/auth/login.php" class="btn-hero-primary">
                        <span>Start Your Journey</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="#features" class="btn-hero-secondary">
                        <i class="fas fa-play"></i>
                        <span>Explore Features</span>
                    </a>
                </div>
                <div class="hero-stats" data-aos="fade-up" data-aos-delay="500">
                    <div class="hero-stat">
                        <div class="stat-number">0-60</div>
                        <div class="stat-label">in 3.2 sec</div>
                    </div>
                    <div class="hero-stat">
                        <div class="stat-number">200+</div>
                        <div class="stat-label">km range</div>
                    </div>
                    <div class="hero-stat">
                        <div class="stat-number">4.5★</div>
                        <div class="stat-label">rating</div>
                    </div>
                </div>
            </div>
            
            <div class="scroll-indicator">
                <div class="scroll-mouse">
                    <div class="scroll-wheel"></div>
                </div>
                <div class="scroll-text">Scroll to explore</div>
            </div>
        </section>
    </div>

    <!-- Marquee Banner -->
    <div class="marquee">
        <div class="marquee-content">
            <span>✦ ELECTRIC PERFORMANCE</span>
            <span>✦ LUXURY DESIGN</span>
            <span>✦ SUSTAINABLE POWER</span>
            <span>✦ SMART TECHNOLOGY</span>
            <span>✦ PREMIUM QUALITY</span>
            <span>✦ LIMITED EDITION</span>
        </div>
        <div class="marquee-content">
            <span>✦ ELECTRIC PERFORMANCE</span>
            <span>✦ LUXURY DESIGN</span>
            <span>✦ SUSTAINABLE POWER</span>
            <span>✦ SMART TECHNOLOGY</span>
            <span>✦ PREMIUM QUALITY</span>
            <span>✦ LIMITED EDITION</span>
        </div>
    </div>

    <!-- Stats Section - Data dari Database -->
    <section class="stats-section" id="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-icon">
                        <i class="fas fa-motorcycle"></i>
                    </div>
                    <div class="stat-value"><?php echo number_format($stats['total_motor'] ?? 0); ?></div>
                    <div class="stat-label">Total Motor</div>
                </div>
                <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-value"><?php echo number_format($stats['tersedia'] ?? 0); ?></div>
                    <div class="stat-label">Tersedia</div>
                </div>
                <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-value"><?php echo number_format($stats['dipinjam'] ?? 0); ?></div>
                    <div class="stat-label">Sedang Dipinjam</div>
                </div>
                <div class="stat-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-value">1K+</div>
                    <div class="stat-label">Happy Riders</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="section-tag">Why Choose Us</span>
                <h2 class="section-title">Experience the <span class="gradient-text">Ultimate Ride</span></h2>
                <p class="section-subtitle">Discover the features that make MoToR the most advanced motorcycle rental service.</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3>Easy Booking</h3>
                    <p>Quick and seamless booking process with just a few clicks. Rent your dream motorcycle instantly.</p>
                    <div class="feature-link">Learn More <i class="fas fa-arrow-right"></i></div>
                </div>
                
                <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Full Insurance</h3>
                    <p>Every rental comes with comprehensive insurance coverage for your peace of mind.</p>
                    <div class="feature-link">Learn More <i class="fas fa-arrow-right"></i></div>
                </div>
                
                <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>24/7 Support</h3>
                    <p>Our dedicated support team is available around the clock to assist you.</p>
                    <div class="feature-link">Learn More <i class="fas fa-arrow-right"></i></div>
                </div>
                
                <div class="feature-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3>Free Delivery</h3>
                    <p>Free delivery and pickup service within the city area for all rentals.</p>
                    <div class="feature-link">Learn More <i class="fas fa-arrow-right"></i></div>
                </div>
                
                <div class="feature-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-icon">
                        <i class="fas fa-wrench"></i>
                    </div>
                    <h3>Well Maintained</h3>
                    <p>All motorcycles are regularly serviced and maintained to the highest standards.</p>
                    <div class="feature-link">Learn More <i class="fas fa-arrow-right"></i></div>
                </div>
                
                <div class="feature-card" data-aos="fade-up" data-aos-delay="600">
                    <div class="feature-icon">
                        <i class="fas fa-tag"></i>
                    </div>
                    <h3>Best Price</h3>
                    <p>Competitive pricing with special discounts for long-term rentals.</p>
                    <div class="feature-link">Learn More <i class="fas fa-arrow-right"></i></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Models Slider Section -->
    <section class="models-section" id="models">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="section-tag">Our Collection</span>
                <h2 class="section-title">Choose Your <span class="gradient-text">Perfect Ride</span></h2>
                <p class="section-subtitle">Discover our premium collection of well-maintained motorcycles.</p>
            </div>
            
            <div class="swiper models-slider" data-aos="fade-up" data-aos-delay="200">
                <div class="swiper-wrapper">
                    <?php if (!empty($motors)): ?>
                        <?php foreach ($motors as $motor): ?>
                            <div class="swiper-slide">
                                <div class="model-card">
                                    <div class="model-badge">
                                        <?php echo $motor['status'] == 'tersedia' ? 'Available' : 'Popular'; ?>
                                    </div>
                                    <div class="model-image">
                                        <img src="https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=400&h=300&fit=crop" alt="<?php echo htmlspecialchars($motor['nama_barang']); ?>">
                                    </div>
                                    <div class="model-info">
                                        <h3><?php echo htmlspecialchars($motor['nama_barang']); ?></h3>
                                        <p class="model-desc"><?php echo htmlspecialchars($motor['merk'] ?? 'Premium Motorcycle'); ?> • <?php echo $motor['tahun'] ?? '2024'; ?></p>
                                        <div class="model-specs">
                                            <span><i class="fas fa-tachometer-alt"></i> Ready</span>
                                            <span><i class="fas fa-gas-pump"></i> Full Tank</span>
                                            <span><i class="fas fa-shield-alt"></i> Insured</span>
                                        </div>
                                        <div class="model-price">Rp <?php echo number_format($motor['harga_sewa_perhari'], 0, ',', '.'); ?><span>/day</span></div>
                                        <a href="../../../pages/auth/login.php" class="btn-model">Rent Now <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="swiper-slide">
                            <div class="model-card">
                                <div class="model-image">
                                    <img src="https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=400&h=300&fit=crop" alt="Motorcycle">
                                </div>
                                <div class="model-info">
                                    <h3>Coming Soon</h3>
                                    <p class="model-desc">New models arriving soon</p>
                                    <div class="model-price">Contact Us<span></span></div>
                                    <a href="#" class="btn-model">Notify Me <i class="fas fa-bell"></i></a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section" id="reviews">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="section-tag">Testimonials</span>
                <h2 class="section-title">What Our <span class="gradient-text">Riders Say</span></h2>
                <p class="section-subtitle">Join thousands of satisfied customers who've experienced our service.</p>
            </div>
            
            <div class="testimonials-grid">
                <div class="testimonial-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="testimonial-quote">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <p class="testimonial-text">"Amazing service! The motorcycle was in perfect condition and the booking process was super easy. Will definitely rent again!"</p>
                    <div class="testimonial-author">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Author">
                        <div class="author-info">
                            <h4>Michael Chen</h4>
                            <div class="author-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="testimonial-quote">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <p class="testimonial-text">"Best rental experience I've ever had. The customer support is exceptional and the bikes are top-notch. Highly recommended!"</p>
                    <div class="testimonial-author">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Author">
                        <div class="author-info">
                            <h4>Sarah Johnson</h4>
                            <div class="author-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="testimonial-quote">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <p class="testimonial-text">"Professional, reliable, and affordable. The free delivery service saved me so much time. Will be my go-to rental service from now on!"</p>
                    <div class="testimonial-author">
                        <img src="https://randomuser.me/api/portraits/men/68.jpg" alt="Author">
                        <div class="author-info">
                            <h4>David Rodriguez</h4>
                            <div class="author-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content" data-aos="zoom-in">
                <h2>Ready to <span class="gradient-text">Start Your Journey?</span></h2>
                <p>Join the MoToR family today and experience the freedom of the open road.</p>
                <div class="cta-buttons">
                    <a href="../../../pages/auth/register.php" class="btn-cta-primary">
                        <span>Register Now</span>
                        <i class="fas fa-user-plus"></i>
                    </a>
                    <a href="../../../pages/auth/login.php" class="btn-cta-secondary">
                        <span>Sign In</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Partners Section -->
    <section class="partners-section">
        <div class="container">
            <p class="partners-title" data-aos="fade-up">Trusted by Industry Leaders</p>
            <div class="partners-grid" data-aos="fade-up" data-aos-delay="200">
                <div class="partner-logo">YAMAHA</div>
                <div class="partner-logo">HONDA</div>
                <div class="partner-logo">SUZUKI</div>
                <div class="partner-logo">KAWASAKI</div>
                <div class="partner-logo">VESPA</div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="about">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand" data-aos="fade-up">
                    <div class="footer-logo">M<span>R</span></div>
                    <p>Experience the thrill of the open road with MoToR's premium motorcycle rental service.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-links" data-aos="fade-up" data-aos-delay="100">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#models">Models</a></li>
                        <li><a href="#reviews">Reviews</a></li>
                    </ul>
                </div>
                <div class="footer-links" data-aos="fade-up" data-aos-delay="200">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Terms & Conditions</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>
                <div class="footer-newsletter" data-aos="fade-up" data-aos-delay="300">
                    <h4>Stay Updated</h4>
                    <p>Subscribe for exclusive offers and news</p>
                    <form class="newsletter-form" id="newsletterForm">
                        <input type="email" placeholder="Your email address" required>
                        <button type="submit"><i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 MoToR. All rights reserved. | Premium Motorcycle Rental Service</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100,
            easing: 'ease-out-cubic'
        });

        // Preloader
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            setTimeout(() => {
                preloader.classList.add('hide');
                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 500);
            }, 1500);
        });

        // Custom Cursor
        const cursor = document.querySelector('.cursor');
        const cursorFollower = document.querySelector('.cursor-follower');

        if (cursor && cursorFollower) {
            document.addEventListener('mousemove', (e) => {
                cursor.style.transform = `translate(${e.clientX - 4}px, ${e.clientY - 4}px)`;
                cursorFollower.style.transform = `translate(${e.clientX - 20}px, ${e.clientY - 20}px)`;
            });
            
            document.querySelectorAll('a, button, .feature-card, .stat-card, .model-card, .testimonial-card').forEach(el => {
                el.addEventListener('mouseenter', () => {
                    cursor.style.transform = 'scale(2)';
                    cursorFollower.style.transform = 'scale(1.5)';
                    cursorFollower.style.borderColor = 'rgba(108, 59, 216, 0.8)';
                });
                el.addEventListener('mouseleave', () => {
                    cursor.style.transform = 'scale(1)';
                    cursorFollower.style.transform = 'scale(1)';
                    cursorFollower.style.borderColor = 'rgba(108, 59, 216, 0.3)';
                });
            });
        }

        // Navbar Scroll
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Mobile Menu
        const menuToggle = document.getElementById('menuToggle');
        const menuClose = document.getElementById('menuClose');
        const mobileMenu = document.getElementById('mobileMenu');

        if (menuToggle) {
            menuToggle.addEventListener('click', () => {
                mobileMenu.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        }

        if (menuClose) {
            menuClose.addEventListener('click', () => {
                mobileMenu.classList.remove('active');
                document.body.style.overflow = '';
            });
        }

        document.querySelectorAll('.mobile-nav-link').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('active');
                document.body.style.overflow = '';
            });
        });

        // Active Nav Link on Scroll
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link');

        window.addEventListener('scroll', () => {
            let current = '';
            const scrollPosition = window.scrollY + 200;
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                    current = section.getAttribute('id');
                }
            });
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        });

        // Models Slider
        const modelsSlider = new Swiper('.models-slider', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                768: { slidesPerView: 2 },
                1024: { slidesPerView: 3 },
            },
        });

        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const offset = 80;
                    const targetPosition = target.offsetTop - offset;
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Newsletter Form
        const newsletterForm = document.getElementById('newsletterForm');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const email = newsletterForm.querySelector('input').value;
                if (email) {
                    alert(`Thank you for subscribing! We'll send updates to ${email}`);
                    newsletterForm.querySelector('input').value = '';
                }
            });
        }

        // Scroll Indicator Fade
        const scrollIndicator = document.querySelector('.scroll-indicator');
        window.addEventListener('scroll', () => {
            if (scrollIndicator) {
                if (window.scrollY > 100) {
                    scrollIndicator.style.opacity = '0';
                    scrollIndicator.style.pointerEvents = 'none';
                } else {
                    scrollIndicator.style.opacity = '1';
                    scrollIndicator.style.pointerEvents = 'auto';
                }
            }
        });
    </script>
</body>
</html>