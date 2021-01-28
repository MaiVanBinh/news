-- MySQL dump 10.13  Distrib 8.0.22, for Win64 (x86_64)
--
-- Host: localhost    Database: news
-- ------------------------------------------------------
-- Server version	8.0.22

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int NOT NULL,
  `updated_by` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cate_users_idx` (`created_by`,`updated_by`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (7,'ádasd','2021-01-28 22:11:05','2021-01-28 22:11:05',1,1);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `url` varchar(105) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `size` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `in_use` tinyint DEFAULT '0',
  `created_by` int NOT NULL DEFAULT '1',
  `updated_by` int NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_created_user_idx` (`created_by`),
  KEY `fk_updated_user_idx` (`updated_by`),
  CONSTRAINT `fk_created_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_updated_user` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `images`
--

LOCK TABLES `images` WRITE;
/*!40000 ALTER TABLE `images` DISABLE KEYS */;
INSERT INTO `images` VALUES (11,'http://localhost:8080/images/1611330200_android','6671','1611330200_android.png',0,1,1,'2021-01-22 22:43:20','2021-01-28 12:35:59'),(12,'http://localhost:8080/images/1611380705_android','6671','1611380705_android.png',0,1,1,'2021-01-23 12:45:05','2021-01-28 10:49:43'),(13,'http://localhost:8080/images/1611385974_android','6671','1611385974_android.png',0,1,1,'2021-01-23 14:12:54','2021-01-28 10:49:54'),(14,'http://localhost:8080/images/1611763234_6121s','541889','1611763234_6121s.jpg',0,1,1,'2021-01-27 23:00:34','2021-01-28 21:02:50'),(15,'http://localhost:8080/images/1611763782_a89ce08dc81d108a9160493113bd6c31cf3d70f942b7b-Vb2TqC','273275','1611763782_a89ce08dc81d108a9160493113bd6c31cf3d70f942b7b-Vb2TqC.jpg',0,1,1,'2021-01-27 23:09:42','2021-01-28 21:02:48'),(16,'http://localhost:8080/images/1611763792_6121s','541889','1611763792_6121s.jpg',0,1,1,'2021-01-27 23:09:52','2021-01-28 21:02:48'),(17,'http://localhost:8080/images/1611763795_5970s','99010','1611763795_5970s.jpg',0,1,1,'2021-01-27 23:09:55','2021-01-28 09:53:38'),(18,'http://localhost:8080/images/1611807176_228130715ee841a5e2528de6e3ae1820','24181','1611807176_228130715ee841a5e2528de6e3ae1820.jpg',0,1,1,'2021-01-28 11:12:56','2021-01-28 11:12:56'),(19,'http://localhost:8080/images/1611807179_final-fantasy-background-full-hd-34921','273597','1611807179_final-fantasy-background-full-hd-34921.jpg',0,1,1,'2021-01-28 11:12:59','2021-01-28 21:02:51'),(20,'http://localhost:8080/images/1611807807_TPJ0LBBHQ-U0164N94XPH-f846202080f2-512','47062','1611807807_TPJ0LBBHQ-U0164N94XPH-f846202080f2-512.jfif',0,1,1,'2021-01-28 11:23:27','2021-01-28 11:23:27'),(21,'http://localhost:8080/images/1611807835_5970s','99010','1611807835_5970s.jpg',0,1,1,'2021-01-28 11:23:55','2021-01-28 11:23:55'),(22,'http://localhost:8080/images/1611807837_5970s','99010','1611807837_5970s.jpg',0,1,1,'2021-01-28 11:23:57','2021-01-28 11:23:57'),(23,'http://localhost:8080/images/1611807838_5970s','99010','1611807838_5970s.jpg',0,1,1,'2021-01-28 11:23:58','2021-01-28 21:02:51');
/*!40000 ALTER TABLE `images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_image`
--

DROP TABLE IF EXISTS `post_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `post_image` (
  `post` int NOT NULL,
  `image` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int NOT NULL DEFAULT '1',
  `updated_by` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`post`,`image`),
  KEY `fk_image_idx` (`image`),
  KEY `fk_create_user_idx` (`created_by`),
  KEY `fk_update_user_idx` (`updated_by`),
  CONSTRAINT `fk_create_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_image` FOREIGN KEY (`image`) REFERENCES `images` (`id`),
  CONSTRAINT `fk_post_post` FOREIGN KEY (`post`) REFERENCES `posts` (`id`),
  CONSTRAINT `fk_update_user` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_image`
--

LOCK TABLES `post_image` WRITE;
/*!40000 ALTER TABLE `post_image` DISABLE KEYS */;
/*!40000 ALTER TABLE `post_image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `content` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `category` int NOT NULL,
  `created_by` int NOT NULL,
  `updated_by` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_publish` tinyint DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk1_idx` (`created_by`),
  KEY `fj2_idx` (`updated_by`),
  KEY `fk_new)s_cate_idx` (`category`),
  CONSTRAINT `fk1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `fk2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_new)s_cate` FOREIGN KEY (`category`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (51,'ádas','ádasdasd','',7,1,1,'2021-01-28 22:11:19','2021-01-28 22:11:19',0);
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int NOT NULL DEFAULT '1',
  `updated_by` int NOT NULL DEFAULT '1',
  `role` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','maivanbinh1321999@gmail.com','$2y$10$XGfa8UOsP06uYp0qL75e0uvDgOQv6D6s4ipPKJjRCKdvoElaIRxHK','2021-01-20 14:06:50','2021-01-20 14:06:50',1,1,1),(2,'admin','maivanbinh19@gmail.com','$2y$10$8lwyiYmzSDk3VCgcOOOa3.MEF4Y5LPT72mkjVeJyHnjOfDheSi2cy','2021-01-20 14:22:32','2021-01-20 14:22:32',1,1,0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-01-28 22:12:22
