<?php
include '../../app.php';
session_start();

if (isset($_POST['tombol'])) {
    $user_id = (int)$_POST['user_id'];
    $barang_id = (int)$_POST['barang_id'];
    $jumlah = (int)$_POST['jumlah'];
    $tgl_pinjam = escapeString($_POST['tgl_pinjam']);
    $tgl_kembali = escapeString($_POST['tgl_kembali']);
    $keterangan = escapeString($_POST['keterangan']);
    $total_harga = (float)$_POST['total_harga'];
    
    // Insert
    $qInsert = "INSERT INTO peminjaman (user_id, barang_id, jumlah, tgl_pinjam, tgl_kembali_rencana, keterangan, status, total_harga) 
                VALUES ($user_id, $barang_id, $jumlah, '$tgl_pinjam', '$tgl_kembali', '$keterangan', 'pending', $total_harga)";
    
    if (mysqli_query($connect, $qInsert)) {
        $aktivitas = "Menambahkan peminjaman baru untuk user ID: $user_id";
        logActivity($aktivitas);
        $_SESSION['success'] = "Peminjaman berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Gagal: " . mysqli_error($connect);
    }
    header("Location: ../../pages/peminjaman/index.php");
    exit;
}
?>