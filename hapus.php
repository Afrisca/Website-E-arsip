<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "e_arsip");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil ID dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data untuk mengetahui file_path
$query = "SELECT file_path FROM surat_sosmed WHERE id = $id";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $file_path = $data['file_path'];

    // Hapus file dari folder uploads jika ada
    if (file_exists($file_path)) {
        unlink($file_path);
    }

    // Hapus data dari database
    $delete = "DELETE FROM surat_sosmed WHERE id = $id";
    if ($conn->query($delete)) {
        header("Location: arsip-sosmed.php");
        exit;
    } else {
        echo "Gagal menghapus data.";
    }
} else {
    echo "Data tidak ditemukan.";
}
?>
