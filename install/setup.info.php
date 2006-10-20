<?php
#:: MODx Installer Setup file 
#:::::::::::::::::::::::::::::::::::::::::

	$moduleName = "MODx";
	$moduleVersion = " 0.9.5 beta";
	$moduleSQLBaseFile = "setup.sql";
	$moduleSQLDataFile = "setup.data.sql";
	$moduleSQLUpdateFile = "setup.updates.sql";
	$moduleWhatsNewFile = "setup.whatsnew.html";
	$moduleWhatsNewTitle = "What's New";

	# setup chunks template files - array : name, description, type - 0:file or 1:content, file or content
	$mc = &$moduleChunks;
	$mc[] = array("WebLoginSideBar","WebLogin Sidebar Template",0,"./chunk.weblogin.sidebar.tpl");

	# setup snippets template files - array : name, description, type - 0:file or 1:content, file or content,properties
	$ms = &$moduleSnippets;
	$ms[] = array("AjaxSearch","<strong>1.1</strong> Ajax enabled search form with results highlighting.",0,"$setupPath/snippet.ajaxSearch.tpl","");
	$ms[] = array("Breadcrumbs","<strong>0.9d</strong> Configurable breadcrumb page trail navigation.",0,"$setupPath/snippet.breadcrumbs.tpl","");
	$ms[] = array("Ditto","<strong>1.0.2</strong> Summarizes and lists pages to create blogs, catalogs, PR archives, bio listings and more.",0,"$setupPath/snippet.ditto.tpl","");
	$ms[] = array("eForm","<strong>1.4</strong> Robust form parser/processor with validation, multiple sending options, chunk/page support for forms and reports, and file uploads.",0,"$setupPath/snippet.eform.tpl","");
	$ms[] = array("FirstChildRedirect","<strong>1.0</strong> Automatically redirects to the first child of a folder document.",0,"$setupPath/snippet.firstchild.tpl","");
	$ms[] = array("Jot","<strong>1.0 beta8</strong> User comments with moderation and email subscription.",0,"$setupPath/snippet.jot.tpl","");
	$ms[] = array("ListIndexer","<strong>1.0</strong> Shows the most recent documents, highly flexible.",0,"$setupPath/snippet.listindexer.tpl","");
	$ms[] = array("MemberCheck","<strong>1.0</strong> Selectively show chunks based on logged in Web User' group memberships.",0,"$setupPath/snippet.membercheck.tpl","");
	$ms[] = array("NewsPublisher","<strong>1.4</strong> Publish news articles directly from the web.",0,"$setupPath/snippet.newspublisher.tpl","");
	$ms[] = array("Personalize","<strong>2.0</strong> Basic personalization for web users.",0,"$setupPath/snippet.personalize.tpl","");
	$ms[] = array("PoweredBy","A little link to MODx",0,"$setupPath/snippet.poweredby.tpl","");
	$ms[] = array("Wayfinder","<strong>beta3</strong> Completely template-driven menu builder that's simple and fast to configure.",0,"$setupPath/snippet.wayfinder.tpl","");
	$ms[] = array("UltimateParent","<strong>1.2</strong> - Gets the highest parent that isn't 0.",0,"$setupPath/snippet.ultparent.tpl","");
	$ms[] = array("WebChangePwd","<strong>1.0</strong> Web User Change Password Snippet.",0,"$setupPath/snippet.webchangepwd.tpl","&tpl=Template;string;");
	$ms[] = array("WebLogin","<strong>1.0</strong> Web User Login Snippet.",0,"$setupPath/snippet.weblogin.tpl","&loginhomeid=Login Home Id;string; &logouthomeid=Logout Home Id;string; &logintext=Login Button Text;string; &logouttext=Logout Button Text;string; &tpl=Template;string;");
	$ms[] = array("WebSignup","<strong>1.1</strong> Web User Signup Snippet.",0,"$setupPath/snippet.websignup.tpl","&tpl=Template;string;");

	# setup plugins template files - array : name, description, type - 0:file or 1:content, file or content,properties
	$mp = &$modulePlugins;
	$mp[] = array("Bottom Button Bar","Adds a set of buttons to the bottom of all manager pages",0,"$setupPath/plugin.bottombuttonbar.tpl","","OnChunkFormRender, OnDocFormRender, OnModFormRender, OnPluginFormRender, OnSnipFormRender, OnTVFormRender, OnTempFormRender, OnUserFormRender, OnWUsrFormRender");
	$mp[] = array("Forgot Manager Login","Resets your manager login when you forget your password. ",0,"$setupPath/plugin.ForgotManagerPassword.tpl","","OnBeforeManagerLogin,OnManagerAuthentication,OnManagerLoginFormRender");
	$mp[] = array("Inherit Parent Template","New docs automatically select template of parent folder",0,"$setupPath/plugin.inherit-parent-tpl.tpl","","OnDocFormPrerender");
	$mp[] = array("QuickEdit","Front-end Content Editor.",0,"$setupPath/quickedit.plugin.tpl","","OnParseDocument,OnWebPagePrerender","f888bac76e1537ca8e0cbec772b4624a");
	$mp[] = array("TinyMCE","<strong>2.0.6.1b:</strong> TinyMCE RichText Editor Plugin",0,"$setupPath/plugin.tinymce.tpl","&tinyPlugins=Plugins;text;style,layer,table,advhr,advimage,advlink,emotions,insertdatetime,preview,flash,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable &tinyButtons1=Button Row 1;text;newdocument,separator,bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,styleselect,formatselect,fontselect,fontsizeselect &tinyButtons2=Button Row 2;text;cut,copy,paste,separator,search,replace,separator,pastetext,pasteword,selectall,separator,bullist,numlist,separator,outdent,indent,separator,undo,redo,separator,link,unlink,anchor,image,separator,cleanup,help,code,separator,insertdate,inserttime,preview,separator,forecolor,backcolor &tinyButtons3=Button Row 3;text;tablecontrols,separator,hr,removeformat,visualaid,separator,sub,sup,separator,charmap,emotions,iespell,flash,advhr,separator,print,separator,ltr,rtl,separator,fullscreen &tinyButtons4=Button Row 4;text;insertlayer,moveforward,movebackward,absolute,separator,styleprops &disabledButtons=Disabled Buttons;text; &tinyFormats=Block Formats;text;p,div,address,pre,h1,h2,h3,h4,h5,h6 &webtheme=Web Theme;list;simple,advanced,full;simple &entity_encoding=Entity Encoding;list;named,numeric,raw;named &entities=Entities;text;","OnRichTextEditorRegister,OnRichTextEditorInit,OnInterfaceSettingsRender");
    $mp[] = array("Search Highlighting","Show search terms highlighted on page linked from search results. (Requires AjaxSearch snippet)",0,"$setupPath/plugin.searchhighlight.tpl","","OnWebPagePrerender");
    $mp[] = array("DisableCache","Disables the cache for every page (needed for template switcher demo).",0,"$setupPath/plugin.disablecache.tpl","","OnLoadWebDocument");
    $mp[] = array("TemplateSwitcher","Changes templates used on all pages in your site.",0,"$setupPath/plugin.templateswitcher.tpl","","OnLoadWebDocument");

	# setup modules - array : name, description, type - 0:file or 1:content, file or content,properties, guid,enable_sharedparams
	$mm = &$moduleModules;
	$mm[] = array("Doc Manager","Quickly perform bulk updates to the Documents in your site including templates, publishing details, and permissions.",0,"$setupPath/module.docmanager.tpl","","",1);
	$mm[] = array("QuickEdit","Renders QuickEdit links in the frontend",0,"$setupPath/quickedit.module.tpl","&mod_path=Module Path (from site root);string;assets/modules/quick_edit &show_manager_link=Show Manager Link;int;1 &show_help_link=Show Help Link;int;1 &editable=Editable Fields;string;pagetitle,longtitle,description,content,alias,introtext,menutitle,published,hidemenu,menuindex,searchable,cacheable,template","f888bac76e1537ca8e0cbec772b4624a",1);

	# setup callback function
	$callBackFnc = "clean_up";
	
	function clean_up($sqlParser) {
		$ids = array();
		$mysqlVerOk = -1;

		if(function_exists("mysql_get_server_info")) {
			$mysqlVerOk = (version_compare(mysql_get_server_info(),"4.0.2")>=0);
		}	
		
		// secure web documents - privateweb 
		mysql_query("UPDATE ".$sqlParser->prefix."site_content SET privateweb = 0 WHERE privateweb = 1",$sqlParser->conn);
		$sql =  "SELECT DISTINCT sc.id 
				 FROM ".$sqlParser->prefix."site_content sc
				 LEFT JOIN ".$sqlParser->prefix."document_groups dg ON dg.document = sc.id
				 LEFT JOIN ".$sqlParser->prefix."webgroup_access wga ON wga.documentgroup = dg.document_group
				 WHERE wga.id>0";
		$ds = mysql_query($sql,$sqlParser->conn);
		if(!$ds) {
			echo "An error occurred while executing a query: ".mysql_error();
		}
		else {
			while($r = mysql_fetch_assoc($ds)) $ids[]=$r["id"];
			if(count($ids)>0) {
				mysql_query("UPDATE ".$sqlParser->prefix."site_content SET privateweb = 1 WHERE id IN (".implode(", ",$ids).")");	
				unset($ids);
			}
		}
		
		// secure manager documents privatemgr
		mysql_query("UPDATE ".$sqlParser->prefix."site_content SET privatemgr = 0 WHERE privatemgr = 1");
		$sql =  "SELECT DISTINCT sc.id 
				 FROM ".$sqlParser->prefix."site_content sc
				 LEFT JOIN ".$sqlParser->prefix."document_groups dg ON dg.document = sc.id
				 LEFT JOIN ".$sqlParser->prefix."membergroup_access mga ON mga.documentgroup = dg.document_group
				 WHERE mga.id>0";
		$ds = mysql_query($sql);
		if(!$ds) {
			echo "An error occurred while executing a query: ".mysql_error();
		}
		else {
			while($r = mysql_fetch_assoc($ds)) $ids[]=$r["id"];
			if(count($ids)>0) {
				mysql_query("UPDATE ".$sqlParser->prefix."site_content SET privatemgr = 1 WHERE id IN (".implode(", ",$ids).")");	
				unset($ids);
			}		
		}

		/**** Add Quick Plugin to Module ***/
		// get quick edit module id
		$ds = mysql_query("SELECT id FROM ".$sqlParser->prefix."site_modules WHERE name='QuickEdit'");
		if(!$ds) {
			echo "An error occurred while executing a query: ".mysql_error();
		}
		else {
			$row = mysql_fetch_assoc($ds);
			$moduleid=$row["id"];
		}		
		// get plugin id
		$ds = mysql_query("SELECT id FROM ".$sqlParser->prefix."site_plugins WHERE name='QuickEdit'");
		if(!$ds) {
			echo "An error occurred while executing a query: ".mysql_error();
		}
		else {
			$row = mysql_fetch_assoc($ds);
			$pluginid=$row["id"];
		}		
		// setup plugin as module dependency
		$ds = mysql_query("SELECT module FROM ".$sqlParser->prefix."site_module_depobj WHERE module='$moduleid' AND resource='$pluginid' AND type='30' LIMIT 1"); 
		if(!$ds) {
			echo "An error occurred while executing a query: ".mysql_error();
		}
		elseif (mysql_num_rows($ds)==0){
			mysql_query("INSERT INTO ".$sqlParser->prefix."site_module_depobj (module, resource, type) VALUES('$moduleid','$pluginid',30)");
		}
	}
?>
