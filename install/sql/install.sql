DROP TABLE IF EXISTS `prefix_accounts`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `prefix_accounts` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(40) default NULL,
  `security_level` varchar(40) default NULL,
  `creator_id` int(10) unsigned default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

INSERT INTO `prefix_accounts` VALUES (1,'SubwayCRM','lowl',1,NOW(),NOW());

DROP TABLE IF EXISTS `prefix_addresses`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `prefix_addresses` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `associated_with_id` int(10) unsigned default NULL,
  `association_type` int(10) unsigned default NULL,
  `street` varchar(40) default NULL,
  `city` varchar(40) default NULL,
  `state` varchar(40) default NULL,
  `zip` varchar(40) default NULL,
  `country` varchar(40) default NULL,
  `name` varchar(40) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

DROP TABLE IF EXISTS `prefix_association_tags`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `prefix_association_tags` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tag_id` int(10) unsigned default NULL,
  `associated_with_id` int(10) unsigned default NULL,
  `association_type` int(10) unsigned default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

INSERT INTO `prefix_association_tags` VALUES (1,1,3,0);

DROP TABLE IF EXISTS `prefix_cake_sessions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `prefix_cake_sessions` (
  `id` varchar(255) NOT NULL default '',
  `data` text,
  `expires` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

DROP TABLE IF EXISTS `prefix_companies`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `prefix_companies` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `account_id` int(10) unsigned NOT NULL,
  `name` varchar(40) default NULL,
  `creator_id` int(10) unsigned default NULL,
  `image_id` int(10) unsigned default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

INSERT INTO `prefix_companies` VALUES (1,1,'Reuters',1,NULL,NOW(),NOW());

DROP TABLE IF EXISTS `prefix_contact_methods`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `prefix_contact_methods` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `associated_with_id` int(10) unsigned default NULL,
  `association_type` int(10) unsigned default NULL,
  `type` int(10) unsigned default NULL,
  `detail` varchar(40) default NULL,
  `name` varchar(40) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

INSERT INTO `prefix_contact_methods` VALUES (1,1,0,1,'email@yourdomain.com','Work',NOW(),NOW());

DROP TABLE IF EXISTS `prefix_files`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `prefix_files` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(75) NOT NULL,
  `type` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `data` mediumblob NOT NULL,
  `creator_id` int(10) unsigned default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

DROP TABLE IF EXISTS `prefix_groups`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `prefix_groups` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `account_id` int(10) unsigned NOT NULL,
  `name` varchar(40) default NULL,
  `parent_id` int(10) unsigned default NULL,
  `lft` int(10) unsigned default NULL,
  `rgt` int(10) unsigned default NULL,
  `creator_id` int(10) unsigned default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

INSERT INTO `prefix_groups` VALUES (1,1,'ROOT',NULL,1,13,1,NOW(),NOW());

DROP TABLE IF EXISTS `prefix_kases`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `prefix_kases` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `account_id` int(10) unsigned NOT NULL,
  `name` varchar(40) default NULL,
  `creator_id` int(10) unsigned default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

DROP TABLE IF EXISTS `prefix_notes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `prefix_notes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `associated_with_id` int(10) unsigned default NULL,
  `association_type` int(10) unsigned default NULL,
  `kase_id` int(11) default NULL,
  `file_id` int(11) default NULL,
  `file_name` varchar(200) default NULL,
  `text` longtext NOT NULL,
  `creator_id` int(10) unsigned default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

DROP TABLE IF EXISTS `prefix_people`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `prefix_people` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `account_id` int(10) unsigned NOT NULL,
  `username` varchar(20) default NULL,
  `password` varchar(40) default NULL,
  `first_name` varchar(40) default NULL,
  `surname` varchar(40) default NULL,
  `title` varchar(40) default NULL,
  `company_id` int(10) unsigned default NULL,
  `creator_id` int(10) unsigned default NULL,
  `group_id` int(10) unsigned default NULL,
  `administrator` int(10) unsigned default NULL,
  `invitation` int(10) unsigned default NULL,
  `image_id` int(10) unsigned default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

INSERT INTO `prefix_people` VALUES (1,1,'useradmin','passadmin','thefirstname','thesurname','',1,1,1,1,NULL,NULL,NOW(),NOW());

DROP TABLE IF EXISTS `prefix_tags`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `prefix_tags` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `account_id` int(10) unsigned NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

DROP TABLE IF EXISTS `prefix_tasks`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `prefix_tasks` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `assigned_id` int(10) unsigned default NULL,
  `creator_id` int(10) unsigned default NULL,
  `associated_with_id` int(10) unsigned default NULL,
  `association_type` int(10) unsigned default NULL,
  `subject` varchar(100) NOT NULL,
  `completed` datetime default NULL,
  `due_date` date default NULL,
  `due_time` time default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

DROP TABLE IF EXISTS `prefix_configurations`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `prefix_configurations` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(100) default NULL,
  `value` varchar(100) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

DROP TABLE IF EXISTS `prefix_lrus`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `prefix_lrus` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `person_id` int(10) unsigned default NULL,
  `value` varchar(100) default NULL,
  `name` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

