<?php
// AKTIFKAN ERROR REPORTING
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Include koneksi database
include '../../app.php';

// Query data motor
$qMotor = "SELECT * FROM barang ORDER BY id DESC";
$result = mysqli_query($connect, $qMotor);

if (!$result) {
    die("Query error: " . mysqli_error($connect));
}

$motors = [];
while ($row = mysqli_fetch_assoc($result)) {
    $motors[] = $row;
}

$totalMotors = count($motors);

// Hitung statistik
$tersediaCount = 0;
$dipinjamCount = 0;
$rusakCount = 0;
$hilangCount = 0;

foreach ($motors as $motor) {
    if ($motor['status'] == 'tersedia') $tersediaCount++;
    elseif ($motor['status'] == 'dipinjam') $dipinjamCount++;
    elseif ($motor['status'] == 'rusak') $rusakCount++;
    elseif ($motor['status'] == 'hilang') $hilangCount++;
}
?>

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
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
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

    /* Button styling - Tambah Motor */
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
        text-decoration: none;
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

    .alert-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }

    .alert-info i {
        font-size: 1.2rem;
        margin-right: 10px;
    }

    /* Stats badges in alert */
    .stats-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 8px 15px;
        border-radius: 20px;
        margin: 0 5px;
        font-weight: 600;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
        display: inline-block;
    }

    .stats-badge:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    /* Status badges in table */
    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-block;
        min-width: 120px;
        text-align: center;
        text-transform: capitalize;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .status-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    /* Status color variations */
    .status-tersedia {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .status-dipinjam {
        background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
        color: white;
    }

    .status-rusak {
        background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
        color: white;
    }

    .status-hilang {
        background: linear-gradient(135deg, #fd7e14 0%, #dc3545 100%);
        color: white;
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-action {
        border-radius: 8px;
        padding: 8px 15px;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        border: none;
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

    /* Table styling */
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
        padding: 18px 20px;
        border-bottom: 1px solid #f0f2f5;
        vertical-align: middle;
        color: #5a5c69;
    }

    .dataTable tbody tr {
        transition: all 0.2s ease;
    }

    .dataTable tbody tr:hover {
        background-color: #f8f9ff;
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    /* No data styling */
    .no-data {
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(135deg, #f5f7fa 0%, #f0f2f5 100%);
        border-radius: 12px;
    }

    .no-data i {
        font-size: 64px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 20px;
    }

    /* Footer */
    .footer-custom {
        margin-top: 30px;
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid #e3e6f0;
        color: #858796;
        font-size: 0.85rem;
    }

    /* DataTables custom */
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 8px;
        border: 1px solid #d1d3e2;
        padding: 6px 12px;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #667eea;
        outline: none;
        box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white !important;
        border: none;
        border-radius: 8px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: linear-gradient(135deg, #5a67d8 0%, #6b46a0 100%);
        color: white !important;
        border: none;
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
        .card-body-custom {
            padding: 20px;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 5px;
        }
        
        .btn-action {
            justify-content: center;
            width: 100%;
        }
        
        .status-badge {
            min-width: 100px;
            padding: 6px 12px;
            font-size: 0.8rem;
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
        
        .btn-add-motor {
            width: 100%;
            justify-content: center;
        }
        
        .alert-info {
            flex-direction: column;
            text-align: center;
        }
        
        .stats-badge {
            margin: 3px;
            padding: 6px 12px;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 576px) {
        .stats-badge {
            flex: 1;
            min-width: 45%;
            margin: 2px;
            text-align: center;
        }
        
        .status-badge {
            min-width: 80px;
            padding: 4px 10px;
            font-size: 0.75rem;
        }
    }
</style>

<?php 
include '../../partials/header.php'; 
$page = 'motor'; 
include '../../partials/sidebar.php'; 
?>

<div class="container-fluid">
    <div id="main">
        <div class="main-card">
            <!-- Card Header -->
            <div class="card-header-custom">
                <h2 class="page-title">
                    <i class="fas fa-motorcycle"></i>
                    Data Motor
                </h2>
                <a href="./create.php" class="btn-add-motor">
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
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert-container">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Info Stats dengan gradient ungu -->
                <div class="alert-info">
                    <div>
                        <i class="fas fa-chart-line"></i>
                        <strong>Total <?= $totalMotors ?> Motor Terdaftar</strong>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="stats-badge">
                            <i class="fas fa-check-circle me-1"></i> Tersedia: <?= $tersediaCount ?>
                        </span>
                        <span class="stats-badge">
                            <i class="fas fa-clock me-1"></i> Dipinjam: <?= $dipinjamCount ?>
                        </span>
                        <span class="stats-badge">
                            <i class="fas fa-tools me-1"></i> Rusak: <?= $rusakCount ?>
                        </span>
                        <span class="stats-badge">
                            <i class="fas fa-exclamation-triangle me-1"></i> Hilang: <?= $hilangCount ?>
                        </span>
                    </div>
                </div>

                <!-- Tabel Data Motor -->
                <?php if ($totalMotors > 0): ?>
                    <div class="table-responsive">
                        <table id="motorTable" class="table" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">NO</th>
                                    <th class="text-center">KODE</th>
                                    <th class="text-center">NAMA MOTOR</th>
                                    <th class="text-center">MERK</th>
                                    <th class="text-center">STOK</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-center">HARGA/HARI</th>
                                    <th class="text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($motors as $index => $item): ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= $index + 1 ?></td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <div class="rounded-circle bg-light p-2">
                                                <i class="fas fa-motorcycle" style="background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                                            </div>
                                            <strong><?= htmlspecialchars($item['kode_barang']) ?></strong>
                                        </div>
                                    </td>
                                    <td><strong><?= htmlspecialchars($item['nama_barang']) ?></strong></td>
                                    <td><?= htmlspecialchars($item['merk'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <span class="fw-semibold"><?= $item['jumlah'] ?> Unit</span>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $statusClass = '';
                                        switch($item['status']) {
                                            case 'tersedia': $statusClass = 'status-tersedia'; break;
                                            case 'dipinjam': $statusClass = 'status-dipinjam'; break;
                                            case 'rusak': $statusClass = 'status-rusak'; break;
                                            case 'hilang': $statusClass = 'status-hilang'; break;
                                            default: $statusClass = 'status-tersedia';
                                        }
                                        ?>
                                        <span class="status-badge <?= $statusClass ?>">
                                            <?= ucfirst($item['status']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold" style="color: #4e73df;">Rp <?= number_format($item['harga_sewa_perhari'], 0, ',', '.') ?></span>
                                        <div class="small text-muted">/hari</div>
                                    </td>
                                    <td class="text-center">
                                        <div class="action-buttons">
                                            <a href="./detail.php?id=<?= $item['id'] ?>" class="btn-action btn-view" title="Detail">
                                                <i class="fas fa-eye"></i> <span class="d-none d-lg-inline">Detail</span>
                                            </a>
                                            <a href="./edit.php?id=<?= $item['id'] ?>" class="btn-action btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i> <span class="d-none d-lg-inline">Edit</span>
                                            </a>
                                            <a href="../../action/motor/destroy.php?id=<?= $item['id'] ?>"
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus motor <?= addslashes($item['nama_barang']) ?>?')"
                                               class="btn-action btn-delete" title="Hapus">
                                                <i class="fas fa-trash"></i> <span class="d-none d-lg-inline">Hapus</span>
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
                        <h5 class="mt-3">Belum Ada Data Motor</h5>
                        <p>Silakan tambah motor baru menggunakan tombol "Tambah Motor" di atas</p>
                    </div>
                <?php endif; ?>

                <!-- Footer -->
                <div class="footer-custom">
                    <p>&copy; <?= date('Y') ?> Sistem Informasi Peminjaman Motor. All rights reserved.</p>
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

<script>
$(document).ready(function() {
    $('#motorTable').DataTable({
        language: {
            processing: "Memproses...",
            search: "Cari:",
            searchPlaceholder: "Cari kode atau nama motor...",
            lengthMenu: "Tampilkan _MENU_ motor per halaman",
            zeroRecords: "Tidak ada data yang ditemukan",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ motor",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 motor",
            infoFiltered: "(disaring dari _MAX_ total motor)",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "›",
                previous: "‹"
            }
        },
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
        order: [[1, 'asc']],
        columnDefs: [
            { targets: 0, orderable: false, searchable: false },
            { targets: 7, orderable: false, searchable: false }
        ]
    });
});
</script>

</body>
</html>