<?php include 'includes/header.php'; ?>

<?php
$id_anggota = $_SESSION['id'];
$countPinjam = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM transaksi WHERE id_anggota = '$id_anggota' AND status = 'Dipinjam'"))['total'];
$countTotal = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM transaksi WHERE id_anggota = '$id_anggota'"))['total'];
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-primary text-white p-4 overflow-hidden position-relative">
                <div class="position-relative z-index-2">
                    <h2 class="fw-bold">Halo, <?= $_SESSION['nama']; ?>! 👋</h2>
                    <p class="mb-0 opacity-75">Sudahkah Anda membaca buku hari ini? Jelajahi koleksi kami sekarang.</p>
                </div>
                <!-- Subtle decorative circle -->
                <div class="position-absolute bg-white opacity-10 rounded-circle" style="width: 200px; height: 200px; top: -50px; right: -50px;"></div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card p-3 border-start border-warning border-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                        <span style="font-size: 1.5rem;">📖</span>
                    </div>
                    <div>
                        <p class="text-muted mb-0">Buku Sedang Dipinjam</p>
                        <h3 class="fw-bold mb-0"><?= $countPinjam; ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3 border-start border-success border-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <span style="font-size: 1.5rem;">📜</span>
                    </div>
                    <div>
                        <p class="text-muted mb-0">Total Riwayat Pinjam</p>
                        <h3 class="fw-bold mb-0"><?= $countTotal; ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-4">
        <h5 class="fw-bold border-bottom pb-2 mb-3">Informasi Penting</h5>
        <ul class="text-muted">
            <li>Batas waktu peminjaman adalah 7 hari.</li>
            <li>Harap menjaga kebersihan dan keutuhan buku.</li>
            <li>Jika terlambat mengembalikan, silakan hubungi petugas.</li>
        </ul>
        <div class="mt-2 text-center">
            <a href="buku.php" class="btn btn-primary px-4">Cari & Pinjam Buku</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
