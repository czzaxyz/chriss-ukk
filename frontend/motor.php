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
<section class="motor-terbaru section-padding">
    <div class="container">
        <div class="section-header text-center">
            <span class="badge-section">🔥 HOT DEALS</span>
            <h2>Motor <span class="highlight">Terbaru</span> & Terpopuler</h2>
            <p>Dapatkan pengalaman berkendara terbaik dengan motor-motor pilihan kami</p>
        </div>

        <div class="motor-grid">
            <?php if (mysqli_num_rows($query_motor) > 0): ?>
                <?php while ($motor = mysqli_fetch_assoc($query_motor)): ?>
                    <div class="motor-card">
                        <div class="motor-card-inner">
                            <div class="motor-image">
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRaTv8EpZfudmW9qVbWoaEFkDa4A0enXrI02w&s" alt="<?= htmlspecialchars($motor['nama_barang']) ?>">
                                <div class="motor-badge">
                                    <span class="badge-category"><?= htmlspecialchars($motor['nama_kategori']) ?></span>
                                    <?php if ($motor['stok'] <= 3): ?>
                                        <span class="badge-stock limited">Stok Terbatas</span>
                                    <?php endif; ?>
                                </div>
                                <div class="motor-overlay">
                                    <a href="detail-motor/<?= $motor['slug'] ?>" class="btn-overlay">
                                        <i class="fas fa-eye"></i> Lihat Detail
                                    </a>
                                </div>
                            </div>
                            <div class="motor-content">
                                <div class="motor-header">
                                    <h3><?= htmlspecialchars($motor['nama_barang']) ?></h3>
                                    <div class="rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                        <span>4.9</span>
                                    </div>
                                </div>
                                <div class="motor-spec">
                                    <div class="spec-item">
                                        <i class="fas fa-trademark"></i>
                                        <span><?= htmlspecialchars($motor['merk'] ?? 'Umum') ?></span>
                                    </div>
                                    <div class="spec-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span><?= $motor['tahun'] ?? '-' ?></span>
                                    </div>
                                    <div class="spec-item">
                                        <i class="fas fa-box"></i>
                                        <span>Stok: <?= $motor['stok'] ?> unit</span>
                                    </div>
                                </div>
                                <a href="detail-motor/<?= $motor['slug'] ?>" class="btn-rent-now">
                                    <span>Sewa Sekarang</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-motorcycle"></i>
                    <h4>Belum Ada Motor Tersedia</h4>
                    <p>Silakan cek kembali nanti</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
/* ============================================
   MOTOR TERBARU SECTION - MODERN STYLE
   ============================================ */

.motor-terbaru {
    padding: 80px 0;
    background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
}

.section-header {
    margin-bottom: 50px;
}

.badge-section {
    display: inline-block;
    background: linear-gradient(135deg, #ff6b6b, #ee5a24);
    padding: 6px 20px;
    border-radius: 30px;
    font-size: 0.7rem;
    font-weight: 700;
    color: white;
    letter-spacing: 1px;
    margin-bottom: 15px;
    box-shadow: 0 4px 15px rgba(238, 90, 36, 0.3);
}

.section-header h2 {
    font-size: 2.2rem;
    font-weight: 800;
    color: #1a202c;
    margin-bottom: 15px;
}

.section-header h2 .highlight {
    background: linear-gradient(135deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.section-header p {
    font-size: 1rem;
    color: #718096;
    max-width: 600px;
    margin: 0 auto;
}

/* Motor Grid */
.motor-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

/* Motor Card */
.motor-card {
    background: white;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    transition: all 0.4s ease;
}

.motor-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.motor-card-inner {
    position: relative;
}

/* Motor Image */
.motor-image {
    position: relative;
    overflow: hidden;
    height: 240px;
}

.motor-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.motor-card:hover .motor-image img {
    transform: scale(1.1);
}

.motor-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.badge-category {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}

.badge-stock {
    background: #fef3c7;
    color: #92400e;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 600;
}

.badge-stock.limited {
    background: #fee2e2;
    color: #991b1b;
}

/* Overlay */
.motor-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.motor-card:hover .motor-overlay {
    opacity: 1;
}

.btn-overlay {
    background: white;
    color: #667eea;
    padding: 10px 20px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.3s;
}

.btn-overlay:hover {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    transform: scale(1.05);
}

/* Motor Content */
.motor-content {
    padding: 20px;
}

.motor-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.motor-header h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
}

.rating {
    display: flex;
    align-items: center;
    gap: 3px;
}

.rating i {
    font-size: 0.7rem;
    color: #fbbf24;
}

.rating span {
    font-size: 0.7rem;
    color: #718096;
    margin-left: 4px;
}

/* Motor Spec */
.motor-spec {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eef2f6;
}

.spec-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.7rem;
    color: #718096;
}

.spec-item i {
    color: #667eea;
    font-size: 0.7rem;
}

/* Motor Price */
.motor-price {
    margin-bottom: 15px;
}

.price-label {
    font-size: 0.65rem;
    color: #a0aec0;
    display: block;
}

.price-value {
    font-size: 1.3rem;
    font-weight: 800;
    color: #667eea;
}

.per-day {
    font-size: 0.7rem;
    font-weight: normal;
    color: #a0aec0;
}

/* Button Rent */
.btn-rent-now {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    text-decoration: none;
    padding: 10px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.3s;
    margin-top: 5px;
}

.btn-rent-now:hover {
    gap: 15px;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    color: white;
}

/* View More Button */
.btn-view-more {
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

.btn-view-more:hover {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    gap: 15px;
    border-color: transparent;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 20px;
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

/* Responsive */
@media (max-width: 1024px) {
    .motor-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .motor-terbaru {
        padding: 50px 0;
    }
    
    .section-header h2 {
        font-size: 1.6rem;
    }
    
    .motor-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .motor-image {
        height: 200px;
    }
}

@media (max-width: 480px) {
    .motor-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
    
    .motor-spec {
        flex-wrap: wrap;
        gap: 10px;
    }
}
</style>