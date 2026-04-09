<?php
// action/peminjaman/return_process.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include '../../app.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (isset($_POST['tombol']) && $id > 0) {
    $kondisi = escapeString($_POST['kondisi']);
    $keterangan = escapeString($_POST['keterangan']);
    $denda_manual = (float)$_POST['denda_manual'];
    
    // Ambil data peminjaman
    $q_peminjaman = mysqli_query($connect, "SELECT p.*, b.harga_sewa_perhari, b.nama_barang 
        FROM peminjaman p 
        LEFT JOIN barang b ON p.barang_id = b.id 
        WHERE p.id = $id");
    $peminjaman = mysqli_fetch_assoc($q_peminjaman);
    
    if (!$peminjaman) {
        $_SESSION['error'] = "Data peminjaman tidak ditemukan!";
        header("Location: ../../pages/peminjaman/index.php");
        exit;
    }
    
    // Hitung hari telat
    $tgl_kembali_rencana = new DateTime($peminjaman['tgl_kembali_rencana']);
    $tgl_sekarang = new DateTime();
    $telat_hari = $tgl_sekarang > $tgl_kembali_rencana ? $tgl_sekarang->diff($tgl_kembali_rencana)->days : 0;
    
    // Hitung denda (Rp 50.000 per hari per unit)
    $denda = $denda_manual + ($telat_hari * $peminjaman['jumlah'] * 50000);
    
    // Hitung lama pinjam aktual
    $tgl_pinjam = new DateTime($peminjaman['tgl_pinjam']);
    $lama_pinjam = $tgl_sekarang->diff($tgl_pinjam)->days;
    if ($lama_pinjam == 0) $lama_pinjam = 1;
    
    // Hitung total harga
    $total_harga = $lama_pinjam * $peminjaman['jumlah'] * $peminjaman['harga_sewa_perhari'];
    
    // Update peminjaman
    $qUpdate = "UPDATE peminjaman SET 
                tgl_kembali_aktual = CURDATE(),
                lama_pinjam = $lama_pinjam,
                status = 'selesai',
                total_harga = $total_harga,
                kondisi = '$kondisi',
                denda = $denda,
                keterangan_pengembalian = '$keterangan',
                tgl_pengembalian_input = NOW(),
                updated_at = NOW()
                WHERE id = $id";
    
    if (mysqli_query($connect, $qUpdate)) {
        // Update stok motor (kembalikan)
        $updateStok = mysqli_query($connect, "UPDATE barang SET jumlah_tersedia = jumlah_tersedia + {$peminjaman['jumlah']} WHERE id = {$peminjaman['barang_id']}");
        
        // Jika kondisi tidak baik, ubah status motor menjadi rusak
        if ($kondisi != 'baik') {
            mysqli_query($connect, "UPDATE barang SET status = 'rusak' WHERE id = {$peminjaman['barang_id']}");
        }
        
        // Log activity
        $aktivitas = "Memproses pengembalian motor: {$peminjaman['nama_barang']} | Telat: $telat_hari hari | Denda: Rp " . number_format($denda, 0, ',', '.');
        logActivity($aktivitas);
        
        $_SESSION['success'] = "Pengembalian berhasil diproses!<br>
                                Total Harga: Rp " . number_format($total_harga, 0, ',', '.') . "<br>
                                Denda: Rp " . number_format($denda, 0, ',', '.') . "<br>
                                Total Bayar: Rp " . number_format($total_harga + $denda, 0, ',', '.');
    } else {
        $_SESSION['error'] = "Gagal memproses pengembalian: " . mysqli_error($connect);
    }
    
    header("Location: ../../pages/peminjaman/index.php");
    exit;
} else {
    $_SESSION['error'] = "ID tidak valid!";
    header("Location: 217halaman sebelumnya7/pages/peminjaman/index.php");
    exit;
}
?>