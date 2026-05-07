<?php
session_start();
include "../config/connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* PROSES DAFTAR AKUN */
    if (isset($_POST['register'])) {

        $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
        $username = mysqli_real_escape_string($koneksi, $_POST['username']);
        $email = mysqli_real_escape_string($koneksi, $_POST['email']);
        $nomor_induk = mysqli_real_escape_string($koneksi, $_POST['nomor_induk']);
        $jurusan = mysqli_real_escape_string($koneksi, $_POST['jurusan']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Cek duplikasi
        $cek = mysqli_query($koneksi, "
            SELECT * FROM users 
            WHERE username='$username' 
               OR email='$email' 
               OR nomor_induk='$nomor_induk'
        ");

        if (mysqli_num_rows($cek) > 0) {
            echo "<script>alert('Username, Email, atau Nomor Induk sudah terdaftar');</script>";
        } else {

            $insert = mysqli_query($koneksi, "
                INSERT INTO users (nama, username, email, nomor_induk, jurusan, password)
                VALUES ('$nama', '$username', '$email', '$nomor_induk', '$jurusan', '$password')
            ");

            if ($insert) {
                echo "<script>alert('Pendaftaran berhasil! Silakan login'); location.href='login_akun.php';</script>";
            } else {
                echo "<script>alert('Pendaftaran gagal');</script>";
            }
        }
    }

    /* PROSES LOGIN*/

    if (isset($_POST['login'])) {

        $username = mysqli_real_escape_string($koneksi, $_POST['username']);
        $password = $_POST['password'];

        $query = mysqli_query($koneksi, "
            SELECT * FROM users WHERE username='$username'
        ");

        if (mysqli_num_rows($query) == 1) {

            $data = mysqli_fetch_assoc($query);

            if (password_verify($password, $data['password'])) {

                $_SESSION['id'] = $data['id'];
                $_SESSION['nama'] = $data['nama'];
                $_SESSION['username'] = $data['username'];
                $_SESSION['role'] = $data['role'];
                $_SESSION['foto'] = $data['foto'];
                // Redirect sesuai role
                if ($data['role'] == 1) {
                    header("Location: ../app/dashboard_admin.php");
                } else {
                    header("Location: ../public/dashboard_user.php");
                }
                exit;

            } else {
                echo "<script>alert('Password salah');</script>";
            }

        } else {
            echo "<script>alert('Username tidak ditemukan');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>PORTAL | Perpustakaan Digital</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../assets/icon_situs.png" type="image/png">
    <link rel="stylesheet" href="style.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">

        <!-- FORM LOGIN -->
        <div class="form-box login">
            <form method="POST">
                <h1>Masuk</h1>

                <div class="input-box">
                    <input type="text" name="username" placeholder="Username" required>
                    <i class='bx bxs-user'></i>
                </div>

                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>

                <button type="submit" name="login" class="btn btn-login">Masuk</button>
            </form>
        </div>

        <!-- FORM DAFTAR -->
        <div class="form-box register">
            <form method="POST">
                <h1>Daftar</h1>

                <div class="input-box">
                    <input type="text" name="nama" placeholder="Nama Lengkap" required>
                    <i class='bx bxs-user'></i>
                </div>

                <div class="input-box">
                    <input type="text" name="username" placeholder="Username" required>
                    <i class='bx bxs-user'></i>
                </div>

                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class='bx bxs-envelope'></i>
                </div>

                <div class="input-box">
                    <input type="text" name="nomor_induk" placeholder="NIS / NIP" required>
                    <i class='bx bxs-id-card'></i>
                </div>

                <div class="input-box">
                    <input type="text" name="jurusan" placeholder="Jurusan (opsional)">
                    <i class='bx bxs-school'></i>
                </div>

                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>

                <button type="submit" name="register" class="btn btn-daftar">Daftar</button>
            </form>
        </div>

        <!-- TOGGLE -->
        <div class="toggle-box">
            <div class="toggle-panel toggle-left">
                <h2>Selamat Datang di
                    Perpus Digital SMANSA TEBA</h2>
                <p>Belum punya akun?</p>
                <button class="btn register-btn">Daftar</button>
            </div>

            <div class="toggle-panel toggle-right">
                <h2>Halo! 👋 Yuk, Daftar akun ke Perpus Digital</h2>
                <p>Sudah punya akun?</p>
                <button class="btn login-btn">Masuk</button>
            </div>
        </div>

    </div>

    <script src="main.js"></script>
</body>

</html>