<?php
include '../../app.php';
session_start();

if (!empty($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $qDelete = "DELETE FROM peminjaman WHERE id = $id";
    if (mysqli_query($connect, $qDelete)) {
        $aktivitas = "Menghapus peminjaman dengan ID: $id";
        logActivity($aktivitas);
        $_SESSION['success'] = "Peminjaman berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus: " . mysqli_error($connect);
    }
    header("Location: ../../pages/peminjaman/index.php");
    exit;
} else {
    $_SESSION['error'] = "ID tidak valid!";
    header("Location: ../../pages/peminjaman/index.php");
    exit;
}
?>