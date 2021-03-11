-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 11, 2021 at 09:59 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `case_laka`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `_korban`
--

CREATE TABLE IF NOT EXISTS `_korban` (
`id_korban` int(11) NOT NULL,
  `id_laka` int(11) NOT NULL,
  `id_pendidikan` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `_korban`
--

INSERT INTO `_korban` (`id_korban`, `id_laka`, `id_pendidikan`, `nama`) VALUES
(1, 1, 1, 'Adi'),
(2, 1, 1, 'Budi'),
(3, 2, 4, 'Upin'),
(4, 2, 3, 'Ipin'),
(5, 2, 2, 'Patrik'),
(7, 3, 4, 'Shagy');

-- --------------------------------------------------------

--
-- Table structure for table `_laka`
--

CREATE TABLE IF NOT EXISTS `_laka` (
`id_laka` int(11) NOT NULL,
  `tgl` datetime NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `_laka`
--

INSERT INTO `_laka` (`id_laka`, `tgl`, `keterangan`) VALUES
(1, '2021-03-03 09:26:30', 'Rem Blong'),
(2, '2021-03-09 15:27:26', 'Mabuk-mabukan'),
(3, '2021-02-10 15:27:26', 'Pencurian');

-- --------------------------------------------------------

--
-- Table structure for table `_pendidikan`
--

CREATE TABLE IF NOT EXISTS `_pendidikan` (
`id` int(11) NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `_pendidikan`
--

INSERT INTO `_pendidikan` (`id`, `keterangan`) VALUES
(1, 'SD'),
(2, 'SLTP'),
(3, 'SLTA'),
(4, 'Perguruan Tinggi'),
(5, 'Lain-lain');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
 ADD PRIMARY KEY (`id`), ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `_korban`
--
ALTER TABLE `_korban`
 ADD PRIMARY KEY (`id_korban`);

--
-- Indexes for table `_laka`
--
ALTER TABLE `_laka`
 ADD PRIMARY KEY (`id_laka`);

--
-- Indexes for table `_pendidikan`
--
ALTER TABLE `_pendidikan`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `_korban`
--
ALTER TABLE `_korban`
MODIFY `id_korban` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `_laka`
--
ALTER TABLE `_laka`
MODIFY `id_laka` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `_pendidikan`
--
ALTER TABLE `_pendidikan`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
