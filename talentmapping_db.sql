-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2025 at 07:29 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `talentmapping_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `competency_descriptions`
--

CREATE TABLE `competency_descriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `competency_code` varchar(30) NOT NULL,
  `competency_name` varchar(50) NOT NULL,
  `strength_description` text DEFAULT NULL,
  `weakness_description` text DEFAULT NULL,
  `improvement_activity` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `training_recommendations` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `competency_descriptions`
--

INSERT INTO `competency_descriptions` (`id`, `competency_code`, `competency_name`, `strength_description`, `weakness_description`, `improvement_activity`, `created_at`, `updated_at`, `training_recommendations`) VALUES
(1, 'SM', 'Self Management', 'Kamu bisa memanajemen waktu dengan efektif, disiplin tinggi, serta menjaga work-life balance dengan baik. Kamu juga mampu mengorganisir dan merencanakan kegiatan dengan baik untuk mencapai tujuan.', 'Kamu cenderung terlalu fokus sendiri, kurang terbuka berdiskusi atau meminta bantuan, dan belum cukup fleksibel dalam manajemen waktu.', 'Tetapkan target jangka pendek dan panjang secara realistis. Cari mentor untuk bimbingan karier dan motivasi kerja. Evaluasi dan apresiasi progres pribadi secara berkala.', '2025-08-19 22:27:35', '2025-08-19 22:27:35', 'Time Management for Peak Performance | Manajemen Emosi: Kecerdasan Emosional untuk Keunggulan Profesional | Job Priority & Strategic Task Prioritization'),
(2, 'CIA', 'Communication & Interpersonal Ability', 'Kamu orang yang bertanggung jawab, pendengar yang baik, serta memiliki kemampuan komunikasi yang efektif dalam berinteraksi dan membangun hubungan dengan orang lain.', 'Kamu masih ragu untuk speak up di forum besar dan cenderung menghindari konflik, yang bisa menimbulkan miskomunikasi.', 'Latihan presentasi dan public speaking. Join diskusi aktif dalam meeting. Praktik active listening dan empati dalam komunikasi sehari-hari.', '2025-08-19 22:27:35', '2025-08-19 22:27:35', 'Komunikasi Efektif & Assertif | Komunikasi intrapersonal & interpersonal | Empati dalam Interaksi Profesional'),
(3, 'TS', 'Thinking Skills', 'Kamu mendorong inovasi & kreativitas, berpikir kritis, dan mampu menganalisis situasi serta informasi secara efektif untuk menciptakan solusi yang berguna.', 'Kamu sering overthinking dan terlalu fokus pada detail, sehingga sulit ambil keputusan cepat atau menyesuaikan saat kondisi berubah.', 'Main game strategi atau puzzle buat ngasah otak. Ikut workshop inovasi atau brainstorming kelompok. Refleksi hasil keputusan untuk evaluasi cara berpikir.', '2025-08-19 22:27:35', '2025-08-19 22:27:35', 'Critical Thinking & Analisis Informasi | Creative Thinking & Ideation Techniques | Problem-Solving & Decision-Making Frameworks'),
(4, 'WWO', 'Work with Others', 'Kamu cepat mengatasi masalah, mampu bekerja sama dengan orang lain untuk mencapai tujuan bersama, serta menghargai kontribusi orang lain.', 'Kamu susah menghadapi perbedaan pendapat, terlalu mengikuti orang lain, dan kurang percaya diri ambil keputusan sendiri.', 'Ikut proyek tim untuk melatih kolaborasi. Belajar memberi dan menerima feedback secara konstruktif. Latihan diskusi terbuka untuk menghadapi perbedaan pendapat.', '2025-08-19 22:27:35', '2025-08-19 22:27:35', 'Komunikasi Efektif untuk Kolaborasi dalam Tim | Empati dan Kecerdasan Emosional dalam Interaksi Tim | Team Work & Collaboration Excellence'),
(5, 'CA', 'Career Attitude', 'Kamu pantang menyerah, selalu berusaha tumbuh dan meningkatkan diri, serta memiliki ambisi tinggi untuk mencapai tujuan dan kepuasan kerja.', 'Terlalu fokus pada diri sendiri, kadang merasa kurang puas dengan progres, dan membuat keputusan tanpa pertimbangan matang.', 'Buat career planning dan roadmap jangka panjang. Identifikasi skill gaps dan buat plan pengembangan. Network dengan profesional di bidang yang diminati.', '2025-08-19 22:27:35', '2025-08-19 22:27:35', 'Etika Profesi & Integritas Personal | Proaktif & Inisiatif: Membangun Karir plan | Growth Mindset for Continuous Development'),
(6, 'L', 'Leadership', 'Kamu memiliki kemampuan untuk mempengaruhi dan membimbing orang lain, dengan ketegasan serta kemampuan analitis untuk mencapai tujuan bersama.', 'Kamu susah mendelegasikan tugas, cenderung dominan, dan belum cukup memberi ruang tim untuk berkembang secara mandiri.', 'Ambil peran leadership dalam proyek kecil. Ikut pelatihan public speaking dan leadership skills. Belajar dari mentor yang memiliki pengalaman kepemimpinan.', '2025-08-19 22:27:35', '2025-08-19 22:27:35', 'Goal Setting & Strategi Pencapaian | Komunikasi Efektif untuk Pemimpin | Influence People: Seni Membangun Pengaruh dalam Tim'),
(7, 'SE', 'Self Esteem', 'Kamu percaya diri, tegas dalam bersikap, dan terbuka pada kritik untuk terus mengembangkan diri dan memperbaiki kekurangan.', 'Kamu kadang terlalu percaya diri hingga terlihat sombong, dan sering membandingkan diri sendiri, bikin semangat turun.', 'Set goals yang achievable dan rayakan pencapaian kecil. Identifikasi dan kembangkan kekuatan personal. Cari feedback positif dari rekan kerja dan supervisor.', '2025-08-19 22:27:35', '2025-08-19 22:27:35', 'Self-Awareness & Pengenalan Diri Mendalam | Self-Compassion: Berlaku Baik pada Diri Sendiri | Positive Self-Talk & Confidence Building'),
(8, 'PS', 'Problem Solving', 'Kamu peka terhadap masalah, mampu menganalisis situasi dan memahami masalah, serta menciptakan solusi yang efektif untuk mengatasi tantangan.', 'Kamu sering overthinking dan ragu ambil tindakan, terutama saat menghadapi masalah rumit, sehingga solusi jadi tertunda.', 'Latihan case study dan problem solving exercises. Diskusi dengan tim untuk mendapat berbagai perspektif. Dokumentasi solusi yang berhasil untuk referensi masa depan.', '2025-08-19 22:27:35', '2025-08-19 22:27:35', 'Analytical Thinking & Data-Driven Decision Making | Critical Thinking for Strategic Solutions | Creative Thinking & Ideation for Breakthrough Solutions'),
(9, 'PE', 'Professional Ethics', 'Kamu berintegritas kuat, dapat membuat keputusan etis dengan pertimbangan yang matang, dan bertanggung jawab dengan tindakan yang dilakukan.', 'Kamu kadang ragu mengambil keputusan etis saat tertekan dan kurang mempertimbangkan nilai moral dari semua sisi masalah.', 'Pelajari code of conduct perusahaan secara mendalam. Diskusi ethical dilemmas dengan mentor atau supervisor. Refleksi personal values dan align dengan professional standards.', '2025-08-19 22:27:35', '2025-08-19 22:27:35', 'Kode Etik Profesi & Standar Perilaku | Hukum dan Aturan Profesional yang Relevan | Self-Awareness and Ethical Reflection'),
(10, 'GH', 'General Hardskills', 'Kamu memiliki keterampilan teknis yang baik, melek teknologi, dan mampu menggunakan alat serta perangkat untuk menganalisis data atau menyelesaikan masalah.', 'Kurang update dengan teknologi terbaru, skill teknis yang kurang mendalam, atau tidak mengikuti perkembangan industri.', 'Ikut pelatihan teknis dan sertifikasi yang relevan. Praktik hands-on dengan tools dan software terbaru. Join community atau forum profesional untuk sharing knowledge.', '2025-08-19 22:27:35', '2025-08-19 22:27:35', 'Technical Skills Development | Digital Literacy & Technology Mastery | Data Analysis & Reporting Tools');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` varchar(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `company` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `event_code` varchar(15) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `pic_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `max_participants` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name`, `company`, `description`, `event_code`, `start_date`, `end_date`, `pic_id`, `is_active`, `max_participants`, `created_at`, `updated_at`) VALUES
('EVT25', 'BCTI Talent Assessment Program 2025', 'Business & Communication Training Institute', 'Business & Communication Training Institute Assessment 2025', 'BCTI2025', '2025-08-25', '2025-12-31', 7, 1, 1000, '2025-08-24 18:43:39', '2025-09-05 18:43:04'),
('EVT29', 'Squid Camp Vol.5', 'Dispora Kalsel', 'Squid Camp', 'SQ2025', '2025-08-30', '2025-10-31', 7, 1, 100, '2025-08-29 20:10:48', '2025-10-28 22:55:16');

-- --------------------------------------------------------

--
-- Table structure for table `event_participants`
--

CREATE TABLE `event_participants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` varchar(5) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `test_completed` tinyint(1) NOT NULL DEFAULT 0,
  `results_sent` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_participants`
--

INSERT INTO `event_participants` (`id`, `event_id`, `user_id`, `test_completed`, `results_sent`, `created_at`, `updated_at`) VALUES
(25, 'EVT25', 6, 0, 0, '2025-10-28 18:18:14', '2025-10-28 18:18:14');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '2025_08_18_022251_create_events_table', 1),
(3, '2025_08_18_022258_create_test_sessions_table', 1),
(4, '2025_08_18_022303_create_test_results_table', 1),
(5, '2025_08_18_032230_create_question_versions_table', 1),
(6, '2025_08_18_032230_create_st30_questions_table', 1),
(7, '2025_08_18_032231_create_st30_responses_table', 1),
(8, '2025_08_18_032232_create_competency_descriptions_table', 1),
(9, '2025_08_18_032233_create_typology_descriptions_table', 1),
(10, '2025_08_18_130325_create_event_participants_table', 1),
(11, '2025_08_18_130326_create_resend_requests_table', 1),
(12, '2025_08_18_130327_create_activity_logs_table', 1),
(13, '2025_08_18_130327_create_password_reset_tokens_table', 1),
(14, '2025_08_18_130327_create_sessions_table', 1),
(15, '2025_08_18_224309_create_sjt_questions_table', 1),
(16, '2025_08_18_224317_create_sjt_options_table', 1),
(17, '2025_08_18_225029_create_sjt_responses_table', 1),
(18, '2025_08_19_052300_create_cache_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(50) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `question_versions`
--

CREATE TABLE `question_versions` (
  `id` varchar(5) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `type` enum('st30','sjt') NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `question_versions`
--

INSERT INTO `question_versions` (`id`, `version`, `type`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
('SJV01', 1, 'sjt', 'SJT Version 1.0', 'Initial version of SJT Situational Judgment Test questions', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('STV01', 1, 'st30', 'ST-30 Version 1.0', 'Initial version of ST-30 Strength Typology questions', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50');

-- --------------------------------------------------------

--
-- Table structure for table `resend_requests`
--

CREATE TABLE `resend_requests` (
  `id` varchar(5) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `test_result_id` varchar(5) NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `sjt_options`
--

CREATE TABLE `sjt_options` (
  `id` varchar(255) NOT NULL,
  `question_id` varchar(255) NOT NULL,
  `option_letter` char(1) NOT NULL,
  `option_text` text NOT NULL,
  `score` int(11) NOT NULL DEFAULT 0,
  `competency_target` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sjt_options`
--

INSERT INTO `sjt_options` (`id`, `question_id`, `option_letter`, `option_text`, `score`, `competency_target`, `is_active`, `created_at`, `updated_at`) VALUES
('SJO001', 'SJ101', 'a', 'Bikin jadwal dengan jeda istirahat teratur biar fokus nggak cepat habis.', 4, 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO002', 'SJ101', 'b', 'Kerjain terus tanpa jeda biar cepat selesai, istirahat belakangan.', 3, 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO003', 'SJ101', 'c', 'Mengerjakan semuanya apa adanya biar semua selesai.', 2, 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO004', 'SJ101', 'd', 'Kerjain sesuai mood, asal nggak terlalu banyak gangguan.', 1, 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO005', 'SJ101', 'e', 'Asal dikerjain, yang penting selesai.', 0, 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO006', 'SJ102', 'a', 'Tarik napas dalam-dalam dulu, terus cari cara tenang buat ngatasinnya.', 4, 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO007', 'SJ102', 'b', 'Cerita ke temen atau atasan buat minta saran.', 3, 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO008', 'SJ102', 'c', 'Diamkan aja, lama-lama juga berlalu.', 2, 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO009', 'SJ102', 'd', 'Ungkapin emosi langsung biar lega.', 1, 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO010', 'SJ102', 'e', 'Biarkan emosi mempengaruhi cara kerja.', 0, 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO011', 'SJ103', 'a', 'Analisis masalahnya dulu, lalu cari solusi yang paling masuk akal.', 4, 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO012', 'SJ103', 'b', 'Tanya sama orang yang lebih berpengalaman.', 3, 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO013', 'SJ103', 'c', 'Coba-coba berbagai cara sampai ada yang berhasil.', 2, 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO014', 'SJ103', 'd', 'Ikuti insting dan harapan yang terbaik.', 1, 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO015', 'SJ103', 'e', 'Hindari masalah itu sampai ada yang bantu.', 0, 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO016', 'SJ104', 'a', 'Evaluasi hasilnya dan pelajari apa yang bisa diperbaiki.', 4, 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO017', 'SJ104', 'b', 'Tanya pendapat orang lain tentang keputusan itu.', 3, 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO018', 'SJ104', 'c', 'Kalau berhasil ya syukur, kalau gagal ya sudah.', 2, 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO019', 'SJ104', 'd', 'Jarang evaluasi, yang penting sudah diputuskan.', 1, 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO020', 'SJ104', 'e', 'Nggak pernah mikirin lagi setelah diputuskan.', 0, 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO021', 'SJ105', 'a', 'Siapkan presentasi yang menarik dan jelaskan manfaatnya untuk tim.', 4, 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO022', 'SJ105', 'b', 'Diskusi informal dulu dengan beberapa orang sebelum presentasi resmi.', 3, 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO023', 'SJ105', 'c', 'Sampaikan secara langsung tanpa persiapan khusus.', 2, 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO024', 'SJ105', 'd', 'Tulis email saja biar lebih praktis.', 1, 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO025', 'SJ105', 'e', 'Tunggu sampai ada yang tanya baru dijelaskan.', 0, 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO026', 'SJ106', 'a', 'Ajukan pertanyaan terbuka dan pastikan semua diberi kesempatan bicara.', 4, 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO027', 'SJ106', 'b', 'Panggil nama mereka secara langsung untuk meminta pendapat.', 3, 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO028', 'SJ106', 'c', 'Tunggu mereka bicara sendiri kalau memang mau.', 2, 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO029', 'SJ106', 'd', 'Fokus pada yang aktif saja biar diskusi lancar.', 1, 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO030', 'SJ106', 'e', 'Biarkan yang dominan yang bicara terus.', 0, 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO031', 'SJ107', 'a', 'Komunikasikan cara kerja masing-masing dan cari titik temu.', 4, 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO032', 'SJ107', 'b', 'Ikuti cara kerja mereka demi keharmonisan tim.', 3, 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO033', 'SJ107', 'c', 'Tetap pakai cara sendiri tapi toleran dengan mereka.', 2, 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO034', 'SJ107', 'd', 'Minta mereka yang menyesuaikan cara kerja saya.', 1, 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO035', 'SJ107', 'e', 'Hindari kerja sama langsung dengan mereka.', 0, 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO036', 'SJ108', 'a', 'Koordinasi rutin dan pastikan semua tahu tanggung jawab masing-masing.', 4, 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO037', 'SJ108', 'b', 'Ingatkan teman-teman tentang deadline dan progress.', 3, 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO038', 'SJ108', 'c', 'Fokus pada bagian tugas saya saja.', 2, 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO039', 'SJ108', 'd', 'Percaya semua orang bisa mengerjakan bagiannya.', 1, 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO040', 'SJ108', 'e', 'Biarkan koordinator tim yang mengatur semuanya.', 0, 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO041', 'SJ109', 'a', 'Cari cara untuk membuat tugas jadi lebih menarik dan meaningful.', 4, 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO042', 'SJ109', 'b', 'Ingatkan diri tentang tujuan jangka panjang dari pekerjaan ini.', 3, 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO043', 'SJ109', 'c', 'Beri reward kecil untuk diri sendiri setelah menyelesaikan tugas.', 2, 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO044', 'SJ109', 'd', 'Kerjakan sambil dengerin musik atau ngobrol.', 1, 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO045', 'SJ109', 'e', 'Menunda-nunda sampai deadline dekat.', 0, 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO046', 'SJ110', 'a', 'Fokus pada pembelajaran dan growth yang bisa didapat dari kesulitan itu.', 4, 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO047', 'SJ110', 'b', 'Cari dukungan dari rekan kerja atau mentor.', 3, 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO048', 'SJ110', 'c', 'Ingatkan diri bahwa ini hanya sementara.', 2, 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO049', 'SJ110', 'd', 'Keluhkan kesulitan ke orang lain biar lega.', 1, 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO050', 'SJ110', 'e', 'Menyerah dan cari pekerjaan lain yang lebih mudah.', 0, 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO051', 'SJ111', 'a', 'Komunikasikan visi jelas dan dorong tim untuk fokus pada tujuan bersama.', 4, 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO052', 'SJ111', 'b', 'Berikan apresiasi atas usaha tim dan rayakan small wins.', 3, 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO053', 'SJ111', 'c', 'Tetap optimis dan yakin tim bisa melewati tantangan.', 2, 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO054', 'SJ111', 'd', 'Biarkan tim mengatasi masalah mereka sendiri.', 1, 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO055', 'SJ111', 'e', 'Fokus pada tugas individu saja.', 0, 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO056', 'SJ112', 'a', 'Ambil tanggung jawab dan buat keputusan setelah mendengar input tim.', 4, 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO057', 'SJ112', 'b', 'Diskusikan dengan tim untuk mencari solusi bersama.', 3, 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO058', 'SJ112', 'c', 'Tunggu ada yang lain yang berinisiatif duluan.', 2, 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO059', 'SJ112', 'd', 'Minta atasan atau senior yang memutuskan.', 1, 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO060', 'SJ112', 'e', 'Hindari terlibat dalam pengambilan keputusan.', 0, 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO061', 'SJ113', 'a', 'Tunjukkan komitmen tinggi dengan memberikan contoh kerja keras.', 4, 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO062', 'SJ113', 'b', 'Berbagi pengalaman dan cerita motivasi.', 3, 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO063', 'SJ113', 'c', 'Memberikan pujian dan dorongan saat mereka butuh.', 2, 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO064', 'SJ113', 'd', 'Berharap mereka termotivasi sendiri.', 1, 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO065', 'SJ113', 'e', 'Fokus pada pekerjaan sendiri saja.', 0, 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO066', 'SJ114', 'a', 'Persiapan matang dan praktik presentasi sebelumnya.', 4, 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO067', 'SJ114', 'b', 'Fokus pada pesan yang ingin disampaikan, bukan pada rasa nervous.', 3, 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO068', 'SJ114', 'c', 'Tarik napas dalam dan mulai bicara pelan-pelan.', 2, 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO069', 'SJ114', 'd', 'Bicara cepat biar cepat selesai.', 1, 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO070', 'SJ114', 'e', 'Hindari kontak mata dan bicara seperlunya.', 0, 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO071', 'SJ115', 'a', 'Jadikan pembelajaran dan motivasi untuk berbuat lebih baik.', 4, 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO072', 'SJ115', 'b', 'Analisis apa yang salah dan buat rencana perbaikan.', 3, 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO073', 'SJ115', 'c', 'Terima sebagai bagian dari proses belajar.', 2, 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO074', 'SJ115', 'd', 'Merasa kecewa tapi coba lupakan.', 1, 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO075', 'SJ115', 'e', 'Menyalahkan faktor eksternal atau orang lain.', 0, 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO076', 'SJ116', 'a', 'Percaya pada kemampuan diri dan ambil keputusan berdasarkan informasi yang ada.', 4, 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO077', 'SJ116', 'b', 'Tanya pendapat orang terdekat sebelum memutuskan.', 3, 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO078', 'SJ116', 'c', 'Ambil keputusan sambil berharap yang terbaik.', 2, 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO079', 'SJ116', 'd', 'Tunda keputusan sampai lebih yakin.', 1, 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO080', 'SJ116', 'e', 'Minta orang lain yang memutuskan.', 0, 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO081', 'SJ117', 'a', 'Identifikasi akar masalah dan kumpulkan informasi sebanyak mungkin.', 4, 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO082', 'SJ117', 'b', 'Diskusikan dengan tim atau mentor untuk mendapat perspektif lain.', 3, 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO083', 'SJ117', 'c', 'Coba solusi yang pernah berhasil di situasi serupa.', 2, 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO084', 'SJ117', 'd', 'Langsung coba berbagai solusi sampai ada yang berhasil.', 1, 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO085', 'SJ117', 'e', 'Panik dan meminta bantuan orang lain segera.', 0, 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO086', 'SJ118', 'a', 'Evaluasi efek samping dan cari cara untuk meminimalkannya.', 4, 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO087', 'SJ118', 'b', 'Komunikasikan efek samping ke stakeholder dan cari solusi bersama.', 3, 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO088', 'SJ118', 'c', 'Modifikasi solusi agar efek sampingnya lebih kecil.', 2, 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO089', 'SJ118', 'd', 'Biarkan efek samping sambil cari solusi lain.', 1, 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO090', 'SJ118', 'e', 'Hentikan solusi itu dan kembali ke kondisi awal.', 0, 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO091', 'SJ119', 'a', 'Pelajari kasus serupa dan adaptasi solusinya untuk konteks saya.', 4, 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO092', 'SJ119', 'b', 'Konsultasi dengan expert atau orang berpengalaman.', 3, 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO093', 'SJ119', 'c', 'Breakdown masalah jadi bagian-bagian kecil.', 2, 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO094', 'SJ119', 'd', 'Trial and error dengan berbagai pendekatan.', 1, 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO095', 'SJ119', 'e', 'Hindari masalah sampai ada orang lain yang mengatasi.', 0, 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO096', 'SJ120', 'a', 'Bicarakan secara empat mata dengan rekan tersebut untuk klarifikasi.', 4, 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO097', 'SJ120', 'b', 'Laporkan ke atasan setelah memastikan faktanya.', 3, 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO098', 'SJ120', 'c', 'Diskusikan dengan rekan lain untuk mendapat saran.', 2, 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO099', 'SJ120', 'd', 'Diamkan saja karena bukan urusan saya.', 1, 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO100', 'SJ120', 'e', 'Ikut-ikutan tidak jujur biar tidak menonjol.', 0, 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO101', 'SJ121', 'a', 'Pilih kejujuran meskipun merugikan secara pribadi.', 4, 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO102', 'SJ121', 'b', 'Cari cara untuk jujur tanpa merugikan diri sendiri.', 3, 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO103', 'SJ121', 'c', 'Tunda keputusan dan cari saran dari mentor.', 2, 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO104', 'SJ121', 'd', 'Pilih keuntungan pribadi tapi merasa bersalah.', 1, 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO105', 'SJ121', 'e', 'Pilih keuntungan pribadi tanpa merasa bersalah.', 0, 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO106', 'SJ122', 'a', 'Menolak dan memastikan kredit diberikan kepada yang berhak.', 4, 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO107', 'SJ122', 'b', 'Terima tapi pastikan kontribusi orang lain juga diakui.', 3, 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO108', 'SJ122', 'c', 'Terima tapi merasa tidak nyaman.', 2, 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO109', 'SJ122', 'd', 'Terima dengan senang hati.', 1, 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO110', 'SJ122', 'e', 'Terima dan anggap itu memang hak saya.', 0, 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO111', 'SJ123', 'a', 'Sangat sering, selalu update dengan teknologi terbaru.', 4, 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO112', 'SJ123', 'b', 'Cukup sering, terutama yang berhubungan dengan pekerjaan.', 3, 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO113', 'SJ123', 'c', 'Kadang-kadang, kalau memang diperlukan.', 2, 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO114', 'SJ123', 'd', 'Jarang, lebih suka pakai yang sudah familiar.', 1, 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO115', 'SJ123', 'e', 'Tidak pernah, menghindari teknologi baru.', 0, 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO116', 'SJ124', 'a', 'Sangat yakin, merasa mahir dengan berbagai perangkat digital.', 4, 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO117', 'SJ124', 'b', 'Cukup yakin, bisa mengatasi sebagian besar tugas digital.', 3, 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO118', 'SJ124', 'c', 'Biasa saja, bisa pakai yang basic.', 2, 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO119', 'SJ124', 'd', 'Kurang yakin, sering perlu bantuan.', 1, 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO120', 'SJ124', 'e', 'Tidak yakin sama sekali dengan kemampuan teknis.', 0, 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO121', 'SJ125', 'a', 'Senang dan percaya diri bisa mengajar dengan baik.', 4, 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO122', 'SJ125', 'b', 'Bersedia tapi perlu persiapan ekstra.', 3, 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO123', 'SJ125', 'c', 'Nervous tapi mau mencoba.', 2, 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO124', 'SJ125', 'd', 'Tidak yakin bisa mengajar dengan baik.', 1, 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO125', 'SJ125', 'e', 'Menolak karena tidak merasa cukup kompeten.', 0, 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO126', 'SJ126', 'a', 'Prioritaskan tugas berdasarkan urgensi dan pentingnya.', 4, 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO127', 'SJ126', 'b', 'Minta bantuan atau delegasikan sebagian tugas.', 3, 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO128', 'SJ126', 'c', 'Kerjakan semampunya sambil berharap bisa selesai.', 2, 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO129', 'SJ126', 'd', 'Stress dan kerjakan semuanya dengan terburu-buru.', 1, 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO130', 'SJ126', 'e', 'Panik dan tidak tahu harus mulai dari mana.', 0, 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO131', 'SJ127', 'a', 'Ambil jeda sejenak, tarik napas, lalu kembali dengan pikiran jernih.', 4, 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO132', 'SJ127', 'b', 'Cerita ke teman atau keluarga buat melepas stress.', 3, 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO133', 'SJ127', 'c', 'Tetap kerjakan tapi dengan mood yang buruk.', 2, 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO134', 'SJ127', 'd', 'Marah-marah dulu biar lega.', 1, 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO135', 'SJ127', 'e', 'Biarkan emosi negatif mempengaruhi seluruh pekerjaan.', 0, 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO136', 'SJ128', 'a', 'Buat planning realistis dengan buffer time dan break.', 4, 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO137', 'SJ128', 'b', 'Set reminder dan tracking progress secara berkala.', 3, 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO138', 'SJ128', 'c', 'Kerjakan dengan steady pace tanpa memaksakan diri.', 2, 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO139', 'SJ128', 'd', 'Mengejar deadline dengan kerja keras tanpa istirahat.', 1, 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO140', 'SJ128', 'e', 'Kerja semampunya, kalau telat ya sudah.', 0, 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO141', 'SJ129', 'a', 'Cross-check dengan sumber terpercaya dan verifikasi faktanya.', 4, 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO142', 'SJ129', 'b', 'Bandingkan informasi dari berbagai sumber.', 3, 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO143', 'SJ129', 'c', 'Pilih sumber yang paling masuk akal menurutku.', 2, 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO144', 'SJ129', 'd', 'Pakai informasi dari sumber yang paling familiar.', 1, 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO145', 'SJ129', 'e', 'Ambil semua informasi tanpa verifikasi.', 0, 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO146', 'SJ130', 'a', 'Analisis masalah dan kumpulkan informasi yang relevan.', 4, 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO147', 'SJ130', 'b', 'Diskusikan dengan orang yang berpengalaman.', 3, 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO148', 'SJ130', 'c', 'Coba solusi sederhana yang terpikirkan duluan.', 2, 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO149', 'SJ130', 'd', 'Ikuti insting dan langsung bertindak.', 1, 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO150', 'SJ130', 'e', 'Tunggu masalah hilang dengan sendirinya.', 0, 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO151', 'SJ131', 'a', 'Breakdown tugas, tentukan deadline dan prioritas each step.', 4, 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO152', 'SJ131', 'b', 'Mulai dari yang paling urgent atau paling mudah.', 3, 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO153', 'SJ131', 'c', 'Kerjakan semuanya secara bersamaan.', 2, 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO154', 'SJ131', 'd', 'Kerjakan sesuai mood atau yang menarik.', 1, 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO155', 'SJ131', 'e', 'Bingung dan tidak tahu harus mulai dari mana.', 0, 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO156', 'SJ132', 'a', 'Gunakan visual aids dan contoh konkret yang relatable.', 4, 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO157', 'SJ132', 'b', 'Tanya balik untuk memastikan mereka paham.', 3, 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO158', 'SJ132', 'c', 'Jelaskan dengan bahasa yang simple dan pelan-pelan.', 2, 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO159', 'SJ132', 'd', 'Ulangi penjelasan kalau mereka terlihat bingung.', 1, 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO160', 'SJ132', 'e', 'Anggap mereka pasti paham kalau sudah dijelaskan.', 0, 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO161', 'SJ133', 'a', 'Dengarkan semua pendapat dulu, lalu cari titik temu.', 4, 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO162', 'SJ133', 'b', 'Fasilitasi diskusi agar semua bisa saling mendengar.', 3, 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO163', 'SJ133', 'c', 'Tetap pada pendapat sendiri tapi tetap respek sama yang lain.', 2, 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO164', 'SJ133', 'd', 'Ikuti pendapat mayoritas biar cepat selesai.', 1, 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO165', 'SJ133', 'e', 'Tetap memaksa pendapat sendiri sampai diterima.', 0, 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO166', 'SJ134', 'a', 'Dengarkan keluh kesahnya dan tawarkan bantuan konkret.', 4, 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO167', 'SJ134', 'b', 'Beri kata-kata penyemangat dan dukungan moral.', 3, 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO168', 'SJ134', 'c', 'Ajak ngobrol santai biar mood-nya membaik.', 2, 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO169', 'SJ134', 'd', 'Kasih saran berdasarkan pengalaman pribadi.', 1, 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO170', 'SJ134', 'e', 'Biarkan dia mengatasi masalahnya sendiri.', 0, 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO171', 'SJ135', 'a', 'Ajak bicara personal untuk tahu kenapa kurang aktif.', 4, 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO172', 'SJ135', 'b', 'Beri tugas kecil yang sesuai kemampuan untuk membangun kepercayaan diri.', 3, 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO173', 'SJ135', 'c', 'Libatkan mereka dalam diskusi dan minta pendapat.', 2, 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO174', 'SJ135', 'd', 'Ingatkan tentang tanggung jawab mereka di tim.', 1, 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO175', 'SJ135', 'e', 'Biarkan saja, yang penting anggota lain aktif.', 0, 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO176', 'SJ136', 'a', 'Fasilitasi diskusi untuk mencari solusi win-win.', 4, 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO177', 'SJ136', 'b', 'Cari common ground dan fokus pada tujuan bersama.', 3, 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO178', 'SJ136', 'c', 'Voting atau ikuti keputusan mayoritas.', 2, 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO179', 'SJ136', 'd', 'Biar leader tim yang memutuskan.', 1, 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO180', 'SJ136', 'e', 'Tidak ikut campur, biar mereka debat sendiri.', 0, 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO181', 'SJ137', 'a', 'Cari tahu style komunikasi dan preferensi kerja mereka.', 4, 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO182', 'SJ137', 'b', 'Komunikasi yang jelas dan sabar dalam beradaptasi.', 3, 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO183', 'SJ137', 'c', 'Fokus pada tujuan kerja, abaikan perbedaan karakter.', 2, 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO184', 'SJ137', 'd', 'Minta mereka yang menyesuaikan dengan cara kerja saya.', 1, 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO185', 'SJ137', 'e', 'Hindari kolaborasi langsung dengan mereka.', 0, 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO186', 'SJ138', 'a', 'Selalu bertanya dan mencari feedback untuk improvement.', 4, 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO187', 'SJ138', 'b', 'Ikut training atau kursus untuk upgrade skill.', 3, 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO188', 'SJ138', 'c', 'Baca artikel atau video tutorial di waktu luang.', 2, 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO189', 'SJ138', 'd', 'Belajar kalau memang ada tugas yang memerlukan.', 1, 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO190', 'SJ138', 'e', 'Merasa skill yang ada sudah cukup.', 0, 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO191', 'SJ139', 'a', 'Analisis masalah dan cari solusi kreatif.', 4, 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO192', 'SJ139', 'b', 'Minta bantuan dari rekan atau atasan.', 3, 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO193', 'SJ139', 'c', 'Tetap berusaha meski hasilnya belum optimal.', 2, 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO194', 'SJ139', 'd', 'Mengeluh tapi tetap kerjakan.', 1, 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO195', 'SJ139', 'e', 'Menyerah dan berharap ada yang bantu.', 0, 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO196', 'SJ140', 'a', 'Excited dan langsung bikin rencana pembelajaran.', 4, 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO197', 'SJ140', 'b', 'Semangat tapi agak nervous, perlu persiapan ekstra.', 3, 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO198', 'SJ140', 'c', 'Menerima dengan perasaan biasa saja.', 2, 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO199', 'SJ140', 'd', 'Agak khawatir tapi tetap mau coba.', 1, 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO200', 'SJ140', 'e', 'Menolak karena takut gagal atau tidak mampu.', 0, 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO201', 'SJ141', 'a', 'Ambil inisiatif dan ajak tim untuk mulai bergerak.', 4, 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO202', 'SJ141', 'b', 'Beri contoh dengan mulai mengerjakan tugas.', 3, 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO203', 'SJ141', 'c', 'Tunggu sebentar, kalau masih tidak ada yang mulai baru saya ambil alih.', 2, 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO204', 'SJ141', 'd', 'Ingatkan tim tentang deadline dan tanggung jawab.', 1, 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO205', 'SJ141', 'e', 'Biarkan saja, mungkin ada yang akan ambil inisiatif nanti.', 0, 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO206', 'SJ142', 'a', 'Ajak ngobrol personal untuk tahu masalahnya dan kasih dukungan.', 4, 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO207', 'SJ142', 'b', 'Beri apresiasi atas kontribusi mereka sejauh ini.', 3, 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO208', 'SJ142', 'c', 'Ingatkan tentang tujuan tim dan pentingnya semangat.', 2, 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO209', 'SJ142', 'd', 'Biarkan mereka mengatasi masalahnya sendiri.', 1, 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO210', 'SJ142', 'e', 'Fokus pada anggota tim yang semangat saja.', 0, 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO211', 'SJ143', 'a', 'Excited karena kesempatan belajar hal baru.', 4, 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO212', 'SJ143', 'b', 'Nervous tapi yakin bisa belajar dan berhasil.', 3, 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO213', 'SJ143', 'c', 'Campur aduk antara nervous dan penasaran.', 2, 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO214', 'SJ143', 'd', 'Khawatir dan merasa tidak yakin bisa berhasil.', 1, 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO215', 'SJ143', 'e', 'Takut dan ingin menghindari tugas tersebut.', 0, 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO216', 'SJ144', 'a', 'Terima kritik dengan lapang dada dan jadikan feedback untuk improve.', 4, 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO217', 'SJ144', 'b', 'Dengarkan baik-baik dan tanya detail cara perbaikannya.', 3, 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO218', 'SJ144', 'c', 'Terima kritik meski agak sakit hati.', 2, 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO219', 'SJ144', 'd', 'Merasa kesal tapi tidak berani menunjukkan.', 1, 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO220', 'SJ144', 'e', 'Merasa diserang dan defensif.', 0, 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO221', 'SJ145', 'a', 'Cari tahu akar masalahnya dan kumpulkan informasi.', 4, 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO222', 'SJ145', 'b', 'Diskusi dengan rekan untuk dapat insight baru.', 3, 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO223', 'SJ145', 'c', 'Coba solusi yang pernah berhasil di kasus serupa.', 2, 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO224', 'SJ145', 'd', 'Trial error dengan berbagai cara.', 1, 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO225', 'SJ145', 'e', 'Bingung dan minta bantuan orang lain segera.', 0, 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO226', 'SJ146', 'a', 'Evaluasi kenapa gagal dan cari pendekatan berbeda.', 4, 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO227', 'SJ146', 'b', 'Brainstorming dengan tim untuk ide-ide baru.', 3, 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO228', 'SJ146', 'c', 'Modifikasi solusi pertama dengan perbaikan.', 2, 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO229', 'SJ146', 'd', 'Coba solusi lain secara random.', 1, 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO230', 'SJ146', 'e', 'Panik dan minta bantuan atasan.', 0, 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO231', 'SJ147', 'a', 'Tegur secara empat mata dan ingatkan tentang aturan.', 4, 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO232', 'SJ147', 'b', 'Laporkan ke atasan setelah memberi peringatan.', 3, 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO233', 'SJ147', 'c', 'Diskusi dengan rekan lain dulu sebelum bertindak.', 2, 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO234', 'SJ147', 'd', 'Diamkan karena tidak mau terlibat masalah.', 1, 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO235', 'SJ147', 'e', 'Ikut melanggar aturan biar tidak menonjol.', 0, 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO236', 'SJ148', 'a', 'Jujur tentang kemampuan dan minta guidance untuk belajar.', 4, 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO237', 'SJ148', 'b', 'Komunikasikan timeline realistic berdasarkan kemampuan.', 3, 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO238', 'SJ148', 'c', 'Coba kerjakan sambil belajar secara diam-diam.', 2, 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO239', 'SJ148', 'd', 'Terima tugas dan berharap bisa figure it out.', 1, 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO240', 'SJ148', 'e', 'Pura-pura bisa padahal tidak yakin.', 0, 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO241', 'SJ149', 'a', 'Hands-on practice dan ikut course atau workshop.', 4, 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO242', 'SJ149', 'b', 'Belajar dari tutorial online dan dokumentasi.', 3, 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO243', 'SJ149', 'c', 'Minta ajaran dari rekan yang sudah mahir.', 2, 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO244', 'SJ149', 'd', 'Trial error sambil baca-baca panduan.', 1, 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO245', 'SJ149', 'e', 'Menghindari teknologi baru kalau bisa.', 0, 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO246', 'SJ150', 'a', 'Jujur ke atasan dan minta waktu untuk belajar.', 4, 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO247', 'SJ150', 'b', 'Cari resource pembelajaran dan langsung praktek.', 3, 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO248', 'SJ150', 'c', 'Coba kerjakan sambil belajar secara bersamaan.', 2, 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO249', 'SJ150', 'd', 'Stress dan khawatir tidak bisa menyelesaikan.', 1, 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJO250', 'SJ150', 'e', 'Tolak tugas karena merasa tidak mampu.', 0, 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23');

-- --------------------------------------------------------

--
-- Table structure for table `sjt_questions`
--

CREATE TABLE `sjt_questions` (
  `id` varchar(255) NOT NULL,
  `version_id` varchar(255) NOT NULL,
  `number` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `competency` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sjt_questions`
--

INSERT INTO `sjt_questions` (`id`, `version_id`, `number`, `question_text`, `competency`, `is_active`, `created_at`, `updated_at`) VALUES
('SJ101', 'SJV01', 1, 'Bagaimana cara kamu mengatur waktu untuk tugas yang butuh fokus tinggi?', 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ102', 'SJV01', 2, 'Kalau ada situasi bikin emosi di tempat kerja, gimana caramu?', 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ103', 'SJV01', 3, 'Saat menghadapi masalah yang belum pernah kamu temui sebelumnya, apa yang kamu lakukan?', 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ104', 'SJV01', 4, 'Bagaimana kamu mengevaluasi keputusan yang sudah dibuat?', 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ105', 'SJV01', 5, 'Saat kamu ingin menyampaikan ide baru kepada tim, bagaimana cara terbaik untuk melakukannya?', 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ106', 'SJV01', 6, 'Saat ada diskusi kelompok, bagaimana kamu memastikan semua orang ikut berpartisipasi?', 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ107', 'SJV01', 7, 'Jika kamu bekerja dengan rekan yang memiliki cara kerja berbeda, bagaimana kamu menyesuaikan diri?', 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ108', 'SJV01', 8, 'Ketika tugas dibagi dalam tim, apa yang biasanya kamu lakukan untuk memastikan semuanya berjalan lancar?', 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ109', 'SJV01', 9, 'Bagaimana kamu menjaga motivasi dalam bekerja, terutama saat menghadapi tugas yang monoton?', 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ110', 'SJV01', 10, 'Saat kamu menghadapi kesulitan dalam pekerjaan, apa yang membantu kamu untuk tetap semangat?', 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ111', 'SJV01', 11, 'Bagaimana kamu menjaga semangat dan motivasi dalam tim ketika menghadapi tantangan besar?', 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ112', 'SJV01', 12, 'Ketika tim membutuhkan seseorang untuk bertanggung jawab mengambil keputusan penting, apa yang kamu lakukan?', 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ113', 'SJV01', 13, 'Bagaimana kamu menginspirasi rekan kerja melalui tindakan dan kata-kata?', 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ114', 'SJV01', 14, 'Bagaimana kamu menunjukkan rasa percaya diri saat mempresentasikan ide di depan orang banyak?', 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ115', 'SJV01', 15, 'Bagaimana kamu memperlakukan kegagalan atau kesalahan yang kamu buat?', 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ116', 'SJV01', 16, 'Ketika menghadapi situasi baru yang memerlukan keputusan cepat, bagaimana kamu mengatasi rasa ragu?', 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ117', 'SJV01', 17, 'Ketika menghadapi masalah besar, apa langkah pertama yang kamu ambil?', 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ118', 'SJV01', 18, 'Jika solusi yang kamu pilih ternyata memiliki efek samping yang tidak diinginkan, apa yang kamu lakukan?', 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ119', 'SJV01', 19, 'Jika kamu menemukan masalah yang belum pernah kamu hadapi sebelumnya, bagaimana pendekatan kamu?', 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ120', 'SJV01', 20, 'Jika kamu tahu ada rekan kerja yang tidak jujur dalam melaporkan hasil kerja, apa yang akan kamu lakukan?', 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ121', 'SJV01', 21, 'Jika kamu terjebak dalam situasi di mana kamu harus memilih antara keuntungan pribadi dan kejujuran, apa yang kamu pilih?', 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ122', 'SJV01', 22, 'Jika kamu diberi kesempatan untuk mengambil kredit atas pekerjaan orang lain, apa yang akan kamu lakukan?', 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ123', 'SJV01', 23, 'Seberapa sering kamu menggunakan perangkat lunak atau aplikasi baru untuk mendukung pekerjaan?', 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ124', 'SJV01', 24, 'Seberapa yakin kamu dengan kemampuan teknismu menggunakan perangkat digital untuk menyelesaikan tugas?', 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ125', 'SJV01', 25, 'Jika kamu diminta untuk memberikan pelatihan tentang keterampilan teknis kepada rekan kerja, bagaimana kamu merasa?', 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ126', 'SJV01', 26, 'Kalau tugas lagi banyak dan deadline mepet, gimana cara kamu mengatasi stress?', 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ127', 'SJV01', 27, 'Bagaimana cara kamu menkontrol diri dari pekerjaan yang bikin kesal?', 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ128', 'SJV01', 28, 'Gimana caramu memastikan tugas selesai tepat waktu tanpa bikin diri kelelahan?', 'SM', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ129', 'SJV01', 29, 'Kalau kamu dapat informasi dari beberapa sumber yang berbeda, gimana caramu memilah mana yang bener?', 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ130', 'SJV01', 30, 'Kalau ada masalah baru, apa langkah pertama yang kamu lakuin?', 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ131', 'SJV01', 31, 'Kalau dapat tugas yang susah, gimana caramu nentuin prioritas penyelesaiannya?', 'TS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ132', 'SJV01', 32, 'Gimana cara kamu supaya ide kamu dipahami sama tim dengan jelas?', 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ133', 'SJV01', 33, 'Kalau ada pendapat yang beda dalam tim, gimana biasanya kamu nyikapi?', 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ134', 'SJV01', 34, 'Kalau temen kerja lagi down, apa yang kamu lakuin buat bantuin dia?', 'CIA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ135', 'SJV01', 35, 'Kalau ada yang nggak aktif di tim, gimana cara kamu buat ngedorong mereka ikut kontribusi?', 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ136', 'SJV01', 36, 'Kalau tim punya pendapat yang beda-beda, gimana kamu bantuin biar bisa cepet sepakat?', 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ137', 'SJV01', 37, 'Gimana cara kamu biar bisa kerjasama dengan orang yang karakternya beda banget?', 'WWO', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ138', 'SJV01', 38, 'Gimana caramu nunjukin kalau kamu punya semangat belajar dan berkembang?', 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ139', 'SJV01', 39, 'Kalau ada hambatan di kerjaan, apa yang biasanya kamu lakuin?', 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ140', 'SJV01', 40, 'Kalau dikasih tugas baru, gimana sikap kamu buat pastiin berhasil?', 'CA', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ141', 'SJV01', 41, 'Kalau nggak ada yang ambil inisiatif di tim, gimana caramu buat gerakin mereka?', 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ142', 'SJV01', 42, 'Kalau ada anggota tim yang kurang semangat, apa yang kamu lakuin buat motifasi mereka?', 'L', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ143', 'SJV01', 43, 'Gimana perasaan kamu kalau dikasih tugas baru yang belum pernah kamu kerjain?', 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ144', 'SJV01', 44, 'Kalau dapat kritik dari atasan, gimana cara kamu merespon dan belajar dari itu?', 'SE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ145', 'SJV01', 45, 'Kalau kamu nemuin masalah baru di kerjaan, biasanya langkah pertama yang kamu ambil apa?', 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ146', 'SJV01', 46, 'Kalau solusi pertama gagal, gimana cara kamu mencari alternatif lain?', 'PS', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ147', 'SJV01', 47, 'Kalau ada rekan yang melakukan pelanggaran aturan, gimana kamu nyikapi?', 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ148', 'SJV01', 48, 'Saat dikasih tugas yang susah, gimana kamu memastikan tetap jujur sama kemampuan diri?', 'PE', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ149', 'SJV01', 49, 'Kalau ada teknologi atau alat baru yang perlu dipelajari buat kerja, gimana cara kamu belajarnya?', 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23'),
('SJ150', 'SJV01', 50, 'Kalau dikasih tugas teknis yang kamu belum kuasai, gimana caramu hadapi?', 'GH', 1, '2025-08-20 12:40:23', '2025-08-20 12:40:23');

-- --------------------------------------------------------

--
-- Table structure for table `sjt_responses`
--

CREATE TABLE `sjt_responses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `session_id` varchar(5) NOT NULL,
  `question_id` varchar(5) NOT NULL,
  `question_version_id` varchar(5) NOT NULL,
  `page_number` int(10) UNSIGNED NOT NULL,
  `selected_option` char(1) NOT NULL,
  `response_time` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sjt_responses`
--

INSERT INTO `sjt_responses` (`id`, `session_id`, `question_id`, `question_version_id`, `page_number`, `selected_option`, `response_time`) VALUES
(1551, 'TS623', 'SJ101', 'SJV01', 1, 'a', NULL),
(1552, 'TS623', 'SJ102', 'SJV01', 1, 'd', NULL),
(1553, 'TS623', 'SJ103', 'SJV01', 1, 'd', NULL),
(1554, 'TS623', 'SJ104', 'SJV01', 1, 'b', NULL),
(1555, 'TS623', 'SJ105', 'SJV01', 1, 'c', NULL),
(1556, 'TS623', 'SJ106', 'SJV01', 1, 'c', NULL),
(1557, 'TS623', 'SJ107', 'SJV01', 1, 'b', NULL),
(1558, 'TS623', 'SJ108', 'SJV01', 1, 'c', NULL),
(1559, 'TS623', 'SJ109', 'SJV01', 1, 'c', NULL),
(1560, 'TS623', 'SJ110', 'SJV01', 1, 'c', NULL),
(1561, 'TS623', 'SJ111', 'SJV01', 2, 'a', NULL),
(1562, 'TS623', 'SJ112', 'SJV01', 2, 'c', NULL),
(1563, 'TS623', 'SJ113', 'SJV01', 2, 'c', NULL),
(1564, 'TS623', 'SJ114', 'SJV01', 2, 'b', NULL),
(1565, 'TS623', 'SJ115', 'SJV01', 2, 'b', NULL),
(1566, 'TS623', 'SJ116', 'SJV01', 2, 'b', NULL),
(1567, 'TS623', 'SJ117', 'SJV01', 2, 'b', NULL),
(1568, 'TS623', 'SJ118', 'SJV01', 2, 'c', NULL),
(1569, 'TS623', 'SJ119', 'SJV01', 2, 'c', NULL),
(1570, 'TS623', 'SJ120', 'SJV01', 2, 'd', NULL),
(1571, 'TS623', 'SJ121', 'SJV01', 3, 'e', NULL),
(1572, 'TS623', 'SJ122', 'SJV01', 3, 'd', NULL),
(1573, 'TS623', 'SJ123', 'SJV01', 3, 'd', NULL),
(1574, 'TS623', 'SJ124', 'SJV01', 3, 'e', NULL),
(1575, 'TS623', 'SJ125', 'SJV01', 3, 'c', NULL),
(1576, 'TS623', 'SJ126', 'SJV01', 3, 'b', NULL),
(1577, 'TS623', 'SJ127', 'SJV01', 3, 'c', NULL),
(1578, 'TS623', 'SJ128', 'SJV01', 3, 'b', NULL),
(1579, 'TS623', 'SJ129', 'SJV01', 3, 'c', NULL),
(1580, 'TS623', 'SJ130', 'SJV01', 3, 'c', NULL),
(1581, 'TS623', 'SJ131', 'SJV01', 4, 'a', NULL),
(1582, 'TS623', 'SJ132', 'SJV01', 4, 'b', NULL),
(1583, 'TS623', 'SJ133', 'SJV01', 4, 'a', NULL),
(1584, 'TS623', 'SJ134', 'SJV01', 4, 'b', NULL),
(1585, 'TS623', 'SJ135', 'SJV01', 4, 'b', NULL),
(1586, 'TS623', 'SJ136', 'SJV01', 4, 'b', NULL),
(1587, 'TS623', 'SJ137', 'SJV01', 4, 'd', NULL),
(1588, 'TS623', 'SJ138', 'SJV01', 4, 'c', NULL),
(1589, 'TS623', 'SJ139', 'SJV01', 4, 'b', NULL),
(1590, 'TS623', 'SJ140', 'SJV01', 4, 'd', NULL),
(1591, 'TS623', 'SJ141', 'SJV01', 5, 'c', NULL),
(1592, 'TS623', 'SJ142', 'SJV01', 5, 'b', NULL),
(1593, 'TS623', 'SJ143', 'SJV01', 5, 'a', NULL),
(1594, 'TS623', 'SJ144', 'SJV01', 5, 'c', NULL),
(1595, 'TS623', 'SJ145', 'SJV01', 5, 'c', NULL),
(1596, 'TS623', 'SJ146', 'SJV01', 5, 'b', NULL),
(1597, 'TS623', 'SJ147', 'SJV01', 5, 'c', NULL),
(1598, 'TS623', 'SJ148', 'SJV01', 5, 'b', NULL),
(1599, 'TS623', 'SJ149', 'SJV01', 5, 'b', NULL),
(1600, 'TS623', 'SJ150', 'SJV01', 5, 'b', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `st30_questions`
--

CREATE TABLE `st30_questions` (
  `id` varchar(5) NOT NULL,
  `version_id` varchar(5) NOT NULL,
  `number` int(10) UNSIGNED NOT NULL,
  `statement` text NOT NULL,
  `typology_code` varchar(5) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `st30_questions`
--

INSERT INTO `st30_questions` (`id`, `version_id`, `number`, `statement`, `typology_code`, `is_active`, `created_at`, `updated_at`) VALUES
('ST01', 'STV01', 1, 'Pengelola urusan bisnis, organisasi, atau lembaga yang rapih dan baik', 'AMB', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST02', 'STV01', 2, 'Menjadi perwakilan dari suatu organisasi/institusi, baik resmi maupun tidak resmi', 'ADM', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST03', 'STV01', 3, 'Penggemar hal-hal detil, dan selalu melakukan analisa terhadap berbagai peristiwa', 'ANA', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST04', 'STV01', 4, 'Mampu dengan mudah mengorganisir berbagai hal, atau berbagai sumber daya yang dimilikinya', 'ARR', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST05', 'STV01', 5, 'Menyukai aktivitas memberikan perhatian atau mendampingi dan dukungan kepada orang lain', 'CAR', 1, '2025-08-19 22:20:50', '2025-10-20 00:15:19'),
('ST06', 'STV01', 6, 'Gemar memberikan perintah, kadang memaksa. Berani menghadapi masalah secara langsung', 'CMD', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST07', 'STV01', 7, 'Menyampaikan informasi, ide, perasaan dengan cara yang sederhana dan mudah dimengerti', 'COM', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST08', 'STV01', 8, 'Senang atau mampu menciptakan sesuatu yang baru, seperti penulis, ilmuwan, dll.', 'CRE', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST09', 'STV01', 9, 'Bisa membayangkan bagaimana sesuatu akan dibuat, dan bisa menggambar rancangan hal tersebut', 'DES', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST10', 'STV01', 10, 'Memberikan atau mengirimkan sesuatu kepada orang-orang tertentu, dan dalam jumlah tertentu', 'DIS', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST11', 'STV01', 11, 'Mendidik, atau berperan dalam merencanakan dan mengarahkan pendidikan', 'EDU', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST12', 'STV01', 12, 'Mampu melakukan studi dan analisis yang mendalam, dan membuat kesimpulan mengenai sesuatu', 'EVA', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST13', 'STV01', 13, 'Mampu menyelidiki sesuatu secara sistematis, sebagai upaya menemukan motif, untuk mengungkap kebenaran', 'EXP', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST14', 'STV01', 14, 'Mampu menginterpretasikan sesuatu, termasuk menerjemahkannya ke dalam bahasa lain', 'INT', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST15', 'STV01', 15, 'Mampu membuat jurnal, buku harian atau catatan kejadian sehari-hari, atau menulis laporan dan berita untuk disiarkan', 'COM', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST16', 'STV01', 16, 'Mampu merumuskan strategi promosi, untuk mendorong orang agar mereka membeli lebih banyak produk/jasanya', 'AMB', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST17', 'STV01', 17, 'Mampu menjadi penengah, mengatasi dan menyelesaikan konflik antar dua pihak yang bertikai', 'CMD', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST18', 'STV01', 18, 'Membuat orang lain menjadi bersemangat, tertarik, dan berkomitmen untuk melakukan sesuatu dengan sebaik mungkin', 'CMD', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST19', 'STV01', 19, 'Mengoperasikan dan menjaga mesin-mesin, instrument, atau peralatan lain', 'ARR', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST20', 'STV01', 20, 'Menghasilkan suatu produk, terutama produk yang dihasilkan oleh proses industri atau manufaktur', 'CRE', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST21', 'STV01', 21, 'Memastikan kualitas yang memadai dan baik, terutama dalam produk yang dibuat', 'EVA', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST22', 'STV01', 22, 'Mengembalikan sesuatu menjadi seperti kondisi/keadaan semula, atau normal, atau menjadi lebih baik', 'CAR', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST23', 'STV01', 23, 'Melindungi keselamatan atau keamanan sesuatu atau seseorang dari bahaya kerusakan, kehilangan dan pencurian', 'CAR', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST24', 'STV01', 24, 'Hebat dalam meyakinkan dan mempengaruhi orang lain untuk membeli barang/jasa yang ditawarkannya', 'AMB', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST25', 'STV01', 25, 'Senang melayani dan mendahulukan orang lain', 'CAR', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST26', 'STV01', 26, 'Mampu dan pandai memilih seseorang untuk ditempatkan pada suatu posisi atau tugas tertentu', 'ARR', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST27', 'STV01', 27, 'Mampu melakukan perencanaan jangka panjang', 'ADM', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST28', 'STV01', 28, 'Gemar mengkombinasikan berbagai pandangan, ide, obyek, dll. menjadi sesuatu hal yang baru', 'CRE', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST29', 'STV01', 29, 'Mampu dan pandai melakukan tugas pengelolaan keuangan, pembukuan dan akuntansi', 'ANA', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50'),
('ST30', 'STV01', 30, 'Gemar berpikir jauh ke depan melampaui cakrawala', 'EXP', 1, '2025-08-19 22:20:50', '2025-08-19 22:20:50');

-- --------------------------------------------------------

--
-- Table structure for table `st30_responses`
--

CREATE TABLE `st30_responses` (
  `id` varchar(5) NOT NULL,
  `session_id` varchar(5) NOT NULL,
  `question_version_id` varchar(5) NOT NULL,
  `stage_number` int(10) UNSIGNED NOT NULL,
  `selected_items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`selected_items`)),
  `excluded_items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`excluded_items`)),
  `for_scoring` tinyint(1) NOT NULL,
  `response_time` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `st30_responses`
--

INSERT INTO `st30_responses` (`id`, `session_id`, `question_version_id`, `stage_number`, `selected_items`, `excluded_items`, `for_scoring`, `response_time`) VALUES
('STR01', 'TS623', 'STV01', 1, '[\"ST01\",\"ST02\",\"ST03\",\"ST04\",\"ST05\",\"ST06\"]', NULL, 1, NULL),
('STR02', 'TS623', 'STV01', 2, '[\"ST07\",\"ST08\",\"ST09\",\"ST10\",\"ST11\"]', NULL, 1, NULL),
('STR03', 'TS623', 'STV01', 3, '[\"ST12\",\"ST13\",\"ST14\",\"ST15\",\"ST16\"]', NULL, 0, NULL),
('STR04', 'TS623', 'STV01', 4, '[\"ST17\",\"ST18\",\"ST19\",\"ST20\",\"ST21\",\"ST22\"]', NULL, 0, NULL);

--
-- Triggers `st30_responses`
--
DELIMITER $$
CREATE TRIGGER `bi_st30_responses_json` BEFORE INSERT ON `st30_responses` FOR EACH ROW BEGIN
  IF NEW.selected_items IS NULL OR JSON_VALID(NEW.selected_items) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'selected_items must be valid JSON';
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `bi_st30_responses_lock_version` BEFORE INSERT ON `st30_responses` FOR EACH ROW BEGIN
  DECLARE sess_version VARCHAR(10);
  SELECT st30_version_id INTO sess_version
  FROM test_sessions
  WHERE id = NEW.session_id
  LIMIT 1;

  -- Jika session punya versi terkunci, paksa question_version_id = versi session
  IF sess_version IS NOT NULL AND NEW.question_version_id <> sess_version THEN
    SET NEW.question_version_id = sess_version;
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `bu_st30_responses_json` BEFORE UPDATE ON `st30_responses` FOR EACH ROW BEGIN
  IF NEW.selected_items IS NULL OR JSON_VALID(NEW.selected_items) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'selected_items must be valid JSON';
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `bu_st30_responses_lock_version` BEFORE UPDATE ON `st30_responses` FOR EACH ROW BEGIN
  DECLARE sess_version VARCHAR(10);
  SELECT st30_version_id INTO sess_version
  FROM test_sessions
  WHERE id = NEW.session_id
  LIMIT 1;

  IF sess_version IS NOT NULL AND NEW.question_version_id <> sess_version THEN
    SET NEW.question_version_id = sess_version;
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `test_results`
--

CREATE TABLE `test_results` (
  `id` varchar(5) NOT NULL,
  `session_id` varchar(5) NOT NULL,
  `st30_results` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`st30_results`)),
  `sjt_results` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sjt_results`)),
  `dominant_typology` varchar(5) DEFAULT NULL,
  `report_generated_at` timestamp NULL DEFAULT NULL,
  `email_sent_at` timestamp NULL DEFAULT NULL,
  `pdf_path` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `test_results`
--

INSERT INTO `test_results` (`id`, `session_id`, `st30_results`, `sjt_results`, `dominant_typology`, `report_generated_at`, `email_sent_at`, `pdf_path`, `created_at`, `updated_at`) VALUES
('TR001', 'TS623', '{\"strengths\":[{\"code\":\"ADM\",\"name\":\"Administrator\",\"desc\":\"Memiliki pola kerja yang terstruktur, terencana, rapih, suka melayani serta menjunjung tinggi tanggung jawab dan ketaatan tata tertib\"},{\"code\":\"AMB\",\"name\":\"Ambassador\",\"desc\":\"Bersahabat, menyampaikan dan menjelaskan sesuatu, senang melayani dan bertanggung jawab\"},{\"code\":\"ANA\",\"name\":\"Analyst\",\"desc\":\"Memiliki ketertarikan dengan hitung-menghitung berhubungan dengan angka, data dan analisis\"},{\"code\":\"ARR\",\"name\":\"Arranger\",\"desc\":\"Suka mengatur seorang atau sekelompok untuk bekerjasama dalam hal penempatan atau penugasan orang, barang ataupun event\"},{\"code\":\"CAR\",\"name\":\"Caretaker\",\"desc\":\"Memberikan perhatian atau merawat orang lain yang memiliki masalah fisik, mental, medis atau kesejahteraan umum. mampu merasakan perasaan orang lain serta terdorong membantu orang lain\"},{\"code\":\"CMD\",\"name\":\"Commander\",\"desc\":\"Memiliki kemampuan mengantur dan mengawasi dalam melaksanakan tugas, tegas, mungkin keras kepala, berani mengambil tanggung jawab\"}],\"weakness\":[{\"code\":\"COM\",\"name\":\"Communicator\",\"desc\":\"Cenderung menyederhanakan hal yang kompleks, kurang mendalam dalam penyampaian, terlalu fokus pada gaya daripada isi, mudah bosan dengan topik yang terlalu teknis atau detail\"},{\"code\":\"CRE\",\"name\":\"Creator\",\"desc\":\"Sulit fokus pada satu ide, mudah terdistraksi, kurang terstruktur, sulit merealisasikan ide, cenderung mengabaikan detail teknis atau praktis\"},{\"code\":\"DES\",\"name\":\"Designer\",\"desc\":\"Cenderung terjebak pada detail visual, sulit memilih ide utama, perfeksionis, lambat ambil keputusan, dan kurang kolaboratif\"},{\"code\":\"DIS\",\"name\":\"Distributor\",\"desc\":\"Cenderung bekerja berulang tanpa refleksi, mudah lelah secara fisik, kurang fleksibel saat situasi berubah, dan minim inovasi dalam metode\"},{\"code\":\"EDU\",\"name\":\"Educator\",\"desc\":\"Cenderung terlalu mengontrol, sulit menerima metode belajar berbeda, mudah frustrasi saat perkembangan lambat, dan mengabaikan kebutuhan dirinya sendiri\"}]}', '{\"top3\":[{\"code\":\"SE\",\"name\":\"Self Esteem\",\"score\":15,\"strength\":\"Kamu percaya diri, tegas dalam bersikap, dan terbuka pada kritik untuk terus mengembangkan diri dan memperbaiki kekurangan.\",\"weakness\":\"Kamu kadang terlalu percaya diri hingga terlihat sombong, dan sering membandingkan diri sendiri, bikin semangat turun.\",\"activity\":\"Set goals yang achievable dan rayakan pencapaian kecil. Identifikasi dan kembangkan kekuatan personal. Cari feedback positif dari rekan kerja dan supervisor.\",\"training\":\"Self-Awareness & Pengenalan Diri Mendalam | Self-Compassion: Berlaku Baik pada Diri Sendiri | Positive Self-Talk & Confidence Building\"},{\"code\":\"CIA\",\"name\":\"Communication & Interpersonal Ability\",\"score\":14,\"strength\":\"Kamu orang yang bertanggung jawab, pendengar yang baik, serta memiliki kemampuan komunikasi yang efektif dalam berinteraksi dan membangun hubungan dengan orang lain.\",\"weakness\":\"Kamu masih ragu untuk speak up di forum besar dan cenderung menghindari konflik, yang bisa menimbulkan miskomunikasi.\",\"activity\":\"Latihan presentasi dan public speaking. Join diskusi aktif dalam meeting. Praktik active listening dan empati dalam komunikasi sehari-hari.\",\"training\":\"Komunikasi Efektif & Assertif | Komunikasi intrapersonal & interpersonal | Empati dalam Interaksi Profesional\"},{\"code\":\"L\",\"name\":\"Leadership\",\"score\":13,\"strength\":\"Kamu memiliki kemampuan untuk mempengaruhi dan membimbing orang lain, dengan ketegasan serta kemampuan analitis untuk mencapai tujuan bersama.\",\"weakness\":\"Kamu susah mendelegasikan tugas, cenderung dominan, dan belum cukup memberi ruang tim untuk berkembang secara mandiri.\",\"activity\":\"Ambil peran leadership dalam proyek kecil. Ikut pelatihan public speaking dan leadership skills. Belajar dari mentor yang memiliki pengalaman kepemimpinan.\",\"training\":\"Goal Setting & Strategi Pencapaian | Komunikasi Efektif untuk Pemimpin | Influence People: Seni Membangun Pengaruh dalam Tim\"}],\"bottom3\":[{\"code\":\"PE\",\"name\":\"Professional Ethics\",\"score\":7,\"strength\":\"Kamu berintegritas kuat, dapat membuat keputusan etis dengan pertimbangan yang matang, dan bertanggung jawab dengan tindakan yang dilakukan.\",\"weakness\":\"Kamu kadang ragu mengambil keputusan etis saat tertekan dan kurang mempertimbangkan nilai moral dari semua sisi masalah.\",\"activity\":\"Pelajari code of conduct perusahaan secara mendalam. Diskusi ethical dilemmas dengan mentor atau supervisor. Refleksi personal values dan align dengan professional standards.\",\"training\":\"Kode Etik Profesi & Standar Perilaku | Hukum dan Aturan Profesional yang Relevan | Self-Awareness and Ethical Reflection\"},{\"code\":\"GH\",\"name\":\"General Hardskills\",\"score\":9,\"strength\":\"Kamu memiliki keterampilan teknis yang baik, melek teknologi, dan mampu menggunakan alat serta perangkat untuk menganalisis data atau menyelesaikan masalah.\",\"weakness\":\"Kurang update dengan teknologi terbaru, skill teknis yang kurang mendalam, atau tidak mengikuti perkembangan industri.\",\"activity\":\"Ikut pelatihan teknis dan sertifikasi yang relevan. Praktik hands-on dengan tools dan software terbaru. Join community atau forum profesional untuk sharing knowledge.\",\"training\":\"Technical Skills Development | Digital Literacy & Technology Mastery | Data Analysis & Reporting Tools\"},{\"code\":\"CA\",\"name\":\"Career Attitude\",\"score\":10,\"strength\":\"Kamu pantang menyerah, selalu berusaha tumbuh dan meningkatkan diri, serta memiliki ambisi tinggi untuk mencapai tujuan dan kepuasan kerja.\",\"weakness\":\"Terlalu fokus pada diri sendiri, kadang merasa kurang puas dengan progres, dan membuat keputusan tanpa pertimbangan matang.\",\"activity\":\"Buat career planning dan roadmap jangka panjang. Identifikasi skill gaps dan buat plan pengembangan. Network dengan profesional di bidang yang diminati.\",\"training\":\"Etika Profesi & Integritas Personal | Proaktif & Inisiatif: Membangun Karir plan | Growth Mindset for Continuous Development\"}],\"all\":[{\"code\":\"SE\",\"name\":\"Self Esteem\",\"score\":15,\"strength\":\"Kamu percaya diri, tegas dalam bersikap, dan terbuka pada kritik untuk terus mengembangkan diri dan memperbaiki kekurangan.\",\"weakness\":\"Kamu kadang terlalu percaya diri hingga terlihat sombong, dan sering membandingkan diri sendiri, bikin semangat turun.\",\"activity\":\"Set goals yang achievable dan rayakan pencapaian kecil. Identifikasi dan kembangkan kekuatan personal. Cari feedback positif dari rekan kerja dan supervisor.\",\"training\":\"Self-Awareness & Pengenalan Diri Mendalam | Self-Compassion: Berlaku Baik pada Diri Sendiri | Positive Self-Talk & Confidence Building\"},{\"code\":\"CIA\",\"name\":\"Communication & Interpersonal Ability\",\"score\":14,\"strength\":\"Kamu orang yang bertanggung jawab, pendengar yang baik, serta memiliki kemampuan komunikasi yang efektif dalam berinteraksi dan membangun hubungan dengan orang lain.\",\"weakness\":\"Kamu masih ragu untuk speak up di forum besar dan cenderung menghindari konflik, yang bisa menimbulkan miskomunikasi.\",\"activity\":\"Latihan presentasi dan public speaking. Join diskusi aktif dalam meeting. Praktik active listening dan empati dalam komunikasi sehari-hari.\",\"training\":\"Komunikasi Efektif & Assertif | Komunikasi intrapersonal & interpersonal | Empati dalam Interaksi Profesional\"},{\"code\":\"L\",\"name\":\"Leadership\",\"score\":13,\"strength\":\"Kamu memiliki kemampuan untuk mempengaruhi dan membimbing orang lain, dengan ketegasan serta kemampuan analitis untuk mencapai tujuan bersama.\",\"weakness\":\"Kamu susah mendelegasikan tugas, cenderung dominan, dan belum cukup memberi ruang tim untuk berkembang secara mandiri.\",\"activity\":\"Ambil peran leadership dalam proyek kecil. Ikut pelatihan public speaking dan leadership skills. Belajar dari mentor yang memiliki pengalaman kepemimpinan.\",\"training\":\"Goal Setting & Strategi Pencapaian | Komunikasi Efektif untuk Pemimpin | Influence People: Seni Membangun Pengaruh dalam Tim\"},{\"code\":\"SM\",\"name\":\"Self Management\",\"score\":13,\"strength\":\"Kamu bisa memanajemen waktu dengan efektif, disiplin tinggi, serta menjaga work-life balance dengan baik. Kamu juga mampu mengorganisir dan merencanakan kegiatan dengan baik untuk mencapai tujuan.\",\"weakness\":\"Kamu cenderung terlalu fokus sendiri, kurang terbuka berdiskusi atau meminta bantuan, dan belum cukup fleksibel dalam manajemen waktu.\",\"activity\":\"Tetapkan target jangka pendek dan panjang secara realistis. Cari mentor untuk bimbingan karier dan motivasi kerja. Evaluasi dan apresiasi progres pribadi secara berkala.\",\"training\":\"Time Management for Peak Performance | Manajemen Emosi: Kecerdasan Emosional untuk Keunggulan Profesional | Job Priority & Strategic Task Prioritization\"},{\"code\":\"PS\",\"name\":\"Problem Solving\",\"score\":12,\"strength\":\"Kamu peka terhadap masalah, mampu menganalisis situasi dan memahami masalah, serta menciptakan solusi yang efektif untuk mengatasi tantangan.\",\"weakness\":\"Kamu sering overthinking dan ragu ambil tindakan, terutama saat menghadapi masalah rumit, sehingga solusi jadi tertunda.\",\"activity\":\"Latihan case study dan problem solving exercises. Diskusi dengan tim untuk mendapat berbagai perspektif. Dokumentasi solusi yang berhasil untuk referensi masa depan.\",\"training\":\"Analytical Thinking & Data-Driven Decision Making | Critical Thinking for Strategic Solutions | Creative Thinking & Ideation for Breakthrough Solutions\"},{\"code\":\"TS\",\"name\":\"Thinking Skills\",\"score\":12,\"strength\":\"Kamu mendorong inovasi & kreativitas, berpikir kritis, dan mampu menganalisis situasi serta informasi secara efektif untuk menciptakan solusi yang berguna.\",\"weakness\":\"Kamu sering overthinking dan terlalu fokus pada detail, sehingga sulit ambil keputusan cepat atau menyesuaikan saat kondisi berubah.\",\"activity\":\"Main game strategi atau puzzle buat ngasah otak. Ikut workshop inovasi atau brainstorming kelompok. Refleksi hasil keputusan untuk evaluasi cara berpikir.\",\"training\":\"Critical Thinking & Analisis Informasi | Creative Thinking & Ideation Techniques | Problem-Solving & Decision-Making Frameworks\"},{\"code\":\"WWO\",\"name\":\"Work with Others\",\"score\":12,\"strength\":\"Kamu cepat mengatasi masalah, mampu bekerja sama dengan orang lain untuk mencapai tujuan bersama, serta menghargai kontribusi orang lain.\",\"weakness\":\"Kamu susah menghadapi perbedaan pendapat, terlalu mengikuti orang lain, dan kurang percaya diri ambil keputusan sendiri.\",\"activity\":\"Ikut proyek tim untuk melatih kolaborasi. Belajar memberi dan menerima feedback secara konstruktif. Latihan diskusi terbuka untuk menghadapi perbedaan pendapat.\",\"training\":\"Komunikasi Efektif untuk Kolaborasi dalam Tim | Empati dan Kecerdasan Emosional dalam Interaksi Tim | Team Work & Collaboration Excellence\"},{\"code\":\"CA\",\"name\":\"Career Attitude\",\"score\":10,\"strength\":\"Kamu pantang menyerah, selalu berusaha tumbuh dan meningkatkan diri, serta memiliki ambisi tinggi untuk mencapai tujuan dan kepuasan kerja.\",\"weakness\":\"Terlalu fokus pada diri sendiri, kadang merasa kurang puas dengan progres, dan membuat keputusan tanpa pertimbangan matang.\",\"activity\":\"Buat career planning dan roadmap jangka panjang. Identifikasi skill gaps dan buat plan pengembangan. Network dengan profesional di bidang yang diminati.\",\"training\":\"Etika Profesi & Integritas Personal | Proaktif & Inisiatif: Membangun Karir plan | Growth Mindset for Continuous Development\"},{\"code\":\"GH\",\"name\":\"General Hardskills\",\"score\":9,\"strength\":\"Kamu memiliki keterampilan teknis yang baik, melek teknologi, dan mampu menggunakan alat serta perangkat untuk menganalisis data atau menyelesaikan masalah.\",\"weakness\":\"Kurang update dengan teknologi terbaru, skill teknis yang kurang mendalam, atau tidak mengikuti perkembangan industri.\",\"activity\":\"Ikut pelatihan teknis dan sertifikasi yang relevan. Praktik hands-on dengan tools dan software terbaru. Join community atau forum profesional untuk sharing knowledge.\",\"training\":\"Technical Skills Development | Digital Literacy & Technology Mastery | Data Analysis & Reporting Tools\"},{\"code\":\"PE\",\"name\":\"Professional Ethics\",\"score\":7,\"strength\":\"Kamu berintegritas kuat, dapat membuat keputusan etis dengan pertimbangan yang matang, dan bertanggung jawab dengan tindakan yang dilakukan.\",\"weakness\":\"Kamu kadang ragu mengambil keputusan etis saat tertekan dan kurang mempertimbangkan nilai moral dari semua sisi masalah.\",\"activity\":\"Pelajari code of conduct perusahaan secara mendalam. Diskusi ethical dilemmas dengan mentor atau supervisor. Refleksi personal values dan align dengan professional standards.\",\"training\":\"Kode Etik Profesi & Standar Perilaku | Hukum dan Aturan Profesional yang Relevan | Self-Awareness and Ethical Reflection\"}]}', 'AMB', '2025-10-28 23:56:53', '2025-10-28 23:57:21', 'reports/muhammad akmal.pdf', '2025-10-28 18:39:42', '2025-10-28 23:57:21');

-- --------------------------------------------------------

--
-- Table structure for table `test_sessions`
--

CREATE TABLE `test_sessions` (
  `id` varchar(5) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `event_id` varchar(5) DEFAULT NULL,
  `session_token` varchar(32) NOT NULL,
  `current_step` varchar(32) NOT NULL DEFAULT 'form_data',
  `st30_version_id` varchar(10) DEFAULT NULL,
  `participant_name` varchar(50) DEFAULT NULL,
  `participant_background` varchar(50) DEFAULT NULL,
  `position` varchar(25) DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `test_sessions`
--

INSERT INTO `test_sessions` (`id`, `user_id`, `event_id`, `session_token`, `current_step`, `st30_version_id`, `participant_name`, `participant_background`, `position`, `is_completed`, `completed_at`, `created_at`, `updated_at`) VALUES
('TS623', 6, 'EVT25', 'rklesfRhfjogsqw8b6U0afCTLaFNdPS5', 'thanks', NULL, 'Muhammad Akmal', 'BCTI', 'Event Offiver', 1, '2025-10-29 02:39:42', '2025-10-28 18:18:14', '2025-10-28 23:57:21');

-- --------------------------------------------------------

--
-- Table structure for table `typology_descriptions`
--

CREATE TABLE `typology_descriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `typology_code` varchar(5) NOT NULL,
  `typology_name` varchar(30) NOT NULL,
  `strength_description` text DEFAULT NULL,
  `weakness_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typology_descriptions`
--

INSERT INTO `typology_descriptions` (`id`, `typology_code`, `typology_name`, `strength_description`, `weakness_description`, `created_at`, `updated_at`) VALUES
(1, 'AMB', 'Ambassador', 'Bersahabat, menyampaikan dan menjelaskan sesuatu, senang melayani dan bertanggung jawab', 'Sulit berkata tidak, terlalu fokus pada orang lain, menghindari konflik, mudah lelah karena tanggung jawab berlebih, cenderung perfeksionis dalam membantu orang lain', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(2, 'ADM', 'Administrator', 'Memiliki pola kerja yang terstruktur, terencana, rapih, suka melayani serta menjunjung tinggi tanggung jawab dan ketaatan tata tertib', 'Kaku terhadap perubahan, kurang fleksibel, mudah stres saat rencana terganggu, sulit mendelegasikan tugas, terlalu menuntut kesempurnaan dari diri sendiri dan orang lain', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(3, 'ANA', 'Analyst', 'Memiliki ketertarikan dengan hitung-menghitung berhubungan dengan angka, data dan analisis', 'Terlalu fokus pada detail, kurang peka terhadap aspek emosional, cenderung kaku dalam berpikir, sulit melihat gambaran besar, mudah terjebak dalam perhitungan yang rumit', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(4, 'ARR', 'Arranger', 'Suka mengatur seorang atau sekelompok untuk bekerjasama dalam hal penempatan atau penugasan orang, barang ataupun event', 'Cenderung dominan, sulit menerima masukan, terlalu mengontrol, kurang memberi ruang inisiatif orang lain, mudah frustasi jika tim tidak berjalan sesuai rencana', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(5, 'CAR', 'Caretaker', 'Memberikan perhatian atau merawat orang lain yang memiliki masalah fisik, mental, medis atau kesejahteraan umum. mampu merasakan perasaan orang lain serta terdorong membantu orang lain', 'Mudah terbawa perasaan, sulit menjaga batasan pribadi, cepat lelah secara emosional, cenderung mengabaikan kebutuhan diri sendiri, rentan merasa bersalah jika tidak bisa membantu', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(6, 'CMD', 'Commander', 'Memiliki kemampuan mengantur dan mengawasi dalam melaksanakan tugas, tegas, mungkin keras kepala, berani mengambil tanggung jawab', 'Cenderung otoriter, kurang mendengarkan pendapat orang lain, sulit berkompromi, terlalu fokus pada kontrol, bisa terlihat kaku atau kurang empati dalam kepemimpinan', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(7, 'COM', 'Communicator', 'Mudah dalam mengkomunikasikan sesuatu secara sederhana, menarik dan mudah dimengerti', 'Cenderung menyederhanakan hal yang kompleks, kurang mendalam dalam penyampaian, terlalu fokus pada gaya daripada isi, mudah bosan dengan topik yang terlalu teknis atau detail', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(8, 'CRE', 'Creator', 'Memiliki imajinasi dalam suatu rancangan, memiliki ide yang muncul secara spontan dan bervariasi', 'Sulit fokus pada satu ide, mudah terdistraksi, kurang terstruktur, sulit merealisasikan ide, cenderung mengabaikan detail teknis atau praktis', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(9, 'DES', 'Designer', 'Senang membuat gambar atau illustrasi bagunan atau produk yang akan dibuat, memiliki sifat analitis juga memiliki beragam ide kreatif', 'Cenderung terjebak pada detail visual, sulit memilih ide utama, perfeksionis, lambat ambil keputusan, dan kurang kolaboratif', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(10, 'DIS', 'Distributor', 'Memiliki sifat ulet, teguh dalam beraktivitas pekerjaan mencakup mengirimkan sesuatu berupa barang, surat atau artikel pada saat yang hampir bersamaan', 'Cenderung bekerja berulang tanpa refleksi, mudah lelah secara fisik, kurang fleksibel saat situasi berubah, dan minim inovasi dalam metode', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(11, 'EDU', 'Educator', 'Mengajar, membimbing, menyampaikan, melatih ilmua dan/atau keterampilan agar bisa dipahami orang lain, selalu ingin memajukan orang lain dan senang melihat kemajuan orang yang dibimbingnya', 'Cenderung terlalu mengontrol, sulit menerima metode belajar berbeda, mudah frustrasi saat perkembangan lambat, dan mengabaikan kebutuhan dirinya sendiri', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(12, 'EVA', 'Evaluator', 'Mengumpulkan informasi, mempelajari dan menimbang dalam rangka memutuskan sesuatu terkait nilai. mutu, kepentingan atau kondisi', 'Terlalu lama dalam mengambil keputusan, cenderung perfeksionis, sulit menerima ketidakpastian, dan bisa mengabaikan dinamika atau konteks sosial', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(13, 'EXP', 'Explorer', 'Senang mempelajari latar belakang, senang berolah pikir dan melakukan penelitian untuk menemukan fakta-fakta', 'Terlalu banyak menganalisis, sulit mengambil keputusan cepat, tenggelam dalam detail, cenderung skeptis, kurang responsif terhadap hal yang bersifat praktis atau instan', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(14, 'INT', 'Interpreter', 'Suka menjelaskan arti atau makna dari sesuatu sehingga mudah dipahami orang lain, senang berkomunikasi baik dalam bentuk tulisan dan lisan', 'Cenderung terlalu banyak bicara, sulit menyampaikan secara singkat, mudah terjebak dalam penjelasan yang berulang, kurang mendengarkan, terlalu fokus pada sudut pandang sendiri', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(15, 'JOU', 'Journalist', 'Senang mengkomunikasikan idenya, suka mengumpulkan berbagai informasi dengan rapih, terstruktur dan teratur', 'Terlalu terpaku pada struktur, sulit menerima perubahan mendadak, cenderung overthinking saat ide tidak tersampaikan dengan baik, kurang fleksibel dalam menyusun informasi, bisa terjebak dalam perencanaan tanpa eksekusi', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(16, 'MAR', 'Marketer', 'Senang berpikiran strategis, menyampaikan atau mengkomuikasikan sesuatu, memiliki ide kreatif dan senang menonjolkan kelebihan produk/jasa yang diusungnya', 'Cenderung terlalu fokus pada gambaran besar, kurang memperhatikan detail teknis, bisa terlalu percaya diri, berisiko memaksakan ide, dan mudah kecewa jika ide tidak diterima dengan antusias', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(17, 'MED', 'Mediator', 'Mampu mengatasi dan menyelesaikan konflik antara dua belah pihak yang berselisih, tegas menghadapi orang dan tidak menyukai konflik', 'Cenderung menghindari konfrontasi langsung, bisa terlihat terlalu mengontrol, sulit bersikap netral sepenuhnya, mudah terbebani secara emosional, dan kurang fleksibel dalam menghadapi dinamika emosi orang lain', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(18, 'MOT', 'Motivator', 'Senang memberikan semangat kepada individu atau sekelompok agar bisa menjadi lebih baik, melalui gaya dan stylenya sendiri', 'Fokus pada gaya bukan isi, tampak berlebihan, mudah kecewa tanpa respons positif, kurang peka pada kebutuhan gaya berbeda', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(19, 'OPE', 'Operator', 'Senang menjalankan, mengoperasikan dan merawat mesin, peralatan, proses atau sistem, senang melayani, teratur, disiplin, serta gigih bekerja', 'Kurang fleksibel terhadap perubahan, cenderung monoton, sulit beradaptasi dengan hal baru, terlalu fokus pada prosedur, dan kurang tertarik pada aspek kreatif atau inovatif', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(20, 'PRO', 'Producer', 'Senang memasang, memproduksi, membangun mesin, perangkat atau bangunan, sosok pekerja keras, teratur dan gerak cepat', 'Kurang sabar saat lambat, abaikan rencana jangka panjang, terburu ambil keputusan, cuek emosi, fokus hasil, lupakan proses', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(21, 'QCA', 'Quality Controller', 'Suka mengawasi dan memeriksa suatu proses pembuatan produk/jasa sesuai dengan ketentuan kualitas/estetika (SOP)/ sosok perfeksionis, teliti dan fokus', 'Terlalu perfeksionis, sulit puas dengan hasil, cenderung micromanage, mudah stres jika standar tidak terpenuhi, dan kurang fleksibel terhadap improvisasi atau perubahan mendadak', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(22, 'RES', 'Restorer', 'Menyukai memperbaiki atau memulihkan sesuatu ke fungsi normalnya atau lebih baik, senang mengutak-atik, mencari tahu sistem kerja dan mengembalikan fungsi sesuatu', 'Terpaku cara lama, sulit menerima kegagalan, mudah frustrasi tanpa solusi, terlalu teknis, abaikan aspek emosional dan sosial', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(23, 'SAF', 'Safekeeper', 'Memiliki sifat teliti, hati-hati, waspada dan memegang teguh tanggung jawab', 'Lambat ambil keputusan, terlalu khawatir salah, sulit ambil risiko, kurang spontan, overthinking saat menghadapi tugas', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(24, 'SEL', 'Seller', 'Umumnya menyukai berhubungan dengan orang lain, baik untuk mempengaruhi, bekerjasama atau melayani', 'Mudah terpengaruh oleh opini orang lain, sulit bekerja sendiri, cenderung menghindari konflik demi menjaga hubungan, terlalu fokus menyenangkan orang lain, dan rentan kelelahan sosial', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(25, 'SER', 'Server', 'Memiliki sifat yang tulus dalam bekerja dan melayani orang lain, suka melayani orang lain dan mendahulukan kepantingan orang lain', 'Cenderung mengabaikan kebutuhan diri sendiri, mudah dimanfaatkan, sulit mengatakan tidak, cepat merasa bersalah, dan rentan kelelahan emosional karena terlalu banyak memberi', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(26, 'SLC', 'Selector', 'Memiliki kemampuan untuk memilih dan merekrut seseorang sesuai dengan apa yang dibutuhkan, memiliki insting kuat dalam melihat keunikan sifat orang lain sehingga dapat memperkirakan potensinya', 'Menilai pakai intuisi tanpa data, bias kesan pertama, terlalu percaya diri, sulit objektif, rawan salah menempatkan orang', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(27, 'STR', 'Strategist', 'Memiliki kemampuan perencanaan yang baik untuk mencapai goals, memiliki insting kuat dalam memilih solusi yang tepat, bijaksana dan penuh pertimbangan', 'Cenderung overthinking, terlalu lama dalam mengambil keputusan, sulit bersikap spontan, terlalu banyak mempertimbangkan risiko, dan bisa kurang fleksibel dalam situasi mendesak', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(28, 'SYN', 'Synthesizer', 'Mengkombinasikan berbagai elemen, ide dan informasi menjadi sesuatu yang baru seperti menggabungkan beberapa ide, teori atau temuan menjadi suatu temuan baru', 'Berpikir terlalu kompleks, sulit menyederhanakan ide, kehilangan fokus tujuan, terlalu banyak eksperimen, ide terus berkembang, sulit diselesaikan', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(29, 'TRE', 'Treasurer', 'Memiliki kemampuan analitis, rapi, teratur, teliti dan bertanggung jawab dalam menata keuangan dengan catatan yang rapih, tertata dan tanpa kesalahan', 'Kaku dalam pengelolaan, sulit berimprovisasi, mudah stres saat ada kesalahan, perfeksionis, terlalu fokus detail, abaikan gambaran besar', '2025-08-19 22:19:14', '2025-08-19 22:19:14'),
(30, 'VIS', 'Visionary', 'Senang memimpikan apa yang mungkin terjadi jauh ke masa depan sehingga dapat menentukan tujuan jangka panjang yang benar', 'Kurang fokus pada langkah konkret, mudah berangan-angan, sulit menyesuaikan realita, abaikan detail teknis, sulit adaptasi perubahan', '2025-08-19 22:19:14', '2025-08-19 22:19:14');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `google_id` varchar(191) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(60) NOT NULL,
  `role` enum('user','pic','staff','admin') NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone_number`, `google_id`, `email_verified_at`, `password`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Muhammad Akmal', 'admin@talentmapping.com', NULL, NULL, NULL, '$2y$12$qphKXQgRcNZaqlZykkMtkeIpnFWPTvF4cFj40EpMby53yIGxPmXBW', 'admin', 1, NULL, '2025-08-18 20:12:11', '2025-10-01 19:29:00'),
(6, 'Muhammad Akmal', 'mhammadakmall@gmail.com', '082252957879', NULL, NULL, '$2y$12$RdYHoNNvuXCSSxNM6lvMJ.EVZ.q34RigPpZ3zQcOfABjXbh3EDBN6', 'user', 1, NULL, '2025-09-03 20:06:06', '2025-10-30 18:07:32'),
(7, 'Pieter', 'pietersonnn@gmail.com', NULL, NULL, NULL, '$2y$12$MGTwHAUVILKWB5stgrpizOhvIqWgnnaCcn8lrezHQMQgMrFHnMFji', 'pic', 1, NULL, '2025-09-05 18:40:08', '2025-09-05 18:40:08'),
(8, 'Muhammad Faisal', 'akmal.parlon@gmail.com', NULL, NULL, NULL, '$2y$12$veSsTjY4EV0tURGfbsQ5IeIMzzB.yvPDxmJ0XGxYTbZb8dCPwT2hy', 'user', 1, NULL, '2025-09-21 20:28:52', '2025-09-21 20:28:52'),
(9, 'Tito Setiyanto', 'fetyf115@gmail.com', NULL, NULL, NULL, '$2y$12$3sJlWYaD/oVvcFvYIPAV9e0PO/ibBpJH/tZD1WR5YlT6E5YhevW72', 'user', 1, NULL, '2025-09-23 00:13:28', '2025-09-25 17:25:37'),
(10, 'Fetty Fatimah', 'bcti@hasnurcentre.com', NULL, NULL, NULL, '$2y$12$RjsHilr0Zr1Nn2SIb5zNHe8bLRDTiXcqZnYNKiyosZdYQWl1wFs8m', 'user', 1, NULL, '2025-09-25 04:46:37', '2025-09-28 18:40:13'),
(11, 'Muhammad Akmal', 'mhammadakmalll@gmail.com', NULL, '106390106697241079700', NULL, '$2y$12$CMkuHsfcI7kCfCV.ZFLInen3plzu/Nz/xUlt3gSJ6KzPdq2dRtKr6', 'user', 1, 'MkRCNRVZtCQdzDGSaO2DXT96nTxFzxBQSIEQRabce5okQWipZNatp4RjKIj0', '2025-10-30 18:38:31', '2025-10-30 18:38:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`),
  ADD KEY `activity_logs_action_created_at_index` (`action`,`created_at`);

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
-- Indexes for table `competency_descriptions`
--
ALTER TABLE `competency_descriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `competency_descriptions_competency_code_unique` (`competency_code`),
  ADD KEY `competency_descriptions_competency_name_index` (`competency_name`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `events_event_code_unique` (`event_code`),
  ADD KEY `events_pic_id_foreign` (`pic_id`),
  ADD KEY `events_is_active_start_date_end_date_index` (`is_active`,`start_date`,`end_date`);

--
-- Indexes for table `event_participants`
--
ALTER TABLE `event_participants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `event_participants_event_id_user_id_unique` (`event_id`,`user_id`),
  ADD KEY `event_participants_user_id_foreign` (`user_id`),
  ADD KEY `event_participants_test_completed_results_sent_index` (`test_completed`,`results_sent`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`),
  ADD KEY `password_reset_tokens_token_created_at_index` (`token`,`created_at`);

--
-- Indexes for table `question_versions`
--
ALTER TABLE `question_versions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `question_versions_type_version_unique` (`type`,`version`),
  ADD KEY `question_versions_is_active_index` (`is_active`);

--
-- Indexes for table `resend_requests`
--
ALTER TABLE `resend_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resend_requests_user_id_foreign` (`user_id`),
  ADD KEY `resend_requests_approved_by_foreign` (`approved_by`),
  ADD KEY `resend_requests_status_index` (`status`),
  ADD KEY `resend_requests_test_result_id_foreign` (`test_result_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sjt_options`
--
ALTER TABLE `sjt_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sjt_options_question_id_foreign` (`question_id`),
  ADD KEY `sjt_options_competency_target_index` (`competency_target`),
  ADD KEY `sjt_options_option_letter_index` (`option_letter`);

--
-- Indexes for table `sjt_questions`
--
ALTER TABLE `sjt_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sjt_questions_version_id_foreign` (`version_id`),
  ADD KEY `sjt_questions_competency_index` (`competency`),
  ADD KEY `sjt_questions_number_index` (`number`),
  ADD KEY `idx_sjt_questions_version_number` (`version_id`,`number`);

--
-- Indexes for table `sjt_responses`
--
ALTER TABLE `sjt_responses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_session_question` (`session_id`,`question_id`),
  ADD KEY `sjt_responses_session_id_question_id_page_number_index` (`session_id`,`question_id`,`page_number`),
  ADD KEY `sjt_responses_question_id_foreign` (`question_id`),
  ADD KEY `sjt_responses_question_version_id_foreign` (`question_version_id`);

--
-- Indexes for table `st30_questions`
--
ALTER TABLE `st30_questions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `st30_questions_version_id_number_unique` (`version_id`,`number`),
  ADD KEY `st30_questions_typology_code_is_active_index` (`typology_code`,`is_active`),
  ADD KEY `idx_st30_questions_version_active_number` (`version_id`,`is_active`,`number`);

--
-- Indexes for table `st30_responses`
--
ALTER TABLE `st30_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `st30_responses_session_id_stage_number_for_scoring_index` (`session_id`,`stage_number`,`for_scoring`),
  ADD KEY `idx_st30_responses_session_stage` (`session_id`,`stage_number`),
  ADD KEY `idx_st30_responses_version` (`question_version_id`);

--
-- Indexes for table `test_results`
--
ALTER TABLE `test_results`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `test_results_session_id_unique` (`session_id`),
  ADD KEY `test_results_dominant_typology_email_sent_at_index` (`dominant_typology`,`email_sent_at`);

--
-- Indexes for table `test_sessions`
--
ALTER TABLE `test_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `test_sessions_session_token_unique` (`session_token`),
  ADD KEY `test_sessions_user_id_foreign` (`user_id`),
  ADD KEY `test_sessions_current_step_is_completed_index` (`current_step`,`is_completed`),
  ADD KEY `test_sessions_event_id_foreign` (`event_id`);

--
-- Indexes for table `typology_descriptions`
--
ALTER TABLE `typology_descriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `typology_descriptions_typology_code_unique` (`typology_code`),
  ADD KEY `typology_descriptions_typology_name_index` (`typology_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_is_active_index` (`role`,`is_active`),
  ADD KEY `idx_users_google_id` (`google_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `competency_descriptions`
--
ALTER TABLE `competency_descriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `event_participants`
--
ALTER TABLE `event_participants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `sjt_responses`
--
ALTER TABLE `sjt_responses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1601;

--
-- AUTO_INCREMENT for table `typology_descriptions`
--
ALTER TABLE `typology_descriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_pic_id_foreign` FOREIGN KEY (`pic_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `event_participants`
--
ALTER TABLE `event_participants`
  ADD CONSTRAINT `event_participants_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_participants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `resend_requests`
--
ALTER TABLE `resend_requests`
  ADD CONSTRAINT `resend_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `resend_requests_test_result_id_foreign` FOREIGN KEY (`test_result_id`) REFERENCES `test_results` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `resend_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sjt_options`
--
ALTER TABLE `sjt_options`
  ADD CONSTRAINT `fk_sjt_options_competency` FOREIGN KEY (`competency_target`) REFERENCES `competency_descriptions` (`competency_code`),
  ADD CONSTRAINT `fk_sjt_options_question` FOREIGN KEY (`question_id`) REFERENCES `sjt_questions` (`id`),
  ADD CONSTRAINT `sjt_options_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `sjt_questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sjt_questions`
--
ALTER TABLE `sjt_questions`
  ADD CONSTRAINT `sjt_questions_version_id_foreign` FOREIGN KEY (`version_id`) REFERENCES `question_versions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sjt_responses`
--
ALTER TABLE `sjt_responses`
  ADD CONSTRAINT `sjt_responses_question_version_id_foreign` FOREIGN KEY (`question_version_id`) REFERENCES `question_versions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sjt_responses_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `test_sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `st30_questions`
--
ALTER TABLE `st30_questions`
  ADD CONSTRAINT `st30_questions_version_id_foreign` FOREIGN KEY (`version_id`) REFERENCES `question_versions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `st30_responses`
--
ALTER TABLE `st30_responses`
  ADD CONSTRAINT `st30_responses_question_version_id_foreign` FOREIGN KEY (`question_version_id`) REFERENCES `question_versions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `st30_responses_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `test_sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `test_results`
--
ALTER TABLE `test_results`
  ADD CONSTRAINT `test_results_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `test_sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `test_sessions`
--
ALTER TABLE `test_sessions`
  ADD CONSTRAINT `test_sessions_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `test_sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
