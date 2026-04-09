<?php
// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['status']) && $_SESSION['status'] == 'login';
$username = $isLoggedIn ? $_SESSION['username'] : '';
$role = $isLoggedIn ? $_SESSION['role'] : '';
?>

<!-- START NAVBAR -->
<div id="navigation" class="navbar-light bg-faded site-navigation">
    <div class="container-fluid">
        <div class="row">
            <div class="col-20 align-self-center">
                <div class="site-logo">
                    <a href="index.php"><img src="frontend/templates/assets/img/logo.png" alt="Logo"></a>
                </div>
            </div><!--- END Col -->

            <div class="col-60 d-flex">
                <nav id="main-menu">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="frontend/sections/about.php">About</a></li>
                        <li class="menu-item-has-children"><a href="#">Motor</a>
                            <ul>
                                <li><a href="../../frontend/sections/motor.php?kategori=matic">Matic</a></li>
                                <li><a href="../../frontend/sections/motor.php?kategori=trail%2Foffroad">Trail/Offroad</a></li>
                                <li><a href="../../frontend/sections/motor.php?kategori=sport">Sport</a></li>
                                <li><a href="../../frontend/sections/motor.php?kategori=bebek">Bebek</a></li>
                                <li><a href="../../frontend/sections/motor.php?kategori=skuter">Skuter</a></li>
                                <li><a href="../../frontend/sections/motor.php?kategori=classic">Classic</a></li>
                            </ul>
                        </li>
                        <?php if ($isLoggedIn): ?>
                            <li><a href="frontend/sections/peminjaman.php">Peminjaman Saya</a></li>
                        <?php endif; ?>
                        <li><a href="frontend/sections/contact.php">Contact</a></li>
                    </ul>
                </nav>
            </div><!--- END Col -->

            <div class="col-20 d-none d-xl-block text-end align-self-center">
                <?php if ($isLoggedIn): ?>
                    <!-- Tampilan ketika sudah login -->
                    <div class="dropdown-user">
                        <span class="header-btn">
                            <i class="fa fa-user-circle"></i> Halo, <?= htmlspecialchars($username) ?>
                        </span>
                        <a href="../../chriss-ukk/frontend/auth/logout.php" class="btn_one btn-logout">
                            <i class="fa fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Tampilan ketika belum login -->
                    <a href="frontend/auth/login.php" class="header-btn">Sign In</a>
                    <a href="frontend/auth/register.php" class="btn_one">Sign Up</a>
                <?php endif; ?>
            </div><!--- END Col -->

            <ul class="mobile_menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="frontend/sections/about.php">About</a></li>
                <li class="menu-item-has-children"><a href="#">Motor</a>
                    <ul>
                        <li><a href="../../frontend/sections/motor.php?kategori=matic">Matic</a></li>
                        <li><a href="../../frontend/sections/motor.php?kategori=trail%2Foffroad">Trail/Offroad</a></li>
                        <li><a href="../../frontend/sections/motor.php?kategori=sport">Sport</a></li>
                        <li><a href="../../frontend/sections/motor.php?kategori=bebek">Bebek</a></li>
                        <li><a href="../../frontend/sections/motor.php?kategori=skuter">Skuter</a></li>
                        <li><a href="../../frontend/sections/motor.php?kategori=classic">Classic</a></li>
                    </ul>
                </li>
                <?php if ($isLoggedIn): ?>
                    <li><a href="frontend/sections/peminjaman.php">Peminjaman Saya</a></li>
                <?php endif; ?>
                <li><a href="frontend/sections/contact.php">Contact</a></li>
                <?php if ($isLoggedIn): ?>
                    <li><a href="../../chriss-ukk/frontend/auth/logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="frontend/auth/login.php">Sign In</a></li>
                    <li><a href="frontend/auth/register.php">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </div><!--- END ROW -->
    </div><!--- END CONTAINER -->
</div>
<!-- END NAVBAR -->

<style>
    /* Style untuk dropdown user */
    .dropdown-user {
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .dropdown-user .header-btn {
        background: linear-gradient(135deg, #5661ff 0%, #5661ff 100%);
        color: white;
        padding: 8px 20px;
        border-radius: 30px;
        font-weight: 600;
    }

    .dropdown-user .btn_one {
        background: #f8f9fa;
        border: 1px solid #e2e8f0;
        color: #4a5568;
        padding: 8px 20px;
        border-radius: 30px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .dropdown-user .btn_one:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
    }

    .dropdown-user .btn-logout {
        background: #fee2e2;
        border-color: #fecaca;
        color: #991b1b;
    }

    .dropdown-user .btn-logout:hover {
        background: #fecaca;
    }

    /* Mobile menu style */
    .mobile_menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .mobile_menu li {
        padding: 10px 0;
    }

    .mobile_menu a {
        text-decoration: none;
        color: #4a5568;
        display: block;
    }

    .mobile_menu a:hover {
        color: #667eea;
    }
</style>