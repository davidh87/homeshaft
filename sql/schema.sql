DROP TABLE IF EXISTS `shafts`;
CREATE TABLE `shafts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `giver` varchar(30) NOT NULL,
  `receiver` varchar(30) NOT NULL,
  `amount` int(11) NOT NULL,
  `reason` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `giver` (`giver`),
  KEY `receiver` (`receiver`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
