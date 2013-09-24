CREATE TABLE IF NOT EXISTS `#__guilds_ranks` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `#__guilds_ranks` (`status`) VALUES
('Error'), 
('Community'),
('Visitor'),
('MIA'),
('Recruit'),
('Cadet'),
('Member');

CREATE TABLE IF NOT EXISTS `#__guilds_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
);

INSERT INTO `#__guilds_types` (`id`, `name`, `ordering`, `published`) VALUES
('game', 1, 1),
('allegiance', 2, 1),
('class', 3, 1),
('guild', 4, 1);


CREATE TABLE IF NOT EXISTS `#__guilds_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `type` int(6) NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `#__guilds_categories` (`name`, `parent`, `type`, `ordering`, `published`) VALUES
('Discharged', 0, 1, 1, 1);

CREATE TABLE IF NOT EXISTS `#__guilds_members` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `user_id` int(6) NOT NULL,
  `username` varchar(150) NOT NULL,
  `appdate` date DEFAULT NULL,
  `status` int(6) DEFAULT NULL,
  `tbd` varchar(5) DEFAULT NULL,
  `notes` longtext ,
  `edit_id` int(6) DEFAULT NULL,
  `edit_time` datetime DEFAULT NULL,
  `sto_handle` varchar(255) DEFAULT NULL,
  `gw2_handle` varchar(255) DEFAULT NULL,
  `tor_handle` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`)
);

INSERT INTO `#__guilds_members` (`user_id`,`username`)
SELECT `id`,`username` FROM `#__users` ORDER BY `id` asc;

CREATE TABLE IF NOT EXISTS `#__guilds_characters` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `user_id` int(6) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `handle` varchar(255) DEFAULT NULL,
  `checked` date DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `unpublisheddate` date DEFAULT NULL,
  `invite` tinyint(1) NOT NULL DEFAULT '0',
  `game` int(6) DEFAULT NULL,
  `allegiance` int(6) DEFAULT NULL,
  `class` int(6) DEFAULT NULL,
  `guild` int(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
);