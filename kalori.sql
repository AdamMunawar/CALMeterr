-- --------------------------------------------------------
-- MySQL Dump Script
-- --------------------------------------------------------

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Set character set for the session
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- Database: `kalori`
-- --------------------------------------------------------

-- --------------------------------------------------------
-- Table structure for table `data_user`
CREATE TABLE `data_user` (
  `id` int NOT NULL,
  `weight` float NOT NULL,
  `height` float NOT NULL,
  `age` int NOT NULL,
  `gender` enum('Laki-Laki','Perempuan') COLLATE utf8mb4_general_ci NOT NULL,
  `activity` enum('Ringan','Sedang','Berat') COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `calorie_need` float DEFAULT NULL,
  `group_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `data_user`
INSERT INTO `data_user` (`id`, `weight`, `height`, `age`, `gender`, `activity`, `username`, `calorie_need`, `group_name`) VALUES
(20, 120, 120, 14, 'Perempuan', 'Ringan', 'aya', 2351.04, 'GOLONGAN B (Kalori Sedang)'),
(21, 45, 161, 20, 'Laki-Laki', 'Ringan', 'aku', 1626.88, 'GOLONGAN A (Kalori Rendah)'),
(22, 74, 175, 20, 'Laki-Laki', 'Sedang', 'tibi', 2736.79, 'GOLONGAN B (Kalori Sedang)'),
(23, 50, 161, 20, 'Laki-Laki', 'Sedang', 'hilman', 2136.72, 'GOLONGAN A (Kalori Rendah)');

-- --------------------------------------------------------
-- Table structure for table `input_makanan`
CREATE TABLE `input_makanan` (
  `id` int NOT NULL,
  `nama_makanan` varchar(100) DEFAULT NULL,
  `porsi` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `input_makanan`
INSERT INTO `input_makanan` (`id`, `nama_makanan`, `porsi`) VALUES
(1, 'Nasi Putih', 2),
(2, 'Teh Hangat', 2),
(3, 'Es Teh Manis', 90);

-- --------------------------------------------------------
-- Table structure for table `makanan`
CREATE TABLE `makanan` (
  `id` int NOT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `porsi` varchar(100) DEFAULT NULL,
  `kalori` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `makanan`
INSERT INTO `makanan` (`id`, `kategori`, `nama`, `porsi`, `kalori`) VALUES
(1, 'makanan_pokok', 'Nasi Putih', '1 centong (150g)', 250),
(2, 'makanan_pokok', 'Nasi Merah', '1 centong (150g)', 220),
(3, 'makanan_pokok', 'Nasi Uduk', '1 centong (150g)', 280),
(4, 'makanan_pokok', 'Nasi Kuning', '1 centong (150g)', 270),
(5, 'makanan_pokok', 'Nasi Kebuli', '1 centong (150g)', 300),
-- Tambahkan data lainnya sesuai kebutuhan
(54, 'tambahan', 'Bakpao', '1 buah', 220);

-- --------------------------------------------------------
-- Table structure for table `makanan_dikonsumsi`
CREATE TABLE `makanan_dikonsumsi` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `nama_makanan` varchar(255) NOT NULL,
  `kalori` int NOT NULL,
  `tanggal_konsumsi` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `makanan_dikonsumsi`
INSERT INTO `makanan_dikonsumsi` (`id`, `username`, `nama_makanan`, `kalori`, `tanggal_konsumsi`) VALUES
(1, 'user1', 'Nasi Goreng', 300, '2024-12-17'),
(2, 'user1', 'Ayam Bakar', 250, '2024-12-17'),
(3, 'user2', 'Soto Ayam', 150, '2024-12-17');

-- Trigger for `makanan_dikonsumsi`
DELIMITER $$
CREATE TRIGGER `set_default_tanggal_konsumsi` BEFORE INSERT ON `makanan_dikonsumsi`
FOR EACH ROW BEGIN
    IF NEW.tanggal_konsumsi IS NULL THEN
        SET NEW.tanggal_konsumsi = CURDATE();
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------
-- Table structure for table `users`
CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_data_filled` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `users`
INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `is_data_filled`) VALUES
(16, 'aya', '$2y$10$XM5uL7tz0OIogygCbyd2/uajaqljjhPAY1sem6S5vniFajtX9BweW', '2024-12-16 08:38:58', 0),
(17, 'aku', '$2y$10$YgqnXkTJlorQ.LxbLEVMW.grreB0EusqmuWPujbbRWeHhoqszJB/O', '2024-12-16 22:54:30', 0),
(18, 'tibi', '$2y$10$VAMzc/6l4T/uTlI3doy6uuDJG/jaE7juGvFpoh4mybs6xQjE8xJPK', '2024-12-17 01:11:29', 0),
(19, 'hilman', '$2y$10$/PvXtkSxcUsmCovsHhVAX.hZ3C.VVvntgN6Brci/yPnZTm4.2Wkw.', '2024-12-17 02:06:36', 0);

-- --------------------------------------------------------
-- Add Primary Keys and Auto Increment
ALTER TABLE `data_user`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `input_makanan`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `makanan`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `makanan_dikonsumsi`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `data_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
ALTER TABLE `input_makanan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `makanan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
ALTER TABLE `makanan_dikonsumsi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
