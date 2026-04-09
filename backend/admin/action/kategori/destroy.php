<?php
include '../../app.php';
session_start();

if (!empty($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Ambil data kategori yang akan dihapus
    $queryKategori = mysqli_query($connect, "SELECT * FROM kategori WHERE id = $id");
    $kategori = mysqli_fetch_assoc($queryKategori);
    
    if (!$kategori) {
        $_SESSION['error'] = "Data kategori tidak ditemukan!";
        header("Location: ../../pages/kategori/index.php");
        exit;
    }
    
    // Cek apakah kategori memiliki motor
    $checkMotor = mysqli_query($connect, "SELECT * FROM barang WHERE kategori_id = $id");
    if (mysqli_num_rows($checkMotor) > 0) {
        $_SESSION['error'] = "Kategori memiliki motor, tidak bisa dihapus!";
        header("Location: ../../pages/kategori/index.php");
        exit;
    }
    
    // Hapus
    $qDelete = "DELETE FROM kategori WHERE id = $id";
    if (mysqli_query($connect, $qDelete)) {
        // Log activity
        $aktivitas = "Menghapus kategori: {$kategori['nama_kategori']}";
        logActivity($aktivitas);
        
        $_SESSION['success'] = "Kategori berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus: " . mysqli_error($connect);
    }
    header("Location: ../../pages/kategori/index.php");
    exit;
} else {
    $_SESSION['error'] = "ID tidak valid!";
    header("Location: ../../pages/kategori/index.php");
    exit;
}
?>