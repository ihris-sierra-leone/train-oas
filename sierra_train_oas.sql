-- MySQL dump 10.13  Distrib 5.7.28, for Linux (x86_64)
--
-- Host: localhost    Database: sierra_train_oas
-- ------------------------------------------------------
-- Server version	5.7.28-0ubuntu0.18.04.4

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `annual_fees`
--

DROP TABLE IF EXISTS `annual_fees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `annual_fees` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `msisdn` varchar(100) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `applicant_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `timestamp` varchar(100) NOT NULL,
  `receipt` varchar(100) NOT NULL,
  `pay_method` varchar(255) DEFAULT NULL,
  `amount` double(100,2) NOT NULL,
  `charges` int(11) NOT NULL,
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `academic_year` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `receipt` (`receipt`),
  KEY `createdon` (`createdon`)
) ENGINE=InnoDB AUTO_INCREMENT=1072 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application`
--

DROP TABLE IF EXISTS `application`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `AYear` varchar(50) NOT NULL,
  `Semester` varchar(50) NOT NULL,
  `application_type` int(11) NOT NULL,
  `CSEE` int(11) DEFAULT '0',
  `NT` int(11) DEFAULT '0',
  `entry_category` varchar(11) DEFAULT '0',
  `duration` int(11) DEFAULT '3',
  `FirstName` varchar(255) NOT NULL,
  `MiddleName` varchar(255) DEFAULT NULL,
  `LastName` varchar(255) NOT NULL,
  `cooperate` int(11) NOT NULL,
  `dob` date NOT NULL,
  `Gender` varchar(20) NOT NULL,
  `Disability` int(11) NOT NULL,
  `Nationality` int(11) NOT NULL,
  `form4_index` varchar(255) DEFAULT NULL,
  `form6_index` varchar(255) DEFAULT NULL,
  `diploma_number` varchar(255) DEFAULT NULL,
  `member_type` int(11) NOT NULL,
  `level` varchar(255) NOT NULL,
  `Regno` varchar(20) NOT NULL,
  `admission_number` varchar(30) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Mobile1` varchar(50) DEFAULT NULL,
  `Mobile2` varchar(50) DEFAULT NULL,
  `postal` text,
  `physical` text,
  `birth_place` text,
  `residence_country` int(11) DEFAULT '0',
  `marital_status` int(11) DEFAULT '1',
  `createdby` bigint(20) NOT NULL,
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modifiedon` timestamp NULL DEFAULT NULL,
  `modifiedby` bigint(20) DEFAULT NULL,
  `photo` varchar(255) DEFAULT 'default.jpg',
  `status` int(11) DEFAULT '0',
  `submitedon` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) DEFAULT '0',
  `tiob_member` varchar(255) DEFAULT NULL,
  `submitted` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8995 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_attachment`
--

DROP TABLE IF EXISTS `application_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `certificate` varchar(11) NOT NULL,
  `attachment` varchar(255) NOT NULL,
  `comment` text,
  `applicant_id` int(11) NOT NULL,
  `createdby` bigint(20) NOT NULL,
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `filename` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2077 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_criteria_setting`
--

DROP TABLE IF EXISTS `application_criteria_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_criteria_setting` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `AYear` varchar(50) NOT NULL,
  `entry` varchar(20) DEFAULT NULL,
  `form4_inclusive` text,
  `form4_exclusive` text,
  `form4_pass` int(11) DEFAULT '0',
  `form6_inclusive` text,
  `form6_exclusive` text,
  `form6_pass` int(11) DEFAULT '0',
  `gpa_pass` double DEFAULT NULL,
  `ProgrammeCode` varchar(50) NOT NULL,
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdby` bigint(20) NOT NULL,
  `modifiedby` bigint(20) DEFAULT NULL,
  `modifiedon` timestamp NULL DEFAULT NULL,
  `min_point` double DEFAULT NULL,
  `form4_or_subject` text,
  `form6_or_subject` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_deadline`
--

DROP TABLE IF EXISTS `application_deadline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_deadline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deadline` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_diploma_nacteresult`
--

DROP TABLE IF EXISTS `application_diploma_nacteresult`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_diploma_nacteresult` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint(20) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `grade` varchar(10) NOT NULL,
  `authority_id` bigint(20) NOT NULL,
  `combine` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_education_authority`
--

DROP TABLE IF EXISTS `application_education_authority`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_education_authority` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `certificate` varchar(11) DEFAULT NULL,
  `exam_authority` varchar(255) NOT NULL,
  `applicant_id` bigint(20) NOT NULL,
  `division` varchar(100) NOT NULL,
  `school` text NOT NULL,
  `country` varchar(100) NOT NULL,
  `programme_title` text,
  `createdon` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `createdby` bigint(20) NOT NULL,
  `index_number` varchar(100) DEFAULT NULL,
  `technician_type` int(11) DEFAULT NULL,
  `completed_year` varchar(100) NOT NULL,
  `center_number` varchar(50) DEFAULT NULL,
  `division_point` varchar(50) DEFAULT NULL,
  `avn` varchar(255) DEFAULT NULL,
  `api_status` int(11) DEFAULT '0',
  `comment` text,
  `response` text,
  `hide` int(11) DEFAULT '0',
  `diploma_code` varchar(50) DEFAULT NULL,
  `programme_category` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=876 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_education_subject`
--

DROP TABLE IF EXISTS `application_education_subject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_education_subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint(20) NOT NULL,
  `authority_id` bigint(20) NOT NULL,
  `certificate` varchar(11) NOT NULL,
  `subject` int(11) NOT NULL,
  `grade` varchar(10) NOT NULL,
  `year` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2559 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_elegibility`
--

DROP TABLE IF EXISTS `application_elegibility`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_elegibility` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint(20) NOT NULL,
  `ProgrammeCode` varchar(50) NOT NULL,
  `choice` int(11) NOT NULL,
  `AYear` varchar(50) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0  = Not Eligible, 1 = Eligible',
  `comment` text,
  `point` int(11) NOT NULL,
  `entry_category` varchar(30) DEFAULT NULL,
  `gpa` varchar(30) DEFAULT NULL,
  `selected` int(11) DEFAULT '0' COMMENT '0=Not selected, 1= Selected',
  `sitting_no` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_employer`
--

DROP TABLE IF EXISTS `application_employer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_employer` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) DEFAULT NULL,
  `mobile1` varchar(50) DEFAULT NULL,
  `mobile2` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text NOT NULL,
  `applicant_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_experience`
--

DROP TABLE IF EXISTS `application_experience`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_experience` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint(20) NOT NULL,
  `type` int(11) NOT NULL,
  `name` text NOT NULL,
  `column1` text,
  `column2` text,
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdby` bigint(20) NOT NULL,
  `modifiedby` bigint(20) DEFAULT NULL,
  `modifiedon` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `applicant_id` (`applicant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_nextkin_info`
--

DROP TABLE IF EXISTS `application_nextkin_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_nextkin_info` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) DEFAULT NULL,
  `mobile1` varchar(50) DEFAULT NULL,
  `mobile2` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `postal` mediumtext NOT NULL,
  `is_primary` int(11) DEFAULT '0',
  `relation` varchar(100) DEFAULT NULL,
  `applicant_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=855 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_payment`
--

DROP TABLE IF EXISTS `application_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_payment` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `msisdn` varchar(100) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `applicant_id` bigint(20) NOT NULL,
  `timestamp` varchar(100) NOT NULL,
  `receipt` varchar(100) NOT NULL,
  `amount` double(100,2) NOT NULL,
  `charges` double(100,2) NOT NULL,
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `academic_year` int(11) NOT NULL,
  `pay_method` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `receipt` (`receipt`),
  KEY `createdon` (`createdon`)
) ENGINE=InnoDB AUTO_INCREMENT=458 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_programme_choice`
--

DROP TABLE IF EXISTS `application_programme_choice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_programme_choice` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint(20) NOT NULL,
  `choice1` varchar(50) NOT NULL,
  `choice2` varchar(50) NOT NULL,
  `choice3` varchar(50) NOT NULL,
  `choice4` varchar(50) NOT NULL,
  `choice5` varchar(50) NOT NULL,
  `createdby` bigint(20) NOT NULL,
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=406 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_referee`
--

DROP TABLE IF EXISTS `application_referee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_referee` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) DEFAULT NULL,
  `mobile1` varchar(50) DEFAULT NULL,
  `mobile2` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `organization` text,
  `position` text,
  `address` text NOT NULL,
  `is_primary` int(11) DEFAULT '0',
  `applicant_id` bigint(20) NOT NULL,
  `rec_code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_referee_recommendation`
--

DROP TABLE IF EXISTS `application_referee_recommendation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_referee_recommendation` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint(20) NOT NULL,
  `referee_id` bigint(20) NOT NULL,
  `recommend_overall` int(11) DEFAULT '-1',
  `applicant_known` text,
  `description_for_qn3` text,
  `weakness` text,
  `other_degree` int(11) DEFAULT '-1',
  `producing_org_work` int(11) DEFAULT '-1',
  `recommendation_arrea` text,
  `other_capability` text,
  `anything` text,
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modifiedon` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_sponsor`
--

DROP TABLE IF EXISTS `application_sponsor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_sponsor` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) DEFAULT NULL,
  `mobile1` varchar(50) DEFAULT NULL,
  `mobile2` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text NOT NULL,
  `applicant_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_steps`
--

DROP TABLE IF EXISTS `application_steps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_steps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint(20) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2907 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ayear`
--

DROP TABLE IF EXISTS `ayear`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ayear` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `AYear` varchar(45) NOT NULL,
  `Status` varchar(1) DEFAULT '0',
  `semester` varchar(50) DEFAULT NULL,
  `campus_id` bigint(20) DEFAULT '1',
  `auto_update` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `campus`
--

DROP TABLE IF EXISTS `campus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campus` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `location` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `certifications`
--

DROP TABLE IF EXISTS `certifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `certifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `college`
--

DROP TABLE IF EXISTS `college`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `college` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) DEFAULT NULL,
  `PostalAddress` varchar(45) DEFAULT NULL,
  `Email` varchar(45) DEFAULT NULL,
  `LandLine` varchar(45) DEFAULT NULL,
  `Mobile` varchar(45) DEFAULT NULL,
  `City` varchar(100) NOT NULL,
  `Country` varchar(100) NOT NULL,
  `Site` varchar(100) NOT NULL,
  `Telegram` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Name_UNIQUE` (`Name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `college_schools`
--

DROP TABLE IF EXISTS `college_schools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `college_schools` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type1` varchar(100) NOT NULL,
  `name` text NOT NULL,
  `principal` bigint(20) NOT NULL,
  `createdby` bigint(20) NOT NULL,
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedby` bigint(20) NOT NULL,
  `modifiedon` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `courses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shortname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `programme_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Facultyid` varchar(45) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Address` varchar(45) DEFAULT NULL,
  `LandLine` varchar(45) DEFAULT NULL,
  `Email` varchar(45) DEFAULT NULL,
  `school_id` int(11) DEFAULT '0',
  `head` bigint(20) DEFAULT '0',
  `location` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `disability`
--

DROP TABLE IF EXISTS `disability`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `disability` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `exam_entry_applications`
--

DROP TABLE IF EXISTS `exam_entry_applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exam_entry_applications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `registration_number` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `examination_center` int(11) DEFAULT NULL,
  `date_processed` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `academic_year` varchar(14) COLLATE utf8_unicode_ci DEFAULT NULL,
  `courses` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `exam_session` int(11) DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `receipt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `exam_fee`
--

DROP TABLE IF EXISTS `exam_fee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exam_fee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `programmeID` int(11) NOT NULL,
  `member_category` varchar(150) NOT NULL,
  `amount` int(11) NOT NULL,
  `annual_amount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `exam_register`
--

DROP TABLE IF EXISTS `exam_register`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exam_register` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coursecode` varchar(100) NOT NULL,
  `centre_id` tinyint(11) NOT NULL,
  `venue` varchar(150) NOT NULL,
  `exam_date` date NOT NULL,
  `time` varchar(30) NOT NULL,
  `year` year(4) NOT NULL,
  `semester` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `exam_results`
--

DROP TABLE IF EXISTS `exam_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exam_results` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `exam_category` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `academic_year` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `semester` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `course` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `exam_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `registration_number` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `score_marks` decimal(5,2) DEFAULT NULL,
  `history` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `published` tinyint(1) DEFAULT '0',
  `comments` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `has_sup` tinyint(1) DEFAULT NULL,
  `has_carry` tinyint(1) DEFAULT NULL,
  `exam_session` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `action_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Recorder` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`academic_year`,`course`,`registration_number`,`exam_session`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5273 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `before_exam_results_update` BEFORE UPDATE ON `exam_results` FOR EACH ROW BEGIN 
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
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `before_exam_results_delete` BEFORE DELETE ON `exam_results` FOR EACH ROW BEGIN 
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
  END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `exam_results_audit`
--

DROP TABLE IF EXISTS `exam_results_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exam_results_audit` (
  `exam_category` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `academic_year` varchar(14) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `semester` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `course` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `exam_date` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `registration_number` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `score_marks` decimal(5,2) DEFAULT NULL,
  `score_marks_after` int(11) NOT NULL,
  `history` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `published` tinyint(1) DEFAULT '0',
  `comments` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `has_sup` tinyint(1) DEFAULT NULL,
  `has_carry` tinyint(1) DEFAULT NULL,
  `exam_session` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `action_time` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Recorder` varchar(150) NOT NULL,
  `action_value` varchar(150) NOT NULL,
  `action_user` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `exam_sessions`
--

DROP TABLE IF EXISTS `exam_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exam_sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `examination_centers`
--

DROP TABLE IF EXISTS `examination_centers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `examination_centers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `center_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `center_code` varchar(11) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `center_venue` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `examinations_payment`
--

DROP TABLE IF EXISTS `examinations_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `examinations_payment` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `msisdn` varchar(100) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `applicant_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `timestamp` varchar(100) NOT NULL,
  `receipt` varchar(100) NOT NULL,
  `pay_method` varchar(255) DEFAULT NULL,
  `amount` double(100,2) NOT NULL,
  `charges` int(11) NOT NULL,
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `academic_year` int(11) NOT NULL,
  `session` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `receipt` (`receipt`),
  KEY `createdon` (`createdon`)
) ENGINE=InnoDB AUTO_INCREMENT=1047 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fee_statement`
--

DROP TABLE IF EXISTS `fee_statement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fee_statement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `timestamp` varchar(100) NOT NULL,
  `receipt` varchar(100) NOT NULL,
  `amount` double(100,2) NOT NULL,
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `academic_year` int(11) NOT NULL,
  `msisdn` varchar(100) DEFAULT NULL,
  `reference` varchar(200) DEFAULT NULL,
  `applicant_id` varchar(200) DEFAULT NULL,
  `flag` varchar(20) DEFAULT NULL,
  `annual_amount` varchar(200) DEFAULT NULL,
  `application_amount` varchar(200) DEFAULT NULL,
  `pay_method` varchar(255) DEFAULT NULL,
  `charges` double(100,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `receipt` (`receipt`),
  KEY `createdon` (`createdon`)
) ENGINE=InnoDB AUTO_INCREMENT=2181 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fellow_member`
--

DROP TABLE IF EXISTS `fellow_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fellow_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_type_id` bigint(20) NOT NULL,
  `name` varchar(150) NOT NULL,
  `title` varchar(150) NOT NULL,
  `mobile` bigint(20) NOT NULL,
  `email` varchar(150) NOT NULL,
  `postal` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gender`
--

DROP TABLE IF EXISTS `gender`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gender` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `group_mails`
--

DROP TABLE IF EXISTS `group_mails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group_mails` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `recipient_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `josephine`
--

DROP TABLE IF EXISTS `josephine`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `josephine` (
  `missing_users_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_notification`
--

DROP TABLE IF EXISTS `log_notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_notification` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `priority` int(11) NOT NULL,
  `message` text NOT NULL,
  `createdby` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mail_group`
--

DROP TABLE IF EXISTS `mail_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `maritalstatus`
--

DROP TABLE IF EXISTS `maritalstatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `maritalstatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `member`
--

DROP TABLE IF EXISTS `member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_type_id` bigint(20) NOT NULL,
  `institution_name` varchar(150) NOT NULL,
  `title` varchar(150) NOT NULL,
  `mobile` bigint(20) NOT NULL,
  `mobiletwo` bigint(20) DEFAULT NULL,
  `telephone` bigint(20) DEFAULT NULL,
  `fax` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `postal` varchar(150) DEFAULT NULL,
  `website` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=166 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `member_type`
--

DROP TABLE IF EXISTS `member_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_type` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `message_sent`
--

DROP TABLE IF EXISTS `message_sent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_sent` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `message_id` varchar(255) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `sender` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `priority` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `sent_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sent_status` varchar(100) NOT NULL,
  `sms_count` int(11) NOT NULL,
  `is_sent` int(11) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1444 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `module`
--

DROP TABLE IF EXISTS `module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `module_group_role`
--

DROP TABLE IF EXISTS `module_group_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `module_group_role` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `section` varchar(200) NOT NULL,
  `role` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_module_group_role_1_idx` (`group_id`),
  KEY `fk_module_group_role_2_idx` (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `module_role`
--

DROP TABLE IF EXISTS `module_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `module_role` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `section` varchar(50) NOT NULL,
  `role` mediumtext NOT NULL,
  `description` mediumtext NOT NULL,
  `only_developer` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_module_role_1_idx` (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `module_section`
--

DROP TABLE IF EXISTS `module_section`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `module_section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_module_section_1_idx` (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `nationality`
--

DROP TABLE IF EXISTS `nationality`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nationality` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` text NOT NULL,
  `Country` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=250 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `necta_check_subject`
--

DROP TABLE IF EXISTS `necta_check_subject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `necta_check_subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `response` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `necta_tmp_result`
--

DROP TABLE IF EXISTS `necta_tmp_result`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `necta_tmp_result` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL,
  `authority_id` int(11) NOT NULL,
  `action` varchar(10) NOT NULL,
  `action_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `applicant_id` bigint(20) DEFAULT NULL,
  `route` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=996 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notify_tmp`
--

DROP TABLE IF EXISTS `notify_tmp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notify_tmp` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `data` text,
  `status` int(11) DEFAULT NULL,
  `sent_count` int(11) DEFAULT '0',
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2557 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payment_status`
--

DROP TABLE IF EXISTS `payment_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_status` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `payment_status` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payments_log`
--

DROP TABLE IF EXISTS `payments_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msisdn` varchar(191) DEFAULT NULL,
  `receipt` varchar(191) DEFAULT NULL,
  `reference` varchar(191) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `data` varchar(1000) DEFAULT NULL,
  `createdon` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=442 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `programme`
--

DROP TABLE IF EXISTS `programme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `programme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(45) DEFAULT NULL,
  `Shortname` varchar(255) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Departmentid` varchar(45) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `Name_UNIQUE` (`Name`),
  UNIQUE KEY `Code_UNIQUE` (`Code`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `recommandation_area`
--

DROP TABLE IF EXISTS `recommandation_area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recommandation_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `run_eligibility`
--

DROP TABLE IF EXISTS `run_eligibility`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `run_eligibility` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ProgrammeCode` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `run_selection`
--

DROP TABLE IF EXISTS `run_selection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `run_selection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ProgrammeCode` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `secondary_category`
--

DROP TABLE IF EXISTS `secondary_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `secondary_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `secondary_subject`
--

DROP TABLE IF EXISTS `secondary_subject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `secondary_subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shortname` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` int(11) DEFAULT '1',
  `code` varchar(50) DEFAULT NULL,
  `category` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `semester`
--

DROP TABLE IF EXISTS `semester`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `semester` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Name_UNIQUE` (`Name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `student_exam_registered`
--

DROP TABLE IF EXISTS `student_exam_registered`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_exam_registered` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registration_number` varchar(100) NOT NULL,
  `coursecode` varchar(50) NOT NULL,
  `course_id` int(11) NOT NULL,
  `exam_year` year(4) NOT NULL,
  `exam_semester` varchar(50) NOT NULL,
  `center_id` int(11) NOT NULL,
  PRIMARY KEY (`registration_number`,`coursecode`,`exam_year`,`exam_semester`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=103091 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `student_exist`
--

DROP TABLE IF EXISTS `student_exist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_exist` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `student_not_exist`
--

DROP TABLE IF EXISTS `student_not_exist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_not_exist` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account_check` int(11) NOT NULL,
  `registration_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admission_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entry_year` int(11) DEFAULT NULL,
  `graduation_year` int(11) DEFAULT NULL,
  `year_of_study` int(11) DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `programme_id` int(10) unsigned NOT NULL,
  `level` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `centre_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `member_number` int(11) DEFAULT NULL,
  `member_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cooperate` int(11) NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `other_names` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dob` date DEFAULT NULL,
  `marital_status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nationality` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `application_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `profile_avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `academics_information` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contacts_information` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employment_information` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `institution_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `updated_at` (`updated_at`),
  KEY `entry_year` (`entry_year`),
  KEY `cooperate` (`cooperate`),
  KEY `first_name` (`first_name`),
  KEY `created_at` (`created_at`),
  KEY `updated_at_2` (`updated_at`)
) ENGINE=InnoDB AUTO_INCREMENT=9179 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `studylevel`
--

DROP TABLE IF EXISTS `studylevel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `studylevel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `technician_type`
--

DROP TABLE IF EXISTS `technician_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technician_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `temp_annual_fees`
--

DROP TABLE IF EXISTS `temp_annual_fees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `temp_annual_fees` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `msisdn` varchar(100) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `applicant_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `timestamp` varchar(100) NOT NULL,
  `receipt` varchar(100) NOT NULL,
  `pay_method` varchar(255) DEFAULT NULL,
  `amount` double(100,2) NOT NULL,
  `charges` int(11) NOT NULL,
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `academic_year` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `receipt` (`receipt`)
) ENGINE=InnoDB AUTO_INCREMENT=545 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `temp_exam_registered`
--

DROP TABLE IF EXISTS `temp_exam_registered`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `temp_exam_registered` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registration_number` varchar(100) NOT NULL,
  `coursecode` varchar(50) NOT NULL,
  `course_id` int(11) NOT NULL,
  `exam_year` year(4) NOT NULL,
  `exam_semester` varchar(50) NOT NULL,
  `center_id` int(11) NOT NULL,
  PRIMARY KEY (`registration_number`,`coursecode`,`exam_year`,`exam_semester`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5151 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_login_history`
--

DROP TABLE IF EXISTS `user_login_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_login_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(50) NOT NULL,
  `browser` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37762 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_title`
--

DROP TABLE IF EXISTS `user_title`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_title` (
  `id` int(11) NOT NULL,
  `title` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `profile` varchar(255) DEFAULT 'default.jpg',
  `title` varchar(50) DEFAULT NULL,
  `campus_id` int(11) DEFAULT '1',
  `forgotten_password_time` int(11) DEFAULT NULL,
  `access_area` int(11) DEFAULT NULL COMMENT '1=Schools,2=Department',
  `access_area_id` int(11) DEFAULT NULL,
  `sims_map` bigint(20) DEFAULT '0',
  `applicant_id` bigint(20) DEFAULT '0',
  `user_status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9668 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_groups`
--

DROP TABLE IF EXISTS `users_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_groups` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6245 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_login_attempts`
--

DROP TABLE IF EXISTS `users_login_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_login_attempts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(50) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11945 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `venue`
--

DROP TABLE IF EXISTS `venue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `venue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `centre_id` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
-- Dump completed on 2020-01-20 13:26:10
INSERT INTO `users` VALUES (1,'41.59.72.98','administrator1','$2y$08$B2K20vLjBJQrUR821Su1l.zalK6KfvvwvYWQgHJDZ.NmbVVQ2L/aK','','flowinfestus@gmail.com','',NULL,NULL,1498301400,1576579286,1,'Juma','Lungo','255742523460','default.jpg','Mr',1,NULL,0,0,0,0,1),(2,'127.0.0.1','administrator','$2y$08$XclRp7JA3dRhPmmKiQk8xu.hnVfpPdNSPxZ9KkzAVKl3sbm51BMSq','','admin@gmail.com','','',NULL,1498301400,1563172749,1,'Juma','Lungo','255742523460','default.jpg','Mr',1,0,0,0,0,0,1);

UNLOCK TABLES;

LOCK TABLES `user_title` WRITE;
/*!40000 ALTER TABLE `user_title` DISABLE KEYS */;
INSERT INTO `user_title` VALUES (1,'Mr'),(2,'Mrs'),(3,'Dr'),(4,'Prof'),(5,'Ms'),(6,'Rev'),(7,'Eng');
/*!40000 ALTER TABLE `user_title` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `college` WRITE;
/*!40000 ALTER TABLE `college` DISABLE KEYS */;
INSERT INTO `college` VALUES (1,'THE TANZANIA INSTITUTE OF BANKERS','P.O.Box  8182','info@tiob.or.tz','+255 22 2133350','+255 22 2112604','Dar es Salaam','TANZANIA','http://www.tiob.or.tz','+255-222-443149');
/*!40000 ALTER TABLE `college` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `campus` WRITE;
/*!40000 ALTER TABLE `campus` DISABLE KEYS */;
INSERT INTO `campus` VALUES (1,'Main Campus','Dar-es-Salaam');
/*!40000 ALTER TABLE `campus` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `ayear` WRITE;
/*!40000 ALTER TABLE `ayear` DISABLE KEYS */;
INSERT INTO `ayear` VALUES (7,'2017','0','November Session',1,NULL),(8,'2018','0','May Session',1,NULL),(9,'2016','0','May Session',1,NULL),(10,'2016','0','November Session',1,NULL),(11,'2017','0','May Session',1,NULL),(12,'2018','0','November Session',1,NULL),(13,'2019','0','May Session',1,NULL),(14,'2019','1','November Session',1,NULL);
/*!40000 ALTER TABLE `ayear` ENABLE KEYS */;
UNLOCK TABLES;
