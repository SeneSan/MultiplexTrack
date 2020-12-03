-- MySQL dump 10.13  Distrib 8.0.18, for osx10.15 (x86_64)
--
-- Host: 127.0.0.1    Database: multiplex
-- ------------------------------------------------------
-- Server version	8.0.22

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
-- Table structure for table `movies`
--

DROP TABLE IF EXISTS `movies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `year` int NOT NULL,
  `type` varchar(2) NOT NULL,
  `duration` int NOT NULL,
  `categories` varchar(255) NOT NULL,
  `poster` varchar(255) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movies`
--

LOCK TABLES `movies` WRITE;
/*!40000 ALTER TABLE `movies` DISABLE KEYS */;
INSERT INTO `movies` VALUES (24,'Avengers Assemble',2012,'3D',143,' Action, Adventure, Sci-Fi','avenger_assemble_2012.jpeg','Earth\'s mightiest heroes must come together and learn to fight as a team if they are going to stop the mischievous Loki and his alien army from enslaving humanity.'),(25,'Avengers: Age of Ultron',2015,'3D',141,'Action, Adventure, Sci-Fi','avenger_age_of_ultron_2015.jpg','When Tony Stark and Bruce Banner try to jump-start a dormant peacekeeping program called Ultron, things go horribly wrong and it\'s up to Earth\'s mightiest heroes to stop the villainous Ultron from enacting his terrible plan.'),(26,'Avengers: Endgame',2019,'3D',149,'Action, Adventure, Drama','avengers_end_game_2019.jpg','After the devastating events of Avengers: Infinity War (2018), the universe is in ruins. With the help of remaining allies, the Avengers assemble once more in order to reverse Thanos\' actions and restore balance to the universe.'),(27,'The Dark Knight',2008,'2D',153,' Action, Crime, Drama','dark_knight_2008.jpg','When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.'),(28,'Pulp Fiction',1994,'2D',153,'Crime, Drama ','pulp_fiction_1994.jpg','The lives of two mob hitmen, a boxer, a gangster and his wife, and a pair of diner bandits intertwine in four tales of violence and redemption.'),(29,'Inception',2010,'2D',148,'Action, Adventure, Sci-Fi','inception_2010.jpg','A thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O.'),(30,'Spirited Away',2001,'2D',125,'Animation, Adventure, Family','spirited_away_2001.jpg','During her family\'s move to the suburbs, a sullen 10-year-old girl wanders into a world ruled by gods, witches, and spirits, and where humans are changed into beasts.'),(31,'The Pianist',2002,'2D',150,'Biography, Drama, Music','pianist_2002.jpg','A Polish Jewish musician struggles to survive the destruction of the Warsaw ghetto of World War II.'),(32,'Joker',2019,'2D',122,'Crime, Drama, Thriller ','joker_2019.jpg','In Gotham City, mentally troubled comedian Arthur Fleck is disregarded and mistreated by society. He then embarks on a downward spiral of revolution and bloody crime. This path brings him face-to-face with his alter-ego: the Joker.'),(33,'Spider-Man: Into the Spider-Verse',2018,'3D',117,'Animation, Action, Adventure ','into_spider_verse_2018.jpg','Teen Miles Morales becomes the Spider-Man of his universe, and must join with five spider-powered individuals from other dimensions to stop a threat for all realities.');
/*!40000 ALTER TABLE `movies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `time_slots`
--

DROP TABLE IF EXISTS `time_slots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `time_slots` (
  `id` int NOT NULL AUTO_INCREMENT,
  `screen_id` int NOT NULL,
  `movie_id` int NOT NULL,
  `start_date_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `time_slots_movies_id_fk` (`movie_id`),
  CONSTRAINT `time_slots_movies_id_fk` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `time_slots`
--

LOCK TABLES `time_slots` WRITE;
/*!40000 ALTER TABLE `time_slots` DISABLE KEYS */;
INSERT INTO `time_slots` VALUES (72,1,25,'2020-12-03 09:00:00'),(73,1,33,'2020-12-02 12:00:00'),(74,1,30,'2020-12-03 14:30:00'),(75,1,32,'2020-12-03 17:00:00'),(76,2,27,'2020-12-03 09:00:00'),(77,2,29,'2020-12-01 12:00:00'),(78,2,26,'2020-12-02 15:30:00'),(79,3,24,'2020-12-01 09:00:00'),(80,3,31,'2020-12-01 12:00:00'),(81,3,28,'2020-12-01 15:30:00'),(82,1,33,'2020-12-02 09:00:00'),(83,1,33,'2020-12-01 11:30:00'),(84,1,33,'2020-12-01 14:00:00'),(85,1,33,'2020-12-01 16:30:00'),(86,1,33,'2020-12-03 09:00:00'),(87,1,33,'2020-12-03 12:00:00'),(88,1,33,'2020-12-03 14:00:00'),(89,1,33,'2020-12-03 16:30:00'),(90,2,33,'2020-12-03 10:00:00'),(91,2,33,'2020-12-03 12:30:00'),(92,2,33,'2020-12-03 15:30:00'),(93,3,33,'2020-12-03 09:30:00'),(94,3,33,'2020-12-03 12:00:00'),(95,3,33,'2020-12-03 14:30:00'),(96,4,33,'2020-12-03 09:00:00'),(97,4,33,'2020-12-03 12:00:00'),(98,4,33,'2020-12-03 16:00:00');
/*!40000 ALTER TABLE `time_slots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phonenumber` varchar(10) DEFAULT NULL,
  `type` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (6,'test','$2y$10$flQHBeBRtoVCZMGXACVQQOtPTpiSH3ErUMGQg5AOYgVGjmgxWDb0q','test','test',1),(7,'sene','$2y$10$1RC2oAUVfifLGYY.IWZtKO4CHjsTvuZbmNWhvTCyDwjo.tv3oyp2C','sene@example.com','123123123',2);
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

-- Dump completed on 2020-12-03 21:03:08
