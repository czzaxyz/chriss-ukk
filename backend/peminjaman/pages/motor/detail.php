<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include '../../partials/header.php';
$page = 'motor';
include '../../partials/sidebar.php';
include '../../app.php';

$id = (int)$_GET['id'];
$qDetail = mysqli_query($connect, "SELECT b.*, k.nama_kategori 
    FROM barang b LEFT JOIN kategori k ON b.kategori_id = k.id WHERE b.id = $id");
$motor = mysqli_fetch_object($qDetail);
if (!$motor) header("Location: index.php");
?>

<link rel="stylesheet" href="style.css">

<div id="main">
    <div class="detail-card">
        <div class="detail-header">
            <div class="motor-icon"><i class="fas fa-motorcycle"></i></div>
            <h2><?= htmlspecialchars($motor->nama_barang) ?></h2>
            <div class="kode-badge"><?= htmlspecialchars($motor->kode_barang) ?></div>
        </div>
        <div class="detail-body">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-folder"></i> Kategori</div>
                    <div class="info-value"><?= htmlspecialchars($motor->nama_kategori ?? '-') ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-trademark"></i> Merk</div>
                    <div class="info-value"><?= htmlspecialchars($motor->merk ?? '-') ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-calendar"></i> Tahun</div>
                    <div class="info-value"><?= $motor->tahun ?? '-' ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-boxes"></i> Stok</div>
                    <div class="info-value"><?= $motor->stok ?> Unit</div>
                </div>
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-check-circle"></i> Tersedia</div>
                    <div class="info-value"><?= $motor->jumlah_tersedia ?> Unit</div>
                </div>
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-chart-line"></i> Status</div>
                    <div class="info-value">
                        <span class="status-badge status-<?= $motor->status ?>"><?= ucfirst($motor->status) ?></span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-money-bill"></i> Harga/Hari</div>
                    <div class="info-value">Rp <?= number_format($motor->harga_sewa_perhari, 0, ',', '.') ?></div>
                </div>
            </div>
            <?php if($motor->deskripsi): ?>
            <div class="info-item" style="margin-top: 0;">
                <div class="info-label"><i class="fas fa-align-left"></i> Deskripsi</div>
                <div class="info-value" style="font-weight: normal;"><?= nl2br(htmlspecialchars($motor->deskripsi)) ?></div>
            </div>
            <?php endif; ?>
            <div class="detail-footer">
                <a href="index.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<?php include '../../partials/footer.php'; include '../../partials/script.php'; ?>