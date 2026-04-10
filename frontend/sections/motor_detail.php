<?php
session_start();
include "../partials1/header.php";
include "../partials1/navbar.php";
include "../../config/koneksi.php";

// Cek login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    echo "<script>
        alert('Silakan login terlebih dahulu');
        window.location.href = 'login.php';
    </script>";
    exit;
}

$id = (int)$_GET['id'];
$query = mysqli_query($connect, "SELECT b.*, k.nama_kategori 
    FROM barang b 
    LEFT JOIN kategori k ON b.kategori_id = k.id 
    WHERE b.id = $id");
$motor = mysqli_fetch_assoc($query);

if (!$motor) {
    echo "<script>alert('Motor tidak ditemukan'); window.location.href='motor_list.php';</script>";
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
                    <li><a href="motor_list.php">Motor</a></li>
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

<!-- MODAL SEWA (sama seperti di motor_list.php) -->
<!-- ... copy modal dari motor_list.php ... -->

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

@media (max-width: 768px) {
    .motor-specs-detail {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Script untuk modal (sama seperti motor_list.php)
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

<?php include "../partials1/footer.php"; ?>
<?php include "../partials1/script.php"; ?>