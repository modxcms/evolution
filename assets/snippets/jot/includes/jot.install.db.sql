CREATE TABLE IF NOT EXISTS `{PREFIX}jot_content` (
  `id` int(10) NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `tagid` varchar(50) default NULL,
  `published` int(1) NOT NULL default '0',
  `uparent` int(10) NOT NULL,
  `parent` int(10) NOT NULL default '0',
  `flags` varchar(25) default NULL,
  `secip` varchar(32) default NULL,
  `sechash` varchar(32) default NULL,
  `content` mediumtext,
  `mode` int(1) NOT NULL default '1',
  `createdby` int(10) NOT NULL default '0',
  `createdon` int(20) NOT NULL default '0',
  `editedby` int(10) NOT NULL default '0',
  `editedon` int(20) NOT NULL default '0',
  `deleted` int(1) NOT NULL default '0',
  `deletedon` int(20) NOT NULL default '0',
  `deletedby` int(10) NOT NULL default '0',
  `publishedon` int(20) NOT NULL default '0',
  `publishedby` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `parent` (`parent`),
  KEY `secip` (`secip`),
  KEY `tagidx` (`tagid`),
  KEY `uparent` (`uparent`)
) ENGINE=MyISAM;


CREATE TABLE IF NOT EXISTS `{PREFIX}jot_subscriptions` (
  `id` mediumint(10) NOT NULL auto_increment,
  `uparent` mediumint(10) NOT NULL,
  `tagid` varchar(50) NOT NULL,
  `userid` mediumint(10) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uparent` (`uparent`),
  KEY `tagid` (`tagid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM;


CREATE TABLE IF NOT EXISTS `{PREFIX}jot_fields` (
  `id` mediumint(10) NOT NULL,
  `label` varchar(50) NOT NULL,
  `content` text,
  KEY `id` (`id`),
  KEY `label` (`label`)
) ENGINE=MyISAM;