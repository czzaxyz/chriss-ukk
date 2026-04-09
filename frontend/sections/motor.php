<?php
include "../partials1/header.php";
include "../partials1/navbar.php";
include "../../config/koneksi.php";

// Ambil parameter
$kategori_filter = isset($_GET['kategori']) ? mysqli_real_escape_string($connect, $_GET['kategori']) : '';
$search = isset($_GET['search']) ? mysqli_real_escape_string($connect, $_GET['search']) : '';

// Pagination
$limit = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Build WHERE clause
$where = "WHERE b.status = 'tersedia'";
if (!empty($kategori_filter)) {
    $where .= " AND LOWER(k.nama_kategori) = '" . strtolower($kategori_filter) . "'";
}
if (!empty($search)) {
    $where .= " AND (b.nama_barang LIKE '%$search%' OR b.merk LIKE '%$search%' OR b.kode_barang LIKE '%$search%')";
}

// Query motor
$query_motor = mysqli_query($connect, "SELECT b.*, k.nama_kategori 
    FROM barang b 
    LEFT JOIN kategori k ON b.kategori_id = k.id 
    $where
    ORDER BY b.id DESC 
    LIMIT $offset, $limit");

// Total data untuk pagination
$query_total = mysqli_query($connect, "SELECT COUNT(*) as total 
    FROM barang b 
    LEFT JOIN kategori k ON b.kategori_id = k.id 
    $where");
$total_data = mysqli_fetch_assoc($query_total)['total'];
$total_pages = ceil($total_data / $limit);

// Ambil semua kategori untuk filter
$query_kategori = mysqli_query($connect, "SELECT * FROM kategori ORDER BY nama_kategori");
?>

<!-- START SECTION TOP -->
<section class="section-top">
    <div class="container">
        <div class="col-lg-10 offset-lg-1 text-center">
            <div class="section-top-title wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.3s" data-wow-offset="0">
                <h1>Koleksi Motor</h1>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li> / Motor</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- END SECTION TOP -->

<!-- START MOTOR LIST -->
<section class="team_area section-padding">
    <div class="container">
        <div class="section-title text-center">
            <h2>Daftar <span>Motor</span> Tersedia</h2>
            <p>Pilih motor favorit Anda dengan harga terbaik dan kondisi terawat</p>
        </div>

        <!-- Grid Motor -->
        <div class="row">
            <?php if (mysqli_num_rows($query_motor) > 0): ?>
                <?php while ($motor = mysqli_fetch_assoc($query_motor)): ?>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.1s" data-wow-offset="0">
                        <div class="our-team">
                            <div class="team-content">
                                <a href="motor_detail.php?id=<?= $motor['id'] ?>">
                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRaTv8EpZfudmW9qVbWoaEFkDa4A0enXrI02w&s" alt="<?= htmlspecialchars($motor['nama_barang']) ?>">
                                </a>
                                <ul class="social-links">
                                    <li><span class="status-badge"><?= ucfirst($motor['status']) ?></span></li>
                                </ul>
                            </div>
                            <div class="team-prof">
                                <h3><a href="motor_detail.php?id=<?= $motor['id'] ?>"><?= htmlspecialchars($motor['nama_barang']) ?></a></h3>
                                <span><?= htmlspecialchars($motor['merk'] ?? 'Umum') ?> • <?= $motor['tahun'] ?? '-' ?></span>
                            </div>
                            <div class="sth_det2">
                                <span class="ti-tag"> <?= htmlspecialchars($motor['nama_kategori']) ?></span>
                                <span class="ti-shopping-cart"> Stok: <?= $motor['stok'] ?></span>
                            </div>
                            <div class="motor-price">
                                Rp <?= number_format($motor['harga_sewa_perhari'], 0, ',', '.') ?>
                                <span class="per-day">/hari</span>
                            </div>
                            <a href="motor_detail.php?id=<?= $motor['id'] ?>" class="btn-rent">
                                Sewa Sekarang <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="empty-state">
                        <i class="fa fa-motorcycle"></i>
                        <h4>Tidak Ada Motor Ditemukan</h4>
                        <p>Coba ubah filter atau cari kata kunci lain</p>
                        <a href="motor.php" class="btn-reset">Lihat Semua Motor</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="pagination-wrapper">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>&kategori=<?= urlencode($kategori_filter) ?>&search=<?= urlencode($search) ?>" class="page-link">&laquo; Sebelumnya</a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <?php if ($i >= $page - 2 && $i <= $page + 2): ?>
                    <a href="?page=<?= $i ?>&kategori=<?= urlencode($kategori_filter) ?>&search=<?= urlencode($search) ?>" class="page-link <?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            
            <?php if ($page < $total_pages): ?>
                <a href="?page=<?= $page + 1 ?>&kategori=<?= urlencode($kategori_filter) ?>&search=<?= urlencode($search) ?>" class="page-link">Selanjutnya &raquo;</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<!-- END MOTOR LIST -->

<style>
/* Filter Section */
.filter-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 30px;
    padding: 15px 20px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}

.filter-categories {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.filter-btn {
    padding: 8px 20px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 30px;
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 600;
    color: #4a5568;
    transition: all 0.3s;
}

.filter-btn:hover, .filter-btn.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: transparent;
}

.search-wrapper .search-form {
    display: flex;
    align-items: center;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 40px;
    overflow: hidden;
}

.search-wrapper input {
    border: none;
    padding: 10px 15px;
    width: 220px;
    outline: none;
    background: transparent;
}

.search-wrapper button {
    background: transparent;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    color: #667eea;
}

.result-info {
    text-align: center;
    margin-bottom: 20px;
    padding: 10px;
    background: #f0fff4;
    border-radius: 10px;
    color: #276749;
    font-size: 0.85rem;
}

.clear-filter {
    color: #e53e3e;
    margin-left: 10px;
    text-decoration: none;
}

.clear-filter:hover {
    text-decoration: underline;
}

.btn-reset {
    display: inline-block;
    margin-top: 15px;
    padding: 10px 25px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
}

/* Motor Price */
.motor-price {
    text-align: center;
    font-size: 1.2rem;
    font-weight: 700;
    color: #e74c3c;
    margin: 10px 0;
}

.motor-price .per-day {
    font-size: 0.7rem;
    font-weight: normal;
    color: #718096;
}

.btn-rent {
    display: block;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-align: center;
    padding: 8px 15px;
    border-radius: 30px;
    margin: 10px 15px;
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-rent:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102,126,234,0.4);
    color: white;
}

.status-badge {
    display: inline-block;
    background: #48bb78;
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}

.team-content {
    position: relative;
}

.team-content .social-links {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background: rgba(0,0,0,0.5);
    border-radius: 20px;
    padding: 5px 10px;
}

.sth_det2 {
    display: flex;
    justify-content: center;
    gap: 15px;
    font-size: 0.75rem;
    color: #718096;
    margin: 10px 0;
}

.sth_det2 span {
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.pagination-wrapper {
    text-align: center;
    margin-top: 30px;
    display: flex;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
}

.page-link {
    display: inline-block;
    padding: 8px 15px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    text-decoration: none;
    color: #4a5568;
    transition: all 0.2s;
}

.page-link:hover, .page-link.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: transparent;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state i {
    font-size: 4rem;
    color: #cbd5e0;
    margin-bottom: 20px;
}

.empty-state h4 {
    font-size: 1.2rem;
    color: #4a5568;
    margin-bottom: 10px;
}

@media (max-width: 768px) {
    .filter-section {
        flex-direction: column;
        align-items: stretch;
    }
    .filter-categories {
        justify-content: center;
    }
    .search-wrapper .search-form input {
        width: 100%;
    }
    .sth_det2 {
        flex-direction: column;
        align-items: center;
        gap: 5px;
    }
}
</style>

<?php include "../partials1/footer.php"; ?>
<?php include "../partials1/script.php"; ?>