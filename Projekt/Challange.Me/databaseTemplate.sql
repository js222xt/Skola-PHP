-- phpMyAdmin SQL Dump
-- version 3.5.8.1
-- http://www.phpmyadmin.net
--
-- Host: 10.246.16.214:3306
-- Generation Time: Oct 25, 2014 at 02:41 PM
-- Server version: 5.5.39-MariaDB-1~wheezy
-- PHP Version: 5.3.3-7+squeeze15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `brjgames_nu`
--
CREATE DATABASE `brjgames_nu` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `brjgames_nu`;

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `AddChallenge`(IN `_AID` INT, IN `_CID` INT)
BEGIN
START TRANSACTION;
	IF NOT EXISTS (SELECT 1 FROM activechallenges WHERE AID = _AID AND CID = _CID) THEN
    BEGIN
	INSERT INTO activechallenges VALUES(null, _AID, _CID);
    UPDATE challenges SET Accepted = Accepted + 1 WHERE ID = _CID;
    END;
	END IF;
    COMMIT;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `AddChallengeToDB`(IN `_Name` VARCHAR(200), IN `_Desc` VARCHAR(250), IN `_Worth` INT)
BEGIN
	INSERT INTO challenges VALUES(null, _Name, _Desc, _Worth, 0, 0);
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `AddCommentToChallenge`(IN `_AID` INT, IN `_CID` INT, IN `_Title` VARCHAR(50), IN `_Comment` VARCHAR(250))
BEGIN
	IF EXISTS (SELECT 1 FROM challenges WHERE ID = _CID) THEN
    BEGIN
	INSERT INTO comments VALUES(null, _AID, _CID, _Title, _Comment); 
    END;
    END IF;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `AddFriend`(IN `_AID1` INT, IN `_AID2` INT)
BEGIN
	IF NOT ExISTS(SELECT 1 FROM friends WHERE PID1 = _AID1 AND PID2 = _AID2 OR PID1 = _AID2 AND PID2 = _AID1) THEN
	BEGIN
    	INSERT INTO friends VALUES(null, _AID1, _AID2);
	END;
    END IF;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `BanUser`(IN `_AID` INT)
BEGIN
 IF NOT EXISTS(SELECT 1 FROM banned WHERE AID = _AID) THEN
 BEGIN
 	START TRANSACTION;
 	INSERT INTO banned VALUES(null, _AID);
    UPDATE account SET Banned = 1 WHERE AID = _AID;
    COMMIT;
End;
END IF;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `ChallengeUser`(IN `_AID1` INT, IN `_AID2` INT, IN `_CID` INT)
BEGIN
IF NOT EXISTS (SELECT * FROM challenged WHERE CBUID = _AID1 AND CUID = _AID2 AND CID = _CID) THEN
BEGIN
IF EXISTS(SELECT 1 FROM challenges WHERE ID = _CID) THEN
BEGIN
INSERT INTO challenged VALUES(null, _AID1, _AID2, _CID, 0);
END;
END IF;
END;
END IF;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `FinishChallengeForUser`(IN `_AID` INT, IN `_CID` INT)
BEGIN
	START TRANSACTION;
    IF EXISTS( SELECT 1 FROM activechallenges WHERE AID = _AID AND CID = _CID)THEN
    BEGIN
    	DELETE FROM activechallenges WHERE AID = _AID AND CID = _CID;
        UPDATE challenges SET Completed = Completed + 1;
        UPDATE account SET challengePoints = challengePoints + (SELECT WorthChallengePoints FROM challenges WHERE ID = _CID);
	INSERT INTO completedchallenges VALUES (NULL , _AID, _CID);
	END;
    END IF;
    COMMIT;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `GetAllChallangesForID`(IN `_AID` INT)
BEGIN
	SELECT * FROM activechallenges WHERE AID = _AID;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `GetAllChallenges`()
BEGIN
	SELECT * FROM challenges;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `GetAllChallengesUserChallnegedWith`(IN `_CUID` INT)
BEGIN
SELECT * FROM challenged WHERE CUID = _CUID;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `GetAllComments`(IN `_CID` INT)
BEGIN
	SELECT * FROM comments WHERE CID = _CID;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `GetAllCompletedChallangesForID`(IN `_AID` INT)
BEGIN
	SELECT * FROM completedchallenges WHERE AID = _AID;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `GetAllFriendsForUser`(IN `_AID` INT)
BEGIN
	SELECT * FROM friends WHERE PID1 = _AID OR PID2 = _AID;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `GetAllUsers`()
BEGIN
 SELECT * FROM account;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `GetChallenge`(IN `_ID` INT)
BEGIN
	SELECT * FROM challenges WHERE ID = _ID;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `GetComment`(IN `_CID` INT)
BEGIN
	SELECT * FROM comments WHERE CID = _CID;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `GetEmailFromEmail`(IN `_Email` VARCHAR(200))
BEGIN
	SELECT * FROM account WHERE Email = _Email;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `GetUnreadChallengesNotifications`(IN `_CUID` INT)
BEGIN
SELECT * FROM challenged WHERE CUID = _CUID AND beenRead = 0;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `GetUser`(IN `_ID` INT)
BEGIN
	SELECT * FROM account WHERE ID = _ID;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `GetUserFromUsername`(IN `_username` VARCHAR(50))
BEGIN
SELECT * FROM account WHERE Username = _username;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `LoginUser`(IN `_username` VARCHAR(50), IN `_password` VARCHAR(50))
BEGIN
	SELECT * FROM account WHERE Username = _username AND APassword = _password;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `MarkChallengedAsRead`(IN `_CUID` INT)
BEGIN
	UPDATE challenged SET beenRead = 1 WHERE CUID = _CUID;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `RegisterAccount`(IN `_Username` VARCHAR(50), IN `_Password` VARCHAR(50), IN `_Email` VARCHAR(200), IN `_FName` VARCHAR(50), IN `_LName` VARCHAR(50), IN `_CHP` INT)
BEGIN
	IF NOT EXISTS (SELECT 1 FROM account WHERE Username = _Username OR Email = _Email) THEN
	BEGIN
    	INSERT INTO account VALUES(null, _Username, _Password, _Email, _FName, _LName, _CHP, 0, 0);
    END;
    END IF;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `RemoveChallengeFromDB`(IN `_CID` INT)
BEGIN
	START TRANSACTION;
    DELETE FROM comments WHERE CID = _CID;
    DELETE FROM activechallenges WHERE CID = _CID;
    DELETE FROM challenged WHERE CID = _CID;
    DELETE FROM completedchallenges WHERE CID = _CID;
    DELETE FROM challenges WHERE ID = _CID;
    COMMIT;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `RemoveChallengeFromUser`(IN `_AID` INT, IN `_CID` INT)
BEGIN
	DELETE FROM activechallenges WHERE AID = _AID AND CID = _CID;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `RemoveComment`(IN `_ID` INT)
BEGIN
	DELETE FROM comments WHERE ID = _ID;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `RemoveFriend`(IN `_AID1` INT, IN `_AID2` INT)
BEGIN
	DELETE FROM friends WHERE PID1 = _AID1 AND PID2 = _AID2 OR PID1 = _AID2 AND PID2 = _AID1;
END$$

CREATE DEFINER=`brjgames_nu`@`%` PROCEDURE `UnbanUser`(IN `_AID` INT)
BEGIN
	START TRANSACTION;
	DELETE FROM banned WHERE AID = _AID;
    UPDATE account SET Banned  = 0 WHERE AID = _AID;
    COMMIT;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE IF NOT EXISTS `account` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) NOT NULL,
  `APassword` varchar(50) NOT NULL COMMENT 'Account password',
  `Email` varchar(200) NOT NULL,
  `FName` varchar(50) NOT NULL,
  `LName` varchar(50) NOT NULL,
  `challengePoints` int(11) NOT NULL DEFAULT '0',
  `IsAdmin` tinyint(1) NOT NULL DEFAULT '0',
  `Banned` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`ID`, `Username`, `APassword`, `Email`, `FName`, `LName`, `challengePoints`, `IsAdmin`, `Banned`) VALUES
(10, 'Admin', '1265053b5cb8d29ed07e7a42f6d7a9bb', 'admin@challenge.me', 'Admin', 'Istrator', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `activechallenges`
--

CREATE TABLE IF NOT EXISTS `activechallenges` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `AID` int(11) NOT NULL COMMENT 'Account ID',
  `CID` int(11) NOT NULL COMMENT 'Challenge ID',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `activechallenges`
--

INSERT INTO `activechallenges` (`ID`, `AID`, `CID`) VALUES
(1, 1, 1),
(3, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `banned`
--

CREATE TABLE IF NOT EXISTS `banned` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `AID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `challenged`
--

CREATE TABLE IF NOT EXISTS `challenged` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CBUID` int(11) NOT NULL COMMENT 'Challegned by user id',
  `CUID` int(11) NOT NULL COMMENT 'Challenged user id',
  `CID` int(11) NOT NULL,
  `beenRead` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Seen?',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `challenged`
--

INSERT INTO `challenged` (`ID`, `CBUID`, `CUID`, `CID`, `beenRead`) VALUES
(1, 1, 3, 1, 0),
(3, 1, 2, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `challenges`
--

CREATE TABLE IF NOT EXISTS `challenges` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(200) NOT NULL,
  `Description` varchar(250) NOT NULL,
  `WorthChallengePoints` int(255) NOT NULL DEFAULT '0',
  `Accepted` int(11) NOT NULL DEFAULT '0',
  `Completed` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `challenges`
--

INSERT INTO `challenges` (`ID`, `Name`, `Description`, `WorthChallengePoints`, `Accepted`, `Completed`) VALUES
(1, 'Eat a banana', 'read title', 10, 2, 0),
(2, 'Throw a banana peal on the floor', 'JUST DO IT', 20, 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `AID` int(11) NOT NULL,
  `CID` int(11) NOT NULL,
  `Title` varchar(50) NOT NULL,
  `Comment` varchar(250) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `completedchallenges`
--

CREATE TABLE IF NOT EXISTS `completedchallenges` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `AID` int(11) NOT NULL COMMENT 'Account ID',
  `CID` int(11) NOT NULL COMMENT 'Challenge ID',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PID1` int(11) NOT NULL,
  `PID2` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`ID`, `PID1`, `PID2`) VALUES
(1, 1, 2),
(2, 3, 1);
