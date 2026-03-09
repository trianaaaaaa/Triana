<?php
// Support environment variables for Vercel deployment
$host = getenv('DB_HOST') ?: "localhost";
$user = getenv('DB_USER') ?: "root";
$pass = getenv('DB_PASS') ?: "";
$db   = getenv('DB_NAME') ?: "tria_UKK";
$port = getenv('DB_PORT') ?: 3306;

// Support full MySQL URL format
$db_url = getenv('DATABASE_URL');
if ($db_url) {
    $parsed = parse_url($db_url);
    $host = $parsed['host'];
    $user = $parsed['user'];
    $pass = $parsed['pass'] ?? '';
    $db   = ltrim($parsed['path'], '/');
    $port = $parsed['port'] ?? 3306;
}

// Initialize connection for possible SSL usage
$koneksi = mysqli_init();
if (getenv('DB_SSL') == 'true') {
    mysqli_ssl_set($koneksi, NULL, NULL, NULL, NULL, NULL);
}

// Attempt connection
$success = mysqli_real_connect($koneksi, $host, $user, $pass, $db, $port, NULL, MYSQLI_CLIENT_SSL);

if (!$success) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
