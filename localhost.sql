-- phpMyAdmin SQL Dump
-- version 2.11.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 12, 2011 at 03:21 AM
-- Server version: 5.1.41
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sts`
--
CREATE DATABASE `sts` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `sts`;

-- --------------------------------------------------------

--
-- Stand-in structure for view `cancelledstsview`
--
CREATE TABLE `cancelledstsview` (
`grpEntered` int(5)
,`suppCurr` varchar(3)
,`stsComp` int(4)
,`stsDateEntered` datetime
,`dept` varchar(50)
,`suppName` varchar(25)
,`stsRefNo` int(8)
,`stsNo` int(7)
,`stsRemarks` text
,`stsAmt` decimal(12,2)
,`nbrApplication` int(2)
,`applyDate` date
,`brnShortDesc` varchar(12)
,`endDate` date
,`paymentMode` varchar(17)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `releasedstsview`
--
CREATE TABLE `releasedstsview` (
`grpEntered` int(5)
,`suppCurr` varchar(3)
,`stsComp` int(4)
,`stsDateEntered` datetime
,`dept` varchar(50)
,`suppName` varchar(25)
,`stsRefNo` int(8)
,`stsNo` int(7)
,`stsRemarks` text
,`stsAmt` decimal(12,2)
,`nbrApplication` int(2)
,`applyDate` date
,`brnShortDesc` varchar(12)
,`endDate` date
,`paymentMode` varchar(17)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `stsprintview`
--
CREATE TABLE `stsprintview` (
`grpEntered` varchar(50)
,`stsTag` char(1)
,`stsAmt` decimal(12,2)
,`stsComp` int(4)
,`stsDate` date
,`stsRefNo` int(8)
,`suppName` varchar(25)
,`stsStartNo` int(8)
,`stsEndNo` int(8)
,`nbrApplication` int(2)
,`applyDate` date
,`endDate` date
,`stsDept` int(2)
,`stsCls` int(2)
,`stsSubCls` int(2)
,`stsType` int(2)
,`suppCurr` varchar(3)
,`stsRemarks` text
,`Dept` varchar(50)
,`Class` varchar(50)
,`SClass` varchar(50)
,`paymentMode` varchar(22)
);
-- --------------------------------------------------------

--
-- Table structure for table `tblapbatch`
--

CREATE TABLE `tblapbatch` (
  `stsComp` int(3) NOT NULL COMMENT 'Company Code',
  `lastApBatch` int(3) NOT NULL COMMENT 'Last AP Batch Number Used',
  PRIMARY KEY (`stsComp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblapbatch`
--

INSERT INTO `tblapbatch` (`stsComp`, `lastApBatch`) VALUES
(1001, 1),
(1002, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblarbatch`
--

CREATE TABLE `tblarbatch` (
  `stsComp` int(3) NOT NULL COMMENT 'Company Code',
  `lastArBatch` int(3) NOT NULL COMMENT 'Last AP Batch Number Used',
  PRIMARY KEY (`stsComp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblarbatch`
--

INSERT INTO `tblarbatch` (`stsComp`, `lastArBatch`) VALUES
(1001, 0),
(1002, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblbranch`
--

CREATE TABLE `tblbranch` (
  `compCode` int(2) NOT NULL COMMENT 'Company Code',
  `brnCode` int(3) NOT NULL COMMENT 'Branch Code',
  `brnDesc` varchar(40) DEFAULT NULL COMMENT 'Branch Name/Description (max 40 ',
  `brnShortDesc` varchar(12) DEFAULT NULL COMMENT 'Branch Short Name/Desc.(max 12) char',
  `brnAddr1` varchar(40) DEFAULT NULL COMMENT 'Address -1 (max 40 chars)',
  `brnAddr2` varchar(40) DEFAULT NULL COMMENT 'Address - 2 (max 40 chars)',
  `brnAddr3` varchar(30) DEFAULT NULL COMMENT 'Address - 3  (max 30 chars)',
  `minWage` decimal(7,2) DEFAULT NULL COMMENT 'Minimum Wage of Branch Area',
  `brnSignatory` varchar(50) DEFAULT NULL COMMENT 'Name of Branch Signatory',
  `brnSignTitle` varchar(50) DEFAULT NULL COMMENT 'Title of Branch Signatory',
  `brnDefGrp` char(1) DEFAULT NULL COMMENT 'Default Group Code',
  `brnStat` char(1) DEFAULT NULL COMMENT 'Branch Status (‘D’-deleted)',
  PRIMARY KEY (`compCode`,`brnCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblbranch`
--

INSERT INTO `tblbranch` (`compCode`, `brnCode`, `brnDesc`, `brnShortDesc`, `brnAddr1`, `brnAddr2`, `brnAddr3`, `minWage`, `brnSignatory`, `brnSignTitle`, `brnDefGrp`, `brnStat`) VALUES
(1001, 202, 'PUREGOLD DUTY FREE CLARK', 'PDF CLARK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1001, 1001202, 'HEAD OFFICE DF CLARK ', 'PDF CLARK HO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1002, 201, 'PUREGOLD DUTY FREE SUBIC', 'PDF SUBIC', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1002, 1002201, 'HEAD OFFICE DF SUBIC', 'PDF SUBIC HO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblcancelledsts`
--

CREATE TABLE `tblcancelledsts` (
  `stsNo` int(8) NOT NULL COMMENT 'STS Number',
  `stsSeq` int(3) NOT NULL COMMENT 'Sequence Number',
  `stsRefNo` int(8) DEFAULT NULL COMMENT 'Reference Number',
  `stsComp` int(3) NOT NULL DEFAULT '0' COMMENT 'Company Code',
  `stsStrCode` int(3) DEFAULT NULL COMMENT 'Store Code',
  `suppCode` int(6) DEFAULT NULL COMMENT 'Supplier Code',
  `stsPaymentMode` char(1) DEFAULT NULL COMMENT 'Payment Mode (''C'' - Check, ''D'' - Deduction from payables)',
  `stsDept` int(2) DEFAULT NULL COMMENT 'STS Department',
  `stsCls` int(2) DEFAULT NULL COMMENT 'STS Class',
  `stsSubCls` int(2) DEFAULT NULL COMMENT 'STS Sub-Class',
  `grpEntered` int(3) DEFAULT NULL COMMENT 'Entered By Mdsg Group',
  `stsStrAmt` decimal(12,2) DEFAULT NULL COMMENT 'STS Total Amount',
  `uploadedAmt` decimal(12,2) DEFAULT NULL COMMENT 'Amount Uploaded as of Cancel Date',
  `applyDate` date DEFAULT NULL COMMENT 'First Date of Application (for Payment Mode ''Deduction'' Only)',
  `cancelledBy` varchar(25) DEFAULT NULL COMMENT 'Cancelled By',
  `cancelDate` date DEFAULT NULL COMMENT 'Date Cancelled',
  `cancelId` int(8) DEFAULT NULL COMMENT 'Reason for Cancellation',
  PRIMARY KEY (`stsNo`,`stsSeq`,`stsComp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcancelledsts`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblcanceltype`
--

CREATE TABLE `tblcanceltype` (
  `cancelId` int(8) NOT NULL AUTO_INCREMENT COMMENT 'Cancellation ID',
  `cancelDesc` varchar(50) DEFAULT NULL COMMENT 'Cancellation Description',
  `cancelStat` varchar(1) DEFAULT NULL COMMENT 'Cancellation Status (''A'' - Active; ''I'' - Inactive)',
  `createdBy` int(8) DEFAULT NULL COMMENT 'Created By',
  `dateAdded` date DEFAULT NULL COMMENT 'Date Added',
  PRIMARY KEY (`cancelId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `tblcanceltype`
--

INSERT INTO `tblcanceltype` (`cancelId`, `cancelDesc`, `cancelStat`, `createdBy`, `dateAdded`) VALUES
(37, 'for testing purposes', 'A', 1, '2011-12-08');

-- --------------------------------------------------------

--
-- Table structure for table `tblcompany`
--

CREATE TABLE `tblcompany` (
  `compCode` int(4) NOT NULL COMMENT 'Company Code',
  `compName` varchar(50) DEFAULT NULL COMMENT 'Company Name',
  `compShortName` varchar(10) DEFAULT NULL COMMENT 'Company Short Name - Initials',
  `compAdd1` varchar(50) DEFAULT NULL COMMENT 'Address-1',
  `compAdd2` varchar(50) DEFAULT NULL COMMENT 'Address-2',
  `compAdd3` varchar(50) DEFAULT NULL COMMENT 'Address-3',
  `compZip` int(6) DEFAULT NULL COMMENT 'Zip Code of Company',
  `compTin` varchar(15) DEFAULT NULL COMMENT 'Company TIN',
  `compAcct` char(3) DEFAULT NULL COMMENT 'G/L Company Account Code',
  `compStat` char(1) DEFAULT NULL COMMENT 'Status (Default = ‘ ‘',
  PRIMARY KEY (`compCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcompany`
--

INSERT INTO `tblcompany` (`compCode`, `compName`, `compShortName`, `compAdd1`, `compAdd2`, `compAdd3`, `compZip`, `compTin`, `compAcct`, `compStat`) VALUES
(6, 'PUREGOLD REALTY LEASING & MANAGEMENT CORPORATION', 'PG - RLTY ', '900 D. ROMUALDEZ ST.', NULL, NULL, NULL, '004-590-913-000', NULL, 'D'),
(101, 'PUREGOLD PRICE CLUB, INC.', 'PPCI', '312 SHAW BLVD.', 'MANDALUYONG CITY', NULL, NULL, NULL, NULL, 'D'),
(700, 'PUREGOLD JUNIOR SUPERMARKET, INC.', 'PG JR', 'C.V.  STARR AVENUE PHILAMLIFE VILLAGE', '', NULL, NULL, '007086674', NULL, 'D'),
(1001, 'PUREGOLD DUTY FREE  CLARK INC.', 'PDF CLARK', 'CM RECTO HI-WAY COR. P. KALAW ST.', NULL, NULL, NULL, '003937673', NULL, NULL),
(1002, 'PUREGOLD DUTY FREE SUBIC INC.', 'PDF SUBIC', 'BLDG. 1109 PALM ST.', 'SBFZ, OLONGAPO CITY', NULL, NULL, '204-137-024', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblmenu`
--

CREATE TABLE `tblmenu` (
  `modueID` int(4) NOT NULL AUTO_INCREMENT,
  `moduleName` varchar(50) NOT NULL,
  `label` varchar(50) NOT NULL,
  `page` varchar(50) NOT NULL,
  `moduleStat` char(1) NOT NULL,
  `menuOrder` int(4) NOT NULL,
  `moduleOrder` int(4) NOT NULL,
  PRIMARY KEY (`modueID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `tblmenu`
--

INSERT INTO `tblmenu` (`modueID`, `moduleName`, `label`, `page`, `moduleStat`, `menuOrder`, `moduleOrder`) VALUES
(1, 'Transactions', 'Regular STS', 'modules/transactions/regularSTS.php', 'A', 1, 1),
(2, 'Transactions', 'Promo Fund', 'modules/transactions/promoFund.php', 'A', 1, 2),
(3, 'Transactions', 'Rebates', 'modules/transactions/stsRebates.php', 'A', 1, 3),
(4, 'Transactions', 'Display Allowance Contract', 'modules/transactions/displayAllowance.php', 'A', 1, 4),
(5, 'Reports', 'Un-released Transactions', 'modules/reports/unreleasedTransactions.php', 'A', 2, 1),
(6, 'Reports', 'Released Transactions', 'modules/reports/releasedTransactions.php', 'A', 2, 2),
(7, 'Reports', 'Cancelled Transactions', 'modules/reports/cancelledTransactions.php', 'A', 2, 3),
(8, 'Inquiries', 'Supplier Transaction Slip', 'modules/inquiries/sts.php', 'A', 3, 1),
(9, 'Inquiries', 'Rebates', 'modules/inquiries/rebates.php', 'A', 3, 2),
(10, 'Inquiries', 'Display Allowance Contract', 'modules/inquiries/dispAllowanceContract.php', 'A', 3, 3),
(11, 'Maintenance', 'Product Group', 'modules/maintenance/productGroup.php', 'A', 4, 1),
(12, 'Maintenance', 'STS Hierarchy', 'modules/maintenance/stsHierarchy', 'A', 4, 2),
(13, 'Maintenance', 'Display Specifications', 'modules/maintenance/displaySpecs.php', 'A', 4, 3),
(14, 'Maintenance', 'Size Specifications', 'modules/maintenance/sizeSpecs.php', 'A', 4, 4),
(15, 'Maintenance', 'Cancellation Reasons', 'modules/maintenance/cancellationReasons.php', 'A', 4, 5),
(16, 'Reports', 'Released STS (Apply Date)', 'modules/reports/stsApplyDate.php', 'A', 2, 4),
(17, 'Admin', 'End of Day Processing', 'modules/admin/EOD.php', 'A', 5, 1),
(18, 'Maintenance', 'Change Password', 'modules/maintenance/changePassword.php', 'A', 4, 6),
(19, 'Admin', 'Users', 'modules/admin/users.php', 'A', 5, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tblprodgrp`
--

CREATE TABLE `tblprodgrp` (
  `prodID` int(8) NOT NULL,
  `prodName` varchar(50) DEFAULT NULL,
  `prodStat` varchar(1) DEFAULT NULL,
  `prodCrtdBy` int(8) DEFAULT NULL,
  `dateAdded` datetime DEFAULT NULL,
  PRIMARY KEY (`prodID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblprodgrp`
--

INSERT INTO `tblprodgrp` (`prodID`, `prodName`, `prodStat`, `prodCrtdBy`, `dateAdded`) VALUES
(1, 'FOOD', 'A', 1, '2011-11-19 00:00:00'),
(2, 'NON FOOD', 'A', 1, '2011-11-19 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tblrefno`
--

CREATE TABLE `tblrefno` (
  `compCode` int(5) NOT NULL COMMENT 'Company Code',
  `refNo` int(7) DEFAULT NULL COMMENT 'Reference No.',
  PRIMARY KEY (`compCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblrefno`
--

INSERT INTO `tblrefno` (`compCode`, `refNo`) VALUES
(1001, 2),
(1002, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblstsapply`
--

CREATE TABLE `tblstsapply` (
  `stsNo` int(8) NOT NULL COMMENT 'STS Number',
  `stsSeq` int(3) NOT NULL COMMENT 'Sequence Number',
  `stsRefNo` int(8) NOT NULL COMMENT 'Reference Number',
  `stsComp` int(3) NOT NULL COMMENT 'Company Code',
  `stsStrCode` int(3) NOT NULL COMMENT 'Store Code',
  `suppCode` int(6) NOT NULL COMMENT 'Supplier Code',
  `stsDept` int(2) DEFAULT NULL COMMENT 'STS Department',
  `stsCls` int(2) DEFAULT NULL COMMENT 'STS Class',
  `stsSubCls` int(2) DEFAULT NULL COMMENT 'STS Sub-Class',
  `grpEntered` int(3) DEFAULT NULL COMMENT 'Entered By Mdsg Group',
  `stsApplyAmt` decimal(12,2) DEFAULT NULL COMMENT 'STS Amount to Apply',
  `stsApplyDate` date DEFAULT NULL COMMENT 'Date to Apply STS',
  `stsActualDate` date DEFAULT NULL COMMENT 'Date of Actual Application',
  `stsPaymentMode` char(1) DEFAULT NULL COMMENT 'Payment Mode (''C'' - Check, ''D'' - Deducation from payables)',
  `status` char(1) DEFAULT NULL COMMENT 'Status (''O'', '''' - Open for Application, ''A'' - Applied)',
  `uploadDate` date DEFAULT NULL COMMENT 'Date Uploaded',
  PRIMARY KEY (`stsNo`,`stsSeq`,`stsComp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblstsapply`
--

INSERT INTO `tblstsapply` (`stsNo`, `stsSeq`, `stsRefNo`, `stsComp`, `stsStrCode`, `suppCode`, `stsDept`, `stsCls`, `stsSubCls`, `grpEntered`, `stsApplyAmt`, `stsApplyDate`, `stsActualDate`, `stsPaymentMode`, `status`, `uploadDate`) VALUES
(2, 1, 1, 1001, 202, 100008, 2, 2, 2, 1, '-12500.00', '2011-12-02', '2011-12-08', 'D', 'A', '2011-12-08'),
(2, 2, 1, 1001, 202, 100008, 2, 2, 2, 1, '-12500.00', '2012-01-02', NULL, 'D', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblstsdlyap`
--

CREATE TABLE `tblstsdlyap` (
  `stsNo` int(8) NOT NULL COMMENT 'STS Number',
  `stsSeq` int(3) NOT NULL COMMENT 'Sequence Number',
  `stsRefNo` int(8) NOT NULL COMMENT 'Reference Number',
  `stsComp` int(3) NOT NULL COMMENT 'Company Code',
  `stsStrCode` int(3) NOT NULL COMMENT 'Store Code',
  `suppCode` int(6) DEFAULT NULL COMMENT 'Supplier Code',
  `stsDept` int(2) DEFAULT NULL COMMENT 'STS Department',
  `stsCls` int(2) DEFAULT NULL COMMENT 'STS Class',
  `stsSubCls` int(2) DEFAULT NULL COMMENT 'STS Sub Class',
  `grpEntered` int(3) DEFAULT NULL COMMENT 'Entered By Mdsg Group',
  `stsApplyAmt` decimal(12,2) DEFAULT NULL COMMENT 'STS Amount for Store',
  `stsApplyDate` date DEFAULT NULL COMMENT 'Date to Apply STS',
  `stsActualDate` date DEFAULT NULL COMMENT 'Date of Actual Application',
  `dlyStatus` char(1) DEFAULT NULL COMMENT 'Status (''A'' - Applied)',
  `uploadDate` date DEFAULT NULL COMMENT 'Date Uploaded',
  `stsPaymentMode` char(1) DEFAULT NULL COMMENT 'Payment Mode',
  `uploadApRef` int(3) DEFAULT NULL COMMENT 'Upload Reference (this could be the voucher of Batch number)',
  `uploadApFile` varchar(50) DEFAULT NULL COMMENT 'File Name of AP Oracle Interface',
  PRIMARY KEY (`stsNo`,`stsSeq`,`stsComp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblstsdlyap`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblstsdlyaphist`
--

CREATE TABLE `tblstsdlyaphist` (
  `stsNo` int(8) NOT NULL COMMENT 'STS Number',
  `stsSeq` int(3) NOT NULL COMMENT 'Sequence Number',
  `stsRefNo` int(8) NOT NULL COMMENT 'Reference Number',
  `stsComp` int(3) NOT NULL COMMENT 'Company Code',
  `stsStrCode` int(3) NOT NULL COMMENT 'Store Code',
  `suppCode` int(6) DEFAULT NULL COMMENT 'Supplier Code',
  `stsDept` int(2) DEFAULT NULL COMMENT 'STS Department',
  `stsCls` int(2) DEFAULT NULL COMMENT 'STS Class',
  `stsSubCls` int(2) DEFAULT NULL COMMENT 'STS Sub Class',
  `grpEntered` int(3) DEFAULT NULL COMMENT 'Entered By Mdsg Group',
  `stsApplyAmt` decimal(12,2) DEFAULT NULL COMMENT 'STS Amount for Store',
  `stsApplyDate` date DEFAULT NULL COMMENT 'Date to Apply STS',
  `stsActualDate` date DEFAULT NULL COMMENT 'Date of Actual Application',
  `dlyStatus` char(1) DEFAULT NULL COMMENT 'Status (''A'' - Applied)',
  `uploadDate` date DEFAULT NULL COMMENT 'Date Uploaded',
  `stsPaymentMode` char(1) DEFAULT NULL COMMENT 'Payment Mode',
  `uploadApRef` int(3) DEFAULT NULL COMMENT 'Upload Reference (this could be the voucher of Batch number)',
  `uploadApFile` varchar(50) DEFAULT NULL COMMENT 'File Name of AP Oracle Interface',
  PRIMARY KEY (`stsNo`,`stsSeq`,`stsComp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblstsdlyaphist`
--

INSERT INTO `tblstsdlyaphist` (`stsNo`, `stsSeq`, `stsRefNo`, `stsComp`, `stsStrCode`, `suppCode`, `stsDept`, `stsCls`, `stsSubCls`, `grpEntered`, `stsApplyAmt`, `stsApplyDate`, `stsActualDate`, `dlyStatus`, `uploadDate`, `stsPaymentMode`, `uploadApRef`, `uploadApFile`) VALUES
(2, 1, 1, 1001, 202, 100008, 2, 2, 2, 1, '-12500.00', '2011-12-02', NULL, 'A', '2011-12-08', 'D', 1, 'DC120811_092734.401');

-- --------------------------------------------------------

--
-- Table structure for table `tblstsdlyar`
--

CREATE TABLE `tblstsdlyar` (
  `stsNo` int(8) NOT NULL COMMENT 'STS Number',
  `stsSeq` int(3) NOT NULL COMMENT 'Sequence Number',
  `stsRefNo` int(8) NOT NULL COMMENT 'Reference Number',
  `stsComp` int(3) NOT NULL COMMENT 'Company Code',
  `stsStrCode` int(3) NOT NULL COMMENT 'Store Code',
  `suppCode` int(6) DEFAULT NULL COMMENT 'Supplier Code',
  `stsDept` int(2) DEFAULT NULL COMMENT 'STS Department',
  `stsCls` int(2) DEFAULT NULL COMMENT 'STS Class',
  `stsSubCls` int(2) DEFAULT NULL COMMENT 'STS Sub Class',
  `grpEntered` int(3) DEFAULT NULL COMMENT 'Entered By Mdsg Group',
  `stsApplyAmt` decimal(12,2) DEFAULT NULL COMMENT 'STS Amount for Store',
  `stsApplyDate` date DEFAULT NULL COMMENT 'Date to Apply STS',
  `stsActualDate` date DEFAULT NULL COMMENT 'Date of Actual Application',
  `dlyStatus` char(1) DEFAULT NULL COMMENT 'Status (''A'' - Applied)',
  `uploadDate` date DEFAULT NULL COMMENT 'Date Uploaded',
  `stsPaymentMode` char(1) DEFAULT NULL COMMENT 'Payment Mode',
  `uploadArRef` int(3) DEFAULT NULL COMMENT 'Upload Reference (this could be the voucher of Batch number)',
  `uploadArFile` varchar(50) DEFAULT NULL COMMENT 'File Name of AP Oracle Interface',
  PRIMARY KEY (`stsNo`,`stsSeq`,`stsComp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblstsdlyar`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblstsdlyarhist`
--

CREATE TABLE `tblstsdlyarhist` (
  `stsNo` int(8) NOT NULL COMMENT 'STS Number',
  `stsSeq` int(3) NOT NULL COMMENT 'Sequence Number',
  `stsRefNo` int(8) NOT NULL COMMENT 'Reference Number',
  `stsComp` int(3) NOT NULL COMMENT 'Company Code',
  `stsStrCode` int(3) NOT NULL COMMENT 'Store Code',
  `suppCode` int(6) DEFAULT NULL COMMENT 'Supplier Code',
  `stsDept` int(2) DEFAULT NULL COMMENT 'STS Department',
  `stsCls` int(2) DEFAULT NULL COMMENT 'STS Class',
  `stsSubCls` int(2) DEFAULT NULL COMMENT 'STS Sub Class',
  `grpEntered` int(3) DEFAULT NULL COMMENT 'Entered By Mdsg Group',
  `stsApplyAmt` decimal(12,2) DEFAULT NULL COMMENT 'STS Amount for Store',
  `stsApplyDate` date DEFAULT NULL COMMENT 'Date to Apply STS',
  `stsActualDate` date DEFAULT NULL COMMENT 'Date of Actual Application',
  `dlyStatus` char(1) DEFAULT NULL COMMENT 'Status (''A'' - Applied)',
  `uploadDate` date DEFAULT NULL COMMENT 'Date Uploaded',
  `stsPaymentMode` char(1) DEFAULT NULL COMMENT 'Payment Mode',
  `uploadArRef` int(3) DEFAULT NULL COMMENT 'Upload Reference (this could be the voucher of Batch number)',
  `uploadArFile` varchar(50) DEFAULT NULL COMMENT 'File Name of AP Oracle Interface',
  PRIMARY KEY (`stsNo`,`stsSeq`,`stsComp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblstsdlyarhist`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblstsdtl`
--

CREATE TABLE `tblstsdtl` (
  `stsRefNo` int(8) NOT NULL COMMENT 'Reference Number',
  `stsComp` int(8) NOT NULL,
  `stsStrCode` int(8) NOT NULL COMMENT 'Store Code',
  `stsStrAmt` decimal(12,2) NOT NULL COMMENT 'STS Amount for Store',
  `stsNo` int(7) DEFAULT NULL COMMENT 'STS Number(Note: the value is initially set to zeroes upon entry, tagged upon approval',
  `dtlStatus` char(1) DEFAULT NULL COMMENT 'Status (''C'' - Cancelled, ''A'' - Applied, ''O'' - Open',
  PRIMARY KEY (`stsStrCode`,`stsRefNo`,`stsComp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblstsdtl`
--

INSERT INTO `tblstsdtl` (`stsRefNo`, `stsComp`, `stsStrCode`, `stsStrAmt`, `stsNo`, `dtlStatus`) VALUES
(1, 1001, 202, '25000.00', 2, 'A'),
(2, 1001, 202, '12000.00', 1, 'A');

-- --------------------------------------------------------

--
-- Table structure for table `tblstshdr`
--

CREATE TABLE `tblstshdr` (
  `stsRefNo` int(8) NOT NULL COMMENT 'STS Reference Number',
  `stsComp` int(4) NOT NULL,
  `suppCode` int(6) DEFAULT NULL COMMENT 'Supplier Code (valid value in tblSuppliers)',
  `stsDept` int(2) DEFAULT NULL COMMENT 'STS Department',
  `stsCls` int(2) DEFAULT NULL COMMENT 'STS Class',
  `stsSubCls` int(2) DEFAULT NULL COMMENT 'STS Sub-Class',
  `stsAmt` decimal(12,2) DEFAULT NULL COMMENT 'STS Amount',
  `stsRemarks` text COMMENT 'STS Remarks',
  `stsPaymentMode` char(1) DEFAULT NULL COMMENT 'Payment Mode ''C'' - check, ''D'' - Deduction for payables',
  `stsTerms` int(3) DEFAULT NULL COMMENT 'Terms for Payment Mode Check',
  `nbrApplication` int(2) DEFAULT NULL COMMENT 'Number of Applications/Payments',
  `applyDate` date DEFAULT NULL COMMENT 'First Date of Application',
  `stsStartNo` int(8) DEFAULT NULL COMMENT 'Starting STS Number',
  `stsEndNo` int(8) DEFAULT NULL COMMENT 'Ending STS Number',
  `stsPrtTag` char(1) DEFAULT NULL COMMENT 'STS Print Tag (''Y'' - STS already printer)',
  `stsApplyTag` char(1) DEFAULT NULL COMMENT 'Apply Tag (''Y'' - Application Started)',
  `stsEnteredBy` varchar(25) DEFAULT NULL COMMENT 'Sts Entered By',
  `stsDateEntered` datetime DEFAULT NULL COMMENT 'Date / Time Entered',
  `grpEntered` int(5) DEFAULT NULL COMMENT 'Entered By Mdsg Group',
  `approvedBy` varchar(25) DEFAULT NULL COMMENT 'Approved By',
  `dateApproved` date DEFAULT NULL COMMENT 'Date Approved',
  `stsPrintedBy` varchar(25) DEFAULT NULL COMMENT 'STS Printed By',
  `stsDatePrinted` datetime DEFAULT NULL COMMENT 'STS Print Date',
  `stsReprintedBy` varchar(25) DEFAULT NULL COMMENT 'STS Reprinted By',
  `stsReprintedDate` datetime DEFAULT NULL COMMENT 'STS Reprinted Date',
  `stsTag` char(1) DEFAULT NULL COMMENT 'STS Tag (''Y'' - STS tagged, ''N'','''' - no STS',
  `stsDate` date DEFAULT NULL COMMENT 'STS Date',
  `applyTagDate` date DEFAULT NULL COMMENT 'Date Application Trans are created',
  `stsStat` char(1) DEFAULT NULL COMMENT 'STS Status (''C'' - Cancelled, ''H'' - held, ''R'' - Released,''O'','''' - Open, Default)',
  `stsType` int(2) DEFAULT NULL COMMENT '1 - Regular, 2 - Rebates, 3 - Promo Fund, 4 - Display Allowance',
  `suppCurr` varchar(3) DEFAULT NULL,
  `cancelId` int(8) DEFAULT NULL,
  PRIMARY KEY (`stsRefNo`,`stsComp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblstshdr`
--

INSERT INTO `tblstshdr` (`stsRefNo`, `stsComp`, `suppCode`, `stsDept`, `stsCls`, `stsSubCls`, `stsAmt`, `stsRemarks`, `stsPaymentMode`, `stsTerms`, `nbrApplication`, `applyDate`, `stsStartNo`, `stsEndNo`, `stsPrtTag`, `stsApplyTag`, `stsEnteredBy`, `stsDateEntered`, `grpEntered`, `approvedBy`, `dateApproved`, `stsPrintedBy`, `stsDatePrinted`, `stsReprintedBy`, `stsReprintedDate`, `stsTag`, `stsDate`, `applyTagDate`, `stsStat`, `stsType`, `suppCurr`, `cancelId`) VALUES
(1, 1001, 100008, 2, 2, 2, '25000.00', 'for testing purposes', 'D', 0, 2, '2011-12-02', 1, 2, NULL, 'Y', '1', '2011-12-07 17:13:28', 1, '1', '2011-12-08', '1', '2011-12-08 16:01:40', '1', '2011-12-08 16:02:55', 'Y', '2011-12-08', '2011-12-08', 'R', 1, 'PHP', NULL),
(2, 1001, 100775, 1, 3, 3, '12000.00', 'for testing', 'D', 0, 1, '2011-12-02', 1, 1, NULL, NULL, '1', '2011-12-07 17:15:40', 1, '1', '2011-12-08', '1', '2011-12-08 15:59:42', NULL, NULL, 'Y', '2011-12-08', NULL, 'C', 1, 'PHP', 37);

-- --------------------------------------------------------

--
-- Table structure for table `tblstsno`
--

CREATE TABLE `tblstsno` (
  `compCode` int(5) NOT NULL COMMENT 'Company Code',
  `stsNo` int(7) DEFAULT NULL COMMENT 'STS Control No.',
  PRIMARY KEY (`compCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblstsno`
--

INSERT INTO `tblstsno` (`compCode`, `stsNo`) VALUES
(1001, 2),
(1002, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblststranstype`
--

CREATE TABLE `tblststranstype` (
  `compCode` int(5) NOT NULL COMMENT 'Company Code',
  `stsTransTypeId` int(8) NOT NULL AUTO_INCREMENT COMMENT 'STS transaction type Id',
  `stsTransTypeLvl` int(1) DEFAULT NULL COMMENT 'STS transactions type level view (1 - Department; 2 - Class; 3 - Sub-class)',
  `stsTransTypeDept` int(3) DEFAULT NULL COMMENT 'STS Transaction Type Department',
  `stsTransTypeClass` int(3) DEFAULT NULL COMMENT 'STS Transaction Type Class',
  `stsTransTypeSClass` int(3) DEFAULT NULL COMMENT 'STS Transaction Type Sub-Class',
  `stsTransTypeName` varchar(50) DEFAULT NULL COMMENT 'STS Transaction Type Name',
  `stsGL` varchar(10) DEFAULT NULL COMMENT 'STS GL Account',
  `stsStat` char(1) DEFAULT NULL COMMENT 'STS Transactions Type Status (''A'' - Active; ''I'' - Inactive)',
  `stsTransGrp` int(1) DEFAULT NULL,
  `createdBy` int(8) DEFAULT NULL COMMENT 'Created By',
  `dateAdded` date DEFAULT NULL COMMENT 'Date Added',
  PRIMARY KEY (`stsTransTypeId`,`compCode`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=65 ;

--
-- Dumping data for table `tblststranstype`
--

INSERT INTO `tblststranstype` (`compCode`, `stsTransTypeId`, `stsTransTypeLvl`, `stsTransTypeDept`, `stsTransTypeClass`, `stsTransTypeSClass`, `stsTransTypeName`, `stsGL`, `stsStat`, `stsTransGrp`, `createdBy`, `dateAdded`) VALUES
(201, 1, 1, 1, 0, 0, 'LISTING FEE', NULL, 'A', 1, NULL, NULL),
(201, 2, 1, 2, 0, 0, 'SUPPLIER PROMO', NULL, 'A', 1, NULL, NULL),
(201, 3, 1, 3, 0, 0, 'CHARGES SALES INVOICE', NULL, 'A', 1, NULL, NULL),
(201, 4, 1, 4, 0, 0, 'REBATE', NULL, 'A', 1, NULL, NULL),
(201, 5, 1, 5, 0, 0, 'ADMINISTRATIVE CHARGES\r\n', NULL, 'A', 1, NULL, NULL),
(201, 6, 1, 6, 0, 0, 'NON-PUREGOLD FUND\r\n', NULL, 'A', 1, NULL, NULL),
(201, 7, 1, 7, 0, 0, 'SUPPLY CHAIN FEE\r\n', NULL, 'A', 1, NULL, NULL),
(201, 8, 2, 1, 1, 0, 'CASH / CHEQUE PAYMENTS\r\nCASH / CHEQUE PAYMENTS\r\n', NULL, 'A', 1, NULL, NULL),
(201, 9, 2, 1, 2, 0, 'WIRE TRANSFER\r\n', NULL, 'A', 1, NULL, NULL),
(201, 10, 2, 1, 3, 0, 'DISCOUNTS\r\n', NULL, 'A', 1, NULL, NULL),
(201, 11, 2, 1, 4, 0, 'FREE GOODS\r\n', NULL, 'A', 1, NULL, NULL),
(201, 12, 3, 1, 1, 1, 'CASH / CHEQUE PAYMENTS\r\n', '605006', 'A', 1, NULL, NULL),
(201, 13, 3, 1, 2, 2, 'WIRE TRANSFER\r\n', '605006', 'A', 1, NULL, NULL),
(201, 14, 3, 1, 3, 3, 'DISCOUNTS\r\n', '605006', 'A', 1, NULL, NULL),
(201, 15, 3, 1, 4, 4, 'FREE GOODS\r\n', '605006', 'A', 1, NULL, NULL),
(201, 16, 2, 2, 1, 0, 'NATIONAL PROMOTION\r\n', NULL, 'A', 1, NULL, NULL),
(201, 17, 3, 2, 1, 1, 'JOINING FEE\r\n', '605004', 'A', 1, NULL, NULL),
(201, 18, 3, 2, 1, 2, 'REBATES / DISCOUNTS\r\n', '605004', 'A', 1, NULL, NULL),
(201, 19, 3, 2, 1, 3, 'PRINT MEDIA\r\n', '605004', 'A', 1, NULL, NULL),
(201, 20, 2, 2, 2, 0, 'STORE PROMOTION\r\n', NULL, 'A', 1, NULL, NULL),
(201, 21, 3, 2, 2, 1, 'SAMPLING DEMO\r\n', '605004', 'A', 1, NULL, NULL),
(201, 22, 3, 2, 2, 2, 'DROP BOX\r\n', '605004', 'A', 1, NULL, NULL),
(201, 23, 3, 2, 2, 3, 'PUSH GIRL\r\n', '605004', 'A', 1, NULL, NULL),
(201, 24, 3, 2, 2, 4, 'AUDIO & PRINT ADS\r\n', '605004', 'A', 1, NULL, NULL),
(201, 25, 2, 2, 3, 0, 'ADVERTISING PROMO\r\n', NULL, 'A', 1, NULL, NULL),
(201, 26, 3, 2, 3, 1, 'ACT MEDIA\r\n', '605004', 'A', 1, NULL, NULL),
(201, 27, 3, 2, 3, 2, 'BILL BOARDS\r\n', '605004', 'A', 1, NULL, NULL),
(201, 28, 2, 3, 1, 0, 'IN-STORE PROMO\r\n', NULL, 'A', 1, NULL, NULL),
(201, 29, 3, 3, 1, 1, 'THEMATIC PROMOTION\r\n', '030100', 'A', 1, NULL, NULL),
(201, 30, 3, 3, 1, 2, '3-DAY SALE\r\n', '030100', 'A', 1, NULL, NULL),
(201, 31, 3, 3, 1, 3, 'ANNIVERSARY SUPPORT\r\n', '030100', 'A', 1, NULL, NULL),
(201, 32, 3, 3, 1, 4, 'OTHER PROMO\r\n', '030100', 'A', 1, NULL, NULL),
(201, 33, 2, 3, 2, 0, 'OTHER ACTIVITIES\r\n', NULL, 'A', 1, NULL, NULL),
(201, 34, 3, 3, 2, 1, 'OTHER ACTIVITIES\r\n', '030100', 'A', 1, NULL, NULL),
(201, 35, 2, 4, 1, 0, 'PRICE CHANGE\r\n', NULL, 'A', 1, NULL, NULL),
(201, 36, 3, 4, 1, 1, 'SUPPLIER INITIATED\r\n', '605003', 'A', 1, NULL, NULL),
(201, 37, 3, 4, 1, 2, 'PG INITIATED\r\n', '605003', 'A', 1, NULL, NULL),
(201, 38, 2, 4, 2, 0, 'VOLUME INCENTIVE\r\n', NULL, 'A', 1, NULL, NULL),
(201, 39, 3, 4, 2, 1, 'DELIVERIES\r\n', '605002', 'A', 1, NULL, NULL),
(201, 40, 3, 4, 2, 2, 'SALES\r\n', '605002', 'A', 1, NULL, NULL),
(201, 41, 2, 5, 1, 0, 'STORE CHARGES\r\n', NULL, 'A', 1, NULL, NULL),
(201, 42, 3, 5, 1, 1, 'SUPPLIES / SIGNAGE\r\n', '747104', 'A', 1, NULL, NULL),
(201, 43, 3, 5, 1, 2, 'TELEPHONE / FAX\r\n', '760104', 'A', 1, NULL, NULL),
(201, 44, 3, 5, 1, 3, 'ELECTRICITY\r\n', '770104', 'A', 1, NULL, NULL),
(201, 45, 3, 5, 1, 4, 'DAMAGED GOODS\r\n', '990009', 'A', 1, NULL, NULL),
(201, 46, 3, 5, 1, 5, 'MERCHANDISING FIXTURES & COLLATERALS\r\n', '990009', 'A', 1, NULL, NULL),
(201, 47, 3, 5, 1, 6, 'MERCHANDISERS / REFILLERS\r\n', '990009', 'A', 1, NULL, NULL),
(201, 48, 2, 5, 2, 0, 'REIMBURSEMENT\r\n', NULL, 'A', 1, NULL, NULL),
(201, 49, 3, 5, 2, 1, 'SIGNAGES\r\n', '030100', 'A', 1, NULL, NULL),
(201, 50, 3, 5, 2, 2, 'STORE EQUIPMENT\r\n', '030100', 'A', 1, NULL, NULL),
(201, 51, 3, 5, 2, 3, 'BALLOONS\r\n', '030100', 'A', 1, NULL, NULL),
(201, 52, 3, 5, 2, 4, 'FOOD & BEVERAGE\r\n', '030100', 'A', 1, NULL, NULL),
(201, 53, 3, 5, 2, 5, 'FUEL & GAS\r\n', '030100', 'A', 1, NULL, NULL),
(201, 54, 3, 5, 2, 6, 'INCIDENT\r\n', '030100', 'A', 1, NULL, NULL),
(201, 55, 2, 6, 1, 0, 'EMPLOYEES SOLICITATION\r\n', NULL, 'A', 1, NULL, NULL),
(201, 56, 3, 6, 1, 1, 'SPORTS FEST\r\n', '370007', 'A', 1, NULL, NULL),
(201, 57, 3, 6, 1, 2, 'BUSINESS CONFERENCE (TEAM BUILDING)\r\n', '370007', 'A', 1, NULL, NULL),
(201, 58, 2, 7, 1, 0, 'SERVICES\r\n', NULL, 'A', 1, NULL, NULL),
(201, 59, 3, 7, 1, 1, 'BUNDLING FEE\r\n', '980002', 'A', 1, NULL, NULL),
(201, 60, 3, 7, 1, 2, 'STICKERING FEE\r\n', '980003', 'A', 1, NULL, NULL),
(201, 61, 3, 7, 1, 3, 'RETAGGING FEE\r\n', '980004', 'A', 1, NULL, NULL),
(201, 62, 3, 7, 1, 4, 'STORAGE FEE\r\n', '980005', 'A', 1, NULL, NULL),
(201, 63, 1, 8, 0, 0, 'PROMO FUND', '370003', 'A', 3, NULL, NULL),
(201, 64, 1, 9, 0, 0, 'DISPLAY ALLOWANCE', '605005', 'A', 4, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblsuppliers`
--

CREATE TABLE `tblsuppliers` (
  `suppCode` bigint(20) NOT NULL,
  `suppName` varchar(25) DEFAULT NULL,
  `suppAddr1` varchar(25) DEFAULT NULL,
  `suppAddr2` varchar(25) DEFAULT NULL,
  `suppAddr3` varchar(25) DEFAULT NULL,
  `suppZip` bigint(20) DEFAULT NULL,
  `suppTel` varchar(10) DEFAULT NULL,
  `suppFax` varchar(10) DEFAULT NULL,
  `suppTerms` bigint(20) DEFAULT NULL,
  `suppType` varchar(2) DEFAULT NULL,
  `suppComm` decimal(5,2) DEFAULT NULL,
  `suppCurr` varchar(3) DEFAULT NULL,
  `suppStat` varchar(1) DEFAULT NULL,
  `cntctPrson` varchar(50) DEFAULT NULL,
  `cntctPrsnAdd` varchar(100) DEFAULT NULL,
  `oracleTag9` char(1) DEFAULT NULL,
  `suppTaxType` char(1) DEFAULT NULL,
  `oracleTag9B` char(1) DEFAULT NULL,
  `MINOR_ACCOUNT` varchar(50) DEFAULT NULL,
  `taxCode` char(2) DEFAULT NULL,
  PRIMARY KEY (`suppCode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblsuppliers`
--

INSERT INTO `tblsuppliers` (`suppCode`, `suppName`, `suppAddr1`, `suppAddr2`, `suppAddr3`, `suppZip`, `suppTel`, `suppFax`, `suppTerms`, `suppType`, `suppComm`, `suppCurr`, `suppStat`, `cntctPrson`, `cntctPrsnAdd`, `oracleTag9`, `suppTaxType`, `oracleTag9B`, `MINOR_ACCOUNT`, `taxCode`) VALUES
(100004, 'JOYCE & DIANA INTL INC', '8006 PIONEER CENTRE BLDG ', ' ', 'art', 0, '6340459491', '6871157', 2001, 'CO', '25.00', 'PHP', 'A', '1', '1', 'Y', 'P', 'Y', '0082', NULL),
(100006, 'UNITED KAIPARA DAIRIES CO', 'P.O. BOX 6424, DUBAI', 'UNITED ARAB EMIRATES', ' ', 0, '3382133', '3383099', 1004, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100007, 'HARMONY TRADING', '80 M. DEL PILAR ST., SAN ', ' ', ' ', 0, '372-6615/1', '372-6578', 23, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100008, 'HUASANWAN FOODMART, INC.', '637-639 STA. ELENA STREET', ' ', ' ', 0, '411-92-49', '361-97-96', 23, 'RG', '0.00', 'PHP', 'A', 'NORMA', '637-639 STA. ELENA ST. BINONDO MANILA', 'Y', 'C', 'Y', NULL, NULL),
(100009, 'OMNI PACIFIC COMPANY, INC', '2499 NORTH MAIN STREET,', 'SUITE 250 WALNUT CREEK,', 'CA 94597-7163 U.S.A.', 0, '9330695', '9330691', 1019, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100012, 'HOCKSENG FOOD PTE LTD', '320 JALAN BOON LAY', 'SINGAPORE 619525', ' ', 0, '67782282', '67790186', 1003, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100014, 'EVERWELL PTE LTD', '30B QUALITY ROAD', 'SINGAPORE 618826', ' ', 0, '62682878', '62686824', 1000, 'RG', '0.00', 'USD', 'A', 'MS. MELIZA', 'SINGAPORE', 'Y', 'C', 'Y', NULL, NULL),
(100017, 'KROMOPEAK INNOVATIONS, IN', '37 C. JOSE ABAD SANTOS ST', 'LITTLE BAGUIO, SAN JUAN', 'METRO MANILA, PHILIPPINES', 0, '727-33-45', '727-33-45', 23, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100018, 'ALTRIX INTERNATIONAL INC.', '213 214 ROBELLE MANSION 8', 'JP', 'PHILIPPINES', 0, '899-7388', '890-3421', 1000, 'RG', '0.00', 'PHP', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100020, 'ACE CANNING CORPORATIONS', 'LOT 33-37, LENGKUK KELULI', 'KAWASAN PERINDUSTRIAN BUK', 'SELATAN, SELONGOR DARUL E', 0, '33622828', '33622929', 1003, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100022, 'WILMINGTON IMEX, INC.', '#5 ALFONSO XIII STREET', 'SAN JUAN MM', 'PHILIPPINES', 0, '721-87-08', '722-49-91', 1016, 'RG', '0.00', 'PHP', 'A', 'MR. JUN POLOMATA', 'SAN JUAN METRO MANILA', 'Y', 'C', 'Y', NULL, NULL),
(100023, 'CHUNG SHUN TRADING CO.', 'FLAT 3207 32/F, BLOCK A', 'KAI TIN TOWER, 59 KAI TIN', 'KOWLOON, HONGKONG', 0, '9288883', '9210816', 1023, 'RG', '0.00', 'AUD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100024, 'SERMASISON CORPORATION ', '117 SCOUT FUENTABELLA ST.', 'QUEZON CITY', ' ', 0, '9288883', '9210816', 23, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100025, 'FLY ACE CORPORATION', 'UNIT G-17 CHINATOWN STEEL', '531', 'BINONDO,', 0, '242-6921 T', '243-7032', 1016, 'RG', '0.00', 'PHP', 'A', 'MR. ALVIN RAMOS', 'N/A', 'Y', 'C', 'Y', NULL, NULL),
(100029, 'THE MERRY COOKS, INC.', '#286 E. RODRIGUEZ AVE. ', 'QUEZON CITY', 'PHILIPPINES', 0, '743-45-68/', '743-49-57/', 1008, 'RG', '0.00', 'PHP', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100030, 'LOTTE CHILSUNG BEVERAGE C', '50-2 JAMWON-DONG SEOCHO-K', 'SEOUL SOUTH KOREA', ' ', 0, '34799434', '62349004', 1025, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100031, 'SCANASIA OVERSEAS, INC.', '3/F MOLAVE BUILDING', 'DON', 'MAKATI', 0, '815-01-23', '818-14-67', 1016, 'RG', '0.00', 'PHP', 'A', 'MS. HELEN ERGINO', 'N/A', 'Y', 'C', 'Y', NULL, NULL),
(100033, 'ARPEL INTL MARKETING CORP', '# 13 DE JESUS ST. SFDM, Q', ' ', ' ', 0, '373-1624', '373-1627', 1016, 'RG', '0.00', 'PHP', 'A', 'MS. JOYCE PE?ARANDA', 'QUEZON CITY', 'Y', 'C', 'Y', NULL, NULL),
(100034, 'MALEE BANGKOK CO., LTD', '470 MOO 1 SUKHUMVIT ROAD,', 'MUANG, SAMUKPRAKAN', '10280 THAILAND', 0, '3231111', '3231122', 1005, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100036, 'TUNG PO COMPANY', 'FLAT A 19/F., BANK TOWER ', ' ', ' ', 0, '363-63-05', '364-62-57', 1009, 'RG', '0.00', 'USD', 'A', 'GERALDINE DY', 'QUEZON CITY', 'Y', 'C', 'Y', NULL, NULL),
(100037, 'GLOBAL STRATEGIC PARTNERS', '131 B CORDILLERA ST.', 'STA MESA HEIGHTS, QUEZON ', 'PHILIPPINES', 0, '415-3089', '414-6177', 23, 'RG', '0.00', 'PHP', 'A', 'MR. VINCENT PICA?A', 'QUEZON CITY', 'Y', 'C', 'Y', NULL, NULL),
(100039, 'SUPER COFFEE CORPORATION ', '2 SENOKO SOUTH ROAD', 'SUPER INDUSTRIAL BUILDING', 'SINGAPORE 758096', 0, '7533088', '7537833', 1021, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100040, 'BENBY ENTERPRISES, INC.', '1037 BANAWE ST., BRGY MAN', ' ', ' ', 0, '363-63-05', '364-62-57', 1016, 'RG', '0.00', 'PHP', 'A', 'MS. FEA SANTIAGO', 'QUEZON CITY', 'Y', 'C', 'Y', NULL, NULL),
(100042, 'SUREE INTERFOODS CO., LTD', '11/13 MOO 3, TAMBOL BANBO', ' ', ' ', 0, '839-870', '419-448', 1034, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100046, 'GLOBAL MERCHANDISING CORP', '2525 16TH STREET, SUITE 3', ' ', ' ', 0, '415-285-83', '415-641-09', 1006, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100048, 'TOMMY LO, INC.', '712 BANCROFT ROAD, #452 W', ' ', ' ', 0, '001-280-99', '001-925-28', 1024, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100051, 'PETERSON PARTS TRADING,IN', '174 G. ARANETA AVENUE,', 'QUEZON CITY', ' ', 0, '30', '7261315', 23, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100054, 'GOODWAY INTL TRADING CORP', 'RM 503 CULMAT BLDG 1270-1', ' ', ' ', 0, '725-4758', '725-3979', 1016, 'RG', '0.00', 'PHP', 'A', 'MR. FRANCIS BALITON', 'QUEZON CITY', 'Y', 'C', 'Y', NULL, NULL),
(100056, 'FEDERATED DISTRIBUTORS', 'FDI BLDG. QUEENSWAY AVE C', 'VENECIA DE LEON ST. BARRI', 'PARANAQUE CITY, PHILIPPIN', 0, '851-7020', '852-8205', 1016, 'RG', '0.00', 'PHP', 'A', 'MS. JEAN LAGUNILLA', 'PARA?AQUE CITY', 'Y', 'C', 'Y', NULL, NULL),
(100057, 'IMPRESSIONS IMPEX INTL CO', '# 1000, KM 19.8, EAST SER', 'CUPANG MUNTINLUPA CITY', ' ', 0, ' ', ' ', 23, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100058, 'LAM SOON EDIBLE OILS SDN.', 'WISMA DLS, NO.6, JALAN JU', 'HICOM-GLENMARIE INDUSTRIA', 'P.O. BOX 7478, 40716 SHAH', 0, '60-3-7882-', '60-3-5569-', 1019, 'RG', '0.00', 'USD', 'A', 'MR. KM CHEAH', 'N/A', 'Y', 'C', 'Y', NULL, NULL),
(100059, 'JNL88 MARKETING', 'UNIT 407 CONTINENTAL COUR', 'GREENHILLS, SAN JUAN', 'METRO, MANILA', 0, '41-8838', '330-7608', 23, 'RG', '0.00', 'PHP', 'A', 'MR. JEFFREY', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100061, 'KIKKOMAN CORPORATION', '2-1-1, NISHI-SHINBASI, MI', ' ', ' ', 0, '635-05-59', '633-94-63', 22, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100063, 'CYA INDUSTRIES, INC. (GE)', '#103 MERCEDES AVE., BRGY.', ' ', ' ', 1800, '643-3456 L', '641-9896', 2001, 'CO', '8.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', '0077', NULL),
(100065, 'LONG DISC INTERNATIONAL', '#543 ELCANO ST. BINONDO M', ' ', ' ', 0, '2416510', '2424079', 1024, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100066, 'SOLID FIVE DIST. INC.', '1037 BANAWE ST. QC', ' ', ' ', 0, '366-6075', '413-5919', 1016, 'RG', '0.00', 'PHP', 'A', 'MS. GINA P.CALARA', 'QUEZON CITY', 'Y', 'C', 'Y', NULL, NULL),
(100070, 'AMERICAN TRADING INTL INC', '11300 WEST OLYMPIC BOULEV', 'SUITE 780, LOS ANGELES, ', 'CALIFORNIA 90064 U.S.A.', 0, '310-445-14', '310-445-14', 1006, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100072, 'MDB INC.', '6802 WATCHER STREET COMME', '', '', 0, '986-49-97', '834-74-27', 1013, 'RG', '0.00', 'USD', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100075, 'MOLINA & SONS (PHILS.) IN', 'RM. 1722 17TH FLOOR., TYT', 'PLAZA CENTER PLAZE LORENZ', 'RUIZ, BINONDO MANILA', 0, '242-52-37', '242-52-39', 1016, 'RG', '0.00', 'PHP', 'A', 'MR. JOEY PESTILOS', 'BINONDO MANILA', 'Y', 'C', 'Y', NULL, NULL),
(100077, 'APOLLO FOOD INDUSTRIES SD', 'NO. 70, JALAN LANGKASUKA,', 'INDUSTRIAL AREA, 80350 JO', 'BAHRU, JOHOR W. MALAYSIA', 0, '07-2365096', '07-2374748', 1021, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100079, 'JACOBSEN BAKERY LTD.', 'POSTBOKS 99 NILANVEJ 1', '8722 HEDENSTED', ' ', 0, '76-752730', '75-890587', 1024, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100080, 'ACERPORT INC.', '1014 THERESA ST., RIZAL V', 'VILLAGE MAKATI CITY', ' ', 0, '899-8720', '896-0710', 1016, 'RG', '0.00', 'PHP', 'A', 'MR. CARLOS ANGELES', 'MAKATI CITY', 'Y', 'C', 'Y', NULL, NULL),
(100082, 'BARACHIEL ENTERPRISES', '12 ILAYA ST., TONDO, METR', ' ', ' ', 0, ' ', '7258432', 23, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100083, 'RPC GROCERY DISTRIBUTORS ', '21113 47TH AVENUE E SPANA', '', ' ', 0, '001-253-57', '001-253-87', 1004, 'RG', '0.00', 'USD', 'A', 'MS. INGER RAMBERG', 'SPANAWAY, WA 98387', 'Y', 'C', 'Y', NULL, NULL),
(100087, 'ANDALUCIA TRADING COMPANY', '1130 E. ROMUALDEZ STREET', 'ERMITA, MANILA', 'PHILIPPINES', 0, '521-33-26', '521-33-26', 1016, 'RG', '0.00', 'PHP', 'A', 'MS. LILIA GONZALES', 'ROMUALDEZ ST ERMITA MANILA', 'Y', 'C', 'Y', NULL, NULL),
(100088, 'GNP TRADING CORPORATION', '166-A ALFONSO XIII ST., S', ' ', ' ', 0, '7238301', '7254459', 23, 'RG', '0.00', 'PHP', 'A', 'MR. GEORGE PUA', '166-A ALFONSO XIII ST., S', 'Y', 'C', 'Y', NULL, NULL),
(100092, 'TRANSAXION UNLIMITED CORP', 'UNIT 801-A FIRST MARCEL P', '926 G. ARANETA AVENUE, QU', ' ', 0, '4117147', '7419717', 23, 'RG', '0.00', 'PHP', 'A', 'MS. EDIT', 'UNIT 801-A FIRST MARCEL P', 'Y', 'C', 'Y', NULL, NULL),
(100093, 'KRAFT FOODS SWITZERLAND L', 'WORLD TRAVEL RETAIL', 'LINDBERGH-ALLEE I CH-8152', 'GLATTPARK SWITZERLAND', 0, '879-4060', '879-4061', 1007, 'RG', '0.00', 'CHF', 'A', 'MS. JONAS SAMALERO', 'PARANAQUE CITY', 'Y', 'C', 'Y', NULL, NULL),
(100094, 'KAREILA MANAGEMENT CORP', '1130 E. ROMUALDEZ ST', 'ERMITA, MANILA', 'PHILIPPINES', 0, '523-9433', '524-4114', 1016, 'RG', '0.00', 'PHP', 'A', 'MS. CARLA DEQUILLA', 'ERMITA MANILA', 'Y', 'C', 'Y', NULL, NULL),
(100095, 'CONSOLIDATED DAIRY & FROZ', '18F UNIT AB SAN FERNANDO ', 'TOWER PLAZA DEL CONDE', 'BINONDO MANILA', 0, '242-4305', '242-4566', 1016, 'RG', '0.00', 'PHP', 'A', 'MR. RUDY LUGASAN', 'BINONDO MANILA', 'Y', 'C', 'Y', NULL, NULL),
(100096, 'GILAMAR ENTERPRISES, INC.', '3925 SOCIEGO ST., STA MES', ' ', ' ', 0, '7160713', '7161518', 22, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100097, 'HERSHEY INTERNATIONAL', '19 EAST CHOCOLATE AVENUE', 'HERSHEY, PA 17033-0812', ' ', 0, '724-1400', '724-1399', 1003, 'RG', '0.00', 'USD', 'A', 'MS. BRIGETTE NABONG', 'PARANAQUE CITY', 'Y', 'C', 'Y', NULL, NULL),
(100100, 'KRAFT FOODS AUSTRALIA PTY', '30 CONVENTION CENTRE PLAC', 'SOUTH WHARF, VICTORIA 300', ' AUSTRALIA', 3006, '61-3-9676-', '61-3-9676-', 1008, 'RG', '0.00', 'USD', 'A', 'MS. MICHELLE SILVESTRE', 'PARANAQUE CITY', 'Y', 'C', 'Y', NULL, NULL),
(100101, '(NTBU)GOLDEN TOP MARKETIN', '136 BIAK NA BATO ST., ', 'BRGY.SIENNA STA.MESA', 'HEIGHT, QUEZON CITY', 0, '583-4219', '721-5648', 22, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', NULL, 'XX'),
(100102, 'MARS PHILIPPINES', '11F, TOWER 1, THE ENTERPR', 'CENTER 6766 AYALA AVENUE', 'PASEO DE ROXAS MAKATI CIT', 0, '887-7000', '887-1217', 1007, 'RG', '0.00', 'USD', 'A', 'MR. GIO MARCELO', 'MAKATI CITY', 'Y', 'C', 'Y', NULL, NULL),
(100103, 'PREMIER WINES & SPIRITS, ', 'PACO, MANILA', '', '', 0, ' ', ' ', 1016, 'RG', '0.00', 'PHP', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100105, 'WILLIAMS & HUMBERT PHILS.', 'TABACALERA BLDG. 900 D.', 'ROMUALDEZ ST. ERMITA MANI', ' ', 0, '523-8633', '523-8635', 1016, 'RG', '0.00', 'PHP', 'A', 'MS. DOROTHY SO', 'ERMITA MANILA', 'Y', 'C', 'Y', NULL, NULL),
(100106, 'HOWA BOEKI CO.,LTD.', 'HOWA BLDG. 3-6-17, ', 'TEMMA, KITA-KU OSAKA', '530-0043, JAPAN', 0, '816-6881-3', '816-6352-1', 1021, 'RG', '0.00', 'YEN', 'A', 'MR. T. HORIE', 'OSAKA, JAPAN', 'Y', 'C', 'Y', NULL, NULL),
(100107, 'SHANDONG CHUNYU FOODS CO.', 'SHANDONG CHINA', '', '', 0, '711-5178', '711-8469', 1000, 'RG', '0.00', 'USD', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100110, 'SNAPSNACK FOODS CORP.', '5F MARKET MARKET GLOBAL', 'CITY', 'TAGUIG', 0, '632-7741', '632-7742', 1018, 'RG', '0.00', 'PHP', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100111, 'BEEGGYMEN TRADE CENTER', '211B DO?A ROSARIO VILLAGE', 'TABOK, MANDAUE CITY, CEBU', 'PHILIPPINES', 0, '032-420-74', '032-346-74', 1016, 'RG', '0.00', 'PHP', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100112, 'BARGAIN WHOLESALE', '4000 E UNION PACIFIC AVEN', 'LOS ANGELES CALIFORNIA', 'U.S.A.', 90023, '0013238819', '0013238819', 1005, 'RG', '0.00', 'USD', 'A', 'JAIME BONILLA', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100114, 'BARGAIN BANK', '2322 51ST STREET', 'VERNON CALIFORNIA', 'U.S.A.', 90058, '0013235855', '0013235854', 1020, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100115, 'CHINDA INTERNATIONAL', '13928 E VALLEY BOULEVARD', 'CITY INDUSTRY CALIFORNIA', 'U.S.A.', 0, '6263694086', '6268550198', 1023, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100118, 'FOUR SEASON GENERAL MERCH', '2801 EAST VERNON AVENUE', 'LOS', 'U.S.A.', 90058, '0013235824', '0013235829', 1003, 'RG', '0.00', 'USD', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100119, 'FOODEX USA', '111 DERWOOD ROAD', 'SUITE', 'CALIFORNIA', 94583, '0019257432', '0019257432', 1005, 'RG', '0.00', 'USD', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100120, 'EARNEST MULTINATIONAL TRA', '145 15TH AVENUE, UNION', 'SQUARE CONDO ', 'QUEZON CITY', 0, '911-9058', '911-9058', 1016, 'RG', '0.00', 'PHP', 'A', 'MS. BETTY NGO', 'QUEZON CITY', 'Y', 'C', 'Y', NULL, NULL),
(100122, 'TRANS USA CORPORATION', '4134 LAKESIDE DRIVE, RICH', '', ' ', 0, '510-222-48', '510-222-52', 1013, 'RG', '0.00', 'USD', 'A', 'MR. WILLIAM SHIH', 'NA', 'Y', 'C', 'Y', NULL, NULL),
(100123, 'MAX WHOLESALE EXPORT INC', '2410 E 38TH  STREET', 'VERNON CALIFORNIA', 'U.S.A.', 90058, '0013232671', '0013235838', 1024, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100124, 'PROCTER AND GAMBLE INTL', '238A THOMSON ROAD ', '2101 10 NOVENA SQUARE TOW', 'SINGAPORE', 307684, '0065682451', '0065682463', 1011, 'RG', '0.00', 'USD', 'A', 'BOON WEE TAN', 'SINGAPORE', 'Y', 'C', 'Y', NULL, NULL),
(100125, 'PACIFIC PACKAGING', 'NO 6 LOYANG DRIVE', 'LOYANG INDUSTRIAL ESTATE', 'SINGAPORE', 508937, '0065654518', '0065654545', 23, 'RG', '0.00', 'USD', 'A', 'MR THOMAS HENG', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100127, 'WILLARD MANUFACTURING INC', '1 5295 JOHN LUCAS', 'ONTARIO L7L5AB', ' ', 0, '9056315800', '0019056310', 1003, 'RG', '0.00', 'USD', 'A', 'MS ANNETTE STRIKWERDA', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100129, 'REVLON MANUFACTURING LTD', '1551 SOUTH WASHINGTON AVE', 'PISCATWAY JEW JERSEY', 'U.S.A.', 8854, '7241400', '7241399', 1019, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100130, 'SUPERVALU INTERNATIONAL', '495 EAST 19TH STREET', 'TACOMA WASHINGTON', 'U.S.A.', 98421, '0012535937', '0012535937', 1010, 'RG', '0.00', 'USD', 'A', 'MARK LIEW', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100132, 'UNIFIED WESTERN GROCERS', '5200 SHEILA STREET', 'COMMERCE CALIFORNIA', 'U.S.A.', 90040, '0013232645', '0013232647', 1003, 'RG', '0.00', 'USD', 'A', 'SUSAN CORDERO', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100133, 'VOLUME DISTRIBUTOR', '4199 BANDINI BLVD', 'VERNON CALIFORNIA', 'U.S.A.', 90023, '0013239811', '0013239811', 1002, 'RG', '0.00', 'USD', 'A', 'GLORIA KAMACHI', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100141, 'ATLANTIC INTL TRADING CO ', 'FLAT D 23 FLOOR 4 WHITFIE', 'CAUSEWAY BAY', 'HONGKONG', 0, '0085225788', '0085228071', 1000, 'RG', '0.00', 'USD', 'A', 'MS. ESTELLA KWOK', 'CAUSEWAY BAY HONG KONG', 'Y', 'C', 'Y', NULL, NULL),
(100143, 'PT SINAR ANTJOL', 'JL MALAKA II NO 1 TO 3 ', 'JAKARTA 11230 ', 'INDONESIA', 0, '0062216693', '0062216695', 1009, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100146, 'SCA HYGIENE PRODUCTS CORP', '16TH FLOOR ASIAN STAR BUI', 'ASEAN DRIVE CO SINGAPURA ', 'FILINVEST CORPORATE CITY ', 0, '5435090', '5435102', 1016, 'RG', '0.00', 'PHP', 'A', 'CONRAD AUSTRIA', '16TH FLOOR ASIAN STAR BLDG FILINVEST CORPORATE CITY', 'Y', 'C', 'Y', NULL, NULL),
(100150, 'GRACIA NEW ERATEX', 'RUKO MARINATAMA MANGGA DU', 'BLOK C NO. 12A GUNUNG SAH', 'NO.2 JAKARTA UTARA 14410', 14410, '0062216660', '0062216623', 1022, 'RG', '0.00', 'USD', 'A', 'SUSI', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100153, 'GARDENIA BAKERIES PHIL IN', 'GARDENIA CENTRE STAR AVE ', 'LAGUNA INTERNATIONAL IND', 'MAMPLASAN, BI?AN LAGUNA', 0, '0495391136', '0495391148', 1017, 'RG', '0.00', 'PHP', 'A', 'AMFIE TOBIAS', 'LAGUNA', 'Y', 'C', 'Y', NULL, NULL),
(100154, 'UNILEVER RFM ICECREAM INC', 'HACIENDA LUISITA SAN MIGU', ' ', ' ', 2301, '9851422', '9851425', 1017, 'RG', '0.00', 'PHP', 'A', 'RICO SALES', 'PHILIPPINES', 'Y', 'C', 'Y', NULL, NULL),
(100156, 'SAN MIGUEL FOODS INC', '208 BLDG2 19 GEN ATIENZA ', 'SAN', 'PASIG', 0, '6322524', '6323299', 1018, 'RG', '0.00', 'PHP', 'A', 'RAYMOND TIENZO', 'PHILIPPINES', 'Y', 'C', 'Y', NULL, NULL),
(100158, 'ABSOLUTE SALES CORP', 'WISE BLDG FAIRLANE ST ', 'CORNER', 'PASIG', 0, '0458606466', '0459630512', 1016, 'RG', '0.00', 'PHP', 'A', 'JESSE LOYA', 'PASIG CITY', 'Y', 'C', 'Y', NULL, NULL),
(100160, 'CANADIAN MANUFACTURING', 'UNIT 64 LEGASPI SUITES 17', ' ', ' ', 0, '8921173', '8945747', 2001, 'CO', '18.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', '0066', NULL),
(100162, 'HUDGES INDUSTRIES', 'LOT 65D BAGSAKAN COR SIRL', ' ', ' ', 0, '8391785', '8370993', 2001, 'CO', '22.00', 'PHP', 'A', 'MS. NIDA', 'LOT 65D BAGSAKAN COR SIRL', 'Y', 'C', 'Y', '0068', NULL),
(100164, 'SMI GENERAL MERCHANDISE', '19 LOTE DULONG BAYAN STA ', ' ', ' ', 0, '44 6413571', '44 6411111', 2001, 'CO', '20.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', '0076', NULL),
(100166, 'SHOWCASE CARPET CENTER CO', '31 SEKURIT COMPOUND AMANG', ' ', ' ', 0, '6429390', '6436843', 2001, 'CO', '25.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', '0083', NULL),
(100167, 'BRANDS WORLDWIDE', '129 BAYABONG PLACE', 'AYALA ALABANG', 'MUNTINLUPA CITY', 1780, '7750049', '8094129', 1024, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100168, 'EXECUTIVE GARMENTS', '227A  P SEVILLA STREET', 'GRACE', 'PHILIPPINES', 0, '2557093', '2537911', 1008, 'RG', '0.00', 'PHP', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100170, 'US COTTON LLC', '1500 PRAIRIE DRIVE', 'CARROLTON TEXAS', ' ', 75007, '5058922269', '5058920977', 1025, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100174, 'KIMBERLY IMPORT & EXPORT ', '2443-2445 ARSONVEL ST.BGY', '1234 MAKATI CITY, PHILIPP', ' ', 0, '8123601', '8446407', 22, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100176, 'MEGADELI MARKETING, INC.', '491 F. MANALO ST. BGY. BA', ' ', ' ', 0, '7275883', '7276162', 22, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100184, 'ESSONS VENTURES SYSTEMS', '11 PASEO LEON STREET', 'LAS VILLAS DE ', 'VALLE VERDE, PASIG CITY', 0, '9152911', '9152911', 22, 'CO', '25.00', 'PHP', 'A', 'ENRIQUITO SANTIAGO/MARICEL', 'SAME AS ABOVE', 'Y', 'C', 'Y', '0080', NULL),
(100191, 'SHARPMIND READING CORNER', 'G. ARANETA CORNER', 'E. RODRIGUEZ AVE. ', 'BRGY. DONA IMELDA, QC', 0, '7073365', '7323161', 22, 'CO', '20.00', 'PHP', 'A', 'ISA CHUA', 'SAME AS ABOVE', 'Y', 'C', 'Y', '0081', NULL),
(100193, 'PRO THROWBACKS GEN. MDSE', 'C. M. RECTO HIGHWAY COR.', 'KALAW STREET', 'CLARKFIELD, PAMPANGA', 0, ' ', ' ', 22, 'CO', '20.00', 'PHP', 'A', 'ROSEN CAPINGCOT-HARVISON', 'SAME AS ABOVE', 'Y', 'C', 'Y', '0084', NULL),
(100196, 'ASIA LINK TRADING', 'NO. 6 BUILDING 28', 'JIANGNAN 3RD WARD,', 'YIWU, ZHEJIANG, CHINA', 0, '2418366', '2418368', 1019, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100221, 'FIBREFILL MANUFACTURING I', 'C RAYMUNDO AVENUE BO MAYB', ' ', ' ', 0, '6413845', '6435533', 22, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100224, 'RDC BEDMATS ENTERPRISE', '38F OCAMPO AVE DONA MANUE', ' ', ' ', 0, '8743673', '8725996', 22, 'RG', '0.00', 'PHP', 'A', 'ROMEO DE CLARO', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100227, 'RAJMIN GARMENTS MFG INC', 'UNIT 517 CITYLAND P TAMO ', ' ', ' ', 0, '7576269', '7289175', 22, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100228, 'PROCHAIN WORL CORP.', '4FL-2 NO. 84, FU HSING SO', ' ', ' ', 123, '002-2325-8', '002-2700-7', 1005, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100229, 'ESMARK INTERNATIONAL', 'SUITE 101 LEVEL 19 ', 'GROSVENOR ST. NEUTRAL BAY', 'NSW, AUSTRALIA', 2089, '0016129908', '0016129908', 1005, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100231, 'EADECO SDN BHD', '55992 BATU 5 JALAN TUNKU ', ' ', ' ', 0, '6052922933', '6052922800', 1023, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100233, 'TOV LEV ENTERPRISES, INC.', '4280 MAYWOOD AVE., VERNON', ' ', ' ', 90058, '001-323-58', '001-323-58', 1024, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100234, 'CANDLE-LITE', '10521 MILLINGTON COURT CI', ' ', ' ', 45242, '001-513-56', '001-513-56', 1025, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100235, 'BRIAN TRADING COMPANY', '16930 N W 4TH AVE MIAMI F', '', '', 33169, '3056515020', '3056514012', 30, 'RG', '0.00', 'USD', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100237, 'HOME DYNAMIX', 'ONE CAROL PLACE MOONACHIE', ' ', ' ', 7074, '2018070111', '2013296377', 1034, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100238, 'SHANGHAI MINGUANG IMP EXP', '5F 584 ZHI ZAO JU ROAD SH', ' ', ' ', 200023, '8621631585', '8621631582', 1000, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100239, 'DOOIL USA, INC.', '2620 E. VERNON AVE,. CALI', ' ', ' ', 90058, '001-323-58', '001-323-58', 1024, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100240, 'THE MAZEL COMPANY', '31000 AURORA RD., SOLON, ', ' ', ' ', 44139, '001-440-24', '001-440-34', 1024, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100241, 'ADORABLE PILLOWS MFG. INC', '902 ESSEX STREET BROOKLYN', ' ', ' ', 11208, '718-272172', '718-272185', 1024, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100242, 'HDI HOUSEWARES', '145 PROGRESS DRIVE, WEST ', ' ', ' ', 53095, '001-262-33', '001-262-33', 1025, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100244, 'REPUBLIC IMPORT COMPANY, ', '5920 CORVETTE STREET, COM', ' ', ' ', 90040, '001-323-88', '001-323-88', 1024, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100247, 'AMRAPUR INC.', '12621 WESTERN AVE., GARDE', ' ', ' ', 92841, '714-754163', '714-754073', 1024, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100248, 'ASPINWALL & CO. LTD.', 'NORTH COMMERCIAL CANAL RO', ' ', ' ', 0, '91-4772243', '91-4772242', 1034, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100250, 'COTTON VALLEY LLC', '90 DISCTRIBUTION BLVD, ED', ' ', ' ', 8817, '732-650121', '732-650121', 1023, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100251, 'YMF INC', '5 TRUMAN DRIVE SOUTH, EDI', ' ', ' ', 8817, '732-393180', '732-393099', 1025, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100253, 'GIBSON OVERSEAS, INC.', '2410 YATES AVENUE, COMMER', ' ', ' ', 900401918, '001-323-83', '001-323-83', 1033, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100254, 'NIDICO GROUP INC', '1029 PULINSKI ROAD IVYLAN', ' ', ' ', 18974, '267-280880', '267-280017', 1025, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100255, 'SPECTRUM HOOME FASHIONS, ', '4961 SANTA ANITA AVE., UN', ' ', ' ', 0, '626-448090', '626-448094', 1006, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100256, 'HARRY HYMAN & SON, INC.', '356 WEST END AVENUE NEW Y', ' ', ' ', 10024, '001-212-76', '001-212-79', 1023, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100257, 'ARAMCO IMPORTS INC.', '6431 BANDINI BLVD. COMMER', ' ', ' ', 90040, '001-323-83', '001-323-62', 1034, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100258, 'ACME LINEN', '5136 E. TRIGGS ST. CITY O', ' ', ' ', 90022, ' ', '323-267577', 1024, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100259, 'BALLINGTON CORPORATION', '2145-2147 NORTH TYLER AVE', ' ', ' ', 91733, '001-626-27', '001-626-27', 1025, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100262, 'BLISS HAMMOCKS, INC.', '901 MOTOR PARKWAY HOUPPAU', ' ', ' ', 11788, '001-631-27', '001-631-27', 1024, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100265, 'CKC INTERNATIONAL LLC', '7 SLATER DRIVE ELIZABTH, ', ' ', ' ', 7206, '001-908-24', '001-908-52', 1006, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100268, 'SUPER NATURE INC.', '9661 TELSTAR AVE., EL MON', ' ', ' ', 91731, '001-626-57', '001-626-57', 1005, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100270, 'ALCO CONSUMER PRODUCTS, I', '111 MELRICH ROAD CRANBURY', ' ', ' ', 8512, '001-609-49', '001-609-49', 1005, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100274, '26 CALIFORNIA BAZAR INC.', '2652 E. 45TH STREET, VERN', 'AAA', ' ', 90058, '001-323-58', '001-323-58', 1005, 'RG', '0.00', 'USD', 'A', ' 1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100275, 'CARPET ENTERPRISES INC.', '560 MARINE DRIVE P.O. BOX', ' ', ' ', 30703, '706-602782', '706-602781', 1005, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100280, 'MAXS WHOLESALE IMPORT EXP', '2410 E. 38TH STREET, VERN', ' ', ' ', 90058, '001-323-26', '001-323-58', 1006, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100281, 'THE LIBMAN COMPANY', '220 N. SHELDON ARCOLA, IL', ' ', ' ', 61910, '001-217-26', '001-217-26', 1005, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100282, 'ARROW PLASTIC MANUFACTURI', '701 E. DEVON AVENUE, ELK ', ' ', ' ', 60007, '001-847-59', '001-847-59', 1005, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100283, 'CIRCLE IMPORTS, INC.', '9 TAYLOR ROAD, EDISON, NE', ' ', ' ', 8817, '001-732-28', '001-732-28', 1005, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100284, 'LIBRA', '3310 NORTH 2ND STREET, MI', ' ', ' ', 55412, '001-612-52', '001-612-52', 1024, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100285, 'PIONEER INDUSTRIAL CORPOR', '174 2 SILOM ROAD, BANGKOK', ' ', ' ', 10500, '00-662-233', '00-662-234', 1005, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100286, 'TABLETOPS UNLIMITED, INC.', '23000 S. AVALON BLVD., CA', ' ', ' ', 90745, '001-310-54', ' ', 1023, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100287, 'CREATIVE BATH PRODUCTS, I', '250 CREATIVE DRIVE CENTRA', ' ', ' ', 11722, '001-631-58', '001-631-58', 1006, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100291, 'MINKY HOMECARE LLC', 'P.O. BOX 599 NEOSHO, MO', ' ', ' ', 64850, '001-417-38', ' ', 1005, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100293, 'MC CALLUM INDUSTRIES', '21-27 MIHINI ST. HENDERSO', 'NEW ZEALAND', ' ', 0, '649-839029', '649-836094', 1005, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100294, 'ESMARK ITERNATIONAL PTY L', 'SUITE 3, LEVEL 2, 696 MIL', 'AUATRALIA.', ' ', 0, '612-990877', '612-990878', 1015, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100295, 'HORMEL FOODS INTERNATIONA', '1 HORMEL PLACE AUSTIN MN ', ' ', ' ', 0, '507-437540', '507-437511', 1023, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100296, 'COVI S.A.', 'WESTBAAK 11X 3012 KC ROTT', ' ', ' ', 0, '3110-41128', '3110-41268', 1023, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100301, 'ZUELLIG PHARMA PHILS', 'MALUGAY STREET MAKATI CIT', ' ', ' ', 0, '845-7386', '816-0181', 1016, 'RG', '0.00', 'PHP', 'A', 'CHERRY MANALAYSAY', 'MAKATI CITY', 'Y', 'C', 'Y', NULL, NULL),
(100305, 'SOLID WIND COMMERCIAL', 'NO.48 SAN JOSE ST., MAGSA', 'BRGY. 104 ZONE 10 TONDO, ', ' ', 1012, '2513668', '2513668', 1008, 'RG', '0.00', 'PHP', 'A', 'MR PHILIP KUA', 'NO.48 SAN JOSE ST., MAGSAYSAY VILL. BRGY. 104 ZONE 10 TONDO, MANILA', 'Y', 'C', 'Y', NULL, NULL),
(100307, 'GT PACIFIC INC.', '685-B TANDANG SORA AVE., ', '', '', 1106, '9848404', '9848406', 1008, 'RG', '0.00', 'PHP', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100308, 'VENTURES HARDWARE CORP', 'HP METROLANE COMPLEX, P.T', ' ', ' ', 1109, '3010132', '5284436', 1008, 'CO', '10.00', 'PHP', 'A', 'MS. EMILY', 'PHILIPPINES', 'Y', 'C', 'Y', '0059', NULL),
(100312, 'MINAMI MANUFACTURING CORP', 'NO.25 PACIFIC ST., T.S CR', '', '', 1106, '6348691', '6345899', 1008, 'RG', '0.00', 'PHP', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100313, 'NEW BENEHLYN ENTERPRISES', 'SUITE 701-702 TYTANA PLAZ', '', '', 1006, '2410159', '2410274', 1008, 'RG', '0.00', 'PHP', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100314, 'BELLS LIGHTING INC.', 'NO.27 OLIVEROS DRIVE, BAL', '', '', 1106, '3302101', '', 1008, 'CO', '15.00', 'PHP', 'A', NULL, NULL, 'Y', 'C', 'Y', '0060', NULL),
(100316, 'NKD INTERNATIONAL TRADING', 'NO.240 BANAWE ST., COR. P', '', '', 1115, '3610828', '3646240', 1008, 'CO', '15.00', 'PHP', 'A', NULL, NULL, 'Y', 'C', 'Y', '0079', NULL),
(100318, 'EXCELLENCE APPLIANCE TECH', 'NO.22 D. TUAZON COR. L.CA', '', '', 1113, '7120535', '7120537', 1008, 'CO', '8.00', 'PHP', 'A', NULL, NULL, 'Y', 'C', 'Y', '0074', NULL),
(100321, 'GUANGZHOU LIGHT HOLDINGS ', 'NO.87 THE BUND GUANGZHOU,', '', '', 1116, '9848404', '9848406', 1007, 'RG', '0.00', 'USD', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100323, 'ASAHI ELECTRICAL MFG.CORP', '117P.PARADA ST.,STA.LUCIA', '', '', 1500, '6428437', '6417597', 1008, 'RG', '0.00', 'PHP', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100327, 'FRESH\\''N NATURAL FOODS,IN', 'UNIT 927 CITYLAND SHAW TO', 'MANDALUYONG CITY', ' ', 0, '6871995', '6871995', 22, 'RG', '0.00', 'PHP', 'A', 'MS.BOOTS', 'UNIT 927 CITYLAND SHAW TO ', 'Y', 'C', 'Y', NULL, NULL),
(100329, 'NESTLE INT\\''L TRAVEL RTL', 'CENTRE DE LA POSTE', 'AV, DE LA GARE 52, CH-161', 'CHATEL ST., DENIS SWITZER', 1618, ' ', ' ', 1020, 'RG', '0.00', 'USD', 'A', 'MS. JO KATIPUNAN', 'PHILIPPINES', 'Y', 'C', 'Y', NULL, NULL),
(100331, 'ACECO MILLS INC.', '720 FRELINGHUYSEN AVE NEW', '', '', 7114, '973-733220', '973-733224', 1005, 'RG', '0.00', 'USD', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100333, 'A. ORENSTEIN', '1212', '', '', 1212, '', '', 1005, 'RG', '0.00', 'USD', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100337, 'MILLENIUM INTERNATIONAL', '11716 MC BEAN DRIVE  ELMO', '', '', 91732, '001-626-58', '001-626-58', 1003, 'RG', '0.00', 'USD', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100342, 'MAMEE PACIFIC FOOD PRODUC', 'LOT 1, AIR KEROH INDUSTRI', '75450 MELAKA, MALAYSIA', '', 75450, '', '', 1003, 'RG', '0.00', 'USD', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100344, 'Q&H FOODS, INC.', 'NO.22 N. DOMINGO ST. QUEZ', '', '', 11123, '636-1455', '631-0786', 1000, 'RG', '0.00', 'PHP', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100346, 'GLORIOUS COMM\\''L CORP.', '8272 DAPITAN ST., GUADALU', '', '', 1000, '8823985', '8821282', 1008, 'RG', '0.00', 'PHP', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100351, 'DELFI MARKETING, INC.', 'NO. 23 M. TUAZON STREET, ', 'MARIKINA', '', 1000, ' ', ' ', 1017, 'RG', '0.00', 'PHP', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100353, 'LST SUMMIT INC', '452 BONI AVE NEW ZANIGA', 'MANDALUYONG CITY 1550', ' ', 1550, '532 5275', '532 0723', 1008, 'RG', '0.00', 'PHP', 'A', 'NILO GODINO', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100356, 'NASPAC MARKETING PTE LTD', 'NO 4 TOH TUCK LINK #04-00', 'MARKONO LOGISTICS BLDG SI', '', 596226, '6563653313', '6563689690', 1000, 'RG', '0.00', 'USD', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100359, 'VIET THANH CERAMIC CORP.', 'VIETNAM', '', '', 0, '8461-954-6', '8641-955-9', 1000, 'RG', '0.00', 'USD', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100361, 'CLOSETMAID', 'USA', 'USA', '', 10500, '', '', 1007, 'RG', '0.00', 'USD', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100363, 'MCKENZIE DISTRIBUTION CO.', '88 E RODRIGUEZ JR. AVE.LI', 'PHILS 1110', ' ', 0, '6382660 ', '6378091', 1016, 'RG', '0.00', 'PHP', 'A', 'ARLYN ARCAGUA', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100365, 'REJ DIAMOND PHARMACEUTICA', '8 FERIA ROAD COMMONWEALTH', 'DILIMAN', '', 0, '932-7555', '931-4916', 1016, 'RG', '0.00', 'PHP', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100368, 'VALIANT DISTRIBUTION ', '86 E RODRIGUEZ JR. AVE', 'LIBIS QUEZON CITY', '', 1000, '', '', 1016, 'RG', '0.00', 'PHP', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100370, 'MARS INTERNATIONAL TRAVEL', '11TH FLOOR TOWER 1, THE E', 'CENTER 6766 AYALA AVENUE,', 'MAKATI CITY PHILIPPINES', 1000, '887-7000', '887-1217', 1008, 'RG', '0.00', 'USD', 'A', 'MR. GIO MARCELO', 'MAKATI CITY, PHILIPPINES', 'Y', 'C', 'Y', NULL, NULL),
(100372, 'FREIGHBURG', 'AAA', 'AAA', '', 1000, 'AAA', 'AAA', 1018, 'CG', '0.00', 'PHP', 'D', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100375, 'PUREGOLD DUTYFREE, SUBIC ', '1109 PALM STREET', 'SUBIC BAY, FREEPORT ZONE', 'OLONGAPO CITY', 0, '0472525556', '0472525556', 1016, 'RG', '0.00', 'USD', 'A', 'MS. JOSEFINA CRONICO', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100377, 'PTC COMMERCIAL CORPORATIO', 'BLK 4 LOT 2 FIRST TONDO, ', 'TONDO, MANILA, PHILIPPINE', '', 0, '256-8831', '255-2956', 1016, 'RG', '0.00', 'PHP', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100379, 'ZUELLIG PHARMA CORP.- ABB', 'ZUELLIG BLDG.SEN.GIL PUYA', 'MAKATI CITY', '', 10000, '', '', 1018, 'RG', '0.00', 'PHP', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100381, 'WRIGLEY PHILIPPINES, INC.', '11TH FL. NET ONE CENTER, ', 'CRESCENT PARK WEST, BONIF', '1634 TAGUIG CITY, PHILIPP', 1000, '', '', 1016, 'RG', '0.00', 'PHP', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL),
(100384, 'IDEAL MACARONI & SPAGHETT', '33 LUNA II ST, MALABON ME', '', '', 1123, '2810755', '2814674', 1016, 'RG', '0.00', 'PHP', 'A', 'MS. JOY SERAPIO', 'MALABON METRO MANILA', 'Y', 'C', 'Y', NULL, NULL),
(100386, 'OPTIMEX TRADE / BENFOODS', '1 FISHERY PORT ROAD JURON', '', '', 10000, '0066526743', '0066577584', 1004, 'RG', '0.00', 'USD', 'A', 'MR.DANNY PERRERAS', '1 FISHERY PORT ROAD JURONG SINGAPORE', 'Y', 'C', 'Y', NULL, NULL),
(100388, 'PUREGOLD DUTY FREE INC. C', 'C. M. RECTO HIGHWAY CORNE', 'P. KALAW STREET', 'CLARKFIELD, PAMPANGA', 0, '5844045', '0455992348', 23, 'RG', '0.00', 'USD', 'A', 'MR. JAMES BALINGIT/MR. ROBERT PASAMONTE', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100390, 'KENNEDY INT\\''L ', '250 STULTS ROAD DAYTON, ', 'NJ USA', ' ', 8810, '001-609-40', '001-609-40', 1005, 'RG', '0.00', 'USD', 'A', ' SAMMY ROTHSCHILD ', '250 STULTS ROAD DAYTON, NJ USA', 'Y', 'C', 'Y', NULL, NULL),
(100392, 'AUSTRALIAN FINE FOODS PTY', 'A.B.N. 39 007 132 336 70 ', '', '', 3043, '0061039310', '0061039310', 1005, 'RG', '0.00', 'AUD', 'A', 'MS. AGNES GOMEZ', 'A.B.N. 39 007 132 336 70 SPRINGBANK STREET TULLAMARINE, VICTORIA, AUSTRALIA', 'Y', 'C', 'Y', NULL, NULL),
(100393, 'PLUVIAL ENTERPRISES', '1412 SAPANG BAKAW, L.BATO', '', '', 1448, '4531627', '4445807', 22, 'CO', '25.00', 'PHP', 'A', 'LEONARDO RAYMUNDO', '1412 SAPANG BAKAW, LAWANG BATO, VALENZUELA CITY', 'Y', 'C', 'Y', '0085', NULL),
(100396, 'ATLAS HOME PRODUCTS, INC.', '552 ELCANO ST., TONDO MAN', ' ', ' ', 1000, '2423556; 7', '7210329', 22, 'CO', '25.00', 'PHP', 'A', 'KENDRICK NGO', '552 ELCANO ST., TONDO, MANILA', 'Y', 'C', 'Y', '0088', NULL),
(100400, 'SENTROS CONCEPT FURNITURE', '167 F.MANALO ST., SAN JUA', '', '', 1000, '7254426', '7445272', 22, 'CO', '30.00', 'PHP', 'A', 'AILEEN BALORAN', '167 F. MANALO ST., SAN JUAN CITY', 'Y', 'C', 'Y', '0086', NULL),
(100402, 'INFINITI INTERTRADE INC.', '2ND FLR PROSPERITY BANAWE', '', '', 0, '712-5488', '712-5488', 1016, 'RG', '0.00', 'PHP', 'A', 'MR. BRIAN ROGER', 'QUEZON CITY', 'Y', 'C', 'Y', NULL, NULL),
(100404, 'SPARGA ASIA CO.', 'M. GREENFIELD KM.14, MERV', 'MERVILLE, PARA?AQUE CITY1', '', 1000, '', '', 1016, 'RG', '0.00', 'PHP', 'A', 'MS. MARIESTELLA MAGNAYE', 'PARANAQUE', 'Y', 'C', 'Y', NULL, NULL),
(100407, 'GOLDBRAND MARKETING INC.', '8520 JUANITA DE LEON ST.,', ' ', ' ', 0, '829-3319', '825-3710', 1016, 'RG', '0.00', 'PHP', 'A', 'MS. LIBAY ', 'PARA?AQUE CITY', 'Y', 'C', 'Y', NULL, NULL),
(100409, 'BUDDEEZ, INC.', '1106 CROSSWINDS COURT WEN', 'MO ', '', 63385, '(636)639-6', '(636)639-8', 1006, 'RG', '0.00', 'USD', 'A', 'MARK HORSTMAN', '1106 CROSSWINDS COURT WENTZVILLE', 'Y', 'C', 'Y', NULL, NULL),
(100411, 'KETER PLASTIC LTD.', '2 SAPIR ST., INDUSTRIAL A', 'HERZELLA, ISRAEL', '', 46852, '972-6-9591', '972-9-9566', 1003, 'RG', '0.00', 'USD', 'A', 'STEVE TAYLOR / AGNES AYOUN', '2 SAPIR ST., INDUSTRIAL AREA, HERZELLA, ISRAEL', 'Y', 'C', 'Y', NULL, NULL),
(100422, 'COLOMBO MERCHANT PHILIPPI', 'MEZZANINE 1, SOUTH CENTER', '2206 VENTURE STREET', 'MADRIGAL BUSINESS PARK, A', 0, '8794060', '8794061', 22, 'CO', '25.00', 'PHP', 'A', 'JEAN SISON', 'SAME AS ABOVE', 'Y', 'C', 'Y', '0087', NULL),
(100430, 'DYNAMEX INC', '3RD FLOOR KLG BLDG PASCOR', '', '', 1000, '8263400', '8201885', 1016, 'RG', '0.00', 'PHP', 'A', 'MS. ANNA', 'PARANAQUE CITY', 'Y', 'C', 'Y', NULL, NULL),
(100432, 'TRADERS EAST MARKETING', '7 TOH GUAN ROAD EAST #01-', 'ALPHA BUILDING SINGAPORE', ' ', 608599, '65 6896323', '65 6896834', 1003, 'RG', '0.00', 'EUR', 'A', 'MR. THOMAS J.L. TAN', 'SINGAPORE', 'Y', 'C', 'Y', NULL, NULL),
(100434, 'DELON LABORATORIES LTD', '69 BRUNSWICK BLVD ', 'MONTREAL, QUEBEC', 'CANADA, H9B 2N4', 0, '514-685-99', '514-685-57', 1012, 'RG', '0.00', 'USD', 'A', 'MAYER SASSON', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100438, 'GOLDEN QUEEN CORP.', '#30 CHA-CHUAN RD. LINTIN ', 'KWEISHAN SHIANG, ', 'TAOYUAN, TAIWAN', 1234, '886-3-3297', '886-3-3507', 1000, 'RG', '0.00', 'NT$', 'A', 'MS. EMILY TSAI', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100442, 'TOBACCO DF PHILIPPINES, I', 'S-115, THE CK BUSINESS CE', '3F MILE LONG BLDG', 'AMORSOLO COR, HERERRA MAK', 1200, '047-252555', '', 1016, 'RG', '0.00', 'PHP', 'A', 'MS. ALMA CELINA CLARK', 'MAKATI CITY, PHILIPPINES', 'Y', 'C', 'Y', NULL, NULL),
(100444, 'GILBERT EMERSON MARKETING', '187 WILSON ST. 1500', 'SAN JUAN, METRO, MANILA', 'PHILIPPINES', 0, '7252318/72', '7264558', 23, 'RG', '0.00', 'PHP', 'A', 'LAURENCE BESANA', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100447, 'CHAYA MARKETING INC', 'UNIT NO. 9 HORSESHOE STRE', 'RANCHO ESTATE II', 'CUPANG, ANTIPOLO CITY', 0, '9414162/68', '9485411', 22, 'CO', '25.00', 'USD', 'A', 'BOBBY UY/MICHAEL', 'SAME AS ABOVE ', 'Y', 'C', 'Y', '0071', NULL),
(100449, 'CONTEMPO MIX', 'CAR HALL CORNER TWENNING ', 'HIGHWAY,CLARKFIELD,ANGELE', 'PAMPANGA', 0, '4219184', '4819184', 22, 'CO', '22.00', 'PHP', 'A', 'LILIA CHING', 'SAME AS ABOVE', 'Y', 'C', 'Y', '0019', NULL),
(100452, 'SAM (ONE) COMM\\''L', '8/F NDCCC BLDG.STA.ELENA ', 'JUAN LUNA STS.,', 'BINONDO,MANILA', 0, '2553277,25', '2423755', 22, 'CO', '22.00', 'PHP', 'A', 'NUEL DIOLANDA', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100454, 'PHERICA INTL.CORP.', '8/F NDCCC BLDG. STA.ELENA', 'JUAN LUNA STS.,BINONDO MA', ' ', 0, '2168090', '2423755', 22, 'CO', '22.00', 'USD', 'A', 'NUEL DIOLANDA', 'SAME AS ABOVE', 'Y', 'C', 'Y', '0041', NULL),
(100456, 'LA CARLOTA FOODS ENTERPRI', 'NO. 1 JADE STREET, SAN PE', '', '', 1, '', '', 1016, 'RG', '0.00', 'PHP', 'A', 'MS. LOT CAUNTAY', 'SAN PEDRO, LAGUNA', 'Y', 'C', 'Y', NULL, NULL),
(100458, 'CWC INVENTORIES, INC.', '2644 METRO BOULEVARD', 'ST. LOUIS, MO 63043', '', 0, '314-739131', '3147397398', 1005, 'RG', '0.00', 'USD', 'A', 'RON GRAVEMANN', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100460, 'MOUNTAIN FOODS, INC.', '330 E. 25TH STREET UPLAND', '', '', 900, '00909-985-', '00909-985-', 1004, 'RG', '0.00', 'USD', 'A', 'MS. AGNES GOMEZ', 'UPLAND CA 91784', 'Y', 'C', 'Y', NULL, NULL),
(100462, 'EASY PHA-MAX PHILIPPINES ', '10TH FLOOR CITYLAND HERRE', '98 V.A RUFINO COR. VALERO', ' ', 0, '8866191-92', '8868189', 23, 'RG', '0.00', 'PHP', 'A', 'MS. CHING', '0', 'Y', 'C', 'Y', NULL, NULL),
(100464, 'FOCUS NETWORK AGENCIES (S', 'NO. 87 DEFU LANE 10 #02-0', 'TECHNO CENTRE SINGAPORE ', ' ', 539219, ' ', ' ', 1008, 'RG', '0.00', 'USD', 'A', 'MS. JAYMIE AGDIPA', 'PARANAQUE CITY', 'Y', 'C', 'Y', NULL, NULL),
(100466, 'JIA CLOTHESLINE', 'BLUMENTRIT ST., GULOD, ST', '', '', 3022, '044-641111', '044-641111', 1008, 'RG', '0.00', 'PHP', 'A', 'JOJO DELA CRUZ', 'SAME', 'Y', 'C', 'Y', NULL, NULL),
(100468, 'HUNGRY PAC', '1601 C. AGUILA ST. SAN MI', '', '', 1000, '7360092', '', 23, 'RG', '0.00', 'PHP', 'A', 'CYNTHIA ANG', '1601 C. AGUILA ST. SAN MIGUEL', 'Y', 'C', 'Y', NULL, NULL),
(100470, 'ULTRA BGL INCORPORATED', '99 MARIA CLARA ST. QUEZON', ' ', ' ', 1, '743-0158', '743-0152', 1016, 'RG', '0.00', 'PHP', 'A', 'MR. BRIAN NOCON', 'QUEZON CITY', 'Y', 'C', 'Y', NULL, NULL),
(100472, 'MIDLAND PACIFIC FOOD CORP', 'UNIT 713 GLOBE TELECOM PL', '', '', 1550, '631-4601', '638-2365', 1016, 'RG', '0.00', 'PHP', 'A', 'MS. JOSE AMADOR', 'MANDALUYONG CITY', 'Y', 'C', 'Y', NULL, NULL),
(100475, 'FISH FOR THE GODS TRADING', '56 PALIO ST. PROJECT II Q', ' ', ' ', 1102, '632-928985', ' ', 22, 'RG', '0.00', 'PHP', 'A', 'MR. JESSIE SAYSON', '56 PALIO ST. PROJ II Q.C.', 'Y', 'C', 'Y', NULL, NULL),
(100477, 'FIRST EXCELSIOR INC.', 'STERLING INDUSTRIAL PARK,', 'STERLING INDUSTRIAL PARK,', ' ', 0, '409-3079', '2859560', 1017, 'CO', '20.00', 'USD', 'A', 'JEFFREY CHUA', 'STERLING INDUSTRIAL PARK,CENTRAL AVE. LT.17,IBA MEYCAUAYAN BULACAN', 'Y', 'C', 'Y', '0078', NULL),
(100480, 'ANCHOR HOCKING COMPANY', '#1115 WEST FIFTG AVE. LAN', 'OHIO 43130 USA', '', 43130, '', '', 1003, 'RG', '0.00', 'USD', 'A', 'MR. FRED HAPAK', '#1115 WEST FIFTG AVE. LANCASTER. OHIO 43130 USA', 'Y', 'C', 'Y', NULL, NULL),
(100483, 'TASTYFOOD INDUSTRIES (S) ', '30-B QUALITY ROAD, SINGAP', '', '', 618826, '(65)626600', '(65)626611', 1023, 'RG', '0.00', 'USD', 'A', 'MR. SLAMET', '30-B QUALITY ROAD, SINGAPORE', 'Y', 'C', 'Y', NULL, NULL),
(100485, 'SHANGHAI FOODSTUFF IMPORT', '526 SI CHUAN BEI LU, SHAN', ' ', ' ', 101, ' ', ' ', 1024, 'RG', '0.00', 'USD', 'A', 'MRS. NATALIE HUNG', '526 SI CHUAN BEI LU, SHANGHAI CHINA', 'Y', 'C', 'Y', NULL, NULL),
(100487, 'RESORT SHOP', 'CLUBHOUSE B,FONTANA RESOR', ' ', ' ', 0, '045-599531', '045-599531', 1016, 'CO', '25.00', 'USD', 'A', 'GINA CASTILLO', 'CLUBHOUSE B,FONTANA RESORT CLARKFIELD PAMPANGA', 'Y', 'C', 'Y', NULL, NULL),
(100491, 'SYSU INTERNATIONAL', '145 PANAY AVENUE QC', '', '', 0, '920-5291', '920-7520', 1016, 'RG', '0.00', 'PHP', 'A', 'MR. ROLAND GANIA', '145 PANAY AVENUE QC', 'Y', 'C', 'Y', NULL, NULL),
(100493, 'NAVARRO FOODS INT\\''L INC.', '278 PANDUCENA ST. BEBE AN', '', '', 2019, '(045)98119', '', 23, 'RG', '0.00', 'PHP', 'A', 'MS. GIL ', '278 PANDUCENA ST. BEBE ANAC, MASANTOL, PAMPANGA', 'Y', 'C', 'Y', NULL, NULL),
(100495, 'CDO FOODSPHERE, INC.', '560 WEST SERVICE ROAD, PA', ' ', ' ', 560, '2941111/43', '294-0682/8', 23, 'RG', '0.00', 'PHP', 'A', 'MR. JONATHAN BENDICION/MR. NELSON B. DIAZ', '560 WEST SERVICE ROAD, PASO DE BLAS, VALENZUELA CITY', 'Y', 'C', 'Y', NULL, NULL),
(100496, 'CENTURY CANNING CORPORATI', 'RM 806 CENTERPOINT BLDG J', ' ', ' ', 1400, '5209180', '5209188', 1026, 'RG', '0.00', 'PHP', 'A', 'MR', 'RM 806 CENTERPOINT BLDG JULIA VARGAS COR. GARMET ST., ORTIGAS CENTER, PASIG CITY', 'Y', 'C', 'Y', NULL, NULL),
(100497, 'SL AGRITECH CORPORATION', 'STERLING PLACE, 2302 PASO', '', '', 1231, '(0632)8137', '(0632)888-', 23, 'RG', '0.00', 'PHP', 'A', 'MS. CAROLYN LOPEZ', 'STERLING PLACE, 2302 PASONG TAMO EXTENSION, MKT CITY', 'Y', 'C', 'Y', NULL, NULL),
(100500, 'DN FRUIT BUNCH-FRUITS', '48 - C BRGY. PANSOL, KATI', ' ', ' ', 1000, '434-7530', '434-7530', 23, 'CO', '16.00', 'PHP', 'A', 'MS. IMELDA CHAVEZ', '48 - C BRGY. PANSOL, KATIPUNAN QC', 'Y', 'C', 'Y', '0089', NULL),
(100502, 'DERICH DIAMOND LAB PHARMA', '8 FERIA RD COMMONWEALTH A', 'DILIMAN, QUEZON CITY ', 'PHILIPPINES', 1121, '9327555', '', 23, 'RG', '0.00', 'PHP', 'A', 'TOTI VILLAVERDE', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100504, 'TIMES STORE', 'RM 203 LEE BUS. CENTER, J', 'CRUZ ST., DAVAO CITY', '', 0, '986-2910', '8175362', 1017, 'CO', '25.00', 'USD', 'A', 'OBERLIE PAJARILLO', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100507, 'CREATIVE BAKERS COMPANY I', '50-60 SAN RAFAEL ST.,', 'MANDALUYONG CITY', 'PHILIPPINES', 0, ' ', ' ', 22, 'RG', '0.00', 'PHP', 'A', 'MS. SHIRLEY', 'PHILIPPINES', 'Y', 'C', 'Y', NULL, NULL),
(100509, 'MARBY FOOD VENTURES CORP', '1002 SAN FRANCISCO ', 'BULACAN 3017', ' ', 0, ' ', ' ', 22, 'RG', '0.00', 'PHP', 'A', 'MS. JURELYN', 'MANDALUYONG', 'Y', 'C', 'Y', NULL, NULL),
(100511, 'CJ CHEILJEDANG CORPORATIO', 'CJ BLDG. 500 5-GA, NAMDAE', '', '', 0, '', '', 1004, 'RG', '0.00', 'USD', 'A', 'MR. LEE TAE HEE', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100513, 'MIROVENTURES INC.', '142 MOSCOW ST., PASIG GRE', ' ', ' ', 0, '7487083', '7481247', 1017, 'CO', '25.00', 'PHP', 'A', 'MIGUEL SANTIAGO', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100515, 'BARGAIN BRANDS VENTURES I', '11 PASEO LEON STREET,LAS ', 'VERDE,PASIG CITY', ' ', 0, '6319047', ' ', 22, 'CO', '25.00', 'PHP', 'A', 'ENRIQUE SANTIAGO', 'SAME AS ABOVE', 'Y', 'C', 'Y', '0080', NULL),
(100517, 'TATUM & COMPANY INC.', 'BLK.83,LT 10,LAGRO NOVALI', 'QUEZON CITY', '', 0, '9399821', '9399821', 1017, 'CO', '25.00', 'USD', 'A', 'VICENTA LINGAD', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100520, 'WS SPORTS INC.', 'JME BLDG.35 CALBAYOG ST. ', 'MANDALUYONG CITY', ' ', 0, '535-2793', '534-9467', 1017, 'CO', '25.00', 'USD', 'A', 'ROMMEL GABALDON', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100522, 'MOUNTAIN STAR TEXTILE MIL', 'BO.SAN JOSE RODRIGUEZ RIZ', ' ', ' ', 0, '330-7611', '362-1953', 22, 'CO', '25.00', 'USD', 'A', 'MICHELLE CUARTERO', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100525, 'CLC MARKETING VENTURES CO', '114-116-A SAUYO RD.NOVALI', 'QUEZON CITY', ' ', 0, '939-7292', '456-1413', 1017, 'CO', '25.00', 'USD', 'A', 'GEORGE CHUA', 'SAME AS ABOVE', 'Y', 'C', 'Y', '0070', NULL),
(100527, 'MARKED DOWN VALUE RETAILE', '127 SAMPAGUITA AVE., UPS ', 'PARANAQUE CITY', ' ', 0, '842-7490', '821-2674', 22, 'CO', '25.00', 'USD', 'A', 'JESSIE SAYSON', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100529, 'WONDER BOOK CORP.', '2356-2360 J.A. SANTOS DRI', 'PARANAQUE CITY', ' ', 0, '851-9887', '851-8698', 1017, 'CO', '25.00', 'PHP', 'A', 'MAE RAMIREZ', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100532, 'HOME EXPRESSIONS CONCEPTS', '186 A. BONIFACIO AVE., ST', '', '', 1900, '', '', 22, 'CO', '25.00', 'USD', 'A', 'GIL TURLA', '186 A. BONIFACIO AVE., STO. NI?O CAINTA RIZAL 1900', 'Y', 'C', 'Y', NULL, NULL),
(100534, 'JOYCE & DIANA WORLDWIDE I', '8006 PIONEER CENTER BLDG.', ' ', ' ', 1603, '6340459', '6871157', 23, 'CO', '0.00', 'PHP', 'A', 'FUNG SHUM WAI FUN', '8006 PIONEER CENTER BLDG. CORNER UNILAB & BRIXTON STS. BRGY. KAPITOLYO PASIG CITY', 'Y', 'C', 'Y', '0082', NULL),
(100535, 'JOYCE & DIANA WORLDWIDE, ', '8006 PIONEER CENTER BLDG.', '', '', 1603, '6340459', '6871157', 22, 'CO', '25.00', 'USD', 'A', 'FUNG SHUM WAI FUN', '8006 PIONEER CENTER BLDG. CORNER UNILAB & BRIXTON STS. BRGY. KAPITOLYO PASIG CITY', 'Y', 'C', 'Y', '0082', NULL),
(100537, 'DN FRUIT BUNCH-SEAFOODS', '48- C BRGY. PANSOL KATIPU', ' ', ' ', 1103, ' ', ' ', 1017, 'CO', '12.00', 'PHP', 'A', 'IMELDA CUEVAS CHAVEZ', '48- C BRGY PANSOL KATIPUNAN Q.C.', 'Y', 'C', 'Y', '0089', NULL),
(100539, 'MANILA BAMBI FOODS COMPAN', '1117-G APACIBLE ST. PACO ', '', '', 1000, '', '', 1016, 'RG', '0.00', 'PHP', 'A', 'MR. MICHAEL TEJUMAL', '1117-G. APACIBLE ST. PACO MANILA', 'Y', 'C', 'Y', NULL, NULL),
(100541, 'MR. TOK GENERAL MERCHANDI', '#84 TELEPATIO, SAN ILDEFO', ' ', ' ', 1000, '455-2925 l', ' ', 22, 'RG', '0.00', 'PHP', 'A', 'MS. MARIE ANGULO', 'SAN ILDEFONSO, BULACAN', 'Y', 'C', 'Y', NULL, NULL),
(100545, 'GOLDEN ACRES FOOD SERVICE', '2188 ELISCO ROAD, BARRIO ', ' ', ' ', 10000, ' ', ' ', 1016, 'CG', '0.00', 'PHP', 'A', 'CHRISTIAN L. MADRID', '2188 ELISCO ROAD, BARRIO TIPAS IBAYO, TAGUIG CITY', 'Y', 'C', 'Y', NULL, NULL),
(100549, 'FRABELLE GROUP OF COMPANI', '1051 NORTH BAY BOULEVARD,', '', '', 1000, '', '281-2840', 1016, 'CG', '0.00', 'PHP', 'A', 'ARNEL G. BIRAGUAS', '1051 NORTH BAY BOULEVARD, NAVOTAS, METRO MANILA, PHILS.', 'Y', 'C', 'Y', NULL, NULL),
(100551, 'KING SUE HAM & SAUSAGE CO', 'SYNERGY BLDG. NO. 80 7TH ', '402 TANDANG SORA ST. CORN', ' ', 1000, ' ', ' ', 1016, 'CG', '0.00', 'PHP', 'A', 'TENYLLE KING ANG ', 'SYNERGY BLDG. NO. 80 7TH AVE., GRACE PARK, CALOOCAN CITY', 'Y', 'C', 'Y', NULL, NULL),
(100557, 'HELLO DOLLY MFG. INC.', '675 QUIRINO AVE.,SAN DION', 'PARANAQUE CITY', ' ', 0, '826-5571', '825-5745', 23, 'RG', '0.00', 'PHP', 'A', 'HARRY UTTAM', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100559, 'J@J FRUITS AND VEGETABLE', 'ALINAP ST., BARANGAY BALY', '', '', 0, '0920-954-6', '047-224-60', 22, 'CO', '16.00', 'PHP', 'A', 'MS. SHIZA KAYE PIMENTEL ', 'ALINAP ST., BARANGAY BALAYBAY, CASTILLEJOS, ZAMBALES  ', 'Y', 'C', 'Y', NULL, NULL),
(100565, 'PACIFIC MEAT COMPANY INC.', 'RM. 806 CENTERPOINT BLDG.', ' ', ' ', 1000, ' ', ' ', 1026, 'RG', '0.00', 'PHP', 'A', 'BRIAN M. GUECO', 'RM. 806 CENTERPOINT BLDG.  JULIA VARGAS COR . GARNET ST. ORTIGAS CENTER PASIG CITY', 'Y', 'C', 'Y', NULL, NULL),
(100572, 'WIN WIN FOOD SINGAPORE PT', '10 ANG MO KIO STREET 65, ', ' TEDOPOINT #01-11, SINGAP', '569059', 569059, '065-6257-2', '065-6257-2', 1004, 'RG', '0.00', 'USD', 'A', 'MR. BERNARD LEE', 'SINGAPORE', 'Y', 'C', 'Y', NULL, NULL),
(100576, 'MEKENI FOOD CORPORATION', 'BALUBAD PORAC, PAMPANGA Q', '', '', 1000, '', '', 1017, 'RG', '0.00', 'PHP', 'A', 'MR. ERIC OCAMPO', 'BALUBAD PORAC PAMPANGA, QC', 'Y', 'C', 'Y', NULL, NULL),
(100578, 'SAN MIGUEL INC. - MAGNOLI', '23RD FLR. JMT CONDOMINIUM', ' ', ' ', 1605, ' ', ' ', 1018, 'CO', '12.00', 'PHP', 'A', 'MR. ARNEL TRAZONA', '23RD FLR. JMT CONDOMINIUM ADB AVE, ORTIGAS PASIG', 'Y', 'C', 'Y', NULL, NULL),
(100581, 'FABRIANO S.P.A. INC', '103 MERCEDEZ AVE', 'SAN MIGUEL PASIG CITY', ' ', 1109, ' ', ' ', 23, 'CO', '8.00', 'PHP', 'A', 'JOCERYL GARAY II', 'PASIG CITY', 'Y', 'C', 'Y', NULL, NULL);
INSERT INTO `tblsuppliers` (`suppCode`, `suppName`, `suppAddr1`, `suppAddr2`, `suppAddr3`, `suppZip`, `suppTel`, `suppFax`, `suppTerms`, `suppType`, `suppComm`, `suppCurr`, `suppStat`, `cntctPrson`, `cntctPrsnAdd`, `oracleTag9`, `suppTaxType`, `oracleTag9B`, `MINOR_ACCOUNT`, `taxCode`) VALUES
(100585, 'LAURA\\''S FOOD PRODUCTS CO', '305 IBANEZ ST., ANGONO RI', ' ', ' ', 1930, '451-1942', '651-3229', 1016, 'RG', '0.00', 'PHP', 'A', 'MS. IRELYN M. HINGCO', 'ANGONO RIZAL', 'Y', 'C', 'Y', NULL, NULL),
(100589, 'EMPERADOR DISTILLERS, INC', '7/F 1880 EASTWOOD AVE,', 'EASTWOOD CYBERPARK, E. RO', 'BAGUMBAYAN, Q.C', 1110, '709-2222', '709-1985', 22, 'RG', '0.00', 'PHP', 'A', 'MS. JULIE LATAM', '7/F 1880 EASTWOOD AVENUE, EASTWOOD CYBERPARK, E. RODRIGUEZ JR. AVENUE, BAGUMBAYAN, QUEZON CITY', 'Y', 'C', 'Y', NULL, NULL),
(100591, 'PHILIPPINE WINE MERCHANTS', '2253 AURORA BLVD, PASAY', ' ', ' ', 0, '832-2523', '832-2624', 1008, 'RG', '0.00', 'PHP', 'A', 'MS. DAWN IGNACIO', '2253 AURORA BOULEVARD, PASAY CITY', 'Y', 'C', 'Y', NULL, NULL),
(100594, 'PERNOD RICARD PHILIPPINES', 'SUBIC PAHILIPPINES', '', '', 1000, '', '', 1016, 'RG', '0.00', 'PHP', 'A', 'MR.', 'SUBIC', 'Y', 'C', 'Y', NULL, NULL),
(100596, 'HERMANO OIL MANUFACTURING', 'NO. 1 PIKO ST., ARTY SUBD', ' ', ' ', 1000, '939-0494', '9396441', 23, 'RG', '0.00', 'PHP', 'A', 'MR. EDWIN HERMANO', '#1 PIKO ST.M, ARTY SUBDIVISION, TALIPAPA NOVALICHES, QUAZON CITY', 'Y', 'C', 'Y', NULL, NULL),
(100598, 'SUPERB CATCH, INC.', '46 MARIA CLARA ST. ACACIA', ' ', ' ', 1474, ' ', ' ', 1017, 'RG', '0.00', 'PHP', 'A', 'JENNY UY', '46 MARIA CLARA ST. ACACIA, MALABON CITY', 'Y', 'C', 'Y', NULL, NULL),
(100601, 'SNAPDRAGON INC.', '#36 SNAPDRAGON ST., MIDTO', '', '', 1900, '6552528', '2480931', 22, 'CO', '25.00', 'PHP', 'A', 'MARIE ELOISA VIGO', '#36 SNAPDRAGON ST., MIDTOWN VILLAGE, SAN ANDRES CAINTA RIZAL', 'Y', 'C', 'Y', '0091', NULL),
(100603, 'TITANIA WINE CELLAR, INC.', 'UNIT 8, SOUTHWAY CONDOMIN', '', '', 1000, '8941371-74', '894-1378', 23, 'CG', '0.00', 'PHP', 'A', 'MR. MARTIN FRONDA', 'UNIT 8, SOUTHWAY CONDOMINIUM, 7435 YAKAL ST., SAN ANTONIO VILLAGE, MAKATI CITY, PHILIPPINES', 'Y', 'C', 'Y', NULL, NULL),
(100605, 'ESRQ TRADERS INC.', '3RD FLR. JMT CORPORATE', 'CONDOMINIUM, ADB AVE.', 'ORTIGAS CTR, PASIG CITY', 0, '6336398/63', '635-0095', 23, 'CG', '0.00', 'PHP', 'A', 'M', '3RD FLR. JMT CORPORATE, CONDOMINIUM ADB AVENUE, ORTIGAS CENTER, PASIG CITY', 'Y', 'C', 'Y', NULL, NULL),
(100609, 'TITAN BARONG OF LUMBAN', 'B7 L24 P3 STO.NINO VILL. ', ' MUNTINLUPA CITY', ' ', 0, '861-7951', '861-7951', 1017, 'CO', '25.00', 'PHP', 'A', 'JOHN TITAN', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100615, 'FUTURE TRADE INTERNATIONA', '8014 WEST SERVICE ROAD.', 'MARCELO GREEN VILLAGE', 'SUCAT PARANAQUE CITY', 0, '556-7038/5', '556-7007', 1031, 'RG', '0.00', 'USD', 'A', 'MS. ANALU SANTOS', '8014 WEST SERVICE ROAD. MARCELO GREEN VILLAGE SUCAT PARANAQUE CITY', 'Y', 'C', 'Y', NULL, NULL),
(100617, 'FISHER FARMS INCORPORATED', '65 KALAYAAN AVE. BRGY CEN', 'DAMPOL 2ND-A, PULILAN BUL', ' ', 1000, '676-2780', ' (632)299-', 1017, 'RG', '0.00', 'PHP', 'A', 'JAIME A. TO ', '65 KALAYAAN AVE. BRGY. CENTRAL, QUEZON CITY', 'Y', 'C', 'Y', NULL, NULL),
(100620, 'SAN MIGUEL BREWERY INC.', '158 20TH AVENUE', 'CUBAO, QUEZON CITY', '', 1109, '0', '0', 1026, 'RG', '0.00', 'PHP', 'A', 'MR. GILBERT GALURA', '158 20TH AVENUE CUBAO, QUEZON CITY', 'Y', 'C', 'Y', NULL, NULL),
(100621, 'ZEN ASIA', 'RM 505 DON PABLO BLDG', '114 AMORSOLO ST. LEGAZPI', 'VILLAGE MAKATI CITY', 0, '8160317-18', '813-7877', 1031, 'CG', '0.00', 'PHP', 'A', 'MS. LESLEY MERCADO', 'RM 505 DON PABLO BLDG 114 AMORSOLO ST. LEGAZPI VILLAGE MAKATI CITY', 'Y', 'C', 'Y', NULL, NULL),
(100623, 'AJINOMOTO PHILIPPINES COR', '#331 SEN. GIL PUYAT AVENU', '', '', 0, '897-38-63', '890-79-59', 1026, 'RG', '0.00', 'PHP', 'A', 'MR. JULIUS DE GUZMAN', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100625, 'BARRIO FIESTA MANUFACTURI', 'MARCOS HIGHWAY DELA PAZ, ', '', '', 1608, '682-97-71 ', '682-98-32', 23, 'RG', '0.00', 'PHP', 'A', 'MS. CRIS DANGAN', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100626, 'DIAGEO PHILIPPINES, INC', 'SUBIC BAY', ' ', ' ', 12345, ' ', ' ', 1016, 'RG', '0.00', 'PHP', 'A', 'MR. ', 'SUBIC BAY', 'Y', 'C', 'Y', NULL, NULL),
(100627, 'PHILIPPINE GENERAL MDSG. ', '76 CALBAYOG CORNER LIBERT', '', '', 0, '531-42-85', '533-27-28', 1026, 'RG', '0.00', 'PHP', 'A', 'MR. CRIS SALAZAR', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100629, 'SAN PABLO MANUFACTURING C', '16F, UCPB BLDG. MAKATI AV', ' ', ' ', 0, '891-19-19', '893-11-54', 23, 'RG', '0.00', 'PHP', 'A', 'MS. SUSAN QUITEVIS', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100630, 'NUTRI-ASIA INC.', '12F CENTERPOINT COND. GAR', '', '', 0, '636-02-79', '687-0096', 1026, 'RG', '0.00', 'PHP', 'A', 'MS. NELLI VINASOY', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100631, 'LIBERTY COMMODITIES CORPO', '8F LIBERTY BLDG. 835A ARN', '', '', 0, '840-57-93', '867-16-06', 23, 'RG', '0.00', 'PHP', 'A', 'MR. JHUN DIATA CRUZ', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100632, 'YS COMMERCIAL ENTERPRISES', '11 VALENCIA STREET, NEW M', '', '', 0, '724-67-41', '724-45-13', 23, 'RG', '0.00', 'PHP', 'A', 'MR. ANICETO JAVIER', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100635, 'LAMOIYAN CORPORATION', '15 WEST SERVICE ROAD', 'SOUTH SUPERHIGHWAY', 'PARANAQUE CITY, PHILS', 0, '8238072LOC', '8236969', 23, 'RG', '0.00', 'PHP', 'A', 'FLORDELIZA A. SORILLA', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100639, 'MEATWORLD INTL INC.', '2ND FLR UNIT II MELENDREZ', '1090 NORTHBAY BLVD. COR V', '', 1485, '2813608/28', '2834832', 1026, 'CO', '15.00', 'PHP', 'A', 'AIDA VERDER', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100641, 'ZEST-O CORPORATION', '574 EDSA CALOOCAN CITY, P', '', '', 0, '3646541', '3665198', 1017, 'RG', '0.00', 'PHP', 'A', 'MR. DELIA SAN JUAN', 'CALOOCAN CITY, PHILIPPINES', 'Y', 'C', 'Y', NULL, NULL),
(100642, 'AUTHORITY TRADING CORPORA', '3 EAST CABRAL ST., MAYSAN', ' ', ' ', 1000, '277-9727 O', '277-9733', 23, 'RG', '0.00', 'PHP', 'A', 'MS. MARIE', '#3&4 PLANTERS SUBDIVISION, RINCON VALENZUELA CITY', 'Y', 'C', 'Y', NULL, NULL),
(100643, 'REGENT FOODS CORPORATION', '80 ELISCO ROAD, BO KALAWA', '', '', 0, '643-8999', '641-5888', 22, 'RG', '0.00', 'PHP', 'A', 'MS. CORA E. PALIZA', 'PASIG CITY', 'Y', 'C', 'Y', NULL, NULL),
(100647, 'KIMBERLY-CLARK PHILIPPINE', '32/F TOWER1, THE ENTERPRI', ' 6766 AYALA AVE COR. PASE', ' MAKATI CITY,PHILIPPINES', 1200, '+632 884 8', '+632 884 8', 22, 'RG', '0.00', 'PHP', 'A', 'JES A. RONSAYRO', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100648, 'REPUBLIC BISCUIT CORPORAT', '57 GEN LUIS ST. SITIO CAP', '', '', 0, '937-4434', '936-8558', 22, 'RG', '0.00', 'PHP', 'A', 'MS. IRENE ZUNIGA', 'NOVALICHES QUEZON CITY', 'Y', 'C', 'Y', NULL, NULL),
(100649, 'J.S. UNITRADE MERCHANDISE', '31ST FLR, RAFFLES CORPORA', ' EMERALD AVE.,ORTIGAS CEN', ' PASIG CITY, PHILIPPINES', 1605, '+632 916 1', '+632 916 5', 1027, 'RG', '0.00', 'PHP', 'A', 'CANDY J. MEDINA', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100653, 'INTERNATIONAL MARKET FOCU', '2ND FLOOR TCS BLDG', '1117 M. NATIVIDAD ST., ', 'STA. CRUZ, MANILA', 0, '+632 733 8', '+632 735 6', 23, 'RG', '0.00', 'PHP', 'A', 'MS. ELLEN DUTERTE', '2ND FLR.,TCS BLDG., 1117 M. NATIVIDAD ST., STA. CRUZ, MANILA', 'Y', 'C', 'Y', NULL, NULL),
(100655, 'MEGAPOLITAN MARKETING INC', 'BLDG 2 PHIL VETERANS', 'INDUSTRIAL COMPOUND', 'TAGUIG CITY', 1116, '+ 632 984 ', '+632 984 7', 23, 'RG', '0.00', 'PHP', 'A', 'DIANNE CHERISSE A. ABU', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100657, 'COCA-COLA BOTTLERS PHILS,', '1890 PAZ GUAZON COR. OTIS', 'PACO, MANILA, PHILIPPINES', ' ', 0, '8702014', '5633226', 1017, 'RG', '0.00', 'PHP', 'A', 'MS. CHIQUI GARRIDO', 'MENDIOLA SALES OFFICE', 'Y', 'C', 'Y', NULL, NULL),
(100658, 'NESTLE PHILIPPINES, INC.', 'NESTLE CENTER 31 PLAZA DR', 'ROCKWELL CENTER, MAKATI C', 'PHILIPPINES', 0, '8980001', '8980072', 1017, 'RG', '0.00', 'PHP', 'A', 'MR. MARCO MANUEL', 'MAKATI CITY', 'Y', 'C', 'Y', NULL, NULL),
(100659, 'PHILUSA CORPORATION', '28 SHAW BLVD., COR. PIONE', 'PASIG CITY, PHILIPPINES', '', 0, '+632 631 1', '+632 635 6', 23, 'RG', '0.00', 'PHP', 'A', 'AMOR REY CAGAANAN', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100660, 'WYETH PHILIPPINES, INC.', '2286 CHINO ROCES AVENUE, ', 'P.O. BOX 1207 MCPO 1252', 'MAKATI CITY, PHILIPPINES', 0, '8846600', '8173523', 1017, 'RG', '0.00', 'PHP', 'A', 'MR. CESAR ROXAS', 'MAKATI CITY', 'Y', 'C', 'Y', NULL, NULL),
(100662, 'ALASKA MILK CORPORATION', '6/F CORITHIAN PLAZA BLDG.', '121 PASEO DE ROXAS ', 'MAKATI CITY, PHILIPPINES', 0, '8404500', '8102964', 1027, 'RG', '0.00', 'PHP', 'A', 'MS. MACY CASTRO', 'MAKATI CITY', 'Y', 'C', 'Y', NULL, NULL),
(100663, 'GREEN CROSS INCORPORATED', '14/F COMMON GOAL TOWER, F', 'MADRIGAL BUSINESS PARK, A', 'MUNTINLUPA CITY, PHILIPPI', 0, '+632 772 2', '+632 772 2', 1027, 'RG', '0.00', 'PHP', 'A', 'RONALDO A. REYES', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100664, 'DEL MONTE PHILIPPINES, IN', 'PHILIPPINES', ' ', ' ', 0, '8562888', '8563244', 1029, 'RG', '0.00', 'PHP', 'A', 'MS. KAY ARMAMENTO', 'PHILIPPINES', 'Y', 'C', 'Y', NULL, NULL),
(100666, 'SANITARY CARE PRODUCTS AS', 'NO. 25 1ST AVENUE', 'BAGUMBAYAN, TAGUIG CITY', 'PHILIPPINES', 1632, '+632 838 6', '+632 838 8', 1012, 'RG', '0.00', 'PHP', 'A', 'ALVIN PARAS', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100667, 'MONDE NISSIN CORPORATION', '9 SHERIDAN ST., MANDALUYO', '', '', 1550, '747-1762', '747-1756', 22, 'RG', '0.00', 'PHP', 'A', 'MR. MIGUEL C. CARLOS', 'MANDALUYONG CITY', 'Y', 'C', 'Y', NULL, NULL),
(100670, 'MONHEIM DIST INC.', 'SEB COMMERCIAL CTR, BALTA', 'ORTIGAS AVE. EXT., TAYTAY', '', 0, '+632 660 5', '+632 658 5', 22, 'RG', '0.00', 'PHP', 'A', 'ANN RELI', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100673, 'NEW SENORITO FROZEN FOOD ', 'NEW SENORITO BUILDING DAG', '', '', 1000, '2889990/28', '2853887', 1017, 'RG', '0.00', 'PHP', 'A', 'LANI ', 'NEW SENORITO BUILDING DAGAT DAGATAN AVENUE CORNER LIBIS CALOOCAN CITY', 'Y', 'C', 'Y', NULL, NULL),
(100677, 'ESG TRADING CORPORATION ', '117 GLADNESS ST. ANNEX 18', 'HUGO GABUNA ST. POBLACION', '', 1000, '8246106', '8241966', 1016, 'RG', '0.00', 'PHP', 'A', 'EDISON S. GABUNA SR.', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100678, 'YCTR', '1435 MAYHALIGUE ST. STA. ', ' ', ' ', 1000, '7320996', '7320996', 1017, 'CG', '0.00', 'PHP', 'A', 'TIMOTHY RAEGAN YU ', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100680, 'UNILEVER PHILIPPINES, INC', 'U.N. AVENUE, MANILA', ' ', ' ', 0, '588-88-88', '588-77-92', 1029, 'RG', '0.00', 'PHP', 'A', 'MS. JANELLE UY', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100682, 'KRAFT FOODS (PHILIPPINES)', '8378 DR. A SANTOS AVENUE ', ' ', ' ', 0, '815-72-38', '858-26-09', 1030, 'RG', '0.00', 'PHP', 'A', 'MS. BEVERLY BORROMEO', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100684, 'PROFOOD INTERNATIONAL COR', '27 MAGINOO STREET, DILIMA', 'QUEZON CITY.', 'PHILIPPINES', 0, '9214298', '9283062', 1016, 'RG', '0.00', 'PHP', 'A', 'MR. OSCAR FERNANDEZ', 'QUEZON CITY', 'Y', 'C', 'Y', NULL, NULL),
(100686, 'RFM CORPORATION ', 'RFM CORPORATE CENTER, PIO', 'CORNER SHERIDAN STREET,', 'MADALUYONG CITY. PHILIPPI', 0, '6318101', '', 1016, 'RG', '0.00', 'PHP', 'A', 'MS. ANNA SORIANO', 'MANDALUYONG CITY', 'Y', 'C', 'Y', NULL, NULL),
(100688, 'EVERGOOD FOOD PRODUCT', '#4 CAROLYN PARK,', 'BAESA 1,Q. H-WAY', 'Q.C', 1117, '3301557', '3302933', 23, 'RG', '0.00', 'PHP', 'A', 'SIR PAM / SIR DAN', 'QUEZON CUTY', 'Y', 'C', 'Y', NULL, NULL),
(100690, 'A TUNG CHINGCO MANUFACTUR', 'NO.19 GOLDEN ROAD CALOOCA', '', '', 1400, '9374366', '9374403', 1026, 'RG', '0.00', 'PHP', 'A', 'MR.ROGELIO MUSNGI', 'CALOOCAN CITY', 'Y', 'C', 'Y', NULL, NULL),
(100692, 'NEWBORN FOOD PRODUCTS, IN', 'UNIT 403 PROMAX PLACE BLD', ' ', ' ', 0, '741-25-55', '742-24-76', 22, 'RG', '0.00', 'PHP', 'A', 'MR. JEFF PENTINO', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100694, 'BENCREST INDUSTRIES, INC.', 'UNIT3-A, 917 EDSA (PHILAM', '', '', 0, '929-07-12', '411-88-10', 1008, 'RG', '0.00', 'PHP', 'A', 'MS. CHYRELL CANTA', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100696, 'COLUMBUS SEAFOODS CORPORA', 'RM. 806 CENTERPOINT BLDG.', '', '', 1400, '6341231', '6341232', 1026, 'RG', '0.00', 'PHP', 'A', 'MR.AGUINO / MR. GUECO', 'PASIG CITY', 'Y', 'C', 'Y', NULL, NULL),
(100698, 'NEW ISABELA GRAINS MILLIN', '#6 SOUTH A, BRGY. PALIGSA', '', '', 1100, '4118850', '', 23, 'RG', '0.00', 'PHP', 'A', 'MS.MICHELLE / MR.DAVID', '#6 SOUTH A, BRGY. PALIGSAHAN Q.C.', 'Y', 'C', 'Y', NULL, NULL),
(100701, 'CRYSTAL REPACKING', '15 NORTH ROAD CUBAO QUEZO', 'QC', ' ', 0, '721-6341', '413-5051', 23, 'RG', '0.00', 'PHP', 'A', 'ROMEO TAN', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100703, 'BIG BOY CONSUMER SALES IN', 'NO.9 MILLER ST. BRGY BUNG', 'QUEZON CITY', ' ', 0, '438-9398', '439-9059', 1017, 'CO', '20.00', 'PHP', 'A', 'ARNOLD PONCE', 'SAME AS ABOVE', 'Y', 'C', 'Y', '0090', NULL),
(100705, 'LIWAYWAY MARKETING CORPOR', '2225 TOLENTINO ST., PASAY', '', '', 1300, '844-8441', '844-9142', 1026, 'RG', '0.00', 'PHP', 'A', 'MS. CELY YAP', 'PASAY CITY', 'Y', 'C', 'Y', NULL, NULL),
(100707, 'M.M. GUANZON INC', 'METRO MANILA', '', '', 0, '', '', 1012, 'RG', '0.00', 'PHP', 'A', 'MR. MARI FERNANDEZ', 'MANILA', 'Y', 'C', 'Y', NULL, NULL),
(100710, 'UNIVERSAL ROBINA CORPORAT', 'LITTON MILL COMPOUND AMAN', ' ', ' ', 1600, '641-9919', ' ', 1032, 'RG', '0.00', 'PHP', 'A', 'MR. DANPER ENARLE', 'PASIG CITY', 'Y', 'C', 'Y', NULL, NULL),
(100713, 'IDS (PHILIPPINES) INC.', 'NO.29 INDUSTRIA ST.,BAGUM', 'QUEZON CITY PHILIPPINES', ' ', 0, '687-5620', '687-1574', 23, 'RG', '0.00', 'PHP', 'A', 'RASHELL CABRERA', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100715, 'JBC FOOD CORPORATION', '57 GEN LUIS ST. SITIO CAP', '', '', 0, '', '935-9278', 22, 'RG', '0.00', 'PHP', 'A', 'MS. IRENE ZUNIGA', 'NOVALICHES QUEZON CITY', 'Y', 'C', 'Y', NULL, NULL),
(100717, 'GRAND DRAGON ENTERPRISES ', '81 SEN GIL PUYAT AVE., PA', '', '', 0, '526-4731', '5257101', 22, 'RG', '0.00', 'PHP', 'A', 'MR. PAUL R. HIPOLITO', 'PASAY CITY', 'Y', 'C', 'Y', NULL, NULL),
(100719, 'SARA LEE PHILS. INC', '24TH FLR TOWER 1 INS LIFE', 'CORP AVE FILINVEST CORP C', 'MUNTINLUPA CITY1770', 1770, '772-2222', '771-0010', 23, 'RG', '0.00', 'PHP', 'A', 'JEFFREY ADAYA', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100721, 'GLOBAL WINDS', '2639 ZAMORA STREET', 'PASAY CITY PASA 1300', '', 1300, '833-1429', '552-7155', 23, 'RG', '0.00', 'PHP', 'A', 'RACHEL RIVERA', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100724, 'ASSI MART (SHINMART)', 'MAKATI CITY', '', '', 0, '895-9456', '899-5980', 23, 'RG', '0.00', 'PHP', 'A', 'MR.', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100728, 'COLGATE-PALMOLIVE PHILS I', '1049 J. P. RIZAL STREET ', 'GUADALUPE VIEJO,', 'MAKATI CITY', 0, ' ', ' ', 1027, 'RG', '0.00', 'PHP', 'A', 'KHENDI LOIZ', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100731, 'NEW HATCHIN TRADING CORPO', '7602 SACRED HEART ST. MET', 'SAN ANTONIO VILLAGE, MAKA', '', 0, '897-72-07', '897-72-09', 1016, 'RG', '0.00', 'PHP', 'A', 'MR. ARNOLD VILLARUBIA', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100735, 'PROCTER & GAMBLE DIST PHI', 'P.O. BOX 1396', 'MAKATI CITY', '', 0, '558-8800', '', 1027, 'RG', '0.00', 'PHP', 'A', 'ALLEN CUADERNO', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100738, 'HUN & HAL WELLNESS DISTRI', 'G/F GOLDLOOP TOWER, ', 'J. ESCRIBA DRIVE, ', 'ORTIGAS CENTER, PASIG CIT', 0, ' ', ' ', 23, 'RG', '0.00', 'PHP', 'A', 'JENNAFER ILAGAN', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100740, 'ASIACONNECT, INC.', 'SUITE 1003 10FLR THE RICH', ' ', ' ', 1605, '637-5181/6', '638-4234', 22, 'RG', '0.00', 'PHP', 'A', 'MR. JHUN MALLANAO JR.', 'SUITE 1003 10FLR THE RICHMOND PLAZA, 21 SAN MIGUEL AVE., ORTIGAS CENTER, PASIG CITY, 1605, PHIL.', 'Y', 'C', 'Y', NULL, NULL),
(100742, 'BEAUTY ELEMENTS VENTURES ', 'BRGY SAN ANTONIO ', 'MAKATIC CITY ', '', 0, '', '', 23, 'RG', '0.00', 'PHP', 'A', 'BARBARA MENDOZA', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100754, 'LOREAL PHILIPPINES INC', 'ADB AVE., CORNER PROVEDA ', 'ORTIGAS CENTER, PASIG CIT', '', 1605, '632 632 02', '632 636 61', 23, 'RG', '0.00', 'PHP', 'A', 'MA. CARMELA RONO', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100756, 'GLORIOUS COMMERCIAL EXPOR', '#8272 DAPITAN ST., GUADAL', ' ', ' ', 1212, '8823985-87', '8821279', 1008, 'RG', '0.00', 'PHP', 'A', 'RAM VARANDMALL', '#8272 DAPITAN ST., GUADALUPE NUEVO MAKATI CITY', 'Y', 'C', 'Y', NULL, NULL),
(100758, 'GRAND ALPHATECH INTL CORP', 'STO. DOMINGO ROAD MAPAYAP', ' ', ' ', 4027, '637-3433', '637-3392', 23, 'RG', '0.00', 'PHP', 'A', 'MS. CECILLE ABELLERA', 'CALAMBA LAGUNA', 'Y', 'C', 'Y', NULL, NULL),
(100760, 'SOUTHEAST ASIA FOOD, INC.', '12/F CENTERPOINT CONDOMIN', 'GARNET ROAD, COR. JULIA V', 'ORTIGAS CENTER, PASIG CIT', 0, '636-02-79', '637-49-19', 1026, 'RG', '0.00', 'PHP', 'A', 'MS. NELLI VINASOY', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100763, 'DCI KELSEN GROUP', 'BREDGADE 27 8766 NORRE SN', '', '', 0, '', '', 1033, 'RG', '0.00', 'EUR', 'A', 'MS. HELLE NIELSEN', 'NORRE SNEDE', 'Y', 'C', 'Y', NULL, NULL),
(100765, 'PUREGOLD PRICE CLUB - DAU', 'MC ARTHUR HIWAY DAU MABAL', '', '', 0, '2468385', '2468339', 23, 'RG', '0.00', 'PHP', 'A', 'MR.JAMES BALINGIT', 'DAU MABALACAT PAMP.', 'Y', 'C', 'Y', NULL, NULL),
(100768, 'JOHNSON & JOHNSON PHILS I', 'EDISON  AVENUE BO ', 'IBAYO, PARA?AQUE CITY', ' ', 1700, '2358084', '2358086', 22, 'RG', '0.00', 'PHP', 'A', 'MA. THERESA MERCADO', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100771, 'BOUNTY FRESH  CHICKEN', '179 MARIANO PONCE ST. CAL', '', '', 1400, '', '', 22, 'RG', '0.00', 'PHP', 'A', 'MR. JUN NANQUIL', '179 MARIANO PONCE ST. CALOOCAN CITY PILIPPINES', 'Y', 'C', 'Y', NULL, NULL),
(100773, 'LANDMARK (MANAGEMENT) P&C', '3/F EHA BLDG.,DUTY FREE P', 'NINOY AQUINO AVE., SUCAT ', '', 0, '', '', 1017, 'CO', '25.00', 'USD', 'A', 'VALERIANO CALMA', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100775, 'YUNIMEX TRADING INC A', 'G/F MATHEUS BLDG DON PEDR', '', '', 1000, '', '', 1017, 'CO', '25.00', 'PHP', 'A', 'MR. OH', 'G/F MATHEUS BLDG DON PEDRO ST. POBLACIO. MAKATI', 'Y', 'C', 'Y', '0093', NULL),
(100776, 'YUNIMEX TRADING INC B', 'G/F MATHEUS BLDG DON PEDR', '', '', 1000, '', '', 1017, 'CO', '25.00', 'PHP', 'A', 'MR. OH', 'G/F MATHEUS BLDG DON PEDRO ST. POBLACIO. MAKATI', 'Y', 'C', 'Y', '0093', NULL),
(100778, 'MICHELL CAKES & PASTRIES', 'BALANGA BATAAN', ' ', ' ', 1000, ' ', ' ', 22, 'CG', '0.00', 'PHP', 'A', 'MS. ZENAIDA GONZALES', 'BALANGA, BATAAN', 'Y', 'C', 'Y', NULL, NULL),
(100780, 'JLT ICE CUBE SPECIALIST', '48 JONES ST. NEW ASINAN O', '', '', 2000, '', '', 22, 'CG', '0.00', 'PHP', 'A', 'JERRY TALOY', '48 JONES ST. NEW ASINAN OLONGAPO CITY', 'Y', 'C', 'Y', NULL, NULL),
(100782, 'BEAM GLOBAL PHILIPPINES I', 'LEVEL 15, WYNSUM CORP PLA', 'EMERALD AVENUE', 'ORTIGAS CENTER PASIG CITY', 1600, '667-3333', '667-3156', 1000, 'RG', '0.00', 'USD', 'A', 'M', 'PASIG CITY', 'Y', 'C', 'Y', NULL, NULL),
(100784, 'LACTO-B INCORPORATED', '1443 PONCE ST., C.M. RECT', 'ANGELES CITY', ' ', 1000, ' ', ' ', 1018, 'RG', '0.00', 'PHP', 'A', 'MR. RICHARD TORRESS', 'ANGELES CITY', 'Y', 'C', 'Y', NULL, NULL),
(100786, 'CDJT ENTERPRISES ', 'MMG II MAGSAYSAY RD. SAN ', '', '', 4023, '5565984', '8681891', 22, 'RG', '0.00', 'PHP', 'A', 'MS.CECILIA TAN', 'MMG II MAGSAYSAY RD. SAN ANTONIO SAN PEDRO LAGUNA', 'Y', 'C', 'Y', NULL, NULL),
(100788, 'MICRO-B INCORPORATED', '131 GORDON AVE ', 'NEW KALALAKE ', 'OLONGAPO CITY', 2200, '', '', 1026, 'RG', '0.00', 'PHP', 'A', 'MR. RICHARD TORRES', 'MANILA', 'Y', 'C', 'Y', NULL, NULL),
(100790, 'GINEBRA SAN MIGUEL INC.', '208 BLDG2 19 GEN ATIENZA ', '', '', 1000, '6322524', '', 1018, 'RG', '0.00', 'PHP', 'A', 'MR. MARK MERCADO', '208 BLDG2 19 GEN ATIENZA', 'Y', 'C', 'Y', NULL, NULL),
(100796, 'HELLO DOLLY MFG.INC.', '0675 QURINO AVE.,SAN DION', ' PARANAQUE METRO MLA', ' ', 0, '8265571', '8255745', 1017, 'CO', '25.00', 'PHP', 'A', 'HARRY UTTAM', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100798, 'MAYCAR F00DS, INC.', '404 AMANG RODRIGUEZ AVE. ', ' ', ' ', 1000, ' ', ' ', 23, 'CG', '0.00', 'PHP', 'A', 'JING MOLINA', '404 AMANG RODRIGUEZ AVE. MANGGAHAN PASIG CITY', 'Y', 'C', 'Y', NULL, NULL),
(100800, 'ADREM DISTRIBUTION SPECIA', 'LAGUNDI, MEXICO, PAMPANGA', '', '', 0, '045-963-09', '045-860-17', 23, 'RG', '0.00', 'PHP', 'A', 'MS. REMIE SANTOS', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100802, 'MARKET REACH INT\\''L RESOU', 'UNIT-102 MERVIN 2000 BLDG', 'P. OCAMPO ST., BRGY. LA P', 'MAKATI CITY, METRO MANILA', 1000, '4032487', '4033193', 1034, 'RG', '0.00', 'USD', 'A', 'MR. JACK', 'MAKATI CITY', 'Y', 'C', 'Y', NULL, NULL),
(100804, 'MOONDISH FOODS CORPORATIO', '71-B KKK BONDED PACKAGING', '', '', 0, '8392057', '8391871', 23, 'RG', '0.00', 'PHP', 'A', 'MS.IRELYN', '71-B KKK BONDED PACKAGING WAREHOUSE DBP AVE. TAGUIG CITY', 'Y', 'C', 'Y', NULL, NULL),
(100806, 'REYSONS FOOD PROCESSING', '102 TALAYAN ST. TALAYAN V', '', '', 0, '412-9340', '372-57-13', 23, 'RG', '0.00', 'PHP', 'A', 'MR. ALLAN SARMIENTO', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100808, 'ZWANENBERG FOOD GROUP USA', '3640 MUDDY CREEK ROAD CIN', '', '', 45238, '', '', 1004, 'RG', '0.00', 'USD', 'A', 'MS. MARIEKE VAN WOUDENBERG', '3640 MUDDY CREEK ROAD CINCINATI, OH', 'Y', 'C', 'Y', NULL, NULL),
(100810, 'SHARMILA, INC.', '1117-B GREGORIO APACIBLE ', '', '', 1000, '5252744', '5236551', 23, 'RG', '0.00', 'PHP', 'A', 'MR.VIO CASANADA JR.', '1117-B GREGORIO APACIBLE ST., PACO MLA.', 'Y', 'C', 'Y', NULL, NULL),
(100812, 'RHEINLAND DISTRIBUTIONS I', 'MONHEIM BUILDING II BALTA', 'ORTIGAS AVENUE ', 'TAYTAY RIZAL', 0, '2844133', '2844134', 23, 'RG', '0.00', 'PHP', 'A', 'ANTHONY BALUYO', 'SAME AS ABOVE', 'Y', 'C', 'Y', NULL, NULL),
(100813, 'ORIENT INTERNATIONAL BUSI', '#755 WHEIHAI ROAD, FLOOR ', '', '', 200023, '86-21-3406', '86-21-6315', 1004, 'RG', '0.00', 'USD', 'A', 'SHERWIN', '#755 WHEIHAI ROAD, FLOOR 7, SHANGHAI', 'Y', 'C', 'Y', NULL, NULL),
(100817, 'MULTIRICH FOODS CORPORATI', '9007 TS MIGUEL VILLARICA ', 'PATUBIG MARILAO ', 'BULACAN', 3019, ' ', '935-9278', 22, 'RG', '0.00', 'PHP', 'A', 'MS. IRENE ZUNIGA', 'NOVALICHES QUEZON CITY', 'Y', 'C', 'Y', NULL, NULL),
(100819, 'OLONGAPO GAS CORPORATION', '1436 TULIO STREET, ', 'TABACUTABACUHAN,', 'STA. RITA, OLONGAPO CITY', 0, '047-222362', '047-223723', 1026, 'RG', '0.00', 'PHP', 'A', 'MR. NEMENCIO C. RODRIGUEZ', 'N/A', 'Y', 'C', 'Y', NULL, NULL),
(100822, 'CITIFOOD INDUSTRIES ', 'CONSOJONG TALISAY CITY ', 'CEBU CITY ', ' ', 0, '032-272809', '032-272419', 23, 'RG', '0.00', 'PHP', 'A', 'MS. SUSIE', 'CEBU CITY ', 'Y', 'C', 'Y', NULL, NULL),
(100824, 'SAMSUNG ELECTRONICS PHILS', '8TH FLOOR HANJINPHIL BLDG', 'UNIVERSITY PARKWAY NORTH ', 'BONIFACIO GLOBAL CITY ', 1, '214-7639', '214-7724', 23, 'RG', '0.00', 'PHP', 'A', 'DOMINIC ABA ', '1', 'Y', 'C', 'Y', NULL, NULL),
(100825, 'QFI DISCOM.CL INC.', 'WAREHOUSE #13 BALITI CITY', 'OF SAN FERNANDO ', 'PAMPANGA ', 2000, '1', '1', 22, 'RG', '0.00', 'PHP', 'A', 'MR. PETER ', '1', 'Y', 'C', 'Y', NULL, NULL),
(100830, 'S P I CORPORATION', 'SILANGAN AIRSTRIP INDUSTR', 'ESTATE CANLUBANG  CALAMBA', 'CITY LAGUNA', 4028, '', '', 22, 'RG', '0.00', 'PHP', 'A', 'IRENE ZUNIGA', 'SAME', 'Y', 'C', 'Y', NULL, NULL),
(100835, 'JOYCE AND DIANA WORLDWIDE', '8006 PIONEER CENTRE BLDG.', 'PIONEER ST. KAPITOLYO ', '', 1603, '634-0453', '', 23, 'RG', '0.00', 'PHP', 'A', 'MS. MARISSA', '1', 'Y', 'C', 'Y', NULL, NULL),
(100837, 'HEAVENLY BLUE ENTERPRISES', '10 GOV. PASCUAL AVE. ACAC', '', 'MALABON CITY', 1474, '1', '1', 22, 'CO', '20.00', 'PHP', 'A', 'JEFFREY CHUA ', '1', 'Y', 'C', 'Y', NULL, NULL),
(100839, 'ICHIBAN GENERAL MERCHANDI', '908 FEDERAL TOWER DASMARI', 'ST. BRGY 282 ZONE 24 SAN ', 'MANILA', 1010, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', 'MELODIE OCAMPO', '1', 'Y', 'C', 'Y', NULL, NULL),
(100841, 'FARMLAND ENTERPRISES', 'MMG 11 MAGSAYSAY ROAD SAN', 'ANTONIO SAN PEDRO ', 'LAGUNA', 4023, '1', '1', 22, 'RG', '0.00', 'PHP', 'A', 'CECILE TAN', '1', 'Y', 'C', 'Y', NULL, NULL),
(100844, 'UNI-FAB METAL INDUSTRIES,', 'BANCAL CARMONA ', 'CAVITE ', '', 4116, '4301863', '4301864', 23, 'RG', '0.00', 'PHP', 'A', 'MARGARITA JAVIER ', '1', 'Y', 'C', 'Y', NULL, NULL),
(100846, 'INTELLIGENT SKIN CARE INC', 'UNIT 2504-A WEST TOWER PS', 'CENTER EXCHANGE ROAD ORTI', 'CENTER PASIG CITY ', 1605, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', 'MR. EMMAN', '1', 'Y', 'C', 'Y', NULL, NULL),
(100849, 'DN FRUIT BUNCH-VEGETABLES', '25-B TALABA BACOOR CAVITE', '', '', 1314, '1', '1', 1026, 'CO', '16.00', 'PHP', 'A', 'IMELDA CHAVEZ', '1', 'Y', 'C', 'Y', '0089', NULL),
(100851, 'GOSONS FOOD CORP.', 'LOT 3 BLK 1 PH13 CONGRESS', 'AVE. EXT. PASONG TAMO ', 'TANDANG SORA ', 1116, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', 'LORNA SARSAGA', '1', 'Y', 'C', 'Y', NULL, NULL),
(100853, 'INT\\''L. PHARMACEUTICALS, ', 'BRGY. BACAG VILLASIS ', 'PANGASINAN', ' ', 1, '1', '1', 23, 'RG', '0.00', 'PHP', 'D', 'ENRICO BONTIA', '1', 'Y', 'C', 'Y', NULL, NULL),
(100855, 'QUANTA PAPER MARKETING IN', '568 ROSECO COMP QUIRINO ', 'QUIRINO HIGHWAY TALIPAPA ', 'NOVALICHES ', 1100, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100858, 'INT\\''L PHARMACEUTICALS, I', 'BRGY BACAG VILLASIS', 'PANGASINAN', '', 1, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100860, 'GOLDEN WINES, INC.', 'UNIT 20 2ND FLOOR TOPY\\''S', 'COMMERCIAL BLDG. INDUSTRI', 'ECONOMIA STS. BAGUMABAYAN', 1, '1', '1', 23, 'CG', '0.00', 'PHP', 'A', 'MARIBEL POBLADOR ', '1', 'Y', 'C', 'Y', NULL, NULL),
(100862, 'AIKON TEDSIM MARKETING CO', '117 P. PARADA STA. LUCIA ', 'SAN JUAN CITY', '', 1500, '726-2629', '', 1008, 'RG', '0.00', 'PHP', 'A', 'BENEDICT', '1', 'Y', 'C', 'Y', NULL, NULL),
(100866, 'BRANDLINES ENTERPRISES IN', '216 BANAWE ST. BRGY MANRE', 'Q.C.', '', 1, '3677593', '3646257', 23, 'RG', '0.00', 'PHP', 'A', 'FEA SANTIAGO', '1', 'Y', 'C', 'Y', NULL, NULL),
(100869, 'Y.M.F. CARPET INC.', '#5 TRUMAN DRIVE SOUTH EDI', 'NEW JERSEY ', '', 1, '1', '1', 1019, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100870, 'VIETNAM FOOD INDUSTRIES J', '913 TRUNG CHINH ST. TAY T', 'WARD TAN PHU DIST. HOCHIM', 'CITY VIETNAM', 1, '1', '1', 1003, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100873, 'RPG DISTRIBUTION SERVICES', '301-302 FREIGHTERS RD. ', 'CENTRAL BUSINESS PARK A. ', 'RODRIGUEZ AVE MANGGAHAN P', 1607, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', 'RONNIE CABANLIT', '1', 'Y', 'C', 'Y', NULL, NULL),
(100875, 'LE DERMA TOUCHE INC', '129 ST. JOSEPH AVE. HDLC ', 'BARANGKA MARIKINA CITY', '', 1803, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100876, 'MULTIPLAST CORP.', 'SERVICE ROAD LAWANG BATO ', 'VALENZUELA CITY', '', 1447, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', 'JEFF CHUA ', '1', 'Y', 'C', 'Y', NULL, NULL),
(100877, 'Q.C. STYROPACKAGING CORP.', '1200 EDSA BAHAY TORO 1', 'QUEZON CITY ', '', 1106, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', 'JEFF CHUA', '1', 'Y', 'C', 'Y', NULL, NULL),
(100879, 'SEVILLA CANDLE FACTORY IN', 'ACTIVIDAD COR ESPERANZA S', 'TINAJEROS MALABON', '', 1470, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', 'ELSA BESA', '1', 'Y', 'C', 'Y', NULL, NULL),
(100880, 'CHEF TONY\\''S SNACK FOODS ', 'UG/F 739 GLIO BLDG. BANAW', 'BRGY. ST. PETER ', 'QUEZON CITY', 1, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100882, 'LITTLE LAWRENCE INC.', '81 I. LOPEZ NEW ZANIGA ', ' ', 'MANDALUYONG CITY', 1550, '1', '1', 22, 'CG', '0.00', 'PHP', 'A', 'ROBERT CHUA ', '1', 'Y', 'C', 'Y', NULL, NULL),
(100884, 'MARKETREACH DISTRIBUTORS,', '15 SCT. MADRINAN SOUTH TR', 'DILIMAN Q.C.', '', 1103, '1', '1', 22, 'RG', '0.00', 'PHP', 'A', 'LORNA', '1', 'Y', 'C', 'Y', NULL, NULL),
(100885, 'SUNMARU CONFECTIONERY MAN', '1166 SOLER ST. BINONDO ', 'MANILA ', '', 1006, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', 'NENA PASCUAL ', '1', 'Y', 'C', 'Y', NULL, NULL),
(100887, 'PRIME CONFECTIONS INC.', '4 SOCORRO FERNANDEZ ST. ', 'MANDALUYONG CITY', '', 1550, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', 'MARJ BARRETO', '1', 'Y', 'C', 'Y', NULL, NULL),
(100889, 'GOURMET & WINE EXPERTS IN', 'BUILDING 14 LA FUERZA COM', '2241 DON CHINO ROCES AVE.', 'MAKATI CITY ', 1, '843-8897', '843-8907', 23, 'RG', '0.00', 'PHP', 'A', 'CARMELA FAVIA ', '1', 'Y', 'C', 'Y', NULL, NULL),
(100891, 'COUVERTURE INT\\''L. TRADIN', '2445 ARSOVEL ST. SAN ISID', 'MAKATI CITY', '', 1306, '8123601', '8446407', 23, 'RG', '0.00', 'PHP', 'A', 'MAUREEN GONZALES ', '1', 'Y', 'C', 'Y', NULL, NULL),
(100893, 'FRABELLE MARKET CORP.', '1051 NORTHBAY BLVD. NBBS', 'NAVOTAS ', '', 1485, '2836879', '2812840', 22, 'RG', '0.00', 'PHP', 'A', 'ARNEL BIRAGUAS ', '1', 'Y', 'C', 'Y', NULL, NULL),
(100895, 'MALABON SOAP & OIL IND\\''L', 'LUNA II SAN AGUSTIN MALAB', '', '', 1, '514-59-93', '', 23, 'RG', '0.00', 'PHP', 'A', 'GENY SIA', '1', 'Y', 'C', 'Y', NULL, NULL),
(100897, 'ORIENTWAY MARKETING ', '1381 FELIX HUERTAS ST. ST', '', '', 1, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', 'GENY SIA ', '1', 'Y', 'C', 'Y', NULL, NULL),
(100898, 'KSK FOOD PRODUCTS ', 'L4231 BIGNAY CALOOCAN CIT', '', '', 1448, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', 'BYRON RAAGAS', '1', 'Y', 'C', 'Y', NULL, NULL),
(100900, 'SD FOODS INC.', '226 CLAUDIO ST. BRGY SAN ', 'MORONG RIZAL', ' ', 1960, '4084280', '4133867', 22, 'CO', '15.00', 'PHP', 'A', 'FELIX GUGGENHEIM', '1', 'Y', 'C', 'Y', NULL, NULL),
(100902, 'SENORITO FROZEN FOOD ', 'DAGAT-DAGATAN AVE. COR. L', 'CALOOCAN CITY', '', 1400, '1', '1', 22, 'CG', '0.00', 'PHP', 'A', 'LANI MODELO', '1', 'Y', 'C', 'Y', NULL, NULL),
(100905, 'KING GLOBAL TRADING ', 'GATHALIAN REALTY CORP. OL', 'NATIONAL HI-WAY BANAY-BAN', 'CABUYAO LAGUNA', 4025, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', 'MICHAEL TAGLE ', '1', 'Y', 'C', 'Y', NULL, NULL),
(100906, 'INOZA INDUSTRIES INC.', '179 MARIANO PONCE ST. ', 'CALOOCAN CITY ', '', 1400, '3660616', '3678958', 23, 'RG', '0.00', 'PHP', 'A', 'JON JOAQUIN', '1', 'Y', 'C', 'Y', NULL, NULL),
(100908, 'INDUSTRIAL INTERLINK CORP', '225A KANLAON ST. MAHARLIK', 'QUEZON CITY', '', 1114, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100910, 'NUTRI-LICIOUS MARKETING C', '7 M FLORES ST. PATEROS ', 'MANILA', '', 1620, '1', '1', 22, 'RG', '0.00', 'PHP', 'A', 'ELMER REYES ', '1', 'Y', 'C', 'Y', NULL, NULL),
(100914, 'PT. UNITED ASIA LOGISTICS', 'RAYA RUNGKUT SURABAYA ', 'INDONESIA', '', 1, '1', '1', 1027, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100920, 'HD MARKETING AND DISTRIBU', '25F PHILAMLIFE TOWER PASE', 'ROXAS MAKATI CITY', '', 1226, '2174546', '', 22, 'RG', '0.00', 'PHP', 'A', 'RONNIE SANTOS ', '1', 'Y', 'C', 'Y', NULL, NULL),
(100922, 'FOODEX (CANADA), INC.', '100 PARK PLACE SUITE 275', 'SAN RAMON CA', '', 1, '1', '1', 1005, 'RG', '0.00', 'CND', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100924, 'PENGPONG FRESH FRUIT AND ', '195 RIZAL AVENUE WEST ', 'TAPINAC OLONGAPO CITY', ' ', 2200, '1', '1', 23, 'CG', '0.00', 'PHP', 'A', 'CHRISTOPHER ', '1', 'Y', 'C', 'Y', NULL, NULL),
(100926, 'MARC-ZON MERCHANDISING ', 'SIMPLE LIVING SUBD. ', 'CAMACHOBALANGA CITY BATAA', '', 2100, '4518888', '3988035', 23, 'CG', '0.00', 'PHP', 'A', 'EDWARD CARINGAN ', '1', 'Y', 'C', 'Y', NULL, NULL),
(100928, 'PINAKAMASARAP CORP.', '46 JOY ST. BALINGASA I Q.', '', '', 1115, '4180555', '4180551', 23, 'RG', '0.00', 'PHP', 'A', 'MARYROSE PADERNAL ', '1', 'Y', 'C', 'Y', NULL, NULL),
(100930, 'GLAXOSMITHKLINE PHILIPPIN', 'GLAXO WELLCOME BLDG. 2266', 'ROCES AVE. MAKATI CITY', '', 1231, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100932, 'MARCO PRODUCTS', '2840 P. ZAMORA ST. PASAY ', '', '', 1300, '8338149', '5517963', 1008, 'RG', '0.00', 'PHP', 'A', 'BERNADETTE PONCIANO', '1', 'Y', 'C', 'Y', NULL, NULL),
(100934, 'A.U.N. INDUSTRIAL SALES ', '1674 BAMBANG ST. STA. CRU', 'MANILA ', '', 1000, '7491885', '7491752', 23, 'CO', '25.00', 'PHP', 'A', 'AGNES AGRAVANTE', '1', 'Y', 'C', 'Y', NULL, NULL),
(100936, 'A-MARK TRADING & GENERAL ', '4817 RM2 V. MAPA ST. STA ', 'MANILA ', '', 1016, '7133570', '7164292', 23, 'RG', '0.00', 'PHP', 'A', 'ISABELITA MAURICIO', '1', 'Y', 'C', 'Y', NULL, NULL),
(100938, 'RODZON MARKETING CORP.', '2451 LAKANDULA ST. PASAY ', '', '', 1300, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', 'JOSELITO TIANZON', '1', 'Y', 'C', 'Y', NULL, NULL),
(100941, 'SPLASH CORP.', 'HBC CORPORATE CTR 2/F #54', 'MINDANAO AVE. Q.C.', '', 860, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', 'DIANNE ABU', '1', 'Y', 'C', 'Y', NULL, NULL),
(100943, 'CONCORD FISHING CORP.', '70 C-3 RD NEAR TORCILLO S', 'DAGAT DAGATAN  BRGY. 22', 'CALOOCAN CITY', 1400, '1', '1', 22, 'CG', '0.00', 'PHP', 'A', 'WILBERT VALENTINO ', '1', 'Y', 'C', 'Y', NULL, NULL),
(100944, 'WESTPAC MEAT PROCESSING C', '1068 NORTHBAY BLVD. NBBS ', 'NAVOTAS ', '', 1485, '1', '1', 22, 'CG', '0.00', 'PHP', 'A', 'WILBERT VALENTINO ', '1', 'Y', 'C', 'Y', NULL, NULL),
(100946, 'FOOD INDUSTRIES INC.', 'NO. 2116 PASONG TAMO ST. ', 'PIO DEL PILAR MAKATI CITY', '', 1107, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100948, '5660 TRADING ', '35 WILSON ST. GREENHILLS ', 'SAN JUAN MANILA', '', 1502, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', 'MELODIE OCAMPO', '1', 'Y', 'C', 'Y', NULL, NULL),
(100950, 'MFT! ENTERPRISES ', '2364 OPALO ST. SAN ANDRES', 'BUKID MANILA', '', 1, '7889911', '4548807', 1008, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100952, 'SUKATA INC.', '700-C DEL MONTE AVE. BRGY', 'TALAYAN Q.C.', '', 1, '3618076', '3648429', 23, 'RG', '0.00', 'PHP', 'A', 'CINDY NG', '1', 'Y', 'C', 'Y', NULL, NULL),
(100954, 'HEINZ CO. AUSTRALIA LTD.', '2 SOUTHBANK BLVD. ', '', '', 1, '98615757', '1', 1013, 'RG', '0.00', 'PHP', 'A', 'MILETTE OROSA', '1', 'Y', 'C', 'Y', NULL, NULL),
(100956, 'FILIBERTO BIANCONI', 'PIAZZA DEL MERCATO ITALY', ' ', ' ', 1, '1', '1', 1003, 'RG', '0.00', 'EUR', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100958, 'RENAISSANCE FOODS CORP.', '117 PROGRESS AVE. COR. IN', 'DRIVE CARMELRAY INDL PARK', 'CANLUBANG CALAMBA LAHUNA', 4028, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100959, 'RESOURCEFUL INTERNATIONAL', '82 N. ROXAS ST. BRGY. STO', 'QUEZON CITY', '', 1, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', 'VIVENCIO TANG JR.', '1', 'Y', 'C', 'Y', NULL, NULL),
(100961, 'DESIGNER BLOOMS ', '2ND FLOOR WIMPEX BLDG. KM', 'WEST SERVICE ROAD PARA?AQ', ' ', 1700, '1', '1', 22, 'CO', '15.00', 'PHP', 'A', 'TING GARCIA', '1', 'Y', 'C', 'Y', '0092', NULL),
(100963, 'GOLDENTOP MARKETING INT\\''', '136 BIAK NA BATO COR. SCT', 'BRGY. SIENA Q.C.', '', 1, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', 'BABY AMAGNA ', '1', 'Y', 'C', 'Y', NULL, NULL),
(100965, 'LUSITANO INC.', '1379 SAN GREGORIO ST. BRG', '678 ZONE 074 PACO MANILA', '', 1, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', 'C', 'Y', NULL, NULL),
(100968, 'WALFOODS INC.', '3011 ABUCAY ST. MANUGUIT ', 'MANILA ', '', 1012, '1', '1', 1031, 'RG', '0.00', 'PHP', 'A', 'ELVIRA SANTIAGO ', '1', 'Y', 'C', 'Y', NULL, NULL),
(100970, 'SERMASISON CORPORATION I', '117 SCOUT FUENTEBELLA ST.', '', '', 1103, '1', '1', 1005, 'RG', '0.00', 'AUD', 'A', 'JASPER SIY ', '1', 'Y', NULL, 'Y', NULL, NULL),
(100972, 'TUBU FOOD MANUFACTURING C', '81 ZUZUARREGUI ST. MATAND', 'BALARA Q.C.', '', 1, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', 'AGIE BEDOR ', '1', 'Y', NULL, 'Y', NULL, NULL),
(100974, 'SYSHIONG COMMERCIAL TRADI', '1 MAGPOC ST. CUPANG ', 'ANTIPOLO CITY RIZAL', '', 1870, '1', '1', 22, 'CO', '25.00', 'PHP', 'A', 'ARSENIO UNGRIANO ', '1', 'Y', NULL, 'Y', NULL, NULL),
(100976, 'CONNECTRESEARCH CORP.', 'UNIT 1003 RICHMONDE PLAZA', 'MIGUEL AVE. ORTIGAS CTR. ', 'ANTONIO PASIG CITY', 1605, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', 'MARIFLOR PASION', '1', 'Y', NULL, 'Y', NULL, NULL),
(100978, 'CANADIAN MANUFACTURING', '38 STA.ANA DRIVE SUN VALL', 'SUBD. PARANAQUE CITY', '', 1700, '1', '1', 1008, 'RG', '0.00', 'PHP', 'A', 'HIRANANO DARNATAM', '1', 'Y', NULL, 'Y', NULL, NULL),
(100980, 'V-PLAST TRADING ', '2 CATTLEYA ST. VALLE VERD', 'UGONG PASIG CITY', '', 1604, '1', '1', 1008, 'RG', '0.00', 'PHP', 'A', 'KATHLEEN LEDESMA ', '1', 'Y', NULL, 'Y', NULL, NULL),
(100982, 'HAI HUANG FOOD PRODUCTS ', '137 ENCARNACION SAN RAFAE', 'VILL NAVOTAS METRO MANILA', '', 1485, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', 'NELSON SALCEDO ', '1', 'Y', NULL, 'Y', NULL, NULL),
(102081, 'SUNCREST FOODS INCORPORAT', 'LLANO RD. LLANO CALOOCAN ', ' ', ' ', 1400, '9831931', '2238959', 22, 'RG', '0.00', 'PHP', 'A', 'CRIS SALAZAR', '1', 'Y', NULL, 'Y', NULL, NULL),
(102083, 'ROEL\\''S FOOD CORP.', '102 COMMERCIAL ST. MARISO', 'VILLAGE NINOY AQUINO ANGE', '', 2009, '1', '1', 22, 'CG', '0.00', 'PHP', 'A', '1', '1', 'Y', NULL, 'Y', NULL, NULL),
(102085, 'BRIGHTHEAD TRADING INC.', '104 FRANCISCO ST. BRGY 75', 'CALOOCAN CITY', '', 1400, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', 'NILO GODINO', '1', 'Y', NULL, 'Y', NULL, NULL),
(102088, 'MERITUS PRIME DISTRIBUTIO', '1379 SAN GREGORIO ST.', 'BRGY. 678 ZONE 074 PACO M', '', 1000, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', NULL, 'Y', NULL, NULL),
(102089, 'BUREAU OF INTERNAL REVENU', 'None', NULL, NULL, 0, NULL, NULL, 9999, 'RG', NULL, 'PHP', 'A', 'NONE', 'NONE', 'Y', 'C', 'Y', NULL, NULL),
(102090, 'SWEETIE SHOPPE INC.', '2445 ARSONVEL ST. BRGY.', 'SAN ISIDRO PASIG CITY', '', 1, '1', '1', 1016, 'RG', '0.00', 'PHP', 'A', 'MICHAEL CAJOTE', '1', 'Y', NULL, 'Y', NULL, NULL),
(102092, 'SHANGHAI DRAGON CORP.', '584 ZHIZAOJO ROAD SHANGHA', 'CHINA ', '', 1, '1', '1', 1004, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', NULL, 'Y', NULL, NULL),
(102094, 'F.I. FREEMAN INDUSTRIES ', '3-7 PACIMAR STATE LULUNGB', 'ANGELES', '', 1, '3845079', '8468520', 1008, 'RG', '0.00', 'PHP', 'A', 'JANKI THADANI', '1', 'Y', NULL, 'Y', NULL, NULL),
(102096, 'VALMARCE FOOD MARKETING C', '61B BALINGASA ST.  BRGY B', 'QUEZON CITY', '', 1, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', 'JHUN CALDERON', '1', 'Y', NULL, 'Y', NULL, NULL),
(102098, 'PAPERTECH INC.', '835 FELIPE PIKE ST', 'BAGONG ILOG', 'PASIG CITY', 1604, '', '', 1008, 'RG', '0.00', 'PHP', 'A', 'VICTOR DREZ', '835 FELIPE PIKE ST BAGONG ILOG PASIG CITY', 'Y', NULL, 'Y', NULL, NULL),
(102100, 'NEW ZEALAND CREAMERY INCO', '6409 CAMIA STREET', 'GUADALUPE VIEJO', 'MAKATI CITY', 1211, '', '', 1017, 'RG', '0.00', 'PHP', 'A', 'ORLANDO CALDITO', 'NONE', 'Y', NULL, 'Y', NULL, NULL),
(102102, 'MALBI', 'DNEPROPETROVSK, 49044 UKR', 'CHLALOVA, 12 STR', 'UKRAINE', 1, ' ', ' ', 1003, 'RG', '0.00', 'USD', 'A', 'MARINA SHAMSEUEVA', '1', 'Y', NULL, 'Y', NULL, NULL),
(102105, 'DRANIX DISTRIBUTORS, INC.', '641 INDUSTRIAL COMPOUND I', 'SANTIAGO ST. LINGUNAN', 'VALENZUELA CITY', 1447, '292-7862', '292-6741', 1016, 'RG', '0.00', 'PHP', 'A', 'JOSEPH POLICARPIO', '1', 'Y', NULL, 'Y', NULL, NULL),
(102108, '5 LEAF PUBLISHING CO., IN', 'UNIT 103 SOUTHGATE BLDG 1', 'FINANCE DR MADRIGAL BUS P', 'AYALA ALABANG NUNTINLUPA ', 1770, '850-4976', '850-4670', 1016, 'RG', '0.00', 'PHP', 'A', 'MARK JOSEPH DESA', '1', 'Y', NULL, 'Y', NULL, NULL),
(102110, 'KENDA INTERNATIONAL INC.', '2445 ARSONVEL ST.', 'MAKATI CITY', ' ', 1, ' ', ' ', 23, 'RG', '0.00', 'PHP', 'A', 'ULYSSES VERGARA', '1', 'Y', NULL, 'Y', NULL, NULL),
(102111, 'TRADING GATEWAYS INT\\''L P', 'BLDG 8344 SULU ROAD', 'CUBI POINT SUBIC BAY FREE', 'ZONE', 2200, '', '', 23, 'RG', '0.00', 'PHP', 'A', 'DAVE SENG', '1', 'Y', NULL, 'Y', NULL, NULL),
(102114, 'KJA SUMMIT FOOD CORP.', '239 CORDERO ST GRACE PARK', 'CALOOCAN CITY', '', 14000, '', '', 23, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', NULL, 'Y', NULL, NULL),
(102117, 'BEST BRANDS DISTRIBUTION ', '8520 J. DE LEON STREET SA', 'PARANAQUE CITY ', '', 1700, '', '', 23, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', NULL, 'Y', NULL, NULL),
(102120, 'LINDOVAZ TRADING', '1027 C3  DOMINGO SANTIAGO', 'SAMPALOC MANILA', '', 1008, '', '', 22, 'CO', '25.00', 'PHP', 'A', '1', '1', 'Y', NULL, 'Y', NULL, NULL),
(102122, 'TOTAL MATERIAL HANDLING P', 'G/F DOWJONES BLDG. KM. 19', 'RD. MARCELO GREEN', 'PARANAQUE CITY', 1700, '743-6761', '743-4587', 23, 'RG', '0.00', 'PHP', 'A', 'FELIX TATARO', '1', 'Y', NULL, 'Y', NULL, NULL),
(102124, 'PHIL-TOP INDUSTRIES INC.', '6020 TATALON ST. KOWLOON ', 'UGONG VALENZUELA', '', 1448, '443-7135', '443-4022', 1008, 'RG', '0.00', 'PHP', 'A', 'EDNA LUCENA', '1', 'Y', NULL, 'Y', NULL, NULL),
(102126, 'HEALTHY VALLEY COMMERCIAL', '1079 BEDFORD ST. BROOKSID', 'CAINTA, RIZAL', ' ', 1900, '655-3989', ' ', 2002, 'RG', '0.00', 'PHP', 'A', 'MEL MONTECASTRO', '1', 'Y', NULL, 'Y', NULL, NULL),
(102128, 'NEUMANN & MUELLER PHILIPP', '2106 ATLANTA CENTER ANNAP', 'GREENHILLS SAN JUAN', 'METRO MANILA', 1, '721-9800', '723-3182', 23, 'RG', '0.00', 'PHP', 'A', 'JOY SERAPIO', '1', 'Y', NULL, 'Y', NULL, NULL),
(102131, 'MARSHMALLOWS INTL S.L.', 'POL. LND. COTES BAIXES, C', 'NO. 3 03804 ALCOU (ALLCAN', 'SPAIN', 1, ' ', ' ', 2003, 'RG', '0.00', 'EUR', 'A', '1', '1', 'Y', NULL, 'Y', NULL, NULL),
(102133, 'KLG INT\\''L', '4825 AMBER LANE, SUITE A', 'SACRAMENTO, CA ', '', 95841, '', '', 1021, 'RG', '0.00', 'USD', 'A', 'DEIRDRE CARLETON', '1', 'Y', NULL, 'Y', NULL, NULL),
(102135, 'MAGDALENAS DE LAS HERAS S', 'POLIG INDUST ALLENDEDUERO', 'C/ SEGOVIA PARCELA 1-C E-', 'ARANDA DE DUERO BURGOS, S', 1, ' ', ' ', 2004, 'RG', '0.00', 'EUR', 'A', '1', '1', 'Y', NULL, 'Y', NULL, NULL),
(102138, 'D.J.H. INC.', 'J390 NW 161 STREET MIAMI ', '', '', 33014, '', '', 2004, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', NULL, 'Y', NULL, NULL),
(102140, 'HDS TRADING CORP.', '1305 JERSEY AVENUE NORTH', 'BRUNSWICK, NJ', '', 8902, '', '', 1013, 'RG', '0.00', 'USD', 'A', 'NA', '1', 'Y', NULL, 'Y', NULL, NULL),
(102142, 'JEVERPS MANUFACTURING COR', 'KM15 ACSIE ROAD', 'KM16 SOUTH SUPERHIGHWAY', 'PARANAQUE MM', 1700, '8232376/77', '', 1016, 'RG', '0.00', 'PHP', 'A', 'MS. NORA', 'PARANAQUE', 'Y', NULL, 'Y', NULL, NULL),
(102144, 'ALLSPRING FOREVER CORPORA', '80 BALITI SAN FERNANDO', 'PAMPANGA', '', 2000, '', '', 23, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', NULL, 'Y', NULL, NULL),
(102146, 'SOPRANO', 'BRAZIL', ' ', ' ', 1, ' ', ' ', 2004, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', NULL, 'Y', NULL, NULL),
(102148, 'MONTOSCO, INC.', 'NO. 1817 PIY MARGAL COR. ', 'ST. SAMPALOC', 'MANILA', 1008, '', '', 23, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', NULL, 'Y', NULL, NULL),
(102150, 'INOVACAO DA VIDA, INC.', 'UNIT 2504-A WEST TOWER PS', 'EXCHANGE RD. ORTIGAS CTR.', 'SAN ANTONIO PASIG CITY', 1600, ' ', ' ', 2005, 'RG', '0.00', 'PHP', 'A', '1', '1', 'Y', NULL, 'Y', NULL, NULL),
(102152, 'APOLLOPLUS DISTRIBUTION I', '51 CONGRESSIONAL AVE', 'BRGY BAHAY TORO, QC', ' ', 1, ' ', ' ', 23, 'RG', '0.00', 'PHP', 'A', 'NA', 'NA', 'Y', NULL, 'Y', NULL, NULL),
(102154, 'CCLF', '1 BIS VILLA THORETON 7572', 'PARIS CEDEX 15', 'FRANCE', 1, ' ', ' ', 2004, 'RG', '0.00', 'EUR', 'A', 'ANU NORMAK', '1', 'Y', NULL, 'Y', NULL, NULL),
(102156, 'VOK BEVERAGES PTY LTD.', '162 CROSS KEYS RD. PO BOX', '263 SALISBURY SOUTH DC SA', 'AUSTRALIA', 5106, '', '', 1003, 'RG', '0.00', 'USD', 'A', '1', '1', 'Y', NULL, 'Y', NULL, NULL),
(102158, 'COLOMBO MERCHANT PHILS. I', 'MEZZANINE 1, SOUTH CENTER', '2206 VENTURE ST. MADRIGAL', 'BUSINESS PARK ALABANG', 1770, '', '', 23, 'CG', '0.00', 'PHP', 'A', 'AMIE ARGUELLES', '1', 'Y', NULL, 'Y', NULL, NULL),
(102160, 'HANS FREITAG GMBH & CO. K', 'SIEMENSSTRABE II-D-27283', 'VERDEN (ALLER)', ' ', 1, '1', '1', 2004, 'RG', '0.00', 'EUR', 'A', 'VICENZO LEME', '1', 'Y', NULL, 'Y', NULL, NULL),
(102162, 'JAKA DISTRIBUTION INC.', 'KM. 18 EAST SERVICE RD. C', 'COMPOUND SAN MARTIN DE PO', 'PQUE CITY', 1700, '838-4808', '838-4813', 23, 'RG', '0.00', 'PHP', 'A', 'ANNA LORAINE M. SUAREZ', '1', 'Y', NULL, 'Y', NULL, NULL),
(102164, 'MEGA FISHING CORPORATION', 'UNIT 1206-1207 12TH FLOOR', 'ORIENT SQUARE BLDG. EMERA', 'ORTIGAS, PASIG CITY', 1, '', '', 23, 'RG', '0.00', 'PHP', 'A', '1', 'NA', 'Y', NULL, 'Y', NULL, NULL),
(102166, 'PAMPANGAS BEST INC.', 'OLONGAPO GAPAN ROAD', 'DOLORES CITY OF SAN FERNA', 'PAMPANGA', 2000, '1', '1', 23, 'RG', '0.00', 'PHP', 'A', 'NA', 'NA', 'Y', NULL, 'Y', NULL, NULL),
(102169, 'CMP FREEPORT, INC.', 'AREA 34 BRAND REX, COMPOU', 'ARGONAUT HIGHWAY,', 'SUBIC BAY FREEPORT ZONE', 2200, '879-4060', '879-4061', 23, 'CO', '25.00', 'PHP', 'A', 'BEVERLY ONG CAMPOS', '1', 'Y', NULL, 'Y', NULL, NULL),
(102172, 'INBOUND PACIFIC FREEPORT ', 'LOT 11/12 BRAND REX CMPD.', 'ARGONAUT HIGHWAY BOTON AR', 'SUBIC BAY FREEPORT ZONE', 2200, '6363265', '6342194', 23, 'CO', '17.00', 'PHP', 'A', 'JOSE MARIA ROSALES', '1', 'Y', NULL, 'Y', NULL, NULL),
(102174, 'JS PHILIPPINES GLOBAL COR', 'UNIT3 #88 AMANG RODRIGUEZ', 'SANTOLAN PASIG CITY', '', 1610, '6822881', '6818228', 22, 'CO', '25.00', 'PHP', 'A', 'RAYZEL SUELLA', '1', 'Y', NULL, 'Y', NULL, NULL),
(102177, 'DJH, INC.', 'P.O. BOX 4811#5390 NW161 ', 'MIAMI GARDENS, FL33014', 'USA', 33014, '1', '1', 2006, 'RG', '0.00', 'USD', 'A', '1', 'MARVIN TUCKLAPER', 'Y', NULL, 'Y', NULL, NULL),
(102179, 'ILOCOS FOOD PRODUCTS', 'TALEB BANTAY ILOCOS SUR', '', '', 2727, '', '', 23, 'RG', '0.00', 'PHP', 'A', 'MERLE ROMANO', 'NA', 'Y', NULL, 'Y', NULL, NULL),
(102181, 'AHEAD MARKETING ', '52 SISA EXT. TIINAJEROS ', 'MALABON CITY', '', 1470, '3513584', '', 22, 'CO', '30.00', 'PHP', 'A', 'MARY ANN CHAN', '1', 'Y', NULL, 'Y', NULL, NULL),
(102183, 'TRUELUCK GENERAL MERCHAND', '626 LAVEZARES ST. SAN NIC', 'MANILA', '', 1010, '', '', 23, 'RG', '0.00', 'PHP', 'A', 'CELESTINA RAPON GADIANE', '1', 'Y', NULL, 'Y', NULL, NULL),
(102185, 'PEERLESS PRODUCTS MANUFAC', '35 SAN FRANCISCO KARUHATA', 'VALENZUELA CITY', '', 1441, '843-3777', '', 23, 'RG', '0.00', 'PHP', 'A', 'MARTIN TUPAZ', '1', 'Y', NULL, 'Y', NULL, NULL),
(102187, 'LUXENBERG MARKETING INC.', '502 EVEKAL BLDG. 855 ARNA', 'MAKATI CITY', '', 1224, '', '', 22, 'CO', '15.00', 'PHP', 'A', 'JEFFREY CHUA', '1', 'Y', NULL, 'Y', NULL, NULL),
(102195, '1768 MARKETING', '1768 PLY MARGAL ST. SAMPA', 'METRO MANILA', '', 1008, '0922838111', '', 22, '', '0.00', 'PHP', 'A', 'TSA CHUA', 'NA', NULL, NULL, NULL, NULL, 'NP'),
(102197, 'E & L DELICATESSEN', 'B3 L12 SAN AGUSTIN VILLAG', 'SAN ROQUE, ANTIPOLO RIZAL', '', 1870, '', '', 22, 'CO', '15.00', 'PHP', 'A', 'CELEDONIA MUYRONG', '1', 'Y', NULL, 'Y', NULL, 'NC'),
(999999, 'UNASSIGNED', '1234', '1234', '1234', 0, '', '', 0, 'RG', '0.00', 'AUD', 'A', NULL, NULL, 'Y', 'C', 'Y', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

CREATE TABLE `tblusers` (
  `userId` int(4) NOT NULL AUTO_INCREMENT,
  `strCode` varchar(3) DEFAULT NULL,
  `minCode` varchar(3) DEFAULT NULL,
  `userName` varchar(50) NOT NULL,
  `userPass` varchar(50) DEFAULT NULL,
  `pages` text,
  `userLevel` int(4) DEFAULT NULL,
  `dateEnt` datetime DEFAULT NULL,
  `dateUpdt` datetime DEFAULT NULL,
  `userStat` char(1) DEFAULT NULL,
  `fullName` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tblusers`
--

INSERT INTO `tblusers` (`userId`, `strCode`, `minCode`, `userName`, `userPass`, `pages`, `userLevel`, `dateEnt`, `dateUpdt`, `userStat`, `fullName`) VALUES
(1, NULL, '1', 'mike', 'bWxhdQ==', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19', 1, '2011-11-05 08:55:47', NULL, 'A', 'Michael Laurence Barde'),
(2, NULL, '2', 'cindy', 'Y2luZHk=', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15', 1, '2011-11-05 09:24:47', NULL, 'A', 'Cindy Desphy'),
(3, NULL, '1', 'squall', 'c3F1YWxs', '18', 2, '2011-12-08 00:00:00', NULL, 'A', 'Squall Leonhart');

-- --------------------------------------------------------

--
-- Stand-in structure for view `unreleasedstsview`
--
CREATE TABLE `unreleasedstsview` (
`grpEntered` int(5)
,`suppCurr` varchar(3)
,`stsComp` int(4)
,`stsDateEntered` datetime
,`dept` varchar(50)
,`suppName` varchar(25)
,`stsRefNo` int(8)
,`stsNo` int(7)
,`stsRemarks` text
,`stsAmt` decimal(12,2)
,`nbrApplication` int(2)
,`applyDate` date
,`brnShortDesc` varchar(12)
,`endDate` date
,`paymentMode` varchar(17)
);
-- --------------------------------------------------------

--
-- Structure for view `cancelledstsview`
--
DROP TABLE IF EXISTS `cancelledstsview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `sts`.`cancelledstsview` AS select `sts`.`tblstshdr`.`grpEntered` AS `grpEntered`,`sts`.`tblstshdr`.`suppCurr` AS `suppCurr`,`sts`.`tblstshdr`.`stsComp` AS `stsComp`,`sts`.`tblstshdr`.`stsDateEntered` AS `stsDateEntered`,(select `sts`.`tblststranstype`.`stsTransTypeName` AS `dept` from `sts`.`tblststranstype` where ((`sts`.`tblststranstype`.`stsTransTypeDept` = `sts`.`tblstshdr`.`stsDept`) and (`sts`.`tblststranstype`.`stsTransTypeLvl` = 1))) AS `dept`,`sts`.`tblsuppliers`.`suppName` AS `suppName`,`sts`.`tblstshdr`.`stsRefNo` AS `stsRefNo`,`sts`.`tblstsdtl`.`stsNo` AS `stsNo`,`sts`.`tblstshdr`.`stsRemarks` AS `stsRemarks`,`sts`.`tblstshdr`.`stsAmt` AS `stsAmt`,`sts`.`tblstshdr`.`nbrApplication` AS `nbrApplication`,`sts`.`tblstshdr`.`applyDate` AS `applyDate`,`sts`.`tblbranch`.`brnShortDesc` AS `brnShortDesc`,(`sts`.`tblstshdr`.`applyDate` + interval `sts`.`tblstshdr`.`nbrApplication` month) AS `endDate`,(case `sts`.`tblstshdr`.`stsPaymentMode` when _utf8'C' then _utf8'CASH / CHEQUE' when _utf8'D' then _utf8'INVOICE DEDUCTION' end) AS `paymentMode` from (((`sts`.`tblstshdr` join `sts`.`tblstsdtl` on(((`sts`.`tblstshdr`.`stsRefNo` = `sts`.`tblstsdtl`.`stsRefNo`) and (`sts`.`tblstshdr`.`stsComp` = `sts`.`tblstsdtl`.`stsComp`)))) join `sts`.`tblbranch` on(((`sts`.`tblstsdtl`.`stsComp` = `sts`.`tblbranch`.`compCode`) and (`sts`.`tblstsdtl`.`stsStrCode` = `sts`.`tblbranch`.`brnCode`)))) join `sts`.`tblsuppliers` on((`sts`.`tblstshdr`.`suppCode` = `sts`.`tblsuppliers`.`suppCode`))) where ((`sts`.`tblstshdr`.`stsTag` = _latin1'Y') and (`sts`.`tblstshdr`.`stsStat` = 'C'));

-- --------------------------------------------------------

--
-- Structure for view `releasedstsview`
--
DROP TABLE IF EXISTS `releasedstsview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `sts`.`releasedstsview` AS select `sts`.`tblstshdr`.`grpEntered` AS `grpEntered`,`sts`.`tblstshdr`.`suppCurr` AS `suppCurr`,`sts`.`tblstshdr`.`stsComp` AS `stsComp`,`sts`.`tblstshdr`.`stsDateEntered` AS `stsDateEntered`,(select `sts`.`tblststranstype`.`stsTransTypeName` AS `dept` from `sts`.`tblststranstype` where ((`sts`.`tblststranstype`.`stsTransTypeDept` = `sts`.`tblstshdr`.`stsDept`) and (`sts`.`tblststranstype`.`stsTransTypeLvl` = 1))) AS `dept`,`sts`.`tblsuppliers`.`suppName` AS `suppName`,`sts`.`tblstshdr`.`stsRefNo` AS `stsRefNo`,`sts`.`tblstsdtl`.`stsNo` AS `stsNo`,`sts`.`tblstshdr`.`stsRemarks` AS `stsRemarks`,`sts`.`tblstshdr`.`stsAmt` AS `stsAmt`,`sts`.`tblstshdr`.`nbrApplication` AS `nbrApplication`,`sts`.`tblstshdr`.`applyDate` AS `applyDate`,`sts`.`tblbranch`.`brnShortDesc` AS `brnShortDesc`,(`sts`.`tblstshdr`.`applyDate` + interval `sts`.`tblstshdr`.`nbrApplication` month) AS `endDate`,(case `sts`.`tblstshdr`.`stsPaymentMode` when _utf8'C' then _utf8'CASH / CHEQUE' when _utf8'D' then _utf8'INVOICE DEDUCTION' end) AS `paymentMode` from (((`sts`.`tblstshdr` join `sts`.`tblstsdtl` on(((`sts`.`tblstshdr`.`stsRefNo` = `sts`.`tblstsdtl`.`stsRefNo`) and (`sts`.`tblstshdr`.`stsComp` = `sts`.`tblstsdtl`.`stsComp`)))) join `sts`.`tblbranch` on(((`sts`.`tblstsdtl`.`stsComp` = `sts`.`tblbranch`.`compCode`) and (`sts`.`tblstsdtl`.`stsStrCode` = `sts`.`tblbranch`.`brnCode`)))) join `sts`.`tblsuppliers` on((`sts`.`tblstshdr`.`suppCode` = `sts`.`tblsuppliers`.`suppCode`))) where ((`sts`.`tblstshdr`.`stsTag` = _latin1'Y') and (`sts`.`tblstshdr`.`stsStat` = 'R'));

-- --------------------------------------------------------

--
-- Structure for view `stsprintview`
--
DROP TABLE IF EXISTS `stsprintview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `sts`.`stsprintview` AS select (select `sts`.`tblprodgrp`.`prodName` AS `prodName` from `sts`.`tblprodgrp` where (`sts`.`tblprodgrp`.`prodID` = `sts`.`tblstshdr`.`grpEntered`)) AS `grpEntered`,`sts`.`tblstshdr`.`stsTag` AS `stsTag`,`sts`.`tblstshdr`.`stsAmt` AS `stsAmt`,`sts`.`tblstshdr`.`stsComp` AS `stsComp`,`sts`.`tblstshdr`.`stsDate` AS `stsDate`,`sts`.`tblstshdr`.`stsRefNo` AS `stsRefNo`,`sts`.`tblsuppliers`.`suppName` AS `suppName`,`sts`.`tblstshdr`.`stsStartNo` AS `stsStartNo`,`sts`.`tblstshdr`.`stsEndNo` AS `stsEndNo`,`sts`.`tblstshdr`.`nbrApplication` AS `nbrApplication`,`sts`.`tblstshdr`.`applyDate` AS `applyDate`,(`sts`.`tblstshdr`.`applyDate` + interval (`sts`.`tblstshdr`.`nbrApplication` - 1) month) AS `endDate`,`sts`.`tblstshdr`.`stsDept` AS `stsDept`,`sts`.`tblstshdr`.`stsCls` AS `stsCls`,`sts`.`tblstshdr`.`stsSubCls` AS `stsSubCls`,`sts`.`tblstshdr`.`stsType` AS `stsType`,`sts`.`tblstshdr`.`suppCurr` AS `suppCurr`,`sts`.`tblstshdr`.`stsRemarks` AS `stsRemarks`,(select `sts`.`tblststranstype`.`stsTransTypeName` AS `stsTransTypeName` from `sts`.`tblststranstype` where ((`sts`.`tblststranstype`.`stsTransTypeLvl` = 1) and (`sts`.`tblststranstype`.`stsTransTypeDept` = `sts`.`tblstshdr`.`stsDept`))) AS `Dept`,(select `sts`.`tblststranstype`.`stsTransTypeName` AS `stsTransTypeName` from `sts`.`tblststranstype` where ((`sts`.`tblststranstype`.`stsTransTypeLvl` = 2) and (`sts`.`tblststranstype`.`stsTransTypeDept` = `sts`.`tblstshdr`.`stsDept`) and (`sts`.`tblststranstype`.`stsTransTypeClass` = `sts`.`tblstshdr`.`stsCls`))) AS `Class`,(select `sts`.`tblststranstype`.`stsTransTypeName` AS `stsTransTypeName` from `sts`.`tblststranstype` where ((`sts`.`tblststranstype`.`stsTransTypeLvl` = 3) and (`sts`.`tblststranstype`.`stsTransTypeDept` = `sts`.`tblstshdr`.`stsDept`) and (`sts`.`tblststranstype`.`stsTransTypeClass` = `sts`.`tblstshdr`.`stsCls`) and (`sts`.`tblststranstype`.`stsTransTypeSClass` = `sts`.`tblstshdr`.`stsSubCls`))) AS `SClass`,(case `sts`.`tblstshdr`.`stsPaymentMode` when _utf8'C' then _utf8'CASH / CHEQUE PAYMENTS' when _utf8'D' then _utf8'INVOICE DEDUCTION' end) AS `paymentMode` from (`sts`.`tblstshdr` join `sts`.`tblsuppliers` on((`sts`.`tblstshdr`.`suppCode` = `sts`.`tblsuppliers`.`suppCode`)));

-- --------------------------------------------------------

--
-- Structure for view `unreleasedstsview`
--
DROP TABLE IF EXISTS `unreleasedstsview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `sts`.`unreleasedstsview` AS select `sts`.`tblstshdr`.`grpEntered` AS `grpEntered`,`sts`.`tblstshdr`.`suppCurr` AS `suppCurr`,`sts`.`tblstshdr`.`stsComp` AS `stsComp`,`sts`.`tblstshdr`.`stsDateEntered` AS `stsDateEntered`,(select `sts`.`tblststranstype`.`stsTransTypeName` AS `dept` from `sts`.`tblststranstype` where ((`sts`.`tblststranstype`.`stsTransTypeDept` = `sts`.`tblstshdr`.`stsDept`) and (`sts`.`tblststranstype`.`stsTransTypeLvl` = 1))) AS `dept`,`sts`.`tblsuppliers`.`suppName` AS `suppName`,`sts`.`tblstshdr`.`stsRefNo` AS `stsRefNo`,`sts`.`tblstsdtl`.`stsNo` AS `stsNo`,`sts`.`tblstshdr`.`stsRemarks` AS `stsRemarks`,`sts`.`tblstshdr`.`stsAmt` AS `stsAmt`,`sts`.`tblstshdr`.`nbrApplication` AS `nbrApplication`,`sts`.`tblstshdr`.`applyDate` AS `applyDate`,`sts`.`tblbranch`.`brnShortDesc` AS `brnShortDesc`,(`sts`.`tblstshdr`.`applyDate` + interval `sts`.`tblstshdr`.`nbrApplication` month) AS `endDate`,(case `sts`.`tblstshdr`.`stsPaymentMode` when _utf8'C' then _utf8'CASH / CHEQUE' when _utf8'D' then _utf8'INVOICE DEDUCTION' end) AS `paymentMode` from (((`sts`.`tblstshdr` join `sts`.`tblstsdtl` on(((`sts`.`tblstshdr`.`stsRefNo` = `sts`.`tblstsdtl`.`stsRefNo`) and (`sts`.`tblstshdr`.`stsComp` = `sts`.`tblstsdtl`.`stsComp`)))) join `sts`.`tblbranch` on(((`sts`.`tblstsdtl`.`stsComp` = `sts`.`tblbranch`.`compCode`) and (`sts`.`tblstsdtl`.`stsStrCode` = `sts`.`tblbranch`.`brnCode`)))) join `sts`.`tblsuppliers` on((`sts`.`tblstshdr`.`suppCode` = `sts`.`tblsuppliers`.`suppCode`))) where (isnull(`sts`.`tblstshdr`.`stsTag`) and (`sts`.`tblstshdr`.`stsStat` = 'R'));
