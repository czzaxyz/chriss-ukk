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
                    <a href="index.php"><img src="templates/assets/img/logo.png" alt="Logo"></a>
                </div>
            </div><!--- END Col -->
            
            <div class="col-60 d-flex">
                <nav id="main-menu">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="motor.php">Motor</a></li>
                        <li><a href="about.php">About</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <?php if ($isLoggedIn): ?>
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li><a href="peminjaman.php">Peminjaman Saya</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div><!--- END Col -->
            
            <div class="col-20 d-none d-xl-block text-end align-self-center">
                <?php if ($isLoggedIn): ?>
                    <span class="header-btn">Halo, <?= htmlspecialchars($username) ?></span>
                    <a href="logout.php" class="btn_one">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="header-btn">Sign In</a>
                    <a href="register.php" class="btn_one">Sign Up</a>
                <?php endif; ?>
            </div><!--- END Col -->
            
            <ul class="mobile_menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="motor.php">Motor</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <?php if ($isLoggedIn): ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="peminjaman.php">Peminjaman Saya</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Sign In</a></li>
                    <li><a href="register.php">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </div><!--- END ROW -->
    </div><!--- END CONTAINER -->
</div>
<!-- END NAVBAR -->