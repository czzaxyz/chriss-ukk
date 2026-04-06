<?php
// AKTIFKAN ERROR REPORTING
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pastikan session_start() di awal
session_start();

// Include koneksi database
include '../../app.php';

// Tanggal filter default (bulan ini)
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
$tipe_laporan = isset($_GET['tipe_laporan']) ? $_GET['tipe_laporan'] : 'peminjaman';

// Query berdasarkan tipe laporan
$reports = [];
$total_data = 0;

switch($tipe_laporan) {
    case 'peminjaman':
        // Laporan Peminjaman
        $query = "SELECT 
                    p.*,
                    u.username as nama_peminjam,
                    b.nama_barang,
                    b.harga_sewa_perhari,
                    k.nama_kategori
                FROM peminjaman p
                LEFT JOIN users u ON p.user_id = u.id
                LEFT JOIN barang b ON p.barang_id = b.id
                LEFT JOIN kategori k ON b.kategori_id = k.id
                WHERE DATE(p.created_at) BETWEEN '$start_date' AND '$end_date'
                ORDER BY p.created_at DESC";
        
        $result = mysqli_query($connect, $query);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $reports[] = $row;
            }
            $total_data = count($reports);
        }
        break;
        
    case 'pengembalian':
        // Laporan Pengembalian
        $query = "SELECT 
                    pg.*,
                    p.kode_peminjaman,
                    u.username as nama_peminjam,
                    b.nama_barang,
                    b.harga_sewa_perhari
                FROM pengembalian pg
                LEFT JOIN peminjaman p ON pg.peminjaman_id = p.id
                LEFT JOIN users u ON p.user_id = u.id
                LEFT JOIN barang b ON p.barang_id = b.id
                WHERE DATE(pg.created_at) BETWEEN '$start_date' AND '$end_date'
                ORDER BY pg.created_at DESC";
        
        $result = mysqli_query($connect, $query);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $reports[] = $row;
            }
            $total_data = count($reports);
        }
        break;
        
    case 'log_aktivitas':
        // Laporan Log Aktivitas
        $query = "SELECT 
                    la.*,
                    u.username
                FROM log_aktivitas la
                LEFT JOIN users u ON la.user_id = u.id
                WHERE DATE(la.created_at) BETWEEN '$start_date' AND '$end_date'
                ORDER BY la.created_at DESC";
        
        $result = mysqli_query($connect, $query);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $reports[] = $row;
            }
            $total_data = count($reports);
        }
        break;
        
    case 'kategori':
        // Laporan Kategori
        $query = "SELECT 
                    k.*,
                    COUNT(b.id) as total_barang
                FROM kategori k
                LEFT JOIN barang b ON k.id = b.kategori_id
                GROUP BY k.id
                ORDER BY k.created_at DESC";
        
        $result = mysqli_query($connect, $query);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $reports[] = $row;
            }
            $total_data = count($reports);
        }
        break;
}

// Hitung total pendapatan dan denda untuk periode ini - PERBAIKAN DI SINI
$qPendapatan = "SELECT COALESCE(SUM(total_harga), 0) as total_pendapatan 
                FROM peminjaman 
                WHERE status = 'selesai' 
                AND DATE(created_at) BETWEEN '$start_date' AND '$end_date'";
$resultPendapatan = mysqli_query($connect, $qPendapatan);
if ($resultPendapatan) {
    $row = mysqli_fetch_assoc($resultPendapatan);
    $total_pendapatan = $row['total_pendapatan'] ?? 0;
} else {
    $total_pendapatan = 0;
}

$qDenda = "SELECT COALESCE(SUM(denda), 0) as total_denda 
           FROM pengembalian 
           WHERE DATE(created_at) BETWEEN '$start_date' AND '$end_date'";
$resultDenda = mysqli_query($connect, $qDenda);
if ($resultDenda) {
    $row = mysqli_fetch_assoc($resultDenda);
    $total_denda = $row['total_denda'] ?? 0;
} else {
    $total_denda = 0;
}

// Fungsi untuk format angka dengan aman
function safeNumberFormat($number, $decimals = 0, $decimal_separator = ',', $thousands_separator = '.') {
    if ($number === null || $number === '') {
        return '0';
    }
    
    // Pastikan $number adalah numerik
    $number = (float) $number;
    
    return number_format($number, $decimals, $decimal_separator, $thousands_separator);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Sistem - Sistem Peminjaman Alat Berat</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    
    <style>
        /* ===== GLOBAL STYLING ===== */
        body {
            background-color: #f8f9fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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

        /* Card styling */
        .main-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e3e6f0;
            overflow: hidden;
        }

        .card-header-custom {
            background: #ffffff;
            border-bottom: 2px solid #f0f2f5;
            padding: 20px 30px;
        }

        .card-body-custom {
            padding: 30px;
        }

        /* Header styling */
        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #4e73df;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-title i {
            font-size: 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Filter Section */
        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e3e6f0;
        }

        /* Summary Cards */
        .summary-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 20px;
            color: white;
            margin-bottom: 20px;
        }

        .summary-card .icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .summary-card h4 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .summary-card .value {
            font-size: 1.8rem;
            font-weight: 700;
        }

        /* Report Table */
        .report-table-container {
            background: white;
            border-radius: 12px;
            padding: 0;
            margin-top: 25px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e3e6f0;
            overflow: hidden;
        }

        .table-header {
            background: #f8f9ff;
            padding: 15px 25px;
            border-bottom: 1px solid #e3e6f0;
        }

        .table-header h5 {
            margin: 0;
            color: #4e73df;
            font-weight: 600;
        }

        /* Table styling */
        .dataTable thead th {
            background: #f8f9ff;
            color: #4e73df;
            border-bottom: 2px solid #e3e6f0;
            padding: 15px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .dataTable tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f2f5;
            vertical-align: middle;
        }

        .dataTable tbody tr:hover {
            background-color: #f8f9ff;
        }

        /* Status badges */
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

        .badge-terlambat {
            background: #fed7aa;
            color: #9a3412;
        }

        /* Print-specific styles */
        @media print {
            body {
                background: white !important;
                color: black !important;
            }
            
            #main {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
            }
            
            .main-card, .filter-card, .report-table-container {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            
            .no-print {
                display: none !important;
            }
            
            .table-header {
                background: #f5f5f5 !important;
            }
            
            .dataTable thead th {
                background: #f5f5f5 !important;
                color: black !important;
                border-bottom: 2px solid #ddd !important;
            }
            
            .summary-card {
                background: #f5f5f5 !important;
                color: black !important;
                border: 1px solid #ddd !important;
            }
            
            .summary-card .icon {
                background: #e0e0e0 !important;
                color: black !important;
            }
            
            .page-title {
                color: black !important;
            }
            
            .page-title i {
                -webkit-text-fill-color: black !important;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card-header-custom {
                flex-direction: column;
                gap: 15px;
                padding: 15px;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
            
            .filter-card .row {
                margin-bottom: 10px;
            }
            
            .filter-card .col-md-3 {
                margin-bottom: 15px;
            }
            
            .dataTable {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

<?php 
include '../../partials/header.php'; 
$page = 'laporan'; 
include '../../partials/sidebar.php'; 
?>

<div class="container-fluid">
    <div id="main">
        <!-- Main Card -->
        <div class="main-card">
            <!-- Card Header -->
            <div class="card-header-custom d-flex align-items-center justify-content-between">
                <h2 class="page-title">
                    <i class="fas fa-chart-bar"></i>
                    Sistem Laporan
                </h2>
            </div>
            
            <!-- Card Body -->
            <div class="card-body-custom">
                <!-- Filter Section -->
                <div class="filter-card no-print">
                    <form method="GET" action="" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Tipe Laporan</label>
                            <select name="tipe_laporan" class="form-select" onchange="this.form.submit()">
                                <option value="peminjaman" <?= $tipe_laporan == 'peminjaman' ? 'selected' : '' ?>>Laporan Peminjaman</option>
                                <option value="pengembalian" <?= $tipe_laporan == 'pengembalian' ? 'selected' : '' ?>>Laporan Pengembalian</option>
                                <option value="log_aktivitas" <?= $tipe_laporan == 'log_aktivitas' ? 'selected' : '' ?>>Laporan Log Aktivitas</option>
                                <option value="kategori" <?= $tipe_laporan == 'kategori' ? 'selected' : '' ?>>Laporan Kategori</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" value="<?= $start_date ?>" max="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control" value="<?= $end_date ?>" max="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter me-2"></i> Filter
                                </button>
                                <a href="?" class="btn btn-outline-secondary">
                                    <i class="fas fa-sync-alt"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Summary Section -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="summary-card">
                            <div class="icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h4>Total Data</h4>
                            <div class="value"><?= safeNumberFormat($total_data) ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="summary-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                            <div class="icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <h4>Total Pendapatan</h4>
                            <div class="value">Rp <?= safeNumberFormat($total_pendapatan, 0, ',', '.') ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="summary-card" style="background: linear-gradient(135deg, #ff5858 0%, #f09819 100%);">
                            <div class="icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h4>Total Denda</h4>
                            <div class="value">Rp <?= safeNumberFormat($total_denda, 0, ',', '.') ?></div>
                        </div>
                    </div>
                </div>
                
                <!-- Report Header for Print -->
                <div class="text-center mb-4 d-none d-print-block">
                    <h3>LAPORAN <?= strtoupper(str_replace('_', ' ', $tipe_laporan)) ?></h3>
                    <p>Periode: <?= date('d F Y', strtotime($start_date)) ?> - <?= date('d F Y', strtotime($end_date)) ?></p>
                    <p>Dicetak pada: <?= date('d F Y H:i:s') ?></p>
                </div>
                
                <!-- Report Table -->
                <div class="report-table-container">
                    <div class="table-header">
                        <h5>
                            <i class="fas fa-list me-2"></i>
                            <?php
                            $report_titles = [
                                'peminjaman' => 'Laporan Data Peminjaman',
                                'pengembalian' => 'Laporan Data Pengembalian',
                                'log_aktivitas' => 'Laporan Log Aktivitas',
                                'kategori' => 'Laporan Data Kategori'
                            ];
                            echo $report_titles[$tipe_laporan];
                            ?>
                        </h5>
                    </div>
                    
                    <div class="table-responsive">
                        <table id="reportTable" class="table table-hover">
                            <thead>
                                <?php if($tipe_laporan == 'peminjaman'): ?>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Peminjam</th>
                                    <th>Barang</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Status</th>
                                    <th>Total Harga</th>
                                </tr>
                                <?php elseif($tipe_laporan == 'pengembalian'): ?>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Peminjaman</th>
                                    <th>Peminjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Kondisi</th>
                                    <th>Denda</th>
                                    <th>Keterangan</th>
                                </tr>
                                <?php elseif($tipe_laporan == 'log_aktivitas'): ?>
                                <tr>
                                    <th>No</th>
                                    <th>User</th>
                                    <th>Aksi</th>
                                    <th>Deskripsi</th>
                                    <th>IP Address</th>
                                    <th>Waktu</th>
                                </tr>
                                <?php elseif($tipe_laporan == 'kategori'): ?>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kategori</th>
                                    <th>Deskripsi</th>
                                    <th>Total Barang</th>
                                    <th>Tanggal Dibuat</th>
                                </tr>
                                <?php endif; ?>
                            </thead>
                            <tbody>
                                <?php if($total_data > 0): ?>
                                    <?php foreach($reports as $index => $row): ?>
                                    <tr>
                                        <?php if($tipe_laporan == 'peminjaman'): ?>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($row['kode_peminjaman']) ?></td>
                                        <td><?= htmlspecialchars($row['nama_peminjam']) ?></td>
                                        <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($row['tgl_pinjam'])) ?></td>
                                        <td>
                                            <?php
                                            $status = $row['status'];
                                            $statusClass = 'badge-' . $status;
                                            $statusText = ucfirst($status);
                                            echo '<span class="status-badge ' . $statusClass . '">' . $statusText . '</span>';
                                            ?>
                                        </td>
                                        <td>Rp <?= safeNumberFormat($row['total_harga'], 0, ',', '.') ?></td>
                                        
                                        <?php elseif($tipe_laporan == 'pengembalian'): ?>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($row['kode_peminjaman']) ?></td>
                                        <td><?= htmlspecialchars($row['nama_peminjam']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($row['tgl_kembali'])) ?></td>
                                        <td>
                                            <?php
                                            $kondisi = $row['kondisi'];
                                            $kondisiColor = $kondisi == 'baik' ? 'success' : ($kondisi == 'rusak_ringan' ? 'warning' : 'danger');
                                            echo '<span class="badge bg-' . $kondisiColor . '">' . ucfirst($kondisi) . '</span>';
                                            ?>
                                        </td>
                                        <td>Rp <?= safeNumberFormat($row['denda'], 0, ',', '.') ?></td>
                                        <td><?= htmlspecialchars(substr($row['keterangan'], 0, 50)) ?><?= strlen($row['keterangan']) > 50 ? '...' : '' ?></td>
                                        
                                        <?php elseif($tipe_laporan == 'log_aktivitas'): ?>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($row['username'] ?? 'Unknown') ?></td>
                                        <td><?= htmlspecialchars($row['aksi']) ?></td>
                                        <td><?= htmlspecialchars(substr($row['deskripsi'], 0, 50)) ?><?= strlen($row['deskripsi']) > 50 ? '...' : '' ?></td>
                                        <td><code><?= htmlspecialchars($row['ip_address']) ?></code></td>
                                        <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                                        
                                        <?php elseif($tipe_laporan == 'kategori'): ?>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                        <td><?= htmlspecialchars(substr($row['deskripsi'], 0, 100)) ?><?= strlen($row['deskripsi']) > 100 ? '...' : '' ?></td>
                                        <td><?= safeNumberFormat($row['total_barang']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-database fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">Tidak Ada Data</h5>
                                            <p class="text-muted">Tidak ada data ditemukan untuk periode ini</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Footer for Print -->
                <div class="mt-5 d-none d-print-block">
                    <div class="row">
                        <div class="col-md-6">
                            <p>Dicetak oleh: <strong><?= htmlspecialchars($_SESSION['username'] ?? 'Administrator') ?></strong></p>
                        </div>
                        <div class="col-md-6 text-end">
                            <p>Hal: <span id="pageNumber">1</span></p>
                        </div>
                    </div>
                    <hr>
                    <p class="text-center text-muted">
                        &copy; <?= date('Y') ?> Sistem Peminjaman Alat Berat - Laporan Sistem
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../partials/script.php'; ?>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- DataTables Buttons -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable with export buttons
    var table = $('#reportTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                className: 'btn btn-success no-print',
                text: '<i class="fas fa-file-excel me-2"></i>Excel',
                title: 'Laporan <?= ucfirst(str_replace('_', ' ', $tipe_laporan)) ?>',
                messageTop: 'Periode: <?= date('d F Y', strtotime($start_date)) ?> - <?= date('d F Y', strtotime($end_date)) ?>',
                filename: 'laporan_<?= $tipe_laporan ?>_<?= date('Y-m-d') ?>'
            },
            {
                extend: 'pdf',
                className: 'btn btn-danger no-print',
                text: '<i class="fas fa-file-pdf me-2"></i>PDF',
                title: 'Laporan <?= ucfirst(str_replace('_', ' ', $tipe_laporan)) ?>',
                messageTop: 'Periode: <?= date('d F Y', strtotime($start_date)) ?> - <?= date('d F Y', strtotime($end_date)) ?>',
                filename: 'laporan_<?= $tipe_laporan ?>_<?= date('Y-m-d') ?>',
                orientation: 'landscape',
                pageSize: 'A4'
            },
            {
                extend: 'print',
                className: 'btn btn-info no-print',
                text: '<i class="fas fa-print me-2"></i>Print',
                title: 'Laporan <?= ucfirst(str_replace('_', ' ', $tipe_laporan)) ?>',
                messageTop: 'Periode: <?= date('d F Y', strtotime($start_date)) ?> - <?= date('d F Y', strtotime($end_date)) ?>',
                customize: function (win) {
                    $(win.document.body).find('h1').css('text-align', 'center');
                    $(win.document.body).find('table').addClass('table-bordered');
                }
            }
        ],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: ">",
                previous: "<"
            }
        },
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        order: [[0, 'asc']]
    });
    
    // Hide DataTables default header in print
    $('.dt-buttons').addClass('no-print');
    $('.dataTables_length').addClass('no-print');
    $('.dataTables_filter').addClass('no-print');
    $('.dataTables_info').addClass('no-print');
    $('.dataTables_paginate').addClass('no-print');
});

// Custom print function
function printReport() {
    window.print();
}

// Custom Excel export
function exportToExcel() {
    // Trigger DataTable Excel button
    $('.buttons-excel').click();
}

// Custom PDF export
function exportToPDF() {
    // Trigger DataTable PDF button
    $('.buttons-pdf').click();
}

// Update page numbers for print
window.onafterprint = function() {
    // Reset any print-specific changes
    $('body').removeClass('printing');
};

window.onbeforeprint = function() {
    // Add print-specific classes
    $('body').addClass('printing');
    
    // Update page numbers
    var totalPages = Math.ceil($('#reportTable tbody tr').length / 10);
    $('#pageNumber').text('1 dari ' + totalPages);
};
</script>

</body>
</html>