<?php include 'includes/header.php'; ?>

<div class="container-fluid">
    <h2 class="fw-bold mb-4">Dashboard Overview</h2>

    <div class="row g-4">
        <!-- Count Buku -->
        <?php
        $countBuku = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku"))['total'];
        $countAnggota = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM anggota"))['total'];
        $countTransaksi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM transaksi WHERE status = 'Dipinjam'"))['total'];
        ?>
        <div class="col-md-4">
            <div class="card p-3 border-start border-primary border-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Total Buku</p>
                        <h3 class="fw-bold mb-0"><?= $countBuku; ?></h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded text-primary">
                        <span style="font-size: 1.5rem;">📚</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 border-start border-success border-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Total Anggota</p>
                        <h3 class="fw-bold mb-0"><?= $countAnggota; ?></h3>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded text-success">
                        <span style="font-size: 1.5rem;">👥</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 border-start border-warning border-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Peminjaman Aktif</p>
                        <h3 class="fw-bold mb-0"><?= $countTransaksi; ?></h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded text-warning">
                        <span style="font-size: 1.5rem;">🔄</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card shadow-sm p-4 text-center bg-white">
                <h4 class="fw-bold text-primary">Selamat Bekerja, Administrator!</h4>
                <p class="text-muted">Gunakan menu di samping kiri untuk mengelola data perpustakaan digital.</p>
                <div class="mt-3">
                    <a href="buku.php" class="btn btn-outline-primary btn-sm mx-1">Kelola Buku</a>
                    <a href="anggota.php" class="btn btn-outline-primary btn-sm mx-1">Kelola Anggota</a>
                    <a href="transaksi.php" class="btn btn-outline-primary btn-sm mx-1">Kelola Transaksi</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
