<?php
session_start();
require 'config/koneksi.php';

if (isset($_SESSION['login'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: /admin/dashboard");
    } else {
        header("Location: /user/dashboard");
    }
    exit;
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password_input = $_POST['password'];
    $password_hashed = md5($password_input);
    $role = $_POST['role'];

    if ($role == 'admin') {
        // First, check if user exists
        $stmt_check = mysqli_prepare($koneksi, "SELECT id_admin, nama, password FROM admin WHERE username = ?");
        if ($stmt_check) {
            mysqli_stmt_bind_param($stmt_check, "s", $username);
            mysqli_stmt_execute($stmt_check);
            $result = mysqli_stmt_get_result($stmt_check);

            if (mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
                // Check password
                if ($row['password'] === $password_hashed) {
                    $_SESSION['login'] = true;
                    $_SESSION['id'] = $row['id_admin'];
                    $_SESSION['nama'] = $row['nama'];
                    $_SESSION['role'] = 'admin';
                    header("Location: /admin/dashboard");
                    exit;
                } else {
                    $error = "Password salah untuk admin '$username'.";
                }
            } else {
                // Check if table is empty
                $check_empty = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM admin");
                $data_empty = mysqli_fetch_assoc($check_empty);
                if ($data_empty['total'] == 0) {
                    $error = "Tabel 'admin' kosong. Silakan import database.sql.";
                } else {
                    $error = "Username admin '$username' tidak ditemukan.";
                }
            }
        } else {
            die("Error pada query admin: " . mysqli_error($koneksi));
        }
    } else {
        // Check for user (anggota)
        $stmt_check = mysqli_prepare($koneksi, "SELECT id_anggota, nama, password FROM anggota WHERE username = ?");
        if ($stmt_check) {
            mysqli_stmt_bind_param($stmt_check, "s", $username);
            mysqli_stmt_execute($stmt_check);
            $result = mysqli_stmt_get_result($stmt_check);

            if (mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
                if ($row['password'] === $password_hashed) {
                    $_SESSION['login'] = true;
                    $_SESSION['id'] = $row['id_anggota'];
                    $_SESSION['nama'] = $row['nama'];
                    $_SESSION['role'] = 'user';
                    header("Location: /user/dashboard");
                    exit;
                } else {
                    $error = "Password salah untuk user '$username'.";
                }
            } else {
                // Check if table is empty
                $check_empty = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM anggota");
                $data_empty = mysqli_fetch_assoc($check_empty);
                if ($data_empty['total'] == 0) {
                    $error = "Tabel 'anggota' kosong. Silakan import database.sql atau daftar akun baru.";
                } else {
                    $error = "Username user '$username' tidak ditemukan.";
                }
            }
        } else {
            die("Error pada query anggota: " . mysqli_error($koneksi));
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Perpustakaan Digital</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>
<body>

<div class="card login-card p-4">
    <div class="text-center mb-4">
        <h3 class="fw-bold text-primary">Perpustakaan Digital</h3>
        <p class="text-muted">Silahkan login untuk melanjutkan</p>
    </div>

    <?php if (isset($error)) : ?>
        <div class="alert alert-danger text-center py-2" role="alert">
            <?= is_string($error) ? $error : "Username atau Password salah!"; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" id="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="password" required>
        </div>
        <div class="mb-4">
            <label class="form-label">Login Sebagai</label>
            <select name="role" class="form-select" required>
                <option value="user">Siswa (Anggota)</option>
                <option value="admin">Administrator</option>
            </select>
        </div>
        <button type="submit" name="login" class="btn btn-primary w-100 py-2 mb-3">Login Sekarang</button>
        <p class="text-center mb-0">Belum punya akun? <a href="register.php" class="text-decoration-none text-primary">Daftar Siswa</a></p>
    </form>
</div>

<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
