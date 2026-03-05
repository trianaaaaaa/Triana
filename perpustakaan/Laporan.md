# Dokumentasi Aplikasi Perpustakaan Digital (UKK)

## 1. ERD (Entity Relationship Diagram) Sederhana

**Entitas & Atribut:**
*   **Admin**: `id_admin` (PK), `nama`, `username`, `password`
*   **Anggota**: `id_anggota` (PK), `nama`, `kelas`, `username`, `password`
*   **Buku**: `id_buku` (PK), `judul`, `pengarang`, `penerbit`, `tahun`, `stok`
*   **Transaksi**: `id_transaksi` (PK), `id_anggota` (FK), `id_buku` (FK), `tanggal_pinjam`, `tanggal_kembali`, `status`

**Relasi:**
*   **Anggota (1) -- Transaksi (M)**: Satu anggota dapat melakukan banyak transaksi peminjaman.
*   **Buku (1) -- Transaksi (M)**: Satu buku dapat dipinjam dalam banyak transaksi.

---

## 2. Penjelasan Singkat Sistem
Aplikasi Perpustakaan Digital ini dirancang untuk memudahkan manajemen perpustakaan sekolah secara mandiri di jaringan lokal (localhost). Sistem mendukung dua level pengguna: **Admin** untuk pengelolaan data master (buku & anggota) serta pengawasan transaksi, dan **Siswa (User)** untuk melakukan pencarian buku, peminjaman secara mandiri, serta melihat riwayat pinjaman mereka.

---

## 3. Penjelasan Fungsi Utama
*   **Keamanan (Auth)**: Menggunakan sesi PHP (`session`) dan hashing password MD5 sesuai spesifikasi. Validasi dilakukan di sisi server untuk mencegah akses tanpa login.
*   **Kelola Buku (Admin)**: CRUD lengkap dengan fitur stok otomatis (berkurang saat dipinjam, bertambah saat dikembalikan).
*   **Pencarian**: Fitur pencarian data menggunakan query `LIKE` yang efisien pada sisi Admin maupun User.
*   **Siklus Peminjaman**: 
    *   User memilih buku -> Klik Pinjam -> Stok berkurang -> Transaksi tercatat sebagai 'Dipinjam'.
    *   User/Admin mengembalikan buku -> Klik Kembali -> Stok bertambah -> Status menjadi 'Dikembalikan' dan tanggal kembali tercatat otomatis.

---

## 4. Laporan Evaluasi Singkat
*   **Kesesuaian**: Seluruh spesifikasi yang diminta dalam soal UKK telah diimplementasikan, termasuk struktur folder, teknologi (PHP Native, Bootstrap 5), dan skema basis data.
*   **Performa**: Aplikasi berjalan sangat ringan karena tidak menggunakan framework berat. Query SQL dioptimasi untuk kecepatan di localhost.
*   **UI/UX**: Desain modern menggunakan Bootstrap 5 dengan skema warna Biru Soft dan Putih yang bersih, sangat cocok untuk lingkungan pendidikan.
*   **Offline Ready**: Semua aset CSS dan JS disimpan secara lokal dalam folder `assets/`, sehingga sistem dapat berjalan tanpa koneksi internet sama sekali.
