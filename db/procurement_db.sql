-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 18, 2021 at 02:14 PM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `procurement_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachment`
--

CREATE TABLE `attachment` (
  `id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `purchase_order_id` int(11) DEFAULT NULL,
  `requisition_id` int(11) DEFAULT NULL,
  `rfp_id` int(11) DEFAULT NULL,
  `contract_application_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `attachment`
--

INSERT INTO `attachment` (`id`, `title`, `url`, `purchase_order_id`, `requisition_id`, `rfp_id`, `contract_application_id`, `status`) VALUES
(1, 'Benefit Received', 'File-20211012011238-0-Gambananye (copy).jpeg', NULL, 6, NULL, NULL, 1),
(5, 'T&Cs', 'File-20211014030432-0-hts-log.txt', NULL, NULL, 2, NULL, 1),
(6, 'T&Cs', 'File-20211014030432-1-index.html', NULL, NULL, 2, NULL, 1),
(11, 'company profile', 'File-20211016102947-0-hts-log.txt', NULL, NULL, NULL, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `value` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `name`, `value`, `status`) VALUES
(1, 'site_name', 'eProcurement', 1),
(2, 'site_url', 'http://127.0.0.1/procurementsystem/', 1),
(3, 'company_description', 'Desc', 1),
(4, 'company_name', 'eProcurement', 1),
(5, 'office_location', 'Mbarara', 1),
(6, 'password_generator_length', '8', 1),
(7, 'percentage_tax', '18', 1),
(8, 'currency_symbol', 'UGx', 1),
(9, 'currency_symbol_location', 'Left', 1),
(10, 'company_logo', 'uploads/company_logo.png', 1),
(11, 'company_favicon', 'uploads/company_favicon.png', 1),
(12, 'email_from_address', 'test@gmail.com', 1),
(13, 'email_from_name', 'Test Test', 1),
(14, 'email_smtp_host', 'smtp.gmail.com', 1),
(15, 'email_smtp_port', '465', 1),
(16, 'email_smtp_username', 'test@gmail.com', 1),
(17, 'email_smtp_password', NULL, 1),
(18, 'email_smtp_security', 'ssl', 1),
(19, 'email_smtp_auth', 'false', 1),
(20, 'email_smtp_enable', 'true', 1),
(21, 'email_smtp_domain', 'smtp.gmail.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `contract_application`
--

CREATE TABLE `contract_application` (
  `id` int(11) NOT NULL,
  `rfp_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `application_response` text DEFAULT NULL,
  `application_date` date DEFAULT NULL,
  `application_status` varchar(50) DEFAULT 'Pending',
  `contract_title` text DEFAULT NULL,
  `contract_amount` float DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `supplier_signiture` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contract_application`
--

INSERT INTO `contract_application` (`id`, `rfp_id`, `user_id`, `application_response`, `application_date`, `application_status`, `contract_title`, `contract_amount`, `start_date`, `end_date`, `supplier_signiture`, `status`) VALUES
(1, 2, 1, '{\"How long have you been dealing\":\"The prospective bidder should be able to understand the nature of the business and the goals it wishes to achieve with the project. The project must be defined in enough detail for the bidder to clearly understand its scope and all of the products and services that must be provided in order to carry it out. The format of the expected proposals must also be detailed. Uniform responses are needed to compare and contrast offers.\\r\\n RFPs follow a fairly rigid format, although that format may vary among the agencies and companies that prepare them. This sample from the Center for Planning Excellence in Baton Rouge, Louisiana, shows the elements in a typical RFP, which include an introduction and background, a description of the deliverables, and information about the selection criteria\",\" Any other info to know\":\"The prospective bidder should be able to understand the nature of the business and the goals it wishes to achieve with the project. The project must be defined in enough detail for the bidder to clearly understand its scope and all of the products and services that must be provided in order to carry it out. The format of the expected proposals must also be detailed. Uniform responses are needed to compare and contrast offers.\\r\\n RFPs follow a fairly rigid format, although that format may vary among the agencies and companies that prepare them. This sample from the Center for Planning Excellence in Baton Rouge, Louisiana, shows the elements in a typical RFP, which include an introduction and background, a description of the deliverables, and information about the selection criteria\",\" What is the project budget?\":\"The prospective bidder should be able to understand the nature of the business and the goals it wishes to achieve with the project. The project must be defined in enough detail for the bidder to clearly understand its scope and all of the products and services that must be provided in order to carry it out. The format of the expected proposals must also be detailed. Uniform responses are needed to compare and contrast offers.\\r\\n RFPs follow a fairly rigid format, although that format may vary among the agencies and companies that prepare them. This sample from the Center for Planning Excellence in Baton Rouge, Louisiana, shows the elements in a typical RFP, which include an introduction and background, a description of the deliverables, and information about the selection criteria\",\"What are the end goals of the project?\":\"The prospective bidder should be able to understand the nature of the business and the goals it wishes to achieve with the project. The project must be defined in enough detail for the bidder to clearly understand its scope and all of the products and services that must be provided in order to carry it out. The format of the expected proposals must also be detailed. Uniform responses are needed to compare and contrast offers.\\r\\n RFPs follow a fairly rigid format, although that format may vary among the agencies and companies that prepare them. This sample from the Center for Planning Excellence in Baton Rouge, Louisiana, shows the elements in a typical RFP, which include an introduction and background, a description of the deliverables, and information about the selection criteria\",\"What factors are crucial deal breakers?\":\"The prospective bidder should be able to understand the nature of the business and the goals it wishes to achieve with the project. The project must be defined in enough detail for the bidder to clearly understand its scope and all of the products and services that must be provided in order to carry it out. The format of the expected proposals must also be detailed. Uniform responses are needed to compare and contrast offers.\\r\\n RFPs follow a fairly rigid format, although that format may vary among the agencies and companies that prepare them. This sample from the Center for Planning Excellence in Baton Rouge, Louisiana, shows the elements in a typical RFP, which include an introduction and background, a description of the deliverables, and information about the selection criteria\",\"How will you protect our organization from risk?\":\"The prospective bidder should be able to understand the nature of the business and the goals it wishes to achieve with the project. The project must be defined in enough detail for the bidder to clearly understand its scope and all of the products and services that must be provided in order to carry it out. The format of the expected proposals must also be detailed. Uniform responses are needed to compare and contrast offers.\\r\\n RFPs follow a fairly rigid format, although that format may vary among the agencies and companies that prepare them. This sample from the Center for Planning Excellence in Baton Rouge, Louisiana, shows the elements in a typical RFP, which include an introduction and background, a description of the deliverables, and information about the selection criteria\"}', '2021-10-21', 'Rejected', '', NULL, NULL, NULL, NULL, 1),
(3, 2, 2, '{\"How long have you been dealing\":\"The prospective bidder should be able to understand the nature of the business and the goals it wishes to achieve with the project. The project must be defined in enough detail for the bidder to clearly understand its scope and all of the products and services that must be provided in order to carry it out. The format of the expected proposals must also be detailed. Uniform responses are needed to compare and contrast offers. RFPs follow a fairly rigid format, although that format may vary among the agencies and companies that prepare them. This sample from the Center for Planning Excellence in Baton Rouge, Louisiana, shows the elements in a typical RFP, which include an introduction and background, a description of the deliverables, and information about the selection criteria\",\" Any other info to know\":\"The prospective bidder should be able to understand the nature of the business and the goals it wishes to achieve with the project. The project must be defined in enough detail for the bidder to clearly understand its scope and all of the products and services that must be provided in order to carry it out. The format of the expected proposals must also be detailed. Uniform responses are needed to compare and contrast offers. RFPs follow a fairly rigid format, although that format may vary among the agencies and companies that prepare them. This sample from the Center for Planning Excellence in Baton Rouge, Louisiana, shows the elements in a typical RFP, which include an introduction and background, a description of the deliverables, and information about the selection criteria\",\" What is the project budget?\":\"Data.....\",\"What are the end goals of the project?\":\"\",\"What factors are crucial deal breakers?\":\"\",\"How will you protect our organization from risk?\":\"\"}', '2021-10-16', 'Rejected', NULL, NULL, NULL, NULL, NULL, 1),
(5, 2, 3, '{\"How long have you been dealing\":\"The prospective bidder should be able to understand the nature of the business and the goals it wishes to achieve with the project. The project must be defined in enough detail for the bidder to clearly understand its scope and all of the products and services that must be provided in order to carry it out. The format of the expected proposals must also be detailed. Uniform responses are needed to compare and contrast offers.\\r\\n RFPs follow a fairly rigid format, although that format may vary among the agencies and companies that prepare them. This sample from the Center for Planning Excellence in Baton Rouge, Louisiana, shows the elements in a typical RFP, which include an introduction and background, a description of the deliverables, and information about the selection criteria\",\" Any other info to know\":\"The prospective bidder should be able to understand the nature of the business and the goals it wishes to achieve with the project. The project must be defined in enough detail for the bidder to clearly understand its scope and all of the products and services that must be provided in order to carry it out. The format of the expected proposals must also be detailed. Uniform responses are needed to compare and contrast offers.\\r\\n RFPs follow a fairly rigid format, although that format may vary among the agencies and companies that prepare them. This sample from the Center for Planning Excellence in Baton Rouge, Louisiana, shows the elements in a typical RFP, which include an introduction and background, a description of the deliverables, and information about the selection criteria\",\" What is the project budget?\":\"The prospective bidder should be able to understand the nature of the business and the goals it wishes to achieve with the project. The project must be defined in enough detail for the bidder to clearly understand its scope and all of the products and services that must be provided in order to carry it out. The format of the expected proposals must also be detailed. Uniform responses are needed to compare and contrast offers.\\r\\n RFPs follow a fairly rigid format, although that format may vary among the agencies and companies that prepare them. This sample from the Center for Planning Excellence in Baton Rouge, Louisiana, shows the elements in a typical RFP, which include an introduction and background, a description of the deliverables, and information about the selection criteria\",\"What are the end goals of the project?\":\"The prospective bidder should be able to understand the nature of the business and the goals it wishes to achieve with the project. The project must be defined in enough detail for the bidder to clearly understand its scope and all of the products and services that must be provided in order to carry it out. The format of the expected proposals must also be detailed. Uniform responses are needed to compare and contrast offers.\\r\\n RFPs follow a fairly rigid format, although that format may vary among the agencies and companies that prepare them. This sample from the Center for Planning Excellence in Baton Rouge, Louisiana, shows the elements in a typical RFP, which include an introduction and background, a description of the deliverables, and information about the selection criteria\",\"What factors are crucial deal breakers?\":\"The prospective bidder should be able to understand the nature of the business and the goals it wishes to achieve with the project. The project must be defined in enough detail for the bidder to clearly understand its scope and all of the products and services that must be provided in order to carry it out. The format of the expected proposals must also be detailed. Uniform responses are needed to compare and contrast offers.\\r\\n RFPs follow a fairly rigid format, although that format may vary among the agencies and companies that prepare them. This sample from the Center for Planning Excellence in Baton Rouge, Louisiana, shows the elements in a typical RFP, which include an introduction and background, a description of the deliverables, and information about the selection criteria\",\"How will you protect our organization from risk?\":\"The prospective bidder should be able to understand the nature of the business and the goals it wishes to achieve with the project. The project must be defined in enough detail for the bidder to clearly understand its scope and all of the products and services that must be provided in order to carry it out. The format of the expected proposals must also be detailed. Uniform responses are needed to compare and contrast offers.\\r\\n RFPs follow a fairly rigid format, although that format may vary among the agencies and companies that prepare them. This sample from the Center for Planning Excellence in Baton Rouge, Louisiana, shows the elements in a typical RFP, which include an introduction and background, a description of the deliverables, and information about the selection criteria\"}', '2021-10-21', 'Approved', 'Supply of Chairs', 20000, '2021-10-21', '2022-05-11', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `name`, `status`) VALUES
(1, 'TAX', 1),
(2, 'AUDIT', 1);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `name`, `status`) VALUES
(1, 'Maize', 1),
(2, 'Potatoes', 1),
(3, 'Computers', 1),
(4, 'Desktops', 1),
(5, 'Video Cameras', 1);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `log_action` text DEFAULT NULL,
  `log_time` timestamp NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `log_action`, `log_time`, `status`) VALUES
(1, 1, 'logged into the system', '2021-10-10 08:11:32', 1),
(2, 1, 'logged into the system', '2021-10-10 08:14:14', 1),
(3, 1, 'logged into the system', '2021-10-10 11:51:07', 1),
(4, 1, 'logged into the system', '2021-10-11 15:36:10', 1),
(5, 1, 'changed system settings', '2021-10-12 08:24:05', 1),
(6, 1, 'logged into the system', '2021-10-12 08:40:04', 1),
(7, 1, 'logged into the system', '2021-10-12 09:03:02', 1),
(8, 1, 'logged into the system', '2021-10-12 09:04:36', 1),
(9, 1, 'logged into the system', '2021-10-12 09:47:59', 1),
(10, 1, 'changed system settings', '2021-10-12 09:54:05', 1),
(11, 1, 'changed system logo', '2021-10-12 10:16:16', 1),
(12, 1, 'changed system settings', '2021-10-12 10:16:17', 1),
(13, 1, 'changed system logo', '2021-10-12 10:17:51', 1),
(14, 1, 'changed system settings', '2021-10-12 10:17:51', 1),
(15, 1, 'changed system logo', '2021-10-12 10:21:07', 1),
(16, 1, 'changed system settings', '2021-10-12 10:21:07', 1),
(17, 1, 'changed system logo', '2021-10-12 10:21:40', 1),
(18, 1, 'changed system settings', '2021-10-12 10:21:40', 1),
(19, 1, 'logged into the system', '2021-10-13 12:30:59', 1),
(20, 1, 'logged into the system', '2021-10-14 12:46:50', 1),
(21, 1, 'logged into the system', '2021-10-15 08:33:41', 1),
(22, 1, 'logged into the system', '2021-10-15 13:27:32', 1),
(23, 1, 'changed system settings', '2021-10-15 13:38:10', 1),
(24, 1, 'logged into the system', '2021-10-15 14:23:05', 1),
(25, 1, 'logged into the system', '2021-10-15 14:38:56', 1),
(26, 1, 'changed system settings', '2021-10-15 14:40:21', 1),
(27, 1, 'changed system settings', '2021-10-15 14:40:54', 1),
(28, 1, 'changed system settings', '2021-10-15 14:41:02', 1),
(29, 1, 'changed system settings', '2021-10-15 14:42:08', 1),
(30, 4, 'logged into the system', '2021-10-15 15:06:38', 1),
(31, 1, 'logged into the system', '2021-10-15 15:10:21', 1),
(32, 1, 'changed user information for user id ', '2021-10-15 15:18:24', 1),
(33, 1, 'changed user information for user id 1', '2021-10-15 15:19:44', 1),
(34, 3, 'logged into the system', '2021-10-16 06:21:03', 1),
(35, 1, 'logged into the system', '2021-10-16 06:51:53', 1),
(36, 1, 'logged into the system', '2021-10-16 12:02:49', 1),
(37, 1, 'logged into the system', '2021-10-17 16:28:09', 1),
(38, 1, 'logged into the system', '2021-10-18 08:12:28', 1),
(39, 1, 'logged into the system', '2021-10-18 08:38:10', 1),
(40, 1, 'logged into the system', '2021-10-18 09:02:32', 1),
(41, 3, 'logged into the system', '2021-10-18 11:32:37', 1),
(42, 1, 'logged into the system', '2021-10-18 11:48:51', 1),
(43, 1, 'logged into the system', '2021-10-18 12:07:48', 1),
(44, 1, 'logged into the system', '2021-10-18 12:09:39', 1),
(45, 1, 'logged into the system', '2021-10-18 12:11:33', 1),
(46, 1, 'changed system settings', '2021-10-18 12:11:41', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notificationtemplate`
--

CREATE TABLE `notificationtemplate` (
  `template_id` int(11) NOT NULL,
  `code` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `info` text DEFAULT NULL,
  `sms` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `notificationtemplate`
--

INSERT INTO `notificationtemplate` (`template_id`, `code`, `name`, `subject`, `message`, `info`, `sms`, `status`) VALUES
(1, 'account_activation', '', 'Account verification', '<p>Dear {names}. Thanks for creating a supplier account with us. follow the link below to activate your account.</p><p>{link}</p><p><br></p><p>{company}</p>', NULL, '', 1),
(2, 'request_for_proposal_approval', '', 'RFP Approval', '<p style=\"font-size: 14px;\">Dear<b> {names}</b>. we congratulate you for you merged to be the winner during our selection. Your contract <b>{contract_title}</b> is valid from<b> {start_date}</b> to <b>{end_date}</b></p><p style=\"font-size: 14px;\"><br></p><p style=\"font-size: 14px;\"><b>{company}</b></p>', NULL, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order`
--

CREATE TABLE `purchase_order` (
  `id` int(11) NOT NULL,
  `order_number` varchar(45) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `payment_mode` varchar(45) DEFAULT 'Cash',
  `delivery_date` date DEFAULT NULL,
  `time_created` timestamp NULL DEFAULT current_timestamp(),
  `requisition_id` int(11) NOT NULL,
  `supplier` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `purchase_order`
--

INSERT INTO `purchase_order` (`id`, `order_number`, `date`, `status`, `payment_mode`, `delivery_date`, `time_created`, `requisition_id`, `supplier`) VALUES
(1, '45543', '2021-10-15', 1, 'Cash', '2021-10-30', '2021-10-16 16:11:51', 6, 3),
(2, '4554312', '2021-10-17', 1, 'Cash', '2021-10-18', '2021-10-18 11:50:42', 6, 3);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_item`
--

CREATE TABLE `purchase_order_item` (
  `id` int(11) NOT NULL,
  `requisition_item_id` int(11) NOT NULL,
  `purchase_order_id` int(11) NOT NULL,
  `quantity` float DEFAULT NULL,
  `unit_price` float DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `time_created` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `requisition`
--

CREATE TABLE `requisition` (
  `id` int(11) NOT NULL,
  `status` int(11) DEFAULT 1,
  `department_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `requisition_number` varchar(45) DEFAULT NULL,
  `requisition_status` varchar(45) DEFAULT 'Pending',
  `approval_by` varchar(45) DEFAULT NULL,
  `time_created` timestamp NULL DEFAULT current_timestamp(),
  `approval_comment` text DEFAULT NULL,
  `approval_time` datetime DEFAULT NULL,
  `amount_requested` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `requisition`
--

INSERT INTO `requisition` (`id`, `status`, `department_id`, `user_id`, `date`, `requisition_number`, `requisition_status`, `approval_by`, `time_created`, `approval_comment`, `approval_time`, `amount_requested`) VALUES
(1, 1, 1, 1, '2021-10-10', 'JD/PRO/DGF/RT/00011', 'Approved', '1', '2021-10-10 12:33:52', 'aaa', '2021-10-15 14:07:57', 707000),
(2, 1, 1, 1, '2021-10-10', 'JD/PRO/DGF/RT/00011', 'Rejected', NULL, '2021-10-10 12:34:33', NULL, NULL, 707000),
(3, 1, 1, 1, '2021-10-10', 'JD/PRO/DGF/RT/00011', 'Pending', NULL, '2021-10-10 12:35:25', NULL, NULL, 707000),
(4, 1, 1, 1, '2021-10-10', 'JD/PRO/DGF/RT/00011', 'Pending', NULL, '2021-10-10 12:35:59', NULL, NULL, 707000),
(5, 1, 1, 1, '2021-10-10', 'JD/PRO/DGF/RT/00011', 'Pending', NULL, '2021-10-10 12:36:36', NULL, NULL, 707000),
(6, 1, 2, 1, '2021-10-10', 'JD/PRO/DGF/RT/00011', 'Approved', '1', '2021-10-10 12:36:57', 'Asssnte', '2021-10-12 14:20:40', 1046000);

-- --------------------------------------------------------

--
-- Table structure for table `requisition_item`
--

CREATE TABLE `requisition_item` (
  `id` int(11) NOT NULL,
  `requisition_id` int(11) DEFAULT NULL,
  `rfp_id` int(11) DEFAULT NULL,
  `item_id` int(11) NOT NULL,
  `purchase_order_id` int(11) DEFAULT NULL,
  `quantity` float DEFAULT NULL,
  `unit_price` float DEFAULT NULL,
  `time_created` timestamp NULL DEFAULT current_timestamp(),
  `status` int(11) DEFAULT 1,
  `unit_measure` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `requisition_item`
--

INSERT INTO `requisition_item` (`id`, `requisition_id`, `rfp_id`, `item_id`, `purchase_order_id`, `quantity`, `unit_price`, `time_created`, `status`, `unit_measure`) VALUES
(1, 5, NULL, 1, NULL, 5, 7000, '2021-10-10 12:36:36', 1, NULL),
(2, 5, NULL, 2, NULL, 56, 12000, '2021-10-10 12:36:36', 1, NULL),
(8, 6, NULL, 1, 1, 5, 70000, '2021-10-12 12:20:26', 1, 'Kg'),
(9, 6, NULL, 2, 1, 56, 12000, '2021-10-12 12:20:26', 1, 'Bag'),
(10, 6, NULL, 1, 2, 12, 2000, '2021-10-12 12:20:26', 1, 'Piece'),
(11, 6, NULL, 5, 2, 12, 2000, '2021-10-12 12:20:26', 1, 'Piece'),
(12, 6, NULL, 5, 2, 12, 2000, '2021-10-12 12:20:26', 1, 'Piece'),
(13, 5, NULL, 5, NULL, 12, 2000, '2021-10-12 12:20:26', 1, 'Piece'),
(14, 5, NULL, 5, NULL, 12, 2000, '2021-10-12 12:20:26', 1, 'Piece');

-- --------------------------------------------------------

--
-- Table structure for table `rfp`
--

CREATE TABLE `rfp` (
  `id` int(11) NOT NULL,
  `purpose_statement` text DEFAULT NULL,
  `open_date` date NOT NULL,
  `close_date` date NOT NULL,
  `expected_delivery_date` date DEFAULT NULL,
  `expected_response` text DEFAULT NULL,
  `expected_attachments` text DEFAULT NULL,
  `payment_terms` text DEFAULT NULL,
  `requisition_id` int(11) DEFAULT NULL,
  `rfp_status` varchar(45) DEFAULT 'Open',
  `status` int(11) NOT NULL DEFAULT 1,
  `time_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rfp`
--

INSERT INTO `rfp` (`id`, `purpose_statement`, `open_date`, `close_date`, `expected_delivery_date`, `expected_response`, `expected_attachments`, `payment_terms`, `requisition_id`, `rfp_status`, `status`, `time_created`, `user_id`) VALUES
(2, 'Datatatatatatata', '2021-10-06', '2021-10-15', '2022-07-06', 'How long have you been dealing, Any other info to know, What is the project budget?,What are the end goals of the project?,What factors are crucial deal breakers?,How will you protect our organization from risk?', 'company profile, price list, address', 'Terms', NULL, 'Complete', 1, '2021-10-14 07:55:28', 1);

-- --------------------------------------------------------

--
-- Table structure for table `rfp_item`
--

CREATE TABLE `rfp_item` (
  `id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `rfp_id` int(11) DEFAULT NULL,
  `quantity` double NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `time_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rfp_item`
--

INSERT INTO `rfp_item` (`id`, `item_id`, `rfp_id`, `quantity`, `description`, `status`, `time_created`) VALUES
(3, 1, 2, 66, 'fdfdfdfd', 1, '2021-10-14 07:55:28'),
(4, 2, 2, 10, 'hhdhdhdhd', 1, '2021-10-14 07:55:28');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL COMMENT '	',
  `fname` varchar(45) DEFAULT NULL,
  `lname` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `username` varchar(45) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `category` varchar(45) DEFAULT NULL,
  `gender` varchar(45) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `time_created` timestamp NULL DEFAULT current_timestamp(),
  `status` int(11) DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `nin` varchar(45) DEFAULT NULL,
  `designation` varchar(45) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `is_verified` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `fname`, `lname`, `email`, `phone`, `username`, `password`, `category`, `gender`, `dob`, `time_created`, `status`, `last_login`, `nin`, `designation`, `address`, `department_id`, `is_verified`) VALUES
(1, 'Admin', 'Admin', 'admin@gmail.com', '087585855', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'Admin', 'Male', '1993-07-15', '2021-10-10 08:07:39', 1, '2021-10-18 14:11:33', '666666666', 'Audit senior ', NULL, 1, 1),
(2, 'test', 'test', 'test@gmail.com', '+3177887552', 'test', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Supplier', 'Male', NULL, '2021-10-10 11:21:58', 1, NULL, 'CM94055103PVH', 'Mr', 'Mbarara\r\nMbarara', 1, 1),
(3, 'Supplier', 'Name', 'supplier@gmail.com', '+3177887552', 'supplier@gmail.com', 'e2979e759574b094b7c50f54846af43ef8eff1a0', 'Supplier', 'Female', '2020-08-06', '2021-10-15 14:51:38', 1, '2021-10-18 13:32:37', '8635353536363', 'Mr', 'Mbarara\r\nMbarara', NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attachment`
--
ALTER TABLE `attachment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_attachment_purchase_order1_idx` (`purchase_order_id`),
  ADD KEY `fk_attachment_requisition1_idx` (`requisition_id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contract_application`
--
ALTER TABLE `contract_application`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notificationtemplate`
--
ALTER TABLE `notificationtemplate`
  ADD PRIMARY KEY (`template_id`);

--
-- Indexes for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_purchase_order_requisition1_idx` (`requisition_id`),
  ADD KEY `fk_purchase_order_user1_idx` (`supplier`);

--
-- Indexes for table `purchase_order_item`
--
ALTER TABLE `purchase_order_item`
  ADD PRIMARY KEY (`id`,`requisition_item_id`,`purchase_order_id`),
  ADD KEY `fk_purchase_order_has_requisition_item_requisition_item1_idx` (`requisition_item_id`),
  ADD KEY `fk_purchase_order_has_requisition_item_purchase_order1_idx` (`purchase_order_id`);

--
-- Indexes for table `requisition`
--
ALTER TABLE `requisition`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_requisition_department_idx` (`department_id`),
  ADD KEY `fk_requisition_user1_idx` (`user_id`);

--
-- Indexes for table `requisition_item`
--
ALTER TABLE `requisition_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_requisition_has_item_item1_idx` (`item_id`),
  ADD KEY `fk_requisition_has_item_requisition1_idx` (`requisition_id`),
  ADD KEY `rfp_id` (`rfp_id`);

--
-- Indexes for table `rfp`
--
ALTER TABLE `rfp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `rfp_item`
--
ALTER TABLE `rfp_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `rfp_id` (`rfp_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attachment`
--
ALTER TABLE `attachment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `contract_application`
--
ALTER TABLE `contract_application`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `notificationtemplate`
--
ALTER TABLE `notificationtemplate`
  MODIFY `template_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchase_order`
--
ALTER TABLE `purchase_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchase_order_item`
--
ALTER TABLE `purchase_order_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requisition`
--
ALTER TABLE `requisition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `requisition_item`
--
ALTER TABLE `requisition_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `rfp`
--
ALTER TABLE `rfp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rfp_item`
--
ALTER TABLE `rfp_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '	', AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attachment`
--
ALTER TABLE `attachment`
  ADD CONSTRAINT `fk_attachment_purchase_order1` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_attachment_requisition1` FOREIGN KEY (`requisition_id`) REFERENCES `requisition` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD CONSTRAINT `fk_purchase_order_requisition1` FOREIGN KEY (`requisition_id`) REFERENCES `requisition` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_purchase_order_user1` FOREIGN KEY (`supplier`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_order_item`
--
ALTER TABLE `purchase_order_item`
  ADD CONSTRAINT `fk_purchase_order_has_requisition_item_purchase_order1` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_purchase_order_has_requisition_item_requisition_item1` FOREIGN KEY (`requisition_item_id`) REFERENCES `requisition_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `requisition`
--
ALTER TABLE `requisition`
  ADD CONSTRAINT `fk_requisition_department` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_requisition_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `requisition_item`
--
ALTER TABLE `requisition_item`
  ADD CONSTRAINT `fk_requisition_has_item_item1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_requisition_has_item_requisition1` FOREIGN KEY (`requisition_id`) REFERENCES `requisition` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `rfp_item`
--
ALTER TABLE `rfp_item`
  ADD CONSTRAINT `rfp_item_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rfp_item_ibfk_2` FOREIGN KEY (`rfp_id`) REFERENCES `rfp` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
