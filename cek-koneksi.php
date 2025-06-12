<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "e_arsip";

$conn = mysqli_connect($host, $user, $pass, $db);

if ($conn) {
    echo "✅ Koneksi ke database BERHASIL!";
} else {
    echo "❌ Koneksi GAGAL: " . mysqli_connect_error();
}
?>
