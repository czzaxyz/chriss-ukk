<?php
// action/contact/update_status.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include '../../app.php';

if (isset($_GET['verify']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $status = mysqli_real_escape_string($connect, $_GET['verify']);
    
    // Validasi status yang diperbolehkan
    if (in_array($status, ['read', 'replied'])) {
        $q_update = mysqli_query($connect, "UPDATE contacts SET status = '$status' WHERE id = $id");
        if ($q_update) {
            $_SESSION['success'] = "Status pesan berhasil diubah menjadi " . ucfirst($status);
            
            // Log activity
            if (function_exists('logActivity')) {
                logActivity("Mengubah status pesan ID $id menjadi $status");
            }
        } else {
            $_SESSION['error'] = "Gagal mengubah status pesan";
        }
    }
    
    header("Location: ../../pages/contact/index.php");
    exit;
}

header("Location: ../../pages/contact/index.php");
exit;
?>