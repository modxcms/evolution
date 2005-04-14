<?php

/***************************************************************************
 Class Name: etomite
 Function: This class contains the main parsing functions
/***************************************************************************/

class etomite {
	var $db, $rs, $result, $sql, $table_prefix, $config, $debug,
		$documentIdentifier, $documentMethod, $documentGenerated, $documentContent, $tstart,
		$snippetParsePasses, $documentObject, $templateObject, $snippetObjects,
		$stopOnNotice, $executedQueries, $queryTime, $currentSnippet, $documentName,
		$aliases, $visitor, $entrypage, $documentListing, $dumpSnippets, $chunkCache, 
		$snippetCache, $contentTypes, $dumpSQL, $queryCode, $virtualDir;
	
	function etomite() {
		$this->dbConfig['host'] = $GLOBALS['database_server'];
		$this->dbConfig['dbase'] = $GLOBALS['dbase'];
		$this->dbConfig['user'] = $GLOBALS['database_user'];
		$this->dbConfig['pass'] = $GLOBALS['database_password'];
		$this->dbConfig['table_prefix'] = $GLOBALS['table_prefix'];
	}

	function checkCookie() {
		if(isset($_COOKIE['etomiteLoggingCookie'])) {
			$this->visitor = $_COOKIE['etomiteLoggingCookie'];
			if(isset($_SESSION['_logging_first_hit'])) {
				$this->entrypage = 0;
			} else {
				$this->entrypage = 1;
				$_SESSION['_logging_first_hit'] = 1;
			}
		} else {
			if (function_exists('posix_getpid')) {
			  $visitor = crc32(microtime().posix_getpid());
			} else {
			  $visitor = crc32(microtime().session_id());
			}
			$this->visitor = $visitor;
			$this->entrypage = 1;
			setcookie('etomiteLoggingCookie', $visitor, time()+(365*24*60*60), '/', '');
		}
	}


	function getMicroTime() { 
	   list($usec, $sec) = explode(" ", microtime()); 
	   return ((float)$usec + (float)$sec); 
	}

	function sendRedirect($url, $count_attempts=0, $type='') {
		if(empty($url)) {
			return false;
		} else {
			if($count_attempts==1) {
			// append the redirect count string to the url
				$currentNumberOfRedirects = isset($_REQUEST['err']) ? $_REQUEST['err'] : 0 ;
				if($currentNumberOfRedirects>3) {
					$this->messageQuit("Redirection attempt failed - please ensure the document you're trying to redirect to exists. Redirection URL: <i>$url</i>");
				} else {
					$currentNumberOfRedirects += 1;
					if(strpos($url, "?")>0) {
						$url .= "&err=$currentNumberOfRedirects";
					} else {
						$url .= "?err=$currentNumberOfRedirects";
					}
				}
			}
			if($type==REDIRECT_REFRESH) {
				$header = "Refresh: 0;URL=".$url;
			} elseif($type==REDIRECT_META) {
				$header = "<META HTTP-EQUIV=Refresh CONTENT='0; URL=".$url."' />";
				echo $header;
				exit;
			} elseif($type==REDIRECT_HEADER || empty($type)) {
				$header = "Location: $url";
			}
			header($header);
			$this->postProcess();
		}
	}
	
	function dbConnect() {
	// function to connect to the database
		$tstart = $this->getMicroTime(); 
		if(@!$this->rs = mysql_connect($this->dbConfig['host'], $this->dbConfig['user'], $this->dbConfig['pass'])) {
			$this->messageQuit("Failed to create the database connection!");
		} else {
			mysql_select_db($this->dbConfig['dbase']);
			$tend = $this->getMicroTime(); 
			$totaltime = $tend-$tstart;
			if($this->dumpSQL) {
				$this->queryCode .= "<fieldset style='text-align:left'><legend>Database connection</legend>".sprintf("Database connection was created in %2.4f s", $totaltime)."</fieldset><br />";
			}
			$this->queryTime = $this->queryTime+$totaltime;
		}
	}
	
	function dbQuery($query) {
	// function to query the database
		// check the connection and create it if necessary
		if(empty($this->rs)||!is_resource($this->rs)) {
			$this->dbConnect();
		}
		$tstart = $this->getMicroTime(); 
		if(@!$result = mysql_query($query, $this->rs)) {
			$this->messageQuit("Execution of a query to the database failed", $query);
		} else {
			$tend = $this->getMicroTime(); 
			$totaltime = $tend-$tstart;
			$this->queryTime = $this->queryTime+$totaltime;
			if($this->dumpSQL) {
				$this->queryCode .= "<fieldset style='text-align:left'><legend>Query ".($this->executedQueries+1)." - ".sprintf("%2.4f s", $totaltime)."</legend>".$query."</fieldset><br />";
			}
			$this->executedQueries = $this->executedQueries+1;
			return $result;
		}
	}
	
	function recordCount($rs) {
	// function to count the number of rows in a record set
		return mysql_num_rows($rs);
	}
	
	function fetchRow($rs, $mode='assoc') {
		if($mode=='assoc') {
			return mysql_fetch_assoc($rs);
		} elseif($mode=='num') {
			return mysql_fetch_row($rs);
		} elseif($mode=='both') {
			return mysql_fetch_array($rs, MYSQL_BOTH);		
		} else {
			$this->messageQuit("Unknown get type ($mode) specified for fetchRow - must be empty, 'assoc', 'num' or 'both'.");
		}
	}
	
	function affectedRows($rs) {
		return mysql_affected_rows($rs);
	}
	
	function insertId($rs) {
		return mysql_insert_id($rs);
	}
	
	function dbClose() {
	// function to close a database connection
		mysql_close($this->rs);
	}
     
	function getSettings() {
		if(file_exists($this->getCachePath()."/siteCache.idx")) {
			include_once $this->getCachePath()."/siteCache.idx";
		} else {
			$result = $this->dbQuery("SELECT setting_name, setting_value FROM ".$this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."system_settings");
			while ($row = $this->fetchRow($result, 'both')) {
				$this->config[$row[0]] = $row[1];
			}			
		}
		
		// load user setting if user is logged in
		if($id=$this->getLoginUserID()){
			if ($this->getLoginUserType()=='web') $query = "web_user_settings WHERE webuser='$id'";
			else $query = "user_settings WHERE user='$id'";
			$result = $this->dbQuery("SELECT setting_name, setting_value FROM ".$this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."$query");
			while ($row = $this->fetchRow($result, 'both')) $this->config[$row[0]] = $row[1];
		}		
	} 
	
	function getDocumentMethod() {
	// function to test the query and find the retrieval method
		if(isset($_REQUEST['q'])) {
			return "alias";
		} elseif(isset($_REQUEST['id'])) {
			return "id";
		} else {
			return "none";
		}
	}

	function getDocumentIdentifier($method) {
	// function to test the query and find the retrieval method
		switch($method) {
			case "alias" :
				return $_REQUEST['q'];
				break;
			case "id" :
				return $_REQUEST['id'];
				break;
			case "none" :
				return $this->config['site_start'];
				break;
			default :
				return $this->config['site_start'];
		}			
	}

	function checkSession() {
		if(isset($_SESSION['validated'])) {
			return true;
		} else  {
			return false;
		}
	}

	function checkPreview() {
		if($this->checkSession()==true) {
			if(isset($_REQUEST['z']) && $_REQUEST['z']=='manprev') {
				return true;
			} else {
				return false;
			}
		} else  {
			return false;
		}
	}

	function checkSiteStatus() {
		$siteStatus = $this->config['site_status'];
		if($siteStatus==1) {
			return true;
		} elseif($siteStatus==0 && $this->checkSession()) {
			return true;
		} else {
			return false;
		}
	}

	function cleanDocumentIdentifier($qOrig) { // modx updates
		$q = $qOrig;
		// First remove any / before or after
		if ($q[ strlen( $q) - 1] == '/') $q = substr( $q,0,-1);
		if ($q[ 0 ] == '/') $q = substr($q,1);
		// Save path if any
		$this->virtualDir = dirname($q);
		$this->virtualDir = ($this->virtualDir == '.' ? '' : $this->virtualDir);
		$q = basename($q);
		$q = str_replace($this->config['friendly_url_prefix'], "", $q);
		$q = str_replace($this->config['friendly_url_suffix'], "", $q);
		if(is_numeric($q) && !$this->documentListing[$q]) { // we got an ID returned, check to make sure it;s no an alias
			$this->documentMethod = 'id';
			return $q;
		} 
		else { // we didn't get an ID back, so instead we assume it's an alias
			if($this->config['friendly_alias_urls']==1) {
				$q = str_replace($this->config['friendly_url_prefix'], "", $q);
				$q = str_replace($this->config['friendly_url_suffix'], "", $q);
			} 
			else {
				$q = $qOrig;
			}
			$this->documentMethod = 'alias';
			return $q;
		}
	}


	function checkCache($id) {
		$cacheFile = "assets/cache/docid_".$id.".etoCache";
		if(file_exists($cacheFile)) {
			$this->documentGenerated=0;
			return join("",file($cacheFile));
		} else {
			$this->documentGenerated=1;
			return "";		
		}
	}

	function addNotice($content, $type="text/html") {
		/*	
			PLEASE READ!
		
			This function places a copyright message and a link to Etomite in the page about to be 
			sent to the visitor's browser. The message is placed just before your </body> or </BODY>
			tag, and if Etomite can't find either of these, it will simply paste the message onto 
			the end of the page. 
		 
			I've not obfuscated this notice, or hidden it away somewhere deep in the code, to give 
			you the chance to alter the markup on the P tag, should you wish to do so. You can even
			remove the message as long as:
			1 - the "Etomite is Copyright..." message stays (doesn't have to be visible) and,
			2 - the link remains in place (must be visible, and must be a regular HTML link). You 
				are allowed to add a target="_blank" attribute to the link if you wish to do so.
			
			Should you decide to remove the entire message and the link, I will probably refuse to 
			give you any support you request, unless you have a very good reason for removing the 
			message. Donations or other worthwhile contributions are usually considered to be a good
			reason. ;) If in doubt, contact me through the Private Messaging system in the forums at
			http://forums.etomite.org.
			
			If you have a 'powered by' logo of Etomite on your pages, you are hereby granted 
			permission to remove this message. The 'powered by' logo must, however,  be visible on 
			all pages within your site, and must have a regular HTML link to http://www.etomite.org.
			The link's title attribute must contain the text "Etomite Content Management System". 
			Textual links are also allowed, as long as they also appear on every page, have the same
			title attribute, and contain "Etomite Content Management System" as the visible, clickable
			test. These links also must be regular HTML links.
			
			Leaving this message and the link intact will show your appreciation of the 2500+ hours 
			I've spent building the system and providing support to it's users, and the hours I will
			be spending on it in future.
			
			Removing this message, in my opinion, shows a lack of appreciation, and a lack of 
			community spirit. The term 'free-loading' comes to mind. :)
			
			Thanks for understanding, and thanks for not removing the message and link!
				- Alex
			
		*/
		$notice	  = "\n<!--\n\n".
					"\tI kindly request you leave the copyright notice and the link \n".
					"\tto Etomite.org intact to show your appreciation of the time\n".
					"\tI and the contributors to the Etomite project have (freely) \n".
					"\tspent on the system. Removal of the copyright notice and\n".
					"\tthe link, without the permission of the author, may affect\n".
					"\tor even cause us to deny any support requests you make. By \n".
					"\tleaving this link intact, you show your support of the project,\n".
					"\tand help to increase interest, traffic and use of Etomite, \n".
					"\twhich will ultimately benefit all who use the system. To save\n".
					"\tbandwidth, you may remove this message, as long as the link\n".
					"\tand copyright notice stay in place.\n\n".
					"\tEtomite is Copyright 2004 and Trademark of the Etomite Project. \n\n";
		if($type=="text/html") {		
			$poweredby ="Powered By <a href='http://www.etomite.org' title='Content managed by the Etomite Content Management System'>Etomite</a>.";
			$notice .=	"-->\n\n".
						"<p>\n".
						"\tContent managed by the <a href='http://www.etomite.org' title='Content managed by the Etomite Content Management System'>Etomite Content Management System</a>.\n".
						"</p>\n\n";
		} else {
			$notice .=	"\tThe Etomite Content Management System can be found at http://www.etomite.org\n\n".
						"-->";
		}
		// insert the message into the document
		if(strpos($content, "<div id=\"poweredbyetomite\">")>0) {
			$content = str_replace("<div id=\"poweredbyetomite\">", "<div id=\"poweredbyetomite\">".$poweredby, $content);
		} elseif(strpos($content, "<div id=\"etomitecredits\">")>0) {
			$content = str_replace("<div id=\"etomitecredits\">", "<div id=\"etomitecredits\">".$notice, $content);
		} elseif(strpos($content, "</body>")>0) {
			$content = str_replace("</body>", $notice."</body>", $content);
		} elseif(strpos($content, "</BODY>")>0) {
			$content = str_replace("</body>", $notice."</BODY>", $content);		
		} else {
			$content .= $notice;
		}
		return $content;
	}
	
	function outputContent() {

		$output = $this->documentContent;
		
		// check for non-cached snippet output
		if(strpos($output, '[!')>-1) {
			$output = str_replace('[!', '[[', $output);
			$output = str_replace('!]', ']]', $output);
			
			$this->nonCachedSnippetParsePasses = empty($this->nonCachedSnippetParsePasses) ? 1 : $this->nonCachedSnippetParsePasses ;
			for($i=0; $i<$this->nonCachedSnippetParsePasses; $i++) {
				if($this->dumpSnippets==1) {
					echo "<fieldset style='text-align: left'><legend>NONCACHED PARSE PASS ".($i+1)."</legend>The following snipppets (if any) were parsed during this pass.<div style='width:100%' align='center'>";
				}
				// replace settings referenced in document
				$output = $this->mergeSettingsContent($output);
				// replace HTMLSnippets in document
				$output = $this->mergeHTMLSnippetsContent($output);				
				// find and merge snippets
				$output = $this->evalSnippets($output);
				if($this->dumpSnippets==1) {
					echo "</div></fieldset><br />";
				}
			}
		}		
		
		$output = $this->rewriteUrls($output);
		
		$totalTime = ($this->getMicroTime() - $this->tstart);
		$queryTime = $this->queryTime;
		$phpTime = $totalTime-$queryTime;

		$queryTime = sprintf("%2.4f s", $queryTime);
		$totalTime = sprintf("%2.4f s", $totalTime);
		$phpTime = sprintf("%2.4f s", $phpTime);
		$source = $this->documentGenerated==1 ? "database" : "cache";
		$queries = isset($this->executedQueries) ? $this->executedQueries : 0 ;
		
		// send out content-type headers
		if(IN_ETOMITE_PARSER=="true") {
			$type = !empty($this->contentTypes[$this->documentIdentifier]) ? $this->contentTypes[$this->documentIdentifier] : "text/html";
			$header = 'Content-Type: '.$type.'; charset='.$this->config['etomite_charset'];
			header($header);
		}
		
		$documentOutput = $this->addNotice($output, $type);
		if($this->dumpSQL) {
			$documentOutput .= $this->queryCode;
		}
		$documentOutput = str_replace("[^q^]", $queries, $documentOutput);
		$documentOutput = str_replace("[^qt^]", $queryTime, $documentOutput);
		$documentOutput = str_replace("[^p^]", $phpTime, $documentOutput);
		$documentOutput = str_replace("[^t^]", $totalTime, $documentOutput);
		$documentOutput = str_replace("[^s^]", $source, $documentOutput);
		
		echo $documentOutput;

		ob_end_flush();
	}


	function checkPublishStatus(){
		include $this->getCachePath()."/etomitePublishing.idx";
		$timeNow = time()+$this->config['server_offset_time'];
		if($cacheRefreshTime<=$timeNow && $cacheRefreshTime!=0) {
			// now, check for documents that need publishing
			$sql = "UPDATE ".$this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."site_content SET published=1 WHERE ".$this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."site_content.pub_date < $timeNow AND ".$this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."site_content.pub_date!=0";
			if(@!$result = $this->dbQuery($sql)) {
				$this->messageQuit("Execution of a query to the database failed", $sql);
			}
			
			// now, check for documents that need un-publishing
			$sql = "UPDATE ".$this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."site_content SET published=0 WHERE ".$this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."site_content.unpub_date < $timeNow AND ".$this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."site_content.unpub_date!=0";
			if(@!$result = $this->dbQuery($sql)) {
				$this->messageQuit("Execution of a query to the database failed", $sql);
			}
				
			// clear the cache
			$basepath = $this->mapPath($this->getCachePath());
			if ($handle = opendir($basepath)) {
				$filesincache = 0;
				$deletedfilesincache = 0;
				while (false !== ($file = readdir($handle))) { 
					if ($file != "." && $file != "..") { 
						$filesincache += 1;
						if (preg_match ("/\.etoCache/", $file)) {
							$deletedfilesincache += 1;
							while(!unlink($basepath."/".$file));
						}
					} 
				}
				closedir($handle); 
			}

			// update publish time file
			$timesArr = array();
			$sql = "SELECT MIN(pub_date) AS minpub FROM ".$this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."site_content WHERE pub_date>$timeNow";
			if(@!$result = $this->dbQuery($sql)) {
				$this->messageQuit("Failed to find publishing timestamps", $sql);
			}
			$tmpRow = $this->fetchRow($result);
			$minpub = $tmpRow['minpub'];
			if($minpub!=NULL) {
				$timesArr[] = $minpub;
			}
			
			$sql = "SELECT MIN(unpub_date) AS minunpub FROM ".$this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."site_content WHERE unpub_date>$timeNow";
			if(@!$result = $this->dbQuery($sql)) {
				$this->messageQuit("Failed to find publishing timestamps", $sql);
			}
			$tmpRow = $this->fetchRow($result);
			$minunpub = $tmpRow['minunpub'];
			if($minunpub!=NULL) {
				$timesArr[] = $minunpub;
			}

			if(count($timesArr)>0) {
				$nextevent = min($timesArr);
			} else {
				$nextevent = 0;			
			}
			
			$basepath = $this->mapPath($this->getCachePath());
			$fp = @fopen($basepath."/etomitePublishing.idx","wb");
			if($fp) {
				@flock($fp, LOCK_EX);
				$len = strlen($data);
				@fwrite($fp, "<?php \$cacheRefreshTime=$nextevent; ?>", $len);
				@flock($fp, LOCK_UN);
				@fclose($fp);
			}
		}
	}

	function postProcess() {

		// if the current document was generated, cache it!
		if($this->documentGenerated==1 && $this->documentObject['cacheable']==1 && $this->documentObject['type']=='document') {
			$basepath = $this->mapPath($this->getCachePath());
			if($fp = @fopen($basepath."/docid_".$this->documentIdentifier.".etoCache","w")){
				fputs($fp,$this->documentContent);
				fclose($fp);
			}
		}

		if($this->config['track_visitors']==1 && !isset($_REQUEST['z'])) {
			$this->log();
		}
		// end post processing
	}
	
	// mod by Raymond
	function mergeDocumentContent($template) {
		preg_match_all('~\[\*(.*?)\*\]~', $template, $matches);
		$variableCount = count($matches[1]);
		for($i=0; $i<$variableCount; $i++) {
			$key = $matches[1][$i];
			$value = $this->documentObject[$key];
			$replace[$i] = stripslashes($value);
		}
		$template = str_replace($matches[0], $replace, $template);
		return $template;
	}
	
	function mergeSettingsContent($template) {
		preg_match_all('~\[\((.*?)\)\]~', $template, $matches);
		$settingsCount = count($matches[1]);
		for($i=0; $i<$settingsCount; $i++) {
			$replace[$i] = $this->config[$matches[1][$i]];
		}
		$template = str_replace($matches[0], $replace, $template);
		return $template;
	}

	function mergeHTMLSnippetsContent($content) {
		preg_match_all('~{{(.*?)}}~', $content, $matches);
		$settingsCount = count($matches[1]);
		for($i=0; $i<$settingsCount; $i++) {
			if(isset($this->chunkCache[$matches[1][$i]])) {
				$replace[$i] = base64_decode($this->chunkCache[$matches[1][$i]]);
			} else {
				$sql = "SELECT * FROM ".$this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."site_htmlsnippets WHERE ".$this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."site_htmlsnippets.name='".$matches[1][$i]."';";
				$result = $this->dbQuery($sql);
				$limit=$this->recordCount($result);
				if($limit<1) {
					$this->chunkCache[$matches[1][$i]] = "";
					$replace[$i] = "";
				} else {
					$row=$this->fetchRow($result);
					$this->chunkCache[$matches[1][$i]] = $row['snippet'];
					$replace[$i] = $row['snippet'];
				}
			}
		}
		$content = str_replace($matches[0], $replace, $content);
		return $content;
	}

	// Added by Raymond
	function mergePlaceholderContent($content) {
		preg_match_all('~\[\+(.*?)\+\]~', $content, $matches);
		$cnt = count($matches[1]);
		for($i=0; $i<$cnt; $i++) {
			$replace[$i] = $this->placeholders[$matches[1][$i]];
		}
		$content = str_replace($matches[0], $replace, $content);
		return $content;	
	}

	function evalSnippet($snippet, $params) {
		$etomite = &$this;
		if(is_array($params)) {
			extract($params, EXTR_SKIP);
		}
		$snip = eval(base64_decode($snippet));
		return $snip;
	}
	
	function evalSnippets($documentSource) {
		preg_match_all('~\[\[(.*?)\]\]~', $documentSource, $matches);
		
		$etomite = &$this;

		$matchCount=count($matches[1]);
		for($i=0; $i<$matchCount; $i++) {
			$spos = strpos($matches[1][$i], '?', 0);
			if($spos!==false) {
				$params = substr($matches[1][$i], $spos, strlen($matches[1][$i]));
			} else {
				$params = '';
			}
			$matches[1][$i] = str_replace($params, '', $matches[1][$i]);
			$snippetParams[$i] = $params;
		}
		$nrSnippetsToGet = count($matches[1]);
		for($i=0;$i<$nrSnippetsToGet;$i++) {	// Raymond: Mod for Snippet props
			if(isset($this->snippetCache[$matches[1][$i]])) {			
				$snippets[$i]['name'] = $matches[1][$i];
				$snippets[$i]['snippet'] = $this->snippetCache[$matches[1][$i]];
				$snippets[$i]['properties'] = $this->snippetCache[$matches[1][$i]."Props"];
			} else {
				$sql = "SELECT * FROM ".$this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."site_snippets WHERE ".$this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."site_snippets.name='".$matches[1][$i]."';";
				$result = $this->dbQuery($sql);
				if($this->recordCount($result)==1) {
					$row = $this->fetchRow($result);					
					$snippets[$i]['name'] = $row['name'];
					$snippets[$i]['snippet'] = base64_encode($row['snippet']);
					$snippets[$i]['properties'] = base64_encode($row['properties']);
					$this->snippetCache = $snippets[$i];
				} else {
					$snippets[$i]['name'] = $matches[1][$i];
					$snippets[$i]['snippet'] = base64_encode("return false;");
					$snippets[$i]['properties'] = '';
					$this->snippetCache = $snippets[$i];
				}
			}
		}
		
		for($i=0; $i<$nrSnippetsToGet; $i++) {
			$parameter = array();
			$snippetName = $this->currentSnippet = $snippets[$i]['name'];
			$snippetProperties = $snippets[$i]['properties']; 
			// default params/properties - Raymond
			if(!empty($snippetProperties)) {
				$tempSnippetParams = explode("&",base64_decode($snippetProperties));
				for($x=0; $x<count($tempSnippetParams); $x++) {
					$parameterTemp = explode("=", $tempSnippetParams[$x]);
					$parameterValueTemp = explode(";", trim($parameterTemp[1]));
					if ($parameterValueTemp[1]=='list' && $parameterValueTemp[3]!="") $parameter[$parameterTemp[0]] = $parameterValueTemp[3]; //list default
					else if($parameterValueTemp[1]!='list' && $parameterValueTemp[2]!="") $parameter[$parameterTemp[0]] = $parameterValueTemp[2];
				}
			}
			// current params
			$currentSnippetParams = $snippetParams[$i];
			if(!empty($currentSnippetParams)) {
				$tempSnippetParams = str_replace("?", "", $currentSnippetParams);
				$splitter = "&";
				if (strpos($tempSnippetParams, "&amp;")>0) $tempSnippetParams = str_replace("&amp;","&",$tempSnippetParams);
				$tempSnippetParams = split($splitter, $tempSnippetParams);
				for($x=0; $x<count($tempSnippetParams); $x++) {
					$parameterTemp = explode("=", $tempSnippetParams[$x]);
					$fp = strPos($parameterTemp[1],'`');
					$lp = strrPos($parameterTemp[1],'`');
					if(!($fp===false && $lp===false)) $parameterTemp[1] = substr($parameterTemp[1],$fp+1,$lp-1);
					$parameter[$parameterTemp[0]] = $parameterTemp[1];
				}
			}
			$executedSnippets[$i] = $this->evalSnippet($snippets[$i]['snippet'], $parameter);
			if($this->dumpSnippets==1) {
				echo "<fieldset><legend><b>$snippetName</b></legend><textarea style='width:60%; height:200px'>".htmlentities($executedSnippets[$i])."</textarea></fieldset><br />";
			}
			$documentSource = str_replace("[[".$snippetName.$currentSnippetParams."]]", $executedSnippets[$i], $documentSource);
		}
		return $documentSource;
	}

	function makeFriendlyURL($pre,$suff,$alias) {
		$dir = dirname($alias);
		return ($dir!='.' ? "$dir/":"").$pre.basename($alias).$suff;
	}
	
	function rewriteUrls($documentSource) {
	 	// rewrite the urls
		if($this->config['friendly_urls']==1) {  
			$aliases = array(); 
			foreach ( $this->aliasListing as $item ) {
				$aliases[$item['id']] = ( strlen( $item['path'] ) > 0 ? $item['path'] . '/' : '' ) . $item['alias']; 
			}
			$in = '!\[\~(.*?)\~\]!ise'; // Use preg_replace with /e to make it evaluate PHP
				$isfriendly = ( $this->config['friendly_alias_urls'] == 1 ? 1 : 0 );
				$pref = $this->config['friendly_url_prefix'];
				$suff = $this->config['friendly_url_suffix'];
				$thealias = '$aliases[\\1]';
				$found_friendlyurl = "\$this->makeFriendlyURL('$pref','$suff',$thealias)";
				$not_found_friendlyurl = "\$this->makeFriendlyURL('$pref','$suff','".'\\1'."')";
				$out = '(isset('.$thealias.') ? ('.$isfriendly.' ? ' . $found_friendlyurl . ' : '.$thealias.') : ' . $not_found_friendlyurl . ')';
			$documentSource = preg_replace($in, $out, $documentSource); 
		}
		else {
			$in = '!\[\~(.*?)\~\]!is'; 
			$out = "index.php?id=".'\1';
			$documentSource = preg_replace($in, $out, $documentSource);   
		}
		return $documentSource;
	}
	
	function executeParser() {
		//error_reporting(0);
		if (version_compare( phpversion(), "5.0.0", ">=" )) 
		   set_error_handler(array(&$this,"phpError"), E_ALL);
		else 
		   set_error_handler(array(&$this,"phpError"));

		// get the settings
		if(empty($this->config)) {
			$this->getSettings();
		}
		if(!$this->checkSiteStatus()) {
			$this->documentContent = $this->config['site_unavailable_message'];
			$this->outputContent();
			exit; // stop processing here, as the site's offline
		}
		
		// make sure the cache doesn't need updating
		$this->checkPublishStatus();
		
		// check the logging cookie
		if($this->config['track_visitors']==1 && !isset($_REQUEST['z'])) {
			$this->checkCookie();
		}
		
		
		// find out which document we need to display
		$this->documentMethod = $this->getDocumentMethod();
		$this->documentIdentifier = $this->getDocumentIdentifier($this->documentMethod);
		if($this->documentMethod=="none"){
			$this->documentMethod = "id"; // now we know the site_start, change the none method to id
		}
		if($this->documentMethod=="alias"){
			$this->documentIdentifier = $this->cleanDocumentIdentifier($this->documentIdentifier);
		}

		if($this->documentMethod=="alias"){
			// Check use_alias_path and check if $this->virtualDir is set to anything, then parse the path
			if ($this->config['use_alias_path'] == 1) {
				$alias = (strlen($this->virtualDir) > 0 ? $this->virtualDir.'/' : '').$this->documentIdentifier;
				$this->documentIdentifier = $this->documentListing[$alias];
			}
			else {
				$this->documentIdentifier = $this->documentListing[$this->documentIdentifier];
			}
			$this->documentMethod = 'id';
		}				
				
		// we now know the method and identifier, let's check the cache
		$this->documentContent = $this->checkCache($this->documentIdentifier);
		if($this->documentContent!="") {
			// do nothing?
		} else {
			$tblsc = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."site_content";
			$tbldg = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."document_groups";
			$tbldgn = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."documentgroup_names";
			// get document groups for current user
			if($_SESSION['docgroups']) $docgrp = implode(",",$_SESSION['docgroups']);
			$sql = "SELECT DISTINCT sc.* 
					FROM $tblsc sc
					LEFT JOIN $tbldg dg ON dg.document = sc.id
					LEFT JOIN $tbldgn dgn ON dgn.id = dg.document_group
					WHERE sc.".$this->documentMethod." = '".$this->documentIdentifier."' 
					AND (1='".$_SESSION['role']."' OR ".(IN_ETOMITE_PARSER ? "NOT(dgn.private_webgroup<=>1)":"NOT(dgn.private_memgroup<=>1)").(!$docgrp ? "":" OR dg.document_group IN ($docgrp)").");";
			$result = $this->dbQuery($sql);
			$rowCount = $this->recordCount($result);
			if($rowCount<1) {
				if ($this->config['unauthorized_page'] && $this->dbQuery("SELECT id FROM $tbldg WHERE document = '".$this->documentIdentifier."' LIMIT 1;")) $this->sendRedirect($this->makeUrl($this->config['unauthorized_page']),1); // match found but not publicly accessible, send the visitor to the unauthorized_page
				else $this->sendRedirect($this->makeUrl($this->config['error_page']), 1); // no match found, send the visitor to the error_page
				exit;
			}
			if($rowCount>1) {
				$this->messageQuit("More than one result returned when attempting to translate `alias` to `id` - there are multiple documents using the same alias"); // no match found, send the visitor to the error_page
			}
			# this is now the document :) #
			$this->documentObject = $this->fetchRow($result);

			// Modified by Raymond for TV - Orig Modified by Apodigm - Docvars
			$tbn = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix'];
			$sql = "SELECT DISTINCT tv.*, IF(tvc.value!='',tvc.value,tv.default_text) as value ";
			$sql.= "FROM ".$tbn."site_tmplvars tv ";
			$sql.= "INNER JOIN ".$tbn."site_tmplvar_templates tvtpl ON tvtpl.tmplvarid = tv.id ";
			$sql.= "LEFT JOIN ".$tbn."site_tmplvar_contentvalues tvc ON tvc.tmplvarid=tv.id AND tvc.contentid = ".$this->documentIdentifier." ";
			$sql.= "LEFT JOIN ".$tbn."site_tmplvar_access tva ON tva.tmplvarid=tv.id  ";			
			$sql.= "WHERE tvtpl.templateid = ".$this->documentObject['template']." AND (1='".$_SESSION['role']."' OR ISNULL(tva.documentgroup)".((!$docgrp)? "":" OR tva.documentgroup IN ($docgrp)").");";
			$rs = $this->dbQuery($sql);
			$rowCount = $this->recordCount($rs);
			if($rowCount>0) {
				$baspath = $this->mapPath($this->getManagerPath()."/includes");
				include_once $baspath."/tmplvars.format.inc.php";
				include_once $baspath."/tmplvars.commands.inc.php";
				for($i=0;$i<$rowCount;$i++) {
					$row = $this->fetchRow($rs);
					$tmplvars[$row['name']] = getTVDisplayFormat($this,$row['name'],$row['value'],$row['display'],$row['display_params'],$row['type']);
				}				
				$this->documentObject = array_merge($this->documentObject,$tmplvars);
			}
			//End Modification

			// write the documentName to the object
			$this->documentName = $this->documentObject['pagetitle'];

			// validation routines
			if($this->documentObject['deleted']==1) {
				$this->sendRedirect($this->makeUrl($this->config['error_page']), 1);
			}
													//  && !$this->checkPreview()
			if($this->documentObject['published']==0) {
				//echo "this document is not published";
				//exit;
				$this->sendRedirect($this->makeUrl($this->config['error_page']), 1);
			}
			
			// check whether it's a reference
			if($this->documentObject['type']=="reference") {
				$this->sendRedirect($this->documentObject['content']);
			}
									
			// get the template and start parsing!
			$sql = "SELECT * FROM ".$this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."site_templates WHERE ".$this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."site_templates.id = '".$this->documentObject['template']."';";
			$result = $this->dbQuery($sql);
			$rowCount = $this->recordCount($result);
			if($rowCount!=1) {
				$this->messageQuit("Incorrect number of templates returned from database", $sql);
			}
			$row = $this->fetchRow($result);
			$documentSource = $row['content'];
			
			// get snippets and parse them the required number of times
			$this->snippetParsePasses = empty($this->snippetParsePasses) ? 3 : $this->snippetParsePasses ;
			for($i=0; $i<$this->snippetParsePasses; $i++) {
				if($this->dumpSnippets==1) {
					echo "<fieldset><legend><b style='color: #821517;'>PARSE PASS ".($i+1)."</b></legend>The following snipppets (if any) were parsed during this pass.<div style='width:100%' align='center'>";
				}
				// combine template and content
				$documentSource = $this->mergeDocumentContent($documentSource);
				// replace settings referenced in document
				$documentSource = $this->mergeSettingsContent($documentSource);
				// replace HTMLSnippets in document
				$documentSource = $this->mergeHTMLSnippetsContent($documentSource);				
				// find and merge snippets
				$documentSource = $this->evalSnippets($documentSource);
				if($this->dumpSnippets==1) {
					echo "</div></fieldset><br />";
				}
				// find and replace Placeholders - Added by Raymond
				$documentSource = $this->mergePlaceholderContent($documentSource);
			}
			// Added by Raymond
			// Insert TV jscripts into template - template must have a <head> tag
			if (function_exists("getRegisteredJScripts")){				
				$documentSource = preg_replace("/(<head>)/i", "\\1".getRegisteredJScripts(), $documentSource);
			}
			// End Modification
			$this->documentContent = $documentSource;
		}	
		register_shutdown_function(array(&$this,"postProcess")); // tell PHP to call postProcess when it shuts down
		$this->outputContent();
		//$this->postProcess();
	}

	
/***************************************************************************************/
/* Etomite API functions																/
/***************************************************************************************/
	
	
	function getAllChildren($id=0, $sort='menuindex', $dir='ASC', $fields='id, pagetitle, description, parent, alias') {
		$tblsc = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."site_content";
		$tbldg = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."document_groups";
		$tbldgn = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."documentgroup_names";
		// modify field names to use sc. table reference
		$fields = 'sc.'.implode(',sc.',preg_replace("/^\s/i","",explode(',',$fields)));
		$sort = 'sc.'.implode(',sc.',preg_replace("/^\s/i","",explode(',',$sort)));
		// get document groups for current user
		if($_SESSION['docgroups']) $docgrp = implode(",",$_SESSION['docgroups']);
		// build query 
		$sql = "SELECT DISTINCT $fields FROM $tblsc sc
				LEFT JOIN $tbldg dg on dg.document = sc.id
				LEFT JOIN $tbldgn dgn ON dgn.id = dg.document_group
				WHERE sc.parent = '$id' 
				AND (1='".$_SESSION['role']."' OR ".(IN_ETOMITE_PARSER ? "NOT(dgn.private_webgroup<=>1)":"NOT(dgn.private_memgroup<=>1)").(!$docgrp ? "":" OR dg.document_group IN ($docgrp)").") 
				ORDER BY $sort $dir;";
		$result = $this->dbQuery($sql);
		$resourceArray = array();
		for($i=0;$i<@$this->recordCount($result);$i++)  {
			array_push($resourceArray,@$this->fetchRow($result));
		}
		return $resourceArray;
	}
	
	function getActiveChildren($id=0, $sort='menuindex', $dir='ASC', $fields='id, pagetitle, description, parent, alias') {
		$tblsc = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."site_content";
		$tbldg = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."document_groups";
		$tbldgn = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."documentgroup_names";
		$sql = "SELECT $fields FROM $tbl WHERE $tbl.parent=$id AND $tbl.published=1 AND $tbl.deleted=0 ORDER BY $sort $dir;";
		// modify field names to use sc. table reference
		$fields = 'sc.'.implode(',sc.',preg_replace("/^\s/i","",explode(',',$fields)));
		$sort = 'sc.'.implode(',sc.',preg_replace("/^\s/i","",explode(',',$sort)));

		// get document groups for current user
		if($_SESSION['docgroups']) $docgrp = implode(",",$_SESSION['docgroups']);
		// build query 
		$sql = "SELECT DISTINCT $fields FROM $tblsc sc
				LEFT JOIN $tbldg dg on dg.document = sc.id
				LEFT JOIN $tbldgn dgn ON dgn.id = dg.document_group
				WHERE sc.parent = '$id' AND sc.published=1 AND sc.deleted=0 
				AND (1='".$_SESSION['role']."' OR ".(IN_ETOMITE_PARSER ? "NOT(dgn.private_webgroup<=>1)":"NOT(dgn.private_memgroup<=>1)").(!$docgrp ? "":" OR dg.document_group IN ($docgrp)").") 
				ORDER BY $sort $dir;";
		$result = $this->dbQuery($sql);
		$resourceArray = array();
		for($i=0;$i<@$this->recordCount($result);$i++)  {
			array_push($resourceArray,@$this->fetchRow($result));
		}
		return $resourceArray;
	}

	function getDocuments($ids=array(), $published=1, $deleted=0, $fields="*", $where='', $sort="menuindex", $dir="ASC", $limit="") {
		if(count($ids)==0) {
			return false;
		} else {
			$limit = ($limit != "") ? "LIMIT $limit" : ""; // LIMIT capabilities - rad14701
			$tblsc = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."site_content";
			$tbldg = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."document_groups";
			$tbldgn = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."documentgroup_names";
			// modify field names to use sc. table reference
			$fields = 'sc.'.implode(',sc.',preg_replace("/^\s/i","",explode(',',$fields)));
			$sort = 'sc.'.implode(',sc.',preg_replace("/^\s/i","",explode(',',$sort)));
			if ($where!='') $where += 'AND '.$where;
			// get document groups for current user
			if($_SESSION['docgroups']) $docgrp = implode(",",$_SESSION['docgroups']);
			$sql = "SELECT DISTINCT $fields FROM $tblsc sc 
					LEFT JOIN $tbldg dg on dg.document = sc.id
					LEFT JOIN $tbldgn dgn ON dgn.id = dg.document_group
					WHERE (sc.id IN (".join($ids, ",").") AND sc.published=$published AND sc.deleted=$deleted $where)
					AND (1='".$_SESSION['role']."' OR ".(IN_ETOMITE_PARSER ? "NOT(dgn.private_webgroup<=>1)":"NOT(dgn.private_memgroup<=>1)").(!$docgrp ? "":" OR dg.document_group IN ($docgrp)").") 
					ORDER BY $sort $dir $limit;";				
			$result = $this->dbQuery($sql);
			$resourceArray = array();
			for($i=0;$i<@$this->recordCount($result);$i++)  {
				array_push($resourceArray,@$this->fetchRow($result));
			}
			return $resourceArray;
		}
	}

	function getDocument($id=0,$fields="*",$published=1, $deleted=0) {
		if($id==0) {
			return false;
		} else {
			$tmpArr[] = $id;
			$docs = $this->getDocuments($tmpArr, $published, $deleted, $fields);
			if($docs!=false) {
				return $docs[0];
			} else {
				return false;
			}
		}
	}
	
	function getPageInfo($pageid=-1, $active=1, $fields='id, pagetitle, description, alias') { 
		if($pageid==0) { 
			return false; 
		} else { 
			$tblsc = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."site_content";
			$tbldg = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."document_groups";
			$tbldgn = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."documentgroup_names";
			$activeSql = $active==1 ? "AND sc.published=1 AND sc.deleted=0" : "" ;
			// modify field names to use sc. table reference
			$fields = 'sc.'.implode(',sc.',preg_replace("/^\s/i","",explode(',',$fields)));
			// get document groups for current user
			if($_SESSION['docgroups']) $docgrp = implode(",",$_SESSION['docgroups']);
			$sql = "SELECT DISTINCT $fields 
					FROM $tblsc sc 
					LEFT JOIN $tbldg dg on dg.document = sc.id
					LEFT JOIN $tbldgn dgn ON dgn.id = dg.document_group
					WHERE (sc.id=$pageid $activeSql) 
					AND (1='".$_SESSION['role']."' OR ".(IN_ETOMITE_PARSER ? "NOT(dgn.private_webgroup<=>1)":"NOT(dgn.private_memgroup<=>1)").(!$docgrp ? "":" OR dg.document_group IN ($docgrp)").");";
			$result = $this->dbQuery($sql); 
			$pageInfo = @$this->fetchRow($result); 
			return $pageInfo; 
		} 
	}
	
	function getParent($pid=-1, $active=1, $fields='id, pagetitle, description, alias, parent') {
		if($pid==-1) {
			$pid = $this->documentObject['parent'];
			return ($pid==0)? false:$this->getPageInfo($pid,$active,$fields);
		}else if($pid==0) {
			return false;
		} else {
			// first get the child document
			$child = $this->getPageInfo($pid,$active,"parent");
			// now return the child's parent
			$pid = ($child['parent'])? $child['parent']:0;
			return ($pid==0)? false:$this->getPageInfo($pid,$active,$fields);
		}
	}

	// Added by Raymond
	function getSnippetId() {
		if ($this->currentSnippet) {
			$tbl = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."site_snippets";
			$rs = $this->dbQuery("SELECT id FROM $tbl WHERE name='".mysql_escape_string($this->currentSnippet)."' LIMIT 1");
			$row = @$this->fetchRow($rs);
			if ($row['id']) return $row['id'];
		}
		return 0;
	}

	function getSnippetName() {
		return $this->currentSnippet;
	}

	function clearCache() {
		$basepath = $this->mapPath($this->getCachePath());
		if (@$handle = opendir($basepath)) {
			$filesincache = 0;
			$deletedfilesincache = 0;
			while (false !== ($file = readdir($handle))) { 
				if ($file != "." && $file != "..") { 
					$filesincache += 1;
					if (preg_match ("/\.etoCache/", $file)) {
						$deletedfilesincache += 1;
						unlink($basepath."/".$file);
					}
				} 
			}
			closedir($handle); 
			return true;
		} else {
			return false;
		}
	}

	function makeUrl($id, $alias='', $args='') {
		if(!is_numeric($id)) {
			$this->messageQuit("`$id` is not numeric and may not be passed to makeUrl()");
		}
		if($this->config['friendly_urls']==1 && $alias!='') {
			return $alias.$args;		
		} elseif($this->config['friendly_urls']==1 && $alias=='') {
			return $this->config['friendly_url_prefix'].$id.$this->config['friendly_url_suffix'].$args;
		} else {
			return "index.php?id=$id$args";
		}
	}
	
	function getConfig($name='') {
		if(!empty($this->config[$name])) {
			return $this->config[$name];
		} else {
			return false;
		}
	}
	
	function getVersionData() {
		include $this->getManagerPath()."/includes/version.inc.php";
		$version = array();
		$version['code_name'] = $code_name;
		$version['version'] = $small_version;
		$version['patch_level'] = $patch_level;
		$version['full_appname'] = $full_appname;
		return $version;
	}
	
	function makeList($array, $ulroot='root', $ulprefix='sub_', $type='', $ordered=false, $tablevel=0) {
		// first find out whether the value passed is an array
		if(!is_array($array)) {
			return "<ul><li>Bad list</li></ul>";
		}
		if(!empty($type)) {
			$typestr = " style='list-style-type: $type'";
		} else {
			$typestr = "";		
		}
		$tabs = "";
		for($i=0; $i<$tablevel; $i++) {
			$tabs .= "\t";
		}
		$listhtml = $ordered==true ? $tabs."<ol class='$ulroot'$typestr>\n" : $tabs."<ul class='$ulroot'$typestr>\n" ;
		foreach($array as $key=>$value) {
			if(is_array($value)) {
				$listhtml .= $tabs."\t<li>".$key."\n".$this->makeList($value, $ulprefix.$ulroot, $ulprefix, $type, $ordered, $tablevel+2).$tabs."\t</li>\n";
			} else {
				$listhtml .= $tabs."\t<li>".$value."</li>\n";
			}
		}
		$listhtml .= $ordered==true ? $tabs."</ol>\n" : $tabs."</ul>\n" ;
		return $listhtml;
	}

	function userLoggedIn() {
		$userdetails = array();
		if(isset($_SESSION['validated'])) {
			$userdetails['loggedIn']=true;
			$userdetails['id']=$_SESSION['internalKey'];
			$userdetails['username']=$_SESSION['shortname'];
			$userdetails['usertype']=$_SESSION['usertype']; // added by Raymond
			return $userdetails;
		} else {
			return false;
		}		
	}
		
	function getKeywords($id=0) {
		if($id==0) {
			$id=$this->documentObject['id'];
		}
		$tbl = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix'];
		$sql = "SELECT keywords.keyword FROM ".$tbl."site_keywords AS keywords INNER JOIN ".$tbl."keyword_xref AS xref ON keywords.id=xref.keyword_id WHERE xref.content_id = '$id'";
		$result = $this->dbQuery($sql);
		$limit = $this->recordCount($result);
		$keywords = array();
		if($limit > 0) 	{
			for($i=0;$i<$limit;$i++) {
				$row = $this->fetchRow($result);
				$keywords[] = $row['keyword'];
			}
		}
		return $keywords;
	}

	function runSnippet($snippetName, $params=array()) {
		$tbl = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix'];
		$sql = "SELECT snippet FROM ".$tbl."site_snippets WHERE name = '$snippetName'";
		$result = $this->dbQuery($sql);
		$limit = $this->recordCount($result);
		if($limit!=1) 	{
			return false;
		} else {
			$row = $this->fetchRow($result);
			return $this->evalSnippet($row['snippet'], $params);
		}		
	}
	
	function getChunk($chunkName) {
		return base64_decode($this->chunkCache[$chunkName]);
	}

	function putChunk($chunkName) { // alias name >.<
		return $this->getChunk($chunkName);
	}

	function parseChunk($chunkName, $chunkArr, $prefix="{", $suffix="}") {
		if(!is_array($chunkArr)) {
			return false;
		}
		$chunk = $this->getChunk($chunkName);
		foreach($chunkArr as $key => $value) {
			$chunk = str_replace($prefix.$key.$suffix, $value, $chunk);
		}
		return $chunk;
	}

	function getUserData() {
		include_once $this->getManagerPath()."/includes/etomiteExtenders/getUserData.extender.php";
		return $tmpArray;
	}
	
	function getSiteStats() {
		$tbl = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."log_totals";
		$sql = "SELECT * FROM $tbl";
		$result = $this->dbQuery($sql);		
		$tmpRow = $this->fetchRow($result);
		return $tmpRow;
	}
	
	#::::::::::::::::::::::::::::::::::::::::
	# Added By: Raymond Irving - MODx
	#

	// Modified by Raymond for TV - Orig Modified by Apodigm - DocVars
	# returns a single TV record. $idnames - can be an id or name that belongs the template that the current document is using
	function getTemplateVar($idname="", $fields = "*") {
		if($idname=="") {
			return false;
		}
		else {
			$result = $this->getTemplateVars(array($idname));
			return ($result!=false) ? $result[0]:false;
		}
	}
	
	# returns an array of TV records. $idnames - can be an id or name that belongs the template that the current document is using
	function getTemplateVars($idnames=array(), $fields = "*") {
		if(($idnames!='*' && !is_array($idnames)) || count($idnames)==0) {
			return false;
		}
		else {
			$result = array();
			$fields = ($fields=="") ? "tv.*" : 'tv.'.implode(',tv.',preg_replace("/^\s/i","",explode(',',$fields)));
			if ($idnames=="*") $query = "tv.id<>0";
			else $query = (is_numeric($idnames[0]) ? "tv.id":"tv.name")." IN ('".implode("','",$idnames)."')";			
			$tbn = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix'];
			if($_SESSION['docgroups']) $docgrp = implode(",",$_SESSION['docgroups']);
			$sql = "SELECT DISTINCT $fields, IF(tvc.value!='',tvc.value,tv.default_text) as value ";
			$sql.= "FROM ".$tbn."site_tmplvars tv ";
			$sql.= "INNER JOIN ".$tbn."site_tmplvar_templates tvtpl ON tvtpl.tmplvarid = tv.id ";
			$sql.= "LEFT JOIN ".$tbn."site_tmplvar_contentvalues tvc ON tvc.tmplvarid=tv.id AND tvc.contentid = ".$this->documentIdentifier." ";
			$sql.= "LEFT JOIN ".$tbn."site_tmplvar_access tva ON tva.tmplvarid=tv.id  ";			
			$sql.= "WHERE ".$query." AND (tvtpl.templateid = ".$this->documentObject['template']." AND (1='".$_SESSION['role']."' OR ISNULL(tva.documentgroup)".((!$docgrp)? "":" OR tva.documentgroup IN ($docgrp)")."));";
			$rs = $this->dbQuery($sql);
			for($i=0;$i<@$this->recordCount($rs);$i++)  {
				array_push($result,@$this->fetchRow($rs));
			}
			return $result;
		}	
	}
	
	# returns an array containing TV rendered output values. $idnames - can be an id or name that belongs the template that the current document is using
	function getTemplateVarOutput($idnames=array(), $fields = "*") {
		if(count($idnames)==0) {
			return false;
		}
		else {
			$output = array();
			$result = $this->getTemplateVars(($idnames=='*' || is_array($idnames)) ? $idnames:array($idnames));
			if ($result==false) return false;
			else {
				$baspath = $this->mapPath($this->getManagerPath()."/includes");
				include_once $baspath."/tmplvars.format.inc.php";
				include_once $baspath."/tmplvars.commands.inc.php";				
				for($i=0;$i<count($result);$i++) {
					$row = $result[$i];
					$output[$row['name']] = ($this->documentObject[$row['name']]) ? $this->documentObject[$row['name']] : getTVDisplayFormat($this,$row['name'],$row['value'],$row['display'],$row['display_params'],$row['type']);
				}
				return $output;
			}
		}
	}
	
	# returns true if the current user is a member the specified groups
	function isMemberOfUserGroup($groups=array()){
		// to-do :)
	}
	
	# returns the physical path from the supplied virtual 
	function mapPath($path,$filename=""){
		if(!$path) $path=".";
		if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
			// only on windows?
			$absPath = str_replace("\\","/",realpath($path));
			return $absPath.($absPath && $filename ? "/$filename":"$filename"); 
		}
		else {
			// determine base path 
			if (substr($path,0,1)=="/") $basePath = $_SERVER['DOCUMENT_ROOT'];
			else $basePath = dirname($_SERVER['SCRIPT_FILENAME']);
			// return absolute path
			$absPath = realpath("$basePath/$path");
			return $absPath.($absPath && $filename ? "/$filename":"$filename"); 
		}
	}

	# return placeholder value
	function getPlaceholder($name){
		return $this->placeholders[$name];
	}

	# sets a value for a placeholder
	function setPlaceholder($name,$value){
		$this->placeholders[$name] = $value;
	}
	
	# returns the virtual relative path to the manager folder
	function getManagerPath() {
		if (IN_ETOMITE_SYSTEM=="true") $pth='.';
		else $pth='manager';
		return $pth;
	}
	
	# returns the virtual relative path to the cache folder
	function getCachePath() {
		if (IN_ETOMITE_SYSTEM=="true") $pth='../assets/cache';
		else $pth='./assets/cache';
		return $pth;
	}

	# sends a message to a user's message box
	function sendAlert($type,$to,$from,$subject,$msg,$private=0){
		$private = ($private)? 1:0; 
		$tbl = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix'];
		if(!is_numeric($to)){
			// Query for the To ID
			$sql = "SELECT id FROM ".$tbl."manager_users WHERE ".$tbl."manager_users.username='$to';";
			$rs = $this->dbQuery($sql);
			if ($this->recordCount($rs)){
				$rs = $this->fetchRow($rs);
				$to = $rs['id'];
			}
		}
		if(!is_numeric($from)){
			// Query for the From ID
			$sql = "SELECT id FROM ".$tbl."manager_users WHERE ".$tbl."manager_users.username='$from';";
			$rs = $this->dbQuery($sql);
			if ($this->recordCount($rs)){
				$rs = $this->fetchRow($rs);
				$from = $rs['id'];
			}
		}
		// insert a new message into user_messages
		$sql = "INSERT INTO ".$tbl."user_messages ( id , type , subject , message , sender , recipient , private , postdate , messageread ) VALUES ( '', '$type', '$subject', '$msg', '$from', '$to', '$private', '".time()."', '0' );";
		$rs = $this->dbQuery($sql); 
	}

	# Returns true, install or interact when inside manager
	function insideManager(){
		$m=false;
		if(IN_ETOMITE_SYSTEM=='true'){
			$m = true;
			if (SNIPPET_INTERACTIVE_MODE=='true') $m = "interact";
			else if (SNIPPET_INSTALL_MODE=='true')$m = "install";
		}
		return $m;
	}

	# Returns current user id
	function getLoginUserID(){
		return (isset($_SESSION['validated']))? $_SESSION['internalKey']:'';
	}
	
	# Returns current user name
	function getLoginUserName(){
		return (isset($_SESSION['validated']))? $_SESSION['shortname']:'';
	}
	
	# Returns current login user type - web or manager
	function getLoginUserType(){
		return (isset($_SESSION['validated']))? $_SESSION['usertype']:'';
	}
	
	# Returns an array of doc groups that current user is assigned to
	function getDocGroups(){
		return (isset($_SESSION['docgroups']))? $_SESSION['docgroups']:'';
	}
	
	# Change current web user's password - returns true if successful, oterhwise return error message
	function changePassword($oldPwd,$newPwd){
		$rt = false;
		if($_SESSION["validated"]==1){
			$tbl = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."web_users";
			$ds = $this->dbQuery("SELECT * FROM $tbl WHERE id='".$_SESSION["internalkey"]."'");
			$limit = mysql_num_rows($ds);
			if($limit==1){
				if($ds["password"]==md5($oldPwd)) {
					if(strlen($newPwd) < 6 ) {
						return "Password is too short!";
						exit;
					}
					elseif($newPwd=="") {
						return "You didn't specify a password for this user!";
						exit;		
					} 
					else{
						$this->dbQuery("UPDATE $tbl SET password = md5('".$newPwd."') WHERE id='".$_SESSION["internalkey"]."'");
						return true;
					}
				}
			}
		}
	}

	# Remove unwanted html tags and snippet, settings and tags
	function stripTags($html,$allowed="") {
		$t = strip_tags($html,$allowed);
		$t = preg_replace('~\[\[(.*?)\]\]~',"",$t);	//snippet
		$t = preg_replace('~\[\!(.*?)\!\]~',"",$t);	//snippet
		$t = preg_replace('~\[\((.*?)\)\]~',"",$t);	//settings
		$t = preg_replace('~{{(.*?)}}~',"",$t);		//chunks
		return $t;
	}	
	#::Mod End:::::::::::::::::::::::::::::::::::
	
	
	/*############################################
	Etomite_dbFunctions.php
	New database functions for Etomite CMS
	Author: Ralph A. Dahlgren - rad14701@yahoo.com
	Etomite ID: rad14701
	See documentation for usage details
	############################################*/
	  function getIntTableRows($fields="*", $from="", $where="", $sort="", $dir="ASC", $limit="") {
	  // function to get rows from ANY internal database table
		if($from=="") {
		  return false;
		} else {
		  $where = ($where != "") ? "WHERE $where" : "";
		  $sort = ($sort != "") ? "ORDER BY $sort $dir" : "";
		  $limit = ($limit != "") ? "LIMIT $limit" : "";
		  $tbl = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix'].$from;
		  $sql = "SELECT $fields FROM $tbl $where $sort $limit;";
		  $result = $this->dbQuery($sql);
		  $resourceArray = array();
		  for($i=0;$i<@$this->recordCount($result);$i++)  {
			array_push($resourceArray,@$this->fetchRow($result));
		  }
		  return $resourceArray;
		}
	  }

	  function putIntTableRow($fields="", $into="") {
	  // function to put a row into ANY internal database table
		if(($fields=="") || ($into=="")){
		  return false;
		} else {
		  $tbl = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix'].$into;
		  $sql = "INSERT INTO $tbl SET ";
		  foreach($fields as $key=>$value) {
			$sql .= $key."=";
			if (is_numeric($value)) $sql .= $value.",";
			else $sql .= "'".$value."',";
		  }
		  $sql = rtrim($sql,",");
		  $sql .= ";";
		  $result = $this->dbQuery($sql);
		  return $result;
		}
	  }

	  function updIntTableRow($fields="", $into="", $where="", $sort="", $dir="ASC", $limit="") {
	  // function to update a row into ANY internal database table
		if(($fields=="") || ($into=="")){
		  return false;
		} else {
		  $where = ($where != "") ? "WHERE $where" : "";
		  $sort = ($sort != "") ? "ORDER BY $sort $dir" : "";
		  $limit = ($limit != "") ? "LIMIT $limit" : "";
		  $tbl = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix'].$into;
		  $sql = "UPDATE $tbl SET ";
		  foreach($fields as $key=>$value) {
			$sql .= $key."=";
			if (is_numeric($value)) $sql .= $value.",";
			else $sql .= "'".$value."',";
		  }
		  $sql = rtrim($sql,",");
		  $sql .= " $where $sort $limit;";
		  $result = $this->dbQuery($sql);
		  return $result;
		}
	  }

	  function getExtTableRows($host="", $user="", $pass="", $dbase="", $fields="*", $from="", $where="", $sort="", $dir="ASC", $limit="") {
	  // function to get table rows from an external MySQL database
		if(($host=="") || ($user=="") || ($pass=="") || ($dbase=="") || ($from=="")){
		  return false;
		} else {
		  $where = ($where != "") ? "WHERE  $where" : "";
		  $sort = ($sort != "") ? "ORDER BY $sort $dir" : "";
		  $limit = ($limit != "") ? "LIMIT $limit" : "";
		  $tbl = $dbase.".".$from;
		  $this->dbExtConnect($host, $user, $pass, $dbase);
		  $sql = "SELECT $fields FROM $tbl $where $sort $limit;";
		  $result = $this->dbQuery($sql);
		  $resourceArray = array();
		  for($i=0;$i<@$this->recordCount($result);$i++)  {
			array_push($resourceArray,@$this->fetchRow($result));
		  }
		  return $resourceArray;
		}
	  }

	  function putExtTableRow($host="", $user="", $pass="", $dbase="", $fields="", $into="") {
	  // function to put a row into an external database table
		if(($host=="") || ($user=="") || ($pass=="") || ($dbase=="") || ($fields=="") || ($into=="")){
		  return false;
		} else {
		  $this->dbExtConnect($host, $user, $pass, $dbase);
		  $tbl = $dbase.".".$into;
		  $sql = "INSERT INTO $tbl SET ";
		  foreach($fields as $key=>$value) {
			$sql .= $key."=";
			if (is_numeric($value)) $sql .= $value.",";
			else $sql .= "'".$value."',";
		  }
		  $sql = rtrim($sql,",");
		  $sql .= ";";
		  $result = $this->dbQuery($sql);
		  return $result;
		}
	  }

	  function updExtTableRow($host="", $user="", $pass="", $dbase="", $fields="", $into="", $where="", $sort="", $dir="ASC", $limit="") {
	  // function to update a row into an external database table
		if(($fields=="") || ($into=="")){
		  return false;
		} else {
		  $this->dbExtConnect($host, $user, $pass, $dbase);
		  $tbl = $dbase.".".$into;
		  $where = ($where != "") ? "WHERE $where" : "";
		  $sort = ($sort != "") ? "ORDER BY $sort $dir" : "";
		  $limit = ($limit != "") ? "LIMIT $limit" : "";
		  $sql = "UPDATE $tbl SET ";
		  foreach($fields as $key=>$value) {
			$sql .= $key."=";
			if (is_numeric($value)) $sql .= $value.",";
			else $sql .= "'".$value."',";
		  }
		  $sql = rtrim($sql,",");
		  $sql .= " $where $sort $limit;";
		  $result = $this->dbQuery($sql);
		  return $result;
		}
	  }

	  function dbExtConnect($host, $user, $pass, $dbase) {
	  // function to connect to external database
		$tstart = $this->getMicroTime();
		if(@!$this->rs = mysql_connect($host, $user, $pass)) {
		  $this->messageQuit("Failed to create connection to the $dbase database!");
		} else {
		  mysql_select_db($dbase);
		  $tend = $this->getMicroTime();
		  $totaltime = $tend-$tstart;
		  if($this->dumpSQL) {
			$this->queryCode .= "<fieldset style='text-align:left'><legend>Database connection</legend>".sprintf("Database connection to %s was created in %2.4f s", $dbase, $totaltime)."</fieldset><br />";
		  }
		  $this->queryTime = $this->queryTime+$totaltime;
		}
	  }

	  function getFormVars($method="",$prefix="",$trim="",$REQUEST_METHOD) {
	  //  function to retrieve form results into an associative array
		$results = array();
		$method = strtoupper($method);
		if($method == "") $method = $REQUEST_METHOD;
		if($method == "POST") $method = &$_POST;
		elseif($method == "GET") $method = &$_GET;
		else return false;
		reset($method);
		foreach($method as $key=>$value) {
		  if(($prefix != "") && (substr($key,0,strlen($prefix)) == $prefix)) {
			if($trim) {
			  $pieces = explode($prefix, $key,2);
			  $key = $pieces[1];
			  $results[$key] = $value;
			}
			else $results[$key] = $value;
		  }
		  elseif($prefix == "") $results[$key] = $value;
		}
		return $results;
	  }

	########################################
	// END New database functions - rad14701
	########################################

/***************************************************************************************/
/* End of Etomite API functions																/
/***************************************************************************************/

	function phpError($nr, $text, $file, $line) {
		if($nr==8 && $this->stopOnNotice==false) {
			return true;
		}
		if (is_readable($file)) {
			$source = file($file);
			$source = htmlspecialchars($source[$line-1]);
		} else { 
			$source = "";
		}  //Error $nr in $file at $line: <div><code>$source</code></div>
		$this->messageQuit("PHP Parse Error", '', true, $nr, $file, $source, $text, $line);
	}

	function messageQuit($msg='unspecified error', $query='', $is_error=true, $nr='', $file='', $source='', $text='', $line='') {
				$parsedMessageString = "
		<html><head><title>Etomite ".$GLOBALS['version']." &raquo; ".$GLOBALS['code_name']."</title> 
		<style>TD, BODY { font-size: 11px; font-family:verdana; }</style>
		<script type='text/javascript'>
			function copyToClip() 
			{
				holdtext.innerText = sqlHolder.innerText;
				Copied = holdtext.createTextRange();
				Copied.execCommand('Copy');
			}
		</script>
		</head><body>
		";
		if($is_error) {
			$parsedMessageString .= "<h3 style='color:red'>&laquo; Etomite Parse Error &raquo;</h3>
			<table border='0' cellpadding='1' cellspacing='0'>
			<tr><td colspan='3'>Etomite encountered the following error while attempting to parse the requested resource:</td></tr>
			<tr><td colspan='3'><b style='color:red;'>&laquo; $msg &raquo;</b></td></tr>";
		} else {
			$parsedMessageString .= "<h3 style='color:#003399'>&laquo; Etomite Debug/ stop message &raquo;</h3>
			<table border='0' cellpadding='1' cellspacing='0'>
			<tr><td colspan='3'>The Etomite parser recieved the following debug/ stop message:</td></tr>
			<tr><td colspan='3'><b style='color:#003399;'>&laquo; $msg &raquo;</b></td></tr>";
		}
		
		if(!empty($query)) {
			$parsedMessageString .= "<tr><td colspan='3'><b style='color:#999;font-size: 9px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SQL:&nbsp;<span id='sqlHolder'>$query</span></b>
			<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:copyToClip();' style='color:#821517;font-size: 9px; text-decoration: none'>[Copy SQL to ClipBoard]</a><textarea id='holdtext' style='display:none;'></textarea></td></tr>";
		}
		
		if($text!='') {
		
			$errortype = array (
				E_ERROR          => "Error",
				E_WARNING        => "Warning",
				E_PARSE          => "Parsing Error",
				E_NOTICE          => "Notice",
				E_CORE_ERROR      => "Core Error",
				E_CORE_WARNING    => "Core Warning",
				E_COMPILE_ERROR  => "Compile Error",
				E_COMPILE_WARNING => "Compile Warning",
				E_USER_ERROR      => "User Error",
				E_USER_WARNING    => "User Warning",
				E_USER_NOTICE    => "User Notice",
			);
		   
			$parsedMessageString .= "<tr><td>&nbsp;</td></tr><tr><td colspan='3'><b>PHP error debug</b></td></tr>";
			
			$parsedMessageString .= "<tr><td valign='top'>&nbsp;&nbsp;Error: </td>";
			$parsedMessageString .= "<td colspan='2'>$text</td><td>&nbsp;</td>";
			$parsedMessageString .= "</tr>";

			$parsedMessageString .= "<tr><td valign='top'>&nbsp;&nbsp;Error type/ Nr.: </td>";
			$parsedMessageString .= "<td colspan='2'>".$errortype[$nr]." - $nr</b></td><td>&nbsp;</td>";
			$parsedMessageString .= "</tr>";
			
			$parsedMessageString .= "<tr><td>&nbsp;&nbsp;File: </td>";
			$parsedMessageString .= "<td colspan='2'>$file</td><td>&nbsp;</td>";
			$parsedMessageString .= "</tr>";
	
			$parsedMessageString .= "<tr><td>&nbsp;&nbsp;Line: </td>";
			$parsedMessageString .= "<td colspan='2'>$line</td><td>&nbsp;</td>";
			$parsedMessageString .= "</tr>";
			if($source!='') {
				$parsedMessageString .= "<tr><td valign='top'>&nbsp;&nbsp;Line $line source: </td>";
				$parsedMessageString .= "<td colspan='2'>$source</td><td>&nbsp;</td>";
				$parsedMessageString .= "</tr>";
			}
		}

		$parsedMessageString .= "<tr><td>&nbsp;</td></tr><tr><td colspan='3'><b>Parser timing</b></td></tr>";

		$parsedMessageString .= "<tr><td>&nbsp;&nbsp;MySQL: </td>";
		$parsedMessageString .= "<td><i>[^qt^] s</i></td><td>(<i>[^q^] Requests</i>)</td>";
		$parsedMessageString .= "</tr>";
		
		$parsedMessageString .= "<tr><td>&nbsp;&nbsp;PHP: </td>";
		$parsedMessageString .= "<td><i>[^p^] s</i></td><td>&nbsp;</td>";
		$parsedMessageString .= "</tr>";

		$parsedMessageString .= "<tr><td>&nbsp;&nbsp;Total: </td>";
		$parsedMessageString .= "<td><i>[^t^] s</i></td><td>&nbsp;</td>";
		$parsedMessageString .= "</tr>";

		$parsedMessageString .= "</table>";
		$parsedMessageString .= "</body></html>";
	
		$this->documentContent = $parsedMessageString;
		$this->outputContent();
		
		exit;
	}


	// Parsing functions used in this class are based on/ inspired by code by Sebastian Bergmann.
	// The regular expressions used in this class are taken from the ModLogAn (http://jan.kneschke.de/projects/modlogan/) project.
	function log() {
				$user_agents = array();
		$user_agents[] = array('pattern' => '#^Mozilla/\d+\.\d+ \(compatible; iCab ([^;]); ([^;]); [NUI]; ([^;])\)#', 'string' => 'iCab $1');
		$user_agents[] = array('pattern' => '#^Opera/(\d+\.\d+) \(([^;]+); [^)]+\)#', 'string' => 'Opera $1');
		$user_agents[] = array('pattern' => '#^Mozilla/\d+\.\d+ \(compatible; MSIE [^;]+; ([^)]+)\) Opera (\d+\.\d+)#', 'string' => 'Opera $2');
		$user_agents[] = array('pattern' => '#^Mozilla/\d+\.\d+ \(([^;]+); [^)]+\) Opera (\d+\.\d+)#', 'string' => 'Opera $2');
		$user_agents[] = array('pattern' => '#^Mozilla/[1-9]\.0 ?\(compatible; MSIE ([1-9]\.[0-9b]+);(?: ?[^;]+;)*? (Mac_[^;)]+|Windows [^;)]+)(?:; [^;]+)*\)#', 'string' => 'Internet Explorer $1');
		$user_agents[] = array('pattern' => '#^Mozilla/\d+\.\d+ \([^;]+; [NIU]; ([^;]+); [^;]+; Galeon\) Gecko/\d{8}$#', 'string' => 'Galeon');
		$user_agents[] = array('pattern' => '#^Mozilla/\d+\.\d+ \([^;]+; [NIU]; Galeon; [^;]+; ([^;)]+)\)$#', 'string' => 'Galeon $1');
		$user_agents[] = array('pattern' => '#^Mozilla/\d+\.\d+ Galeon/([0-9.]+) \(([^;)]+)\) Gecko/\d{8}$#', 'string' => 'Galeon $1');
		$user_agents[] = array('pattern' => '#^Mozilla/\d+\.\d+ \([^;]+; [NIU]; ([^;]+); [^;]+; rv:[^;]+(?:; [^;]+)*\) Gecko/\d{8} ([a-zA-Z ]+/[0-9.b]+)#', 'string' => '$2');
		$user_agents[] = array('pattern' => '#^Mozilla/\d+\.\d+ \([^;]+; [NIU]; ([^;]+); [^;]+; rv:([^;]+)(?:; [^;]+)*\) Gecko/\d{8}$#', 'string' => 'Mozilla $2');
		$user_agents[] = array('pattern' => '#^Mozilla/\d+\.\d+ \([^;]+; [NIU]; ([^;]+); [^;]+; (m\d+)(?:; [^;]+)*\) Gecko/\d{8}$#', 'string' => 'Mozilla $2');
		$user_agents[] = array('pattern' => '#^Mozilla/\d+\.\d+ \([^;]+; [NIU]; ([^;]+)(?:; [^;]+)*\) Mozilla/(.+)$#', 'string' => 'Mozilla $2');
		$user_agents[] = array('pattern' => '#^Mozilla/4\.(\d+)[^(]+\(X11; [NIU] ?; ([^;]+)(?:; [^;]+)*\)#', 'string' => 'Netscape 4.$1');
		$user_agents[] = array('pattern' => '#^Mozilla/4\.(\d+)[^(]+\((OS/2|Linux|Macintosh|Win[^;]*)[;,] [NUI] ?[^)]*\)#', 'string' => 'Netscape 4.$1');
		$user_agents[] = array('pattern' => '#^Mozilla/3\.(\d+)\S*[^(]+\(X11; [NIU] ?; ([^;]+)(?:; [^;)]+)*\)#', 'string' => 'Netscape 3.$1');
		$user_agents[] = array('pattern' => '#^Mozilla/3\.(\d+)\S*[^(]+\(([^;]+); [NIU] ?(?:; [^;)]+)*\)#', 'string' => 'Netscape 3.$1');
		$user_agents[] = array('pattern' => '#^Mozilla/2\.(\d+)\S*[^(]+\(([^;]+); [NIU] ?(?:; [^;)]+)*\)#', 'string' => 'Netscape 2.$1');
		$user_agents[] = array('pattern' => '#^Mozilla \(X11; [NIU] ?; ([^;)]+)\)#', 'string' => 'Netscape');
		$user_agents[] = array('pattern' => '#^Mozilla/3.0 \(compatible; StarOffice/(\d+)\.\d+; ([^)]+)\)$#', 'string' => 'StarOffice $1');
		$user_agents[] = array('pattern' => '#^ELinks \((.+); (.+); .+\)$#', 'string' => 'ELinks $1');
		$user_agents[] = array('pattern' => '#^Mozilla/3\.0 \(compatible; NetPositive/([0-9.]+); BeOS\)$#', 'string' => 'NetPositive $1');
		$user_agents[] = array('pattern' => '#^Konqueror/(\S+)$#', 'string' => 'Konqueror $1');
		$user_agents[] = array('pattern' => '#^Mozilla/5\.0 \(compatible; Konqueror/([^;]); ([^)]+)\).*$#', 'string' => 'Konqueror $1');
		$user_agents[] = array('pattern' => '#^Lynx/(\S+)#', 'string' => 'Lynx/$1');
		$user_agents[] = array('pattern' => '#^Mozilla/4.0 WebTV/(\d+\.\d+) \(compatible; MSIE 4.0\)$#', 'string' => 'WebTV $1');
		$user_agents[] = array('pattern' => '#^Mozilla/4.0 \(compatible; MSIE 5.0; (Win98 A); (ATHMWWW1.1); MSOCD;\)$#', 'string' => '$2');
		$user_agents[] = array('pattern' => '#^(RMA/1.0) \(compatible; RealMedia\)$#', 'string' => '$1');
		$user_agents[] = array('pattern' => '#^antibot\D+([0-9.]+)/(\S+)#', 'string' => 'antibot $1');
		$user_agents[] = array('pattern' => '#^Mozilla/[1-9]\.\d+ \(compatible; ([^;]+); ([^)]+)\)$#', 'string' => '$1');
		$user_agents[] = array('pattern' => '#^Mozilla/([1-9]\.\d+)#', 'string' => 'compatible Mozilla/$1');
		$user_agents[] = array('pattern' => '#^([^;]+)$#', 'string' => '$1');	
		$GLOBALS['user_agents'] = $user_agents;
		
		$operating_systems = array();
		$operating_systems[] = array('pattern' => '#Win.*NT 5.0#', 'string' => 'Windows 2000');
		$operating_systems[] = array('pattern' => '#Win.*NT 5.1#', 'string' => 'Windows XP');
		$operating_systems[] = array('pattern' => '#Win.*(XP|2000|ME|NT|9.?)#', 'string' => 'Windows $1');
		$operating_systems[] = array('pattern' => '#Windows .*(3\.11|NT)#', 'string' => 'Windows $1');
		$operating_systems[] = array('pattern' => '#Win32#', 'string' => 'Windows [unknown version)');
		$operating_systems[] = array('pattern' => '#Linux 2\.(.?)\.#', 'string' => 'Linux 2.$1.x');
		$operating_systems[] = array('pattern' => '#Linux#', 'string' => 'Linux (unknown version)');
		$operating_systems[] = array('pattern' => '#FreeBSD .*-CURRENT$#', 'string' => 'FreeBSD Current');
		$operating_systems[] = array('pattern' => '#FreeBSD (.?)\.#', 'string' => 'FreeBSD $1.x');
		$operating_systems[] = array('pattern' => '#NetBSD 1\.(.?)\.#', 'string' => 'NetBSD 1.$1.x');
		$operating_systems[] = array('pattern' => '#(Free|Net|Open)BSD#', 'string' => '$1BSD [unknown version]');
		$operating_systems[] = array('pattern' => '#HP-UX B\.(10|11)\.#', 'string' => 'HP-UX B.$1.xP');
		$operating_systems[] = array('pattern' => '#IRIX(64)? 6\.#', 'string' => 'IRIX 6.x');
		$operating_systems[] = array('pattern' => '#SunOS 4\.1#', 'string' => 'SunOS 4.1.x');
		$operating_systems[] = array('pattern' => '#SunOS 5\.([4-6])#', 'string' => 'Solaris 2.$1.x');
		$operating_systems[] = array('pattern' => '#SunOS 5\.([78])#', 'string' => 'Solaris $1.x');
		$operating_systems[] = array('pattern' => '#Mac_PowerPC#', 'string' => 'Mac OS [PowerPC]');
		$operating_systems[] = array('pattern' => '#Mac#', 'string' => 'Mac OS');
		$operating_systems[] = array('pattern' => '#X11#', 'string' => 'UNIX [unknown version]');
		$operating_systems[] = array('pattern' => '#Unix#', 'string' => 'UNIX [unknown version]');
		$operating_systems[] = array('pattern' => '#BeOS#', 'string' => 'BeOS [unknown version]');
		$operating_systems[] = array('pattern' => '#QNX#', 'string' => 'QNX [unknown version]');
		$GLOBALS['operating_systems'] = $operating_systems;

		// fix for stupid browser shells sending lots of requests
		if(strpos($_SERVER['HTTP_USER_AGENT'], "http://www.avantbrowser.com") > -1) {
			exit;
		}

		if(strpos($_SERVER['HTTP_USER_AGENT'], "WebDAV") > -1) {
			exit;
		}
			
		//work out browser and operating system
		$user_agent = $this->useragent($_SERVER['HTTP_USER_AGENT']);
		$os = crc32($user_agent['operating_system']);
		$ua = crc32($user_agent['user_agent']);
		
		//work out access time data
		$accesstime = getdate(); 
		$hour = $accesstime['hours']; 
		$weekday = $accesstime['wday']; 

		// work out the host
		if (isset($_SERVER['REMOTE_ADDR'])) {
			$hostname = $_SERVER['REMOTE_ADDR'];
			if (isset($_SERVER['REMOTE_HOST'])) {
				$hostname = $_SERVER['REMOTE_HOST'];
			} else {
				if ($this->config['resolve_hostnames']==1) {
					$hostname = gethostbyaddr($hostname); // should be an IP address
				}
			}
		} else {
			$hostname = 'Unknown';
		}		
		$host = crc32($hostname);

		// work out the referer
		$referer = urldecode($_SERVER['HTTP_REFERER']);
		if(empty($referer)) {
			$referer = "Unknown";
		} else {
			$pieces = parse_url($referer);
		    $referer = $pieces[scheme]."://".$pieces[host].$pieces[path];
		}
		if(strpos($referer, $_SERVER['SERVER_NAME'])>0) {
			$referer = "Internal";		
		}
		$ref = crc32($referer);

		if($this->documentIdentifier==0) {
			$docid=$this->config['error_page'];
		} else {
			$docid=$this->documentIdentifier;
		}

		if($docid==$this->config['error_page']) {
			exit; //stop logging 404's
		}
		
		// log the access hit
		$tbl = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."log_access";
		$sql = "INSERT INTO $tbl(visitor, document, timestamp, hour, weekday, referer, entry) VALUES('".$this->visitor."', '".$docid."', '".(time()+$this->config['server_offset_time'])."', '".$hour."', '".$weekday."', '".$ref."', '".$this->entrypage."')";
		$result = $this->dbQuery($sql);
		
		// check if the visitor exists in the database
		if(!isset($_SESSION['visitorLogged'])) {
			$tbl = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."log_visitors";
			$sql = "SELECT COUNT(*) FROM $tbl WHERE id='".$this->visitor."'";
			$result = $this->dbQuery($sql);
			$tmp = $this->fetchRow($result);
			$_SESSION['visitorLogged'] = $tmp['COUNT(*)'];
		} else {
			$_SESSION['visitorLogged'] = 1;
		}
		
		// log the visitor		
		if($_SESSION['visitorLogged']==0) {		
			$tbl = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."log_visitors";
			$sql = "INSERT INTO $tbl(id, os_id, ua_id, host_id) VALUES('".$this->visitor."', '".crc32($user_agent['operating_system'])."', '".$ua."', '".$host."')";
			$result = $this->dbQuery($sql);
			$_SESSION['visitorLogged'] = 1;
		}

		// check if the user_agent exists in the database
		if(!isset($_SESSION['userAgentLogged'])) {
			$tbl = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."log_user_agents";
			$sql = "SELECT COUNT(*) FROM $tbl WHERE id='".$ua."'";
			$result = $this->dbQuery($sql);
			$tmp = $this->fetchRow($result);
			$_SESSION['userAgentLogged'] = $tmp['COUNT(*)'];
		} else {
			$_SESSION['userAgentLogged'] = 1;
		}
		
		// log the user_agent		
		if($_SESSION['userAgentLogged']==0) {		
			$tbl = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."log_user_agents";
			$sql = "INSERT INTO $tbl(id, data) VALUES('".$ua."', '".$user_agent['user_agent']."')";
			$result = $this->dbQuery($sql);
			$_SESSION['userAgentLogged'] = 1;
		}

		// check if the os exists in the database
		if(!isset($_SESSION['operatingSystemLogged'])) {
			$tbl = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."log_operating_systems";
			$sql = "SELECT COUNT(*) FROM $tbl WHERE id='".$os."'";
			$result = $this->dbQuery($sql);
			$tmp = $this->fetchRow($result);
			$_SESSION['operatingSystemLogged'] = $tmp['COUNT(*)'];
		} else {
			$_SESSION['operatingSystemLogged'] = 1;
		}
		
		// log the os		
		if($_SESSION['operatingSystemLogged']==0) {		
			$tbl = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."log_operating_systems";
			$sql = "INSERT INTO $tbl(id, data) VALUES('".$os."', '".$user_agent['operating_system']."')";
			$result = $this->dbQuery($sql);
			$_SESSION['operatingSystemLogged'] = 1;
		}

		// check if the hostname exists in the database
		if(!isset($_SESSION['hostNameLogged'])) {
			$tbl = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."log_hosts";
			$sql = "SELECT COUNT(*) FROM $tbl WHERE id='".$host."'";
			$result = $this->dbQuery($sql);
			$tmp = $this->fetchRow($result);
			$_SESSION['hostNameLogged'] = $tmp['COUNT(*)'];
		} else {
			$_SESSION['hostNameLogged'] = 1;
		}
		
		// log the hostname		
		if($_SESSION['hostNameLogged']==0) {		
			$tbl = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."log_hosts";
			$sql = "INSERT INTO $tbl(id, data) VALUES('".$host."', '".$hostname."')";
			$result = $this->dbQuery($sql);
			$_SESSION['hostNameLogged'] = 1;
		}

		// log the referrer
		$tbl = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."log_referers";
		$sql = "REPLACE INTO $tbl(id, data) VALUES('".$ref."', '".$referer."')";
		$result = $this->dbQuery($sql);
		
		/*************************************************************************************/
		// update the logging cache
		$tbl = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."log_totals";
		$realMonth = strftime("%m");
		$realToday = strftime("%Y-%m-%d");

		// find out if we're on a new day
		$sql = "SELECT today, month FROM $tbl LIMIT 1";
		$result = $this->dbQuery($sql);
		$rowCount = $this->recordCount($result);
		if($rowCount<1) {
			$sql = "INSERT $tbl(today, month) VALUES('$realToday', '$realMonth')";
			$tmpresult = $this->dbQuery($sql);
			$sql = "SELECT today, month FROM $tbl LIMIT 1";
			$result = $this->dbQuery($sql);
		}
		$tmpRow = $this->fetchRow($result);
		$dbMonth = $tmpRow['month'];
		$dbToday = $tmpRow['today'];
		
		if($dbToday!=$realToday) {
			$sql = "UPDATE $tbl SET today='$realToday', piDay=0, viDay=0, visDay=0";
			$result = $this->dbQuery($sql);		
		}
		
		if($dbMonth!=$realMonth) {
			$sql = "UPDATE $tbl SET month='$realMonth', piMonth=0, viMonth=0, visMonth=0";
			$result = $this->dbQuery($sql);		
		}		
		
		// update the table for page impressions
		$sql = "UPDATE $tbl SET piDay=piDay+1, piMonth=piMonth+1, piAll=piAll+1";
		$result = $this->dbQuery($sql);		
		
		// update the table for visits
		if($this->entrypage==1) {
			$sql = "UPDATE $tbl SET viDay=viDay+1, viMonth=viMonth+1, viAll=viAll+1";
			$result = $this->dbQuery($sql);				
		}
		
		// get visitor counts from the logging tables
		$day      = date('j');
		$month    = date('n');
		$year     = date('Y');
		
		$monthStart = mktime(0,   0,  0, $month, 1, $year);
		$dayStart = mktime(0,   0,  0, $month, $day, $year);
		$dayEnd   = mktime(23, 59, 59, $month, $day, $year);
		
		$tmptbl = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."log_access";
		
		$sql = "SELECT COUNT(DISTINCT(visitor)) FROM $tmptbl WHERE timestamp > '".$dayStart."' AND timestamp < '".$dayEnd."'";
		$rs = $this->dbQuery($sql);
		$tmp = $this->fetchRow($rs);
		$visDay = $tmp['COUNT(DISTINCT(visitor))'];

		$sql = "SELECT COUNT(DISTINCT(visitor)) FROM $tmptbl WHERE timestamp > '".$monthStart."' AND timestamp < '".$dayEnd."'";
		$rs = $this->dbQuery($sql);
		$tmp = $this->fetchRow($rs);
		$visMonth = $tmp['COUNT(DISTINCT(visitor))'];

		$sql = "SELECT COUNT(DISTINCT(visitor)) FROM $tmptbl";
		$rs = $this->dbQuery($sql);
		$tmp = $this->fetchRow($rs);
		$visAll = $tmp['COUNT(DISTINCT(visitor))'];

		// update the table for visitors
		$sql = "UPDATE $tbl SET visDay=$visDay, visMonth=$visMonth, visAll=$visAll";
		$result = $this->dbQuery($sql);		
		/*************************************************************************************/
	
	}
	
	
	function match($elements, $rules) {
		if (!is_array($elements)) {
			$noMatch  = $elements;
			$elements = array($elements);
		} else {
			$noMatch = 'Not identified';
		}
		foreach ($rules as $rule) {
			if (!isset($result)) {
				foreach ($elements as $element) {
					$element = trim($element);
					$pattern = trim($rule['pattern']);
					if (preg_match($pattern, $element, $tmp)) {
						$result = str_replace(array('$1', '$2', '$3'), array(isset($tmp[1]) ? $tmp[1] : '', isset($tmp[2]) ? $tmp[2] : '', isset($tmp[3]) ? $tmp[3] : '' ), trim($rule['string']));
						break;
					}
				}
			} else {
				break;
			}
		}
		return isset($result) ? $result : $noMatch;
	}

	function userAgent($string) {
		if (preg_match('#\((.*?)\)#', $string, $tmp)) {
			$elements   = explode(';', $tmp[1]);
			$elements[] = $string;
		} else {
			$elements = array($string);
		}
		if ($elements[0] != 'compatible') {
			$elements[] = substr($string, 0, strpos($string, '('));
		}
		$result['operating_system'] = $this->match($elements,$GLOBALS['operating_systems']);
		$result['user_agent'] = $this->match($elements,$GLOBALS['user_agents']);
		return $result;
	}

// End of etomite class.

}

?>