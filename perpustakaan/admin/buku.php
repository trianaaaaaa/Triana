<?php
include 'includes/header.php';

// Handle Hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = mysqli_prepare($koneksi, "DELETE FROM buku WHERE id_buku = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    header("Location: buku.php?msg=deleted");
    exit;
}

// Handle Tambah
if (isset($_POST['tambah'])) {
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $penerbit = $_POST['penerbit'];
    $tahun = $_POST['tahun'];
    $stok = $_POST['stok'];

    $stmt = mysqli_prepare($koneksi, "INSERT INTO buku (judul, pengarang, penerbit, tahun, stok) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssssi", $judul, $pengarang, $penerbit, $tahun, $stok);
    mysqli_stmt_execute($stmt);
    header("Location: buku.php?msg=added");
    exit;
}

// Handle Edit
if (isset($_POST['edit'])) {
    $id = $_POST['id_buku'];
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $penerbit = $_POST['penerbit'];
    $tahun = $_POST['tahun'];
    $stok = $_POST['stok'];

    $stmt = mysqli_prepare($koneksi, "UPDATE buku SET judul=?, pengarang=?, penerbit=?, tahun=?, stok=? WHERE id_buku=?");
    mysqli_stmt_bind_param($stmt, "ssssii", $judul, $pengarang, $penerbit, $tahun, $stok, $id);
    mysqli_stmt_execute($stmt);
    header("Location: buku.php?msg=updated");
    exit;
}

// Pencarian
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $search_param = "%$search%";
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM buku WHERE judul LIKE ? OR pengarang LIKE ? ORDER BY id_buku DESC");
    mysqli_stmt_bind_param($stmt, "ss", $search_param, $search_param);
    mysqli_stmt_execute($stmt);
    $buku_result = mysqli_stmt_get_result($stmt);
} else {
    $buku_result = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY id_buku DESC");
}
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0">📚 Daftar Buku</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">➕ Tambah Buku</button>
    </div>

    <?php if (isset($_GET['msg'])) : ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <?php
            if ($_GET['msg'] == 'added') echo 'Buku berhasil ditambahkan!';
            if ($_GET['msg'] == 'updated') echo 'Buku berhasil diperbarui!';
            if ($_GET['msg'] == 'deleted') echo 'Buku berhasil dihapus!';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="" method="GET" class="row g-2 mb-4">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari judul atau pengarang..." value="<?= htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100">Cari</button>
                </div>
                <?php if ($search != "") : ?>
                    <div class="col-md-2">
                        <a href="buku.php" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                <?php endif; ?>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Judul</th>
                            <th>Pengarang</th>
                            <th>Penerbit</th>
                            <th>Tahun</th>
                            <th>Stok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($buku_result)) : ?>
                            <tr>
                                <td><?= $row['id_buku']; ?></td>
                                <td class="fw-semibold"><?= $row['judul']; ?></td>
                                <td><?= $row['pengarang']; ?></td>
                                <td><?= $row['penerbit']; ?></td>
                                <td><?= $row['tahun']; ?></td>
                                <td>
                                    <span class="badge <?= $row['stok'] > 0 ? 'bg-info' : 'bg-danger'; ?>">
                                        <?= $row['stok']; ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-warning btn-sm text-white" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_buku']; ?>">Edit</button>
                                    <a href="buku.php?hapus=<?= $row['id_buku']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="modalEdit<?= $row['id_buku']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="" method="POST">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Buku</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="id_buku" value="<?= $row['id_buku']; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label">Judul Buku</label>
                                                    <input type="text" name="judul" class="form-control" value="<?= $row['judul']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Pengarang</label>
                                                    <input type="text" name="pengarang" class="form-control" value="<?= $row['pengarang']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Penerbit</label>
                                                    <input type="text" name="penerbit" class="form-control" value="<?= $row['penerbit']; ?>" required>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Tahun</label>
                                                        <input type="number" name="tahun" class="form-control" value="<?= $row['tahun']; ?>" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Stok</label>
                                                        <input type="number" name="stok" class="form-control" value="<?= $row['stok']; ?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" name="edit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Buku Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Buku</label>
                        <input type="text" name="judul" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pengarang</label>
                        <input type="text" name="pengarang" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Penerbit</label>
                        <input type="text" name="penerbit" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun</label>
                            <input type="number" name="tahun" class="form-control" value="<?= date('Y'); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" name="stok" class="form-control" value="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah" class="btn btn-primary">Tambah Buku</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
