<?php
/* ------------------------------------------------------ *\
   ------------------------------------------------------
   PHP Simple Library XCore 1.0.1 Database Backup File
        Create On: 2016-11-13 13:53:48
        SQL Server version: 10.1.10-MariaDB
        Host: localhost   
        Database: hello_world
        Tables: 12
   ------------------------------------------------------
\* ------------------------------------------------------ */

try {
/** Open Transaction Avoid Error **/
Query::beginTransaction();
$effect=($create=new Query('CREATE DATABASE IF NOT EXISTS '.conf('Database.dbname').';'))->exec();
if ($create->erron()==0){
        echo 'Create Database '.conf('Database.dbname').' Ok,effect '.$effect.' rows'."\r\n";
    }
    else{
        die('Database '.conf('Database.dbname').'create filed!');   
    }
 (new Query('DROP TABLE IF EXISTS #{article_tag}'))->exec();

        $effect=($query_article_tag=new Query('CREATE TABLE `#{article_tag}` (
  `tid` bigint(20) NOT NULL,
  `aid` bigint(20) NOT NULL,
  KEY `tid` (`tid`),
  KEY `aid` (`aid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8'))->exec();
        if ($query_article_tag->erron()==0){
            echo 'Create Table:'.conf('Database.prefix').'article_tag Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.conf('Database.prefix').'article_tag Error!,effect '.$effect.' rows'."\r\n";   
        }
        ob_flush();
        flush(); (new Query('DROP TABLE IF EXISTS #{articles}'))->exec();

        $effect=($query_articles=new Query('CREATE TABLE `#{articles}` (
  `aid` bigint(20) NOT NULL AUTO_INCREMENT COMMENT \'ID\',
  `topic` bigint(20) NOT NULL DEFAULT \'0\' COMMENT \'话题\',
  `category` bigint(20) NOT NULL,
  `title` tinytext NOT NULL,
  `remark` tinytext NOT NULL COMMENT \'摘要\',
  `contents` text NOT NULL,
  `author` bigint(20) NOT NULL,
  `views` int(11) NOT NULL DEFAULT \'0\',
  `created` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `keep_top` tinyint(1) NOT NULL DEFAULT \'0\' COMMENT \'置顶\',
  `replys` int(11) NOT NULL DEFAULT \'0\' COMMENT \'回复数\',
  `public` tinyint(1) NOT NULL DEFAULT \'1\',
  `allow_reply` tinyint(1) NOT NULL DEFAULT \'1\',
  `verify` tinyint(1) NOT NULL DEFAULT \'0\' COMMENT \'验证\',
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
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8'))->exec();
        if ($query_articles->erron()==0){
            echo 'Create Table:'.conf('Database.prefix').'articles Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.conf('Database.prefix').'articles Error!,effect '.$effect.' rows'."\r\n";   
        }
        ob_flush();
        flush(); (new Query('DROP TABLE IF EXISTS #{category}'))->exec();

        $effect=($query_category=new Query('CREATE TABLE `#{category}` (
  `cid` bigint(20) NOT NULL AUTO_INCREMENT COMMENT \'分类\',
  `icon` bigint(20) NOT NULL COMMENT \'分类图标\',
  `topic` bigint(20) NOT NULL,
  `name` varchar(80) NOT NULL DEFAULT \'无分类\',
  `discription` tinytext NOT NULL,
  `count` int(11) NOT NULL DEFAULT \'0\',
  `parent` int(11) NOT NULL DEFAULT \'0\',
  PRIMARY KEY (`cid`),
  UNIQUE KEY `name` (`name`),
  KEY `cname` (`name`),
  KEY `parent` (`parent`),
  KEY `topic` (`topic`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8'))->exec();
        if ($query_category->erron()==0){
            echo 'Create Table:'.conf('Database.prefix').'category Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.conf('Database.prefix').'category Error!,effect '.$effect.' rows'."\r\n";   
        }
        ob_flush();
        flush(); (new Query('DROP TABLE IF EXISTS #{nav}'))->exec();

        $effect=($query_nav=new Query('CREATE TABLE `#{nav}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(24) NOT NULL,
  `url` tinytext NOT NULL,
  `title` varchar(255) NOT NULL,
  `show` tinyint(1) NOT NULL DEFAULT \'1\',
  `sort` int(11) NOT NULL DEFAULT \'0\',
  `parent` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8'))->exec();
        if ($query_nav->erron()==0){
            echo 'Create Table:'.conf('Database.prefix').'nav Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.conf('Database.prefix').'nav Error!,effect '.$effect.' rows'."\r\n";   
        }
        ob_flush();
        flush();        $effect=($query_nav_insert=new Query('INSERT INTO  `#{nav}` (`id`,`name`,`url`,`title`,`show`,`sort`,`parent`) VALUES (\'1\',\'首页\',\'/\',\'index\',\'1\',\'1\',\'0\'),(\'3\',\'文章\',\'/article\',\'article\',\'1\',\'2\',\'0\'),(\'9\',\'关于\',\'/about\',\'\',\'1\',\'7\',\'0\')'))->exec();
        if ($query_nav_insert->erron()==0){
            echo 'Insert Table:'.conf('Database.prefix').'nav Data Ok!,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Insert Table:'.conf('Database.prefix').'nav Data  Error!,effect '.$effect.' rows'."\r\n";   
        }
        ob_flush();
        flush(); (new Query('DROP TABLE IF EXISTS #{permission}'))->exec();

        $effect=($query_permission=new Query('CREATE TABLE `#{permission}` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL DEFAULT \'0\',
  `sort` int(11) NOT NULL COMMENT \'分组排序\',
  `gname` varchar(80) NOT NULL,
  `editSite` enum(\'Y\',\'N\') NOT NULL DEFAULT \'N\' COMMENT \'编辑站点\',
  `editGroup` enum(\'Y\',\'N\') NOT NULL DEFAULT \'N\' COMMENT \'编辑分组\',
  `editUser` enum(\'Y\',\'N\') NOT NULL DEFAULT \'N\' COMMENT \'编辑用户\',
  `useSu` enum(\'Y\',\'N\') NOT NULL DEFAULT \'N\' COMMENT \'可以使用别人的名义\',
  `editCategory` enum(\'Y\',\'N\') NOT NULL DEFAULT \'N\' COMMENT \'编辑分类\',
  PRIMARY KEY (`gid`),
  UNIQUE KEY `uid` (`uid`),
  KEY `gname` (`gname`),
  KEY `priority` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT=\'权限表\''))->exec();
        if ($query_permission->erron()==0){
            echo 'Create Table:'.conf('Database.prefix').'permission Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.conf('Database.prefix').'permission Error!,effect '.$effect.' rows'."\r\n";   
        }
        ob_flush();
        flush();        $effect=($query_permission_insert=new Query('INSERT INTO  `#{permission}` (`gid`,`uid`,`sort`,`gname`,`editSite`,`editGroup`,`editUser`,`useSu`,`editCategory`) VALUES (\'1\',\'0\',\'0\',\'网站所有者\',\'Y\',\'Y\',\'Y\',\'Y\',\'Y\')'))->exec();
        if ($query_permission_insert->erron()==0){
            echo 'Insert Table:'.conf('Database.prefix').'permission Data Ok!,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Insert Table:'.conf('Database.prefix').'permission Data  Error!,effect '.$effect.' rows'."\r\n";   
        }
        ob_flush();
        flush(); (new Query('DROP TABLE IF EXISTS #{signin_historys}'))->exec();

        $effect=($query_signin_historys=new Query('CREATE TABLE `#{signin_historys}` (
  `hid` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL,
  `ip` varchar(64) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`hid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8'))->exec();
        if ($query_signin_historys->erron()==0){
            echo 'Create Table:'.conf('Database.prefix').'signin_historys Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.conf('Database.prefix').'signin_historys Error!,effect '.$effect.' rows'."\r\n";   
        }
        ob_flush();
        flush(); (new Query('DROP TABLE IF EXISTS #{site_options}'))->exec();

        $effect=($query_site_options=new Query('CREATE TABLE `#{site_options}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_2` (`name`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT=\'网站设置表\''))->exec();
        if ($query_site_options->erron()==0){
            echo 'Create Table:'.conf('Database.prefix').'site_options Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.conf('Database.prefix').'site_options Error!,effect '.$effect.' rows'."\r\n";   
        }
        ob_flush();
        flush();        $effect=($query_site_options_insert=new Query('INSERT INTO  `#{site_options}` (`id`,`name`,`value`) VALUES (\'1\',\'site_name\',\'DxSite - 追求极简\'),(\'2\',\'theme\',\'default\'),(\'19\',\'site_logo\',\'/static/img/dxsite.svg\'),(\'20\',\'keywords\',\'DxSite,追求极简\'),(\'21\',\'lang\',\'zh_cn\'),(\'22\',\'HV_SignUp\',\'0\'),(\'23\',\'HV_SignIn\',\'0\'),(\'24\',\'HV_Post\',\'0\'),(\'25\',\'HV_Comment\',\'0\'),(\'26\',\'allowSignUp\',\'1\'),(\'27\',\'copyright\',\'ATD工作室\'),(\'28\',\'site_close\',\'0\'),(\'29\',\'close_info\',\'芒刺中国系统开发中\'),(\'31\',\'beian\',\'湘ICP备16001199号-1\')'))->exec();
        if ($query_site_options_insert->erron()==0){
            echo 'Insert Table:'.conf('Database.prefix').'site_options Data Ok!,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Insert Table:'.conf('Database.prefix').'site_options Data  Error!,effect '.$effect.' rows'."\r\n";   
        }
        ob_flush();
        flush(); (new Query('DROP TABLE IF EXISTS #{tags}'))->exec();

        $effect=($query_tags=new Query('CREATE TABLE `#{tags}` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `topic` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`tid`),
  UNIQUE KEY `name` (`name`),
  KEY `topic` (`topic`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8'))->exec();
        if ($query_tags->erron()==0){
            echo 'Create Table:'.conf('Database.prefix').'tags Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.conf('Database.prefix').'tags Error!,effect '.$effect.' rows'."\r\n";   
        }
        ob_flush();
        flush(); (new Query('DROP TABLE IF EXISTS #{upload_resource}'))->exec();

        $effect=($query_upload_resource=new Query('CREATE TABLE `#{upload_resource}` (
  `rid` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` varchar(12) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `reference` int(11) NOT NULL,
  PRIMARY KEY (`rid`),
  UNIQUE KEY `hash` (`hash`),
  KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8'))->exec();
        if ($query_upload_resource->erron()==0){
            echo 'Create Table:'.conf('Database.prefix').'upload_resource Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.conf('Database.prefix').'upload_resource Error!,effect '.$effect.' rows'."\r\n";   
        }
        ob_flush();
        flush(); (new Query('DROP TABLE IF EXISTS #{uploads}'))->exec();

        $effect=($query_uploads=new Query('CREATE TABLE `#{uploads}` (
  `rid` bigint(20) NOT NULL AUTO_INCREMENT,
  `owner` bigint(20) NOT NULL,
  `for` bigint(20) NOT NULL,
  `what` int(11) NOT NULL COMMENT \'为什么上传的\',
  `name` varchar(80) NOT NULL,
  `extension` varchar(16) NOT NULL,
  `time` int(11) NOT NULL,
  `resource` bigint(20) NOT NULL,
  `public` int(1) NOT NULL COMMENT \'是否公开\',
  PRIMARY KEY (`rid`),
  KEY `owner` (`owner`),
  KEY `public` (`public`),
  KEY `resource` (`resource`),
  KEY `extension` (`extension`),
  KEY `for` (`for`),
  KEY `what` (`what`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT=\'上传资源表\''))->exec();
        if ($query_uploads->erron()==0){
            echo 'Create Table:'.conf('Database.prefix').'uploads Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.conf('Database.prefix').'uploads Error!,effect '.$effect.' rows'."\r\n";   
        }
        ob_flush();
        flush(); (new Query('DROP TABLE IF EXISTS #{user_info}'))->exec();

        $effect=($query_user_info=new Query('CREATE TABLE `#{user_info}` (
  `uid` bigint(20) NOT NULL,
  `avatar` bigint(20) NOT NULL COMMENT \'头像文件ID\',
  `qq` varchar(20) DEFAULT NULL,
  `discription` tinytext NOT NULL,
  `phone` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `avatar` (`avatar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=\'用户信息\''))->exec();
        if ($query_user_info->erron()==0){
            echo 'Create Table:'.conf('Database.prefix').'user_info Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.conf('Database.prefix').'user_info Error!,effect '.$effect.' rows'."\r\n";   
        }
        ob_flush();
        flush(); (new Query('DROP TABLE IF EXISTS #{users}'))->exec();

        $effect=($query_users=new Query('CREATE TABLE `#{users}` (
  `uid` bigint(20) NOT NULL AUTO_INCREMENT,
  `uname` varchar(13) NOT NULL,
  `upass` varchar(60) NOT NULL,
  `gid` int(11) NOT NULL DEFAULT \'0\',
  `signup` int(11) NOT NULL,
  `signin` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `email_verify` enum(\'Y\',\'N\') NOT NULL DEFAULT \'N\',
  `lastip` varchar(32) NOT NULL,
  `token` varchar(32) NOT NULL COMMENT \'登陆验证值\',
  `verify` varchar(32) NOT NULL,
  `expriation` int(11) NOT NULL COMMENT \'验证过期时间\',
  `status` int(11) NOT NULL DEFAULT \'0\' COMMENT \'状态\',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `uid` (`uid`),
  UNIQUE KEY `uname` (`uname`),
  KEY `uid_2` (`uid`),
  KEY `uid_3` (`uid`),
  KEY `uid_4` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8'))->exec();
        if ($query_users->erron()==0){
            echo 'Create Table:'.conf('Database.prefix').'users Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.conf('Database.prefix').'users Error!,effect '.$effect.' rows'."\r\n";   
        }
        ob_flush();
        flush();/** End Querys **/
Query::commit();
return true;
} 
catch (Exception $e)
{
    Query::rollBack();
   return false;
}