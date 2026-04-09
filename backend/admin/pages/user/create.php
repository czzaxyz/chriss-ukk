<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include '../../partials/header.php';
$page = 'user';
include '../../partials/sidebar.php';
include '../../app.php';
?>

<link rel="stylesheet" href="../motor/style.css">

<style>
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
                <i class="fas fa-user-plus"></i>
            </div>
            <h2>Tambah User Baru</h2>
            <p>Isi data user dengan lengkap</p>
        </div>
        <div class="form-body">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            
            <form action="../../action/user/store.php" method="POST">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-user"></i> Username *</div>
                        <input type="text" name="username" class="form-control" placeholder="Contoh: john_doe" required>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-lock"></i> Password *</div>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-tag"></i> Role *</div>
                        <select name="role" class="form-select" required>
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="petugas">Petugas</option>
                            <option value="peminjam">Peminjam</option>
                        </select>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-user-circle"></i> Nama Lengkap *</div>
                        <input type="text" name="nama_lengkap" class="form-control" placeholder="Contoh: John Doe" required>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-envelope"></i> Email</div>
                        <input type="email" name="email" class="form-control" placeholder="contoh@email.com">
                        <small class="text-muted" style="font-size: 0.65rem;">Opsional, bisa dikosongkan</small>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-phone"></i> No Telepon</div>
                        <input type="text" name="no_telp" class="form-control" placeholder="Contoh: 08123456789">
                        <small class="text-muted" style="font-size: 0.65rem;">Opsional, bisa dikosongkan</small>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-map-marker-alt"></i> Alamat</div>
                    <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat lengkap..."></textarea>
                    <small class="text-muted" style="font-size: 0.65rem;">Opsional, bisa dikosongkan</small>
                </div>
                
                <div class="detail-footer">
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" name="tombol" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../partials/footer.php'; include '../../partials/script.php'; ?>