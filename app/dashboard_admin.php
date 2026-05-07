<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: ../sign/login_akun.php");
    exit;
}

include "../config/connect.php";

/* FOTO PROFIL */
$id_user = $_SESSION['id'];
$qFoto = mysqli_query($koneksi, "SELECT foto FROM users WHERE id='$id_user'");
$dataFoto = mysqli_fetch_assoc($qFoto);

$fotoProfil = !empty($dataFoto['foto'])
    ? "../assets/profil_admin/" . $dataFoto['foto']
    : "../assets/pp.jpg";

/* Ambil 6 kategori */
$kategori = [];
$q = mysqli_query($koneksi, "SELECT kategori FROM kategori_buku LIMIT 6");
while ($row = mysqli_fetch_assoc($q)) {
    $kategori[] = $row['kategori'];
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Dashboard | Pustaka SMAN 1 Terbanggi Besar</title>
    <link rel="icon" href="../assets/library.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
        }

        /* ================= NAVBAR ================= */
        .navbar {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
        }

        .navbar-brand {
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
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

        @media (max-width: 576px) {
            .navbar-brand {
                font-size: 14px;
                gap: 8px;
            }

            .navbar-brand img {
                width: 36px;
            }
        }

        /* ================= MOBILE NAVBAR CLEAN ================= */
        @media (max-width: 991px) {
            .navbar .container {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .navbar-brand {
                flex: 1;
                white-space: nowrap;
            }

            .navbar-toggler {
                margin-left: auto;
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

        /* PROFILE */
        .profile-img {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* ================= HERO ================= */
        .hero {
            min-height: calc(100vh - 70px);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 80px 15px 40px;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            transition: opacity 1s ease-in-out;
            opacity: 0;
        }

        .hero-bg.active {
            opacity: 1;
        }

        .hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .55);
            backdrop-filter: blur(2px);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            color: #fff;
            text-align: center;
            max-width: 800px;
            width: 100%;
        }

        .hero-content h1 {
            font-weight: 700;
            font-size: clamp(28px, 5vw, 42px);
        }

        /* SEARCH */
        .search-wrapper {
            max-width: 700px;
            margin: 30px auto 0;
        }

        .search-wrapper input {
            height: 55px;
            border-radius: 30px;
            padding-left: 25px;
        }

        @media (max-width: 576px) {
            .search-wrapper input {
                height: 48px;
                font-size: 14px;
            }
        }

        /* ================= KATEGORI ================= */
        .kategori-section {
            padding: 60px 0;
        }

        .kategori-card {
            background: #fff;
            border-radius: 16px;
            padding: 25px 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .08);
            transition: .25s;
        }

        .kategori-card:hover {
            transform: translateY(-6px);
        }

        .kategori-card img {
            width: 60px;
            margin-bottom: 12px;
        }

        footer {
            background: #212529;
            color: #fff;
            padding: 15px;
            text-align: center;
        }
    </style>
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="../assets/logosmk.png">
                PUSTAKA SMAN 1 TERBANGGI BESAR
            </a>

            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="buku/data_buku.php">Buku</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Transaksi
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="peminjaman/data_peminjaman.php">Peminjaman</a></li>
                            <li><a class="dropdown-item"
                                    href="pengembalian/riwayat_pengembalian_admin.php">Pengembalian</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#">Informasi</a></li>
                    <li class="nav-item dropdown ms-3">
                        <img src="<?= $fotoProfil ?>" class="profile-img dropdown-toggle" data-bs-toggle="dropdown">
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="profil_admin.php">Profil Saya</a></li>
                            <li><a class="dropdown-item text-danger" href="logout.php">Keluar</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero mt-5">
        <div class="hero-bg active" style="background-image:url('../assets/dashboard.jpg')"></div>
        <div class="hero-bg" style="background-image:url('../assets/dashboard2.jpg')"></div>
        <div class="hero-bg" style="background-image:url('../assets/dashboard3.jpg')"></div>

        <div class="hero-content">
            <h1>HALLO ADMIN PERPUSTAKAAN DIGITAL</h1>
            <p>SMAN 1 Terbanggi Besar</p>

            <div class="search-wrapper">
                <input type="text" class="form-control" placeholder="Masukkan kata kunci untuk mencari koleksi...">
            </div>
        </div>
    </section>

    <!-- KATEGORI -->
    <section class="kategori-section">
        <div class="container">
            <h4 class="text-center fw-bold mb-4">Kategori Buku</h4>

            <div class="row g-4">
                <div class="col-6 col-md-4">
                    <a href="buku/data_buku.php?kategori=Ilmu%20sosial" class="text-decoration-none">
                        <div class="kategori-card">
                            <img src="../assets/kategori/buku_sains.png">
                            <span class="fw-medium text-dark">Sains</span>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-md-4">
                    <a href="buku/data_buku.php?kategori=Informatika" class="text-decoration-none">
                        <div class="kategori-card">
                            <img src="../assets/kategori/buku_informatika.png">
                            <span class="fw-medium text-dark">Informatika</span>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-md-4">
                    <a href="buku/data_buku.php?kategori=Komik" class="text-decoration-none">
                        <div class="kategori-card">
                            <img src="../assets/kategori/buku_komik.png">
                            <span class="fw-medium text-dark">Komik</span>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-md-4">
                    <a href="buku/data_buku.php?kategori=Literatur" class="text-decoration-none">
                        <div class="kategori-card">
                            <img src="../assets/kategori/buku_literatur.png">
                            <span class="fw-medium text-dark">Literatur</span>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-md-4">
                    <a href="buku/data_buku.php?kategori=Novel" class="text-decoration-none">
                        <div class="kategori-card">
                            <img src="../assets/kategori/buku_novel.png">
                            <span class="fw-medium text-dark">Novel</span>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-md-4">
                    <a href="buku/data_buku.php?kategori=Teknologi" class="text-decoration-none">
                        <div class="kategori-card">
                            <img src="../assets/kategori/buku_teknologi.png">
                            <span class="fw-medium text-dark">Teknologi</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <footer>
        © 2025 Pustaka SMAN 1 Terbanggi Besar
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const slides = document.querySelectorAll('.hero-bg');
        let index = 0;

        setInterval(() => {
            slides[index].classList.remove('active');
            index = (index + 1) % slides.length;
            slides[index].classList.add('active');
        }, 5000);
    </script>

</body>

</html>