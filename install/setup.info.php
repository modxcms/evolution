<?php
#:: MODx Installer Setup file 
#:::::::::::::::::::::::::::::::::::::::::

	$moduleName = "MODx";
	$moduleVersion = "2 TP3";
	$moduleSQLBaseFile = "setup.sql";
	$moduleSQLDataFile = "setup.data.sql";
	$moduleWhatsNewFile = "setup.whatsnew.html";
	$moduleWhatsNewTitle = "What's New";

	# setup chunks template files - array : name, description, type - 0:file or 1:content, file or content
	$mc = &$moduleChunks;
	$mc[] = array("WebLoginSideBar","WebLogin Sidebar Template",0,"./chunk.weblogin.sidebar.tpl");

	# setup snippets template files - array : name, description, type - 0:file or 1:content, file or content,properties
	$ms = &$moduleSnippets;
	$ms[] = array("DateTime","Makes a date and time... thingy.",0,"$setupPath/snippet.datetime.tpl","");
	$ms[] = array("DontLogPageHit","Stops the parser from logging the page hit",0,"$setupPath/snippet.dontlogpagehit.tpl","");
	$ms[] = array("FirstHit","Fetches the first ever recorded page impression from the database.",0,"$setupPath/snippet.firsthit.tpl","");
	$ms[] = array("GetKeywords","Fetches the keywords attached to the document.",0,"$setupPath/snippet.getkeywords.tpl","");
	$ms[] = array("GetStats","Fetches the visitor statistics totals from the database",0,"$setupPath/snippet.getstats.tpl","");
	$ms[] = array("MenuBuilder","Builds the site menu",0,"$setupPath/snippet.menubuilder.tpl","");
	$ms[] = array("NewsListing","Displays news.",0,"$setupPath/snippet.newslisting.tpl","");
	$ms[] = array("PoweredBy","A little link to MODx",0,"$setupPath/snippet.poweredby.tpl","");
	$ms[] = array("SearchForm","Snippet to search the site.",0,"$setupPath/snippet.searchform.tpl","");
	$ms[] = array("PageTrail","Outputs the page trail, based on Bill Wilson's script",0,"$setupPath/snippet.pagetrail.tpl","&sep=Separator;string; &style=Style;string; &class=Class;string;");
	$ms[] = array("WebLogin","Web User Login Snippet",0,"$setupPath/snippet.weblogin.tpl","&loginhomeid=Login Home Id;string; &logouthomeid=Logout Home Id;string; &logintext=Login Button Text;string; &logouttext=Logout Button Text;string; &tpl=Template;string;");
	$ms[] = array("WebChangePwd","Web User Change Password Snippet",0,"$setupPath/snippet.webchangepwd.tpl","&tpl=Template;string;");
	$ms[] = array("WebSignup","Web User Signup Snippet",0,"$setupPath/snippet.websignup.tpl","&tpl=Template;string;");

	# setup plugins template files - array : name, description, type - 0:file or 1:content, file or content,properties
	$mp = &$modulePlugins;
	$mp[] = array("FCKEditor","RichText Editor Plugin",0,"$setupPath/plugin.fckeditor.tpl","&webset=Web Toolbars;string;['Bold','Italic','Underline','-','Link','Unlink']","OnRichTextEditorRegister,OnRichTextEditorInit,OnInterfaceSettingsRender");

	# setup callback function
	$callBackFnc = "clean_up";
	
	function clean_up($sqlParser) {
		$ids = array();
		$mysqlVerOk = -1;

		if(function_exists("mysql_get_server_info")) {
			$mysqlVerOk = (version_compare(mysql_get_server_info(),"4.0.2")>=0);
		}	
		
		// secure web documents - privateweb 
		mysql_query("UPDATE ".$sqlParser->prefix."site_content SET privateweb = 0 WHERE privateweb = 1");
		$sql =  "SELECT DISTINCT sc.id 
				 FROM ".$sqlParser->prefix."site_content sc
				 LEFT JOIN ".$sqlParser->prefix."document_groups dg ON dg.document = sc.id
				 LEFT JOIN ".$sqlParser->prefix."webgroup_access wga ON wga.documentgroup = dg.document_group
				 WHERE wga.id>0";
		$ds= mysql_query($sql);
		if(!$ds) {
			echo "An error occured while executing a query: ".mysql_error();
		}
		else {
			while($r = mysql_fetch_row($ds)) $ids[]=$r["id"];
			if(count($ids)>0) {
				mysql_query("UPDATE ".$sqlParser->prefix."site_content SET privateweb = 1 WHERE id IN (".implode(", ",$ids).")");	
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
			echo "An error occured while executing a query: ".mysql_error();
		}
		else {
			while($r = mysql_fetch_row($ds)) $ids[]=$r["id"];
			if(count($ids)>0) {
				mysql_query("UPDATE ".$sqlParser->prefix."site_content SET privatemgr = 1 WHERE id IN (".implode(", ",$ids).")");	
			}		
		}
	}
?>