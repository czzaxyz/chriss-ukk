<?php
include '../../app.php';
session_start();

// Pastikan ID ada di URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>
        alert('ID tidak valid');
        window.location.href='../../pages/motor/index.php';
    </script>";
    exit;
}

$id = (int) $_GET['id'];

// Query ambil data dari tabel barang
$qSelect = "SELECT b.*, k.nama_kategori 
            FROM barang b
            LEFT JOIN kategori k ON b.kategori_id = k.id
            WHERE b.id = $id";
$result = mysqli_query($connect, $qSelect) or die(mysqli_error($connect));

$motor = mysqli_fetch_object($result);

if (!$motor) {
    echo "<script>
        alert('Data motor tidak ditemukan');
        window.location.href='../../pages/motor/index.php';
    </script>";
    exit;
}

// LOG ACTIVITY - READ/VIEW (Opsional, bisa diaktifkan jika perlu)
// logActivity($_SESSION['user_id'], $_SESSION['username'], $_SESSION['role'], "Melihat detail motor: {$motor->nama_barang} (Kode: {$motor->kode_barang})");
?>