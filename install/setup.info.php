<?php
#:: MODx Installer Setup file 
#:::::::::::::::::::::::::::::::::::::::::

	$moduleName = "MODx";
	$moduleVersion = " 0.9.0";
	$moduleSQLBaseFile = "setup.sql";
	$moduleSQLDataFile = "setup.data.sql";
	$moduleWhatsNewFile = "setup.whatsnew.html";
	$moduleWhatsNewTitle = "What's New";

	# setup chunks template files - array : name, description, type - 0:file or 1:content, file or content
	$mc = &$moduleChunks;
	$mc[] = array("WebLoginSideBar","WebLogin Sidebar Template",0,"./chunk.weblogin.sidebar.tpl");

	# setup snippets template files - array : name, description, type - 0:file or 1:content, file or content,properties
	$ms = &$moduleSnippets;
	$ms[] = array("DateTime","Outputs the current date and time to the page.",0,"$setupPath/snippet.datetime.tpl","");
	$ms[] = array("GetStats","Fetches the visitor statistics totals from the database",0,"$setupPath/snippet.getstats.tpl","");
	$ms[] = array("ContactForm","Simple, configurable XHTML-validating contact form for delivery to email accounts.",0,"$setupPath/snippet.contactform.tpl","");
	$ms[] = array("DropMenu","Robust and configurable XHTML-validating menu and site map builder. Output an unordered list.",0,"$setupPath/snippet.dropmenu.tpl","");
	$ms[] = array("FlexSearchForm","Robust site search with like and partial matching.",0,"$setupPath/snippet.flexsearchform.tpl","");
	$ms[] = array("NewsFeed","Enable RSS2 news feed from your website.",0,"$setupPath/snippet.newsfeed.tpl","");
	$ms[] = array("NewsListing","Displays news.",0,"$setupPath/snippet.newslisting.tpl","");
	$ms[] = array("NewsPublisher","Publish news articles directly from the web.",0,"$setupPath/snippet.newspublisher.tpl","");
	$ms[] = array("UserComments","Add user comments to documents.",0,"$setupPath/snippet.usercomments.tpl","");
	$ms[] = array("PoweredBy","A little link to MODx",0,"$setupPath/snippet.poweredby.tpl","");
	$ms[] = array("PageTrail","Outputs the page trail, based on Bill Wilson's script",0,"$setupPath/snippet.pagetrail.tpl","&sep=Separator;string; &style=Style;string; &class=Class;string;");
	$ms[] = array("WebLogin","Web User Login Snippet",0,"$setupPath/snippet.weblogin.tpl","&loginhomeid=Login Home Id;string; &logouthomeid=Logout Home Id;string; &logintext=Login Button Text;string; &logouttext=Logout Button Text;string; &tpl=Template;string;");
	$ms[] = array("WebChangePwd","Web User Change Password Snippet",0,"$setupPath/snippet.webchangepwd.tpl","&tpl=Template;string;");
	$ms[] = array("WebSignup","Web User Signup Snippet",0,"$setupPath/snippet.websignup.tpl","&tpl=Template;string;");

	# setup plugins template files - array : name, description, type - 0:file or 1:content, file or content,properties
	$mp = &$modulePlugins;
	$mp[] = array("FCKEditor","RichText Editor Plugin",0,"$setupPath/plugin.fckeditor.tpl","&webset=Web Toolbar Set;list;basic,standard,advanced,custom;custom &webcustom=Custom Web Toolbar;textarea;['Bold','Italic','Underline','-','Link','Unlink']","OnRichTextEditorRegister,OnRichTextEditorInit,OnInterfaceSettingsRender");
	$mp[] = array("QuickEdit","Renders QuickEdit links in the frontend",0,"$setupPath/quickedit.plugin.tpl","","OnParseDocument,OnWebPagePrerender","f888bac76e1537ca8e0cbec772b4624a");

	# setup modules - array : name, description, type - 0:file or 1:content, file or content,properties, guid,enable_sharedparams
	$mm = &$moduleModules;
	$mm[] = array("QuickEdit","Renders QuickEdit links in the frontend",0,"$setupPath/quickedit.module.tpl","&mod_path=Module Path (from site root);string;assets/modules/quick_edit &show_manager_link=Show Manager Link;int;1 &show_help_link=Show Help Link;int;1","f888bac76e1537ca8e0cbec772b4624a",1);

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
