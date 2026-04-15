<?php
include "../partials/header.php";
include "../partials/navbar.php";
?>

<!-- START SECTION TOP -->
<section class="section-top">
    <div class="container">
        <div class="col-lg-10 offset-lg-1 text-center">
            <div class="section-top-title wow fadeInRight">
                <h1>Kebijakan Privasi</h1>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li> / Kebijakan Privasi</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- END SECTION TOP -->

<!-- START KEBIJAKAN PRIVASI -->
<section class="privacy-section section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="privacy-card">
                    <div class="privacy-header">
                        <i class="fas fa-shield-alt"></i>
                        <h2>Kebijakan Privasi</h2>
                        <p>Kami menghargai privasi Anda dan berkomitmen melindungi data pribadi Anda</p>
                    </div>
                    
                    <div class="privacy-content">
                        <div class="privacy-item">
                            <h3><i class="fas fa-database"></i> 1. Informasi yang Kami Kumpulkan</h3>
                            <p>Kami mengumpulkan informasi berikut saat Anda menggunakan layanan kami:</p>
                            <ul>
                                <li><strong>Data Pribadi:</strong> Nama, alamat, nomor telepon, email, KTP, SIM.</li>
                                <li><strong>Data Transaksi:</strong> Riwayat peminjaman, pembayaran, denda.</li>
                                <li><strong>Data Teknis:</strong> Alamat IP, jenis browser, waktu akses.</li>
                            </ul>
                        </div>

                        <div class="privacy-item">
                            <h3><i class="fas fa-chart-line"></i> 2. Penggunaan Informasi</h3>
                            <p>Informasi yang kami kumpulkan digunakan untuk:</p>
                            <ul>
                                <li>Memproses peminjaman motor Anda.</li>
                                <li>Mengirim notifikasi terkait peminjaman.</li>
                                <li>Meningkatkan layanan dan pengalaman pengguna.</li>
                                <li>Memenuhi kewajiban hukum dan peraturan.</li>
                                <li>Menghubungi Anda jika diperlukan.</li>
                            </ul>
                        </div>

                        <div class="privacy-item">
                            <h3><i class="fas fa-lock"></i> 3. Perlindungan Data</h3>
                            <ul>
                                <li>Kami menggunakan enkripsi untuk melindungi data sensitif.</li>
                                <li>Data Anda hanya diakses oleh karyawan yang berwenang.</li>
                                <li>Kami tidak menjual data pribadi Anda kepada pihak ketiga.</li>
                                <li>Server kami dilindungi dengan firewall dan keamanan terbaru.</li>
                            </ul>
                        </div>

                        <div class="privacy-item">
                            <h3><i class="fas fa-share-alt"></i> 4. Berbagi Informasi</h3>
                            <p>Kami dapat membagikan informasi Anda dalam situasi berikut:</p>
                            <ul>
                                <li>Dengan persetujuan Anda.</li>
                                <li>Untuk mematuhi hukum dan peraturan yang berlaku.</li>
                                <li>Untuk melindungi hak dan keamanan kami.</li>
                                <li>Dengan mitra tepercaya yang membantu operasional kami.</li>
                            </ul>
                        </div>

                        <div class="privacy-item">
                            <h3><i class="fas fa-cookie-bite"></i> 5. Cookie</h3>
                            <p>Website kami menggunakan cookie untuk meningkatkan pengalaman pengguna. Cookie membantu kami mengingat preferensi Anda dan menganalisis lalu lintas website. Anda dapat menonaktifkan cookie melalui pengaturan browser.</p>
                        </div>

                        <div class="privacy-item">
                            <h3><i class="fas fa-user-edit"></i> 6. Hak Anda</h3>
                            <ul>
                                <li>Mengakses data pribadi yang kami simpan.</li>
                                <li>Meminta koreksi data yang tidak akurat.</li>
                                <li>Meminta penghapusan data pribadi.</li>
                                <li>Menarik persetujuan Anda kapan saja.</li>
                            </ul>
                        </div>

                        <div class="privacy-item">
                            <h3><i class="fas fa-envelope"></i> 7. Hubungi Kami</h3>
                            <p>Jika Anda memiliki pertanyaan tentang kebijakan privasi ini, silakan hubungi:</p>
                            <ul>
                                <li><i class="fas fa-envelope"></i> Email: privacy@rentalmotor.com</li>
                                <li><i class="fas fa-phone"></i> Telepon: (021) 1234 5678</li>
                                <li><i class="fas fa-map-marker-alt"></i> Alamat: Jl. Motor Raya No. 123, Jakarta</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.privacy-section {
    padding: 60px 0;
    background: #f8fafc;
}

.privacy-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}

.privacy-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 40px;
    text-align: center;
    color: white;
}

.privacy-header i {
    font-size: 50px;
    margin-bottom: 15px;
}

.privacy-header h2 {
    font-size: 1.8rem;
    margin-bottom: 10px;
}

.privacy-content {
    padding: 40px;
}

.privacy-item {
    margin-bottom: 35px;
    padding-bottom: 25px;
    border-bottom: 1px solid #eef2f6;
}

.privacy-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.privacy-item h3 {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 15px;
}

.privacy-item h3 i {
    color: #667eea;
    margin-right: 10px;
}

.privacy-item p {
    color: #4a5568;
    line-height: 1.6;
    margin-bottom: 10px;
}

.privacy-item ul {
    list-style: none;
    padding-left: 30px;
}

.privacy-item ul li {
    position: relative;
    padding-left: 20px;
    margin-bottom: 8px;
    color: #4a5568;
}

.privacy-item ul li::before {
    content: "•";
    color: #667eea;
    position: absolute;
    left: 0;
}

@media (max-width: 768px) {
    .privacy-header {
        padding: 25px;
    }
    .privacy-header h2 {
        font-size: 1.3rem;
    }
    .privacy-content {
        padding: 25px;
    }
}
</style>

<?php include "../partials/footer.php"; ?>
<?php include "../partials/script.php"; ?>