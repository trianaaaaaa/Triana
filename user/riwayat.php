<?php
include 'includes/header.php';

$id_anggota = $_SESSION['id'];

$query = "SELECT transaksi.*, buku.judul 
          FROM transaksi 
          JOIN buku ON transaksi.id_buku = buku.id_buku 
          WHERE transaksi.id_anggota = '$id_anggota'
          ORDER BY transaksi.id_transaksi DESC";
$result = mysqli_query($koneksi, $query);
?>

<div class="container-fluid">
    <h2 class="fw-bold mb-4">📜 Riwayat Transaksi</h2>

    <?php if (isset($_GET['msg'])) : ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <?php 
                if($_GET['msg'] == 'success') echo 'Buku berhasil dipinjam!';
                if($_GET['msg'] == 'returned') echo 'Buku berhasil dikembalikan!';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card p-4 border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Judul Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td class="fw-bold"><?= $row['judul']; ?></td>
                            <td><?= date('d M Y', strtotime($row['tanggal_pinjam'])); ?></td>
                            <td><?= $row['tanggal_kembali'] ? date('d M Y', strtotime($row['tanggal_kembali'])) : '-'; ?></td>
                            <td>
                                <?php if ($row['status'] == 'Dipinjam') : ?>
                                    <span class="badge bg-warning text-dark">Sedang Dipinjam</span>
                                <?php else : ?>
                                    <span class="badge bg-success">Sudah Dikembalikan</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>

                    <?php if (mysqli_num_rows($result) == 0) : ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Belum ada riwayat transaksi.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
