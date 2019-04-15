-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 15, 2019 at 01:35 AM
-- Server version: 5.7.21
-- PHP Version: 5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coop`
--
CREATE DATABASE IF NOT EXISTS `coop` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `coop`;

-- --------------------------------------------------------

--
-- Table structure for table `amortization_schedule`
--

DROP TABLE IF EXISTS `amortization_schedule`;
CREATE TABLE IF NOT EXISTS `amortization_schedule` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `loan_id` int(11) UNSIGNED NOT NULL,
  `due_date` date NOT NULL,
  `amount_paid` decimal(15,2) NOT NULL,
  `principal_amount` decimal(15,2) NOT NULL,
  `interest` decimal(15,2) NOT NULL,
  `surcharge` decimal(15,2) NOT NULL,
  `current_balance` decimal(15,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `loan_id` (`loan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `applicants`
--

DROP TABLE IF EXISTS `applicants`;
CREATE TABLE IF NOT EXISTS `applicants` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `given_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `name_suffix` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `birth_place` varchar(255) DEFAULT NULL,
  `civil_status` varchar(255) DEFAULT NULL,
  `spouse_id` int(11) UNSIGNED DEFAULT NULL,
  `residential_address` varchar(255) DEFAULT NULL,
  `provincial_address` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `education` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `office_address` varchar(255) DEFAULT NULL,
  `monthly_salary` decimal(15,2) DEFAULT '0.00',
  `business_name` varchar(255) DEFAULT NULL,
  `monthly_income` decimal(15,2) DEFAULT '0.00',
  `dependents` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `spouse_id` (`spouse_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1000018 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `applicants`
--

INSERT INTO `applicants` (`id`, `given_name`, `middle_name`, `last_name`, `name_suffix`, `birth_date`, `birth_place`, `civil_status`, `spouse_id`, `residential_address`, `provincial_address`, `contact_number`, `education`, `occupation`, `office_address`, `monthly_salary`, `business_name`, `monthly_income`, `dependents`) VALUES
(1000001, 'Admin', 'Syak Sa', 'Ni', NULL, '1990-11-01', 'Baguio City', 'Single', NULL, 'Baguio City', 'Tublay, Benguet', '1234567', 'College Graduate', 'Farmer', 'Home', '10000.00', NULL, NULL, NULL),
(1000002, 'Jocelyn', 'Cashier', 'Cashier', NULL, '1998-09-09', 'ALAPANG', 'Single', NULL, 'Alapang, benguet', 'Alapang', '09123456782', 'College', 'bookkeeper', '011 Dapiting Alapang, La Trinidad Benguet', '10000.00', NULL, NULL, NULL),
(1000006, 'Juana', 'De La', 'Cruz', NULL, '1990-03-22', 'La Trinidad', 'Married', 1000007, 'La Trinidad, Benguet', 'Tublay, Benguet', '6618931', 'College Graduate', 'Housewife', 'Home', '10000.00', NULL, NULL, NULL),
(1000007, 'Juan', 'Ricardo', 'Cruz', NULL, '1990-02-28', 'Baguio City', 'Married', 1000006, 'Baguio City', 'Tublay, Benguet', '4221793', 'High School Graduate', 'Farmer', 'Baguio City', '15000.00', NULL, NULL, NULL),
(1000008, 'Juan', 'Antero', 'Masipag', NULL, '1990-02-28', 'Baguio City', 'Single', NULL, 'Baguio City', 'Tublay, Benguet', '4221793', 'High School Graduate', 'Farmer', 'Baguio City', '15000.00', NULL, NULL, NULL),
(1000009, 'John', 'Smith', 'Doe', NULL, '2014-03-14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, '0.00', 0),
(1000010, 'Juana', 'Del', 'Rosario', NULL, '1990-04-05', 'Baguio City', 'Single', NULL, 'Baguio City', 'Tublay, Benguet', '4221799', 'College Graduate', 'Housewife', 'Home', '10000.00', NULL, NULL, NULL),
(1000011, 'das', 'ddasda', 'dasd', 'dasd', '1990-04-06', 'dsadas', 'Single', NULL, 'dsadsa', 'dasdsa', '4221793', 'dasdsa', 'dadasdadsa', 'dsadsa', '10000.00', NULL, NULL, NULL),
(1000012, 'Board', 'Of', 'Directors', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, '0.00', 0),
(1000013, 'melisa', 'madco', 'Chaur', NULL, '1997-03-03', 'bontoc,mt. province', 'Single', NULL, 'Kias, Baguio City', 'Bontoc, Mt. Province', '09484783673', 'College', 'student', 'ccdc', '500.00', NULL, NULL, NULL),
(1000014, 'Linda', 'Tampican', 'Mangawa', NULL, '1996-09-07', 'Tadian, Mt. Province', 'Single', NULL, 'Balili, La Trinidad Benguet', 'Tadian, Mt. Province', '09123456789', 'College', 'Student', 'Bsu', '5000.00', NULL, NULL, NULL),
(1000015, 'rap jasper', 'candelario', 'gayaman', NULL, '1998-09-17', 'Baguio City', 'Single', NULL, 'Magsaysay, Baguio City', 'Ifugao', '09123456789', 'College', 'agent', 'Camp Johnhay', '13000.00', NULL, NULL, NULL),
(1000016, 'Juliet', 'Madco', 'Chaur', NULL, '1995-07-04', 'Baguio City', 'Single', NULL, 'Kias, Baguio City', 'Bontoc, Mt. Province', '09098448137', 'High School', 'house keeper', 'Magsaysay', '6000.00', NULL, NULL, NULL),
(1000017, 'Harvey', 'Camil', 'Anas', NULL, '1994-12-05', 'PCDH', 'Single', NULL, 'Baguio', 'Baguio', '09204452381', 'BSIT', 'None', 'None', '100000.00', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `audit`
--

DROP TABLE IF EXISTS `audit`;
CREATE TABLE IF NOT EXISTS `audit` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL,
  `date_recorded` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_audit_employees` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `audit`
--

INSERT INTO `audit` (`id`, `employee_id`, `action`, `date_recorded`) VALUES
(97, 1000002, 'Logged out', '2017-05-16 11:55:30'),
(98, 1000001, 'Logged in', '2017-05-16 11:55:38'),
(99, 1000001, 'Logged in', '2019-04-15 09:31:18'),
(100, 1000001, 'Logged out', '2019-04-15 09:31:30'),
(101, 1000002, 'Logged in', '2019-04-15 09:31:39'),
(102, 1000002, 'Added a new member.', '2019-04-15 09:32:26'),
(103, 1000002, 'Logged out', '2019-04-15 09:32:46'),
(104, 1000012, 'Logged in', '2019-04-15 09:32:51'),
(105, 1000012, 'Updated member info.', '2019-04-15 09:32:54'),
(106, 1000012, 'Logged out', '2019-04-15 09:32:59'),
(107, 1000002, 'Logged in', '2019-04-15 09:33:03'),
(108, 1000002, 'Updated member info.', '2019-04-15 09:33:10'),
(109, 1000002, 'Logged out', '2019-04-15 09:33:30'),
(110, 1000001, 'Logged in', '2019-04-15 09:33:34'),
(111, 1000001, 'Logged out', '2019-04-15 09:34:28');

-- --------------------------------------------------------

--
-- Table structure for table `beneficiaries`
--

DROP TABLE IF EXISTS `beneficiaries`;
CREATE TABLE IF NOT EXISTS `beneficiaries` (
  `id` int(11) UNSIGNED NOT NULL,
  `member_id` int(11) UNSIGNED NOT NULL,
  `relationship` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`member_id`) USING BTREE,
  KEY `beneficiaries_ibfk_2` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `beneficiaries`
--
DROP TRIGGER IF EXISTS `beneficiaries_after_insert`;
DELIMITER $$
CREATE TRIGGER `beneficiaries_after_insert` AFTER INSERT ON `beneficiaries` FOR EACH ROW BEGIN



	INSERT INTO audit VALUES(NULL, @user_id, 'Added a beneficiary.', NOW());



END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `beneficiaries_after_update`;
DELIMITER $$
CREATE TRIGGER `beneficiaries_after_update` AFTER UPDATE ON `beneficiaries` FOR EACH ROW BEGIN



	INSERT INTO audit VALUES(NULL, @user_id, 'Updated beneficiary info.');



END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `category`, `name`, `value`) VALUES
(1, 'Membership', 'Fee', '200'),
(2, 'Savings Account', 'Minimum Amount To Earn Interest', '200'),
(3, 'Savings Account', 'Interest Rate', '1.5'),
(4, 'Savings Account', 'Minimum Amount To Avoid Service Charge', '500'),
(5, 'Savings Account', 'Service Charge', '20'),
(6, 'Time Deposit', 'Minimum Amount', '50000'),
(7, 'Time Deposit', 'Minimum Duration', '3'),
(8, 'Time Deposit', 'Interest Rate', '5'),
(9, 'Time Deposit', 'Service Charge', '150'),
(10, 'Emergency Loan', 'Maximum Amount', '5000'),
(11, 'Emergency Loan', 'Interest Rate', '0.015'),
(12, 'Emergency Loan', 'Service Fee', '0.03'),
(13, 'Emergency Loan', 'Filing Fee', '25'),
(14, 'Emergency Loan', 'Surcharge Rate', '0.02'),
(15, 'Emergency Loan', 'Insurance Rate', '0.056'),
(16, 'Emergency Loan', 'Minimum Duration', '3'),
(17, 'Emergency Loan', 'Maximum Duration', '3'),
(18, 'Emergency Loan', 'Grace Period', '10'),
(19, 'Emergency Loan', 'Retention Fund', '0.01'),
(20, 'Regular With Collateral Loan', 'Maximum Amount', '50000'),
(21, 'Regular With Collateral Loan', 'Interest Rate', '0.015'),
(22, 'Regular With Collateral Loan', 'Service Fee', '0.03'),
(23, 'Regular With Collateral Loan', 'Filing Fee', '25'),
(24, 'Regular With Collateral Loan', 'Surcharge Rate', '0.02'),
(25, 'Regular With Collateral Loan', 'Insurance Rate', '0.056'),
(26, 'Regular With Collateral Loan', 'Minimum Duration', '6'),
(27, 'Regular With Collateral Loan', 'Maximum Duration', '12'),
(28, 'Regular With Collateral Loan', 'Grace Period', '10'),
(29, 'Regular With Collateral Loan', 'Retention Fund', '0.01'),
(30, 'Regular With Coborrower Loan', 'Maximum Amount', '50000'),
(31, 'Regular With Coborrower Loan', 'Interest Rate', '0.015'),
(32, 'Regular With Coborrower Loan', 'Service Fee', '0.03'),
(33, 'Regular With Coborrower Loan', 'Filing Fee', '25'),
(34, 'Regular With Coborrower Loan', 'Surcharge Rate', '0.02'),
(35, 'Regular With Coborrower Loan', 'Insurance Rate', '0.056'),
(36, 'Regular With Coborrower Loan', 'Minimum Duration', '6'),
(37, 'Regular With Coborrower Loan', 'Maximum Duration', '12'),
(38, 'Regular With Coborrower Loan', 'Grace Period', '10'),
(39, 'Regular With Coborrower Loan', 'Retention Fund', '0.01'),
(40, 'Salary Loan', 'Maximum Amount', '50000'),
(41, 'Salary Loan', 'Interest Rate', '0.015'),
(42, 'Salary Loan', 'Service Fee', '0.03'),
(43, 'Salary Loan', 'Filing Fee', '25'),
(44, 'Salary Loan', 'Surcharge Rate', '0.02'),
(45, 'Salary Loan', 'Insurance Rate', '0.056'),
(46, 'Salary Loan', 'Minimum Duration', '6'),
(47, 'Salary Loan', 'Maximum Duration', '12'),
(48, 'Salary Loan', 'Grace Period', '10'),
(49, 'Salary Loan', 'Retention Fund', '0.01');

-- --------------------------------------------------------

--
-- Table structure for table `da_interest`
--

DROP TABLE IF EXISTS `da_interest`;
CREATE TABLE IF NOT EXISTS `da_interest` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_id` int(11) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `date_recorded` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=167 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `da_service_charges`
--

DROP TABLE IF EXISTS `da_service_charges`;
CREATE TABLE IF NOT EXISTS `da_service_charges` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_id` int(11) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `date_recorded` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `da_transactions`
--

DROP TABLE IF EXISTS `da_transactions`;
CREATE TABLE IF NOT EXISTS `da_transactions` (
  `transaction_id` int(11) UNSIGNED NOT NULL,
  `account_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`transaction_id`,`account_id`),
  KEY `da_transactions_ibfk_2` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `deposit_accounts`
--

DROP TABLE IF EXISTS `deposit_accounts`;
CREATE TABLE IF NOT EXISTS `deposit_accounts` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id` int(11) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `date_opened` datetime NOT NULL,
  `date_matured` datetime DEFAULT NULL,
  `interest_rate` decimal(5,2) DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=100024 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `deposit_accounts`
--

INSERT INTO `deposit_accounts` (`id`, `member_id`, `amount`, `date_opened`, `date_matured`, `interest_rate`, `status`) VALUES
(100023, 1000017, '0.00', '2019-04-15 09:33:10', NULL, NULL, 'Active');

--
-- Triggers `deposit_accounts`
--
DROP TRIGGER IF EXISTS `deposit_accounts_after_insert`;
DELIMITER $$
CREATE TRIGGER `deposit_accounts_after_insert` AFTER INSERT ON `deposit_accounts` FOR EACH ROW BEGIN



	CASE



		WHEN NEW.interest_rate IS NOT NULL



			THEN INSERT INTO audit VALUES(NULL, @user_id, 'Opened a time deposit account.', NOW());



		ELSE SET @user_id = @user_id;



	END CASE;



END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `deposit_accounts_after_update`;
DELIMITER $$
CREATE TRIGGER `deposit_accounts_after_update` AFTER UPDATE ON `deposit_accounts` FOR EACH ROW BEGIN



	CASE



		WHEN NEW.interest_rate IS NULL AND NEW.amount > OLD.amount



			THEN INSERT INTO audit VALUES(NULL, @user_id, 'Added a new deposit.', NOW());



		WHEN NEW.interest_rate IS NULL AND NEW.amount < OLD.amount



			THEN INSERT INTO audit VALUES(NULL, @user_id, 'Added a new withdrawal.', NOW());



		ELSE SET @user_id = @user_id;



	END CASE;



END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE IF NOT EXISTS `employees` (
  `id` int(11) UNSIGNED NOT NULL,
  `position` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_logout` datetime DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `position`, `username`, `password`, `last_login`, `last_logout`, `status`) VALUES
(1000001, 'Admin', 'admin', 'admin', '2019-04-15 09:33:34', '2019-04-15 09:34:28', 'Active'),
(1000002, 'Front Desk', 'encoder', 'encoder', '2019-04-15 09:33:03', '2019-04-15 09:33:30', 'Active'),
(1000007, 'Board of Directors', 'fd', 'fd', NULL, NULL, 'Active'),
(1000012, 'Board of Directors', 'board', 'board', '2019-04-15 09:32:51', '2019-04-15 09:32:59', 'Active');

--
-- Triggers `employees`
--
DROP TRIGGER IF EXISTS `employees_after_insert`;
DELIMITER $$
CREATE TRIGGER `employees_after_insert` AFTER INSERT ON `employees` FOR EACH ROW BEGIN



	INSERT INTO audit VALUES(NULL, @user_id, 'Added new employee.', NOW());



END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `employees_after_update`;
DELIMITER $$
CREATE TRIGGER `employees_after_update` AFTER UPDATE ON `employees` FOR EACH ROW BEGIN



	CASE



		WHEN OLD.last_login IS NULL AND NEW.last_login IS NOT NULL



			THEN INSERT INTO audit VALUES(NULL, @user_id, 'Logged in', NOW());



		WHEN OLD.last_login != NEW.last_login



			THEN INSERT INTO audit VALUES(NULL, @user_id, 'Logged in', NOW());



		WHEN OLD.last_logout IS NULL AND NEW.last_logout IS NOT NULL



			THEN INSERT INTO audit VALUES(NULL, @user_id, 'Logged out', NOW());



		WHEN OLD.last_logout != NEW.last_logout



			THEN INSERT INTO audit VALUES(NULL, @user_id, 'Logged out', NOW());



		WHEN NEW.status = 'Active'



			THEN INSERT INTO audit VALUES(NULL, @user_id, 'Activated an employee account', NOW());



		WHEN NEW.status = 'Inactive'



			THEN INSERT INTO audit VALUES(NULL, @user_id, 'Deactivated an employee account', NOW());



		ELSE INSERT INTO audit VALUES(NULL, @user_id, 'Updated employee info', NOW());



	END CASE;



END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

DROP TABLE IF EXISTS `loans`;
CREATE TABLE IF NOT EXISTS `loans` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id` int(11) UNSIGNED NOT NULL,
  `date_applied` datetime NOT NULL,
  `approved_by` int(11) UNSIGNED DEFAULT NULL,
  `date_approved` datetime DEFAULT NULL,
  `amount_applied` decimal(15,2) NOT NULL,
  `loan_type` varchar(255) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `service_fee` decimal(15,2) NOT NULL,
  `interest` decimal(15,2) NOT NULL,
  `lrf` decimal(15,2) NOT NULL,
  `insurance` decimal(15,2) NOT NULL,
  `filing_fee` decimal(15,2) NOT NULL,
  `net_amount` decimal(15,2) NOT NULL,
  `date_released` datetime DEFAULT NULL,
  `date_matured` datetime NOT NULL,
  `interest_rate` decimal(5,4) NOT NULL,
  `surcharge_rate` decimal(5,4) NOT NULL,
  `grace_period` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `collateral` varchar(255) DEFAULT NULL,
  `coborrowers` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`),
  KEY `approved_by` (`approved_by`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `loans`
--
DROP TRIGGER IF EXISTS `loans_after_insert`;
DELIMITER $$
CREATE TRIGGER `loans_after_insert` AFTER INSERT ON `loans` FOR EACH ROW BEGIN



	INSERT INTO audit VALUES(NULL, @user_id, 'Added a new loan.', NOW());



END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `loan_transactions`
--

DROP TABLE IF EXISTS `loan_transactions`;
CREATE TABLE IF NOT EXISTS `loan_transactions` (
  `transaction_id` int(11) UNSIGNED NOT NULL,
  `loan_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`transaction_id`,`loan_id`),
  KEY `loan_transactions_ibfk_2` (`loan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `memberships`
--

DROP TABLE IF EXISTS `memberships`;
CREATE TABLE IF NOT EXISTS `memberships` (
  `id` int(11) UNSIGNED NOT NULL,
  `date_applied` datetime NOT NULL,
  `date_approved` datetime DEFAULT NULL,
  `approved_by` int(11) UNSIGNED DEFAULT NULL,
  `transaction_id` int(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_id` (`transaction_id`),
  KEY `approved_by` (`approved_by`),
  KEY `approved_by_2` (`approved_by`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `memberships`
--

INSERT INTO `memberships` (`id`, `date_applied`, `date_approved`, `approved_by`, `transaction_id`) VALUES
(1000017, '2019-04-15 09:32:26', '2019-04-15 09:32:54', 1000012, 100075);

--
-- Triggers `memberships`
--
DROP TRIGGER IF EXISTS `memberships_after_insert`;
DELIMITER $$
CREATE TRIGGER `memberships_after_insert` AFTER INSERT ON `memberships` FOR EACH ROW BEGIN



	INSERT INTO audit VALUES(NULL, @user_id, 'Added a new member.', NOW());



END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `memberships_after_update`;
DELIMITER $$
CREATE TRIGGER `memberships_after_update` AFTER UPDATE ON `memberships` FOR EACH ROW BEGIN



	INSERT INTO audit VALUES(NULL, @user_id, 'Updated member info.', NOW());



END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sc_transactions`
--

DROP TABLE IF EXISTS `sc_transactions`;
CREATE TABLE IF NOT EXISTS `sc_transactions` (
  `transaction_id` int(11) UNSIGNED NOT NULL,
  `share_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`transaction_id`,`share_id`),
  KEY `sc_transactions_ibfk_2` (`share_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `share_capital`
--

DROP TABLE IF EXISTS `share_capital`;
CREATE TABLE IF NOT EXISTS `share_capital` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id` int(11) UNSIGNED NOT NULL,
  `amount` decimal(15,0) NOT NULL,
  `date_activated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `member_id` (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `share_capital`
--

INSERT INTO `share_capital` (`id`, `member_id`, `amount`, `date_activated`) VALUES
(18, 1000017, '0', '2019-04-15 09:33:10');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id` int(11) UNSIGNED NOT NULL,
  `date_recorded` datetime NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `or_num` varchar(255) DEFAULT NULL,
  `purpose` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=100076 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `member_id`, `date_recorded`, `amount`, `or_num`, `purpose`) VALUES
(100075, 1000017, '2019-04-15 09:33:10', '200.00', '123456', 'Membership Fee Payment');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `amortization_schedule`
--
ALTER TABLE `amortization_schedule`
  ADD CONSTRAINT `amortization_schedule_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `applicants`
--
ALTER TABLE `applicants`
  ADD CONSTRAINT `applicants_ibfk_1` FOREIGN KEY (`spouse_id`) REFERENCES `applicants` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `audit`
--
ALTER TABLE `audit`
  ADD CONSTRAINT `FK_audit_employees` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD CONSTRAINT `beneficiaries_ibfk_1` FOREIGN KEY (`id`) REFERENCES `applicants` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `beneficiaries_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `memberships` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `da_interest`
--
ALTER TABLE `da_interest`
  ADD CONSTRAINT `da_interest_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `deposit_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `da_service_charges`
--
ALTER TABLE `da_service_charges`
  ADD CONSTRAINT `da_service_charges_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `deposit_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `da_transactions`
--
ALTER TABLE `da_transactions`
  ADD CONSTRAINT `da_transactions_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `da_transactions_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `deposit_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `deposit_accounts`
--
ALTER TABLE `deposit_accounts`
  ADD CONSTRAINT `deposit_accounts_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `memberships` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`id`) REFERENCES `applicants` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `memberships` (`id`),
  ADD CONSTRAINT `loans_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `employees` (`id`);

--
-- Constraints for table `loan_transactions`
--
ALTER TABLE `loan_transactions`
  ADD CONSTRAINT `loan_transactions_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loan_transactions_ibfk_2` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `memberships`
--
ALTER TABLE `memberships`
  ADD CONSTRAINT `memberships_ibfk_1` FOREIGN KEY (`id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `memberships_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `employees` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `memberships_ibfk_3` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `sc_transactions`
--
ALTER TABLE `sc_transactions`
  ADD CONSTRAINT `sc_transactions_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sc_transactions_ibfk_2` FOREIGN KEY (`share_id`) REFERENCES `share_capital` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `share_capital`
--
ALTER TABLE `share_capital`
  ADD CONSTRAINT `share_capital_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `memberships` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `memberships` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
