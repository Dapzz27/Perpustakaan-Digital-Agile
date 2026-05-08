<?php
session_start();
include "../../config/connect.php";
if (isset($_GET['id']) && $_GET['id'] !== '') {
    $id_buku = mysqli_real_escape_string($koneksi, $_GET['id']);
    $result = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku = '$id_buku'");


    if ($result && mysqli_num_rows($result) > 0) {
        $buku = mysqli_fetch_assoc($result);
    } else {
        echo "Data tidak ditemukan.";
        exit;
    }
} else {
    echo "ID buku tidak disediakan atau tidak valid.";
    exit;
}

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_buku = mysqli_real_escape_string($koneksi, $_POST['id_buku']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $pengarang = mysqli_real_escape_string($koneksi, $_POST['pengarang']);
    $tahun_terbit = (int) $_POST['tahun_terbit'];
    $jumlah = (int) $_POST['jumlah'];

    // Proses file cover
    $cover = $buku['cover']; // default cover lama
    if (!empty($_FILES['cover']['name'])) {
        $cover_tmp = $_FILES['cover']['tmp_name'];
        $cover_name = basename($_FILES['cover']['name']);
        $upload_path = "../../assets/" . $cover_name;

        if (move_uploaded_file($cover_tmp, $upload_path)) {
            $cover = $cover_name;
        }
    }

    // Update query
    $update = mysqli_query($koneksi, "UPDATE buku SET 
        cover = '$cover',
        kategori = '$kategori',
        judul = '$judul',
        pengarang = '$pengarang',
        tahun_terbit = '$tahun_terbit',
        jumlah = '$jumlah'
        WHERE id_buku = '$id_buku'
    ");

    if ($update) {
        header("Location: data_buku.php");
        exit;
    } else {
        echo "Gagal mengupdate data: " . mysqli_error($koneksi);
    }
}

// Ambil data kategori
$kategori_list = mysqli_query($koneksi, "SELECT * FROM kategori_buku");
?>


<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Edit Buku</title>
    <link rel="icon" href="../../assets/books.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                    <li class="nav-item"><a class="nav-link" href="../dashboard_admin.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="buku/data_buku.php">Buku</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Transaksi
                        </a>
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
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mt-2" style="min-height: 525px;">
                <div class="card">
                    <div class="card-header">
                        Edit Data Buku
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id_buku"
                                        value="<?= htmlspecialchars($buku['id_buku']); ?>">

                                    <div class="form-group mb-3">
                                        <label for="cover">Cover Buku</label><br>
                                        <img src="../../assets/<?= htmlspecialchars($buku['cover']); ?>" width="100"
                                            class="mb-2" alt="Cover Lama">
                                        <input type="file" class="form-control" id="cover" name="cover"
                                            accept="image/*">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="kategori">Kategori</label>
                                        <select class="form-control" id="kategori" name="kategori" required>
                                            <option value="" disabled>-- Choose Kategori --</option>

                                            <?php
                                            while ($kat = mysqli_fetch_assoc($kategori_list)) {
                                                $selected = ($kat['kategori'] == $buku['kategori']) ? 'selected' : '';
                                                echo '<option value="' . htmlspecialchars($kat['kategori']) . '" ' . $selected . '>'
                                                    . htmlspecialchars($kat['kategori']) .
                                                    '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>


                                    <div class="form-group mb-3">
                                        <label for="judul">Judul Buku</label>
                                        <input type="text" class="form-control" id="judul" name="judul"
                                            value="<?= htmlspecialchars($buku['judul']); ?>"
                                            placeholder="Masukkan Judul Buku" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="pengarang">Pengarang</label>
                                        <input type="text" class="form-control" id="pengarang" name="pengarang"
                                            value="<?= htmlspecialchars($buku['pengarang']); ?>"
                                            placeholder="Masukkan Pengarang" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="tahun_terbit">Tahun Terbit</label>
                                        <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit"
                                            value="<?= htmlspecialchars($buku['tahun_terbit']); ?>"
                                            placeholder="Masukkan Tahun Terbit" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="jumlah">Jumlah Buku</label>
                                        <input type="number" class="form-control" id="jumlah" name="jumlah"
                                            value="<?= htmlspecialchars($buku['jumlah']); ?>"
                                            placeholder="Masukkan Jumlah Buku" required>
                                    </div>

                                    <div class="form-group">
                                        <input type="submit" class="btn btn-primary mt-3" value="Simpan">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- FOOTER -->
    <footer>
        © 2025 Pustaka SMAN 1 Terbanggi Besar
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>