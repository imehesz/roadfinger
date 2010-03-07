-- SQLite
CREATE TABLE tweets (city varchar(100), created NUMERIC, id INTEGER PRIMARY KEY, location varchar(240), profile_image varchar(240), raw_date varchar(50), raw_tweet TEXT, state varchar(25), user varchar(100));

-- MySQL
CREATE TABLE IF NOT EXISTS `roadfinger_tweets` (
`city` varchar(100) DEFAULT NULL,
`created` decimal(10,0) DEFAULT NULL,
`id` int(11) NOT NULL AUTO_INCREMENT,
`location` varchar(240) DEFAULT NULL,
`profile_image` varchar(240) DEFAULT NULL,
`raw_date` varchar(50) DEFAULT NULL,
`raw_tweet` text,
`state` varchar(25) DEFAULT NULL,
`user` varchar(100) DEFAULT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `user` (`user`,`raw_date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8
