-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 05, 2026 at 05:31 PM
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
-- Database: `MovieFlix`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(11, 'Fantasy'),
(16, 'Thriller'),
(17, 'Action'),
(18, 'Mystery'),
(19, 'Comedy'),
(21, 'Horror');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `movie_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `release_date` date NOT NULL,
  `duration` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `cover_image` varchar(255) DEFAULT NULL,
  `genre` varchar(100) NOT NULL,
  `avg_sentiment` float DEFAULT 0,
  `category_vector` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`category_vector`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`movie_id`, `title`, `description`, `release_date`, `duration`, `file_path`, `created_at`, `cover_image`, `genre`, `avg_sentiment`, `category_vector`) VALUES
(56, 'The Conjuring', 'The Conjuring (2013) is a supernatural horror film directed by James Wan. It is based on the real-life cases of paranormal investigators Ed and Lorraine Warren. The story follows the Perron family, who move into an old farmhouse in Rhode Island, only to experience increasingly disturbing and terrifying supernatural events', '2015-01-15', 120, 'uploads/movies/conjuring.mp4', '2026-01-05 07:22:20', 'uploads/images/Conjuring.jpg', '', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `movie_categories`
--

CREATE TABLE `movie_categories` (
  `movie_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movie_categories`
--

INSERT INTO `movie_categories` (`movie_id`, `category_id`) VALUES
(56, 21);

-- --------------------------------------------------------

--
-- Table structure for table `movie_recommendations`
--

CREATE TABLE `movie_recommendations` (
  `movie_id` int(11) NOT NULL,
  `recs_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`recs_json`)),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `movie_sentiments`
--

CREATE TABLE `movie_sentiments` (
  `movie_id` int(11) NOT NULL,
  `avg_sentiment` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `movie_vectors`
--

CREATE TABLE `movie_vectors` (
  `movie_id` int(11) NOT NULL,
  `vector_json` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `rating` decimal(2,1) DEFAULT NULL CHECK (`rating` >= 0 and `rating` <= 5),
  `review_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `created_at`, `is_admin`) VALUES
(1, 'Gehendra Chaudhary', 'chaudharyegehendra49@gmail.com', 'Gehendra123@', '2024-10-28 01:17:44', 1),
(4, 'Gehendrachy', 'chaudharygehendra46@gmail.com', '', '2024-11-08 05:04:30', 0),
(7, 'Gehendrad', 'chaudharygehendra40@gmail.com', 'Gehendra123', '2024-11-08 09:55:01', 0),
(8, 'Gehend', 'chaudharygehendra41@gmail.com', 'Gehendra123', '2024-11-08 10:00:22', 0),
(9, 'Lamenta', 'chaudharygehendra10@gmail.com', 'Gehendra12', '2024-11-08 10:12:50', 0),
(10, 'Lament', 'chaudharygehendra11@gmail.com', 'Gehendra12', '2024-11-08 10:21:37', 0),
(11, 'john', 'chaudharygehendra12@gmail.com', 'Gehendra12', '2024-11-08 10:23:30', 0),
(13, 'sadasd', 'chaudharygehendra45@gmail.com', 'Gehendra123', '2024-11-08 10:39:15', 0),
(14, 'ffggf', 'chaudharygehendra67@gmail.com', 'Gehendra12', '2024-11-08 10:42:48', 0),
(15, 'GehendraChadu', 'chaudharygehendra200@gmail.com', 'Gehendra12', '2024-11-08 10:47:59', 0),
(16, 'Gehendrafggvgvv', 'chaudharygehendra400@gmail.com', 'Gehendra12', '2024-11-08 10:50:25', 0),
(17, 'Gehendra123456', 'chasduadry@gmail.com', 'Gehendra123@', '2024-11-12 08:26:04', 0),
(18, 'lol', 'chaudharyhoma49@gmail.com', 'Gehendra123@', '2024-11-21 12:07:35', 0),
(19, 'asdasd', 'lolchaudhary11@gmail.com', 'GHEendra123@', '2024-11-21 12:51:58', 0),
(20, 'Gehendra', 'chaudharygehendra80@gmail.com', 'Gehendra123@', '2024-11-23 02:53:40', 0),
(21, 'Gehendra323', 'chaudharygehendra464@gmail.com', 'Gehendra123@', '2024-11-23 03:35:57', 0),
(22, 'Narayan', 'chaudharygehendra494@gmail.com', 'Gehendra123@', '2024-11-23 03:36:53', 0),
(24, 'Ramu', 'Asha49@gmail.com', 'Gehendra123@', '2024-12-12 06:58:39', 0),
(25, 'John12', 'xyz32@gmail.com', 'User123@', '2024-12-12 11:55:39', 1),
(26, 'Ishwor', 'ishwor@gmail.com', 'Ishwor123@', '2025-02-25 07:36:53', 0),
(27, 'Love', 'lauv@gmail.com', 'Love123@', '2025-02-27 02:20:32', 0),
(28, 'Daji123', 'daji@gmail.com', 'Gehendra123@', '2026-01-04 02:41:58', 0);

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wishlist_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`movie_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Indexes for table `movie_categories`
--
ALTER TABLE `movie_categories`
  ADD PRIMARY KEY (`movie_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `movie_recommendations`
--
ALTER TABLE `movie_recommendations`
  ADD PRIMARY KEY (`movie_id`),
  ADD KEY `updated_at` (`updated_at`);

--
-- Indexes for table `movie_sentiments`
--
ALTER TABLE `movie_sentiments`
  ADD PRIMARY KEY (`movie_id`);

--
-- Indexes for table `movie_vectors`
--
ALTER TABLE `movie_vectors`
  ADD PRIMARY KEY (`movie_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wishlist_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `movie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wishlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `movie_categories`
--
ALTER TABLE `movie_categories`
  ADD CONSTRAINT `movie_categories_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `movie_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
