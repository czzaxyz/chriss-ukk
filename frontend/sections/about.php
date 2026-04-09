<?php include "../partials1/header.php"; ?>
<?php include "../partials1/navbar.php"; ?>

<!-- START SECTION TOP -->
<section class="section-top">
    <div class="container">
        <div class="col-lg-10 offset-lg-1 text-center">
            <div class="section-top-title wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.3s" data-wow-offset="0">
                <h1>Tentang Kami</h1>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li> / Tentang Kami</li>
                </ul>
            </div><!-- //.HERO-TEXT -->
        </div><!--- END COL -->
    </div><!--- END CONTAINER -->
</section>
<!-- END SECTION TOP -->

<!-- START TENTANG RENTAL MOTOR -->
<section class="top_cat__area section-padding" style="background-image: url(assets/img/bg/shape-1.png); background-size:cover; background-position: center center;">
    <div class="container">
        <div class="section-title text-center">
            <h2>Kenapa Harus Sewa Motor di Kami?</h2>
            <p>Kami menyediakan layanan rental motor terpercaya dengan berbagai pilihan motor berkualitas. Proses mudah, harga terjangkau, dan pelayanan terbaik untuk perjalanan Anda.</p>
        </div>
        <div class="row">
            <div class="col-lg-3 col-sm-6 col-xs-12 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s" data-wow-offset="0">
                <div class="single_tp">
                    <span class="sc_one">01</span>
                    <h3>Motor <br />Terawat</h3>
                    <p>Semua motor kami dalam kondisi prima, service rutin, dan siap menemani perjalanan Anda.</p>
                </div>
            </div><!-- END COL -->
            <div class="col-lg-3 col-sm-6 col-xs-12 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s" data-wow-offset="0">
                <div class="single_tp">
                    <span class="sc_two">02</span>
                    <h3>Harga <br />Terjangkau</h3>
                    <p>Harga sewa motor yang kompetitif dengan berbagai pilihan paket sewa harian, mingguan, dan bulanan.</p>
                </div>
            </div><!-- END COL -->
            <div class="col-lg-3 col-sm-6 col-xs-12 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.4s" data-wow-offset="0">
                <div class="single_tp">
                    <span class="sc_three">03</span>
                    <h3>Proses <br />Mudah & Cepat</h3>
                    <p>Peminjaman motor hanya dengan KTP, tanpa ribet. Proses cepat dan aman.</p>
                </div>
            </div><!-- END COL -->
            <div class="col-lg-3 col-sm-6 col-xs-12 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.4s" data-wow-offset="0">
                <div class="single_tp">
                    <span class="sc_four">04</span>
                    <h3>Layanan <br />24/7</h3>
                    <p>Customer service siap membantu Anda kapan saja, darurat di jalan pun kami bantu.</p>
                </div>
            </div><!-- END COL -->
        </div><!-- END ROW -->
    </div><!--- END CONTAINER -->
</section>
<!-- END TENTANG RENTAL MOTOR -->

<!-- START TENTANG KAMI -->
<section class="ab_area section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-sm-12 col-xs-12 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s" data-wow-offset="0">
                <div class="ab_img">
                    <img src="https://cdn.pixabay.com/photo/2016/11/18/21/30/bike-1836962_1280.jpg" class="img-fluid" alt="Motor Rental">
                </div>
            </div><!--- END COL -->
            <div class="col-lg-6 col-sm-12 col-xs-12 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.1s" data-wow-offset="0">
                <div class="ab_content">
                    <h2>Kami Menyediakan Rental Motor Terpercaya Sejak 2020</h2>
                    <p>Berawal dari hobi berkendara dan kebutuhan masyarakat akan transportasi yang fleksibel, kami hadir untuk memberikan solusi rental motor yang mudah, cepat, dan terpercaya.</p>
                    <p>Dengan pengalaman lebih dari 5 tahun, kami telah melayani ribuan pelanggan dari berbagai kalangan. Kami selalu mengutamakan kenyamanan dan keamanan pelanggan.</p>
                    <ul>
                        <li><span class="ti-check"></span> <b>500+</b> Motor Siap Pakai</li>
                        <li><span class="ti-check"></span> <b>2.500+</b> Pelanggan Puas</li>
                        <li><span class="ti-check"></span> <b>30+</b> Jenis Motor Tersedia</li>
                        <li><span class="ti-check"></span> <b>24 Jam</b> Layanan Darurat</li>
                    </ul>
                    <div class="motor-dropdown">
                        <button class="dropbtn" onclick="toggleDropdown()">
                            Lihat Semua Motor
                            <i class="fas fa-chevron-down" style="font-size: 12px; margin-left: 8px;"></i>
                        </button>
                        <div id="myDropdown" class="dropdown-content-custom">
                            <div class="dropdown-header">Pilih Kategori Motor</div>

                            <!-- Matic -->
                            <a href="motor.php?kategori=matic">
                                <i class="fas fa-motorcycle"></i>
                                <span>Motor Matic</span>
                                <small>Matic terbaru & irit</small>
                            </a>

                            <!-- Trail/Offroad -->
                            <a href="motor.php?kategori=trail%2Foffroad">
                                <i class="fas fa-mountain"></i>
                                <span>Motor Trail/Offroad</span>
                                <small>Untuk medan berat & offroad</small>
                            </a>

                            <!-- Sport -->
                            <a href="motor.php?kategori=sport">
                                <i class="fas fa-bolt"></i>
                                <span>Motor Sport</span>
                                <small>Performa tinggi & bertenaga</small>
                            </a>

                            <!-- Bebek -->
                            <a href="motor.php?kategori=bebek">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Motor Bebek</span>
                                <small>Irit bahan bakar</small>
                            </a>

                            <!-- Skuter -->
                            <a href="motor.php?kategori=skuter">
                                <i class="fas fa-motorcycle"></i>
                                <span>Motor Skuter</span>
                                <small>Bagasi luas & nyaman</small>
                            </a>

                            <!-- Classic -->
                            <a href="motor.php?kategori=classic">
                                <i class="fas fa-crown"></i>
                                <span>Motor Classic</span>
                                <small>Desain vintage & klasik</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div><!--- END COL -->
        </div><!--- END ROW -->
    </div><!--- END CONTAINER -->
</section>

<!-- START COUNTER - Versi Sederhana -->
<section class="count_area counter_feature">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="single-counter text-center">
                    <i class="fas fa-motorcycle" style="font-size: 40px; color: #667eea; margin-bottom: 15px;"></i>
                    <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 5px;">500+</h2>
                    <p style="color: #4a5568;">Motor Tersedia</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="single-counter text-center">
                    <i class="fas fa-users" style="font-size: 40px; color: #667eea; margin-bottom: 15px;"></i>
                    <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 5px;">2.500+</h2>
                    <p style="color: #4a5568;">Pelanggan Puas</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="single-counter text-center">
                    <i class="fas fa-star" style="font-size: 40px; color: #667eea; margin-bottom: 15px;"></i>
                    <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 5px;">4.9</h2>
                    <p style="color: #4a5568;">Rating Pelanggan</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="single-counter text-center">
                    <i class="fas fa-headset" style="font-size: 40px; color: #667eea; margin-bottom: 15px;"></i>
                    <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 5px;">24/7</h2>
                    <p style="color: #4a5568;">Layanan Pelanggan</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END COUNTER -->

<?php include "../partials1/footer.php"; ?>
<?php include "../partials1/script.php"; ?>

<style>
    /* Click Dropdown - Tidak Ada Getaran */
    .motor-dropdown {
        position: relative;
        display: inline-block;
    }

    .dropbtn {
        background: linear-gradient(135deg, #4d64ff 0%, #4d64ff 100%);
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 50px;
        cursor: pointer;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .dropbtn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }

    .dropdown-content-custom {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background: white;
        min-width: 280px;
        border-radius: 16px;
        box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.15);
        z-index: 100;
        margin-top: 10px;
    }

    .dropdown-content-custom.show {
        display: block;
    }

    .dropdown-header {
        padding: 12px 16px;
        font-weight: 700;
        color: #2d3748;
        border-bottom: 1px solid #eef2f6;
        font-size: 0.85rem;
    }

    .dropdown-content-custom a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        text-decoration: none;
        transition: background 0.2s;
        border-bottom: 1px solid #f0f2f5;
    }

    .dropdown-content-custom a:last-child {
        border-bottom: none;
    }

    .dropdown-content-custom a i {
        width: 24px;
        font-size: 1.1rem;
        color: #667eea;
    }

    .dropdown-content-custom a span {
        flex: 1;
        font-weight: 600;
        color: #4a5568;
    }

    .dropdown-content-custom a small {
        font-size: 0.65rem;
        color: #a0aec0;
    }

    .dropdown-content-custom a:hover {
        background: #f8f9ff;
    }

    .dropdown-footer {
        padding: 8px;
        border-top: 1px solid #eef2f6;
        background: #f8fafc;
    }

    .btn-all {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white !important;
        border-radius: 12px;
        font-weight: 600;
        padding: 10px 16px !important;
    }

    .btn-all i {
        color: white !important;
    }
</style>

<script>
    function toggleDropdown() {
        document.getElementById("myDropdown").classList.toggle("show");
    }

    // Tutup dropdown jika klik di luar
    window.onclick = function(event) {
        if (!event.target.matches('.dropbtn')) {
            var dropdowns = document.getElementsByClassName("dropdown-content-custom");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
</script>