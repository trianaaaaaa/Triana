<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "tria_UKK";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
