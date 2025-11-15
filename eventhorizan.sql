-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2025 at 02:14 PM
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
-- Database: `eventhorizan`
--

-- --------------------------------------------------------

--
-- Table structure for table `clubs`
--

CREATE TABLE `clubs` (
  `id` int(11) NOT NULL,
  `club_name` varchar(255) NOT NULL,
  `club_description` text DEFAULT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `club_main_image` varchar(255) DEFAULT NULL,
  `club_extra_image_1` varchar(255) DEFAULT NULL,
  `club_extra_image_2` varchar(255) DEFAULT NULL,
  `club_extra_image_3` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `contact_description_1` varchar(255) DEFAULT NULL,
  `contact_number_1` varchar(20) DEFAULT NULL,
  `contact_number_2` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clubs`
--

INSERT INTO `clubs` (`id`, `club_name`, `club_description`, `short_description`, `club_main_image`, `club_extra_image_1`, `club_extra_image_2`, `club_extra_image_3`, `created_at`, `contact_description_1`, `contact_number_1`, `contact_number_2`) VALUES
(1, 'Photography Club', 'A creative community for students passionate about photography. The club organizes photo walks, competitions, and workshops on lighting, editing, and storytelling through images. Members get the chance to showcase their work in exhibitions and collaborate on visual projects across the campus.', 'Capturing creativity through lenses, lighting, and storytelling.', 'photography_main.jpg', 'photography_event1.jpg', 'photography_event2.jpg', 'photography_event3.jpg', '2025-11-07 10:51:18', 'For membership, event details, or photo submissions, please contact the club coordinator.', '+94 75 869 0018', '+94 77 345 6789'),
(5, 'Tech Innovators Club', 'A student-led community at APIIT Sri Lanka focused on developing innovative software and hardware projects. The club conducts coding workshops, hackathons, and tech talks to help members enhance their technical and teamwork skills while exploring the latest trends in technology.', 'Inspiring innovation through coding, teamwork, and technology exploration.', '1762843222_1.jpg', '1762843222_uni3.webp', '1762843222_uni2.webp', '1762843222_h1.jpeg', '2025-11-11 12:10:22', 'For membership, event details, or collaborations, please reach out to our club coordinator.', '+94 71 456 7890', '+94 77 234 5678'),
(7, 'ADHD Awareness Club', 'A supportive community that spreads awareness about Attention Deficit Hyperactivity Disorder (ADHD), offering guidance, workshops, and peer support for students managing ADHD and their allies.', 'Promoting awareness and support for students with ADHD.', '1763001902_uni2.webp', '1763001902_h4.webp', '1763001902_uni3.webp', '1763001902_uni4.jpg', '2025-11-13 08:15:02', 'For membership or event inquiries, contact our club coordinator.', '+94 77 345 6789', '+94 77 345 6789'),
(10, 'Cornell Corkery', 'Eligendi ratione doloribus aperiam nesciunt velit.', 'Delectus fugit eum iusto adipisci consequatur corrupti.', '1763133426_uni3.webp', NULL, NULL, NULL, '2025-11-14 20:47:06', 'Netherlands', '60', '119'),
(11, 'Quinn Runte', 'Cumque in tempora dolores iste quibusdam facere a.', 'Iste modi tempore nisi ipsam eos deserunt debitis.', '1763133451_h1.jpeg', NULL, NULL, NULL, '2025-11-14 20:47:31', 'Eswatini', '544', '129'),
(12, 'Tyson Kemmer', 'Eos aliquam dolores architecto esse.', 'Officia veniam eum totam.', '1763133466_uni6.jpg', NULL, NULL, NULL, '2025-11-14 20:47:46', 'Bolivia', '76', '288'),
(13, 'Lilla Zboncak', 'Aspernatur vel ratione nisi corporis.', 'Distinctio iste tempora harum.', '1763133491_uni1.jpg', NULL, NULL, NULL, '2025-11-14 20:48:11', 'Samoa', '426', '323'),
(14, 'Korbin Block', 'Cumque sed vero numquam doloremque adipisci eveniet neque.', 'Necessitatibus itaque odit sequi ea repellendus culpa quis dolores.', '1763133629_macos-sequoia-forest-3840x2160-24082.jpg', NULL, NULL, NULL, '2025-11-14 20:50:29', 'Azerbaijan', '188', '145'),
(15, 'Audrey Zboncak', 'Officiis laudantium temporibus vitae ut totam non voluptatem est.', 'Sapiente nemo eveniet.', '1763133654_premium_photo-1671734045770-4b9e.png', NULL, NULL, NULL, '2025-11-14 20:50:54', 'American Samoa', '166', '256');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` datetime NOT NULL,
  `venue` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `ticket_url` varchar(255) DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `extra_image_1` varchar(255) DEFAULT NULL,
  `extra_image_2` varchar(255) DEFAULT NULL,
  `extra_image_3` varchar(255) DEFAULT NULL,
  `club_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `venue`, `price`, `ticket_url`, `main_image`, `extra_image_1`, `extra_image_2`, `extra_image_3`, `club_id`, `created_at`) VALUES
(1, 'Annual Photography Exhibition', 'Showcase of photos taken by club members throughout the year.', '2025-12-15 15:30:00', 'APIIT Auditorium', 150000.00, 'https://www.google.com/search?q=rf&oq=rf&gs_lcrp=EgZjaHJvbWUyCQgAEEUYORiABDIKCAEQABixAxiABDIHCAIQABiABDIHCAMQABiABDIHCAQQABiABDIHCAUQABiABDIHCAYQABiABDIGCAcQRRg80gEHNjQ2ajBqN6gCALACAA&sourceid=chrome&ie=UTF-8', 'event_main.jpg', 'event_extra1.jpg', 'event_extra2.jpg', 'event_extra3.jpg', 1, '2025-11-07 10:51:18'),
(11, 'Investor Integration Developer', 'Id iusto quis deserunt laudantium cupiditate.', '2025-12-15 04:17:00', 'APIIT Auditorium', NULL, NULL, '1763007255_uni7.webp', '1763007237_uni1.jpg', '1763007237_uni7.webp', '1763007237_h4.webp', 1, '2025-11-07 15:57:38'),
(12, 'Investor Web Administrator', 'Reprehenderit aperiam ex quas vero recusandae nobis quos.', '2025-12-15 19:18:00', 'APIIT Auditorium', NULL, NULL, '1763007194_uni2.webp', '1763007194_uni4.jpg', '1763007194_uni6.jpg', '1763007194_uni5.png', 1, '2025-11-07 15:58:02'),
(13, 'International Brand Producer', 'Iure mollitia dolor officia vero excepturi architecto similique.', '2025-11-10 10:26:00', 'APIIT Auditorium', NULL, NULL, '1763007172_h3.webp', '1763007172_uni2.webp', '1763007172_uni6.jpg', '1763007172_h4.webp', 1, '2025-11-07 16:22:44');

-- --------------------------------------------------------

--
-- Table structure for table `past_events`
--

CREATE TABLE `past_events` (
  `id` int(11) NOT NULL,
  `club_id` int(11) NOT NULL,
  `event_title` varchar(255) NOT NULL,
  `event_description` text NOT NULL,
  `main_image` varchar(255) NOT NULL,
  `extra_image_1` varchar(255) DEFAULT NULL,
  `extra_image_2` varchar(255) DEFAULT NULL,
  `extra_image_3` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `past_events`
--

INSERT INTO `past_events` (`id`, `club_id`, `event_title`, `event_description`, `main_image`, `extra_image_1`, `extra_image_2`, `extra_image_3`, `created_at`) VALUES
(1, 5, ' Product Communications Workshop 2024', 'The Product Communications Workshop 2024 was designed to help students improve their ability to communicate technical ideas effectively. Industry experts led interactive sessions on presentation design, client communication, and teamwork within software development projects. Participants worked in small groups to practice pitching product ideas and received feedback from mentors on clarity and delivery.', '1763004796_h3.webp', '1763004796_uni7.webp', '1763004796_uni1.jpg', '1763004796_1.jpg', '2025-11-13 03:33:16'),
(2, 7, 'ADHD Awareness Workshop 2024', 'The ADHD Awareness Workshop 2024 was held to educate students and staff about Attention Deficit Hyperactivity Disorder. The event featured a guest psychologist, interactive group sessions, and real-life success stories from individuals managing ADHD. Participants learned effective strategies for focus, time management, and emotional well-being. The session concluded with an open Q&A and resource distribution to support continued awareness on campus.', '1763006759_h3.webp', '1763006759_uni6.jpg', '1763006759_uni5.png', '1763006759_uni4.jpg', '2025-11-13 04:05:59'),
(4, 7, 'Product Communications Workshop 2024', 'Porro minus tempora a voluptatem consectetur quod.', '1763028263_h3.webp', NULL, NULL, NULL, '2025-11-13 10:04:23'),
(5, 1, 'International Implementation Representative', 'Dicta totam voluptatem nobis.', '1763088867_apiit.png', NULL, NULL, NULL, '2025-11-14 02:54:27');

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reminders`
--

INSERT INTO `reminders` (`id`, `user_id`, `event_id`, `created_at`) VALUES
(12, 1, 1, '2025-11-13 06:19:59'),
(13, 3, 1, '2025-11-13 11:24:40'),
(14, 3, 11, '2025-11-13 11:27:47'),
(15, 1, 11, '2025-11-14 15:25:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `created_at`) VALUES
(1, 'ineshfernando643@gmail.com', '$2y$10$LBtPstSlUxrt97Tr2LNUne2gs/311HxAVYzop.AR8faPrIRg3xOH6', '2025-11-06 14:05:45'),
(3, 'fernando@gmail.com', '$2y$10$lIO4u9alICFgTJsl7tZWpuerM/ItVdUTydiWaib8o.9GMRrmRM/u.', '2025-11-13 11:24:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clubs`
--
ALTER TABLE `clubs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_events_clubs` (`club_id`);

--
-- Indexes for table `past_events`
--
ALTER TABLE `past_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `club_id` (`club_id`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clubs`
--
ALTER TABLE `clubs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `past_events`
--
ALTER TABLE `past_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reminders`
--
ALTER TABLE `reminders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `fk_events_clubs` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `past_events`
--
ALTER TABLE `past_events`
  ADD CONSTRAINT `past_events_ibfk_1` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reminders`
--
ALTER TABLE `reminders`
  ADD CONSTRAINT `reminders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reminders_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
