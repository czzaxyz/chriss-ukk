<?php
include '../../app.php';
session_start();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (isset($_POST['tombol']) && $id > 0) {
    $username = escapeString($_POST['username']);
    $password = $_POST['password'];
    $role = escapeString($_POST['role']);
    $nama_lengkap = escapeString($_POST['nama_lengkap']);
    
    // Handle nullable fields
    $email = !empty($_POST['email']) ? escapeString($_POST['email']) : null;
    $no_telp = !empty($_POST['no_telp']) ? escapeString($_POST['no_telp']) : null;
    $alamat = !empty($_POST['alamat']) ? escapeString($_POST['alamat']) : null;
    
    // Validasi username unik (abaikan dirinya sendiri)
    $checkUsername = mysqli_query($connect, "SELECT * FROM users WHERE username = '$username' AND id != $id");
    if (mysqli_num_rows($checkUsername) > 0) {
        $_SESSION['error'] = "Username sudah digunakan!";
        header("Location: ../../pages/user/edit.php?id=$id");
        exit;
    }
    
    // Validasi email unik (jika email diisi)
    if (!empty($email)) {
        $checkEmail = mysqli_query($connect, "SELECT * FROM users WHERE email = '$email' AND id != $id");
        if (mysqli_num_rows($checkEmail) > 0) {
            $_SESSION['error'] = "Email sudah digunakan!";
            header("Location: ../../pages/user/edit.php?id=$id");
            exit;
        }
    }
    
    // Build query update
    $sql = "UPDATE users SET 
            username = '$username',
            role = '$role',
            nama_lengkap = '$nama_lengkap',
            email = " . ($email ? "'$email'" : "NULL") . ",
            no_telp = " . ($no_telp ? "'$no_telp'" : "NULL") . ",
            alamat = " . ($alamat ? "'$alamat'" : "NULL");
    
    // Update password jika diisi
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql .= ", password = '$hashed_password'";
    }
    
    $sql .= " WHERE id = $id";
    
    if (mysqli_query($connect, $sql)) {
        $aktivitas = "Mengupdate user: $username (Role: $role)";
        logActivity($aktivitas);
        $_SESSION['success'] = "User berhasil diupdate!";
        header("Location: ../../pages/user/index.php");
        exit;
    } else {
        $_SESSION['error'] = "Gagal: " . mysqli_error($connect);
        header("Location: ../../pages/user/edit.php?id=$id");
        exit;
    }
}
?>