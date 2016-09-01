<?php

$query = array();

# 用户资料及第三方信息

$query[] = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."user_profile` (
	`user_id` int(11) unsigned NOT NULL DEFAULT '0',
	`user_nickname` varchar(12) NOT NULL DEFAULT '',
	`user_email` varchar(30) NOT NULL DEFAULT '',
	`user_avatar` varchar(225) NOT NULL DEFAULT '',
	`user_sign` varchar(100) NOT NULL DEFAULT '',
	`user_sex` varchar(2) NOT NULL DEFAULT '',
	`user_city` varchar(10) NOT NULL DEFAULT '火星',
	`qq_token` varchar(100) NOT NULL DEFAULT '',
	KEY `user_id` (`user_id`),
	KEY `qq_token` (`qq_token`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

# 用户关注
$query[]="CREATE TABLE IF NOT EXISTS `".DB_PREFIX."user_follow` (
	`follow_time` int(11) unsigned NOT NULL DEFAULT '0',
	`follow_id` int(11) unsigned NOT NULL DEFAULT '0',
	`user_id` int(11) unsigned NOT NULL DEFAULT '0',
	KEY `follow_id` (`follow_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

return $query;