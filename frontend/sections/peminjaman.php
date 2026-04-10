<?php
session_start();
include "../partials/header.php";
include "../partials/navbar.php";
include "../../config/koneksi.php";

// Cek login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    echo "<script>
        alert('Silakan login terlebih dahulu');
        window.location.href = 'login.php';
    </script>";
    exit;
}

// Cek role harus peminjam
if ($_SESSION['role'] != 'peminjam') {
    echo "<script>
        alert('Halaman ini hanya untuk peminjam!');
        window.location.href = './';
    </script>";
    exit;
}

// Fungsi generate kode peminjaman random
function generateKodePeminjaman($connect)
{
    $year = date('Y');
    $random = strtoupper(substr(uniqid(), -5));
    $kode = "PINJ-$year-" . $random;

    // Cek apakah kode sudah ada
    $check = mysqli_query($connect, "SELECT id FROM peminjaman WHERE kode_peminjaman = '$kode'");
    if (mysqli_num_rows($check) > 0) {
        return generateKodePeminjaman($connect); // Rekursif jika kode sudah ada
    }
    return $kode;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Proses sewa motor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sewa_motor'])) {
    $barang_id = (int)$_POST['barang_id'];
    $jumlah = (int)$_POST['jumlah'];
    $tgl_pinjam = mysqli_real_escape_string($connect, $_POST['tgl_pinjam']);
    $tgl_kembali = mysqli_real_escape_string($connect, $_POST['tgl_kembali']);
    $keterangan = mysqli_real_escape_string($connect, $_POST['keterangan']);

    // Cek stok motor
    $cek_stok = mysqli_query($connect, "SELECT stok, harga_sewa_perhari, nama_barang FROM barang WHERE id = $barang_id");
    $motor = mysqli_fetch_assoc($cek_stok);

    if (!$motor) {
        echo "<script>alert('Motor tidak ditemukan!');</script>";
    } elseif ($motor['stok'] < $jumlah) {
        echo "<script>alert('Stok motor tidak mencukupi! Tersedia: {$motor['stok']} unit');</script>";
    } else {
        $lama = (strtotime($tgl_kembali) - strtotime($tgl_pinjam)) / (60 * 60 * 24);
        $total_harga = $lama * $jumlah * $motor['harga_sewa_perhari'];

        // Generate kode random
        $kode_peminjaman = generateKodePeminjaman($connect);

        // INSERT dengan kode random
        $query = "INSERT INTO peminjaman (kode_peminjaman, user_id, barang_id, jumlah, tgl_pinjam, tgl_kembali_rencana, keterangan, status, total_harga, lama_pinjam, created_at) 
                  VALUES ('$kode_peminjaman', $user_id, $barang_id, $jumlah, '$tgl_pinjam', '$tgl_kembali', '$keterangan', 'pending', $total_harga, $lama, NOW())";

        if (mysqli_query($connect, $query)) {
            echo "<script>
                alert('Peminjaman berhasil diajukan! Kode: $kode_peminjaman');
                window.location.href = 'peminjaman';
            </script>";
        } else {
            echo "<script>alert('Gagal mengajukan peminjaman: " . addslashes(mysqli_error($connect)) . "');</script>";
        }
    }
}

// Pagination
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? mysqli_real_escape_string($connect, $_GET['search']) : '';
$filter_status = isset($_GET['status']) ? mysqli_real_escape_string($connect, $_GET['status']) : '';

// Query search & filter
$where = "p.user_id = $user_id";
if (!empty($search)) {
    $where .= " AND (p.kode_peminjaman LIKE '%$search%' OR b.nama_barang LIKE '%$search%')";
}
if (!empty($filter_status)) {
    $where .= " AND p.status = '$filter_status'";
}

// Total data
$query_total = mysqli_query($connect, "SELECT COUNT(*) as total 
    FROM peminjaman p 
    LEFT JOIN barang b ON p.barang_id = b.id 
    WHERE $where");
$total_data = mysqli_fetch_assoc($query_total)['total'];
$total_pages = ceil($total_data / $limit);

// Query peminjaman
$query_peminjaman = mysqli_query($connect, "SELECT p.*, b.nama_barang, b.kode_barang, b.harga_sewa_perhari 
    FROM peminjaman p 
    LEFT JOIN barang b ON p.barang_id = b.id 
    WHERE $where
    ORDER BY p.id DESC 
    LIMIT $offset, $limit");

// Hitung statistik
$q_stats = mysqli_query($connect, "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status = 'dipinjam' THEN 1 ELSE 0 END) as aktif,
    SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai,
    SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) as ditolak
    FROM peminjaman WHERE user_id = $user_id");
$stats = mysqli_fetch_assoc($q_stats);

// Ambil daftar motor untuk dropdown
$query_motor = mysqli_query($connect, "SELECT id, nama_barang, kode_barang, stok, harga_sewa_perhari FROM barang WHERE status = 'tersedia' AND stok > 0 ORDER BY nama_barang");
?>

<!-- START SECTION TOP -->
<section class="section-top">
    <div class="container">
        <div class="col-lg-10 offset-lg-1 text-center">
            <div class="section-top-title wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.3s" data-wow-offset="0">
                <h1>Peminjaman Saya</h1>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li> / Peminjaman Saya</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- END SECTION TOP -->

<!-- START PEMINJAMAN LIST -->
<section class="peminjaman_area section-padding">
    <div class="container">
        <div class="section-title text-center">
            <h2>Riwayat <span>Peminjaman</span> Motor</h2>
            <p>Halo, <strong><?= htmlspecialchars($username) ?></strong>! Berikut adalah daftar peminjaman motor Anda</p>
        </div>

        <!-- Tombol Sewa Motor Baru -->
        <div class="text-center mb-4">
            <button class="btn-rent-new" onclick="openRentModal()">
                <i class="fas fa-plus-circle"></i> Sewa Motor Baru
            </button>
        </div>

        <!-- Statistik Cards -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-list"></i></div>
                <div class="stat-number"><?= $stats['total'] ?? 0 ?></div>
                <div class="stat-label">Total Peminjaman</div>
            </div>
            <div class="stat-card pending">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-number"><?= $stats['pending'] ?? 0 ?></div>
                <div class="stat-label">Menunggu</div>
            </div>
            <div class="stat-card aktif">
                <div class="stat-icon"><i class="fas fa-motorcycle"></i></div>
                <div class="stat-number"><?= $stats['aktif'] ?? 0 ?></div>
                <div class="stat-label">Dipinjam</div>
            </div>
            <div class="stat-card selesai">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-number"><?= $stats['selesai'] ?? 0 ?></div>
                <div class="stat-label">Selesai</div>
            </div>
            <div class="stat-card ditolak">
                <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
                <div class="stat-number"><?= $stats['ditolak'] ?? 0 ?></div>
                <div class="stat-label">Ditolak</div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar">
            <div class="show-entries">
                <label>Tampilkan:</label>
                <select id="limit-select" class="form-select-sm">
                    <option value="5" <?= $limit == 5 ? 'selected' : '' ?>>5</option>
                    <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                    <option value="25" <?= $limit == 25 ? 'selected' : '' ?>>25</option>
                    <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                </select>
                <span>data</span>
            </div>
            <div class="filter-status">
                <label>Filter Status:</label>
                <select id="status-filter" class="form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="pending" <?= $filter_status == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="disetujui" <?= $filter_status == 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
                    <option value="dipinjam" <?= $filter_status == 'dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
                    <option value="selesai" <?= $filter_status == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                    <option value="ditolak" <?= $filter_status == 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                </select>
            </div>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="search-input" placeholder="Cari kode atau motor..." value="<?= htmlspecialchars($search) ?>">
            </div>
        </div>

        <!-- Tabel Peminjaman -->
        <div class="table-container">
            <table class="table-peminjaman">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>KODE</th>
                        <th>MOTOR</th>
                        <th>TGL PINJAM</th>
                        <th>TGL KEMBALI</th>
                        <th>LAMA</th>
                        <th>STATUS</th>
                        <th>TOTAL</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($query_peminjaman) > 0): ?>
                        <?php
                        $no = $offset + 1;
                        while ($row = mysqli_fetch_assoc($query_peminjaman)):
                            $status_class = '';
                            $status_text = '';
                            switch ($row['status']) {
                                case 'pending':
                                    $status_class = 'status-pending';
                                    $status_text = 'Menunggu';
                                    break;
                                case 'disetujui':
                                    $status_class = 'status-disetujui';
                                    $status_text = 'Disetujui';
                                    break;
                                case 'dipinjam':
                                    $status_class = 'status-dipinjam';
                                    $status_text = 'Dipinjam';
                                    break;
                                case 'selesai':
                                    $status_class = 'status-selesai';
                                    $status_text = 'Selesai';
                                    break;
                                case 'ditolak':
                                    $status_class = 'status-ditolak';
                                    $status_text = 'Ditolak';
                                    break;
                                default:
                                    $status_class = 'status-pending';
                                    $status_text = 'Menunggu';
                            }
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= htmlspecialchars($row['kode_peminjaman']) ?></strong></td>
                                <td><?= htmlspecialchars($row['nama_barang']) ?><br><small class="text-muted"><?= htmlspecialchars($row['kode_barang']) ?></small></td>
                                <td><?= date('d/m/Y', strtotime($row['tgl_pinjam'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($row['tgl_kembali_rencana'])) ?></td>
                                <td><?= $row['lama_pinjam'] ?> hari</small></td>
                                <td><span class="status-badge <?= $status_class ?>"><?= $status_text ?></span></td>
                                <td>Rp <?= number_format($row['total_harga'] ?? 0, 0, ',', '.') ?></td>
                                <td class="action-buttons">
                                    <a href="detail-peminjaman/<?= !empty($row['slug']) ? urlencode($row['slug']) : 'id=' . $row['id'] ?>" class="btn-detail" title="Detail">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <?php if ($row['status'] == 'pending'): ?>
                                        <span class="btn-waiting" title="Menunggu konfirmasi admin">
                                            <i class="fas fa-hourglass-half"></i> Menunggu
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($row['status'] == 'dipinjam'): ?>
                                        <span class="btn-borrowed" title="Motor sedang dipinjam">
                                            <i class="fas fa-motorcycle"></i> Dipinjam
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($row['status'] == 'selesai'): ?>
                                        <span class="btn-returned" title="Peminjaman selesai">
                                            <i class="fas fa-check-circle"></i> Selesai
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-motorcycle"></i>
                                    <h4>Belum Ada Peminjaman</h4>
                                    <p>Anda belum melakukan peminjaman motor</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination-wrapper">
                <div class="data-info">
                    Menampilkan <?= min($offset + 1, $total_data) ?> - <?= min($offset + $limit, $total_data) ?> dari <?= $total_data ?> peminjaman
                </div>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>&status=<?= urlencode($filter_status) ?>&search=<?= urlencode($search) ?>" class="page-link">&laquo; Sebelumnya</a>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php if ($i >= $page - 2 && $i <= $page + 2): ?>
                            <a href="?page=<?= $i ?>&limit=<?= $limit ?>&status=<?= urlencode($filter_status) ?>&search=<?= urlencode($search) ?>" class="page-link <?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>&status=<?= urlencode($filter_status) ?>&search=<?= urlencode($search) ?>" class="page-link">Selanjutnya &raquo;</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- MODAL SEWA MOTOR -->
<div id="rentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-motorcycle"></i> Form Peminjaman Motor</h3>
            <span class="close" onclick="closeRentModal()">&times;</span>
        </div>
        <form method="POST" action="" id="rentForm">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Nama Peminjam</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($username) ?>" disabled>
            </div>
            <div class="form-group">
                <label><i class="fas fa-motorcycle"></i> Pilih Motor *</label>
                <select name="barang_id" id="motor_select" class="form-control" required onchange="updateMotorInfo()">
                    <option value="">-- Pilih Motor --</option>
                    <?php while ($motor = mysqli_fetch_assoc($query_motor)): ?>
                        <option value="<?= $motor['id'] ?>"
                            data-nama="<?= htmlspecialchars($motor['nama_barang']) ?>"
                            data-kode="<?= htmlspecialchars($motor['kode_barang']) ?>"
                            data-harga="<?= $motor['harga_sewa_perhari'] ?>"
                            data-stok="<?= $motor['stok'] ?>">
                            <?= htmlspecialchars($motor['nama_barang']) ?> (<?= htmlspecialchars($motor['kode_barang']) ?>) - Stok: <?= $motor['stok'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div id="motor_info" class="motor-info-preview" style="display: none;">
                <div class="info-preview">
                    <span id="motor_nama"></span>
                    <span id="motor_harga"></span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group half">
                    <label><i class="fas fa-calendar"></i> Tanggal Pinjam *</label>
                    <input type="date" name="tgl_pinjam" id="tgl_pinjam" class="form-control" required>
                </div>
                <div class="form-group half">
                    <label><i class="fas fa-calendar-check"></i> Tanggal Kembali *</label>
                    <input type="date" name="tgl_kembali" id="tgl_kembali" class="form-control" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group half">
                    <label><i class="fas fa-boxes"></i> Jumlah *</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control" value="1" min="1" required>
                </div>
                <div class="form-group half">
                    <label><i class="fas fa-money-bill"></i> Total Harga</label>
                    <div class="total-price" id="total_price">Rp 0</div>
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-align-left"></i> Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan tambahan..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeRentModal()">Batal</button>
                <button type="submit" name="sewa_motor" class="btn-submit">Ajukan Peminjaman</button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Tombol Sewa Baru */
    .btn-rent-new {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        margin-bottom: 20px;
    }

    .btn-rent-new:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(72, 187, 120, 0.3);
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
    }

    .modal-content {
        background: white;
        margin: 5% auto;
        width: 500px;
        max-width: 90%;
        border-radius: 20px;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 25px;
        border-bottom: 1px solid #eef2f6;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px 20px 0 0;
        color: white;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 1.2rem;
    }

    .close {
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        color: white;
        transition: all 0.2s;
    }

    .close:hover {
        opacity: 0.7;
    }

    .modal-content .form-group {
        padding: 0 25px;
        margin-bottom: 15px;
    }

    .form-row {
        display: flex;
        gap: 15px;
        padding: 0 25px;
    }

    .form-row .form-group {
        padding: 0;
        flex: 1;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        font-size: 0.8rem;
        color: #4a5568;
    }

    .form-group label i {
        margin-right: 5px;
        color: #667eea;
    }

    .form-control,
    .modal-content select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.85rem;
    }

    .form-control:focus,
    .modal-content select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .total-price {
        font-size: 1.2rem;
        font-weight: 700;
        color: #667eea;
        background: #f8fafc;
        padding: 10px;
        border-radius: 10px;
        text-align: center;
    }

    .motor-info-preview {
        padding: 0 25px;
        margin-bottom: 15px;
    }

    .info-preview {
        background: #f0fff4;
        padding: 10px 15px;
        border-radius: 10px;
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 20px 25px;
        border-top: 1px solid #eef2f6;
    }

    .btn-cancel {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #4a5568;
        padding: 10px 20px;
        border-radius: 10px;
        cursor: pointer;
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    /* Peminjaman Area */
    .peminjaman_area {
        padding: 60px 0;
        background: #f8fafc;
    }

    /* Stats Row */
    .stats-row {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 15px 25px;
        text-align: center;
        min-width: 120px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-icon i {
        font-size: 28px;
        color: #667eea;
        margin-bottom: 8px;
    }

    .stat-number {
        font-size: 1.8rem;
        font-weight: 800;
        color: #2d3748;
    }

    .stat-label {
        font-size: 0.7rem;
        color: #718096;
        text-transform: uppercase;
    }

    .stat-card.pending .stat-icon i {
        color: #ed8936;
    }

    .stat-card.aktif .stat-icon i {
        color: #48bb78;
    }

    .stat-card.selesai .stat-icon i {
        color: #38a169;
    }

    .stat-card.ditolak .stat-icon i {
        color: #e53e3e;
    }

    /* Filter Bar */
    .filter-bar {
        background: white;
        border-radius: 16px;
        padding: 15px 20px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    }

    .show-entries,
    .filter-status {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
    }

    .form-select-sm {
        padding: 6px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: white;
    }

    .search-box {
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 6px 12px;
    }

    .search-box i {
        color: #a0aec0;
    }

    .search-box input {
        border: none;
        background: transparent;
        padding: 6px 0;
        width: 220px;
        outline: none;
    }

    /* Table */
    .table-container {
        background: white;
        border-radius: 16px;
        overflow-x: auto;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    }

    .table-peminjaman {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
        min-width: 800px;
    }

    .table-peminjaman thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 14px 16px;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
    }

    .table-peminjaman tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid #f0f2f5;
        vertical-align: middle;
    }

    .table-peminjaman tbody tr:hover {
        background: #f8f9ff;
    }

    /* Status Badge */
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-disetujui {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-dipinjam {
        background: #d1fae5;
        color: #065f46;
    }

    .status-selesai {
        background: #d1fae5;
        color: #065f46;
    }

    .status-ditolak {
        background: #fee2e2;
        color: #991b1b;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-detail {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 0.7rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-waiting,
    .btn-borrowed,
    .btn-returned {
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 0.7rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-waiting {
        background: #fef3c7;
        color: #92400e;
    }

    .btn-borrowed {
        background: #dbeafe;
        color: #1e40af;
    }

    .btn-returned {
        background: #d1fae5;
        color: #065f46;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .data-info {
        font-size: 0.8rem;
        color: #718096;
    }

    .pagination {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .page-link {
        padding: 6px 12px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        text-decoration: none;
        color: #4a5568;
        transition: all 0.2s;
    }

    .page-link:hover,
    .page-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: transparent;
    }

    .empty-state {
        text-align: center;
        padding: 50px 20px;
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e0;
        margin-bottom: 20px;
    }

    .empty-state h4 {
        font-size: 1.2rem;
        margin-bottom: 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .filter-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box input {
            width: 100%;
        }

        .stats-row {
            gap: 10px;
        }

        .stat-card {
            min-width: calc(50% - 10px);
            padding: 10px;
        }

        .stat-number {
            font-size: 1.2rem;
        }

        .pagination-wrapper {
            flex-direction: column;
            text-align: center;
        }

        .form-row {
            flex-direction: column;
            gap: 0;
        }

        .modal-content {
            margin: 20% auto;
        }
    }
</style>

<script>
    // Modal functions
    function openRentModal() {
        document.getElementById('rentModal').style.display = 'block';
        setMinDates();
    }

    function closeRentModal() {
        document.getElementById('rentModal').style.display = 'none';
    }

    // Set minimal tanggal
    function setMinDates() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tgl_pinjam').min = today;
        document.getElementById('tgl_kembali').min = today;
    }

    // Update motor info
    function updateMotorInfo() {
        const select = document.getElementById('motor_select');
        const selectedOption = select.options[select.selectedIndex];
        const motorInfo = document.getElementById('motor_info');

        if (select.value) {
            const nama = selectedOption.getAttribute('data-nama');
            const kode = selectedOption.getAttribute('data-kode');
            const harga = parseInt(selectedOption.getAttribute('data-harga'));
            const stok = parseInt(selectedOption.getAttribute('data-stok'));

            document.getElementById('motor_nama').innerHTML = `<strong>${nama}</strong> (${kode})`;
            document.getElementById('motor_harga').innerHTML = `Rp ${harga.toLocaleString('id-ID')}/hari`;
            document.getElementById('jumlah').max = stok;
            motorInfo.style.display = 'block';
        } else {
            motorInfo.style.display = 'none';
        }
        hitungTotal();
    }

    // Hitung total harga
    function hitungTotal() {
        const select = document.getElementById('motor_select');
        const tglPinjam = document.getElementById('tgl_pinjam').value;
        const tglKembali = document.getElementById('tgl_kembali').value;
        const jumlah = parseInt(document.getElementById('jumlah').value) || 1;

        if (select.value && tglPinjam && tglKembali && tglKembali > tglPinjam) {
            const harga = parseInt(select.options[select.selectedIndex].getAttribute('data-harga'));
            const hari = Math.ceil((new Date(tglKembali) - new Date(tglPinjam)) / (1000 * 60 * 60 * 24));
            const total = hari * jumlah * harga;
            document.getElementById('total_price').innerHTML = 'Rp ' + total.toLocaleString('id-ID');
        } else {
            document.getElementById('total_price').innerHTML = 'Rp 0';
        }
    }

    // Event listeners
    document.getElementById('motor_select')?.addEventListener('change', hitungTotal);
    document.getElementById('tgl_pinjam')?.addEventListener('change', hitungTotal);
    document.getElementById('tgl_kembali')?.addEventListener('change', hitungTotal);
    document.getElementById('jumlah')?.addEventListener('input', hitungTotal);

    // Filter and search
    document.getElementById('limit-select')?.addEventListener('change', function() {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('limit', this.value);
        urlParams.set('page', 1);
        window.location.href = '?' + urlParams.toString();
    });

    document.getElementById('status-filter')?.addEventListener('change', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (this.value) {
            urlParams.set('status', this.value);
        } else {
            urlParams.delete('status');
        }
        urlParams.set('page', 1);
        window.location.href = '?' + urlParams.toString();
    });

    let searchTimeout;
    document.getElementById('search-input')?.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const urlParams = new URLSearchParams(window.location.search);
            if (this.value) {
                urlParams.set('search', this.value);
            } else {
                urlParams.delete('search');
            }
            urlParams.set('page', 1);
            window.location.href = '?' + urlParams.toString();
        }, 500);
    });

    // Tutup modal klik di luar
    window.onclick = function(event) {
        const modal = document.getElementById('rentModal');
        if (event.target == modal) {
            closeRentModal();
        }
    }
</script>

<?php include "../partials/footer.php"; ?>
<?php include "../partials/script.php"; ?>