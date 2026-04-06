<?php
// AKTIFKAN ERROR REPORTING
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pastikan session_start() di awal
session_start();

// Include koneksi database
include '../../app.php';

// Query semua alat berat dengan join ke tabel kategori
$qAlat = "SELECT 
            b.*,
            k.nama_kategori as nama_kategori
          FROM barang b 
          LEFT JOIN kategori k ON b.kategori_id = k.id 
          ORDER BY b.id DESC";
$result = mysqli_query($connect, $qAlat);

if (!$result) {
    die("Query error: " . mysqli_error($connect));
}

// Simpan data ke array untuk digunakan nanti
$alats = [];
while ($row = mysqli_fetch_assoc($result)) {
    $alats[] = $row;
}

// Hitung total data
$totalAlats = count($alats);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Alat Berat - Card View</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
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

        /* Button styling */
        .btn-add-alat {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-add-alat:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }

        /* Alert styling */
        .alert-container {
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 25px;
            border: none;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .alert-success {
            background: linear-gradient(to right, #d4edda, #c3e6cb);
            border-left: 5px solid #28a745;
            color: #155724;
            padding: 15px 20px;
        }

        .alert-danger {
            background: linear-gradient(to right, #f8d7da, #f5c6cb);
            border-left: 5px solid #dc3545;
            color: #721c24;
            padding: 15px 20px;
        }

        .alert-info {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(240, 147, 251, 0.3);
        }

        .alert-info i {
            font-size: 1.2rem;
            margin-right: 10px;
        }

        /* Stats badges */
        .stats-badge {
            background: rgba(255, 255, 255, 0.9);
            padding: 8px 15px;
            border-radius: 20px;
            margin: 0 5px;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .badge-tersedia {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
        }

        .badge-dipinjam {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .badge-rusak {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
        }

        .badge-hilang {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        /* CARD VIEW STYLING */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }

        .alat-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #eaeaea;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .alat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-kode {
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .card-id {
            font-size: 0.85rem;
            opacity: 0.8;
            background: rgba(255, 255, 255, 0.2);
            padding: 3px 8px;
            border-radius: 10px;
        }

        .card-body {
            padding: 25px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .alat-nama {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .alat-detail {
            color: #718096;
            font-size: 0.95rem;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .alat-detail-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            gap: 10px;
        }

        .alat-detail-item i {
            width: 20px;
            color: #4e73df;
        }

        .alat-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
            padding: 15px;
            background: #f8f9ff;
            border-radius: 10px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 1.3rem;
            font-weight: 700;
            color: #4e73df;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-container {
            margin: 20px 0;
        }

        .status-badge {
            padding: 10px 25px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
            text-align: center;
            width: 100%;
        }

        .card-footer {
            padding: 20px;
            background: #f8f9ff;
            border-top: 1px solid #eaeaea;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .harga-info {
            text-align: center;
            flex-grow: 1;
        }

        .harga-label {
            font-size: 0.9rem;
            color: #718096;
            margin-bottom: 5px;
        }

        .harga-value {
            font-size: 1.3rem;
            font-weight: 700;
            color: #10b981;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-action {
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-view {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .btn-edit {
            background: linear-gradient(135deg, #fad961 0%, #f76b1c 100%);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #ff5858 0%, #f09819 100%);
            color: white;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            color: white;
        }

        /* Filter controls */
        .filter-controls {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
            align-items: center;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-label {
            font-weight: 600;
            color: #4a5568;
            min-width: 80px;
        }

        .filter-select {
            padding: 8px 15px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: white;
            color: #4a5568;
            min-width: 150px;
        }

        .btn-filter {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-reset {
            background: #e2e8f0;
            color: #4a5568;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-reset:hover {
            background: #cbd5e0;
        }

        /* View toggle buttons */
        .view-toggle {
            display: flex;
            gap: 10px;
            margin-left: auto;
        }

        .btn-view-toggle {
            background: white;
            border: 1px solid #e2e8f0;
            padding: 8px 15px;
            border-radius: 8px;
            cursor: pointer;
            color: #4a5568;
            transition: all 0.2s ease;
        }

        .btn-view-toggle.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
        }

        /* No data state */
        .no-data {
            padding: 50px 20px;
            text-align: center;
            color: #6c757d;
            background: white;
            border-radius: 15px;
            margin: 20px 0;
        }

        .no-data i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .no-data h5 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .no-data p {
            font-size: 1.1rem;
            opacity: 0.8;
        }

        /* Footer styling */
        .footer-custom {
            margin-top: 30px;
            text-align: center;
            color: #6c757d;
            font-size: 0.9rem;
            padding: 20px;
            border-top: 1px solid #e3e6f0;
            background: white;
            border-radius: 0 0 12px 12px;
        }

        /* Responsive adjustments */
        @media (max-width: 1200px) {
            .card-grid {
                grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            }
        }

        @media (max-width: 992px) {
            .card-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }
            
            .card-body-custom {
                padding: 20px;
            }
            
            .filter-controls {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .view-toggle {
                margin-left: 0;
                margin-top: 15px;
            }
        }

        @media (max-width: 768px) {
            .card-header-custom {
                flex-direction: column;
                gap: 15px;
                padding: 15px;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
            
            .btn-add-alat {
                width: 100%;
                justify-content: center;
            }
            
            .card-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
                width: 100%;
            }
            
            .btn-action {
                justify-content: center;
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .alat-stats {
                grid-template-columns: 1fr;
            }
            
            .stats-badge {
                display: block;
                margin: 5px 0;
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<?php 
include '../../partials/header.php'; 
$page = 'alat'; 
include '../../partials/sidebar.php'; 
?>

<div class="container-fluid">
    <div id="main">
        <!-- Main Card -->
        <div class="main-card">
            <!-- Card Header -->
            <div class="card-header-custom d-flex align-items-center justify-content-between">
                <h2 class="page-title">
                    <i class="fas fa-hard-hat"></i>
                    Data Alat Berat
                </h2>
                <a href="./create.php" class="btn btn-add-alat">
                    <i class="fas fa-plus-circle"></i>
                    Tambah Alat
                </a>
            </div>
            
            <!-- Card Body -->
            <div class="card-body-custom">
                <!-- Session Messages -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert-container">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php
                            echo $_SESSION['success'];
                            unset($_SESSION['success']);
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert-container">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Info Stats -->
                <?php
                $tersediaCount = 0;
                $dipinjamCount = 0;
                $rusakCount = 0;
                $hilangCount = 0;
                $totalHarga = 0;
                
                foreach ($alats as $alat) {
                    if ($alat['status'] == 'tersedia') $tersediaCount++;
                    if ($alat['status'] == 'dipinjam') $dipinjamCount++;
                    if ($alat['status'] == 'rusak') $rusakCount++;
                    if ($alat['status'] == 'hilang') $hilangCount++;
                    $totalHarga += $alat['harga_sewa_perhari'] * $alat['jumlah'];
                }
                ?>
                
                <div class="alert alert-info d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fas fa-info-circle"></i>
                        <strong>Total <?php echo $totalAlats; ?> Alat Berat Terdaftar</strong>
                    </div>
                    <?php if ($totalAlats > 0): ?>
                        <div class="d-none d-md-flex">
                            <span class="stats-badge badge-tersedia">
                                <i class="fas fa-check-circle me-1"></i> Tersedia: <?php echo $tersediaCount; ?>
                            </span>
                            <span class="stats-badge badge-dipinjam">
                                <i class="fas fa-clock me-1"></i> Dipinjam: <?php echo $dipinjamCount; ?>
                            </span>
                            <span class="stats-badge badge-rusak">
                                <i class="fas fa-tools me-1"></i> Rusak: <?php echo $rusakCount; ?>
                            </span>
                            <span class="stats-badge badge-hilang">
                                <i class="fas fa-exclamation-triangle me-1"></i> Hilang: <?php echo $hilangCount; ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Filter Controls -->
                <div class="filter-controls">
                    <div class="filter-group">
                        <span class="filter-label">Status:</span>
                        <select class="filter-select" id="filterStatus">
                            <option value="all">Semua Status</option>
                            <option value="tersedia">Tersedia</option>
                            <option value="dipinjam">Dipinjam</option>
                            <option value="rusak">Rusak</option>
                            <option value="hilang">Hilang</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <span class="filter-label">Kategori:</span>
                        <select class="filter-select" id="filterKategori">
                            <option value="all">Semua Kategori</option>
                            <?php
                            // Get unique kategori names
                            $kategoriOptions = [];
                            foreach ($alats as $alat) {
                                if (!empty($alat['nama_kategori'])) {
                                    $kategoriOptions[$alat['kategori_id']] = $alat['nama_kategori'];
                                }
                            }
                            $kategoriOptions = array_unique($kategoriOptions);
                            foreach ($kategoriOptions as $id => $kategori) {
                                echo "<option value=\"$id\">$kategori</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <button class="btn-filter" id="applyFilter">
                        <i class="fas fa-filter me-2"></i>Terapkan Filter
                    </button>
                    
                    <button class="btn-reset" id="resetFilter">
                        <i class="fas fa-redo me-2"></i>Reset
                    </button>
                    
                    <div class="view-toggle">
                        <button class="btn-view-toggle active" data-view="grid">
                            <i class="fas fa-th-large"></i> Grid
                        </button>
                        <button class="btn-view-toggle" data-view="list">
                            <i class="fas fa-list"></i> List
                        </button>
                    </div>
                </div>
                
                <!-- Alat Cards -->
                <?php if ($totalAlats > 0): ?>
                    <div class="card-grid" id="alatGrid">
                        <?php foreach ($alats as $index => $item): 
                            $badgeClass = '';
                            $statusIcon = '';
                            switch($item['status']) {
                                case 'tersedia': 
                                    $badgeClass = 'badge-tersedia'; 
                                    $statusIcon = 'check-circle';
                                    break;
                                case 'dipinjam': 
                                    $badgeClass = 'badge-dipinjam'; 
                                    $statusIcon = 'clock';
                                    break;
                                case 'rusak': 
                                    $badgeClass = 'badge-rusak'; 
                                    $statusIcon = 'tools';
                                    break;
                                case 'hilang': 
                                    $badgeClass = 'badge-hilang'; 
                                    $statusIcon = 'exclamation-triangle';
                                    break;
                                default: 
                                    $badgeClass = 'badge-tersedia';
                                    $statusIcon = 'check-circle';
                            }
                        ?>
                        <div class="alat-card" data-status="<?= $item['status'] ?>" data-kategori="<?= $item['kategori_id'] ?>">
                            <div class="card-header">
                                <div class="card-kode">
                                    <?= htmlspecialchars($item['kode_barang']) ?>
                                </div>
                                <div class="card-id">
                                    ID: #<?= $item['id'] ?>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <h3 class="alat-nama">
                                    <?= htmlspecialchars($item['nama_barang']) ?>
                                </h3>
                                
                                <?php if (!empty($item['merk'])): ?>
                                    <div class="alat-detail">
                                        <div class="alat-detail-item">
                                            <i class="fas fa-tag"></i>
                                            <span>Merk: <strong><?= htmlspecialchars($item['merk']) ?></strong></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($item['nama_kategori'])): ?>
                                    <div class="alat-detail">
                                        <div class="alat-detail-item">
                                            <i class="fas fa-list"></i>
                                            <span>Kategori: <strong><?= htmlspecialchars($item['nama_kategori']) ?></strong></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($item['tahun'])): ?>
                                    <div class="alat-detail">
                                        <div class="alat-detail-item">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span>Tahun: <strong><?= htmlspecialchars($item['tahun']) ?></strong></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="alat-stats">
                                    <div class="stat-item">
                                        <div class="stat-value"><?= $item['jumlah_tersedia'] ?? $item['jumlah'] ?></div>
                                        <div class="stat-label">Stok Tersedia</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value"><?= $item['stok'] ?? $item['jumlah'] ?></div>
                                        <div class="stat-label">Total Stok</div>
                                    </div>
                                </div>
                                
                                <?php if (!empty($item['deskripsi'])): ?>
                                    <div class="alat-detail">
                                        <div class="alat-detail-item">
                                            <i class="fas fa-info-circle"></i>
                                            <span><?= htmlspecialchars(substr($item['deskripsi'], 0, 100)) ?>...</span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="status-container">
                                    <span class="status-badge <?= $badgeClass ?>">
                                        <i class="fas fa-<?= $statusIcon ?> me-2"></i>
                                        <?= ucfirst($item['status']) ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <div class="harga-info">
                                    <div class="harga-label">Harga Sewa / Hari</div>
                                    <div class="harga-value">
                                        Rp <?= number_format($item['harga_sewa_perhari'], 0, ',', '.') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-hard-hat"></i>
                        <h5>Belum Ada Data Alat Berat</h5>
                        <p>Mulai dengan menambahkan alat berat baru menggunakan tombol di atas</p>
                    </div>
                <?php endif; ?>
                
                <!-- Footer -->
                <div class="footer-custom">
                    <p class="mb-0">
                        &copy; <?= date('Y') ?> Website Peminjaman Alat Berat. Hak Cipta Dilindungi.
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

<script>
$(document).ready(function() {
    // View toggle functionality
    $('.btn-view-toggle').click(function() {
        $('.btn-view-toggle').removeClass('active');
        $(this).addClass('active');
        
        var view = $(this).data('view');
        
        if (view === 'list') {
            $('#alatGrid').removeClass('card-grid').addClass('list-view');
            $('.alat-card').css({
                'flex-direction': 'row',
                'max-width': '100%'
            });
            $('.card-body').css({
                'flex': '1',
                'display': 'flex',
                'flex-direction': 'column',
                'justify-content': 'center'
            });
            $('.card-footer').css({
                'flex-direction': 'column',
                'justify-content': 'center',
                'width': '250px'
            });
        } else {
            $('#alatGrid').removeClass('list-view').addClass('card-grid');
            $('.alat-card').css({
                'flex-direction': 'column',
                'max-width': 'none'
            });
            $('.card-body').css({
                'flex': '',
                'display': '',
                'flex-direction': '',
                'justify-content': ''
            });
            $('.card-footer').css({
                'flex-direction': '',
                'justify-content': '',
                'width': ''
            });
        }
    });
    
    // Filter functionality
    $('#applyFilter').click(function() {
        var statusFilter = $('#filterStatus').val();
        var kategoriFilter = $('#filterKategori').val();
        
        $('.alat-card').each(function() {
            var cardStatus = $(this).data('status');
            var cardKategori = $(this).data('kategori');
            var showCard = true;
            
            // Apply status filter
            if (statusFilter !== 'all' && cardStatus !== statusFilter) {
                showCard = false;
            }
            
            // Apply kategori filter
            if (kategoriFilter !== 'all' && cardKategori != kategoriFilter) { // Use != instead of !== because types may differ
                showCard = false;
            }
            
            // Show/hide card with animation
            if (showCard) {
                $(this).fadeIn(300);
            } else {
                $(this).fadeOut(300);
            }
        });
    });
    
    // Reset filter
    $('#resetFilter').click(function() {
        $('#filterStatus').val('all');
        $('#filterKategori').val('all');
        $('.alat-card').fadeIn(300);
    });
    
    // Card hover animation
    $('.alat-card').hover(
        function() {
            $(this).css('transform', 'translateY(-5px)');
        },
        function() {
            $(this).css('transform', 'translateY(0)');
        }
    );
    
    // Initialize card animation
    $('.alat-card').each(function(index) {
        $(this).css({
            'opacity': '0',
            'transform': 'translateY(20px)'
        });
        
        setTimeout(() => {
            $(this).animate({
                'opacity': '1',
                'transform': 'translateY(0)'
            }, 300 + (index * 100));
        }, 100);
    });
});
</script>

</body>
</html>