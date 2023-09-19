-- SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET SQL_SAFE_UPDATES = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- -----------------------------------------------------
-- Schema conversionform
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `4717database` DEFAULT CHARACTER SET utf8 ;
USE `4717database` ;

-- --------------------------------------------------------------------------------------------------------------------------------------------------------------------
-- Table `4717database`.`user` --------------------------------------------------------------------------------------------------------------------------------------
-- --------------------------------------------------------------------------------------------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `4717database`.`user` (
  `_id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(32) NOT NULL,
  `lastname` VARCHAR(50) NOT NULL,
  `firstname` VARCHAR(50) NOT NULL,
  `birthday` DATETIME NOT NULL,
  `usertype` VARCHAR(45),
  PRIMARY KEY (`_id`)) 
ENGINE = InnoDB;

-- --------------------------------------------------------------------------------------------------------------------------------------------------------------------
-- Table `4717database`.`doctor_schedule` -------------------------------------------------------------------------------------------------------------------------------
-- --------------------------------------------------------------------------------------------------------------------------------------------------------------------
-- DO I DELETE PAST RECORDS (for a month)? 
-- Link patient/_id
CREATE TABLE IF NOT EXISTS `4717database`.`doctor_schedule` (
  `doctor` VARCHAR(10) NOT NULL,
  `date` VARCHAR(10) NOT NULL,
  `time` VARCHAR(10) NOT NULL,
  `patient` VARCHAR(100) NOT NULL,
  `appointmentType` VARCHAR(100) NOT NULL,
  `comment` VARCHAR(250))
ENGINE = InnoDB;