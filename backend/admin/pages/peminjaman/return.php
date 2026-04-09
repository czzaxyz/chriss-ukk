<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include '../../partials/header.php';
$page = 'peminjaman';
include '../../partials/sidebar.php';
include '../../app.php';

$id = (int)$_GET['id'];
$q_peminjaman = mysqli_query($connect, "SELECT p.*, b.harga_sewa_perhari, b.nama_barang, b.kode_barang, u.username, u.nama_lengkap
    FROM peminjaman p 
    LEFT JOIN barang b ON p.barang_id = b.id 
    LEFT JOIN users u ON p.user_id = u.id
    WHERE p.id = $id");
$peminjaman = mysqli_fetch_object($q_peminjaman);

if (!$peminjaman) {
    $_SESSION['error'] = "Data peminjaman tidak ditemukan!";
    header("Location: index.php");
    exit;
}

// Hitung denda jika telat
$tgl_kembali_rencana = new DateTime($peminjaman->tgl_kembali_rencana);
$tgl_sekarang = new DateTime();
$telat_hari = $tgl_sekarang > $tgl_kembali_rencana ? $tgl_sekarang->diff($tgl_kembali_rencana)->days : 0;
$denda_otomatis = $telat_hari * $peminjaman->jumlah * 50000;
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
.info-denda {
    background: #fff5f5;
    border-left: 4px solid #fc8181;
}
.info-bayar {
    background: #d1fae5;
    border-left: 4px solid #10b981;
}
</style>

<div id="main">
    <div class="form-card">
        <div class="form-header">
            <div class="form-icon">
                <i class="fas fa-undo-alt"></i>
            </div>
            <h2>Form Pengembalian Motor</h2>
            <p>Isi data pengembalian motor dengan lengkap</p>
        </div>
        <div class="form-body">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            
            <!-- Informasi Peminjaman -->
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-barcode"></i> Kode Peminjaman</div>
                    <div class="info-value"><?= htmlspecialchars($peminjaman->kode_peminjaman) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-user"></i> Peminjam</div>
                    <div class="info-value"><?= htmlspecialchars($peminjaman->username) ?> (<?= htmlspecialchars($peminjaman->nama_lengkap) ?>)</div>
                </div>
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-motorcycle"></i> Motor</div>
                    <div class="info-value"><?= htmlspecialchars($peminjaman->nama_barang) ?> (<?= htmlspecialchars($peminjaman->kode_barang) ?>)</div>
                </div>
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-boxes"></i> Jumlah</div>
                    <div class="info-value"><?= $peminjaman->jumlah ?> Unit</div>
                </div>
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-calendar"></i> Tanggal Pinjam</div>
                    <div class="info-value"><?= date('d/m/Y', strtotime($peminjaman->tgl_pinjam)) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-calendar-check"></i> Tanggal Kembali Rencana</div>
                    <div class="info-value"><?= date('d/m/Y', strtotime($peminjaman->tgl_kembali_rencana)) ?></div>
                </div>
                <div class="info-item info-denda">
                    <div class="info-label"><i class="fas fa-clock"></i> Keterlambatan</div>
                    <div class="info-value"><?= $telat_hari ?> hari</div>
                </div>
                <div class="info-item info-denda">
                    <div class="info-label"><i class="fas fa-money-bill"></i> Denda Otomatis</div>
                    <div class="info-value">Rp <?= number_format($denda_otomatis, 0, ',', '.') ?></div>
                    <small class="text-muted">Rp 50.000/hari/unit</small>
                </div>
            </div>
            
            <form action="../../action/peminjaman/return_process.php?id=<?= $id ?>" method="POST">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-tools"></i> Kondisi Motor *</div>
                        <select name="kondisi" class="form-select" required>
                            <option value="baik">Baik</option>
                            <option value="rusak_ringan">Rusak Ringan</option>
                            <option value="rusak_berat">Rusak Berat</option>
                        </select>
                        <small class="text-muted">Pilih kondisi motor saat dikembalikan</small>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-money-bill-wave"></i> Denda Manual (Opsional)</div>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="denda_manual" class="form-control" value="0" min="0" step="1000">
                        </div>
                        <small class="text-muted">Tambahan denda selain denda keterlambatan</small>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-align-left"></i> Keterangan Pengembalian</div>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Motor dalam kondisi baik, ada goresan sedikit..."></textarea>
                </div>
                
                <div class="info-item info-bayar">
                    <div class="info-label"><i class="fas fa-calculator"></i> Estimasi Total Bayar</div>
                    <div class="info-value" id="total_bayar_display">Rp <?= number_format($denda_otomatis, 0, ',', '.') ?></div>
                    <small class="text-muted">Total Harga Sewa + Denda (akan dihitung otomatis oleh sistem)</small>
                </div>
                
                <div class="detail-footer">
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" name="tombol" class="btn btn-warning">
                        <i class="fas fa-save"></i> Proses Pengembalian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Hitung ulang estimasi total bayar
    const dendaOtomatis = <?= $denda_otomatis ?>;
    
    function hitungTotalBayar() {
        const dendaManual = parseInt(document.querySelector('input[name="denda_manual"]').value) || 0;
        const total = dendaOtomatis + dendaManual;
        document.getElementById('total_bayar_display').innerHTML = 'Rp ' + total.toLocaleString('id-ID');
    }
    
    document.querySelector('input[name="denda_manual"]').addEventListener('input', hitungTotalBayar);
</script>

<?php include '../../partials/footer.php'; include '../../partials/script.php'; ?>