<?php
session_start();
include "conn.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = hash("sha256", $_POST['password']);

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // Simpan data ke session
        $_SESSION['login'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect sesuai role
        if ($user['role'] == 'admin') {
            header("Location: dashboard-admin.php");
        } else {
            header("Location: dashboard-pegawai.php");
        }
        exit;
    } else {
        $error = "Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login E-Arsip</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
       body {
    font-family: sans-serif;
    background: url('image/background_login.jpg') no-repeat center center fixed;
    background-size: cover;
        }
        body::before {
        content: "";
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-color: rgba(98, 0, 140, 0.6); /* Ungu transparan */
        z-index: 0;
        }
        .login-box {
            position: relative;
            z-index: 1;
            width: 350px;
            margin: 100px auto;
            padding: 40px 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            text-align: center;
        }
        .login-box img {
        width: 150px;
        margin-bottom: 10px;
        }
       .login-box h2 {
        margin-bottom: 25px;
        font-size: 25px;
        font-weight: bold;
        color: purple;
        }
         .login-box input {
        width: 100%;
        padding: 10px;
        margin-bottom: 14px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
        }
         .login-box button {
        width: 100%;
        padding: 12px;
        background: purple;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 15px;
        font-weight: bold;
        cursor: pointer;
        }
        .login-box p {
        color: red;
        font-size: 14px;
        margin-top: -10px;
        margin-bottom: 10px;
        }
    </style>
</head>
<body>
     <div class="login-box">
        <img src="image/Logo_Lemhannas.png" alt="Logo Lembaga Ketahanan Nasional"> 
        <h2>Login Lemhannas RI</h2>
        <?php if (!empty($error)) echo "<p>$error</p>"; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>