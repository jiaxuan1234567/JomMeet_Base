-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2025 at 06:02 PM
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
CREATE DATABASE IF NOT EXISTS `jommeet` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `jommeet`;

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
  `preference` enum('FOOD','CHILL','STUDY','NATURAL','SHOPPING','WORKOUT','ENTERTAINMENT','MUSIC','MOVIE') NOT NULL,
  `hostProfileID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gathering`
--

INSERT INTO `gathering` (`gatheringID`, `locationID`, `theme`, `maxParticipant`, `minParticipant`, `currentParticipant`, `date`, `startTime`, `endTime`, `createdAt`, `status`, `preference`, `hostProfileID`) VALUES
(1, 39, 'Movie', 5, 3, 3, '2025-05-25', '20:00:00', '21:00:00', '2025-05-01 16:11:42', 'NEW', 'ENTERTAINMENT', 1),
(3, 31, 'JomMovie', 5, 1, 1, '2025-05-25', '22:00:00', '00:00:00', '2025-04-29 16:14:50', 'NEW', 'ENTERTAINMENT', 2),
(4, 3, 'JomMovie', 5, 1, 1, '2025-06-25', '13:13:33', '15:13:33', '2025-04-29 15:34:06', 'NEW', 'ENTERTAINMENT', 1),
(5, 55, 'happy', 4, 3, 1, '2025-01-01', '16:00:00', '19:00:00', '2025-04-29 16:17:58', 'END', 'ENTERTAINMENT', 1),
(6, 59, 'do assignment', 6, 3, 1, '2025-05-01', '15:00:00', '19:00:00', '2025-05-01 11:10:11', 'END', 'ENTERTAINMENT', 1),
(12, 3, 'g1', 4, 3, 2, '2025-05-01', '01:37:00', '04:37:00', '2025-05-01 10:46:00', 'END', 'ENTERTAINMENT', 3),
(13, 59, 'library vibe', 5, 3, 2, '2025-05-03', '14:54:00', '18:58:00', '2025-04-30 09:11:45', 'CANCELLED', 'ENTERTAINMENT', 3),
(14, 15, 'stay', 6, 3, 2, '2025-05-01', '17:30:00', '19:30:00', '2025-04-30 09:08:31', 'CANCELLED', 'ENTERTAINMENT', 3),
(15, 6, 'happy', 3, 3, 2, '2025-04-30', '20:31:00', '23:31:00', '2025-04-30 16:17:03', 'END', 'ENTERTAINMENT', 3),
(16, 3, 'happy', 3, 3, 2, '2025-04-30', '17:32:00', '21:32:00', '2025-04-30 16:17:03', 'END', 'ENTERTAINMENT', 3),
(17, 8, 'g1', 5, 3, 3, '2025-04-30', '16:37:00', '18:35:00', '2025-05-01 13:30:45', 'END', 'ENTERTAINMENT', 3),
(18, 15, 'Test 1', 4, 3, 2, '2025-04-30', '18:43:00', '21:43:00', '2025-05-01 13:30:45', 'END', 'ENTERTAINMENT', 3),
(19, 58, 'Test 1', 6, 3, 2, '2025-05-02', '18:45:00', '20:45:00', '2025-04-30 09:45:33', 'CANCELLED', 'ENTERTAINMENT', 3),
(20, 59, 'look', 5, 3, 2, '2025-05-03', '18:59:00', '20:01:00', '2025-05-02 05:43:27', 'CANCELLED', 'ENTERTAINMENT', 3),
(21, 4, 'happy', 8, 3, 2, '2025-05-02', '13:15:00', '15:15:00', '2025-05-02 05:43:02', 'START', 'ENTERTAINMENT', 3),
(22, 51, 'Happy', 4, 3, 2, '2025-05-01', '19:18:00', '20:18:00', '2025-05-01 13:25:15', 'END', 'ENTERTAINMENT', 3),
(23, 44, 'abc', 6, 3, 0, '2025-05-03', '23:30:00', '23:59:00', '2025-05-02 05:52:45', 'CANCELLED', 'ENTERTAINMENT', 3),
(24, 51, 'abv', 4, 3, 1, '2025-05-03', '20:58:00', '22:58:00', '2025-05-02 05:48:27', 'CANCELLED', 'ENTERTAINMENT', 3),
(25, 7, 'q', 4, 3, 1, '2025-05-03', '04:10:00', '13:10:00', '2025-05-02 14:12:09', 'NEW', 'ENTERTAINMENT', 3),
(26, 33, 'a', 4, 3, 1, '2025-05-03', '14:35:00', '16:35:00', '2025-05-02 14:37:43', 'NEW', 'ENTERTAINMENT', 3),
(27, 23, 'Lets go eat a dinner', 8, 3, 1, '2025-05-03', '03:00:00', '04:00:00', '2025-05-02 15:08:26', 'NEW', 'FOOD', 3);

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
(3, 'ChIJuVtJtsFJzDER_CiU3--jnGA', 'GSC NU Sentral', 'Lot L5.14, Level 5 Nu Sentral, 201, Jalan Tun Sambanthan, Kuala Lumpur Sentral, 50470 Kuala Lumpur, Federal Territory of Kuala Lumpur, Malaysia', 101.6874146, 3.1332046, NULL),
(4, 'ChIJIfr-rI5JzDER-ZaN-AHX4ds', 'GSC Mid Valley Megamall', 'Lot T-001 Mid Valley Megamall, 3RD FLOOR, Lingkaran Syed Putra, Mid Valley City, 59200 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6767198, 3.1165695, NULL),
(5, 'ChIJQ1CTIhQ2zDEREDeShnpBm5E', 'TGV Cinemas - Sunway Velocity Mall', '4-31, Level 4, Mall 90, SUNWAY VELOCITY, Jalan Peel, Maluri, 55100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7236037, 3.1282754, NULL),
(6, 'ChIJI2-8UTpJzDERQKeUu9-tIjA', 'Velvet Cinemas by GSC, 163 Retail Park', '3F-02, Sunway 163 Mall, 8, Jalan Kiara, Mont Kiara, 50480 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6519568, 3.1663776, NULL),
(7, 'ChIJLaUrPDw2zDERoc1UjcMtCv0', 'GSC MyTOWN Shopping Centre', 'Level 3A & 3B, Seksyen 90, MyTOWN Shopping Centre, L3-AT-002, Jalan Cochrane, Maluri, 55100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7228570, 3.1350050, NULL),
(8, 'ChIJT_lbN9VOzDER5oDC79Vdly4', 'TGV 1 Utama', 'Level 3, Old Wing, 1 Utama Shopping Centre, Level 3, Old Wing, 1 Utama Shopping Centre, 1, Lebuh Bandar Utama, Bandar Utama, 47800 Petaling Jaya, Selangor, Malaysia', 101.6171474, 3.1483393, NULL),
(9, 'ChIJJ39HhztIzDERB6sEaf2nD0U', 'TGV Cinemas Sunway Putra Mall', 'Lot 6-3, 6th Floor, Sunway Putra Mall, 100, Jalan Putra, Chow Kit, 50350 Kuala Lumpur, Wilayah Persekutuan, Malaysia', 101.6924194, 3.1664256, NULL),
(10, 'ChIJc9qENy9IzDERvdz6deCCVjU', 'GSC Quill City Mall', 'Lot 5-23 & 6-08, 5th Floor, Quill City Mall, 1018, Jln Sultan Ismail, Bandar Wawasan, 50250 Kuala Lumpur, Federal Territory of Kuala Lumpur, Malaysia', 101.6999064, 3.1598032, NULL),
(12, 'ChIJX566bO5JzDERQAUUuGtqv4Q', 'Infinity Cafe | Open 24 Hours | Dua Sentral', '8, Jalan Tun Sambanthan, Kampung Attap, 50470 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6925913, 3.1361801, NULL),
(13, 'ChIJkZmt8EVJzDERFJIS5XcrFmc', 'Bricks Factory Cafe', '274, Jalan Tun Sambanthan, Brickfields, 50470 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6840683, 3.1286860, NULL),
(14, 'ChIJE7jFVtJJzDERgZoAEKorgIA', 'LOKL Coffee Co', '30, Jalan Tun H S Lee, City Centre, 50100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6980030, 3.1487312, NULL),
(15, 'ChIJDXJyAQA3zDERda_lDVCi4Rg', 'Elite Restaurant & Cafe', '46 & 48, Jalan Berangan, Bukit Bintang, 50200 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7089266, 3.1478141, NULL),
(16, 'ChIJmxWkt5VJzDERJJUH7Oag9Ig', 'Lisette\'s Café & Bakery @ Bangsar', 'No. 8, Jalan Kemuja, Bangsar, 59000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6798452, 3.1296548, NULL),
(17, 'ChIJEXusLwBJzDERjEoP2PEX_os', 'Crays SB Cafe', '18, Lorong Syed Putra Kiri, Bukit Seputeh, 50460 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6891025, 3.1261941, NULL),
(18, 'ChIJW0w7UChIzDERWGflpMgxv20', 'Cafe:in House', 'Unit 1-01, Mercu, Summer Suites, 8, Jalan Cendana, Kampung Baru, 50250 Kuala Lumpur, Federal Territory of Kuala Lumpur, Malaysia', 101.7049013, 3.1587838, NULL),
(19, 'ChIJ21kzqpg3zDER6Pa6rFJAZno', 'After One KL', '1, Persiaran Lidcol , Jalan Yap Kwan Seng, Wilayah Persekutuan, 1, Persiaran Lidcol, Kampung Baru, 50450 Kuala Lumpur, Federal Territory of Kuala Lumpur, Malaysia', 101.7134671, 3.1618489, NULL),
(20, 'ChIJp8GrJA9JzDERtM8SV0_I-g8', 'AOOO Melbourne Cafe', '182-2, Jalan Tun H S Lee, City Centre, 50000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6968899, 3.1434460, NULL),
(21, 'ChIJox4q31JJzDEReRVhESYWoOk', 'WaaronKuus Cafe', 'Lorong Petaling, Street, 50000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6979440, 3.1431759, NULL),
(22, 'ChIJ1ZHj0NFJzDERf1ipaIMmYF0', 'Al-Baik Di Bistro Restaurant', '3, Jalan Tun Tan Cheng Lock, City Centre, 50050 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6959181, 3.1445632, NULL),
(23, 'ChIJ10yNZHpJzDERctqOuzJv7WA', 'Barra Restaurant', '158, Jalan Petaling, City Centre, 50000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6982010, 3.1407535, NULL),
(24, 'ChIJKdx-FsVJzDER94Il2e9a6iM', 'Heritage One Station Restaurant', 'Bangunan Stesen Keretapi, 2, Jalan Sultan Hishamuddin, Kampung Attap, 50050 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6933454, 3.1403277, NULL),
(26, 'ChIJnftOSGZJzDERmFJXvw8q6uE', 'The Lankan KL', '57, Jalan Sultan, City Centre, 50000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6988056, 3.1430000, NULL),
(27, 'ChIJh3ZOYLlNzDERuh_5oyWvsCU', 'Chum Chum Pizzeria & MAKAMAKAN by Serai Group', '171, Jalan Tun H S Lee, City Centre, 50000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6964667, 3.1430827, NULL),
(28, 'ChIJF9vClg9JzDEROmZLFClX7BI', 'Dodoo Kitchen KL', '192, Jalan Tun H S Lee, City Centre, 50000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6966177, 3.1432151, NULL),
(29, 'ChIJ8wMHh2RJzDER-OYCWRC0QAM', 'Ní.KIZOKU Modern Japanese Dining Bar 霓貴族', '59A, Jalan Sultan, City Centre, 50000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6987587, 3.1430708, NULL),
(30, 'ChIJcUEU9NBJzDERmdp-SI6pBDk', 'Restoran Han Kee', '46, Jalan Sultan, City Centre, 50000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6985406, 3.1431643, NULL),
(31, 'ChIJb7Fg89BJzDERf2kY_kQPtNk', 'Westlake restaurant', '40, Jalan Sultan, City Centre, 50000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6985827, 3.1429904, NULL),
(32, 'ChIJdwiugCk2zDER-Nq2nFuVqMk', 'Relax Time Foot Reflexology', '69 Tingkat Bawah, Changkat Bukit Bintang, Bukit Bintang, 50200 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7093593, 3.1463365, NULL),
(33, 'ChIJedFyHgA3zDERFuCrMQ1_RmY', 'BE RELAX SPA MASSAGE', 'Berjaya Times Square, Imbi, 55100 Kuala Lumpur, Federal Territory of Kuala Lumpur, Malaysia', 101.7105512, 3.1421984, NULL),
(34, 'ChIJU0CFLio2zDERuy1kXU4rRX8', 'Bintang Relax Reflexology', '71, Jln Bukit Bintang, Bukit Bintang, 55100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7092981, 3.1467400, NULL),
(35, 'ChIJqRpFS8BJzDERyZFHD2Blw6M', 'Relax Oasis Signature Massage', 'Lot 3-24, Nu Sentral Mall, 201, Jalan Tun Sambanthan, Brickfields, 50470 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6868521, 3.1333800, NULL),
(36, 'ChIJw7XMOQBLzDER0qy7Ss4q8Rg', 'Relax Wellness OUG', '39m, Jalan Mega Mendung, Taman United, 58200 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6732097, 3.0832129, NULL),
(37, 'ChIJHQutnBE4zDERhKa0PWH1-ag', 'Relax Oasis', '67, Jln Taman Ibu Kota, Taman Danau Kota, 53300 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7207315, 3.2045417, NULL),
(38, 'ChIJJw8eYGo3zDERTm9iLgNQxD8', 'Pusat Relax Reflex & Success', 'Sungei Wang Plaza, Jln Sultan Ismail, Bukit Bintang, 50200 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7114301, 3.1451862, NULL),
(39, 'ChIJyTXJANFJzDERIYNUUKRFbVc', 'Relax & Health Foot Reflexology', '23,23a, Jalan Hang Lekir, City Centre, 50000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6984304, 3.1443382, NULL),
(40, 'ChIJAd_rr9tJzDERlgxIxsT26Yk', 'GCK JS CAFE Ride N Relax', '315, Lorong Tuanku Abdul Rahman 2, Chow Kit, 50300 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6972185, 3.1599280, NULL),
(41, 'ChIJgZ_TfndJzDERMwGDfnrla_4', 'CHILL MATE CAFE', 'Lot K2&K3, Jalan Raja Uda, Kampung Baru, 50300 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7061444, 3.1655596, NULL),
(42, 'ChIJh1xnSABHzDER4UB3aMyuGjs', 'Chill Lounge', 'NO 5-2 JALAN 3/62A BANDAR MENJALARA, KEPONG, Kepong, 52100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6287580, 3.1950054, NULL),
(43, 'ChIJTadUOC9IzDERl0bLJsnzDOY', 'Chill Chill @ Quill City Mall', '50250, Quill City Mall, 1018, Jln Sultan Ismail, Bandar Wawasan, 55100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7001253, 3.1594441, NULL),
(44, 'ChIJn7bwacpHzDERExREYt5Bbic', 'It\'s Chill Time', '3-G, Jalan SS2/ 64, SS 2, 47300 Petaling Jaya, Selangor, Malaysia', 101.6199278, 3.1181697, NULL),
(45, 'ChIJe4npNvU1zDERDqDJp5jRbb8', 'Chill Bay Restaurant and Bar', '2-G &, 2A-G, Jalan Tengah Cheras Selatan 118, Taman Sri Indah, 43200 Cheras, Selangor, Malaysia', 101.7690935, 3.0353576, NULL),
(46, 'ChIJ4dGVK95LzDERpg5cLT_preY', 'Chill Haus', 'C­-02­-05 PLAZA BUKIT JALIL JALAN PERSIARAN JALIL 1 BANDAR BUKIT JALIL, Bandar, Bukit Jalil, 57000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6688649, 3.0531771, NULL),
(47, 'ChIJI4OglXtNzDERQ5kqD6v0PQY', 'Chill at Buriram - Your Neighborhood Bar', '3a, Jalan SS 12/1b, SS12, 47500 Subang Jaya, Selangor, Malaysia', 101.5944372, 3.0787266, NULL),
(48, 'ChIJg-iNXO9HzDEREghcQLXjV90', 'Papa Seafood & Chill', '50, Jalan 29, Selayang Baru, 68100 Batu Caves, Selangor, Malaysia', 101.6647719, 3.2498789, NULL),
(49, 'ChIJxyA7EsZJzDERPGYegMrXm5k', 'Piccola Kitchen & Bar', '6, Jalan Ceylon, Bukit Ceylon, 50200 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7056063, 3.1480307, NULL),
(51, 'ChIJ40RJN9VOzDERoOsruuWzGMg', 'Chill*X Diner @ TGV One Utama', 'Bandar Utama, 47800 Petaling Jaya, Selangor, Malaysia', 101.6169621, 3.1484038, NULL),
(53, 'ChIJJUuDw3XHzjER1ZME-3KzBMo', 'ZUS Coffee - Jalan Tun Perak, Masjid Jamek', 'No. 45-1 & 45-2, GROUND FLOOR, Lebuh Ampang, City Centre, 50100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6970357, 3.1485279, NULL),
(54, 'ChIJIcLEWYFJzDERkY2U9o7Bi-c', 'ZUS Coffee - Jakel Mall, Kuala Lumpur', 'Lot SC.02 & SC.03, Jakel Mall, Lot 159, Jakel Square, Off, Jalan Munshi Abdullah, City Centre, 50100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6995880, 3.1538864, NULL),
(55, 'ChIJD1DKVbE3zDER9rg8xHU5l60', 'ZUS Coffee - Suria KLCC', 'Lot No. OS301 , Level 3, Menara Berkembar Petronas, Persiaran Petronas, Kuala Lumpur City Centre, 50088 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7127089, 3.1576844, NULL),
(56, 'ChIJrbUZRLhJzDERj7ThagxEVh4', 'ZUS Coffee Quill City Mall', 'LG-23A, 1018, Jln Sultan Ismail, Bandar Wawasan, 54200 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.7005383, 3.1598185, NULL),
(57, 'ChIJ28XWEtdJzDER4UDBFKbzpOo', 'ZUS Coffee - Pertama Complex, Kuala Lumpur', 'Kiosk No. 3, Kompleks Pertama, Jalan Tuanku Abdul Rahman, Chow Kit, 50100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6955075, 3.1574179, NULL),
(58, 'ChIJN3WHly1JzDERn3JXzhf_TGw', 'ZUS Coffee - Semua House', 'GF.04, Ground Floor, Semua House, Jalan Bunus 6, City Centre, 50100 Wilayah Persekutuan, Wilayah Persekutuan Kuala Lumpur, Malaysia', 101.6972503, 3.1550605, NULL),
(59, 'ChIJMaC2v0M4zDERhKvjqmfgxS0', 'Tunku Abdul Rahman University of Management and Technology (TAR UMT)', 'Ground Floor, Bangunan Tan Sri Khaw Kai Boh (Block A), Jalan Genting Kelang, Setapak, 53300 Kuala Lumpur, Federal Territory of Kuala Lumpur, Malaysia', 101.7265571, 3.2152552, NULL);

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
(1, 'Yeoh', 'Yeah', 'INFJ', 'NEW', '0102348059', '123'),
(2, 'Yeoh', 'Yeah', 'INFJ', 'NEW', '0102348059', '123'),
(3, 'Vin Sen', 'I am a person', 'INFJ', 'NEW', '0173098763', 'abc');

-- --------------------------------------------------------

--
-- Table structure for table `profilegathering`
--

CREATE TABLE `profilegathering` (
  `profileGatheringID` int(11) NOT NULL,
  `profileID` int(11) NOT NULL,
  `gatheringID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profilegathering`
--

INSERT INTO `profilegathering` (`profileGatheringID`, `profileID`, `gatheringID`) VALUES
(40, 1, 1),
(41, 1, 1),
(42, 1, 3),
(43, 1, 4),
(44, 1, 5),
(45, 1, 6),
(46, 3, 12),
(47, 3, 13),
(49, 3, 14),
(50, 3, 15),
(51, 3, 16),
(52, 3, 17),
(53, 1, 17),
(54, 3, 18),
(57, 3, 21),
(58, 3, 22),
(61, 3, 25),
(62, 3, 26),
(63, 3, 27);

-- --------------------------------------------------------

--
-- Table structure for table `profile_hobby`
--

CREATE TABLE `profile_hobby` (
  `hobby` enum('Basketball','Badminton','Hiking','Singing','Photography','Reading','Jogging','Camping','Traveling','Swimming','Yoga','Meditation','Drawing','Painting','Squash','Gym') NOT NULL,
  `profileID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile_hobby`
--

INSERT INTO `profile_hobby` (`hobby`, `profileID`) VALUES
('Badminton', 1),
('Badminton', 3),
('Hiking', 1),
('Singing', 3),
('Yoga', 2),
('Drawing', 2),
('Painting', 2),
('Gym', 3);

-- --------------------------------------------------------

--
-- Table structure for table `profile_preference`
--

CREATE TABLE `profile_preference` (
  `preference` enum('Entertainment','Sports','Dining','Nature','Hangout','Coffee','Picnic','Chill') NOT NULL,
  `profileID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile_preference`
--

INSERT INTO `profile_preference` (`preference`, `profileID`) VALUES
('Entertainment', 3),
('Dining', 3),
('Nature', 2),
('Nature', 3),
('Coffee', 2),
('Coffee', 3),
('Chill', 1);

-- --------------------------------------------------------

--
-- Table structure for table `reminder`
--

CREATE TABLE `reminder` (
  `reminderID` int(11) NOT NULL,
  `profileID` int(11) NOT NULL,
  `description` text NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 1, 'happy', 'i am very happy today', '2025-04-28 11:52:07'),
(2, 1, 'sad', 'i am very sad', '2025-04-28 11:52:07'),
(3, 1, 'happy', 'i am very happy today', '2025-04-28 11:52:11'),
(4, 1, 'sad', 'i am very sad', '2025-04-28 11:52:11');

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
  ADD PRIMARY KEY (`profileGatheringID`),
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
  ADD KEY `profileID` (`profileID`);

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
  MODIFY `feedbackID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gathering`
--
ALTER TABLE `gathering`
  MODIFY `gatheringID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `locationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `profileID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `profilegathering`
--
ALTER TABLE `profilegathering`
  MODIFY `profileGatheringID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `reminder`
--
ALTER TABLE `reminder`
  MODIFY `reminderID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `self_reflect`
--
ALTER TABLE `self_reflect`
  MODIFY `selfreflectID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  ADD CONSTRAINT `reminder_ibfk_1` FOREIGN KEY (`profileID`) REFERENCES `profile` (`profileID`);

--
-- Constraints for table `self_reflect`
--
ALTER TABLE `self_reflect`
  ADD CONSTRAINT `self_reflect_ibfk_1` FOREIGN KEY (`profileID`) REFERENCES `profile` (`profileID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
