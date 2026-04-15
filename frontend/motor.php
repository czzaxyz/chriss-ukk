<?php
// Koneksi ke database
include 'config/koneksi.php';

// Ambil 6 motor terbaru dari database
$query_motor = mysqli_query($connect, "SELECT b.*, k.nama_kategori 
    FROM barang b 
    LEFT JOIN kategori k ON b.kategori_id = k.id 
    WHERE b.status = 'tersedia' 
    ORDER BY b.id DESC 
    LIMIT 6");
?>

<!-- START MOTOR TERBARU -->
<section class="home_course section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="section-title">
                    <span class="subtitle">MOTOR TERBARU</span>
                    <h2>Pilihan Motor <span>Terbaik</span> untuk Perjalanan Anda</h2>
                    <p>Dapatkan motor impian dengan harga terbaik dan kondisi terawat</p>
                </div>
            </div>
        </div><!--- END ROW -->
        
        <div class="row">
            <?php if (mysqli_num_rows($query_motor) > 0): ?>
                <?php while ($motor = mysqli_fetch_assoc($query_motor)): ?>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="single_course">
                            <div class="single_c_img">
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRaTv8EpZfudmW9qVbWoaEFkDa4A0enXrI02w&s" class="img-fluid" alt="<?= htmlspecialchars($motor['nama_barang']) ?>">
                                <span class="category-badge"><?= htmlspecialchars($motor['nama_kategori']) ?></span>
                            </div>
                            <div class="motor-rating">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-half-alt"></i>
                                <span>(4.9)</span>
                            </div>
                            <h4><a href="motor_detail.php?id=<?= $motor['id'] ?>"><?= htmlspecialchars($motor['nama_barang']) ?></a></h4>
                            <div class="motor-specs">
                                <p><i class="fas fa-trademark"></i> <?= htmlspecialchars($motor['merk'] ?? 'Umum') ?></p>
                                <p><i class="fas fa-calendar-alt"></i> <?= $motor['tahun'] ?? '-' ?></p>
                                <p><i class="fas fa-boxes"></i> Stok: <?= $motor['stok'] ?> unit</p>
                            </div>
                            <div class="price">
                                Rp <?= number_format($motor['harga_sewa_perhari'], 0, ',', '.') ?>
                                <span>/hari</span>
                            </div>
                            <a href="frontend/sections/motor_detail.php?id=<?= $motor['id'] ?>" class="btn-rent">
                                <i class="fas fa-motorcycle"></i> Sewa Sekarang
                            </a>
                        </div>
                    </div><!-- END COL -->
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="alert alert-info">Belum ada motor tersedia</div>
                </div>
            <?php endif; ?>
    </div><!--- END CONTAINER -->
</section>
<!-- END MOTOR TERBARU -->
 <style>
	/* ============================================
   MOTOR TERBARU SECTION STYLE
   ============================================ */

.home_course {
    padding: 80px 0;
    background: #f8fafc;
}

.section-title {
    margin-bottom: 50px;
}

.section-title .subtitle {
    display: inline-block;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 5px 20px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    color: white;
    margin-bottom: 15px;
    letter-spacing: 1px;
}

.section-title h2 {
    font-size: 2.2rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 15px;
}

.section-title h2 span {
    color: #667eea;
}

.section-title p {
    font-size: 1rem;
    color: #718096;
    max-width: 600px;
    margin: 0 auto;
}

/* Single Motor Card */
.single_course {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    margin-bottom: 30px;
}

.single_course:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

.single_c_img {
    position: relative;
    overflow: hidden;
}

.single_c_img img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.single_course:hover .single_c_img img {
    transform: scale(1.05);
}

.category-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 5px 15px;
    border-radius: 30px;
    font-size: 0.7rem;
    font-weight: 600;
}

/* Motor Rating */
.motor-rating {
    padding: 15px 20px 5px;
}

.motor-rating i {
    color: #fbbf24;
    font-size: 0.8rem;
    margin-right: 2px;
}

.motor-rating span {
    color: #718096;
    font-size: 0.7rem;
    margin-left: 5px;
}

/* Motor Title */
.single_course h4 {
    padding: 0 20px;
    margin-bottom: 10px;
    font-size: 1.1rem;
    font-weight: 700;
}

.single_course h4 a {
    color: #2d3748;
    text-decoration: none;
    transition: color 0.2s;
}

.single_course h4 a:hover {
    color: #667eea;
}

/* Motor Specs */
.motor-specs {
    padding: 0 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 15px;
}

.motor-specs p {
    font-size: 0.75rem;
    color: #718096;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 5px;
}

.motor-specs i {
    color: #667eea;
    width: 14px;
}

/* Price */
.price {
    padding: 10px 20px;
    font-size: 1.3rem;
    font-weight: 800;
    color: #667eea;
    border-top: 1px solid #eef2f6;
    margin-top: 10px;
}

.price span {
    font-size: 0.7rem;
    font-weight: normal;
    color: #a0aec0;
}

/* Button Rent */
.btn-rent {
    display: block;
    margin: 15px 20px 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-align: center;
    padding: 10px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.3s;
}

.btn-rent:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    color: white;
}

/* Button View All */
.btn-view-all {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: transparent;
    border: 2px solid #667eea;
    color: #667eea;
    padding: 12px 30px;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
}

.btn-view-all:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: transparent;
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .home_course {
        padding: 50px 0;
    }
    
    .section-title h2 {
        font-size: 1.6rem;
    }
    
    .single_c_img img {
        height: 180px;
    }
    
    .motor-specs {
        gap: 10px;
    }
}	
 </style>