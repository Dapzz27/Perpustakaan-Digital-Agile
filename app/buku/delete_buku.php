<?php
include "../../config/connect.php";

if (isset($_GET['id']) && $_GET['id'] !== '') {
    $id_buku = mysqli_real_escape_string($koneksi, $_GET['id']);

    $hapus = mysqli_query($koneksi, "DELETE FROM buku WHERE id_buku = '$id_buku'");

    if ($hapus) {
        header("Location: data_buku.php");
        exit;
    } else {
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }
} else {
    echo "ID buku tidak disediakan atau tidak valid.";
}
