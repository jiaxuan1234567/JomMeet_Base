-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 25, 2025 at 03:36 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
  `preference` enum('ENTERTAINMENT','SPORTS','DINING','NATURE','HANGOUT','COFFEE','PICNIC','CHILL') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gathering`
--

INSERT INTO `gathering` (`gatheringID`, `locationID`, `theme`, `maxParticipant`, `minParticipant`, `currentParticipant`, `date`, `startTime`, `endTime`, `createdAt`, `preference`) VALUES
(1, 1, 'Movie', 5, 3, 2, '2025-04-21', '31:38:28', '34:38:28', '2025-04-25 13:21:35', 'ENTERTAINMENT'),
(3, 1, 'JomMovie', 5, 1, 2, '2025-04-25', '13:13:33', '15:13:33', '2025-04-25 13:21:36', 'ENTERTAINMENT'),
(4, 1, 'JomMovie', 5, 1, 2, '2025-04-25', '13:13:33', '15:13:33', '2025-04-25 13:21:38', 'ENTERTAINMENT');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `locationID` int(11) NOT NULL,
  `locationName` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `longtitude` varchar(255) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`locationID`, `locationName`, `address`, `longtitude`, `latitude`, `image`, `city`, `state`) VALUES
(1, 'TGV Cinemas', 'TGV', '123', '123', 'Yea', 'Ampang', 'Selangor');

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `profileID` int(11) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `aboutme` text NOT NULL,
  `hobbies` enum('BASKETBALL','HIKING','BADMINTON','PHOTOGRAPHY','SINGING','READING','JOGGING','CAMPING','TRAVELING','SWIMMING','YOGA','MEDITIATION','DRAWING','PAINTING','SQUASH','GYM') NOT NULL,
  `preference` enum('ENTERTAINMENT','SPORTS','DINING','NATURE','HANGOUT','COFFEE','PICNIC','CHILL') NOT NULL,
  `mbti` varchar(255) NOT NULL,
  `profileStatus` enum('CREATED','NEW') NOT NULL DEFAULT 'NEW',
  `phone` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`profileID`, `nickname`, `aboutme`, `hobbies`, `preference`, `mbti`, `profileStatus`, `phone`, `password`) VALUES
(1, 'Yeoh', 'Yeah', 'BASKETBALL', 'ENTERTAINMENT', 'INFJ', 'NEW', '0102348059', '123'),
(2, 'Yeoh', 'Yeah', 'BASKETBALL', 'ENTERTAINMENT', 'INFJ', 'NEW', '0102348059', '123');

-- --------------------------------------------------------

--
-- Table structure for table `profileGathering`
--

CREATE TABLE `profileGathering` (
  `profileGatheringID` int(11) NOT NULL,
  `profileID` int(11) NOT NULL,
  `gatheringID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profileGathering`
--

INSERT INTO `profileGathering` (`profileGatheringID`, `profileID`, `gatheringID`) VALUES
(19, 1, 1),
(20, 1, 3),
(21, 1, 4);

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
  ADD KEY `locationID` (`locationID`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`locationID`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`profileID`);

--
-- Indexes for table `profileGathering`
--
ALTER TABLE `profileGathering`
  ADD PRIMARY KEY (`profileGatheringID`),
  ADD KEY `gatheringID` (`gatheringID`),
  ADD KEY `profileID` (`profileID`);

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
  MODIFY `gatheringID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `locationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `profileID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `profileGathering`
--
ALTER TABLE `profileGathering`
  MODIFY `profileGatheringID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `reminder`
--
ALTER TABLE `reminder`
  MODIFY `reminderID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `self_reflect`
--
ALTER TABLE `self_reflect`
  MODIFY `selfreflectID` int(11) NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `gathering_ibfk_1` FOREIGN KEY (`locationID`) REFERENCES `location` (`locationID`);

--
-- Constraints for table `profileGathering`
--
ALTER TABLE `profileGathering`
  ADD CONSTRAINT `profilegathering_ibfk_1` FOREIGN KEY (`gatheringID`) REFERENCES `gathering` (`gatheringID`),
  ADD CONSTRAINT `profilegathering_ibfk_2` FOREIGN KEY (`profileID`) REFERENCES `profile` (`profileID`);

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
