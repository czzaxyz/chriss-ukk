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
        window.location.href = 'index.php';
    </script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil parameter (bisa id atau slug)
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$slug = isset($_GET['slug']) ? mysqli_real_escape_string($connect, $_GET['slug']) : '';

// Query detail peminjaman
if (!empty($slug)) {
    $query = mysqli_query($connect, "SELECT p.*, b.nama_barang, b.kode_barang, b.merk, b.tahun, b.harga_sewa_perhari, b.deskripsi as motor_deskripsi, k.nama_kategori
        FROM peminjaman p 
        LEFT JOIN barang b ON p.barang_id = b.id 
        LEFT JOIN kategori k ON b.kategori_id = k.id
        WHERE p.slug = '$slug' AND p.user_id = $user_id");
} else {
    $query = mysqli_query($connect, "SELECT p.*, b.nama_barang, b.kode_barang, b.merk, b.tahun, b.harga_sewa_perhari, b.deskripsi as motor_deskripsi, k.nama_kategori
        FROM peminjaman p 
        LEFT JOIN barang b ON p.barang_id = b.id 
        LEFT JOIN kategori k ON b.kategori_id = k.id
        WHERE p.id = $id AND p.user_id = $user_id");
}
$peminjaman = mysqli_fetch_assoc($query);

if (!$peminjaman) {
    echo "<script>
        alert('Data peminjaman tidak ditemukan!');
        window.location.href = 'peminjaman';
    </script>";
    exit;
}

// Hitung total bayar
$total_bayar = ($peminjaman['total_harga'] ?? 0) + ($peminjaman['denda'] ?? 0);

// Tentukan class status
$status_class = '';
$status_text = '';
$status_icon = '';
switch ($peminjaman['status']) {
    case 'pending':
        $status_class = 'status-pending';
        $status_text = 'Menunggu Konfirmasi';
        $status_icon = 'fas fa-clock';
        break;
    case 'disetujui':
        $status_class = 'status-disetujui';
        $status_text = 'Disetujui';
        $status_icon = 'fas fa-check-circle';
        break;
    case 'dipinjam':
        $status_class = 'status-dipinjam';
        $status_text = 'Sedang Dipinjam';
        $status_icon = 'fas fa-motorcycle';
        break;
    case 'selesai':
        $status_class = 'status-selesai';
        $status_text = 'Selesai';
        $status_icon = 'fas fa-check-double';
        break;
    case 'ditolak':
        $status_class = 'status-ditolak';
        $status_text = 'Ditolak';
        $status_icon = 'fas fa-times-circle';
        break;
    default:
        $status_class = 'status-pending';
        $status_text = 'Menunggu Konfirmasi';
        $status_icon = 'fas fa-clock';
}
?>

<!-- START SECTION TOP -->
<section class="section-top">
    <div class="container">
        <div class="col-lg-10 offset-lg-1 text-center">
            <div class="section-top-title wow fadeInRight">
                <h1>Detail Peminjaman</h1>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="peminjaman">Peminjaman Saya</a></li>
                    <li> / Detail</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- END SECTION TOP -->

<!-- START DETAIL PEMINJAMAN -->
<section class="detail-peminjaman section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Header Card - Seperti detail motor -->
                <div class="detail-header-card">
                    <div class="header-icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <h2><?= htmlspecialchars($peminjaman['kode_peminjaman']) ?></h2>
                    <div class="header-badge">
                        <span class="status-badge-large <?= $status_class ?>">
                            <i class="<?= $status_icon ?>"></i> <?= $status_text ?>
                        </span>
                    </div>
                </div>

                <!-- Informasi Peminjaman Card -->
                <div class="info-card">
                    <div class="info-card-header">
                        <i class="fas fa-info-circle"></i>
                        <h3>Informasi Peminjaman</h3>
                    </div>
                    <div class="info-card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Kode Peminjaman</div>
                                <div class="info-value"><?= htmlspecialchars($peminjaman['kode_peminjaman']) ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Tanggal Peminjaman</div>
                                <div class="info-value"><?= date('d F Y H:i', strtotime($peminjaman['created_at'])) ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Motor Card -->
                <div class="info-card">
                    <div class="info-card-header">
                        <i class="fas fa-motorcycle"></i>
                        <h3>Informasi Motor</h3>
                    </div>
                    <div class="info-card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Nama Motor</div>
                                <div class="info-value"><strong><?= htmlspecialchars($peminjaman['nama_barang']) ?></strong></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Kode Motor</div>
                                <div class="info-value"><?= htmlspecialchars($peminjaman['kode_barang']) ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Kategori</div>
                                <div class="info-value"><?= htmlspecialchars($peminjaman['nama_kategori'] ?? '-') ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Merk</div>
                                <div class="info-value"><?= htmlspecialchars($peminjaman['merk'] ?? '-') ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Tahun</div>
                                <div class="info-value"><?= $peminjaman['tahun'] ?? '-' ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Harga Sewa/Hari</div>
                                <div class="info-value price">Rp <?= number_format($peminjaman['harga_sewa_perhari'], 0, ',', '.') ?></div>
                            </div>
                        </div>
                        <?php if (!empty($peminjaman['motor_deskripsi'])): ?>
                        <div class="info-item full-width">
                            <div class="info-label">Deskripsi Motor</div>
                            <div class="info-value"><?= nl2br(htmlspecialchars($peminjaman['motor_deskripsi'])) ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Detail Peminjaman Card -->
                <div class="info-card">
                    <div class="info-card-header">
                        <i class="fas fa-calendar-alt"></i>
                        <h3>Detail Peminjaman</h3>
                    </div>
                    <div class="info-card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Tanggal Pinjam</div>
                                <div class="info-value"><?= date('d F Y', strtotime($peminjaman['tgl_pinjam'])) ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Tanggal Kembali Rencana</div>
                                <div class="info-value"><?= date('d F Y', strtotime($peminjaman['tgl_kembali_rencana'])) ?></div>
                            </div>
                            <?php if ($peminjaman['tgl_kembali_aktual']): ?>
                            <div class="info-item">
                                <div class="info-label">Tanggal Kembali Aktual</div>
                                <div class="info-value"><?= date('d F Y', strtotime($peminjaman['tgl_kembali_aktual'])) ?></div>
                            </div>
                            <?php endif; ?>
                            <div class="info-item">
                                <div class="info-label">Lama Pinjam</div>
                                <div class="info-value"><?= $peminjaman['lama_pinjam'] ?> Hari</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Jumlah Motor</div>
                                <div class="info-value"><?= $peminjaman['jumlah'] ?> Unit</div>
                            </div>
                        </div>
                        <?php if (!empty($peminjaman['keterangan'])): ?>
                        <div class="info-item full-width">
                            <div class="info-label">Keterangan Peminjaman</div>
                            <div class="info-value"><?= nl2br(htmlspecialchars($peminjaman['keterangan'])) ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Pembayaran Card -->
                <div class="info-card">
                    <div class="info-card-header">
                        <i class="fas fa-money-bill-wave"></i>
                        <h3>Detail Pembayaran</h3>
                    </div>
                    <div class="info-card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Total Harga Sewa</div>
                                <div class="info-value price">Rp <?= number_format($peminjaman['total_harga'] ?? 0, 0, ',', '.') ?></div>
                            </div>
                            <?php if (($peminjaman['denda'] ?? 0) > 0): ?>
                            <div class="info-item">
                                <div class="info-label">Denda</div>
                                <div class="info-value denda">Rp <?= number_format($peminjaman['denda'], 0, ',', '.') ?></div>
                            </div>
                            <?php endif; ?>
                            <div class="info-item total">
                                <div class="info-label">Total Bayar</div>
                                <div class="info-value total-price">Rp <?= number_format($total_bayar, 0, ',', '.') ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Pengembalian Card (jika sudah selesai) -->
                <?php if ($peminjaman['status'] == 'selesai'): ?>
                <div class="info-card">
                    <div class="info-card-header">
                        <i class="fas fa-undo-alt"></i>
                        <h3>Informasi Pengembalian</h3>
                    </div>
                    <div class="info-card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Kondisi Motor</div>
                                <div class="info-value">
                                    <?php 
                                    $kondisi_class = '';
                                    $kondisi_text = '';
                                    switch ($peminjaman['kondisi']) {
                                        case 'baik': $kondisi_class = 'kondisi-baik'; $kondisi_text = 'Baik'; break;
                                        case 'rusak_ringan': $kondisi_class = 'kondisi-rusak-ringan'; $kondisi_text = 'Rusak Ringan'; break;
                                        case 'rusak_berat': $kondisi_class = 'kondisi-rusak-berat'; $kondisi_text = 'Rusak Berat'; break;
                                        default: $kondisi_class = 'kondisi-baik'; $kondisi_text = 'Baik';
                                    }
                                    ?>
                                    <span class="kondisi-badge <?= $kondisi_class ?>"><?= $kondisi_text ?></span>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Tanggal Pengembalian Input</div>
                                <div class="info-value"><?= date('d F Y H:i', strtotime($peminjaman['tgl_pengembalian_input'])) ?></div>
                            </div>
                        </div>
                        <?php if (!empty($peminjaman['keterangan_pengembalian'])): ?>
                        <div class="info-item full-width">
                            <div class="info-label">Keterangan Pengembalian</div>
                            <div class="info-value"><?= nl2br(htmlspecialchars($peminjaman['keterangan_pengembalian'])) ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Tombol Aksi -->
                <div class="action-buttons">
                    <a href="https://web.craft.co.id/peminjaman" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Detail Peminjaman - Style seperti detail motor */
.detail-peminjaman {
    padding: 60px 0;
    background: #f8fafc;
}

/* Header Card */
.detail-header-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 30px;
    text-align: center;
    color: white;
    margin-bottom: 30px;
    position: relative;
    overflow: hidden;
}

.detail-header-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 2px, transparent 2px);
    background-size: 30px 30px;
    opacity: 0.5;
}

.header-icon {
    width: 70px;
    height: 70px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
    backdrop-filter: blur(5px);
}

.header-icon i {
    font-size: 32px;
}

.detail-header-card h2 {
    font-size: 1.5rem;
    margin-bottom: 15px;
    font-weight: 700;
}

.header-badge {
    display: inline-block;
}

.status-badge-large {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 20px;
    border-radius: 30px;
    font-size: 0.85rem;
    font-weight: 600;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(5px);
}

/* Info Card */
.info-card {
    background: white;
    border-radius: 16px;
    margin-bottom: 20px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.info-card-header {
    background: #f8fafc;
    padding: 15px 20px;
    border-bottom: 1px solid #eef2f6;
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-card-header i {
    font-size: 1.2rem;
    color: #667eea;
}

.info-card-header h3 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: #2d3748;
}

.info-card-body {
    padding: 20px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.info-item {
    background: #f8fafc;
    border-radius: 12px;
    padding: 12px 15px;
    transition: all 0.2s;
}

.info-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.info-item.full-width {
    grid-column: span 2;
}

.info-label {
    font-size: 0.7rem;
    text-transform: uppercase;
    color: #667eea;
    font-weight: 600;
    margin-bottom: 5px;
    letter-spacing: 0.5px;
}

.info-value {
    font-size: 0.95rem;
    font-weight: 500;
    color: #2d3748;
}

.info-value.price {
    color: #e74c3c;
    font-weight: 700;
}

.info-value.denda {
    color: #e53e3e;
    font-weight: 600;
}

.total {
    background: linear-gradient(135deg, #f0fff4 0%, #e6fffa 100%);
    border: 1px solid #c6f6d5;
}

.total .info-value {
    font-size: 1.1rem;
    font-weight: 700;
    color: #38a169;
}

/* Status Badge */
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

/* Kondisi Badge */
.kondisi-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}
.kondisi-baik { background: #d1fae5; color: #065f46; }
.kondisi-rusak-ringan { background: #fef3c7; color: #92400e; }
.kondisi-rusak-berat { background: #fee2e2; color: #991b1b; }

/* Action Buttons */
.action-buttons {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #4a5568;
    padding: 12px 30px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.2s;
}

.btn-back:hover {
    background: #e2e8f0;
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .info-item.full-width {
        grid-column: span 1;
    }
    
    .detail-header-card {
        padding: 20px;
    }
    
    .detail-header-card h2 {
        font-size: 1.2rem;
    }
    
    .info-card-body {
        padding: 15px;
    }
}
</style>

<?php include "../partials/footer.php"; ?>
<?php include "../partials/script.php"; ?>