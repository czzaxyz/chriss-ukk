<?php
// Tidak boleh ada spasi atau baris kosong sebelum ini!
// Pastikan file disimpan dengan encoding UTF-8 tanpa BOM

// Cek session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("Location: ../../pages/auth/login.php?pesan=belum_login");
    exit();
}
?>
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
    <title>Website Peminjaman Motor</title>
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