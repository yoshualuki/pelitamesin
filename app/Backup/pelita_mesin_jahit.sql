-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2025 at 07:27 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pelita_mesin_jahit`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(2, '2025_03_13_170502_initial_database', 1),
(152, '2025_03_13_170952_create_productr_db', 2),
(188, '2025_03_13_170855_create_customer_db', 3),
(192, '2025_03_14_081941_create_admins_table', 3),
(193, '2025_03_14_151837_create_sessions_table', 3),
(194, '2025_03_17_030911_create_cache_table', 3),
(195, '2025_03_17_130923_create_product_db', 3),
(196, '2025_03_21_000319_rename_username_to_users_table', 4),
(207, '2025_03_13_170919_create_order_db', 5),
(208, '2025_03_13_170933_create_order_details_db', 5),
(209, '2025_04_04_125511_create_order_refund', 5),
(210, '2025_04_04_125518_create_order_refund_details', 5),
(211, '2025_04_04_125629_create_payments', 5);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `shipping_cost` decimal(12,2) DEFAULT NULL,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `final_amount` decimal(12,2) NOT NULL,
  `province` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `recipient_name` varchar(255) DEFAULT NULL,
  `recipient_phone` varchar(255) DEFAULT NULL,
  `recipient_email` varchar(255) DEFAULT NULL,
  `courier` varchar(255) DEFAULT NULL,
  `service` varchar(255) DEFAULT NULL,
  `estimated_delivery` int(11) DEFAULT NULL,
  `tracking_number` varchar(255) DEFAULT NULL,
  `weight` int(11) NOT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_status` varchar(255) DEFAULT NULL,
  `payment_code` varchar(255) DEFAULT NULL,
  `payment_date` timestamp NULL DEFAULT NULL,
  `voucher_code` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `cancel_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_amount`, `shipping_cost`, `discount_amount`, `final_amount`, `province`, `city`, `district`, `shipping_address`, `recipient_name`, `recipient_phone`, `recipient_email`, `courier`, `service`, `estimated_delivery`, `tracking_number`, `weight`, `payment_method`, `payment_status`, `payment_code`, `payment_date`, `voucher_code`, `status`, `notes`, `completed_at`, `cancel_reason`, `created_at`, `updated_at`) VALUES
('ORD-1743758570-ovBv', '7e780489-a8e7-4111-9f9b-6beb8f33e391', '4150000.00', '165000.00', '0.00', '4315000.00', 'Jawa Timur', 'Jombang', NULL, 'Address 1', 'Sumber Rejeki Malang', '085151515123', 'sumberrejekimalang@gmail.com', 'jne', 'JTR', NULL, NULL, 80000, NULL, NULL, NULL, NULL, NULL, 'cancelled', NULL, NULL, NULL, '2025-04-04 09:22:50', '2025-04-04 10:12:05'),
('ORD-1743759571-FCrI', '7e780489-a8e7-4111-9f9b-6beb8f33e391', '1650000.00', '240000.00', '0.00', '1890000.00', 'Jawa Timur', 'Surabaya', NULL, 'Address 123', 'Sumber Rejeki Malang', '085158388562', 'sumberrejekimalang@gmail.com', 'jne', 'CTC', NULL, NULL, 8000, 'Virtual Akun bca', NULL, '98758208558643329906952', '2025-04-04 10:20:46', NULL, 'waiting_confirmation', NULL, NULL, NULL, '2025-04-04 09:39:31', '2025-04-04 10:20:46'),
('ORD-1743761829-mjMa', '7e780489-a8e7-4111-9f9b-6beb8f33e391', '1350000.00', '465000.00', '0.00', '1815000.00', 'Kalimantan Selatan', 'Balangan', NULL, 'qweqweqw', 'Sumber Rejeki Malang', '085151515151', 'sumberrejekimalang@gmail.com', 'jne', 'JTR', NULL, NULL, 80000, 'Virtual Akun bca', NULL, '98758827878005734016531', NULL, NULL, 'processing', NULL, NULL, NULL, '2025-04-04 10:17:09', '2025-04-05 16:33:37'),
('ORD-1743860597-VpNW', '7e780489-a8e7-4111-9f9b-6beb8f33e391', '23059500.00', '165000.00', '0.00', '23224500.00', 'Jawa Tengah', 'Semarang', NULL, 'Srinindito 7 no 88', 'Sumber Rejeki Malang', '08954432211', 'sumberrejekimalang@gmail.com', 'jne', 'JTR', NULL, NULL, 22100, 'Virtual Akun bca', NULL, '98758039296998175192647', '2025-04-05 13:44:42', NULL, 'processing', NULL, NULL, NULL, '2025-04-05 13:43:17', '2025-04-05 16:31:09'),
('ORD-1743860813-1hac', '7e780489-a8e7-4111-9f9b-6beb8f33e391', '1650000.00', '1174500.00', '0.00', '2824500.00', 'Kalimantan Selatan', 'Banjarbaru', NULL, 'jajajajaj', 'Sumber Rejeki Malang', '0987161517', 'sumberrejekimalang@gmail.com', 'pos', 'Pos Reguler', NULL, NULL, 8000, NULL, NULL, NULL, NULL, NULL, 'cancelled', NULL, NULL, NULL, '2025-04-05 13:46:53', '2025-04-05 13:49:52'),
('ORD-1743863603-CJu3', '7e780489-a8e7-4111-9f9b-6beb8f33e391', '70650000.00', '210000.00', '0.00', '70860000.00', 'DKI Jakarta', 'Jakarta Selatan', NULL, 'kakkakaka', 'Sumber Rejeki Malang', '08121313113', 'sumberrejekimalang@gmail.com', 'jne', 'JTR', NULL, NULL, 2430000, NULL, NULL, NULL, NULL, NULL, 'waiting_payment', NULL, NULL, NULL, '2025-04-05 14:33:23', '2025-04-05 14:33:23');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `price` decimal(25,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `weight` decimal(25,0) NOT NULL,
  `variant` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variant`)),
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `product_name`, `product_image`, `price`, `quantity`, `weight`, `variant`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'ORD-1743758570-ovBv', '0dfcace7-0557-45a5-861a-57b8bf4f6534', 'Mesin Jahit Industry Typical GC 6158MD Meja Mica Kaki Import Dinamo Servo', 'images/products/1742369169.jpeg', '4150000.00', 1, '80000', NULL, NULL, '2025-04-04 09:22:50', '2025-04-04 09:22:50'),
(2, 'ORD-1743759571-FCrI', '04b697cc-e42a-4408-bdc6-42ee2cbf933f', 'Mesin Jahit Butterfly Portable JH8530A', 'images/products/1742382506.jfif', '1650000.00', 1, '8000', NULL, NULL, '2025-04-04 09:39:31', '2025-04-04 09:39:31'),
(3, 'ORD-1743761829-mjMa', '0ff83786-c5e6-4580-8024-4565dede42eb', 'Mesin Jahit Hitam Classic Singer JA2 Fullset Meja Laci Kaki Pancal', 'images/products/1742369490.jpeg', '1350000.00', 1, '80000', NULL, NULL, '2025-04-04 10:17:09', '2025-04-04 10:17:09'),
(4, 'ORD-1743860597-VpNW', '86dc74dd-004a-485c-9a5f-feb12dcf99d5', 'Oli Singer Botol Kecil', 'images/products/1742231523.png', '8500.00', 7, '300', NULL, NULL, '2025-04-05 13:43:17', '2025-04-05 13:43:17'),
(5, 'ORD-1743860597-VpNW', '0b6fb3cc-e838-4cbf-b2ba-b6ebdad37ea2', 'Mesin Jahit Bordir Komputer Janome MC-550E', 'images/products/1742378419.jpeg', '23000000.00', 1, '20000', NULL, NULL, '2025-04-05 13:43:17', '2025-04-05 13:43:17'),
(6, 'ORD-1743860813-1hac', '04b697cc-e42a-4408-bdc6-42ee2cbf933f', 'Mesin Jahit Butterfly Portable JH8530A', 'images/products/1742382506.jfif', '1650000.00', 1, '8000', NULL, NULL, '2025-04-05 13:46:53', '2025-04-05 13:46:53'),
(7, 'ORD-1743863603-CJu3', '0dfcace7-0557-45a5-861a-57b8bf4f6534', 'Mesin Jahit Industry Typical GC 6158MD Meja Mica Kaki Import Dinamo Servo', 'images/products/1742369169.jpeg', '4150000.00', 10, '80000', NULL, NULL, '2025-04-05 14:33:23', '2025-04-05 14:33:23'),
(8, 'ORD-1743863603-CJu3', '0ff83786-c5e6-4580-8024-4565dede42eb', 'Mesin Jahit Hitam Classic Singer JA2 Fullset Meja Laci Kaki Pancal', 'images/products/1742369490.jpeg', '1350000.00', 20, '80000', NULL, NULL, '2025-04-05 14:33:23', '2025-04-05 14:33:23'),
(9, 'ORD-1743863603-CJu3', '0fa7eae4-87df-49de-9c7b-5422c538441b', 'Mesin Jahit Singer 984 Meja Mica Kaki H', 'images/products/1742370059.jpeg', '2150000.00', 1, '30000', NULL, NULL, '2025-04-05 14:33:23', '2025-04-05 14:33:23');

-- --------------------------------------------------------

--
-- Table structure for table `order_refunds`
--

CREATE TABLE `order_refunds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `refund_id` varchar(255) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `reason` text NOT NULL,
  `admin_notes` text DEFAULT NULL,
  `refund_method` varchar(255) NOT NULL,
  `bank_account` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `receipt_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_refund_details`
--

CREATE TABLE `order_refund_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `refund_id` bigint(20) UNSIGNED NOT NULL,
  `order_detail_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `refund_amount` decimal(12,2) NOT NULL,
  `reason` text NOT NULL,
  `condition` varchar(255) NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `method` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_code` varchar(255) DEFAULT NULL,
  `payment_date` timestamp NULL DEFAULT NULL,
  `receipt_url` varchar(255) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(25,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `weight` decimal(25,0) NOT NULL,
  `description` text DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `stock`, `weight`, `description`, `brand`, `image`, `created_at`, `updated_at`) VALUES
('04b697cc-e42a-4408-bdc6-42ee2cbf933f', 'Mesin Jahit Butterfly Portable JH8530A', '1650000.00', 58, '8000', 'Merek : Butterfly\r\nTipe : JH 8530 A\r\nMade in : China\r\nUkuran Mesin : 45cmx25cmx37cm\r\nKelas Mesin : Mesin Jahit Portable\r\n( Portable tidak menggunakan meja)\r\n\r\nMerk butterfly Terkenal Sejak 1919\r\nKualitas Prima, dengan harga terjangkau\r\nAwet dan mudah perawatannya\r\nSuku Cadang & Aksesoris tambahan mudah\r\n\r\nSpesifikasi :\r\n- 22 fungsi jahitan + 1 fungsi Lobang Kancing\r\n- 4 Langkah Lobang Kancing\r\n- Posisi Spool Bawah\r\n- Pengatur Panjang Jahitan\r\n- Pengatur Lebar Jahitan\r\n- LED Lamp built-in\r\n- Pemasuk Benang Ke Jarum Otomatis\r\n- 70 Watt\r\n- Free Arm ( Untuk Menjahit Bagian Sempit )\r\n\r\n\r\nFungsi : Jahit lurus,Jahit Zig-Zag,Semi obras,Semi neci,Pasang Retsleting Biasa & Resleting Jepang,Lubang dan Pasang kancing, Aplikasi Feston, 1/4 Patchwork,Bordir,Quilting/Menjahit Bebas,Jahit Kelim,Jahit kerut(Lipatan Rok),dll.\r\nIsi didalam dos\r\n- 1x Mesin Jahit Butterfly JH 8530 A\r\n- 1x Foot/Pedal Controller\r\n- 1x Sarung/Cover Mesin Jahit\r\n- 1x Buku Panduan Bahasa Indonesia & Inggris\r\n- 1x Tool Kit Standard (1 Bks Jarum (isi 10),3-5pcs Bobbin/Spool Plastik,3-6pcs Sepatu/Kaki Jahit Standard,Pendedel Benang,dll', 'Butterfly', 'images/products/1742382506.jfif', '2025-03-19 11:08:26', '2025-03-19 11:08:26'),
('0b6fb3cc-e838-4cbf-b2ba-b6ebdad37ea2', 'Mesin Jahit Bordir Komputer Janome MC-550E', '23000000.00', 4, '20000', 'Mesin Bodir Komputer JANOME\r\n\r\nBrand : JANOME (JAPAN)\r\nTipe : Memory Craft MC 550E\r\n\r\nSpecifications :\r\n160 Design Embroidery Built-In\r\n6 Font Alphabet (Huruf dan Angka) untuk Monogramming\r\nUkuran Bidang Bodir yang Luas (200 mm x 360 mm)\r\nGerakan Mesin Dari Kanan ke Kiri memungkinkan Pengguna Membordir Area bahan yang sudah dijahit\r\nTambahan Extension Table Berbidang Lebar untuk kenyamanan pengguna saat mengerjakan project Berbidang Lebar\r\nAuto Return Post-Thread Break memungkinkan Pengguna melanjutkan pekerjaan di titik yang sama saat Benang Habis/Rusak\r\nKecepatan Embroidery 400 - 860 stitches per minute (s.p.m)\r\nOn-Screen Editing untuk merobah ukuran design Embroidery pada Panel LCD TOuch Screen yang tersedia\r\nProses Edit On-Screen meliputi perbesaran / pengecilan, rotasi, penggabungan, pembuatan frame, copy paste, dll.\r\nFlexible stitch Travelling\r\n4 Buah Snap-On Hoop Embroidery\r\nStart - Stop Button untuk pengoperasian tanpa pedal\r\nThread Cutter Auto memotong Benang saat setelah proses Bordir selesai menghasilkan kualitas jahitan bordir yang sempurna\r\n\r\nWhat\'s in the Box ???\r\n1* unit Mesin Embroidery Computerised JANOME MC 550E\r\n1* Power Supply Cable\r\n1* USB Extension Cable\r\n1* Extension Table Memory Craft\r\n1* Dust Cover JANOME\r\n1* Tool Kit Set JANOME MC 500E (Bobbins Case, Bobbins, Embroidery Needles, Thread Holder, Screw driver, dll)\r\n4* Snap-On Embroidery Hoop - RE 36b Hoop (200 mm x 360 mm) - SQ20b Hoop (200mm x 200mm) - RE20b Hoop (110 mm x 200 mm) dan SQ14b Hoop (110mm x 110mm)', 'Janome', 'images/products/1742378419.jpeg', '2025-03-19 07:05:27', '2025-03-27 08:03:18'),
('0dfcace7-0557-45a5-861a-57b8bf4f6534', 'Mesin Jahit Industry Typical GC 6158MD Meja Mica Kaki Import Dinamo Servo', '4150000.00', 38, '80000', 'Typical Mesin Jahit Servo GC 6158MD Otomatis New Model dari GC628D\r\n\r\nTYPICAL GC 6158MD - Mesin Jahit Jarum 1 Industrial Servo Motor\r\n\r\nMesin Jahit Jarum 1 Industrial Dengan Dinamo Servo\r\n\r\nMesin Jahit dengan tingkat efisiensi tinggi, listrik rendah, kemampuan tangguh dalam menjahit, dapat diatur kecepatannya\r\nSangat cocok bagi Penjahit Professional dan Pengusaha Butik / Garmen\r\n\r\nMerk : TYPICAL\r\nTipe : GC 6158MD\r\nKelas Mesin : Industrial\r\n(Mesin Jahit Industrial harus menggunakan Meja dan Kaki dalam pengoperasiannya)\r\n\r\nSpecifications :\r\n- Berfungsi Menjahit Lurus dengan Jarum 1\r\n- Kecepatan Mesin dapat diatur\r\n- Maksimal Ketebalan Bahan 10 mm\r\n- Lebar Jahitan dapat diatur hingga 5.0 mm\r\n- Pengangkat Sepatu dapat diangkat hingga 5,0 mm (tangan) - 13 mm (lutut)\r\n- Sistem Pelumasan Auto\r\n- Sudah Tersedia Lampu LED\r\n- Pengaturan Posisi Jarum ( up and down needle )\r\n- Layar Display Lebar\r\n- Menggunakan Teknologi Servo Motor (Listrik bervariasi tergantung penggunaan anda)\r\n- Posisi Standby Listrik 4 Watt\r\n\r\nDEKORATIF DAN FUNGSI JAHITAN :\r\n- JAHIT LURUS JARUM 1\r\n- JAHIT LILIT - LIPAT - KELIM\r\n- JAHIT RETSLETING BIASA MAUPUN RETSLETING JEPANG\r\n- JAHIT LUBANG KANCING\r\n- JAHIT KERUT ( LIPATAN ROK )\r\n\r\nPaket Penjualan :\r\n- 1x Mesin Jahit TYPICAL GC 6158MD\r\n- 1x Tool Kit Standard (obeng, Sarung Mesin, dan Oil)\r\n- 1x Akesoris Pendukung (Tiang Benang, Kawat Dinamo, dll)\r\n- 1x Meja import\r\n- 1x Kaki import\r\n- 1x Dinamo Servo Included ( Siap Jahit )', 'Typical', 'images/products/1742369169.jpeg', '2025-03-19 07:26:09', '2025-03-23 16:54:21'),
('0fa7eae4-87df-49de-9c7b-5422c538441b', 'Mesin Jahit Singer 984 Meja Mica Kaki H', '2150000.00', 77, '30000', 'Merek : SINGER (USA)\r\nTipe : 984 Series\r\nMade in : Taiwan\r\nKelas Mesin : Semi Portable Multifungsi\r\n(Tidak membutuhkan meja dan kaki untuk digunakan, namun disarankan untuk menggunakan meja dan kaki mesin jahit atau box mesin jahit dalam pemakaiannya)\r\n\r\nSpesifikasi :\r\n- 24 Fungsi Pola Jahitan\r\n- 4 Langkah/Step Membuat Lobang Kancing\r\n- Front Load Bobbins/Spool Bawah\r\n- Adjustable Stitch Length/Pengatur Panjang Jahitan hingga 4.0 mm\r\n- Adjustable Width Length/Pengatur Lebar Jahitan hingga 5.0 mm\r\n- Adjustable Presser Foot/ Tekanan Sepatu Dapat Diatur Menyesuaikan Ketebalan Bahan\r\n- Drop Feed Dog (memudahkan pengguna untuk quilting dan bodir)\r\n- Built-In Lampu LED\r\n-termasuk meja mica anti gores dan kaki Z crem (cat powder coating)\r\n\r\nWhat\'s in the box ?\r\n- 1x Unit Mesin Jahit SINGER 984\r\n- 1x Built-in 60 Watt Motor SINGER\r\n- 1x Pedal / Foot Controller & Kabel Power\r\n- 1x Booklet Instruction Manual (Bahasa Indonesia)\r\n- 1x Tool Kit Set provided by SINGER (Sepatu Kelim, Sepatu Lobang Kancing, Sepatu Jahit Ritsleting, Oil, Jarum Set, Obeng, Pendedel, Tiang Benang Tambahan untuk Jarum Ganda)\r\n- 1x Label Garansi Resmi SINGER Indonesia 1 Tahun\r\n- Meja Papan Mica Antigores\r\n- Kaki Model H', 'Singer', 'images/products/1742370059.jpeg', '2025-03-19 07:40:59', '2025-03-19 07:40:59'),
('0ff83786-c5e6-4580-8024-4565dede42eb', 'Mesin Jahit Hitam Classic Singer JA2 Fullset Meja Laci Kaki Pancal', '1350000.00', 75, '80000', 'SINGER 15 CLASS (Tradisional)\r\nFull Set A\r\nMesin Jahit Lurus Jarum Satu\r\n\r\nMerk : SINGER\r\nTipe : 15 Class\r\n\r\nSpecs :\r\n- Mesin Jahit Tradisional\r\n- Body dan Sparepart Full Besi\r\n- Speed 1,500 s.p.m\r\n- Fungsi Menjahit Lurus\r\n\r\nPaket Penjualan Full Set A terdiri dari :\r\n- 1 unit Mesin Jahit SINGER 15 CL\r\n- 1 set Meja papan original Singer Laci\r\n- 1 set Kaki Genjot atau Kayuh atau Treadle Stand SINGER\r\n- 1 Tool Kit Standard Singer 15 CL (4 bobbins, 1 Jarum Singer, 1 Sepatu Kelim, 1 Tube Oil, dll.)', 'Singer', 'images/products/1742369490.jpeg', '2025-03-19 07:31:30', '2025-03-19 07:31:30'),
('12d323b7-91ba-4cd2-bf7d-e588e1e0c1ba', 'Mesin Jahit Portable Janome NS311A', '1900000.00', 22, '8000', 'Spesifikasi Janome NS311A\r\n- 12 Fungsi Jahitan Built-In + 1 Fungsi Lobang Kancing\r\n- 4 Langkah Membuat Lobang Kancing\r\n- Frame Body Aluminium\r\n- Sistem Spool/Bobbins Bawah\r\n- Built-In Bulb Lamp\r\n\r\n\r\nISI DUS :\r\n- 1 UNIT JANOME NS 311A\r\n- 1 PEDAL UNTUK MENJAHIT\r\n- COVER / SARUNG MESIN JAHIT\r\n- 1 TAHUN GARANSI RESMI\r\n- MANUAL BOOK\r\n- TOOL KIT ( JARUM 1 BUNGUKS , SPOOL , SEPATU2 , DLL. )', 'Janome', 'images/products/1742372439.jfif', '2025-03-19 08:20:39', '2025-03-19 08:20:39'),
('1710b726-5e46-44b3-b940-e9c05db35765', 'Needle Bar Lever Atau Jerapah NP7A', '68000.00', 77, '400', 'Needle Bar lever atau Jerapah untuk mesin jahit karung NP7A', 'SPAREPART', 'images/products/1742231786.jpg', '2025-03-17 10:16:26', '2025-03-18 07:07:51'),
('1de2574a-07a9-492f-bb29-e9c245fb471d', 'Mesin Jahit Hitam Singer Classic JA2 Head Only', '825000.00', 70, '12000', 'SINGER 15 CLASS (Tradisional)\r\nFull Set\r\nMesin Jahit Lurus Jarum Satu\r\n\r\nMerk : SINGER\r\nTipe : 15 Class\r\n\r\nSpecs :\r\n- Mesin Jahit Tradisional\r\n- Body dan Sparepart Full Besi\r\n- Speed 1,500 s.p.m\r\n- Fungsi Menjahit Lurus', 'Singer', 'images/products/1742383731.jpg', '2025-03-19 11:28:51', '2025-03-19 11:32:07'),
('24108211-5185-4cac-92e7-edf9576e3701', 'Mesin Jahit Portable Brother JK17B', '1899000.00', 38, '8000', 'Merek : BROTHER (JAPAN)\r\nTipe : JK-17B\r\nMade in : Vietnam\r\nKelas Mesin : Mesin Jahit Portable\r\n\r\nBROTHER INDONESIA mempersembahkan Brother JK-17B, mesin jahit yang memiliki fitur yang lengkap dan user-friendly untuk pemula. Dengan warnanya yang colorful, cocok sebagai mood booster yang membuat kamu lebih semangat dan kreatif! Body yang compact namun ringan menjadikan mesin ini sangat portable untuk menjahit dimana saja dan kapan saja. Dengan gigi 6 baris, mesin ini mampu menjahit bahan tipis sampai tebal sekalipun. Selalu yang terdepan dalam menjamin kualitas produknya, Brother memberikan 3 tahun garansi service untuk seluruh produknya.\r\n\r\nSpesifikasi\r\n- 17 Fungsi jahitan\r\n- Lubang kancing 4 langkah dengan pengaturan kerapatan jahitan lubang kancing\r\n- Pemasuk benang otomatis (F.A.S.T and simple threading)\r\n- 2 titik pemotong benang; Atas (standard) dan Bawah (untuk benang bawah)\r\n- Sistem bobbin atas (proses pemasangan bobbin lebih mudah)\r\n- Lampu LED berwarna kebiruan\r\n- Gigi 6 baris (sempurna untuk menjahit tipis & tebal)\r\n- Jarak jahitan s/d 4 mm\r\n- Lebar zigzag s/d 5 mm\r\n- Tombol maju mundur (untuk kunci jahitan)\r\n- Free arm (memudahkan menjahit area sempit)\r\n- Suara mesin halus (getaran rendah)\r\n- Hemat energi (50 Watt)\r\n- 750 jahitan per menit\r\n\r\nPaket Penjualan\r\n- 1 x Mesin Jahit BROTHER JK-17B\r\n- 1 x Pedal Injakan + adaptor\r\n- 1 x Lembar Panduan Bahasa Inggris & Indonesia\r\n- 1 x Kartu Garansi Resmi BROTHER Indonesia 3 Tahun\r\n- 1 x Tool Kit Standard (1 bks Jarum Organ isi 3 batang, 3 pcs bobbin, Sepatu Lubang Kancing 4 Langkah, Sepatu Pasang Kancing, Sepatu Resleting, Plat Penutup Bordir, Obeng)', 'Brother', 'images/products/1742368013.jpeg', '2025-03-19 06:59:49', '2025-03-19 07:06:53'),
('26f8ff7f-2fbe-4f52-8f46-40c5f165edf3', 'Mesin Obras Neci Portable Butterfly HD64', '2840000.00', 17, '15000', 'Mesin Obras Neci Portable BUTTERFLY HD864 HD 864 Multifungsi ORI ~ LEEN Merek : BUTTERFLY Tipe : HD 864 Kelas Mesin : Mesin Jahit Obras Portable (Mesin Jahit Obras Portable tidak menggunakan meja) Deskripsi : Mesin jahit Obras yang sangat tangguh, Pengoperasian mudah dan Ringkas, Mudah dibawa kemana-mana, Cocok digunakan untuk Pemula yang baru belajar jahit maupun Mahir Yang Ingin Membuat Butik Busana, Dikhususkan untuk anda yang ingin dengan Mesin Jahit yang awet dan bertenaga. Rangka yang terbuat dari besi membuat Mesin anda menjadi kokoh dan awet, Suara Mesin Halus. Spesifikasi : - Dapat berfungsi sbg Obras Benang 2, 3 dan 4 - Dapat berfungsi juga sebagai Neci Roll Som - Free Arm (Ruang Jahit lebih leluasa utk mengerjakan bagian yang sempit) - Rangka Metal - Built-In LED Lamp (2 pcs) - Heavy Duty Series (Dapat mengobras bahan-bahan yang tebal seperti Bahan Denim) - Listrik 110 Watt - Color Coded Threading Diagram (Membantu memudahkan jalur memasukkan benang ke jarum) - Adjustable Feed Dog (Untuk membuat hasil jahitan yang berkerut) - Speed 1,300 Jahitan Per Menit Fungsi : Jahit Tepi Obras Benang 2/3/4, Jahit Neci / Roll Som, Neci Menggunakan Senar, FlatLock, Dsb. What\'s in the box?? - Unit Mesin BUTTERFLY HD864 - Foot/Pedal Controller - Waste Bin (Tempat Sampah Hasil Potongan Obras) - Buku Instruction Manual (Bahasa Indonesia) - Tool Kit (Obeng, Jarum, Pinset, Brush, Pisau Spare, dll)', 'Butterfly', 'images/products/1742233459.jpeg', '2025-03-17 10:44:19', '2025-03-17 10:44:19'),
('28dca9a0-41a7-44bd-ada7-90f985a219c4', 'Mesin Jahit Hitam Classic Butterfly JA1 Fullset', '1230000.00', 77, '60000', 'Merek : Butterfly Original \r\nTipe : JA1-1\r\nMade in : China\r\nKelas Mesin : Mesin Jahit Jarum 1 Traditional\r\n\r\nMesin jahit Jarum 1 Traditional yang sangat tangguh, Pengoperasian mudah, Cocok digunakan untuk Pemula yang baru belajar jahit maupun Mahir Yang Ingin Membuat Butik Rumahan. Body dan Rangka Besi\r\n\r\nSpesifikasi :\r\n- Mesin Jahit Tradisional\r\n- Body dan Sparepart Full Besi\r\n- Speed 1,500 s.p.m \r\n- Fungsi Menjahit Lurus\r\n\r\nWhat\'s in the box??\r\n- 1 unit Mesin Jahit BUTTERFLY JA 1-1\r\n-  1 unit Meja laci Kaki Pancal\r\n- 1 Tool Kit Standard BUTTERFLY JA-1 (4 bobbins, 1 Jarum Singer, 1 Sepatu Kelim, 1 Tube Oil, dll.)', 'Butterfly', 'images/products/1742367210.jpeg', '2025-03-19 06:53:30', '2025-03-19 07:31:55'),
('2923039a-8d96-489d-893c-c8fa9236106c', 'Mesin Jahit Portable Juki HZL-8370AT', '2700000.00', 17, '8000', 'Merk : JUKI\r\nTipe : HZL-8370AT\r\nKelas Mesin : Portable Multifungsi\r\n\r\nSpesifikasi dan Fitur :\r\n* 21 Pola Jahitan Built-In + 1 Fungsi Lubang Kancing\r\n* Auto 4 Step Sistem Lobang Kancing\r\n* Sistem Bobbins Oscillating / Bobbin Besi\r\n* Auto Needle Threader/Pemasuk Benang ke Jarum\r\n* Pengatur Panjang Jahitan hingga 4.0 mm\r\n* Pengatur Lebar Jahitan hingga 5.0 mm\r\n* Presser Foot Lifter extra tinggi hingga 9.0 mm\r\n* In-Built LED Light\r\n* Free Arm untuk Menjahit Bahan dengan Area Sempit\r\n* Pengatur Tekanan Sepatu disesuaikan dengan kebutuhan Anda\r\n* Reverse Lever / Tuas Jahitan Mundur untuk Mengunci Jahitan\r\n* Heavy Duty Metal Frame, Heavy Duty Motor\r\n* Listrik 75 Watt\r\n* Pengatur Tegangan Benang Auto\r\n* Penggulung Bobbins/Spool Auto\r\n* Sistem Presser Foot Snap-On\r\n\r\nWhat\'s in the Box ???\r\n* 1x unit Mesin Jahit JUKI HZL-8370AT\r\n* 1x set Pedal / Foot Controller dan Cable Connector\r\n* 1x Meja Tambahan JUKI HZL 8370AT\r\n* 1x Booklet Instructions Manual\r\n* 1x DVD Instructions Manual\r\n* 1x Tool Kit Standard JUKI HZL-8370AT (Sepatu Lobang Kancing, Sepatu Pasang Kancing, Sepatu Zipper, Quilting Guide, 3*Bobbins/Spool, Spool Pin, Spool Cap, Obeng)', 'Juki', 'images/products/1742308414.jpeg', '2025-03-18 07:33:34', '2025-03-18 07:33:34'),
('2a4b42c4-a723-4fb9-918d-17fa15c005ba', 'Baut 92 Untuk Mesin Karung NP7a', '3000.00', 431, '200', 'Baut 92 untuk mesin karung', 'SPAREPART', 'images/products/1742229231.jfif', '2025-03-17 09:33:51', '2025-03-19 08:04:50'),
('2b392d8b-f046-4d3e-a827-14a7ca5cc352', 'Mesin Jahit Bordir Komputer Brother NV880-E', '20500000.00', 4, '16000', 'Mesin Bordir Komputer BROTHER INNOVIS NV 880 E / NV880E Portable\r\n\r\nMerk : BROTHER\r\nMade In : Vietnam\r\nTipe : Innov-is NV-880E\r\nGaransi : Resmi 3 Tahun by Brother Indonesia\r\nKelas Mesin : Embroidery Machine\r\n*dapat langsung dioperasikan tanpa tambahan meja dan kaki\r\n\r\nMesin Bordir BROTHER NV 880E adalah unit mesin lineup terbaru dari Brother Indonesia! Brother NV880E dilengkapi dengan inovasi terbaru: Color-sorting feature, dimana warna benang dapat diatur secara otomatis oleh mesin jika sedang membordir dalam quantity yang banyak dan In-hoop feature, dimana posisi desain di dalam bidangan dapat dilihat secara langsung di Layar LCD.\r\n\r\nSelain itu, mesin ini juga memiliki bidangan yang sangat luas, ditambah kecepatan menjahit 30% dari mesin bordir pada umumnya (Speed extra tinggi 850 vs. 650 SPM). Penggunaan menjadi super ringkas dengan kemudahan konektivitas (USB dan Kabel WLAN).\r\n\r\nSpesifikasi\r\n- 258 Desain bordir\r\n- 13 Jenis font dan angka\r\n- Kecepatan bordir hingga 850 SPM (Jahitan per Menit)\r\n- Area bordir besar, hingga 260mm x 160mm\r\n- Bisa mengedit langsung pada 4,85\" LCD Panel Full Color\r\n- USB Port untuk transfer file design\r\n- Konektivitas dengan Kabel WLAN, bisa dihubungkan dengan komputer\r\n- LED pointer membantu untuk menunjukkan posisi marking bordir\r\n- Tombol Start/Stop (menjahit otomatis)\r\n- Tombol Pengangkat Sepatu (tidak perlu memutar handwheel)\r\n- Pemasuk benang otomatis (sistem F.A.S.T yang efisien dan ringkas)\r\n- Sistem bobbin atas\r\n\r\nPaket Penjualan\r\n1x BROTHER NV 880E\r\n1x Kabel Adaptor + Pedal\r\n1x Buku Manual\r\n1x Buku Design Bordir\r\n1x Sepatu Bordir dengan LED Pointer\r\n1x Bidangan 260mm x 160mm\r\n1x Bidangan 150mm x 150mm\r\n1x Bidangan 100mm x 100mm\r\n12x Benang Bordir\r\n1x Tool Kit (4x Bobbin, Spool Cap + Pin, Kuas, Pendedel, 3x Obeng, Jarum Bordir dan Gunting Bordir)\r\n1x Sarung Penutup Mesin\r\n1x Kartu Garansi Resmi 3 Tahun Service by Brother Indonesia', 'Brother', 'images/products/1742367700.jpeg', '2025-03-19 07:01:40', '2025-03-19 07:32:44'),
('2c125e37-3de7-4f1b-a261-1eff0ba68dc3', 'Dinamo Singer 100W', '140000.00', 237, '1200', 'Dinamo Singer 100WATT', 'Singer', 'images/products/1742228573.jpg', '2025-03-17 09:22:53', '2025-03-19 07:35:04'),
('2d32651c-7276-42fd-b7e8-a72a51343114', 'Rotary HOOK/Sarangan Mesin Highspeed', '75000.00', 211, '500', 'Sarangan untuk mesin jahit industri', 'SPAREPART', 'images/products/1742379078.jpg', '2025-03-19 10:10:49', '2025-03-19 10:11:18'),
('2f949cf1-1adc-457b-a7ea-84c564ddca97', 'Sepul/Spool/Bobbin Mesin Jahit Highspeed Industry', '1250.00', 475, '200', 'Sepul untuk mesin jahit industri\r\nHarga tertera untuk per 1pcs', 'SPAREPART', 'images/products/1742379372.jfif', '2025-03-19 10:16:12', '2025-03-19 10:17:33'),
('2ff9bff0-0253-4dd9-bf19-d5aad11fc2b9', 'Baut 7 Untuk Mesin Karung NP7A', '3500.00', 572, '200', 'Baut 7 untuk mesin karung NP7A', 'SPAREPART', 'images/products/1742228957.jpg', '2025-03-17 09:29:17', '2025-03-18 07:14:27'),
('341b6a64-ea3b-4f76-b685-dc5a0fb7955a', 'Sarangan Mesin Jahit Hitam Classic', '15000.00', 75, '800', 'Sarangan Untuk mesin jahit hitam classic', 'SPAREPART', 'images/products/1742378634.jpg', '2025-03-19 10:03:54', '2025-03-19 10:03:54'),
('35dd903a-d40c-4586-9354-209e0ff62959', 'Gunting Pemotong Benang', '4000.00', 172, '600', 'Gunting untuk memotong benang jahit', 'AKSESORIS', 'images/products/1742379664.jfif', '2025-03-19 10:21:04', '2025-03-19 10:23:18'),
('39eecfbe-793e-4c4c-8e81-f6674fddae16', 'Mesin Jahit Singer Portable 3223G', '2450000.00', 3, '8000', 'Mesin Jahit Singer 3223G\r\n23 pola jahitan\r\n4 langkah pelubang kancing\r\nPemotong benang\r\nPemutar bobbin otomatis\r\nTombol pengatur panjang jahitan\r\nTombol pengatur pola jahitan\r\nTombol pengatur tekanan benang atas\r\nTombol jahitan mundur', 'Singer', 'images/products/1742377772.jpeg', '2025-03-19 09:49:32', '2025-03-19 09:49:32'),
('3a071816-9b87-4656-8900-913d0c1c5762', 'Mesin Jahit Portable Singer 3232', '2600000.00', 47, '8000', 'Merek : SINGER (USA)\r\nTipe : Simple 3232\r\nMade in : Taiwan\r\nWarna : Ungu\r\nKelas Mesin : Mesin Jahit Portable Portable\r\n(Mesin Jahit Portable tidak menggunakan meja)\r\n\r\nMesin Jahit Singer Simple dirancang dan didesain Sederhana. Menggunakan Teknologi SSS ( Simple Sewing System ) Membuat anda Mudah dan Ringkas dalam segala pekerjaan Menjahit anda. Cocok Digunakan untuk Pemula , Pelajar, Butik, Designer Sekalipun.\r\nRangka yang terbuat dari besi membuat mesin anda menjadi kokoh dan awet.\r\n\r\nSpesifikasi :\r\n- 32 Fungsi Jahitan\r\n- 1 Langkah/Step Membuat Lobang Kancing\r\n- Pemasang Benang Otomatis\r\n- Adjustable Stitch Length/Pengatur Panjang Jahitan', 'Singer', 'images/products/1742382937.jfif', '2025-03-19 11:15:37', '2025-03-19 11:15:37'),
('3a64b676-ef33-48cd-9108-01546107df98', 'Mesin Jahit Hitam Classic Butterfly JA1 Head Only', '785000.00', 37, '12000', 'Butrefly JA1 (Tradisional)\r\nMesin Jahit Lurus Jarum Satu\r\n\r\nMerk : Butterfly\r\nTipe : JA1\r\n\r\nSpecs :\r\n- Mesin Jahit Tradisional\r\n- Body dan Sparepart Full Besi\r\n- Speed 1,500 s.p.m\r\n- Fungsi Menjahit Lurus', 'Butterfly', 'images/products/1742383838.jfif', '2025-03-19 11:30:38', '2025-03-19 11:30:38'),
('3bdea6cf-a958-49d2-a85b-11d206fa7274', 'Meja Lubang Mica + Kaki Import', '260000.00', 66, '20000', 'Meja lubang mica + kaki import untuk mesin hitam atau semi portable', 'AKSESORIS', 'images/products/1742448713.jpeg', '2025-03-20 05:31:53', '2025-03-20 05:31:53'),
('3d3136d3-21f7-4ffa-b213-1ca2cf0033e3', 'Mesin Jahit Portable Singer 3342', '2700000.00', 9, '8000', 'Merek : SINGER (USA)\r\nTipe : 3342 Fashion Mate (New Model 2016)\r\nMade in : Taiwan\r\nKelas Mesin : Mesin Jahit Portable\r\n(Mesin Jahit Portable tidak menggunakan meja)\r\n\r\nMesin Jahit Model Terbaru dari Singer Tipe Fashion Mate, Pengoperasian mudah dan Ringkas di desain Khusus untuk anda para Fashionista, Mudah dibawa kemana-mana, Cocok digunakan untuk pemula yang baru belajar jahit maupun mahir.\r\n\r\nSpesifikasi :\r\n> 32 Pola Jahitan Built-In (6 Basic, 6 Stretch, 19 Dekoratif dan 1 fungsi Auto 1-Step Lobang Kancing)\r\n> Sistem Lobang Kancing Auto 1 Langkah\r\n> Auto Needle Threader Built-In (Auto Pemasuk Benang ke Jarum)\r\n> Sistem Bobbins Atas (Top Drop-In Bobbins)\r\n> Penggulung Benang Auto - Auto Bobbin Winding\r\n> Auto Thread Tension - Pengatur Tegangan Benang\r\n> STAYBRIGHT LED Light - In Built (Lampu LED Putih)\r\n> Adjustable Stitch Length (Pengatur Panjang Jahitan hingga 4.0 mm)\r\n> Adjustable Stitch Width (Pengatur Lebar Jahitan hingga 5.0 mm)\r\n> Free Arm Sewing - Memudahkan Menjahit Bahan Silindris atau Bidang Sempit\r\n> Heavy Duty Metal Frame\r\n> Reverse Lever Available (Tuas Menjahit Mundur Mengunci Jahitan)\r\n> Handle Mesin pada Bagian Belakang Mesin\r\n> Hemat Listrik 85 Watt\r\n\r\nWhat\'s in the box??\r\n- 1x Mesin Jahit SINGER 3342 Fashion Mate\r\n- 1x Foot/Pedal Controller\r\n- 1x Buku Panduan Bahasa Indonesia & Inggris\r\n- 1x Kartu Garansi Resmi Singer Indonesia 1 Tahun\r\n- 1 x Tool Kit Standard 3342 Fashionmate (Sepatu Lobang Kancing, Sepatu Pasang Kancing, Sepatu Zipper Foot, 3x bobbins, Darning Plate, Quilting Guide, Brush dan Seam Ripper/Pendedel, Spool Pin, dan Spool Cap', 'Singer', 'images/products/1742367369.jpeg', '2025-03-19 06:56:09', '2025-03-19 06:56:09'),
('4428fac3-ad09-4597-8ba4-7a2f7da2938b', 'Mesin Jahit Portable Janome ST24', '3700000.00', 7, '9000', 'Janome St24 ST 24 Mesin Jahit Portable Heavy Duty - Biru Tua Putih Type : ST-24 HEAVY DUTY watt : 60, Merk : Japan, perakitan : THAILAND volt : 220~240 ~ 50HZ Spesifikasinya Dibawah Ini FEATUR-FEATUR : -Mempunyai 25 macam jahitan -Satu langkah lubang kancing otomatis -Pemasuk benang kemata jarum auto -Lebar zig-zag bisa di atur sesuai keinginan -Minim Getaran -Lampu LED -Bisa jahit bahan kaos dan jeans -Drop feed dog untuk semi bordir atau pasang kancing -Tiang benang bisa naik turun -Free arm (Jahitan Bebas Lengan) -Bisa feston miring -Ada hard cover -Cocok untuk pemula dan mahir -Bisa semi obras -Bisa semi bordir manual -Bisa quilting dan patchwork -Spare-part ready dan umum Accesories Bawaan Mesin Janome ST24 Sepatu Walking , Sepatu Darning, Sepatu Obras Semi, Sepatu 1/4 #34; Pacthwork, Sepatu Som, Sepatu Satin, Sepatu Lubang Kancing Otomatis, Sepatu Retsleting, Sepatu Normal/Zik Zak (Set Dimesin), Sepatu Ultra Glide/Teflon Orginal, Jarum Organ No.16/18, Jarum organ nomor 14, Pendedel, Bobbin x3, Obeng Kecil x1Manual Buku, Hard Cover, Pedal Dinamo\r\nLihat Selengkapnya', 'Janome', 'images/products/1742372084.jpg', '2025-03-19 08:14:44', '2025-03-19 08:14:44'),
('458615df-1e31-4548-9afb-7beb84e24451', 'Mesin Neci Typical Industri GN793 Head Only', '4400000.00', 8, '45000', 'Mesin Neci TYPICAL GN793 (Industrial Garmen)\r\n\r\nMesin Neci Benang 3 dengan 2 Jarum\r\n\r\nMesin Neci ini sangat nyaman digunakan dan menopang industri pakaian jadi skala kecil hingga besar, dapat menjahit bahan apapun juga (tipis hingga tebal). Sangat cocok bagi penjahit professional dan pengusaha butik / garmen. Maupun industri kerudung / jilbab.\r\n\r\n\r\nMerk : TYPICAL (Original)\r\nTipe : GN 793\r\nMade in : China\r\nKelas Mesin : Mesin Neci Industrial Garmen\r\n\r\nSpecs :\r\n- Berfungsi Untuk Membuat Jahitan Tepi Tipis (Neci) dengan Jahitan Benang 3\r\n- Dapat difungsikan sebagai Obras Benang 3\r\n- Kecepatan Maksimal 5,500 spm\r\n- Maksimal Ketebalan Bahan 13 mm\r\n- Panjang Jahitan dapat diatur hingga 5.0 mm\r\n- Pengangkat Sepatu dapat diangkat hingga 5,0 mm (tangan) - 13 mm (lutut)\r\n- Sistem Pelumasan Auto', 'Typical', 'images/products/1742381978.jpg', '2025-03-19 10:59:38', '2025-03-19 10:59:38'),
('469b8645-91e5-4be8-bd9c-0dd20cf29dbe', 'Mesin Jahit Hitam Classic Butterfly JA2 Fullset', '1260000.00', 76, '60000', 'Merek : Butterfly Original \r\nTipe : JA2\r\nMade in : China\r\nKelas Mesin : Mesin Jahit Jarum 1 Traditional\r\n\r\nMesin jahit Jarum 1 Traditional yang sangat tangguh, Pengoperasian mudah, Cocok digunakan untuk Pemula yang baru belajar jahit maupun Mahir Yang Ingin Membuat Butik Rumahan. Body dan Rangka Besi\r\n\r\nSpesifikasi :\r\n- Mesin Jahit Tradisional\r\n- Body dan Sparepart Full Besi\r\n- Speed 1,500 s.p.m \r\n- Fungsi Menjahit Lurus maju mundur\r\n\r\nWhat\'s in the box??\r\n- 1 unit Mesin Jahit BUTTERFLY JA 2\r\n-  1 unit Meja laci Kaki Pancal\r\n- 1 Tool Kit Standard BUTTERFLY JA-2 (4 bobbins, 1 Jarum Singer, 1 Sepatu Kelim, 1 Tube Oil, dll.)', 'Butterfly', 'images/products/1742384100.jpeg', '2025-03-19 11:35:00', '2025-03-19 11:35:00'),
('4891ee5a-89d3-421f-b0ab-6a96468d7a9f', 'Mesin Jahit Singer 984 Semi Portable', '1950000.00', 61, '12000', 'Merk : SINGER\r\nTipe : 984\r\nKelas Mesin : Semi - Portable Multifungsi*\r\n*Semi - Portable Multifungsi\r\n(tidak membutuhkan meja dan kaki untuk digunakan, namun disarankan untuk menggunakan meja dan kaki mesin jahit atau box mesin jahit dalam pemakaiannya)\r\n\r\nSpecifications :\r\n- 24 Fungsi Jahitan\r\n- 4 Langkah/Step Membuat Lobang Kancing\r\n- Front Load Bobbins/Spool Bawah\r\n- Adjustable Stitch Length/Pengatur Panjang Jahitan hingga 4.0 mm\r\n- Adjustable Width Length/Pengatur Lebar Jahitan hingga 5.0 mm\r\n- Adjustable Presser Foot/ Tekanan Sepatu Dapat Diatur Menyesuaikan Ketebalan Bahan\r\n- Drop Feed Dog (memudahkan pengguna untuk quilting dan bodir)\r\n- Built-In LED Light\r\n\r\nWhat\'s in the box ???\r\n- 1x Unit Mesin Jahit SINGER 984\r\n- 1x In-Built 60 Watt Motor SINGER\r\n- 1x Pedal / Foot Controller & Kabel Daya\r\n- 1x Booklet Instruction Manual (Bahasa Indonesia)\r\n- 1x Tool Kit Set provided by SINGER (Sepatu Kelim, Sepatu Lobang Kancing, Sepatu Jahit Ritsleting, Oil, Jarum Set, Obeng, Pendedel, Tiang Benang Tambahan untuk Jarum Ganda)', 'Singer', 'images/products/1742382834.png', '2025-03-19 11:13:54', '2025-03-19 11:13:54'),
('4907eb14-586e-423a-98d0-cbc787ee939b', 'Handle atau Pegangan Mesin Jahit Karung NP7A', '42000.00', 343, '700', 'Handle mesin jahit karung', 'SPAREPART', 'images/products/1742276734.jpg', '2025-03-17 22:45:34', '2025-03-17 22:45:34'),
('4b887b77-750a-4dc7-9d58-f2f140ba7dbe', 'Baut Cincin atau Roller NP7A', '14500.00', 209, '200', 'Roller NP7A', 'SPAREPART', 'images/products/1742228852.jfif', '2025-03-17 09:27:32', '2025-03-18 07:14:58'),
('4f5f1045-9849-47a7-93ba-313e26cfa9c8', 'Jarum Singer untuk Mesin Jahit Portable/Classic', '6250.00', 751, '200', 'Jarum jahit mesin portable/classic', 'Singer', 'images/products/1742276444.jfif', '2025-03-17 22:40:45', '2025-03-18 07:20:44'),
('57d9b71a-ddc2-4ac8-b5a2-399ced7d8792', 'Sekoci Mesin Jahit Hitam Portable', '12500.00', 288, '300', 'Sekoci untuk mesin jahit portable', 'SPAREPART', 'images/products/1742378847.jpg', '2025-03-19 10:07:27', '2025-03-19 10:07:54'),
('5be73eff-b713-43a3-a0b3-96dc421a2ede', 'Mesin Jahit Industry Juki DDL 8100E Meja Import Kaki Import Dinamo Servo', '4500000.00', 17, '80000', 'Mesin Jahit JUKI DDL 8100E - High Speed Jarum 1 Industrial Garmen\r\n\r\nMesin Jahit High Speed Jarum 1 Industrial KHUSUS TEBAL PRODUKSI JAHIT JEANS LEVIS DENIM KANVAS MAUPUN KULIT\r\n\r\nMesin Jahit dengan tingkat efisiensi tinggi dan berkemampuan tangguh dalam menjahit. Sangat cocok bagi penjahit professional dan pengusaha butik / garmen\r\n\r\nMerk : JUKI Original (JAPAN)\r\nTipe : DDL-8100E\r\nKelas Mesin : Industrial\r\n(Mesin Jahit Industrial harus menggunakan Meja dan Kaki dalam pengoperasiannya)\r\n\r\nSpecifications :\r\n- Berfungsi menjahit lurus dengan jarum 1\r\n- Kecepatan 4500 S.P.M.\r\n- Maksimal ketebalan Bahan 17 mm\r\n- Jarak jahitan dapat diatur hingga 5.0 mm\r\n- Pengangkat sepatu dapat diangkat hingga 7.0 mm (tangan) - 13.0 mm (lutut)\r\n- GARANSI SERVICE 6 BULAN\r\n\r\nPaket Penjualan :\r\n- 1x Mesin Jahit JUKI DDL 8100E\r\n- 1x Meja Mesin Jahit import Benho / worker\r\n- 1x Kaki Industrial import Yoko/ Benho\r\n- 1x Dinamo servo yuasa\r\n- 1x Tool Kit (Tiang Benang, Oli, Spool, Jarum, Obeng, dll)', 'Juki', 'images/products/1742371688.jpeg', '2025-03-19 08:08:08', '2025-03-19 08:08:08'),
('5bf56af9-6979-4d65-800d-12d62c13dd7e', 'Needle Plate NP7A', '24500.00', 91, '800', 'Needle plate mesin karung NP7A', 'SPAREPART', 'images/products/1742230652.png', '2025-03-17 09:57:32', '2025-03-18 07:00:39'),
('60e834d1-131f-4d10-8068-f83aff65da43', 'Mesin Jahit Mini Singer M1005', '890000.00', 85, '5000', 'Deskripsi MESIN JAHIT MINI PORTABLE SINGER M1005\r\n- 11 pilihan pola jahitan\r\n(6 jahitan lurus, 3 jahitan zigzag, 1 jahitan kelim, 1 jahitan zigzag 3-point)\r\n- Sistem Bobbin Atas (Top Drop-in Bobbin)\r\n- Tombol Jahitan Mundur\r\n- Pengaman Jari (Finger Guard)', 'Singer', 'images/products/1742371444.png', '2025-03-19 08:04:04', '2025-03-19 08:04:17'),
('618c982d-cb97-4703-b515-92ac0751f17f', 'Mesin Jahit Bordir Komputer Singer EM200', '14000000.00', 3, '18000', 'Merk : SINGER (USA)\r\nTipe : EM 200 QUANTUM STYLIST\r\n\r\n#BONUS 20 PCS JARUM BORDIR + 1 BOX BOBBINS ISI 25 PCS +PEN CUTTER + CUTTING MAT A4 + SOFTWARE WILCOMM#\r\n\r\nSpecifications :\r\n- 200 Embroidery Designs + 6 Pilihan Alphabet\r\n- Built-In Area Bodir Extra-Besar (260 mm X 152 mm) dalam hoop untuk pengerjaan Bordir berbidang Besar\r\n- 2 Snap-On Embroidery Hoops Included yaitu Large embroidery hoop (260 mmX 152 mm) and small embroidery hoop (101 mm X 101 mm)\r\n- LCD Touch Screen\r\n- USB Stick Embroidery Design Transfer\r\n- Area Bidang Kerja antara Body mesin dengan Dasar Bahan yang cukup tinggi, sehingga memudahkan ketika mengangkat sepatu\r\n- 700 Stitches Per Minute', 'Singer', 'images/products/1742378348.jpeg', '2025-03-19 07:03:07', '2025-03-19 09:59:08'),
('64cb16de-66bc-4dbb-ad2a-50c5e6e31c2d', 'Motor Servo Atau Dinamo Servo 550w', '550000.00', 69, '3000', 'Dinamo servo untuk mesin industri', 'SPAREPART', 'images/products/1742448481.jpeg', '2025-03-20 05:28:01', '2025-03-20 05:28:01'),
('65566bec-eaa9-4946-a8d6-2c8ba7ebb703', 'Mesin Jahit Digital Janome 1030MX', '3499000.00', 3, '8000', 'Merk : JANOME\r\nTipe : 1030 MX\r\n\r\nMesin Yang Sangat Tangguh Awet, Pengoperasian Yang Sangat Mudah Dan Ringkas Mudah Dibawa Kemana - Mana\r\n\r\nSpesifikasi :\r\n- 30 Pola Jahitan In-built\r\n- Area Bidang Jahit Lebar Hingga 16cm\r\n- Kecepatan Maximal 890 spm\r\n- Layar LCD Terang\r\n- Memory Lubang Kancing Otomatis\r\n- Pemasuk Benang Otomatis\r\n- Lampu LED Terang Aman Dimata\r\n- Adapter Sepatu Besi Model Snap On\r\n- Dimensi Mesin : 38,5 x 15 x 28,2cm\r\n- Berat Bersih 5,0 Kg\r\n- Panjang Jahitan 0.1 - 4mm\r\n- Kecepatan Dapat Diatur (Speed Control)\r\n- Pemutar Bobbin Otomatis Dan Besi\r\n- Drop Feed (Bordir,Kancing,Quilting)\r\n- Gigi Penarik Kain 5 Baris Lebih Kuat\r\n- Plat Jarum Dengan Penanda Presisi Jahitan\r\n- Manual Cutter Pemutus Benang\r\n- Auto Pilot Sewing (Menjahit Tanpa Pedal)\r\n- Auto Needle-Stop Position\r\n- Tombol Auto Pengunci Jahitan\r\n- Tombol Auto Lock Stitch\r\n\r\nWhat\'s In The Box??\r\n- 1 Unit Mesin Jahit JANOME 1030 MX\r\n- 1 x Foot Controller / Pedal\r\n- Kabel Daya\r\n- Soft Cover / sarung Mesin\r\n- Buku Intruksi manual\r\n- Tool Kit Standar', 'Janome', 'images/products/1742372213.jpg', '2025-03-19 08:16:53', '2025-03-19 08:16:53'),
('706b8034-68e3-40b5-922f-5c03050d2c02', 'Pendedel atau Tetelan Benang', '2500.00', 437, '300', 'Pendedel untuk memutus benang', 'AKSESORIS', 'images/products/1743337374.jpg', '2025-03-30 12:22:56', '2025-03-30 12:22:56'),
('75b9566f-887b-437e-b37a-2feefeaad9b8', 'Mesin Jahit Portable Janome 3112P', '1950000.00', 9, '8000', 'Portable', 'Janome', 'images/products/1742228301.jpeg', '2025-03-17 09:18:21', '2025-03-18 07:15:10'),
('79c57445-d62c-4d14-9a46-9a98ae118b9c', 'Jarum Jahit Industri Highspeed DBx1', '23000.00', 473, '300', 'Jarum jahit untuk mesin industri highspeed\r\n1 pack isi 10pcs jarum', 'SPAREPART', 'images/products/1742380264.jpg', '2025-03-19 10:31:04', '2025-03-19 10:53:13'),
('7c0c92d4-5ff9-4c7c-9ad7-25d319d113b1', 'Sekoci Mesin Jahit Industri  Highspeed', '17500.00', 322, '400', 'Sekoci untuk mesin jahit industri', 'SPAREPART', 'images/products/1742379132.jfif', '2025-03-19 10:12:12', '2025-03-19 10:12:12'),
('7d975893-2934-435f-a340-fb0b52eefb0b', 'Mesin Jahit Portable Messina N808', '1500000.00', 6, '8000', 'Spesifikasi\r\n• 12 pola jahitan\r\n• 4 langkah pelubang kancing\r\n• Pemutar benang otomatis\r\n• Bisa bordir, pasang kancing, neci, semi obras, semi som, dll\r\n• Motor cepat 70 watt\r\n• Dilengkapi lampu LED yg terang\r\n• Garansi resmi 1 tahun\r\n\r\nAcsessories bawaan :\r\n• Foot controller ( pedal injakan motor )\r\n• Buku petunjuk bahasa indonesia dan inggris\r\n• Softcase penutup mesin\r\n• Macam2 sepatu : multifungsi, lubang kancing, pasang kancing, pasang resleting\r\n• Pendedel, 4 spol, penggaris besi, sikat, skoci dll\r\n• Semua tinggal pakai', 'Messina', 'images/products/1742449325.jfif', '2025-03-20 05:42:05', '2025-03-20 05:42:05'),
('7e409ff6-ebe5-484d-9ecc-074dd169895c', 'Mesin Jahit Hitam Classic Singer JA2 Fullset Meja Papan Kaki Pancal', '1375000.00', 61, '60000', 'SINGER 15 CLASS (Tradisional)\r\nFull Set A\r\nMesin Jahit Lurus Jarum Satu\r\n\r\nMerk : SINGER\r\nTipe : 15 Class\r\n\r\nSpecs :\r\n- Mesin Jahit Tradisional\r\n- Body dan Sparepart Full Besi\r\n- Speed 1,500 s.p.m\r\n- Fungsi Menjahit Lurus\r\n\r\nPaket Penjualan Full Set A terdiri dari :\r\n- 1 unit Mesin Jahit SINGER 15 CL\r\n- 1 set Meja papan original Singer\r\n- 1 set Kaki Genjot atau Kayuh atau Treadle Stand SINGER\r\n- 1 Tool Kit Standard Singer 15 CL (4 bobbins, 1 Jarum Singer, 1 Sepatu Kelim, 1 Tube Oil, dll.)', 'Singer', 'images/products/1742369393.jpeg', '2025-03-19 07:29:53', '2025-03-19 07:29:53'),
('7f48a384-7808-4122-9f62-154a5703f350', 'Motor/Dinamo Mesin Karung Newlong NP7A', '380000.00', 75, '3000', 'Dinamo mesin jahit karung', 'SPAREPART', 'images/products/1742229764.jfif', '2025-03-17 09:42:44', '2025-03-17 09:42:44'),
('8481bcf8-95a4-4f18-aca4-62ec07b02d5c', 'Mesin Jahit Industry Jack F5 Servo (Head Only)', '3900000.00', 13, '60000', 'MESIN JAHIT INDUSTRI JACK F5 (new model) SERVO HIGH SPEED\r\n\r\nMESIN JAHIT SERVO JACK F5 Mesin Jahit Jarum 1 Industrial Dengan Dinamo Servo\r\n\r\nMesin Jahit dengan tingkat efisiensi tinggi, listrik rendah, kemampuan tangguh dalam menjahit, dapat diatur kecepatannya\r\nSangat cocok bagi Penjahit Professional dan Pengusaha Butik / Garmen\r\n\r\nMerk : JACK\r\nTipe : F5\r\nMade in : China\r\nKelas Mesin : Industrial\r\n(Mesin Jahit Industrial harus menggunakan Meja dan Kaki dalam pengoperasiannya)\r\n\r\nSpecifications :\r\n- Berfungsi Menjahit Lurus dengan Jarum 1\r\n- Kecepatan Mesin dapat diatur\r\n- Kecepatan Maksimal 5,000 spm\r\n- Maksimal Ketebalan Bahan 10 mm\r\n- Lebar Jahitan dapat diatur hingga 5.0 mm\r\n- Pengangkat Sepatu dapat diangkat hingga 5,0 mm (tangan) - 13 mm (lutut)\r\n- Sistem Pelumasan Auto\r\n- Built-in Bobbin Winder\r\n- Menggunakan Lampu LED\r\n- Menggunakan Teknologi Servo Motor (Listrik bervariasi 4 - 500 watt tergantung kecepatan yang anda gunakan)\r\n- Posisi Standby Listrik 4 Watt\r\n- GARANSI SERVICE 1 TAHUN\r\n\r\nPaket Penjualan :\r\n- 1x Mesin Jahit JACK F5\r\n- 1x Tool Kit Standard (3 jenis obeng, Sarung Mesin, dan Oil)\r\n- 1x Akesoris Pendukung (Tiang Benang, Kawat Dinamo, dll)', 'Jack', 'images/products/1742367494.jpeg', '2025-03-19 06:58:14', '2025-03-19 06:58:39'),
('84ebdb30-5729-46a1-917f-0505d5c9be33', 'Minyak Mesin Jahit Botol 900ml', '26000.00', 174, '700', 'Minyak mesin jahit botol 900Ml', 'SPAREPART', 'images/products/1742231640.jpg', '2025-03-17 10:14:00', '2025-03-17 10:14:00'),
('855dc896-f4f8-42de-934f-c18c1227f8f0', 'Mesin Jahit Karung Feiren GK9-900R', '1100000.00', 37, '5000', 'Mesin Jahit Karung Goni GK-9-900 Baterai Cordless Portable Bag Closer\r\n\r\nMerek : FEIREN\r\nTipe : GK9-900\r\nMade in : China\r\nKelas Mesin : Mesin Jahit Karung Portable / Portable Bag Closer\r\nWarna : Kuning / Merah  Bisa Request warna melalui Chat/NOTE, jika tidak ada request maka akan dikirimkan barang sesuai stok yg tersedia\r\n\r\nMesin jahit karung yang tangguh, memudahkan anda dalam menjahit dan menyegel karung beras, karung goni, karung plastik, karung limbah, karung isi kebutuhan anda, dll. Dengan menggunakan baterai anda dapat leluasa menggunakan mesin jahit karung ini pada lokasi yang tidak terdapat arus listrik.\r\n\r\nSpesifikasi :\r\n- 1 Jarum, 1 Benang\r\n- Menggunakan Baterai 2600Mah (4 Jam penggunaan)\r\n- Ketebalan Jahit Hingga 8mm\r\n- Voltage 36V\r\n- Power 280Watt\r\n- RPM 9000r/min\r\n- amp 0.75 A\r\n- Freq 50 Hz\r\n\r\nPaket Penjualan\r\n1x Mesin jahit karung GK9-900\r\n1x Baterai 2600mah\r\n3x Jarum jahit DNx1 no.25\r\n1x Benang jahit karung\r\n1x Obeng\r\n1x Kabel colokan\r\n1x Buku panduan cara penggunaan', 'Feiren', 'images/products/1742305268.jpeg', '2025-03-18 06:41:08', '2025-03-18 06:41:08'),
('86dc74dd-004a-485c-9a5f-feb12dcf99d5', 'Oli Singer Botol Kecil', '8500.00', 237, '300', 'Oli singer 80cc', 'Singer', 'images/products/1742231523.png', '2025-03-17 10:12:03', '2025-03-17 10:12:03'),
('870a361a-a999-427c-86ae-b30be41adf5b', 'Feed Dog atau GIGI NP7A', '24500.00', 80, '400', 'Gigi Mesin Jahit karung np7a', 'SPAREPART', 'images/products/1742229844.jfif', '2025-03-17 09:44:04', '2025-03-17 09:44:04'),
('896f9862-1a93-42f0-93b1-475c069e3fa6', 'Jarum Jahit Karung DNX1 Fstrong', '12000.00', 639, '300', 'Jarum Jahit karung DNX1 FStrong \r\n1 Pack isi 10pcs jarum', 'SPAREPART', 'images/products/1742231157.jpg', '2025-03-17 10:05:57', '2025-03-17 10:05:57'),
('8bd52eff-a3f4-4f0c-adb7-e5d9f8b53f0a', 'Mesin Obras Neci Butterfly GN1-1', '850000.00', 67, '15000', 'Merek : BUTTERFLY\r\nTipe : GN-1\r\nMade in : China\r\nKelas Mesin : Mesin Jahit Obras 3 Benang Traditional\r\n\r\nMesin jahit Obras yang sangat tangguh, Pengoperasian mudah, Cocok digunakan untuk Pemula yang baru belajar jahit maupun Mahir Yang Ingin Membuat Butik Rumahan. Body dan Rangka Besi\r\n\r\nSpesifikasi :\r\n- Obras benang 3\r\n- Sudah Neci\r\n- 2000 s.p.m\r\n- Lengkap dengan aksesoris kwalitas terbaik\r\n- Meja papan+kaki obras+dinamo\r\n\r\nWhat\'s in the box??\r\n- 1x Unit Mesin Obras Benang 3 Merk BUTTERFLY\r\n- Tool Kit Mesin BUTTERFLY GN-1 (Obeng, Jarum, Pisau Obras, Pinset, dll.)\r\n- Letter L dudukan Dinamo', 'Butterfly', 'images/products/1742378129.jpeg', '2025-03-17 22:09:03', '2025-03-19 09:55:29'),
('8d9d662d-2589-4dd2-8683-59b614cad2b6', 'Block Gigi NP7A', '78000.00', 68, '1000', 'Block NP7A', 'SPAREPART', 'images/products/1742229992.jfif', '2025-03-17 09:46:32', '2025-03-17 09:46:32'),
('927e8b7b-31ec-4ca5-9b44-d58d0a585c78', 'Mesin Jahit Industry Typical 628-1 Meja  Mica Kaki Import Dinamo Servo', '3675000.00', 44, '80000', 'TYPICAL GC 6-28-1 - Mesin Jahit High Speed Jarum 1 Industrial\r\n\r\nMerk : TYPICAL\r\nTipe : GC 6-28-1\r\nKelas Mesin : Industrial (Mesin Jahit Industrial harus menggunakan Meja dan Kaki dalam pengoperasiannya)\r\nMesin jahit dengan tingkat efisiensi tinggi dan berkemampuan tangguh dalam menjahit. Sangat cocok bagi penjahit professional dan pengusaha butik / garmen.\r\n\r\nSpecifications :\r\n- Berfungsi menjahit lurus dengan jarum 1\r\n- Kecepatan mesin dapat diatur - Kecepatan 4500 S.P.M. - Maksimal ketebalan Bahan 10 mm - Jarak jahitan dapat diatur hingga 5.0 mm\r\n- Pengangkat sepatu dapat diangkat hingga 7.0 mm (tangan) - 13.0 mm (lutut)\r\nMeja Mica Kaki Impor + Dinamo Servo 550 Watt saat jalan 135 Watt, saat tidak digunakan 0 Watt. Jika Dinamo Clutch nyala awal dan tidak dipakai tetap 250 Watt. 1x Tool Kit (Tiang benang, oli, spool, jarum, obeng, dll)', 'Typical', 'images/products/1742353329.jpeg', '2025-03-19 03:02:09', '2025-03-19 07:34:19'),
('9a92e248-42eb-4cfe-a036-04075a903190', 'Mesin Jahit Portable Messina Paris 5832', '1950000.00', 5, '8000', 'Merek : MESSINA (SINGER) Tipe : Paris P5832 Made in : Taiwan Kelas Mesin : Mesin Jahit Portable (Mesin Jahit Portable tidak menggunakan meja) Deskripsi : Mesin jahit yang sangat tangguh, Pengoperasian mudah dan Ringkas, Mudah dibawa kemana-mana, Cocok digunakan untuk pemula yang baru belajar jahit maupun mahir, dikhususkan untuk anda yang ingin dengan mesin jahit yang awet dan bertenaga. Rangka yang terbuat dari besi membuat mesin anda menjadi kokoh dan awet. Spesifikasi : - 32 Fungsi Jahitan + 1 fungsi Lobang Kancing - 1 Langkah membuat Lobang Kancing - Ada Pemutus Benang - Auto Needle Threader (Membantu memasukkan benang ke jarum) - Ada Jagaan Ukuran Lobang Kancing - Lampu Bohlam - Sistem Spool/Bobbins Bawah - Listrik 85 Watt Fungsi : Jahit lurus,Jahit Zig-Zag,Semi obras,Semi neci,Pasang Retsleting Biasa amp; Resleting Jepang,Lubang dan Pasang kancing, Aplikasi Feston, 1/4 Patchwork,Bordir,Quilting/Menjahit Bebas,Jahit Kelim,Jahit kerut(Lipatan Rok),dll. What in the box??\r\n - 1x Mesin Jahit MESSINA Paris P5832 \r\n - 1x Foot/Pedal Controller - 1x Dust Cover / Sarung Mesin\r\n - 1x Buku Panduan Bahasa Indonesia amp; Inggris\r\n - 1x Kartu Garansi Resmi MESSINA Indonesia 1 Tahun\r\n - 1x Tool Kit Standard (1 Bks Jarum (isi 10),3-5pcs Bobbin/Spool Plastik,3-6pcs Sepatu/Kaki Jahit Standard,Pendedel Benang,dll', 'Messina', 'images/products/1742449231.jpg', '2025-03-20 05:40:31', '2025-03-20 05:40:31'),
('9ee82184-b716-422d-b03c-310a4168706b', 'Jarum Jahit Karung DNX1 Organ Japan', '80000.00', 929, '300', 'Jarum Jahit karung DNX1 Organ Japan\r\n1 Pack isi 10jarum', 'SPAREPART', 'images/products/1742231040.jfif', '2025-03-17 10:04:00', '2025-03-19 10:54:35'),
('a23a9735-8cb8-4af9-b59d-0a7783daa634', 'Mesin Jahit Singer 984 Meja Kaki Singer', '2240000.00', 66, '30000', 'Merek : SINGER (USA)\r\nTipe : 984 Series\r\nMade in : Taiwan\r\nKelas Mesin : Semi Portable Multifungsi\r\n(Tidak membutuhkan meja dan kaki untuk digunakan, namun disarankan untuk menggunakan meja dan kaki mesin jahit atau box mesin jahit dalam pemakaiannya)\r\n\r\nSpesifikasi :\r\n- 24 Fungsi Pola Jahitan\r\n- 4 Langkah/Step Membuat Lobang Kancing\r\n- Front Load Bobbins/Spool Bawah\r\n- Adjustable Stitch Length/Pengatur Panjang Jahitan hingga 4.0 mm\r\n- Adjustable Width Length/Pengatur Lebar Jahitan hingga 5.0 mm\r\n- Adjustable Presser Foot/ Tekanan Sepatu Dapat Diatur Menyesuaikan Ketebalan Bahan\r\n- Drop Feed Dog (memudahkan pengguna untuk quilting dan bodir)\r\n- Built-In Lampu LED\r\n-termasuk meja mica anti gores dan kaki Z crem (cat powder coating)\r\n\r\nWhat\'s in the box ?\r\n- 1x Unit Mesin Jahit SINGER 984\r\n- 1x Built-in 60 Watt Motor SINGER\r\n- 1x Pedal / Foot Controller & Kabel Power\r\n- 1x Booklet Instruction Manual (Bahasa Indonesia)\r\n- 1x Tool Kit Set provided by SINGER (Sepatu Kelim, Sepatu Lobang Kancing, Sepatu Jahit Ritsleting, Oil, Jarum Set, Obeng, Pendedel, Tiang Benang Tambahan untuk Jarum Ganda)\r\n- 1x Label Garansi Resmi SINGER Indonesia 1 Tahun\r\n- Meja Papan Singer\r\n- Kaki Model I Singer New\r\nLihat Selengkapnya', 'Singer', 'images/products/1742369995.jpeg', '2025-03-19 07:39:55', '2025-03-19 07:39:55'),
('a2951010-7424-4fc7-a347-0235ef9ea4f7', 'Mesin Jahit Hitam Classic Butterfly JA2 Head Only', '800000.00', 76, '12000', 'Butrefly JA2 (Tradisional)\r\nMesin Jahit Lurus Jarum Satu\r\n\r\nMerk : Butterfly\r\nTipe : JA2\r\n\r\nSpecs :\r\n- Mesin Jahit Tradisional\r\n- Body dan Sparepart Full Besi\r\n- Speed 1,500 s.p.m\r\n- Fungsi Menjahit Lurus maju dan mundur', 'Butterfly', 'images/products/1742383896.jpg', '2025-03-19 11:31:36', '2025-03-19 11:31:36'),
('a3313d46-3f0d-482b-9f4b-eee035bc82f8', 'Tas Mesin Jahit Merk Singer', '150000.00', 372, '700', 'Tas untuk mesin jahit portable', 'Singer', 'images/products/1742382742.jfif', '2025-03-19 11:12:22', '2025-03-19 11:12:22'),
('a439d742-c5eb-4f22-8c9f-589b93a42c26', 'Mesin Jahit Semi Portable Janome NS7330', '2650000.00', 14, '12000', 'Mesin serbaguna model flat yang bisa dimasukkan di meja / rata dengan meja. dengan dinamo di luar mesin letaknya dibelakang mesin yang memudahkan untuk penggantian dinamo\r\nSpesifikasi\r\n- 12 Fungsi Jahitan Built-In + 1 Fungsi Lobang Kancing\r\n- 4 Langkah Membuat Lobang Kancing\r\n- Frame Body Aluminium\r\n- Sistem Spool/Bobbins Bawah\r\n- Built-In Bulb Lamp\r\n\r\n\r\nISI DUS :\r\n- 1 UNIT JANOME NS 7330N\r\n- 1 PEDAL UNTUK MENJAHIT\r\n- COVER / SARUNG MESIN JAHIT\r\n- 1 TAHUN GARANSI RESMI\r\n- MANUAL BOOK\r\n- TOOL KIT ( JARUM 1 BUNGUKS , SPOOL , SEPATU2 , DLL. )', 'Janome', 'images/products/1742371838.jpeg', '2025-03-19 08:10:38', '2025-03-19 08:10:38'),
('a4685688-fc9f-4ea4-8293-bc78f6d1a05c', 'Mesin Jahit Singer Portable Heavy Duty 4423', '3050000.00', 28, '8000', 'Mesin Jahit Singer Heavy Duty 4423:\r\n\r\n-1.100 jahitan / menit\r\n- 23 pola jahitan\r\n- 1 langkah pelubang kancing\r\n- Pemotong benang\r\n- Pemutar bobbin otomatis\r\n- Pemasang benang otomatis\r\n- Tombol pengatur panjang jahitan\r\n- Tombol pengatur lebar jahitan\r\n- Tombol pengatur pola jahitan\r\n- Tombol posisi jarum\r\n- Tombol pengatur tekanan benang atas\r\n- Tombol jahitan mundur\r\n- Lampu LED\r\n- Heavy duty motor', 'Singer', 'images/products/1742368184.jpeg', '2025-03-19 07:09:44', '2025-03-19 07:09:44'),
('a55cf638-dc13-4922-b78e-34164c3d2bbe', 'Mesin Obras Neci Portable Singer 14HD864', '3850000.00', 22, '15000', 'Merek : SINGER ( USA )\r\nTipe : 14HD854\r\nMade in : Taiwan\r\nKelas Mesin : Mesin Jahit Obras Portable\r\n(Mesin Jahit Obras Portable tidak menggunakan meja)\r\n\r\nSpesifikasi :\r\n- Dapat berfungsi sbg Obras Benang 2, 3 dan 4\r\n- Dapat berfungsi juga sebagai Neci Roll Som\r\n- Free Arm (Ruang Jahit lebih leluasa utk mengerjakan bagian yang sempit)\r\n- Rangka Metal\r\n- Built-In LED Lamp (2 pcs)\r\n- Heavy Duty Series (Dapat mengobras bahan-bahan yang tebal seperti Bahan Denim)\r\n- Listrik 110 Watt\r\n- Adjustable Feed Dog (Untuk membuat hasil jahitan yang berkerut)\r\n- Speed 1,300 Jahitan Per Menit\r\n\r\nFungsi : Jahit Tepi Obras Benang 2/3/4, Jahit Neci / Roll Som, Neci Menggunakan Senar, FlatLock, Dsb.\r\n\r\nWhat\'s in the box??\r\n- Unit Mesin SINGER 14HD854\r\n- Foot/Pedal Controller\r\n- Waste Bin (Tempat Sampah Hasil Potongan Obras)\r\n- Buku Instruction Manual (Bahasa Indonesia)\r\n- Kartu Garansi Resmi Singer 1 Tahun\r\n- Tool Kit (Obeng, Jarum, Pinset, Brush, Pisau Spare, dll)', 'Singer', 'images/products/1742368874.png', '2025-03-19 07:21:14', '2025-03-19 07:21:14'),
('a5e2a4c3-c9a7-418a-890b-5813f72e88fc', 'Mesin Jahit Portable Butterfly JH5832A', '1850000.00', 37, '8000', 'Merek : Butterfly\r\nTipe : JH 5832 A\r\nMade in : China\r\nUkuran Mesin : 45cmx25cmx37cm\r\n\r\nDeskripsi :\r\nMesin jahit yang Cocok digunakan untuk pemula yang baru belajar dan mahir\r\n\r\nSpesifikasi :\r\n- 32 fungsi jahitan\r\n- 1 langkah membuat lobang kancing\r\n- Built in Lamp\r\n- Pemasuk Benang Otomatis (Auto Needle Threader)\r\n- Free Arm (Menjahit lebih mudah untuk bagian sempit)\r\n- Spool Bawah\r\n- Listrik 70 Watt\r\n\r\nFungsi : Jahit lurus,Jahit Zig-Zag,Semi obras,Semi neci,Pasang Retsleting Biasa & Resleting Jepang,Lubang dan Pasang kancing, Aplikasi Feston, 1/4 Patchwork,Bordir,Quilting/Menjahit Bebas,Jahit Kelim,Jahit kerut(Lipatan Rok),dll.\r\n\r\n\r\nisi didalam dos;\r\n- 1x Mesin Jahit Butterfly JH 5832 A\r\n- 1x Foot/Pedal Controller\r\n- 1x Sarung/Cover Mesin Jahit\r\n- 1x Buku Panduan Bahasa Indonesia & Inggris\r\n- 1x Label Garansi Service Butterfly 1 Tahun\r\n- 1x Tool Kit Standard (Obeng, Sontekan Bahan, Sepatu Lobang Kancing, Sepatu Pasang Kancing, Sepatu Zipper, Jarum, Spool, dll)', 'Butterfly', 'images/products/1742382588.jpg', '2025-03-19 11:09:48', '2025-03-19 11:09:48'),
('a85e9fda-e4d2-4e88-836d-bbfe7830a135', 'Mesin Jahit Singer Portable Heavy Duty 4432', '3250000.00', 31, '8000', 'Merek : SINGER (USA)\r\nTipe : HEAVY DUTY 4432\r\nMade in : Taiwan\r\nKelas Mesin : Mesin Jahit Portable\r\n(Mesin Jahit Portable tidak menggunakan meja)\r\n\r\nMesin Jahit Pemula dengan Motor Heavy Duty yang 60% lebih besar dibandingkan Motor Mesin Jahit pada umumnya, dan dilengkapi dengan 32 Pola Jahitan, Lampu LED, Pemasuk Benang Auto, Sistem Gigi/Feeding 7 Row, Fitur Drop Feed, dll membuat mesin ini mudah digunakan, namun sangat kuat dan tangguh serta memiliki kecepatan yang sangat baik \r\n\r\nSpecifications :\r\n- 32 Pola Jahitan In-Built\r\n- Sistem Lobang Kancing Otomatis 1 Langkah\r\n- Pemasuk Otomatis Benang Ke Jarum\r\n- Sistem Bobbins Atas - Top Drop In Bobbins\r\n- Heavy Duty Motor 60% lebih besar \r\n- Kecepatan mesin hingga 1,100 spm\r\n- Pengatur Panjang Jahitan\r\n- Free Arm untuk Menjahit Bahan Silindris/Tubular/Bidang Sempit\r\n- Pengatur Lebar Jahitan hingga 6.0 mm\r\n- LED Light Built-In\r\n- Pemutus Benang Manual\r\n- Fitur Drop Feed (Gigi bisa diturunkan) untuk Quilting/Bordir/Pasang Kancing, dll.\r\n- Frame metal di body (jalan bahan) untuk memudahkan menjahit\r\n- Tekanan Sepatu dapat diatur untuk disesuaikan dengan Bahan yang sedang dikerjakan\r\n- Hemat Energi hanya 90 Watt\r\n\r\nWhats in the box ?\r\n- 1x Unit Mesin SINGER 4432 Heavy Duty\r\n- 1x In-Built SINGER Heavy Duty Motor 90 Watt\r\n- 1x Pedal / Foot Controller\r\n- 1x Sarung / Dust Cover Mesin\r\n- 1x Buku Instruction Manual (Bahasa Indonesia) : )\r\n- 1x Tool Kit Standard SINGER 4432 Heavy Duty (Sepatu Lobang Kancing, Sepatu Pasang Kancing, Sepatu Zipper, Obeng, Jarum, Brush dan Seam Ripper, Quilting Guide Bar)\r\n- 1x Kartu Garansi Resmi SINGER Indonesia 1 Tahun', 'Singer', 'images/products/1742368265.jpeg', '2025-03-19 07:11:05', '2025-03-19 07:11:05');
INSERT INTO `products` (`id`, `name`, `price`, `stock`, `weight`, `description`, `brand`, `image`, `created_at`, `updated_at`) VALUES
('ac148053-2f07-4127-ae10-3eaf41fcc81f', 'Mesin Jahit Karung Hakatori GK9-2', '575000.00', 55, '4000', 'Mesin Jahit Karung Hakatori GK9-2\r\n\r\nPaket Penjualan:\r\n- Benang Karung \r\n- Kabel colokan listrik\r\n- Buku Panduan\r\n- Aksesoris (jarum, minyak, tolkit)', 'Hakatori', 'images/products/1742372863.jpeg', '2025-03-19 08:27:43', '2025-03-19 08:27:43'),
('ace99ba8-da0a-4807-8031-a8eca232e5c1', 'Mesin Jahit Semi Portable Janome S850', '2500000.00', 6, '12000', 'Mesin serbaguna model flat yang bisa dimasukkan di meja / rata dengan meja. dengan dinamo di luar mesin letaknya dibelakang mesin yang memudahkan untuk penggantian dinamo\r\nSpesifikasi :\r\n- 23 Fungsi Jahitan\r\n- 4 Langkah/Step Membuat Lobang Kancing\r\n- Bottom Load Bobbins/Spool Bawah\r\n- Auto Needle Threader\r\n- Adjustable Stitch Length/Pengatur Panjang Jahitan\r\n- Adjustable Width Length/Pengatur Lebar Jahitan\r\n- Adjustable Presser Foot/ Tekanan Sepatu Dapat Diatur\r\n- Drop Feed Dog (memudahkan pengguna untuk quilting dan bodir)\r\n- Built-In Bulb Light (Bohlam)\r\n- Sistem Dinamo Luar - Mudah dimodifikasi dan Mudah Perawatan\r\n\r\nISI DUS :\r\n- 1 UNIT JANOME S850\r\n- 1 PEDAL UNTUK MENJAHIT\r\n- COVER / SARUNG MESIN JAHIT\r\n- MANUAL BOOK\r\n- TOOL KIT ( JARUM 1 BUNGUKS , SPOOL , SEPATU2 , DLL. )', 'Janome', 'images/products/1742371952.jfif', '2025-03-19 08:12:32', '2025-03-19 08:12:32'),
('ad4e0f89-2444-4afd-beac-9d10abd6eff7', 'Mesin Obras Neci Singer 81a1 with Motor (HEAD ONLY)', '1100000.00', 77, '15000', 'Mesin Obras SINGER 81A1 dan DINAMO SINGER\r\n\r\nPaket penjualan termasuk :\r\n- 1 Mesin Obras SINGER 81A1 (Obras 3 Benang)\r\n- 1x Motor Include on Machine\r\n- Tool Kit Obras SINGER 81A1 (Tiang Benang, Pinset, Jarum, dll)\r\n- Tool Kit Dinamo SINGER (Tali Dinamo, Baut Dinamo)', 'Singer', 'images/products/1742368514.jpeg', '2025-03-19 07:12:49', '2025-03-19 07:33:11'),
('b23742b1-5935-42b6-a4e9-e86fc2b190ac', 'Sepul/Spool/Bobbin Mesin Jahit Portable', '1500.00', 512, '200', 'Sepul untuk mesin jahit portable\r\nHarga tertera untuk per 1 pcs', 'SPAREPART', 'images/products/1742379424.jfif', '2025-03-19 10:17:04', '2025-03-19 10:18:26'),
('b26351b3-9fac-4fd5-abe9-5793d03f3ddb', 'Mesin Jahit Karung Newlong Japan NP7A', '4340000.00', 76, '10000', 'NLI Japang', 'Newlong', 'images/products/1742228377.jpeg', '2025-03-17 09:19:37', '2025-03-17 09:19:37'),
('b5c6872c-d34c-4b01-bfc9-7b499a58335a', 'Mesin Jahit Portable Butterfly JH8190A', '1499000.00', 48, '8000', 'Mesin Jahit BUTTERFLY JH 8190A Portable Multifungsi\r\n\r\nMesin Jahit Pemula yang memenuhi kebutuhan Standard bagi penggunanya, dapat menjahit bahan pakaian tipis hingga tebal dengan 11 pilihan Pola Jahitan, Fungsi Lobang Kancing 4 Langkah, dan berbagai Fitur Lainnya yang sangat membantu Pengguna\r\n\r\nMerk : BUTTERFLY \r\nTipe : JH 8190A\r\nKelas Mesin : Portable Multifungsi\r\nMade In : China\r\nListrik : 70 Watt\r\n\r\nSpecs :\r\n- 11 Pilihan Pola Jahitan Langsung & Fungsi Lobang Kancing\r\n- Sistem Lobang Kancing Manual 4 Langkah\r\n- Sistem Bobbins Bawah / Oscillating Hook\r\n- Lampu LED In-Built\r\n- Pengaturan Panjang Jahitan hingga 4.0mm\r\n- Pengaturan Lebar Jahitan hingga 5.0mm\r\n- Tuas Jahitan Mundur / Reverse Lever Stitch\r\n- Pengaturan Tekanan Sepatu\r\n- Free Arm  memudahkan Menjahit Bahan Silindris, Sempit dan Area Tubular \r\n\r\nWhats in the box ?\r\n- 1x Unit Mesin BUTTERFLY JH 8190A\r\n- 1x In-Built 70 Watt Motor\r\n- 1x Pedal Controller\r\n- 1x Buku Panduan Instruction Manual (English)\r\n- 1x DVD Panduan Bahasa Indonesia\r\n- 1x Sarung Kanvas / Dust Cover Mesin\r\n- 1x Tool Kit Standard BUTTERFLY JH 8190A (Sepatu Lobang Kancing, Sepatu Pasang Kancing, Sepatu Pasang Zipper, Brush pembersih mesin, Spool, Jarum, Minyak, Sodetan Bahan)', 'Butterfly', 'images/products/1742378496.jpeg', '2025-03-18 06:39:00', '2025-03-19 10:01:36'),
('b77fcdb6-4220-411a-a47e-2d959639fe03', 'Mesin Jahit Singer Quantum Stylish 9960', '8200000.00', 2, '11000', 'Merek : SINGER (USA)\r\nTipe : Quantum 9960\r\nMade in : Taiwan\r\nKelas Mesin : Mesin Jahit Portable Digital\r\n\r\nMesin Jahit dari SINGER dengan Feature Terlengkap dan Terbaik di kelasnya, Pengoperasian Mudah dan Nyaman digunakan. Dengan Teknologi dan fitur melimpah membuat Singer 9960 nyaman dan aman digunakan. Cocok untuk Pemula sampai Mahir terutama bagi anda yang mempunyai cita-cita Fashion Designer\r\n\r\nSpesifikasi :\r\n600 Built-In Stitches Functions\r\n1-Step Auto Buttonholes (13 Model Lobang Kancing)\r\nAutomatic Thread Cutter\r\nAutomatic Needle Threader\r\nAutomatic Stitch Length & Width (Auto Pengaturan Lebar dan Panjang Jahitan)\r\nNeedle Up/Down Button (Pengatur Posisi Jarum pada saat mesin Stop)\r\nExtension Table Included\r\nLarge Back-Lit LCD Screen with Brightness Control\r\nTop Drop-In Bobbin System/Sistem Bobbin Atas.\r\n5 Built-In Alphabets\r\n2 StayBright LED Lights Built-In\r\nHeavy Duty Metal Frame\r\n25 Needle Positions Adjustment (Posisi Jarum dapat diatur di 25 posisi)\r\nAutomatic Reverse (Tombol Pengunci Jahitan)\r\nAutomatic Tension (Auto Pengatur Tegangan Benang)\r\n\r\nWhat\'s in the box??\r\n1x Unit Mesin SINGER 9960\r\n1x Pedal/Foot Controller\r\n1x Booklet Instruction Manual\r\n1x Extension Table/Meja Tambahan untuk mengerjakan bahan berbidang lebar\r\n1x Hard Carrying Case\r\n1x Tool Kit Set provided by SINGER (sepatu lobang kancing, sepatu pasang kancing, sepatu zipper, sepatu blindstitch, sepatu braiding, sepatu kelim, sepatu s\r\n- 1x Kartu Garansi Resmi SINGER 1 Tahun', 'Singer', 'images/products/1742383246.jfif', '2025-03-19 11:20:46', '2025-03-19 11:20:46'),
('b7c041d9-e73e-415e-82e5-28cd224ed941', 'Dinamo Singer 150W', '160000.00', 198, '1200', 'DINAMO SINGER 150WATT', 'Singer', 'images/products/1742228732.jfif', '2025-03-17 09:25:32', '2025-03-19 07:35:20'),
('bd0209ed-d011-4367-b2cb-e9e3f4bbfb0c', 'Mesin Obras Industry 4 benang Typical GN794 Fullset Meja Kaki', '4900000.00', 23, '80000', 'Merk : TYPICAL (Original)\r\nTipe : GN 794\r\nMade in : China\r\nKelas Mesin : Mesin Obras Industrial Garmen\r\n\r\nSpecs :\r\n- Berfungsi Mengobras Pakaian dengan Jahitan Benang 4\r\n- Dapat difungsikan sebagai Obras Benang 3\r\n- Kecepatan Maksimal 5,500 spm\r\n- Maksimal Ketebalan Bahan 13 mm\r\n- Panjang Jahitan dapat diatur hingga 5.0 mm\r\n- Pengangkat Sepatu dapat diangkat hingga 5,0 mm (tangan) - 13 mm (lutut)\r\n- Sistem Pelumasan Auto\r\n\r\nPaket Penjualan :\r\n- 1x Unit Mesin Obras TYPICAL GN 794\r\n- 1x Meja Import\r\n- 1x Kaki Import\r\n- 1x Tool Kit (Tiang Benang, Oli, Spool, Jarum, Obeng, dll)\r\n- Dinamo Servo', 'Typical', 'images/products/1742378070.jfif', '2025-03-19 09:54:30', '2025-03-19 09:54:30'),
('bd1c0e43-4830-4021-b42f-5d032cee58ae', 'Gunting Pemotong Kain Singer 8inch', '40000.00', 77, '1000', 'Gunting pemotong kain merk singer 8inch', 'Singer', 'images/products/1742448399.jpeg', '2025-03-20 05:26:40', '2025-03-20 05:26:40'),
('c3194b49-50e7-44ee-81c5-3c2ec62203e5', 'Mesin Potong Kain Tegak 10Inch KM LUMINO', '2699000.00', 3, '18000', 'Mesin Potong Bahan Kain Tegak 10\" KM AUV (China)\r\n\r\nMerk : KM LUMINO\r\nTipe : AUV 10\"\r\nKapasitas Potong : 25,4 cm\r\nUkuran Mata Pisau : 10\" (25,4 cm)\r\nPemakaian Listrik 500 Watt\r\n\r\nMesin Potong bahan kelas industrial sangat membantu dalam pengerjaan pemotongan bahan sesuai pola. Sangat berguna dan membantu bagi pengusaha konveksi maupun industri manufaktur pakaian jadi. Perawatan mudah, sparepart selalu tersedia dengan harga terjangkau!\r\n\r\n*Pelumasan Auto\r\n*Switch On-Off\r\n*Pengasah Pisau Auto', 'Lumino', 'images/products/1742448883.jpeg', '2025-03-20 05:34:43', '2025-03-20 05:35:10'),
('c76a3aaf-5f88-4bc0-acfd-076666fa99c1', 'Mesin Obras Neci Butterfly GN1-1 Fullset', '1250000.00', 66, '40000', 'Merek : BUTTERFLY\r\nTipe : GN-1\r\nMade in : China\r\nKelas Mesin : Mesin Jahit Obras 3 Benang Traditional\r\n\r\nMesin jahit Obras yang sangat tangguh, Pengoperasian mudah, Cocok digunakan untuk Pemula yang baru belajar jahit maupun Mahir Yang Ingin Membuat Butik Rumahan. Body dan Rangka Besi\r\n\r\nSpesifikasi :\r\n- Obras benang 3\r\n- Sudah Neci\r\n- 2000 s.p.m\r\n- Lengkap dengan aksesoris kwalitas terbaik\r\n- Meja papan+kaki obras+dinamo\r\n\r\nWhat\'s in the box??\r\n- 1x Unit Mesin Obras Benang 3 Merk BUTTERFLY\r\n- Tool Kit Mesin BUTTERFLY GN-1 (Obeng, Jarum, Pisau Obras, Pinset, dll.)\r\n- Letter L dudukan Dinamo\r\n- Meja Papan Obras \r\n- Kaki H Obras\r\n- Dinamo', 'Butterfly', 'images/products/1742378173.jpeg', '2025-03-19 07:17:07', '2025-03-19 09:56:13'),
('c84e726b-9a46-4b6d-9c34-c6f745226f27', 'Kaki Jahit Singer', '175000.00', 84, '10000', 'Kaki untuk meja jahit mesin portable/industri/semiportable', 'Singer', 'images/products/1742448635.jpeg', '2025-03-20 05:30:35', '2025-03-20 05:30:35'),
('c856fbcc-91db-4939-94da-8f4436f4c896', 'Mesin Jahit Portable Messina M210', '1250000.00', 5, '6000', 'Merek : MESSINA (SINGER)\r\nTipe : Milan M-210\r\nMade in : Taiwan\r\nKelas Mesin : Mesin Jahit Portable\r\n(Mesin Jahit Portable tidak menggunakan meja)\r\n\r\nDeskripsi : Mesin jahit yang sangat tangguh, Pengoperasian mudah dan Ringkas, Mudah dibawa kemana-mana, Cocok digunakan untuk pemula yang baru belajar jahit maupun mahir, dikhususkan untuk anda yang ingin dengan mesin jahit yang awet dan bertenaga. Rangka yang terbuat dari besi membuat mesin anda menjadi kokoh dan awet.\r\n\r\nSpesifikasi :\r\n- 9 Jenis Fungsi Jahitan + 1 fungsi Lobang Kancing (12 Pilihan Pola Jahitan)\r\n- 4 langkah membuat Lobang Kancing\r\n- Ada Pemotong Benang\r\n- Handle/Tuas Jahitan Mundur\r\n- Listrik 60 Watt\r\n- Lampu LED\r\n- Sistem Spool/Bobbins Bawah\r\n\r\n\r\nFungsi : Jahit lurus,Jahit Zig-Zag,Semi obras,Semi neci,Pasang Retsleting Biasa & Resleting Jepang,Lubang dan Pasang kancing, Aplikasi Feston, 1/4 Patchwork,Bordir,Quilting/Menjahit Bebas,Jahit Kelim,Jahit kerut(Lipatan Rok),dll.\r\n\r\nWhat\'s in the box??\r\n- 1x Mesin Jahit MESSINA Milan M-210\r\n- 1x Foot/Pedal Controller\r\n- 1x Buku Panduan Bahasa Indonesia & Inggris\r\n- 1x Kartu Garansi Resmi MESSINA Indonesia 1 Tahun\r\n- 1x Tool Kit Standard (1 Bks Jarum (isi 10),3 pcs Bobbin/Spool Plastik, 2pcs Sepatu/Kaki Jahit Standard,Pendedel Benang,dll)', 'Messina', 'images/products/1742449438.jpg', '2025-03-20 05:43:58', '2025-03-20 05:43:58'),
('c89c8112-10c8-44bf-906f-38f585d8578b', 'Mesin Bordir Komputer CNY E-900', '7800000.00', 3, '18000', 'Mesin Bordir Komputer Portable CNY E900 merupakan mesin bordir portable otomatis skala rumahan sampai industri yang cocok digunakan oleh pemula dan profesional. Memiliki kecepatan menjahit 650spm , bordir otomatis 1 jarum dengan warna yang tidak terbatas\r\n\r\nMerek : CNY\r\nTipe : E 900\r\nKelas : Mesin Bordir Komputer Portable\r\nMesin Jahit Bordir Komputer, bisa membuat logo bordir nama dll dengan desain custom. Cocok digunakan pemula sampai mahir yang ingin membuka usaha bordir.\r\n\r\n\r\nSpesifikasi :\r\n- Layar sentuh LCD\r\n- Memiliki 2 hoop frame (area bordir):\r\nArea S : 10 x 10 cm\r\nArea L : 23,5 x 10 cm\r\n- Rendah Listrik, hanya 85 Watt\r\n- Desain sistem komputer: Format Tajima DST.\r\n- Sensor Pemotong benang otomatis\r\n- Pemutar benang otomatis\r\n- Lampu Led\r\n- Koneksi USB Flashdisk\r\n- Kecepatan 650 Jahitan Per Menit\r\n- Ukuran mesin 40cm x 22cm x 30cm\r\n- Ukuran packing / bungkusan: 510 x 375 x 370mm\r\n- Speed Control / Pengatur kecepatan\r\n\r\nWhat\'s in the box?\r\n- 1 x CNY E900\r\n- 1 x Label Garansi Service 1 Tahun\r\n- 1 x Dvd Intruksi\r\n- 2 x Bidangan / Hoop Bordir\r\n- 1 x Buku Panduan\r\n- 1 x Tool Kit Standar\r\n\r\n\r\nLuas area bordir :\r\nRoyal hoop / pembidang bordir 230mm x 110mm\r\nStandard hoop / pembidang bordir 100mm x 100mm\r\nsistem penyimpanan flashdisk\r\ndilengkapi dengan lampu LED\r\npemutar bobbin otomatis\r\npemasang benang otomatis\r\npengoperasian cocok untuk bordir pemula sampai profesional', 'CNY', 'images/products/1742228434.jpeg', '2025-03-17 09:20:34', '2025-03-19 11:02:06'),
('cbcc1b98-eacf-4e14-a10c-2b94704f88e4', 'Kaki Genjot/Pancal Singer Manual', '550000.00', 43, '20000', 'Kaki genjot untuk mesin hitam classic', 'Singer', 'images/products/1742448570.jpeg', '2025-03-20 05:29:30', '2025-03-20 05:29:30'),
('ccce11bc-83e5-4173-a6f4-6bbde81d34fe', 'Gunting Pemotong Kain Dexian', '50000.00', 77, '2000', 'Gunting untuk memotong kain', 'AKSESORIS', 'images/products/1742379924.jpg', '2025-03-19 10:25:24', '2025-03-19 10:25:24'),
('ce3ecb2d-c5d5-4d0b-adf7-3e9f30779595', 'Singer JA2 FULLSET', '1500000.00', 77, '60000', 'Singer JA2 FULLSET', 'Singer', 'images/products/1742228128.jpeg', '2025-03-17 09:15:28', '2025-03-17 09:15:28'),
('ce5758b4-e58e-47b2-885e-a92985ab3cef', 'Mesin Jahit Singer Portable 8280', '1975000.00', 17, '8000', '- 8 fungsi jahitan + 1 fungsi Lobang Kancing\r\n- 4 langkah membuat Lobang Kancing\r\n- Ada Pemotong Benang\r\n- Pengatur panjang jahitan\r\n- Handle/Tuas Jahitan Mundur\r\n- Listrik 85 Watt\r\n- Lampu Bohlam\r\n- Sistem Spool/Bobbins Bawah\r\n\r\nISI DIDALAM DOS ;\r\n- 1x Unit Mesin\r\n- 1x Pedal Controller\r\n- 1x Instruksi Manual (Bahasa Indonesia) : )\r\n- 1x Sarung Cover Mesin\r\n- 1 set Tool Kit (Sepatu Lobang Kancing, Sepatu Pasang Kancing, Sepatu Zipper, Spool, Jarum, Brush dll.)', 'Singer', 'images/products/1742383108.jpg', '2025-03-19 11:18:28', '2025-03-19 11:18:28'),
('cf6b1968-ed9d-4726-995a-80a2b4d60429', 'Mesin Potong Octa RS100', '650000.00', 57, '3000', 'Mesin Potong Kain Octa original RS100', 'Octa', 'images/products/1742286212.jpeg', '2025-03-18 01:23:32', '2025-03-18 01:23:32'),
('d0d8b70a-05b7-420f-95f5-388c3a62ac28', 'Mesin Jahit Mini Butterfly M21 Biru', '890000.00', 59, '5000', 'Merk : BUTTERFLY\r\nTipe : M21\r\nMade in : China\r\nKelas Mesin : Mesin Jahit Portable Mini\r\n\r\nButterfly M21, Mesin Jahit Portable mini yang cocok untuk Teman Jahit yang pemula dan punya budget under 1 jt! Memiliki 11 Pola Jahitan, dan sudah termasuk pola lubang kancing juga, yang akan memudahkan pengerjaan jahitmu! Ada fitur Pengaturan Kecepatan untuk kamu yang mau menyesuaikan kecepatan menjahitmu. Yang paling unik, di dalam set mesin ini sudah termasuk stand hp untuk kamu letakkan di samping sembari kamu menjahit!\r\n\r\nTersedia dalam 2 warna yang moodbooster banget: Biru Pink dan Beige!\r\n\r\nSpesifikasi\r\n- 11 Pola Jahitan\r\n- Sistem Lubang Kancing 4 Langkah\r\n- Bisa jahit tanpa Pedal\r\n- 2 Mode Kecepatan Jahitan\r\n- Tombol Maju Mundur Jahitan (Pengunci jahitan)\r\n- Pisau Pemotong Benang\r\n- Penggulung Benang\r\n- Pengaturan Tegangan Benang\r\n- Sistem Bobbin Atas\r\n- Kecepatan Menjahit 450 s.p.m\r\n\r\nPaket Penjualan\r\n1x Mesin Jahit BUTTERFLY M21\r\n1x Stand HP\r\n1x Pedal Injakan\r\n1x Buku Panduan Bahasa Indonesia\r\n1x Adapter 12V\r\n1x Extension Table / Meja Tambahan Mesin Jahit Butterfly M21\r\n1 x Tool Kit (Spool, Jarum, Sepatu Lubang Kancing, Sepatu Standar, Mata Nenek, Obeng Mesin)', 'Butterfly', 'images/products/1742268921.jpg', '2025-03-17 20:35:21', '2025-03-17 20:35:21'),
('d3f86585-4b0b-4c2c-acc4-d69f2deb6e76', 'Mesin Overdeck Jahit Kaos Bis Typical GK31500-02BB Fullset Meja Kaki Dinamo', '7100000.00', 3, '80000', 'Mesin Kam / Overdeck Bis\r\nMerk : TYPICAL\r\nTipe : GK 31500-02\r\nMesin dalam kondisi BIS (Corong)\r\n\r\nMesin Jahit Kaos - Overdeck - Interlock - Cover Stitch (Industrial)\r\n\r\nMesin Jahit ini dunakan oleh Industri Kecil hingga Besar pada Produksi Kaos\r\n\r\nSangat cocok bagi Penjahit Professional dan Pengusaha Butik / Garmen\r\n\r\nSpecs :\r\n- Berfungsi Menjahit Kaos bagian Hemming, Leher\r\n- Dikenal Juga dengan Sebutan Kamput atau Cover Stitch\r\n- Kecepatan Maksimal 5,500 spm\r\n- Maksimal Ketebalan Bahan 12 mm\r\n- Panjang dan Lebar Jahitan dapat diatur hingga 6.4 mm\r\n- Pengangkat Sepatu dapat diangkat hingga 5,0 mm (tangan) - 13 mm (lutut)\r\n- Sistem Pelumasan Auto\r\n\r\nPaket Penjualan :\r\n- 1 Unit Mesin TYPICAL GK31500-02\r\n- 1 x Dinamo servo\r\n- 1 Meja Mesin Jahit Grade A\r\n- 1 Kaki Mesin Jahit Industrial \r\n- 1 Tool Kit (Tiang Benang, Oil, Spool, Jarum, Obeng, Tiang Pasang Bias Tape, dll)', 'Typical', 'images/products/1742380552.jpg', '2025-03-19 10:35:52', '2025-03-19 10:35:52'),
('d68b4566-61e6-4be2-a648-e0c24d3b4abf', 'Mesin Jahit Portable Singer M1155', '1475000.00', 67, '8000', 'Merk: Singer\r\nMade in: China\r\nPower: 50 Watt\r\n\r\nSINGER M1155 adalah mesin jahit low watt yang hanya menggunakan daya 50W saja. Mesin jahit hemat energi ini dilengkapi dengan kelengkapan yang dapat membantu Anda membuat berbagai kreasi jahitan. Penampilan mesin yang modern dan harga sangat terjangkau akan membuat Anda terpukau.\r\n\r\nSpesifikasi:\r\n14 pilihan jahitan\r\n4 langkah pelubang kancing\r\nPemotong benang\r\nPemutar bobbin otomatis\r\nPengatur pola jahitan\r\nPengatur tekanan benang atas\r\nTuas jahitan mundur\r\nLampu LED\r\nLow Watt (50W)\r\n\r\nIsi Dalam Box:\r\n1x SINGER M1155\r\n1x Pedal (Foot Controller)\r\n1x Buku Instruksi Manual (Bahasa Indonesia)\r\n1x Tool Kit Standard\r\n1x Kartu Garansi Resmi SINGER Indonesia (1 Tahun)', 'Singer', 'images/products/1742372584.jpeg', '2025-03-19 08:23:04', '2025-03-19 08:23:04'),
('d8005e2e-7a3a-47b5-af6d-8fd41bfe4ebc', 'Presser Feet 16 Atau Sepatu set isi 16 SINGER', '150000.00', 38, '1000', 'Sepatu set isi 16 \r\nbisa digunakan untuk semua mesin portable', 'Singer', 'images/products/1742383367.jfif', '2025-03-19 11:22:47', '2025-03-19 11:22:47'),
('dafd83c1-0cf7-466f-b421-87d917618bb7', 'Vanbelt Mesin Jahit Karung Newlong 160XL', '12000.00', 488, '400', 'Vanbelt mesin jahit karung newlong NP7A', 'SPAREPART', 'images/products/1742229711.jpg', '2025-03-17 09:41:51', '2025-03-17 09:41:51'),
('de41e60c-806e-4441-b3c3-99ad98883dc6', 'Mesin Jahit Singer Portable Heavy Duty 4411', '2750000.00', 11, '8000', 'Mesin Jahit Singer Heavy Duty 4411:\r\n\r\n-1.100 jahitan / menit\r\n- 11 pola jahitan\r\n- 1 langkah pelubang kancing\r\n- Pemotong benang\r\n- Pemutar bobbin otomatis\r\n- Pemasang benang otomatis\r\n- Tombol pengatur panjang jahitan\r\n- Tombol pengatur lebar jahitan\r\n- Tombol pengatur pola jahitan\r\n- Tombol posisi jarum\r\n- Tombol pengatur tekanan benang atas\r\n- Tombol jahitan mundur\r\n- Lampu LED\r\n- Heavy duty motor', 'Singer', 'images/products/1742377895.jpeg', '2025-03-19 09:51:35', '2025-03-19 09:51:35'),
('df2c2896-d21d-4193-ab1c-55da07ac6acc', 'Mesin Jahit Karung Flyingman GK9-500', '800000.00', 57, '6000', 'Mesin jahit karung flyingman gk9-500', 'Flyingman', 'images/products/1742271233.jpeg', '2025-03-17 21:13:53', '2025-03-17 21:13:53'),
('df577e5c-5d70-4dd6-8e87-8d3c32ca864c', 'Mesin Jahit Portable Singer 1412', '2000000.00', 9, '8000', 'Merek : SINGER (USA)\r\nTipe : Promise 1412\r\nMade in : Taiwan\r\n\r\nKelas Mesin : Mesin Jahit Portable Portable\r\n(Mesin Jahit Portable tidak menggunakan meja)\r\n\r\nMesin Jahit Terbaik di Kelasnya, Pengoperasian mudah dan Ringkas, Mudah dibawa kemana-mana, Cocok untuk Penjahit Pemula, Pekerja dan Pelajar tata busana, Pekerjaan Menjahit Rumah tangga, Butik, Fashion design ataupun industri skala kecil. Dikhususkan untuk anda yang ingin dengan mesin jahit yang awet dan bertenaga. Rangka yang terbuat dari besi membuat mesin anda menjadi kokoh dan awet. Watt rendah sehingga dapat digunakan dimana dan kapan saja.\r\n\r\nSpesifikasi :\r\n- 12 Fungsi Jahitan\r\n- 4 Langkah/Step Membuat Lobang Kancing\r\n- Pemasang Benang Manual\r\n- Adjustable Stitch Length/Pengatur Panjang Jahitan\r\n- Adjustable Width Length/Pengatur Lebar Jahitan\r\n- Free Arm Sewing ( Menjahit Bidang Sempit )\r\n- Pemutus Benang\r\n- Built in Lamp\r\n- Sistem Bobbin/Spool Bawah\r\n- 80 Watt', 'Singer', 'images/products/1742383486.jfif', '2025-03-19 11:24:46', '2025-03-19 11:24:46'),
('e1a81dbf-c257-4e4a-a90d-036212fc4d27', 'Mesin Obras Neci Butterfly GN1-1D With Motor', '900000.00', 39, '15000', 'Merek : BUTTERFLY\r\nTipe : GN1-1D\r\nMade in : China\r\nKelas Mesin : Mesin Jahit Obras 3 Benang Traditional\r\n\r\nMesin jahit Obras yang sangat tangguh, Pengoperasian mudah, Cocok digunakan untuk Pemula yang baru belajar jahit maupun Mahir Yang Ingin Membuat Butik Rumahan. Body dan Rangka Besi\r\n\r\nSpesifikasi :\r\n- Obras benang 3\r\n- Sudah Neci\r\n- 2000 s.p.m\r\n- Lengkap dengan aksesoris kwalitas terbaik\r\nWhat\'s in the box??\r\n- 1x Unit Mesin Obras Benang 3 Merk BUTTERFLY\r\n- Tool Kit Mesin BUTTERFLY GN1-1D (Obeng, Jarum, Pisau Obras, Pinset, dll.)\r\n- Letter L dudukan Dinamo\r\n- Motor sudah melekat dengan mesin', 'Butterfly', 'images/products/1742368777.jpeg', '2025-03-19 07:19:37', '2025-03-19 07:19:37'),
('e22fce78-64ae-4ea3-ad56-b75eab89ac41', 'Jarum Jahit Obras Industri DCx1', '21000.00', 313, '300', 'Jarum jahit untuk mesin obras industri\r\n1 pack isi 10pcs jarum', 'SPAREPART', 'images/products/1742381748.jpg', '2025-03-19 10:55:48', '2025-03-19 10:55:48'),
('e3c58d0b-70d4-4aae-9f4e-6a3d55b608b1', 'Sekoci Mesin Jahit Hitam Classic', '12500.00', 275, '300', 'Sekoci untuk mesin jahit hitam classic', 'SPAREPART', 'images/products/1742378811.jpg', '2025-03-19 10:06:51', '2025-03-19 10:08:10'),
('e4428a62-d2e3-4852-bd96-8f8fcfc83007', 'Tension NP7A', '17500.00', 122, '300', 'Tension benang mesin karung np7a', 'SPAREPART', 'images/products/1742230884.jfif', '2025-03-17 10:01:24', '2025-03-17 10:01:24'),
('e4832a1b-6dd5-417f-8fc8-bd9e3a5de66b', 'Pisau Potong Octa RS100', '35000.00', 91, '700', 'Pisau potong untuk mesin octa RS100', 'SPAREPART', 'images/products/1742449033.jpg', '2025-03-20 05:37:13', '2025-03-20 05:37:13'),
('e5ea9887-c527-411a-9396-d79d85dd872f', 'Mesin Jahit Karung Newlong NP7A NL RRT CINA', '1100000.00', 45, '6000', 'Merek : NL Tipe : NP-7A Made in : China Kelas Mesin : Mesin Jahit Karung Portable / Portable Bag Closer Mesin jahit karung yang sangat tangguh, memudahkan Anda dalam menjahit dan menyegel karung beras, karung goni, karung plastik, karung limbah, karung rumah tangga, dll. Spesifikasi : - Jarum 1, Benang 1 (single needle, single stitch) - Pemasangan benang mudah - High Speed Bag Closer - Pegangan Portable yang Nyaman dan Aman - Sistem Pelumas Otomatis - Jahitan Panjang (Mm) 8.5 (3 Per Inch) - 10.500 R.p.m @50Hz - 11.500 R.p.m @60Hz What\'s in the box?? - 1x Mesin Jahit NL NP-7A - 1x Jarum Jahit DNx1 no.25 - 1x Drive Motor 65W 50/60 Hz, 1PH 220Volt - 1x Benang Jahit Karung - 1x Botol Minyak Pelumas - 1x Screwdriver (obeng) - 1x Kabel Colokan listrik - 1x Timing Belt - 1x Buku Panduan Cara Penggunaan', 'Newlong', 'images/products/1742372720.jpeg', '2025-03-19 08:25:20', '2025-03-19 08:25:20'),
('ec61d557-8060-4dbe-94d3-f4f55b1f2f0c', 'Gunting Pemotong Kain', '60000.00', 38, '1000', 'Gunting untuk memotong kain', 'AKSESORIS', 'images/products/1742379725.jpg', '2025-03-19 10:22:05', '2025-03-19 10:23:31'),
('f00d05ae-9f32-411a-9d03-0b4e921a43bb', 'Mesin Obras Neci Singer 81a1 with Motor Fullset', '1300000.00', 47, '40000', 'Mesin Obras SINGER 81A1 dan DINAMO SINGER\r\n\r\nPaket penjualan termasuk :\r\n- 1 Mesin Obras SINGER 81A1 (Obras 3 Benang)\r\n- 1x Motor Include on Machine\r\n- Tool Kit Obras SINGER 81A1 (Tiang Benang, Pinset, Jarum, dll)\r\n- Tool Kit Dinamo SINGER (Tali Dinamo, Baut Dinamo)\r\n- Meja Papan Obras Singer\r\n- Kaki Industrial Singer Model I', 'Singer', 'images/products/1742368466.jpeg', '2025-03-19 07:14:26', '2025-03-19 07:33:39'),
('f065170a-17fd-4c51-9a3c-8eb8fe68f937', 'Sepul/Spool/Bobbin Mesin Jahit Hitam Classic', '1000.00', 461, '100', 'Sepul untuk mesin jahit hitam classic\r\nHarga tertera untuk per 1pcs', 'SPAREPART', 'images/products/1742379333.jpg', '2025-03-19 10:15:33', '2025-03-19 10:18:43'),
('f580d549-129a-4da8-8e30-1f4dbb3e4c6d', 'Microswitch atau ON OFF Mesin Karung NP7A Omron JAPAN', '36000.00', 143, '300', 'On OFF mesin jahit karung np7a Omron Japan', 'SPAREPART', 'images/products/1742231338.jfif', '2025-03-17 10:08:58', '2025-03-17 10:08:58');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('CPalBNLS1byA2YQNvXNEXjLrtHUzxAxC0C7jmQXB', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiMWNwRGlndE9RR2NVMjhzbFo0cFcxZzVHTVFUWU9RaHlBS2ZLYWlLYSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9vcmRlcnMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjIyOiJQSFBERUJVR0JBUl9TVEFDS19EQVRBIjthOjA6e31zOjQ6InVzZXIiO086MTU6IkFwcFxNb2RlbHNcVXNlciI6MzI6e3M6MTM6IgAqAGNvbm5lY3Rpb24iO3M6NToibXlzcWwiO3M6ODoiACoAdGFibGUiO3M6NToidXNlcnMiO3M6MTM6IgAqAHByaW1hcnlLZXkiO3M6MjoiaWQiO3M6MTA6IgAqAGtleVR5cGUiO3M6Njoic3RyaW5nIjtzOjEyOiJpbmNyZW1lbnRpbmciO2I6MDtzOjc6IgAqAHdpdGgiO2E6MDp7fXM6MTI6IgAqAHdpdGhDb3VudCI7YTowOnt9czoxOToicHJldmVudHNMYXp5TG9hZGluZyI7YjowO3M6MTA6IgAqAHBlclBhZ2UiO2k6MTU7czo2OiJleGlzdHMiO2I6MTtzOjE4OiJ3YXNSZWNlbnRseUNyZWF0ZWQiO2I6MDtzOjI4OiIAKgBlc2NhcGVXaGVuQ2FzdGluZ1RvU3RyaW5nIjtiOjA7czoxMzoiACoAYXR0cmlidXRlcyI7YToxMTp7czoyOiJpZCI7czozNjoiMmI5NDdmYjktOWEyYS00YTZhLWI3OGMtNjQyMTQ2NDFlOTJlIjtzOjQ6Im5hbWUiO3M6MTQ6IkFkbWluIFBlbGl0YSAxIjtzOjg6InBhc3N3b3JkIjtzOjYwOiIkMnkkMTIkU3pQTS4ucXh3SnA4MUVxUWtNWi5NLmlLSklGLnJ0cnN3VDBrbzRpOXNUZy5ILzNZVzRrLm0iO3M6NToiZW1haWwiO3M6MTY6ImFkbWluQHBlbGl0YS5jb20iO3M6NToicGhvbmUiO3M6MDoiIjtzOjc6ImFkZHJlc3MiO3M6MDoiIjtzOjU6InBob3RvIjtzOjA6IiI7czo2OiJhY3RpdmUiO2k6MTtzOjQ6InJvbGUiO3M6NToiYWRtaW4iO3M6MTA6ImNyZWF0ZWRfYXQiO3M6MTk6IjIwMjUtMDMtMjMgMjI6MjU6MTUiO3M6MTA6InVwZGF0ZWRfYXQiO3M6MTk6IjIwMjUtMDMtMjMgMjI6MjU6MTUiO31zOjExOiIAKgBvcmlnaW5hbCI7YToxMTp7czoyOiJpZCI7czozNjoiMmI5NDdmYjktOWEyYS00YTZhLWI3OGMtNjQyMTQ2NDFlOTJlIjtzOjQ6Im5hbWUiO3M6MTQ6IkFkbWluIFBlbGl0YSAxIjtzOjg6InBhc3N3b3JkIjtzOjYwOiIkMnkkMTIkU3pQTS4ucXh3SnA4MUVxUWtNWi5NLmlLSklGLnJ0cnN3VDBrbzRpOXNUZy5ILzNZVzRrLm0iO3M6NToiZW1haWwiO3M6MTY6ImFkbWluQHBlbGl0YS5jb20iO3M6NToicGhvbmUiO3M6MDoiIjtzOjc6ImFkZHJlc3MiO3M6MDoiIjtzOjU6InBob3RvIjtzOjA6IiI7czo2OiJhY3RpdmUiO2k6MTtzOjQ6InJvbGUiO3M6NToiYWRtaW4iO3M6MTA6ImNyZWF0ZWRfYXQiO3M6MTk6IjIwMjUtMDMtMjMgMjI6MjU6MTUiO3M6MTA6InVwZGF0ZWRfYXQiO3M6MTk6IjIwMjUtMDMtMjMgMjI6MjU6MTUiO31zOjEwOiIAKgBjaGFuZ2VzIjthOjA6e31zOjg6IgAqAGNhc3RzIjthOjA6e31zOjE3OiIAKgBjbGFzc0Nhc3RDYWNoZSI7YTowOnt9czoyMToiACoAYXR0cmlidXRlQ2FzdENhY2hlIjthOjA6e31zOjEzOiIAKgBkYXRlRm9ybWF0IjtOO3M6MTA6IgAqAGFwcGVuZHMiO2E6MDp7fXM6MTk6IgAqAGRpc3BhdGNoZXNFdmVudHMiO2E6MDp7fXM6MTQ6IgAqAG9ic2VydmFibGVzIjthOjA6e31zOjEyOiIAKgByZWxhdGlvbnMiO2E6MDp7fXM6MTA6IgAqAHRvdWNoZXMiO2E6MDp7fXM6MTA6InRpbWVzdGFtcHMiO2I6MTtzOjEzOiJ1c2VzVW5pcXVlSWRzIjtiOjA7czo5OiIAKgBoaWRkZW4iO2E6Mjp7aTowO3M6ODoicGFzc3dvcmQiO2k6MTtzOjE0OiJyZW1lbWJlcl90b2tlbiI7fXM6MTA6IgAqAHZpc2libGUiO2E6MDp7fXM6MTE6IgAqAGZpbGxhYmxlIjthOjM6e2k6MDtzOjQ6Im5hbWUiO2k6MTtzOjU6ImVtYWlsIjtpOjI7czo4OiJwYXNzd29yZCI7fXM6MTA6IgAqAGd1YXJkZWQiO2E6MTp7aTowO3M6MToiKiI7fXM6MTk6IgAqAGF1dGhQYXNzd29yZE5hbWUiO3M6ODoicGFzc3dvcmQiO3M6MjA6IgAqAHJlbWVtYmVyVG9rZW5OYW1lIjtzOjE0OiJyZW1lbWJlcl90b2tlbiI7fXM6NDoibWVudSI7czo5OiJkYXNoYm9hcmQiO30=', 1743870837);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `role` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `email`, `phone`, `address`, `photo`, `active`, `role`, `created_at`, `updated_at`) VALUES
('2b947fb9-9a2a-4a6a-b78c-64214641e92e', 'Admin Pelita 1', '$2y$12$SzPM..qxwJp81EqQkMZ.M.iKJIF.rtrswT0ko4i9sTg.H/3YW4k.m', 'admin@pelita.com', '', '', '', 1, 'admin', '2025-03-23 15:25:15', '2025-03-23 15:25:15'),
('7e780489-a8e7-4111-9f9b-6beb8f33e391', 'Sumber Rejeki Malang', '$2y$12$jg4gFPtZl4BdZgkBU/UE3.iRCMmeqsrPccngEc6qlUuF//P3FQzsi', 'sumberrejekimalang@gmail.com', '', '', '', 1, 'user', '2025-03-20 15:38:52', '2025-03-20 15:38:52'),
('bb1d26ca-a8bd-492b-98ba-cbe6597825c5', 'Toko Subur Semarang', '$2y$12$ToumVvvvRwqpnLIIbgwPYuYQRxJ1CQXHu7HXmlnD17vmzPP5vpu1C', 'subursmg@gmail.com', '', '', '', 1, 'user', '2025-03-23 17:14:19', '2025-03-23 17:14:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `orders_user_id_index` (`user_id`),
  ADD KEY `orders_status_index` (`status`),
  ADD KEY `orders_payment_status_index` (`payment_status`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_details_order_id_index` (`order_id`),
  ADD KEY `order_details_product_id_index` (`product_id`);

--
-- Indexes for table `order_refunds`
--
ALTER TABLE `order_refunds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_refunds_refund_id_unique` (`refund_id`),
  ADD KEY `order_refunds_order_id_index` (`order_id`),
  ADD KEY `order_refunds_user_id_index` (`user_id`),
  ADD KEY `order_refunds_status_index` (`status`);

--
-- Indexes for table `order_refund_details`
--
ALTER TABLE `order_refund_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_refund_details_refund_id_index` (`refund_id`),
  ADD KEY `order_refund_details_order_detail_id_index` (`order_detail_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_order_id_index` (`order_id`),
  ADD KEY `payments_transaction_id_index` (`transaction_id`),
  ADD KEY `payments_status_index` (`status`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order_refunds`
--
ALTER TABLE `order_refunds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_refund_details`
--
ALTER TABLE `order_refund_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_refunds`
--
ALTER TABLE `order_refunds`
  ADD CONSTRAINT `order_refunds_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_refunds_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_refund_details`
--
ALTER TABLE `order_refund_details`
  ADD CONSTRAINT `order_refund_details_order_detail_id_foreign` FOREIGN KEY (`order_detail_id`) REFERENCES `order_details` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_refund_details_refund_id_foreign` FOREIGN KEY (`refund_id`) REFERENCES `order_refunds` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
