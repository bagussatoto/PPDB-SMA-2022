-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2022 at 02:47 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ppdbsmp`
--

-- --------------------------------------------------------

--
-- Table structure for table `calonppdb`
--

CREATE TABLE `calonppdb` (
  `ID` int(11) NOT NULL,
  `tanggaldaftar` varchar(50) DEFAULT NULL,
  `nomap` varchar(4) DEFAULT NULL,
  `namalengkap` varchar(100) DEFAULT NULL,
  `jeniskelamin` varchar(50) DEFAULT NULL,
  `nisn` varchar(10) DEFAULT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `tempatlahir` varchar(50) DEFAULT NULL,
  `tanggallahir1` varchar(50) DEFAULT NULL,
  `noaktalahir` varchar(50) DEFAULT NULL,
  `agama` varchar(50) DEFAULT NULL,
  `alamatjalan` varchar(100) DEFAULT NULL,
  `rt` varchar(3) DEFAULT NULL,
  `rw` varchar(3) DEFAULT NULL,
  `dusun` varchar(50) DEFAULT NULL,
  `deskel` varchar(50) DEFAULT NULL,
  `kecamatan` varchar(50) DEFAULT NULL,
  `kodepos` varchar(5) DEFAULT NULL,
  `tempattinggal` varchar(50) DEFAULT NULL,
  `modatransportasi` varchar(50) DEFAULT NULL,
  `anakke` varchar(50) DEFAULT NULL,
  `namaayah` varchar(50) DEFAULT NULL,
  `nikayah` varchar(16) DEFAULT NULL,
  `tanggallahir2` varchar(50) DEFAULT NULL,
  `pendidikan1` varchar(50) DEFAULT NULL,
  `pekerjaan1` varchar(50) DEFAULT NULL,
  `penghasilan1` varchar(50) DEFAULT NULL,
  `statusayah` varchar(50) DEFAULT NULL,
  `statusibu` varchar(50) DEFAULT NULL,
  `nikibu` varchar(16) DEFAULT NULL,
  `tanggallahir3` varchar(50) DEFAULT NULL,
  `pendidikan2` varchar(50) DEFAULT NULL,
  `pekerjaan2` varchar(50) DEFAULT NULL,
  `penghasilan2` varchar(50) DEFAULT NULL,
  `namawali` varchar(50) DEFAULT NULL,
  `nikwali` varchar(16) DEFAULT NULL,
  `tanggallahir4` varchar(50) DEFAULT NULL,
  `pendidikan3` varchar(50) DEFAULT NULL,
  `pekerjaan3` varchar(50) DEFAULT NULL,
  `penghasilan3` varchar(50) DEFAULT NULL,
  `kontak` varchar(50) DEFAULT NULL,
  `nomorwa` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `tinggibadan` varchar(5) DEFAULT NULL,
  `beratbadan` varchar(5) DEFAULT NULL,
  `jaraktempatkesekolah` varchar(5) DEFAULT NULL,
  `jumlahsaudarakandung` varchar(50) DEFAULT NULL,
  `asaltk` varchar(50) DEFAULT NULL,
  `nomorsurattk` varchar(50) DEFAULT NULL,
  `paktaintegritas` varchar(5000) DEFAULT NULL,
  `namaibu` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pekerjaan`
--

CREATE TABLE `pekerjaan` (
  `ID` int(11) NOT NULL,
  `namapekerjaan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pekerjaan`
--

INSERT INTO `pekerjaan` (`ID`, `namapekerjaan`) VALUES
(1, 'ASN / TNI / POLRI'),
(2, 'Karyawan Swasta'),
(3, 'Buruh'),
(4, 'Wiraswasta'),
(5, 'Wirausaha'),
(6, 'Pensiun'),
(7, 'Tidak Bekerja');

-- --------------------------------------------------------

--
-- Table structure for table `pendidikan`
--

CREATE TABLE `pendidikan` (
  `ID` int(11) NOT NULL,
  `namatingkat` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pendidikan`
--

INSERT INTO `pendidikan` (`ID`, `namatingkat`) VALUES
(1, 'SD/MI Sederajat'),
(2, 'SMP/MTS Sederajat'),
(3, 'SMA/MA/SMK Sederajat'),
(4, 'Diploma 1'),
(5, 'Diploma 2'),
(6, 'Diploma 3'),
(7, 'Diploma IV'),
(8, 'Strata 1'),
(9, 'Strata 2'),
(10, 'Strata 3');

-- --------------------------------------------------------

--
-- Table structure for table `penghasilan`
--

CREATE TABLE `penghasilan` (
  `ID` int(11) NOT NULL,
  `namapenghasilan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `penghasilan`
--

INSERT INTO `penghasilan` (`ID`, `namapenghasilan`) VALUES
(1, '< Rp. 500.000,-'),
(2, 'Rp. 500.000 s/d Rp. 1.000.000,-'),
(3, 'Rp. 1.000.000 s/d Rp. 2.000.000'),
(4, 'Rp. 2.000.000 s/d Rp. 3.000.000'),
(5, 'Rp. 3.000.000 s/d Rp. 4.000.000'),
(6, 'Rp. 4.000.000 s/d Rp.5.000.000'),
(7, '> Rp. 5.000.000,-'),
(8, 'Tidak Berpenghasilan');

-- --------------------------------------------------------

--
-- Table structure for table `ppdb2022_uggroups`
--

CREATE TABLE `ppdb2022_uggroups` (
  `GroupID` int(11) NOT NULL,
  `Label` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ppdb2022_uggroups`
--

INSERT INTO `ppdb2022_uggroups` (`GroupID`, `Label`) VALUES
(1, 'admin1');

-- --------------------------------------------------------

--
-- Table structure for table `ppdb2022_ugmembers`
--

CREATE TABLE `ppdb2022_ugmembers` (
  `UserName` varchar(300) NOT NULL,
  `GroupID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ppdb2022_ugmembers`
--

INSERT INTO `ppdb2022_ugmembers` (`UserName`, `GroupID`) VALUES
('admin01', -1);

-- --------------------------------------------------------

--
-- Table structure for table `ppdb2022_ugrights`
--

CREATE TABLE `ppdb2022_ugrights` (
  `TableName` varchar(300) NOT NULL,
  `GroupID` int(11) NOT NULL,
  `AccessMask` varchar(10) DEFAULT NULL,
  `Page` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ppdb2022_ugrights`
--

INSERT INTO `ppdb2022_ugrights` (`TableName`, `GroupID`, `AccessMask`, `Page`) VALUES
('admin_members', -1, 'ADESPIM', NULL),
('admin_rights', -1, 'ADESPIM', NULL),
('admin_users', -1, 'ADESPIM', NULL),
('calonppdb', -3, 'AS', ''),
('calonppdb', -1, 'ADESPIM', NULL),
('calonppdb', 1, 'AEDSP', ''),
('pekerjaan', -1, 'ADESPIM', NULL),
('pekerjaan', 1, 'AEDSP', ''),
('pendidikan', -1, 'ADESPIM', NULL),
('pendidikan', 1, 'AEDSP', ''),
('penghasilan', -1, 'ADESPIM', NULL),
('penghasilan', 1, 'AEDSP', ''),
('user', -1, 'ADESPIM', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `ID` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`ID`, `username`, `password`) VALUES
(2, 'admin01', '$2y$10$ryKQuyyWE6WOO5YEpflEbOloB7JrUYm6u536SqwL0igMo54aUQceS');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calonppdb`
--
ALTER TABLE `calonppdb`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `pekerjaan`
--
ALTER TABLE `pekerjaan`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `pendidikan`
--
ALTER TABLE `pendidikan`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `penghasilan`
--
ALTER TABLE `penghasilan`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ppdb2022_uggroups`
--
ALTER TABLE `ppdb2022_uggroups`
  ADD PRIMARY KEY (`GroupID`);

--
-- Indexes for table `ppdb2022_ugmembers`
--
ALTER TABLE `ppdb2022_ugmembers`
  ADD PRIMARY KEY (`UserName`(50),`GroupID`);

--
-- Indexes for table `ppdb2022_ugrights`
--
ALTER TABLE `ppdb2022_ugrights`
  ADD PRIMARY KEY (`TableName`(50),`GroupID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calonppdb`
--
ALTER TABLE `calonppdb`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pekerjaan`
--
ALTER TABLE `pekerjaan`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pendidikan`
--
ALTER TABLE `pendidikan`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `penghasilan`
--
ALTER TABLE `penghasilan`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `ppdb2022_uggroups`
--
ALTER TABLE `ppdb2022_uggroups`
  MODIFY `GroupID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
