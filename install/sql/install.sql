CREATE DATABASE session;
CREATE DATABASE up;

DROP TABLE IF EXISTS `session`.`data`;
CREATE TABLE  `session`.`data` (
  `session_id` varchar(35) NOT NULL,
  `data` longtext NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  `modified_at` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `up`.`category`;
CREATE TABLE  `up`.`category` (
  `ik` int(11) NOT NULL AUTO_INCREMENT,
  `parent_ik` int(11) DEFAULT NULL,
  `title` varchar(27) DEFAULT NULL,
  `slug` varchar(44) NOT NULL,
  PRIMARY KEY (`ik`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `up`.`credit_card`;
CREATE TABLE  `up`.`credit_card` (
  `ik` int(11) NOT NULL AUTO_INCREMENT,
  `post_ik` int(11) DEFAULT NULL,
  `user_ik` int(11) DEFAULT NULL,
  `success` tinyint(1) DEFAULT NULL,
  `stripe_id` varchar(75) DEFAULT NULL,
  `card_token` varchar(75) DEFAULT NULL,
  `processed_at` datetime DEFAULT NULL,
  `cvc_result` varchar(10) DEFAULT NULL,
  `zip_result` varchar(10) DEFAULT NULL,
  `address_result` varchar(10) DEFAULT NULL,
  `amount` decimal(7,2) DEFAULT NULL,
  `response` mediumtext,
  PRIMARY KEY (`ik`),
  KEY `post` (`post_ik`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `up`.`likes`;
CREATE TABLE  `up`.`likes` (
  `post_ik` bigint(20) DEFAULT NULL,
  `user_ik` bigint(20) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `user_ik` (`user_ik`,`post_ik`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `up`.`market`;
CREATE TABLE  `up`.`market` (
  `post_ik` bigint(20) NOT NULL,
  `shop_ik` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `shipping_price` decimal(10,2) DEFAULT NULL,
  `ships_to` enum('World','United States') DEFAULT 'United States',
  `status` enum('expired','available','sold','created','deleted') DEFAULT NULL,
  `sold_to_user_ik` bigint(20) DEFAULT NULL,
  `listed_at` datetime DEFAULT NULL,
  `sold_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`post_ik`),
  KEY `shop` (`shop_ik`) USING BTREE,
  KEY `deleted` (`deleted`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `up`.`marketing`;
CREATE TABLE  `up`.`marketing` (
  `ik` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(26) DEFAULT NULL,
  `lastName` varchar(26) DEFAULT NULL,
  `email` varchar(65) DEFAULT NULL,
  `organization` varchar(40) DEFAULT NULL,
  `ip` varchar(64) DEFAULT NULL,
  `forwarded_ip` varchar(64) DEFAULT NULL,
  `subscribed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ik`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `up`.`message`;
CREATE TABLE  `up`.`message` (
  `ik` bigint(20) NOT NULL AUTO_INCREMENT,
  `sent` datetime DEFAULT NULL,
  `read` datetime DEFAULT NULL,
  `from_user_ik` bigint(20) DEFAULT NULL,
  `to_user_ik` bigint(20) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` mediumtext,
  PRIMARY KEY (`ik`),
  KEY `from_user` (`from_user_ik`) USING BTREE,
  KEY `to_user` (`to_user_ik`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `up`.`payment`;
CREATE TABLE  `up`.`payment` (
  `ik` int(11) NOT NULL AUTO_INCREMENT,
  `post_ik` int(11) DEFAULT NULL,
  `shop_ik` int(11) DEFAULT NULL,
  `from_user_ik` int(11) DEFAULT NULL,
  `to_user_ik` int(11) DEFAULT NULL,
  `stripe_id` varchar(75) DEFAULT NULL,
  `amount` decimal(7,2) DEFAULT NULL,
  `authorized_amount` decimal(7,2) DEFAULT NULL,
  `applied_at` datetime DEFAULT NULL,
  PRIMARY KEY (`ik`),
  KEY `from_user_ik` (`from_user_ik`) USING BTREE,
  KEY `to_user_ik` (`to_user_ik`) USING BTREE,
  KEY `post` (`post_ik`) USING BTREE,
  KEY `shop` (`shop_ik`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `up`.`post`;
CREATE TABLE  `up`.`post` (
  `ik` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(9) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_ik` int(11) DEFAULT NULL,
  `type` enum('idea','market') DEFAULT NULL,
  `status` enum('created','uploaded','submitted','posted','deleted') DEFAULT NULL,
  `category_ik` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` longtext,
  `indexed` tinyint(4) DEFAULT '0',
  `visible` tinyint(4) DEFAULT '0',
  `tags` longtext,
  `views` bigint(20) DEFAULT '0',
  `likes` int(11) DEFAULT '0',
  `comments` int(11) DEFAULT '0',
  `shares` int(11) DEFAULT '0',
  `reports` int(11) DEFAULT '0',
  PRIMARY KEY (`ik`),
  KEY `id` (`id`),
  KEY `type` (`type`),
  KEY `indexed` (`indexed`,`status`) USING BTREE,
  KEY `user` (`user_ik`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `up`.`sale`;
CREATE TABLE  `up`.`sale` (
  `ik` int(11) NOT NULL AUTO_INCREMENT,
  `post_ik` int(11) DEFAULT NULL,
  `sold_by_shop_ik` int(11) DEFAULT NULL,
  `sold_to_user_ik` int(11) DEFAULT NULL,
  `amount` decimal(7,2) DEFAULT NULL,
  `ship_amount` decimal(7,2) DEFAULT NULL,
  `total_amount` decimal(7,2) DEFAULT NULL,
  `transaction_fee` decimal(7,2) DEFAULT NULL,
  `listing_fee` decimal(7,2) DEFAULT NULL,
  `ship_name` varchar(75) DEFAULT NULL,
  `ship_address` varchar(75) DEFAULT NULL,
  `ship_city` varchar(26) DEFAULT NULL,
  `ship_st` char(2) DEFAULT NULL,
  `ship_zip` char(5) DEFAULT NULL,
  `sold_at` datetime DEFAULT NULL,
  `shipped` tinyint(1) DEFAULT '0',
  `shipped_at` datetime DEFAULT NULL,
  PRIMARY KEY (`ik`),
  KEY `sold_to` (`sold_to_user_ik`) USING BTREE,
  KEY `sold_by` (`sold_by_shop_ik`) USING BTREE,
  KEY `post` (`post_ik`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `up`.`shares`;
CREATE TABLE  `up`.`shares` (
  `post_ik` bigint(20) DEFAULT NULL,
  `user_ik` bigint(20) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sent_to` varchar(255) DEFAULT NULL,
  `message` longtext,
  KEY `post_ik` (`post_ik`,`user_ik`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `up`.`shipping`;
CREATE TABLE  `up`.`shipping` (
  `user_ik` bigint(20) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(26) DEFAULT NULL,
  `st` char(2) DEFAULT NULL,
  `zip` char(5) DEFAULT NULL,
  PRIMARY KEY (`user_ik`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `up`.`shop`;
CREATE TABLE  `up`.`shop` (
  `ik` int(11) NOT NULL AUTO_INCREMENT,
  `user_ik` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `url` varchar(50) DEFAULT NULL,
  `customer_token` varchar(75) DEFAULT NULL,
  `card_token` varchar(75) DEFAULT NULL,
  `bank_token` varchar(75) DEFAULT NULL,
  `address` varchar(75) DEFAULT NULL,
  `city` varchar(26) DEFAULT NULL,
  `st` char(2) DEFAULT NULL,
  `zip` char(5) DEFAULT NULL,
  `country` varchar(30) DEFAULT NULL,
  `ships_to` enum('World','United States') DEFAULT 'United States',
  `preferred_language` varchar(20) DEFAULT 'English',
  `preferred_currency` varchar(20) DEFAULT 'US Dollar',
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `phone_number` varchar(10) DEFAULT NULL,
  `last4` char(4) DEFAULT NULL,
  `balance` decimal(7,2) DEFAULT NULL,
  `total_revenue` decimal(9,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `terms_agreed_at` datetime DEFAULT NULL,
  `terms_agreed_to` longtext,
  `last_transferred_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `activated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`ik`),
  KEY `user` (`user_ik`) USING BTREE,
  KEY `url` (`url`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `up`.`survey`;
CREATE TABLE  `up`.`survey` (
  `shop_ik` int(11) NOT NULL,
  `answer` varchar(70) DEFAULT NULL,
  PRIMARY KEY (`shop_ik`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `up`.`user`;
CREATE TABLE  `up`.`user` (
  `ik` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(68) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varbinary(255) NOT NULL,
  `url` varchar(25) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `etsy` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `name` varchar(51) NOT NULL,
  `role` enum('Admins','Moderators','Users') DEFAULT 'Users',
  `type` enum('seller','member') DEFAULT 'member',
  `gender` enum('Male','Female','Unspecified') DEFAULT 'Unspecified',
  `location` varchar(35) DEFAULT NULL,
  `about` longtext,
  `registered` datetime DEFAULT NULL,
  `login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `token` varchar(64) DEFAULT NULL,
  `token_requested` datetime DEFAULT NULL,
  `feature_enabled` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`ik`),
  UNIQUE KEY `email_2` (`email`),
  KEY `email` (`email`),
  KEY `token` (`token`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;