-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2016 at 04:00 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rokomariserviceoadb`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendences`
--

CREATE TABLE `attendences` (
  `id` int(10) UNSIGNED NOT NULL,
  `total_month` int(11) NOT NULL,
  `perfect_zone` int(11) NOT NULL,
  `good_zone` int(11) NOT NULL,
  `total_mark` double NOT NULL,
  `member_pin` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `attendences`
--



-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` int(10) UNSIGNED NOT NULL,
  `organization_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `evaluations`
--



-- --------------------------------------------------------

--
-- Table structure for table `evaluation_marks`
--

CREATE TABLE `evaluation_marks` (
  `id` int(10) UNSIGNED NOT NULL,
  `evaluation_id` int(10) UNSIGNED NOT NULL,
  `evaluation_team_id` int(10) UNSIGNED NOT NULL,
  `member_id` int(10) UNSIGNED NOT NULL,
  `mark_with_coordinator` float(5,2) UNSIGNED NOT NULL,
  `mark_without_coordinator` float(5,2) UNSIGNED NOT NULL,
  `valuator_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `evaluation_marks`
--


-- --------------------------------------------------------

--
-- Table structure for table `evaluation_teams`
--

CREATE TABLE `evaluation_teams` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `coordinator_id` int(10) UNSIGNED DEFAULT NULL,
  `evaluation_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `evaluation_teams`
--


-- --------------------------------------------------------

--
-- Table structure for table `evaluation_team_members`
--

CREATE TABLE `evaluation_team_members` (
  `id` int(10) UNSIGNED NOT NULL,
  `evaluation_team_id` int(10) UNSIGNED NOT NULL,
  `member_id` int(10) UNSIGNED NOT NULL,
  `weight` int(5) UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `evaluation_team_members`
--


-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pin` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `organization_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `members`
--

-- --------------------------------------------------------

--
-- Table structure for table `members_natures`
--

CREATE TABLE `members_natures` (
  `id` int(10) UNSIGNED NOT NULL,
  `member_id` int(10) UNSIGNED NOT NULL,
  `nature_id` int(10) UNSIGNED NOT NULL,
  `nature_point` int(11) NOT NULL,
  `valuator_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `members_natures`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--


-- --------------------------------------------------------

--
-- Table structure for table `natures`
--

CREATE TABLE `natures` (
  `id` int(10) UNSIGNED NOT NULL,
  `evaluation_id` int(10) UNSIGNED NOT NULL,
  `serial` int(10) UNSIGNED NOT NULL,
  `detail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `natures`
--


-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `organizations`
--


-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `organization_id` int(10) UNSIGNED NOT NULL,
  `coordinator_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `teams`
--


-- --------------------------------------------------------

--
-- Table structure for table `teams_members`
--

CREATE TABLE `teams_members` (
  `id` int(10) UNSIGNED NOT NULL,
  `team_id` int(10) UNSIGNED NOT NULL,
  `member_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `teams_members`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` tinyint(1) NOT NULL DEFAULT '1',
  `organization_id` int(10) UNSIGNED DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--


--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendences`
--
ALTER TABLE `attendences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organization_id` (`organization_id`);

--
-- Indexes for table `evaluation_marks`
--
ALTER TABLE `evaluation_marks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `valuator_id` (`valuator_id`),
  ADD KEY `evaluation_team_id` (`evaluation_team_id`),
  ADD KEY `evaluation_id` (`evaluation_id`);

--
-- Indexes for table `evaluation_teams`
--
ALTER TABLE `evaluation_teams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coordinator_id` (`coordinator_id`),
  ADD KEY `evaluation_id` (`evaluation_id`);

--
-- Indexes for table `evaluation_team_members`
--
ALTER TABLE `evaluation_team_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evaluation_team_id` (`evaluation_team_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pin` (`pin`),
  ADD KEY `organization_id` (`organization_id`);

--
-- Indexes for table `members_natures`
--
ALTER TABLE `members_natures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `nature_id` (`nature_id`),
  ADD KEY `valuator_id` (`valuator_id`);

--
-- Indexes for table `natures`
--
ALTER TABLE `natures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evaluation_id` (`evaluation_id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organization_id` (`organization_id`),
  ADD KEY `coordinator_id` (`coordinator_id`);

--
-- Indexes for table `teams_members`
--
ALTER TABLE `teams_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `team_id` (`team_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `organization_id` (`organization_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendences`
--
ALTER TABLE `attendences`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `evaluation_marks`
--
ALTER TABLE `evaluation_marks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `evaluation_teams`
--
ALTER TABLE `evaluation_teams`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `evaluation_team_members`
--
ALTER TABLE `evaluation_team_members`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `members_natures`
--
ALTER TABLE `members_natures`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `natures`
--
ALTER TABLE `natures`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `teams_members`
--
ALTER TABLE `teams_members`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`);

--
-- Constraints for table `evaluation_marks`
--
ALTER TABLE `evaluation_marks`
  ADD CONSTRAINT `evaluation_marks_ibfk_1` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluations` (`id`),
  ADD CONSTRAINT `evaluation_marks_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`),
  ADD CONSTRAINT `evaluation_marks_ibfk_3` FOREIGN KEY (`valuator_id`) REFERENCES `members` (`id`),
  ADD CONSTRAINT `evaluation_marks_ibfk_4` FOREIGN KEY (`evaluation_team_id`) REFERENCES `evaluation_teams` (`id`);

--
-- Constraints for table `evaluation_teams`
--
ALTER TABLE `evaluation_teams`
  ADD CONSTRAINT `evaluation_teams_ibfk_1` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluations` (`id`),
  ADD CONSTRAINT `evaluation_teams_ibfk_2` FOREIGN KEY (`coordinator_id`) REFERENCES `members` (`id`);

--
-- Constraints for table `evaluation_team_members`
--
ALTER TABLE `evaluation_team_members`
  ADD CONSTRAINT `evaluation_team_members_ibfk_1` FOREIGN KEY (`evaluation_team_id`) REFERENCES `evaluation_teams` (`id`),
  ADD CONSTRAINT `evaluation_team_members_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`);

--
-- Constraints for table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `members_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`);

--
-- Constraints for table `members_natures`
--
ALTER TABLE `members_natures`
  ADD CONSTRAINT `members_natures_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`),
  ADD CONSTRAINT `members_natures_ibfk_3` FOREIGN KEY (`nature_id`) REFERENCES `natures` (`id`),
  ADD CONSTRAINT `members_natures_ibfk_4` FOREIGN KEY (`valuator_id`) REFERENCES `members` (`id`);

--
-- Constraints for table `natures`
--
ALTER TABLE `natures`
  ADD CONSTRAINT `natures_ibfk_1` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluations` (`id`);

--
-- Constraints for table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`),
  ADD CONSTRAINT `teams_ibfk_2` FOREIGN KEY (`coordinator_id`) REFERENCES `members` (`id`);

--
-- Constraints for table `teams_members`
--
ALTER TABLE `teams_members`
  ADD CONSTRAINT `teams_members_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`),
  ADD CONSTRAINT `teams_members_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
