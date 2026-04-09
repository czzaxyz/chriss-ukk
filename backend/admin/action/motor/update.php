<?php
include '../../app.php';
session_start();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (isset($_POST['tombol']) && $id > 0) {
    // Ambil data lama sebelum update
    $queryOld = mysqli_query($connect, "SELECT * FROM barang WHERE id = $id");
    $oldData = mysqli_fetch_assoc($queryOld);
    
    $kode_barang = escapeString($_POST['kode_barang']);
    $nama_barang = escapeString($_POST['nama_barang']);
    $kategori_id = (int)$_POST['kategori_id'];
    $merk = escapeString($_POST['merk']);
    $tahun = !empty($_POST['tahun']) ? (int)$_POST['tahun'] : NULL;
    $stok = (int)$_POST['stok'];
    $status = escapeString($_POST['status']);
    $harga_sewa_perhari = (float)$_POST['harga_sewa_perhari'];
    $deskripsi = escapeString($_POST['deskripsi']);
    
    // Cek kode unik
    $check = mysqli_query($connect, "SELECT * FROM barang WHERE kode_barang = '$kode_barang' AND id != $id");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['error'] = "Kode barang sudah digunakan!";
        header("Location: ../../pages/motor/edit.php?id=$id");
        exit;
    }
    
    // Update
    $qUpdate = "UPDATE barang SET 
                kode_barang = '$kode_barang',
                nama_barang = '$nama_barang',
                kategori_id = $kategori_id,
                merk = '$merk',
                tahun = " . ($tahun ? $tahun : "NULL") . ",
                stok = $stok,
                status = '$status',
                harga_sewa_perhari = $harga_sewa_perhari,
                deskripsi = '$deskripsi'
                WHERE id = $id";
    
    if (mysqli_query($connect, $qUpdate)) {
        // Catat log aktivitas
        $aktivitas = "Mengupdate motor: $nama_barang (Kode: $kode_barang) | Sebelum: Stok={$oldData['stok']}, Status={$oldData['status']} | Sesudah: Stok=$stok, Status=$status";
        logActivity($aktivitas);
        
        $_SESSION['success'] = "Motor berhasil diupdate!";
        header("Location: ../../pages/motor/index.php");
        exit;
    } else {
        $_SESSION['error'] = "Gagal: " . mysqli_error($connect);
        header("Location: ../../pages/motor/edit.php?id=$id");
        exit;
    }
}
?>