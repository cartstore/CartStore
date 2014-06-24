CREATE TABLE `calendar_event` (
  `id` int(4) NOT NULL auto_increment,
  `event` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `day` tinyint(2) NOT NULL,
  `month` tinyint(2) NOT NULL,
  `year` int(4) NOT NULL,
  `time_from` varchar(10) NOT NULL,
  `time_until` varchar(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `calendar_users` (
  `user_id` int(8) NOT NULL auto_increment,
  `username` varchar(11) NOT NULL,
  `password` varchar(32) NOT NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `calendar_users` (`user_id`, `username`, `password`) VALUES (1, 'admin', '21232f297a57a5a743894a0e4a801fc3');