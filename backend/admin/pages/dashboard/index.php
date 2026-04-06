<?php
// AKTIFKAN ERROR REPORTING
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pastikan session_start() di awal
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    // Redirect ke login dengan pesan
    header("Location: ../../../pages/auth/login.php?pesan=belum_login");
    exit();
}

// Cek role user (opsional, sesuaikan dengan kebutuhan)
//  if ($_SESSION['role'] !== 'admin') {
//       // Redirect jika bukan admin
//      header("Location: ../../../auth/pages/login.php");
//      exit();
//  }

// Include koneksi database
include '../../app.php';

// Query untuk mendapatkan statistik dashboard
$statistics = [];

// 1. Total User
$qTotalUser = "SELECT COUNT(*) as total FROM users";
$resultTotalUser = mysqli_query($connect, $qTotalUser);
$statistics['total_users'] = mysqli_fetch_assoc($resultTotalUser)['total'];

// 2. Total Barang
$qTotalBarang = "SELECT COUNT(*) as total FROM barang";
$resultTotalBarang = mysqli_query($connect, $qTotalBarang);
$statistics['total_barang'] = mysqli_fetch_assoc($resultTotalBarang)['total'];

// 3. Total Peminjaman
$qTotalPeminjaman = "SELECT COUNT(*) as total FROM peminjaman";
$resultTotalPeminjaman = mysqli_query($connect, $qTotalPeminjaman);
$statistics['total_peminjaman'] = mysqli_fetch_assoc($resultTotalPeminjaman)['total'];

// 4. Total Pengembalian
$qTotalPengembalian = "SELECT COUNT(*) as total FROM pengembalian";
$resultTotalPengembalian = mysqli_query($connect, $qTotalPengembalian);
$statistics['total_pengembalian'] = mysqli_fetch_assoc($resultTotalPengembalian)['total'];

// 5. Total Peminjaman Aktif (dipinjam)
$qPeminjamanAktif = "SELECT COUNT(*) as total FROM peminjaman WHERE status = 'dipinjam'";
$resultPeminjamanAktif = mysqli_query($connect, $qPeminjamanAktif);
$statistics['peminjaman_aktif'] = mysqli_fetch_assoc($resultPeminjamanAktif)['total'];

// 6. Total Peminjaman Pending
$qPeminjamanPending = "SELECT COUNT(*) as total FROM peminjaman WHERE status = 'pending'";
$resultPeminjamanPending = mysqli_query($connect, $qPeminjamanPending);
$statistics['peminjaman_pending'] = mysqli_fetch_assoc($resultPeminjamanPending)['total'];

// 7. Total Barang Tersedia
$qBarangTersedia = "SELECT COUNT(*) as total FROM barang WHERE status = 'tersedia'";
$resultBarangTersedia = mysqli_query($connect, $qBarangTersedia);
$statistics['barang_tersedia'] = mysqli_fetch_assoc($resultBarangTersedia)['total'];

// 8. Total Barang Dipinjam
$qBarangDipinjam = "SELECT COUNT(*) as total FROM barang WHERE status = 'dipinjam'";
$resultBarangDipinjam = mysqli_query($connect, $qBarangDipinjam);
$statistics['barang_dipinjam'] = mysqli_fetch_assoc($resultBarangDipinjam)['total'];

// 9. Total Pendapatan (dari peminjaman selesai)
$qTotalPendapatan = "SELECT SUM(total_harga) as total FROM peminjaman WHERE status = 'selesai'";
$resultTotalPendapatan = mysqli_query($connect, $qTotalPendapatan);
$statistics['total_pendapatan'] = mysqli_fetch_assoc($resultTotalPendapatan)['total'] ?? 0;

// 10. Total Denda
$qTotalDenda = "SELECT SUM(denda) as total FROM pengembalian";
$resultTotalDenda = mysqli_query($connect, $qTotalDenda);
$statistics['total_denda'] = mysqli_fetch_assoc($resultTotalDenda)['total'] ?? 0;

// Query untuk data terbaru
$qPeminjamanTerbaru = "SELECT p.*, u.username, b.nama_barang 
                       FROM peminjaman p 
                       LEFT JOIN users u ON p.user_id = u.id 
                       LEFT JOIN barang b ON p.barang_id = b.id 
                       ORDER BY p.created_at DESC LIMIT 5";
$resultPeminjamanTerbaru = mysqli_query($connect, $qPeminjamanTerbaru);

$qBarangTerbaru = "SELECT * FROM barang ORDER BY created_at DESC LIMIT 5";
$resultBarangTerbaru = mysqli_query($connect, $qBarangTerbaru);

$qUserTerbaru = "SELECT * FROM users ORDER BY created_at DESC LIMIT 5";
$resultUserTerbaru = mysqli_query($connect, $qUserTerbaru);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Peminjaman Alat Berat</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- ApexCharts CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.35.0/dist/apexcharts.css">
    
    <style>
        /* ===== GLOBAL STYLING ===== */
        body {
            background-color: #f8f9fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* Sidebar adjustment */
        #main {
            margin-left: 260px;
            margin-top: 70px;
            padding: 25px;
            width: calc(100% - 260px);
            min-height: calc(100vh - 70px);
            transition: all 0.3s ease;
            background-color: #f8f9fc;
        }

        @media (max-width: 768px) {
            #main {
                margin-left: 0;
                width: 100%;
                padding: 15px;
            }
        }

        /* Welcome Header */
        .welcome-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 30px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .welcome-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 30px 30px;
            opacity: 0.3;
        }

        .welcome-header h1 {
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 2.2rem;
        }

        .welcome-header p {
            opacity: 0.9;
            margin-bottom: 0;
            font-size: 1.1rem;
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            height: 100%;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e3e6f0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
        }

        .stats-card.users::before {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stats-card.barang::before {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        .stats-card.peminjaman::before {
            background: linear-gradient(135deg, #ff5858 0%, #f09819 100%);
        }

        .stats-card.pendapatan::before {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 1.8rem;
            color: white;
        }

        .stats-card.users .stats-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stats-card.barang .stats-icon {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        .stats-card.peminjaman .stats-icon {
            background: linear-gradient(135deg, #ff5858 0%, #f09819 100%);
        }

        .stats-card.pendapatan .stats-icon {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .stats-content h3 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: #2d3748;
        }

        .stats-content p {
            color: #718096;
            margin-bottom: 15px;
            font-size: 0.95rem;
        }

        .stats-trend {
            font-size: 0.85rem;
            font-weight: 600;
        }

        .trend-up {
            color: #38a169;
        }

        .trend-down {
            color: #e53e3e;
        }

        /* Chart Container */
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e3e6f0;
            height: 100%;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .chart-header h3 {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
        }

        /* Recent Activity Cards */
        .activity-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e3e6f0;
            height: 100%;
        }

        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .activity-header h3 {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f0f2f5;
            transition: all 0.2s ease;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-item:hover {
            background: #f8f9ff;
            border-radius: 10px;
            padding: 15px;
            margin: 0 -15px;
        }

        .activity-icon {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.2rem;
            color: white;
        }

        .activity-icon.user {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .activity-icon.barang {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        .activity-icon.peminjaman {
            background: linear-gradient(135deg, #ff5858 0%, #f09819 100%);
        }

        .activity-icon.pengembalian {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .activity-content h6 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 3px;
            color: #2d3748;
        }

        .activity-content p {
            font-size: 0.85rem;
            color: #718096;
            margin-bottom: 0;
        }

        .activity-time {
            font-size: 0.8rem;
            color: #a0aec0;
            text-align: right;
        }

        /* Status Badges */
        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-dipinjam {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-selesai {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-ditolak {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-tersedia {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-dipinjam-barang {
            background: #dbeafe;
            color: #1e40af;
        }

        /* Quick Actions */
        .quick-actions {
            margin-top: 30px;
        }

        .action-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e3e6f0;
            transition: all 0.3s ease;
            height: 100%;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .action-icon {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            color: white;
        }

        .action-icon.user {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .action-icon.barang {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        .action-icon.peminjaman {
            background: linear-gradient(135deg, #ff5858 0%, #f09819 100%);
        }

        .action-icon.laporan {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .action-card h4 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #2d3748;
        }

        .action-card p {
            color: #718096;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        .btn-action {
            background: transparent;
            border: 2px solid;
            border-radius: 10px;
            padding: 8px 20px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-action.user {
            color: #667eea;
            border-color: #667eea;
        }

        .btn-action.barang {
            color: #43e97b;
            border-color: #43e97b;
        }

        .btn-action.peminjaman {
            color: #ff5858;
            border-color: #ff5858;
        }

        .btn-action.laporan {
            color: #4facfe;
            border-color: #4facfe;
        }

        .btn-action:hover {
            color: white;
            transform: translateY(-2px);
        }

        .btn-action.user:hover {
            background: #667eea;
        }

        .btn-action.barang:hover {
            background: #43e97b;
        }

        .btn-action.peminjaman:hover {
            background: #ff5858;
        }

        .btn-action.laporan:hover {
            background: #4facfe;
        }

        /* Footer */
        .footer-custom {
            margin-top: 40px;
            text-align: center;
            color: #6c757d;
            font-size: 0.9rem;
            padding: 20px;
            border-top: 1px solid #e3e6f0;
            background: white;
            border-radius: 15px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .welcome-header h1 {
                font-size: 1.8rem;
            }
            
            .stats-content h3 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .welcome-header {
                padding: 20px;
            }
            
            .stats-card,
            .chart-container,
            .activity-card,
            .action-card {
                margin-bottom: 20px;
            }
            
            .activity-item {
                flex-direction: column;
                text-align: center;
            }
            
            .activity-icon {
                margin-right: 0;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>

<?php 
include '../../partials/header.php'; 
$page = 'dashboard'; 
include '../../partials/sidebar.php'; 
?>

<div class="container-fluid">
    <div id="main">
        <!-- Welcome Header -->
        <div class="welcome-header">
            <h1>Selamat Datang, Administrator!</h1>
            <p>Sistem Manajemen Peminjaman Alat Berat - Dashboard Utama</p>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card users">
                    <div class="stats-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-content">
                        <h3><?php echo number_format($statistics['total_users']); ?></h3>
                        <p>Total Pengguna</p>
                        <div class="stats-trend trend-up">
                            <i class="fas fa-arrow-up me-1"></i>
                            <?php echo number_format($statistics['total_users']); ?> Terdaftar
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card barang">
                    <div class="stats-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="stats-content">
                        <h3><?php echo number_format($statistics['total_barang']); ?></h3>
                        <p>Total Alat Berat</p>
                        <div class="d-flex justify-content-between">
                            <span class="stats-trend trend-up">
                                <i class="fas fa-check-circle me-1"></i>
                                <?php echo number_format($statistics['barang_tersedia']); ?> Tersedia
                            </span>
                            <span class="stats-trend trend-down">
                                <i class="fas fa-tools me-1"></i>
                                <?php echo number_format($statistics['barang_dipinjam']); ?> Dipinjam
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card peminjaman">
                    <div class="stats-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div class="stats-content">
                        <h3><?php echo number_format($statistics['total_peminjaman']); ?></h3>
                        <p>Total Peminjaman</p>
                        <div class="d-flex justify-content-between">
                            <span class="stats-trend trend-up">
                                <i class="fas fa-clock me-1"></i>
                                <?php echo number_format($statistics['peminjaman_pending']); ?> Pending
                            </span>
                            <span class="stats-trend trend-down">
                                <i class="fas fa-sync-alt me-1"></i>
                                <?php echo number_format($statistics['peminjaman_aktif']); ?> Aktif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card pendapatan">
                    <div class="stats-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stats-content">
                        <h3>Rp <?php echo number_format($statistics['total_pendapatan'], 0, ',', '.'); ?></h3>
                        <p>Total Pendapatan</p>
                        <div class="stats-trend trend-up">
                            <i class="fas fa-chart-line me-1"></i>
                            Denda: Rp <?php echo number_format($statistics['total_denda'], 0, ',', '.'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Recent Activities -->
        <div class="row mb-4">
            <!-- Chart -->
            <div class="col-xl-8 mb-4">
                <div class="chart-container">
                    <div class="chart-header">
                        <h3>Statistik Peminjaman Bulanan</h3>
                    </div>
                    <div id="chart"></div>
                </div>
            </div>
            
            <!-- Recent Peminjaman -->
            <div class="col-xl-4 mb-4">
                <div class="activity-card">
                    <div class="activity-header">
                        <h3>Peminjaman Terbaru</h3>
                    </div>
                    <?php if (mysqli_num_rows($resultPeminjamanTerbaru) > 0): ?>
                        <?php while ($peminjaman = mysqli_fetch_assoc($resultPeminjamanTerbaru)): ?>
                        <div class="activity-item">
                            <div class="activity-icon peminjaman">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6><?php echo htmlspecialchars($peminjaman['nama_barang']); ?></h6>
                                <p><?php echo htmlspecialchars($peminjaman['username']); ?></p>
                            </div>
                            <div class="activity-time">
                                <span class="status-badge badge-<?php echo $peminjaman['status']; ?>">
                                    <?php echo ucfirst($peminjaman['status']); ?>
                                </span>
                                <div class="mt-2 small">
                                    <?php echo date('d M', strtotime($peminjaman['created_at'])); ?>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-handshake fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada data peminjaman</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Additional Recent Activities -->
        <div class="row">
            
            <!-- Recent Users -->
            <div class="col-xl-6 mb-4">
                <div class="activity-card">
                    <div class="activity-header">
                        <h3>User Terbaru</h3>
                    </div>
                    <?php if (mysqli_num_rows($resultUserTerbaru) > 0): ?>
                        <?php while ($user = mysqli_fetch_assoc($resultUserTerbaru)): ?>
                        <div class="activity-item">
                            <div class="activity-icon user">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6><?php echo htmlspecialchars($user['username']); ?></h6>
                                <p>ID: #<?php echo $user['id']; ?></p>
                            </div>
                            <div class="activity-time">
                                <span class="status-badge" style="background: <?php 
                                    echo $user['role'] == 'admin' ? '#f093fb' : 
                                           ($user['role'] == 'petugas' ? '#4facfe' : '#43e97b'); 
                                ?>; color: white;">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                                <div class="mt-2 small">
                                    <?php echo date('d M', strtotime($user['created_at'])); ?>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada data user</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-custom">
            <p class="mb-0">
                &copy; <?php echo date('Y'); ?> Sistem Peminjaman Alat Berat. 
                <span class="text-primary"><?php echo date('d F Y'); ?></span>
            </p>
        </div>
    </div>
</div>

<?php include '../../partials/script.php'; ?>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.35.0/dist/apexcharts.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Chart
    var options = {
        series: [{
            name: 'Total Peminjaman',
            data: [
                <?php
                // Sample data for chart (you should replace with actual data from database)
                $monthlyData = [4000, 3000, 5000, 4000, 6000, 5000, 7000, 6000, 8000, 7000, 9000, 8000];
                foreach ($monthlyData as $value) {
                    echo $value . ',';
                }
                ?>
            ]
        }],
        chart: {
            type: 'area',
            height: 350,
            toolbar: {
                show: true
            }
        },
        colors: ['#667eea'],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.3,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
        },
        yaxis: {
            title: {
                text: 'Jumlah Peminjaman'
            }
        },
        grid: {
            borderColor: '#f1f1f1',
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();

    // Animate stats cards on scroll
    function animateOnScroll() {
        $('.stats-card').each(function() {
            var elementTop = $(this).offset().top;
            var elementBottom = elementTop + $(this).outerHeight();
            var viewportTop = $(window).scrollTop();
            var viewportBottom = viewportTop + $(window).height();
            
            if (elementBottom > viewportTop && elementTop < viewportBottom) {
                $(this).addClass('animate__animated animate__fadeInUp');
            }
        });
    }

    // Initial check
    animateOnScroll();

    // Check on scroll
    $(window).on('scroll', function() {
        animateOnScroll();
    });

    // Auto-refresh page every 5 minutes (300000 ms)
    setTimeout(function() {
        location.reload();
    }, 300000);
});
</script>

</body>
</html>