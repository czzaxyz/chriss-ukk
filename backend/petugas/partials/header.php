<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
        rel="apple-touch-icon"
        sizes="76x76"
        href="../../../templates-admin/build/assets/img/apple-icon.png" />
    <link rel="icon" type="image/png" href="../../../templates-admin/build/assets/img/favicon.png" />
    <title>Sistem Peminjaman Motor</title>
    <!--     Fonts and icons     -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700"
        rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script
        src="https://kit.fontawesome.com/42d5adcbca.js"
        crossorigin="anonymous"></script>
    <!-- Nucleo Icons -->
    <link href="../../../templates-admin/build/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../../../templates-admin/build/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <!-- Main Styling -->
    <link
        href="../../../templates-admin/build/assets/css/soft-ui-dashboard-tailwind.css?v=1.0.5"
        rel="stylesheet" />
    <!-- Nepcha Analytics (nepcha.com) -->
    <script
        defer
        data-site="YOUR_DOMAIN_HERE"
        src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
    
    <!-- Loading Screen CSS -->
    
</head>

<body class="m-0 font-sans text-base antialiased font-normal leading-default bg-gray-50 text-slate-500">
    <?php
    // Tidak boleh ada spasi atau baris kosong sebelum ini!
    // Pastikan file disimpan dengan encoding UTF-8 tanpa BOM

    // Cek session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Cek login
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
        header("Location: ../../../pages/auth/login.php?pesan=belum_login");
        exit();
    }
    ?>
    <style>
        /* Loading Screen Overlay - BACKGROUND BIRU */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #8d0076 0%, #f700ff 50%, #b700ff 100%);
            z-index: 99999;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        .loading-overlay.hide {
            opacity: 0;
            visibility: hidden;
        }

        .loader-container {
            text-align: center;
            transform: translateY(-20px);
        }

        /* Spinner */
        .loader-spinner {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            position: relative;
        }

        .loader-spinner .circle {
            width: 80px;
            height: 80px;
            border: 4px solid rgba(255, 255, 255, 0.2);
            border-top: 4px solid white;
            border-right: 4px solid white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        /* Motor Icon Animation */
        .loader-motor {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 30px;
            color: white;
            animation: bounce 0.8s ease infinite;
        }

        /* Loading Text */
        .loader-text {
            color: white;
            font-size: 1.2rem;
            font-weight: 600;
            margin-top: 20px;
            letter-spacing: 2px;
            font-family: 'Open Sans', sans-serif;
        }

        .loader-dots span {
            animation: dotPulse 1.5s ease infinite;
            opacity: 0;
        }

        .loader-dots span:nth-child(1) {
            animation-delay: 0s;
        }

        .loader-dots span:nth-child(2) {
            animation-delay: 0.3s;
        }

        .loader-dots span:nth-child(3) {
            animation-delay: 0.6s;
        }

        /* Progress Bar - 0.7 detik */
        .loader-progress {
            width: 200px;
            height: 3px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            margin: 20px auto 0;
            overflow: hidden;
        }

        .loader-progress-bar {
            width: 0%;
            height: 100%;
            background: white;
            border-radius: 10px;
            animation: progress 0.7s ease-out forwards;
        }

        /* Animations */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translate(-50%, -50%) scale(1);
            }

            50% {
                transform: translate(-50%, -50%) scale(1.2);
            }
        }

        @keyframes dotPulse {

            0%,
            100% {
                opacity: 0;
            }

            50% {
                opacity: 1;
            }
        }

        @keyframes progress {
            0% {
                width: 0%;
            }

            100% {
                width: 100%;
            }
        }

        /* Fade In Content */
        .content-wrapper {
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .content-wrapper.show {
            opacity: 1;
        }
    </style>

    <body class="m-0 font-sans text-base antialiased font-normal leading-default bg-gray-50 text-slate-500">

        <!-- Loading Screen -->
        <div id="loadingOverlay" class="loading-overlay">
            <div class="loader-container">
                <div class="loader-spinner">
                    <div class="circle"></div>
                    <div class="loader-motor">
                        <i class="fas fa-motorcycle"></i>
                    </div>
                </div>
                <div class="loader-text">
                    Memuat Sistem Peminjaman Motor
                    <span class="loader-dots">
                        <span>.</span><span>.</span><span>.</span>
                    </span>
                </div>
                <div class="loader-progress">
                    <div class="loader-progress-bar"></div>
                </div>
            </div>
        </div>

        <!-- Content Wrapper -->
        <div id="contentWrapper" class="content-wrapper">

            <script>
                // Loading screen 0.7 detik
                document.addEventListener('DOMContentLoaded', function() {
                    // Tunggu 0.7 detik, lalu sembunyikan loading screen
                    setTimeout(function() {
                        var loadingOverlay = document.getElementById('loadingOverlay');
                        if (loadingOverlay) {
                            loadingOverlay.classList.add('hide');

                            // Tampilkan konten
                            var contentWrapper = document.getElementById('contentWrapper');
                            if (contentWrapper) {
                                contentWrapper.classList.add('show');
                            }

                            // Hapus loading overlay dari DOM setelah animasi selesai
                            setTimeout(function() {
                                loadingOverlay.style.display = 'none';
                            }, 500);
                        }
                    }, 700); // 0.7 detik = 700 milidetik
                });
            </script>

            <!-- Catatan: Semua konten halaman akan dimasukkan di sini -->
            <!-- Pastikan untuk menutup div contentWrapper di footer.php -->
            <!-- END PRELOADER -->

<!-- Catatan: Semua konten halaman akan dimasukkan di sini -->
<!-- Pastikan untuk menutup div contentWrapper di footer.php -->