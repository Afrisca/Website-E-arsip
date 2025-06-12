<?php
session_start();
// Cek login dan role
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "e_arsip");
// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $tahun = date("Y");
    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $upload_path = "uploads/" . $file_name;

    move_uploaded_file($file_tmp, $upload_path);

    $sql = "INSERT INTO surat_sosmed (nama_dokumen, tahun, file_path) 
            VALUES ('$judul', '$tahun', '$upload_path')";
    $conn->query($sql);
}

// Ambil filter tahun
$tahun_filter = isset($_GET['tahun']) ? $_GET['tahun'] : '';


// Pagination
$limit = 10; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Hitung total data
$count_sql = "SELECT COUNT(*) as total FROM surat_sosmed";
if ($tahun_filter != '') {
    $count_sql .= " WHERE tahun = '$tahun_filter'";
}
$total_result = $conn->query($count_sql)->fetch_assoc();
$total_data = $total_result['total'];
$total_pages = ceil($total_data / $limit);

// Query dokumen
$query = "SELECT * FROM surat_sosmed";
if ($tahun_filter != '') {
    $query .= " WHERE tahun = '$tahun_filter'";
}
$query .= " ORDER BY tahun DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Arsip Dokumen Sosialisasi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            background: #f9f9f9;
        }

        .navbar {
            background-color: purple;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .navbar a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
            font-weight: bold;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        table {
            border-collapse: collapse; width: 100%; margin-top: 20px;
        }

        th, td {
            padding: 10px; border: 1px solid #ccc; text-align: left;
        }

        th {
            background-color: purple; color: white;
        }

        h2 {
            color: purple;
        }

        .upload-box {
            margin-top: 20px;
        }

        .filter-box {
            margin-top: 20px;
        }

        .btn-upload {
            background: purple; color: white; padding: 8px 16px; border: none;
        }

        i {
            padding: 10px;
            border-radius: 10px;
            color: white;
        }

        .fa-eye {
            background-color: #ffc107;
        }

        .fa-download {
            background-color: #28a745;
        }

        .fa-pen {
            background-color: #007bff;
        }

        .fa-trash {
            background-color: #dc3545;
        }

        .container {
            padding: 30px;
        }

.pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            display: inline-block;
            margin: 0 5px;
            padding: 8px 12px;
            background-color: #eee;
            text-decoration: none;
            color: black;
            border-radius: 5px;
        }
        .pagination a.active {
            background-color: purple;
            color: white;
        }
    </style>
</head>
<body>

<div class="navbar">
    <?php
    if ($_SESSION['role'] == 'admin') {
        echo '<a href="dashboard-admin.php">Beranda</a>';
    } elseif ($_SESSION['role'] == 'pegawai') {
        echo '<a href="dashboard-pegawai.php">Beranda</a>';
    }
    ?>
    <a href="arsip-sosmed.php">Arsip Sosmed</a>
    <a href="logout.php">Logout</a>
</div>


<div class="container">
<h2>DEREKTORAT SOSIALISASI DAN MEDIA</h2>
<?php if ($_SESSION['role'] == 'admin') { ?>
<div class="upload-box">
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="judul" placeholder="Judul Dokumen" required>
        <input type="file" name="file" required>
        <button type="submit" class="btn-upload">Upload</button>
    </form>
</div>
<?php } ?>

<div class="filter-box">
    <form method="GET" action="">
        <label for="filter_tahun"><strong>Filter Tahun:</strong></label>
        <select name="tahun" id="filter_tahun" onchange="this.form.submit()">
            <option value="">-- Semua Tahun --</option>
            <?php
            $tahun_q = $conn->query("SELECT DISTINCT tahun FROM surat_sosmed ORDER BY tahun DESC");
            while ($t = $tahun_q->fetch_assoc()) {
                $selected = ($tahun_filter == $t['tahun']) ? 'selected' : '';
                echo "<option value='{$t['tahun']}' $selected>{$t['tahun']}</option>";
            }
            ?>
        </select>
    </form>
</div>

<h2>Arsip Surat Direktorat Sosialisasi dan Media</h2>
<table>
    <tr>
        <th>No</th>
        <th>Nama Dokumen</th>
        <th>Tahun</th>
        <th>Aksi</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        $no = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$no}</td>
                    <td>{$row['nama_dokumen']}</td>
                    <td>{$row['tahun']}</td>
                    <td>
                        <a href='view.php?file=" . urlencode($row['file_path']) . "' target='_blank' title='Lihat'>
                            <i class='fa-solid fa-eye'></i>
                        </a>
                        <a href='{$row['file_path']}' download title='Download'>
                            <i class='fa-solid fa-download'></i>
                        </a>";

            if ($_SESSION['role'] == 'admin') {
                echo "
                        <a href='edit.php?id={$row['id']}' title='Edit'>
                            <i class='fa-solid fa-pen'></i>
                        </a>
                        <a href='hapus.php?id={$row['id']}' onclick='return confirm(\"Yakin hapus dokumen ini?\")' title='Hapus'>
                            <i class='fa-solid fa-trash'></i>
                        </a>";
            }

            echo "</td></tr>";
            $no++;
        }
    } else {
        echo "<tr><td colspan='4'>Tidak ada dokumen.</td></tr>";
    }
    ?>
</table>

</div>
</body>
</html>
