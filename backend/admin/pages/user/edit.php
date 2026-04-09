<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include '../../partials/header.php';
$page = 'user';
include '../../partials/sidebar.php';
include '../../app.php';

$id = (int)$_GET['id'];
$qUser = mysqli_query($connect, "SELECT * FROM users WHERE id = $id");
$user = mysqli_fetch_object($qUser);
if (!$user) header("Location: index.php");
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
                <i class="fas fa-user-edit"></i>
            </div>
            <h2>Edit User</h2>
            <p>Edit data user yang sudah ada</p>
        </div>
        <div class="form-body">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            
            <form action="../../action/user/update.php?id=<?= $id ?>" method="POST">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-user"></i> Username *</div>
                        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user->username) ?>" required>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-lock"></i> Password</div>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
                        <small class="text-muted" style="font-size: 0.65rem;">Kosongkan jika tidak ingin mengubah password</small>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-tag"></i> Role *</div>
                        <select name="role" class="form-select" required>
                            <option value="admin" <?= $user->role == 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="petugas" <?= $user->role == 'petugas' ? 'selected' : '' ?>>Petugas</option>
                            <option value="peminjam" <?= $user->role == 'peminjam' ? 'selected' : '' ?>>Peminjam</option>
                        </select>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-user-circle"></i> Nama Lengkap *</div>
                        <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($user->nama_lengkap) ?>" required>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-envelope"></i> Email</div>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user->email ?? '') ?>" placeholder="contoh@email.com">
                        <small class="text-muted" style="font-size: 0.65rem;">Opsional, bisa dikosongkan</small>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-phone"></i> No Telepon</div>
                        <input type="text" name="no_telp" class="form-control" value="<?= htmlspecialchars($user->no_telp ?? '') ?>" placeholder="Contoh: 08123456789">
                        <small class="text-muted" style="font-size: 0.65rem;">Opsional, bisa dikosongkan</small>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-map-marker-alt"></i> Alamat</div>
                    <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat lengkap..."><?= htmlspecialchars($user->alamat ?? '') ?></textarea>
                    <small class="text-muted" style="font-size: 0.65rem;">Opsional, bisa dikosongkan</small>
                </div>
                
                <div class="detail-footer">
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" name="tombol" class="btn btn-warning">
                        <i class="fas fa-save"></i> Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../partials/footer.php'; include '../../partials/script.php'; ?>