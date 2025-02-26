-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 26, 2025 at 05:27 PM
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
  `cover_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`movie_id`, `title`, `description`, `release_date`, `duration`, `file_path`, `created_at`, `cover_image`) VALUES
(39, 'Hotel Transylvania 3', 'otel Transylvania 3: Summer Vacation is a 2018 animated film and the third installment in the Hotel Transylvania series. Directed by Genndy Tartakovsky, the movie follows Count Dracula and his monster family as they embark on a luxurious cruise for a much-needed vacation', '2021-01-14', 120, 'uploads/movies/67bf351d13af0_H0t3l.Tr4n5ylv4n14.Tr4n5f0rm4n14.22.br.sdm0v13sp01nt.sbs.mp4', '2024-12-12 18:23:14', 'uploads/images/67bf348334d7b_Hotel TransylVania.jpeg'),
(46, 'Narnia', 'The Narnia series refers to The Chronicles of Narnia, a popular fantasy book series written by C.S. Lewis. The series consists of seven novels, the most famous being The Lion, the Witch, and the Wardrobe.', '2024-12-18', 115, 'uploads/movies/67bf358c11548_Narnia.mp4', '2024-12-13 06:59:46', 'uploads/images/67bf358c1158d_Narnia.jpeg'),
(48, 'Fantastic Beasts', 'Fantastic Beasts is a fantasy film series set in the Wizarding World, the same universe as the Harry Potter series, created by J.K. Rowling. It serves as a prequel to the Harry Potter films, exploring the magical world in the early 20th century.', '2025-02-13', 120, 'uploads/movies/67bf378597ee9_Fantastic Beast.mkv', '2025-02-26 06:59:07', 'uploads/images/67bf375e7ffec_fantastic_beast.jpg'),
(51, 'Angry Birds', 'The Angry Birds Movie is a 2016 animated film based on the popular mobile game series Angry Birds by Rovio Entertainment. Directed by Clay Kaytis and Fergal Reilly, the film is set on Bird Island, where flightless birds live in peace—except for Red, who has trouble managing his anger.', '2024-01-26', 120, 'uploads/movies/movie_67bf2d3945ea46.79109326.mp4', '2025-02-26 15:03:21', 'uploads/images/cover_67bf2d394606d8.84733676.jpeg'),
(52, 'Despicable Me ', 'Despicable Me is a popular animated film franchise produced by Illumination Entertainment. The first movie, Despicable Me, was released in 2010 and centers around a supervillain named Gru, who adopts three orphaned girls and eventually has a change of heart', '2023-06-13', 100, 'uploads/movies/movie_67bf3af6cb48c3.04139130.mp4', '2025-02-26 16:01:58', 'uploads/images/cover_67bf3af6cb4b32.09478364.jpg'),
(53, 'Bhool Bhulaiya 3', '\"Bhool Bhulaiyaa 3\" is a 2024 Indian Hindi-language comedy horror film directed by Anees Bazmee. It serves as the third installment in the \"Bhool Bhulaiyaa\" franchise, following \"Bhool Bhulaiyaa\" (2007) and \"Bhool Bhulaiyaa 2\" (2022). ', '2024-01-26', 130, 'uploads/movies/movie_67bf3c226ddb80.82760802.mkv', '2025-02-26 16:06:58', 'uploads/images/cover_67bf3c226dde26.38932149.jpeg'),
(54, 'Hotel Transylvania 1', 'The story is set in a world where monsters live in hiding, far away from humans. Dracula (voiced by Adam Sandler) has created a lavish hotel to protect his daughter Mavis (voiced by Selena Gomez) from the outside world.', '2023-01-24', 200, 'uploads/movies/67bf4068d9165_hot3l.tr4nsylv4ni4.2.2o15.72o.mp4', '2025-02-26 16:24:16', 'uploads/images/cover_67bf4030cf78f9.37139636.jpeg');

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
(39, 11),
(46, 16),
(48, 18),
(51, 19),
(52, 17),
(53, 21),
(54, 16);

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

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `user_id`, `movie_id`, `rating`, `review_text`, `created_at`) VALUES
(28, 8, 46, 3.0, 'Good movie', '2025-02-26 15:07:19');

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
(26, 'Ishwor', 'ishwor@gmail.com', 'Ishwor123@', '2025-02-25 07:36:53', 0);

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
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`wishlist_id`, `user_id`, `movie_id`, `added_at`) VALUES
(21, 1, 39, '2025-02-26 10:00:43');

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
  ADD PRIMARY KEY (`movie_id`);

--
-- Indexes for table `movie_categories`
--
ALTER TABLE `movie_categories`
  ADD PRIMARY KEY (`movie_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

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
  MODIFY `movie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wishlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

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
