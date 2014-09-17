-- phpMyAdmin SQL Dump
-- version 3.5.0
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2013 年 09 月 22 日 21:09
-- 服务器版本: 5.1.62
-- PHP 版本: 5.3.15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `hupms`
--

-- --------------------------------------------------------

--
-- 表的结构 `hupms_flag`
--

CREATE TABLE IF NOT EXISTS `hupms_flag` (
  `Name` varchar(255) DEFAULT NULL,
  `Value` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `hupms_flag`
--

INSERT INTO `hupms_flag` (`Name`, `Value`) VALUES
('class', 1);

-- --------------------------------------------------------

--
-- 表的结构 `hupms_invalid`
--

CREATE TABLE IF NOT EXISTS `hupms_invalid` (
  `No` varchar(255) DEFAULT NULL,
  `Novip` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `hupms_lesson`
--

CREATE TABLE IF NOT EXISTS `hupms_lesson` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Type` int(11) DEFAULT NULL,
  `Room` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Teacher` int(11) DEFAULT NULL,
  `Day` int(11) DEFAULT NULL,
  `Time_s` varchar(255) DEFAULT NULL,
  `Time_e` varchar(255) DEFAULT NULL,
  `Del` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- 转存表中的数据 `hupms_lesson`
--

INSERT INTO `hupms_lesson` (`Id`, `Type`, `Room`, `Name`, `Teacher`, `Day`, `Time_s`, `Time_e`, `Del`) VALUES
(4, 1, 1, 'Punkin 初级', 16, 1, '19:50', '21:10', 0),
(5, 1, 1, 'Club Dance 入门', 6, 2, '18:20', '19:40', 0),
(6, 1, 1, 'House 初级', 6, 2, '19:50', '21:10', 0),
(7, 1, 2, 'Lockin 初级', 8, 2, '18:20', '19:40', 0),
(8, 1, 1, 'Poppin 初级', 5, 3, '19:50', '21:10', 0),
(9, 1, 2, 'Rhythm&Basic', 8, 3, '18:20', '19:40', 0),
(10, 1, 1, 'Jazz 初级', 13, 4, '18:20', '19:40', 0),
(11, 1, 1, 'Hiphop 初级', 1, 4, '19:50', '21:10', 0),
(12, 1, 1, 'Lockin 初级', 2, 5, '18:20', '19:40', 0),
(13, 1, 1, 'Jazz 初级', 7, 6, '15:40', '17:00', 0),
(14, 1, 1, 'Lockin 中级', 10, 6, '18:20', '19:40', 0),
(15, 1, 1, 'Poppin 高级', 5, 6, '19:50', '21:10', 0),
(16, 1, 2, 'Body Challenge 入门', 6, 6, '15:40', '17:00', 0),
(17, 1, 1, 'Rhythm&Basic 入门', 7, 0, '14:00', '15:20', 0),
(18, 1, 2, 'House 初级', 12, 6, '18:20', '19:40', 0),
(19, 1, 2, 'Hiphop 中级', 6, 6, '19:50', '21:10', 0),
(20, 1, 2, 'House 中级', 11, 0, '15:40', '17:00', 0),
(21, 1, 2, 'Poppin 中级', 14, 0, '18:20', '19:40', 0),
(22, 1, 2, 'Lockin 高级', 2, 0, '19:50', '21:10', 0),
(23, 1, 1, 'Jazz 中级', 13, 0, '15:40', '17:00', 0),
(24, 1, 1, 'Punkin 初级', 10, 0, '18:20', '19:40', 0),
(25, 1, 1, 'Hiphop 高级', 1, 0, '19:40', '21:10', 0),
(26, 1, 1, 'test', 1, 0, '11:11', '11:11', 1);

-- --------------------------------------------------------

--
-- 表的结构 `hupms_log`
--

CREATE TABLE IF NOT EXISTS `hupms_log` (
  `Username` varchar(255) DEFAULT NULL,
  `DoTime` datetime DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Content` varchar(255) DEFAULT NULL,
  `Detail` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `hupms_log`
--

INSERT INTO `hupms_log` (`Username`, `DoTime`, `Name`, `Content`, `Detail`) VALUES
('yuan', '2013-09-21 10:10:06', 'MoQ', '恢复了0003号学员蔡启豪', NULL),
('yuan', '2013-09-21 10:51:46', 'MoQ', '冻结了0003号学员蔡启豪', NULL),
('yuan', '2013-09-21 10:52:41', 'MoQ', '恢复了0003号学员蔡启豪', NULL),
('yuan', '2013-09-21 10:57:07', 'MoQ', '0003号学生购买了10次课程，现在总次数为10次', NULL),
('yuan', '2013-09-21 14:06:39', 'MoQ', '0003号学生购买了一张半年卡', NULL),
('yuan', '2013-09-21 14:07:11', 'MoQ', '0003号学生购买了一张半年卡', NULL),
('yuan', '2013-09-21 14:08:02', 'MoQ', '0003号学生购买了一张半年卡', NULL),
('yuan', '2013-09-21 14:08:46', 'MoQ', '0023号学生购买了一张年卡', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `hupms_record_b`
--

CREATE TABLE IF NOT EXISTS `hupms_record_b` (
  `Date` date DEFAULT NULL,
  `Sno` varchar(255) DEFAULT NULL,
  `Count` int(11) DEFAULT NULL,
  `Type` int(11) DEFAULT NULL,
  `Markup` varchar(255) DEFAULT NULL,
  `Money` float(7,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `hupms_record_b`
--

INSERT INTO `hupms_record_b` (`Date`, `Sno`, `Count`, `Type`, `Markup`, `Money`) VALUES
('2013-09-21', '0003', NULL, 3, '0003', 500.00),
('2013-09-21', '0003', NULL, 3, '0003', 500.00),
('2013-09-21', '0003', NULL, 3, '0003', 500.00),
('2013-09-21', '0023', NULL, 4, '', 1000.00);

-- --------------------------------------------------------

--
-- 表的结构 `hupms_record_s`
--

CREATE TABLE IF NOT EXISTS `hupms_record_s` (
  `Sno` varchar(255) DEFAULT NULL,
  `Lid` int(11) DEFAULT NULL,
  `DT` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `hupms_record_s`
--

INSERT INTO `hupms_record_s` (`Sno`, `Lid`, `DT`) VALUES
('23', 12, '2013-09-20'),
('23', 12, '2013-09-20'),
('23', 12, '2013-09-20'),
('23', 12, '2013-09-20');

-- --------------------------------------------------------

--
-- 表的结构 `hupms_record_t`
--

CREATE TABLE IF NOT EXISTS `hupms_record_t` (
  `Lid` int(11) DEFAULT NULL,
  `Tid` int(11) DEFAULT NULL,
  `DT` date DEFAULT NULL,
  `Late` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `hupms_record_t`
--

INSERT INTO `hupms_record_t` (`Lid`, `Tid`, `DT`, `Late`) VALUES
(13, 1, '2013-09-21', 0),
(14, 2, '2013-09-21', 20);

-- --------------------------------------------------------

--
-- 表的结构 `hupms_student`
--

CREATE TABLE IF NOT EXISTS `hupms_student` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) DEFAULT NULL,
  `Nickname` varchar(255) DEFAULT NULL,
  `No` varchar(255) DEFAULT NULL,
  `Novip` varchar(255) DEFAULT NULL,
  `Tel` varchar(255) DEFAULT NULL,
  `Mail` varchar(255) DEFAULT NULL,
  `Identity` varchar(255) DEFAULT NULL,
  `School` varchar(255) DEFAULT NULL,
  `Intro` text,
  `Lcount` int(11) DEFAULT NULL,
  `Vflag` int(11) DEFAULT NULL,
  `Vdate` date DEFAULT NULL,
  `Del` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `hupms_student`
--

INSERT INTO `hupms_student` (`Id`, `Name`, `Nickname`, `No`, `Novip`, `Tel`, `Mail`, `Identity`, `School`, `Intro`, `Lcount`, `Vflag`, `Vdate`, `Del`) VALUES
(1, '蔡启豪', '包子', '0003', '5003', '', '', '', '', '', 10, 0, '2013-12-13', 0),
(2, 'abc', '', '0023', '', '', '', '', '', '', 7, 0, '2014-09-21', 0);

-- --------------------------------------------------------

--
-- 表的结构 `hupms_teacher`
--

CREATE TABLE IF NOT EXISTS `hupms_teacher` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) DEFAULT NULL,
  `Nickname` varchar(255) DEFAULT NULL,
  `Tel` varchar(255) DEFAULT NULL,
  `Mail` varchar(255) DEFAULT NULL,
  `Level` int(11) DEFAULT NULL,
  `Identity` varchar(255) DEFAULT NULL,
  `Intro` text,
  `Del` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- 转存表中的数据 `hupms_teacher`
--

INSERT INTO `hupms_teacher` (`Id`, `Name`, `Nickname`, `Tel`, `Mail`, `Level`, `Identity`, `Intro`, `Del`) VALUES
(1, '李哲铭', 'Sim', '', '', 1, '', '', 0),
(2, '叶正', 'Y.Z.', '13611698884', 'yz@wiik.cn', 1, 'xxxxx', 'intro', 0),
(5, '吐尔洪江', 'Timus', '', '', 1, '', '', 0),
(6, '金骏', 'Jin', '', '', 1, '', '', 0),
(7, '刘莹洁', 'Nicky', '', '', 1, '', '', 0),
(8, '李浩岩', 'Dada', '', '', 1, '', '', 0),
(9, '花鑫', 'Shin', '', '', 2, '', '', 0),
(10, '舒翎', 'Ashli', '', '', 1, '', '', 0),
(11, '黄春', '黄春', '', '', 1, '', '', 0),
(12, '鲁俊', 'Shun', '', '', 1, '', '', 0),
(13, '何美萱', 'Conny', '', '', 1, '', '', 0),
(14, '杨卓安', 'Automan', '', '', 1, '', '', 0),
(15, '张宇驰', '鱼翅', '', '', 1, '', '', 0),
(16, '王利宁', '教授', '', '', 1, '', '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `hupms_user`
--

CREATE TABLE IF NOT EXISTS `hupms_user` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Sess` varchar(255) DEFAULT NULL,
  `LastLogin` varchar(255) DEFAULT NULL,
  `UserLevel` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `hupms_user`
--

INSERT INTO `hupms_user` (`Id`, `Username`, `Password`, `Name`, `Sess`, `LastLogin`, `UserLevel`) VALUES
(1, 'yuan', '1a4310693505f3718d753de7d96c5e06', 'MoQ', 'pcv0onikclp42t26o835n077i4', '2013-09-21 09:41:24', '5'),
(2, 'nicky', '827ccb0eea8a706c4c34a16891f84e7b', 'nicky', NULL, NULL, '3'),
(3, 'jin', '84fff20659999e2b83b45c6851ec57dd', '金老板', 'lvv6mttm10dr182hkc4slbq4t3', '2013-09-08 12:50:44', '3');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
