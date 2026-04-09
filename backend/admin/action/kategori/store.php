<?php
include '../../app.php';
session_start();

if (isset($_POST['tombol'])) {
    $nama_kategori = escapeString($_POST['nama_kategori']);
    $deskripsi = escapeString($_POST['deskripsi']);
    
    // Validasi nama kategori unik
    $check = mysqli_query($connect, "SELECT * FROM kategori WHERE nama_kategori = '$nama_kategori'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['error'] = "Nama kategori sudah digunakan!";
        header("Location: ../../pages/kategori/create.php");
        exit;
    }
    
    // Insert
    $qInsert = "INSERT INTO kategori (nama_kategori, deskripsi, created_at) 
                VALUES ('$nama_kategori', '$deskripsi', NOW())";
    
    if (mysqli_query($connect, $qInsert)) {
        // Log activity
        $aktivitas = "Menambahkan kategori baru: $nama_kategori";
        logActivity($aktivitas);
        
        $_SESSION['success'] = "Kategori berhasil ditambahkan!";
        header("Location: ../../pages/kategori/index.php");
        exit;
    } else {
        $_SESSION['error'] = "Gagal: " . mysqli_error($connect);
        header("Location: ../../pages/kategori/create.php");
        exit;
    }
}
?>