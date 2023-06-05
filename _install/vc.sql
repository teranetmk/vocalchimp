-- MySQL dump 10.19  Distrib 10.3.29-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: vocalchimp
-- ------------------------------------------------------
-- Server version	10.3.29-MariaDB-0ubuntu0.20.04.1

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
-- Table structure for table `activity`
--

DROP TABLE IF EXISTS `activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_ids` char(50) NOT NULL,
  `level` varchar(11) NOT NULL,
  `event` varchar(50) NOT NULL,
  `detail` text NOT NULL,
  `debug_detail` text NOT NULL,
  `created_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity`
--

LOCK TABLES `activity` WRITE;
/*!40000 ALTER TABLE `activity` DISABLE KEYS */;
INSERT INTO `activity` VALUES (1,'0bWHjn9usb065a85cc223037e3b5dff82c4c08fba2XaMlC3Gk','Information','signin-success','{\"ip\":\"172.68.155.141\",\"is_mobile\":false,\"is_browser\":true,\"browser_name\":\"Chrome\",\"browser_version\":\"90.0.4430.212\",\"platform\":\"Linux\"}','','2021-05-21 17:36:54'),(2,'0bWHjn9usb065a85cc223037e3b5dff82c4c08fba2XaMlC3Gk','Information','update-password','{\"ip\":\"172.68.155.141\",\"is_mobile\":false,\"is_browser\":true,\"browser_name\":\"Chrome\",\"browser_version\":\"90.0.4430.212\",\"platform\":\"Linux\"}','','2021-05-21 17:37:45');
/*!40000 ALTER TABLE `activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `backup_log`
--

DROP TABLE IF EXISTS `backup_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `backup_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` char(50) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `options` varchar(50) NOT NULL,
  `created_method` varchar(8) NOT NULL,
  `created_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `backup_log`
--

LOCK TABLES `backup_log` WRITE;
/*!40000 ALTER TABLE `backup_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `backup_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog`
--

DROP TABLE IF EXISTS `blog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` char(50) NOT NULL,
  `author` varchar(255) NOT NULL,
  `user_ids` char(50) NOT NULL,
  `slug` varchar(512) NOT NULL,
  `cover_photo` varchar(255) NOT NULL,
  `catalog` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `keyword` varchar(1024) NOT NULL,
  `body` text NOT NULL,
  `created_time` timestamp NULL DEFAULT NULL,
  `updated_time` timestamp NULL DEFAULT NULL,
  `read_times` int(11) NOT NULL,
  `comments` text NOT NULL,
  `enabled` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog`
--

LOCK TABLES `blog` WRITE;
/*!40000 ALTER TABLE `blog` DISABLE KEYS */;
/*!40000 ALTER TABLE `blog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog`
--

DROP TABLE IF EXISTS `catalog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` char(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog`
--

LOCK TABLES `catalog` WRITE;
/*!40000 ALTER TABLE `catalog` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_form`
--

DROP TABLE IF EXISTS `contact_form`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_form` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` char(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `catalog` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `created_time` timestamp NULL DEFAULT NULL,
  `read_status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_form`
--

LOCK TABLES `contact_form` WRITE;
/*!40000 ALTER TABLE `contact_form` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_form` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documentation`
--

DROP TABLE IF EXISTS `documentation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documentation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` char(50) NOT NULL,
  `user_ids` char(50) NOT NULL,
  `slug` varchar(512) NOT NULL,
  `catalog` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `keyword` varchar(1024) NOT NULL,
  `body` text NOT NULL,
  `attachment` text NOT NULL,
  `created_time` timestamp NULL DEFAULT NULL,
  `updated_time` timestamp NULL DEFAULT NULL,
  `enabled` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentation`
--

LOCK TABLES `documentation` WRITE;
/*!40000 ALTER TABLE `documentation` DISABLE KEYS */;
/*!40000 ALTER TABLE `documentation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_template`
--

DROP TABLE IF EXISTS `email_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` varchar(50) NOT NULL,
  `purpose` varchar(50) NOT NULL,
  `built_in` tinyint(4) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_template`
--

LOCK TABLES `email_template` WRITE;
/*!40000 ALTER TABLE `email_template` DISABLE KEYS */;
INSERT INTO `email_template` VALUES (1,'68KCh0tH40689a5a3d773c0e66c9ebf17fa1ddfd2M5czj3tVX','signup_activation',1,'Activate your account','<p>Hello {{first_name}}!</p><p>Thank you for joining CyberBukit Membership!</p><p>Please click the following URL to activate your account:<br>{{verification_url}}<br></p><p>If clicking the URL above doesn\'t work, copy and paste the URL into a browser window.</p><p>CyberBukit Membership Support<br>https://membership.demo.cyberbukit.com</p>'),(2,'Sm1M50EX7c92c02f0db2d4d0b57958b7a5897ac4e6Lm2nECNP','reset_password',1,'Password reset','<p>Hello!</p><p>We\'ve generated a URL to reset your password. If you did not request to reset your password or if you\'ve changed your mind, simply ignore this email and nothing will happen.</p><p>You can reset your password by clicking the following URL:<br>{{verification_url}}</p><p>If clicking the URL above does not work, copy and paste the URL into a browser window. The URL will only be valid for a limited time and will expire.</p><p>Thank you,</p><p>CyberBukit Membership Support<br>https://membership.demo.cyberbukit.com</p>'),(3,'y4Y8hpRW1f21144be085fec47b9380fca78dbdb885xvEcLYdP','change_email',1,'Verify your email address','<p>Hello!</p><p>We\'ve generated a URL to change your email address. If you did not request to change your email address or if you\'ve changed your mind, simply ignore this email and nothing will happen.</p><p>You can change your email address by clicking the following URL:<br>{{verification_url}}</p><p>If clicking the URL above does not work, copy and paste the URL into a browser window. The URL will only be valid for a limited time and will expire.</p><p>Thank you,</p><p>CyberBukit Membership Support<br>https://membership.demo.cyberbukit.com</p>'),(4,'jSwbWZdAeef79225efbec3e064b048f20eaa7bb9cwnYX4SWrG','notify_email',1,'Your account has been created','<p>Hello {{first_name}}!</p><p>Welcome to CyberBukit Membership!</p><p>Your CyberBukit Membership account has been created successfully!</p><p>Here is your signin credential:<br>Email Address: {{email_address}}<br>Password: {{password}}<br>Signin URL: {{base_url}}<br>Please sign in and change your password. If you have any questions please feel free to contact us.</p><p>CyberBukit Membership Support<br>https://membership.demo.cyberbukit.com</p>'),(5,'nIQMARFoda2cade675a5876feb1bb77ad4db0086aH6MghaBPL','invite_email',1,'Signup Invitation','<p>Hello!</p><p>The administrator of CyberBukit Membership has sent you this email to invite you to sign up as our member.</p><p>Please click the following URL to activate your account:<br>{{verification_url}}</p><p>If clicking the URL above doesn\'t work, copy and paste the URL into a browser window.</p><p>CyberBukit Membership Support<br>https://membership.demo.cyberbukit.com</p>'),(6,'pW5oGbDQHf7ecf4013f762df420bffe8011b18616mDIwCxvMA','2FA_email',1,'Two Factor Authentication','<p>Your CyberBukit Membership verification code is {{code}}</p>'),(7,'tzqM8x3p7174d8c2d251203aaeeaa85fe4d6ad8338BhC0Oq5W','ticket_notify_agent',1,'A new ticket raised','<p>A new ticket raised or updated. Please sign in and check.</p>'),(8,'8HKUnZ5F24a9ce26849ee64dcba7aaa2187950d40vHQYtzcsK','ticket_notify_user',1,'Your ticket has been replied','<p>Dear customer,</p><p>You ticket has been replied by our agent(s). Please sign in and check.</p><p>CyberBukit Membership Support</p><p>https://membership.demo.cyberbukit.com</p>'),(9,'5DQa8U2Ig7810ecd10382727e3c8f9be866362547OaCI8ki3t','pay_success',1,'We have received your payment','<p>Dear customer,</p><p>We have received your payment for {{purchase_item}}, The amount is {{purchase_price}}.</p><p>Thanks for your payment.</p><p>CyberBukit Membership Support</p><p>https://membership.demo.cyberbukit.com</p>');
/*!40000 ALTER TABLE `email_template` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq`
--

DROP TABLE IF EXISTS `faq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` char(50) NOT NULL,
  `user_ids` char(50) NOT NULL,
  `catalog` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `created_time` timestamp NULL DEFAULT NULL,
  `enabled` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq`
--

LOCK TABLES `faq` WRITE;
/*!40000 ALTER TABLE `faq` DISABLE KEYS */;
/*!40000 ALTER TABLE `faq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `file_manager`
--

DROP TABLE IF EXISTS `file_manager`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `file_manager` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` char(50) NOT NULL,
  `user_ids` char(50) NOT NULL,
  `temporary_ids` varchar(50) NOT NULL,
  `catalog` varchar(50) NOT NULL,
  `original_filename` varchar(255) NOT NULL,
  `file_ext` varchar(10) NOT NULL,
  `description` text NOT NULL,
  `created_time` timestamp NULL DEFAULT NULL,
  `trash` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `file_manager`
--

LOCK TABLES `file_manager` WRITE;
/*!40000 ALTER TABLE `file_manager` DISABLE KEYS */;
/*!40000 ALTER TABLE `file_manager` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notification`
--

DROP TABLE IF EXISTS `notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` varchar(50) NOT NULL,
  `from_user_ids` varchar(32) NOT NULL,
  `to_user_ids` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `is_read` tinyint(4) NOT NULL,
  `send_email` tinyint(4) NOT NULL,
  `created_time` timestamp NULL DEFAULT NULL,
  `read_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notification`
--

LOCK TABLES `notification` WRITE;
/*!40000 ALTER TABLE `notification` DISABLE KEYS */;
/*!40000 ALTER TABLE `notification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_connector`
--

DROP TABLE IF EXISTS `oauth_connector`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_connector` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` char(50) NOT NULL,
  `purpose` char(6) NOT NULL,
  `provider` varchar(50) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `email_address` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `created_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_connector`
--

LOCK TABLES `oauth_connector` WRITE;
/*!40000 ALTER TABLE `oauth_connector` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_connector` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_item`
--

DROP TABLE IF EXISTS `payment_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` char(50) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `type` varchar(50) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_description` text NOT NULL,
  `item_currency` char(3) NOT NULL,
  `item_price` double NOT NULL,
  `recurring_interval` varchar(5) NOT NULL,
  `recurring_interval_count` int(11) NOT NULL,
  `stuff_setting` text NOT NULL,
  `purchase_limit` tinyint(4) NOT NULL,
  `access_condition` text NOT NULL,
  `trash` tinyint(4) NOT NULL,
  `show_order` smallint(6) NOT NULL,
  `auto_renew` tinyint(4) NOT NULL,
  `access_code` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_item`
--

LOCK TABLES `payment_item` WRITE;
/*!40000 ALTER TABLE `payment_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_log`
--

DROP TABLE IF EXISTS `payment_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` char(50) NOT NULL,
  `user_ids` char(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `gateway` varchar(50) NOT NULL,
  `currency` char(3) NOT NULL,
  `price` double NOT NULL,
  `quantity` int(11) NOT NULL,
  `amount` double NOT NULL,
  `gateway_identifier` varchar(255) NOT NULL,
  `gateway_event_id` varchar(255) NOT NULL,
  `item_ids` char(50) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `redirect_status` varchar(50) NOT NULL,
  `callback_status` varchar(50) NOT NULL,
  `created_time` timestamp NULL DEFAULT NULL,
  `callback_time` timestamp NULL DEFAULT NULL,
  `visible_for_user` tinyint(4) NOT NULL,
  `generate_invoice` tinyint(4) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `stuff` text NOT NULL,
  `coupon` varchar(50) NOT NULL,
  `coupon_discount` double NOT NULL,
  `tax` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_log`
--

LOCK TABLES `payment_log` WRITE;
/*!40000 ALTER TABLE `payment_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_purchased`
--

DROP TABLE IF EXISTS `payment_purchased`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_purchased` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` char(50) NOT NULL,
  `user_ids` char(50) NOT NULL,
  `payment_ids` char(50) NOT NULL,
  `item_type` varchar(12) NOT NULL,
  `item_ids` char(50) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `created_time` timestamp NULL DEFAULT NULL,
  `description` varchar(1024) NOT NULL,
  `stuff` text NOT NULL,
  `used_up` tinyint(4) NOT NULL,
  `auto_renew` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_purchased`
--

LOCK TABLES `payment_purchased` WRITE;
/*!40000 ALTER TABLE `payment_purchased` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_purchased` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_subscription`
--

DROP TABLE IF EXISTS `payment_subscription`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_subscription` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` char(50) NOT NULL,
  `item_ids` char(50) NOT NULL,
  `user_ids` char(50) NOT NULL,
  `payment_gateway` varchar(50) NOT NULL,
  `gateway_identifier` varchar(255) NOT NULL,
  `gateway_auth_code` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `created_time` timestamp NULL DEFAULT NULL,
  `updated_time` timestamp NULL DEFAULT NULL,
  `description` varchar(1024) NOT NULL,
  `stuff` text NOT NULL,
  `used_up` tinyint(4) NOT NULL,
  `auto_renew` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_subscription`
--

LOCK TABLES `payment_subscription` WRITE;
/*!40000 ALTER TABLE `payment_subscription` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_subscription` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission`
--

DROP TABLE IF EXISTS `permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` char(50) NOT NULL,
  `built_in` tinyint(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission`
--

LOCK TABLES `permission` WRITE;
/*!40000 ALTER TABLE `permission` DISABLE KEYS */;
INSERT INTO `permission` VALUES (1,'clS0yImZs6d5c5313f55ce8da264739388072f066KxvhYGN6r',1,'User_Management'),(2,'GPb9zsStof247c17218790606d68f5b35d717a11fL9gIMAB3w',1,'Roles_And_Permissions'),(3,'qVkzOmGWvfbffeb1362f77a7e4907c4a406cf7d8fJ6zlgRE4b',1,'Global_Settings'),(4,'PltnM3iOv30c602d36754ab238e35a90d59d77713xcXwaBnhu',1,'Admin_Tools'),(5,'xUghnS8rX9057421bb62cc8e6800b720c0f4042beR9m7zZ3xb',1,'Database_Backup'),(6,'g8aI6bw5V6d930844f19fc137ac17260fe6b65043gQ9HKemkT',1,'Payment_Management'),(7,'VzamDpcvhb706e8635168dd315656a30654e13db9fqcjSnpsm',1,'Support_Management'),(8,'0D5mPEOrQ7aca71700c5c2bc2693adfb719566552iQEatJGXx',1,'TTS_Management');
/*!40000 ALTER TABLE `permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` char(50) NOT NULL,
  `built_in` tinyint(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `permission` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'d7DrO85Nu9a534ea1c14ea96369c921933c347f5dhMZnVfg46',1,'Super_Admin','{\"clS0yImZs6d5c5313f55ce8da264739388072f066KxvhYGN6r\":true,\"GPb9zsStof247c17218790606d68f5b35d717a11fL9gIMAB3w\":true,\"qVkzOmGWvfbffeb1362f77a7e4907c4a406cf7d8fJ6zlgRE4b\":true,\"PltnM3iOv30c602d36754ab238e35a90d59d77713xcXwaBnhu\":true,\"xUghnS8rX9057421bb62cc8e6800b720c0f4042beR9m7zZ3xb\":true,\"g8aI6bw5V6d930844f19fc137ac17260fe6b65043gQ9HKemkT\":true,\"VzamDpcvhb706e8635168dd315656a30654e13db9fqcjSnpsm\":true,\"0D5mPEOrQ7aca71700c5c2bc2693adfb719566552iQEatJGXx\":true}'),(2,'wIHxFXf2od10023bde3961e6fed9c560e13ac75f2sE03pBt7v',1,'Admin','{\"clS0yImZs6d5c5313f55ce8da264739388072f066KxvhYGN6r\":true,\"GPb9zsStof247c17218790606d68f5b35d717a11fL9gIMAB3w\":false,\"qVkzOmGWvfbffeb1362f77a7e4907c4a406cf7d8fJ6zlgRE4b\":false,\"PltnM3iOv30c602d36754ab238e35a90d59d77713xcXwaBnhu\":false,\"xUghnS8rX9057421bb62cc8e6800b720c0f4042beR9m7zZ3xb\":false,\"g8aI6bw5V6d930844f19fc137ac17260fe6b65043gQ9HKemkT\":false,\"VzamDpcvhb706e8635168dd315656a30654e13db9fqcjSnpsm\":false,\"0D5mPEOrQ7aca71700c5c2bc2693adfb719566552iQEatJGXx\":false}'),(3,'S4ZhmaqIO1a311dffa9b3cace791c8993964e5cd95dJi4Nj3F',1,'User','{\"clS0yImZs6d5c5313f55ce8da264739388072f066KxvhYGN6r\":false,\"GPb9zsStof247c17218790606d68f5b35d717a11fL9gIMAB3w\":false,\"qVkzOmGWvfbffeb1362f77a7e4907c4a406cf7d8fJ6zlgRE4b\":false,\"PltnM3iOv30c602d36754ab238e35a90d59d77713xcXwaBnhu\":false,\"xUghnS8rX9057421bb62cc8e6800b720c0f4042beR9m7zZ3xb\":false,\"g8aI6bw5V6d930844f19fc137ac17260fe6b65043gQ9HKemkT\":false,\"VzamDpcvhb706e8635168dd315656a30654e13db9fqcjSnpsm\":false,\"0D5mPEOrQ7aca71700c5c2bc2693adfb719566552iQEatJGXx\":false}');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `script_addons`
--

DROP TABLE IF EXISTS `script_addons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `script_addons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `version` varchar(50) NOT NULL,
  `license_code` varchar(255) NOT NULL,
  `updater_id` int(11) NOT NULL,
  `updater_key` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `script_addons`
--

LOCK TABLES `script_addons` WRITE;
/*!40000 ALTER TABLE `script_addons` DISABLE KEYS */;
/*!40000 ALTER TABLE `script_addons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `script_license`
--

DROP TABLE IF EXISTS `script_license`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `script_license` (
  `SETTING_ID` tinyint(1) NOT NULL AUTO_INCREMENT,
  `ROOT_URL` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CLIENT_EMAIL` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `LICENSE_CODE` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `LCD` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `LRD` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `INSTALLATION_KEY` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `INSTALLATION_HASH` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `LT` tinyint(1) NOT NULL,
  PRIMARY KEY (`SETTING_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `script_license`
--

LOCK TABLES `script_license` WRITE;
/*!40000 ALTER TABLE `script_license` DISABLE KEYS */;
INSERT INTO `script_license` VALUES (1,'https://app.vocalchimp.com','','6b9b2d9c-1a4e-42ee-942a-8e457a60320d','VkpGbTFraUt6NlJPd0xwOWk4Mk8xdz09Ojqdy5k+0FZXV+m2HymL732b','ak5oajcrbWh6K1pDY3paTEVTc29uUT09OjqlRPchlY95QSGsVLYTRx8V','NktNK1c2NGhWNUptcDZXSmFBSjJkdlJXUTJSNUZDcjBCT3k5cjd0NEhIWHI2aXlCOC9Qd1h1aFVoaG94UVhiT21lNnM2eVpQc0ozQ2pHM05zRWNRSWc9PTo6bXq4gP/3R898VHDEUTIWIA==','3ab39b2c9427d12e482c24cacfa8b3e317f44f5d00bacc756589fa2dd5cf9e98',1);
/*!40000 ALTER TABLE `script_license` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` char(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `user_ids` char(50) NOT NULL,
  `created_time` timestamp NULL DEFAULT NULL,
  `expired_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `session`
--

LOCK TABLES `session` WRITE;
/*!40000 ALTER TABLE `session` DISABLE KEYS */;
/*!40000 ALTER TABLE `session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `setting`
--

DROP TABLE IF EXISTS `setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sys_name` varchar(50) NOT NULL,
  `theme` varchar(50) NOT NULL,
  `maintenance_mode` tinyint(4) NOT NULL,
  `maintenance_message` varchar(255) NOT NULL,
  `signup_enabled` tinyint(4) NOT NULL,
  `psr` varchar(6) DEFAULT NULL,
  `tc_show` tinyint(4) NOT NULL,
  `terms_conditions` text NOT NULL,
  `email_verification_required` tinyint(4) NOT NULL,
  `signin_before_verified` tinyint(4) NOT NULL,
  `remember` tinyint(4) NOT NULL,
  `forget_enabled` tinyint(4) NOT NULL,
  `api_enabled` tinyint(4) NOT NULL,
  `html_purify` tinyint(4) NOT NULL,
  `xss_clean` tinyint(4) NOT NULL,
  `throttling_policy` varchar(6) NOT NULL,
  `throttling_unlock_time` tinyint(4) NOT NULL,
  `recaptcha_enabled` tinyint(4) NOT NULL,
  `recaptcha_detail` varchar(255) NOT NULL,
  `google_analytics_id` varchar(50) NOT NULL,
  `oauth_setting` text NOT NULL,
  `two_factor_authentication` varchar(50) NOT NULL,
  `smtp_setting` text NOT NULL,
  `page_size` tinyint(4) NOT NULL,
  `default_role` char(50) NOT NULL,
  `default_package` varchar(50) NOT NULL,
  `debug_level` tinyint(4) NOT NULL,
  `last_backup_time` timestamp NULL DEFAULT NULL,
  `gdpr` text NOT NULL,
  `payment_setting` text NOT NULL,
  `invoice_setting` text NOT NULL,
  `ticket_setting` text NOT NULL,
  `file_setting` text NOT NULL,
  `front_setting` text NOT NULL,
  `enabled_addons` text NOT NULL,
  `affiliate_setting` varchar(1024) NOT NULL,
  `dashboard_custom_css` varchar(255) NOT NULL,
  `dashboard_custom_javascript` varchar(255) NOT NULL,
  `version` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `setting`
--

LOCK TABLES `setting` WRITE;
/*!40000 ALTER TABLE `setting` DISABLE KEYS */;
INSERT INTO `setting` VALUES (1,'CyberBukit TTS','default',0,'Under Maintenance, Please try later.',1,'low',1,'{\"title\":\"T&C Title\",\"body\":\"T&C Body\"}',0,0,1,1,0,0,1,'normal',15,0,'{\"version\":\"v2_1\",\"site_key\":\"\",\"secret_key\":\"\"}','','{\"google\":{\"enabled\":0,\"client_id\":\"\",\"client_secret\":\"\"},\"facebook\":{\"enabled\":0,\"app_id\":\"\",\"app_secret\":\"\"},\"twitter\":{\"enabled\":0,\"consumer_key\":\"\",\"consumer_secret\":\"\"}}','disabled','{\"host\":\"\",\"port\":\"\",\"is_auth\":1,\"username\":\"\",\"password\":\"\",\"crypto\":\"none\",\"from_email\":\"\",\"from_name\":\"\"}',25,'S4ZhmaqIO1a311dffa9b3cace791c8993964e5cd95dJi4Nj3F','0',0,'2000-01-01 00:00:00','{\"enabled\":1,\"allow_remove\":0,\"cookie_message\":\"This website uses cookies to ensure you get the best experience on our website.\",\"cookie_policy_link_text\":\"Learn more\",\"cookie_policy_link\":\"\"}','{\"type\":\"sandbox\",\"feature\":\"both\",\"tax_rate\":0,\"stripe_one_time_enabled\":\"0\",\"stripe_recurring_enabled\":\"0\",\"stripe_publishable_key\":\"\",\"stripe_secret_key\":\"\",\"stripe_signing_secret\":\"\",\"paypal_one_time_enabled\":\"0\",\"paypal_recurring_enabled\":\"0\",\"paypal_client_id\":\"\",\"paypal_secret\":\"\",\"paypal_webhook_id\":\"\"}','{\"enabled\":1,\"invoice_format\":\"pdf\",\"company_name\":\"\",\"company_number\":\"\",\"tax_number\":\"\",\"address_line_1\":\"\",\"address_line_2\":\"\",\"phone\":\"\"}','{\"enabled\":1,\"guest_ticket\":0,\"rating\":1,\"allow_upload\":0,\"notify_agent_list\":\"\",\"notify_user\":0,\"close_rule\":\"3\"}','{\"file_type\":\"jpg|jpeg|png|gif|svg|zip|rar|pdf|mp3|mp4|doc|docx|xls|xlsx\",\"file_size\":\"102400\"}','{\"enabled\":0,\"logo\":\"logo.png\",\"company_name\":\"CyberBukit\",\"email_address\":\"support@cyberbukit.com\",\"html_title\":\"CyberBukit\",\"html_author\":\"CyberBukit\",\"html_description\":\"\",\"html_keyword\":\"\",\"about_us\":\"\",\"pricing_enabled\":1,\"faq_enabled\":1,\"documentation_enabled\":1,\"blog_enabled\":1,\"subscriber_enabled\":1,\"social_facebook\":\"\",\"social_twitter\":\"\",\"social_linkedin\":\"\",\"social_github\":\"\",\"custom_css\":\"\",\"custom_javascript\":\"\"}','{}','{\"enabled\":0,\"commission_policy\":\"A\",\"commission_rate\":0, \"description\":\"\", \"stuff\":\"\"}','','','1.0.7');
/*!40000 ALTER TABLE `setting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `statistics`
--

DROP TABLE IF EXISTS `statistics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statistics`
--

LOCK TABLES `statistics` WRITE;
/*!40000 ALTER TABLE `statistics` DISABLE KEYS */;
INSERT INTO `statistics` VALUES (1,'signup_last_six_days','{\"2020-11-25\":0,\"2020-11-26\":0,\"2020-11-27\":0,\"2020-11-29\":0,\"2020-11-29\":0,\"2020-11-30\":0}');
/*!40000 ALTER TABLE `statistics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscriber`
--

DROP TABLE IF EXISTS `subscriber`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscriber` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` char(50) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `from_ip` varchar(50) NOT NULL,
  `created_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscriber`
--

LOCK TABLES `subscriber` WRITE;
/*!40000 ALTER TABLE `subscriber` DISABLE KEYS */;
/*!40000 ALTER TABLE `subscriber` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `throttling`
--

DROP TABLE IF EXISTS `throttling`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `throttling` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(50) NOT NULL,
  `user_tag` varchar(50) NOT NULL,
  `times` tinyint(4) NOT NULL,
  `time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `throttling`
--

LOCK TABLES `throttling` WRITE;
/*!40000 ALTER TABLE `throttling` DISABLE KEYS */;
/*!40000 ALTER TABLE `throttling` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket`
--

DROP TABLE IF EXISTS `ticket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` char(50) NOT NULL,
  `ids_father` varchar(50) NOT NULL,
  `source` varchar(10) NOT NULL,
  `user_ids` char(50) NOT NULL,
  `user_fullname` varchar(100) NOT NULL,
  `main_status` tinyint(4) NOT NULL,
  `read_status` tinyint(6) NOT NULL,
  `catalog` varchar(50) NOT NULL,
  `priority` tinyint(4) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `associated_files` text NOT NULL,
  `created_time` timestamp NULL DEFAULT NULL,
  `updated_time` timestamp NULL DEFAULT NULL,
  `rating` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket`
--

LOCK TABLES `ticket` WRITE;
/*!40000 ALTER TABLE `ticket` DISABLE KEYS */;
/*!40000 ALTER TABLE `ticket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `token`
--

DROP TABLE IF EXISTS `token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(21) NOT NULL,
  `token` char(50) NOT NULL,
  `reference` varchar(255) NOT NULL,
  `created_time` timestamp NULL DEFAULT NULL,
  `done` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `token`
--

LOCK TABLES `token` WRITE;
/*!40000 ALTER TABLE `token` DISABLE KEYS */;
/*!40000 ALTER TABLE `token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tts_configuration`
--

DROP TABLE IF EXISTS `tts_configuration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tts_configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `default_language` varchar(50) NOT NULL,
  `storage` varchar(50) NOT NULL,
  `download_type` varchar(8) NOT NULL,
  `maximum_character` int(11) NOT NULL,
  `maximum_character_preview` int(11) NOT NULL,
  `preview_delay` tinyint(1) NOT NULL,
  `ssml` tinyint(1) NOT NULL,
  `engine` varchar(8) NOT NULL,
  `clean_up` int(11) NOT NULL,
  `pricing_model` text NOT NULL,
  `file_path_preview` varchar(255) NOT NULL,
  `file_path_user` varchar(255) NOT NULL,
  `aws` text NOT NULL,
  `google` text NOT NULL,
  `wasabi` text NOT NULL,
  `azure` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tts_configuration`
--

LOCK TABLES `tts_configuration` WRITE;
/*!40000 ALTER TABLE `tts_configuration` DISABLE KEYS */;
INSERT INTO `tts_configuration` VALUES (1,'en-US','local','friendly',3000,50,0,0,'both',0,'{\"enabled\":1,\"currency\":\"USD\",\"price\":\"4\",\"characters\":\"1000000\"}','tts_file/preview/','tts_file/user/','{\"config_file\":\"\",\"region\":\"\",\"bucket\":\"\",\"folder\":\"\"}','{\"config_file\":\"\"}','{\"config_file\":\"\",\"region\":\"\",\"bucket\":\"\",\"folder\":\"\"}','{\"region\":\"southeastasia\",\"subscription_key\":\"\"}');
/*!40000 ALTER TABLE `tts_configuration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tts_log`
--

DROP TABLE IF EXISTS `tts_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tts_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` char(50) NOT NULL,
  `user_ids` char(50) NOT NULL,
  `campaign` varchar(255) NOT NULL,
  `scheme` varchar(50) NOT NULL,
  `engine` varchar(50) NOT NULL,
  `language_code` varchar(50) NOT NULL,
  `language_name` varchar(50) NOT NULL,
  `voice_id` varchar(50) NOT NULL,
  `voice_name` varchar(50) NOT NULL,
  `config` varchar(1024) NOT NULL,
  `text` text NOT NULL,
  `characters_count` int(11) NOT NULL,
  `storage` varchar(50) NOT NULL,
  `tts_uri` varchar(1024) NOT NULL,
  `created_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tts_log`
--

LOCK TABLES `tts_log` WRITE;
/*!40000 ALTER TABLE `tts_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `tts_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tts_preview_log`
--

DROP TABLE IF EXISTS `tts_preview_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tts_preview_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` char(50) NOT NULL,
  `user_ids` char(50) NOT NULL,
  `scheme` varchar(50) NOT NULL,
  `engine` varchar(50) NOT NULL,
  `language_code` varchar(50) NOT NULL,
  `voice_id` varchar(50) NOT NULL,
  `text` text NOT NULL,
  `tts_uri` varchar(1024) NOT NULL,
  `created_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tts_preview_log`
--

LOCK TABLES `tts_preview_log` WRITE;
/*!40000 ALTER TABLE `tts_preview_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `tts_preview_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tts_resource`
--

DROP TABLE IF EXISTS `tts_resource`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tts_resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` char(50) NOT NULL,
  `scheme` varchar(50) NOT NULL,
  `language_name` varchar(50) NOT NULL,
  `language_code` varchar(50) NOT NULL,
  `voice_id` varchar(50) NOT NULL,
  `engine` varchar(50) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `enabled` tinyint(4) NOT NULL,
  `stuff` text NOT NULL,
  `accessibility_standard` text NOT NULL,
  `accessibility_neural` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tts_resource`
--

LOCK TABLES `tts_resource` WRITE;
/*!40000 ALTER TABLE `tts_resource` DISABLE KEYS */;
/*!40000 ALTER TABLE `tts_resource` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tts_statitics`
--

DROP TABLE IF EXISTS `tts_statitics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tts_statitics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_ids` char(50) NOT NULL,
  `payg_balance` int(11) NOT NULL,
  `payg_purchased` bigint(20) NOT NULL,
  `characters_preview_used` bigint(20) NOT NULL,
  `characters_production_used` bigint(20) NOT NULL,
  `voice_generated` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tts_statitics`
--

LOCK TABLES `tts_statitics` WRITE;
/*!40000 ALTER TABLE `tts_statitics` DISABLE KEYS */;
INSERT INTO `tts_statitics` VALUES (1,'0bWHjn9usb065a85cc223037e3b5dff82c4c08fba2XaMlC3Gk',0,0,0,0,0);
/*!40000 ALTER TABLE `tts_statitics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` char(50) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `api_key` char(50) NOT NULL,
  `balance` text NOT NULL,
  `email_address` varchar(50) NOT NULL,
  `email_verified` tinyint(4) NOT NULL,
  `email_address_pending` varchar(50) NOT NULL,
  `phone` varchar(21) NOT NULL,
  `phone_verified` tinyint(4) NOT NULL,
  `phone_pending` varchar(21) NOT NULL,
  `oauth_google_identifier` varchar(50) NOT NULL,
  `oauth_facebook_identifier` varchar(50) NOT NULL,
  `oauth_twitter_identifier` varchar(50) NOT NULL,
  `signup_source` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `company` varchar(255) NOT NULL,
  `avatar` varchar(54) NOT NULL,
  `timezone` varchar(255) NOT NULL,
  `date_format` varchar(20) NOT NULL,
  `time_format` varchar(20) NOT NULL,
  `language` varchar(50) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `country` varchar(2) NOT NULL,
  `address_line_1` varchar(255) NOT NULL,
  `address_line_2` varchar(255) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `zip_code` varchar(20) NOT NULL,
  `role_ids` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_time` timestamp NULL DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT NULL,
  `login_success_detail` text DEFAULT NULL,
  `online` tinyint(4) NOT NULL,
  `online_time` timestamp NULL DEFAULT NULL,
  `new_notification` tinyint(4) NOT NULL,
  `referral` varchar(255) NOT NULL,
  `affiliate_enabled` tinyint(1) NOT NULL,
  `affiliate_code` varchar(50) NOT NULL,
  `affiliate_earning` varchar(1024) NOT NULL,
  `affiliate_setting` varchar(1024) NOT NULL,
  `company_number` varchar(50) NOT NULL,
  `tax_number` varchar(50) NOT NULL,
  `stuff` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'0bWHjn9usb065a85cc223037e3b5dff82c4c08fba2XaMlC3Gk','admin','$2y$12$RBzxx/rHkiCDxnDz4bqu7OujqQopUG2S0eo47r6YTAWYq3xNut87O','pljhshumN66e81b818cbdfbki90e1190206e6cf7c97gassvv','{\"usd\":0}','admin@admin.com',1,'','',0,'','','','','','Super','Admin','','default.jpg','UTC','Y-m-d','H:i:s','English','USD','','','','','','','d7DrO85Nu9a534ea1c14ea96369c921933c347f5dhMZnVfg46',1,'2020-12-01 00:00:00','2020-12-01 00:00:00','{\"time\":\"2021-05-21 17:36:54 UTC\",\"interface\":\"web\",\"ip_address\":\"172.68.155.141\",\"user_agent\":\"Chrome 90.0.4430.212\"}',1,'2021-05-21 17:40:41',0,'{\"src_from\":\"\",\"referral_code\":\"\"}',0,'','{}','','','','');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-05-21 17:42:11
