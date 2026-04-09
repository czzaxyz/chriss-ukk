<?php
include '../../app.php';
session_start();

if (isset($_POST['tombol'])) {
    $kode_barang = escapeString($_POST['kode_barang']);
    $nama_barang = escapeString($_POST['nama_barang']);
    $kategori_id = (int)$_POST['kategori_id'];
    $merk = escapeString($_POST['merk']);
    $tahun = !empty($_POST['tahun']) ? (int)$_POST['tahun'] : NULL;
    $stok = (int)$_POST['stok'];
    $status = escapeString($_POST['status']);
    $harga_sewa_perhari = (float)$_POST['harga_sewa_perhari'];
    $deskripsi = escapeString($_POST['deskripsi']);
    
    // Set jumlah_tersedia sama dengan stok awal
    $jumlah_tersedia = $stok;
    
    // Validasi kode unik
    $check = mysqli_query($connect, "SELECT * FROM barang WHERE kode_barang = '$kode_barang'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['error'] = "Kode barang sudah digunakan!";
        header("Location: ../../pages/motor/create.php");
        exit;
    }
    
    // Insert
    $qInsert = "INSERT INTO barang (kode_barang, nama_barang, kategori_id, merk, tahun, stok, jumlah_tersedia, status, harga_sewa_perhari, deskripsi) 
                VALUES ('$kode_barang', '$nama_barang', $kategori_id, '$merk', " . ($tahun ? $tahun : "NULL") . ", $stok, $jumlah_tersedia, '$status', $harga_sewa_perhari, '$deskripsi')";
    
    if (mysqli_query($connect, $qInsert)) {
        // Catat log aktivitas
        $aktivitas = "Menambahkan motor baru: $nama_barang (Kode: $kode_barang) | Stok: $stok | Harga: Rp " . number_format($harga_sewa_perhari, 0, ',', '.');
        logActivity($aktivitas);
        
        $_SESSION['success'] = "Motor berhasil ditambahkan!";
        header("Location: ../../pages/motor/index.php");
        exit;
    } else {
        $_SESSION['error'] = "Gagal: " . mysqli_error($connect);
        header("Location: ../../pages/motor/create.php");
        exit;
    }
}
?>