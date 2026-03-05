<?php
include 'includes/header.php';

$id_anggota = $_SESSION['id'];

// Handle Pengembalian
if (isset($_GET['kembali'])) {
    $id_transaksi = $_GET['kembali'];
    $tanggal_kembali = date('Y-m-d');

    // Get book ID to restore stock
    $trans = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id_buku FROM transaksi WHERE id_transaksi = '$id_transaksi' AND id_anggota = '$id_anggota'"));
    
    if ($trans) {
        $id_buku = $trans['id_buku'];
        // Update status
        mysqli_query($koneksi, "UPDATE transaksi SET status = 'Dikembalikan', tanggal_kembali = '$tanggal_kembali' WHERE id_transaksi = '$id_transaksi'");
        // Restore stock
        mysqli_query($koneksi, "UPDATE buku SET stok = stok + 1 WHERE id_buku = '$id_buku'");
        
        header("Location: riwayat.php?msg=returned");
    }
    exit;
}

$query = "SELECT transaksi.*, buku.judul 
          FROM transaksi 
          JOIN buku ON transaksi.id_buku = buku.id_buku 
          WHERE transaksi.id_anggota = '$id_anggota' AND transaksi.status = 'Dipinjam'
          ORDER BY transaksi.tanggal_pinjam DESC";
$result = mysqli_query($koneksi, $query);
?>

<div class="container-fluid">
    <h2 class="fw-bold mb-4">↩️ Pengembalian Buku</h2>

    <div class="card p-4 border-0 shadow-sm">
        <h5 class="fw-bold mb-3 text-muted">Buku yang anda bawa:</h5>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Judul Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td class="fw-bold"><?= $row['judul']; ?></td>
                            <td><?= date('d M Y', strtotime($row['tanggal_pinjam'])); ?></td>
                            <td><span class="badge bg-warning text-dark">Dipinjam</span></td>
                            <td class="text-center">
                                <a href="pengembalian.php?kembali=<?= $row['id_transaksi']; ?>" class="btn btn-outline-success btn-sm px-3 rounded-pill" onclick="return confirm('Kembalikan buku ini?')">Kembalikan Sekarang</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>

                    <?php if (mysqli_num_rows($result) == 0) : ?>
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <p class="text-muted mb-0">Kamu tidak memiliki pinjaman aktif.</p>
                                <a href="buku.php" class="btn btn-primary btn-sm mt-3">Cari Buku</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
