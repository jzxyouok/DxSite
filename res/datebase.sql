-- ----------------------------------------------------------
-- PHP Simple Library XCore 1.x-dev Database Backup File
-- Create On 2016-10-19 20:37:05
-- Host: localhost   Database: hello_world
-- Server version	10.1.10-MariaDB
-- ------------------------------------------------------
/*!40101 SET NAMES utf8 */;

--
-- Create Table atd_article_tag
--

DROP TABLE IF EXISTS `atd_article_tag`;
CREATE TABLE `atd_article_tag` (
  `tid` bigint(20) NOT NULL,
  `aid` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Create Table atd_articles
--

DROP TABLE IF EXISTS `atd_articles`;
CREATE TABLE `atd_articles` (
  `aid` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `topic` bigint(20) NOT NULL DEFAULT '0' COMMENT '话题',
  `category` bigint(20) NOT NULL,
  `title` tinytext NOT NULL,
  `remark` tinytext NOT NULL COMMENT '摘要',
  `contents` text NOT NULL,
  `author` bigint(20) NOT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `keep_top` tinyint(1) NOT NULL DEFAULT '0' COMMENT '置顶',
  `replys` int(11) NOT NULL DEFAULT '0' COMMENT '回复数',
  `public` tinyint(1) NOT NULL DEFAULT '1',
  `allow_reply` tinyint(1) NOT NULL DEFAULT '1',
  `verify` tinyint(1) NOT NULL DEFAULT '0' COMMENT '验证',
  `hash` varchar(32) NOT NULL,
  PRIMARY KEY (`aid`),
  UNIQUE KEY `filemd5` (`hash`),
  KEY `topic` (`topic`),
  KEY `keep_top` (`keep_top`),
  KEY `public` (`public`),
  KEY `allow_replay` (`allow_reply`),
  KEY `verify` (`verify`),
  KEY `modified` (`modified`),
  KEY `modified_2` (`modified`),
  KEY `category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;


--
-- Create Table atd_bugs
--

DROP TABLE IF EXISTS `atd_bugs`;
CREATE TABLE `atd_bugs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(80) NOT NULL,
  `discription` varchar(255) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


--
-- Create Table atd_category
--

DROP TABLE IF EXISTS `atd_category`;
CREATE TABLE `atd_category` (
  `cid` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '分类',
  `icon` bigint(20) NOT NULL COMMENT '分类图标',
  `name` varchar(80) NOT NULL DEFAULT '无分类',
  `discription` tinytext NOT NULL,
  `counts` int(11) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`),
  KEY `cname` (`name`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


--
-- Create Table atd_groups
--

DROP TABLE IF EXISTS `atd_groups`;
CREATE TABLE `atd_groups` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
  `sort` int(11) NOT NULL COMMENT '分组排序',
  `gname` varchar(80) NOT NULL,
  `E_Site` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '编辑站点',
  `E_group` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '编辑分组',
  `E_user` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '编辑用户',
  `U_su` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '可以使用别人的名义',
  `E_category` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '编辑分类',
  PRIMARY KEY (`gid`),
  KEY `gname` (`gname`),
  KEY `priority` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='权限表';


--
-- Create Table atd_nav
--

DROP TABLE IF EXISTS `atd_nav`;
CREATE TABLE `atd_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(24) NOT NULL,
  `url` tinytext NOT NULL,
  `title` varchar(255) NOT NULL,
  `show` tinyint(1) NOT NULL DEFAULT '1',
  `sort` int(11) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;




INSERT INTO `atd_nav` VALUES ('1','index','/','','1','1','0'),('2','notes','/notes','','1','6','0'),('3','article','/article','','1','2','0'),('4','books','/books','','1','3','0'),('5','question','/question','','1','4','0'),('7','test','/test','OnlineJudge','1','5','0'),('9','about','/about','','1','7','0');


--
-- Create Table atd_signin_historys
--

DROP TABLE IF EXISTS `atd_signin_historys`;
CREATE TABLE `atd_signin_historys` (
  `hid` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL,
  `ip` varchar(64) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`hid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;


--
-- Create Table atd_site_options
--

DROP TABLE IF EXISTS `atd_site_options`;
CREATE TABLE `atd_site_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_2` (`name`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COMMENT='网站设置表';




INSERT INTO `atd_site_options` VALUES ('1','site_name','芒刺中国 -- 导入'),('2','theme','default'),('19','site_logo','/static/img/mccn.svg'),('20','keywords','芒刺,程序员,文摘'),('21','lang','zh_cn'),('22','HV_SignUp','0'),('23','HV_SignIn','0'),('24','HV_Post','0'),('25','HV_Comment','0'),('26','allowSignUp','1'),('27','copyright','芒刺中国'),('28','site_close','0'),('29','close_info','芒刺中国系统开发中'),('30','default_avatar','39');


--
-- Create Table atd_tags
--

DROP TABLE IF EXISTS `atd_tags`;
CREATE TABLE `atd_tags` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `topic` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`tid`),
  UNIQUE KEY `name` (`name`),
  KEY `topic` (`topic`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;


--
-- Create Table atd_upload_resource
--

DROP TABLE IF EXISTS `atd_upload_resource`;
CREATE TABLE `atd_upload_resource` (
  `rid` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` varchar(12) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `reference` int(11) NOT NULL,
  PRIMARY KEY (`rid`),
  UNIQUE KEY `hash` (`hash`),
  KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=1435 DEFAULT CHARSET=utf8;


--
-- Create Table atd_uploads
--

DROP TABLE IF EXISTS `atd_uploads`;
CREATE TABLE `atd_uploads` (
  `rid` bigint(20) NOT NULL AUTO_INCREMENT,
  `owner` bigint(20) NOT NULL,
  `name` varchar(80) NOT NULL,
  `extension` varchar(16) NOT NULL,
  `time` int(11) NOT NULL,
  `resource` bigint(20) NOT NULL,
  `public` int(1) NOT NULL COMMENT '是否公开',
  PRIMARY KEY (`rid`),
  KEY `owner` (`owner`),
  KEY `public` (`public`),
  KEY `resource` (`resource`),
  KEY `extension` (`extension`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8 COMMENT='上传资源表';


--
-- Create Table atd_user_info
--

DROP TABLE IF EXISTS `atd_user_info`;
CREATE TABLE `atd_user_info` (
  `uid` bigint(20) NOT NULL,
  `avatar` bigint(20) NOT NULL COMMENT '头像文件ID',
  `qq` varchar(20) DEFAULT NULL,
  `discription` tinytext NOT NULL,
  `phone` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `avatar` (`avatar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户信息';


--
-- Create Table atd_users
--

DROP TABLE IF EXISTS `atd_users`;
CREATE TABLE `atd_users` (
  `uid` bigint(20) NOT NULL AUTO_INCREMENT,
  `uname` varchar(12) NOT NULL,
  `upass` varchar(60) NOT NULL,
  `gid` int(11) NOT NULL DEFAULT '3',
  `signup` int(11) NOT NULL,
  `signin` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `email_verify` enum('Y','N') NOT NULL DEFAULT 'N',
  `lastip` varchar(32) NOT NULL,
  `token` varchar(32) NOT NULL COMMENT '登陆验证值',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `uid` (`uid`),
  UNIQUE KEY `uname` (`uname`),
  KEY `uid_2` (`uid`),
  KEY `uid_3` (`uid`),
  KEY `uid_4` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

