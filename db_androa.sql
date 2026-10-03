-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Des 2025 pada 08.28
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_androa`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `articles`
--

CREATE TABLE `articles` (
  `article_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `views` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `articles`
--

INSERT INTO `articles` (`article_id`, `author_id`, `title`, `content`, `image_path`, `status`, `views`, `created_at`, `updated_at`) VALUES
(13, 13, 'Sejarah dan Perkembangan Fashion Unisex', 'Fashion unisex memiliki perjalanan panjang yang berawal dari perlawanan terhadap aturan berpakaian yang sangat kaku. Di awal abad ke-20, perempuan mulai menuntut kebebasan untuk mengenakan pakaian yang lebih fungsional, dan tokoh seperti Coco Chanel menjadi ikon perubahan tersebut. Ia merancang pakaian yang tidak lagi terikat standar feminin tradisional, seperti celana panjang dan setelan yang terinspirasi dari pakaian pria. Langkah ini memicu perubahan besar dalam dunia mode, membuka pintu bagi gagasan bahwa pakaian tidak harus dikendalikan oleh gender.\r\n\r\nPada era 1960–1980an, budaya pop ikut memperkuat gerakan ini. Musik rock, glam, dan androgini menjadi sorotan, mendorong generasi muda untuk tampil dengan gaya yang lebih bebas. Memasuki era modern, desainer seperti Rad Hourani, Yohji Yamamoto, dan Rick Owens memperkenalkan koleksi resmi yang benar-benar gender-neutral. Mereka membawa fashion unisex ke panggung global dengan desain minimalis yang menekankan kebebasan dan kesederhanaan. Perjalanan panjang inilah yang menjadikan fashion unisex bukan sekadar tren, tetapi bagian penting dari evolusi budaya berpakaian.', 'article_images/article_1765000499.jpg', 'published', 0, '2025-12-06 05:54:59', '2025-12-06 05:54:59'),
(14, 13, 'Pengaruh Budaya Pop dan Gaya Hidup Modern', 'Perkembangan fashion unisex semakin pesat seiring perubahan budaya dan pola pikir masyarakat modern. Generasi muda kini lebih vokal dalam memperjuangkan kesetaraan dan keberagaman, termasuk dalam hal ekspresi melalui pakaian. Media sosial turut memainkan peran besar, karena gaya unisex mudah menyebar melalui konten kreatif di Instagram, TikTok, dan platform lain. Influencer dan komunitas digital memperkenalkan gaya yang fleksibel, membuat fashion unisex semakin diterima oleh berbagai kalangan.\r\n\r\nSelain itu, selebriti dunia seperti Harry Styles, Billie Eilish, dan Jaden Smith menjadi ikon yang mendorong normalisasi pakaian gender-neutral. Mereka tampil tanpa mengikuti standar maskulin atau feminin, mengirim pesan bahwa berpakaian adalah bentuk ekspresi diri, bukan batasan. Brand dan rumah mode besar pun mulai mengikuti tren ini dengan meluncurkan koleksi unisex yang lebih luas. Semua faktor ini menjadikan fashion unisex bukan hanya gaya, tetapi bagian dari perubahan besar dalam cara masyarakat melihat identitas dan kebebasan personal.', 'article_images/article_1765000606.jpg', 'published', 0, '2025-12-06 05:55:51', '2025-12-06 05:56:46'),
(15, 13, 'Ciri, Desain, dan Konsep Pakaian Unisex', 'Fashion unisex memiliki ciri desain yang khas, yaitu berfokus pada kenyamanan, kesederhanaan, dan fleksibilitas untuk semua gender. Pakaian unisex cenderung menggunakan warna netral, seperti hitam, putih, abu-abu, dan earth tone, yang mudah dipadukan. Siluetnya lebih longgar atau oversize sehingga dapat menyesuaikan berbagai bentuk tubuh tanpa menonjolkan unsur gender tertentu. Material yang digunakan pun biasanya universal dan nyaman, seperti katun, linen, dan denim, sehingga cocok untuk pemakaian sehari-hari.\r\n\r\nKonsep desain unisex juga menekankan fungsi dan aksesibilitas. Banyak brand menghadirkan hoodie, kaos basic, kemeja boxy, hingga celana cargo yang dibuat agar dapat dipakai siapa saja. Tidak ada pembagian \"khusus pria\" atau \"khusus wanita\", melainkan fokus pada bagaimana pakaian dapat meningkatkan rasa percaya diri dan kebebasan bergerak. Dengan pendekatan yang lebih inklusif ini, fashion unisex menunjukkan bahwa mode dapat menjadi ruang untuk merayakan identitas tanpa harus mengikuti batasan yang diwariskan oleh tradisi.', 'article_images/article_1765000645.jpg', 'published', 0, '2025-12-06 05:57:25', '2025-12-06 05:57:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `post_type` enum('article','gallery') NOT NULL,
  `guest_name` varchar(50) DEFAULT NULL,
  `content` text NOT NULL,
  `is_moderated` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `post_id` int(11) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `comments`
--

INSERT INTO `comments` (`comment_id`, `user_id`, `post_type`, `guest_name`, `content`, `is_moderated`, `created_at`, `post_id`, `article_id`) VALUES
(51, 13, 'gallery', NULL, 'dayuummm', 0, '2025-12-06 06:08:41', 30, NULL),
(52, 13, 'gallery', NULL, 'woow', 0, '2025-12-06 06:09:00', 29, NULL),
(53, 14, 'gallery', NULL, '2 bocah kecill', 0, '2025-12-06 06:09:38', 31, NULL),
(54, 15, 'gallery', NULL, 'anjayy', 0, '2025-12-06 06:10:16', 30, NULL),
(55, 15, 'gallery', NULL, 'iya lagii <3', 0, '2025-12-06 06:10:27', 31, NULL),
(56, 16, 'gallery', NULL, 'mirip jg sama gw..', 0, '2025-12-06 06:11:22', 28, NULL),
(57, 16, 'gallery', NULL, 'cantikk jg', 0, '2025-12-06 06:11:44', 27, NULL),
(58, 16, 'article', NULL, 'owalaahhh', 0, '2025-12-06 06:12:39', NULL, 15),
(59, 17, 'article', NULL, 'keren yaaa', 0, '2025-12-06 06:13:12', NULL, 13),
(60, 15, 'article', NULL, 'gw mau coba style cowo ahhh', 0, '2025-12-06 06:13:46', NULL, 15),
(61, 14, 'article', NULL, 'owow', 0, '2025-12-06 06:35:04', NULL, 14),
(63, 15, 'gallery', NULL, 'shopee ada kak', 0, '2025-12-06 06:48:48', 33, NULL),
(64, 15, 'gallery', NULL, 'masasih', 0, '2025-12-06 06:49:18', 32, NULL),
(65, 17, 'gallery', NULL, 'boleh', 0, '2025-12-06 06:52:02', 34, NULL),
(66, 17, 'gallery', NULL, 'iya lagi mirip dikit', 0, '2025-12-06 06:52:16', 32, NULL),
(67, 17, 'gallery', NULL, 'wow', 0, '2025-12-06 06:52:27', 29, NULL),
(68, 17, 'gallery', NULL, 'gantengg', 0, '2025-12-06 06:52:36', 30, NULL),
(69, 17, 'gallery', NULL, 'aduhh aku imut banget ', 0, '2025-12-06 06:52:50', 31, NULL),
(70, 15, 'gallery', NULL, 'umm bole', 0, '2025-12-06 06:55:27', 35, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `gallery_posts`
--

CREATE TABLE `gallery_posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `caption` text DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `style_category` varchar(50) NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `gallery_posts`
--

INSERT INTO `gallery_posts` (`post_id`, `user_id`, `caption`, `image_path`, `style_category`, `is_approved`, `created_at`) VALUES
(27, 17, 'aduh cantik banget dia, keren pake baju ituuu!!', 'gallery_uploads/post_17_1765000800.jpg', 'Casual', 0, '2025-12-06 06:00:00'),
(28, 14, 'keren yaa klo cowo pake jaket kulit gini', 'gallery_uploads/post_14_1765000892.jpg', 'Casual', 0, '2025-12-06 06:01:32'),
(29, 15, 'asek...!', 'gallery_uploads/post_15_1765000998.jpg', 'Streetwear', 0, '2025-12-06 06:03:18'),
(30, 16, 'woowww..', 'gallery_uploads/post_16_1765001107.jpg', 'Formal', 0, '2025-12-06 06:05:07'),
(31, 13, 'aseeekkkk :v', 'gallery_uploads/post_13_1765001299.jpeg', 'Experimental', 0, '2025-12-06 06:08:19'),
(32, 16, 'wait... i actually kinda look like him...', 'gallery_uploads/post_16_1765003422.jpg', 'Formal', 0, '2025-12-06 06:43:42'),
(33, 14, 'p beli dimana inih... plz komen', 'gallery_uploads/post_14_1765003684.jpg', 'Experimental', 0, '2025-12-06 06:48:04'),
(34, 13, 'mw coba pake baju kaya gini dech', 'gallery_uploads/post_13_1765003875.jpg', 'Experimental', 0, '2025-12-06 06:51:15'),
(35, 17, 'dia role model aku', 'gallery_uploads/post_17_1765004098.jpg', 'Sporty', 0, '2025-12-06 06:54:58'),
(36, 15, 'oufit of the day', 'gallery_uploads/post_15_1765004647.jpg', 'Streetwear', 0, '2025-12-06 07:04:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `likes`
--

CREATE TABLE `likes` (
  `like_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_type` enum('article','gallery') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `post_id` int(11) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `likes`
--

INSERT INTO `likes` (`like_id`, `user_id`, `post_type`, `created_at`, `post_id`, `article_id`) VALUES
(153, 13, 'article', '2025-12-06 05:57:47', NULL, 15),
(155, 15, 'gallery', '2025-12-06 06:03:24', 29, NULL),
(156, 15, 'gallery', '2025-12-06 06:03:25', 27, NULL),
(157, 13, 'gallery', '2025-12-06 06:08:23', 31, NULL),
(158, 13, 'gallery', '2025-12-06 06:08:26', 30, NULL),
(159, 13, 'gallery', '2025-12-06 06:08:27', 29, NULL),
(160, 13, 'gallery', '2025-12-06 06:08:30', 27, NULL),
(161, 14, 'gallery', '2025-12-06 06:09:18', 31, NULL),
(162, 14, 'gallery', '2025-12-06 06:09:19', 30, NULL),
(163, 14, 'gallery', '2025-12-06 06:09:20', 28, NULL),
(164, 14, 'gallery', '2025-12-06 06:09:42', 29, NULL),
(165, 15, 'gallery', '2025-12-06 06:10:35', 31, NULL),
(166, 16, 'gallery', '2025-12-06 06:10:47', 31, NULL),
(171, 16, 'gallery', '2025-12-06 06:11:32', 30, NULL),
(172, 16, 'gallery', '2025-12-06 06:11:37', 28, NULL),
(173, 16, 'article', '2025-12-06 06:12:26', NULL, 14),
(174, 16, 'article', '2025-12-06 06:12:27', NULL, 13),
(175, 16, 'article', '2025-12-06 06:12:28', NULL, 15),
(176, 17, 'article', '2025-12-06 06:12:58', NULL, 15),
(177, 17, 'article', '2025-12-06 06:12:59', NULL, 14),
(178, 17, 'article', '2025-12-06 06:13:00', NULL, 13),
(179, 15, 'article', '2025-12-06 06:13:23', NULL, 15),
(180, 15, 'article', '2025-12-06 06:13:24', NULL, 13),
(181, 13, 'article', '2025-12-06 06:14:00', NULL, 14),
(183, 13, 'article', '2025-12-06 06:14:06', NULL, 13),
(184, 14, 'article', '2025-12-06 06:35:08', NULL, 14),
(185, 16, 'gallery', '2025-12-06 06:44:39', 32, NULL),
(186, 14, 'gallery', '2025-12-06 06:48:10', 33, NULL),
(187, 15, 'gallery', '2025-12-06 06:48:59', 33, NULL),
(188, 15, 'gallery', '2025-12-06 06:49:01', 32, NULL),
(189, 15, 'gallery', '2025-12-06 06:49:02', 30, NULL),
(190, 17, 'gallery', '2025-12-06 06:51:57', 34, NULL),
(191, 17, 'gallery', '2025-12-06 06:53:01', 27, NULL),
(192, 17, 'gallery', '2025-12-06 06:55:01', 35, NULL),
(193, 15, 'gallery', '2025-12-06 07:04:15', 36, NULL),
(194, 15, 'gallery', '2025-12-06 07:04:22', 35, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `avatar_path` varchar(255) DEFAULT '/assets/profile-default.jpg',
  `role` enum('guest','user','admin') NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `full_name`, `bio`, `avatar_path`, `role`, `is_active`, `created_at`) VALUES
(13, 'dhika', 'dhika@gmail.com', 'dhika123', 'Dhika', '', '../../Asset/avatars/avatar_13_1765000154.jpeg', 'admin', 1, '2025-12-06 05:45:28'),
(14, 'vero', 'vero@gmail.com', 'vero123', 'vero', '', '../../Asset/avatars/avatar_14_1765000070.jpeg', 'admin', 1, '2025-12-06 05:46:16'),
(15, 'aca', 'aca@gmail.com', 'aca123', 'aca', '', '../../Asset/avatars/avatar_15_1765000094.jpeg', 'admin', 1, '2025-12-06 05:46:30'),
(16, 'rafhael', 'rafhael@gmail.com', 'rafa123', 'Rafhael', '', '../../Asset/avatars/avatar_16_1765000136.jpeg', 'user', 1, '2025-12-06 05:46:48'),
(17, 'louis', 'louis@gmail.com', 'louis123', 'louis', '', '../../Asset/avatars/avatar_17_1765000113.jpeg', 'user', 1, '2025-12-06 05:47:08');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`article_id`),
  ADD KEY `articles_ibfk_1` (`author_id`);

--
-- Indeks untuk tabel `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `article_id` (`article_id`),
  ADD KEY `comments_ibfk_1` (`user_id`);

--
-- Indeks untuk tabel `gallery_posts`
--
ALTER TABLE `gallery_posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `gallery_posts_ibfk_1` (`user_id`);

--
-- Indeks untuk tabel `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `article_id` (`article_id`),
  ADD KEY `likes_ibfk_1` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `articles`
--
ALTER TABLE `articles`
  MODIFY `article_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT untuk tabel `gallery_posts`
--
ALTER TABLE `gallery_posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT untuk tabel `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_article` FOREIGN KEY (`article_id`) REFERENCES `articles` (`article_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_gallery` FOREIGN KEY (`post_id`) REFERENCES `gallery_posts` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `gallery_posts`
--
ALTER TABLE `gallery_posts`
  ADD CONSTRAINT `gallery_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `like_article` FOREIGN KEY (`article_id`) REFERENCES `articles` (`article_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `like_gallery` FOREIGN KEY (`post_id`) REFERENCES `gallery_posts` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
