<?php
session_start();
include "../../config/connect.php";

/* ================= CEK LOGIN ================= */
if (!isset($_SESSION['username'])) {
<<<<<<< HEAD
    header("Location: ../../sign/login_akun.php");
=======
    echo "Session user tidak ditemukan.";
>>>>>>> main
    exit;
}

/* ================= DATA USER ================= */
$username_login = $_SESSION['username'];
<<<<<<< HEAD
$id_user        = $_SESSION['id'];

$queryUser = mysqli_query($koneksi, "
    SELECT nama, username, nomor_induk, jurusan
=======
$id_user = $_SESSION['id'];

$queryUser = mysqli_query($koneksi, "
    SELECT username, nomor_induk 
>>>>>>> main
    FROM users
    WHERE username = '$username_login'
");
$dataUser = mysqli_fetch_assoc($queryUser);

/* ================= FOTO PROFIL ================= */
<<<<<<< HEAD
$qFoto     = mysqli_query($koneksi, "SELECT foto FROM users WHERE id='$id_user'");
$dataFoto  = mysqli_fetch_assoc($qFoto);
$fotoProfil = !empty($dataFoto['foto'])
    ? "../../assets/profil/" . $dataFoto['foto']
    : "../../assets/pp.jpg";

/* ================= DATA BUKU ================= */
=======
$qFoto = mysqli_query($koneksi, "SELECT foto FROM users WHERE id='$id_user'");
$dataFoto = mysqli_fetch_assoc($qFoto);

$fotoProfil = !empty($dataFoto['foto'])
    ? "../../assets/profil_admin/" . $dataFoto['foto']
    : "../../assets/pp.jpg";

/* ================= DATA BUKU (VARCHAR) ================= */
>>>>>>> main
$id_buku = isset($_GET['id'])
    ? mysqli_real_escape_string($koneksi, $_GET['id'])
    : '';

$queryBuku = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku = '$id_buku'");
<<<<<<< HEAD
$dataBuku  = mysqli_fetch_assoc($queryBuku);

if (!$dataBuku) {
    echo "<script>alert('Buku tidak ditemukan'); window.location='../buku/daftar_buku.php';</script>";
    exit;
}

/* ================= PESAN FLASH ================= */
$flash_error   = isset($_SESSION['flash_error'])   ? $_SESSION['flash_error']   : '';
$flash_success = isset($_SESSION['flash_success']) ? $_SESSION['flash_success'] : '';
unset($_SESSION['flash_error'], $_SESSION['flash_success']);

/* ================= PROSES SUBMIT ================= */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_buku_post    = mysqli_real_escape_string($koneksi, $_POST['id_buku']);
    $nomor_induk     = mysqli_real_escape_string($koneksi, $_POST['nomor_induk']);
    $username_post   = mysqli_real_escape_string($koneksi, $_POST['username']);
    $tgl_peminjaman  = $_POST['tgl_peminjaman'];
    $tgl_pengembalian = $_POST['tgl_pengembalian'];
    $no_tlp          = mysqli_real_escape_string($koneksi, $_POST['no_tlp']);

    $errors = [];

    /* === VALIDASI NOMOR HP === */
    if (!preg_match('/^(08[1-9][0-9]{7,10}|\+62[1-9][0-9]{7,10})$/', $no_tlp)) {
        $errors[] = "Format nomor HP tidak valid. Gunakan format 08xx atau +62xx.";
    }

    /* === VALIDASI TANGGAL === */
    $today = date('Y-m-d');
    if ($tgl_peminjaman < $today) {
        $errors[] = "Tanggal peminjaman tidak boleh kurang dari hari ini.";
    }
    $selisih = (strtotime($tgl_pengembalian) - strtotime($tgl_peminjaman)) / (60 * 60 * 24);
    if ($selisih != 5) {
        $errors[] = "Tanggal pengembalian harus tepat 5 hari setelah tanggal peminjaman.";
    }

    /* === CEK STOK === */
    $cekStok = mysqli_query($koneksi, "SELECT jumlah FROM buku WHERE id_buku='$id_buku_post'");
    $stok    = mysqli_fetch_assoc($cekStok);
    if ((int) $stok['jumlah'] <= 0) {
        $errors[] = "Maaf, stok buku ini sudah habis.";
    }

    if (!empty($errors)) {
        $_SESSION['flash_error'] = implode('<br>', $errors);
        header("Location: peminjaman.php?id=$id_buku_post");
        exit;
    }

    /* === SIMPAN PEMINJAMAN === */
=======
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
>>>>>>> main
    $insert = mysqli_query($koneksi, "
        INSERT INTO peminjaman
        (id_buku, nomor_induk, username, no_tlp, tgl_peminjaman, tgl_pengembalian)
        VALUES
<<<<<<< HEAD
        ('$id_buku_post','$nomor_induk','$username_post','$no_tlp','$tgl_peminjaman','$tgl_pengembalian')
    ");

    if ($insert) {
        mysqli_query($koneksi, "UPDATE buku SET jumlah = jumlah - 1 WHERE id_buku='$id_buku_post'");
        $_SESSION['flash_success'] = "Peminjaman buku <strong>{$dataBuku['judul']}</strong> berhasil! Silakan ambil buku di perpustakaan.";
        header("Location: daftar_peminjaman.php");
        exit;
    } else {
        $_SESSION['flash_error'] = "Terjadi kesalahan sistem. Silakan coba lagi.";
        header("Location: peminjaman.php?id=$id_buku_post");
        exit;
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Form Peminjaman Buku – Pustaka SMAN 1 Terbanggi Besar</title>
    <link rel="icon" href="../../assets/books.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,500;0,9..144,700;1,9..144,300&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --cream:    #FAF7F2;
            --ink:      #1A1814;
            --ink-mute: #5C574F;
            --gold:     #B8883A;
            --gold-lt:  #F5E8CC;
            --teal:     #1A6B5A;
            --teal-lt:  #D4EDE8;
            --red:      #B03A2E;
            --red-lt:   #FAE5E3;
            --card-bg:  #FFFFFF;
            --border:   rgba(26,24,20,0.12);
            --radius:   14px;
            --shadow:   0 2px 16px rgba(26,24,20,0.08);
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--ink);
            min-height: 100vh;
            padding-top: 76px;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: #FFFFFF;
            border-bottom: 1px solid var(--border);
            padding: 0 0;
            height: 68px;
        }
        .navbar-brand {
            font-family: 'Fraunces', serif;
            font-weight: 700;
            font-size: 1rem;
            color: var(--ink) !important;
            letter-spacing: -0.01em;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .navbar-brand img { width: 40px; border-radius: 6px; }
        .nav-link {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--ink-mute) !important;
            padding: 6px 12px !important;
            border-radius: 8px;
            transition: background 0.18s, color 0.18s;
        }
        .nav-link:hover { background: var(--cream); color: var(--ink) !important; }
        .nav-link.active { color: var(--ink) !important; }
        .profile-img { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; cursor: pointer; border: 2px solid var(--border); }
        .dropdown-menu {
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 6px;
            font-size: 0.875rem;
        }
        .dropdown-item { border-radius: 8px; padding: 8px 14px; color: var(--ink); }
        .dropdown-item:hover { background: var(--cream); }
        .dropdown-item.text-danger:hover { background: var(--red-lt); }

        /* ===== BREADCRUMB ===== */
        .breadcrumb-area {
            padding: 18px 0 0;
        }
        .breadcrumb {
            font-size: 0.8rem;
            margin: 0;
            background: none;
        }
        .breadcrumb-item a { color: var(--ink-mute); text-decoration: none; }
        .breadcrumb-item a:hover { color: var(--ink); }
        .breadcrumb-item.active { color: var(--gold); font-weight: 500; }
        .breadcrumb-item + .breadcrumb-item::before { color: var(--border); }

        /* ===== PAGE TITLE ===== */
        .page-title {
            font-family: 'Fraunces', serif;
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--ink);
            letter-spacing: -0.02em;
            margin: 6px 0 0;
        }
        .page-subtitle { color: var(--ink-mute); font-size: 0.9rem; margin-top: 4px; }

        /* ===== FLASH ALERTS ===== */
        .alert-custom {
            border-radius: var(--radius);
            border: none;
            padding: 14px 18px;
            font-size: 0.875rem;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        .alert-danger-custom {
            background: var(--red-lt);
            color: var(--red);
        }
        .alert-success-custom {
            background: var(--teal-lt);
            color: var(--teal);
        }
        .alert-custom .alert-icon { font-size: 1.1rem; flex-shrink: 0; margin-top: 1px; }

        /* ===== MAIN LAYOUT ===== */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 24px;
            align-items: start;
            padding: 28px 0 60px;
        }
        @media (max-width: 991px) {
            .main-grid { grid-template-columns: 1fr; }
        }

        /* ===== FORM CARD ===== */
        .form-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }
        .form-card-header {
            padding: 22px 28px 18px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .form-card-header-icon {
            width: 40px; height: 40px;
            background: var(--gold-lt);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem;
        }
        .form-card-header h2 {
            font-family: 'Fraunces', serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--ink);
            margin: 0;
            letter-spacing: -0.01em;
        }
        .form-card-header p {
            font-size: 0.8rem;
            color: var(--ink-mute);
            margin: 0;
        }
        .form-card-body { padding: 28px; }

        /* ===== SECTION LABEL ===== */
        .section-label {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--ink-mute);
            margin: 0 0 14px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border);
        }

        /* ===== FORM FIELDS ===== */
        .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 6px;
        }
        .form-label .req { color: var(--red); margin-left: 2px; }

        .form-control, .form-select {
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.9rem;
            font-family: 'DM Sans', sans-serif;
            color: var(--ink);
            background: var(--cream);
            transition: border 0.2s, box-shadow 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(184,136,58,0.12);
            background: #FFFFFF;
            outline: none;
        }
        .form-control[readonly] {
            background: rgba(26,24,20,0.03);
            color: var(--ink-mute);
            cursor: default;
        }
        .form-control[readonly]:focus {
            border-color: var(--border);
            box-shadow: none;
        }
        .form-hint {
            font-size: 0.75rem;
            color: var(--ink-mute);
            margin-top: 5px;
        }
        .form-hint.text-danger { color: var(--red); }

        /* ===== DATE ROW ===== */
        .date-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        @media (max-width: 576px) { .date-row { grid-template-columns: 1fr; } }

        /* ===== DATE BADGE ===== */
        .date-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: var(--gold-lt);
            color: var(--gold);
            font-size: 0.72rem;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 20px;
            margin-top: 6px;
        }

        /* ===== DIVIDER ===== */
        .form-divider { border: none; border-top: 1px solid var(--border); margin: 24px 0; }

        /* ===== SUBMIT AREA ===== */
        .submit-area {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .btn-submit {
            background: var(--ink);
            color: #FFFFFF;
            border: none;
            border-radius: 10px;
            padding: 12px 26px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-submit:hover { background: #2E2A22; transform: translateY(-1px); }
        .btn-submit:active { transform: translateY(0); }
        .btn-cancel {
            background: transparent;
            color: var(--ink-mute);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 12px 22px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
        }
        .btn-cancel:hover { background: var(--cream); color: var(--ink); }

        /* ===== SIDEBAR CARD ===== */
        .sidebar-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }
        .book-cover-wrap {
            position: relative;
            width: 100%;
            padding-bottom: 62%;
            background: var(--cream);
            overflow: hidden;
        }
        .book-cover-wrap img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .book-cover-wrap .stok-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 600;
        }
        .badge-available { background: var(--teal-lt); color: var(--teal); }
        .badge-low { background: #FEF3CD; color: #856404; }
        .badge-empty { background: var(--red-lt); color: var(--red); }

        .book-info { padding: 20px; }
        .book-kategori {
            display: inline-block;
            background: var(--gold-lt);
            color: var(--gold);
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            padding: 3px 9px;
            border-radius: 20px;
            margin-bottom: 10px;
        }
        .book-title {
            font-family: 'Fraunces', serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--ink);
            letter-spacing: -0.01em;
            margin: 0 0 4px;
            line-height: 1.35;
        }
        .book-author {
            font-size: 0.82rem;
            color: var(--ink-mute);
            margin: 0 0 14px;
        }
        .book-meta { border-top: 1px solid var(--border); padding-top: 14px; display: grid; gap: 10px; }
        .book-meta-row { display: flex; justify-content: space-between; align-items: center; }
        .book-meta-label { font-size: 0.78rem; color: var(--ink-mute); }
        .book-meta-value { font-size: 0.82rem; font-weight: 600; color: var(--ink); }

        /* ===== ATURAN CARD ===== */
        .rules-card {
            background: var(--gold-lt);
            border: 1px solid rgba(184,136,58,0.2);
            border-radius: var(--radius);
            padding: 18px 20px;
            margin-top: 16px;
        }
        .rules-card h5 {
            font-family: 'Fraunces', serif;
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--ink);
            margin: 0 0 12px;
            display: flex;
            align-items: center;
            gap: 7px;
        }
        .rules-list { list-style: none; padding: 0; margin: 0; display: grid; gap: 8px; }
        .rules-list li {
            font-size: 0.8rem;
            color: var(--ink-mute);
            display: flex;
            gap: 8px;
            align-items: flex-start;
        }
        .rules-list li .rule-num {
            width: 18px; height: 18px;
            min-width: 18px;
            background: var(--gold);
            color: #FFFFFF;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.65rem;
            font-weight: 700;
            margin-top: 0px;
        }

        /* ===== FOOTER ===== */
        footer {
            background: var(--ink);
            color: rgba(255,255,255,0.5);
            text-align: center;
            padding: 18px;
            font-size: 0.8rem;
        }
        footer strong { color: rgba(255,255,255,0.85); }

        /* ===== CONFIRM MODAL ===== */
        .modal-content {
            border: none;
            border-radius: var(--radius);
            font-family: 'DM Sans', sans-serif;
        }
        .modal-header {
            border-bottom: 1px solid var(--border);
            padding: 18px 24px;
        }
        .modal-title {
            font-family: 'Fraunces', serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--ink);
        }
        .modal-body { padding: 22px 24px; font-size: 0.9rem; color: var(--ink-mute); }
        .modal-summary {
            background: var(--cream);
            border-radius: 10px;
            padding: 14px 16px;
            margin-top: 14px;
            display: grid;
            gap: 8px;
            font-size: 0.85rem;
        }
        .modal-summary-row { display: flex; justify-content: space-between; }
        .modal-summary-label { color: var(--ink-mute); }
        .modal-summary-value { font-weight: 600; color: var(--ink); }
        .modal-footer { border-top: 1px solid var(--border); padding: 14px 24px; gap: 10px; }
    </style>
</head>
<body>

<!-- ======================== NAVBAR ======================== -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="../dashboard_user.php">
            <img src="../../assets/logosmk.png" alt="Logo">
            PUSTAKA SMAN 1 TERBANGGI BESAR
        </a>

        <button class="navbar-toggler border-0" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-center gap-1">
                <li class="nav-item"><a class="nav-link" href="../dashboard_user.php">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="../buku/daftar_buku.php">Katalog Buku</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">Transaksi</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="daftar_peminjaman.php">Peminjaman Saya</a></li>
                        <li><a class="dropdown-item" href="../Pengembalian/riwayat_pengembalian.php">Riwayat Pengembalian</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown ms-2">
                    <img src="<?= $fotoProfil ?>" class="profile-img dropdown-toggle" data-bs-toggle="dropdown" alt="Profil">
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="px-3 py-2">
                            <p class="mb-0 fw-bold" style="font-size:0.85rem;color:var(--ink)"><?= htmlspecialchars($dataUser['nama'] ?? $dataUser['username']) ?></p>
                            <p class="mb-0" style="font-size:0.75rem;color:var(--ink-mute)"><?= htmlspecialchars($dataUser['nomor_induk']) ?></p>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li><a class="dropdown-item" href="../profil_pengguna.php">Profil Saya</a></li>
                        <li><a class="dropdown-item text-danger" href="../logout.php">Keluar</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- ======================== CONTENT ======================== -->
<div class="container">
    <!-- Breadcrumb -->
    <div class="breadcrumb-area">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard_user.php">Beranda</a></li>
                <li class="breadcrumb-item"><a href="../buku/daftar_buku.php">Katalog Buku</a></li>
                <li class="breadcrumb-item active">Form Peminjaman</li>
            </ol>
        </nav>
        <h1 class="page-title">Form Peminjaman Buku</h1>
        <p class="page-subtitle">Isi data di bawah ini untuk meminjam buku dari perpustakaan.</p>
    </div>

    <!-- Flash Messages -->
    <?php if ($flash_error): ?>
    <div class="alert-custom alert-danger-custom mt-4">
        <span class="alert-icon">⚠️</span>
        <div><?= $flash_error ?></div>
    </div>
    <?php endif; ?>
    <?php if ($flash_success): ?>
    <div class="alert-custom alert-success-custom mt-4">
        <span class="alert-icon">✅</span>
        <div><?= $flash_success ?></div>
    </div>
    <?php endif; ?>

    <!-- Main Grid -->
    <div class="main-grid">

        <!-- ===== FORM ===== -->
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-header-icon">📋</div>
                <div>
                    <h2>Data Peminjaman</h2>
                    <p>Pastikan semua data terisi dengan benar sebelum mengajukan peminjaman.</p>
                </div>
            </div>

            <div class="form-card-body">
                <form method="POST" id="formPeminjaman" novalidate>

                    <!-- Bagian: Informasi Buku -->
                    <p class="section-label">Informasi Buku</p>

                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label">Judul Buku</label>
                            <input class="form-control" value="<?= htmlspecialchars($dataBuku['judul']) ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">ID Buku</label>
                            <input class="form-control" name="id_buku" value="<?= htmlspecialchars($dataBuku['id_buku']) ?>" readonly>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Pengarang</label>
                            <input class="form-control" value="<?= htmlspecialchars($dataBuku['pengarang']) ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            <input class="form-control" value="<?= htmlspecialchars($dataBuku['kategori']) ?>" readonly>
                        </div>
                    </div>

                    <hr class="form-divider">

                    <!-- Bagian: Identitas Peminjam -->
                    <p class="section-label">Identitas Peminjam</p>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input class="form-control" name="username" value="<?= htmlspecialchars($dataUser['username']) ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor Induk</label>
                            <input class="form-control" name="nomor_induk" value="<?= htmlspecialchars($dataUser['nomor_induk']) ?>" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="no_tlp">Nomor Telepon Aktif <span class="req">*</span></label>
                        <input type="tel" class="form-control" name="no_tlp" id="no_tlp"
                               placeholder="Contoh: 08123456789"
                               value="<?= isset($_POST['no_tlp']) ? htmlspecialchars($_POST['no_tlp']) : '' ?>"
                               required>
                        <div class="form-hint">Gunakan format 08xx atau +62xx. Nomor ini digunakan untuk konfirmasi peminjaman.</div>
                    </div>

                    <hr class="form-divider">

                    <!-- Bagian: Jadwal Peminjaman -->
                    <p class="section-label">Jadwal Peminjaman</p>

                    <div class="date-row mb-2">
                        <div>
                            <label class="form-label" for="tgl_peminjaman">Tanggal Pinjam <span class="req">*</span></label>
                            <input type="date" class="form-control" name="tgl_peminjaman" id="tgl_peminjaman" required>
                            <div class="form-hint">Minimal hari ini.</div>
                        </div>
                        <div>
                            <label class="form-label" for="tgl_pengembalian">Tanggal Kembali</label>
                            <input type="date" class="form-control" name="tgl_pengembalian" id="tgl_pengembalian" readonly>
                            <div class="date-badge" id="returnBadge" style="display:none">
                                📅 Otomatis 5 hari
                            </div>
                        </div>
                    </div>

                    <div class="alert-custom alert-success-custom mb-3" style="font-size:0.8rem; padding:10px 14px;">
                        <span>📌</span>
                        <span>Durasi peminjaman adalah <strong>5 hari</strong> kalender. Tanggal pengembalian akan dihitung otomatis.</span>
                    </div>

                    <hr class="form-divider">

                    <!-- Submit -->
                    <div class="submit-area">
                        <button type="button" class="btn-submit" onclick="konfirmasiPeminjaman()">
                            📚 Ajukan Peminjaman
                        </button>
                        <a href="../buku/daftar_buku.php" class="btn-cancel" id="btnBatal">
                            Batal
                        </a>
                    </div>

                </form>
            </div>
        </div>

        <!-- ===== SIDEBAR ===== -->
        <div>
            <!-- Info Buku -->
            <div class="sidebar-card">
                <div class="book-cover-wrap">
                    <img src="../../app/buku/uploads/<?= htmlspecialchars($dataBuku['cover']) ?>"
                         alt="Cover <?= htmlspecialchars($dataBuku['judul']) ?>">
                    <?php
                    $jumlah = (int) $dataBuku['jumlah'];
                    if ($jumlah <= 0) {
                        echo '<span class="stok-badge badge-empty">Stok Habis</span>';
                    } elseif ($jumlah <= 2) {
                        echo '<span class="stok-badge badge-low">Stok Terbatas</span>';
                    } else {
                        echo '<span class="stok-badge badge-available">Tersedia</span>';
                    }
                    ?>
                </div>

                <div class="book-info">
                    <span class="book-kategori"><?= htmlspecialchars($dataBuku['kategori']) ?></span>
                    <h3 class="book-title"><?= htmlspecialchars($dataBuku['judul']) ?></h3>
                    <p class="book-author">✍️ <?= htmlspecialchars($dataBuku['pengarang']) ?></p>

                    <div class="book-meta">
                        <div class="book-meta-row">
                            <span class="book-meta-label">ID Buku</span>
                            <span class="book-meta-value"><?= htmlspecialchars($dataBuku['id_buku']) ?></span>
                        </div>
                        <div class="book-meta-row">
                            <span class="book-meta-label">Tahun Terbit</span>
                            <span class="book-meta-value"><?= date('Y', strtotime($dataBuku['tahun_terbit'])) ?></span>
                        </div>
                        <div class="book-meta-row">
                            <span class="book-meta-label">Stok Tersisa</span>
                            <span class="book-meta-value" style="color:<?= $jumlah > 0 ? 'var(--teal)' : 'var(--red)' ?>">
                                <?= $jumlah ?> eksemplar
                            </span>
                        </div>
                        <div class="book-meta-row">
                            <span class="book-meta-label">Durasi Pinjam</span>
                            <span class="book-meta-value">5 hari</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aturan Peminjaman -->
            <div class="rules-card">
                <h5>📖 Aturan Peminjaman</h5>
                <ul class="rules-list">
                    <li>
                        <span class="rule-num">1</span>
                        <span>Setiap siswa hanya dapat meminjam 1 buku dalam 1 periode.</span>
                    </li>
                    <li>
                        <span class="rule-num">2</span>
                        <span>Durasi peminjaman maksimal 5 hari kalender sejak tanggal pinjam.</span>
                    </li>
                    <li>
                        <span class="rule-num">3</span>
                        <span>Keterlambatan pengembalian dikenakan denda sesuai ketentuan perpustakaan.</span>
                    </li>
                    <li>
                        <span class="rule-num">4</span>
                        <span>Buku yang rusak atau hilang menjadi tanggung jawab peminjam.</span>
                    </li>
                    <li>
                        <span class="rule-num">5</span>
                        <span>Ambil buku langsung ke petugas perpustakaan setelah pengajuan dikonfirmasi.</span>
=======
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
>>>>>>> main
                    </li>
                </ul>
            </div>
        </div>
<<<<<<< HEAD

    </div><!-- end main-grid -->
</div>

<!-- ======================== MODAL KONFIRMASI ======================== -->
<div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Pastikan data peminjaman berikut sudah benar sebelum mengajukan.</p>
                <div class="modal-summary">
                    <div class="modal-summary-row">
                        <span class="modal-summary-label">Buku</span>
                        <span class="modal-summary-value"><?= htmlspecialchars($dataBuku['judul']) ?></span>
                    </div>
                    <div class="modal-summary-row">
                        <span class="modal-summary-label">Peminjam</span>
                        <span class="modal-summary-value"><?= htmlspecialchars($dataUser['username']) ?></span>
                    </div>
                    <div class="modal-summary-row">
                        <span class="modal-summary-label">No. Telepon</span>
                        <span class="modal-summary-value" id="modalNoTlp">–</span>
                    </div>
                    <div class="modal-summary-row">
                        <span class="modal-summary-label">Tanggal Pinjam</span>
                        <span class="modal-summary-value" id="modalTglPinjam">–</span>
                    </div>
                    <div class="modal-summary-row">
                        <span class="modal-summary-label">Tanggal Kembali</span>
                        <span class="modal-summary-value" id="modalTglKembali">–</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" data-bs-dismiss="modal">Periksa Lagi</button>
                <button type="button" class="btn-submit" onclick="document.getElementById('formPeminjaman').submit()">
                    ✅ Ya, Ajukan Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ======================== FOOTER ======================== -->
<footer>
    © 2025 <strong>Pustaka SMAN 1 Terbanggi Besar</strong>. Semua hak dilindungi.
</footer>

<!-- ======================== SCRIPTS ======================== -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    /* === Auto-set tanggal minimal & pengembalian otomatis === */
    const tglPinjam   = document.getElementById('tgl_peminjaman');
    const tglKembali  = document.getElementById('tgl_pengembalian');
    const returnBadge = document.getElementById('returnBadge');

    const today = new Date().toISOString().split('T')[0];
    tglPinjam.min   = today;
    tglPinjam.value = today;

    function updateReturnDate() {
        if (!tglPinjam.value) return;
        const d = new Date(tglPinjam.value);
        d.setDate(d.getDate() + 5);
        tglKembali.value = d.toISOString().split('T')[0];
        returnBadge.style.display = 'inline-flex';
    }

    updateReturnDate();
    tglPinjam.addEventListener('change', updateReturnDate);

    /* === Warn before leaving with unsaved changes === */
    let formChanged = false;
    document.querySelectorAll('#formPeminjaman input[type="tel"], #formPeminjaman input[type="date"]').forEach(el => {
        el.addEventListener('input', () => formChanged = true);
    });

    document.getElementById('btnBatal').addEventListener('click', function(e) {
        if (formChanged && !confirm('Apakah Anda yakin ingin membatalkan pengajuan peminjaman ini?')) {
            e.preventDefault();
        }
    });

    /* === Modal konfirmasi === */
    function konfirmasiPeminjaman() {
        const noTlp = document.getElementById('no_tlp').value.trim();
        const tglP  = tglPinjam.value;
        const tglK  = tglKembali.value;

        if (!noTlp) {
            document.getElementById('no_tlp').focus();
            document.getElementById('no_tlp').style.borderColor = 'var(--red)';
            return;
        }
        if (!tglP) {
            document.getElementById('tgl_peminjaman').focus();
            return;
        }

        const fmt = d => {
            if (!d) return '–';
            const [y, m, day] = d.split('-');
            const bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
            return `${day} ${bulan[parseInt(m)-1]} ${y}`;
        };

        document.getElementById('modalNoTlp').textContent    = noTlp;
        document.getElementById('modalTglPinjam').textContent = fmt(tglP);
        document.getElementById('modalTglKembali').textContent = fmt(tglK);

        new bootstrap.Modal(document.getElementById('modalKonfirmasi')).show();
    }

    /* === Reset border warna saat focus === */
    document.getElementById('no_tlp').addEventListener('focus', function() {
        this.style.borderColor = '';
    });
</script>

</body>
=======
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

>>>>>>> main
</html>