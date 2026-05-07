<?php
session_start();
include "../../config/connect.php";

/* ================= CEK LOGIN ================= */
if (!isset($_SESSION['username'])) {
    echo "Session user tidak ditemukan.";
    exit;
}

/* ================= DATA USER ================= */
$username_login = $_SESSION['username'];
$id_user = $_SESSION['id'];

$queryUser = mysqli_query($koneksi, "
    SELECT username, nomor_induk 
    FROM users
    WHERE username = '$username_login'
");
$dataUser = mysqli_fetch_assoc($queryUser);

/* ================= FOTO PROFIL ================= */
$qFoto = mysqli_query($koneksi, "SELECT foto FROM users WHERE id='$id_user'");
$dataFoto = mysqli_fetch_assoc($qFoto);

$fotoProfil = !empty($dataFoto['foto'])
    ? "../../assets/profil_admin/" . $dataFoto['foto']
    : "../../assets/pp.jpg";

/* ================= DATA BUKU (VARCHAR) ================= */
$id_buku = isset($_GET['id'])
    ? mysqli_real_escape_string($koneksi, $_GET['id'])
    : '';

$queryBuku = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku = '$id_buku'");
$dataBuku = mysqli_fetch_assoc($queryBuku);

if (!$dataBuku) {
    echo "Buku tidak ditemukan.";
    exit;
}

/* ================= PROSES SUBMIT ================= */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_buku = mysqli_real_escape_string($koneksi, $_POST['id_buku']);
    $nomor_induk = mysqli_real_escape_string($koneksi, $_POST['nomor_induk']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $tgl_peminjaman = $_POST['tgl_peminjaman'];
    $tgl_pengembalian = $_POST['tgl_pengembalian'];
    $no_tlp = mysqli_real_escape_string($koneksi, $_POST['no_tlp']);

    /* ===== VALIDASI NOMOR HP INDONESIA ===== */
    if (!preg_match('/^(08[1-9][0-9]{7,10}|\+62[1-9][0-9]{7,10})$/', $no_tlp)) {
        echo "<script>alert('Format nomor HP tidak valid'); history.back();</script>";
        exit;
    }

    /* ===== VALIDASI TANGGAL ===== */
    $today = date('Y-m-d');

    if ($tgl_peminjaman < $today) {
        echo "<script>alert('Tanggal peminjaman tidak boleh kurang dari hari ini'); history.back();</script>";
        exit;
    }

    $selisih = (strtotime($tgl_pengembalian) - strtotime($tgl_peminjaman)) / (60 * 60 * 24);
    if ($selisih != 5) {
        echo "<script>alert('Tanggal pengembalian harus 5 hari setelah peminjaman'); history.back();</script>";
        exit;
    }

    /* ===== CEK STOK ===== */
    $cekStok = mysqli_query($koneksi, "SELECT jumlah FROM buku WHERE id_buku='$id_buku'");
    $stok = mysqli_fetch_assoc($cekStok);

    if ((int) $stok['jumlah'] <= 0) {
        echo "<script>alert('Stok buku habis'); history.back();</script>";
        exit;
    }

    /* ===== SIMPAN PEMINJAMAN ===== */
    $insert = mysqli_query($koneksi, "
        INSERT INTO peminjaman
        (id_buku, nomor_induk, username, no_tlp, tgl_peminjaman, tgl_pengembalian)
        VALUES
        ('$id_buku','$nomor_induk','$username','$no_tlp','$tgl_peminjaman','$tgl_pengembalian')
    ");

    if ($insert) {
        mysqli_query($koneksi, "UPDATE buku SET jumlah = jumlah - 1 WHERE id_buku='$id_buku'");
        echo "<script>alert('Peminjaman berhasil'); window.location='daftar_peminjaman.php';</script>";
        exit;
    } else {
        echo mysqli_error($koneksi);
    }
}
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Data Peminjaman Buku</title>
    <link rel="icon" href="../../assets/books.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            padding-top: 70px;
        }

        .profile-img {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg fixed-top bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="#">
                <img src="../../assets/logosmk.png" width="48">
                PUSTAKA SMAN 1 TERBANGGI BESAR
            </a>

            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    <li class="nav-item"><a class="nav-link" href="../dashboard_user.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../buku/daftar_buku.php">Buku</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Transaksi</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="peminjaman.php">Peminjaman</a></li>
                            <li><a class="dropdown-item" href="data_pengembalian.php">Pengembalian</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
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

    <div class="container mt-4">
        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <img src="../../assets/books.png" width="40">
                <h2 class="mb-0 fw-bold">Form Peminjaman Buku</h2>
            </div>

            <div class="card-body">
                <form method="POST">

                    <div class="mb-3">
                        <label>Judul Buku</label>
                        <input class="form-control" value="<?= $dataBuku['judul']; ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label>ID Buku</label>
                        <input class="form-control" name="id_buku" value="<?= $dataBuku['id_buku']; ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Username</label>
                        <input class="form-control" name="username" value="<?= $dataUser['username']; ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Nomor Induk</label>
                        <input class="form-control" name="nomor_induk" value="<?= $dataUser['nomor_induk']; ?>"
                            readonly>
                    </div>

                    <div class="mb-3">
                        <label>Tanggal Peminjaman</label>
                        <input type="date" name="tgl_peminjaman" id="tgl_peminjaman" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Tanggal Pengembalian</label>
                        <input type="date" name="tgl_pengembalian" id="tgl_pengembalian" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Nomor Telepon</label>
                        <input type="text" name="no_tlp" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-success">Pinjam Buku</button>
                    <a href="../buku/daftar_buku.php" class="btn btn-secondary ms-2">Batal</a>
                </form>
            </div>
        </div>
    </div>

    <footer class="text-center mt-5 py-3 bg-dark text-white">
        © 2025 Pustaka SMAN 1 Terbanggi Besar
    </footer>

    <!-- BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SCRIPT VALIDASI & AUTO DATE -->
    <script>
        let formChanged = false;

        document.querySelectorAll("input").forEach(el => {
            el.addEventListener("change", () => formChanged = true);
        });

        document.querySelectorAll("a").forEach(link => {
            link.addEventListener("click", e => {
                if (formChanged && !confirm("Apakah anda ingin membatalkan peminjaman ini?")) {
                    e.preventDefault();
                }
            });
        });

        window.addEventListener("beforeunload", e => {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = "";
            }
        });

        const tglPinjam = document.getElementById("tgl_peminjaman");
        const tglKembali = document.getElementById("tgl_pengembalian");

        const today = new Date().toISOString().split("T")[0];
        tglPinjam.min = today;

        tglPinjam.addEventListener("change", function () {
            const d = new Date(this.value);
            d.setDate(d.getDate() + 5);
            tglKembali.value = d.toISOString().split("T")[0];
        });
    </script>

</body>

</html>