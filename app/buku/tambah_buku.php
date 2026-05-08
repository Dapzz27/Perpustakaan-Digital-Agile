<?php
session_start();
include "../../config/connect.php";

/* FOTO PROFIL */
$id_user = $_SESSION['id'];
$qFoto = mysqli_query($koneksi, "SELECT foto FROM users WHERE id='$id_user'");
$dataFoto = mysqli_fetch_assoc($qFoto);

$fotoProfil = !empty($dataFoto['foto'])
    ? "../../assets/profil_admin/" . $dataFoto['foto']
    : "../../assets/pp.jpg";
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Tambah Buku</title>
    <link rel="icon" href="../../assets/books 2.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            padding-top: 70px;
        }

        .navbar {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
        }

        .navbar-brand {
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            white-space: nowrap;
        }

        .navbar-brand img {
            width: 48px;
        }

        .nav-link {
            font-weight: 500;
            color: #333 !important;
            position: relative;
        }

        @media (min-width: 992px) {
            .nav-link::after {
                content: '';
                position: absolute;
                left: 0;
                bottom: -6px;
                width: 0;
                height: 2px;
                background: #1976d2;
                transition: .3s;
            }

            .nav-link:hover::after {
                width: 100%;
            }
        }

        @media (max-width: 991px) {
            .navbar-toggler {
                border: none;
            }

            .navbar-collapse {
                text-align: right;
                padding-top: 20px;
            }

            .navbar-nav {
                align-items: flex-end !important;
                gap: 14px;
            }

            .dropdown-menu {
                text-align: right;
                border: none;
                box-shadow: none;
            }
        }

        .profile-img {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
        }

        footer {
            background: #212529;
            color: #fff;
            padding: 15px;
            text-align: center;
            margin-top: 80px;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="../dashboard_admin.php">
                <img src="../../assets/logosmk.png">
                PUSTAKA SMAN 1 TERBANGGI BESAR
            </a>

            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="../dashboard_admin.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="data_buku.php">Buku</a></li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Transaksi</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="peminjaman/data_peminjaman.php">Peminjaman</a></li>
                            <li><a class="dropdown-item" href="data_pengembalian.php">Pengembalian</a></li>
                        </ul>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="#">Informasi</a></li>

                    <li class="nav-item dropdown ms-3">
                        <img src="<?= $fotoProfil ?>" class="profile-img dropdown-toggle" data-bs-toggle="dropdown">
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="../profil_admin.php">Profil Saya</a></li>
                            <li><a class="dropdown-item text-danger" href="../logout.php">Keluar</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- CONTENT -->
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mt-2">
                <div class="card">
                    <div class="card-header fw-bold">
                        Tambah Data Buku
                    </div>

                    <div class="card-body">
                        <form action="simpan_buku.php" method="POST" enctype="multipart/form-data">

                            <div class="mb-3">
                                <label class="form-label">ID Buku</label>
                                <input type="text" name="id_buku" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Cover Buku</label>
                                <input type="file" name="cover" class="form-control" accept="image/*" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="kategori" class="form-control" required>
                                    <option value="" disabled selected>-- Pilih Kategori --</option>
                                    <?php
                                    $kategori = mysqli_query($koneksi, "SELECT kategori FROM kategori_buku");
                                    while ($k = mysqli_fetch_assoc($kategori)) {
                                        echo "<option value='" . htmlspecialchars($k['kategori']) . "'>" . htmlspecialchars($k['kategori']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Judul Buku</label>
                                <input type="text" name="judul" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Pengarang</label>
                                <input type="text" name="pengarang" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tahun Terbit</label>
                                <input type="number" name="tahun_terbit" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jumlah Buku</label>
                                <input type="number" name="jumlah" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        © 2025 Pustaka SMAN 1 Terbanggi Besar
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>