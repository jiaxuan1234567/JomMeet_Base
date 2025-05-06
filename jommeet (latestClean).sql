-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2025 at 04:30 PM
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
-- Database: `jommeet`
--

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedbackID` int(11) NOT NULL,
  `profileID` int(11) NOT NULL,
  `gatheringID` int(11) NOT NULL,
  `locationID` int(11) NOT NULL,
  `feedbackDesc` text NOT NULL,
  `feedbackType` enum('LOCATION','GATHERING') NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedbackID`, `profileID`, `gatheringID`, `locationID`, `feedbackDesc`, `feedbackType`, `date`) VALUES
(1, 3, 5, 1, 'Great gathering experience!', 'GATHERING', '2025-05-02 14:30:00'),
(2, 3, 5, 1, 'The location was clean and easy to find.', 'LOCATION', '2025-05-02 09:15:00'),
(3, 5, 5, 1, 'Fun activities but the place was crowded.', 'GATHERING', '2025-05-02 16:45:00'),
(4, 5, 5, 1, 'Location lacked proper signage.', 'LOCATION', '2025-05-02 10:05:00'),
(5, 6, 5, 1, 'Loved the vibe and the people!', 'GATHERING', '2025-05-03 19:30:00'),
(6, 6, 5, 1, 'Needs better parking facilities.', 'LOCATION', '2025-05-03 13:20:00'),
(7, 8, 5, 1, 'The event started late but was fun.', 'GATHERING', '2025-05-03 18:00:00'),
(8, 8, 5, 1, 'Too far from public transport.', 'LOCATION', '2025-05-05 11:45:00'),
(9, 6, 6, 5, 'Great gathering experience!', 'GATHERING', '2025-04-29 14:30:00'),
(10, 2, 6, 5, 'The location was clean and easy to find.', 'LOCATION', '2025-04-29 09:15:00'),
(11, 3, 6, 5, 'Fun activities but the place was crowded.', 'GATHERING', '2025-04-29 16:45:00'),
(12, 3, 6, 5, 'Location lacked proper signage.', 'LOCATION', '2025-04-29 10:05:00'),
(13, 4, 6, 5, 'Loved the vibe and the people!', 'GATHERING', '2025-04-30 19:30:00'),
(14, 6, 6, 5, 'Needs better parking facilities.', 'LOCATION', '2025-04-30 13:20:00'),
(15, 2, 6, 5, 'The event started late but was fun.', 'GATHERING', '2025-04-30 18:00:00'),
(16, 4, 6, 5, 'Too far from public transport.', 'LOCATION', '2025-04-30 11:45:00'),
(17, 2, 12, 35, 'Great gathering experience!', 'GATHERING', '2025-04-25 14:30:00'),
(18, 2, 12, 35, 'The location was clean and easy to find.', 'LOCATION', '2025-04-26 09:15:00'),
(19, 3, 12, 35, 'Fun activities but the place was crowded.', 'GATHERING', '2025-04-26 16:45:00'),
(20, 3, 12, 35, 'Location lacked proper signage.', 'LOCATION', '2025-04-27 10:05:00'),
(21, 4, 12, 35, 'Loved the vibe and the people!', 'GATHERING', '2025-04-27 19:30:00'),
(22, 4, 12, 35, 'Needs better parking facilities.', 'LOCATION', '2025-04-28 13:20:00'),
(23, 5, 12, 35, 'The event started late but was fun.', 'GATHERING', '2025-04-28 18:00:00'),
(24, 5, 12, 35, 'Too far from public transport.', 'LOCATION', '2025-04-29 11:45:00'),
(25, 3, 16, 14, 'Great gathering experience!', 'GATHERING', '2025-04-25 14:30:00'),
(26, 3, 16, 14, 'The location was clean and easy to find.', 'LOCATION', '2025-04-26 09:15:00'),
(27, 5, 16, 14, 'Fun activities but the place was crowded.', 'GATHERING', '2025-04-26 16:45:00'),
(28, 5, 16, 14, 'Location lacked proper signage.', 'LOCATION', '2025-04-27 10:05:00'),
(29, 6, 16, 14, 'Loved the vibe and the people!', 'GATHERING', '2025-04-27 19:30:00'),
(30, 7, 16, 14, 'Needs better parking facilities.', 'LOCATION', '2025-04-28 13:20:00'),
(31, 4, 20, 25, 'The event started late but was fun.', 'GATHERING', '2025-04-28 18:00:00'),
(32, 5, 20, 25, 'Too far from public transport.', 'LOCATION', '2025-04-29 11:45:00'),
(33, 6, 20, 25, 'Great gathering experience!', 'GATHERING', '2025-04-25 14:30:00'),
(34, 2, 22, 39, 'The location was clean and easy to find.', 'LOCATION', '2025-04-26 09:15:00'),
(35, 4, 22, 39, 'Fun activities but the place was crowded.', 'GATHERING', '2025-04-26 16:45:00'),
(36, 5, 22, 39, 'Location lacked proper signage.', 'LOCATION', '2025-04-27 10:05:00'),
(37, 6, 22, 39, 'Loved the vibe and the people!', 'GATHERING', '2025-04-27 19:30:00'),
(38, 8, 22, 39, 'Needs better parking facilities.', 'LOCATION', '2025-04-28 13:20:00'),
(39, 3, 25, 47, 'The event started late but was fun.', 'GATHERING', '2025-04-16 18:00:00'),
(40, 6, 25, 47, 'Too far from public transport.', 'LOCATION', '2025-04-17 11:45:00'),
(41, 7, 25, 47, 'Great gathering experience!', 'GATHERING', '2025-04-16 14:30:00'),
(42, 7, 25, 47, 'The location was clean and easy to find.', 'LOCATION', '2025-04-18 09:15:00'),
(43, 8, 25, 47, 'Fun activities but the place was crowded.', 'GATHERING', '2025-04-16 16:45:00'),
(44, 10, 25, 47, 'Location lacked proper signage.', 'LOCATION', '2025-04-18 10:05:00'),
(45, 2, 27, 17, 'Loved the vibe and the people!', 'GATHERING', '2025-04-19 19:30:00'),
(46, 2, 27, 17, 'Needs better parking facilities.', 'LOCATION', '2025-04-20 13:20:00'),
(47, 3, 27, 17, 'The event started late but was fun.', 'GATHERING', '2025-04-20 18:00:00'),
(48, 4, 27, 17, 'Too far from public transport.', 'LOCATION', '2025-04-20 11:45:00'),
(49, 2, 29, 13, 'Great gathering experience!', 'GATHERING', '2025-04-15 14:30:00'),
(50, 2, 29, 13, 'The location was clean and easy to find.', 'LOCATION', '2025-04-19 09:15:00'),
(51, 4, 29, 13, 'Fun activities but the place was crowded.', 'GATHERING', '2025-04-18 16:45:00'),
(52, 7, 29, 13, 'Location lacked proper signage.', 'LOCATION', '2025-04-19 10:05:00'),
(53, 7, 29, 13, 'Loved the vibe and the people!', 'GATHERING', '2025-04-20 19:30:00'),
(54, 3, 32, 22, 'Needs better parking facilities.', 'LOCATION', '2025-04-28 13:20:00'),
(55, 9, 32, 22, 'The event started late but was fun.', 'GATHERING', '2025-04-28 18:00:00'),
(56, 5, 36, 30, 'Too far from public transport.', 'LOCATION', '2025-04-25 11:45:00'),
(57, 1, 32, 22, 'Needs better parking facilities.', 'LOCATION', '2025-04-22 13:20:00'),
(58, 6, 32, 22, 'The event started late but was fun.', 'GATHERING', '2025-04-22 18:00:00'),
(59, 5, 36, 30, 'Too far from public transport.', 'LOCATION', '2025-04-25 11:45:00'),
(60, 5, 36, 30, 'Location lacked proper signage.', 'LOCATION', '2025-04-26 10:05:00'),
(61, 6, 36, 30, 'Loved the vibe and the people!', 'GATHERING', '2025-04-27 19:30:00'),
(62, 4, 38, 19, 'Needs better parking facilities.', 'LOCATION', '2025-04-18 13:20:00'),
(63, 3, 40, 45, 'The event started late but was fun.', 'GATHERING', '2025-04-17 18:00:00'),
(64, 6, 40, 45, 'Too far from public transport.', 'LOCATION', '2025-04-18 11:45:00'),
(65, 3, 42, 50, 'Loved the vibe and the people!', 'GATHERING', '2025-04-14 19:30:00'),
(66, 2, 45, 7, 'Needs better parking facilities.', 'LOCATION', '2025-04-28 13:20:00'),
(67, 8, 47, 15, 'The event started late but was fun.', 'GATHERING', '2025-04-28 18:00:00'),
(68, 5, 50, 23, 'Too far from public transport.', 'LOCATION', '2025-04-29 11:45:00');

-- --------------------------------------------------------

--
-- Table structure for table `gathering`
--

CREATE TABLE `gathering` (
  `gatheringID` int(11) NOT NULL,
  `locationID` int(11) NOT NULL,
  `theme` varchar(255) NOT NULL,
  `maxParticipant` int(11) NOT NULL,
  `minParticipant` int(11) NOT NULL,
  `currentParticipant` int(11) NOT NULL,
  `date` date NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('NEW','START','END','CANCELLED') NOT NULL DEFAULT 'NEW',
  `preference` varchar(100) NOT NULL,
  `hostProfileID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gathering`
--

INSERT INTO `gathering` (`gatheringID`, `locationID`, `theme`, `maxParticipant`, `minParticipant`, `currentParticipant`, `date`, `startTime`, `endTime`, `createdAt`, `status`, `preference`, `hostProfileID`) VALUES
(1, 15, 'Night at the Museum', 4, 3, 4, '2025-04-24', '18:00:00', '20:00:00', '2025-04-23 03:24:18', 'CANCELLED', 'NATURAL', 2),
(2, 23, 'E-sports Watch Party', 7, 3, 1, '2025-05-12', '14:00:00', '16:00:00', '2025-05-05 12:42:49', 'NEW', 'ENTERTAINMENT', 4),
(3, 33, 'Painting in the Park', 4, 3, 4, '2025-04-24', '10:00:00', '12:00:00', '2025-04-22 03:24:18', 'CANCELLED', 'CHILL', 3),
(4, 27, 'Comedy Night', 5, 3, 4, '2025-04-24', '13:00:00', '15:00:00', '2025-04-22 03:24:18', 'CANCELLED', 'ENTERTAINMENT', 3),
(5, 1, 'Craft & Create', 8, 3, 4, '2025-05-01', '18:00:00', '20:00:00', '2025-04-28 03:24:18', 'END', 'CHILL', 5),
(6, 5, 'Tech Talk & Chill', 6, 3, 4, '2025-04-28', '16:00:00', '18:00:00', '2025-04-24 03:24:18', 'END', 'STUDY', 4),
(7, 9, 'Pet Lovers Meetup', 5, 3, 1, '2025-05-18', '10:00:00', '12:00:00', '2025-05-05 12:42:54', 'NEW', 'CHILL', 5),
(8, 8, 'Mini Book Club', 7, 3, 5, '2025-05-01', '09:00:00', '11:00:00', '2025-04-26 03:24:18', 'CANCELLED', 'SHOPPING', 2),
(9, 42, 'Mini Book Club', 5, 3, 1, '2025-05-12', '12:00:00', '14:00:00', '2025-05-05 12:43:05', 'NEW', 'STUDY', 5),
(10, 40, 'Weekend Farmers Market Tour', 4, 3, 1, '2025-05-12', '16:00:00', '18:00:00', '2025-05-05 12:43:08', 'NEW', 'NATURAL', 6),
(11, 20, 'Art & Wine Night', 6, 3, 1, '2025-05-07', '17:00:00', '19:00:00', '2025-05-05 12:43:11', 'NEW', 'CHILL', 3),
(12, 35, 'Chill Gaming Session', 7, 3, 5, '2025-04-20', '14:00:00', '17:00:00', '2025-04-18 03:24:18', 'END', 'ENTERTAINMENT', 1),
(13, 6, 'Late Night Movie', 5, 3, 1, '2025-05-10', '20:00:00', '23:00:00', '2025-05-05 12:43:14', 'NEW', 'MOVIE', 6),
(14, 11, 'Nature Photography Trip', 4, 3, 1, '2025-05-06', '06:00:00', '09:00:00', '2025-05-05 12:43:18', 'NEW', 'NATURAL', 2),
(15, 31, 'Coffee & Code', 6, 3, 1, '2025-05-08', '09:00:00', '12:00:00', '2025-05-05 12:43:22', 'NEW', 'FOOD', 1),
(16, 14, 'Fitness Challenge', 7, 3, 6, '2025-04-22', '07:00:00', '09:00:00', '2025-04-20 03:24:18', 'END', 'WORKOUT', 3),
(17, 10, 'Hiking the Local Trail', 5, 3, 1, '2025-05-09', '08:00:00', '11:00:00', '2025-05-05 12:43:25', 'NEW', 'NATURAL', 5),
(18, 2, 'Sunset Yoga', 8, 3, 7, '2025-04-21', '17:30:00', '19:00:00', '2025-04-18 03:24:18', 'CANCELLED', 'WORKOUT', 4),
(19, 18, 'Indoor Climbing', 6, 3, 1, '2025-05-11', '13:00:00', '15:00:00', '2025-05-05 12:43:28', 'NEW', 'WORKOUT', 2),
(20, 25, 'Weekend Art Jam', 4, 3, 3, '2025-04-23', '10:00:00', '12:00:00', '2025-04-21 03:24:18', 'END', 'ENTERTAINMENT', 6),
(21, 44, 'Morning Jog with Friends', 5, 3, 1, '2025-05-06', '07:00:00', '08:30:00', '2025-05-05 12:43:31', 'NEW', 'WORKOUT', 5),
(22, 39, 'Board Games Night', 6, 3, 5, '2025-04-19', '18:00:00', '21:00:00', '2025-04-16 03:24:18', 'END', 'ENTERTAINMENT', 4),
(23, 9, 'City Night Photography', 4, 3, 1, '2025-05-08', '19:00:00', '21:00:00', '2025-05-05 12:43:34', 'NEW', 'CHILL', 2),
(24, 28, 'Tea & Talk Session', 5, 3, 1, '2025-05-07', '15:00:00', '17:00:00', '2025-05-05 12:43:37', 'NEW', 'CHILL', 1),
(25, 47, 'Lakeside Picnic', 6, 3, 6, '2025-04-15', '11:00:00', '14:00:00', '2025-04-12 03:24:18', 'END', 'CHILL', 6),
(26, 5, 'Open Mic Hangout', 7, 3, 1, '2025-05-09', '20:00:00', '22:00:00', '2025-05-05 12:43:40', 'NEW', 'MUSIC', 3),
(27, 17, 'Local Food Crawl', 8, 3, 7, '2025-04-18', '13:00:00', '17:00:00', '2025-04-15 03:24:18', 'END', 'FOOD', 2),
(28, 33, 'Paint & Sip Workshop', 6, 3, 1, '2025-05-09', '16:00:00', '18:00:00', '2025-05-05 12:43:44', 'NEW', 'CHILL', 4),
(29, 13, 'Nature Walk & Chill', 5, 3, 4, '2025-04-14', '08:00:00', '10:00:00', '2025-04-11 03:24:18', 'END', 'NATURAL', 1),
(30, 42, 'Casual Coffee Chat', 4, 3, 1, '2025-05-10', '10:00:00', '11:30:00', '2025-05-05 12:43:46', 'NEW', 'FOOD', 5),
(31, 1, 'Evening Chillout by the Beach', 6, 3, 1, '2025-05-09', '17:30:00', '20:00:00', '2025-05-05 12:43:49', 'NEW', 'NATURAL', 3),
(32, 22, 'Group Meditation Session', 5, 3, 4, '2025-04-20', '07:30:00', '09:00:00', '2025-04-17 03:24:18', 'END', 'CHILL', 6),
(33, 26, 'Waterfall Hiking Trip', 8, 3, 1, '2025-05-07', '06:00:00', '10:00:00', '2025-05-05 12:43:51', 'NEW', 'NATURAL', 4),
(34, 4, 'Karaoke & Coffee', 7, 3, 6, '2025-04-21', '19:00:00', '22:00:00', '2025-04-18 03:24:18', 'CANCELLED', 'MUSIC', 1),
(35, 36, 'Chill Book Exchange', 4, 3, 1, '2025-05-08', '15:00:00', '16:30:00', '2025-05-05 12:43:54', 'NEW', 'STUDY', 2),
(36, 30, 'Coffee with Entrepreneurs', 6, 3, 6, '2025-04-22', '10:00:00', '12:00:00', '2025-04-19 03:24:18', 'END', 'CHILL', 5),
(37, 3, 'Food Lovers Meetup', 8, 3, 1, '2025-05-11', '13:00:00', '16:00:00', '2025-05-05 12:43:57', 'NEW', 'FOOD', 3),
(38, 19, 'Casual Soccer Match', 7, 3, 6, '2025-04-16', '17:00:00', '18:30:00', '2025-04-13 03:24:18', 'END', 'WORKOUT', 4),
(39, 8, 'Drawing in the Park', 5, 3, 1, '2025-05-08', '08:00:00', '10:30:00', '2025-05-05 12:43:59', 'NEW', 'NATURAL', 2),
(40, 45, 'Squash for Beginners', 4, 3, 3, '2025-04-19', '18:00:00', '19:30:00', '2025-04-15 03:24:18', 'END', 'WORKOUT', 6),
(41, 16, 'Weekend Coffee Meet', 5, 3, 1, '2025-05-10', '10:00:00', '12:00:00', '2025-05-05 12:44:02', 'NEW', 'CHILL', 1),
(42, 50, 'Sunset Hiking Trail', 6, 3, 5, '2025-04-13', '17:00:00', '19:30:00', '2025-04-10 03:24:18', 'END', 'NATURAL', 3),
(43, 6, 'Picnic at the Botanical Garden', 7, 3, 1, '2025-05-07', '11:00:00', '14:00:00', '2025-05-05 12:44:04', 'NEW', 'CHILL', 5),
(44, 12, 'Photography & Coffee', 6, 3, 1, '2025-05-08', '14:00:00', '17:00:00', '2025-05-05 12:44:06', 'NEW', 'CHILL', 4),
(45, 7, 'Night Chill at Rooftop Cafe', 4, 3, 3, '2025-04-15', '19:00:00', '21:30:00', '2025-04-11 03:24:18', 'END', 'FOOD', 2),
(46, 41, 'Yoga by the Lake', 5, 3, 1, '2025-05-09', '07:00:00', '08:30:00', '2025-05-05 12:44:08', 'NEW', 'WORKOUT', 6),
(47, 15, 'Art & Coffee Jam', 6, 3, 5, '2025-04-18', '16:00:00', '18:00:00', '2025-04-14 03:24:18', 'END', 'CHILL', 3),
(48, 55, 'Explore New Restaurants', 8, 3, 1, '2025-05-10', '18:00:00', '21:00:00', '2025-05-05 12:42:43', 'NEW', 'FOOD', 1),
(49, 34, 'Coffee Debate Club', 5, 3, 4, '2025-05-09', '14:00:00', '15:30:00', '2025-05-05 13:11:41', 'NEW', 'CHILL', 4),
(50, 23, 'Mindful Picnic Morning', 6, 3, 5, '2025-04-12', '09:00:00', '11:00:00', '2025-04-08 03:24:18', 'END', 'FOOD', 5);

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `locationID` int(11) NOT NULL,
  `placeID` varchar(255) NOT NULL,
  `locationName` text NOT NULL,
  `address` varchar(500) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`locationID`, `placeID`, `locationName`, `address`, `longitude`, `latitude`, `image`) VALUES
(1, 'ChIJuVtJtsFJzDER_CiU3--jnGA', 'GSC NU Sentral', 'Lot L5.14, Level 5 Nu Sentral, 201, Jalan Tun Sambanthan, Kuala Lumpur Sentral, 50470 Kuala Lumpur, Federal Territory of Kuala Lumpur, Malaysia', 101.6874146, 3.1332046, NULL),
(2, 'ChIJIfr-rI5JzDER-ZaN-AHX4ds', 'GSC Mid Valley Megamall', 'Lot T-001 Mid Valley Megamall, 3RD FLOOR, Lingkaran Syed Putra, Mid Valley City, 59200 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6767198, 3.1165695, NULL),
(3, 'ChIJQ1CTIhQ2zDEREDeShnpBm5E', 'TGV Cinemas - Sunway Velocity Mall', '4-31, Level 4, Mall 90, SUNWAY VELOCITY, Jalan Peel, Maluri, 55100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7236037, 3.1282754, NULL),
(4, 'ChIJI2-8UTpJzDERQKeUu9-tIjA', 'Velvet Cinemas by GSC, 163 Retail Park', '3F-02, Sunway 163 Mall, 8, Jalan Kiara, Mont Kiara, 50480 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6519568, 3.1663776, NULL),
(5, 'ChIJLaUrPDw2zDERoc1UjcMtCv0', 'GSC MyTOWN Shopping Centre', 'Level 3A & 3B, Seksyen 90, MyTOWN Shopping Centre, L3-AT-002, Jalan Cochrane, Maluri, 55100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7228570, 3.1350050, NULL),
(6, 'ChIJT_lbN9VOzDER5oDC79Vdly4', 'TGV 1 Utama', 'Level 3, Old Wing, 1 Utama Shopping Centre, Level 3, Old Wing, 1 Utama Shopping Centre, 1, Lebuh Bandar Utama, Bandar Utama, 47800 Petaling Jaya, Selangor, Malaysia', 101.6171474, 3.1483393, NULL),
(7, 'ChIJJ39HhztIzDERB6sEaf2nD0U', 'TGV Cinemas Sunway Putra Mall', 'Lot 6-3, 6th Floor, Sunway Putra Mall, 100, Jalan Putra, Chow Kit, 50350 Kuala Lumpur, Wilayah Persekutuan, Malaysia', 101.6924194, 3.1664256, NULL),
(8, 'ChIJc9qENy9IzDERvdz6deCCVjU', 'GSC Quill City Mall', 'Lot 5-23 & 6-08, 5th Floor, Quill City Mall, 1018, Jln Sultan Ismail, Bandar Wawasan, 50250 Kuala Lumpur, Federal Territory of Kuala Lumpur, Malaysia', 101.6999064, 3.1598032, NULL),
(9, 'ChIJX566bO5JzDERQAUUuGtqv4Q', 'Infinity Cafe | Open 24 Hours | Dua Sentral', '8, Jalan Tun Sambanthan, Kampung Attap, 50470 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6925913, 3.1361801, NULL),
(10, 'ChIJkZmt8EVJzDERFJIS5XcrFmc', 'Bricks Factory Cafe', '274, Jalan Tun Sambanthan, Brickfields, 50470 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6840683, 3.1286860, NULL),
(11, 'ChIJE7jFVtJJzDERgZoAEKorgIA', 'LOKL Coffee Co', '30, Jalan Tun H S Lee, City Centre, 50100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6980030, 3.1487312, NULL),
(12, 'ChIJDXJyAQA3zDERda_lDVCi4Rg', 'Elite Restaurant & Cafe', '46 & 48, Jalan Berangan, Bukit Bintang, 50200 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7089266, 3.1478141, NULL),
(13, 'ChIJmxWkt5VJzDERJJUH7Oag9Ig', 'Lisette\'s Café & Bakery @ Bangsar', 'No. 8, Jalan Kemuja, Bangsar, 59000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6798452, 3.1296548, NULL),
(14, 'ChIJEXusLwBJzDERjEoP2PEX_os', 'Crays SB Cafe', '18, Lorong Syed Putra Kiri, Bukit Seputeh, 50460 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6891025, 3.1261941, NULL),
(15, 'ChIJW0w7UChIzDERWGflpMgxv20', 'Cafe:in House', 'Unit 1-01, Mercu, Summer Suites, 8, Jalan Cendana, Kampung Baru, 50250 Kuala Lumpur, Federal Territory of Kuala Lumpur, Malaysia', 101.7049013, 3.1587838, NULL),
(16, 'ChIJ21kzqpg3zDER6Pa6rFJAZno', 'After One KL', '1, Persiaran Lidcol , Jalan Yap Kwan Seng, Wilayah Persekutuan, 1, Persiaran Lidcol, Kampung Baru, 50450 Kuala Lumpur, Federal Territory of Kuala Lumpur, Malaysia', 101.7134671, 3.1618489, NULL),
(17, 'ChIJp8GrJA9JzDERtM8SV0_I-g8', 'AOOO Melbourne Cafe', '182-2, Jalan Tun H S Lee, City Centre, 50000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6968899, 3.1434460, NULL),
(18, 'ChIJox4q31JJzDEReRVhESYWoOk', 'WaaronKuus Cafe', 'Lorong Petaling, Street, 50000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6979440, 3.1431759, NULL),
(19, 'ChIJ1ZHj0NFJzDERf1ipaIMmYF0', 'Al-Baik Di Bistro Restaurant', '3, Jalan Tun Tan Cheng Lock, City Centre, 50050 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6959181, 3.1445632, NULL),
(20, 'ChIJ10yNZHpJzDERctqOuzJv7WA', 'Barra Restaurant', '158, Jalan Petaling, City Centre, 50000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6982010, 3.1407535, NULL),
(21, 'ChIJKdx-FsVJzDER94Il2e9a6iM', 'Heritage One Station Restaurant', 'Bangunan Stesen Keretapi, 2, Jalan Sultan Hishamuddin, Kampung Attap, 50050 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6933454, 3.1403277, NULL),
(22, 'ChIJnftOSGZJzDERmFJXvw8q6uE', 'The Lankan KL', '57, Jalan Sultan, City Centre, 50000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6988056, 3.1430000, NULL),
(23, 'ChIJh3ZOYLlNzDERuh_5oyWvsCU', 'Chum Chum Pizzeria & MAKAMAKAN by Serai Group', '171, Jalan Tun H S Lee, City Centre, 50000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6964667, 3.1430827, NULL),
(24, 'ChIJF9vClg9JzDEROmZLFClX7BI', 'Dodoo Kitchen KL', '192, Jalan Tun H S Lee, City Centre, 50000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6966177, 3.1432151, NULL),
(25, 'ChIJ8wMHh2RJzDER-OYCWRC0QAM', 'Ní.KIZOKU Modern Japanese Dining Bar 霓貴族', '59A, Jalan Sultan, City Centre, 50000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6987587, 3.1430708, NULL),
(26, 'ChIJcUEU9NBJzDERmdp-SI6pBDk', 'Restoran Han Kee', '46, Jalan Sultan, City Centre, 50000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6985406, 3.1431643, NULL),
(27, 'ChIJb7Fg89BJzDERf2kY_kQPtNk', 'Westlake restaurant', '40, Jalan Sultan, City Centre, 50000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6985827, 3.1429904, NULL),
(28, 'ChIJdwiugCk2zDER-Nq2nFuVqMk', 'Relax Time Foot Reflexology', '69 Tingkat Bawah, Changkat Bukit Bintang, Bukit Bintang, 50200 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7093593, 3.1463365, NULL),
(29, 'ChIJedFyHgA3zDERFuCrMQ1_RmY', 'BE RELAX SPA MASSAGE', 'Berjaya Times Square, Imbi, 55100 Kuala Lumpur, Federal Territory of Kuala Lumpur, Malaysia', 101.7105512, 3.1421984, NULL),
(30, 'ChIJU0CFLio2zDERuy1kXU4rRX8', 'Bintang Relax Reflexology', '71, Jln Bukit Bintang, Bukit Bintang, 55100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7092981, 3.1467400, NULL),
(31, 'ChIJqRpFS8BJzDERyZFHD2Blw6M', 'Relax Oasis Signature Massage', 'Lot 3-24, Nu Sentral Mall, 201, Jalan Tun Sambanthan, Brickfields, 50470 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6868521, 3.1333800, NULL),
(32, 'ChIJw7XMOQBLzDER0qy7Ss4q8Rg', 'Relax Wellness OUG', '39m, Jalan Mega Mendung, Taman United, 58200 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6732097, 3.0832129, NULL),
(33, 'ChIJHQutnBE4zDERhKa0PWH1-ag', 'Relax Oasis', '67, Jln Taman Ibu Kota, Taman Danau Kota, 53300 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7207315, 3.2045417, NULL),
(34, 'ChIJJw8eYGo3zDERTm9iLgNQxD8', 'Pusat Relax Reflex & Success', 'Sungei Wang Plaza, Jln Sultan Ismail, Bukit Bintang, 50200 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7114301, 3.1451862, NULL),
(35, 'ChIJyTXJANFJzDERIYNUUKRFbVc', 'Relax & Health Foot Reflexology', '23,23a, Jalan Hang Lekir, City Centre, 50000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6984304, 3.1443382, NULL),
(36, 'ChIJAd_rr9tJzDERlgxIxsT26Yk', 'GCK JS CAFE Ride N Relax', '315, Lorong Tuanku Abdul Rahman 2, Chow Kit, 50300 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6972185, 3.1599280, NULL),
(37, 'ChIJgZ_TfndJzDERMwGDfnrla_4', 'CHILL MATE CAFE', 'Lot K2&K3, Jalan Raja Uda, Kampung Baru, 50300 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7061444, 3.1655596, NULL),
(38, 'ChIJh1xnSABHzDER4UB3aMyuGjs', 'Chill Lounge', 'NO 5-2 JALAN 3/62A BANDAR MENJALARA, KEPONG, Kepong, 52100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6287580, 3.1950054, NULL),
(39, 'ChIJTadUOC9IzDERl0bLJsnzDOY', 'Chill Chill @ Quill City Mall', '50250, Quill City Mall, 1018, Jln Sultan Ismail, Bandar Wawasan, 55100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7001253, 3.1594441, NULL),
(40, 'ChIJn7bwacpHzDERExREYt5Bbic', 'It\'s Chill Time', '3-G, Jalan SS2/ 64, SS 2, 47300 Petaling Jaya, Selangor, Malaysia', 101.6199278, 3.1181697, NULL),
(41, 'ChIJe4npNvU1zDERDqDJp5jRbb8', 'Chill Bay Restaurant and Bar', '2-G &, 2A-G, Jalan Tengah Cheras Selatan 118, Taman Sri Indah, 43200 Cheras, Selangor, Malaysia', 101.7690935, 3.0353576, NULL),
(42, 'ChIJ4dGVK95LzDERpg5cLT_preY', 'Chill Haus', 'C­-02­-05 PLAZA BUKIT JALIL JALAN PERSIARAN JALIL 1 BANDAR BUKIT JALIL, Bandar, Bukit Jalil, 57000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6688649, 3.0531771, NULL),
(43, 'ChIJI4OglXtNzDERQ5kqD6v0PQY', 'Chill at Buriram - Your Neighborhood Bar', '3a, Jalan SS 12/1b, SS12, 47500 Subang Jaya, Selangor, Malaysia', 101.5944372, 3.0787266, NULL),
(44, 'ChIJg-iNXO9HzDEREghcQLXjV90', 'Papa Seafood & Chill', '50, Jalan 29, Selayang Baru, 68100 Batu Caves, Selangor, Malaysia', 101.6647719, 3.2498789, NULL),
(45, 'ChIJxyA7EsZJzDERPGYegMrXm5k', 'Piccola Kitchen & Bar', '6, Jalan Ceylon, Bukit Ceylon, 50200 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7056063, 3.1480307, NULL),
(46, 'ChIJ40RJN9VOzDERoOsruuWzGMg', 'Chill*X Diner @ TGV One Utama', 'Bandar Utama, 47800 Petaling Jaya, Selangor, Malaysia', 101.6169621, 3.1484038, NULL),
(47, 'ChIJJUuDw3XHzjER1ZME-3KzBMo', 'ZUS Coffee - Jalan Tun Perak, Masjid Jamek', 'No. 45-1 & 45-2, GROUND FLOOR, Lebuh Ampang, City Centre, 50100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6970357, 3.1485279, NULL),
(48, 'ChIJIcLEWYFJzDERkY2U9o7Bi-c', 'ZUS Coffee - Jakel Mall, Kuala Lumpur', 'Lot SC.02 & SC.03, Jakel Mall, Lot 159, Jakel Square, Off, Jalan Munshi Abdullah, City Centre, 50100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6995880, 3.1538864, NULL),
(49, 'ChIJD1DKVbE3zDER9rg8xHU5l60', 'ZUS Coffee - Suria KLCC', 'Lot No. OS301 , Level 3, Menara Berkembar Petronas, Persiaran Petronas, Kuala Lumpur City Centre, 50088 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7127089, 3.1576844, NULL),
(50, 'ChIJrbUZRLhJzDERj7ThagxEVh4', 'ZUS Coffee Quill City Mall', 'LG-23A, 1018, Jln Sultan Ismail, Bandar Wawasan, 54200 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7005383, 3.1598185, NULL),
(51, 'ChIJ28XWEtdJzDER4UDBFKbzpOo', 'ZUS Coffee - Pertama Complex, Kuala Lumpur', 'Kiosk No. 3, Kompleks Pertama, Jalan Tuanku Abdul Rahman, Chow Kit, 50100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6955075, 3.1574179, NULL),
(52, 'ChIJN3WHly1JzDERn3JXzhf_TGw', 'ZUS Coffee - Semua House', 'GF.04, Ground Floor, Semua House, Jalan Bunus 6, City Centre, 50100 Wilayah Persekutuan, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6972503, 3.1550605, NULL),
(53, 'ChIJMaC2v0M4zDERhKvjqmfgxS0', 'Tunku Abdul Rahman University of Management and Technology (TAR UMT)', 'Ground Floor, Bangunan Tan Sri Khaw Kai Boh (Block A), Jalan Genting Kelang, Setapak, 53300 Kuala Lumpur, Federal Territory of Kuala Lumpur, Malaysia', 101.7265571, 3.2152552, NULL),
(54, 'ChIJOUYP3IZJzDERUtst4rwfLFk', 'One Bowl Lamian Noodle Lanzhou China', 'G-15, Damansara City Mall, 6, Jalan Damanlela, Bukit Damansara, 50490 Kuala Lumpur, Federal Territory of Kuala Lumpur, Malaysia', 101.6620892, 3.1459439, NULL),
(55, 'ChIJxW_LitY3zDERre_dKEKO1As', 'Ozeki Tokyo Cuisine @ Menara TA One', 'Menara Ta One, 22, Jalan P. Ramlee, Kuala Lumpur, 50250 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7094237, 3.1569317, NULL),
(56, 'ChIJN5TGyS1IzDERyyQy8PdOEHo', 'City One Trading', 'LG 03, City One Plaza, Jalan Munshi Abdullah, City Centre, 55100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6977891, 3.1549068, NULL),
(57, 'ChIJfTjgGAj3zTERHCo7sL1Lq1o', 'Klana Beach Resort Port Dickson', 'Kompleks Baitulhilal, Lot 4506 Batu, 8, Jalan Pantai, Tanjung Tanah Merah, 71050 Port Dickson, Negeri Sembilan, Malaysia', 101.8549829, 2.4456421, NULL),
(58, 'ChIJL4N7jK1j0DEROG_HHehYZKo', 'D & A HOMESTAY ENTERPRISE SIMPANG RENGGAM JOHOR', '75, Jalan Putri 8, TAMAN PUTRIMAS, 86200 Simpang Renggam, Johor Darul Ta\'zim, Malaysia', 103.3176875, 1.8098489, NULL),
(59, 'ChIJF0wwO65t0DERDAwUFJpUux8', 'Sunway Kluang Mall', 'Jln Rambutan, Taman Suria, 86000 Kluang, Johor Darul Ta\'zim, Malaysia', 103.3205798, 2.0400737, NULL),
(60, 'ChIJsQTqQ11t2jERF_ULAhUiHKs', 'AEON Mall Tebrau City', '1, Jalan Desa Tebrau, Taman Desa Tebrau, 81100 Johor Bahru, Johor Darul Ta\'zim, Malaysia', 103.7959073, 1.5490688, NULL),
(61, 'ChIJwdaNIz9t0DERdwS6ls4HCY4', 'Kluang Parade', '2, Jln Sentol, Kampung Masjid Lama, 86000 Kluang, Johor Darul Ta\'zim, Malaysia', 103.3201237, 2.0360134, NULL),
(62, 'ChIJwZA57EFGzDERz-Fu0K1Xfo0', 'Musii @ Kepong', '43-G, 43-G, Jln Metro Perdana Barat 2, Taman Usahawan Kepong, 52100 Wilayah Persekutuan, Federal Territory of Kuala Lumpur, Malaysia', 101.6396684, 3.2153804, NULL),
(63, 'ChIJXSTelkxJzDERiD06usLXB0Y', 'The Music Factory', '38B, Jalan SS 22/25, Ss 22, 47400 Petaling Jaya, Selangor, Malaysia', 101.6174634, 3.1269699, NULL),
(64, 'ChIJm1ciopBJzDERjekwfs_LTmY', 'Two AM Music', '1, Lorong Riong, Bangsar, 59100 Kuala Lumpur, Wilayah Persekutuan, Malaysia', 101.6738627, 3.1263058, NULL),
(65, 'ChIJhezyVTFJzDERaLLxYRyEJHo', 'Harmony Music Center', '49, Jalan SS 21/56b, Damansara Utama, 47400 Petaling Jaya, Selangor, Malaysia', 101.6214239, 3.1377379, NULL),
(66, 'ChIJfzOB9jZJzDEROz8sJ3LAow8', 'L. Luthier Flagship Store', '45A, Jalan SS 21/37, Damansara Utama, 47400 Petaling Jaya, Selangor, Malaysia', 101.6235167, 3.1363873, NULL),
(67, 'ChIJXdaC1F1JzDERbyR-G90wRxg', 'Yamaha Music (Fantasia Music Studio)', 'Lot 100-1-005 , The School ,Jaya One, 72A, Jln Profesor Diraja Ungku Aziz, Jaya One, 46200 Petaling Jaya, Selangor, Malaysia', 101.6354371, 3.1184035, NULL),
(68, 'ChIJ_axQXLXryjERArENq4-mrp8', 'KaneYouFixIt Music', '21B, Jalan SS 26/6, Taman Mayang Jaya, 47301 Petaling Jaya, Selangor, Malaysia', 101.6045837, 3.1168560, NULL),
(69, 'ChIJfzFBzxe3SjARJKww2bc6yl4', 'Chew \'N Chill (Tasek Mutiara Branch)', 'Pusat komersial, 20, Persiaran Mutiara 1, Bandar Tasek Mutiara, 14120 Simpang Ampat, Pulau Pinang, Malaysia', 100.4921767, 5.2769355, NULL),
(70, 'ChIJvVrNQiTJSjAR_4xQqspf-WQ', 'Chill Point TCM Massage Therapy 舒点推拿', '1, Lor Desa Impian 2, Desa Impian, 14000 Bukit Mertajam, Penang, Malaysia', 100.4886747, 5.3085574, NULL),
(71, 'ChIJZxxjT7rJSjARNNzwO7Ap67E', 'LeeCorner Chill\'Laa', '5460, Jalan Permatang Tinggi, Kampung Bukit Minyak, 14100 Bukit Mertajam, Pulau Pinang, Malaysia', 100.4479023, 5.3258704, NULL),
(72, 'ChIJCdyudgDDSjAROpWTEPChKxg', 'Chill Night Restaurant 夜猫子', '80, Lebuh Kimberley, George Town, 10100 George Town, Pulau Pinang, Malaysia', 100.3328829, 5.4163364, NULL),
(73, 'ChIJOzeQg9DnSjARqsDmcPBDOqE', 'Grill N Chill', 'Jalan Batu Ferringhi, 11100 Batu Ferringhi, Pulau Pinang, Malaysia', 100.2487100, 5.4754115, NULL),
(74, 'ChIJD6jA-JfDSjARzWtQcWBUH1Y', 'Chill With Classic', '199, Jalan Hutton, 10050 George Town, Pulau Pinang, Malaysia', 100.3269863, 5.4218912, NULL),
(75, 'ChIJxw2gadfHSjARsdP5BSfO0HA', 'PENANG SIP N CHILL CAFE', 'No.2, Signature Avenue, Jalan Lestari, 13700 Perai, Pulau Pinang, Malaysia', 100.4184791, 5.3677826, NULL),
(76, 'ChIJgwGjyLlESzARw8Fqmzx8mHc', 'Chin Hin Jitra (CHJ Motors) - C-Mart ABC, Alor Setar', '111, Kompleks Perniagaan Ampang, Sultanah Bahiyah Hwy, Taman Bahagia, 05050 Alor Setar, Kedah, Malaysia', 100.3506279, 6.1108645, NULL),
(77, 'ChIJQ33d3gbFSjARd-zwellvKWA', 'K.B Fun', 'UF 32, Sunway Carnival Shopping Complex, 3068, Jalan Todak, Pusat, 13700 Seberang Jaya, Penang, Malaysia', 100.3979071, 5.3986036, NULL),
(78, 'ChIJBWbm2tM3zDERTno0px940s4', 'KLCC Park', 'City Centre, Kuala Lumpur City Centre, 50450 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7147872, 3.1555902, NULL),
(79, 'ChIJEWkeL7ZJzDER38lq7GVsWyk', 'Perdana Botanical Garden', 'Jalan Kebun Bunga, Tasik Perdana, 55100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6847408, 3.1425816, NULL),
(80, 'ChIJvwv8fxI1zDERRB1idU9EsJE', 'Kuala Lumpur Eco Park', 'Kuala Lumpur, 57000 Kuala Lumpur, Federal Territory of Kuala Lumpur, Malaysia', 101.7022560, 3.1506078, NULL),
(81, 'ChIJu8zWwchJzDERhcsx4AhQ1oo', 'KL Bird Park', '920, Jalan Cenderawasih, Tasik Perdana, 50480 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6916602, 3.1391529, NULL),
(82, 'ChIJK_vZ2qVIzDERetLWIrl9xGg', 'The Central Park', 'C-3-1, plaza arkadia, 3, Persiaran Residen, Desa Parkcity, 52200 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6283832, 3.1864074, NULL),
(83, 'ChIJX93dYsJKzDERN0oBQxHIIm0', 'Bukit Jalil Recreational Park', 'Jalan 13/155c, Bukit Jalil, 57000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6755556, 3.0522222, NULL),
(84, 'ChIJm4fmX7VJzDERAr6LJ1p2j8Y', 'ASEAN Sculpture Garden', 'Asean Sculpture Garden, Perdana Botanical Garden, Pesiaran Sultan Salahhudin, Kuala Lumpur, 50480 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6839757, 3.1504807, NULL),
(85, 'ChIJFXSF8_xJzDERsqgbZPN5Tvk', 'Lake Garden - Kuala Lumpur', 'Perdana Botanical Gardens, 50480 Kuala Lumpur, Federal Territory of Kuala Lumpur, Malaysia', 101.6860730, 3.1436780, NULL),
(86, 'ChIJgw_35Us3zDERGSdDxaWqe_o', 'Taman Tasik Titiwangsa', 'Jalan Kuantan, Titiwangsa, 53200 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7071919, 3.1777324, NULL),
(87, 'ChIJ-QBKB_FNzDERQgMqI1MFMko', 'Fun X World', 'Lot 2.140B Level 2, Summit USJ Mall, Persiaran Kewajipan, Usj 1, 47600 Subang Jaya, Selangor, Malaysia', 101.5935600, 3.0599489, NULL),
(88, 'ChIJVVVV4iBPzDERrhC-sxphoKg', 'NextGen Theme Park', 'S603-S610, 2 floor, 1 Utama E, 1 Utama Shopping Centre, Central Park Avenue, Bandar Utama, 47800 Petaling Jaya, Selangor, Malaysia', 101.6171750, 3.1488335, NULL),
(89, 'ChIJ2TNDzF5HzDERZzcLpI-mYSI', 'Fun D shisha', 'Kantin@SetorGombak, 4 ½, KantinSG, Batu, Jln Gombak, 53000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7288270, 3.2734330, NULL),
(90, 'ChIJSYtHBAJDzDERd3R9sIltlBY', 'FUN SNACK CLAW', '103-1, JALAN, PUSAT KOMERSIAL, Jalan Anggun City 1, Taman Anggun, 48000 Rawang, Selangor, Malaysia', 101.5364738, 3.3166645, NULL),
(91, 'ChIJ05W3f77DSjAR-xgPFtIHagw', 'Impulse Gaming (Penang Branch)', '2, Jalan Kek Chuan, George Town, 10400 George Town, Pulau Pinang, Malaysia', 100.3246349, 5.4152580, NULL),
(92, 'ChIJw3ffP5hJzDEReUxLWB2yawg', 'Celebrity Fitness Gym - Bangsar Village 2', '3F-11, 3rd Floor, Bangsar Village II, 2, Jalan Telawi 1, Bangsar, 59100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6713433, 3.1300557, NULL),
(93, 'ChIJjUUYhftJzDERtPBj4g8-tl8', 'lyft.club - Bangsar', '10-2, Jalan Telawi 2, Bangsar, 59100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6718613, 3.1324273, NULL),
(94, 'ChIJiS_zEb9JzDER3TB43UtbpGM', 'Hypertrofit Personal Training', '36-1, 34-1, Jalan Telawi 5, Bangsar, 59100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6718945, 3.1329171, NULL),
(95, 'ChIJkYnLE7ZJzDER0TSw63KLzMY', 'Anytime Fitness @Menara UOA Bangsar', '2/8 - 10, Anytime fitness, Menara Uoa Bangsar, Jalan Bangsar, Bangsar, 59100 Kuala Lumpur, Federal Territory of Kuala Lumpur, Malaysia', 101.6786783, 3.1280989, NULL),
(96, 'ChIJ_yDJ-qJJzDERpUar41qCvIQ', 'Impulse Studio Bangsar', '111, Jalan Telawi, Bangsar, 59100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6703824, 3.1302784, NULL),
(97, 'ChIJiTty3oxJzDERmYSlzhjbL-k', 'POWER Personal Training - Bangsar', '38-1, Jalan Telawi, Bangsar, 59100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6705895, 3.1312598, NULL),
(98, 'ChIJGV3GXr9PzDERKRV3PCdkAG4', 'The Hyper Gym and Fitness', 'Kompleks Muhibah, Taman Sains Selangor, 47810 Petaling Jaya, Selangor, Malaysia', 101.5705309, 3.1591133, NULL),
(99, 'ChIJpZ-30sFPzDERXZ2r3zFMeJw', 'Coliseum Fitness', 'C-10-1, Sunway Giza, No. 2 Jalan PJU 5/14, PJU 5 Dataran Sunway, Kota Damansara, 47810 Petaling Jaya, Selangor, Malaysia', 101.5911910, 3.1502498, NULL),
(100, 'ChIJuWa3frNPzDERwfYE8UQwQIc', 'Enrich Fitness Subang', 'B-07-1 Pusat Komersial Arena Bintang, Jalan Zuhal U5/179 Seksyen U5, 40150 Shah Alam, Selangor, Malaysia', 101.5530759, 3.1547390, NULL),
(101, 'ChIJNXFAQDnMyDERoIxrRICFnyA', 'Bukit Gambang Water Park', 'Utama, Bukit Resort City, Jln Bukit Gambang Resort, Kampung Pohoi, 26300 Gambang, Pahang, Malaysia', 103.0516291, 3.7102788, NULL),
(102, 'ChIJZ-dnGHiwyDER2IAWn9oNsp4', 'Esplanade Kuantan', 'Jalan Tanah Putih, 25100 Kuantan, Pahang, Malaysia', 103.3234934, 3.8002751, NULL),
(103, 'ChIJhdYiTZeozjERs3wn-VAJ9EE', 'Deerland Park, Lanchang', '28500, Pahang, Malaysia', 102.1681788, 3.5855348, NULL),
(104, 'ChIJwzmafgYUzDERVr_VxM3tuAE', 'Genting Highlands', 'Genting Highlands, Pahang, Malaysia', 101.7932011, 3.4239780, NULL),
(105, 'ChIJJXR7B1pDzDERoCoguJC062M', 'CHAGEE @ Anggun City, Rawang', 'Pusat Komersial Anggun City, 97-1, Persiaran Anggun, City, 48000 Rawang, Selangor, Malaysia', 101.5365233, 3.3179195, NULL),
(106, 'ChIJhTO5yQM3zDERdDgjVUPydls', 'D\'NATUREL @ Sunway Velocity Mall', 'Malaysia, Wilayah Persekutuan Kuala Lumpur, Kuala Lumpur, Maluri, Lingkaran SV, Lot1邮政编码: 55100', 101.7243674, 3.1271288, NULL),
(107, 'ChIJ5YkKoR9PzDERXIQu3fvBfQw', 'Pure Natural Hair Care', 'No. 21 - 2, Jalan PJU 5/20a, Kota Damansara, 47810 Petaling Jaya, Selangor, Malaysia', 101.5915120, 3.1549390, NULL),
(108, 'ChIJP3e35y82zDERtMlTjdDsXTg', 'The Exchange TRX', 'Persiaran TRX, Tun Razak Exchange, 55188 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7168132, 3.1424073, NULL),
(109, 'ChIJZ0BKPiM3zDER-5kXlbKP8-E', 'Nanyang Cafe 南洋冰室 - The Exchange TRX', 'No.#C.04.0, Level Concourse, The Exchange TRX, Lingkaran TRX, Tun Razak Exchange, 55188 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7173173, 3.1432328, NULL),
(110, 'ChIJc-mvCAI3zDERGKvRhOe2fR0', 'Shake Shack The Exchange TRX', 'Lot PL.11.0, Level 3, The Exchange TRX, Persiaran TRX, Tun Razak Exchange, 55188 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7173546, 3.1422147, NULL),
(111, 'ChIJCR41XYQ3zDERW-f3QRR-3_o', 'Red Box Plus The Exchange TRX', 'L1, Red Box Plus @ The Exchange TRX, 45.A, Persiaran TRX, Tun Razak Exchange, 55188 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7170041, 3.1421588, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `profileID` int(11) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `aboutme` text NOT NULL,
  `mbti` varchar(255) NOT NULL,
  `profileStatus` enum('CREATED','NEW') NOT NULL DEFAULT 'NEW',
  `phone` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`profileID`, `nickname`, `aboutme`, `mbti`, `profileStatus`, `phone`, `password`) VALUES
(1, 'Yong', 'Love hiking and reading.', 'INTJ', 'NEW', '0162418757', 'abc12345'),
(2, 'Vin Sen', 'Tech geek and gamer.', 'ENTP', 'CREATED', '0173098763', '12345'),
(3, 'Yeoh', 'Enjoy cooking and music.', 'ISFP', 'NEW', '0102348059', '12345'),
(4, 'Jiaxuan', 'Passionate about art.', 'INFJ', 'CREATED', '0197502520', 'abc'),
(5, 'Waikin', 'Outdoor explorer.', 'ESTP', 'NEW', '0146429782', 'abc'),
(6, 'TengHui', 'Bookworm and traveler.', 'ENFP', 'NEW', '0173163129', 'abc'),
(7, 'George', 'Coffee lover and coder.', 'INTP', 'CREATED', '0149988776', 'abc'),
(8, 'Hannah', 'Yoga and wellness fan.', 'ESFJ', 'NEW', '0181122334', 'abc'),
(9, 'Ian', 'Fitness and finance enthusiast.', 'ISTJ', 'CREATED', '0163344556', 'abc'),
(10, 'Jasmine', 'Photography is life.', 'ENFJ', 'NEW', '0152233441', 'abc');

-- --------------------------------------------------------

--
-- Table structure for table `profilegathering`
--

CREATE TABLE `profilegathering` (
  `profileID` int(11) NOT NULL,
  `gatheringID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profilegathering`
--

INSERT INTO `profilegathering` (`profileID`, `gatheringID`) VALUES
(1, 1),
(1, 3),
(1, 4),
(1, 12),
(1, 15),
(1, 24),
(1, 25),
(1, 27),
(1, 29),
(1, 32),
(1, 34),
(1, 41),
(1, 45),
(1, 48),
(2, 1),
(2, 4),
(2, 6),
(2, 8),
(2, 12),
(2, 14),
(2, 18),
(2, 19),
(2, 22),
(2, 23),
(2, 27),
(2, 29),
(2, 34),
(2, 35),
(2, 38),
(2, 39),
(2, 40),
(2, 45),
(2, 50),
(3, 1),
(3, 3),
(3, 4),
(3, 5),
(3, 6),
(3, 11),
(3, 12),
(3, 16),
(3, 18),
(3, 25),
(3, 26),
(3, 27),
(3, 31),
(3, 32),
(3, 34),
(3, 37),
(3, 40),
(3, 42),
(3, 45),
(3, 47),
(4, 1),
(4, 2),
(4, 6),
(4, 8),
(4, 12),
(4, 18),
(4, 20),
(4, 22),
(4, 27),
(4, 28),
(4, 29),
(4, 33),
(4, 34),
(4, 38),
(4, 44),
(4, 47),
(4, 49),
(4, 50),
(5, 4),
(5, 5),
(5, 7),
(5, 9),
(5, 12),
(5, 16),
(5, 17),
(5, 18),
(5, 20),
(5, 21),
(5, 22),
(5, 27),
(5, 30),
(5, 34),
(5, 36),
(5, 38),
(5, 42),
(5, 43),
(5, 50),
(6, 5),
(6, 6),
(6, 8),
(6, 10),
(6, 13),
(6, 16),
(6, 18),
(6, 20),
(6, 22),
(6, 25),
(6, 27),
(6, 32),
(6, 34),
(6, 36),
(6, 38),
(6, 40),
(6, 46),
(6, 47),
(6, 49),
(7, 16),
(7, 25),
(7, 27),
(7, 29),
(7, 36),
(7, 38),
(7, 42),
(7, 49),
(8, 3),
(8, 5),
(8, 8),
(8, 16),
(8, 18),
(8, 22),
(8, 25),
(8, 36),
(8, 47),
(8, 50),
(9, 16),
(9, 32),
(9, 36),
(9, 42),
(9, 47),
(9, 49),
(9, 50),
(10, 3),
(10, 8),
(10, 18),
(10, 25),
(10, 36),
(10, 38),
(10, 42);

-- --------------------------------------------------------

--
-- Table structure for table `profile_hobby`
--

CREATE TABLE `profile_hobby` (
  `hobby` varchar(100) NOT NULL,
  `profileID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile_hobby`
--

INSERT INTO `profile_hobby` (`hobby`, `profileID`) VALUES
('Badminton', 7),
('Basketball', 1),
('Basketball', 9),
('Camping', 5),
('Drawing', 6),
('Gym', 8),
('Hiking', 3),
('Hiking', 9),
('Jogging', 3),
('Meditation', 6),
('Painting', 7),
('Photography', 2),
('Photography', 10),
('Reading', 1),
('Reading', 10),
('Singing', 4),
('Squash', 8),
('Swimming', 5),
('Traveling', 2),
('Yoga', 4);

-- --------------------------------------------------------

--
-- Table structure for table `profile_preference`
--

CREATE TABLE `profile_preference` (
  `preference` varchar(100) NOT NULL,
  `profileID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile_preference`
--

INSERT INTO `profile_preference` (`preference`, `profileID`) VALUES
('Chill', 2),
('Chill', 4),
('Chill', 7),
('Entertainment', 1),
('Entertainment', 5),
('Entertainment', 9),
('Food', 1),
('Movie', 8),
('Music', 6),
('Music', 10),
('Natural', 4),
('Shopping', 5),
('Study', 3);

-- --------------------------------------------------------

--
-- Table structure for table `reminder`
--

CREATE TABLE `reminder` (
  `reminderID` int(11) NOT NULL,
  `profileID` int(11) NOT NULL,
  `description` text NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `gatheringID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reminder`
--

INSERT INTO `reminder` (`reminderID`, `profileID`, `description`, `createdAt`, `gatheringID`) VALUES
(1, 2, 'Hi, see you all tomorrow.', '2025-04-23 14:12:15', 1),
(2, 4, 'Don\'t forget to bring snacks!', '2025-04-24 02:00:00', 2),
(4, 5, 'Bring your laptop for the study session.', '2025-04-24 05:45:00', 9),
(5, 6, 'Let’s meet earlier to grab coffee.', '2025-04-24 06:00:00', 10),
(6, 3, 'Movie night is confirmed!', '2025-04-24 07:00:00', 11),
(9, 1, 'Looking forward to tomorrow’s plan.', '2025-04-24 10:00:00', 15),
(10, 5, 'Reminder to complete the group form.', '2025-04-24 11:00:00', 17),
(11, 2, 'See you all at the café!', '2025-04-25 00:00:00', 19),
(12, 5, 'Location pin shared in the group chat.', '2025-04-25 01:30:00', 21),
(14, 1, 'Let’s wear matching shirts!', '2025-04-25 04:00:00', 24),
(15, 3, 'Be on time please.', '2025-04-25 05:00:00', 26),
(16, 4, 'Final call for attendance.', '2025-04-25 06:15:00', 28),
(17, 5, 'Game night theme: Retro.', '2025-04-25 07:30:00', 30),
(18, 3, 'Pick-up will be at 6PM sharp.', '2025-04-25 08:45:00', 31);

-- --------------------------------------------------------

--
-- Table structure for table `self_reflect`
--

CREATE TABLE `self_reflect` (
  `selfreflectID` int(11) NOT NULL,
  `profileID` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `self_reflect`
--

INSERT INTO `self_reflect` (`selfreflectID`, `profileID`, `title`, `content`, `date`) VALUES
(1, 1, 'First Gathering Reflection', 'I was nervous at first, but everyone was so welcoming. Glad I joined!', '2025-04-15 10:30:00'),
(2, 1, 'New Connections', 'Met two really interesting people today. Looking forward to seeing them again.', '2025-04-16 12:45:00'),
(3, 1, 'Awkward but Fun', 'The start was awkward, but the group games helped break the ice.', '2025-04-17 15:00:00'),
(4, 1, 'Trying Something New', 'Joined a gathering outside my comfort zone. Felt proud afterward.', '2025-04-18 17:30:00'),
(5, 1, 'Lessons Learned', 'Realized I need to listen more and talk less. Great growth moment.', '2025-04-19 09:20:00'),
(6, 1, 'Deep Conversations', 'Had a great chat about life goals with someone I just met. Felt refreshing.', '2025-04-20 20:10:00'),
(7, 1, 'Disappointed but Okay', 'Today’s event was poorly organized. Still, met one cool person.', '2025-04-21 18:00:00'),
(8, 1, 'Better Social Skills', 'I’m learning to engage better in small talk.', '2025-04-22 14:25:00'),
(9, 1, 'Confidence Boost', 'Someone complimented my sense of humor today. That felt nice.', '2025-04-23 11:15:00'),
(10, 1, 'Felt Invisible', 'Didn’t get much attention today, but I know not every day is the same.', '2025-04-24 16:40:00'),
(11, 1, 'Great Vibes', 'Everyone was positive and open. Loved the atmosphere.', '2025-04-25 19:30:00'),
(12, 1, 'Helping Others', 'Helped a newcomer feel comfortable. Felt fulfilling.', '2025-04-26 13:15:00'),
(13, 1, 'Introvert Challenge', 'Pushed myself to initiate a conversation first. Small win.', '2025-04-27 10:00:00'),
(14, 1, 'Fun and Games', 'Group activity made me laugh so hard. Great stress relief.', '2025-04-28 20:45:00'),
(15, 1, 'Uncomfortable Silence', 'There were awkward pauses today, but I stayed calm and patient.', '2025-04-29 09:50:00'),
(16, 1, 'Personal Growth', 'Realizing how much I’ve improved since my first gathering.', '2025-04-30 17:20:00'),
(17, 1, 'Cultural Exchange', 'Learned about someone’s background today. It was eye-opening.', '2025-05-01 14:00:00'),
(18, 1, 'Leadership Moment', 'I was asked to help facilitate today. It went surprisingly well.', '2025-05-02 12:30:00'),
(19, 1, 'Reflection on Values', 'A deep talk reminded me of what truly matters in life.', '2025-05-03 16:10:00'),
(20, 1, 'Grateful Heart', 'Feeling thankful for this platform to meet new people.', '2025-05-05 22:00:34'),
(21, 2, 'First Gathering Reflection', 'I was nervous at first, but everyone was so welcoming. Glad I joined!', '2025-04-15 10:30:00'),
(22, 2, 'New Connections', 'Met two really interesting people today. Looking forward to seeing them again.', '2025-04-16 12:45:00'),
(23, 2, 'Awkward but Fun', 'The start was awkward, but the group games helped break the ice.', '2025-04-17 15:00:00'),
(24, 2, 'Trying Something New', 'Joined a gathering outside my comfort zone. Felt proud afterward.', '2025-04-18 17:30:00'),
(25, 2, 'Lessons Learned', 'Realized I need to listen more and talk less. Great growth moment.', '2025-04-19 09:20:00'),
(26, 2, 'Deep Conversations', 'Had a great chat about life goals with someone I just met. Felt refreshing.', '2025-04-20 20:10:00'),
(27, 2, 'Disappointed but Okay', 'Today’s event was poorly organized. Still, met one cool person.', '2025-04-21 18:00:00'),
(28, 2, 'Better Social Skills', 'I’m learning to engage better in small talk.', '2025-04-22 14:25:00'),
(29, 2, 'Confidence Boost', 'Someone complimented my sense of humor today. That felt nice.', '2025-04-23 11:15:00'),
(30, 3, 'Felt Invisible', 'Didn’t get much attention today, but I know not every day is the same.', '2025-04-24 16:40:00'),
(31, 3, 'Great Vibes', 'Everyone was positive and open. Loved the atmosphere.', '2025-04-25 19:30:00'),
(32, 3, 'Helping Others', 'Helped a newcomer feel comfortable. Felt fulfilling.', '2025-04-26 13:15:00'),
(33, 3, 'Introvert Challenge', 'Pushed myself to initiate a conversation first. Small win.', '2025-04-27 10:00:00'),
(34, 3, 'Fun and Games', 'Group activity made me laugh so hard. Great stress relief.', '2025-04-28 20:45:00'),
(35, 3, 'Uncomfortable Silence', 'There were awkward pauses today, but I stayed calm and patient.', '2025-04-29 09:50:00'),
(36, 3, 'Personal Growth', 'Realizing how much I’ve improved since my first gathering.', '2025-04-30 17:20:00'),
(37, 3, 'Cultural Exchange', 'Learned about someone’s background today. It was eye-opening.', '2025-05-01 14:00:00'),
(38, 3, 'Leadership Moment', 'I was asked to help facilitate today. It went surprisingly well.', '2025-05-02 12:30:00'),
(39, 3, 'Reflection on Values', 'A deep talk reminded me of what truly matters in life.', '2025-05-03 16:10:00'),
(40, 3, 'Grateful Heart', 'Feeling thankful for this platform to meet new people.', '2025-05-05 22:01:32'),
(41, 4, 'First Gathering Reflection', 'I was nervous at first, but everyone was so welcoming. Glad I joined!', '2025-04-15 10:30:00'),
(42, 4, 'New Connections', 'Met two really interesting people today. Looking forward to seeing them again.', '2025-04-16 12:45:00'),
(43, 4, 'Awkward but Fun', 'The start was awkward, but the group games helped break the ice.', '2025-04-17 15:00:00'),
(44, 4, 'Trying Something New', 'Joined a gathering outside my comfort zone. Felt proud afterward.', '2025-04-18 17:30:00'),
(45, 4, 'Lessons Learned', 'Realized I need to listen more and talk less. Great growth moment.', '2025-04-19 09:20:00'),
(46, 4, 'Deep Conversations', 'Had a great chat about life goals with someone I just met. Felt refreshing.', '2025-04-20 20:10:00'),
(47, 4, 'Disappointed but Okay', 'Today’s event was poorly organized. Still, met one cool person.', '2025-04-21 18:00:00'),
(48, 5, 'Better Social Skills', 'I’m learning to engage better in small talk.', '2025-04-22 14:25:00'),
(49, 5, 'Confidence Boost', 'Someone complimented my sense of humor today. That felt nice.', '2025-04-23 11:15:00'),
(50, 5, 'Felt Invisible', 'Didn’t get much attention today, but I know not every day is the same.', '2025-04-24 16:40:00'),
(51, 5, 'Great Vibes', 'Everyone was positive and open. Loved the atmosphere.', '2025-04-25 19:30:00'),
(52, 5, 'Helping Others', 'Helped a newcomer feel comfortable. Felt fulfilling.', '2025-04-26 13:15:00'),
(53, 5, 'Introvert Challenge', 'Pushed myself to initiate a conversation first. Small win.', '2025-04-27 10:00:00'),
(54, 6, 'Fun and Games', 'Group activity made me laugh so hard. Great stress relief.', '2025-04-28 20:45:00'),
(55, 6, 'Uncomfortable Silence', 'There were awkward pauses today, but I stayed calm and patient.', '2025-04-29 09:50:00'),
(56, 6, 'Personal Growth', 'Realizing how much I’ve improved since my first gathering.', '2025-04-30 17:20:00'),
(57, 6, 'Cultural Exchange', 'Learned about someone’s background today. It was eye-opening.', '2025-05-01 14:00:00'),
(58, 6, 'Leadership Moment', 'I was asked to help facilitate today. It went surprisingly well.', '2025-05-02 12:30:00'),
(59, 6, 'Reflection on Values', 'A deep talk reminded me of what truly matters in life.', '2025-05-03 16:10:00'),
(60, 6, 'Grateful Heart', 'Feeling thankful for this platform to meet new people.', '2025-05-05 22:02:11'),
(61, 7, 'First Gathering Reflection', 'I was nervous at first, but everyone was so welcoming. Glad I joined!', '2025-04-15 10:30:00'),
(62, 7, 'New Connections', 'Met two really interesting people today. Looking forward to seeing them again.', '2025-04-16 12:45:00'),
(63, 7, 'Awkward but Fun', 'The start was awkward, but the group games helped break the ice.', '2025-04-17 15:00:00'),
(64, 7, 'Trying Something New', 'Joined a gathering outside my comfort zone. Felt proud afterward.', '2025-04-18 17:30:00'),
(65, 7, 'Lessons Learned', 'Realized I need to listen more and talk less. Great growth moment.', '2025-04-19 09:20:00'),
(66, 7, 'Deep Conversations', 'Had a great chat about life goals with someone I just met. Felt refreshing.', '2025-04-20 20:10:00'),
(67, 7, 'Disappointed but Okay', 'Today’s event was poorly organized. Still, met one cool person.', '2025-04-21 18:00:00'),
(68, 8, 'Better Social Skills', 'I’m learning to engage better in small talk.', '2025-04-22 14:25:00'),
(69, 8, 'Confidence Boost', 'Someone complimented my sense of humor today. That felt nice.', '2025-04-23 11:15:00'),
(70, 8, 'Felt Invisible', 'Didn’t get much attention today, but I know not every day is the same.', '2025-04-24 16:40:00'),
(71, 8, 'Great Vibes', 'Everyone was positive and open. Loved the atmosphere.', '2025-04-25 19:30:00'),
(72, 8, 'Helping Others', 'Helped a newcomer feel comfortable. Felt fulfilling.', '2025-04-26 13:15:00'),
(73, 8, 'Introvert Challenge', 'Pushed myself to initiate a conversation first. Small win.', '2025-04-27 10:00:00'),
(74, 9, 'Fun and Games', 'Group activity made me laugh so hard. Great stress relief.', '2025-04-28 20:45:00'),
(75, 9, 'Uncomfortable Silence', 'There were awkward pauses today, but I stayed calm and patient.', '2025-04-29 09:50:00'),
(76, 9, 'Personal Growth', 'Realizing how much I’ve improved since my first gathering.', '2025-04-30 17:20:00'),
(77, 9, 'Cultural Exchange', 'Learned about someone’s background today. It was eye-opening.', '2025-05-01 14:00:00'),
(78, 9, 'Leadership Moment', 'I was asked to help facilitate today. It went surprisingly well.', '2025-05-02 12:30:00'),
(79, 9, 'Reflection on Values', 'A deep talk reminded me of what truly matters in life.', '2025-05-03 16:10:00'),
(80, 9, 'Grateful Heart', 'Feeling thankful for this platform to meet new people.', '2025-05-05 22:02:50'),
(81, 10, 'First Gathering Reflection', 'I was nervous at first, but everyone was so welcoming. Glad I joined!', '2025-04-15 10:30:00'),
(82, 10, 'New Connections', 'Met two really interesting people today. Looking forward to seeing them again.', '2025-04-16 12:45:00'),
(83, 10, 'Awkward but Fun', 'The start was awkward, but the group games helped break the ice.', '2025-04-17 15:00:00'),
(84, 10, 'Trying Something New', 'Joined a gathering outside my comfort zone. Felt proud afterward.', '2025-04-18 17:30:00'),
(85, 10, 'Lessons Learned', 'Realized I need to listen more and talk less. Great growth moment.', '2025-04-19 09:20:00'),
(86, 10, 'Deep Conversations', 'Had a great chat about life goals with someone I just met. Felt refreshing.', '2025-04-20 20:10:00'),
(87, 10, 'Disappointed but Okay', 'Today’s event was poorly organized. Still, met one cool person.', '2025-04-21 18:00:00'),
(88, 10, 'Better Social Skills', 'I’m learning to engage better in small talk.', '2025-04-22 14:25:00'),
(89, 10, 'Confidence Boost', 'Someone complimented my sense of humor today. That felt nice.', '2025-04-23 11:15:00'),
(90, 10, 'Felt Invisible', 'Didn’t get much attention today, but I know not every day is the same.', '2025-04-24 16:40:00'),
(91, 10, 'Great Vibes', 'Everyone was positive and open. Loved the atmosphere.', '2025-04-25 19:30:00'),
(92, 10, 'Helping Others', 'Helped a newcomer feel comfortable. Felt fulfilling.', '2025-04-26 13:15:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedbackID`),
  ADD KEY `gatheringID` (`gatheringID`),
  ADD KEY `profileID` (`profileID`),
  ADD KEY `locationID` (`locationID`);

--
-- Indexes for table `gathering`
--
ALTER TABLE `gathering`
  ADD PRIMARY KEY (`gatheringID`),
  ADD KEY `locationID` (`locationID`),
  ADD KEY `hostProfileID` (`hostProfileID`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`locationID`),
  ADD UNIQUE KEY `placeID` (`placeID`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`profileID`);

--
-- Indexes for table `profilegathering`
--
ALTER TABLE `profilegathering`
  ADD PRIMARY KEY (`profileID`,`gatheringID`),
  ADD KEY `gatheringID` (`gatheringID`),
  ADD KEY `profileID` (`profileID`);

--
-- Indexes for table `profile_hobby`
--
ALTER TABLE `profile_hobby`
  ADD PRIMARY KEY (`hobby`,`profileID`),
  ADD KEY `profileID` (`profileID`);

--
-- Indexes for table `profile_preference`
--
ALTER TABLE `profile_preference`
  ADD PRIMARY KEY (`preference`,`profileID`);

--
-- Indexes for table `reminder`
--
ALTER TABLE `reminder`
  ADD PRIMARY KEY (`reminderID`),
  ADD KEY `profileID` (`profileID`),
  ADD KEY `gatheringID` (`gatheringID`);

--
-- Indexes for table `self_reflect`
--
ALTER TABLE `self_reflect`
  ADD PRIMARY KEY (`selfreflectID`),
  ADD KEY `profileID` (`profileID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedbackID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `gathering`
--
ALTER TABLE `gathering`
  MODIFY `gatheringID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `locationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `profileID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `reminder`
--
ALTER TABLE `reminder`
  MODIFY `reminderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `self_reflect`
--
ALTER TABLE `self_reflect`
  MODIFY `selfreflectID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`gatheringID`) REFERENCES `gathering` (`gatheringID`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`profileID`) REFERENCES `profile` (`profileID`),
  ADD CONSTRAINT `feedback_ibfk_3` FOREIGN KEY (`locationID`) REFERENCES `location` (`locationID`);

--
-- Constraints for table `gathering`
--
ALTER TABLE `gathering`
  ADD CONSTRAINT `gathering_ibfk_1` FOREIGN KEY (`locationID`) REFERENCES `location` (`locationID`),
  ADD CONSTRAINT `gathering_ibfk_2` FOREIGN KEY (`hostProfileID`) REFERENCES `profile` (`profileID`);

--
-- Constraints for table `profilegathering`
--
ALTER TABLE `profilegathering`
  ADD CONSTRAINT `profilegathering_ibfk_1` FOREIGN KEY (`gatheringID`) REFERENCES `gathering` (`gatheringID`),
  ADD CONSTRAINT `profilegathering_ibfk_2` FOREIGN KEY (`profileID`) REFERENCES `profile` (`profileID`);

--
-- Constraints for table `profile_hobby`
--
ALTER TABLE `profile_hobby`
  ADD CONSTRAINT `profile_hobby_ibfk_1` FOREIGN KEY (`profileID`) REFERENCES `profile` (`profileID`);

--
-- Constraints for table `reminder`
--
ALTER TABLE `reminder`
  ADD CONSTRAINT `reminder_ibfk_1` FOREIGN KEY (`profileID`) REFERENCES `profile` (`profileID`),
  ADD CONSTRAINT `reminder_ibfk_2` FOREIGN KEY (`gatheringID`) REFERENCES `gathering` (`gatheringID`);

--
-- Constraints for table `self_reflect`
--
ALTER TABLE `self_reflect`
  ADD CONSTRAINT `self_reflect_ibfk_1` FOREIGN KEY (`profileID`) REFERENCES `profile` (`profileID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
