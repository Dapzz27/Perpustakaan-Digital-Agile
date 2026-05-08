<?php
include "../../config/connect.php";
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>