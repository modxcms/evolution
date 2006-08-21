<?php
/**
 *	MODx Document Parser
 *	Function: This class contains the main document parsing functions
 *
 */

class DocumentParser {
    var $db;			// db object
	var $event,$Event;	// event object

	var $pluginEvent;

	var $nonCacheItems;	// for non cached items.
	var $debugEnabled, $debugCount, $debugOut;
	var $enableCompatMode; // enable compatiblity with previous tag syntax

	var $rs, $result, $sql, $table_prefix, $config, $debug,
		$documentIdentifier, $documentMethod, $documentGenerated, $documentContent, $tstart,
		$documentObject, $templateObject, $snippetObjects,
		$stopOnNotice, $executedQueries, $queryTime, $currentSnippet, $documentName,
		$aliases, $visitor, $entrypage, $documentListing, $dumpSnippets, $chunkCache,
		$snippetCache, $contentTypes, $dumpSQL, $queryCode, $virtualDir,
		$placeholders,$sjscripts,$jscripts,$loadedjscripts,$documentMap;

	// constructor
	function DocumentParser() {
		$this->loadExtension('DBAPI') or die('Could not load DBAPI class.'); // load DBAPI class			
		$this->dbConfig = &$this->db->config; // alias for backward compatibility
		$this->jscripts = array();
		$this->sjscripts = array();
		$this->loadedjscripts = array();
		// events
		$this->event = new SystemEvent();
		$this->Event = &$this->event;  //alias for backward compatibility
		$this->pluginEvent = array();
		$this->nonCacheItems = array();
		$this->enableCompatMode = true; 

		// set track_errors ini variable
		@ini_set("track_errors", "1"); // enable error tracking in $php_errormsg
	}

	// loads an extension from the extenders folder
	function loadExtension($extname){
		global $base_path;
		global $database_type;
		$xpath = $base_path.'/manager/includes/extenders/';
		$objName = strtolower($extname);
		$clsFile = $xpath.$objName.'.class.php';
		if($extname=='DBAPI' && !isset($this->db)){
			include_once($xpath.'dbapi.'.$database_type.'.class.inc.php');
			$this->db = new DBAPI;
		}
		else if($extname=='ManagerAPI' && !isset($this->manager)){
			include_once($xpath.'manager.api.class.inc.php');
			$this->manager = new ManagerAPI;
		}
		// support for addtional extensions - by Raymond
		elseif (!isset($this->{$objName})) {
			if(!file_exists($clsFile)) return false;
			else {
				include_once($clsFile);
				$classname = $extname.'_Extension';
				$this->{$objName} = new $classname;
			}
		}
		return true;
	}

	function getMicroTime() {
	   list($usec, $sec) = explode(' ', microtime());
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
					$this->messageQuit('Redirection attempt failed - please ensure the document you\'re trying to redirect to exists. <p>Redirection URL: <i>'.$url.'</i></p>');
				} else {
					$currentNumberOfRedirects += 1;
					if(strpos($url, "?")>0) {
						$url .= "&err=$currentNumberOfRedirects";
					} else {
						$url .= "?err=$currentNumberOfRedirects";
					}
				}
			}
			if ($type=='REDIRECT_REFRESH') {
				$header = 'Refresh: 0;URL='.$url;
			} elseif ($type=='REDIRECT_META') {
				$header = '<META HTTP-EQUIV="Refresh" CONTENT="0; URL='.$url.'" />';
				echo $header;
				exit;
			} elseif ($type=='REDIRECT_HEADER' || empty($type)) {
	            // check if url has /$base_url 
	            global $base_url,$site_url;
	            if (substr($url,0,strlen($base_url))==$base_url) {
	                // append $site_url to make it work with Location:
	                $url = $site_url.substr($url,strlen($base_url));
	            }
				if(strpos($url, "\n") === false) {
					$header = 'Location: '.$url;
				} else {
					$this->messageQuit('No newline allowed in redirect url.');
				}
			}
			header($header);
			$this->postProcess();
		}
	}

	function sendErrorPage() {
		// invoke OnPageNotFound event
		global $redirect_error;
		$redirect_error=1;
		$this->invokeEvent('OnPageNotFound');
		$this->sendRedirect($this->makeUrl($this->config['error_page'],'','&refurl='.urlencode($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'])), 1);
	}

	function sendUnauthorizedPage() {
		// invoke OnPageUnauthorized event
		$this->invokeEvent('OnPageUnauthorized');
		$this->sendRedirect($this->makeUrl($this->config['unauthorized_page'],'','&refurl='.urlencode($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'])),1);
	}

	// function to connect to the database
	// - deprecated use $modx->db->connect()
	function dbConnect() {
		$this->db->connect();
		$this->rs = $this->db->conn; // for compatibility
	}

	// function to query the database
	// - deprecated use $modx->db->query()
	function dbQuery($sql) {
		return $this->db->query($sql);
	}

	// function to count the number of rows in a record set
	function recordCount($rs) {
		return $this->db->getRecordCount($rs);
	}

	// - deprecated, use $modx->db->getRow()
	function fetchRow($rs, $mode='assoc') {
		return $this->db->getRow($rs,$mode);
	}

	// - deprecated, use $modx->db->getAffectedRows()
	function affectedRows($rs) {
		return $this->db->getAffectedRows($rs);
	}

	// - deprecated, use $modx->db->getInsertId()
	function insertId($rs) {
		return $this->db->getInsertId($rs);
	}

	// function to close a database connection
	// - deprecated, use $modx->db->disconnect()
	function dbClose() {
		$this->db->disconnect();
	}

	function getSettings() {
		global $base_url, $base_path, $site_url;
		if(file_exists($base_path.'/assets/cache/siteCache.idx.php')) {
			include_once $base_path.'/assets/cache/siteCache.idx.php';
		} else {
			$result = $this->dbQuery('SELECT setting_name, setting_value FROM '.$this->getFullTableName('system_settings'));
			while ($row = $this->fetchRow($result, 'both')) {
				$this->config[$row[0]] = $row[1];
			}
		}

		// store base_url and base_path inside config array
		$this->config['base_url'] = $base_url;
		$this->config['base_path'] = $base_path;
		$this->config['site_url'] = $site_url;

		// load user setting if user is logged in
		if($id=$this->getLoginUserID()){
			$usrType = $this->getLoginUserType();
			if (isset($usrType) && $usrType!='web') $usrType = 'mgr';

			if ($usrType == 'mgr') {
					// invoke the OnBeforeManagerInit event
					$this->invokeEvent("OnBeforeManagerInit"); 
			}

			if(isset($_SESSION[$usrType.'UsrConfigSet'])) {
				$usrSettings = &$_SESSION[$usrType.'UsrConfigSet'];
			}
			else {
				$usrSettings = array();
				if ($usrType=='web') $query = $this->getFullTableName('web_user_settings').' WHERE webuser=\''.$id.'\'';
				else $query = $this->getFullTableName('user_settings').' WHERE user=\''.$id.'\'';
				$result = $this->dbQuery('SELECT setting_name, setting_value FROM '.$query);
				while ($row = $this->fetchRow($result, 'both')) $usrSettings[$row[0]] = $row[1];
				if(isset($usrType)) $_SESSION[$usrType.'UsrConfigSet'] = $usrSettings; // store user settings in session
			}			
			$this->config = array_merge($this->config,$usrSettings);
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
		$docIdentifier= $this->config['site_start'];
		switch($method) {
			case 'alias' :
				$docIdentifier= $this->db->escape($_REQUEST['q']);
			break;
			case 'id' :
				if(!is_numeric($_REQUEST['id'])) {
					$this->messageQuit("ID passed in request is NaN!");
				} else {
					$docIdentifier= intval($_REQUEST['id']);
				}
			break;
		}
		return $docIdentifier;
	}

	// check for manager login session
	function checkSession() {
		if(isset($_SESSION['mgrValidated'])) {
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

	// check if site is offline
	function checkSiteStatus() {
		$siteStatus = $this->config['site_status'];
		if($siteStatus==1) {
			// site online
			return true;
		} elseif($siteStatus==0 && $this->checkSession()) {
			// site online but launched via the manager
			return true;
		} else {
			// site is offline
			return false;
		}
	}

	function cleanDocumentIdentifier($qOrig) { // modx updates
		$q = $qOrig;
		// First remove any / before or after
		if ($q && $q[ strlen( $q) - 1] == '/') $q = substr( $q,0,-1);
		if ($q && $q[ 0 ] == '/') $q = substr($q,1);
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
		$cacheFile = "assets/cache/docid_".$id.".pageCache.php";
		if(file_exists($cacheFile)) {
			$this->documentGenerated=0;
			$flContent = implode("",file($cacheFile));
			$flContent = substr($flContent,37); // remove php header
			$a = explode("<!--__MODxCacheSpliter__-->",$flContent,2);
			if(count($a)==1) return $a[0]; // return only document content
			else {
				$docObj = unserialize($a[0]); // rebuild document object
				// check page security
				if($docObj['privateweb'] && isset($docObj['__MODxDocGroups__'])) {
					$pass = false;
					$usrGrps = $this->getUserDocGroups();
					$docGrps = explode(",",$docObj['__MODxDocGroups__']);
					// check is user has access to doc groups
					if(is_array($usrGrps)) {
						foreach($usrGrps as $k=>$v) if(in_array($v,$docGrps)) {$pass=true;break;}
					}
					// diplay error pages is user has no access to cached doc
					if(!$pass) {
						if ($this->config['unauthorized_page']) {
							// check if file is not public
							$tbldg = $this->getFullTableName("document_groups");
							$secrs = $this->dbQuery("SELECT id FROM $tbldg WHERE document = '".$id."' LIMIT 1;");
							if($secrs) $seclimit = mysql_num_rows($secrs);
						}
						if ($seclimit>0)  {
							// match found but not publicly accessible, send the visitor to the unauthorized_page
							$this->sendUnauthorizedPage();
							exit; // stop here
						}
						else {
							// no match found, send the visitor to the error_page
							$this->sendErrorPage();
							exit; // stop here
						}						
					}
				}
				unset($docObj['__MODxDocGroups__']);
				$this->documentObject = $docObj;
				return $a[1];	// return document content
			}
		} else {
			$this->documentGenerated=1;
			return "";
		}
	}

	function outputContent($source,$ignoreEvents=false) {

		//$source = $this->rewriteUrls($source);

		$totalTime = ($this->getMicroTime() - $this->tstart);
		$queryTime = $this->queryTime;
		$phpTime = $totalTime-$queryTime;

		$queryTime = sprintf("%2.4f s", $queryTime);
		$totalTime = sprintf("%2.4f s", $totalTime);
		$phpTime = sprintf("%2.4f s", $phpTime);
		$contentSource = $this->documentGenerated==1 ? "database" : "cache";
		$queries = isset($this->executedQueries) ? $this->executedQueries : 0 ;

		// send out content-type and content-disposition headers
		if(IN_PARSER_MODE=="true") {
			$type = !empty($this->contentTypes[$this->documentIdentifier]) ? $this->contentTypes[$this->documentIdentifier] : "text/html";
			header('Content-Type: '.$type.'; charset='.$this->config['etomite_charset']);
			if(($this->documentIdentifier == $this->config['error_page']) || $redirect_error) header('HTTP/1.0 404 Not Found');
			if(!$this->checkPreview() && $this->documentObject['content_dispo']==1) {
				if($this->documentObject['alias']) $name = $this->documentObject['alias'];
				else {
					// strip title of special characters
					$name = $this->documentObject['pagetitle'];
					$name = strip_tags($name);
					$name = strtolower($name);
					$name = preg_replace('/&.+?;/', '', $name); // kill entities
					$name = preg_replace('/[^\.%a-z0-9 _-]/', '', $name);
					$name = preg_replace('/\s+/', '-', $name);
					$name = preg_replace('|-+|', '-', $name);
					$name = trim($name, '-');					
				}
				$header = 'Content-Disposition: attachment; filename='.$name;
				header($header);
			}
		}

		if($this->dumpSQL) {
			$source .= $this->queryCode;
		}

		// insert special tags
		$tags = array("[[^q]]", "[[^qt]]", "[[^p]]", "[[^t]]", "[[^s]]",'[[^date]]','[[^time]]');
		$vals = array($queries,$queryTime,$phpTime,$totalTime,$contentSource,date("F j, Y"),date('g:i a'));
		$source = str_replace($tags, $vals, $source);

		// invoke OnWebPagePrerender event
		if(!$ignoreEvents) {
			$this->documentOutput = $source;
			$this->invokeEvent("OnWebPagePrerender"); // work on it via documentOutput
			$source = $this->documentOutput;
		}

		// debug output -added by Raymond
		if($this->debugEnabled) {
			$this->debug('Timing',"<b style='color:#800000'>Database:</b> $queryTime &nbsp;|&nbsp;&nbsp;<b style='color:#800000'>PHP:</b> $phpTime &nbsp;&nbsp;|&nbsp;&nbsp;<b style='color:#800000'>Total:</b> $totalTime");
			$this->regClientHTMLBlock($this->getDebugOutput());
		}

 		// remove unused [[ ]] - added by Raymond
 		if(strpos($source,'[[')!==false) {
 			$source = preg_replace('~\[\[(.*?)\]\]~s', '', $source);
 		}

		// insert remaining client-side jscripts & CSS scripts -added by Raymond
		$this->insertClientScripts($source);
 		
        // send output to client
		echo $source;
		ob_end_flush();
        
	}


	function checkPublishStatus(){
		include $this->config["base_path"]."assets/cache/sitePublishing.idx.php";
		$timeNow = time()+$this->config['server_offset_time'];
		if($cacheRefreshTime<=$timeNow && $cacheRefreshTime!=0) {
			// now, check for documents that need publishing
			$sql = "UPDATE ".$this->getFullTableName("site_content")." SET published=1, publishedon=".time().", publishedby=".$this->getLoginUserID()." WHERE ".$this->getFullTableName("site_content").".pub_date < $timeNow AND ".$this->getFullTableName("site_content").".pub_date!=0";
			if(@!$result = $this->dbQuery($sql)) {
				$this->messageQuit("Execution of a query to the database failed", $sql);
			}

			// now, check for documents that need un-publishing
			$sql = "UPDATE ".$this->getFullTableName("site_content")." SET published=0, publishedon=0, publishedby=0 WHERE ".$this->getFullTableName("site_content").".unpub_date < $timeNow AND ".$this->getFullTableName("site_content").".unpub_date!=0";
			if(@!$result = $this->dbQuery($sql)) {
				$this->messageQuit("Execution of a query to the database failed", $sql);
			}

			// clear the cache
			$basepath = $this->config["base_path"]."assets/cache/";
			if ($handle = opendir($basepath)) {
				$filesincache = 0;
				$deletedfilesincache = 0;
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != "..") {
						$filesincache += 1;
						if (preg_match ("/\.pageCache/", $file)) {
							$deletedfilesincache += 1;
							while(!unlink($basepath."/".$file));
						}
					}
				}
				closedir($handle);
			}

			// update publish time file
			$timesArr = array();
			$sql = "SELECT MIN(pub_date) AS minpub FROM ".$this->getFullTableName("site_content")." WHERE pub_date>$timeNow";
			if(@!$result = $this->dbQuery($sql)) {
				$this->messageQuit("Failed to find publishing timestamps", $sql);
			}
			$tmpRow = $this->fetchRow($result);
			$minpub = $tmpRow['minpub'];
			if($minpub!=NULL) {
				$timesArr[] = $minpub;
			}

			$sql = "SELECT MIN(unpub_date) AS minunpub FROM ".$this->getFullTableName("site_content")." WHERE unpub_date>$timeNow";
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

			$basepath = $this->config["base_path"]."assets/cache";
			$fp = @fopen($basepath."/sitePublishing.idx.php","wb");
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
		if($this->documentGenerated==1 && $this->documentObject['cacheable']==1 && $this->documentObject['type']=='document' && $this->documentObject['published']==1) {
			$basepath = $this->config["base_path"]."assets/cache";
			// invoke OnBeforeSaveWebPageCache event
			$this->invokeEvent("OnBeforeSaveWebPageCache");
			if($fp = @fopen($basepath."/docid_".$this->documentIdentifier.".pageCache.php","w")){
				// get and store document groups inside document object. Document groups will be used to check security on cache pages
				$sql = "SELECT document_group FROM ".$this->getFullTableName("document_groups")." WHERE document='".$this->documentIdentifier."'";				
				$docGroups = $this->db->getColumn("document_group",$sql);
				if (is_array($docGroups)) $this->documentObject['__MODxDocGroups__'] = implode(",",$docGroups);
				$docObjSerial = serialize($this->documentObject);
				$cacheContent = $docObjSerial."<!--__MODxCacheSpliter__-->".$this->documentContent;
				fputs($fp,"<?php die('Unauthorized access.'); ?>$cacheContent");
				fclose($fp);
			}
		}

		// Useful for example to external page counters/stats packages
		if($this->config['track_visitors']==1) {
			$this->invokeEvent('OnWebPageComplete');
		}
		// end post processing
	}

	function mergeDocumentMETATags($template) {
		if($this->documentObject['haskeywords']==1) {
			// insert keywords
			$keywords = implode(", ",$this->getKeywords());
			$metas = "\t<meta name=\"keywords\" content=\"$keywords\" />\n";
		}
		if($this->documentObject['hasmetatags']==1){
			// insert meta tags
			$tags = $this->getMETATags();
			foreach ($tags as $n=>$col) {
				$tag = strtolower($col['tag']);
				$tagvalue = $col['tagvalue'];
				$tagstyle = $col['http_equiv'] ? 'http-equiv':'name';
				$metas.= "\t<meta $tagstyle=\"$tag\" content=\"$tagvalue\" />\n";
			}
		}
		$template = preg_replace("/(<head>)/i", "\\1\n".$metas, $template);
		return $template;
	}

	// mod by Raymond
	function mergeDocumentContent($template) {
		// load new parser extension
		$this->loadExtension('Parser');
		// convert old tags to new
		if($this->enableCompatMode) $this->convertTags($template);
		$template = $this->parser->parseText($template,'[[*');
		return $template;
	}

	// modified by Raymond
	function mergeSettingsContent($template) {
		// load new parser extension
		$this->loadExtension('Parser');
		// convert old tags to new
		if($this->enableCompatMode) $this->convertTags($template);
		$template = $this->parser->parseText($template,'[[++');
		return $template;
	}
       
	// modified by Raymond
	function mergeChunkContent($content) {
		// load new parser extension
		$this->loadExtension('Parser');
		// convert old tags to new
		if($this->enableCompatMode) $this->convertTags($content);
		$content = $this->parser->parseText($content,'[[$');
		return $content;
	}     

	// modified by Raymond
	function mergePlaceholderContent($content) {
		// load new parser extension
		$this->loadExtension('Parser');
		// convert old tags to new
		if($this->enableCompatMode) $this->convertTags($content);
		$content = $this->parser->parseText($content,'[[+');
		return $content;
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
			$in = '!\[\~([0-9]+)\~\]!ise'; // Use preg_replace with /e to make it evaluate PHP
				$isfriendly = ( $this->config['friendly_alias_urls'] == 1 ? 1 : 0 );
				$pref = $this->config['friendly_url_prefix'];
				$suff = $this->config['friendly_url_suffix'];
				$thealias = '$aliases["\\1"]';
				$found_friendlyurl = "\$this->makeFriendlyURL('$pref','$suff',$thealias)";
				$not_found_friendlyurl = "\$this->makeFriendlyURL('$pref','$suff','".'\\1'."')";
				$out = "({$isfriendly} && isset({$thealias}) ? {$found_friendlyurl} : {$not_found_friendlyurl})";
			$documentSource = preg_replace($in, $out, $documentSource);
		}
		else {
			$in = '!\[\~([0-9]+)\~\]!is';
			$out = "index.php?id=".'\1';
			$documentSource = preg_replace($in, $out, $documentSource);
		}
		return $documentSource;
	}

	/**
	 * name: getDocumentObject  - used by parser
	 * desc: returns a document object - $method: alias, id
	 */
	function getDocumentObject($method,$identifier){
		global $changed;
		global $myObject;
		$tblsc = $this->getFullTableName("site_content");
		$tbldg = $this->getFullTableName("document_groups");
			 
		// get document groups for current user
		if($docgrp = $this->getUserDocGroups()) $docgrp = implode(",",$docgrp);
		// get document
		$access = ($this->isFrontend() ? "sc.privateweb=0":"1='".$_SESSION['mgrRole']."' OR sc.privatemgr=0").
				  (!$docgrp ? "":" OR dg.document_group IN ($docgrp)");
		$sql = "SELECT sc.*
				FROM $tblsc sc
				LEFT JOIN $tbldg dg ON dg.document = sc.id
				WHERE sc.".$method." = '".$identifier."'
				AND ($access) LIMIT 1;";
		$result = $this->db->query($sql);
		$rowCount = $this->recordCount($result);
		if($rowCount<1) {
			if ($this->config['unauthorized_page']) {
				// Fix for FS #375 - netnoise 2006/08/14
				if($method!='id') $identifier = $this->cleanDocumentIdentifier($identifier);
				if(!is_numeric($identifier) && array_key_exists($identifier, $this->documentListing)) {
				 $identifier = $this->documentListing[$identifier];
				 $method = 'id';
				}
		
				// check if file is not public
				$secrs = $this->dbQuery("SELECT id FROM $tbldg WHERE document = '".$identifier."' LIMIT 1;");
				if($secrs) $seclimit = mysql_num_rows($secrs);
			}
			if ($seclimit>0)  {
				// match found but not publicly accessible, send the visitor to the unauthorized_page
				$this->sendUnauthorizedPage();
				exit; // stop here
			}
			else {
				// no match found, send the visitor to the error_page
				$this->invokeEvent('OnPageNotFound');
				$this->documentIdentifier = $this->config['error_page'];
				$this->documentMethod = "id";
				$this->documentObject = $this->getDocumentObject(
						$this->documentMethod,
						$this->documentIdentifier
						);
				$changed = 1;
				$myObject = $this->documentObject; 
			}
		}
		if ($changed) { return $myObject; }

		# this is now the document :) #
		$documentObject = $this->fetchRow($result);

		// load TVs and merge with document - Orig by Apodigm - Docvars
		$tbn = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix'];
		$sql = "SELECT tv.*, IF(tvc.value!='',tvc.value,tv.default_text) as value ";
		$sql.= "FROM ".$tbn."site_tmplvars tv ";
		$sql.= "INNER JOIN ".$tbn."site_tmplvar_templates tvtpl ON tvtpl.tmplvarid = tv.id ";
		$sql.= "LEFT JOIN ".$tbn."site_tmplvar_contentvalues tvc ON tvc.tmplvarid=tv.id AND tvc.contentid = '".$this->documentIdentifier."' ";
		$sql.= "WHERE tvtpl.templateid = '".$documentObject['template']."'";
		$rs = $this->dbQuery($sql);
		$rowCount = $this->recordCount($rs);
		if($rowCount>0) {
			for($i=0;$i<$rowCount;$i++) {
				$row = $this->fetchRow($rs);
				$tmplvars[$row['name']] = array($row['name'],$row['value'],$row['display'],$row['display_params'],$row['type']);
			}
			$documentObject = array_merge($documentObject,$tmplvars);
		}
		return $documentObject;
	}

	/**
	 * name: parseDocumentSource - used by parser
	 * desc: return document source aftering parsing tvs, snippets, chunks, etc.
	 */
	function parseDocumentSource(&$source,$buildCache = false){

		// load new parser extension
		$this->loadExtension('Parser');

		// invoke OnParseDocument event - to be optimised
		$this->documentOutput = $source;
		$this->invokeEvent("OnParseDocument");	// work on it via $modx->documentOutput
		$source = $this->documentOutput;

		// parse the source and store result in documentOutput
		$this->parser->nonCacheItems = &$this->nonCacheItems; // pass non cached items to parser
		$this->parser->enableCompatMode = $this->enableCompatMode;
		$this->documentOutput = $this->parser->parseText($source,'[[',$buildCache);

	}


	/**
	 * executeParser 
	 * return document source aftering parsing tvs, snippets, chunks, etc.
	 */
	function executeParser() {
		// error_reporting(0);
		if (version_compare( phpversion(), "5.0.0", ">=" ))
		   set_error_handler(array(&$this,"phpError"), E_ALL);
		else
		   set_error_handler(array(&$this,"phpError"));

		// is this necessary?	
		//$this->db->connect();

		// get the settings
		if(empty($this->config)) {
			$this->getSettings();
		}

		// IIS friendly url fix - postback not supported on iis6
		if($this->config['friendly_urls']==1 && strpos($_SERVER['SERVER_SOFTWARE'],'Microsoft-IIS')!==false) {
			$url = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING']:'';
			$err = substr($url,0,3);
			if($err=='404'||$err=='405') {
				$k = array_keys($_GET);
				unset($_GET[$k[0]]); unset($_REQUEST[$k[0]]); // remove 404,405 entry
				$qp = parse_url(str_replace($this->config['site_url'],'',substr($url,4)));
				$_SERVER['QUERY_STRING']= isset($qp['query']) ? $qp['query']:'';
				if(!empty($qp['query'])) {
					parse_str($qp['query'],$qv);
					foreach($qv as $n=>$v) $_REQUEST[$n]=$_GET[$n]=$v;
				}
				$_SERVER['PHP_SELF'] = $this->config['base_url'].$qp['path'];
				$_REQUEST['q'] = $_GET['q'] = $qp['path'];
			}
		}


		// check site settings
		if(!$this->checkSiteStatus()) {
			if(!$this->config['site_unavailable_page']) {
				// display offline message
				$this->documentOutput = $this->config['site_unavailable_message'];
				$this->outputContent($this->documentOutput);
				exit; // stop processing here, as the site's offline
			}
			else {
				// setup offline page document settings
				$this->documentMethod = "id";
				$this->documentIdentifier = $this->config['site_unavailable_page'];
			}
		}
		else {
			// make sure the cache doesn't need updating
			$this->checkPublishStatus();

			// find out which document we need to display
			$this->documentMethod = $this->getDocumentMethod();
			$this->documentIdentifier = $this->getDocumentIdentifier($this->documentMethod);
		}

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
				if (array_key_exists($alias, $this->documentListing)) {
					$this->documentIdentifier = $this->documentListing[$alias];
				} else {
					$this->sendErrorPage();
				}
			}
			else {
				$this->documentIdentifier = $this->documentListing[$this->documentIdentifier];
			}
			$this->documentMethod = 'id';
		}

		// invoke OnWebPageInit event
		$this->invokeEvent("OnWebPageInit");

		// invoke OnLogPageView event
		if($this->config['track_visitors']==1) {
			 $this->invokeEvent("OnLogPageView");
		}


		// we now know the method and identifier, let's check the cache
		$this->documentContent = $this->checkCache($this->documentIdentifier);
		if($this->documentContent!="") {
			// invoke OnLoadWebPageCache  event
			$this->invokeEvent("OnLoadWebPageCache");
			// check for non-cached tags output
			if(strpos($this->documentContent, '[[')!==false) {
				// Parse document cached content
				$this->parseDocumentSource($this->documentContent);
			}
            else {
                $this->documentOutput = $this->documentContent;
            }		
		}
		else {
			// get document object
			$this->documentObject = $this->getDocumentObject(
				$this->documentMethod,
				$this->documentIdentifier
			);

			// write the documentName to the object
			$this->documentName = $this->documentObject['pagetitle'];

			// validation routines
			if($this->documentObject['deleted']==1) {
				$this->sendErrorPage();
			}

			//  && !$this->checkPreview()
			if($this->documentObject['published']==0) {
				// Can't view unpublished pages
				if(!$this->hasPermission('view_unpublished')) {
					$this->sendErrorPage();
				} else {
					// Inculde the necessary files to check document permissions
					include_once($this->config['base_path'].'/manager/processors/user_documents_permissions.class.php');
					$udperms = new udperms();
					$udperms->user = $this->getLoginUserID();
					$udperms->document = $this->documentIdentifier;
					$udperms->role = $_SESSION['mgrRole'];
					// Doesn't have access to this document
					if(!$udperms->checkPermissions()) {
						$this->sendErrorPage();
					}
				}
			}

			// check whether it's a reference
			if($this->documentObject['type']=="reference") {
				$refurl = $this->documentObject['content'];
				if(is_numeric($refurl)) {
					// if it's a bare document id
					$refurl = $this->makeUrl($refurl);
				} elseif(strpos($refurl,'[~') !== false) {					// if it's an internal docid tag, process it					$refurl = $this->rewriteUrls($refurl);
				}				$this->sendRedirect($refurl);			}
			
			// check if we should not hit this document
			if($this->documentObject['donthit']==1) {
				$this->config['track_visitors']=0;
			}

			// get the template and start parsing!
			if(!$this->documentObject['template']) $this->documentContent = "[*content*]"; // use blank template
			else {
				$sql = "SELECT * FROM ".$this->getFullTableName("site_templates")." WHERE ".$this->getFullTableName("site_templates").".id = '".$this->documentObject['template']."';";
				$result = $this->dbQuery($sql);
				$rowCount = $this->recordCount($result);
				if($rowCount>1) {
					$this->messageQuit("Incorrect number of templates returned from database", $sql);
				}
				elseif($rowCount==1) {
					$row = $this->fetchRow($result);
					$this->documentContent = $row['content'];
				}
			}

			// invoke OnLoadWebDocument event
			$this->invokeEvent("OnLoadWebDocument");

			// Insert META tags & keywords - template must have a <head> tag
			// note: we do it here so we can process things like [*TVs*] and [+placeholders+] from inside META tags
			if($this->documentObject['hasmetatags']==1||$this->documentObject['haskeywords']==1) {
				$this->documentContent = $this->mergeDocumentMETATags($this->documentContent);
			}

			// convert old tags to new
			if($this->enableCompatMode) {
				$this->convertTags($this->documentContent);
			}

			// Parse document content source and cache			
			$makeCache = $this->documentObject['cacheable']==1 ? true:false;
			$this->parseDocumentSource($this->documentContent,$makeCache);

			// insert client-side jscripts & CSS scripts
			$this->insertClientScripts($this->documentOutput);
			$this->insertClientScripts($this->documentContent);
			$this->clearClientScripts(); // reset scripts

		}

		register_shutdown_function(array(&$this,"postProcess")); // tell PHP to call postProcess when it shuts down
		$this->outputContent($this->documentOutput);
	}

	// converts old tags to new tag format
	function convertTags(&$source) {
		// replace old format
		$matches = array();
		$source = preg_replace('|\{\{(.*?)\}\}|', '[[$\\1]]', $source);	// {{}} -> [[$]]
		$source = preg_replace('|\[\*(.*?)\*\]|', '[[*\\1]]', $source);	// [**] -> [[*]]
		$source = preg_replace('|\[\((.*?)\)\]|', '[[++\\1]]', $source);// [()] -> [[++]]
		$source = preg_replace('|\[\+(.*?)\+\]|', '[[+\\1]]', $source);	// [++] -> [[+]]
		$source = preg_replace('|\[\~(.*?)\~\]|', '[[~\\1]]', $source);	// [~~] -> [[~]]
		$source = preg_replace('|\[\^(.*?)\^\]|', '[[^\\1]]', $source);	// [^^] -> [[^]]
		if(preg_match_all('|\[\!(.*?)\!\]|', $source,$matches)) {		// [!!] -> [[]]
			$limit = count($matches[0]);
			for($i=0; $i<$limit; $i++) {
				list($name) = explode('?',$matches[1][$i],2);
				$this->preventCaching($name);				
			}
			$source = preg_replace('|\[\!(.*?)\!\]|', '[[\\1]]', $source);	
		}
	}


	/***************************************************************************************/
	/* API functions																/
	/***************************************************************************************/

	// prevents snippet from being cached
	function preventCaching($name = '') {		
		$name = !empty($name) ? $name : $this->getSnippetName();
		if(!empty($name)) $this->nonCacheItems[$name] = 1;		
	}
	
	// output to debug screen - add by Raymond
	function debug($title,$value='') {
		if($this->debugEnabled) {
			$tmp = $value ? $value:$title;
			$title = !$value ? 'Output':$title;
			$value = !$value ? $tmp:$value;			
			$id = 'DBG'.($this->debugCount++);
			$ht = '<div style="width:98%;padding:3px;float:left;background-color:'.($this->debugCount%2==0? '#eeeeee':'#fff').';"><label for="DBG'.$id.'" style="display:block;width:150px;float:left;font-weight:bold">'.$title.':</label><div id="DBG'.$id.'" style="float:left">'.$value.'</div></div><div style="clear:left"></div>';
			$this->debugOut = $ht.$this->debugOut;
		}
	}
	
	// enable debug mode - add by Raymond
	function enableDebug($state,$stopOnNotice = false) {
		$this->debugEnabled = $state;
		$this->stopOnNotice = $stopOnNotice;
	}

    // added by Raymond 
	function getDebugOutput() {        
		return '<div align="left" style="position:absolute; top:0px; left:0px"><span onclick="var name=\'cms_debugout\'; var bug = document.getElementById ? document.getElementById(name):document.all[name]; if(bug) bug.style.display = bug.style.display==\'block\' ? \'none\':\'block\'; " style="cursor:pointer;background-color:#fff;border:1px solid #c0c0c0;padding:1px;"><img src="'.$this->config['base_url'].'manager/media/debug/bug.gif" align="left" />&nbsp;Debug&nbsp;</span>
				<div id="cms_debugout" style="width:100%;display:none;background-color:#fff;border-bottom:1px solid #808080;"><h3 style="border-top:1px solid #808080;margin:0;width:100%;padding-bottom:5px;padding-top:5px;background-color:#98BAE8;color:#fff">&nbsp;Debug Output</h3>'.$this->debugOut.'<div style="background-color:#98BAE8;">&nbsp;</div></div></div>'; // need a javascript window
	}

	function getParentIds($id, $height= 10, $parents= array()) {
		$parentId= 0;
		foreach ($this->documentMap as $mapEntry) {
			$parentId= array_search($id, $mapEntry);
			if ($parentId) {
				$parentKey= array_search($parentId, $this->documentListing);
				if (!$parentKey) {
					$parentKey= "$parentId";
				}
				$parents[$parentKey]= $parentId;
				break;
			}
		}
		$height--;
		if ($parentId && $height) { $parents= $parents + $this->getParentIds($parentId, $height, $parents); }
		return $parents;
	}

	function getChildIds($id, $depth= 10, $children= array()) {
		$c= null;
		foreach ($this->documentMap as $mapEntry) {
			if (isset ($mapEntry[$id])) {
				$childId= $mapEntry[$id];
				$childKey= array_search($childId, $this->documentListing);
				if (!$childKey) {
					$childKey= "$childId";
				}
				$c[$childKey]= $childId;
			}
		}
		$depth--;
		if (is_array($c)) {
			if (is_array($children)) {
				$children= $children + $c;
			} else {
				$children= $c;
			}
			if ($depth) {
					foreach ($c as $child) {
						$children= $children + $this->getChildIds($child, $depth, $children);
					}
			}
		}
		return $children;
	}

	# Displays a javascript alert message in the web browser
	function webAlert($msg,$url=""){
		$msg = addslashes(mysql_escape_string($msg));
		if(substr(strtolower($url),0,11)=="javascript:") {
			$act = "__WebAlert();";
			$fnc = "function __WebAlert(){".substr($url,11)."};";
		}
		else {
			$act = ($url ? "window.location.href='".addslashes($url)."';":"");
		}
		$html = "<script>$fnc window.setTimeout(\"alert('$msg');$act\",100);</script>";
		if($this->isFrontend($html)) $this->regClientScript($html);
		else {
			echo $html;
		}
	}

	# Returns true if user has the currect permission
	function hasPermission($pm) {
		$state = false;
		$pms = $_SESSION['mgrPermissions'];
		if($pms) $state = ($pms[$pm]==1);
		return $state;
	}

	# Add an a alert message to the system event log
	function logEvent($evtid,$type,$msg,$source='Parser') {
		$msg = mysql_escape_string($msg);
		$source = mysql_escape_string($source);
		$evtid = intval($evtid);
		if ($type<1) $type = 1; else if($type>3) $type = 3; // Types: 1 = information, 2 = warning, 3 = error
		$sql = "INSERT INTO ".$this->getFullTableName("event_log")."(eventid,type,createdon,source,description,user) ".
				"VALUES($evtid,$type,".time().",'$source','$msg','".$this->getLoginUserID()."')";
		$ds = $this->dbQuery($sql);
		if(!$ds) {
			echo "Error while inserting event log into database.";
			exit;
		}

	}

	# Returns true if parser is executed in backend (manager) mode
	function isBackend(){
		return $this->insideManager() ? true:false;
	}

	# Returns true if parser is executed in frontend mode
	function isFrontend(){
		return !$this->insideManager() ? true:false;
	}

	function getAllChildren($id=0, $sort='menuindex', $dir='ASC', $fields='id, pagetitle, description, parent, alias, menutitle') {
		$tblsc = $this->getFullTableName("site_content");
		$tbldg = $this->getFullTableName("document_groups");
		// modify field names to use sc. table reference
		$fields = 'sc.'.implode(',sc.',preg_replace("/^\s/i","",explode(',',$fields)));
		$sort = 'sc.'.implode(',sc.',preg_replace("/^\s/i","",explode(',',$sort)));
		// get document groups for current user
		if($docgrp = $this->getUserDocGroups()) $docgrp = implode(",",$docgrp);
		// build query
		$access = ($this->isFrontend() ? "sc.privateweb=0":"1='".$_SESSION['mgrRole']."' OR sc.privatemgr=0").
				  (!$docgrp ? "":" OR dg.document_group IN ($docgrp)");
		$sql = "SELECT DISTINCT $fields FROM $tblsc sc
				LEFT JOIN $tbldg dg on dg.document = sc.id
				WHERE sc.parent = '$id'
				AND ($access)
				ORDER BY $sort $dir;";
		$result = $this->dbQuery($sql);
		$resourceArray = array();
		for($i=0;$i<@$this->recordCount($result);$i++)  {
			array_push($resourceArray,@$this->fetchRow($result));
		}
		return $resourceArray;
	}

	function getActiveChildren($id=0, $sort='menuindex', $dir='ASC', $fields='id, pagetitle, description, parent, alias, menutitle') {
		$tblsc = $this->getFullTableName("site_content");
		$tbldg = $this->getFullTableName("document_groups");

		// modify field names to use sc. table reference
		$fields = 'sc.'.implode(',sc.',preg_replace("/^\s/i","",explode(',',$fields)));
		$sort = 'sc.'.implode(',sc.',preg_replace("/^\s/i","",explode(',',$sort)));
		// get document groups for current user
		if($docgrp = $this->getUserDocGroups()) $docgrp = implode(",",$docgrp);
		// build query
		$access = ($this->isFrontend() ? "sc.privateweb=0":"1='".$_SESSION['mgrRole']."' OR sc.privatemgr=0").
				  (!$docgrp ? "":" OR dg.document_group IN ($docgrp)");
		$sql = "SELECT DISTINCT $fields FROM $tblsc sc
				LEFT JOIN $tbldg dg on dg.document = sc.id
				WHERE sc.parent = '$id' AND sc.published=1 AND sc.deleted=0
				AND ($access)
				ORDER BY $sort $dir;";
		$result = $this->dbQuery($sql);
		$resourceArray = array();
		for($i=0;$i<@$this->recordCount($result);$i++)  {
			array_push($resourceArray,@$this->fetchRow($result));
		}
		return $resourceArray;
	}

	function getDocumentChildren($parentid=0, $published=1, $deleted=0, $fields="*", $where='', $sort="menuindex", $dir="ASC", $limit="") {
		$limit = ($limit != "") ? "LIMIT $limit" : "";
		$tblsc = $this->getFullTableName("site_content");
		$tbldg = $this->getFullTableName("document_groups");
		// modify field names to use sc. table reference
		$fields = 'sc.'.implode(',sc.',preg_replace("/^\s/i","",explode(',',$fields)));
		$sort = ($sort=="") ? "":'sc.'.implode(',sc.',preg_replace("/^\s/i","",explode(',',$sort)));
		if ($where!='') $where = 'AND '.$where;
		// get document groups for current user
		if($docgrp = $this->getUserDocGroups()) $docgrp = implode(",",$docgrp);
		// build query
		$access = ($this->isFrontend() ? "sc.privateweb=0":"1='".$_SESSION['mgrRole']."' OR sc.privatemgr=0").
				  (!$docgrp ? "":" OR dg.document_group IN ($docgrp)");
		$sql = "SELECT DISTINCT $fields
				FROM $tblsc sc
				LEFT JOIN $tbldg dg on dg.document = sc.id
				WHERE sc.parent = '$parentid' AND sc.published=$published AND sc.deleted=$deleted $where
				AND ($access) ".
				($sort ? " ORDER BY $sort $dir ":"")." $limit ";
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
			$tblsc = $this->getFullTableName("site_content");
			$tbldg = $this->getFullTableName("document_groups");
			// modify field names to use sc. table reference
			$fields = 'sc.'.implode(',sc.',preg_replace("/^\s/i","",explode(',',$fields)));
			$sort = ($sort=="") ? "":'sc.'.implode(',sc.',preg_replace("/^\s/i","",explode(',',$sort)));
			if ($where!='') $where = 'AND '.$where;
			// get document groups for current user
			if($docgrp = $this->getUserDocGroups()) $docgrp = implode(",",$docgrp);
			$access = ($this->isFrontend() ? "sc.privateweb=0":"1='".$_SESSION['mgrRole']."' OR sc.privatemgr=0").
					  (!$docgrp ? "":" OR dg.document_group IN ($docgrp)");
			$sql = "SELECT DISTINCT $fields FROM $tblsc sc
					LEFT JOIN $tbldg dg on dg.document = sc.id
					WHERE (sc.id IN (".join($ids, ",").") AND sc.published=$published AND sc.deleted=$deleted $where)
					AND ($access) ".
					($sort ? " ORDER BY $sort $dir":"")." $limit ";
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
			$docs = $this->getDocuments($tmpArr, $published, $deleted, $fields,"","","",1);
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
			$tblsc = $this->getFullTableName("site_content");
			$tbldg = $this->getFullTableName("document_groups");
			$activeSql = $active==1 ? "AND sc.published=1 AND sc.deleted=0" : "" ;
			// modify field names to use sc. table reference
			$fields = 'sc.'.implode(',sc.',preg_replace("/^\s/i","",explode(',',$fields)));
			// get document groups for current user
			if($docgrp = $this->getUserDocGroups()) $docgrp = implode(",",$docgrp);
			$access = ($this->isFrontend() ? "sc.privateweb=0":"1='".$_SESSION['mgrRole']."' OR sc.privatemgr=0").
					  (!$docgrp ? "":" OR dg.document_group IN ($docgrp)");
			$sql = "SELECT $fields
					FROM $tblsc sc
					LEFT JOIN $tbldg dg on dg.document = sc.id
					WHERE (sc.id=$pageid $activeSql)
					AND ($access)
					LIMIT 1 ";
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

	function getSnippetId() {
		if ($this->currentSnippet) {
			$tbl = $this->getFullTableName("site_snippets");
			$rs = $this->dbQuery("SELECT id FROM $tbl WHERE name='".mysql_escape_string($this->currentSnippet)."' LIMIT 1");
			$row = @$this->fetchRow($rs);
			if ($row['id']) return $row['id'];
		}
		return 0;
	}

	function getSnippetName() {
		return isset($this->currentSnippet) ? $this->currentSnippet : '';
	}

	function clearCache() {
		$basepath = $this->config["base_path"]."assets/cache";
		if (@$handle = opendir($basepath)) {
			$filesincache = 0;
			$deletedfilesincache = 0;
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					$filesincache += 1;
					if (preg_match ("/\.pageCache/", $file)) {
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

	function makeUrl($id, $alias='', $args='', $scheme = '') {
		$url= '';
		$virtualDir= '';
		if(!is_numeric($id)) {
			$this->messageQuit('`'.$id.'` is not numeric and may not be passed to makeUrl()');
		}
		if($args!='' && $this->config['friendly_urls']==1) {
			// add ? to $args if missing
			$c = substr($args,0,1);
			if ($c=='&') $args = '?'.substr($args,1);
			elseif ($c!='?') $args = '?'.$args; 
		}
		elseif($args!='') {
			// add & to $args if missing
			$c = substr($args,0,1);
			if ($c=='?') $args = '&'.substr($args,1);
			elseif ($c!='&') $args = '&'.$args; 
		}
		if($this->config['friendly_urls']==1 && $alias!='') {
			$url= $this->config['friendly_url_prefix'].$alias.$this->config['friendly_url_suffix'].$args;
		} elseif($this->config['friendly_urls']==1 && $alias=='') {
			$alias = $id;
			if($this->config['friendly_alias_urls']==1) {
				$al = $this->aliasListing[$id];
				$alPath = !empty($al['path'])? $al['path'] . '/': '';
				if($al && $al['alias']) $alias = $al['alias'];
			}
			$alias = $alPath . $this->config['friendly_url_prefix'].$alias.$this->config['friendly_url_suffix'];
			$url= $alias.$args;
		} else {
			$url= 'index.php?id='.$id.$args;
		}

		$host = $this->config['base_url'];
		// check if scheme argument has been set
		if($scheme!='') {
			// for backward compatibility - check if the desired scheme is different than the current scheme
			if (is_numeric($scheme) && $scheme != $_SERVER['HTTPS']) { 
				$scheme = ($_SERVER['HTTPS'] ? 'http' : 'https');
			}

			// to-do: check to make sure that $site_url incudes the url :port (e.g. :8080)
			$host = $scheme=='full' ? $this->config['site_url'] : $scheme.'://'.$_SERVER['HTTP_HOST'].$this->config['base_url'];
		}

		return $host . $virtualDir . $url;
	}

	function getConfig($name='') {
		if(!empty($this->config[$name])) {
			return $this->config[$name];
		} else {
			return false;
		}
	}

	function getVersionData() {
		include $this->config["base_path"]."manager/includes/version.inc.php";
		$v = array();
		$v['code_name'] = $code_name;
		$v['version'] = $version;
		$v['small_version'] = $small_version;
		$v['patch_level'] = $patch_level;
		$v['full_appname'] = $full_appname;
		return $v;
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
		if($this->isFrontend() && isset($_SESSION['webValidated'])) {
			// web user
			$userdetails['loggedIn']=true;
			$userdetails['id']=$_SESSION['webInternalKey'];
			$userdetails['username']=$_SESSION['webShortname'];
			$userdetails['usertype']='web'; // added by Raymond
			return $userdetails;
		}
		else if($this->isBackend() && isset($_SESSION['mgrValidated'])) {
			// manager user
			$userdetails['loggedIn']=true;
			$userdetails['id']=$_SESSION['mgrInternalKey'];
			$userdetails['username']=$_SESSION['mgrShortname'];
			$userdetails['usertype']='manager'; // added by Raymond
			return $userdetails;
		}
		else {
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

	function getMETATags($id=0) {
		if($id==0) {
			$id=$this->documentObject['id'];
		}
		$tbl = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix'];
		$sql = "SELECT smt.* ".
			   "FROM ".$this->getFullTableName("site_metatags")." smt ".
			   "INNER JOIN ".$this->getFullTableName("site_content_metatags")." cmt ON cmt.metatag_id=smt.id ".
			   "WHERE cmt.content_id = '$id'";
		$ds = $this->db->query($sql);
		$limit = $this->db->getRecordCount($ds);
		$metatags = array();
		if($limit > 0) 	{
			for($i=0;$i<$limit;$i++) {
				$row = $this->db->getRow($ds);
				$metatags[$row['name']] = array("tag"=>$row['tag'],"tagvalue"=>$row['tagvalue'],"http_equiv"=>$row['http_equiv']);
			}
		}
		return $metatags;
	}

	// Modified by Raymond to support new parser format
	function runSnippet($snippetName, $params=array()) {
		$output = '';

		// load new parser extension
		$this->loadExtension('Parser');	

		// process/execute snippet
		$this->parser->processSnippet($snippetName,$output,$params);
		return $output;
	}

	// Modified by Raymond
	function getChunk($chunkName) {
		$chunk = '';
		if(isset($this->chunkCache[$chunkName])) {
			$chunk = $this->chunkCache[$chunkName];
		} else {
			$sql = "SELECT * FROM ".$this->getFullTableName("site_htmlsnippets")." WHERE ".$this->getFullTableName("site_htmlsnippets").".name='".$this->db->escape($chunkName)."';";
			$result = $this->dbQuery($sql);
			$limit=$this->recordCount($result);
			if($limit<1) {
				$this->chunkCache[$chunkName] = '';
				$replace[$i] = '';
			} else {
				$row=$this->fetchRow($result);
				$chunk = $this->chunkCache[$chunkName] = $row['snippet'];
			}
		}		
		return $chunk;
	}

	// deprecated
	function putChunk($chunkName) { // alias name >.<
		return $this->getChunk($chunkName);
	}

	// Modified by Raymond to support new parser format
	function parseChunk($chunkName, $chunkArr, $prefix='[[+', $suffix=']]') {
		if(!is_array($chunkArr)) return false;
		$chunk = $this->getChunk($chunkName);
		
		if ($this->enableCompatMode) {
			$this->convertTags($chunk);
			if($prefix=='[+' && $prefix=='+]') {
				$prefix = '[[+'; $suffix = ']]';
			}
		}
		
		foreach($chunkArr as $key => $value) {
			$chunk = str_replace($prefix.$key.$suffix, $value, $chunk);
		}
		return $chunk;
	}

	function getUserData() {
		include_once $this->config["base_path"]."manager/includes/extenders/getuserdata.extender.php";
		return $tmpArray;
	}

	#::::::::::::::::::::::::::::::::::::::::
	# Added By: Raymond Irving - MODx
	#

	function getDocumentChildrenTVars($parentid=0, $tvidnames=array(), $published=1, $docsort="menuindex", $docsortdir="ASC", $tvfields="*", $tvsort="rank", $tvsortdir="ASC") {
		$docs = $this->getDocumentChildren($parentid, $published,0,'*','',$docsort,$docsortdir);
		if (!$docs) return false;
		else {
			$result = array();
			// get user defined template variables
			$fields = ($tvfields=="") ? "tv.*" : 'tv.'.implode(',tv.',preg_replace("/^\s/i","",explode(',',$tvfields)));
			$tvsort = ($tvsort=="") ? "":'tv.'.implode(',tv.',preg_replace("/^\s/i","",explode(',',$tvsort)));
			if ($tvidnames=="*") $query = "tv.id<>0";
			else $query = (is_numeric($tvidnames[0]) ? "tv.id":"tv.name")." IN ('".implode("','",$tvidnames)."')";
			$tbn = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix'];
			if($docgrp = $this->getUserDocGroups()) $docgrp = implode(",",$docgrp);

			$docCount = count($docs);
			for($i=0;$i<$docCount;$i++){

				$tvs = array();
				$docRow=$docs[$i];
				$docid = $docRow['id'];

				$sql = "SELECT $fields, IF(tvc.value!='',tvc.value,tv.default_text) as value ";
				$sql.= "FROM ".$tbn."site_tmplvars tv ";
				$sql.= "INNER JOIN ".$tbn."site_tmplvar_templates tvtpl ON tvtpl.tmplvarid = tv.id ";
				$sql.= "LEFT JOIN ".$tbn."site_tmplvar_contentvalues tvc ON tvc.tmplvarid=tv.id AND tvc.contentid = '".$docid."' ";
				$sql.= "WHERE ".$query." AND tvtpl.templateid = ".$docRow['template'];
				if ($tvsort) $sql.= " ORDER BY $tvsort $tvsortdir ";
				$rs = $this->dbQuery($sql);
				$limit = @$this->recordCount($rs);
				for($x=0;$x<$limit;$x++)  {
					array_push($tvs,@$this->fetchRow($rs));
				}

				// get default/built-in template variables
				ksort($docRow);
				foreach ($docRow as $key => $value) {
					if ($tvidnames=="*" || in_array($key,$tvidnames)) array_push($tvs,array("name"=>$key,"value"=>$value));
				}

				if(count($tvs)) array_push($result,$tvs);
			}
			return $result;
		}
	}

	function getDocumentChildrenTVarOutput($parentid=0, $tvidnames=array(), $published=1, $docsort="menuindex", $docsortdir="ASC") {
		$docs = $this->getDocumentChildren($parentid, $published,0,'*','',$docsort,$docsortdir);
		if (!$docs) return false;
		else {
			$result = array();
			for($i=0;$i<count($docs);$i++){
				$tvs = $this->getTemplateVarOutput($tvidnames,$docs[$i]["id"],$published);
				if($tvs) $result[$docs[$i]['id']] = $tvs; // Use docid as key - netnoise 2006/08/14
			}
			return $result;
		}
	}

	// Modified by Raymond for TV - Orig Modified by Apodigm - DocVars
	# returns a single TV record. $idnames - can be an id or name that belongs the template that the current document is using
	function getTemplateVar($idname="", $fields = "*", $docid="", $published=1) {
		if($idname=="") {
			return false;
		}
		else {
			$result = $this->getTemplateVars(array($idname),$fields,$docid,$published,"",""); //remove sorting for speed
			return ($result!=false) ? $result[0]:false;
		}
	}

	# returns an array of TV records. $idnames - can be an id or name that belongs the template that the current document is using
	function getTemplateVars($idnames=array(), $fields = "*", $docid="", $published=1, $sort="rank", $dir="ASC") {
		if(($idnames!='*' && !is_array($idnames)) || count($idnames)==0) {
			return false;
		}
		else {
			$result = array();

			// get document record
			if ($docid=="") {
				$docid = $this->documentIdentifier;
				$docRow = $this->documentObject;
			}
			else {
				$docRow = $this->getDocument($docid, '*', $published);
				if (!$docRow) return false;
			}

			// get user defined template variables
			$fields = ($fields=="") ? "tv.*" : 'tv.'.implode(',tv.',preg_replace("/^\s/i","",explode(',',$fields)));
			$sort = ($sort=="") ? "":'tv.'.implode(',tv.',preg_replace("/^\s/i","",explode(',',$sort)));
			if ($idnames=="*") $query = "tv.id<>0";
			else $query = (is_numeric($idnames[0]) ? "tv.id":"tv.name")." IN ('".implode("','",$idnames)."')";
			$tbn = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix'];
			if($docgrp = $this->getUserDocGroups()) $docgrp = implode(",",$docgrp);
			$sql = "SELECT $fields, IF(tvc.value!='',tvc.value,tv.default_text) as value ";
			$sql.= "FROM ".$tbn."site_tmplvars tv ";
			$sql.= "INNER JOIN ".$tbn."site_tmplvar_templates tvtpl ON tvtpl.tmplvarid = tv.id ";
			$sql.= "LEFT JOIN ".$tbn."site_tmplvar_contentvalues tvc ON tvc.tmplvarid=tv.id AND tvc.contentid = '".$docid."' ";
			$sql.= "WHERE ".$query." AND tvtpl.templateid = ".$docRow['template'];
			if ($sort) $sql.= " ORDER BY $sort $dir ";
			$rs = $this->dbQuery($sql);
			for($i=0;$i<@$this->recordCount($rs);$i++)  {
				array_push($result,@$this->fetchRow($rs));
			}

			// get default/built-in template variables
			ksort($docRow);
			foreach ($docRow as $key => $value) {
				if ($idnames=="*" || in_array($key,$idnames)) array_push($result,array("name"=>$key,"value"=>$value));
			}

			return $result;
		}
	}

	# returns an associative array containing TV rendered output values. $idnames - can be an id or name that belongs the template that the current document is using
	function getTemplateVarOutput($idnames=array(), $docid="", $published=1) {
		if(count($idnames)==0) {
			return false;
		}
		else {
			$output = array();
			$result = $this->getTemplateVars(($idnames=='*' || is_array($idnames)) ? $idnames:array($idnames),"*",$docid,$published,"",""); // remove sort for speed
			if ($result==false) return false;
			else {
				$baspath = $this->config["base_path"]."manager/includes";
				include_once $baspath."/tmplvars.format.inc.php";
				include_once $baspath."/tmplvars.commands.inc.php";
				for($i=0;$i<count($result);$i++) {
					$row = $result[$i];
					// to-do needs fixing when getting tvs from other pages
					$replace_richtext = "";
					$richtexteditor = "";
					$w = "100%";
					$h = "300";
					$output[$row['name']] = getTVDisplayFormat($row['name'],$row['value'],$row['display'],$row['display_params'],$row['type']);
				}
				return $output;
			}
		}
	}

	# returns the full table name based on db settings
	function getFullTableName($tbl){
		return $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix'].$tbl;
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
		global $base_url;
		$pth=$base_url.'manager/';
		return $pth;
	}

	# returns the virtual relative path to the cache folder
	function getCachePath() {
		global $base_url;
		$pth=$base_url.'assets/cache/';
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
	// deprecated
	function insideManager(){
		$m=false;
		if(IN_MANAGER_MODE=='true'){
			$m = true;
			if (SNIPPET_INTERACTIVE_MODE=='true')	$m = "interact";
			else if (SNIPPET_INSTALL_MODE=='true')	$m = "install";
		}
		return $m;
	}

	# Returns current user id
	function getLoginUserID(){
		if($this->isFrontend() && isset($_SESSION['webValidated'])) {
			return $_SESSION['webInternalKey'];
		}
		else if($this->isBackend() && isset($_SESSION['mgrValidated'])) {
			return $_SESSION['mgrInternalKey'];
		}
	}

	# Returns current user name
	function getLoginUserName(){
		if($this->isFrontend() && isset($_SESSION['webValidated'])) {
			return $_SESSION['webShortname'];
		}
		else if($this->isBackend() && isset($_SESSION['mgrValidated'])) {
			return $_SESSION['mgrShortname'];
		}
	}

	# Returns current login user type - web or manager
	function getLoginUserType(){
		if($this->isFrontend() && isset($_SESSION['webValidated'])) {
			return 'web';
		}
		else if($this->isBackend() && isset($_SESSION['mgrValidated'])) {
			return 'manager';
		}
		else return '';
	}

	# Returns a record for the manager user
	function getUserInfo($uid){
		$sql = "
			SELECT mu.username, mu.password, mua.*
			FROM ".$this->getFullTableName("manager_users")." mu
			INNER JOIN ".$this->getFullTableName("user_attributes")." mua ON mua.internalkey=mu.id
			WHERE mu.id = '$uid'
		";
		$rs = $this->dbQuery($sql);
		$limit = mysql_num_rows($rs);
		if($limit==1) {
			$row = $this->fetchRow($rs);
			if(!$row["usertype"]) $row["usertype"] = "manager";
			return $row;
		}
	}

	# Returns a record for the web user
	function getWebUserInfo($uid){
		$sql = "
			SELECT wu.username, wu.password, wua.*
			FROM ".$this->getFullTableName("web_users")." wu
			INNER JOIN ".$this->getFullTableName("web_user_attributes")." wua ON wua.internalkey=wu.id
			WHERE wu.id='$uid'
		";
		$rs = $this->dbQuery($sql);
		$limit = mysql_num_rows($rs);
		if($limit==1) {
			$row = $this->fetchRow($rs);
			if(!$row["usertype"]) $row["usertype"] = "web";
			return $row;
		}
	}

	# Returns an array of document groups that current user is assigned to.
	# This function will first return the web user doc groups when running from frontend otherwise it will return manager user's docgroup
	# Set $resolveIds to true to return the document group names
	function getUserDocGroups($resolveIds = false){
		if($this->isFrontend() && isset($_SESSION['webDocgroups']) && isset($_SESSION['webValidated'])) {
			$dg = $_SESSION['webDocgroups'];
			$dgn = isset($_SESSION['webDocgrpNames'])? $_SESSION['webDocgrpNames']: false;
		}
		else if($this->isBackend() && isset($_SESSION['mgrDocgroups']) && isset($_SESSION['mgrValidated'])) {
			$dg = $_SESSION['mgrDocgroups'];
			$dgn = $_SESSION['mgrDocgrpNames'];
		}
		else {
			$dg = '';
		}
		if(!$resolveIds) return $dg;
		else if(is_array($dgn)) return $dgn;
		else if (is_array($dg)) {
			// resolve ids to names
			$dgn = array();
			$tbl = $this->getFullTableName("documentgroup_names");
			$ds = $this->dbQuery("SELECT name FROM $tbl WHERE id IN (".implode(",",$dg).")");
			while($row = $this->fetchRow($ds)) $dgn[count($dgn)] = $row['name'];
			// cache docgroup names to session
			if($this->isFrontend()) $_SESSION['webDocgrpNames'] = $dgn;
			else $_SESSION['mgrDocgrpNames'] = $dgn;
			return $dgn;
		}
	}
	function getDocGroups(){return $this->getUserDocGroups();} // deprecated

	# Change current web user's password - returns true if successful, oterhwise return error message
	function changeWebUserPassword($oldPwd,$newPwd){
		$rt = false;
		if($_SESSION["webValidated"]==1){
			$tbl = $this->getFullTableName("web_users");
			$ds = $this->dbQuery("SELECT * FROM $tbl WHERE id='".$this->getLoginUserID()."'");
			$limit = mysql_num_rows($ds);
			if($limit==1){
				$row = $this->fetchRow($ds);
				if($row["password"]==md5($oldPwd)) {
					if(strlen($newPwd) < 6 ) {
						return "Password is too short!";
					}
					elseif($newPwd=="") {
						return "You didn't specify a password for this user!";
					}
					else{
						$this->dbQuery("UPDATE $tbl SET password = md5('".$newPwd."') WHERE id='".$this->getLoginUserID()."'");
						// invoke OnWebChangePassword event
						$this->invokeEvent("OnWebChangePassword",
										array(
											"userid"		=> $row["id"],
											"username"		=> $row["username"],
											"userpassword"	=> $newPwd
										));
						return true;
					}
				}
				else {
					return "Incorrect password.";
				}
			}
		}
	}
	function changePassword($o,$n){return changeWebUserPassword($o,$n); } // deprecated

	# returns true if the current web user is a member the specified groups
	function isMemberOfWebGroup($groupNames=array()){
		if(!is_array($groupNames)) return false;
		// check cache
		$grpNames = isset($_SESSION['webUserGroupNames'])?$_SESSION['webUserGroupNames']:false;
		if(!is_array($grpNames)) {
			$tbl = $this->getFullTableName("webgroup_names");
			$tbl2 = $this->getFullTableName("web_groups");
			$sql = "SELECT wgn.name
					FROM $tbl wgn
					INNER JOIN $tbl2 wg ON wg.webgroup=wgn.id AND wg.webuser='".$this->getLoginUserID()."'";
			$grpNames = $this->db->getColumn("name",$sql);
			// save to cache
			$_SESSION['webUserGroupNames'] = $grpNames;
		}
		foreach($groupNames as $k=>$v)
			if(in_array(trim($v),$grpNames)) return true;
		return false;
	}

	# Registers Client-side CSS scripts - these scripts are loaded at inside the <head> tag
	function regClientCSS($src){
		if (isset($this->loadedjscripts[$src]) && $this->loadedjscripts[$src]) return '';
		$this->loadedjscripts[$src] = true;
		if (strpos(strtolower($src),"<style")!==false||strpos(strtolower($src),"<link")!==false) {
			$this->sjscripts[count($this->sjscripts)] = $src;
		}
		else {
			$this->sjscripts[count($this->sjscripts)] = '<!-- MODx registered -->'."\n".'	<link rel="stylesheet" href="'.$src.' />';
		}
	}

	# Registers Startup Client-side JavaScript - these scripts are loaded at inside the <head> tag
	function regClientStartupScript($src, $plaintext=false){
		if (isset($this->loadedjscripts[$src])) return '';	
		$this->loadedjscripts[$src] = true;
		if ($plaintext==true) $this->sjscripts[count($this->sjscripts)] = $src;
		elseif (strpos(strtolower($src),"<script")!==false) $this->sjscripts[count($this->sjscripts)] = $src;
		else $this->sjscripts[count($this->sjscripts)] = '<!-- MODx registered -->'."\n".'	<script type="text/javascript" src="'.$src.'"></script>';
	}

	# Registers Client-side JavaScript 	- these scripts are loaded at the end of the page
	function regClientScript($src, $plaintext=false){
		if (isset($this->loadedjscripts[$src])) return '';
		$this->loadedjscripts[$src] = true;
		if ($plaintext==true) $this->jscripts[count($this->jscripts)] = $src;
		elseif (strpos(strtolower($src),"<script")!==false) $this->jscripts[count($this->jscripts)] = $src;
		else $this->jscripts[count($this->jscripts)] = '<!-- MODx registered -->'."\n".'	<script type="text/javascript" src="'.$src.'"></script>';
	}

	# Registers Client-side Startup HTML block
	function regClientStartupHTMLBlock($html){
		$this->regClientStartupScript($html,true);
	}

	# Registers Client-side HTML block
	function regClientHTMLBlock($html){
		$this->regClientScript($html,true);
	}

	// Insert registered client-side scripts into source
	// to be optimised
	function insertClientScripts(&$source){
		// Insert Startup jscripts & CSS scripts into template - template must have a <head> tag
		if ($js = $this->getRegisteredClientStartupScripts()){
			$source = str_replace($this->sjscripts,'',$source); // remove old or cached scripts
			$pattern = "~(</head>)~i";
			if(preg_match($pattern,$source)) $source = preg_replace($pattern, $js."\n\\1", $source,1);
			else $source = $js.$source;
		}

		// Insert jscripts & html block into template - template must have a </body> tag
		if ($js = $this->getRegisteredClientScripts()){
			$source = str_replace($this->jscripts,'',$source); // remove old or cached scripts
			$pattern = "~(</body>)~i";
			if(preg_match($pattern,$source)) $source = preg_replace($pattern, $js."\n\\1", $source,1);
			else $source.= $js;
		}
	}

	// clear registered client scripts
	function clearClientScripts() {	
		// reset script array
		unset($this->jscripts);
		unset($this->sjscripts);
		unset($this->loadedjscripts);

		$this->jscripts = array();
		$this->sjscripts = array();
		$this->loadedjscripts = array();		
	}

	# Remove unwanted html tags and snippet, settings and tags
	function stripTags($html,$allowed="") {
		$t = strip_tags($html,$allowed);
		$t = preg_replace('~\[\*(.*?)\*\]~',"",$t);	//tv
		$t = preg_replace('~\[\[(.*?)\]\]~',"",$t);	//snippet
		$t = preg_replace('~\[\!(.*?)\!\]~',"",$t);	//snippet
		$t = preg_replace('~\[\((.*?)\)\]~',"",$t);	//settings
		$t = preg_replace('~{{(.*?)}}~',"",$t);		//chunks
		return $t;
	}

	# add an event listner to a plugin - only for use within the current execution cycle
	function addEventListener($evtName,$pluginName){
		if(!$evtName || !$pluginName) return false;
		$el =  $this->pluginEvent[$evtName];
		if(empty($el)) $el = $this->pluginEvent[$evtName] = array();
		return array_push($el,$pluginName); // return index
	}

	# remove event listner - only for use within the current execution cycle
	function removeEventListener($evtName){
		if (!$evtName) return false;
		unset($this->pluginEvent[$evtName]);
	}

	# remove all event listners - only for use within the current execution cycle
	function removeAllEventListener(){
		unset($this->pluginEvent);
		$this->pluginEvent = array();
	}

	# invoke an event. $extParams - hash array: name=>value
	function invokeEvent($evtName,$parameters = array()){
		if(!$evtName) return false;
		if(!isset($this->pluginEvent[$evtName])) return false;
		$el = $this->pluginEvent[$evtName];
		$results = array();
		$numEvents = count($el);
		if($numEvents > 0) 
		for ($i=0; $i<$numEvents;$i++) { // start for loop
			$pluginName = $el[$i];
			// reset event object
			$e = &$this->Event;
			$e->_resetEventObject();
			$e->name = $evtName;
			$e->activePlugin = $pluginName;

			// get plugin code
			if(isset($this->pluginCache[$pluginName])) {
				$pluginCode = $this->pluginCache[$pluginName];
				$pluginProperties = $this->pluginCache[$pluginName."Props"];
			} else {
				$sql = "SELECT * FROM ".$this->getFullTableName("site_plugins")." WHERE name='".$pluginName."' AND disabled=0;";
				$result = $this->dbQuery($sql);
				if(!$this->recordCount($result)==1) {
					$row = $this->fetchRow($result);
					$pluginCode = $this->pluginCache[$row['name']] = $row['plugincode'];
					$pluginProperties = $this->pluginCache[$row['name']."Props"] = $row['properties'];
				} else {
					$pluginCode = $this->pluginCache[$pluginName] = '';
					$pluginProperties = '';
				}
			}

			// load default params/properties
			$defParams = '';
			if(is_string($pluginProperties) && $pluginProperties!='') $defParams = unserialize($pluginProperties);
			else $defParams = $pluginProperties;
			
			if ($defParams) $parameters = array_merge($defParams,$parameters);

			// execute plugin
			$this->event->params = &$parameters; // store params inside event object
			include_once($this->config['base_path'].'assets/cache/sitePlugins.cache.php');
			$php_errormsg = null; // php error message string
			ob_start();
				if(function_exists($pluginCode)) $pluginCode($this,$parameters);				
				$msg = ob_get_contents();
			ob_end_clean();
			if ($msg) { 
				if(!strpos($php_errormsg,'Deprecated')) { // ignore php5 strict errors
					// log error
					$this->logEvent(1,3,"<b>$php_errormsg</b><br /><br /> $msg",$this->Event->activePlugin." - Plugin");
					if($this->isBackend()) $this->Event->alert("An error occurred while loading. Please see the event log for more information.<p />$msg");
				}
			}
			unset($this->event->params);
		
			if ($e->_output!="") $results[] = $e->_output;
			if ($e->_propagate!=true) break;
		}
		$e->activePlugin = "";
		return $results;
	}

	# parses a resource property string and returns the result as an array
	function parseProperties($propertyString){
		$parameter = array();
		if(!empty($propertyString)) {
			$tmpParams = explode("&",$propertyString);
			for($x=0; $x<count($tmpParams); $x++) {
				if (strpos($tmpParams[$x], '=', 0)) {
					$pTmp = explode("=", $tmpParams[$x]);
					$pvTmp = explode(";", trim($pTmp[1]));
					if ($pvTmp[1]=='list' && $pvTmp[3]!="") $parameter[trim($pTmp[0])] = $pvTmp[3]; //list default
					else if($pvTmp[1]!='list' && $pvTmp[2]!="") $parameter[trim($pTmp[0])] = $pvTmp[2];
				}
			}
		}
		return $parameter;
	}

	// Deprecated functions - please use $modx->db function instead
	
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
		  $tbl = $this->getFullTableName($from);
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
		  $tbl = $this->getFullTableName($into);
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
		  $tbl = $this->getFullTableName($into);
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
	/* End of API functions								       */
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
		$version= isset($GLOBALS['version'])? $GLOBALS['version']: '';
		$code_name= isset($GLOBALS['code_name'])? $GLOBALS['code_name']: '';

		// include message quit template
		include(dirname(__FILE__).'/extenders/message.quit.inc.php');

		$this->documentOutput = $parsedMessageString;
		$this->outputContent($this->documentOutput,true); // generate output without events
		exit;
	}

	function getRegisteredClientScripts(){
		return implode("\n",$this->jscripts);
	}

	function getRegisteredClientStartupScripts(){
		return implode("\n",$this->sjscripts);
	}

	// End of class.

}

// SystemEvent Class
class SystemEvent {
	var $name;
	var $_propagate;
	var $_output;
	var $activated;
	var $activePlugin;

	function SystemEvent($name="") {
		$this->_resetEventObject();
		$this->name=$name;
	}

	// used for displaying a message to the user
	function alert($msg) {
		global $SystemAlertMsgQueque;
		if($msg=="") return;
		if (is_array($SystemAlertMsgQueque)) {
			if($this->name && $this->activePlugin) $title = "<div><b>".$this->activePlugin."</b> - <span style='color:maroon;'>".$this->name."</span></div>";
			$SystemAlertMsgQueque[] = "$title<div style='margin-left:10px;margin-top:3px;'>$msg</div>";
		}
	}

	// used for rendering an out on the screen
	function output($msg) {
		$this->_output .= $msg;
	}

	function stopPropagation(){
		$_propagate = false;
	}

	function _resetEventObject(){
		unset($this->returnedValues);
		$this->name = "";
		$this->_output = "";
		$this->_propagate = true;
		$this->activated = false;
	}
		
}

?>
