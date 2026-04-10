<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include '../../partials/header.php';
$page = 'dashboard';
include '../../partials/sidebar.php';
include '../../app.php';

// =============================================
// STATISTIK DASHBOARD
// =============================================

// Total Motor
$q = mysqli_query($connect, "SELECT COUNT(*) as total FROM barang");
$total_motor = mysqli_fetch_assoc($q)['total'];

// Motor Tersedia
$q = mysqli_query($connect, "SELECT SUM(stok) as total FROM barang WHERE status = 'tersedia'");
$motor_tersedia = mysqli_fetch_assoc($q)['total'] ?? 0;

// Motor Dipinjam
$q = mysqli_query($connect, "SELECT SUM(stok - jumlah_tersedia) as total FROM barang WHERE status != 'rusak'");
$motor_dipinjam = mysqli_fetch_assoc($q)['total'] ?? 0;

// Motor Rusak
$q = mysqli_query($connect, "SELECT SUM(stok) as total FROM barang WHERE status = 'rusak'");
$motor_rusak = mysqli_fetch_assoc($q)['total'] ?? 0;

// Total Peminjaman
$q = mysqli_query($connect, "SELECT COUNT(*) as total FROM peminjaman");
$total_peminjaman = mysqli_fetch_assoc($q)['total'];

// Peminjaman Aktif
$q = mysqli_query($connect, "SELECT COUNT(*) as total FROM peminjaman WHERE status = 'dipinjam'");
$peminjaman_aktif = mysqli_fetch_assoc($q)['total'];

// Peminjaman Pending
$q = mysqli_query($connect, "SELECT COUNT(*) as total FROM peminjaman WHERE status = 'pending'");
$peminjaman_pending = mysqli_fetch_assoc($q)['total'];

// Peminjaman Selesai
$q = mysqli_query($connect, "SELECT COUNT(*) as total FROM peminjaman WHERE status = 'selesai'");
$peminjaman_selesai = mysqli_fetch_assoc($q)['total'];

// Total Pendapatan
$q = mysqli_query($connect, "SELECT SUM(total_harga) as total FROM peminjaman WHERE status = 'selesai'");
$total_pendapatan = mysqli_fetch_assoc($q)['total'] ?? 0;

// Total User
$q = mysqli_query($connect, "SELECT COUNT(*) as total FROM users");
$total_users = mysqli_fetch_assoc($q)['total'];

// Peminjaman Terbaru
$q_peminjaman = mysqli_query($connect, "SELECT p.*, u.username, b.nama_barang 
    FROM peminjaman p 
    LEFT JOIN users u ON p.user_id = u.id 
    LEFT JOIN barang b ON p.barang_id = b.id 
    ORDER BY p.created_at DESC LIMIT 5");

// Motor Terbaru
$q_motor = mysqli_query($connect, "SELECT b.*, k.nama_kategori 
    FROM barang b
    LEFT JOIN kategori k ON b.kategori_id = k.id 
    ORDER BY b.created_at DESC LIMIT 5");
?>

<link rel="stylesheet" href="style.css">

<div id="main">
    <!-- Welcome Header -->
    <div class="welcome-card">
        <h2><i class="fas fa-motorcycle me-2"></i> Selamat Datang, <?= htmlspecialchars($_SESSION['username'] ?? 'Administrator') ?>!</h2>
        <p>Sistem Manajemen Peminjaman Motor - Pantau aktivitas dan statistik di dashboard ini</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-icon primary">
                <i class="fas fa-motorcycle"></i>
            </div>
            <div class="stat-value"><?= $total_motor ?></div>
            <div class="stat-label">Total Motor</div>
            <div class="stat-sub">Terdaftar di sistem</div>
        </div>
        
        <div class="stat-card success">
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value"><?= $motor_tersedia ?></div>
            <div class="stat-label">Motor Tersedia</div>
            <div class="stat-sub">Siap dipinjam</div>
        </div>
        
        <div class="stat-card warning">
            <div class="stat-icon warning">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value"><?= $motor_dipinjam ?></div>
            <div class="stat-label">Sedang Dipinjam</div>
            <div class="stat-sub">Unit sedang digunakan</div>
        </div>
        
        <div class="stat-card info">
            <div class="stat-icon info">
                <i class="fas fa-tools"></i>
            </div>
            <div class="stat-value"><?= $motor_rusak ?></div>
            <div class="stat-label">Motor Rusak</div>
            <div class="stat-sub">Perlu perbaikan</div>
        </div>
    </div>

    <!-- Second Row Stats -->
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-icon primary">
                <i class="fas fa-handshake"></i>
            </div>
            <div class="stat-value"><?= $total_peminjaman ?></div>
            <div class="stat-label">Total Peminjaman</div>
            <div class="stat-sub">Semua transaksi</div>
        </div>
        
        <div class="stat-card warning">
            <div class="stat-icon warning">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="stat-value"><?= $peminjaman_pending ?></div>
            <div class="stat-label">Pending</div>
            <div class="stat-sub">Menunggu approval</div>
        </div>
        
        <div class="stat-card info">
            <div class="stat-icon info">
                <i class="fas fa-sync-alt"></i>
            </div>
            <div class="stat-value"><?= $peminjaman_aktif ?></div>
            <div class="stat-label">Aktif</div>
            <div class="stat-sub">Sedang berlangsung</div>
        </div>
        
        <div class="stat-card success">
            <div class="stat-icon success">
                <i class="fas fa-check-double"></i>
            </div>
            <div class="stat-value"><?= $peminjaman_selesai ?></div>
            <div class="stat-label">Selesai</div>
            <div class="stat-sub">Transaksi selesai</div>
        </div>
    </div>

    <!-- Third Row - Revenue & Users -->
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-icon primary">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-value">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></div>
            <div class="stat-label">Total Pendapatan</div>
            <div class="stat-sub">Dari peminjaman selesai</div>
        </div>
        
        <div class="stat-card info">
            <div class="stat-icon info">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-value"><?= $total_users ?></div>
            <div class="stat-label">Total Pengguna</div>
            <div class="stat-sub">Terdaftar di sistem</div>
        </div>
        
        <div class="stat-card success">
            <div class="stat-icon success">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-value"><?= round(($peminjaman_selesai / max($total_peminjaman, 1)) * 100) ?>%</div>
            <div class="stat-label">Tingkat Selesai</div>
            <div class="stat-sub">Dari total peminjaman</div>
        </div>
        
        <div class="stat-card warning">
            <div class="stat-icon warning">
                <i class="fas fa-percent"></i>
            </div>
            <div class="stat-value"><?= round(($motor_tersedia / max($total_motor, 1)) * 100) ?>%</div>
            <div class="stat-label">Ketersediaan</div>
            <div class="stat-sub">Motor siap pakai</div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row g-3">
        <!-- Recent Peminjaman -->
        <div class="col-md-6">
            <div class="section-card">
                <div class="section-header">
                    <h3><i class="fas fa-handshake"></i> Peminjaman Terbaru</h3>
                    <a href="../peminjaman/index.php" class="btn-link-custom">Lihat semua <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
                <div class="section-body">
                    <?php if (mysqli_num_rows($q_peminjaman) > 0): ?>
                    <table class="table-dashboard">
                        <thead>
                            <tr><th>Motor</th><th>Peminjam</th><th>Status</th><th>Tgl Pinjam</th></tr>
                        </thead>
                        <tbody>
                            <?php while($p = mysqli_fetch_assoc($q_peminjaman)): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($p['nama_barang'] ?? '-') ?></strong></td>
                                <td><?= htmlspecialchars($p['username']) ?></td>
                                <td><span class="status-badge status-<?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span></td>
                                <td><?= date('d/m/Y', strtotime($p['tgl_pinjam'])) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <div class="text-center py-4"><i class="fas fa-handshake fa-2x text-muted mb-2"></i><p class="mb-0">Belum ada peminjaman</p></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Recent Motor -->
        <div class="col-md-6">
            <div class="section-card">
                <div class="section-header">
                    <h3><i class="fas fa-motorcycle"></i> Motor Terbaru</h3>
                    <a href="../motor/index.php" class="btn-link-custom">Lihat semua <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
                <div class="section-body">
                    <?php if (mysqli_num_rows($q_motor) > 0): ?>
                    <table class="table-dashboard">
                        <thead>
                            <tr><th>Kode</th><th>Nama Motor</th><th>Kategori</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            <?php while($m = mysqli_fetch_assoc($q_motor)): ?>
                            <tr>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($m['kode_barang']) ?></span></td>
                                <td><strong><?= htmlspecialchars($m['nama_barang']) ?></strong></td>
                                <td><?= htmlspecialchars($m['nama_kategori'] ?? '-') ?></td>
                                <td><span class="status-badge status-<?= $m['status'] ?>"><?= ucfirst($m['status']) ?></span></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <div class="text-center py-4"><i class="fas fa-motorcycle fa-2x text-muted mb-2"></i><p class="mb-0">Belum ada motor</p></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../partials/footer.php'; include '../../partials/script.php'; ?>