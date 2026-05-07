<?php
session_start();
include "../../config/connect.php";

/* ================= CEK LOGIN ================= */
if (!isset($_SESSION['username'])) {
    echo "Session tidak ditemukan";
    exit;
}

/* ================= VALIDASI ID ================= */
if (!isset($_GET['id'])) {
    echo "ID peminjaman tidak valid";
    exit;
}

$id_peminjaman = (int) $_GET['id'];

/* ================= AMBIL DATA PEMINJAMAN ================= */
$qPinjam = mysqli_query($koneksi, "
    SELECT * 
    FROM peminjaman 
    WHERE id_peminjaman = $id_peminjaman
");

$data = mysqli_fetch_assoc($qPinjam);

if (!$data) {
    echo "Data peminjaman tidak ditemukan";
    exit;
}

/* ================= DATA YANG DIPERLUKAN ================= */
$id_buku = $data['id_buku'];
$username = $data['username'];
$nomor_induk = (int) $data['nomor_induk'];
$no_tlp = $data['no_tlp'];
$tgl_peminjaman = $data['tgl_peminjaman'];
$tgl_pengembalian = $data['tgl_pengembalian'];

/* ================= TRANSAKSI ================= */
mysqli_begin_transaction($koneksi);

try {

    /* 1️⃣ TAMBAH STOK BUKU */
    mysqli_query($koneksi, "
        UPDATE buku 
        SET jumlah = jumlah + 1 
        WHERE id_buku = '$id_buku'
    ");

    /* 2️⃣ SIMPAN KE RIWAYAT PENGEMBALIAN */
    mysqli_query($koneksi, "
        INSERT INTO pengembalian 
        (
            id_buku,
            username,
            nomor_induk,
            no_tlp,
            tgl_peminjaman,
            tgl_pengembalian,
            tgl_dikembalikan
        )
        VALUES
        (
            '$id_buku',
            '$username',
            $nomor_induk,
            '$no_tlp',
            '$tgl_peminjaman',
            '$tgl_pengembalian',
            CURDATE()
        )
    ");

    /* 3️⃣ HAPUS DARI PEMINJAMAN */
    mysqli_query($koneksi, "
        DELETE FROM peminjaman 
        WHERE id_peminjaman = $id_peminjaman
    ");

    mysqli_commit($koneksi);

    echo "
        <script>
            alert('Buku berhasil dikembalikan');
            window.location = '../pengembalian/riwayat_pengembalian_admin.php';
        </script>
    ";

} catch (Exception $e) {

    mysqli_rollback($koneksi);
    echo "Terjadi kesalahan: " . $e->getMessage();
}
