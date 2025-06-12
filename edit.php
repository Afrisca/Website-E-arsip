<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "e_arsip");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data berdasarkan ID
$query = "SELECT * FROM surat_sosmed WHERE id = $id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    echo "Data tidak ditemukan.";
    exit;
}

$data = $result->fetch_assoc();

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $tahun = $_POST['tahun'];
    $tanggal_upload = $_POST['tanggal_upload'];

    // Jika file baru diunggah
    if ($_FILES['file']['name'] != '') {
        $file_name = basename($_FILES['file']['name']);
        $file_tmp = $_FILES['file']['tmp_name'];
        $upload_path = "uploads/" . $file_name;

        // Upload file
        if (move_uploaded_file($file_tmp, $upload_path)) {
            // Update semua data termasuk file
            $sql = "UPDATE surat_sosmed 
                    SET nama_dokumen = '$judul', tahun = '$tahun', tanggal_upload = '$tanggal_upload', file_path = '$upload_path' 
                    WHERE id = $id";
        } else {
            echo "Gagal mengunggah file.";
            exit;
        }
    } else {
        // Update tanpa file
        $sql = "UPDATE surat_sosmed 
                SET nama_dokumen = '$judul', tahun = '$tahun', tanggal_upload = '$tanggal_upload' 
                WHERE id = $id";
    }

    if ($conn->query($sql)) {
        header("Location: arsip-sosmed.php");
        exit;
    } else {
        echo "Gagal memperbarui data: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Dokumen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .edit-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }
        h2 {
            color: purple;
            text-align: center;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input[type="text"], input[type="file"], input[type="number"], input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 20px;
            padding: 10px;
            width: 100%;
            background-color: purple;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        a {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: purple;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="edit-container">
    <h2>Edit Dokumen</h2>

    <form method="POST" enctype="multipart/form-data">
        <label>Nama Dokumen:</label>
        <input type="text" name="judul" value="<?= htmlspecialchars($data['nama_dokumen']); ?>" required>

        <label>Tanggal Upload:</label>
        <input type="date" name="tanggal_upload" value="<?= date('Y-m-d', strtotime($data['tanggal_upload'])); ?>" required>

        <label>Tahun:</label>
        <input type="number" name="tahun" value="<?= $data['tahun']; ?>" required>

        <label>File Baru (opsional):</label>
        <input type="file" name="file">

        <button type="submit">Simpan Perubahan</button>
    </form>

    <a href="arsip-sosmed.php">← Kembali ke Daftar Dokumen</a>
</div>

</body>
</html>
