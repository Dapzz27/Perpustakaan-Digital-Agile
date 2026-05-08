<?php
include "../../config/connect.php";

$id_buku = $_POST['id_buku'];
$kategori = $_POST['kategori'];
$judul = $_POST['judul'];
$pengarang = $_POST['pengarang'];
$tahun_terbit = $_POST['tahun_terbit'];
$jumlah = $_POST['jumlah'];

$cover = $_FILES['cover']['name'];
$tmp_cover = $_FILES['cover']['tmp_name'];
$folder = "uploads/";

if (!file_exists($folder)) {
    mkdir($folder, 0777, true);
}

$path_cover = $folder . basename($cover);
move_uploaded_file($tmp_cover, $path_cover);

$query = "INSERT INTO buku (id_buku, cover, kategori, judul, pengarang, tahun_terbit, jumlah)
          VALUES ('$id_buku', '$cover', '$kategori', '$judul', '$pengarang', '$tahun_terbit', '$jumlah')";

if (mysqli_query($koneksi, $query)) {
    header('Location: data_buku.php');
    exit;
} else {
    echo "Gagal menyimpan data: " . mysqli_error($koneksi);
}
?>