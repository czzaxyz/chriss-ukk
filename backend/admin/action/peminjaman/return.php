<?php
// action/peminjaman/return.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include '../../app.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Redirect ke form pengembalian
    header("Location: ../../pages/peminjaman/return.php?id=$id");
    exit;
} else {
    $_SESSION['error'] = "ID tidak valid!";
    header("Location: ../../pages/peminjaman/index.php");
    exit;
}
?>