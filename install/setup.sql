# MODx Database Script for New/Upgrade Installations
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
) Type=MyISAM COMMENT='Categories to be used snippets,tv,chunks, etc';


CREATE TABLE IF NOT EXISTS `{PREFIX}document_groups` (
  `id` int(10) NOT NULL auto_increment,
  `document_group` int(10) NOT NULL default '0',
  `document` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `document` (document),
  KEY `document_group` (document_group)
) TYPE=MyISAM COMMENT='Contains data used for access permissions.';


CREATE TABLE IF NOT EXISTS `{PREFIX}documentgroup_names` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `private_memgroup` TINYINT DEFAULT 0 COMMENT 'determine whether the document group is private to manager users',
  `private_webgroup` TINYINT DEFAULT 0 COMMENT 'determines whether the document is private to web users',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) TYPE=MyISAM COMMENT='Contains data used for access permissions.';


CREATE TABLE IF NOT EXISTS `{PREFIX}event_log` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `eventid` INTEGER DEFAULT 0,
  `createdon` INTEGER NOT NULL DEFAULT 0,
  `type` TINYINT NOT NULL DEFAULT 1 COMMENT '1- information, 2 - warning, 3- error',
  `user` INTEGER NOT NULL DEFAULT 0 COMMENT 'link to user table',
  `usertype` TINYINT NOT NULL DEFAULT 0 COMMENT '0 - manager, 1 - web',
  `source` VARCHAR(50) NOT NULL,
  `description` TEXT NOT NULL,
  PRIMARY KEY(`id`),
  KEY `user`(`user`)
) TYPE=MYISAM COMMENT='Stores event and error logs';


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
  `menutitle` VARCHAR(30) NOT NULL COMMENT 'Menu title',
  `donthit` TINYINT(1) NOT NULL default '0' COMMENT 'Disable page hit count',
  `haskeywords` TINYINT(1) NOT NULL default '0' COMMENT 'has links to keywords',
  `hasmetatags` TINYINT(1) NOT NULL default '0' COMMENT 'has links to meta tags',
  `privateweb` TINYINT(1) NOT NULL default '0' COMMENT 'Private web document',
  `privatemgr` TINYINT(1) NOT NULL default '0' COMMENT 'Private manager document',
  `content_dispo` TINYINT(1) NOT NULL default '0' COMMENT '0-inline, 1-attachment',
  `hidemenu` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Hide document from menu',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`),
  KEY `parent` (`parent`),
  KEY aliasidx (alias),
  FULLTEXT KEY `content_ft_idx` (`pagetitle`,`description`,`content`)
) TYPE=MyISAM COMMENT='Contains the site document tree.';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_content_metatags` (
  `content_id` int(11) NOT NULL default '0',
  `metatag_id` int(11) NOT NULL default '0',
  KEY `content_id` (`content_id`),
  KEY `metatag_id` (`metatag_id`)
) TYPE=MyISAM COMMENT='Reference table between meta tags and content';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_htmlsnippets` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `description` varchar(255) NOT NULL default 'Chunk',
  `editor_type` INTEGER NOT NULL DEFAULT '0' COMMENT '0-plain text,1-rich text,2-code editor',
  `category` INTEGER NOT NULL DEFAULT '0' COMMENT 'category id',
  `cache_type`	TINYINT(1) NOT NULL default '0' COMMENT 'Cache option',
  `snippet` mediumtext NOT NULL,
  `locked` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Contains the site chunks.';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_keywords` (
  `id` int(11) NOT NULL auto_increment,
  `keyword` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `keyword` (`keyword`)
) TYPE=MyISAM COMMENT='Site keyword list';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_metatags` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL,
  `tag` VARCHAR(20) NOT NULL COMMENT 'tag name',
  `tagvalue` VARCHAR(100) NOT NULL,
  `http_equiv` TINYINT NOT NULL DEFAULT 0 COMMENT '1 - use http_equiv tag style, 0 - use name',
  PRIMARY KEY(`id`)
) TYPE=MYISAM COMMENT='Site meta tags';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_modules` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `description` VARCHAR(255) NOT NULL DEFAULT '0',
  `editor_type` INTEGER NOT NULL DEFAULT '0' COMMENT '0-plain text,1-rich text,2-code editor',
  `disabled` TINYINT NOT NULL,
  `category` INTEGER NOT NULL DEFAULT '0' COMMENT 'category id',
  `wrap` TINYINT NOT NULL DEFAULT '0',
  `locked` TINYINT NOT NULL default '0',
  `icon` VARCHAR(255) NOT NULL COMMENT 'url to module icon',
  `enable_resource` TINYINT NOT NULL DEFAULT '0' COMMENT 'enables the resource file feature',
  `resourcefile` VARCHAR(255) NOT NULL COMMENT 'a physical link to a resource file',
  `createdon` INTEGER NOT NULL,  
  `editedon` INTEGER NOT NULL,
  `guid` VARCHAR(32) NOT NULL COMMENT 'globally unique identifier',
  `enable_sharedparams` TINYINT NOT NULL DEFAULT '0',
  `properties` TEXT NOT NULL,
  `modulecode` MEDIUMTEXT NOT NULL COMMENT 'module boot up code',
  PRIMARY KEY(`id`)
) TYPE=MyISAM COMMENT='Site Modules';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_module_depobj` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `module` INTEGER NOT NULL,
  `resource` INTEGER NOT NULL,
  `type` INTEGER(2) NOT NULL DEFAULT 0 COMMENT '10-chunks, 20-docs, 30-plugins, 40-snips, 50-tpls, 60-tvs',
  PRIMARY KEY(`id`)
) TYPE=MYISAM COMMENT='Module Dependencies';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_module_access` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `module` INTEGER NOT NULL,
  `usergroup` INTEGER NOT NULL,
  PRIMARY KEY(`id`)
) TYPE=MYISAM COMMENT='Module users group access permission';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_plugins` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `description` varchar(255) NOT NULL default 'Plugin',
  `editor_type` INTEGER NOT NULL DEFAULT '0' COMMENT '0-plain text,1-rich text,2-code editor',
  `category` INTEGER NOT NULL DEFAULT '0' COMMENT 'category id',
  `cache_type` TINYINT(1) NOT NULL default '0' COMMENT 'Cache option',
  `plugincode` mediumtext NOT NULL,
  `locked` tinyint(4) NOT NULL default '0',
  `properties` TEXT NOT NULL COMMENT 'Default Properties',  
  `disabled` TINYINT NOT NULL DEFAULT 0 COMMENT 'Disables the plugin',
  `moduleguid` VARCHAR(32) NOT NULL COMMENT 'GUID of module from which to import shared parameters',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Contains the site plugins.';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_plugin_events` (
  `pluginid` INT(10) NOT NULL,
  `evtid` INT(10) NOT NULL
) TYPE=MyISAM COMMENT='Links to system events';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_snippets` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `description` varchar(255) NOT NULL default 'Snippet',
  `editor_type` INTEGER NOT NULL DEFAULT '0' COMMENT '0-plain text,1-rich text,2-code editor',
  `category` INTEGER NOT NULL DEFAULT '0' COMMENT 'category id',
  `cache_type` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Cache option',
  `snippet` mediumtext NOT NULL,
  `locked` tinyint(4) NOT NULL default '0',
  `properties` VARCHAR(255) NOT NULL COMMENT 'Default Properties',  
  `moduleguid` VARCHAR(32) NOT NULL COMMENT 'GUID of module from which to import shared parameters',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Contains the site snippets.';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_templates` (
  `id` int(10) NOT NULL auto_increment,
  `templatename` varchar(50) NOT NULL default '',
  `description` varchar(255) NOT NULL default 'Template',
  `editor_type` INTEGER NOT NULL DEFAULT '0' COMMENT '0-plain text,1-rich text,2-code editor',
  `category` INTEGER NOT NULL DEFAULT '0' COMMENT 'category id',
  `icon` VARCHAR(255) NOT NULL COMMENT 'url to icon file',
  `template_type` INTEGER NOT NULL DEFAULT '0' COMMENT '0-page,1-content',
  `content` mediumtext NOT NULL,
  `locked` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Contains the site templates.';


CREATE TABLE IF NOT EXISTS `{PREFIX}system_eventnames` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `service` TINYINT NOT NULL COMMENT 'System Service number',
  `groupname` VARCHAR(20) NOT NULL default '',
  PRIMARY KEY(`id`)
) TYPE=MYISAM COMMENT='System Event Names. Service = 1 - Kernel, 2 - Manager Access, 3 - Web Access, 4 - Cache, 5 - Template System ';


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
) Type=MyISAM COMMENT='Contains data used for template variable access permissions.';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_tmplvar_contentvalues` (
	`id` int(11) NOT NULL auto_increment,
	`tmplvarid` int(10) NOT NULL COMMENT 'Template Variable id',
	`contentid` int(10) NOT NULL default '0' COMMENT 'Site Content Id',
	`value` text NOT NULL,
	PRIMARY KEY  (id),
	KEY idx_tmplvarid (tmplvarid),
	KEY idx_id (contentid)
) TYPE=MyISAM COMMENT='Site Template Variables Content Values Link Table';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_tmplvar_templates` (
	`tmplvarid` int(10) NOT NULL COMMENT 'Template Variable id',
	`templateid` int(11) NOT NULL default '0',
	PRIMARY KEY (`tmplvarid`, `templateid`)
) TYPE=MyISAM COMMENT='Site Template Variables Templates Link Table';


CREATE TABLE IF NOT EXISTS `{PREFIX}site_tmplvars` (
	`id` INT(11) NOT NULL auto_increment,
	`type` VARCHAR(20) NOT NULL default '',
	`name` VARCHAR(50) NOT NULL default '',
	`caption` VARCHAR(80) NOT NULL default '',
	`description` VARCHAR(255) NOT NULL default '',
	`editor_type` INTEGER NOT NULL DEFAULT '0' COMMENT '0-plain text,1-rich text,2-code editor',
	`category` INTEGER NOT NULL DEFAULT '0' COMMENT 'category id',
	`locked` TINYINT(4) NOT NULL default '0',
	`elements` TEXT NOT NULL,
	`rank` int(11) NOT NULL NOT NULL default '0',
	`display` VARCHAR(20) NOT NULL COMMENT 'Display Control',
	`display_params` TEXT NOT NULL COMMENT 'Display Control Properties',
	`default_text` TEXT NOT NULL,
	PRIMARY KEY  (id),
	KEY `indx_rank`(`rank`)	
) TYPE=MyISAM COMMENT='Site Template Variables';


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
  `new_plugin` int(1) NOT NULL DEFAULT 0,
  `edit_plugin` int(1) NOT NULL DEFAULT 0,
  `save_plugin` int(1) NOT NULL DEFAULT 0,
  `delete_plugin` int(1) NOT NULL DEFAULT 0,
  `new_module` int(1) NOT NULL DEFAULT 0,
  `edit_module` int(1) NOT NULL DEFAULT 0,
  `save_module` int(1) NOT NULL DEFAULT 0,
  `delete_module` int(1) NOT NULL DEFAULT 0,
  `exec_module` int(1) NOT NULL DEFAULT 0,
  `view_eventlog` int(1) NOT NULL DEFAULT 0,
  `delete_eventlog` int(1) NOT NULL DEFAULT 0,
  `manage_metatags` int(1) NOT NULL DEFAULT 0 COMMENT 'manage site meta tags and keywords',	
  `edit_doc_metatags` int(1) NOT NULL DEFAULT 0 COMMENT 'edit document meta tags and keywords' ,
  `new_web_user` int(1) NOT NULL default '0',
  `edit_web_user` int(1) NOT NULL default '0',
  `save_web_user` int(1) NOT NULL default '0',
  `delete_web_user` int(1) NOT NULL default '0',
  `web_access_permissions` int(1) NOT NULL default '0',
  `view_unpublished` int(1) NOT NULL default '0',
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
) Type=MyISAM COMMENT='Contains data used for web access permissions.';


CREATE TABLE IF NOT EXISTS `{PREFIX}webgroup_names` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) Type=MyISAM COMMENT='Contains data used for web access permissions.';


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
  `dob` int(10) NOT NULL DEFAULT '0',
  `gender` int(1) NOT NULL DEFAULT '0' COMMENT '0 - unknown, 1 - Male 2 - female',
  `country` varchar(5) NOT NULL,
  `state` varchar(5) NOT NULL,
  `zip` varchar(5) NOT NULL,
  `fax` varchar(100) NOT NULL,
  `photo` varchar(255) NOT NULL COMMENT 'link to photo',
  `comment` varchar(255) NOT NULL COMMENT 'short comment',  
  PRIMARY KEY  (`id`),
  KEY `userid` (`internalKey`)
) Type=MyISAM COMMENT='Contains information for web users.';


CREATE TABLE IF NOT EXISTS `{PREFIX}web_users` (
  `id` int(10) NOT NULL auto_increment,
  `username` varchar(100) NOT NULL default '',
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


ALTER TABLE `{PREFIX}site_tmplvars` ADD COLUMN `editor_type` INTEGER NOT NULL DEFAULT '0' COMMENT '0-plain text,1-rich text,2-code editor' AFTER `description`
, ADD COLUMN `category` INTEGER NOT NULL DEFAULT '0' COMMENT 'category id' AFTER `editor_type`;


ALTER TABLE `{PREFIX}site_tmplvars` MODIFY COLUMN `name` VARCHAR(50) NOT NULL;


ALTER TABLE `{PREFIX}site_tmplvars` ADD INDEX `indx_rank`(`rank`);


ALTER TABLE `{PREFIX}site_content` ADD INDEX `aliasidx` (alias);


ALTER TABLE `{PREFIX}site_content` ADD COLUMN `introtext` TEXT NOT NULL COMMENT 'Used to provide quick summary of the document' AFTER `isfolder`;


ALTER TABLE `{PREFIX}site_content`  ADD COLUMN `menutitle` VARCHAR(30) NOT NULL COMMENT 'Menu title' AFTER `deletedby`
, ADD COLUMN `donthit` TINYINT(1) NOT NULL default '0' COMMENT 'Disable page hit count' AFTER `menutitle`
, ADD COLUMN `haskeywords` TINYINT(1) NOT NULL default '0' COMMENT 'has links to keywords' AFTER `donthit`
, ADD COLUMN `hasmetatags` TINYINT(1) NOT NULL default '0' COMMENT 'has links to meta tags' AFTER `haskeywords`
, ADD COLUMN `privateweb` TINYINT(1) NOT NULL default '0' COMMENT 'Private web document' AFTER `hasmetatags`
, ADD COLUMN `privatemgr` TINYINT(1) NOT NULL default '0' COMMENT 'Private manager document' AFTER `privateweb`;


ALTER TABLE `{PREFIX}site_content` ADD COLUMN `content_dispo` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0-inline, 1-attachment' AFTER `privatemgr`;


ALTER TABLE `{PREFIX}site_content` ADD COLUMN `hidemenu` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Hide document from menu' AFTER `content_dispo`;


ALTER TABLE `{PREFIX}site_plugins` ADD COLUMN `editor_type` INTEGER NOT NULL DEFAULT '0' COMMENT '0-plain text,1-rich text,2-code editor' AFTER `description`
, ADD COLUMN `category` INTEGER NOT NULL DEFAULT '0' COMMENT 'category id' AFTER `editor_type`
, ADD COLUMN `cache_type` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'cache option' AFTER `category`;


ALTER TABLE `{PREFIX}site_plugins` ADD COLUMN `disabled` TINYINT NOT NULL COMMENT 'Disables the plugin' AFTER `properties`;


ALTER TABLE `{PREFIX}site_plugins` ADD COLUMN `moduleguid` VARCHAR(32) NOT NULL COMMENT 'GUID of module from which to import shared parameters' AFTER `disabled`;


ALTER TABLE `{PREFIX}site_htmlsnippets` ADD COLUMN `editor_type` INTEGER NOT NULL DEFAULT '0' COMMENT '0-plain text,1-rich text,2-code editor' AFTER `description`
, ADD COLUMN `category` INTEGER NOT NULL DEFAULT '0' COMMENT 'category id' AFTER `editor_type`
, ADD COLUMN `cache_type` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'cache option' AFTER `category`;


ALTER TABLE `{PREFIX}site_snippets` ADD COLUMN `editor_type` INTEGER NOT NULL DEFAULT '0' COMMENT '0-plain text,1-rich text,2-code editor' AFTER `description`
, ADD COLUMN `category` INTEGER NOT NULL DEFAULT '0' COMMENT 'category id' AFTER `editor_type`
, ADD COLUMN `cache_type` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'cache option' AFTER `category`;


ALTER TABLE `{PREFIX}site_snippets` ADD COLUMN `properties` VARCHAR(255) NOT NULL COMMENT 'Default Properties' AFTER `locked`;


ALTER TABLE `{PREFIX}site_snippets` ADD COLUMN `moduleguid` VARCHAR(32) NOT NULL COMMENT 'GUID of module from which to import shared parameters' AFTER `properties`


ALTER TABLE `{PREFIX}site_templates` ADD COLUMN `editor_type` INTEGER NOT NULL DEFAULT '0' COMMENT '0-plain text,1-rich text,2-code editor' AFTER `description`
, ADD COLUMN `category` INTEGER NOT NULL DEFAULT '0' COMMENT 'category id' AFTER `editor_type`
, ADD COLUMN `icon` VARCHAR(255) NOT NULL COMMENT 'url to icon file' AFTER `category`
, ADD COLUMN `template_type` INTEGER NOT NULL DEFAULT '0' COMMENT '0-page,1-content' AFTER `icon`;


ALTER TABLE `{PREFIX}document_groups` DROP INDEX `indx_doc_groups`;


ALTER TABLE `{PREFIX}document_groups` ADD INDEX `document` (`document`);


ALTER TABLE `{PREFIX}document_groups` ADD INDEX `document_group` (`document_group`);


ALTER TABLE `{PREFIX}system_settings` MODIFY COLUMN `setting_value` TEXT NOT NULL;


ALTER TABLE `{PREFIX}site_plugins` MODIFY COLUMN `properties` TEXT;


ALTER TABLE `{PREFIX}system_eventnames` ADD COLUMN `groupname` VARCHAR(20) NOT NULL AFTER `service`;


ALTER TABLE `{PREFIX}documentgroup_names` 
 ADD COLUMN `private_memgroup` TINYINT DEFAULT '0' COMMENT 'determine whether the document group is private to manager users' AFTER `name`,
 ADD COLUMN `private_webgroup` TINYINT DEFAULT '0' COMMENT 'determines whether the document is private to web users' AFTER `private_memgroup`;


ALTER TABLE `{PREFIX}user_roles` ADD COLUMN `bk_manager` int(1) NOT NULL DEFAULT '0' AFTER `access_permissions`;


ALTER TABLE `{PREFIX}user_roles` 
 ADD COLUMN `new_plugin` int(1) NOT NULL DEFAULT '0',
 ADD COLUMN `edit_plugin` int(1) NOT NULL DEFAULT '0',
 ADD COLUMN `save_plugin` int(1) NOT NULL DEFAULT '0',
 ADD COLUMN `delete_plugin` int(1) NOT NULL DEFAULT '0',
 ADD COLUMN `view_unpublished` int(1) NOT NULL DEFAULT '0';


ALTER TABLE `{PREFIX}user_roles` 
 ADD COLUMN `new_module` int(1) NOT NULL DEFAULT '0',
 ADD COLUMN `edit_module` int(1) NOT NULL DEFAULT '0',
 ADD COLUMN `save_module` int(1) NOT NULL DEFAULT '0',
 ADD COLUMN `exec_module` int(1) NOT NULL DEFAULT '0',
 ADD COLUMN `delete_module` int(1) NOT NULL DEFAULT '0',
 ADD COLUMN `view_eventlog` int(1) NOT NULL DEFAULT '0',
 ADD COLUMN `delete_eventlog` int(1) NOT NULL DEFAULT '0',
 ADD COLUMN `manage_metatags` int(1) NOT NULL DEFAULT '0',
 ADD COLUMN `edit_doc_metatags` int(1) NOT NULL DEFAULT '0',
 ADD COLUMN `new_web_user` int(1) NOT NULL DEFAULT '0',
 ADD COLUMN `edit_web_user` int(1) NOT NULL DEFAULT '0',
 ADD COLUMN `save_web_user` int(1) NOT NULL DEFAULT '0',
 ADD COLUMN `delete_web_user` int(1) NOT NULL DEFAULT '0',
 ADD COLUMN `web_access_permissions` int(1) NOT NULL DEFAULT '0';


ALTER TABLE `{PREFIX}user_attributes` ADD COLUMN `dob` INTEGER(10) NOT NULL DEFAULT 0 AFTER `sessionid`
, ADD COLUMN `gender` INTEGER(1) NOT NULL DEFAULT '0' COMMENT '0 - unknown, 1 - Male 2 - female' AFTER `dob`
, ADD COLUMN `country` VARCHAR(5) NOT NULL AFTER `gender`
, ADD COLUMN `state` VARCHAR(5) NOT NULL AFTER `country`
, ADD COLUMN `zip` VARCHAR(5) NOT NULL AFTER `state`
, ADD COLUMN `fax` VARCHAR(100) NOT NULL AFTER `zip`
, ADD COLUMN `blockedafter` INTEGER(11) NOT NULL DEFAULT '0' AFTER `blockeduntil`
, ADD COLUMN `photo` VARCHAR(255) NOT NULL COMMENT 'link to photo' AFTER `fax`
, ADD COLUMN `comment` VARCHAR(255) NOT NULL COMMENT 'short comment' AFTER `photo`;


ALTER TABLE `{PREFIX}web_users` MODIFY COLUMN `username` VARCHAR(100) NOT NULL;


ALTER TABLE `{PREFIX}web_user_attributes` ADD COLUMN `dob` INTEGER(10) NOT NULL DEFAULT 0 AFTER `sessionid`
, ADD COLUMN `gender` INTEGER(1) NOT NULL DEFAULT '0' COMMENT '0 - unknown, 1 - Male 2 - female' AFTER `dob`
, ADD COLUMN `country` VARCHAR(5) NOT NULL AFTER `gender`
, ADD COLUMN `state` VARCHAR(5) NOT NULL AFTER `country`
, ADD COLUMN `zip` VARCHAR(5) NOT NULL AFTER `state`
, ADD COLUMN `fax` VARCHAR(100) NOT NULL AFTER `zip`
, ADD COLUMN `blockedafter` INTEGER(11) NOT NULL DEFAULT 0 AFTER `blockeduntil`
, ADD COLUMN `photo` VARCHAR(255) NOT NULL COMMENT 'link to photo' AFTER `fax`
, ADD COLUMN `comment` VARCHAR(255) NOT NULL COMMENT 'short comment' AFTER `photo`;


ALTER TABLE `{PREFIX}user_roles` ADD COLUMN `view_unpublished` int(1) NOT NULL DEFAULT '0' AFTER `web_access_permissions`;

ALTER TABLE `{PREFIX}site_tmplvar_templates` DROP INDEX `idx_tmplvarid`
, DROP INDEX `idx_templateid`
, ADD PRIMARY KEY ( `tmplvarid` , `templateid` )


# Insert / Replace system records
#::::::::::::::::::::::::::::::::::::::::::::::::::::::::::


# non-upgrade-able[[ - This block of code will not be executed during upgrades


# Default Site Template


REPLACE INTO `{PREFIX}site_templates` 
(id, templatename, description, editor_type, category, icon, template_type, content, locked) VALUES ('3','Minimal Template','Default minimal empty template','0','0','','0','<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n\r\n<head>\r\n	<title>[(site_name)] | [*pagetitle*]</title>\r\n\r\n	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\r\n\r\n	<link rel=\"stylesheet\" href=\"assets/templates/default/site.css\" type=\"text/css\" media=\"screen\" />\r\n        <link rel=\"alternate\" type=\"application/rss+xml\" title=\"RSS 2.0\" href=\"[(site_url)][~11~]\" />\r\n        <base href=\"[(site_url)]\" />\r\n\r\n<!--[if lte IE 6]>\r\n\r\n  <style type=\"text/css\" media=\"screen, tv, projection\">\r\n        body { behavior: url(assets/js/csshover.htc); } /* hover:anything support */\r\n        #content { margin-left: 22px; } /* to avoid the BMH */\r\n        a,  a:link { border-bottom-style: solid } /* becuase IE just doesn\'t dot */\r\n  </style>\r\n\r\n  <script type=\"text/javascript\" src=\"assets/js/sleight.js\"></script>\r\n\r\n<![endif]-->\r\n\r\n        <script src=\"manager/media/script/scriptaculous/prototype.js\" type=\"text/javascript\"></script>\r\n        <script src=\"manager/media/script/scriptaculous/scriptaculous.js\" type=\"text/javascript\"></script>\r\n\r\n</head>\r\n<body>\r\n\r\n<div id=\"page\">\r\n\r\n\r\n<div id=\"header\">\r\n	<div id=\"search\"><a name=\"search\"></a>\r\n		[!FlexSearchForm? &FSF_showResults=`0` &FSF_landing=`8`!]\r\n	</div>\r\n	<h1><a href=\"[~[(site_start)]~]\" title=\"[(site_name)]\">[(site_name)]</a></h1>\r\n</div>\r\n<!-- close #header -->\r\n\r\n	<div id=\"content\">\r\n\r\n		<div class=\"post\">\r\n			<h4 class=\"date-header\">[!DateTime? &format=`d-M-Y h:i A`!]</h4>\r\n			<h3 id=\"post-\">[*longtitle*]</h3>\r\n				[*#content*]\r\n		</div>\r\n		<!-- close .post (main column content) -->\r\n\r\n	</div>\r\n	<!-- close #content -->\r\n\r\n	<div id=\"sidebar\">\r\n		<ul>\r\n			<li id=\"dropmenu\"><a name=\"dropmenu\"></a><h2>Pages:</h2>\r\n			[!DropMenu!]\r\n			</li>\r\n\r\n			<li><h2>Meta:</h2>\r\n				<ul>\r\n				    <li><a href=\"http://validator.w3.org/check/referer\" title=\"This page validates as XHTML 1.0 Transitional\">Valid <abbr title=\"eXtensible HyperText Markup Language\">XHTML</abbr></a></li>\r\n                	<li><a href=\"http://jigsaw.w3.org/css-validator/check/referer\" title=\"This page uses valid Cascading Stylesheets\" rel=\"external\">Valid <abbr title=\"W3C Cascading Stylesheets\">css</abbr></a></li>\r\n				    <li><a href=\"http://modxcms.com/\" title=\"Powered by MODx, Do more with less.\">MOD<strong>x</strong></a></li>\r\n		        </ul>\r\n			</li>\r\n\r\n		</ul>\r\n	</div>\r\n	<!-- close #sidebar -->\r\n\r\n<div class=\"clear\">&nbsp;</div>\r\n\r\n<div id=\"footer\">\r\n	<p>\r\n		[(site_name)] is powered by\r\n		<a href=\"http://modxcms.com/\" title=\"Powered by MODx\"><strong>MOD</strong>x Content Management System</a>\r\n		<br /><a href=\"[~11~]\" title=\"Link to our Blog RSS Feeds\">Blog Entries (RSS)</a><br />\r\nMySQL: [^qt^], [^q^] request(s), PHP: [^p^], total: [^t^], document retrieved from [^s^].\r\n	</p>\r\n</div>\r\n<!-- close #footer -->\r\n\r\n</div>\r\n<!-- close #page -->\r\n\r\n</body>\r\n</html>','0');


# Default Site Documents


REPLACE INTO `{PREFIX}site_content` VALUES (1,'document','text/html','MODx CMS Install Success','Welcome to the MODx Content Management System','','minimal-base',1,0,0,0,0,'','<h3>Install Successful!</h3>\r\n<p>You have successfully installed and configured MODx. We hope you find this site an adequate starting configuration for many small business, organization or personal websites; just change out the template and add your own content and snippets, and you\'ll be good to go! </p>\r\n\r\n<h3>Getting Help</h3>\r\n<p>The <a href=\"http://modxcms.com/modx-team.html\" target=\"_blank\">team behind MODx</a> strives to contantly add to and refine the documentation to help you get up to speed with MODx:</p>\r\n<ul>\r\n    <li>For basic instructions on integrating custom templates into MODx, please see the <a target=\"_blank\" href=\"http://modxcms.com/designer-guide.html\">Designer\'s Guide</a>. </li>\r\n    <li>For an introduction to working in MODx from the content editors perspectve, see the <a target=\"_blank\" href=\"http://modxcms.com/editor-guide.html\">Content Editor\'s Guide</a>. </li>\r\n    <li>For a detailed overview of the backend &quot;manager&quot; and setting up Users and Groups, please peruse the <a target=\"_blank\" href=\"http://modxcms.com/developers-guide.html\">Administration Guide</a>.</li>\r\n    <li>For developers, architecture and API documentation can be found in the <a target=\"_blank\" href=\"http://modxcms.com/administration-guide.html\">Developer\'s Guide</a>.</li>\r\n    <li>And if someone has installed this site for you, but you\'re curious as to the steps they went through, please see the <a target=\"_blank\" href=\"http://modxcms.com/getting-started.html\">Getting Started Guide</a>.</li>\r\n</ul>\r\n\r\n<p>And don\'t forget, you can always learn and ask questions at the <a href=\"http://www.modxcms.com/forums\" target=\"_blank\">MODx forums</a>. \r\n',1,3,0,1,1,1,1130304721,1,1130304927,0,0,0,'Base Install',0,0,0,0,0,0,0);


REPLACE INTO `{PREFIX}manager_users` 
(id, username, password)VALUES 
(1, '{ADMIN}', MD5('{ADMINPASS}'));


REPLACE INTO `{PREFIX}user_attributes` 
(id, internalKey, fullname, role, email, phone, mobilephone, blocked, blockeduntil, blockedafter, logincount, lastlogin, thislogin, failedlogincount, sessionid, dob, gender, country, state, zip, fax, photo, comment) VALUES 
(1, 1, 'Built-in Administration account', 1, 'Your email goes here', '0', '0', 0, 0, 0, 0, 0, 0, 0, '', 0, '', '', '', '', '', '', '');


# Default Site Settings


REPLACE INTO `{PREFIX}system_settings` 
(setting_name, setting_value) VALUES 
('manager_theme','MODx'),
('settings_version','0.9.1'),
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
('captcha_words','MODx,Access,Better,BitCode,Cache,Desc,Design,Excell,Enjoy,URLs,TechView,Gerald,Griff,Humphrey,Holiday,Intel,Integration,Joystick,Join(),Tattoo,Genetic,Light,Likeness,Marit,Maaike,Niche,Netherlands,Ordinance,Oscillo,Parser,Phusion,Query,Question,Regalia,Righteous,Snippet,Sentinel,Template,Thespian,Unity,Enterprise,Verily,Veri,Website,WideWeb,Yap,Yellow,Zebra,Zygote'),
('emailsender','you@yourdomain.com'),
('emailsubject','Your login details'),
('signupemail_message','Hello [+uid+] \r\n\r\nHere are your login details for [+sname+] Content Manager:\r\n\r\nUsername: [+uid+]\r\nPassword: [+pwd+]\r\n\r\nOnce you log into the Content Manager, you can change your password.\r\n\r\nRegards,\r\nSite Administrator'),
('websignupemail_message','Hello [+uid+] \r\n\r\nHere are your login details for [+sname+]:\r\n\r\nUsername: [+uid+]\r\nPassword: [+pwd+]\r\n\r\nOnce you log into [+sname+], you can change your password.\r\n\r\nRegards,\r\nSite Administrator'),
('webpwdreminder_message','Hello [+uid+]\r\n\r\nTo active you new password click the following link:\r\n\r\n[+surl+]\r\n\r\nIf successful you can use the following password to login:\r\n\r\nPassword:[+pwd+]\r\n\r\nIf you did not request this email then please ignore it.\r\n\r\nRegards,\r\nSite Administrator'),
('number_of_logs','100'),
('number_of_messages','30'),
('number_of_results','20'),
('use_editor','1'),
('use_browser','1'),
('rb_base_dir','{IMAGEPATH}'),
('rb_base_url','{IMAGEURL}'),
('which_editor','FCKEditor'),
('fck_editor_toolbar','standard'),
('fck_editor_autolang','0'),
('editor_css_path',''),
('editor_css_selectors',''),
('strip_image_paths','0'),
('upload_files','jpg,gif,png,ico,txt,php,html,htm,xml,js,css,cache,zip,gz,rar,z,tgz,tar,htaccess,bmp,mp3,mp4,aac,wav,au,wmv,avi,mpg,mpeg,pdf,psd,doc,xls,txt'),
('upload_maxsize','1048576'),
('show_preview','1'),
('filemanager_path','{FILEMANAGERPATH}'),
('theme_refresher',''),
('manager_layout','1'),
('custom_contenttype','text/css,text/html,text/javascript,text/plain,text/xml');


# ]]non-upgrade-able


REPLACE INTO `{PREFIX}user_roles` 
(id, name, description, frames, home, view_document, new_document, save_document, delete_document, action_ok, logout, help, messages, new_user, edit_user, logs, edit_parser, save_parser, edit_template, settings, credits, new_template, save_template, delete_template, edit_snippet, new_snippet, save_snippet, delete_snippet, empty_cache, edit_document, change_password, error_dialog, about, file_manager, save_user, delete_user, save_password, edit_role, save_role, delete_role, new_role, access_permissions, bk_manager, new_plugin, edit_plugin, save_plugin, delete_plugin, new_module, edit_module, save_module, exec_module, delete_module, view_eventlog, delete_eventlog, manage_metatags, edit_doc_metatags, new_web_user, edit_web_user, save_web_user, delete_web_user, web_access_permissions, view_unpublished) VALUES 
(1, 'Administrator', 'Site administrators have full access to all functions', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);


# 1 - "Parser Service Events", 2 -  "Manager Access Events", 3 - "Web Access Service Events", 4 - "Cache Service Events", 5 - "Template Service Events", 6 - Custom Events


REPLACE INTO `{PREFIX}system_eventnames` 
(id,name,service,groupname) VALUES 
('1','OnDocPublished','5',''), 
('2','OnDocUnPublished','5',''),
('3','OnWebPagePrerender','5',''),
('4','OnWebLogin','3',''),
('5','OnBeforeWebLogout','3',''),
('6','OnWebLogout','3',''),
('7','OnWebSaveUser','3',''),
('8','OnWebDeleteUser','3',''),
('9','OnWebChangePassword ','3',''),
('10','OnWebCreateGroup','3',''),
('11','OnManagerLogin','2',''),
('12','OnBeforeManagerLogout','2',''),
('13','OnManagerLogout','2',''),
('14','OnManagerSaveUser ','2',''),
('15','OnManagerDeleteUser','2',''),
('16','OnManagerChangePassword ','2',''),
('17','OnManagerCreateGroup','2',''),
('18','OnBeforeCacheUpdate','4',''),
('19','OnCacheUpdate','4',''),
('20','OnLoadWebPageCache','4',''),
('21','OnBeforeSaveWebPageCache','4',''),
('22','OnChunkFormPrerender','1','Chunks'),
('23','OnChunkFormRender','1','Chunks'),
('24','OnBeforeChunkFormSave','1','Chunks'),
('25','OnChunkFormSave','1','Chunks'),
('26','OnBeforeChunkFormDelete','1','Chunks'),
('27','OnChunkFormDelete','1','Chunks'),
('28','OnDocFormPrerender','1','Documents'),
('29','OnDocFormRender','1','Documents'),
('30','OnBeforeDocFormSave','1','Documents'),
('31','OnDocFormSave','1','Documents'),
('32','OnBeforeDocFormDelete','1','Documents'),
('33','OnDocFormDelete','1','Documents'),
('34','OnPluginFormPrerender','1','Plugins'),
('35','OnPluginFormRender','1','Plugins'),
('36','OnBeforePluginFormSave','1','Plugins'),
('37','OnPluginFormSave','1','Plugins'),
('38','OnBeforePluginFormDelete','1','Plugins'),
('39','OnPluginFormDelete','1','Plugins'),
('40','OnSnipFormPrerender','1','Snippets'),
('41','OnSnipFormRender','1','Snippets'),
('42','OnBeforeSnipFormSave','1','Snippets'),
('43','OnSnipFormSave','1','Snippets'),
('44','OnBeforeSnipFormDelete','1','Snippets'),
('45','OnSnipFormDelete','1','Snippets'),
('46','OnTempFormPrerender','1','Templates'),
('47','OnTempFormRender','1','Templates'),
('48','OnBeforeTempFormSave','1','Templates'),
('49','OnTempFormSave','1','Templates'),
('50','OnBeforeTempFormDelete','1','Templates'),
('51','OnTempFormDelete','1','Templates'),
('52','OnTVFormPrerender','1','Template Variables'),
('53','OnTVFormRender','1','Template Variables'),
('54','OnBeforeTVFormSave','1','Template Variables'),
('55','OnTVFormSave','1','Template Variables'),
('56','OnBeforeTVFormDelete','1','Template Variables'),
('57','OnTVFormDelete','1','Template Variables'),
('58','OnUserFormPrerender','1','Users'),
('59','OnUserFormRender','1','Users'),
('60','OnBeforeUserFormSave','1','Users'),
('61','OnUserFormSave','1','Users'),
('62','OnBeforeUserFormDelete','1','Users'),
('63','OnUserFormDelete','1','Users'),
('64','OnWUsrFormPrerender','1','Web Users'),
('65','OnWUsrFormRender','1','Web Users'),
('66','OnBeforeWUsrFormSave','1','Web Users'),
('67','OnWUsrFormSave','1','Web Users'),
('68','OnBeforeWUsrFormDelete','1','Web Users'),
('69','OnWUsrFormDelete','1','Web Users'),
('70','OnSiteRefresh','1',''),
('71','OnFileManagerUpload','1',''),
('72','OnModFormPrerender','1','Modules'),
('73','OnModFormRender','1','Modules'),
('74','OnBeforeModFormDelete','1','Modules'),
('75','OnModFormDelete','1','Modules'),
('76','OnBeforeModFormSave','1','Modules'),
('77','OnModFormSave','1','Modules'),
('78','OnBeforeWebLogin','3',''),
('79','OnWebAuthentication','3',''),
('80','OnBeforeManagerLogin','2',''),
('81','OnManagerAuthentication','2',''),
('82','OnSiteSettingsRender','1','System Settings'),
('83','OnFriendlyURLSettingsRender','1','System Settings'),
('84','OnUserSettingsRender','1','System Settings'),
('85','OnInterfaceSettingsRender','1','System Settings'),
('86','OnMiscSettingsRender','1','System Settings'),
('87','OnRichTextEditorRegister','1','RichText Editor'),
('88','OnRichTextEditorInit','1','RichText Editor'),
('89','OnManagerPageInit','2',''),
('90','OnWebPageInit','5',''),
('91','OnLoadWebDocument','5',''),
('92','OnParseDocument','5',''),
('200','OnCreateDocGroup','1','Documents'),
('999','OnPageUnauthorized','1',''),
('1000','OnPageNotFound','1','');


# ^ I don't think we need more than 1000 built-in events. Custom events will start at 1001


# Update System Tables 
#::::::::::::::::::::::::::::::::::::::::::::::::::::::::::


UPDATE `{PREFIX}user_roles` SET 
	bk_manager=1,
	new_plugin=1,
	edit_plugin=1,
	save_plugin=1,
	delete_plugin=1,
	new_module=1,
	edit_module=1,
	save_module=1,
	delete_module=1,
	exec_module=1,
	view_eventlog = 1,
	delete_eventlog = 1,
	manage_metatags = 1,
	edit_doc_metatags = 1,
	new_web_user = 1,
	edit_web_user = 1,
	save_web_user = 1,
	delete_web_user = 1,
	web_access_permissions = 1
	WHERE  id=1;

