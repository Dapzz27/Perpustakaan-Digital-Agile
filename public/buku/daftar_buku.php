<?php
session_start();
include "../../config/connect.php";

$keyword = isset($_GET['keyword']) ? mysqli_real_escape_string($koneksi, $_GET['keyword']) : '';
if (!empty($keyword)) {
    $query = mysqli_query($koneksi, "
        SELECT * FROM buku 
        WHERE judul LIKE '%$keyword%' 
           OR kategori LIKE '%$keyword%'
    ");
} else {
    $query = mysqli_query($koneksi, "SELECT * FROM buku");
}

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
    <title>Daftar Buku</title>
    <link rel="icon" href="../../assets/books.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        /* ================= BODY ================= */
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            padding-top: 70px;
            /* offset navbar fixed */
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

        /* underline effect desktop */
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

        /* ================= MOBILE NAVBAR ================= */
        @media (max-width: 576px) {
            .navbar-brand {
                font-size: 14px;
                gap: 8px;
            }

            .navbar-brand img {
                width: 36px;
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

        /* ================= PROFILE ================= */
        .profile-img {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
        }

        /* ================= FOOTER ================= */
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
            <a class="navbar-brand" href="#">
                <img src="../../assets/logosmk.png" alt="Logo">
                PUSTAKA SMAN 1 TERBANGGI BESAR
            </a>

            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="../dashboard_user.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="daftar_buku.php">Buku</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Transaksi
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../formPeminjaman/daftar_peminjaman.php">Peminjaman</a>
                            </li>
                            <li><a class="dropdown-item" href="data_pengembalian.php">Pengembalian</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#">Informasi</a></li>

                    <li class="nav-item dropdown ms-3">
                        <img src="<?= $fotoProfil ?>" class="profile-img dropdown-toggle" data-bs-toggle="dropdown">
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="../profil_pengguna.php">Profil Saya</a></li>
                            <li><a class="dropdown-item text-danger" href="../logout.php">Keluar</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 mt-2" style="min-height: 525px;">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <img src="../../assets/book_user.gif" alt="Logo Buku"
                                style="height: 40px; margin-right: 10px;">
                            <h4 class="mb-0">Daftar Buku</h4>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col d-flex justify-content-end">
                                    <form class="form-inline float-right" method="GET">
                                        <input type="text" class="form-control" name="keyword" placeholder="Cari buku"
                                            value="<?php echo htmlspecialchars($keyword); ?>">
                                        <input type="submit" class="btn btn-primary ms-2" name="cari" value="Cari">
                                    </form>
                                </div>
                            </div>
                            <section class="book" id="book">
                                <div class="row">
                                    <?php
                                    while ($data = mysqli_fetch_array($query)) {
                                        ?>
                                        <div class="col-md-3 mb-4">
                                            <div class="card h-100">
                                                <img src="../../app/buku/uploads/<?php echo $data['cover']; ?>"
                                                    class="card-img-top img-thumbnail" alt="Cover Buku"
                                                    style="height: 200px; object-fit: cover; cursor: pointer;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#coverModal<?php echo $data['id_buku']; ?>">

                                                <div class="card-body">
                                                    <h5 class="card-title fw-bold"><?php echo $data['judul']; ?></h5>
                                                    <p class="mb-1">Kategori: <?php echo $data['kategori']; ?></p>
                                                    <p class="mb-1">Pengarang: <?php echo $data['pengarang']; ?></p>
                                                    <p class="mb-1">Tahun: <?php echo $data['tahun_terbit']; ?></p>
                                                    <p class="mb-1">Jumlah: <?php echo $data['jumlah']; ?></p>
                                                </div>
                                                <div class="card-footer d-flex justify-content-end">
                                                    <a href="../formPeminjaman/peminjaman.php?id=<?= $data['id_buku']; ?>"
                                                        class="btn btn-warning btn-sm">Pinjam</a>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Gambar Besar -->
                                        <div class="modal fade" id="coverModal<?php echo $data['id_buku']; ?>" tabindex="-1"
                                            aria-labelledby="coverModalLabel<?php echo $data['id_buku']; ?>"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-body text-center">
                                                        <img src="../../assets/<?php echo $data['cover']; ?>"
                                                            class="img-fluid rounded" alt="Cover Buku">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }

                                    if (mysqli_num_rows($query) == 0) {
                                        echo '<div class="col text-center"><img src="../../assets/no_data_book.png" class="img-fluid mb-3" alt="No Data" style="max-width: 100px;">
                            <p class="text-muted">Buku tidak tersedia.</p></div>';
                                    }
                                    ?>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        © 2025 Pustaka SMAN 1 Terbanggi Besar
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>