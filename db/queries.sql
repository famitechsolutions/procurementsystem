ALTER TABLE `user` ADD `is_verified` INT NOT NULL DEFAULT '0' AFTER `department_id`;
ALTER TABLE `user` CHANGE `last_logn` `last_login` DATETIME NULL DEFAULT NULL;

CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `log_action` text DEFAULT NULL,
  `log_time` timestamp NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `requisition` CHANGE `approval_status` `requisition_status` VARCHAR(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'Pending';
ALTER TABLE `requisition` ADD `amount_requested` DOUBLE NOT NULL DEFAULT '0' AFTER `approval_time`;


CREATE TABLE `notificationtemplate` (
  `template_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `info` text DEFAULT NULL,
  `sms` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `attachment` ADD `status` INT NOT NULL DEFAULT '1' AFTER `requisition_id`;
ALTER TABLE `requisition_item` ADD `unit_measure` VARCHAR(45) NULL DEFAULT NULL AFTER `status`;

CREATE TABLE `rfp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `requisition_item` ADD `rfp_id` INT NULL DEFAULT NULL AFTER `requisition_id`, ADD INDEX (`rfp_id`);
ALTER TABLE `requisition_item` CHANGE `requisition_id` `requisition_id` INT(11) NULL DEFAULT NULL;

CREATE TABLE `rfp_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `rfp_id` int(11) DEFAULT NULL,
  `quantity` double NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `time_created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `rfp_id` (`rfp_id`),
  CONSTRAINT `rfp_item_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rfp_item_ibfk_2` FOREIGN KEY (`rfp_id`) REFERENCES `rfp` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

