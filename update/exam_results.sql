-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2017 at 06:02 PM
-- Server version: 5.7.19-0ubuntu0.17.04.1
-- PHP Version: 5.6.31-6+ubuntu17.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zalongwatiob`
--

-- --------------------------------------------------------

--
-- Table structure for table `exam_results`
--

CREATE TABLE `exam_results` (
  `id` int(10) UNSIGNED NOT NULL,
  `exam_category` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `academic_year` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `semester` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `course` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `exam_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `registration_number` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `score_marks` decimal(5,2) DEFAULT NULL,
  `history` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `published` tinyint(1) DEFAULT '0',
  `comments` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `has_sup` tinyint(1) DEFAULT NULL,
  `has_carry` tinyint(1) DEFAULT NULL,
  `exam_session` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `action_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Recorder` varchar(150) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Triggers `exam_results`
--
DELIMITER $$
CREATE TRIGGER `before_exam_results_delete` BEFORE DELETE ON `exam_results` FOR EACH ROW BEGIN 
INSERT INTO exam_results_audit 
SET action_value = 'delete', 
  exam_category = OLD.exam_category,
  academic_year =OLD.academic_year,
  semester =OLD.semester,
  course =OLD.course,
  exam_date= OLD.exam_date,
  registration_number=OLD.registration_number,
  score_marks=OLD.score_marks,
  score_marks_after=OLD.score_marks,
  history =OLD.history,
  published= OLD.published,
  comments=OLD.comments,
  has_sup =OLD.has_sup,
  has_carry= OLD.has_carry,
  exam_session=OLD.exam_session,
  Recorder=OLD.Recorder,
  action_time= OLD.action_time,
  action_user=OLD.Recorder;
  END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_exam_results_update` BEFORE UPDATE ON `exam_results` FOR EACH ROW BEGIN 
IF(NEW.score_marks <> OLD.score_marks) THEN 
INSERT INTO exam_results_audit 
SET action_value = 'update',
  exam_category = OLD.exam_category,
  academic_year =OLD.academic_year,
  semester =OLD.semester,
  course =OLD.course,
  exam_date= OLD.exam_date,
  registration_number=OLD.registration_number,
  score_marks=OLD.score_marks,
  score_marks_after=NEW.score_marks,
  history =OLD.history,
  published= OLD.published,
  comments=OLD.comments,
  has_sup =OLD.has_sup,
  has_carry= OLD.has_carry,
  exam_session=OLD.exam_session,
  Recorder=OLD.Recorder,
  action_time= NEW.action_time,
  action_user=NEW.Recorder;
  END IF; 
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `exam_results`
--
ALTER TABLE `exam_results`
  ADD PRIMARY KEY (`academic_year`,`course`,`registration_number`,`exam_session`),
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `exam_results`
--
ALTER TABLE `exam_results`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
