-- Database: `perpus_digital`

-- Table structure for table `user`
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    nomor_induk VARCHAR(20) NOT NULL UNIQUE,
    jurusan VARCHAR(50),
    password VARCHAR(255) NOT NULL,
    role TINYINT(1) DEFAULT 0
);


-- Table structure for table `buku`
CREATE TABLE `buku` (
  `cover` varchar(255) NOT NULL,
  `id_buku` varchar(20) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `pengarang` varchar(255) NOT NULL,
  `tahun_terbit` date NOT NULL,
  `jumlah` int(11) NOT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



-- Table structure for table `kategori_buku`
CREATE TABLE `kategori_buku` (
  `kategori` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- Table structure for table `peminjaman`
--
CREATE TABLE `peminjaman` (
  `id_peminjaman` int(11) NOT NULL,
  `id_buku` varchar(20) NOT NULL,
  `nomor_induk` int(11) NOT NULL,
  `username` int(11) NOT NULL,
  `tgl_peminjaman` date NOT NULL,
  `tgl_pengembalian` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Table structure for table `pengembalian`
CREATE TABLE pengembalian (
    id_pengembalian INT AUTO_INCREMENT PRIMARY KEY,
    id_buku VARCHAR(20),
    username VARCHAR(50),
    nomor_induk INT(11),
    no_tlp VARCHAR(15),
    tgl_peminjaman DATE,
    tgl_pengembalian DATE,
    tgl_dikembalikan DATE
);

