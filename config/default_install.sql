-- 基础表
SET sql_mode='NO_UNSIGNED_SUBTRACTION';

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `nickname` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `avatar` varchar(255) NOT NULL DEFAULT '',
  `created` datetime DEFAULT NULL,
  `logged` datetime DEFAULT NULL,
  `timeout` datetime DEFAULT NULL,
  `token` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

-- 用户详细
DROP TABLE IF EXISTS `usermeta`;
CREATE TABLE IF NOT EXISTS `usermeta` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `key` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`,`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `options`;
CREATE TABLE IF NOT EXISTS `options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `alias` varchar(200) NOT NULL DEFAULT '',
  `value` text NOT NULL DEFAULT '',
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
  `key` varchar(255) NOT NULL DEFAULT '',
  `value` varchar(255) NOT NULL DEFAULT '',
  KEY `id` (`key`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- INSERT INTO `optionextra` (`id`,`key`,`value`) VALUES
-- (6,'不允许','0')
-- ,(6,'允许','1');


-- 应用表
DROP TABLE IF EXISTS `follows`;
CREATE TABLE IF NOT EXISTS `follows` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL COMMENT '关注者ID',
  `fid` int(10) unsigned NOT NULL COMMENT '被关注者ID',
  `remark` varchar(50) NOT NULL DEFAULT '' COMMENT '备注',
  `ctime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid-fid` (`uid`,`fid`) USING BTREE,
  UNIQUE KEY `fid-uid` (`fid`,`uid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `post_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `post_title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `post_content` text NOT NULL COLLATE utf8mb4_unicode_ci COMMENT '内容',
  `post_time` datetime DEFAULT NULL,
  `post_update` datetime DEFAULT NULL,
  `post_lock` tinyint(1) DEFAULT '0' COMMENT '内容锁（不允许回复）0-否 1-是',
  `post_type` tinyint(1) DEFAULT '1' COMMENT '内容类型 1 动态 2 待办 3 笔记 4 音乐',
  `post_privacy` tinyint(1) DEFAULT '0' COMMENT '内容隐私 0 全部可见 1 粉丝可见 2 仅自己可见 3 需要密码',
  `post_pass` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `post_url` varchar(12) NOT NULL DEFAULT '' COMMENT '内容URL',
  `digg_count` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `read_count` int(11) NOT NULL DEFAULT '0' COMMENT '浏览数',
  `comment_count` int(11) NOT NULL DEFAULT '0' COMMENT '评论数',
  PRIMARY KEY (`post_id`),
  KEY `post_time` (`post_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `post_digg`;
CREATE TABLE IF NOT EXISTS `post_digg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `post_id` int(10) unsigned NOT NULL COMMENT '内容ID',
  `digg_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `post_data`;
CREATE TABLE IF NOT EXISTS `post_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `data` varchar(255) NOT NULL DEFAULT '' COMMENT '数据',
  `data_type` tinyint(1) DEFAULT '0' COMMENT '数据类别 0 原图 1 缩图 2 音乐 3 视频',
  `post_id` int(10) unsigned NOT NULL COMMENT '内容ID',
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `post_topic`;
CREATE TABLE IF NOT EXISTS `post_topic` (
  `topic_id` int(10) unsigned NOT NULL COMMENT '话题ID',
  `post_id` int(10) unsigned NOT NULL COMMENT '内容ID',
  PRIMARY KEY (`post_id`, `topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS `topics`;
CREATE TABLE IF NOT EXISTS `topics` (
  `topic_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topic_name` varchar(150) NOT NULL DEFAULT '' COMMENT '话题名称',
  `topic_desc` varchar(255) NOT NULL DEFAULT '' COMMENT '话题描述',
  `topic_count` int(11) NOT NULL DEFAULT '0' COMMENT '关联的动态数',
  `topic_cover` varchar(255) NOT NULL DEFAULT '' COMMENT '话题封面',
  PRIMARY KEY (`topic_id`),
  KEY `topic_name` (`topic_name`),
  KEY `topic_count` (`topic_count`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '评论ID',
  `comment_content` text NOT NULL COLLATE utf8mb4_unicode_ci COMMENT '评论内容',
  `comment_time` datetime DEFAULT NULL COMMENT '评论时间',
  `post_id` int(10) unsigned NOT NULL COMMENT '动态ID',
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `digg_count` int(11) NOT NULL DEFAULT '0' COMMENT '赞同数',
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `replies`;
CREATE TABLE IF NOT EXISTS `replies` (
  `reply_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '回复ID',
  `reply_content` text COLLATE utf8mb4_unicode_ci COMMENT '回复内容',
  `reply_time` datetime DEFAULT NULL,
  `uid` int(10) unsigned NOT NULL COMMENT '回复者ID',
  `comment_id` int(10) unsigned NOT NULL COMMENT '被回复的评论ID',
  `to_reply_id` int(10) unsigned NOT NULL COMMENT '被回复的回复ID',
  `to_reply_uid` int(10) unsigned NOT NULL COMMENT '被回复的用户ID',
  `digg_count` int(11) NOT NULL DEFAULT '0' COMMENT '赞同数',
  PRIMARY KEY (`reply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `comment_digg`;
CREATE TABLE IF NOT EXISTS `comment_digg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` int(10) unsigned NOT NULL DEFAULT '0',
  `reply_id` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `digg_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `atuid` int(10) unsigned NOT NULL DEFAULT '0',
  `no_type` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '通知类型 0 公告 1 内容评论 2 内容回复 3 内容子回复 4 私信 5 At到我的内容',
  `type_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '类型关联ID',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '类型关联父ID',
  `isread` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL DEFAULT '' COLLATE utf8mb4_unicode_ci COMMENT '内容',
  `ctime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `no_type` (`no_type`),
  KEY `isread` (`isread`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1;