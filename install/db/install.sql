-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Table `test_views_users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `test_views_users` (
                                                  `ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                                                  `COOKIE_ID` CHAR(32) NOT NULL,
                                                  PRIMARY KEY (`ID`),
                                                  UNIQUE INDEX `COOKIE_ID_UNIQUE` (`COOKIE_ID` ASC) VISIBLE)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `test_articles_views`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `test_articles_views` (
                                                     `USER_ID` INT UNSIGNED NOT NULL,
                                                     `ELEMENT_ID` INT NOT NULL,
                                                     `TIMESTAMP` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                                     UNIQUE INDEX `USER_ID_ELEMENT_ID` (`USER_ID` ASC, `ELEMENT_ID` ASC) VISIBLE,
                                                     INDEX `fk_test_articles_views_b_iblock_element_idx` (`ELEMENT_ID` ASC) VISIBLE,
                                                     CONSTRAINT `fk_test_articles_views_test_views_users`
                                                         FOREIGN KEY (`USER_ID`)
                                                             REFERENCES `test_views_users` (`ID`)
                                                             ON DELETE CASCADE
                                                             ON UPDATE NO ACTION,
                                                     CONSTRAINT `fk_test_articles_views_b_iblock_element`
                                                         FOREIGN KEY (`ELEMENT_ID`)
                                                             REFERENCES `b_iblock_element` (`ID`)
                                                             ON DELETE CASCADE
                                                             ON UPDATE NO ACTION)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
