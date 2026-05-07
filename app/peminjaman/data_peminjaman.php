<?php
session_start();
include "../../config/connect.php";

/* ================= CEK LOGIN ADMIN ================= */
if (!isset($_SESSION['username'])) {
    echo "Session admin tidak ditemukan.";
    exit;
}

$id_user = $_SESSION['id'];

/* ================= FOTO PROFIL ================= */
$qFoto = mysqli_query($koneksi, "SELECT foto FROM users WHERE id='$id_user'");
$dataFoto = mysqli_fetch_assoc($qFoto);

$fotoProfil = !empty($dataFoto['foto'])
    ? "../../assets/profil_admin/" . $dataFoto['foto']
    : "../../assets/pp.jpg";

/* ================= DATA PEMINJAMAN (SEMUA USER - ADMIN) ================= */
$query = mysqli_query($koneksi, "
    SELECT 
        p.id_peminjaman,
        p.id_buku,
        u.username,
        u.nomor_induk,
        p.no_tlp,
        p.tgl_peminjaman,
        p.tgl_pengembalian,
        b.judul,
        b.cover
    FROM peminjaman p
    JOIN buku b ON p.id_buku = b.id_buku
    JOIN users u ON p.username = u.username
    ORDER BY p.tgl_peminjaman DESC
");
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Data Peminjaman Buku | Admin</title>
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
                <img src="../../assets/logosmk.png">
                PUSTAKA SMAN 1 TERBANGGI BESAR
            </a>

            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="../dashboard_admin.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../buku/data_buku.php">Buku</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Transaksi
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="data_peminjaman.php">Peminjaman</a></li>
                            <li><a class="dropdown-item"
                                    href="../pengembalian/riwayat_pengembalian_admin.php">Pengembalian</a></li>
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

    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 mt-2">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <img src="../../assets/book_user.gif" style="height:40px; margin-right:10px;">
                            <h4 class="mb-0">Data Peminjaman Buku</h4>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <?php
                                if (mysqli_num_rows($query) == 0) {
                                    echo "<p class='text-center text-muted'>Belum ada peminjaman.</p>";
                                }

                                while ($data = mysqli_fetch_assoc($query)) {

                                    $today = date('Y-m-d');

                                    if ($today > $data['tgl_pengembalian']) {
                                        $status = "Terlambat";
                                        $badge = "danger";
                                    } else {
                                        $status = "Dipinjam";
                                        $badge = "warning";
                                    }
                                    ?>
                                    <div class="col-md-3 mb-4">
                                        <div class="card h-100">
                                            <img src="../../app/buku/uploads/<?= $data['cover']; ?>"
                                                class="card-img-top img-thumbnail" style="height:200px; object-fit:cover;">

                                            <div class="card-body">
                                                <h5 class="fw-bold"><?= $data['judul']; ?></h5>
                                                <p class="mb-1">ID Buku: <?= $data['id_buku']; ?></p>
                                                <p class="mb-1">Username: <?= $data['username']; ?></p>
                                                <p class="mb-1">Nomor Induk: <?= $data['nomor_induk']; ?></p>
                                                <p class="mb-1">No Telp: <?= $data['no_tlp']; ?></p>
                                                <p class="mb-1">Pinjam: <?= $data['tgl_peminjaman']; ?></p>
                                                <p class="mb-1">Kembali: <?= $data['tgl_pengembalian']; ?></p>
                                            </div>

                                            <div class="card-footer text-center">
                                                <span class="badge bg-<?= $badge; ?> mb-2">
                                                    <?= $status; ?>
                                                </span>
                                                <br>
                                                <a href="proses_pengembalian.php?id=<?= $data['id_peminjaman']; ?>"
                                                    class="btn btn-danger btn-sm mt-2">
                                                    Dikembalikan
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        © 2025 Pustaka SMAN 1 Terbanggi Besar
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>