<!-- START HOME -->
<section class="home_bg hb_height" style="background-image: url(assets/img/bg/home-bg2.jpg); background-size: cover; background-position: center center; background-repeat: no-repeat; min-height: 100vh;">
    <div class="container">
        <div class="row align-items-center" style="min-height: 80vh;">
            <!-- Kolom Gambar (Sekarang di Kiri) -->
            <div class="col-lg-6 col-sm-12 col-xs-12">
                <div class="hero-text-img2 text-center">
                    <!-- GAMBAR UKURAN BESAR -->
                    <img src="" class="img-fluid" alt="" style="width: 100%; max-width: 600px; height: auto;">
                    <!-- Tambahan efek floating card -->
                </div>
            </div><!-- END COL -->
            
            <!-- Kolom Teks (Sekarang di Kanan) -->
            <div class="col-lg-6 col-sm-12 col-xs-12">
                <div class="hero-text2 ht_top">
                    <h1>Sewa Motor <span>Sport & Matic</span> untuk Perjalanan Anda</h1>
                    <p>Dapatkan motor impian dengan harga terbaik. Proses cepat, aman, dan tersedia berbagai pilihan motor terbaru.</p>
                </div>
                <div class="home_sb2">
                    <form action="motor.php" method="GET" class="banner_subs2">
                        <input type="text" class="form-control home_si2" name="search" placeholder="Cari motor yang Anda inginkan..." required="required">
                        <button type="submit" class="subscribe__btn">Cari Motor <i class="fa fa-paper-plane-o"></i></button>
                    </form>
                </div>
            </div><!-- END COL -->
        </div><!--- END ROW -->
    </div><!--- END CONTAINER -->
</section>
<!-- END HOME -->

<!-- START COMPANY PARTNER LOGO  (Diubah untuk Motor Rental)-->
<div class="partner-logo section-padding">
    <div class="container">
        <div class="row part_bg">
            <div class="col-lg-4 col-sm-4 col-xs-12">
                <div class="partner_title">
                    <h3>Dipercaya oleh <span>2.500+</span> Pelanggan & Berbagai Komunitas Motor</h3>
                </div>
            </div><!-- END COL  -->
            <div class="col-lg-8 col-sm-8 col-xs-12 text-center">
                <div class="partner">
                    <a href="#"><img src="assets/img/clients/1.png" alt="Partner Logo"></a>
                    <a href="#"><img src="assets/img/clients/2.png" alt="Partner Logo"></a>
                    <a href="#"><img src="assets/img/clients/3.png" alt="Partner Logo"></a>
                    <a href="#"><img src="assets/img/clients/4.png" alt="Partner Logo"></a>
                    <a href="#"><img src="assets/img/clients/5.png" alt="Partner Logo"></a>
                    <a href="#"><img src="assets/img/clients/2.png" alt="Partner Logo"></a>
                    <a href="#"><img src="assets/img/clients/1.png" alt="Partner Logo"></a>
                    <a href="#"><img src="assets/img/clients/3.png" alt="Partner Logo"></a>
                    <a href="#"><img src="assets/img/clients/4.png" alt="Partner Logo"></a>
                </div>
            </div><!-- END COL  -->
        </div><!--END  ROW  -->
    </div><!-- END CONTAINER  -->
</div>
<!-- END COMPANY PARTNER LOGO -->

<style>
    /* --- GAYA UNTUK SECTION HOME BARU --- */

/* --- GAYA UNTUK SECTION HOME UKURAN 1920x1080 --- */

/* Mengatur tinggi section FULL HD */
.hb_height {
    min-height: 100vh;
    height: 100vh;
    max-height: 1080px;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
}

/* Background image agar full */
.home_bg {
    background-size: cover !important;
    background-position: center center !important;
    background-repeat: no-repeat !important;
    width: 100%;
}

/* Container agar proporsional */
.container {
    max-width: 1200px;
    width: 100%;
    margin: 0 auto;
    padding: 0 15px;
}

/* Row agar tengah vertikal */
.row.align-items-center {
    min-height: calc(100vh - 100px);
    display: flex;
    align-items: center;
}

/* Mengatur gambar agar besar dan proporsional */
.hero-text-img2 {
    position: relative;
    text-align: center;
    padding: 20px;
}

.hero-text-img2 img {
    width: 100%;
    max-width: 650px;
    height: auto;
    filter: drop-shadow(0 20px 25px -5px rgba(0, 0, 0, 0.2));
    animation: fadeInUp 0.8s ease-out;
}

/* Animasi untuk gambar */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Gaya untuk teks (ukuran lebih besar untuk Full HD) */
.hero-text2 h1 {
    font-size: 3rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 25px;
    color: #1a202c;
}

.hero-text2 h1 span {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-text2 p {
    font-size: 1.1rem;
    color: #4a5568;
    margin-bottom: 35px;
    line-height: 1.6;
}

/* Gaya untuk form pencarian */
.banner_subs2 {
    display: flex;
    background: white;
    border-radius: 60px;
    padding: 8px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
}

.home_si2 {
    flex: 1;
    border: none !important;
    padding: 18px 25px !important;
    border-radius: 60px !important;
    font-size: 1rem;
}

.home_si2:focus {
    outline: none;
    box-shadow: none;
}

.subscribe__btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 0 35px;
    border-radius: 60px;
    color: white;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s;
    cursor: pointer;
}

.subscribe__btn:hover {
    transform: scale(1.02);
    box-shadow: 0 10px 15px -3px rgba(102, 126, 234, 0.4);
}

/* Gaya untuk tag populer */
.home_tag {
    margin-top: 25px;
    font-size: 1rem;
}

.home_tag span {
    font-weight: 700;
    color: #2d3748;
}

.home_tag a {
    color: #667eea;
    text-decoration: none;
    transition: color 0.2s;
}

.home_tag a:hover {
    color: #764ba2;
}

/* Floating card (opsional) */
.floating-card {
    position: absolute;
    background: white;
    border-radius: 16px;
    padding: 15px 25px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
    animation: float 3s ease-in-out infinite;
}

.floating-card i {
    font-size: 28px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.floating-card h4 {
    font-size: 0.8rem;
    color: #718096;
    margin: 0;
}

.floating-card p {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
}

.floating-card p span {
    font-size: 0.8rem;
    font-weight: normal;
    color: #a0aec0;
}

.card-1 {
    bottom: 30px;
    right: 30px;
    animation-delay: 0s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-15px); }
}

/* ============================================
   PARTNER LOGO STYLE
   ============================================ */
.partner-logo {
    padding: 60px 0;
    background: #f8fafc;
}

.part_bg {
    display: flex;
    align-items: center;
}

.partner_title h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
}

.partner_title h3 span {
    color: #667eea;
    font-size: 2rem;
}

.partner {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 30px;
}

.partner a {
    display: inline-block;
    transition: all 0.3s;
}

.partner a:hover {
    transform: translateY(-5px);
    opacity: 0.8;
}

.partner img {
    max-height: 50px;
    width: auto;
}

/* Responsif untuk layar 1920x1080 */
@media (min-width: 1920px) {
    .container {
        max-width: 1400px;
    }
    
    .hero-text2 h1 {
        font-size: 3.5rem;
    }
    
    .hero-text2 p {
        font-size: 1.2rem;
    }
    
    .hero-text-img2 img {
        max-width: 750px;
    }
}

/* Responsif untuk tablet dan mobile */
@media (max-width: 992px) {
    .hb_height {
        height: auto;
        min-height: auto;
        padding: 60px 0;
    }
    
    .hero-text2 h1 {
        font-size: 2rem;
        margin-top: 30px;
    }
    
    .banner_subs2 {
        flex-direction: column;
        background: transparent;
        box-shadow: none;
        gap: 15px;
    }
    
    .home_si2, .subscribe__btn {
        border-radius: 60px !important;
        width: 100%;
    }
    
    .hero-text-img2 {
        margin-top: 30px;
    }
    
    .floating-card {
        display: none;
    }
    
    .partner_title {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .partner {
        gap: 20px;
    }
    
    .partner img {
        max-height: 40px;
    }
}

@media (max-width: 768px) {
    .hero-text2 h1 {
        font-size: 1.8rem;
    }
    
    .hero-text2 p {
        font-size: 0.95rem;
    }
    
    .partner_title h3 {
        font-size: 1.2rem;
    }
    
    .partner_title h3 span {
        font-size: 1.5rem;
    }
}
</style>