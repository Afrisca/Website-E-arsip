<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'pegawai') {
    header("Location: login.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Pegawai - E-Arsip</title>
  <style>
    * {
           margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: sans-serif;
    }

    body, html {
      height: 100%;
    }

    .navbar {
      background-color: purple;
      color: white;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .navbar .title {
      font-size: 22px;
      font-weight: bold;
    }

    .navbar .title span {
      color: gold;
    }

    .navbar .menu {
      list-style: none;
      display: flex;
      gap: 30px;
    }

    .navbar .menu li a {
      color: white;
      text-decoration: none;
      font-weight: bold;
      transition: color 0.3s;
    }

    .navbar .menu li a:hover {
      color: lightblue;
    }

    .hero {
      position: relative;
      height: 100vh;
      background: url('image/background.jpg') center/cover no-repeat;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: white;
      text-align: center;
    }

    .hero .overlay {
      position: absolute;
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      background-color: rgba(0,0,0,0.6);
      backdrop-filter: blur(2px);
      z-index: 1;
    }

    .hero img.logo {
      z-index: 2;
      width: 150px;
      margin-bottom: 20px;
    }

    .hero h1 {
      z-index: 2;
      font-size: 24px;
      font-weight: bold;
      text-shadow: 1px 1px 4px black;
    }
.hero h2 {
      z-index: 2;
      font-size: 18px;
      margin-top: 10px;
      color: gold;
      text-shadow: 1px 1px 4px black;
    }
  </style>
</head>
<body>
  <header>
    <nav class="navbar">
      <div class="title">ARSIP SURAT <span>SOSIALISASI DAN MEDIA</span></div>
      <ul class="menu">
        <li><a href="dashboard-pegawai.php">Beranda</a></li>
        <li><a href="arsip-sosmed.php" target="_blank">ArsipSosmed</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>

  <main class="hero">
    <div class="overlay"></div>
    <img src="image/Logo_Lemhannas.png" alt="Logo" class="logo">
    <h1>SISTEM INFORMASI PENGARSIPAN SURAT</h1>
    <h2>DIREKTORAT <br> SOSIALISASI DAN MEDIA</h2>
  </main>
</body>
</html>
