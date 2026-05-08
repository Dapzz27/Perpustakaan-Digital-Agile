<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../sign/login_akun.php");
    exit;
}

include "../config/connect.php";

$id_user = $_SESSION['id'];

/* AMBIL DATA USER */
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id='$id_user'");
$user = mysqli_fetch_assoc($query);

/* DEFAULT VALUE */
$nama = $user['nama'];
$email = $user['email'];
$nomor_induk = $user['nomor_induk'];
$jurusan = $user['jurusan'];
$fotoLama = $user['foto'];

/* PROSES UPDATE */
if (isset($_POST['simpan'])) {

    $nama = htmlspecialchars($_POST['nama']);
    $email = htmlspecialchars($_POST['email']);
    $nomor_induk = htmlspecialchars($_POST['nomor_induk']);
    $jurusan = htmlspecialchars($_POST['jurusan']);

    /* FOTO */
    if (!empty($_FILES['foto']['name'])) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $namaFile = 'profil_' . time() . '.' . $ext;
        $path = "../assets/profil/" . $namaFile;

        move_uploaded_file($_FILES['foto']['tmp_name'], $path);
    } else {
        $namaFile = $fotoLama;
    }

    /* UPDATE DATABASE */
    mysqli_query($koneksi, "
        UPDATE users SET
            nama='$nama',
            email='$email',
            nomor_induk='$nomor_induk',
            jurusan='$jurusan',
            foto='$namaFile'
        WHERE id='$id_user'
    ");

    /* UPDATE SESSION FOTO */
    $_SESSION['foto'] = $namaFile;

    header("Location: profil_pengguna.php");
    exit;
}

$fotoProfil = !empty($user['foto'])
    ? "../assets/profil/" . $user['foto']
    : "../assets/pp.jpg";
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Edit Profil</title>
    <link rel="icon" href="../assets/user.png" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fb;
        }

        .form-card {
            background: #fff;
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, .08);
        }

        .profile-img {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .15);
        }

        .btn-save {
            background: #1976d2;
            border: none;
            color: #fff;
            padding: 10px 35px;
            border-radius: 25px;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="container my-5">
        <h2 class="fw-bold mb-4">Edit Profil</h2>

        <div class="form-card">
            <form method="POST" enctype="multipart/form-data">

                <div class="text-center mb-4">
                    <img src="<?= $fotoProfil ?>" class="profile-img mb-2">
                    <div>
                        <input type="file" name="foto" class="form-control mt-2">
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="<?= $nama ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= $email ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">NIS / NIP</label>
                        <input type="text" name="nomor_induk" class="form-control" value="<?= $nomor_induk ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Jurusan</label>
                        <input type="text" name="jurusan" class="form-control" value="<?= $jurusan ?>">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" name="simpan" class="btn btn-save">Simpan Perubahan</button>
                    <a href="profil_pengguna.php" class="btn btn-secondary ms-2">Batal</a>
                </div>

            </form>
        </div>
    </div>

</body>

</html>