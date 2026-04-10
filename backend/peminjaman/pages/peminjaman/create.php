<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include '../../partials/header.php';
$page = 'peminjaman';
include '../../partials/sidebar.php';
include '../../app.php';

// Ambil data user (peminjam)
$q_user = mysqli_query($connect, "SELECT id, username, nama_lengkap FROM users WHERE role = 'peminjam' ORDER BY username");
// Ambil data motor yang tersedia
$q_motor = mysqli_query($connect, "SELECT * FROM barang WHERE status = 'tersedia' AND jumlah_tersedia > 0 ORDER BY nama_barang");
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
                <i class="fas fa-plus-circle"></i>
            </div>
            <h2>Tambah Peminjaman Baru</h2>
            <p>Isi data peminjaman dengan lengkap</p>
        </div>
        <div class="form-body">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            
            <form action="../../action/peminjaman/store.php" method="POST">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-user"></i> Peminjam *</div>
                        <select name="user_id" class="form-select" required>
                            <option value="">Pilih Peminjam</option>
                            <?php while($user = mysqli_fetch_assoc($q_user)): ?>
                                <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['username']) ?> (<?= htmlspecialchars($user['nama_lengkap']) ?>)</option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-motorcycle"></i> Motor *</div>
                        <select name="barang_id" class="form-select" required>
                            <option value="">Pilih Motor</option>
                            <?php while($motor = mysqli_fetch_assoc($q_motor)): ?>
                                <option value="<?= $motor['id'] ?>" data-harga="<?= $motor['harga_sewa_perhari'] ?>">
                                    <?= htmlspecialchars($motor['nama_barang']) ?> (<?= htmlspecialchars($motor['kode_barang']) ?>) - Rp <?= number_format($motor['harga_sewa_perhari'], 0, ',', '.') ?> /hari | Stok: <?= $motor['jumlah_tersedia'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-boxes"></i> Jumlah *</div>
                        <input type="number" name="jumlah" class="form-control" value="1" min="1" required>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-calendar"></i> Tanggal Pinjam *</div>
                        <input type="date" name="tgl_pinjam" class="form-control" required>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-calendar-check"></i> Tanggal Kembali *</div>
                        <input type="date" name="tgl_kembali" class="form-control" required>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-money-bill"></i> Total Harga</div>
                        <div class="form-control" id="total_harga_display" style="background: #f0f2f5; font-weight: bold;">Rp 0</div>
                        <input type="hidden" name="total_harga" id="total_harga_input" value="0">
                    </div>
                </div>
                
                <div class="info-item" style="margin-top: 0;">
                    <div class="info-label"><i class="fas fa-align-left"></i> Keterangan</div>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan tambahan..."></textarea>
                </div>
                
                <div class="detail-footer">
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" name="tombol" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Hitung total harga
    const hargaMotor = {};
    <?php 
    mysqli_data_seek($q_motor, 0);
    while($motor = mysqli_fetch_assoc($q_motor)): 
    ?>
        hargaMotor[<?= $motor['id'] ?>] = <?= $motor['harga_sewa_perhari'] ?>;
    <?php endwhile; ?>
    
    function hitungTotal() {
        const motorId = document.querySelector('select[name="barang_id"]').value;
        const jumlah = parseInt(document.querySelector('input[name="jumlah"]').value) || 0;
        const tglPinjam = new Date(document.querySelector('input[name="tgl_pinjam"]').value);
        const tglKembali = new Date(document.querySelector('input[name="tgl_kembali"]').value);
        
        if (motorId && jumlah > 0 && tglPinjam && tglKembali && tglKembali > tglPinjam) {
            const hari = Math.ceil((tglKembali - tglPinjam) / (1000 * 60 * 60 * 24));
            const total = hari * jumlah * (hargaMotor[motorId] || 0);
            document.getElementById('total_harga_display').innerHTML = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('total_harga_input').value = total;
        } else {
            document.getElementById('total_harga_display').innerHTML = 'Rp 0';
            document.getElementById('total_harga_input').value = 0;
        }
    }
    
    document.querySelector('select[name="barang_id"]').addEventListener('change', hitungTotal);
    document.querySelector('input[name="jumlah"]').addEventListener('input', hitungTotal);
    document.querySelector('input[name="tgl_pinjam"]').addEventListener('change', hitungTotal);
    document.querySelector('input[name="tgl_kembali"]').addEventListener('change', hitungTotal);
</script>

<?php include '../../partials/footer.php'; include '../../partials/script.php'; ?>