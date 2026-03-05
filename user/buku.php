<?php
include 'includes/header.php';

$id_anggota = $_SESSION['id'];

// Handle Peminjaman
if (isset($_GET['pinjam'])) {
    $id_buku = $_GET['pinjam'];
    $tanggal_pinjam = date('Y-m-d');

    // Cek stok
    $buku = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT stok FROM buku WHERE id_buku = '$id_buku'"));
    
    if ($buku['stok'] > 0) {
        // Kurangi stok
        mysqli_query($koneksi, "UPDATE buku SET stok = stok - 1 WHERE id_buku = '$id_buku'");
        
        // Input transaksi
        mysqli_query($koneksi, "INSERT INTO transaksi (id_anggota, id_buku, tanggal_pinjam, status) VALUES ('$id_anggota', '$id_buku', '$tanggal_pinjam', 'Dipinjam')");
        
        header("Location: riwayat.php?msg=success");
    } else {
        header("Location: buku.php?msg=out_of_stock");
    }
    exit;
}

// Pencarian
$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($koneksi, $_GET['search']);
    $query = "SELECT * FROM buku WHERE judul LIKE '%$search%' ORDER BY judul ASC";
} else {
    $query = "SELECT * FROM buku ORDER BY judul ASC";
}
$result = mysqli_query($koneksi, $query);
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0">📖 Eksplorasi Buku</h2>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'out_of_stock') : ?>
        <div class="alert alert-danger" role="alert">Maaf, stok buku ini sudah habis!</div>
    <?php endif; ?>

    <div class="card p-3 mb-4 border-0 shadow-sm bg-white">
        <form action="" method="GET" class="row g-2">
            <div class="col-md-9">
                <input type="text" name="search" class="form-control form-control-lg" placeholder="Cari buku berdasarkan judul..." value="<?= htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-lg w-100">Cari Buku</button>
            </div>
        </form>
    </div>

    <div class="row g-4">
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold text-dark mb-0"><?= $row['judul']; ?></h5>
                            <span class="badge <?= $row['stok'] > 0 ? 'bg-success' : 'bg-danger'; ?> bg-opacity-10 text-<?= $row['stok'] > 0 ? 'success' : 'danger'; ?> border border-<?= $row['stok'] > 0 ? 'success' : 'danger'; ?>">
                                Stok: <?= $row['stok']; ?>
                            </span>
                        </div>
                        <p class="text-muted small mb-3">Oleh: <?= $row['pengarang']; ?></p>
                        
                        <div class="row mb-3 bg-light p-2 rounded mx-0">
                            <div class="col-6">
                                <small class="text-muted d-block uppercase" style="font-size: 0.7rem;">Penerbit</small>
                                <span class="fw-semibold small"><?= $row['penerbit']; ?></span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block uppercase" style="font-size: 0.7rem;">Tahun</small>
                                <span class="fw-semibold small"><?= $row['tahun']; ?></span>
                            </div>
                        </div>

                        <?php if ($row['stok'] > 0) : ?>
                            <a href="buku.php?pinjam=<?= $row['id_buku']; ?>" class="btn btn-primary w-100 rounded-pill" onclick="return confirm('Pinjam buku ini?')">Pinjam Buku</a>
                        <?php else : ?>
                            <button class="btn btn-secondary w-100 rounded-pill" disabled>Stok Habis</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
        
        <?php if (mysqli_num_rows($result) == 0) : ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">Buku tidak ditemukan.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
    .transition {
        transition: all 0.3s ease;
    }
</style>

<?php include 'includes/footer.php'; ?>
