DROP TABLE IF EXISTS Api_Token;
DROP TABLE IF EXISTS Pawns;
DROP TABLE IF EXISTS Game;
DROP TABLE IF EXISTS Persons;

CREATE TABLE `Persons` (
	`Player_ID` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
	`User_Name` varchar(255) NOT NULL,
	PRIMARY KEY (`Player_ID`),
	UNIQUE KEY `User_Name` (`User_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Game` (
	`Game_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	`Player1_ID` bigint(20) unsigned NOT NULL,
	`Player2_ID` bigint(20) unsigned NOT NULL,
	`Status` varchar(255) NOT NULL,
	`Start_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`Game_ID`),
	KEY `Player1_ID` (`Player1_ID`),
	KEY `Player2_ID` (`Player2_ID`),
	CONSTRAINT `Game_ibfk_1` FOREIGN KEY (`Player1_ID`) REFERENCES `Persons` (`Player_ID`),
	CONSTRAINT `Game_ibfk_2` FOREIGN KEY (`Player2_ID`) REFERENCES `Persons` (`Player_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `Pawns` (
	`Pawn_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	`Game_ID` bigint(20) unsigned NOT NULL,
	`X` tinyint(1) unsigned NOT NULL,
	`Y` tinyint(1) unsigned NOT NULL,
	`Color` enum('Red','Blue') NOT NULL,
	`NR` tinyint(3) unsigned NOT NULL,
	PRIMARY KEY (`Pawn_ID`),
	UNIQUE KEY `Pawns_Positon_Taken` (`Game_ID`,`X`,`Y`) USING BTREE,
	UNIQUE KEY `Pawns_order` (`Game_ID`,`NR`) USING BTREE,
	CONSTRAINT `Pawns_ibfk_1` FOREIGN KEY (`Game_ID`) REFERENCES `Game` (`Game_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Api_Token` (
    `Token` char(32) NOT NULL,
    `Player_ID` bigint(20) unsigned NOT NULL,
    PRIMARY KEY (`Token`),
    KEY `Player_ID` (`Player_ID`),
    CONSTRAINT `Api_Token_ibfk_1` FOREIGN KEY (`Player_ID`) REFERENCES `Persons` (`Player_ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
