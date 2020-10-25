
-- ------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- dinosaurteaparty implementation : © Maria Munoz <spukyta@gmail.com>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-- -----

-- dbmodel.sql

-- This is the file where you are describing the database schema of your game
-- Basically, you just have to export from PhpMyAdmin your table structure and copy/paste
-- this export here.
-- Note that the database itself and the standard tables ("global", "stats", "gamelog" and "player") are
-- already created and must not be created here

-- Note: The database schema is created from this file when the game starts. If you modify this file,
--       you have to restart a game to see your changes in database.



-- CREATE TABLE dinosaur
CREATE TABLE `dinosaur` ( 
    `dinosaur_id` INT(10) UNSIGNED NOT NULL ,
    `dinosaur_name` VARCHAR(100) ,    
    `dinosaur_quirk` INT(10) UNSIGNED NULL ,
    `dinosaur_player_id` INT(10) NULL , 
    `dinosaur_quirk3lastanswer` INT(10) NULL ,
    `dinosaur_order` INT(10) UNSIGNED NULL ,
    `dinosaur_active` TINYINT(1) NULL ,
    PRIMARY KEY (`dinosaur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- insert dinosaurs
INSERT INTO `dinosaur` (`dinosaur_id`, `dinosaur_name`, `dinosaur_quirk`, `dinosaur_player_id`, `dinosaur_quirk3lastanswer`, `dinosaur_active`) 
VALUES ('1', 'Amelia', NULL, NULL, NULL, '1');
INSERT INTO `dinosaur` (`dinosaur_id`, `dinosaur_name`, `dinosaur_quirk`, `dinosaur_player_id`, `dinosaur_quirk3lastanswer`, `dinosaur_active`) 
VALUES ('2', 'Beatrice', NULL, NULL, NULL, '1');
INSERT INTO `dinosaur` (`dinosaur_id`, `dinosaur_name`, `dinosaur_quirk`, `dinosaur_player_id`, `dinosaur_quirk3lastanswer`, `dinosaur_active`) 
VALUES ('3', 'Carlton', NULL, NULL, NULL, '1');
INSERT INTO `dinosaur` (`dinosaur_id`, `dinosaur_name`, `dinosaur_quirk`, `dinosaur_player_id`, `dinosaur_quirk3lastanswer`, `dinosaur_active`) 
VALUES ('4', 'Dennis', NULL, NULL, NULL, '1');
INSERT INTO `dinosaur` (`dinosaur_id`, `dinosaur_name`, `dinosaur_quirk`, `dinosaur_player_id`, `dinosaur_quirk3lastanswer`, `dinosaur_active`) 
VALUES ('5', 'Eleanor', NULL, NULL, NULL, '1');
INSERT INTO `dinosaur` (`dinosaur_id`, `dinosaur_name`, `dinosaur_quirk`, `dinosaur_player_id`, `dinosaur_quirk3lastanswer`, `dinosaur_active`) 
VALUES ('6', 'Gerald', NULL, NULL, NULL, '1');
INSERT INTO `dinosaur` (`dinosaur_id`, `dinosaur_name`, `dinosaur_quirk`, `dinosaur_player_id`, `dinosaur_quirk3lastanswer`, `dinosaur_active`) 
VALUES ('7', 'Harriet', NULL, NULL, NULL, '1');
INSERT INTO `dinosaur` (`dinosaur_id`, `dinosaur_name`, `dinosaur_quirk`, `dinosaur_player_id`, `dinosaur_quirk3lastanswer`, `dinosaur_active`) 
VALUES ('8', 'Jeannine', NULL, NULL, NULL, '1');
INSERT INTO `dinosaur` (`dinosaur_id`, `dinosaur_name`, `dinosaur_quirk`, `dinosaur_player_id`, `dinosaur_quirk3lastanswer`, `dinosaur_active`) 
VALUES ('9', 'Kenneth', NULL, NULL, NULL, '1');
INSERT INTO `dinosaur` (`dinosaur_id`, `dinosaur_name`, `dinosaur_quirk`, `dinosaur_player_id`, `dinosaur_quirk3lastanswer`, `dinosaur_active`) 
VALUES ('10', 'Lloyd', NULL, NULL, NULL, '1');
INSERT INTO `dinosaur` (`dinosaur_id`, `dinosaur_name`, `dinosaur_quirk`, `dinosaur_player_id`, `dinosaur_quirk3lastanswer`, `dinosaur_active`) 
VALUES ('11', 'Nigel', NULL, NULL, NULL, '1');
INSERT INTO `dinosaur` (`dinosaur_id`, `dinosaur_name`, `dinosaur_quirk`, `dinosaur_player_id`, `dinosaur_quirk3lastanswer`, `dinosaur_active`) 
VALUES ('12', 'Quinton', NULL, NULL, NULL, '1');
INSERT INTO `dinosaur` (`dinosaur_id`, `dinosaur_name`, `dinosaur_quirk`, `dinosaur_player_id`, `dinosaur_quirk3lastanswer`, `dinosaur_active`) 
VALUES ('13', 'Reginald', NULL, NULL, NULL, '1');
INSERT INTO `dinosaur` (`dinosaur_id`, `dinosaur_name`, `dinosaur_quirk`, `dinosaur_player_id`, `dinosaur_quirk3lastanswer`, `dinosaur_active`) 
VALUES ('14', 'Sebastian', NULL, NULL, NULL, '1');
INSERT INTO `dinosaur` (`dinosaur_id`, `dinosaur_name`, `dinosaur_quirk`, `dinosaur_player_id`, `dinosaur_quirk3lastanswer`, `dinosaur_active`) 
VALUES ('15', 'Tabitha', NULL, NULL, NULL, '1');
INSERT INTO `dinosaur` (`dinosaur_id`, `dinosaur_name`, `dinosaur_quirk`, `dinosaur_player_id`, `dinosaur_quirk3lastanswer`, `dinosaur_active`) 
VALUES ('16', 'Ulysses', NULL, NULL, NULL, '1');
INSERT INTO `dinosaur` (`dinosaur_id`, `dinosaur_name`, `dinosaur_quirk`, `dinosaur_player_id`, `dinosaur_quirk3lastanswer`, `dinosaur_active`) 
VALUES ('17', 'Vicent', NULL, NULL, NULL, '1');
INSERT INTO `dinosaur` (`dinosaur_id`, `dinosaur_name`, `dinosaur_quirk`, `dinosaur_player_id`, `dinosaur_quirk3lastanswer`, `dinosaur_active`) 
VALUES ('18', 'Winston', NULL, NULL, NULL, '1');
INSERT INTO `dinosaur` (`dinosaur_id`, `dinosaur_name`, `dinosaur_quirk`, `dinosaur_player_id`, `dinosaur_quirk3lastanswer`, `dinosaur_active`) 
VALUES ('19', 'Xabier', NULL, NULL, NULL, '1');
INSERT INTO `dinosaur` (`dinosaur_id`, `dinosaur_name`, `dinosaur_quirk`, `dinosaur_player_id`, `dinosaur_quirk3lastanswer`, `dinosaur_active`) 
VALUES ('20', 'Yorick', NULL, NULL, NULL, '1');


-- CREATE TABLE dinosaur_trait
CREATE TABLE `dinosaur_trait` ( 
    `dinosaur_id` INT(10) UNSIGNED NOT NULL ,
    `trait_id` INT(10) UNSIGNED NOT NULL ,
    PRIMARY KEY (`dinosaur_id`,`trait_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- insert dinosaur traits
-- amelia
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('1', '6');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('1', '1');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('1', '9');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('1', '4');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('1', '13');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('1', '8');
-- beatrice
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('2', '6');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('2', '1');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('2', '7');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('2', '9');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('2', '14');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('2', '10');
-- carlton
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('3', '1');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('3', '3');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('3', '4');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('3', '14');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('3', '12');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('3', '10');
-- dennis
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('4', '6');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('4', '1');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('4', '3');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('4', '7');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('4', '9');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('4', '14');
-- eleanor
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('5', '6');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('5', '2');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('5', '1');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('5', '9');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('5', '13');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('5', '8');
-- gerald
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('6', '6');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('6', '2');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('6', '3');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('6', '13');
-- harriet
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('7', '2');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('7', '1');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('7', '4');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('7', '8');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('7', '14');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('7', '15');
-- jeannine
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('8', '6');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('8', '2');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('8', '7');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('8', '8');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('8', '14');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('8', '10');
 -- kenneth
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('9', '2');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('9', '9');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('9', '14');
-- lloyd
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('10', '2');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('10', '1');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('10', '3');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('10', '13');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('10', '12');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('10', '5');
-- nigel
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('11', '6');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('11', '2');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('11', '3');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('11', '4');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('11', '18');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('11', '12');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('11', '5');
-- quinton
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('12', '2');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('12', '7');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('12', '8');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('12', '11');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('12', '12');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('12', '10');
-- reginald
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('13', '2');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('13', '7');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('13', '4');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('13', '11');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('13', '12');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('13', '5');
-- sebastian
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('14', '6');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('14', '2');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('14', '3');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('14', '4');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('14', '11');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('14', '5');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('14', '10');
-- tabitha
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('15', '6');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('15', '1');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('15', '3');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('15', '7');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('15', '9');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('15', '13');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('15', '8');
-- ulysses
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('16', '6');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('16', '7');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('16', '13');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('16', '11');
-- vincent
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('17', '6');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('17', '1');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('17', '3');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('17', '7');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('17', '11');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('17', '15');
-- winston
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('18', '6');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('18', '9');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('18', '4');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('18', '8');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('18', '11');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('18', '5');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('18', '15');
-- xavier
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('19', '6');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('19', '1');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('19', '3');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('19', '4');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('19', '11');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('19', '12');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('19', '5');
-- yorick
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('20', '1');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('20', '3');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('20', '9');
INSERT INTO `dinosaur_trait` (`dinosaur_id`, `trait_id`) VALUES ('20', '13');

-- CREATE TABLE player_trait
CREATE TABLE `player_trait` ( 
    `player_trait_player_id` INT(10) UNSIGNED NOT NULL ,
    `player_trait_trait_id` VARCHAR(100) ,    
    `player_trait_correct` TINYINT(1) UNSIGNED NULL ,
    PRIMARY KEY (`player_trait_player_id`,`player_trait_trait_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;