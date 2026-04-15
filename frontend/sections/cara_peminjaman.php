<?php
include "../partials/header.php";
include "../partials/navbar.php";
?>

<!-- START SECTION TOP -->
<section class="section-top">
    <div class="container">
        <div class="col-lg-10 offset-lg-1 text-center">
            <div class="section-top-title wow fadeInRight">
                <h1>Cara Peminjaman Motor</h1>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li> / Cara Peminjaman</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- END SECTION TOP -->

<!-- START CARA PEMINJAMAN -->
<section class="how-to-section section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="how-to-card">
                    <div class="how-to-header">
                        <i class="fas fa-motorcycle"></i>
                        <h2>Cara Mudah Sewa Motor</h2>
                        <p>Ikuti langkah-langkah berikut untuk menyewa motor</p>
                    </div>
                    
                    <div class="how-to-content">
                        <div class="steps-wrapper">
                            <div class="step-item">
                                <div class="step-number">1</div>
                                <div class="step-content">
                                    <div class="step-icon">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                    <h3>Registrasi Akun</h3>
                                    <p>Buat akun terlebih dahulu dengan mengisi formulir registrasi. Isi data diri dengan lengkap dan benar.</p>
                                    <div class="step-tip">
                                        <i class="fas fa-lightbulb"></i>
                                        <span>Tips: Gunakan email dan nomor telepon aktif untuk memudahkan konfirmasi.</span>
                                    </div>
                                </div>
                            </div>

                            <div class="step-item">
                                <div class="step-number">2</div>
                                <div class="step-content">
                                    <div class="step-icon">
                                        <i class="fas fa-search"></i>
                                    </div>
                                    <h3>Cari Motor</h3>
                                    <p>Pilih motor yang Anda inginkan dari daftar motor yang tersedia. Anda bisa filter berdasarkan kategori, merk, atau harga.</p>
                                    <div class="step-tip">
                                        <i class="fas fa-lightbulb"></i>
                                        <span>Tips: Periksa spesifikasi dan harga sewa sebelum memilih.</span>
                                    </div>
                                </div>
                            </div>

                            <div class="step-item">
                                <div class="step-number">3</div>
                                <div class="step-content">
                                    <div class="step-icon">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <h3>Isi Form Peminjaman</h3>
                                    <p>Klik tombol "Sewa Sekarang" pada motor pilihan Anda. Isi form peminjaman dengan lengkap:</p>
                                    <ul>
                                        <li>Tanggal pinjam</li>
                                        <li>Tanggal kembali</li>
                                        <li>Jumlah motor</li>
                                        <li>Keterangan (opsional)</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="step-item">
                                <div class="step-number">4</div>
                                <div class="step-content">
                                    <div class="step-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <h3>Menunggu Konfirmasi</h3>
                                    <p>Setelah mengajukan peminjaman, status akan "Menunggu Konfirmasi". Admin akan memverifikasi data Anda.</p>
                                    <div class="step-tip">
                                        <i class="fas fa-lightbulb"></i>
                                        <span>Tips: Pastikan nomor telepon Anda aktif untuk dihubungi admin.</span>
                                    </div>
                                </div>
                            </div>

                            <div class="step-item">
                                <div class="step-number">5</div>
                                <div class="step-content">
                                    <div class="step-icon">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <h3>Pembayaran</h3>
                                    <p>Setelah peminjaman disetujui, lakukan pembayaran sesuai total harga yang tertera. Konfirmasi pembayaran ke admin.</p>
                                    <div class="step-tip">
                                        <i class="fas fa-lightbulb"></i>
                                        <span>Tips: Simpan bukti pembayaran untuk referensi.</span>
                                    </div>
                                </div>
                            </div>

                            <div class="step-item">
                                <div class="step-number">6</div>
                                <div class="step-content">
                                    <div class="step-icon">
                                        <i class="fas fa-motorcycle"></i>
                                    </div>
                                    <h3>Ambil Motor</h3>
                                    <p>Datang ke lokasi kami dengan membawa KTP dan SIM asli. Petugas akan memeriksa dokumen dan menyerahkan motor.</p>
                                </div>
                            </div>

                            <div class="step-item">
                                <div class="step-number">7</div>
                                <div class="step-content">
                                    <div class="step-icon">
                                        <i class="fas fa-undo-alt"></i>
                                    </div>
                                    <h3>Pengembalian Motor</h3>
                                    <p>Kembalikan motor tepat waktu sesuai jadwal. Petugas akan memeriksa kondisi motor. Jika ada kerusakan atau keterlambatan, akan dikenakan denda.</p>
                                    <div class="step-tip">
                                        <i class="fas fa-lightbulb"></i>
                                        <span>Tips: Kembalikan motor lebih awal untuk menghindari denda keterlambatan.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="info-box">
                            <h4><i class="fas fa-info-circle"></i> Informasi Penting</h4>
                            <ul>
                                <li>✓ Wajib membawa KTP dan SIM asli saat mengambil motor.</li>
                                <li>✓ Motor harus dikembalikan dalam kondisi yang sama.</li>
                                <li>✓ Bensin ditanggung peminjam selama masa sewa.</li>
                                <li>✓ Denda keterlambatan Rp 50.000/hari.</li>
                                <li>✓ Layanan customer service 24 jam: (021) 1234 5678</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.how-to-section {
    padding: 60px 0;
    background: #f8fafc;
}

.how-to-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}

.how-to-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 40px;
    text-align: center;
    color: white;
}

.how-to-header i {
    font-size: 50px;
    margin-bottom: 15px;
}

.how-to-header h2 {
    font-size: 1.8rem;
    margin-bottom: 10px;
}

.how-to-content {
    padding: 40px;
}

.steps-wrapper {
    display: flex;
    flex-direction: column;
    gap: 30px;
    margin-bottom: 40px;
}

.step-item {
    display: flex;
    gap: 25px;
}

.step-number {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 700;
    flex-shrink: 0;
}

.step-content {
    flex: 1;
}

.step-icon {
    width: 50px;
    height: 50px;
    background: #f0f4ff;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
}

.step-icon i {
    font-size: 24px;
    color: #667eea;
}

.step-content h3 {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 10px;
}

.step-content p {
    color: #4a5568;
    line-height: 1.6;
    margin-bottom: 10px;
}

.step-content ul {
    padding-left: 20px;
    margin-bottom: 10px;
}

.step-content ul li {
    color: #4a5568;
    margin-bottom: 5px;
}

.step-tip {
    background: #fef3c7;
    padding: 10px 15px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 10px;
}

.step-tip i {
    color: #f59e0b;
    font-size: 1rem;
}

.step-tip span {
    color: #92400e;
    font-size: 0.8rem;
}

.info-box {
    background: #e6f7ff;
    border-left: 4px solid #667eea;
    padding: 20px;
    border-radius: 12px;
}

.info-box h4 {
    font-size: 1rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 15px;
}

.info-box h4 i {
    color: #667eea;
    margin-right: 8px;
}

.info-box ul {
    list-style: none;
    padding: 0;
}

.info-box ul li {
    padding: 5px 0;
    color: #4a5568;
}

@media (max-width: 768px) {
    .how-to-header {
        padding: 25px;
    }
    .how-to-header h2 {
        font-size: 1.3rem;
    }
    .how-to-content {
        padding: 25px;
    }
    .step-item {
        flex-direction: column;
        gap: 15px;
    }
    .step-number {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
    }
    .step-content h3 {
        font-size: 1rem;
    }
}
</style>

<?php include "../partials/footer.php"; ?>
<?php include "../partials/script.php"; ?>