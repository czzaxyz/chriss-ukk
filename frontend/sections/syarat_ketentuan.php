<?php
include "../partials/header.php";
include "../partials/navbar.php";
?>

<!-- START SECTION TOP -->
<section class="section-top">
    <div class="container">
        <div class="col-lg-10 offset-lg-1 text-center">
            <div class="section-top-title wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.3s" data-wow-offset="0">
                <h1>Syarat & Ketentuan</h1>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li> / Syarat & Ketentuan</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- END SECTION TOP -->

<!-- START SYARAT & KETENTUAN -->
<section class="terms-section section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="terms-card">
                    <div class="terms-header">
                        <i class="fas fa-file-contract"></i>
                        <h2>Syarat & Ketentuan Peminjaman Motor</h2>
                        <p>Terakhir diperbarui: <?= date('d F Y') ?></p>
                    </div>
                    
                    <div class="terms-content">
                        <div class="terms-item">
                            <h3><i class="fas fa-id-card"></i> 1. Persyaratan Umum</h3>
                            <ul>
                                <li>Peminjam harus berusia minimal 17 tahun.</li>
                                <li>Memiliki Kartu Tanda Penduduk (KTP) yang masih berlaku.</li>
                                <li>Memiliki Surat Izin Mengemudi (SIM) C yang masih berlaku.</li>
                                <li>Mengisi formulir peminjaman dengan data yang benar dan lengkap.</li>
                                <li>Menyetujui semua syarat dan ketentuan yang berlaku.</li>
                            </ul>
                        </div>

                        <div class="terms-item">
                            <h3><i class="fas fa-money-bill-wave"></i> 2. Ketentuan Pembayaran</h3>
                            <ul>
                                <li>Pembayaran sewa motor dilakukan di awal sebelum motor digunakan.</li>
                                <li>Biaya sewa dihitung per hari (24 jam).</li>
                                <li>Denda keterlambatan dikenakan sebesar Rp 50.000 per hari.</li>
                                <li>Denda kerusakan motor akan ditagih sesuai dengan biaya perbaikan.</li>
                                <li>Pembayaran dapat dilakukan melalui transfer bank atau tunai.</li>
                            </ul>
                        </div>

                        <div class="terms-item">
                            <h3><i class="fas fa-gas-pump"></i> 3. Ketentuan Penggunaan Motor</h3>
                            <ul>
                                <li>Motor hanya boleh digunakan di wilayah yang telah ditentukan.</li>
                                <li>Motor tidak boleh digunakan untuk balapan atau kegiatan ilegal.</li>
                                <li>Bensin ditanggung oleh peminjam selama masa sewa.</li>
                                <li>Motor harus dikembalikan dalam kondisi yang sama seperti saat dipinjam.</li>
                                <li>Dilarang meminjamkan motor kepada pihak lain tanpa izin.</li>
                            </ul>
                        </div>

                        <div class="terms-item">
                            <h3><i class="fas fa-shield-alt"></i> 4. Tanggung Jawab</h3>
                            <ul>
                                <li>Peminjam bertanggung jawab penuh atas keamanan motor selama masa sewa.</li>
                                <li>Penyedia tidak bertanggung jawab atas kecelakaan yang terjadi selama peminjaman.</li>
                                <li>Peminjam wajib melaporkan jika terjadi kerusakan atau kecelakaan.</li>
                                <li>Penyedia berhak membatalkan peminjaman jika persyaratan tidak terpenuhi.</li>
                                <li>Peminjam bertanggung jawab atas denda tilang selama masa sewa.</li>
                            </ul>
                        </div>

                        <div class="terms-item">
                            <h3><i class="fas fa-undo-alt"></i> 5. Ketentuan Pengembalian</h3>
                            <ul>
                                <li>Motor harus dikembalikan tepat waktu sesuai kesepakatan.</li>
                                <li>Pengembalian dilakukan di tempat yang sama dengan pengambilan.</li>
                                <li>Perpanjangan sewa harus dikonfirmasi minimal 1 hari sebelumnya.</li>
                                <li>Denda keterlambatan akan dihitung otomatis oleh sistem.</li>
                                <li>Biaya tambahan akan dikenakan jika motor rusak atau hilang.</li>
                            </ul>
                        </div>

                        <div class="terms-item">
                            <h3><i class="fas fa-gavel"></i> 6. Sanksi Pelanggaran</h3>
                            <ul>
                                <li>Pelanggaran terhadap syarat dan ketentuan akan dikenakan sanksi.</li>
                                <li>Peminjam dapat diblokir dari sistem jika melakukan pelanggaran berat.</li>
                                <li>Denda tambahan dapat dikenakan sesuai kebijakan penyedia.</li>
                                <li>Penyedia berhak melaporkan ke pihak berwajib jika terjadi pencurian.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.terms-section {
    padding: 60px 0;
    background: #f8fafc;
}

.terms-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}

.terms-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 40px;
    text-align: center;
    color: white;
}

.terms-header i {
    font-size: 50px;
    margin-bottom: 15px;
}

.terms-header h2 {
    font-size: 1.8rem;
    margin-bottom: 10px;
}

.terms-header p {
    opacity: 0.9;
}

.terms-content {
    padding: 40px;
}

.terms-item {
    margin-bottom: 35px;
    padding-bottom: 25px;
    border-bottom: 1px solid #eef2f6;
}

.terms-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.terms-item h3 {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 15px;
}

.terms-item h3 i {
    color: #667eea;
    margin-right: 10px;
}

.terms-item ul {
    list-style: none;
    padding-left: 30px;
}

.terms-item ul li {
    position: relative;
    padding-left: 20px;
    margin-bottom: 10px;
    color: #4a5568;
    line-height: 1.6;
}

.terms-item ul li::before {
    content: "•";
    color: #667eea;
    font-weight: bold;
    font-size: 1.2rem;
    position: absolute;
    left: 0;
}

@media (max-width: 768px) {
    .terms-header {
        padding: 25px;
    }
    .terms-header h2 {
        font-size: 1.3rem;
    }
    .terms-content {
        padding: 25px;
    }
    .terms-item h3 {
        font-size: 1rem;
    }
}
</style>

<?php include "../partials/footer.php"; ?>
<?php include "../partials/script.php"; ?>