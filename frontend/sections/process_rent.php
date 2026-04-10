<?php
session_start();
include "../../config/koneksi.php";

// Cek login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $user_id = $_SESSION['user_id'];
    $barang_id = (int)$_POST['barang_id'];
    $jumlah = (int)$_POST['jumlah'];
    $tgl_pinjam = mysqli_real_escape_string($connect, $_POST['tgl_pinjam']);
    $tgl_kembali = mysqli_real_escape_string($connect, $_POST['tgl_kembali']);
    $keterangan = mysqli_real_escape_string($connect, $_POST['keterangan']);
    
    // Hitung total harga
    $query_harga = mysqli_query($connect, "SELECT harga_sewa_perhari FROM barang WHERE id = $barang_id");
    $harga = mysqli_fetch_assoc($query_harga);
    $lama = (strtotime($tgl_kembali) - strtotime($tgl_pinjam)) / (60 * 60 * 24);
    $total_harga = $lama * $jumlah * $harga['harga_sewa_perhari'];
    
    // Insert peminjaman
    $query = "INSERT INTO peminjaman (user_id, barang_id, jumlah, tgl_pinjam, tgl_kembali_rencana, keterangan, status, total_harga, created_at) 
              VALUES ($user_id, $barang_id, $jumlah, '$tgl_pinjam', '$tgl_kembali', '$keterangan', 'pending', $total_harga, NOW())";
    
    if (mysqli_query($connect, $query)) {
        echo "<script>
            alert('Peminjaman berhasil diajukan! Menunggu konfirmasi admin.');
            window.location.href = 'peminjaman_saya.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal mengajukan peminjaman: " . mysqli_error($connect) . "');
            window.location.href = 'motor.php';
        </script>";
    }
} else {
    header("Location: motor.php");
    exit;
}
?>