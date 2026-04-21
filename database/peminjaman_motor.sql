-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 10, 2026 at 09:03 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `peminjaman_motor`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `approve_peminjaman` (IN `p_peminjaman_id` INT)   BEGIN
    UPDATE peminjaman 
    SET status = 'dipinjam'
    WHERE id = p_peminjaman_id;
    
    SELECT 'SUCCESS' as status, 'Peminjaman disetujui' as message;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `kembalikan_barang` (IN `p_peminjaman_id` INT, IN `p_kondisi` VARCHAR(20), IN `p_keterangan` TEXT, IN `p_denda` DECIMAL(15,2))   BEGIN
    DECLARE v_telat INT;
    DECLARE v_total_denda DECIMAL(15,2);
    DECLARE v_total_harga DECIMAL(15,2);
    DECLARE v_lama_pinjam INT;
    DECLARE v_harga_perhari DECIMAL(10,2);
    
    -- Ambil data peminjaman
    SELECT total_harga, lama_pinjam, harga_sewa_perhari
    INTO v_total_harga, v_lama_pinjam, v_harga_perhari
    FROM peminjaman p
    JOIN barang b ON p.barang_id = b.id
    WHERE p.id = p_peminjaman_id;
    
    -- Hitung hari telat
    SELECT DATEDIFF(CURDATE(), tgl_kembali_rencana)
    INTO v_telat
    FROM peminjaman
    WHERE id = p_peminjaman_id;
    
    SET v_telat = GREATEST(v_telat, 0);
    
    -- Hitung total denda (denda input + denda keterlambatan 10% per hari)
    SET v_total_denda = p_denda + (v_telat * 0.1 * v_harga_perhari);
    
    -- Update langsung ke tabel peminjaman (tanpa insert ke pengembalian)
    UPDATE peminjaman 
    SET 
        status = 'selesai',
        tgl_kembali_aktual = CURDATE(),
        kondisi = p_kondisi,
        denda = v_total_denda,
        keterangan_pengembalian = p_keterangan,
        tgl_pengembalian_input = NOW(),
        updated_at = NOW()
    WHERE id = p_peminjaman_id;
    
    -- Update status barang jika rusak
    IF p_kondisi != 'baik' THEN
        UPDATE barang 
        SET status = 'rusak'
        WHERE id = (SELECT barang_id FROM peminjaman WHERE id = p_peminjaman_id);
    END IF;
    
    -- Tampilkan hasil
    SELECT 
        'SUCCESS' as status, 
        CONCAT('Pengembalian berhasil. Denda: Rp ', FORMAT(v_total_denda, 0)) as message;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pinjam_barang` (IN `p_user_id` INT, IN `p_barang_id` INT, IN `p_jumlah` INT, IN `p_tgl_pinjam` DATE, IN `p_tgl_kembali` DATE, IN `p_keterangan` TEXT)   BEGIN
    DECLARE v_jumlah_tersedia INT;
    DECLARE v_harga DECIMAL(10,2);
    
    -- Check stock availability
    SELECT jumlah_tersedia, harga_sewa_perhari 
    INTO v_jumlah_tersedia, v_harga
    FROM barang 
    WHERE id = p_barang_id;
    
    IF v_jumlah_tersedia >= p_jumlah THEN
        -- Insert peminjaman (trigger will handle kode and total)
        INSERT INTO peminjaman (
            user_id, 
            barang_id, 
            jumlah, 
            tgl_pinjam, 
            tgl_kembali_rencana,
            keterangan,
            status
        ) VALUES (
            p_user_id,
            p_barang_id,
            p_jumlah,
            p_tgl_pinjam,
            p_tgl_kembali,
            p_keterangan,
            'pending'
        );
        
        SELECT 'SUCCESS' as status, 'Peminjaman berhasil diajukan' as message;
    ELSE
        SELECT 'ERROR' as status, CONCAT('Stok tidak cukup. Tersedia: ', v_jumlah_tersedia) as message;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proses_pengembalian` (IN `p_peminjaman_id` INT, IN `p_kondisi` VARCHAR(20), IN `p_keterangan` TEXT, IN `p_denda_manual` DECIMAL(15,2))   BEGIN
    DECLARE v_status VARCHAR(20);
    DECLARE v_tgl_kembali_rencana DATE;
    DECLARE v_tgl_pinjam DATE;
    DECLARE v_telat_hari INT;
    DECLARE v_denda DECIMAL(15,2);
    DECLARE v_harga_sewa DECIMAL(10,2);
    DECLARE v_jumlah INT;
    DECLARE v_barang_id INT;
    
    SELECT status, tgl_kembali_rencana, tgl_pinjam, barang_id, jumlah 
    INTO v_status, v_tgl_kembali_rencana, v_tgl_pinjam, v_barang_id, v_jumlah
    FROM peminjaman WHERE id = p_peminjaman_id;
    
    IF v_status = 'selesai' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Peminjaman sudah selesai!';
    END IF;
    
    SELECT harga_sewa_perhari INTO v_harga_sewa FROM barang WHERE id = v_barang_id;
    
    SET v_telat_hari = GREATEST(DATEDIFF(CURDATE(), v_tgl_kembali_rencana), 0);
    SET v_denda = (v_telat_hari * v_jumlah * 50000) + COALESCE(p_denda_manual, 0);
    
    UPDATE peminjaman SET
        tgl_kembali_aktual = CURDATE(),
        lama_pinjam = DATEDIFF(CURDATE(), v_tgl_pinjam),
        status = IF(CURDATE() > v_tgl_kembali_rencana, 'terlambat', 'selesai'),
        total_harga = DATEDIFF(CURDATE(), v_tgl_pinjam) * v_jumlah * v_harga_sewa,
        kondisi = p_kondisi,
        denda = v_denda,
        keterangan_pengembalian = p_keterangan,
        tgl_pengembalian_input = NOW(),
        updated_at = NOW()
    WHERE id = p_peminjaman_id;
    
    SELECT 
        p.id,
        p.kode_peminjaman,
        p.tgl_pinjam,
        p.tgl_kembali_rencana,
        p.tgl_kembali_aktual,
        p.lama_pinjam,
        p.kondisi,
        p.denda,
        p.total_harga,
        p.status,
        'SUCCESS' as proses_status,
        CONCAT('Pengembalian berhasil. Total bayar: Rp ', FORMAT(p.total_harga + p.denda, 0)) as message
    FROM peminjaman p
    WHERE p.id = p_peminjaman_id;
    
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` int NOT NULL,
  `kode_barang` varchar(20) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `kategori_id` int NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `merk` varchar(50) DEFAULT NULL,
  `tahun` int DEFAULT NULL,
  `stok` int DEFAULT '1',
  `jumlah_tersedia` int DEFAULT '1',
  `jumlah` int DEFAULT '1',
  `status` enum('tersedia','dipinjam','rusak','hilang') DEFAULT 'tersedia',
  `harga_sewa_perhari` decimal(10,2) NOT NULL,
  `deskripsi` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `kode_barang`, `slug`, `kategori_id`, `nama_barang`, `merk`, `tahun`, `stok`, `jumlah_tersedia`, `jumlah`, `status`, `harga_sewa_perhari`, `deskripsi`, `created_at`) VALUES
(1, 'MTR-001', 'vario-150', 1, 'Vario 150', 'Honda', 2023, 5, 5, 5, 'tersedia', 80000.00, 'Motor matic 150cc, fitur lengkap, irit bahan bakar', '2026-04-09 11:40:33'),
(2, 'MTR-002', 'beat-street', 1, 'Beat Street', 'Honda', 2024, 7, 9, 7, 'tersedia', 60000.00, 'Motor matic stylish, ekonomis, cocok untuk pemula', '2026-04-09 11:40:33'),
(3, 'MTR-003', 'nmax', 1, 'NMAX', 'Yamaha', 2023, 3, 3, 3, 'tersedia', 120000.00, 'Motor matic premium 155cc, nyaman untuk touring', '2026-04-09 11:40:33'),
(4, 'MTR-004', 'aerox-155', 1, 'Aerox 155', 'Yamaha', 2024, 4, 5, 4, 'tersedia', 110000.00, 'Sporty matic 155cc, desain agresif, performa tinggi', '2026-04-09 11:40:33'),
(5, 'MTR-005', 'pcx-160', 1, 'PCX 160', 'Honda', 2024, 2, 2, 2, 'tersedia', 150000.00, 'Matic premium 160cc, full fitur, sangat nyaman', '2026-04-09 11:40:33'),
(6, 'MTR-006', 'scoopy', 1, 'Scoopy', 'Honda', 2024, 6, 6, 6, 'tersedia', 70000.00, 'Matic stylish 110cc, desain retro modern', '2026-04-09 11:40:33'),
(7, 'MTR-007', 'freego', 1, 'FreeGo', 'Yamaha', 2023, 5, 5, 5, 'tersedia', 58000.00, 'Matic simple 125cc, irit dan praktis', '2026-04-09 11:40:33'),
(8, 'MTR-008', 'lexi', 1, 'Lexi', 'Yamaha', 2024, 4, 4, 4, 'tersedia', 85000.00, 'Matic premium 125cc, desain mewah', '2026-04-09 11:40:33'),
(9, 'MTR-009', 'fazzio', 1, 'Fazzio', 'Yamaha', 2024, 4, 4, 4, 'tersedia', 75000.00, 'Matic retro 125cc, gaya vintage modern', '2026-04-09 11:40:33'),
(10, 'MTR-010', 'burgman-street', 1, 'Burgman Street', 'Suzuki', 2023, 3, 3, 3, 'tersedia', 90000.00, 'Matic 125cc dengan storage besar, nyaman', '2026-04-09 11:40:33'),
(11, 'MTR-011', 'crf150l', 2, 'CRF150L', 'Honda', 2023, 3, 3, 3, 'tersedia', 100000.00, 'Motor trail 150cc, tangguh untuk medan berat', '2026-04-09 11:40:33'),
(12, 'MTR-012', 'klx-150', 2, 'KLX 150', 'Kawasaki', 2024, 2, 2, 2, 'tersedia', 120000.00, 'Trail sport 150cc, lincah di medan offroad', '2026-04-09 11:40:33'),
(13, 'MTR-013', 'wr155r', 2, 'WR155R', 'Yamaha', 2024, 2, 2, 2, 'tersedia', 130000.00, 'Dual sport 155cc, performa tangguh', '2026-04-09 11:40:33'),
(14, 'MTR-01444', 'adventure-150', 2, 'Adventure 150', 'Honda', 2024, 2, 2, 2, 'tersedia', 140000.00, 'Adventure scooter 150cc, untuk touring offroad', '2026-04-09 11:40:33'),
(15, 'MTR-015', 'tiger-200', 2, 'Tiger 200', 'Kawasaki', 2023, 1, 1, 1, 'tersedia', 125000.00, 'Trail 200cc, tenaga besar untuk medan ekstrim', '2026-04-09 11:40:33'),
(16, 'MTR-016', 'dr-z400', 2, 'DR-Z400', 'Suzuki', 2023, 1, 1, 1, 'tersedia', 180000.00, 'Trail 400cc, performa tinggi untuk offroad profesional', '2026-04-09 11:40:33'),
(17, 'MTR-017', 'cbr250rr', 3, 'CBR250RR', 'Honda', 2023, 2, 2, 2, 'tersedia', 180000.00, 'Sport bike 250cc, performa balap', '2026-04-09 11:40:33'),
(18, 'MTR-018', 'r15-v4', 3, 'R15 V4', 'Yamaha', 2024, 3, 3, 3, 'tersedia', 160000.00, 'Sport bike 155cc, desain agresif, performa tinggi', '2026-04-09 11:40:33'),
(19, 'MTR-019', 'ninja-250sl', 3, 'Ninja 250SL', 'Kawasaki', 2023, 2, 2, 2, 'tersedia', 170000.00, 'Sport bike 250cc, lincah dan bertenaga', '2026-04-09 11:40:33'),
(20, 'MTR-020', 'gsx-r150', 3, 'GSX-R150', 'Suzuki', 2024, 2, 2, 2, 'tersedia', 155000.00, 'Sport bike 150cc, performa kompetitif', '2026-04-09 11:40:33'),
(21, 'MTR-021', 'zx-25r', 3, 'ZX-25R', 'Kawasaki', 2024, 1, 1, 1, 'tersedia', 250000.00, 'Sport bike 250cc 4 silinder, suara merdu', '2026-04-09 11:40:33'),
(22, 'MTR-022', 'mt-15', 3, 'MT-15', 'Yamaha', 2024, 3, 3, 3, 'tersedia', 135000.00, 'Naked sport 155cc, desain naked agresif', '2026-04-09 11:40:33'),
(23, 'MTR-023', 'cb150r', 3, 'CB150R', 'Honda', 2023, 2, 2, 2, 'tersedia', 145000.00, 'Naked sport 150cc, streetfighter style', '2026-04-09 11:40:33'),
(24, 'MTR-024', 'mx-king-150', 3, 'MX King 150', 'Yamaha', 2024, 3, 3, 3, 'tersedia', 95000.00, 'Sport bebek 150cc, kencang dan lincah', '2026-04-09 11:40:33'),
(25, 'MTR-025', 'supra-x-125', 4, 'Supra X 125', 'Honda', 2023, 4, 4, 4, 'tersedia', 50000.00, 'Motor bebek 125cc, irit bahan bakar', '2026-04-09 11:40:33'),
(26, 'MTR-026', 'grand', 4, 'Grand', 'Yamaha', 2024, 5, 5, 5, 'tersedia', 55000.00, 'Motor bebek 125cc, desain elegan', '2026-04-09 11:40:33'),
(27, 'MTR-027', 'jupiter-mx', 4, 'Jupiter MX', 'Yamaha', 2023, 3, 3, 3, 'tersedia', 60000.00, 'Motor bebek sport 150cc, performa tinggi', '2026-04-09 11:40:33'),
(28, 'MTR-028', 'revo-fit', 4, 'Revo Fit', 'Honda', 2024, 4, 4, 4, 'tersedia', 45000.00, 'Motor bebek 110cc, paling irit', '2026-04-09 11:40:33'),
(29, 'MTR-029', 'sonic-150r', 4, 'Sonic 150R', 'Honda', 2023, 2, 2, 2, 'tersedia', 70000.00, 'Motor bebek sport 150cc, desain sporty', '2026-04-09 11:40:33'),
(30, 'MTR-030', 'xmax', 5, 'XMAX', 'Yamaha', 2024, 2, 2, 2, 'tersedia', 200000.00, 'Skuter premium 250cc, nyaman untuk touring jauh', '2026-04-09 11:40:33');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read','replied') DEFAULT 'unread',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `phone`, `subject`, `message`, `status`, `created_at`) VALUES
(1, 'Chrisss', 'rizkycharisda@gmail.com', '087789554055', 'ss', 'ss', 'read', '2026-04-09 20:24:54'),
(2, 'Chrisss', 'rizkycharisda@gmail.com', '087789554055', 'ss', 'ss', 'replied', '2026-04-09 20:25:05');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int NOT NULL,
  `nama_kategori` varchar(50) NOT NULL,
  `deskripsi` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`, `deskripsi`, `created_at`) VALUES
(1, 'Matic', 'Motor matic otomatis, mudah dikendarai, cocok untuk harian', '2026-04-09 11:40:33'),
(2, 'Trail/Offroad', 'Motor untuk medan offroad, ban kasar, suspensi tinggi', '2026-04-09 11:40:33'),
(3, 'Sport', 'Motor sport performa tinggi, desain aerodinamis', '2026-04-09 11:40:33'),
(4, 'Bebek', 'Motor bebek irit bahan bakar, kopling manual', '2026-04-09 11:40:33'),
(5, 'Skuter', 'Motor skuter modern dengan bagasi luas', '2026-04-09 11:40:33'),
(6, 'Classic', 'Motor klasik retro dengan desain vintage', '2026-04-09 11:40:33');

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log` int NOT NULL,
  `id_user` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL,
  `aktivitas` text NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id_log`, `id_user`, `username`, `role`, `aktivitas`, `waktu`) VALUES
(4, 1, 'chrisss', 'admin', 'Menghapus motor: W175 (Kode: MTR-033)', '2026-04-09 14:29:03'),
(5, 1, 'chrisss', 'admin', 'Menambahkan kategori baru: sss', '2026-04-09 14:47:52'),
(6, 1, 'chrisss', 'admin', 'Menghapus kategori: sss', '2026-04-09 14:48:17'),
(7, 1, 'chrisss', 'admin', 'Menambahkan kategori baru: ss', '2026-04-09 14:49:07'),
(8, 1, 'chrisss', 'admin', 'Menghapus kategori: ss', '2026-04-09 14:49:11'),
(9, 1, 'chrisss', 'admin', 'Menambahkan kategori baru: ss', '2026-04-09 14:49:51'),
(10, 1, 'chrisss', 'admin', 'Menghapus kategori: ss', '2026-04-09 14:49:54'),
(11, 1, 'chrisss', 'admin', 'Menambahkan user baru: peminjam (Role: peminjam)', '2026-04-09 15:05:20'),
(12, 1, 'chrisss', 'admin', 'Menambahkan peminjaman baru untuk user ID: 3', '2026-04-09 15:07:44'),
(13, 1, 'chrisss', 'admin', 'Menyetujui peminjaman dengan ID: 1', '2026-04-09 15:07:50'),
(14, 1, 'chrisss', 'admin', 'Menyetujui peminjaman dengan ID: 1', '2026-04-09 15:11:05'),
(15, 1, 'chrisss', 'admin', 'Memproses pengembalian motor: Aerox 155 | Telat: 0 hari | Denda: Rp 0', '2026-04-09 15:17:05'),
(16, 1, 'admin', 'admin', 'Mengubah status pesan ID 2 menjadi read', '2026-04-09 20:32:39'),
(17, 1, 'admin', 'admin', 'Mengubah status pesan ID 1 menjadi read', '2026-04-09 20:34:44'),
(18, 1, 'admin', 'admin', 'Mengubah status pesan ID 2 menjadi replied', '2026-04-09 20:35:15'),
(19, 3, 'peminjam', 'peminjam', 'Menyetujui peminjaman dengan ID: 3', '2026-04-10 03:10:53'),
(20, 3, 'peminjam', 'peminjam', 'Memproses pengembalian motor: Beat Street | Telat: 0 hari | Denda: Rp 0', '2026-04-10 03:13:12'),
(21, 3, 'peminjam', 'peminjam', 'Menyetujui peminjaman dengan ID: 2', '2026-04-10 03:13:19'),
(22, 3, 'peminjam', 'peminjam', 'Memproses pengembalian motor: Beat Street | Telat: 0 hari | Denda: Rp 0', '2026-04-10 03:13:28');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` int NOT NULL,
  `kode_peminjaman` varchar(20) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `user_id` int NOT NULL,
  `barang_id` int NOT NULL,
  `jumlah` int DEFAULT '1',
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali_rencana` date NOT NULL,
  `tgl_kembali_aktual` date DEFAULT NULL,
  `lama_pinjam` int DEFAULT NULL,
  `status` enum('pending','disetujui','ditolak','dipinjam','selesai','terlambat') DEFAULT 'pending',
  `total_harga` decimal(15,2) DEFAULT NULL,
  `keterangan` text,
  `kondisi` enum('baik','rusak_ringan','rusak_berat') DEFAULT 'baik',
  `denda` decimal(15,2) DEFAULT '0.00',
  `keterangan_pengembalian` text,
  `tgl_pengembalian_input` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `kode_peminjaman`, `slug`, `user_id`, `barang_id`, `jumlah`, `tgl_pinjam`, `tgl_kembali_rencana`, `tgl_kembali_aktual`, `lama_pinjam`, `status`, `total_harga`, `keterangan`, `kondisi`, `denda`, `keterangan_pengembalian`, `tgl_pengembalian_input`, `created_at`, `updated_at`) VALUES
(1, 'PINJ-2026-001', 'pinj-2026-001', 3, 4, 1, '2026-04-09', '2026-04-16', '2026-04-09', 1, 'selesai', 110000.00, 'ssss', 'baik', 0.00, 'ss', '2026-04-09 15:17:05', '2026-04-09 15:07:44', '2026-04-10 08:48:50'),
(2, 'PINJ-2026-90B94', 'pinj-2026-90b94', 3, 2, 1, '2026-04-10', '2026-04-17', '2026-04-10', 1, 'selesai', 60000.00, 'asdasd', 'baik', 0.00, 'asdasd', '2026-04-10 03:13:28', '2026-04-10 03:09:45', '2026-04-10 08:48:50'),
(3, 'PINJ-2026-51B25', 'pinj-2026-51b25', 3, 2, 1, '2026-04-10', '2026-04-17', '2026-04-10', 1, 'selesai', 60000.00, 'asdasd', 'baik', 0.00, 'sss', '2026-04-10 03:13:12', '2026-04-10 03:09:58', '2026-04-10 08:48:50'),
(4, 'PINJ-2026-0047D', 'pinj-2026-0047d', 3, 4, 1, '2026-04-10', '2026-04-15', NULL, 5, 'pending', 550000.00, 'ss', 'baik', 0.00, NULL, NULL, '2026-04-10 07:22:29', '2026-04-10 08:48:50'),
(5, 'PINJ-2026-3CAB3', 'pinj-2026-3cab3', 3, 14, 1, '2026-04-10', '2026-04-10', NULL, 0, 'pending', 0.00, 'ss', 'baik', 0.00, NULL, NULL, '2026-04-10 08:39:30', '2026-04-10 08:48:50'),
(6, 'PINJ-2026-C0450', 'pinj-2026-c0450', 3, 14, 1, '2026-04-10', '2026-04-10', NULL, 0, 'pending', 0.00, 's', 'baik', 0.00, NULL, NULL, '2026-04-10 08:40:07', '2026-04-10 08:48:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','petugas','peminjam') NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `alamat` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `nama_lengkap`, `email`, `no_telp`, `alamat`, `created_at`) VALUES
(1, 'admin', '$2y$10$ajObAfVkTqcgUb0EDYK3euDHu2Sk96uzpYSMk.XF6s5/lD10rzIwG', 'admin', 'admin', '', '', '', '2026-04-09 14:28:17'),
(3, 'peminjam', '$2y$10$wzmH8WPwvRyZmrD8dqUJ.utiegFCBd1hL9O4PGtISs8hVQwOcmUU.', 'peminjam', 'peminjam', NULL, NULL, NULL, '2026-04-09 15:05:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_barang` (`kode_barang`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_kategori` (`nama_kategori`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_peminjaman` (`kode_peminjaman`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `barang_id` (`barang_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
