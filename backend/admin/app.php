<?php
include '../../../../config/escapeString.php';
include '../../../../config/koneksi.php';

// Fungsi log activity
function logActivity($aktivitas) {
    global $connect;
    
    $id_user = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'System';
    $role = isset($_SESSION['role']) ? $_SESSION['role'] : 'Unknown';
    
    $username = mysqli_real_escape_string($connect, $username);
    $role = mysqli_real_escape_string($connect, $role);
    $aktivitas = mysqli_real_escape_string($connect, $aktivitas);
    
    $query = "INSERT INTO log_aktivitas (id_user, username, role, aktivitas, waktu) 
              VALUES ($id_user, '$username', '$role', '$aktivitas', NOW())";
    
    return mysqli_query($connect, $query);
}
?>