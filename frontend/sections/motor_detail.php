<?php
session_start();
include "../partials/header.php";
include "../partials/navbar.php";
include "../../config/koneksi.php";

// Cek login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    echo "<script>
        alert('Silakan login terlebih dahulu');
        window.location.href = 'login.php';
    </script>";
    exit;
}

// Ambil parameter (bisa id atau slug)
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$slug = isset($_GET['slug']) ? mysqli_real_escape_string($connect, $_GET['slug']) : '';

// Query berdasarkan slug atau id
if (!empty($slug)) {
    $query = mysqli_query($connect, "SELECT b.*, k.nama_kategori 
        FROM barang b 
        LEFT JOIN kategori k ON b.kategori_id = k.id 
        WHERE b.slug = '$slug'");
} else {
    $query = mysqli_query($connect, "SELECT b.*, k.nama_kategori 
        FROM barang b 
        LEFT JOIN kategori k ON b.kategori_id = k.id 
        WHERE b.id = $id");
}
$motor = mysqli_fetch_assoc($query);

if (!$motor) {
    echo "<script>alert('Motor tidak ditemukan'); window.location.href='motor.php';</script>";
    exit;
}
?>

<!-- START SECTION TOP -->
<section class="section-top">
    <div class="container">
        <div class="col-lg-10 offset-lg-1 text-center">
            <div class="section-top-title wow fadeInRight">
                <h1><?= htmlspecialchars($motor['nama_barang']) ?></h1>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="motor.php">Motor</a></li>
                    <li> / Detail</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- START MOTOR DETAIL -->
<section class="motor-detail section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="motor-image">
                    <img src="assets/img/course/default-motor.jpg" class="img-fluid" alt="<?= htmlspecialchars($motor['nama_barang']) ?>">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="motor-info">
                    <div class="motor-category"><?= htmlspecialchars($motor['nama_kategori']) ?></div>
                    <h2><?= htmlspecialchars($motor['nama_barang']) ?></h2>
                    <div class="motor-price">
                        Rp <?= number_format($motor['harga_sewa_perhari'], 0, ',', '.') ?>
                        <span>/hari</span>
                    </div>
                    <div class="motor-specs-detail">
                        <div class="spec-item">
                            <i class="fas fa-trademark"></i>
                            <span>Merk</span>
                            <strong><?= htmlspecialchars($motor['merk'] ?? 'Umum') ?></strong>
                        </div>
                        <div class="spec-item">
                            <i class="fas fa-calendar"></i>
                            <span>Tahun</span>
                            <strong><?= $motor['tahun'] ?? '-' ?></strong>
                        </div>
                        <div class="spec-item">
                            <i class="fas fa-boxes"></i>
                            <span>Stok</span>
                            <strong><?= $motor['stok'] ?> Unit</strong>
                        </div>
                        <div class="spec-item">
                            <i class="fas fa-chart-line"></i>
                            <span>Status</span>
                            <strong class="status-<?= $motor['status'] ?>"><?= ucfirst($motor['status']) ?></strong>
                        </div>
                    </div>
                    <div class="motor-description">
                        <h4>Deskripsi</h4>
                        <p><?= nl2br(htmlspecialchars($motor['deskripsi'] ?? 'Tidak ada deskripsi')) ?></p>
                    </div>
                    <?php if ($motor['status'] == 'tersedia' && $motor['stok'] > 0): ?>
                    <button class="btn-rent" onclick="openRentModal(<?= $motor['id'] ?>, '<?= addslashes($motor['nama_barang']) ?>', <?= $motor['harga_sewa_perhari'] ?>, <?= $motor['stok'] ?>)">
                        <i class="fas fa-motorcycle"></i> Sewa Motor Ini
                    </button>
                    <?php else: ?>
                    <button class="btn-rent-disabled" disabled>
                        <i class="fas fa-times-circle"></i> Motor Tidak Tersedia
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MODAL SEWA MOTOR -->
<div id="rentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-motorcycle"></i> Form Peminjaman Motor</h3>
            <span class="close" onclick="closeRentModal()">&times;</span>
        </div>
        <form method="POST" action="peminjaman.php" id="rentForm">
            <input type="hidden" name="barang_id" id="barang_id">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Nama Peminjam</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($_SESSION['username']) ?>" disabled>
            </div>
            <div class="form-group">
                <label><i class="fas fa-motorcycle"></i> Motor</label>
                <input type="text" id="motor_name" class="form-control" disabled>
            </div>
            <div class="form-row">
                <div class="form-group half">
                    <label><i class="fas fa-calendar"></i> Tanggal Pinjam *</label>
                    <input type="date" name="tgl_pinjam" id="tgl_pinjam" class="form-control" required>
                </div>
                <div class="form-group half">
                    <label><i class="fas fa-calendar-check"></i> Tanggal Kembali *</label>
                    <input type="date" name="tgl_kembali" id="tgl_kembali" class="form-control" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group half">
                    <label><i class="fas fa-boxes"></i> Jumlah *</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control" value="1" min="1" required>
                </div>
                <div class="form-group half">
                    <label><i class="fas fa-money-bill"></i> Total Harga</label>
                    <div class="total-price" id="total_price">Rp 0</div>
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-align-left"></i> Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan tambahan..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeRentModal()">Batal</button>
                <button type="submit" name="sewa_motor" class="btn-submit">Ajukan Peminjaman</button>
            </div>
        </form>
    </div>
</div>

<style>
.motor-detail {
    padding: 60px 0;
}

.motor-image img {
    width: 100%;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.motor-info {
    padding: 20px;
}

.motor-category {
    display: inline-block;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 5px 15px;
    border-radius: 30px;
    font-size: 0.8rem;
    margin-bottom: 15px;
}

.motor-info h2 {
    font-size: 2rem;
    margin-bottom: 15px;
}

.motor-price {
    font-size: 1.8rem;
    font-weight: 700;
    color: #e74c3c;
    margin-bottom: 20px;
}

.motor-price span {
    font-size: 0.8rem;
    font-weight: normal;
    color: #718096;
}

.motor-specs-detail {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-bottom: 25px;
}

.spec-item {
    background: #f8fafc;
    padding: 12px;
    border-radius: 12px;
}

.spec-item i {
    color: #667eea;
    margin-right: 8px;
}

.spec-item span {
    display: block;
    font-size: 0.7rem;
    color: #718096;
}

.spec-item strong {
    display: block;
    font-size: 1rem;
    margin-top: 5px;
}

.motor-description h4 {
    margin-bottom: 10px;
    font-size: 1.1rem;
}

.btn-rent {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 15px 30px;
    border-radius: 50px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    margin-top: 20px;
}

.btn-rent:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(102,126,234,0.3);
}

.btn-rent-disabled {
    background: #cbd5e0;
    color: #4a5568;
    border: none;
    padding: 15px 30px;
    border-radius: 50px;
    font-size: 1rem;
    font-weight: 600;
    margin-top: 20px;
    cursor: not-allowed;
}

.status-tersedia { color: #48bb78; }
.status-dipinjam { color: #ed8936; }
.status-rusak { color: #e53e3e; }

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    backdrop-filter: blur(5px);
}

.modal-content {
    background: white;
    margin: 5% auto;
    width: 500px;
    max-width: 90%;
    border-radius: 20px;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    border-bottom: 1px solid #eef2f6;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px 20px 0 0;
    color: white;
}

.modal-header h3 {
    margin: 0;
    font-size: 1.2rem;
}

.close {
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    color: white;
    transition: all 0.2s;
}

.close:hover {
    opacity: 0.7;
}

.modal-content .form-group {
    padding: 0 25px;
    margin-bottom: 15px;
}

.form-row {
    display: flex;
    gap: 15px;
    padding: 0 25px;
}

.form-row .form-group {
    padding: 0;
    flex: 1;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    font-size: 0.8rem;
    color: #4a5568;
}

.form-group label i {
    margin-right: 5px;
    color: #667eea;
}

.form-control, .modal-content select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.85rem;
}

.form-control:focus, .modal-content select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
}

.total-price {
    font-size: 1.2rem;
    font-weight: 700;
    color: #667eea;
    background: #f8fafc;
    padding: 10px;
    border-radius: 10px;
    text-align: center;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 20px 25px;
    border-top: 1px solid #eef2f6;
}

.btn-cancel {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #4a5568;
    padding: 10px 20px;
    border-radius: 10px;
    cursor: pointer;
}

.btn-submit {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 10px 25px;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102,126,234,0.3);
}

@media (max-width: 768px) {
    .motor-specs-detail {
        grid-template-columns: 1fr;
    }
    .form-row {
        flex-direction: column;
        gap: 0;
    }
    .modal-content {
        margin: 20% auto;
    }
}
</style>

<script>
let hargaPerHari = 0;
let stokMotor = 0;

function openRentModal(id, name, price, stok) {
    document.getElementById('barang_id').value = id;
    document.getElementById('motor_name').value = name;
    hargaPerHari = price;
    stokMotor = stok;
    
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tgl_pinjam').min = today;
    document.getElementById('tgl_pinjam').value = today;
    document.getElementById('tgl_kembali').min = today;
    document.getElementById('jumlah').max = stok;
    document.getElementById('jumlah').value = 1;
    
    document.getElementById('rentModal').style.display = 'block';
    hitungTotal();
}

function closeRentModal() {
    document.getElementById('rentModal').style.display = 'none';
}

function hitungTotal() {
    const tglPinjam = document.getElementById('tgl_pinjam').value;
    const tglKembali = document.getElementById('tgl_kembali').value;
    const jumlah = parseInt(document.getElementById('jumlah').value) || 1;
    
    if (tglPinjam && tglKembali && tglKembali > tglPinjam) {
        const hari = Math.ceil((new Date(tglKembali) - new Date(tglPinjam)) / (1000 * 60 * 60 * 24));
        const total = hari * jumlah * hargaPerHari;
        document.getElementById('total_price').innerHTML = 'Rp ' + total.toLocaleString('id-ID');
    } else {
        document.getElementById('total_price').innerHTML = 'Rp 0';
    }
}

document.getElementById('tgl_pinjam')?.addEventListener('change', hitungTotal);
document.getElementById('tgl_kembali')?.addEventListener('change', hitungTotal);
document.getElementById('jumlah')?.addEventListener('input', hitungTotal);

window.onclick = function(event) {
    const modal = document.getElementById('rentModal');
    if (event.target == modal) {
        closeRentModal();
    }
}
</script>

<?php include "../partials/footer.php"; ?>
<?php include "../partials/script.php"; ?>