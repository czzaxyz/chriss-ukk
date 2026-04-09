<?php
include '../../app.php';
session_start();

// Debug: cek session
error_log("Session user_id: " . ($_SESSION['user_id'] ?? 'not set'));

if (!empty($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Ambil data motor yang akan dihapus
    $queryMotor = mysqli_query($connect, "SELECT * FROM barang WHERE id = $id");
    $motor = mysqli_fetch_assoc($queryMotor);
    
    if (!$motor) {
        $_SESSION['error'] = "Data motor tidak ditemukan!";
        header("Location: ../../pages/motor/index.php");
        exit;
    }
    
    // Cek apakah motor sedang dipinjam
    $checkPinjam = mysqli_query($connect, "SELECT * FROM peminjaman WHERE barang_id = $id AND status IN ('pending', 'disetujui', 'dipinjam')");
    if (mysqli_num_rows($checkPinjam) > 0) {
        $_SESSION['error'] = "Motor sedang dipinjam, tidak bisa dihapus!";
        header("Location: ../../pages/motor/index.php");
        exit;
    }
    
    // Hapus motor
    $qDelete = "DELETE FROM barang WHERE id = $id";
    if (mysqli_query($connect, $qDelete)) {
        // Catat log aktivitas (panggil setelah delete berhasil)
        $aktivitas = "Menghapus motor: {$motor['nama_barang']} (Kode: {$motor['kode_barang']})";
        $logResult = logActivity($aktivitas);
        
        // Debug: cek apakah log berhasil
        if (!$logResult) {
            error_log("Gagal menyimpan log: " . mysqli_error($connect));
        }
        
        $_SESSION['success'] = "Motor berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus: " . mysqli_error($connect);
    }
    header("Location: ../../pages/motor/index.php");
    exit;
} else {
    $_SESSION['error'] = "ID tidak valid!";
    header("Location: ../../pages/motor/index.php");
    exit;
}
?>