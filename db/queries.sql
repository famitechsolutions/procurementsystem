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