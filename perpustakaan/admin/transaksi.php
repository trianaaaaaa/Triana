<?php
include 'includes/header.php';

// Handle Hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM transaksi WHERE id_transaksi = '$id'");
    header("Location: transaksi.php?msg=deleted");
    exit;
}

// Handle Update Status (Kembalikan)
if (isset($_GET['kembalikan'])) {
    $id = $_GET['kembalikan'];
    
    // Get book ID to restore stock
    $trans_data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id_buku FROM transaksi WHERE id_transaksi = '$id'"));
    $id_buku = $trans_data['id_buku'];
    
    // Update status and return date
    $tanggal_sekarang = date('Y-m-d');
    mysqli_query($koneksi, "UPDATE transaksi SET status='Dikembalikan', tanggal_kembali='$tanggal_sekarang' WHERE id_transaksi='$id'");
    
    // Restore book stock
    mysqli_query($koneksi, "UPDATE buku SET stok = stok + 1 WHERE id_buku = '$id_buku'");
    
    header("Location: transaksi.php?msg=returned");
    exit;
}

// Filter & Pencarian
$search = "";
$status_filter = "";
$query_parts = [];

if (!empty($_GET['search'])) {
    $search = mysqli_real_escape_string($koneksi, $_GET['search']);
    $query_parts[] = "(anggota.nama LIKE '%$search%' OR buku.judul LIKE '%$search%')";
}

if (!empty($_GET['status'])) {
    $status_filter = mysqli_real_escape_string($koneksi, $_GET['status']);
    $query_parts[] = "transaksi.status = '$status_filter'";
}

$where_clause = "";
if (count($query_parts) > 0) {
    $where_clause = "WHERE " . implode(" AND ", $query_parts);
}

$query = "SELECT transaksi.*, anggota.nama as nama_anggota, buku.judul as judul_buku 
          FROM transaksi 
          JOIN anggota ON transaksi.id_anggota = anggota.id_anggota 
          JOIN buku ON transaksi.id_buku = buku.id_buku 
          $where_clause
          ORDER BY transaksi.id_transaksi DESC";

$transaksi_result = mysqli_query($koneksi, $query);
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0">🔄 Kelola Transaksi</h2>
    </div>

    <?php if (isset($_GET['msg'])) : ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <?php
            if ($_GET['msg'] == 'returned') echo 'Status berhasil diubah menjadi Dikembalikan!';
            if ($_GET['msg'] == 'deleted') echo 'Data transaksi berhasil dihapus!';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="" method="GET" class="row g-2 mb-4">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama anggota atau judul buku..." value="<?= htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="Dipinjam" <?= $status_filter == 'Dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
                        <option value="Dikembalikan" <?= $status_filter == 'Dikembalikan' ? 'selected' : '' ?>>Dikembalikan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100">Filter</button>
                </div>
                <?php if ($search != "" || $status_filter != "") : ?>
                    <div class="col-md-2">
                        <a href="transaksi.php" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                <?php endif; ?>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Anggota</th>
                            <th>Judul Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($transaksi_result)) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td class="fw-bold"><?= $row['nama_anggota']; ?></td>
                                <td><?= $row['judul_buku']; ?></td>
                                <td><?= date('d-m-Y', strtotime($row['tanggal_pinjam'])); ?></td>
                                <td><?= $row['tanggal_kembali'] ? date('d-m-Y', strtotime($row['tanggal_kembali'])) : '-'; ?></td>
                                <td>
                                    <?php if ($row['status'] == 'Dipinjam') : ?>
                                        <span class="badge bg-warning text-dark">Dipinjam</span>
                                    <?php else : ?>
                                        <span class="badge bg-success">Dikembalikan</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($row['status'] == 'Dipinjam') : ?>
                                        <a href="transaksi.php?kembalikan=<?= $row['id_transaksi']; ?>" class="btn btn-primary btn-sm" onclick="return confirm('Konfirmasi pengembalian buku?')">Kembalikan</a>
                                    <?php endif; ?>
                                    <a href="transaksi.php?hapus=<?= $row['id_transaksi']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data transaksi ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <?php if (mysqli_num_rows($transaksi_result) == 0) : ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Tidak ada data transaksi.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
