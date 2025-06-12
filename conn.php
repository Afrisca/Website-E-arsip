<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "e_arsip"; // nama database di phpMyAdmin

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>