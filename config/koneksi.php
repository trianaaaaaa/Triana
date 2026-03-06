<?php
// Support environment variables for Vercel deployment
$host = getenv('DB_HOST') ?: "localhost";
$user = getenv('DB_USER') ?: "root";
$pass = getenv('DB_PASS') ?: "";
$db   = getenv('DB_NAME') ?: "tria_UKK";

// Support full MySQL URL format (e.g. from PlanetScale/Aiven)
$db_url = getenv('DATABASE_URL');
if ($db_url) {
    $parsed = parse_url($db_url);
    $host = $parsed['host'];
    $user = $parsed['user'];
    $pass = $parsed['pass'] ?? '';
    $db   = ltrim($parsed['path'], '/');
    $port = $parsed['port'] ?? 3306;
    $koneksi = mysqli_connect($host, $user, $pass, $db, $port);
} else {
    $koneksi = mysqli_connect($host, $user, $pass, $db);
}

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
