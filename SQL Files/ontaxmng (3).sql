-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 18, 2025 at 05:48 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ontaxmng`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `CheckAndDeleteReport` (IN `userID` INT, IN `taxYear` YEAR)  BEGIN
    DECLARE countIncome INT;
    DECLARE countSales INT;
    DECLARE countProperty INT;

    -- Count records for the user and year in each table
    SELECT COUNT(*) INTO countIncome FROM tblincometax WHERE UserID = userID AND tax_year = taxYear;
    SELECT COUNT(*) INTO countSales FROM tblsalestax WHERE UserID = userID AND tax_year = taxYear;
    SELECT COUNT(*) INTO countProperty FROM tblpropertytax WHERE UserID = userID AND tax_year = taxYear;

    -- If all counts are zero, delete the record from tbl_reports
    IF countIncome = 0 AND countSales = 0 AND countProperty = 0 THEN
        DELETE FROM tbl_reports WHERE UserID = userID AND tax_year = taxYear;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateOrInsertReport` (IN `userID` INT, IN `taxYear` YEAR)  BEGIN
    DECLARE incomeAmount DECIMAL(15,2);
    DECLARE salesAmount DECIMAL(15,2);
    DECLARE propertyValue DECIMAL(15,2);
    DECLARE totalTaxAmount DECIMAL(15,2);

    -- Calculate the sums for income, sales, and property, considering only verified records
    SELECT COALESCE(SUM(income), 0) INTO incomeAmount FROM tblincometax WHERE UserID = userID AND tax_year = taxYear AND status = 'verified';
    SELECT COALESCE(SUM(sales_amount), 0) INTO salesAmount FROM tblsalestax WHERE UserID = userID AND tax_year = taxYear AND status = 'verified';
    SELECT COALESCE(SUM(market_value), 0) INTO propertyValue FROM tblpropertytax WHERE UserID = userID AND tax_year = taxYear AND status = 'verified';

    -- Calculate the total tax amount
    SELECT COALESCE(SUM(tax_amount), 0) INTO totalTaxAmount
    FROM (
        SELECT tax_amount FROM tblincometax WHERE UserID = userID AND tax_year = taxYear AND status = 'verified'
        UNION ALL
        SELECT tax_amount FROM tblsalestax WHERE UserID = userID AND tax_year = taxYear AND status = 'verified'
        UNION ALL
        SELECT tax_amount FROM tblpropertytax WHERE UserID = userID AND tax_year = taxYear AND status = 'verified'
    ) AS combined_tax;

    -- Check if a report already exists for this user and tax year
    IF EXISTS (SELECT 1 FROM tbl_reports WHERE UserID = userID AND tax_year = taxYear) THEN
        -- Update the existing report
        UPDATE tbl_reports
        SET income_amount = incomeAmount,
            sales_amount = salesAmount,
            property_value = propertyValue,
            total_tax_amount = totalTaxAmount
        WHERE UserID = userID AND tax_year = taxYear;
    ELSE
        -- Insert a new report if no record exists
        INSERT INTO tbl_reports (UserID, tax_year, income_amount, sales_amount, property_value, total_tax_amount)
        VALUES (userID, taxYear, incomeAmount, salesAmount, propertyValue, totalTaxAmount);
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `ID` int(5) NOT NULL,
  `FullName` varchar(250) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Email` varchar(250) DEFAULT NULL,
  `Password` varchar(250) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`ID`, `FullName`, `MobileNumber`, `Email`, `Password`, `RegDate`) VALUES
(1, 'Tax Administrator', 3001234567, 'admin@gmail.com', '698d51a19d8a121ce581499d7b701668', '2023-12-15 17:46:20');

-- --------------------------------------------------------

--
-- Table structure for table `tblcontact`
--

CREATE TABLE `tblcontact` (
  `ID` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblcontact`
--

INSERT INTO `tblcontact` (`ID`, `fullname`, `email`, `phone`, `subject`, `message`, `created_at`) VALUES
(3, 'sadia', 'ateeqa@gmail.com', '123566666666', 'Service issues', 'we are in serious troubles because of unable to login. we hope that this is due to heavy traffic on website but also it is a humble request kindly makes resolve the issue as soon as possible.', '2024-11-26 19:42:30'),
(7, 'Tax Payer', 'tax@gmail.com', '12345678', 'Web Validation ', 'i forget my password is there any proces of forgot password in your website?', '2025-01-11 07:58:24'),
(12, 'ateqa', 'ateeqa@gmail.com', '12345678', 'Service issues', '3rd process can share lock when process 1 and 2 are already locked?(Like this type of statement', '2025-01-30 21:16:49'),
(14, 'User', 'xyz@gmail.com', '123566666666', 'want to know last update of taxrates', 'can u send me please updated tax slabs', '2025-02-17 21:20:43');

-- --------------------------------------------------------

--
-- Table structure for table `tblincometax`
--

CREATE TABLE `tblincometax` (
  `ID` int(5) NOT NULL,
  `UserID` int(5) NOT NULL,
  `cnic` varchar(250) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `income` varchar(250) DEFAULT NULL,
  `tax_amount` varchar(255) NOT NULL,
  `tax_year` year(4) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `File1` varchar(255) DEFAULT NULL,
  `File2` varchar(255) DEFAULT NULL,
  `File3` varchar(255) DEFAULT NULL,
  `File4` varchar(255) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblincometax`
--

INSERT INTO `tblincometax` (`ID`, `UserID`, `cnic`, `name`, `income`, `tax_amount`, `tax_year`, `address`, `contact`, `status`, `File1`, `File2`, `File3`, `File4`, `CreationDate`, `UpdationDate`) VALUES
(1, 6, '35201-1234567-8', 'sadia shezadi', '1100000', '12500', 2024, 'attock hazro', '03014357855', 'verified', 'de66dea50a1d9bf810385e68a9ce054b1736548230.pdf', 'de66dea50a1d9bf810385e68a9ce054b1736548230.pdf', '4dd6257ea49e11caa4399ab37b769b791736548230.pdf', 'de66dea50a1d9bf810385e68a9ce054b1736548230.pdf', '2025-01-10 22:30:30', '2025-01-10 22:30:53'),
(2, 6, '35202-1234567-8', 'Ateeqa', '3700000', '462500', 2025, 'attock hazro', '234', 'verified', 'de66dea50a1d9bf810385e68a9ce054b1736721778.pdf', 'de66dea50a1d9bf810385e68a9ce054b1736721778.pdf', 'de66dea50a1d9bf810385e68a9ce054b1736721778.pdf', 'de66dea50a1d9bf810385e68a9ce054b1736721778.pdf', '2025-01-12 22:42:58', '2025-01-12 22:44:55');

--
-- Triggers `tblincometax`
--
DELIMITER $$
CREATE TRIGGER `trg_tblincometax_after_delete` AFTER DELETE ON `tblincometax` FOR EACH ROW BEGIN
    -- Cleanup function or delete check to ensure reports table is up-to-date
    CALL CheckAndDeleteReport(OLD.UserID, OLD.tax_year);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_tblincometax_after_insert` AFTER INSERT ON `tblincometax` FOR EACH ROW BEGIN
    CALL UpdateOrInsertReport(NEW.UserID, NEW.tax_year);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_tblincometax_after_update` AFTER UPDATE ON `tblincometax` FOR EACH ROW BEGIN
    CALL UpdateOrInsertReport(NEW.UserID, NEW.tax_year);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tblnotifications`
--

CREATE TABLE `tblnotifications` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('automated','custom') NOT NULL,
  `recipient_id` int(11) DEFAULT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblnotifications`
--

INSERT INTO `tblnotifications` (`id`, `title`, `message`, `type`, `recipient_id`, `status`, `created_at`, `admin_id`) VALUES
(1, 'Income Tax Application Verified', 'Your Income Tax application has been verified successfully.', 'automated', 6, '', '2025-01-10 22:31:43', 1),
(2, 'Sales Tax Application Verified', 'Your Sales Tax application has been verified successfully.', 'automated', 6, '', '2025-01-11 00:47:53', 1),
(3, 'Property Tax Application Verified', 'Your Property Tax application has been verified successfully.', 'automated', 6, '', '2025-01-11 01:14:34', 1),
(4, 'Final Testing', 'Now Admin perform testing on application for the last time.', 'custom', 6, 'read', '2025-01-11 03:17:04', 1),
(5, 'Final Viva', 'Final Viva Schedule comin Soon.', 'custom', 6, 'read', '2025-01-11 03:28:17', 1),
(6, 'Yearly Report Updation', 'Dear TaxPayer\r\nNow You All check your yearly report and view tax ratios of all categories by clicking view Report button on your Dashboard by just one click..', 'custom', NULL, 'read', '2025-01-11 03:31:04', 1),
(7, 'Sales Tax Application Verified', 'Your Sales Tax application has been verified successfully.', 'automated', 6, '', '2025-01-11 16:07:03', 1),
(8, 'Income Tax Application Verified', 'Your Income Tax application has been verified successfully.', 'automated', 6, '', '2025-01-12 22:44:55', 1),
(9, 'Sales Tax Application Verified', 'Your Sales Tax application has been verified successfully.', 'automated', NULL, '', '2025-01-14 17:05:35', 1),
(10, 'Tax Rates Updated', 'The admin has updated the tax rates. Please review the latest tax rates.', 'automated', NULL, '', '2025-01-16 16:18:51', 1),
(11, 'Tax Rates Updated', 'The admin has updated the tax rates. Please review the latest tax rates.', 'automated', NULL, '', '2025-01-16 16:19:21', 1),
(12, 'Tax Returns Proccessing Update', 'Your payment has been successfully completed.', 'automated', 6, '', '2025-01-16 16:28:14', 1),
(15, 'Last Testing', 'Today we perform last testing. it is advised to test and fix all issues.', 'custom', 6, 'read', '2025-02-18 05:27:47', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblpayments`
--

CREATE TABLE `tblpayments` (
  `ID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `TaxCategory` varchar(50) NOT NULL,
  `PaymentMethod` varchar(20) NOT NULL,
  `Amount` decimal(10,2) NOT NULL,
  `ProofDocument` varchar(255) DEFAULT NULL,
  `PaymentStatus` varchar(20) NOT NULL DEFAULT 'Pending',
  `TransactionID` varchar(100) DEFAULT NULL,
  `CardNumber` varchar(16) DEFAULT NULL,
  `CardExpiry` varchar(5) DEFAULT NULL,
  `CardCVC` varchar(4) DEFAULT NULL,
  `CashDetails` text DEFAULT NULL,
  `PaymentDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblpayments`
--

INSERT INTO `tblpayments` (`ID`, `UserID`, `TaxCategory`, `PaymentMethod`, `Amount`, `ProofDocument`, `PaymentStatus`, `TransactionID`, `CardNumber`, `CardExpiry`, `CardCVC`, `CashDetails`, `PaymentDate`) VALUES
(12, 28, 'Sales Tax', 'Bank Transfer', '34.00', '6738b1a6c3e14__8ba5c68c-674f-407a-8f80-bb3dbbd92b6f.png', 'Completed', 'yy', 'No', 'No', 'No', 'No', '2024-11-16 14:52:22'),
(13, 28, 'Property Tax', 'Cash', '55.00', '6738b61e39e8d__8ba5c68c-674f-407a-8f80-bb3dbbd92b6f.png', 'Failed', 'No', 'No', 'No', 'No', 'uu', '2024-11-16 15:11:26'),
(14, 28, 'Income Tax', 'Bank Transfer', '333.00', '6738b7f850ef4__8ba5c68c-674f-407a-8f80-bb3dbbd92b6f.png', 'Refunded', 'bnking', 'No', 'No', 'No', 'No', '2024-11-16 15:19:20'),
(18, 23, 'Property Tax', 'Card', '7000.00', '673d75abe5d82_WhatsApp_Image_2024-11-19_at_5.07.53_AM-removebg-preview.png', 'Completed', 'No', '66789', '4/2/2', 'rr67', 'No', '2024-11-20 05:37:47'),
(19, 23, 'Sales Tax', 'Card', '88000.00', '674e41e8d1646_kharoos.PNG', 'Failed', 'No', '99', '078', '456', 'No', '2024-12-02 23:25:28'),
(23, 6, 'Sales Tax', 'Card', '5000.00', '678931dd6bba9__e41e1098-9e9c-4527-954b-a16f3fc04c77.jpg', 'Completed', 'No', '5589', '22/9/', '1277', 'No', '2025-01-16 16:20:45');

-- --------------------------------------------------------

--
-- Table structure for table `tblpropertytax`
--

CREATE TABLE `tblpropertytax` (
  `id` int(11) NOT NULL,
  `UserID` varchar(255) NOT NULL,
  `cnic` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `property_type` varchar(100) NOT NULL,
  `market_value` decimal(65,2) DEFAULT NULL,
  `tax_amount` decimal(20,2) DEFAULT NULL,
  `tax_year` year(4) DEFAULT NULL,
  `address` text NOT NULL,
  `contact` varchar(15) NOT NULL,
  `status` varchar(50) NOT NULL,
  `File1` varchar(255) NOT NULL,
  `File2` varchar(255) NOT NULL,
  `File3` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblpropertytax`
--

INSERT INTO `tblpropertytax` (`id`, `UserID`, `cnic`, `name`, `property_type`, `market_value`, `tax_amount`, `tax_year`, `address`, `contact`, `status`, `File1`, `File2`, `File3`, `created_at`) VALUES
(27, '6', '35201-1999567-8', 'sadia shezadi', 'Commercial', '135000.00', '4050.00', 2024, 'punjab', '12345', 'verified', 'bb02d7d743ad0e979611fc3637ec27251736558052.pdf', 'bb02d7d743ad0e979611fc3637ec27251736558052.pdf', 'de66dea50a1d9bf810385e68a9ce054b1736558052.pdf', '2025-01-11 01:14:12'),
(28, '6', '35201-1234767-5', 'Ateeqa', 'Residential', '120000000.00', '4800000.00', 2023, 'attock hazro', '311', 'Pending', 'de66dea50a1d9bf810385e68a9ce054b1739856304.pdf', 'bbc83f96256bce1cc3e84cf2ada82e381739856304.pdf', 'bbc83f96256bce1cc3e84cf2ada82e381739856304.pdf', '2025-02-18 05:25:04');

--
-- Triggers `tblpropertytax`
--
DELIMITER $$
CREATE TRIGGER `trg_tblpropertytax_after_delete` AFTER DELETE ON `tblpropertytax` FOR EACH ROW BEGIN
    CALL CheckAndDeleteReport(OLD.UserID, OLD.tax_year);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_tblpropertytax_after_insert` AFTER INSERT ON `tblpropertytax` FOR EACH ROW BEGIN
    CALL UpdateOrInsertReport(NEW.UserID, NEW.tax_year);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_tblpropertytax_after_update` AFTER UPDATE ON `tblpropertytax` FOR EACH ROW BEGIN
    CALL UpdateOrInsertReport(NEW.UserID, NEW.tax_year);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tblsalestax`
--

CREATE TABLE `tblsalestax` (
  `ID` int(5) NOT NULL,
  `UserID` int(5) DEFAULT NULL,
  `cnic` varchar(250) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `sales_amount` varchar(250) DEFAULT NULL,
  `tax_amount` varchar(255) NOT NULL,
  `tax_year` year(4) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `File1` varchar(255) DEFAULT NULL,
  `File2` varchar(255) DEFAULT NULL,
  `File3` varchar(255) DEFAULT NULL,
  `File4` varchar(255) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblsalestax`
--

INSERT INTO `tblsalestax` (`ID`, `UserID`, `cnic`, `name`, `sales_amount`, `tax_amount`, `tax_year`, `address`, `contact`, `status`, `File1`, `File2`, `File3`, `File4`, `CreationDate`, `UpdationDate`) VALUES
(36, 6, '35201-1244567-9', 'sadia shezadi', '100000', '18000', 2024, 'attock hazro', '123556', 'verified', 'de66dea50a1d9bf810385e68a9ce054b1736556406.pdf', '4dd6257ea49e11caa4399ab37b769b791736556406.pdf', 'de66dea50a1d9bf810385e68a9ce054b1736556406.pdf', NULL, '2025-01-11 00:46:46', '2025-01-11 00:47:53'),
(37, 6, '35201-1244567-3', 'Ateeqa', '860000', '154800', 2025, 'hazro', '234', 'verified', 'de66dea50a1d9bf810385e68a9ce054b1736611576.pdf', 'de66dea50a1d9bf810385e68a9ce054b1736611576.pdf', 'de66dea50a1d9bf810385e68a9ce054b1736611576.pdf', NULL, '2025-01-11 16:06:16', '2025-01-11 16:07:03');

--
-- Triggers `tblsalestax`
--
DELIMITER $$
CREATE TRIGGER `trg_tblsalestax_after_delete` AFTER DELETE ON `tblsalestax` FOR EACH ROW BEGIN
    CALL CheckAndDeleteReport(OLD.UserID, OLD.tax_year);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_tblsalestax_after_insert` AFTER INSERT ON `tblsalestax` FOR EACH ROW BEGIN
    CALL UpdateOrInsertReport(NEW.UserID, NEW.tax_year);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_tblsalestax_after_update` AFTER UPDATE ON `tblsalestax` FOR EACH ROW BEGIN
    CALL UpdateOrInsertReport(NEW.UserID, NEW.tax_year);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tbltaxrates`
--

CREATE TABLE `tbltaxrates` (
  `id` int(11) NOT NULL,
  `tax_category` varchar(50) NOT NULL,
  `slab_description` varchar(255) NOT NULL,
  `rate` decimal(5,3) NOT NULL,
  `fixed_amount` decimal(15,2) DEFAULT 0.00,
  `AdminID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbltaxrates`
--

INSERT INTO `tbltaxrates` (`id`, `tax_category`, `slab_description`, `rate`, `fixed_amount`, `AdminID`) VALUES
(1, 'Income Tax', 'below 600,000', '0.000', '0.00', 1),
(2, 'Income Tax', 'Rs600,001 - Rs1,200,000', '0.025', '0.00', 1),
(3, 'Income Tax', 'Rs1,200,001 - Rs2,400,000', '0.125', '15000.00', 1),
(4, 'Income Tax', 'Rs2,400,001 - Rs3,600,000', '0.225', '165000.00', 1),
(5, 'Income Tax', 'Rs3,600,001 - Rs6,000,000', '0.275', '435000.00', 1),
(6, 'Income Tax', 'Above Rs6,000,000', '0.350', '1095000.00', 1),
(7, 'Sales Tax', 'Standard Rate', '0.180', '0.00', 1),
(8, 'Property Tax', 'Up to Rs50,000,000', '0.030', '0.00', 1),
(9, 'Property Tax', 'Rs50,000,001 - Rs100,000,000', '0.035', '0.00', 1),
(10, 'Property Tax', 'Above Rs100,000,000', '0.040', '0.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `ID` int(5) NOT NULL,
  `FullName` varchar(250) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Email` varchar(250) DEFAULT NULL,
  `Password` varchar(250) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`ID`, `FullName`, `MobileNumber`, `Email`, `Password`, `RegDate`) VALUES
(6, 'Ayesha ', 6667788, 'ayesha@gmail.com', '202cb962ac59075b964b07152d234b70', '2024-09-06 22:55:15'),
(10, 'Ateeqa', 123456789, 'Ateeqa@gmail.com', 'e6f08dfa83fe4e58c34924d60d5ced57', '2024-09-09 17:14:26'),
(14, 'wafa', 987654321, 'wafa@gmail.com', '3a20c03018aa42db1bbfc878da9bcdf8', '2024-09-09 23:42:32'),
(17, 'wafaaa', 1234, 'wafaaa@gmail.com', '698d51a19d8a121ce581499d7b701668', '2024-09-16 20:39:25'),
(18, 'Sadia Shezadi', 311, 'sadiashezadi@gmail.com', '91b5cd208feabcc9b01cd14b7e4e83ad', '2024-09-21 17:32:12'),
(19, 'hina', 12667788, 'amna@gmail.com', 'fe64655948fe14be8d78b67ff31c212a', '2024-09-27 15:41:27'),
(20, 'Sadia Shezadi', 1111111111, 'sadia@gmail.com', '91b5cd208feabcc9b01cd14b7e4e83ad', '2024-09-27 21:24:33'),
(23, 'Aishy', 1234567, 'diya@gmail.com', '900150983cd24fb0d6963f7d28e17f72', '2024-10-02 02:51:32'),
(28, 'saman g', 989, 'saman@gmail.com', '7eb681a31a3c08ee6a429a81fc97e677', '2024-11-15 19:14:54'),
(31, 'viva', 1233445566, 'viva@gmail.com', '91d8dfd979468e3fc76f40300fbe8293', '2025-02-17 18:22:25');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reports`
--

CREATE TABLE `tbl_reports` (
  `ReportID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `tax_year` year(4) NOT NULL,
  `income_amount` decimal(15,2) DEFAULT 0.00,
  `sales_amount` decimal(15,2) DEFAULT 0.00,
  `property_value` decimal(15,2) DEFAULT 0.00,
  `total_taxable_amount` decimal(15,2) GENERATED ALWAYS AS (`income_amount` + `sales_amount` + `property_value`) STORED,
  `total_tax_amount` decimal(15,2) DEFAULT 0.00,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_reports`
--

INSERT INTO `tbl_reports` (`ReportID`, `UserID`, `tax_year`, `income_amount`, `sales_amount`, `property_value`, `total_tax_amount`, `creation_date`) VALUES
(1, 6, 2024, '1100000.00', '100000.00', '135000.00', '34550.00', '2025-01-10 22:30:30'),
(2, 6, 2025, '3700000.00', '860000.00', '0.00', '617300.00', '2025-01-11 16:06:16'),
(4, 6, 2023, '0.00', '0.00', '0.00', '0.00', '2025-02-18 05:25:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblcontact`
--
ALTER TABLE `tblcontact`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblincometax`
--
ALTER TABLE `tblincometax`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_incometax_user` (`UserID`);

--
-- Indexes for table `tblnotifications`
--
ALTER TABLE `tblnotifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `fk_recipient_user` (`recipient_id`);

--
-- Indexes for table `tblpayments`
--
ALTER TABLE `tblpayments`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_user_id` (`UserID`);

--
-- Indexes for table `tblpropertytax`
--
ALTER TABLE `tblpropertytax`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblsalestax`
--
ALTER TABLE `tblsalestax`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_salestax_user` (`UserID`);

--
-- Indexes for table `tbltaxrates`
--
ALTER TABLE `tbltaxrates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_taxrates_admin` (`AdminID`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_reports`
--
ALTER TABLE `tbl_reports`
  ADD PRIMARY KEY (`ReportID`),
  ADD KEY `UserID` (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblcontact`
--
ALTER TABLE `tblcontact`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tblincometax`
--
ALTER TABLE `tblincometax`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblnotifications`
--
ALTER TABLE `tblnotifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tblpayments`
--
ALTER TABLE `tblpayments`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tblpropertytax`
--
ALTER TABLE `tblpropertytax`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tblsalestax`
--
ALTER TABLE `tblsalestax`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `tbltaxrates`
--
ALTER TABLE `tbltaxrates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `tbl_reports`
--
ALTER TABLE `tbl_reports`
  MODIFY `ReportID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblincometax`
--
ALTER TABLE `tblincometax`
  ADD CONSTRAINT `fk_incometax_user` FOREIGN KEY (`UserID`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `tblnotifications`
--
ALTER TABLE `tblnotifications`
  ADD CONSTRAINT `fk_recipient_user` FOREIGN KEY (`recipient_id`) REFERENCES `tbluser` (`ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `tblnotifications_ibfk_1` FOREIGN KEY (`recipient_id`) REFERENCES `tbluser` (`ID`),
  ADD CONSTRAINT `tblnotifications_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `tbladmin` (`ID`);

--
-- Constraints for table `tblpayments`
--
ALTER TABLE `tblpayments`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`UserID`) REFERENCES `tbluser` (`ID`);

--
-- Constraints for table `tblsalestax`
--
ALTER TABLE `tblsalestax`
  ADD CONSTRAINT `fk_salestax_user` FOREIGN KEY (`UserID`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `tbltaxrates`
--
ALTER TABLE `tbltaxrates`
  ADD CONSTRAINT `fk_taxrates_admin` FOREIGN KEY (`AdminID`) REFERENCES `tbladmin` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tbl_reports`
--
ALTER TABLE `tbl_reports`
  ADD CONSTRAINT `tbl_reports_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
