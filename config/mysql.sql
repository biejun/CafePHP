
-- 用户

DROP TABLE IF EXISTS `$prefix$users`;
CREATE TABLE IF NOT EXISTS `$prefix$users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE $collate$ NOT NULL DEFAULT '',
  `password` varchar(60) COLLATE $collate$ NOT NULL DEFAULT '',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `logged` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `timeout` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `token` varchar(32) COLLATE $collate$ NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset$ COLLATE=$collate$ AUTO_INCREMENT=1;

-- 用户详细

DROP TABLE IF EXISTS `$prefix$usermeta`;
CREATE TABLE IF NOT EXISTS `$prefix$usermeta` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `key` nvarchar(255) COLLATE $collate$ NOT NULL DEFAULT '',
  `value` text COLLATE $collate$ NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `key` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset$ COLLATE=$collate$ AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `$prefix$settings`;
CREATE TABLE IF NOT EXISTS `$prefix$settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group` nvarchar(30) COLLATE $collate$ NOT NULL DEFAULT 'general',
  `name` nvarchar(255) COLLATE $collate$ NOT NULL DEFAULT '',
  `alias` nvarchar(255) COLLATE $collate$ NOT NULL DEFAULT '',
  `value` text COLLATE $collate$ NOT NULL,
  `type` enum('text','bigtext','number','date','email','password','checkbox','radio','select','switch') NOT NULL DEFAULT 'text',
  `description` nvarchar(255) COLLATE $collate$ NOT NULL DEFAULT '',
  `is_required` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset$ COLLATE=$collate$ AUTO_INCREMENT=13;

INSERT INTO `$prefix$settings` (`id`,`group`,`name`,`alias`,`value`,`type`,`description`,`is_required`) VALUES
(1,'general','title','站点标题','','text','','yes')
,(2,'general','subtitle','副标题','','text','','yes')
,(3,'general','keywords','关键词','','text','多个关键词用英文逗号分隔','yes')
,(4,'general','description','站点描述','','bigtext','','yes')
,(5,'general','notice','站点公告','','bigtext','','yes')
,(6,'general','users_can_register','用户注册','','radio','','yes')
,(7,'general','icp','ICP备案号','','text','','yes')
,(8,'smtp','smtp_server','邮件服务器主机','','text','','no')
,(9,'smtp','smtp_port','邮件服务器端口','','number','','no')
,(10,'smtp','smtp_user','邮件服务器账号','','text','','no')
,(11,'smtp','smtp_password','邮件服务器密码','','password','','no')
,(12,'smtp','smtp_email','发件邮箱','','email','','no');

DROP TABLE IF EXISTS `$prefix$settings_options`;
CREATE TABLE IF NOT EXISTS `$prefix$settings_options` (
  `id` int(10) unsigned NOT NULL,
  `key` nvarchar(255) COLLATE $collate$ NOT NULL DEFAULT '',
  `value` nvarchar(255) COLLATE $collate$ NOT NULL DEFAULT '',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset$ COLLATE=$collate$;

INSERT INTO `$prefix$settings_options` (`id`,`key`,`value`) VALUES
(6,'不允许','0')
,(6,'允许','1');