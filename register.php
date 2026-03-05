<?php
session_start();
require 'config/koneksi.php';

if (isset($_POST['register'])) {
    $id_anggota = $_POST['id_anggota'];
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    // Cek username unik
    $stmt_check = mysqli_prepare($koneksi, "SELECT username FROM anggota WHERE username = ?");
    mysqli_stmt_bind_param($stmt_check, "s", $username);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        $error_msg = "Username sudah digunakan!";
    } else {
        $stmt_insert = mysqli_prepare($koneksi, "INSERT INTO anggota (id_anggota, nama, kelas, username, password) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt_insert, "sssss", $id_anggota, $nama, $kelas, $username, $password);
        
        if (mysqli_stmt_execute($stmt_insert)) {
            $success_msg = "Pendaftaran Berhasil! Silahkan Login.";
        } else {
            $error_msg = "Pendaftaran Gagal: " . mysqli_stmt_error($stmt_insert);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Siswa - Perpustakaan Digital</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-card {
            width: 100%;
            max-width: 500px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="card register-card p-4 my-5">
    <div class="text-center mb-4">
        <h3 class="fw-bold text-primary">Daftar Akun Siswa</h3>
        <p class="text-muted">Lengkapi data diri untuk meminjam buku</p>
    </div>

    <?php if (isset($error_msg)) : ?>
        <div class="alert alert-danger text-center py-2" role="alert"><?= $error_msg; ?></div>
    <?php endif; ?>

    <?php if (isset($success_msg)) : ?>
        <div class="alert alert-success text-center py-2" role="alert"><?= $success_msg; ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="id_anggota" class="form-label">NIS / ID Anggota</label>
                <input type="text" name="id_anggota" class="form-control" placeholder="Contoh: USR001" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="kelas" class="form-label">Kelas</label>
            <input type="text" name="kelas" class="form-control" placeholder="Contoh: XII RPL 1" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" name="register" class="btn btn-primary w-100 py-2 mb-3">Daftar Sekarang</button>
        <p class="text-center mb-0">Sudah punya akun? <a href="login.php" class="text-decoration-none text-primary">Login Disini</a></p>
    </form>
</div>

<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
