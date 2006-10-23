# MODx Database Script for New/Upgrade Installations
#
# Each sql command is separated by double lines


#
# Update categories for table `site_snippets`
#


UPDATE `{PREFIX}site_snippets` SET `category` = '2' WHERE `name` IN ('MemberCheck', 'Personalize', 'WebChangePwd', 'WebLogin', 'WebSignup');


UPDATE `{PREFIX}site_snippets` SET `category` = '3' WHERE `name` IN ('Ditto', 'Jot', 'ListIndexer', 'NewsPublisher');


UPDATE `{PREFIX}site_snippets` SET `category` = '4' WHERE `name` IN ('Breadcrumbs','FirstChildRedirect','UltimateParent','Wayfinder');


UPDATE `{PREFIX}site_snippets` SET `category` = '5' WHERE `name` IN ('eForm');


UPDATE `{PREFIX}site_snippets` SET `category` = '10' WHERE `name` IN ('AjaxSearch');


#
# Update categories for table `site_plugins`
#


UPDATE `{PREFIX}site_plugins` SET `category` = '10' WHERE `name` IN ('Search Highlighting');


UPDATE `{PREFIX}site_plugins` SET `category` = '6' WHERE `name` IN ('Bottom Button Bar', 'Forgot Manager Login', 'Inherit Parent Template', 'TinyMCE', 'QuickEdit');


UPDATE `{PREFIX}site_plugins` SET `category` = '9' WHERE `name` IN ('DisableCache', 'TemplateSwitcher');


#
# Update categories for table `site_tmplvars`
#


UPDATE `{PREFIX}site_tmplvars` SET `category` = '9' WHERE `name` IN ('blogContent','loginName');
