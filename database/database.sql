-- Database: perpus_digital

CREATE DATABASE IF NOT EXISTS perpus_digital;
USE perpus_digital;

-- =========================================
-- Table structure for table `users`
-- =========================================
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama` VARCHAR(100) NOT NULL,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `nomor_induk` VARCHAR(20) NOT NULL UNIQUE,
    `jurusan` VARCHAR(50),
    `password` VARCHAR(255) NOT NULL,
    `role` TINYINT(1) DEFAULT 0,
    `foto` VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- =========================================
-- Table structure for table `buku`
-- =========================================
CREATE TABLE `buku` (
    `id_buku` VARCHAR(20) PRIMARY KEY,
    `cover` VARCHAR(255) NOT NULL,
    `kategori` VARCHAR(255) NOT NULL,
    `judul` VARCHAR(255) NOT NULL,
    `pengarang` VARCHAR(255) NOT NULL,
    `tahun_terbit` DATE NOT NULL,
    `jumlah` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- =========================================
-- Table structure for table `kategori_buku`
-- =========================================
CREATE TABLE `kategori_buku` (
    `id_kategori` INT AUTO_INCREMENT PRIMARY KEY,
    `kategori` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- =========================================
-- Table structure for table `peminjaman`
-- =========================================
CREATE TABLE `peminjaman` (
    `id_peminjaman` INT AUTO_INCREMENT PRIMARY KEY,
    `id_buku` VARCHAR(20) NOT NULL,
    `nomor_induk` VARCHAR(20) NOT NULL,
    `username` VARCHAR(50) NOT NULL,
    `tgl_peminjaman` DATE NOT NULL,
    `tgl_pengembalian` DATE NOT NULL,

    CONSTRAINT `fk_peminjaman_buku`
    FOREIGN KEY (`id_buku`) REFERENCES `buku`(`id_buku`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- =========================================
-- Table structure for table `pengembalian`
-- =========================================
CREATE TABLE `pengembalian` (
    `id_pengembalian` INT AUTO_INCREMENT PRIMARY KEY,
    `id_buku` VARCHAR(20),
    `username` VARCHAR(50),
    `nomor_induk` VARCHAR(20),
    `no_tlp` VARCHAR(15),
    `tgl_peminjaman` DATE,
    `tgl_pengembalian` DATE,
    `tgl_dikembalikan` DATE,

    CONSTRAINT `fk_pengembalian_buku`
    FOREIGN KEY (`id_buku`) REFERENCES `buku`(`id_buku`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
