<?php
require '../config/koneksi.php';
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: /login");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Perpustakaan Digital</title>
    <!-- Local Bootstrap CSS -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <!-- Font Awesome (Optional, but icons look better. Prompt said no internet, so I'll skip CDN icons and use generic bootstrap icons if available or just text) -->
    <style>
        :root {
            --sidebar-width: 250px;
            --soft-blue: #e7f1ff;
            --primary-blue: #0d6efd;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        #wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }
        #sidebar {
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            background: #fff;
            color: #333;
            transition: all 0.3s;
            border-right: 1px solid #dee2e6;
            min-height: 100vh;
        }
        #sidebar .sidebar-header {
            padding: 20px;
            background: #fff;
            border-bottom: 1px solid #eee;
        }
        #sidebar ul.components {
            padding: 20px 0;
        }
        #sidebar ul li a {
            padding: 12px 20px;
            font-size: 1.1em;
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
        }
        #content {
            width: 100%;
            padding: 20px;
            transition: all 0.3s;
        }
        .navbar {
            padding: 15px 10px;
            background: #fff;
            border: none;
            border-radius: 0;
            margin-bottom: 40px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>

<div id="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <h4 class="fw-bold text-primary mb-0">LIBRA <span class="text-dark">DIGITAL</span></h4>
            <small class="text-muted">Administrator Panel</small>
        </div>

        <ul class="list-unstyled components">
            <li class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
                <a href="dashboard.php">📊 Dashboard</a>
            </li>
            <li class="<?= basename($_SERVER['PHP_SELF']) == 'buku.php' ? 'active' : '' ?>">
                <a href="buku.php">📚 Kelola Buku</a>
            </li>
            <li class="<?= basename($_SERVER['PHP_SELF']) == 'anggota.php' ? 'active' : '' ?>">
                <a href="anggota.php">👥 Kelola Anggota</a>
            </li>
            <li class="<?= basename($_SERVER['PHP_SELF']) == 'transaksi.php' ? 'active' : '' ?>">
                <a href="transaksi.php">🔄 Kelola Transaksi</a>
            </li>
            <li>
                <hr>
            </li>
            <li>
                <a href="../logout.php" onclick="return confirm('Yakin ingin logout?')">🚪 Logout</a>
            </li>
        </ul>
    </nav>

    <!-- Page Content -->
    <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow-sm mb-4">
            <div class="container-fluid">
                <span class="navbar-text fw-semibold">
                    Selamat Datang, <?= $_SESSION['nama']; ?>
                </span>
                <div class="ms-auto">
                    <span class="badge bg-primary">Admin</span>
                </div>
            </div>
        </nav>
