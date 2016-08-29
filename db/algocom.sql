-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema ALGOCOM
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema ALGOCOM
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `ALGOCOM` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `ALGOCOM` ;

-- -----------------------------------------------------
-- Table `ALGOCOM`.`members`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ALGOCOM`.`members` (
  `idmembers` INT NOT NULL AUTO_INCREMENT,
  `firstName` VARCHAR(45) NOT NULL,
  `lastName` VARCHAR(45) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`idmembers`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
