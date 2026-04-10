<?php
// action/peminjaman/approve.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include '../../app.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Update status menjadi 'dipinjam'
    $q_update = mysqli_query($connect, "UPDATE peminjaman SET status = 'dipinjam' WHERE id = $id");
    
    if ($q_update) {
        // Log activity
        $aktivitas = "Menyetujui peminjaman dengan ID: $id";
        logActivity($aktivitas);
        $_SESSION['success'] = "Peminjaman berhasil disetujui!";
    } else {
        $_SESSION['error'] = "Gagal menyetujui peminjaman!";
    }
} else {
    $_SESSION['error'] = "ID tidak valid!";
}

header("Location: ../../pages/peminjaman/index.php");
exit;
?>