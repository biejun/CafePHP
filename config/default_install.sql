-- 用户

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '',
  `password` varchar(60) NOT NULL DEFAULT '',
  `created` datetime DEFAULT NULL,
  `logged` datetime DEFAULT NULL,
  `timeout` datetime DEFAULT NULL,
  `token` varchar(32) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;

-- 用户详细

DROP TABLE IF EXISTS `usermeta`;
CREATE TABLE IF NOT EXISTS `usermeta` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `key` varchar(100) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `key` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `options`;
CREATE TABLE IF NOT EXISTS `options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `alias` varchar(200) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  `type` enum('input','textarea','select','switch') NOT NULL DEFAULT 'input',
  `description` varchar(200) NOT NULL DEFAULT '',
  `rules` varchar(100) NOT NULL DEFAULT 'text',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=13;

INSERT INTO `options` (`id`,`name`,`alias`,`value`,`type`,`description`,`rules`) VALUES
(1,'title','站点标题','站点标题','input','','text|required')
,(2,'subtitle','副标题','','input','','text')
,(3,'keywords','关键词','','input','多个关键词用英文逗号分隔','text')
,(4,'description','站点描述','','textarea','','maxLength=100|rows=3')
,(5,'notice','站点公告','','textarea','','maxLength=200|rows=4')
,(6,'users_can_register','用户注册','0','switch','','boolean')
,(7,'icp','ICP备案号','','input','','text')
,(8,'smtp_server','邮件服务器主机','','input','','text')
,(9,'smtp_port','邮件服务器端口','','input','','number')
,(10,'smtp_user','邮件服务器账号','','input','','text')
,(11,'smtp_password','邮件服务器密码','','input','','password')
,(12,'smtp_email','发件邮箱','','input','','email');

DROP TABLE IF EXISTS `optionextra`;
CREATE TABLE IF NOT EXISTS `optionextra` (
  `id` int(10) unsigned NOT NULL,
  `key` varchar(200) NOT NULL DEFAULT '',
  `value` varchar(200) NOT NULL DEFAULT '',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- INSERT INTO `optionextra` (`id`,`key`,`value`) VALUES
-- (6,'不允许','0')
-- ,(6,'允许','1');