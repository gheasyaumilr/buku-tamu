<?php
// Konfigurasi koneksi database
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "db_bukutamu";

// Membuat koneksi
$koneksi = mysqli_connect($host, $user, $pass, $dbname);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($koneksi, "utf8");
?>
