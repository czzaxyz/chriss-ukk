<?php
include "../partials/header.php";
include "../partials/navbar.php";
?>

<!-- START SECTION TOP -->
<section class="section-top">
    <div class="container">
        <div class="col-lg-10 offset-lg-1 text-center">
            <div class="section-top-title wow fadeInRight">
                <h1>Frequently Asked Questions</h1>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li> / FAQ</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- END SECTION TOP -->

<!-- START FAQ -->
<section class="faq-section section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="faq-card">
                    <div class="faq-header">
                        <i class="fas fa-question-circle"></i>
                        <h2>Pertanyaan yang Sering Diajukan</h2>
                        <p>Temukan jawaban atas pertanyaan umum tentang layanan kami</p>
                    </div>
                    
                    <div class="faq-content">
                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFaq(this)">
                                <h3><i class="fas fa-chevron-right"></i> Apa saja dokumen yang diperlukan untuk menyewa motor?</h3>
                                <i class="fas fa-chevron-down toggle-icon"></i>
                            </div>
                            <div class="faq-answer">
                                <p>Dokumen yang diperlukan untuk menyewa motor adalah:</p>
                                <ul>
                                    <li>Kartu Tanda Penduduk (KTP) asli</li>
                                    <li>Surat Izin Mengemudi (SIM) C asli</li>
                                    <li>Foto copy KTP dan SIM (akan difotokopi oleh petugas)</li>
                                </ul>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFaq(this)">
                                <h3><i class="fas fa-chevron-right"></i> Berapa minimal usia untuk menyewa motor?</h3>
                                <i class="fas fa-chevron-down toggle-icon"></i>
                            </div>
                            <div class="faq-answer">
                                <p>Minimal usia untuk menyewa motor adalah 17 tahun dan sudah memiliki SIM C yang berlaku.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFaq(this)">
                                <h3><i class="fas fa-chevron-right"></i> Apakah bisa memperpanjang masa sewa?</h3>
                                <i class="fas fa-chevron-down toggle-icon"></i>
                            </div>
                            <div class="faq-answer">
                                <p>Ya, Anda dapat memperpanjang masa sewa. Silakan hubungi customer service kami minimal 1 hari sebelum masa sewa berakhir. Perpanjangan dikenakan biaya sesuai harga sewa normal.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFaq(this)">
                                <h3><i class="fas fa-chevron-right"></i> Bagaimana jika motor mengalami kerusakan saat disewa?</h3>
                                <i class="fas fa-chevron-down toggle-icon"></i>
                            </div>
                            <div class="faq-answer">
                                <p>Jika motor mengalami kerusakan, segera hubungi customer service kami. Kami akan memberikan bantuan. Biaya perbaikan akibat kelalaian peminjam akan ditanggung oleh peminjam.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFaq(this)">
                                <h3><i class="fas fa-chevron-right"></i> Apakah ada denda jika telat mengembalikan motor?</h3>
                                <i class="fas fa-chevron-down toggle-icon"></i>
                            </div>
                            <div class="faq-answer">
                                <p>Ya, denda keterlambatan adalah Rp 50.000 per hari. Denda akan dihitung otomatis oleh sistem saat pengembalian.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFaq(this)">
                                <h3><i class="fas fa-chevron-right"></i> Metode pembayaran apa saja yang diterima?</h3>
                                <i class="fas fa-chevron-down toggle-icon"></i>
                            </div>
                            <div class="faq-answer">
                                <p>Kami menerima pembayaran melalui:</p>
                                <ul>
                                    <li>Transfer Bank (BCA, Mandiri, BRI, BNI)</li>
                                    <li>Tunai (di tempat)</li>
                                    <li>QRIS (Dana, OVO, GoPay, ShopeePay)</li>
                                </ul>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFaq(this)">
                                <h3><i class="fas fa-chevron-right"></i> Apakah motor dilengkapi dengan helm?</h3>
                                <i class="fas fa-chevron-down toggle-icon"></i>
                            </div>
                            <div class="faq-answer">
                                <p>Ya, setiap penyewaan motor dilengkapi dengan 2 helm gratis. Helm dalam kondisi bersih dan layak pakai.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFaq(this)">
                                <h3><i class="fas fa-chevron-right"></i> Apakah bisa sewa motor untuk luar kota?</h3>
                                <i class="fas fa-chevron-down toggle-icon"></i>
                            </div>
                            <div class="faq-answer">
                                <p>Ya, Anda bisa menggunakan motor untuk perjalanan luar kota. Namun, harap informasikan tujuan Anda kepada petugas untuk keperluan dokumentasi.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFaq(this)">
                                <h3><i class="fas fa-chevron-right"></i> Apakah ada layanan antar jemput motor?</h3>
                                <i class="fas fa-chevron-down toggle-icon"></i>
                            </div>
                            <div class="faq-answer">
                                <p>Ya, kami menyediakan layanan antar jemput motor dengan biaya tambahan sesuai jarak. Silakan hubungi customer service untuk informasi lebih lanjut.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFaq(this)">
                                <h3><i class="fas fa-chevron-right"></i> Jam operasional rental motor?</h3>
                                <i class="fas fa-chevron-down toggle-icon"></i>
                            </div>
                            <div class="faq-answer">
                                <p>Kami buka setiap hari:</p>
                                <ul>
                                    <li>Senin - Minggu: 08.00 - 20.00 WIB</li>
                                    <li>Layanan darurat 24 jam (call center)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.faq-section {
    padding: 60px 0;
    background: #f8fafc;
}

.faq-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}

.faq-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 40px;
    text-align: center;
    color: white;
}

.faq-header i {
    font-size: 50px;
    margin-bottom: 15px;
}

.faq-header h2 {
    font-size: 1.8rem;
    margin-bottom: 10px;
}

.faq-content {
    padding: 30px;
}

.faq-item {
    margin-bottom: 15px;
    border: 1px solid #eef2f6;
    border-radius: 12px;
    overflow: hidden;
}

.faq-question {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 25px;
    background: white;
    cursor: pointer;
    transition: all 0.3s;
}

.faq-question:hover {
    background: #f8f9ff;
}

.faq-question h3 {
    font-size: 1rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.faq-question h3 i {
    color: #667eea;
    font-size: 0.8rem;
}

.toggle-icon {
    color: #667eea;
    transition: transform 0.3s;
}

.faq-item.active .toggle-icon {
    transform: rotate(180deg);
}

.faq-item.active .faq-question h3 i {
    transform: rotate(90deg);
}

.faq-answer {
    display: none;
    padding: 20px 25px;
    background: #f8fafc;
    border-top: 1px solid #eef2f6;
}

.faq-item.active .faq-answer {
    display: block;
}

.faq-answer p, .faq-answer ul {
    color: #4a5568;
    line-height: 1.6;
    margin-bottom: 10px;
}

.faq-answer ul {
    padding-left: 20px;
}

.faq-answer ul li {
    margin-bottom: 5px;
}

@media (max-width: 768px) {
    .faq-header {
        padding: 25px;
    }
    .faq-header h2 {
        font-size: 1.3rem;
    }
    .faq-content {
        padding: 20px;
    }
    .faq-question {
        padding: 15px;
    }
    .faq-question h3 {
        font-size: 0.85rem;
    }
}
</style>
<script>

function toggleFaq(element) {
    const faqItem = element.closest('.faq-item');
    faqItem.classList.toggle('active');
}
</script>


<?php include "../partials/footer.php"; ?>
<?php include "../partials/script.php"; ?>