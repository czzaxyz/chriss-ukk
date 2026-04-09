<?php
include '../../app.php';
session_start();

if (isset($_POST['tombol'])) {
    $username = escapeString($_POST['username']);
    $password = $_POST['password'];
    $role = escapeString($_POST['role']);
    $nama_lengkap = escapeString($_POST['nama_lengkap']);
    
    // Handle nullable fields
    $email = !empty($_POST['email']) ? escapeString($_POST['email']) : null;
    $no_telp = !empty($_POST['no_telp']) ? escapeString($_POST['no_telp']) : null;
    $alamat = !empty($_POST['alamat']) ? escapeString($_POST['alamat']) : null;
    
    // Validasi username unik
    $checkUsername = mysqli_query($connect, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($checkUsername) > 0) {
        $_SESSION['error'] = "Username sudah digunakan!";
        header("Location: ../../pages/user/create.php");
        exit;
    }
    
    // Validasi email unik (jika email diisi)
    if (!empty($email)) {
        $checkEmail = mysqli_query($connect, "SELECT * FROM users WHERE email = '$email'");
        if (mysqli_num_rows($checkEmail) > 0) {
            $_SESSION['error'] = "Email sudah digunakan!";
            header("Location: ../../pages/user/create.php");
            exit;
        }
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert query dinamis (menangani NULL)
    $qInsert = "INSERT INTO users (username, password, role, nama_lengkap, email, no_telp, alamat, created_at) 
                VALUES ('$username', '$hashed_password', '$role', '$nama_lengkap', " . ($email ? "'$email'" : "NULL") . ", " . ($no_telp ? "'$no_telp'" : "NULL") . ", " . ($alamat ? "'$alamat'" : "NULL") . ", NOW())";
    
    if (mysqli_query($connect, $qInsert)) {
        $aktivitas = "Menambahkan user baru: $username (Role: $role)";
        logActivity($aktivitas);
        $_SESSION['success'] = "User berhasil ditambahkan!";
        header("Location: ../../pages/user/index.php");
        exit;
    } else {
        $_SESSION['error'] = "Gagal: " . mysqli_error($connect);
        header("Location: ../../pages/user/create.php");
        exit;
    }
}
?>