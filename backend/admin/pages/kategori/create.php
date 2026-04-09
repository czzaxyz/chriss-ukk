<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include '../../partials/header.php';
$page = 'kategori';
include '../../partials/sidebar.php';
include '../../app.php';
?>

<link rel="stylesheet" href="../motor/style.css">

<style>
/* Additional style for form */
.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div id="main">
    <div class="form-card">
        <div class="form-header">
            <div class="form-icon">
                <i class="fas fa-plus-circle"></i>
            </div>
            <h2>Tambah Kategori Baru</h2>
            <p>Isi data kategori dengan lengkap</p>
        </div>
        <div class="form-body">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            
            <form action="../../action/kategori/store.php" method="POST">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-tag"></i> Nama Kategori *</div>
                        <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Matic, Sport, Trail" required>
                        <small class="text-muted" style="font-size: 0.65rem; display: block; margin-top: 5px;">Nama kategori harus unik</small>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-align-left"></i> Deskripsi</div>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi singkat tentang kategori..."></textarea>
                    </div>
                </div>
                
                <div class="detail-footer">
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" name="tombol" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../partials/footer.php'; include '../../partials/script.php'; ?>