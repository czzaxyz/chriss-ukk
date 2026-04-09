<?php
// Koneksi ke database
include 'config/koneksi.php';

// Ambil semua kategori dari database (LIMIT 6 untuk 3x2)
$query_kategori = mysqli_query($connect, "SELECT * FROM kategori ORDER BY id ASC LIMIT 6");

// Hitung jumlah motor per kategori
$query_count = mysqli_query($connect, "SELECT kategori_id, COUNT(*) as total FROM barang GROUP BY kategori_id");
$motor_count = [];
while ($row = mysqli_fetch_assoc($query_count)) {
    $motor_count[$row['kategori_id']] = $row['total'];
}
?>

<!-- START CATEGORY TWO -->
<section class="category_two_area section-padding">
    <div class="container">
        <div class="section-title text-center">
            <span class="subtitle">KATEGORI MOTOR</span>
            <h2>Pilih <span>Kategori</span> Motor Sesuai Kebutuhan</h2>
            <p>Tersedia berbagai pilihan kategori motor berkualitas yang siap menemani perjalanan Anda</p>
        </div>
        
        <div class="row">
            <?php if (mysqli_num_rows($query_kategori) > 0): ?>
                <?php 
                $icons = [
                    'Matic' => 'fas fa-motorcycle',
                    'Trail/Offroad' => 'fas fa-mountain',
                    'Sport' => 'fas fa-bolt',
                    'Bebek' => 'fas fa-tachometer-alt',
                    'Skuter' => 'fas fa-thin fa-motorcycle',
                    'Classic' => 'fas fa-crown'
                ];
                $colors = [
                    'Matic' => '#3498db',
                    'Trail/Offroad' => '#27ae60',
                    'Sport' => '#e74c3c',
                    'Bebek' => '#f39c12',
                    'Skuter' => '#9b59b6',
                    'Classic' => '#e67e22'
                ];
                ?>
                <?php while ($kategori = mysqli_fetch_assoc($query_kategori)): 
                    $nama_kategori = $kategori['nama_kategori'];
                    $icon = isset($icons[$nama_kategori]) ? $icons[$nama_kategori] : 'fas fa-tag';
                    $color = isset($colors[$nama_kategori]) ? $colors[$nama_kategori] : '#667eea';
                    $total_motor = isset($motor_count[$kategori['id']]) ? $motor_count[$kategori['id']] : 0;
                ?>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.1s" data-wow-offset="0">
                        <div class="cat_list_two">
                            <div class="category-icon" style="background: <?= $color ?>20;">
                                <i class="<?= $icon ?>" style="color: <?= $color ?>;"></i>
                            </div>
                            <span class="motor-count"><?= $total_motor ?> Motor</span>
                            <h4><a href="motor.php?kategori=<?= urlencode(strtolower($nama_kategori)) ?>"><?= htmlspecialchars($nama_kategori) ?></a></h4>
                            <p><?= htmlspecialchars(substr($kategori['deskripsi'] ?? 'Tersedia berbagai pilihan motor berkualitas', 0, 80)) ?>...</p>
                        </div>
                    </div><!--- END COL -->
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="alert alert-info">Belum ada kategori motor</div>
                </div>
            <?php endif; ?>
        </div><!--- END ROW -->
    </div><!--- END CONTAINER -->
</section>
<!-- END CATEGORY TWO -->
<style>
	/* ============================================
   CATEGORY SECTION STYLE - 3x2 GRID
   ============================================ */

.category_two_area {
    padding: 80px 0;
    background: white;
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

/* Category Card */
.cat_list_two {
    background: white;
    border-radius: 20px;
    padding: 30px 20px;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    margin-bottom: 30px;
    border: 1px solid #eef2f6;
    height: 100%;
}

.cat_list_two:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    border-color: transparent;
}

/* Category Icon */
.category-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    transition: all 0.3s;
}

.category-icon i {
    font-size: 36px;
    transition: all 0.3s;
}

.cat_list_two:hover .category-icon {
    transform: scale(1.1);
}

/* Motor Count */
.motor-count {
    display: inline-block;
    background: #f8fafc;
    padding: 4px 15px;
    border-radius: 30px;
    font-size: 0.7rem;
    font-weight: 600;
    color: #667eea;
    margin-bottom: 15px;
}

/* Category Title */
.cat_list_two h4 {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 12px;
}

.cat_list_two h4 a {
    color: #2d3748;
    text-decoration: none;
    transition: color 0.2s;
}

.cat_list_two h4 a:hover {
    color: #667eea;
}

/* Category Description */
.cat_list_two p {
    font-size: 0.85rem;
    color: #718096;
    line-height: 1.5;
    margin-bottom: 20px;
}

/* Button Category */
.btn-category {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: transparent;
    color: #667eea;
    font-weight: 600;
    font-size: 0.8rem;
    text-decoration: none;
    transition: all 0.3s;
    padding: 8px 0;
}

.btn-category i {
    font-size: 0.7rem;
    transition: transform 0.3s;
}

.btn-category:hover {
    color: #764ba2;
}

.btn-category:hover i {
    transform: translateX(5px);
}

/* Responsive */
@media (max-width: 992px) {
    .category_two_area {
        padding: 60px 0;
    }
    
    .section-title h2 {
        font-size: 1.8rem;
    }
    
    .cat_list_two {
        padding: 25px 15px;
    }
    
    .category-icon {
        width: 60px;
        height: 60px;
    }
    
    .category-icon i {
        font-size: 28px;
    }
}

@media (max-width: 768px) {
    .category_two_area {
        padding: 40px 0;
    }
    
    .section-title h2 {
        font-size: 1.5rem;
    }
}

@media (max-width: 576px) {
    .col-xs-12 {
        width: 100%;
    }
}
</style>