SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `ssmq` ;
CREATE SCHEMA IF NOT EXISTS `ssmq` DEFAULT CHARACTER SET utf8 ;
USE `ssmq` ;

-- -----------------------------------------------------
-- Table `ssmq`.`queues`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ssmq`.`queues` ;

CREATE TABLE IF NOT EXISTS `ssmq`.`queues` (
  `idqueues` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idqueues`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ssmq`.`messages`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ssmq`.`messages` ;

CREATE TABLE IF NOT EXISTS `ssmq`.`messages` (
  `idmessages` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `idqueues` INT(10) UNSIGNED NOT NULL,
  `recipient` VARCHAR(45) NULL DEFAULT NULL,
  `message` VARCHAR(255) NULL,
  `attributes` BLOB NULL DEFAULT NULL,
  `created` TIMESTAMP NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`idmessages`),
  INDEX `fk_messages_queues_idx` (`idqueues` ASC),
  INDEX `key_by_queue_recipient` (`idqueues` ASC, `recipient` ASC),
  INDEX `created_at` (`created` ASC),
  CONSTRAINT `fk_messages_queues`
    FOREIGN KEY (`idqueues`)
    REFERENCES `ssmq`.`queues` (`idqueues`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
