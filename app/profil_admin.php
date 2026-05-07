<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../sign/login_akun.php");
    exit;
}

include "../config/connect.php";

/* ================= DATA ADMIN ================= */
$id_user = $_SESSION['id'];

$queryUser = mysqli_query($koneksi, "
    SELECT nama, email, nomor_induk, jurusan, role, foto
    FROM users
    WHERE id = '$id_user'
");

$user = mysqli_fetch_assoc($queryUser);

$nama = $user['nama'] ?? 'Admin';
$email = $user['email'] ?? '-';
$nomor_induk = $user['nomor_induk'] ?? '-';
$jurusan = $user['jurusan'] ?? '-';
$role = 'Admin';

$fotoProfil = !empty($user['foto'])
    ? "../assets/profil_admin/" . $user['foto']
    : "../assets/pp.jpg";

/* ================= STATISTIK ADMIN ================= */

// Total buku dipinjam (seluruh user)
$qDipinjam = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total FROM peminjaman
");
$dataDipinjam = mysqli_fetch_assoc($qDipinjam);
$totalDipinjam = $dataDipinjam['total'] ?? 0;

// Jumlah user
$qUser = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total FROM users
");
$dataUser = mysqli_fetch_assoc($qUser);
$totalUser = $dataUser['total'] ?? 0;

// Total buku
$qBuku = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total FROM buku
");
$dataBuku = mysqli_fetch_assoc($qBuku);
$totalBuku = $dataBuku['total'] ?? 0;
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Profil Admin</title>
    <link rel="icon" href="../assets/profil_admin.png" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fb;
        }

        .profile-header h1 {
            font-weight: 700;
            color: #0d2a5c;
        }

        .profile-card {
            background: #fff;
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, .08);
        }

        .profile-img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .15);
        }

        .btn-edit {
            background: #fbbf24;
            border: none;
            border-radius: 25px;
            padding: 10px 30px;
            font-weight: 600;
            color: #fff;
        }

        /* CARD INFO ADMIN */
        .info-card {
            background: #0d2a5c;
            color: #fff;
            border-radius: 18px;
            padding: 30px;
            text-align: center;
            height: 100%;
        }

        .info-card h4 {
            font-weight: 600;
            margin-bottom: 10px;
        }

        .info-card span {
            font-size: 36px;
            font-weight: 700;
            color: #fbbf24;
        }
    </style>
</head>

<body>

    <div class="container my-5">

        <!-- HEADER -->
        <div class="profile-header mb-4">
            <h1>Profil Admin</h1>
            <p class="text-muted">Informasi akun administrator</p>
        </div>

        <!-- PROFIL CARD -->
        <div class="profile-card mb-5">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    <img src="<?= $fotoProfil ?>" class="profile-img">
                    <p class="mt-2 text-muted">Foto Profil</p>
                </div>
                <div class="col-md-7">
                    <h3 class="fw-bold"><?= $nama ?></h3>
                    <p>Email: <?= $email ?></p>
                    <p>NIP: <?= $nomor_induk ?></p>
                    <p>Jurusan: <?= $jurusan ?></p>
                    <p>Role: <?= $role ?></p>
                </div>
                <div class="col-md-3 text-md-end text-center">
                    <a href="edit_profil.php" class="btn btn-edit">Edit Profil</a>
                </div>
            </div>
        </div>

        <!-- INFORMASI ADMIN -->
        <h3 class="fw-bold mb-4">Statistik Sistem</h3>

        <div class="row g-4">

            <!-- TOTAL BUKU DIPINJAM -->
            <div class="col-12 col-md-4">
                <div class="info-card">
                    <h4>Total Buku Dipinjam</h4>
                    <span><?= $totalDipinjam ?></span>
                </div>
            </div>

            <!-- JUMLAH USER -->
            <div class="col-12 col-md-4">
                <div class="info-card">
                    <h4>Jumlah User</h4>
                    <span><?= $totalUser ?></span>
                </div>
            </div>

            <!-- TOTAL BUKU -->
            <div class="col-12 col-md-4">
                <div class="info-card">
                    <h4>Total Buku</h4>
                    <span><?= $totalBuku ?></span>
                </div>
            </div>

        </div>

    </div>

</body>

</html>