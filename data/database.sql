SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `DEFAULTDATABASENAME` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ;
USE `DEFAULTDATABASENAME` ;

-- -----------------------------------------------------
-- Table `DEFAULTDATABASENAME`.`users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `DEFAULTDATABASENAME`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `first_name` VARCHAR(256) NULL ,
  `last_name` VARCHAR(256) NULL ,
  `date_created` DATETIME NOT NULL ,
  `date_edited` DATETIME NOT NULL ,
  `email` VARCHAR(256) NOT NULL ,
  `password` VARCHAR(256) NULL ,
  `username` VARCHAR(45) NULL ,
  `role` ENUM('ADMIN', 'USER') NOT NULL DEFAULT "USER" ,
  `status` ENUM('ACTIVE', 'BANNED') NOT NULL DEFAULT "ACTIVE" ,
  `chat_status` ENUM('ONLINE', 'OFFLINE', 'BUSY') NOT NULL DEFAULT "OFFLINE" ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `email_UNIQUE` (`email`(128) ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `DEFAULTDATABASENAME`.`messages`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `DEFAULTDATABASENAME`.`messages` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `date_created` DATETIME NOT NULL ,
  `date_edited` DATETIME NOT NULL ,
  `message` TEXT NOT NULL ,
  `users_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `date_created` (`date_created` ASC) ,
  INDEX `date_edited` (`date_edited` ASC) ,
  INDEX `fk_messages_users1_idx` (`users_id` ASC) ,
  CONSTRAINT `fk_messages_users1`
    FOREIGN KEY (`users_id` )
    REFERENCES `DEFAULTDATABASENAME`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
PACK_KEYS = DEFAULT;


-- -----------------------------------------------------
-- Table `DEFAULTDATABASENAME`.`settings`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `DEFAULTDATABASENAME`.`settings` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `setting_name` VARCHAR(128) NOT NULL ,
  `setting_value` VARCHAR(256) NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `key_UNIQUE` (`setting_name` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `DEFAULTDATABASENAME`.`plugins`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `DEFAULTDATABASENAME`.`plugins` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
