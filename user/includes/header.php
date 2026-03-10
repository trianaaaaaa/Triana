<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'user') {
    header("Location: /login");
    exit;
}
require '../config/koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siswa - Perpustakaan Digital</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        :root {
            --sidebar-width: 250px;
            --soft-blue: #eef2f7;
            --primary-blue: #0d6efd;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        #wrapper {
            display: flex;
            width: 100%;
        }
        #sidebar {
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            background: #fff;
            min-height: 100vh;
            border-right: 1px solid #dee2e6;
        }
        #sidebar .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        #sidebar ul.components {
            padding: 20px 0;
        }
        #sidebar ul li a {
            padding: 12px 20px;
            display: block;
            color: #333;
            text-decoration: none;
        }
        #sidebar ul li a:hover {
            background-color: var(--soft-blue);
            color: var(--primary-blue);
        }
        #sidebar ul li.active > a {
            background: var(--soft-blue);
            color: var(--primary-blue);
            border-right: 4px solid var(--primary-blue);
            font-weight: bold;
        }
        #content {
            width: 100%;
            padding: 20px;
        }
        .navbar {
            padding: 15px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<div id="wrapper">
    <nav id="sidebar">
        <div class="sidebar-header text-center">
            <h4 class="fw-bold text-primary mb-0">LIBRA <span class="text-dark">STUDENT</span></h4>
        </div>
        <ul class="list-unstyled components">
            <li class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
                <a href="dashboard.php">🏠 Dashboard</a>
            </li>
            <li class="<?= basename($_SERVER['PHP_SELF']) == 'buku.php' ? 'active' : '' ?>">
                <a href="buku.php">📖 Daftar Buku</a>
            </li>
            <li class="<?= basename($_SERVER['PHP_SELF']) == 'pengembalian.php' ? 'active' : '' ?>">
                <a href="pengembalian.php">↩️ Pengembalian</a>
            </li>
            <li class="<?= basename($_SERVER['PHP_SELF']) == 'riwayat.php' ? 'active' : '' ?>">
                <a href="riwayat.php">📜 Riwayat Pinjam</a>
            </li>
            <li class="mt-4">
                <a href="../logout.php" class="text-danger" onclick="return confirm('Yakin ingin logout?')">🚪 Logout</a>
            </li>
        </ul>
    </nav>

    <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold;">
                            <?= substr($_SESSION['nama'], 0, 1); ?>
                        </div>
                    </div>
                    <div>
                        <p class="mb-0 fw-bold"><?= $_SESSION['nama']; ?></p>
                        <small class="text-muted">ID: <?= $_SESSION['id']; ?></small>
                    </div>
                </div>
            </div>
        </nav>
