# MODx Database Script for New/Upgrade Installations
# Based on Etmoite 0.6  - www.etomite.org
#
# MODx was created By Raymond Irving - Nov 2004 
#
# Each sql command is separated by double lines \n\n 


CREATE TABLE IF NOT EXISTS `{PREFIX}active_users` (
  `internalKey` int(9) NOT NULL default '0',
  `username` varchar(50) NOT NULL default '',
  `lasthit` int(20) NOT NULL default '0',
  `id` int(10) default NULL,
  `action` varchar(10) NOT NULL default '',
  `ip` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`internalKey`)
) TYPE=MyISAM COMMENT='Contains data about active users.';


CREATE TABLE IF NOT EXISTS `{PREFIX}categories` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `category` VARCHAR(45) NOT NULL,
  PRIMARY KEY(`id`)
) Type=MyISAM COMMENT = 'Categories to be used snippets,tv,chunks, etc';


CREATE TABLE IF NOT EXISTS `{PREFIX}document_groups` (
  `id` int(10) NOT NULL auto_increment,
  `document_group` int(10) NOT NULL default '0',
  `document` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `indx_doc_groups` (document)
) TYPE=MyISAM COMMENT='Contains data used for access permissions.';


CREATE TABLE IF NOT EXISTS `{PREFIX}documentgroup_names` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `private_memgroup` TINYINT DEFAULT 0 COMMENT 'determine whether the document group is private to manager users',
  `private_webgroup` TINYINT DEFAULT 0 COMMENT 'determines whether the document is private to web users',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) TYPE=MyISAM COMMENT='Contains data used for access permissions.';


CREATE TABLE IF NOT EXISTS `{PREFIX}keyword_xref` (
  `content_id` int(11) NOT NULL default '0',
  `keyword_id` int(11) NOT NULL default '0',
  KEY `content_id` (`content_id`),
  KEY `keyword_id` (`keyword_id`)
) TYPE=MyISAM COMMENT='Cross reference bewteen keywords and content';


CREATE TABLE IF NOT EXISTS `{PREFIX}log_access` (
  `visitor` int(11) NOT NULL default '0',
  `document` int(11) NOT NULL default '0',
  `timestamp` int(20) NOT NULL default '0',
  `hour` tinyint(2) NOT NULL default '0',
  `weekday` tinyint(1) NOT NULL default '0',
  `referer` int(11) NOT NULL default '0',
  `entry` tinyint(1) NOT NULL default '0',
  KEY `visitor` (`visitor`),
  KEY `document` (`document`),
  KEY `timestamp` (`timestamp`),
  KEY `referer` (`referer`),
  KEY `entry` (`entry`),
  KEY `hour` (`hour`),
  KEY `weekday` (`weekday`)
) TYPE=InnoDB COMMENT='Contains visitor statistics.';


CREATE TABLE IF NOT EXISTS `{PREFIX}log_hosts` (
  `id` int(11) NOT NULL default '0',
  `data` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=InnoDB COMMENT='Contains visitor statistics.';


CREATE TABLE IF NOT EXISTS `{PREFIX}log_operating_systems` (
  `id` int(11) NOT NULL default '0',
  `data` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=InnoDB COMMENT='Contains visitor statistics.';


CREATE TABLE IF NOT EXISTS `{PREFIX}log_referers` (
  `id` int(11) NOT NULL default '0',
  `data` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=InnoDB COMMENT='Contains visitor statistics.';


CREATE TABLE IF NOT EXISTS `{PREFIX}log_totals` (
  `today` date NOT NULL default '0000-00-00',
  `month` char(2) NOT NULL default '0',
  `piDay` int(11) NOT NULL default '0',
  `piMonth` int(11) NOT NULL default '0',
  `piAll` int(11) NOT NULL default '0',
  `viDay` int(11) NOT NULL default '0',
  `viMonth` int(11) NOT NULL default '0',
  `viAll` int(11) NOT NULL default '0',
  `visDay` int(11) NOT NULL default '0',
  `visMonth` int(11) NOT NULL default '0',
  `visAll` int(11) NOT NULL default '0'
) TYPE=MyISAM COMMENT='Stores temporary logging information.';


CREATE TABLE IF NOT EXISTS `{PREFIX}log_user_agents` (
  `id` int(11) NOT NULL default '0',
  `data` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=InnoDB COMMENT='Contains visitor statistics.';


CREATE TABLE IF NOT EXISTS `{PREFIX}log_visitors` (
  `id` int(11) NOT NULL default '0',
  `os_id` int(11) NOT NULL default '0',
  `ua_id` int(11) NOT NULL default '0',
  `host_id` int(11) NOT NULL default '0',
  KEY `id` (`id`),
  KEY `os_id` (`os_id`),
  KEY `ua_id` (`ua_id`),
  KEY `host_id` (`host_id`)
) TYPE=InnoDB COMMENT='Contains visitor statistics.';


CREATE TABLE IF NOT EXISTS `{PREFIX}manager_log` (
  `id` int(10) NOT NULL auto_increment,
  `timestamp` int(20) NOT NULL default '0',
  `internalKey` int(10) NOT NULL default '0',
  `username` varchar(255) default NULL,
  `action` int(10) NOT NULL default '0',
  `itemid` varchar(10) default '0',
  `itemname` varchar(255) default NULL,
  `message` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Contains a record of user interaction.';


CREATE TABLE IF NOT EXISTS `{PREFIX}manager_users` (
  `id` int(10) NOT NULL auto_increment,
  `username` varchar(15) NOT NULL default '',
  `password` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) TYPE=MyISAM COMMENT='Contains login information for backend users.';


CREATE TABLE IF NOT EXISTS `{PREFIX}member_groups` (
  `id` int(10) NOT NULL auto_increment,
  `user_group` int(10) NOT NULL default '0',
  `member` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Contains data used for access permissions.';


CREATE TABLE IF NOT EXISTS `{PREFIX}membergroup_access` (
  `id` int(10) NOT NULL auto_increment,
  `membergroup` int(10) NOT NULL default '0',
  `documentgroup` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Contains data used for access permissions.';


CREATE TABLE IF NOT EXISTS `{PREFIX}membergroup_names` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) TYPE=MyISAM COMMENT='Contains data used for access permissions.';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_addressbook` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `firstname` VARCHAR(50) NOT NULL,
  `lastname` VARCHAR(50) NOT NULL,
  `aka` VARCHAR(20),
  `street` VARCHAR(50),
  `city` VARCHAR(20),
  `country` VARCHAR(20),
  `zip` VARCHAR(5),
  `state` VARCHAR(20),
  `hphone` VARCHAR(20),
  `wphone` VARCHAR(20),
  `mphone` VARCHAR(20),
  `memail` VARCHAR(80),
  `email` VARCHAR(80),
  `title` VARCHAR(20),
  `catid` INTEGER,
  `manageruser` INTEGER COMMENT 'link to users table ',
  `webuser` INTEGER UNSIGNED COMMENT 'link to webusers table',
  `notes` MEDIUMTEXT,
  PRIMARY KEY(`id`)
) Type=MyISAM COMMENT = 'Site Address Book';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_content` (
  `id` int(10) NOT NULL auto_increment,
  `type` varchar(20) NOT NULL default 'document',
  `contentType` varchar(50) NOT NULL default 'text/html',
  `pagetitle` varchar(100) NOT NULL default '',
  `longtitle` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `alias` varchar(100) default '',
  `published` int(1) NOT NULL default '0',
  `pub_date` int(20) NOT NULL default '0',
  `unpub_date` int(20) NOT NULL default '0',
  `parent` int(10) NOT NULL default '0',
  `isfolder` int(1) NOT NULL default '0',
  `introtext` TEXT NOT NULL COMMENT 'Used to provide quick summary of the document',  
  `content` mediumtext NOT NULL,
  `richtext` tinyint(1) NOT NULL default '1',
  `template` int(10) NOT NULL default '1',
  `menuindex` int(10) NOT NULL default '0',
  `searchable` int(1) NOT NULL default '1',
  `cacheable` int(1) NOT NULL default '1',
  `createdby` int(10) NOT NULL default '0',
  `createdon` int(20) NOT NULL default '0',
  `editedby` int(10) NOT NULL default '0',
  `editedon` int(20) NOT NULL default '0',
  `deleted` int(1) NOT NULL default '0',
  `deletedon` int(20) NOT NULL default '0',
  `deletedby` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`),
  KEY `parent` (`parent`),
  KEY aliasidx (alias),
  FULLTEXT KEY `content_ft_idx` (`pagetitle`,`description`,`content`)
) TYPE=MyISAM COMMENT='Contains the site''s document tree.';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_htmlsnippets` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `description` varchar(255) NOT NULL default 'Chunk',
  `snippet` mediumtext NOT NULL,
  `locked` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Contains the site''s chunks.';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_keywords` (
  `id` int(11) NOT NULL auto_increment,
  `keyword` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `keyword` (`keyword`)
) TYPE=MyISAM COMMENT='Site keyword list';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_plugins` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `description` varchar(255) NOT NULL default 'Plugin',
  `plugincode` mediumtext NOT NULL,
  `locked` tinyint(4) NOT NULL default '0',
  `properties` VARCHAR(255) NOT NULL COMMENT 'Default Properties',  
  `disabled` TINYINT NOT NULL DEFAULT 0 COMMENT 'Disables the plugin',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Contains the site''s plugins.';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_plugin_events` (
  `pluginid` INT(10) NOT NULL,
  `evtid` INT(10) NOT NULL
) TYPE = MyISAM COMMENT = 'Links to system events';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_snippets` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `description` varchar(255) NOT NULL default 'Snippet',
  `snippet` mediumtext NOT NULL,
  `locked` tinyint(4) NOT NULL default '0',
  `properties` VARCHAR(255) NOT NULL COMMENT 'Default Properties',  
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Contains the site''s snippets.';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_templates` (
  `id` int(10) NOT NULL auto_increment,
  `templatename` varchar(50) NOT NULL default '',
  `description` varchar(255) NOT NULL default 'Template',
  `content` mediumtext NOT NULL,
  `locked` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Contains the site''s templates.';


CREATE TABLE IF NOT EXISTS `{PREFIX}system_eventnames` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `service` TINYINT NOT NULL COMMENT 'System Service number',
  PRIMARY KEY(`id`)
) TYPE = MYISAM COMMENT = 'System Event Names. Service = 1 - Kernel, 2 - Manager Access, 3 - Web Access, 4 - Cache, 5 - Template System ';


CREATE TABLE IF NOT EXISTS `{PREFIX}system_settings` (
  `setting_name` VARCHAR(50) NOT NULL default '',
  `setting_value` TEXT NOT NULL default '',
  UNIQUE KEY `setting_name` (`setting_name`)
) TYPE=MyISAM COMMENT='Contains Content Manager settings.';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_tmplvar_access` (
  `id` int(10) NOT NULL auto_increment,
  `tmplvarid` int(10) NOT NULL default '0',
  `documentgroup` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) Type=MyISAM  COMMENT='Contains data used for template variable access permissions.';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_tmplvar_contentvalues` (
	id int(11) NOT NULL auto_increment,
	tmplvarid int(10) NOT NULL COMMENT 'Template Variable id',
	contentid int(10) NOT NULL default '0' COMMENT 'Site Content Id',
	value text NOT NULL,
	PRIMARY KEY  (id),
	KEY idx_tmplvarid (tmplvarid),
	KEY idx_id (contentid)
) TYPE=MyISAM COMMENT = 'Site Template Variables Content Values Link Table';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_tmplvar_templates` (
	tmplvarid int(10) NOT NULL COMMENT 'Template Variable id',
	templateid int(11) NOT NULL default '0',
	KEY idx_tmplvarid (tmplvarid),
	KEY idx_templateid (templateid)
) TYPE=MyISAM COMMENT = 'Site Template Variables Templates Link Table';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_tmplvars` (
	id INT(11) NOT NULL auto_increment,
	type VARCHAR(20) NOT NULL default '',
	name VARCHAR(50) NOT NULL default '',
	caption VARCHAR(80) NOT NULL default '',
	description VARCHAR(255) NOT NULL default '',
	locked TINYINT(4) NOT NULL default '0',
	elements TEXT NOT NULL,
	rank int(11) NOT NULL NOT NULL default '0',
	display VARCHAR(20) NOT NULL COMMENT 'Content Display Format',
	display_params TEXT NOT NULL COMMENT 'Display Properties',
	default_text TEXT NOT NULL,
	PRIMARY KEY  (id),
	KEY `indx_rank`(`rank`)	
) TYPE=MyISAM COMMENT = 'Site Template Variables';


CREATE TABLE IF NOT EXISTS `{PREFIX}user_attributes` (
  `id` int(10) NOT NULL auto_increment,
  `internalKey` int(10) NOT NULL default '0',
  `fullname` varchar(100) NOT NULL default '',
  `role` int(10) NOT NULL default '0',
  `email` varchar(100) NOT NULL default '',
  `phone` varchar(100) NOT NULL default '',
  `mobilephone` varchar(100) NOT NULL default '',
  `blocked` int(1) NOT NULL default '0',
  `blockeduntil` int(11) NOT NULL default '0',
  `blockedafter` int(11) NOT NULL default '0',
  `logincount` int(11) NOT NULL default '0',
  `lastlogin` int(11) NOT NULL default '0',
  `thislogin` int(11) NOT NULL default '0',
  `failedlogincount` int(10) NOT NULL default '0',
  `sessionid` varchar(100) NOT NULL default '',
  `dob` int(10) NOT NULL DEFAULT 0,
  `gender` int(1) NOT NULL DEFAULT 0 COMMENT '0 - unknown, 1 - Male 2 - female',
  `country` varchar(5) NOT NULL,
  `state` varchar(5) NOT NULL,
  `zip` varchar(5) NOT NULL,
  `fax` varchar(100) NOT NULL,
  `photo` varchar(255) NOT NULL COMMENT 'link to photo',
  `comment` varchar(255) NOT NULL COMMENT 'short comment',  
  PRIMARY KEY  (`id`),
  KEY `userid` (`internalKey`)
) TYPE=MyISAM COMMENT='Contains information about the backend users.';


CREATE TABLE IF NOT EXISTS `{PREFIX}user_messages` (
  `id` int(10) NOT NULL auto_increment,
  `type` varchar(15) NOT NULL default '',
  `subject` varchar(60) NOT NULL default '',
  `message` text NOT NULL,
  `sender` int(10) NOT NULL default '0',
  `recipient` int(10) NOT NULL default '0',
  `private` tinyint(4) NOT NULL default '0',
  `postdate` int(20) NOT NULL default '0',
  `messageread` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Contains messages for the Content Manager messaging system.';


CREATE TABLE IF NOT EXISTS `{PREFIX}user_roles` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `frames` int(1) NOT NULL default '0',
  `home` int(1) NOT NULL default '0',
  `view_document` int(1) NOT NULL default '0',
  `new_document` int(1) NOT NULL default '0',
  `save_document` int(1) NOT NULL default '0',
  `delete_document` int(1) NOT NULL default '0',
  `action_ok` int(1) NOT NULL default '0',
  `logout` int(1) NOT NULL default '0',
  `help` int(1) NOT NULL default '0',
  `messages` int(1) NOT NULL default '0',
  `new_user` int(1) NOT NULL default '0',
  `edit_user` int(1) NOT NULL default '0',
  `logs` int(1) NOT NULL default '0',
  `edit_parser` int(1) NOT NULL default '0',
  `save_parser` int(1) NOT NULL default '0',
  `edit_template` int(1) NOT NULL default '0',
  `settings` int(1) NOT NULL default '0',
  `credits` int(1) NOT NULL default '0',
  `new_template` int(1) NOT NULL default '0',
  `save_template` int(1) NOT NULL default '0',
  `delete_template` int(1) NOT NULL default '0',
  `edit_snippet` int(1) NOT NULL default '0',
  `new_snippet` int(1) NOT NULL default '0',
  `save_snippet` int(1) NOT NULL default '0',
  `delete_snippet` int(1) NOT NULL default '0',
  `empty_cache` int(1) NOT NULL default '0',
  `edit_document` int(1) NOT NULL default '0',
  `change_password` int(1) NOT NULL default '0',
  `error_dialog` int(1) NOT NULL default '0',
  `about` int(1) NOT NULL default '0',
  `file_manager` int(1) NOT NULL default '0',
  `save_user` int(1) NOT NULL default '0',
  `delete_user` int(1) NOT NULL default '0',
  `save_password` int(11) NOT NULL default '0',
  `edit_role` int(1) NOT NULL default '0',
  `save_role` int(1) NOT NULL default '0',
  `delete_role` int(1) NOT NULL default '0',
  `new_role` int(1) NOT NULL default '0',
  `access_permissions` int(1) NOT NULL default '0',
  `bk_manager` int(1) NOT NULL DEFAULT 0,
  `view_address` int(1) NOT NULL DEFAULT 0,
  `new_address` int(1) NOT NULL DEFAULT 0,
  `save_address` int(1) NOT NULL DEFAULT 0,
  `delete_address` int(1) NOT NULL DEFAULT 0,
  `new_plugin` int(1) NOT NULL DEFAULT 0,
  `edit_plugin` int(1) NOT NULL DEFAULT 0,
  `save_plugin` int(1) NOT NULL DEFAULT 0,
  `delete_plugin` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Contains information describing the user roles.'


CREATE TABLE IF NOT EXISTS `{PREFIX}user_settings` (
  `user` INTEGER NOT NULL,
  `setting_name` varchar(50) NOT NULL default '',
  `setting_value` varchar(255) NOT NULL default '',
  KEY `setting_name` (`setting_name`),
  KEY `user` (`user`)
) Type=MyISAM COMMENT='Contains backend user settings.';


CREATE TABLE IF NOT EXISTS `{PREFIX}web_groups` (
  `id` int(10) NOT NULL auto_increment,
  `webgroup` int(10) NOT NULL default '0',
  `webuser` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) Type=MyISAM COMMENT='Contains data used for web access permissions.';


CREATE TABLE IF NOT EXISTS `{PREFIX}webgroup_access` (
  `id` int(10) NOT NULL auto_increment,
  `webgroup` int(10) NOT NULL default '0',
  `documentgroup` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) Type=MyISAM  COMMENT='Contains data used for web access permissions.';


CREATE TABLE IF NOT EXISTS `{PREFIX}webgroup_names` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) Type=MyISAM  COMMENT='Contains data used for web access permissions.';


CREATE TABLE IF NOT EXISTS `{PREFIX}web_user_attributes` (
  `id` int(10) NOT NULL auto_increment,
  `internalKey` int(10) NOT NULL default '0',
  `fullname` varchar(100) NOT NULL default '',
  `role` int(10) NOT NULL default '0',
  `email` varchar(100) NOT NULL default '',
  `phone` varchar(100) NOT NULL default '',
  `mobilephone` varchar(100) NOT NULL default '',
  `blocked` int(1) NOT NULL default '0',
  `blockeduntil` int(11) NOT NULL default '0',
  `blockedafter` int(11) NOT NULL default '0',
  `logincount` int(11) NOT NULL default '0',
  `lastlogin` int(11) NOT NULL default '0',
  `thislogin` int(11) NOT NULL default '0',
  `failedlogincount` int(10) NOT NULL default '0',
  `sessionid` varchar(100) NOT NULL default '',
  `dob` int(10) NOT NULL DEFAULT 0,
  `gender` int(1) NOT NULL DEFAULT 0 COMMENT '0 - unknown, 1 - Male 2 - female',
  `country` varchar(5) NOT NULL,
  `state` varchar(5) NOT NULL,
  `zip` varchar(5) NOT NULL,
  `fax` varchar(100) NOT NULL,
  `photo` varchar(255) NOT NULL COMMENT 'link to photo',
  `comment` varchar(255) NOT NULL COMMENT 'short comment',  
  PRIMARY KEY  (`id`),
  KEY `userid` (`internalKey`)
) Type=MyISAM  COMMENT='Contains information for web users.';


CREATE TABLE IF NOT EXISTS `{PREFIX}web_users` (
  `id` int(10) NOT NULL auto_increment,
  `username` varchar(15) NOT NULL default '',
  `password` varchar(100) NOT NULL default '',
  `cachepwd` VARCHAR(100) NOT NULL COMMENT 'Store new unconfirmed password',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) Type=MyISAM;


CREATE TABLE IF NOT EXISTS `{PREFIX}web_user_settings` (
  `webuser` INTEGER NOT NULL,
  `setting_name` varchar(50) NOT NULL default '',
  `setting_value` varchar(255) NOT NULL default '',
  KEY `setting_name` (`setting_name`),
  KEY `webuserid` (`webuser`)
) Type=MyISAM COMMENT='Contains web user settings.';


# For backward compatibilty with early versions
#::::::::::::::::::::::::::::::::::::::::::::::::::::::::::


ALTER TABLE `{PREFIX}web_users` ADD COLUMN `cachepwd` VARCHAR(100) NOT NULL COMMENT 'Store new unconfirmed password' AFTER `password`;


ALTER TABLE `{PREFIX}site_tmplvars` MODIFY COLUMN `name` VARCHAR(50) NOT NULL;


ALTER TABLE `{PREFIX}site_tmplvars` ADD INDEX `indx_rank`(`rank`);


ALTER TABLE `{PREFIX}site_content` ADD COLUMN `introtext` TEXT NOT NULL COMMENT 'Used to provide quick summary of the document' AFTER `isfolder`;


ALTER TABLE `{PREFIX}site_content` ADD INDEX aliasidx (alias)


ALTER TABLE `{PREFIX}site_plugins` ADD COLUMN `disabled` TINYINT NOT NULL COMMENT 'Disables the plugin' AFTER `properties`;


ALTER TABLE `{PREFIX}site_snippets` ADD COLUMN `properties` VARCHAR(255) NOT NULL COMMENT 'Default Properties' AFTER `locked`;


ALTER TABLE `{PREFIX}document_groups` ADD INDEX `indx_doc_groups` (document);


ALTER TABLE `{PREFIX}system_settings` MODIFY COLUMN `setting_value` TEXT NOT NULL;


ALTER TABLE `{PREFIX}documentgroup_names` 
 ADD COLUMN `private_memgroup` TINYINT DEFAULT 0 COMMENT 'determine whether the document group is private to manager users' AFTER `name`,
 ADD COLUMN `private_webgroup` TINYINT DEFAULT 0 COMMENT 'determines whether the document is private to web users' AFTER `private_memgroup`;


ALTER TABLE `{PREFIX}user_roles` 
 ADD COLUMN `bk_manager` int(1) NOT NULL DEFAULT 0 AFTER `access_permissions`,
 ADD COLUMN `view_address` int(1) NOT NULL DEFAULT 0,
 ADD COLUMN `new_address` int(1) NOT NULL DEFAULT 0,
 ADD COLUMN `save_address` int(1) NOT NULL DEFAULT 0,
 ADD COLUMN `delete_address` int(1) NOT NULL DEFAULT 0;


ALTER TABLE `{PREFIX}user_roles` 
 ADD COLUMN `new_plugin` int(1) NOT NULL DEFAULT 0,
 ADD COLUMN `edit_plugin` int(1) NOT NULL DEFAULT 0,
 ADD COLUMN `save_plugin` int(1) NOT NULL DEFAULT 0,
 ADD COLUMN `delete_plugin` int(1) NOT NULL DEFAULT 0;


ALTER TABLE `{PREFIX}user_attributes` ADD COLUMN `dob` INTEGER(10) NOT NULL DEFAULT 0 AFTER `sessionid`
, ADD COLUMN `gender` INTEGER(1) NOT NULL DEFAULT 0 COMMENT '0 - unknown, 1 - Male 2 - female' AFTER `dob`
, ADD COLUMN `country` VARCHAR(5) NOT NULL AFTER `gender`
, ADD COLUMN `state` VARCHAR(5) NOT NULL AFTER `country`
, ADD COLUMN `zip` VARCHAR(5) NOT NULL AFTER `state`
, ADD COLUMN `fax` VARCHAR(100) NOT NULL AFTER `zip`
, ADD COLUMN `blockedafter` INTEGER(11) NOT NULL DEFAULT 0 AFTER `blockeduntil`
, ADD COLUMN `photo` VARCHAR(255) NOT NULL COMMENT 'link to photo' AFTER `fax`
, ADD COLUMN `comment` VARCHAR(255) NOT NULL COMMENT 'short comment' AFTER `photo`;


ALTER TABLE `{PREFIX}web_user_attributes` ADD COLUMN `dob` INTEGER(10) NOT NULL DEFAULT 0 AFTER `sessionid`
, ADD COLUMN `gender` INTEGER(1) NOT NULL DEFAULT 0 COMMENT '0 - unknown, 1 - Male 2 - female' AFTER `dob`
, ADD COLUMN `country` VARCHAR(5) NOT NULL AFTER `gender`
, ADD COLUMN `state` VARCHAR(5) NOT NULL AFTER `country`
, ADD COLUMN `zip` VARCHAR(5) NOT NULL AFTER `state`
, ADD COLUMN `fax` VARCHAR(100) NOT NULL AFTER `zip`
, ADD COLUMN `blockedafter` INTEGER(11) NOT NULL DEFAULT 0 AFTER `blockeduntil`
, ADD COLUMN `photo` VARCHAR(255) NOT NULL COMMENT 'link to photo' AFTER `fax`
, ADD COLUMN `comment` VARCHAR(255) NOT NULL COMMENT 'short comment' AFTER `photo`;


# Insert / Replace system records
#::::::::::::::::::::::::::::::::::::::::::::::::::::::::::


# non-upgrade-able[[ - This block of code will not be executed during upgrades


# Default Site Template


REPLACE INTO `{PREFIX}site_templates` VALUES (1, 'Built-in Template', 'Default template, designed by Helder :)', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" \r\n  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">\r\n<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">\r\n<head>\r\n<title>[(site_name)] &raquo; [*pagetitle*]</title>\r\n<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />\r\n[[GetKeywords]]\r\n        <style type="text/css">\r\n             @import url(\'assets/site/style.css\');\r\n        </style>\r\n</head>\r\n\r\n<body>\r\n<table border="0" cellpadding="0" cellspacing="0" class="mainTable">\r\n  <tr class="fancyRow">\r\n    <td><span class="headers">&nbsp;<img src="manager/media/images/misc/dot.gif" alt="" style="margin-top: 1px;" />&nbsp;<a href="[~[(site_start)]~]">[(site_name)]</a></span></td>\r\n    <td align="right"><span class="headers">[[PageTrail]]</span></td>\r\n  </tr>\r\n  <tr class="fancyRow2">\r\n    <td colspan="2" class="border-top-bottom smallText" align="right">[[PoweredBy]]</td>\r\n  </tr>\r\n  <tr align="left" valign="top">\r\n    <td colspan="2"><table width="100%"  border="0" cellspacing="0" cellpadding="1">\r\n      <tr align="left" valign="top">\r\n        <td class="w22"><table width="100%" border="0" cellpadding="0" cellspacing="0">\r\n          <tr>\r\n            <td align="center" valign="middle" class="logoBox"><a href="[~[(site_start)]~]"><img src="assets/images/logo.png" alt="" /></a></td>\r\n          </tr>\r\n          <tr>\r\n            <td align="left" valign="top"><img src="manager/media/images/_tx_.gif" alt="" height="4" /></td>\r\n          </tr>\r\n          <tr class="fancyRow2">\r\n            <td align="left" valign="top" class="navigationHead">Navigation</td>\r\n          </tr>\r\n          <tr style="padding: 0px; margin: 0px;">\r\n            <td align="left" valign="top" class="navigation" style="padding: 0px; margin: 0px;"><img src="manager/media/images/_tx_.gif" alt="" height="4" /><br />[[MenuBuilder?id=0]]<img src="manager/media/images/_tx_.gif" height="4" alt="" /></td>\r\n          </tr>\r\n        </table></td>\r\n        <td class="pad" id="content"><h1>[*longtitle*]</h1>[*content*]</td>\r\n      </tr>\r\n    </table></td>\r\n  </tr>\r\n  <tr class="fancyRow2">\r\n    <td class="border-top-bottom smallText">&nbsp;</td>\r\n    <td class="border-top-bottom smallText" align="right">MySQL: [^qt^], [^q^] request(s), PHP: [^p^], total: [^t^], document retrieved from [^s^].</td>\r\n  </tr>\r\n</table>\r\n</body>\r\n</html>', 0);


# Default Site Documents


REPLACE INTO `{PREFIX}site_content` VALUES (1, 'document', 'text/html', 'Home', 'Welcome to MODx', 'Introduction to MODx', 'home', 1, 0, 0, 0, 0,'Create and do amazing things with MODx', '<p><strong><font size="5">Welcome to MODx!</font><font size="6"><br></font></strong><font size="2" color="#808080">This is the default installation site. </font></p><p><font size="2"><font color="#666666"><b>Install Successful!</b></font><br>If you are reading this message then it means you have successfully installed and configured the MODx Content Manager software. </font></p><p><font size="2"><font color="#666666"><b>Getting started</b></font><br>To get started with your new Content Manager all you need to do is to add your content, design a unique template for your site, perhaps write some snippets and Template Variables (TV) which will make your site stand out (don\'t forget to share them at the <a href="www.vertextworks.com/forums">forums</a>), and, most of all, enjoy using your new MODx enabled website!</font></p><p><font size="2">To learn more about the MODx Content Manager, see the &quot;Getting Started Tutorial.&quot;</font></p><p><font size="2">To log into the manager, point your browser to <a href="manager">[(site_url)]manager/</a>.</font></p>', 1, 1, 1, 1, 1, 1, 1087155171, 1, 1091397434, 0, 0, 0);


REPLACE INTO `{PREFIX}site_content` VALUES (2, 'document', 'text/html', 'Repository', 'The secret Repository', 'Folder for other stuff :)', '', 0, 0, 0, 0, 1, '', 'This is your repository folder', 0, 1, 0, 0, 0, 1, 1090963494, 1, 1090963494, 0, 0, 0);


REPLACE INTO `{PREFIX}manager_users` VALUES (1, '{ADMIN}', MD5('{ADMINPASS}'));


REPLACE INTO `{PREFIX}user_attributes` 
(id, internalKey, fullname, role, email, phone, mobilephone, blocked, blockeduntil, blockedafter, logincount, lastlogin, thislogin, failedlogincount, sessionid, dob, gender, country, state, zip, fax, photo, comment)
VALUES (1, 1, 'Built-in Administration account', 1, 'Your email goes here', '0', '0', 0, 0, 0, 57, 1091201287, 1091202027, 0, '', 0, '', '', '', '', '', '', '');


# Default Site Settings


REPLACE INTO `{PREFIX}system_settings` VALUES ('manager_theme','MODx'),
('settings_version','2 RC3'),
('server_offset_time','0'),
('server_protocol','http'),
('manager_language','english'),
('etomite_charset','iso-8859-1'),
('site_name','My MODx Site'),
('site_start','1'),
('error_page','1'),
('unauthorized_page','1'),
('site_status','1'),
('site_unavailable_message','The site is currently unavailable'),
('track_visitors','1'),
('resolve_hostnames','1'),
('top_howmany','10'),
('default_template','1'),
('old_template',''),
('publish_default','0'),
('cache_default','0'),
('search_default','0'),
('friendly_urls','0'),
('friendly_url_prefix','p'),
('friendly_url_suffix','.html'),
('friendly_alias_urls','0'),
('use_alias_path','0'),
('use_udperms','0'),
('udperms_allowroot','0'),
('use_captcha','0'),
('captcha_words','Alex,BitCode,Chunk,Design,Extreme,FinalFantasy,Gerry,Holiday,Join(),Kakogenic,Lightning,Maaike,Marit,Niche,Oscilloscope,Phusion,Query,Retail,Snippet,Template,USSEnterprise,Verily,Website,Ypsilon,Zebra'),
('emailsender','you@yourdomain.com'),
('emailsubject','Your login details'),
('signupemail_message','Hello [+uid+] \r\n\r\nHere are your login details for [+sname+] Content Manager:\r\n\r\nUsername: [+uid+]\r\nPassword: [+pwd+]\r\n\r\nOnce you log into the Content Manager, you can change your password.\r\n\r\nRegards,\r\nSite Administrator'),
('websignupemail_message','Hello [+uid+] \r\n\r\nHere are your login details for [+sname+]:\r\n\r\nUsername: [+uid+]\r\nPassword: [+pwd+]\r\n\r\nOnce you log into [+sname+], you can change your password.\r\n\r\nRegards,\r\nSite Administrator'),
('webpwdreminder_message','Hello [+uid+]\r\n\r\nTo active you new password click the following link:\r\n\r\n[+surl+]\r\n\r\nIf successful you can use the following password to login:\r\n\r\nPassword:[+pwd+]\r\n\r\nIf you did not request this email then please ignore it.\r\n\r\nRegrads,\r\nSite Administrator'),
('number_of_logs','100'),
('number_of_messages','30'),
('use_editor','1'),
('which_editor','1'),
('strict_editor','1'),
('im_plugin','1'),
('cm_plugin','0'),
('to_plugin','0'),
('tiny_css_path',''),
('tiny_css_selectors',''),
('strip_image_paths','0'),
('upload_files','jpg,gif,png,ico,txt,php,html,htm,xml,js,css,cache,zip,gz,rar,z,tgz,tar,htaccess,bmp,mp3,wav,au,wmv,avi,mpg,mpeg,pdf,psd'),
('upload_maxsize','1048576'),
('show_preview','1'),
('theme_refresher',''),
('manager_layout','1');


# ]]non-upgrade-able


REPLACE INTO `{PREFIX}user_roles` VALUES (1, 'Administrator', 'Site administrators have full access to all functions', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);


# 1 - "Parser Service Events", 2 -  "Manager Access Events", 3 - "Web Access Service Events", 4 - "Cache Service Events", 5 - "Template Service Events", 6 - Custom Events


REPLACE INTO `{PREFIX}system_eventnames` VALUES 
('1','OnDocPublished','5'), 
('2','OnDocUnPublished','5'),
('3','OnWebPagePrerender','5'),
('4','OnWebLogin','3'),
('5','OnBeforeWebLogout','3'),
('6','OnWebLogout','3'),
('7','OnWebSaveUser ','3'),
('8','OnWebDeleteUser','3'),
('9','OnWebChangePassword ','3'),
('10','OnWebCreateGroup','3'),
('11','OnManagerLogin','2'),
('12','OnBeforeManagerLogout','2'),
('13','OnManagerLogout','2'),
('14','OnManagerSaveUser ','2'),
('15','OnManagerDeleteUser','2'),
('16','OnManagerChangePassword ','2'),
('17','OnManagerCreateGroup','2'),
('18','OnBeforeCacheUpdate','4'),
('19','OnCacheUpdate','4'),
('20','OnLoadWebPageCache ','4'),
('21','OnBeforeSaveWebPageCache','4'),
('22','OnChunkFormPrerender','1'),
('23','OnChunkFormRender','1'),
('24','OnBeforeChunkFormSave','1'),
('25','OnChunkFormSave','1'),
('26','OnBeforeChunkFormDelete','1'),
('27','OnChunkFormDelete','1'),
('28','OnDocFormPrerender','1'),
('29','OnDocFormRender','1'),
('30','OnBeforeDocFormSave','1'),
('31','OnDocFormSave','1'),
('32','OnBeforeDocFormDelete','1'),
('33','OnDocFormDelete','1'),
('34','OnPluginFormPrerender','1'),
('35','OnPluginFormRender','1'),
('36','OnBeforePluginFormSave','1'),
('37','OnPluginFormSave','1'),
('38','OnBeforePluginFormDelete','1'),
('39','OnPluginFormDelete','1'),
('40','OnSnipFormPrerender','1'),
('41','OnSnipFormRender','1'),
('42','OnBeforeSnipFormSave','1'),
('43','OnSnipFormSave','1'),
('44','OnBeforeSnipFormDelete','1'),
('45','OnSnipFormDelete','1'),
('46','OnTempFormPrerender','1'),
('47','OnTempFormRender','1'),
('48','OnBeforeTempFormSave','1'),
('49','OnTempFormSave','1'),
('50','OnBeforeTempFormDelete','1'),
('51','OnTempFormDelete','1'),
('52','OnTVFormPrerender','1'),
('53','OnTVFormRender','1'),
('54','OnBeforeTVFormSave','1'),
('55','OnTVFormSave','1'),
('56','OnBeforeTVFormDelete','1'),
('57','OnTVFormDelete','1'),
('58','OnUserFormPrerender','1'),
('59','OnUserFormRender','1'),
('60','OnBeforeUserFormSave','1'),
('61','OnUserFormSave','1'),
('62','OnBeforeUserFormDelete','1'),
('63','OnUserFormDelete','1'),
('64','OnWUsrFormPrerender','1'),
('65','OnWUsrFormRender','1'),
('66','OnBeforeWUsrFormSave','1'),
('67','OnWUsrFormSave','1'),
('68','OnBeforeWUsrFormDelete','1'),
('69','OnWUsrFormDelete','1'),
('70','OnSiteRefresh','1'),
('71','OnFileManagerUpload','1'),
('200','OnCreateDocGroup','1'),
('999','OnPageUnauthorized','1'),
('1000','OnPageNotFound','1');


# ^ I don't think we need more than 1000 built-in events. Custom events will start at 1001


# Update System Tables 
#::::::::::::::::::::::::::::::::::::::::::::::::::::::::::


UPDATE `{PREFIX}user_roles` SET 
	bk_manager=1,
	view_address=1,
	new_address=1,
	save_address=1,
	delete_address=1,
	new_plugin=1,
	edit_plugin=1,
	save_plugin=1,
	delete_plugin=1	
	WHERE  id=1;

