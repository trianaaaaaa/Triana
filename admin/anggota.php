<?php
include 'includes/header.php';

// Handle Hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = mysqli_prepare($koneksi, "DELETE FROM anggota WHERE id_anggota = ?");
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    header("Location: anggota.php?msg=deleted");
    exit;
}

// Handle Tambah
if (isset($_POST['tambah'])) {
    $id_anggota = $_POST['id_anggota'];
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    // Check unique username
    $stmt_check = mysqli_prepare($koneksi, "SELECT username FROM anggota WHERE username = ?");
    mysqli_stmt_bind_param($stmt_check, "s", $username);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        header("Location: anggota.php?msg=error_username");
    } else {
        $stmt_insert = mysqli_prepare($koneksi, "INSERT INTO anggota (id_anggota, nama, kelas, username, password) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt_insert, "sssss", $id_anggota, $nama, $kelas, $username, $password);
        mysqli_stmt_execute($stmt_insert);
        header("Location: anggota.php?msg=added");
    }
    exit;
}

// Handle Edit
if (isset($_POST['edit'])) {
    $id_anggota = $_POST['id_anggota'];
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $username = $_POST['username'];
    
    // Update password only if provided
    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']);
        $stmt = mysqli_prepare($koneksi, "UPDATE anggota SET nama=?, kelas=?, username=?, password=? WHERE id_anggota=?");
        mysqli_stmt_bind_param($stmt, "sssss", $nama, $kelas, $username, $password, $id_anggota);
    } else {
        $stmt = mysqli_prepare($koneksi, "UPDATE anggota SET nama=?, kelas=?, username=? WHERE id_anggota=?");
        mysqli_stmt_bind_param($stmt, "ssss", $nama, $kelas, $username, $id_anggota);
    }

    mysqli_stmt_execute($stmt);
    header("Location: anggota.php?msg=updated");
    exit;
}

// Pencarian
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $search_param = "%$search%";
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM anggota WHERE nama LIKE ? OR id_anggota LIKE ? ORDER BY id_anggota DESC");
    mysqli_stmt_bind_param($stmt, "ss", $search_param, $search_param);
    mysqli_stmt_execute($stmt);
    $anggota_result = mysqli_stmt_get_result($stmt);
} else {
    $anggota_result = mysqli_query($koneksi, "SELECT * FROM anggota ORDER BY id_anggota DESC");
}
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0">👥 Kelola Anggota</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">➕ Tambah Anggota</button>
    </div>

    <?php if (isset($_GET['msg'])) : ?>
        <div class="alert alert-<?= ($_GET['msg'] == 'error_username') ? 'danger' : 'success' ?> alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <?php
            if ($_GET['msg'] == 'added') echo 'Anggota berhasil ditambahkan!';
            if ($_GET['msg'] == 'updated') echo 'Anggota berhasil diperbarui!';
            if ($_GET['msg'] == 'deleted') echo 'Anggota berhasil dihapus!';
            if ($_GET['msg'] == 'error_username') echo 'Username sudah digunakan oleh anggota lain!';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="" method="GET" class="row g-2 mb-4">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama atau ID..." value="<?= htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100">Cari</button>
                </div>
                <?php if ($search != "") : ?>
                    <div class="col-md-2">
                        <a href="anggota.php" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                <?php endif; ?>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID / NIS</th>
                            <th>Nama Lengkap</th>
                            <th>Kelas</th>
                            <th>Username</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($anggota_result)) : ?>
                            <tr>
                                <td class="fw-bold"><?= $row['id_anggota']; ?></td>
                                <td><?= $row['nama']; ?></td>
                                <td><?= $row['kelas']; ?></td>
                                <td><span class="badge bg-light text-dark"><?= $row['username']; ?></span></td>
                                <td class="text-center">
                                    <button class="btn btn-warning btn-sm text-white" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_anggota']; ?>">Edit</button>
                                    <a href="anggota.php?hapus=<?= $row['id_anggota']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="modalEdit<?= $row['id_anggota']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="" method="POST">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Anggota</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="id_anggota" value="<?= $row['id_anggota']; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Lengkap</label>
                                                    <input type="text" name="nama" class="form-control" value="<?= $row['nama']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Kelas</label>
                                                    <input type="text" name="kelas" class="form-control" value="<?= $row['kelas']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Username</label>
                                                    <input type="text" name="username" class="form-control" value="<?= $row['username']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Ganti Password (Kosongkan jika tidak ingin ganti)</label>
                                                    <input type="password" name="password" class="form-control">
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
                    <h5 class="modal-title">Tambah Anggota Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ID / NIS</label>
                        <input type="text" name="id_anggota" class="form-control" placeholder="USR001" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kelas</label>
                        <input type="text" name="kelas" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah" class="btn btn-primary">Tambah Anggota</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
