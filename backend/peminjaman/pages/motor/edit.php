<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include '../../partials/header.php';
$page = 'motor';
include '../../partials/sidebar.php';
include '../../app.php';

$id = (int)$_GET['id'];
$qMotor = mysqli_query($connect, "SELECT * FROM barang WHERE id = $id");
$motor = mysqli_fetch_object($qMotor);
if (!$motor) header("Location: index.php");

$qKategori = mysqli_query($connect, "SELECT * FROM kategori ORDER BY nama_kategori");
?>

<link rel="stylesheet" href="style.css">

<div id="main">
    <div class="form-card">
        <div class="form-header">
            <div class="form-icon">
                <i class="fas fa-edit"></i>
            </div>
            <h2>Edit Motor</h2>
            <p>Edit data motor yang sudah ada</p>
        </div>
        <div class="form-body">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            
            <form action="../../action/motor/update.php?id=<?= $id ?>" method="POST">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-barcode"></i> Kode Motor *</div>
                        <input type="text" name="kode_barang" class="form-control" value="<?= htmlspecialchars($motor->kode_barang) ?>" required>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-motorcycle"></i> Nama Motor *</div>
                        <input type="text" name="nama_barang" class="form-control" value="<?= htmlspecialchars($motor->nama_barang) ?>" required>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-folder"></i> Kategori *</div>
                        <select name="kategori_id" class="form-select" required>
                            <option value="">Pilih Kategori</option>
                            <?php while($kat = mysqli_fetch_assoc($qKategori)): ?>
                                <option value="<?= $kat['id'] ?>" <?= $motor->kategori_id == $kat['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($kat['nama_kategori']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-trademark"></i> Merk</div>
                        <input type="text" name="merk" class="form-control" value="<?= htmlspecialchars($motor->merk) ?>">
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-calendar"></i> Tahun</div>
                        <input type="number" name="tahun" class="form-control" value="<?= $motor->tahun ?>" min="2000" max="<?= date('Y') ?>">
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-boxes"></i> Stok *</div>
                        <input type="number" name="stok" class="form-control" value="<?= $motor->stok ?>" min="1" required>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-chart-line"></i> Status *</div>
                        <select name="status" class="form-select" required>
                            <option value="tersedia" <?= $motor->status == 'tersedia' ? 'selected' : '' ?>>Tersedia</option>
                            <option value="dipinjam" <?= $motor->status == 'dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
                            <option value="rusak" <?= $motor->status == 'rusak' ? 'selected' : '' ?>>Rusak</option>
                            <option value="hilang" <?= $motor->status == 'hilang' ? 'selected' : '' ?>>Hilang</option>
                        </select>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-money-bill"></i> Harga/Hari *</div>
                        <div class="input-group" style="display: flex;">
                            <span class="input-group-text" style="border-radius: 10px 0 0 10px;">Rp</span>
                            <input type="number" name="harga_sewa_perhari" class="form-control" style="border-radius: 0 10px 10px 0;" value="<?= $motor->harga_sewa_perhari ?>" required min="0" step="1000">
                        </div>
                    </div>
                </div>
                
                <div class="info-item" style="margin-top: 0;">
                    <div class="info-label"><i class="fas fa-align-left"></i> Deskripsi</div>
                    <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($motor->deskripsi) ?></textarea>
                </div>
                
                <div class="detail-footer">
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" name="tombol" class="btn btn-warning">
                        <i class="fas fa-save"></i> Update Motor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../partials/footer.php'; include '../../partials/script.php'; ?>