<?php
// AKTIFKAN ERROR REPORTING
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pastikan session_start() di awal
session_start();

// Include koneksi database
include '../../app.php';

// Query semua peminjaman dengan join termasuk kategori
$qPeminjaman = "SELECT p.*, u.username, b.nama_barang, k.nama_kategori 
          FROM peminjaman p 
          JOIN users u ON p.user_id = u.id 
          JOIN barang b ON p.barang_id = b.id 
          JOIN kategori k ON b.kategori_id = k.id
          ORDER BY p.created_at DESC";
                
$result = mysqli_query($connect, $qPeminjaman);

if (!$result) {
    die("Query error: " . mysqli_error($connect));
}

// Simpan data ke array
$peminjamans = [];
while ($row = mysqli_fetch_assoc($result)) {
    $peminjamans[] = $row;
}

// Hitung total data
$totalPeminjaman = count($peminjamans);

// Hitung status berdasarkan database
$status_counts = [
    'pending' => 0,
    'disetujui' => 0,
    'ditolak' => 0,
    'dipinjam' => 0,
    'selesai' => 0,
    'terlambat' => 0,
    'dikembalikan' => 0
];

foreach ($peminjamans as $item) {
    $status = $item['status'] ?? 'pending';
    if (isset($status_counts[$status])) {
        $status_counts[$status]++;
    } else {
        $status_counts['pending']++; // fallback
    }
}

// Query untuk filter kategori
$kategori_filter = mysqli_query($connect, "SELECT id, nama_kategori FROM kategori ORDER BY nama_kategori");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peminjaman - Admin Panel</title>
    
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
        .btn-add-peminjaman {
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

        .btn-add-peminjaman:hover {
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

        /* Status color variations based on database values */
        .status-pending {
            background: linear-gradient(135deg, #ffd700 0%, #ffa500 100%);
            color: #333;
        }

        .status-disetujui {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .status-ditolak {
            background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
            color: white;
        }

        .status-dipinjam {
            background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
            color: white;
        }

        .status-selesai {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }

        .status-terlambat {
            background: linear-gradient(135deg, #fd7e14 0%, #dc3545 100%);
            color: white;
        }

        .status-dikembalikan {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: #333;
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

        .btn-kembalikan {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
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
            
            .btn-add-peminjaman {
                width: 100%;
                justify-content: center;
            }
            
            .alert-info .d-md-flex {
                flex-wrap: wrap;
                gap: 10px;
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
</head>
<body>

<?php 
include '../../partials/header.php'; 
$page = 'peminjaman'; 
include '../../partials/sidebar.php'; 
?>

<div class="container-fluid">
    <div id="main">
        <!-- Main Card -->
        <div class="main-card">
            <!-- Card Header -->
            <div class="card-header-custom d-flex align-items-center justify-content-between">
                <h2 class="page-title">
                    <i class="fas fa-clipboard-list"></i>
                    Data Peminjaman
                </h2>
                <a href="./create.php" class="btn btn-add-peminjaman">
                    <i class="fas fa-plus-circle"></i>
                    Tambah Peminjaman
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
                <div class="alert alert-info d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fas fa-info-circle"></i>
                        <strong>Total <?php echo $totalPeminjaman; ?> Peminjaman</strong>
                    </div>
                    <?php if ($totalPeminjaman > 0): ?>
                        <div class="d-none d-md-flex flex-wrap">
                            <?php foreach ($status_counts as $status => $count): 
                                if ($count > 0): 
                                    $statusText = ucfirst($status);
                                    $icon = '';
                                    switch($status) {
                                        case 'pending': $icon = 'clock'; break;
                                        case 'disetujui': $icon = 'check'; break;
                                        case 'ditolak': $icon = 'times'; break;
                                        case 'dipinjam': $icon = 'box-open'; break;
                                        case 'selesai': $icon = 'check-circle'; break;
                                        case 'terlambat': $icon = 'exclamation-triangle'; break;
                                        case 'dikembalikan': $icon = 'undo'; break;
                                        default: $icon = 'question';
                                    }
                            ?>
                                <span class="stats-badge">
                                    <i class="fas fa-<?= $icon ?> me-1"></i> 
                                    <?= $statusText ?>: <?= $count ?>
                                </span>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Filter Kategori -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <select id="filterKategori" class="form-select">
                            <option value="">Semua Kategori</option>
                            <?php 
                            if ($kategori_filter && mysqli_num_rows($kategori_filter) > 0) {
                                while($kat = mysqli_fetch_assoc($kategori_filter)): 
                            ?>
                                <option value="<?= htmlspecialchars($kat['nama_kategori']) ?>">
                                    <?= htmlspecialchars($kat['nama_kategori']) ?>
                                </option>
                            <?php 
                                endwhile;
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <!-- Peminjaman Table -->
                <?php if ($totalPeminjaman > 0): ?>
                    <div class="table-responsive">
                        <table id="peminjamanTable" class="table" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center text-light">No</th>
                                    <th class="text-center text-light">Kode</th>
                                    <th class="text-center text-light">Peminjam</th>
                                    <th class="text-center text-light">Barang</th>
                                    <th class="text-center text-light">Kategori</th>
                                    <th class="text-center text-light">Tanggal Pinjam</th>
                                    <th class="text-center text-light">Status</th>
                                    <th class="text-center text-light">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($peminjamans as $index => $item): 
                                    $status = $item['status'] ?? 'pending';
                                    $statusText = ucfirst($status);
                                    $statusClass = 'status-' . $status;
                                ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= $index + 1 ?></td>
                                    <td class="fw-bold text-primary"><?= htmlspecialchars($item['kode_peminjaman']) ?></td>
                                    <td><?= htmlspecialchars($item['username']) ?></td>
                                    <td><?= htmlspecialchars($item['nama_barang']) ?> (<?= $item['jumlah'] ?> unit)</td>
                                    <td class="text-center">
                                        <span class="badge bg-info"><?= htmlspecialchars($item['nama_kategori'] ?? 'Tidak ada') ?></span>
                                    </td>
                                    <td class="text-center">
                                        <div class="text-primary fw-semibold">
                                            <?= date('d-m-Y', strtotime($item['tgl_pinjam'])) ?>
                                        </div>
                                        <div class="small text-muted">
                                            Kembali: <?= date('d-m-Y', strtotime($item['tgl_kembali_rencana'])) ?>
                                        </div>
                                        <?php if (!empty($item['tgl_kembali_aktual'])): ?>
                                        <div class="small text-success fw-semibold">
                                            <i class="fas fa-check-circle"></i>
                                            Kembali: <?= date('d-m-Y', strtotime($item['tgl_kembali_aktual'])) ?>
                                        </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="status-badge <?= $statusClass ?>">
                                            <?= $statusText ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="action-buttons">
                                            <a href="./detail.php?id=<?= $item['id'] ?>" 
                                               class="btn btn-action btn-view"
                                               title="Detail Peminjaman">
                                                <i class="fas fa-eye"></i>
                                                <span class="d-none d-lg-inline">Detail</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-clipboard fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Belum Ada Data Peminjaman</h4>
                        <p class="text-muted">Mulai dengan menambahkan peminjaman baru</p>
                        <a href="./create.php" class="btn btn-add-peminjaman mt-3">
                            <i class="fas fa-plus-circle"></i>
                            Tambah Peminjaman
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../../partials/script.php'; ?>
<?php include '../../partials/footer.php'; ?>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#peminjamanTable').DataTable({
        language: {
            processing: "Memproses...",
            search: "",
            searchPlaceholder: "Cari kode, peminjam, atau barang...",
            lengthMenu: "Tampilkan _MENU_ peminjaman per halaman",
            zeroRecords: "Tidak ada data yang ditemukan",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ peminjaman",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 peminjaman",
            infoFiltered: "(disaring dari _MAX_ total peminjaman)",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: ">",
                previous: "<"
            }
        },
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
        order: [[0, 'desc']],
        columnDefs: [
            {
                targets: 0,
                orderable: false,
                searchable: false,
                className: 'align-middle'
            },
            {
                targets: 4,
                className: 'align-middle',
                orderable: true,
                searchable: true
            },
            {
                targets: 5,
                className: 'align-middle',
                orderable: true,
                searchable: false
            },
            {
                targets: 6,
                className: 'align-middle',
                orderable: true,
                searchable: true
            },
            {
                targets: 7,
                orderable: false,
                searchable: false,
                className: 'align-middle'
            }
        ]
    });
    
    // Filter berdasarkan kategori
    $('#filterKategori').on('change', function() {
        var kategori = $(this).val();
        table.column(4).search(kategori).draw();
    });
    
    // Filter berdasarkan status
    $('#filterStatus').on('change', function() {
        var status = $(this).val();
        table.column(6).search(status).draw();
    });
    
    // Add animation to table rows
    $('#peminjamanTable tbody tr').each(function(index) {
        $(this).css('opacity', '0');
        $(this).delay(index * 100).animate({ opacity: 1 }, 500);
    });
    
    // Tooltip for action buttons
    $('[title]').tooltip({
        trigger: 'hover',
        placement: 'top'
    });
});
</script>

</body>
</html>