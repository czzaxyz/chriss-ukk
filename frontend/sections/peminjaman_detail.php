<?php
session_start();
include "../partials1/header.php";
include "../partials1/navbar.php";
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
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Query detail peminjaman (pastikan milik user yang login)
$query = mysqli_query($connect, "SELECT p.*, b.nama_barang, b.kode_barang, b.merk, b.tahun, b.harga_sewa_perhari, b.deskripsi as motor_deskripsi, k.nama_kategori
    FROM peminjaman p 
    LEFT JOIN barang b ON p.barang_id = b.id 
    LEFT JOIN kategori k ON b.kategori_id = k.id
    WHERE p.id = $id AND p.user_id = $user_id");
$peminjaman = mysqli_fetch_assoc($query);

if (!$peminjaman) {
    echo "<script>
        alert('Data peminjaman tidak ditemukan!');
        window.location.href = 'peminjaman_saya.php';
    </script>";
    exit;
}

// Hitung total bayar
$total_bayar = ($peminjaman['total_harga'] ?? 0) + ($peminjaman['denda'] ?? 0);
?>

<!-- START SECTION TOP -->
<section class="section-top">
    <div class="container">
        <div class="col-lg-10 offset-lg-1 text-center">
            <div class="section-top-title wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.3s" data-wow-offset="0">
                <h1>Detail Peminjaman</h1>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="peminjaman_saya.php">Peminjaman Saya</a></li>
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
                <!-- Status Card -->
                <div class="status-card status-<?= $peminjaman['status'] ?>">
                    <div class="status-icon">
                        <?php 
                        switch ($peminjaman['status']) {
                            case 'pending':
                                echo '<i class="fas fa-clock"></i>';
                                $status_text = 'Menunggu Konfirmasi';
                                break;
                            case 'disetujui':
                                echo '<i class="fas fa-check-circle"></i>';
                                $status_text = 'Disetujui';
                                break;
                            case 'dipinjam':
                                echo '<i class="fas fa-motorcycle"></i>';
                                $status_text = 'Sedang Dipinjam';
                                break;
                            case 'selesai':
                                echo '<i class="fas fa-check-double"></i>';
                                $status_text = 'Selesai';
                                break;
                            case 'ditolak':
                                echo '<i class="fas fa-times-circle"></i>';
                                $status_text = 'Ditolak';
                                break;
                            default:
                                echo '<i class="fas fa-clock"></i>';
                                $status_text = 'Menunggu Konfirmasi';
                        }
                        ?>
                    </div>
                    <div class="status-info">
                        <h3><?= $status_text ?></h3>
                        <p>Kode Peminjaman: <strong><?= htmlspecialchars($peminjaman['kode_peminjaman']) ?></strong></p>
                    </div>
                </div>

                <!-- Detail Card -->
                <div class="detail-card">
                    <div class="detail-header">
                        <h3><i class="fas fa-info-circle"></i> Informasi Peminjaman</h3>
                    </div>
                    <div class="detail-body">
                        <div class="info-row">
                            <div class="info-label">Kode Peminjaman</div>
                            <div class="info-value"><?= htmlspecialchars($peminjaman['kode_peminjaman']) ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Tanggal Peminjaman</div>
                            <div class="info-value"><?= date('d F Y', strtotime($peminjaman['created_at'])) ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Status</div>
                            <div class="info-value">
                                <span class="status-badge status-<?= $peminjaman['status'] ?>">
                                    <?= ucfirst($peminjaman['status']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Motor Card -->
                <div class="detail-card">
                    <div class="detail-header">
                        <h3><i class="fas fa-motorcycle"></i> Informasi Motor</h3>
                    </div>
                    <div class="detail-body">
                        <div class="info-row">
                            <div class="info-label">Nama Motor</div>
                            <div class="info-value"><?= htmlspecialchars($peminjaman['nama_barang']) ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Kode Motor</div>
                            <div class="info-value"><?= htmlspecialchars($peminjaman['kode_barang']) ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Kategori</div>
                            <div class="info-value"><?= htmlspecialchars($peminjaman['nama_kategori'] ?? '-') ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Merk</div>
                            <div class="info-value"><?= htmlspecialchars($peminjaman['merk'] ?? '-') ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Tahun</div>
                            <div class="info-value"><?= $peminjaman['tahun'] ?? '-' ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Harga Sewa/Hari</div>
                            <div class="info-value">Rp <?= number_format($peminjaman['harga_sewa_perhari'], 0, ',', '.') ?></div>
                        </div>
                        <?php if (!empty($peminjaman['motor_deskripsi'])): ?>
                        <div class="info-row">
                            <div class="info-label">Deskripsi Motor</div>
                            <div class="info-value"><?= nl2br(htmlspecialchars($peminjaman['motor_deskripsi'])) ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Detail Peminjaman Card -->
                <div class="detail-card">
                    <div class="detail-header">
                        <h3><i class="fas fa-calendar-alt"></i> Detail Peminjaman</h3>
                    </div>
                    <div class="detail-body">
                        <div class="info-row">
                            <div class="info-label">Tanggal Pinjam</div>
                            <div class="info-value"><?= date('d F Y', strtotime($peminjaman['tgl_pinjam'])) ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Tanggal Kembali Rencana</div>
                            <div class="info-value"><?= date('d F Y', strtotime($peminjaman['tgl_kembali_rencana'])) ?></div>
                        </div>
                        <?php if ($peminjaman['tgl_kembali_aktual']): ?>
                        <div class="info-row">
                            <div class="info-label">Tanggal Kembali Aktual</div>
                            <div class="info-value"><?= date('d F Y', strtotime($peminjaman['tgl_kembali_aktual'])) ?></div>
                        </div>
                        <?php endif; ?>
                        <div class="info-row">
                            <div class="info-label">Lama Pinjam</div>
                            <div class="info-value"><?= $peminjaman['lama_pinjam'] ?> Hari</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Jumlah Motor</div>
                            <div class="info-value"><?= $peminjaman['jumlah'] ?> Unit</div>
                        </div>
                        <?php if (!empty($peminjaman['keterangan'])): ?>
                        <div class="info-row">
                            <div class="info-label">Keterangan Peminjaman</div>
                            <div class="info-value"><?= nl2br(htmlspecialchars($peminjaman['keterangan'])) ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Pembayaran Card -->
                <div class="detail-card">
                    <div class="detail-header">
                        <h3><i class="fas fa-money-bill-wave"></i> Detail Pembayaran</h3>
                    </div>
                    <div class="detail-body">
                        <div class="info-row">
                            <div class="info-label">Total Harga Sewa</div>
                            <div class="info-value">Rp <?= number_format($peminjaman['total_harga'] ?? 0, 0, ',', '.') ?></div>
                        </div>
                        <?php if (($peminjaman['denda'] ?? 0) > 0): ?>
                        <div class="info-row">
                            <div class="info-label">Denda</div>
                            <div class="info-value text-danger">Rp <?= number_format($peminjaman['denda'], 0, ',', '.') ?></div>
                        </div>
                        <?php endif; ?>
                        <div class="info-row total-row">
                            <div class="info-label">Total Bayar</div>
                            <div class="info-value total-price">Rp <?= number_format($total_bayar, 0, ',', '.') ?></div>
                        </div>
                    </div>
                </div>

                <!-- Kondisi Pengembalian Card (jika sudah selesai) -->
                <?php if ($peminjaman['status'] == 'selesai'): ?>
                <div class="detail-card">
                    <div class="detail-header">
                        <h3><i class="fas fa-undo-alt"></i> Informasi Pengembalian</h3>
                    </div>
                    <div class="detail-body">
                        <div class="info-row">
                            <div class="info-label">Kondisi Motor</div>
                            <div class="info-value">
                                <?php 
                                $kondisi_class = '';
                                switch ($peminjaman['kondisi']) {
                                    case 'baik': $kondisi_class = 'kondisi-baik'; break;
                                    case 'rusak_ringan': $kondisi_class = 'kondisi-rusak-ringan'; break;
                                    case 'rusak_berat': $kondisi_class = 'kondisi-rusak-berat'; break;
                                    default: $kondisi_class = 'kondisi-baik';
                                }
                                ?>
                                <span class="kondisi-badge <?= $kondisi_class ?>">
                                    <?= str_replace('_', ' ', ucfirst($peminjaman['kondisi'] ?? 'Baik')) ?>
                                </span>
                            </div>
                        </div>
                        <?php if (!empty($peminjaman['keterangan_pengembalian'])): ?>
                        <div class="info-row">
                            <div class="info-label">Keterangan Pengembalian</div>
                            <div class="info-value"><?= nl2br(htmlspecialchars($peminjaman['keterangan_pengembalian'])) ?></div>
                        </div>
                        <?php endif; ?>
                        <div class="info-row">
                            <div class="info-label">Tanggal Pengembalian Input</div>
                            <div class="info-value"><?= date('d F Y H:i', strtotime($peminjaman['tgl_pengembalian_input'])) ?></div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Tombol Kembali -->
                <div class="back-button">
                    <a href="peminjaman.php" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Kembali ke Peminjaman Saya
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Detail Peminjaman */
.detail-peminjaman {
    padding: 60px 0;
    background: #f8fafc;
}

/* Status Card */
.status-card {
    display: flex;
    align-items: center;
    gap: 20px;
    background: white;
    border-radius: 20px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}

.status-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
}

.status-card.status-pending .status-icon {
    background: #fef3c7;
    color: #92400e;
}
.status-card.status-disetujui .status-icon {
    background: #dbeafe;
    color: #1e40af;
}
.status-card.status-dipinjam .status-icon {
    background: #d1fae5;
    color: #065f46;
}
.status-card.status-selesai .status-icon {
    background: #d1fae5;
    color: #065f46;
}
.status-card.status-ditolak .status-icon {
    background: #fee2e2;
    color: #991b1b;
}

.status-info h3 {
    font-size: 1.3rem;
    margin-bottom: 5px;
}

.status-info p {
    color: #718096;
    margin: 0;
}

/* Detail Card */
.detail-card {
    background: white;
    border-radius: 20px;
    margin-bottom: 25px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}

.detail-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 15px 25px;
    color: white;
}

.detail-header h3 {
    margin: 0;
    font-size: 1.1rem;
}

.detail-header h3 i {
    margin-right: 10px;
}

.detail-body {
    padding: 20px 25px;
}

.info-row {
    display: flex;
    padding: 10px 0;
    border-bottom: 1px solid #f0f2f5;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    width: 180px;
    font-weight: 600;
    color: #4a5568;
}

.info-value {
    flex: 1;
    color: #2d3748;
}

.total-row {
    background: #f0fff4;
    margin-top: 5px;
    padding: 12px 0;
    border-radius: 10px;
}

.total-price {
    font-size: 1.2rem;
    font-weight: 700;
    color: #38a169;
}

/* Status Badge */
.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    display: inline-block;
}
.status-pending { background: #fef3c7; color: #92400e; }
.status-disetujui { background: #dbeafe; color: #1e40af; }
.status-dipinjam { background: #d1fae5; color: #065f46; }
.status-selesai { background: #d1fae5; color: #065f46; }
.status-ditolak { background: #fee2e2; color: #991b1b; }

/* Kondisi Badge */
.kondisi-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    display: inline-block;
}
.kondisi-baik { background: #d1fae5; color: #065f46; }
.kondisi-rusak-ringan { background: #fef3c7; color: #92400e; }
.kondisi-rusak-berat { background: #fee2e2; color: #991b1b; }

/* Back Button */
.back-button {
    text-align: center;
    margin-top: 20px;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #4a5568;
    padding: 12px 25px;
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
    .status-card {
        flex-direction: column;
        text-align: center;
    }
    .info-row {
        flex-direction: column;
    }
    .info-label {
        width: 100%;
        margin-bottom: 5px;
    }
    .detail-header {
        padding: 12px 20px;
    }
    .detail-body {
        padding: 15px 20px;
    }
}
</style>

<?php include "../partials1/footer.php"; ?>
<?php include "../partials1/script.php"; ?>