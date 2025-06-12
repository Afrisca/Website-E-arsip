<?php
$file_path = isset($_GET['file']) ? $_GET['file'] : '';

if (!$file_path) {
    die("File tidak ditemukan.");
}

// Ambil ekstensi file
$file_extension = pathinfo($file_path, PATHINFO_EXTENSION);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Preview Dokumen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            text-align: center;
        }
        iframe {
            width: 80%;
            height: 600px;
            border: none;
        }
    </style>
</head>
<body>
<h2>Pratinjau Dokumen</h2>
<?php
if ($file_extension == "pdf") {
    // Tampilkan PDF dalam iframe
    echo "<iframe src='$file_path' width='100%' height='600px'></iframe>";
} elseif (in_array($file_extension, ["jpg", "jpeg", "png"])) {
    // Tampilkan gambar langsung
    echo "<img src='$file_path' style='max-width: 80%; height: auto;'>";
} elseif (in_array($file_extension, ["doc", "docx", "xls", "xlsx", "ppt", "pptx"])) {
    // Gunakan Google Docs Viewer untuk dokumen Office
    echo "<iframe src='https://docs.google.com/gview?url=" . urlencode($file_path) . "&embedded=true' width='100%' height='600px'></iframe>";
} else {
    echo "<p>Dokumen tidak dapat ditampilkan, silakan <a href='$file_path' download>unduh di sini</a>.</p>";
}
?>
</body>
</html>
