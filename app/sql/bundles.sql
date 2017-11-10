-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 04, 2016 at 05:50 PM
-- Server version: 5.7.15-0ubuntu0.16.04.1
-- PHP Version: 5.6.23-1+deprecated+dontuse+deb.sury.org~trusty+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bundles`
--

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`id`, `name`) VALUES
(9, 'AMI Commerciale'),
(4, 'ESSAKIA Immoblière'),
(5, 'GEMI'),
(3, 'KBH Audit & Conseil'),
(1, 'SYSTEO'),
(2, 'TOP CONSEILS');

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE `file` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `entity` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `file`
--

INSERT INTO `file` (`id`, `name`, `file_name`, `entity`, `entity_id`, `path`) VALUES
(1, 'cv tarak 2016', '0c2a7987e8d33f5c467e4aa4b054bbc9.pdf', 'User', 2, NULL),
(2, 'CV Racel', '103ce122d8aaf5967e7bde0b968cbe48.pdf', 'User', 3, '123'),
(3, 'test final', '0557de34a06df5b050abc018aa9d60d1.pdf', NULL, NULL, NULL),
(4, 'test tpath', 'e819d175772a4280c74468b0c6b1fc38.pdf', 'User', 10, NULL),
(5, 'test path 2', '71be7a6494b426bfcd108f92e8ec3620.txt', 'User', NULL, NULL),
(6, 'test ID', 'eaad76cdf38ee8ec01702aa26ea34547.pdf', 'User', 10, NULL),
(7, 'test new algo', '20611f1e68cec251974c2602d6d757bf.bin', 'User', NULL, NULL),
(8, 'CV Racel', '0d3a72461ed12dc6a14d02c4c106080c.bin', 'User', 6, NULL),
(9, 'CV Race6', 'e2f24a532ccd7eecae3bdf58984cebf8.pdf', 'User', 10, NULL),
(10, 'tarak', '1b1434dfaae495288e798a2ca3d2f99b.pdf', 'User', 10, NULL),
(11, 'Ons', '185a734c4cf8ecce0643f18afa47c197.pdf', 'User', 6, NULL),
(12, 'fdiver', '884af113982481e33720d4c70c329cb3.pdf', 'USer', NULL, NULL),
(13, 'aaa', 'a7325838d3cd2ba1925aee66b98fe4cc.pdf', 'User', NULL, NULL),
(14, 'test final', 'e6775bac1d08127f2202296077dc8de9.odt', NULL, NULL, NULL),
(15, 'test final', 'f75509875118de96b5532795d26a7f63.bin', NULL, NULL, NULL),
(16, 'ss', '7a45efc7b4e0a5c63cea0188f0c8a16c.jpeg', NULL, NULL, NULL),
(17, 'zzzz', 'ed73d9213487d7617e0b39af7ddd9729.pdf', 'Voiture', 3, NULL),
(18, 'test ajax 2', '429e15abbb48bf921dba5cdf0825a32c.pdf', 'User', 5, NULL),
(19, NULL, '9e0e90d721248d564617ed67319a693c.pdf', NULL, NULL, NULL),
(20, 'tarak mrabet', '9bb3a3d2c81bf6f9b449bd736faced47.pdf', 'User', 1, NULL),
(21, 'tarak mrabet', 'ae960835d1aa625e5bb02b8f8b2e081b.pdf', 'User', 1, NULL),
(22, 'tarak mrabet', '3960732902858a1a0fe2fc37e809d040.odt', 'User', 1, NULL),
(23, 'hédi mrabet', '9a2a7c565d8f99644fe84c4b818c1e27.pdf', 'User', 3, NULL),
(26, 'youssef mrabet', 'e157bb8095968f99b089170c7e5c6453.pdf', 'User', 2, NULL),
(28, 'youssef mrabet', '41ae2d2580d33f6ab5cd66c07e8e1709.odt', 'User', 2, NULL),
(29, 'youssef mrabet', 'b2c594fb41f11c0790e10e44bf949a06.pdf', 'User', 2, NULL),
(30, 'youssef mrabet', '1084b26351c441c8efa873bbbcf64ff5.pdf', 'User', 2, NULL),
(31, 'tarak mrabet', 'e672e31352c1a2b2c8d962a92709dcf1.jpeg', 'User', 2, NULL),
(43, 'tarak mrabet', '04de7a30841da98555aad9e0768a7d6a.jpeg', 'User', 2, NULL),
(46, 'tarak mrabet', '38b711059f31ad0feb41fb34b84aa233.docx', 'User', 2, NULL),
(47, 'tarak mrabet', 'e4dd2438fc677dbe2efca75e7df44f9a.png', 'User', 2, NULL),
(48, 'CRA_OMICRONE_AVRIL.xlsx', '811002b7c8bb1d8c7312241506908437.bin', 'User', 2, NULL),
(49, 'CRA_OMICRONE -Juillet.xlsx', '0485cc884f3840961aa2917b36ed3ae4.', 'User', 2, NULL),
(50, 'facebook-ads.pdf', 'c5a1cf9756cd98743ea22e883d58f275.pdf', 'User', 2, NULL),
(52, 'brief.pdf', 'bfe3ef7173981e5f8a4339b93d93d59c.pdf', 'User', 3, NULL),
(53, 'tarak.jpeg', 'ff3080a220f2880c8f4352a98ea3e06a.jpeg', 'User', 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`id`, `client_id`, `name`, `active`) VALUES
(1, 2, 'Garages', 1),
(2, 1, 'Centrale', 1),
(3, 2, 'Brokergest', 1),
(5, 2, 'Autoupdate', 0);

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `task_category_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`id`, `client_id`, `project_id`, `task_category_id`, `user_id`, `date`, `start_time`, `end_time`, `duration`, `description`) VALUES
(1, 2, 1, 1, 2, '2016-09-30', '10:00:00', '12:05:00', 7500, 'Intégration vue ajouter tachesss'),
(2, 1, 2, 1, 2, '2016-10-01', '10:04:00', '10:58:00', 3240, 'Travaux de dev sur dynamic form'),
(3, 5, NULL, 2, 8, '2016-10-03', '00:05:00', '23:55:00', 85800, 'ddddd'),
(4, 2, 1, 1, 1, '2016-10-03', '15:00:00', '16:55:00', 6900, 'Petits travaux gnénérauxd'),
(5, 1, 2, 1, 8, '2016-10-04', '09:30:00', '10:30:00', 3600, 'Travaux sur intervalle de dategg'),
(6, NULL, NULL, 3, 1, '2016-10-04', '09:00:00', '18:10:00', 33000, 'travaux divers de prospection');

-- --------------------------------------------------------

--
-- Table structure for table `task_category`
--

CREATE TABLE `task_category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `task_category`
--

INSERT INTO `task_category` (`id`, `name`, `active`) VALUES
(1, 'Développement', 1),
(2, 'Mise en production', 1),
(3, 'Prospection', 0);

-- --------------------------------------------------------

--
-- Table structure for table `task_check`
--

CREATE TABLE `task_check` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `window_type` smallint(6) NOT NULL,
  `display_time` time NOT NULL,
  `click_time` time NOT NULL,
  `created` datetime NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `apikey` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `email`, `username`, `password`, `apikey`, `roles`, `active`) VALUES
(1, 'systeo', 'ntimedi', 'contact@systeo.biz', 'superadmin', '$2y$13$doQnLexFxMhgE0w5rF.1Qe0QECBtWDBdiyZ273MNiBFF2/WNyIpIK', 'k75wZ7vxx95We6FD27wVJO82iBy38WqjTlGJi16G', 'a:4:{s:11:"Super Admin";s:16:"ROLE_SUPER_ADMIN";s:5:"Admin";s:10:"ROLE_ADMIN";s:7:"Manager";s:12:"ROLE_MANAGER";s:11:"Utilisateur";s:9:"ROLE_USER";}', 1),
(2, 'Tarak', 'MRABET', 'tarak@systeo.biz', 'tarakM', '$2y$13$DvNq7k3EDAhkATPticeL1eNjf7kJ6juSkUqX0ZRXjR07SXyvuhgUm', '2t5YYzQVWCvAAnI2LB1xRah2HQbyNIpF1ktPKaBw', 'a:1:{s:5:"Admin";s:10:"ROLE_ADMIN";}', 1),
(8, 'Mohamed Hédi', 'MRABET', 'hedi@systeo.biz', 'hedi', '$2y$13$MpfBUYYL0IYSmJCBomXud.2IZRc5749B8lAG2EMVc3OywgKuzoklq', 'Ao7ZXJ4vUJMhSFu2kwudoGOVQo9ItXOUbLJZlDl6', 'a:1:{s:11:"Utilisateur";s:9:"ROLE_USER";}', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_C74404555E237E06` (`name`);

--
-- Indexes for table `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_2FB3D0EE19EB6921` (`client_id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_527EDB2519EB6921` (`client_id`),
  ADD KEY `IDX_527EDB25166D1F9C` (`project_id`),
  ADD KEY `IDX_527EDB25543330D0` (`task_category_id`),
  ADD KEY `IDX_527EDB25A76ED395` (`user_id`);

--
-- Indexes for table `task_category`
--
ALTER TABLE `task_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_check`
--
ALTER TABLE `task_check`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_FB2AB733A76ED395` (`user_id`),
  ADD KEY `IDX_FB2AB7338DB60186` (`task_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  ADD UNIQUE KEY `UNIQ_8D93D649B84757A1` (`apikey`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `file`
--
ALTER TABLE `file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;
--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `task_category`
--
ALTER TABLE `task_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `task_check`
--
ALTER TABLE `task_check`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `FK_2FB3D0EE19EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`);

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `FK_527EDB25166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_527EDB2519EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `FK_527EDB25543330D0` FOREIGN KEY (`task_category_id`) REFERENCES `task_category` (`id`),
  ADD CONSTRAINT `FK_527EDB25A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `task_check`
--
ALTER TABLE `task_check`
  ADD CONSTRAINT `FK_FB2AB7338DB60186` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`),
  ADD CONSTRAINT `FK_FB2AB733A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
