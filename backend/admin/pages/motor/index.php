<?php
// AKTIFKAN ERROR REPORTING
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pastikan session_start() di awal
session_start();

// Include koneksi database
include '../../app.php';

// Query semua motor dengan join kategori
$qMotor = "SELECT b.*, k.nama_kategori 
           FROM barang b
           LEFT JOIN kategori k ON b.kategori_id = k.id
           ORDER BY b.id DESC";
$result = mysqli_query($connect, $qMotor);

if (!$result) {
    die("Query error: " . mysqli_error($connect));
}

// Simpan data ke array
$motor = [];
while ($row = mysqli_fetch_assoc($result)) {
    $motor[] = $row;
}

// Hitung total data
$totalMotor = count($motor);

// Hitung statistik
$statTersedia = 0;
$statDipinjam = 0;
$statRusak = 0;
foreach ($motor as $item) {
    switch($item['status']) {
        case 'tersedia': $statTersedia++; break;
        case 'dipinjam': $statDipinjam++; break;
        case 'rusak': $statRusak++; break;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Motor - Admin Panel</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
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
        .btn-add-motor {
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

        .btn-add-motor:hover {
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

        /* Stats Cards */
        .stats-row {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        .stat-card {
            flex: 1;
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 4px solid;
            transition: all 0.3s ease;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .stat-card.total { border-left-color: #4e73df; }
        .stat-card.tersedia { border-left-color: #1cc88a; }
        .stat-card.dipinjam { border-left-color: #f6c23e; }
        .stat-card.rusak { border-left-color: #e74a3b; }
        .stat-number { font-size: 2rem; font-weight: 700; margin-bottom: 5px; }
        .stat-label { color: #6c757d; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-icon { float: right; font-size: 2rem; opacity: 0.3; }

        /* Status Badge */
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }
        .status-tersedia { background: #d4edda; color: #155724; }
        .status-dipinjam { background: #fff3cd; color: #856404; }
        .status-rusak { background: #f8d7da; color: #721c24; }
        .status-hilang { background: #d1ecf1; color: #0c5460; }

        /* Table styling */
        .dataTable {
            width: 100% !important;
            border-collapse: separate !important;
            border-spacing: 0 !important;
        }

        .dataTable thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 20px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .dataTable tbody td {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f2f5;
            vertical-align: middle;
            color: #5a5c69;
        }

        .dataTable tbody tr:hover {
            background-color: #f8f9ff;
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .btn-action {
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s ease;
        }

        .btn-view {
            background: #4facfe;
            color: white;
            border: none;
        }

        .btn-edit {
            background: #fad961;
            color: #333;
            border: none;
        }

        .btn-delete {
            background: #ff5858;
            color: white;
            border: none;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            opacity: 0.9;
            color: white;
        }
        .btn-edit:hover { color: #333; }

        /* No data state */
        .no-data {
            padding: 50px 20px;
            text-align: center;
            color: #6c757d;
        }
        .no-data i { font-size: 4rem; margin-bottom: 20px; opacity: 0.3; }
        .no-data h5 { font-size: 1.5rem; margin-bottom: 10px; font-weight: 600; }

        /* Responsive */
        @media (max-width: 992px) {
            .action-buttons { flex-direction: column; gap: 5px; }
            .btn-action { justify-content: center; }
        }
        @media (max-width: 768px) {
            .card-header-custom { flex-direction: column; gap: 15px; padding: 15px; }
            .page-title { font-size: 1.5rem; }
            .btn-add-motor { width: 100%; justify-content: center; }
            .stats-row { flex-direction: column; }
        }
    </style>
</head>
<body>

<?php 
include '../../partials/header.php'; 
$page = 'motor';
include '../../partials/sidebar.php'; 
?>

<div class="container-fluid">
    <div id="main">
        <!-- Main Card -->
        <div class="main-card">
            <!-- Card Header -->
            <div class="card-header-custom d-flex align-items-center justify-content-between">
                <h2 class="page-title">
                    <i class="fas fa-motorcycle"></i>
                    Data Motor
                </h2>
                <a href="./create.php" class="btn btn-add-motor">
                    <i class="fas fa-plus-circle"></i>
                    Tambah Motor
                </a>
            </div>
            
            <!-- Card Body -->
            <div class="card-body-custom">
                <!-- Session Messages -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert-container">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert-container">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Statistics Cards -->
                <div class="stats-row">
                    <div class="stat-card total">
                        <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
                        <div class="stat-number"><?= $totalMotor ?></div>
                        <div class="stat-label">Total Motor</div>
                    </div>
                    <div class="stat-card tersedia">
                        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="stat-number"><?= $statTersedia ?></div>
                        <div class="stat-label">Tersedia</div>
                    </div>
                    <div class="stat-card dipinjam">
                        <div class="stat-icon"><i class="fas fa-clock"></i></div>
                        <div class="stat-number"><?= $statDipinjam ?></div>
                        <div class="stat-label">Dipinjam</div>
                    </div>
                    <div class="stat-card rusak">
                        <div class="stat-icon"><i class="fas fa-tools"></i></div>
                        <div class="stat-number"><?= $statRusak ?></div>
                        <div class="stat-label">Rusak</div>
                    </div>
                </div>
                
                <!-- Motor Table -->
                <?php if ($totalMotor > 0): ?>
                    <div class="table-responsive">
                        <table id="motorTable" class="table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama Motor</th>
                                    <th>Kategori</th>
                                    <th>Merk</th>
                                    <th>Tahun</th>
                                    <th>Stok</th>
                                    <th>Tersedia</th>
                                    <th>Status</th>
                                    <th>Harga/Hari</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($motor as $index => $item): ?>
                                <tr>
                                    <td class="text-center"><?= $index + 1 ?></td>
                                    <td><span class="badge bg-secondary"><?= htmlspecialchars($item['kode_barang']) ?></span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-motorcycle text-primary me-2"></i>
                                            <strong><?= htmlspecialchars($item['nama_barang']) ?></strong>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($item['nama_kategori'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($item['merk'] ?? '-') ?></td>
                                    <td class="text-center"><?= $item['tahun'] ?? '-' ?></td>
                                    <td class="text-center"><span class="badge bg-info"><?= $item['stok'] ?></span></td>
                                    <td class="text-center"><span class="badge bg-success"><?= $item['jumlah_tersedia'] ?></span></td>
                                    <td class="text-center">
                                        <?php
                                        $statusClass = '';
                                        switch($item['status']) {
                                            case 'tersedia': $statusClass = 'status-tersedia'; $statusText = 'Tersedia'; break;
                                            case 'dipinjam': $statusClass = 'status-dipinjam'; $statusText = 'Dipinjam'; break;
                                            case 'rusak': $statusClass = 'status-rusak'; $statusText = 'Rusak'; break;
                                            default: $statusClass = 'status-tersedia'; $statusText = 'Tersedia';
                                        }
                                        ?>
                                        <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                                    </td>
                                    <td class="text-center"><strong class="text-success">Rp <?= number_format($item['harga_sewa_perhari'], 0, ',', '.') ?></strong></td>
                                    <td class="text-center">
                                        <div class="action-buttons">
                                            <a href="./detail.php?id=<?= $item['id'] ?>" class="btn-action btn-view" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="./edit.php?id=<?= $item['id'] ?>" class="btn-action btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="../../action/motor/destroy.php?id=<?= $item['id'] ?>"
                                               onclick="return confirm('Hapus motor <?= addslashes($item['nama_barang']) ?>?')"
                                               class="btn-action btn-delete" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-motorcycle"></i>
                        <h5>Belum Ada Data Motor</h5>
                        <p>Klik tombol "Tambah Motor" untuk menambahkan data</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../../partials/footer.php'; ?>
<?php include '../../partials/script.php'; ?>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#motorTable').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            zeroRecords: "Tidak ada data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: { next: "›", previous: "‹" }
        },
        pageLength: 10,
        order: [[2, 'asc']],
        columnDefs: [
            { targets: 0, orderable: false, searchable: false },
            { targets: 10, orderable: false, searchable: false }
        ]
    });
});
</script>

</body>
</html>