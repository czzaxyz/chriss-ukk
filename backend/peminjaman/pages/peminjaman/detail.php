<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include '../../partials/header.php';
$page = 'peminjaman';
include '../../partials/sidebar.php';
include '../../app.php';

$id = (int)$_GET['id'];
$q_detail = mysqli_query($connect, "SELECT p.*, u.username, u.nama_lengkap, u.no_telp, b.nama_barang, b.kode_barang, b.harga_sewa_perhari
    FROM peminjaman p
    LEFT JOIN users u ON p.user_id = u.id
    LEFT JOIN barang b ON p.barang_id = b.id
    WHERE p.id = $id");
$peminjaman = mysqli_fetch_object($q_detail);
if (!$peminjaman) header("Location: index.php");
?>

<link rel="stylesheet" href="../motor/style.css">

<div id="main">
    <div class="detail-card">
        <div class="detail-header">
            <div class="motor-icon"><i class="fas fa-handshake"></i></div>
            <h2>Detail Peminjaman</h2>
            <div class="kode-badge"><?= htmlspecialchars($peminjaman->kode_peminjaman) ?></div>
        </div>
        <div class="detail-body">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Peminjam</div>
                    <div class="info-value"><?= htmlspecialchars($peminjaman->username) ?> (<?= htmlspecialchars($peminjaman->nama_lengkap) ?>)</div>
                </div>
                <div class="info-item">
                    <div class="info-label">No. Telepon</div>
                    <div class="info-value"><?= htmlspecialchars($peminjaman->no_telp ?? '-') ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Motor</div>
                    <div class="info-value"><?= htmlspecialchars($peminjaman->nama_barang) ?> (<?= htmlspecialchars($peminjaman->kode_barang) ?>)</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Jumlah</div>
                    <div class="info-value"><?= $peminjaman->jumlah ?> Unit</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal Pinjam</div>
                    <div class="info-value"><?= date('d/m/Y', strtotime($peminjaman->tgl_pinjam)) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal Kembali Rencana</div>
                    <div class="info-value"><?= date('d/m/Y', strtotime($peminjaman->tgl_kembali_rencana)) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal Kembali Aktual</div>
                    <div class="info-value"><?= $peminjaman->tgl_kembali_aktual ? date('d/m/Y', strtotime($peminjaman->tgl_kembali_aktual)) : '-' ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Lama Pinjam</div>
                    <div class="info-value"><?= $peminjaman->lama_pinjam ?> Hari</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value"><span class="status-badge status-<?= $peminjaman->status ?>"><?= ucfirst($peminjaman->status) ?></span></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Total Harga</div>
                    <div class="info-value">Rp <?= number_format($peminjaman->total_harga ?? 0, 0, ',', '.') ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Denda</div>
                    <div class="info-value">Rp <?= number_format($peminjaman->denda ?? 0, 0, ',', '.') ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Kondisi</div>
                    <div class="info-value"><?= ucfirst($peminjaman->kondisi ?? '-') ?></div>
                </div>
            </div>
            <?php if ($peminjaman->keterangan): ?>
                <div class="info-item">
                    <div class="info-label">Keterangan</div>
                    <div class="info-value"><?= nl2br(htmlspecialchars($peminjaman->keterangan)) ?></div>
                </div>
            <?php endif; ?>
            <?php if ($peminjaman->keterangan_pengembalian): ?>
                <div class="info-item">
                    <div class="info-label">Keterangan Pengembalian</div>
                    <div class="info-value"><?= nl2br(htmlspecialchars($peminjaman->keterangan_pengembalian)) ?></div>
                </div>
            <?php endif; ?>
            <div class="detail-footer">
                <a href="index.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                <?php if ($peminjaman->status == 'dipinjam'): ?>
                    <a href="../../action/peminjaman/return.php?id=<?= $peminjaman->id ?>" class="btn btn-warning">
                        <i class="fas fa-undo-alt"></i> Pengembalian
                    </a>
                <?php endif; ?>
                
    </div>
</div>

<?php include '../../partials/footer.php';
include '../../partials/script.php'; ?>