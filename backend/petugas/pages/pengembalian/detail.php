<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include '../../partials/header.php';
$page = 'pengembalian';
include '../../partials/sidebar.php';
include '../../app.php';

$id = (int)$_GET['id'];
$q_detail = mysqli_query($connect, "SELECT p.*, u.username, u.nama_lengkap, u.no_telp, b.nama_barang, b.kode_barang, b.harga_sewa_perhari
    FROM peminjaman p
    LEFT JOIN users u ON p.user_id = u.id
    LEFT JOIN barang b ON p.barang_id = b.id
    WHERE p.id = $id AND p.status = 'selesai'");
$pengembalian = mysqli_fetch_object($q_detail);

if (!$pengembalian) {
    $_SESSION['error'] = "Data pengembalian tidak ditemukan!";
    header("Location: index.php");
    exit;
}

$total_bayar = ($pengembalian->total_harga ?? 0) + ($pengembalian->denda ?? 0);
?>

<link rel="stylesheet" href="../motor/style.css">

<style>
.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
}
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
</style>

<div id="main">
    <div class="detail-card">
        <div class="detail-header">
            <div class="motor-icon"><i class="fas fa-undo-alt"></i></div>
            <h2>Detail Pengembalian</h2>
            <div class="kode-badge"><?= htmlspecialchars($pengembalian->kode_peminjaman) ?></div>
        </div>
        <div class="detail-body">
            <div class="info-grid">
                <div class="info-item"><div class="info-label">Peminjam</div><div class="info-value"><?= htmlspecialchars($pengembalian->username) ?> (<?= htmlspecialchars($pengembalian->nama_lengkap) ?>)</div></div>
                <div class="info-item"><div class="info-label">No. Telepon</div><div class="info-value"><?= htmlspecialchars($pengembalian->no_telp ?? '-') ?></div></div>
                <div class="info-item"><div class="info-label">Motor</div><div class="info-value"><?= htmlspecialchars($pengembalian->nama_barang) ?> (<?= htmlspecialchars($pengembalian->kode_barang) ?>)</div></div>
                <div class="info-item"><div class="info-label">Jumlah</div><div class="info-value"><?= $pengembalian->jumlah ?> Unit</div></div>
                <div class="info-item"><div class="info-label">Tanggal Pinjam</div><div class="info-value"><?= date('d/m/Y', strtotime($pengembalian->tgl_pinjam)) ?></div></div>
                <div class="info-item"><div class="info-label">Tanggal Kembali Rencana</div><div class="info-value"><?= date('d/m/Y', strtotime($pengembalian->tgl_kembali_rencana)) ?></div></div>
                <div class="info-item"><div class="info-label">Tanggal Kembali Aktual</div><div class="info-value"><?= date('d/m/Y', strtotime($pengembalian->tgl_kembali_aktual)) ?></div></div>
                <div class="info-item"><div class="info-label">Lama Pinjam</div><div class="info-value"><?= $pengembalian->lama_pinjam ?> Hari</div></div>
                <div class="info-item"><div class="info-label">Kondisi Motor</div><div class="info-value"><span class="kondisi-badge kondisi-<?= str_replace('_', '-', $pengembalian->kondisi) ?>"><?= str_replace('_', ' ', ucfirst($pengembalian->kondisi)) ?></span></div></div>
                <div class="info-item"><div class="info-label">Total Harga Sewa</div><div class="info-value">Rp <?= number_format($pengembalian->total_harga ?? 0, 0, ',', '.') ?></div></div>
                <div class="info-item"><div class="info-label">Denda</div><div class="info-value">Rp <?= number_format($pengembalian->denda ?? 0, 0, ',', '.') ?></div></div>
                <div class="info-item"><div class="info-label">Total Bayar</div><div class="info-value"><strong class="text-success">Rp <?= number_format($total_bayar, 0, ',', '.') ?></strong></div></div>
            </div>
            <?php if($pengembalian->keterangan): ?>
            <div class="info-item"><div class="info-label">Keterangan Peminjaman</div><div class="info-value"><?= nl2br(htmlspecialchars($pengembalian->keterangan)) ?></div></div>
            <?php endif; ?>
            <?php if($pengembalian->keterangan_pengembalian): ?>
            <div class="info-item"><div class="info-label">Keterangan Pengembalian</div><div class="info-value"><?= nl2br(htmlspecialchars($pengembalian->keterangan_pengembalian)) ?></div></div>
            <?php endif; ?>
            <div class="detail-footer">
                <a href="index.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </div>
</div>

<?php include '../../partials/footer.php'; include '../../partials/script.php'; ?>