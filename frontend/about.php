<!-- START HOME -->
<section class="home_bg hb_height" style="background-image: url('assets/img/bg/home-bg2.jpg'); background-size: cover; background-position: center center; background-repeat: no-repeat; position: relative;">
    <!-- Overlay gelap agar teks lebih terbaca -->
    <div class="overlay-dark"></div>
    
    <div class="container">
        <div class="row align-items-center">
            <!-- Kolom Teks (Kiri) -->
            <div class="col-lg-6 col-sm-12 col-xs-12">
                <div class="hero-text2">
                    <span class="badge-rental">🏍️ Rental Motor Terpercaya</span>
                    <h1>Nikmati Perjalanan <span class="highlight">Lebih Nyaman</span> dengan Motor Berkualitas</h1>
                    <p>Dapatkan motor impian Anda dengan harga terbaik. Proses cepat, aman, dan tersedia berbagai pilihan motor terbaru dari berbagai merk ternama.</p>
                    
                    <!-- Statistik Singkat -->
                    <div class="hero-stats">
                        <div class="stat-item">
                            <h3>500+</h3>
                            <p>Motor Tersedia</p>
                        </div>
                        <div class="stat-item">
                            <h3>2.500+</h3>
                            <p>Pelanggan Puas</p>
                        </div>
                        <div class="stat-item">
                            <h3>24/7</h3>
                            <p>Layanan</p>
                        </div>
                    </div>
                    
                    <!-- Tombol CTA -->
                    <div class="hero-buttons">
                        <a href="frontend/sections/contact.php" class="btn-primary-hero">
                            <i class="fas fa-motorcycle"></i> Sewa Sekarang
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div><!-- END COL -->
            
            <!-- Kolom Gambar (Kanan) -->
            <div class="col-lg-6 col-sm-12 col-xs-12">
                <div class="hero-image-wrapper">
                    <div class="hero-image">
                        <img src="https://imgcdn.oto.com/medium/gallery/exterior/201/3333/qj-motor-srk-250-ra-74647.jpg" class="img-fluid" alt="Motor Rental">
                        <div class="floating-card price-card">
                            <i class="fas fa-tag"></i>
                            <div>
                                <span>Mulai dari</span>
                                <h4>Rp 80.000<span>/hari</span></h4>
                            </div>
                        </div>
                        <div class="floating-card rating-card">
                            <i class="fas fa-star"></i>
                            <div>
                                <span>Rating</span>
                                <h4>4.9 <span>/5</span></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- END COL -->
        </div><!--- END ROW -->
    </div><!--- END CONTAINER -->
</section>
<!-- END HOME -->

<!-- START LAYANAN KAMI -->
<section class="services-section section-padding">
    <div class="container">
        <div class="section-title text-center">
            <span class="subtitle">LAYANAN KAMI</span>
            <h2>Mengapa Harus Sewa di <span>Kami?</span></h2>
            <p>Kami menyediakan layanan rental motor terbaik dengan berbagai keunggulan</p>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-motorcycle"></i>
                    </div>
                    <h4>Motor Berkualitas</h4>
                    <p>Semua motor dalam kondisi prima, service rutin, dan siap pakai</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <h4>Harga Terjangkau</h4>
                    <p>Harga sewa kompetitif dengan berbagai pilihan paket</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h4>Proses Cepat</h4>
                    <p>Peminjaman mudah dan cepat, hanya dengan KTP</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h4>Layanan 24/7</h4>
                    <p>Customer service siap membantu kapan saja</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* ============================================
   HOME SECTION STYLE - MODERN & PREMIUM
   ============================================ */

/* Overlay gelap */
.overlay-dark {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%);
    z-index: 0;
}

.hb_height {
    min-height: 100vh;
    height: 100vh;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
}

.home_bg {
    position: relative;
}

.container {
    position: relative;
    z-index: 2;
}

/* Badge Rental */
.badge-rental {
    display: inline-block;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 0.85rem;
    color: white;
    margin-bottom: 25px;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

/* Hero Text */
.hero-text2 h1 {
    font-size: 3.2rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 20px;
    color: white;
}

.hero-text2 h1 .highlight {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-text2 p {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.85);
    margin-bottom: 30px;
    line-height: 1.6;
}

/* Hero Stats */
.hero-stats {
    display: flex;
    gap: 40px;
    margin-bottom: 35px;
}

.stat-item h3 {
    font-size: 2rem;
    font-weight: 800;
    color: white;
    margin-bottom: 5px;
}

.stat-item p {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

/* Hero Buttons */
.hero-buttons {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.btn-primary-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 14px 32px;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s;
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.btn-primary-hero:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-outline-hero {
    background: transparent;
    border: 2px solid white;
    color: white;
    padding: 12px 30px;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s;
}

.btn-outline-hero:hover {
    background: white;
    color: #667eea;
    transform: translateY(-3px);
}

/* Hero Image */
.hero-image-wrapper {
    position: relative;
    text-align: center;
}

.hero-image {
    position: relative;
    animation: floatImage 4s ease-in-out infinite;
}

.hero-image img {
    width: 100%;
    max-width: 550px;
    height: auto;
    filter: drop-shadow(0 20px 30px rgba(0, 0, 0, 0.3));
    border-radius: 30px;
}

@keyframes floatImage {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}

/* Floating Cards */
.floating-card {
    position: absolute;
    background: white;
    border-radius: 16px;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    animation: floatCard 3s ease-in-out infinite;
}

.price-card {
    bottom: 20px;
    left: -20px;
    animation-delay: 0s;
}

.rating-card {
    top: 30px;
    right: -20px;
    animation-delay: 1s;
}

.floating-card i {
    font-size: 28px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.floating-card span {
    font-size: 0.7rem;
    color: #718096;
}

.floating-card h4 {
    font-size: 1.1rem;
    font-weight: 700;
    margin: 0;
    color: #2d3748;
}

.floating-card h4 span {
    font-size: 0.7rem;
    font-weight: normal;
}

@keyframes floatCard {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

/* ============================================
   SERVICES SECTION
   ============================================ */
.services-section {
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

.service-card {
    background: white;
    padding: 30px 25px;
    border-radius: 20px;
    text-align: center;
    transition: all 0.3s;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    margin-bottom: 25px;
}

.service-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

.service-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}

.service-icon i {
    font-size: 30px;
    color: white;
}

.service-card h4 {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 12px;
    color: #2d3748;
}

.service-card p {
    font-size: 0.85rem;
    color: #718096;
    line-height: 1.5;
}

/* Partner Logo */
.partner-logo {
    padding: 50px 0;
    background: white;
}

.partner_title h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #2d3748;
}

.partner_title h3 span {
    color: #667eea;
    font-size: 1.6rem;
}

.partner {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 30px;
}

.partner img {
    max-height: 45px;
    width: auto;
    opacity: 0.6;
    transition: all 0.3s;
}

.partner img:hover {
    opacity: 1;
    transform: translateY(-3px);
}

/* Responsive */
@media (max-width: 992px) {
    .hero-text2 h1 {
        font-size: 2.2rem;
        margin-top: 30px;
    }
    
    .hero-stats {
        justify-content: center;
    }
    
    .hero-buttons {
        justify-content: center;
    }
    
    .hero-text2 {
        text-align: center;
    }
    
    .hero-image img {
        max-width: 400px;
    }
    
    .floating-card {
        display: none;
    }
    
    .service-card {
        margin-bottom: 20px;
    }
}

@media (max-width: 768px) {
    .hero-text2 h1 {
        font-size: 1.8rem;
    }
    
    .section-title h2 {
        font-size: 1.6rem;
    }
    
    .partner_title {
        text-align: center;
        margin-bottom: 25px;
    }
}
</style>