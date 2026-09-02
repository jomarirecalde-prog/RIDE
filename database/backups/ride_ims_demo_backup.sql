-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: ride_ims
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `ride_ims`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `ride_ims` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `ride_ims`;

--
-- Table structure for table `admin_messages`
--

DROP TABLE IF EXISTS `admin_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `admin_messages_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_messages`
--

LOCK TABLES `admin_messages` WRITE;
/*!40000 ALTER TABLE `admin_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `api_tokens`
--

DROP TABLE IF EXISTS `api_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `api_tokens` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `token_hash` varchar(64) NOT NULL,
  `label` varchar(80) NOT NULL DEFAULT 'default',
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `token_hash` (`token_hash`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `api_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `api_tokens`
--

LOCK TABLES `api_tokens` WRITE;
/*!40000 ALTER TABLE `api_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `api_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_settings`
--

DROP TABLE IF EXISTS `app_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_settings` (
  `setting_key` varchar(120) NOT NULL,
  `setting_value` text NOT NULL,
  `updated_by` int(10) unsigned DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`setting_key`),
  KEY `updated_by` (`updated_by`),
  CONSTRAINT `app_settings_ibfk_1` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_settings`
--

LOCK TABLES `app_settings` WRITE;
/*!40000 ALTER TABLE `app_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `app_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `approval_actions`
--

DROP TABLE IF EXISTS `approval_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `approval_actions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `step` varchar(80) NOT NULL,
  `action` enum('approve','return','reject') NOT NULL,
  `signature_hash` varchar(64) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `proposal_id` (`proposal_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `approval_actions_ibfk_1` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `approval_actions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `approval_actions`
--

LOCK TABLES `approval_actions` WRITE;
/*!40000 ALTER TABLE `approval_actions` DISABLE KEYS */;
/*!40000 ALTER TABLE `approval_actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `entity_type` varchar(60) NOT NULL,
  `entity_id` int(10) unsigned DEFAULT NULL,
  `action` varchar(60) NOT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,12,'user',12,'login','[]','::1','2026-08-08 10:07:02'),(2,12,'user',12,'logout','[]','::1','2026-08-08 10:09:43'),(3,10,'user',10,'login','[]','::1','2026-08-08 10:09:59');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campuses`
--

DROP TABLE IF EXISTS `campuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campuses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `college_id` int(10) unsigned NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_campus` (`college_id`,`code`),
  CONSTRAINT `campuses_ibfk_1` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campuses`
--

LOCK TABLES `campuses` WRITE;
/*!40000 ALTER TABLE `campuses` DISABLE KEYS */;
INSERT INTO `campuses` VALUES (1,1,'MAIN','Main Campus','2026-08-08 09:54:11'),(2,1,'NORTH','North Satellite Campus','2026-08-08 09:54:11'),(3,2,'MAIN','Main Campus','2026-08-08 09:54:11'),(4,3,'MAIN','Main Campus','2026-08-08 09:54:11');
/*!40000 ALTER TABLE `campuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `colleges`
--

DROP TABLE IF EXISTS `colleges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `colleges` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `name` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colleges`
--

LOCK TABLES `colleges` WRITE;
/*!40000 ALTER TABLE `colleges` DISABLE KEYS */;
INSERT INTO `colleges` VALUES (1,'CET','College of Engineering and Technology','2026-08-08 09:54:11'),(2,'CAS','College of Arts and Sciences','2026-08-08 09:54:11'),(3,'CBM','College of Business and Management','2026-08-08 09:54:11');
/*!40000 ALTER TABLE `colleges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `community_beneficiaries`
--

DROP TABLE IF EXISTS `community_beneficiaries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `community_beneficiaries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_id` int(10) unsigned NOT NULL,
  `group_name` varchar(200) NOT NULL,
  `beneficiary_count` int(10) unsigned NOT NULL DEFAULT 0,
  `location` varchar(200) DEFAULT NULL,
  `period_year` year(4) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `proposal_id` (`proposal_id`),
  CONSTRAINT `community_beneficiaries_ibfk_1` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `community_beneficiaries`
--

LOCK TABLES `community_beneficiaries` WRITE;
/*!40000 ALTER TABLE `community_beneficiaries` DISABLE KEYS */;
/*!40000 ALTER TABLE `community_beneficiaries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `direct_messages`
--

DROP TABLE IF EXISTS `direct_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `direct_messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` int(10) unsigned NOT NULL,
  `recipient_id` int(10) unsigned NOT NULL,
  `body` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_dm_recipient_unread` (`recipient_id`,`read_at`),
  KEY `idx_dm_participants` (`sender_id`,`recipient_id`,`created_at`),
  CONSTRAINT `direct_messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `direct_messages_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `direct_messages`
--

LOCK TABLES `direct_messages` WRITE;
/*!40000 ALTER TABLE `direct_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `direct_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `category` enum('proposal','report','publication','patent','extension_media','other','completed_researches','ongoing_researches','research_output_published','research_output_presented','commercialized','resulted_in_extension','journal_citation','book_citation','inventions_um_copyrights','linkages','consolidated_completed_researches','consolidated_research_output_published','progress_report','terminal_report','terminal_report_assessment_form','obr_matrix') NOT NULL DEFAULT 'other',
  `original_name` varchar(255) NOT NULL,
  `stored_name` varchar(255) NOT NULL,
  `mime_type` varchar(120) DEFAULT NULL,
  `file_size` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `proposal_id` (`proposal_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `documents_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `global_message_reads`
--

DROP TABLE IF EXISTS `global_message_reads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_message_reads` (
  `user_id` int(10) unsigned NOT NULL,
  `last_read_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`),
  CONSTRAINT `global_message_reads_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_message_reads`
--

LOCK TABLES `global_message_reads` WRITE;
/*!40000 ALTER TABLE `global_message_reads` DISABLE KEYS */;
/*!40000 ALTER TABLE `global_message_reads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `highlight_slides`
--

DROP TABLE IF EXISTS `highlight_slides`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `highlight_slides` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `caption` text DEFAULT NULL,
  `image_path` varchar(500) NOT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_highlight_order` (`sort_order`,`is_active`),
  KEY `fk_highlight_slides_created_by` (`created_by`),
  CONSTRAINT `fk_highlight_slides_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `highlight_slides`
--

LOCK TABLES `highlight_slides` WRITE;
/*!40000 ALTER TABLE `highlight_slides` DISABLE KEYS */;
INSERT INTO `highlight_slides` VALUES (1,NULL,NULL,'assets/uploads/highlights/slide_20260723_140037_03dbb037.jpg',1,1,1,'2026-08-08 09:58:04','2026-08-08 09:58:04'),(2,NULL,NULL,'assets/uploads/highlights/slide_20260723_140037_1f4f4bc2.jpg',2,1,1,'2026-08-08 09:58:04','2026-08-08 09:58:04'),(3,NULL,NULL,'assets/uploads/highlights/slide_20260723_140037_25e2ab6e.jpg',3,1,1,'2026-08-08 09:58:04','2026-08-08 09:58:04'),(4,NULL,NULL,'assets/uploads/highlights/slide_20260723_140037_38078427.jpg',4,1,1,'2026-08-08 09:58:04','2026-08-08 09:58:04'),(5,NULL,NULL,'assets/uploads/highlights/slide_20260723_140037_48a341f3.jpg',5,1,1,'2026-08-08 09:58:04','2026-08-08 09:58:04'),(6,NULL,NULL,'assets/uploads/highlights/slide_20260723_140037_4a5ef0a2.jpg',6,1,1,'2026-08-08 09:58:04','2026-08-08 09:58:04'),(7,NULL,NULL,'assets/uploads/highlights/slide_20260723_140037_4c2cc32e.jpg',7,1,1,'2026-08-08 09:58:04','2026-08-08 09:58:04'),(8,NULL,NULL,'assets/uploads/highlights/slide_20260723_140037_534e8ee1.jpg',8,1,1,'2026-08-08 09:58:04','2026-08-08 09:58:04'),(9,NULL,NULL,'assets/uploads/highlights/slide_20260723_140037_9e1e70a5.jpg',9,1,1,'2026-08-08 09:58:04','2026-08-08 09:58:04'),(10,NULL,NULL,'assets/uploads/highlights/slide_20260723_141156_23373024.jpg',10,1,1,'2026-08-08 09:58:04','2026-08-08 09:58:04'),(11,NULL,NULL,'assets/uploads/highlights/slide_20260723_141156_7060ccd3.jpg',11,1,1,'2026-08-08 09:58:04','2026-08-08 09:58:04'),(12,NULL,NULL,'assets/uploads/highlights/slide_20260723_141156_79c10e0d.jpg',12,1,1,'2026-08-08 09:58:04','2026-08-08 09:58:04'),(13,NULL,NULL,'assets/uploads/highlights/slide_20260723_141156_83670970.jpg',13,1,1,'2026-08-08 09:58:04','2026-08-08 09:58:04'),(14,NULL,NULL,'assets/uploads/highlights/slide_20260723_141156_8d557874.jpg',14,1,1,'2026-08-08 09:58:04','2026-08-08 09:58:04'),(15,NULL,NULL,'assets/uploads/highlights/slide_20260723_141156_f3214574.jpg',15,1,1,'2026-08-08 09:58:04','2026-08-08 09:58:04');
/*!40000 ALTER TABLE `highlight_slides` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `impact_metrics`
--

DROP TABLE IF EXISTS `impact_metrics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `impact_metrics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_id` int(10) unsigned NOT NULL,
  `period_year` year(4) NOT NULL,
  `people_trained` int(10) unsigned NOT NULL DEFAULT 0,
  `income_generated` decimal(14,2) NOT NULL DEFAULT 0.00,
  `households_served` int(10) unsigned NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `proposal_id` (`proposal_id`),
  CONSTRAINT `impact_metrics_ibfk_1` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `impact_metrics`
--

LOCK TABLES `impact_metrics` WRITE;
/*!40000 ALTER TABLE `impact_metrics` DISABLE KEYS */;
/*!40000 ALTER TABLE `impact_metrics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ip_disclosures`
--

DROP TABLE IF EXISTS `ip_disclosures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ip_disclosures` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_id` int(10) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `disclosure_date` date DEFAULT NULL,
  `status` enum('draft','filed','approved') NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `proposal_id` (`proposal_id`),
  CONSTRAINT `ip_disclosures_ibfk_1` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ip_disclosures`
--

LOCK TABLES `ip_disclosures` WRITE;
/*!40000 ALTER TABLE `ip_disclosures` DISABLE KEYS */;
/*!40000 ALTER TABLE `ip_disclosures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `milestones`
--

DROP TABLE IF EXISTS `milestones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `milestones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_id` int(10) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `due_date` date NOT NULL,
  `completed_at` date DEFAULT NULL,
  `status` enum('pending','completed','overdue') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `proposal_id` (`proposal_id`),
  CONSTRAINT `milestones_ibfk_1` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `milestones`
--

LOCK TABLES `milestones` WRITE;
/*!40000 ALTER TABLE `milestones` DISABLE KEYS */;
/*!40000 ALTER TABLE `milestones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paper_presentations`
--

DROP TABLE IF EXISTS `paper_presentations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paper_presentations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(500) NOT NULL,
  `conference_name` varchar(255) NOT NULL,
  `presentation_type` enum('oral','poster','virtual','other') NOT NULL DEFAULT 'oral',
  `presentation_date` date DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `is_international` tinyint(1) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_paper_presentations_user` (`user_id`),
  KEY `idx_paper_presentations_date` (`presentation_date`),
  CONSTRAINT `paper_presentations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paper_presentations`
--

LOCK TABLES `paper_presentations` WRITE;
/*!40000 ALTER TABLE `paper_presentations` DISABLE KEYS */;
/*!40000 ALTER TABLE `paper_presentations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partner_mous`
--

DROP TABLE IF EXISTS `partner_mous`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partner_mous` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_id` int(10) unsigned NOT NULL,
  `partner_name` varchar(200) NOT NULL,
  `valid_from` date DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `document_id` int(10) unsigned DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `proposal_id` (`proposal_id`),
  KEY `document_id` (`document_id`),
  CONSTRAINT `partner_mous_ibfk_1` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `partner_mous_ibfk_2` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partner_mous`
--

LOCK TABLES `partner_mous` WRITE;
/*!40000 ALTER TABLE `partner_mous` DISABLE KEYS */;
/*!40000 ALTER TABLE `partner_mous` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patents`
--

DROP TABLE IF EXISTS `patents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_id` int(10) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `application_no` varchar(80) DEFAULT NULL,
  `status` enum('filed','pending','granted') NOT NULL DEFAULT 'filed',
  `filed_date` date DEFAULT NULL,
  `granted_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `proposal_id` (`proposal_id`),
  CONSTRAINT `patents_ibfk_1` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patents`
--

LOCK TABLES `patents` WRITE;
/*!40000 ALTER TABLE `patents` DISABLE KEYS */;
/*!40000 ALTER TABLE `patents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(80) NOT NULL,
  `name` varchar(120) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'proposal.create','Create proposals'),(2,'proposal.submit','Submit proposals'),(3,'proposal.review.college','Review at college level'),(4,'proposal.approve.dean','Dean approval'),(5,'proposal.approve.ethics','Ethics approval'),(6,'proposal.approve.ride','RIDE final approval'),(7,'proposal.view.college','View college proposals'),(8,'proposal.view.all','View all proposals'),(9,'admin.manage','Administration'),(10,'report.export','Export reports');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `progress_reports`
--

DROP TABLE IF EXISTS `progress_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `progress_reports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `period_label` varchar(80) NOT NULL,
  `report_type` enum('quarterly','annual','final') NOT NULL DEFAULT 'quarterly',
  `narrative` text DEFAULT NULL,
  `financial_summary` text DEFAULT NULL,
  `outputs` text DEFAULT NULL,
  `status` enum('draft','submitted','reviewed') NOT NULL DEFAULT 'draft',
  `due_date` date DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `proposal_id` (`proposal_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `progress_reports_ibfk_1` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `progress_reports_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `progress_reports`
--

LOCK TABLES `progress_reports` WRITE;
/*!40000 ALTER TABLE `progress_reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `progress_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposal_coauthor_invitations`
--

DROP TABLE IF EXISTS `proposal_coauthor_invitations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposal_coauthor_invitations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_id` int(10) unsigned NOT NULL,
  `invitee_user_id` int(10) unsigned NOT NULL,
  `invited_by_user_id` int(10) unsigned NOT NULL,
  `status` enum('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `responded_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_proposal_invitee` (`proposal_id`,`invitee_user_id`),
  KEY `idx_invitee_status` (`invitee_user_id`,`status`),
  KEY `invited_by_user_id` (`invited_by_user_id`),
  CONSTRAINT `proposal_coauthor_invitations_ibfk_1` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `proposal_coauthor_invitations_ibfk_2` FOREIGN KEY (`invitee_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `proposal_coauthor_invitations_ibfk_3` FOREIGN KEY (`invited_by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposal_coauthor_invitations`
--

LOCK TABLES `proposal_coauthor_invitations` WRITE;
/*!40000 ALTER TABLE `proposal_coauthor_invitations` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposal_coauthor_invitations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposal_comments`
--

DROP TABLE IF EXISTS `proposal_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposal_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `step` varchar(80) DEFAULT NULL,
  `comment` text NOT NULL,
  `action` enum('comment','return','approve','reject') NOT NULL DEFAULT 'comment',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `proposal_id` (`proposal_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `proposal_comments_ibfk_1` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `proposal_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposal_comments`
--

LOCK TABLES `proposal_comments` WRITE;
/*!40000 ALTER TABLE `proposal_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposal_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposals`
--

DROP TABLE IF EXISTS `proposals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `college_id` int(10) unsigned NOT NULL,
  `campus_id` int(10) unsigned DEFAULT NULL,
  `project_type` enum('research','innovation','development','extension') NOT NULL,
  `title` varchar(255) NOT NULL,
  `summary` text DEFAULT NULL,
  `funding_source` varchar(120) DEFAULT NULL,
  `risk_level` enum('low','medium','high') NOT NULL DEFAULT 'low',
  `ethics_required` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('draft','submitted','under_review','returned','approved','ongoing','completed','suspended') NOT NULL DEFAULT 'draft',
  `current_step` varchar(80) DEFAULT NULL,
  `project_code` varchar(40) DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_code` (`project_code`),
  KEY `user_id` (`user_id`),
  KEY `college_id` (`college_id`),
  KEY `campus_id` (`campus_id`),
  CONSTRAINT `proposals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `proposals_ibfk_2` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`),
  CONSTRAINT `proposals_ibfk_3` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposals`
--

LOCK TABLES `proposals` WRITE;
/*!40000 ALTER TABLE `proposals` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prototypes`
--

DROP TABLE IF EXISTS `prototypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prototypes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_id` int(10) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `stage` enum('concept','alpha','beta','production') NOT NULL DEFAULT 'concept',
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `proposal_id` (`proposal_id`),
  CONSTRAINT `prototypes_ibfk_1` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prototypes`
--

LOCK TABLES `prototypes` WRITE;
/*!40000 ALTER TABLE `prototypes` DISABLE KEYS */;
/*!40000 ALTER TABLE `prototypes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `published_papers`
--

DROP TABLE IF EXISTS `published_papers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `published_papers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(500) NOT NULL,
  `authors` text DEFAULT NULL,
  `journal_name` varchar(255) NOT NULL,
  `publication_date` date DEFAULT NULL,
  `publication_year` smallint(5) unsigned DEFAULT NULL,
  `doi` varchar(120) DEFAULT NULL,
  `indexing` varchar(120) DEFAULT NULL,
  `status` enum('published','accepted','in_press') NOT NULL DEFAULT 'published',
  `link` varchar(500) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_published_papers_user` (`user_id`),
  KEY `idx_published_papers_year` (`publication_year`),
  CONSTRAINT `published_papers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `published_papers`
--

LOCK TABLES `published_papers` WRITE;
/*!40000 ALTER TABLE `published_papers` DISABLE KEYS */;
/*!40000 ALTER TABLE `published_papers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report_financial_lines`
--

DROP TABLE IF EXISTS `report_financial_lines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `report_financial_lines` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `report_id` int(10) unsigned NOT NULL,
  `description` varchar(200) NOT NULL,
  `budgeted` decimal(14,2) NOT NULL DEFAULT 0.00,
  `spent` decimal(14,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `report_id` (`report_id`),
  CONSTRAINT `report_financial_lines_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `progress_reports` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_financial_lines`
--

LOCK TABLES `report_financial_lines` WRITE;
/*!40000 ALTER TABLE `report_financial_lines` DISABLE KEYS */;
/*!40000 ALTER TABLE `report_financial_lines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_permissions` (
  `role_id` int(10) unsigned NOT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `permission_id` (`permission_id`),
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permissions`
--

LOCK TABLES `role_permissions` WRITE;
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
INSERT INTO `role_permissions` VALUES (1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),(2,3),(2,6),(2,7),(2,8),(2,10),(3,8),(3,10),(4,3),(4,7),(5,4),(5,7),(6,1),(6,2),(7,5),(8,7),(9,3),(9,6),(9,7),(9,8),(9,10),(10,3),(10,6),(10,7),(10,8),(10,10),(12,3),(12,7);
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'ride_admin','RIDE Admin','System administrator'),(2,'vpride','Admin / VPRIDE','Grants final approval after Director of Research or Director of Extension approval'),(3,'ride_reporter','RIDE Report Generator','Analytics and exports'),(4,'coordinator_research','Coordinator of Research','Endorses research submissions from their college and forwards to the College Dean'),(5,'dean','College Dean','Approves endorsed submissions and forwards to the Director of Research or Director of Extension'),(6,'faculty','Faculty','Faculty researcher and proposal submitter'),(7,'ethics_reviewer','Ethics Review Committee','Ethics review step'),(8,'external_partner','External Partner','View-only collaborator'),(9,'director_research','Director of Research','Approves research submissions after College Dean and forwards to VPRIDE'),(10,'director_extension','Director of Extension','Approves extension submissions after College Dean and forwards to VPRIDE'),(12,'coordinator_extension','Coordinator of Extension','Endorses extension submissions from their college and forwards to the College Dean');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schema_migrations`
--

DROP TABLE IF EXISTS `schema_migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schema_migrations` (
  `version` varchar(40) NOT NULL,
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schema_migrations`
--

LOCK TABLES `schema_migrations` WRITE;
/*!40000 ALTER TABLE `schema_migrations` DISABLE KEYS */;
INSERT INTO `schema_migrations` VALUES ('phase10','2026-08-08 09:54:37'),('phase11','2026-08-08 09:54:37'),('phase12','2026-08-08 09:54:37'),('phase13','2026-08-08 09:54:37'),('phase14','2026-08-08 09:54:37'),('phase15','2026-08-08 09:54:37'),('phase16','2026-08-08 09:54:37'),('phase2','2026-08-08 09:54:37'),('phase21','2026-08-08 09:54:37'),('phase22','2026-08-08 09:54:37'),('phase23','2026-08-08 09:54:37'),('phase3','2026-08-08 09:54:37'),('phase4','2026-08-08 09:54:37'),('phase6','2026-08-08 09:54:37'),('phase7','2026-08-08 09:54:37'),('phase8','2026-08-08 09:54:37'),('phase9','2026-08-08 09:54:37');
/*!40000 ALTER TABLE `schema_migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scholarly_attachments`
--

DROP TABLE IF EXISTS `scholarly_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scholarly_attachments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `record_type` enum('published_paper','paper_presentation') NOT NULL,
  `record_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `stored_name` varchar(255) NOT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `file_size` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `idx_scholarly_attachments_record` (`record_type`,`record_id`),
  CONSTRAINT `scholarly_attachments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scholarly_attachments`
--

LOCK TABLES `scholarly_attachments` WRITE;
/*!40000 ALTER TABLE `scholarly_attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `scholarly_attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `technology_transfers`
--

DROP TABLE IF EXISTS `technology_transfers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technology_transfers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_id` int(10) unsigned NOT NULL,
  `partner_name` varchar(200) NOT NULL,
  `transfer_date` date DEFAULT NULL,
  `status` enum('negotiating','completed') NOT NULL DEFAULT 'negotiating',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `proposal_id` (`proposal_id`),
  CONSTRAINT `technology_transfers_ibfk_1` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technology_transfers`
--

LOCK TABLES `technology_transfers` WRITE;
/*!40000 ALTER TABLE `technology_transfers` DISABLE KEYS */;
/*!40000 ALTER TABLE `technology_transfers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_roles` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `college_id` int(10) unsigned DEFAULT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_id` (`role_id`),
  KEY `college_id` (`college_id`),
  CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_roles_ibfk_3` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_roles`
--

LOCK TABLES `user_roles` WRITE;
/*!40000 ALTER TABLE `user_roles` DISABLE KEYS */;
INSERT INTO `user_roles` VALUES (1,1,NULL,'2026-08-08 10:08:55'),(3,2,NULL,'2026-08-08 10:08:55'),(4,9,NULL,'2026-08-08 10:08:55'),(5,10,NULL,'2026-08-08 10:08:55'),(6,9,NULL,'2026-08-08 10:08:55'),(7,4,1,'2026-08-08 10:08:55'),(8,12,1,'2026-08-08 10:08:55'),(9,5,1,'2026-08-08 10:08:55'),(10,6,1,'2026-08-08 10:08:55'),(11,6,1,'2026-08-08 10:08:55'),(12,4,2,'2026-08-08 10:08:55'),(13,12,2,'2026-08-08 10:08:55'),(14,5,2,'2026-08-08 10:08:55'),(15,6,2,'2026-08-08 10:08:55'),(16,6,2,'2026-08-08 10:08:55'),(17,4,3,'2026-08-08 10:08:55'),(18,12,3,'2026-08-08 10:08:55'),(19,5,3,'2026-08-08 10:08:55'),(20,6,3,'2026-08-08 10:08:55'),(21,6,3,'2026-08-08 10:08:55');
/*!40000 ALTER TABLE `user_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `first_name` varchar(80) NOT NULL,
  `last_name` varchar(80) NOT NULL,
  `college_id` int(10) unsigned DEFAULT NULL,
  `program` varchar(150) DEFAULT NULL,
  `campus_id` int(10) unsigned DEFAULT NULL,
  `signature_path` varchar(255) DEFAULT NULL,
  `avatar_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `college_id` (`college_id`),
  KEY `campus_id` (`campus_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_ibfk_2` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin@ride.local','$2y$10$1UFSLUmOVn4oH5uXD3thxOOGUJ68FZadbLaN18VrdfvrR1u1.RjNi','Ana','Reyes',NULL,NULL,NULL,NULL,NULL,1,'2026-08-08 09:54:11','2026-08-08 10:08:55'),(3,'vpride@ride.local','$2y$10$1UFSLUmOVn4oH5uXD3thxOOGUJ68FZadbLaN18VrdfvrR1u1.RjNi','Ramon','Villanueva',NULL,NULL,NULL,NULL,NULL,1,'2026-08-08 10:06:02','2026-08-08 10:08:55'),(4,'director.research@ride.local','$2y$10$1UFSLUmOVn4oH5uXD3thxOOGUJ68FZadbLaN18VrdfvrR1u1.RjNi','Liza','Mendoza',NULL,NULL,NULL,NULL,NULL,1,'2026-08-08 10:06:02','2026-08-08 10:08:55'),(5,'director.extension@ride.local','$2y$10$1UFSLUmOVn4oH5uXD3thxOOGUJ68FZadbLaN18VrdfvrR1u1.RjNi','Carlos','Javier',NULL,NULL,NULL,NULL,NULL,1,'2026-08-08 10:06:02','2026-08-08 10:08:55'),(6,'director@ride.local','$2y$10$1UFSLUmOVn4oH5uXD3thxOOGUJ68FZadbLaN18VrdfvrR1u1.RjNi','Liza','Mendoza',NULL,NULL,NULL,NULL,NULL,1,'2026-08-08 10:06:02','2026-08-08 10:08:55'),(7,'coord.research.cet@ride.local','$2y$10$1UFSLUmOVn4oH5uXD3thxOOGUJ68FZadbLaN18VrdfvrR1u1.RjNi','Mark','Santos',1,NULL,1,NULL,NULL,1,'2026-08-08 10:06:02','2026-08-08 10:08:55'),(8,'coord.extension.cet@ride.local','$2y$10$1UFSLUmOVn4oH5uXD3thxOOGUJ68FZadbLaN18VrdfvrR1u1.RjNi','Grace','Lim',1,NULL,1,NULL,NULL,1,'2026-08-08 10:06:02','2026-08-08 10:08:55'),(9,'dean.cet@ride.local','$2y$10$1UFSLUmOVn4oH5uXD3thxOOGUJ68FZadbLaN18VrdfvrR1u1.RjNi','Patricia','Ong',1,NULL,1,NULL,NULL,1,'2026-08-08 10:06:02','2026-08-08 10:08:55'),(10,'faculty.research.cet@ride.local','$2y$10$1UFSLUmOVn4oH5uXD3thxOOGUJ68FZadbLaN18VrdfvrR1u1.RjNi','John','Cruz',1,NULL,1,NULL,NULL,1,'2026-08-08 10:06:02','2026-08-08 10:08:55'),(11,'faculty.extension.cet@ride.local','$2y$10$1UFSLUmOVn4oH5uXD3thxOOGUJ68FZadbLaN18VrdfvrR1u1.RjNi','Nina','Bautista',1,NULL,1,NULL,NULL,1,'2026-08-08 10:06:02','2026-08-08 10:08:55'),(12,'coord.research.cas@ride.local','$2y$10$1UFSLUmOVn4oH5uXD3thxOOGUJ68FZadbLaN18VrdfvrR1u1.RjNi','Elena','Ramos',2,NULL,3,NULL,NULL,1,'2026-08-08 10:06:02','2026-08-08 10:08:55'),(13,'coord.extension.cas@ride.local','$2y$10$1UFSLUmOVn4oH5uXD3thxOOGUJ68FZadbLaN18VrdfvrR1u1.RjNi','Paolo','Garcia',2,NULL,3,NULL,NULL,1,'2026-08-08 10:06:02','2026-08-08 10:08:55'),(14,'dean.cas@ride.local','$2y$10$1UFSLUmOVn4oH5uXD3thxOOGUJ68FZadbLaN18VrdfvrR1u1.RjNi','Isabel','Torres',2,NULL,3,NULL,NULL,1,'2026-08-08 10:06:02','2026-08-08 10:08:55'),(15,'faculty.research.cas@ride.local','$2y$10$1UFSLUmOVn4oH5uXD3thxOOGUJ68FZadbLaN18VrdfvrR1u1.RjNi','Miguel','Lopez',2,NULL,3,NULL,NULL,1,'2026-08-08 10:06:02','2026-08-08 10:08:55'),(16,'faculty.extension.cas@ride.local','$2y$10$1UFSLUmOVn4oH5uXD3thxOOGUJ68FZadbLaN18VrdfvrR1u1.RjNi','Sara','Dela Cruz',2,NULL,3,NULL,NULL,1,'2026-08-08 10:06:02','2026-08-08 10:08:55'),(17,'coord.research.cbm@ride.local','$2y$10$1UFSLUmOVn4oH5uXD3thxOOGUJ68FZadbLaN18VrdfvrR1u1.RjNi','Daniel','Tan',3,NULL,4,NULL,NULL,1,'2026-08-08 10:06:02','2026-08-08 10:08:55'),(18,'coord.extension.cbm@ride.local','$2y$10$1UFSLUmOVn4oH5uXD3thxOOGUJ68FZadbLaN18VrdfvrR1u1.RjNi','Monica','Reyes',3,NULL,4,NULL,NULL,1,'2026-08-08 10:06:02','2026-08-08 10:08:55'),(19,'dean.cbm@ride.local','$2y$10$1UFSLUmOVn4oH5uXD3thxOOGUJ68FZadbLaN18VrdfvrR1u1.RjNi','Antonio','Flores',3,NULL,4,NULL,NULL,1,'2026-08-08 10:06:02','2026-08-08 10:08:55'),(20,'faculty.research.cbm@ride.local','$2y$10$1UFSLUmOVn4oH5uXD3thxOOGUJ68FZadbLaN18VrdfvrR1u1.RjNi','Rachel','Gomez',3,NULL,4,NULL,NULL,1,'2026-08-08 10:06:02','2026-08-08 10:08:55'),(21,'faculty.extension.cbm@ride.local','$2y$10$1UFSLUmOVn4oH5uXD3thxOOGUJ68FZadbLaN18VrdfvrR1u1.RjNi','Kevin','Navarro',3,NULL,4,NULL,NULL,1,'2026-08-08 10:06:02','2026-08-08 10:08:55');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'ride_ims'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-08 18:13:41
