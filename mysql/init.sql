-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2025 at 12:27 AM
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
-- Database: `pkb1`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password_hash`, `created_at`) VALUES
(1, 'organizer', '$2y$10$mR3Pj9n4gZofu0er7oSzHOSEXXVzvDklnyzbnr4QQtAoMCrvU8t/m', '2025-10-10 15:50:04');

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `id` int(11) NOT NULL,
  `team1_id` int(11) NOT NULL,
  `team2_id` int(11) NOT NULL,
  `team1_score` int(11) DEFAULT 0,
  `team2_score` int(11) DEFAULT 0,
  `match_time` time NOT NULL,
  `court_num` int(11) DEFAULT NULL,
  `winner_id` int(11) DEFAULT NULL,
  `stage` enum('Group Stage','Bronze Match','Final Stage') NOT NULL DEFAULT 'Group Stage',
  `status` tinyint(4) DEFAULT 1 COMMENT '1 = upcoming, 2 = completed',
  `player1_id` int(11) DEFAULT NULL,
  `player2_id` int(11) DEFAULT NULL,
  `referee` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matches`
--

INSERT INTO `matches` (`id`, `team1_id`, `team2_id`, `team1_score`, `team2_score`, `match_time`, `court_num`, `winner_id`, `stage`, `status`, `player1_id`, `player2_id`, `referee`) VALUES
(36, 29, 28, 0, 0, '10:00:00', 1, NULL, 'Group Stage', 1, 126, 120, 'Amir Izzat'),
(37, 35, 34, 0, 0, '10:15:00', 1, NULL, 'Group Stage', 1, 162, 156, 'ITAS'),
(38, 31, 30, 0, 0, '10:30:00', 1, NULL, 'Group Stage', 1, 138, 132, 'DSM'),
(39, 33, 32, 0, 0, '10:45:00', 1, NULL, 'Group Stage', 1, 150, 144, 'ITOC'),
(40, 29, 30, 0, 0, '11:00:00', 1, NULL, 'Group Stage', 1, 126, 132, 'STQA'),
(41, 35, 33, 0, 0, '11:15:00', 1, NULL, 'Group Stage', 1, 162, 150, 'AOM'),
(42, 28, 31, 0, 0, '11:30:00', 1, NULL, 'Group Stage', 1, 120, 138, 'ITGC'),
(43, 34, 32, 0, 0, '11:45:00', 1, NULL, 'Group Stage', 1, 156, 144, 'ADD'),
(44, 29, 31, 0, 0, '12:00:00', 1, NULL, 'Group Stage', 1, 126, 138, 'ITIM'),
(45, 35, 32, 0, 0, '12:15:00', 1, NULL, 'Group Stage', 1, 162, 144, 'ITAS'),
(46, 28, 30, 0, 0, '12:30:00', 1, NULL, 'Group Stage', 1, 120, 132, 'DSM'),
(47, 33, 34, 0, 0, '12:45:00', 1, NULL, 'Group Stage', 1, 150, 156, 'ITOC'),
(48, 31, 30, 0, 0, '10:00:00', 2, NULL, 'Group Stage', 1, 139, 132, 'DSM'),
(49, 33, 32, 0, 0, '10:15:00', 2, NULL, 'Group Stage', 1, 151, 145, 'ITOC'),
(50, 29, 28, 0, 0, '10:30:00', 2, NULL, 'Group Stage', 1, 127, 121, 'STQA'),
(51, 35, 34, 0, 0, '10:45:00', 2, NULL, 'Group Stage', 1, 163, 157, 'AOM'),
(52, 28, 31, 0, 0, '11:00:00', 2, NULL, 'Group Stage', 1, 121, 139, 'ITGC'),
(53, 34, 32, 0, 0, '11:15:00', 2, NULL, 'Group Stage', 1, 157, 145, 'ADD'),
(54, 29, 30, 0, 0, '11:30:00', 2, NULL, 'Group Stage', 1, 127, 133, 'ITIM'),
(55, 35, 33, 0, 0, '11:45:00', 2, NULL, 'Group Stage', 1, 163, 151, 'ITAS'),
(56, 28, 30, 0, 0, '12:00:00', 2, NULL, 'Group Stage', 1, 121, 133, 'DSM'),
(57, 33, 34, 0, 0, '12:15:00', 2, NULL, 'Group Stage', 1, 151, 157, 'ITOC'),
(58, 29, 31, 0, 0, '12:30:00', 2, NULL, 'Group Stage', 1, 127, 139, 'STQA'),
(59, 35, 32, 0, 0, '12:45:00', 2, NULL, 'Group Stage', 1, 163, 145, 'AOM'),
(60, 29, 28, 0, 0, '10:00:00', 3, NULL, 'Group Stage', 1, 128, 122, 'STQA'),
(61, 35, 34, 0, 0, '10:15:00', 3, NULL, 'Group Stage', 1, 164, 158, 'AOM'),
(62, 31, 30, 0, 0, '10:30:00', 3, NULL, 'Group Stage', 1, 140, 134, 'ITGC'),
(63, 33, 32, 0, 0, '10:45:00', 3, NULL, 'Group Stage', 1, 152, 146, 'ADD'),
(64, 29, 30, 0, 0, '11:00:00', 3, NULL, 'Group Stage', 1, 128, 134, 'ITIM'),
(65, 35, 33, 0, 0, '11:15:00', 3, NULL, 'Group Stage', 1, 164, 152, 'ITAS'),
(66, 28, 31, 0, 0, '11:30:00', 3, NULL, 'Group Stage', 1, 122, 140, 'DSM'),
(67, 34, 32, 0, 0, '11:45:00', 3, NULL, 'Group Stage', 1, 158, 146, 'ITOC'),
(68, 29, 31, 0, 0, '12:00:00', 3, NULL, 'Group Stage', 1, 128, 140, 'STQA'),
(69, 35, 32, 0, 0, '12:15:00', 3, NULL, 'Group Stage', 1, 164, 146, 'AOM'),
(70, 28, 30, 0, 0, '12:30:00', 3, NULL, 'Group Stage', 1, 122, 134, 'AOM'),
(71, 33, 34, 0, 0, '12:45:00', 3, NULL, 'Group Stage', 1, 152, 158, 'ADD');

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `match_played` int(11) DEFAULT 0,
  `total_points` int(11) DEFAULT 0,
  `points_against` int(11) DEFAULT 0,
  `court_num` int(11) DEFAULT NULL,
  `is_reserve` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`id`, `team_id`, `name`, `match_played`, `total_points`, `points_against`, `court_num`, `is_reserve`) VALUES
(120, 28, 'Mohd Suhairie & Nur Farhanna', 0, 0, 0, NULL, 0),
(121, 28, 'Mohd Fariz & Aznein', 0, 0, 0, NULL, 0),
(122, 28, 'Maspul & Nur Hani', 0, 0, 0, NULL, 0),
(123, 28, 'Muhammad Farid & Siti Aminah', 0, 0, 0, NULL, 0),
(124, 28, 'Muhammad Irfan Syahmi & Hafizah', 0, 0, 0, NULL, 0),
(125, 28, 'Khairold Safri & Azra Ain', 0, 0, 0, NULL, 1),
(126, 29, 'Raimi Rahim & Mimi Maisara', 0, 0, 0, NULL, 0),
(127, 29, 'Dheffirdaus & Nor Hazipah', 0, 0, 0, NULL, 0),
(128, 29, 'Mohd Norshahrim & Nurdiyana', 0, 0, 0, NULL, 0),
(129, 29, 'Che Ku Mohd Salahuddin & Sharina Ebel', 0, 0, 0, NULL, 0),
(130, 29, 'Mohd Ros Hamiza & Fatin Aisyah', 0, 0, 0, NULL, 0),
(131, 29, 'Syazwan Shamsuddin & Norehan', 0, 0, 0, NULL, 1),
(132, 30, 'Ahmad Radzi & Rohaida', 0, 0, 0, NULL, 0),
(133, 30, 'Mohd Syahir & Rosmahlina', 0, 0, 0, NULL, 0),
(134, 30, 'Mohd Shah & Norida', 0, 0, 0, NULL, 0),
(135, 30, 'Peter Raj & Noorain', 0, 0, 0, NULL, 0),
(136, 30, 'Norman Hafiz & Siti Fatimah', 0, 0, 0, NULL, 0),
(137, 30, 'Ahmad Syamimi Shahiri & Zuarida', 0, 0, 0, NULL, 1),
(138, 31, 'Muhammad Daniel & Nurhayati', 0, 0, 0, NULL, 0),
(139, 31, 'Ebby Sabilullah & Nur Rashidah', 0, 0, 0, NULL, 0),
(140, 31, 'Muhammad Irfan & Yusnor Arliza', 0, 0, 0, NULL, 0),
(141, 31, 'Ramlan & Hukiza', 0, 0, 0, NULL, 0),
(142, 31, 'Muhammad Rasheed & Norhayati', 0, 0, 0, NULL, 0),
(143, 31, 'Zamri', 0, 0, 0, NULL, 1),
(144, 32, 'Amir Izzat & Norlida', 0, 0, 0, NULL, 0),
(145, 32, 'Raja Shahiezal & Saleha', 0, 0, 0, NULL, 0),
(146, 32, 'Irwan & Noor Syazwani', 0, 0, 0, NULL, 0),
(147, 32, 'Roszaimi & Anim Hafiza', 0, 0, 0, NULL, 0),
(148, 32, 'Ilmar Qalbi & Nurul Fiza', 0, 0, 0, NULL, 0),
(149, 32, 'Wan Ibrahim & Nurul Shafinaz', 0, 0, 0, NULL, 1),
(150, 33, 'Abdul Muntaqim & Nur Anis Aqilah', 0, 0, 0, NULL, 0),
(151, 33, 'Danial Aysar & Rashidah', 0, 0, 0, NULL, 0),
(152, 33, 'Ikmal Hisyam & Nur Atiqah', 0, 0, 0, NULL, 0),
(153, 33, 'Ahmad Khairul Ikhwan & Zuriah Saad', 0, 0, 0, NULL, 0),
(154, 33, 'Mohd Shahrul Nizam & Mazalifah', 0, 0, 0, NULL, 0),
(155, 33, 'Chong Heng Keong & Massabrina', 0, 0, 0, NULL, 1),
(156, 34, 'Mohd Najmi & Siti Aisyah', 0, 0, 0, NULL, 0),
(157, 34, 'Mohammad Syazwan & Aimi Syafiqah', 0, 0, 0, NULL, 0),
(158, 34, 'Amir Asyraf & Nurakifah', 0, 0, 0, NULL, 0),
(159, 34, 'Thariq Aiman & Siti Munirah', 0, 0, 0, NULL, 0),
(160, 34, 'Mohammad Syafiq & Norliah', 0, 0, 0, NULL, 0),
(161, 34, 'Noorhisham Pearl & Qurratul Aini', 0, 0, 0, NULL, 1),
(162, 35, 'TS. Ahmad Shah & Nurdiana', 0, 0, 0, NULL, 0),
(163, 35, 'Ching Min Shen & Nur Hafizah', 0, 0, 0, NULL, 0),
(164, 35, 'Muhammad Hakimi & Nailah', 0, 0, 0, NULL, 0),
(165, 35, 'Khairul Azrin & Siti Nurkhadijah', 0, 0, 0, NULL, 0),
(166, 35, 'Raja Ahmad Mukhairis & Siti Hajar', 0, 0, 0, NULL, 0),
(167, 35, 'Maharizal & Anis Syafiqa', 0, 0, 0, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `group` enum('A','B') NOT NULL,
  `match_played` int(11) DEFAULT 0,
  `match_won` int(11) DEFAULT 0,
  `match_loss` int(11) DEFAULT 0,
  `total_points` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `name`, `group`, `match_played`, `match_won`, `match_loss`, `total_points`) VALUES
(28, 'ADD', 'A', 0, 0, 0, 0),
(29, 'AOM', 'A', 0, 0, 0, 0),
(30, 'ITAS', 'A', 0, 0, 0, 0),
(31, 'ITOC', 'A', 0, 0, 0, 0),
(32, 'ITIM', 'B', 0, 0, 0, 0),
(33, 'STQA', 'B', 0, 0, 0, 0),
(34, 'ITGC', 'B', 0, 0, 0, 0),
(35, 'DSM', 'B', 0, 0, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `team1_id` (`team1_id`),
  ADD KEY `team2_id` (`team2_id`),
  ADD KEY `winner_id` (`winner_id`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `matches_ibfk_1` FOREIGN KEY (`team1_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matches_ibfk_2` FOREIGN KEY (`team2_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matches_ibfk_3` FOREIGN KEY (`winner_id`) REFERENCES `teams` (`id`);

--
-- Constraints for table `players`
--
ALTER TABLE `players`
  ADD CONSTRAINT `players_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
