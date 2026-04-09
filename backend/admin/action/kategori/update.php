<?php
include '../../app.php';
session_start();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (isset($_POST['tombol']) && $id > 0) {
    // Ambil data lama
    $queryOld = mysqli_query($connect, "SELECT * FROM kategori WHERE id = $id");
    $oldData = mysqli_fetch_assoc($queryOld);
    
    $nama_kategori = escapeString($_POST['nama_kategori']);
    $deskripsi = escapeString($_POST['deskripsi']);
    
    // Cek nama kategori unik (abaikan dirinya sendiri)
    $check = mysqli_query($connect, "SELECT * FROM kategori WHERE nama_kategori = '$nama_kategori' AND id != $id");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['error'] = "Nama kategori sudah digunakan!";
        header("Location: ../../pages/kategori/edit.php?id=$id");
        exit;
    }
    
    // Update
    $qUpdate = "UPDATE kategori SET 
                nama_kategori = '$nama_kategori',
                deskripsi = '$deskripsi'
                WHERE id = $id";
    
    if (mysqli_query($connect, $qUpdate)) {
        // Log activity
        $aktivitas = "Mengupdate kategori: $nama_kategori (Sebelum: {$oldData['nama_kategori']})";
        logActivity($aktivitas);
        
        $_SESSION['success'] = "Kategori berhasil diupdate!";
        header("Location: ../../pages/kategori/index.php");
        exit;
    } else {
        $_SESSION['error'] = "Gagal: " . mysqli_error($connect);
        header("Location: ../../pages/kategori/edit.php?id=$id");
        exit;
    }
}
?>