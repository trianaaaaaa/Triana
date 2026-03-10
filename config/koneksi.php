<?php
// Support environment variables for Vercel deployment
// Support environment variables for Vercel deployment
$host = getenv('DB_HOST') ?: "localhost";
$user = getenv('DB_USER') ?: "root";
$pass = getenv('DB_PASS') ?: "";
$db   = getenv('DB_NAME') ?: "tria_UKK";
$port = getenv('DB_PORT') ?: 3306;

// Stabilize session for Vercel and clean URLs
if (session_status() === PHP_SESSION_NONE) {
    if (getenv('VERCEL') == '1') {
        // Force session cookie to root to avoid different sessions in /admin and /user
        session_set_cookie_params([
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }
}

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
try {
    $success = @mysqli_real_connect($koneksi, $host, $user, $pass, $db, $port, NULL, MYSQLI_CLIENT_SSL);
} catch (mysqli_sql_exception $ex) {
    $success = false;
    $error_msg = $ex->getMessage();
}

if (!$success) {
    $error = isset($error_msg) ? $error_msg : mysqli_connect_error();
    if (getenv('VERCEL') == '1') {
        die("<div style='padding:20px; border:2px solid red; font-family:sans-serif;'>
            <h2 style='color:red;'>Database Connection Error on Vercel</h2>
            <p>Sistem mendeteksi kegagalan koneksi. Berikut adalah detail konfigurasi saat ini:</p>
            <table border='1' cellpadding='5' style='border-collapse:collapse; width:100%; margin-bottom:20px;'>
                <tr><th>Variable</th><th>Value</th><th>Status</th></tr>
                <tr><td>Host</td><td>'".$host."'</td><td>".($host == 'localhost' ? "<span style='color:orange;'>Masih LOCALHOST</span>" : "<span style='color:green;'>OK</span>")."</td></tr>
                <tr><td>User</td><td>'".$user."'</td><td>".($user == 'root' ? "<span style='color:orange;'>Bawaan (root)</span>" : "<span style='color:green;'>OK</span>")."</td></tr>
                <tr><td>Database</td><td>'".$db."'</td><td>OK</td></tr>
                <tr><td>Port</td><td>'".$port."'</td><td>OK</td></tr>
            </table>
            
            <p style='color:red;'><b>Pesan Error:</b> $error</p>
            
            <hr>
            <h3>Tindakan yang Perlu Diambil:</h3>
            <ol>
                <li>Pastikan variabel <b>DB_HOST</b> sudah diatur di Dashboard Vercel (Settings > Environment Variables).</li>
                <li>Pastikan tidak ada salah ketik (Contoh: Seharusnya <code>DB_HOST</code>, bukan <code>DBHOST</code>).</li>
                <li>Jika Anda sudah mengubahnya, lakukan <b>Redeploy</b> di Vercel agar perubahan tersimpan.</li>
            </ol>
        </div>");
    }
    die("Koneksi gagal: " . $error);
}
?>
