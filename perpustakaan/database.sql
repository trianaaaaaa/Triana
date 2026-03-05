-- Database: tria_UKK
CREATE DATABASE IF NOT EXISTS tria_UKK;
USE tria_UKK;

-- Tabel admin
CREATE TABLE admin (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Tabel anggota
CREATE TABLE anggota (
    id_anggota VARCHAR(20) PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    kelas VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Tabel buku
CREATE TABLE buku (
    id_buku INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    pengarang VARCHAR(100) NOT NULL,
    penerbit VARCHAR(100) NOT NULL,
    tahun YEAR NOT NULL,
    stok INT NOT NULL
);

-- Tabel transaksi
CREATE TABLE transaksi (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    id_anggota VARCHAR(20) NOT NULL,
    id_buku INT NOT NULL,
    tanggal_pinjam DATE NOT NULL,
    tanggal_kembali DATE DEFAULT NULL,
    status ENUM('Dipinjam', 'Dikembalikan') NOT NULL DEFAULT 'Dipinjam',
    FOREIGN KEY (id_anggota) REFERENCES anggota(id_anggota) ON DELETE CASCADE,
    FOREIGN KEY (id_buku) REFERENCES buku(id_buku) ON DELETE CASCADE
);

-- Insert Default Admin (Password: admin123)
-- MD5 of admin123 is 0192023a7bbd73250516f069df18b500
INSERT INTO admin (nama, username, password) VALUES ('Administrator', 'admin', '0192023a7bbd73250516f069df18b500');

-- Insert Default Anggota (Password: user123)
-- MD5 of user123 is 6ad14ba2286c4618239c1c44e9359a50
INSERT INTO anggota (id_anggota, nama, kelas, username, password) VALUES ('USR001', 'Siswa Contoh', 'XII RPL 1', 'user', '6ad14ba2286c4618239c1c44e9359a50');
