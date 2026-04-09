<?php
include '../../app.php';
session_start();

if (!empty($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Cek apakah motor sedang dipinjam
    $checkPinjam = mysqli_query($connect, "SELECT * FROM peminjaman WHERE barang_id = $id AND status IN ('pending', 'disetujui', 'dipinjam')");
    if (mysqli_num_rows($checkPinjam) > 0) {
        $_SESSION['error'] = "Motor sedang dipinjam, tidak bisa dihapus!";
        header("Location: ../../pages/motor/index.php");
        exit;
    }
    
    // Hapus
    $qDelete = "DELETE FROM barang WHERE id = $id";
    if (mysqli_query($connect, $qDelete)) {
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