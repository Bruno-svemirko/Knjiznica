-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: localhost    Database: kniznica
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `knjige`
--

DROP TABLE IF EXISTS `knjige`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `knjige` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `naslov` varchar(255) NOT NULL,
  `izdavac` varchar(100) DEFAULT NULL,
  `godina_izdanja` varchar(4) DEFAULT NULL,
  `autor_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `knjige`
--

LOCK TABLES `knjige` WRITE;
/*!40000 ALTER TABLE `knjige` DISABLE KEYS */;
INSERT INTO `knjige` VALUES (3,'Nova Knjiga','Izdavac 3','2024',1,'2024-11-23 15:00:59'),(6,'Brunova kniga','┼ákolska knjiga','2001',4,'2024-11-24 14:09:50'),(17,'Brunova kniga','┼ákolska knjiga','2022',1,'2024-11-25 13:43:56'),(18,'Brunova kniga','┼ákolska knjiga','2025',1,'2024-11-25 13:44:08');
/*!40000 ALTER TABLE `knjige` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `korisnici`
--

DROP TABLE IF EXISTS `korisnici`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `korisnici` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ime` varchar(50) NOT NULL,
  `prezime` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `lozinka` varchar(255) NOT NULL,
  `uloga` enum('administrator','autor') NOT NULL,
  `aktivan` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `korisnici`
--

LOCK TABLES `korisnici` WRITE;
/*!40000 ALTER TABLE `korisnici` DISABLE KEYS */;
INSERT INTO `korisnici` VALUES (1,'Admin','Primjer','admin@example.com','$2y$10$pvTAAmz8oMd7yEdyps8kx.OScDnCF93hxw/oSFa.4cerfVhBBlbj.','administrator',1,'2024-11-23 15:00:59'),(4,'Bruno','Srnec','brunosrnec@gmail.com','$2y$10$144mq6hPKYt5yNBeEUoMhOly.RGTmwqG5TaSSaBQtA7vLqmPWy2.u','autor',1,'2024-11-24 14:02:32'),(5,'Autor','Primjer','autor@example.com','$2y$10$GMHpcLBE03YtGcpNyWkFjetig5jJmh3ipsFrtFeoiaT9wDjv1dErK','autor',1,'2024-11-24 14:17:10'),(7,'Admin2','Primjer','admin2@example.com','$2y$10$Q2k0L.3alpCaKShTKSeFuelf4yfKELX6bLzy/B4bASM9DOj2dIlwO','administrator',1,'2024-11-24 15:55:27');
/*!40000 ALTER TABLE `korisnici` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-11-25 14:52:57
