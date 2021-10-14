-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 09, 2021 at 01:26 PM
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
  `requisition_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `time_created` timestamp NULL DEFAULT current_timestamp(),
  `requisition_id` int(11) NOT NULL,
  `supplier` int(11) NOT NULL,
  `approval_status` varchar(45) DEFAULT 'Pending',
  `approval_time` datetime DEFAULT NULL,
  `approval_comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `approval_status` varchar(45) DEFAULT 'Pending',
  `approval_by` varchar(45) DEFAULT NULL,
  `time_created` timestamp NULL DEFAULT current_timestamp(),
  `approval_comment` text DEFAULT NULL,
  `approval_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `requisition_item`
--

CREATE TABLE `requisition_item` (
  `id` int(11) NOT NULL,
  `requisition_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` float DEFAULT NULL,
  `unit_price` float DEFAULT NULL,
  `time_created` timestamp NULL DEFAULT current_timestamp(),
  `status` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `last_logn` datetime DEFAULT NULL,
  `nin` varchar(45) DEFAULT NULL,
  `designation` varchar(45) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  ADD KEY `fk_requisition_has_item_requisition1_idx` (`requisition_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order`
--
ALTER TABLE `purchase_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order_item`
--
ALTER TABLE `purchase_order_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requisition`
--
ALTER TABLE `requisition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requisition_item`
--
ALTER TABLE `requisition_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '	';

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
