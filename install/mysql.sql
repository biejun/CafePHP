-- --------------------------------------------------------

--
-- 表的结构 `any_account`
--
DROP TABLE IF EXISTS `any_users`;
CREATE TABLE IF NOT EXISTS `any_users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '',
  `password` varchar(60) NOT NULL DEFAULT '',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `logged` int(10) unsigned NOT NULL DEFAULT '0',
  `timeout` int(10) unsigned NOT NULL DEFAULT '0',
  `token` varchar(32) NOT NULL DEFAULT '',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=%charset% AUTO_INCREMENT=1;

-- --------------------------------------------------------

DROP TABLE IF EXISTS `any_user_info`;
CREATE TABLE IF NOT EXISTS `any_user_info` (
  `uid` int(10) unsigned NOT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT '',
  `sign` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `sex` enum('男','女') NOT NULL DEFAULT '男', 
  KEY `uid` (`uid`,`sex`)
) ENGINE=MyISAM DEFAULT CHARSET=%charset%;

-- --------------------------------------------------------

DROP TABLE IF EXISTS `any_plans`;
CREATE TABLE IF NOT EXISTS `any_plans` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `text` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `level` tinyint(3) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`pid`),
  KEY `uid` (`uid`,`level`)
) ENGINE=MyISAM DEFAULT CHARSET=%charset% AUTO_INCREMENT=1;

--
-- 表的结构 `any_config`
--
--
DROP TABLE IF EXISTS `any_configs`;
CREATE TABLE IF NOT EXISTS `any_configs` (
  `name` char(72) unique NOT NULL COMMENT '字段名称',
  `alias` varchar(50) NOT NULL COMMENT '字段别名',
  `value` text NOT NULL COMMENT '字段值',
  `type` enum('text','bigtext','number','date','bool') NOT NULL DEFAULT 'text' COMMENT '字段类型',
  `group` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '分组',
  INDEX (`name`,`group`)
) ENGINE=MyISAM DEFAULT CHARSET=%charset%;

-- --------------------------------------------------------

INSERT INTO `any_configs` (`name`, `alias`,`value`,`type`,`group`) VALUES
('title','站点标题','甜蜜时刻','text',1)
,('subtitle','副标题','优雅快速的构建网络应用','text',1)
,('description','站点关键字','grace','text',1)
,('keywords','站点描述','','bigtext',1)
,('icp','备案号','','text',1)
,('theme','主题外观','default','text',1);