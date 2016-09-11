<?php

$query = array();

# 站内信

$query[] = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."message` (
	`message_id` int(11) unsigned NOT NULL DEFAULT '0',
	`user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '信息发送者ID', 
	`send_user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '信息接收者ID',
	`delete_user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '信息删除者ID',
	`message_content` varchar(225) NOT NULL DEFAULT '',
	`message_time` int(11) unsigned NOT NULL DEFAULT '0',
	`message_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0删除，1正常',
	PRIMARY KEY (`message_id`),
	KEY `user_id` (`send_user_id`,`user_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

return $query;