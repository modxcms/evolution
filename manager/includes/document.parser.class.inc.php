<?php
/**
 *	MODx Document Parser
 *	Function: This class contains the main document parsing functions
 *
 */
class DocumentParser {
    var $db; // db object
    var $event, $Event; // event object

    var $pluginEvent;

    var $config= null;
    var $rs, $result, $sql, $table_prefix, $debug, $documentIdentifier, $documentMethod, $documentGenerated, $documentContent, $tstart, $minParserPasses, $maxParserPasses, $documentObject, $templateObject, $snippetObjects, $stopOnNotice, $executedQueries, $queryTime, $currentSnippet, $documentName, $aliases, $visitor, $entrypage, $documentListing, $dumpSnippets, $chunkCache, $snippetCache, $contentTypes, $dumpSQL, $queryCode, $virtualDir, $placeholders, $sjscripts, $jscripts, $loadedjscripts, $documentMap;
    var $forwards= 3;

    // constructor
    function DocumentParser() {
        $this->loadExtension('DBAPI') or die('Could not load DBAPI class.'); // load DBAPI class
        $this->dbConfig= & $this->db->config; // alias for backward compatibility
        $this->jscripts= array ();
        $this->sjscripts= array ();
        $this->loadedjscripts= array ();
        // events
        $this->event= new SystemEvent();
        $this->Event= & $this->event; //alias for backward compatibility
        $this->pluginEvent= array ();
        // set track_errors ini variable
        @ ini_set("track_errors", "1"); // enable error tracking in $php_errormsg
    }

    // loads an extension from the extenders folder
    function loadExtension($extname) {
        global $database_type;

        switch ($extname) {
            // Database API
            case 'DBAPI' :
                if (!include_once MODX_BASE_PATH . 'manager/includes/extenders/dbapi.' . $database_type . '.class.inc.php')
                    return false;
                $this->db= new DBAPI;
                return true;
                break;

                // Manager API
            case 'ManagerAPI' :
                if (!include_once MODX_BASE_PATH . 'manager/includes/extenders/manager.api.class.inc.php')
                    return false;
                $this->manager= new ManagerAPI;
                return true;
                break;

            default :
                return false;
        }
    }

    function getMicroTime() {
        list ($usec, $sec)= explode(' ', microtime());
        return ((float) $usec + (float) $sec);
    }

    function sendRedirect($url, $count_attempts= 0, $type= '', $responseCode= '') {
        if (empty ($url)) {
            return false;
        } else {
            if ($count_attempts == 1) {
                // append the redirect count string to the url
                $currentNumberOfRedirects= isset ($_REQUEST['err']) ? $_REQUEST['err'] : 0;
                if ($currentNumberOfRedirects > 3) {
                    $this->messageQuit('Redirection attempt failed - please ensure the document you\'re trying to redirect to exists. <p>Redirection URL: <i>' . $url . '</i></p>');
                } else {
                    $currentNumberOfRedirects += 1;
                    if (strpos($url, "?") > 0) {
                        $url .= "&err=$currentNumberOfRedirects";
                    } else {
                        $url .= "?err=$currentNumberOfRedirects";
                    }
                }
            }
            if ($type == 'REDIRECT_REFRESH') {
                $header= 'Refresh: 0;URL=' . $url;
            }
            elseif ($type == 'REDIRECT_META') {
                $header= '<META HTTP-EQUIV="Refresh" CONTENT="0; URL=' . $url . '" />';
                echo $header;
                exit;
            }
            elseif ($type == 'REDIRECT_HEADER' || empty ($type)) {
                // check if url has /$base_url
                global $base_url, $site_url;
                if (substr($url, 0, strlen($base_url)) == $base_url) {
                    // append $site_url to make it work with Location:
                    $url= $site_url . substr($url, strlen($base_url));
                }
                if (strpos($url, "\n") === false) {
                    $header= 'Location: ' . $url;
                } else {
                    $this->messageQuit('No newline allowed in redirect url.');
                }
            }
            if ($responseCode && (strpos($responseCode, '30') !== false)) {
                header($responseCode);
            }
            header($header);
            exit();
        }
    }

    function sendForward($id, $responseCode= '') {
        if ($this->forwards > 0) {
            $this->forwards= $this->forwards - 1;
            $this->documentIdentifier= $id;
            $this->documentMethod= 'id';
            $this->documentObject= $this->getDocumentObject('id', $id);
            if ($responseCode) {
                header($responseCode);
            }
            $this->prepareResponse();
            exit();
        } else {
            header('HTTP/1.0 500 Internal Server Error');
            die('<h1>ERROR: Too many forward attempts!</h1><p>The request could not be completed due to too many unsuccessful forward attempts.</p>');
        }
    }

    function sendErrorPage() {
        // invoke OnPageNotFound event
        $this->invokeEvent('OnPageNotFound');
//        $this->sendRedirect($this->makeUrl($this->config['error_page'], '', '&refurl=' . urlencode($_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'])), 1);
        $this->sendForward($this->config['error_page'] ? $this->config['error_page'] : $this->config['site_start'], 'HTTP/1.0 404 Not Found');
        exit();
    }

    function sendUnauthorizedPage() {
        // invoke OnPageUnauthorized event
        $_REQUEST['refurl'] = $this->documentIdentifier;
        $this->invokeEvent('OnPageUnauthorized');
        if ($this->config['unauthorized_page']) {
            $unauthorizedPage= $this->config['unauthorized_page'];
        } elseif ($this->config['error_page']) {
            $unauthorizedPage= $this->config['error_page'];
        } else {
            $unauthorizedPage= $this->config['site_start'];
        }
        $this->sendForward($unauthorizedPage, 'HTTP/1.1 401 Unauthorized');
        exit();
    }

    // function to connect to the database
    // - deprecated use $modx->db->connect()
    function dbConnect() {
        $this->db->connect();
        $this->rs= $this->db->conn; // for compatibility
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
    function fetchRow($rs, $mode= 'assoc') {
        return $this->db->getRow($rs, $mode);
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
        if (!is_array($this->config) || empty ($this->config)) {
            if ($included= file_exists(MODX_BASE_PATH . 'assets/cache/siteCache.idx.php')) {
                $included= include_once (MODX_BASE_PATH . 'assets/cache/siteCache.idx.php');
            }
            if (!$included) {
                $result= $this->dbQuery('SELECT setting_name, setting_value FROM ' . $this->getFullTableName('system_settings'));
                while ($row= $this->fetchRow($result, 'both')) {
                    $this->config[$row[0]]= $row[1];
                }
            }

            // added for backwards compatibility - garry FS#104
            $this->config['etomite_charset'] = & $this->config['modx_charset'];

            // store base_url and base_path inside config array
            $this->config['base_url']= MODX_BASE_URL;
            $this->config['base_path']= MODX_BASE_PATH;
            $this->config['site_url']= MODX_SITE_URL;

            // load user setting if user is logged in
            $usrSettings= array ();
            if ($id= $this->getLoginUserID()) {
                $usrType= $this->getLoginUserType();
                if (isset ($usrType) && $usrType == 'manager')
                    $usrType= 'mgr';

                if ($usrType == 'mgr' && $this->isBackend()) {
                    // invoke the OnBeforeManagerPageInit event, only if in backend
                    $this->invokeEvent("OnBeforeManagerPageInit");
                }

                if (isset ($_SESSION[$usrType . 'UsrConfigSet'])) {
                    $usrSettings= & $_SESSION[$usrType . 'UsrConfigSet'];
                } else {
                    if ($usrType == 'web')
                        $query= $this->getFullTableName('web_user_settings') . ' WHERE webuser=\'' . $id . '\'';
                    else
                        $query= $this->getFullTableName('user_settings') . ' WHERE user=\'' . $id . '\'';
                    $result= $this->dbQuery('SELECT setting_name, setting_value FROM ' . $query);
                    while ($row= $this->fetchRow($result, 'both'))
                        $usrSettings[$row[0]]= $row[1];
                    if (isset ($usrType))
                        $_SESSION[$usrType . 'UsrConfigSet']= $usrSettings; // store user settings in session
                }
            }
            if ($this->isFrontend() && $mgrid= $this->getLoginUserID('mgr')) {
                $musrSettings= array ();
                if (isset ($_SESSION['mgrUsrConfigSet'])) {
                    $musrSettings= & $_SESSION['mgrUsrConfigSet'];
                } else {
                    $query= $this->getFullTableName('user_settings') . ' WHERE user=\'' . $mgrid . '\'';
                    if ($result= $this->dbQuery('SELECT setting_name, setting_value FROM ' . $query)) {
                        while ($row= $this->fetchRow($result, 'both')) {
                            $usrSettings[$row[0]]= $row[1];
                        }
                        $_SESSION['mgrUsrConfigSet']= $musrSettings; // store user settings in session
                    }
                }
                if (!empty ($musrSettings)) {
                    $usrSettings= array_merge($musrSettings, $usrSettings);
                }
            }
            $this->config= array_merge($this->config, $usrSettings);
        }
    }

    function getDocumentMethod() {
        // function to test the query and find the retrieval method
        if (isset ($_REQUEST['q'])) {
            return "alias";
        }
        elseif (isset ($_REQUEST['id'])) {
            return "id";
        } else {
            return "none";
        }
    }

    function getDocumentIdentifier($method) {
        // function to test the query and find the retrieval method
        $docIdentifier= $this->config['site_start'];
        switch ($method) {
            case 'alias' :
                $docIdentifier= $this->db->escape($_REQUEST['q']);
                break;
            case 'id' :
                if (!is_numeric($_REQUEST['id'])) {
                    $this->sendErrorPage();
                } else {
                    $docIdentifier= intval($_REQUEST['id']);
                }
                break;
        }
        return $docIdentifier;
    }

    // check for manager login session
    function checkSession() {
        if (isset ($_SESSION['mgrValidated'])) {
            return true;
        } else {
            return false;
        }
    }

    function checkPreview() {
        if ($this->checkSession() == true) {
            if (isset ($_REQUEST['z']) && $_REQUEST['z'] == 'manprev') {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // check if site is offline
    function checkSiteStatus() {
        $siteStatus= $this->config['site_status'];
        if ($siteStatus == 1) {
            // site online
            return true;
        }
        elseif ($siteStatus == 0 && $this->checkSession()) {
            // site offline but launched via the manager
            return true;
        } else {
            // site is offline
            return false;
        }
    }

    function cleanDocumentIdentifier($qOrig) { // modx updates
        (!empty($qOrig)) or $qOrig = $this->config['site_start'];  // <- netnoise 2006-10-19
        $q= $qOrig;
        // First remove any / before or after
        if ($q[strlen($q) - 1] == '/')
            $q= substr($q, 0, -1);
        if ($q[0] == '/')
            $q= substr($q, 1);
        // Save path if any
        // FS#476 and FS#308: only return virtualDir if friendly paths are enabled
        if ($this->config['use_alias_path'] == 1) {
            $this->virtualDir= dirname($q);
            $this->virtualDir= ($this->virtualDir == '.' ? '' : $this->virtualDir);
            $q= basename($q);
        } else {
            $this->virtualDir= '';
        }
        $q= str_replace($this->config['friendly_url_prefix'], "", $q);
        $q= str_replace($this->config['friendly_url_suffix'], "", $q);
        if (is_numeric($q) && !$this->documentListing[$q]) { // we got an ID returned, check to make sure it's not an alias
            // FS#476 and FS#308: check that id is valid in terms of virtualDir structure
            if ($this->config['use_alias_path'] == 1) {
                if (($this->virtualDir != '' && in_array($q, $this->getChildIds($this->documentListing[$this->virtualDir], 1))) || ($this->virtualDir == '' && in_array($q, $this->getChildIds(0, 1)))) {
                    $this->documentMethod= 'id';
                    return $q;
                } else { // not a valid id in terms of virtualDir, treat as alias
                    $this->documentMethod= 'alias';
                    return $q;
                }
            } else {
                $this->documentMethod= 'id';
                return $q;
            }
        } else { // we didn't get an ID back, so instead we assume it's an alias
            if ($this->config['friendly_alias_urls'] != 1) {
                $q= $qOrig;
            }

            $this->documentMethod= 'alias';
            return $q;
        }
    }

    function checkCache($id) {
        $cacheFile= "assets/cache/docid_" . $id . ".pageCache.php";
        if (file_exists($cacheFile)) {
            $this->documentGenerated= 0;
            $flContent= implode("", file($cacheFile));
            $flContent= substr($flContent, 37); // remove php header
            $a= explode("<!--__MODxCacheSpliter__-->", $flContent, 2);
            if (count($a) == 1)
                return $a[0]; // return only document content
            else {
                $docObj= unserialize($a[0]); // rebuild document object
                // check page security
                if ($docObj['privateweb'] && isset ($docObj['__MODxDocGroups__'])) {
                    $pass= false;
                    $usrGrps= $this->getUserDocGroups();
                    $docGrps= explode(",", $docObj['__MODxDocGroups__']);
                    // check is user has access to doc groups
                    if (is_array($usrGrps)) {
                        foreach ($usrGrps as $k => $v)
                            if (in_array($v, $docGrps)) {
                                $pass= true;
                                break;
                            }
                    }
                    // diplay error pages if user has no access to cached doc
                    if (!$pass) {
                        if ($this->config['unauthorized_page']) {
                            // check if file is not public
                            $tbldg= $this->getFullTableName("document_groups");
                            $secrs= $this->dbQuery("SELECT id FROM $tbldg WHERE document = '" . $id . "' LIMIT 1;");
                            if ($secrs)
                                $seclimit= mysql_num_rows($secrs);
                        }
                        if ($seclimit > 0) {
                            // match found but not publicly accessible, send the visitor to the unauthorized_page
                            $this->sendUnauthorizedPage();
                            exit; // stop here
                        } else {
                            // no match found, send the visitor to the error_page
                            $this->sendErrorPage();
                            exit; // stop here
                        }
                    }
                }
				// Grab the Scripts
				if (isset($docObj['__MODxSJScripts__'])) $this->sjscripts = $docObj['__MODxSJScripts__'];
				if (isset($docObj['__MODXJScripts__']))  $this->jscripts = $docObj['__MODxJScripts__'];

				// Remove intermediate variables
                unset($docObj['__MODxDocGroups__'], $docObj['__MODxSJScripts__'], $docObj['__MODxJScripts__']);

                $this->documentObject= $docObj;
                return $a[1]; // return document content
            }
        } else {
            $this->documentGenerated= 1;
            return "";
        }
    }

    function outputContent($noEvent= false) {

        $this->documentOutput= $this->documentContent;

        if ($this->documentGenerated == 1 && $this->documentObject['cacheable'] == 1 && $this->documentObject['type'] == 'document' && $this->documentObject['published'] == 1) {
    		if (!empty($this->sjscripts)) $this->documentObject['__MODxSJScripts__'] = $this->sjscripts;
    		if (!empty($this->jscripts)) $this->documentObject['__MODxJScripts__'] = $this->jscripts;
        }

        // check for non-cached snippet output
        if (strpos($this->documentOutput, '[!') > -1) {
            $this->documentOutput= str_replace('[!', '[[', $this->documentOutput);
            $this->documentOutput= str_replace('!]', ']]', $this->documentOutput);

            // Parse document source
            $this->documentOutput= $this->parseDocumentSource($this->documentOutput);
    	}

    	// Moved from prepareResponse() by sirlancelot
    	// Insert Startup jscripts & CSS scripts into template - template must have a <head> tag
    	if ($js= $this->getRegisteredClientStartupScripts()) {
    		// change to just before closing </head>
    		// $this->documentContent = preg_replace("/(<head[^>]*>)/i", "\\1\n".$js, $this->documentContent);
    		$this->documentOutput= preg_replace("/(<\/head>)/i", $js . "\n\\1", $this->documentOutput);
    	}

    	// Insert jscripts & html block into template - template must have a </body> tag
    	if ($js= $this->getRegisteredClientScripts()) {
    		$this->documentOutput= preg_replace("/(<\/body>)/i", $js . "\n\\1", $this->documentOutput);
    	}
    	// End fix by sirlancelot

        // remove all unused placeholders
        if (strpos($this->documentOutput, '[+') > -1) {
            $matches= array ();
            preg_match_all('~\[\+(.*?)\+\]~', $this->documentOutput, $matches);
            if ($matches[0])
                $this->documentOutput= str_replace($matches[0], '', $this->documentOutput);
        }

        $this->documentOutput= $this->rewriteUrls($this->documentOutput);

        // send out content-type and content-disposition headers
        if (IN_PARSER_MODE == "true") {
            $type= !empty ($this->contentTypes[$this->documentIdentifier]) ? $this->contentTypes[$this->documentIdentifier] : "text/html";
            header('Content-Type: ' . $type . '; charset=' . $this->config['modx_charset']);
//            if (($this->documentIdentifier == $this->config['error_page']) || $redirect_error)
//                header('HTTP/1.0 404 Not Found');
            if (!$this->checkPreview() && $this->documentObject['content_dispo'] == 1) {
                if ($this->documentObject['alias'])
                    $name= $this->documentObject['alias'];
                else {
                    // strip title of special characters
                    $name= $this->documentObject['pagetitle'];
                    $name= strip_tags($name);
                    $name= strtolower($name);
                    $name= preg_replace('/&.+?;/', '', $name); // kill entities
                    $name= preg_replace('/[^\.%a-z0-9 _-]/', '', $name);
                    $name= preg_replace('/\s+/', '-', $name);
                    $name= preg_replace('|-+|', '-', $name);
                    $name= trim($name, '-');
                }
                $header= 'Content-Disposition: attachment; filename=' . $name;
                header($header);
            }
        }

        $totalTime= ($this->getMicroTime() - $this->tstart);
        $queryTime= $this->queryTime;
        $phpTime= $totalTime - $queryTime;

        $queryTime= sprintf("%2.4f s", $queryTime);
        $totalTime= sprintf("%2.4f s", $totalTime);
        $phpTime= sprintf("%2.4f s", $phpTime);
        $source= $this->documentGenerated == 1 ? "database" : "cache";
        $queries= isset ($this->executedQueries) ? $this->executedQueries : 0;

        $out =& $this->documentOutput;
        if ($this->dumpSQL) {
            $out .= $this->queryCode;
        }
        $out= str_replace("[^q^]", $queries, $out);
        $out= str_replace("[^qt^]", $queryTime, $out);
        $out= str_replace("[^p^]", $phpTime, $out);
        $out= str_replace("[^t^]", $totalTime, $out);
        $out= str_replace("[^s^]", $source, $out);
        //$this->documentOutput= $out;

        // invoke OnWebPagePrerender event
        if (!$noEvent) {
            $this->invokeEvent("OnWebPagePrerender");
        }

        echo $this->documentOutput;
        ob_end_flush();
    }

    function checkPublishStatus() {
        $cacheRefreshTime= 0;
        @include $this->config["base_path"] . "assets/cache/sitePublishing.idx.php";
        $timeNow= time() + $this->config['server_offset_time'];
        if ($cacheRefreshTime <= $timeNow && $cacheRefreshTime != 0) {
            // now, check for documents that need publishing
            $sql = "UPDATE ".$this->getFullTableName("site_content")." SET published=1, publishedon=".time()." WHERE ".$this->getFullTableName("site_content").".pub_date < $timeNow AND ".$this->getFullTableName("site_content").".pub_date!=0 AND published=0";
            if (@ !$result= $this->dbQuery($sql)) {
                $this->messageQuit("Execution of a query to the database failed", $sql);
            }

            // now, check for documents that need un-publishing
            $sql= "UPDATE " . $this->getFullTableName("site_content") . " SET published=0, publishedon=0 WHERE " . $this->getFullTableName("site_content") . ".unpub_date < $timeNow AND " . $this->getFullTableName("site_content") . ".unpub_date!=0 AND published=1";
            if (@ !$result= $this->dbQuery($sql)) {
                $this->messageQuit("Execution of a query to the database failed", $sql);
            }

            // clear the cache
            $basepath= $this->config["base_path"] . "assets/cache/";
            if ($handle= opendir($basepath)) {
                $filesincache= 0;
                $deletedfilesincache= 0;
                while (false !== ($file= readdir($handle))) {
                    if ($file != "." && $file != "..") {
                        $filesincache += 1;
                        if (preg_match("/\.pageCache/", $file)) {
                            $deletedfilesincache += 1;
                            while (!unlink($basepath . "/" . $file));
                        }
                    }
                }
                closedir($handle);
            }

            // update publish time file
            $timesArr= array ();
            $sql= "SELECT MIN(pub_date) AS minpub FROM " . $this->getFullTableName("site_content") . " WHERE pub_date>$timeNow";
            if (@ !$result= $this->dbQuery($sql)) {
                $this->messageQuit("Failed to find publishing timestamps", $sql);
            }
            $tmpRow= $this->fetchRow($result);
            $minpub= $tmpRow['minpub'];
            if ($minpub != NULL) {
                $timesArr[]= $minpub;
            }

            $sql= "SELECT MIN(unpub_date) AS minunpub FROM " . $this->getFullTableName("site_content") . " WHERE unpub_date>$timeNow";
            if (@ !$result= $this->dbQuery($sql)) {
                $this->messageQuit("Failed to find publishing timestamps", $sql);
            }
            $tmpRow= $this->fetchRow($result);
            $minunpub= $tmpRow['minunpub'];
            if ($minunpub != NULL) {
                $timesArr[]= $minunpub;
            }

            if (count($timesArr) > 0) {
                $nextevent= min($timesArr);
            } else {
                $nextevent= 0;
            }

            $basepath= $this->config["base_path"] . "assets/cache";
            $fp= @ fopen($basepath . "/sitePublishing.idx.php", "wb");
            if ($fp) {
                @ flock($fp, LOCK_EX);
                @ fwrite($fp, "<?php \$cacheRefreshTime=$nextevent; ?>");
                @ flock($fp, LOCK_UN);
                @ fclose($fp);
            }
        }
    }

    function postProcess() {
        // if the current document was generated, cache it!
        if ($this->documentGenerated == 1 && $this->documentObject['cacheable'] == 1 && $this->documentObject['type'] == 'document' && $this->documentObject['published'] == 1) {
            $basepath= $this->config["base_path"] . "assets/cache";
            // invoke OnBeforeSaveWebPageCache event
            $this->invokeEvent("OnBeforeSaveWebPageCache");
            if ($fp= @ fopen($basepath . "/docid_" . $this->documentIdentifier . ".pageCache.php", "w")) {
                // get and store document groups inside document object. Document groups will be used to check security on cache pages
                $sql= "SELECT document_group FROM " . $this->getFullTableName("document_groups") . " WHERE document='" . $this->documentIdentifier . "'";
                $docGroups= $this->db->getColumn("document_group", $sql);

				// Attach Document Groups and Scripts
				if (is_array($docGroups)) $this->documentObject['__MODxDocGroups__'] = implode(",", $docGroups);

                $docObjSerial= serialize($this->documentObject);
                $cacheContent= $docObjSerial . "<!--__MODxCacheSpliter__-->" . $this->documentContent;
                fputs($fp, "<?php die('Unauthorized access.'); ?>$cacheContent");
                fclose($fp);
            }
        }

        // Useful for example to external page counters/stats packages
        if ($this->config['track_visitors'] == 1) {
            $this->invokeEvent('OnWebPageComplete');
        }
        // end post processing
    }

    function mergeDocumentMETATags($template) {
        if ($this->documentObject['haskeywords'] == 1) {
            // insert keywords
            $keywords= implode(", ", $this->getKeywords());
            $metas= "\t<meta name=\"keywords\" content=\"$keywords\" />\n";

	    // Don't process when cached
	    $this->documentObject['haskeywords'] = '0';
        }
        if ($this->documentObject['hasmetatags'] == 1) {
            // insert meta tags
            $tags= $this->getMETATags();
            foreach ($tags as $n => $col) {
                $tag= strtolower($col['tag']);
                $tagvalue= $col['tagvalue'];
                $tagstyle= $col['http_equiv'] ? 'http-equiv' : 'name';
                $metas .= "\t<meta $tagstyle=\"$tag\" content=\"$tagvalue\" />\n";
            }

	    // Don't process when cached
	    $this->documentObject['hasmetatags'] = '0';
        }
	if ($metas) $template = preg_replace("/(<head>)/i", "\\1\n\t" . trim($metas), $template);
        return $template;
    }

    // mod by Raymond
    function mergeDocumentContent($template) {
        $replace= array ();
        preg_match_all('~\[\*(.*?)\*\]~', $template, $matches);
        $variableCount= count($matches[1]);
        $basepath= $this->config["base_path"] . "manager/includes";
        for ($i= 0; $i < $variableCount; $i++) {
            $key= $matches[1][$i];
            $key= substr($key, 0, 1) == '#' ? substr($key, 1) : $key; // remove # for QuickEdit format
            $value= $this->documentObject[$key];
            if (is_array($value)) {
                include_once $basepath . "/tmplvars.format.inc.php";
                include_once $basepath . "/tmplvars.commands.inc.php";
                $w= "100%";
                $h= "300";
                $value= getTVDisplayFormat($value[0], $value[1], $value[2], $value[3], $value[4]);
            }
            $replace[$i]= $value;
        }
        $template= str_replace($matches[0], $replace, $template);

        return $template;
    }

    function mergeSettingsContent($template) {
        $replace= array ();
        $matches= array ();
        if (preg_match_all('~\[\(([a-z\_]*?)\)\]~', $template, $matches)) {
            $settingsCount= count($matches[1]);
            for ($i= 0; $i < $settingsCount; $i++) {
                if (array_key_exists($matches[1][$i], $this->config))
                    $replace[$i]= $this->config[$matches[1][$i]];
            }

            $template= str_replace($matches[0], $replace, $template);
        }
        return $template;
    }

    function mergeChunkContent($content) {
        $replace= array ();
        $matches= array ();
        if (preg_match_all('~{{(.*?)}}~', $content, $matches)) {
            $settingsCount= count($matches[1]);
            for ($i= 0; $i < $settingsCount; $i++) {
                if (isset ($this->chunkCache[$matches[1][$i]])) {
                    $replace[$i]= $this->chunkCache[$matches[1][$i]];
                } else {
                    $sql= "SELECT * FROM " . $this->getFullTableName("site_htmlsnippets") . " WHERE " . $this->getFullTableName("site_htmlsnippets") . ".name='" . mysql_escape_string($matches[1][$i]) . "';";
                    $result= $this->dbQuery($sql);
                    $limit= $this->recordCount($result);
                    if ($limit < 1) {
                        $this->chunkCache[$matches[1][$i]]= "";
                        $replace[$i]= "";
                    } else {
                        $row= $this->fetchRow($result);
                        $this->chunkCache[$matches[1][$i]]= $row['snippet'];
                        $replace[$i]= $row['snippet'];
                    }
                }
            }
            $content= str_replace($matches[0], $replace, $content);
        }
        return $content;
    }

    // Added by Raymond
    function mergePlaceholderContent($content) {
        $replace= array ();
        $matches= array ();
        if (preg_match_all('~\[\+(.*?)\+\]~', $content, $matches)) {
            $cnt= count($matches[1]);
            for ($i= 0; $i < $cnt; $i++) {
                $v= '';
                $key= $matches[1][$i];
                if (is_array($this->placeholders) && array_key_exists($key, $this->placeholders))
                    $v= $this->placeholders[$key];
                if ($v === '')
                    unset ($matches[0][$i]); // here we'll leave empty placeholders for last.
                else
                    $replace[$i]= $v;
            }
            $content= str_replace($matches[0], $replace, $content);
        }
        return $content;
    }

    // evalPlugin
    function evalPlugin($pluginCode, $params) {
        $etomite= $modx= & $this;
        $modx->event->params= & $params; // store params inside event object
        if (is_array($params)) {
            extract($params, EXTR_SKIP);
        }
        ob_start();
        eval ($pluginCode);
        $msg= ob_get_contents();
        ob_end_clean();
        if ($msg && isset ($php_errormsg)) {
            if (!strpos($php_errormsg, 'Deprecated')) { // ignore php5 strict errors
                // log error
                $this->logEvent(1, 3, "<b>$php_errormsg</b><br /><br /> $msg", $this->Event->activePlugin . " - Plugin");
                if ($this->isBackend())
                    $this->Event->alert("An error occurred while loading. Please see the event log for more information.<p />$msg");
            }
        } else {
            echo $msg;
        }
        unset ($modx->event->params);
    }

    function evalSnippet($snippet, $params) {
        $etomite= $modx= & $this;

        $modx->event->params= & $params; // store params inside event object
        if (is_array($params)) {
            extract($params, EXTR_SKIP);
        }
        ob_start();
        $snip= eval ($snippet);
        $msg= ob_get_contents();
        ob_end_clean();
        if ($msg && isset ($php_errormsg)) {
            if (!strpos($php_errormsg, 'Deprecated')) { // ignore php5 strict errors
                // log error
                $this->logEvent(1, 3, "<b>$php_errormsg</b><br /><br /> $msg", $this->currentSnippet . " - Snippet");
                if ($this->isBackend())
                    $this->Event->alert("An error occurred while loading. Please see the event log for more information<p />$msg");
            }
        }
        unset ($modx->event->params);
        return $msg . $snip;
    }

    function evalSnippets($documentSource) {
        preg_match_all('~\[\[(.*?)\]\]~', $documentSource, $matches);

        $etomite= & $this;

        if ($matchCount= count($matches[1])) {
            for ($i= 0; $i < $matchCount; $i++) {
                $spos= strpos($matches[1][$i], '?', 0);
                if ($spos !== false) {
                    $params= substr($matches[1][$i], $spos, strlen($matches[1][$i]));
                } else {
                    $params= '';
                }
                $matches[1][$i]= str_replace($params, '', $matches[1][$i]);
                $snippetParams[$i]= $params;
            }
            $nrSnippetsToGet= $matchCount;
            for ($i= 0; $i < $nrSnippetsToGet; $i++) { // Raymond: Mod for Snippet props
                if (isset ($this->snippetCache[$matches[1][$i]])) {
                    $snippets[$i]['name']= $matches[1][$i];
                    $snippets[$i]['snippet']= $this->snippetCache[$matches[1][$i]];
                    if (array_key_exists($matches[1][$i] . "Props", $this->snippetCache))
                        $snippets[$i]['properties']= $this->snippetCache[$matches[1][$i] . "Props"];
                } else {
                    // get from db and store a copy inside cache
                    $sql= "SELECT * FROM " . $this->getFullTableName("site_snippets") . " WHERE " . $this->getFullTableName("site_snippets") . ".name='" . mysql_escape_string($matches[1][$i]) . "';";
                    $result= $this->dbQuery($sql);
                    if ($this->recordCount($result) == 1) {
                        $row= $this->fetchRow($result);
                        $snippets[$i]['name']= $row['name'];
                        $snippets[$i]['snippet']= $this->snippetCache[$row['name']]= $row['snippet'];
                        $snippets[$i]['properties']= $this->snippetCache[$row['name'] . "Props"]= $row['properties'];
                    } else {
                        $snippets[$i]['name']= $matches[1][$i];
                        $snippets[$i]['snippet']= $this->snippetCache[$matches[1][$i]]= "return false;";
                        $snippets[$i]['properties']= '';
                    }
                }
            }

            for ($i= 0; $i < $nrSnippetsToGet; $i++) {
                $parameter= array ();
                $snippetName= $this->currentSnippet= $snippets[$i]['name'];
                // FIXME Undefined index: properties
                if (array_key_exists('properties', $snippets[$i])) {
                    $snippetProperties= $snippets[$i]['properties'];
                } else {
                    $snippetProperties= '';
                }
                // load default params/properties - Raymond
                // FIXME Undefined variable: snippetProperties
                $parameter= $this->parseProperties($snippetProperties);
                // current params
                $currentSnippetParams= $snippetParams[$i];
                if (!empty ($currentSnippetParams)) {
                    $tempSnippetParams= str_replace("?", "", $currentSnippetParams);
                    $splitter= "&";
                    if (strpos($tempSnippetParams, "&amp;") > 0)
                        $tempSnippetParams= str_replace("&amp;", "&", $tempSnippetParams);
                    //$tempSnippetParams = html_entity_decode($tempSnippetParams, ENT_NOQUOTES, $this->config['etomite_charset']); //FS#334 and FS#456
                    $tempSnippetParams= split($splitter, $tempSnippetParams);
                    $snippetParamCount= count($tempSnippetParams);
                    for ($x= 0; $x < $snippetParamCount; $x++) {
                        if (strpos($tempSnippetParams[$x], '=', 0)) {
                            if ($parameterTemp= explode("=", $tempSnippetParams[$x])) {
                                $fp= strpos($parameterTemp[1], '`');
                                $lp= strrpos($parameterTemp[1], '`');
                                if (!($fp === false && $lp === false))
                                    $parameterTemp[1]= substr($parameterTemp[1], $fp +1, $lp -1);
                                $parameter[$parameterTemp[0]]= $parameterTemp[1];
                            }
                        }
                    }
                }
                $executedSnippets[$i]= $this->evalSnippet($snippets[$i]['snippet'], $parameter);
                if ($this->dumpSnippets == 1) {
                    echo "<fieldset><legend><b>$snippetName</b></legend><textarea style='width:60%; height:200px'>" . htmlentities($executedSnippets[$i]) . "</textarea></fieldset><br />";
                }
                $documentSource= str_replace("[[" . $snippetName . $currentSnippetParams . "]]", $executedSnippets[$i], $documentSource);
            }
        }
        return $documentSource;
    }

    function makeFriendlyURL($pre, $suff, $alias) {
        $dir= dirname($alias);
        return ($dir != '.' ? "$dir/" : "") . $pre . basename($alias) . $suff;
    }

    function rewriteUrls($documentSource) {
        // rewrite the urls
        if ($this->config['friendly_urls'] == 1) {
            $aliases= array ();
            foreach ($this->aliasListing as $item) {
                $aliases[$item['id']]= (strlen($item['path']) > 0 ? $item['path'] . '/' : '') . $item['alias'];
            }
            $in= '!\[\~([0-9]+)\~\]!ise'; // Use preg_replace with /e to make it evaluate PHP
            $isfriendly= ($this->config['friendly_alias_urls'] == 1 ? 1 : 0);
            $pref= $this->config['friendly_url_prefix'];
            $suff= $this->config['friendly_url_suffix'];
            $thealias= '$aliases[\\1]';
            $found_friendlyurl= "\$this->makeFriendlyURL('$pref','$suff',$thealias)";
            $not_found_friendlyurl= "\$this->makeFriendlyURL('$pref','$suff','" . '\\1' . "')";
            $out= "({$isfriendly} && isset({$thealias}) ? {$found_friendlyurl} : {$not_found_friendlyurl})";
            $documentSource= preg_replace($in, $out, $documentSource);
        } else {
            $in= '!\[\~([0-9]+)\~\]!is';
            $out= "index.php?id=" . '\1';
            $documentSource= preg_replace($in, $out, $documentSource);
        }
        return $documentSource;
    }

    /**
     * name: getDocumentObject  - used by parser
     * desc: returns a document object - $method: alias, id
     */
    function getDocumentObject($method, $identifier) {
        $tblsc= $this->getFullTableName("site_content");
        $tbldg= $this->getFullTableName("document_groups");

        // get document groups for current user
        if ($docgrp= $this->getUserDocGroups())
            $docgrp= implode(",", $docgrp);
        // get document
        $access= ($this->isFrontend() ? "sc.privateweb=0" : "1='" . $_SESSION['mgrRole'] . "' OR sc.privatemgr=0") .
         (!$docgrp ? "" : " OR dg.document_group IN ($docgrp)");
        $sql= "SELECT sc.*
              FROM $tblsc sc
              LEFT JOIN $tbldg dg ON dg.document = sc.id
              WHERE sc." . $method . " = '" . $identifier . "'
              AND ($access) LIMIT 1;";
        $result= $this->db->query($sql);
        $rowCount= $this->recordCount($result);
        if ($rowCount < 1) {
            if ($this->config['unauthorized_page']) {
                // Fix for FS #375 - netnoise 2006/08/14
                if ($method != 'id')
                    $identifier= $this->cleanDocumentIdentifier($identifier);
                if (!is_numeric($identifier) && array_key_exists($identifier, $this->documentListing)) {
                    $identifier= $this->documentListing[$identifier];
                    $method= 'id';
                }

                // check if file is not public
                $secrs= $this->dbQuery("SELECT id FROM $tbldg WHERE document = '" . $identifier . "' LIMIT 1;");
                if ($secrs)
                    $seclimit= mysql_num_rows($secrs);
            }
            if ($seclimit > 0) {
                // match found but not publicly accessible, send the visitor to the unauthorized_page
                $this->sendUnauthorizedPage();
                exit; // stop here
            } else {
                $this->sendErrorPage();
                exit;
            }
        }

        # this is now the document :) #
        $documentObject= $this->fetchRow($result);

        // load TVs and merge with document - Orig by Apodigm - Docvars
        $sql= "SELECT tv.*, IF(tvc.value!='',tvc.value,tv.default_text) as value ";
        $sql .= "FROM " . $this->getFullTableName("site_tmplvars") . " tv ";
        $sql .= "INNER JOIN " . $this->getFullTableName("site_tmplvar_templates")." tvtpl ON tvtpl.tmplvarid = tv.id ";
        $sql .= "LEFT JOIN " . $this->getFullTableName("site_tmplvar_contentvalues")." tvc ON tvc.tmplvarid=tv.id AND tvc.contentid = '" . $this->documentIdentifier . "' ";
        $sql .= "WHERE tvtpl.templateid = '" . $documentObject['template'] . "'";
        $rs= $this->dbQuery($sql);
        $rowCount= $this->recordCount($rs);
        if ($rowCount > 0) {
            for ($i= 0; $i < $rowCount; $i++) {
                $row= $this->fetchRow($rs);
                $tmplvars[$row['name']]= array (
                    $row['name'],
                    $row['value'],
                    $row['display'],
                    $row['display_params'],
                    $row['type']
                );
            }
            $documentObject= array_merge($documentObject, $tmplvars);
        }
        return $documentObject;
    }

    /**
     * name: parseDocumentSource - used by parser
     * desc: return document source aftering parsing tvs, snippets, chunks, etc.
     */
    function parseDocumentSource($source) {
        // set the number of times we are to parse the document source
        $this->minParserPasses= empty ($this->minParserPasses) ? 2 : $this->minParserPasses;
        $this->maxParserPasses= empty ($this->maxParserPasses) ? 10 : $this->maxParserPasses;
        $passes= $this->minParserPasses;
        for ($i= 0; $i < $passes; $i++) {
            // get source length if this is the final pass
            if ($i == ($passes -1))
                $st= strlen($source);
            if ($this->dumpSnippets == 1) {
                echo "<fieldset><legend><b style='color: #821517;'>PARSE PASS " . ($i +1) . "</b></legend>The following snippets (if any) were parsed during this pass.<div style='width:100%' align='center'>";
            }

            // invoke OnParseDocument event
            $this->documentOutput= $source; // store source code so plugins can
            $this->invokeEvent("OnParseDocument"); // work on it via $modx->documentOutput
            $source= $this->documentOutput;

            // combine template and document variables
            $source= $this->mergeDocumentContent($source);
            // replace settings referenced in document
            $source= $this->mergeSettingsContent($source);
            // replace HTMLSnippets in document
            $source= $this->mergeChunkContent($source);
	    // insert META tags & keywords
	    $source= $this->mergeDocumentMETATags($source);
            // find and merge snippets
            $source= $this->evalSnippets($source);
            // find and replace Placeholders (must be parsed last) - Added by Raymond
            $source= $this->mergePlaceholderContent($source);
            if ($this->dumpSnippets == 1) {
                echo "</div></fieldset><br />";
            }
            if ($i == ($passes -1) && $i < ($this->maxParserPasses - 1)) {
                // check if source length was changed
                $et= strlen($source);
                if ($st != $et)
                    $passes++; // if content change then increase passes because
            } // we have not yet reached maxParserPasses
        }
        return $source;
    }

    function executeParser() {
        //error_reporting(0);
        if (version_compare(phpversion(), "5.0.0", ">="))
            set_error_handler(array (
                & $this,
                "phpError"
            ), E_ALL);
        else
            set_error_handler(array (
                & $this,
                "phpError"
            ));

        $this->db->connect();

        // get the settings
        if (empty ($this->config)) {
            $this->getSettings();
        }

        // IIS friendly url fix
        if ($this->config['friendly_urls'] == 1 && strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== false) {
            $url= $_SERVER['QUERY_STRING'];
            $err= substr($url, 0, 3);
            if ($err == '404' || $err == '405') {
                $k= array_keys($_GET);
                unset ($_GET[$k[0]]);
                unset ($_REQUEST[$k[0]]); // remove 404,405 entry
                $_SERVER['QUERY_STRING']= $qp['query'];
                $qp= parse_url(str_replace($this->config['site_url'], '', substr($url, 4)));
                if (!empty ($qp['query'])) {
                    parse_str($qp['query'], $qv);
                    foreach ($qv as $n => $v)
                        $_REQUEST[$n]= $_GET[$n]= $v;
                }
                $_SERVER['PHP_SELF']= $this->config['base_url'] . $qp['path'];
                $_REQUEST['q']= $_GET['q']= $qp['path'];
            }
        }

        // check site settings
        if (!$this->checkSiteStatus()) {
            header('HTTP/1.0 503 Service Unavailable');
            if (!$this->config['site_unavailable_page']) {
                // display offline message
                $this->documentContent= $this->config['site_unavailable_message'];
                $this->outputContent();
                exit; // stop processing here, as the site's offline
            } else {
                // setup offline page document settings
                $this->documentMethod= "id";
                $this->documentIdentifier= $this->config['site_unavailable_page'];
            }
        } else {
            // make sure the cache doesn't need updating
            $this->checkPublishStatus();

            // find out which document we need to display
            $this->documentMethod= $this->getDocumentMethod();
            $this->documentIdentifier= $this->getDocumentIdentifier($this->documentMethod);
        }

        if ($this->documentMethod == "none") {
            $this->documentMethod= "id"; // now we know the site_start, change the none method to id
        }
        if ($this->documentMethod == "alias") {
            $this->documentIdentifier= $this->cleanDocumentIdentifier($this->documentIdentifier);
        }

        if ($this->documentMethod == "alias") {
            // Check use_alias_path and check if $this->virtualDir is set to anything, then parse the path
            if ($this->config['use_alias_path'] == 1) {
                $alias= (strlen($this->virtualDir) > 0 ? $this->virtualDir . '/' : '') . $this->documentIdentifier;
                if (array_key_exists($alias, $this->documentListing)) {
                    $this->documentIdentifier= $this->documentListing[$alias];
                } else {
                    $this->sendErrorPage();
                }
            } else {
                $this->documentIdentifier= $this->documentListing[$this->documentIdentifier];
            }
            $this->documentMethod= 'id';
        }

        // invoke OnWebPageInit event
        $this->invokeEvent("OnWebPageInit");

        // invoke OnLogPageView event
        if ($this->config['track_visitors'] == 1) {
            $this->invokeEvent("OnLogPageHit");
        }

        $this->prepareResponse();
    }

    function prepareResponse() {
        // we now know the method and identifier, let's check the cache
        $this->documentContent= $this->checkCache($this->documentIdentifier);
        if ($this->documentContent != "") {
            // invoke OnLoadWebPageCache  event
            $this->invokeEvent("OnLoadWebPageCache");
        } else {
            // get document object
            $this->documentObject= $this->getDocumentObject($this->documentMethod, $this->documentIdentifier);

            // write the documentName to the object
            $this->documentName= $this->documentObject['pagetitle'];

            // validation routines
            if ($this->documentObject['deleted'] == 1) {
                $this->sendErrorPage();
            }
            //  && !$this->checkPreview()
            if ($this->documentObject['published'] == 0) {

                // Can't view unpublished pages
                if (!$this->hasPermission('view_unpublished')) {
                    $this->sendErrorPage();
                } else {
                    // Inculde the necessary files to check document permissions
                    include_once ($this->config['base_path'] . '/manager/processors/user_documents_permissions.class.php');
                    $udperms= new udperms();
                    $udperms->user= $this->getLoginUserID();
                    $udperms->document= $this->documentIdentifier;
                    $udperms->role= $_SESSION['mgrRole'];
                    // Doesn't have access to this document
                    if (!$udperms->checkPermissions()) {
                        $this->sendErrorPage();
                    }

                }

            }

            // check whether it's a reference
            if ($this->documentObject['type'] == "reference") {
                if (is_numeric($this->documentObject['content'])) {
                    // if it's a bare document id
                    $this->documentObject['content']= $this->makeUrl($this->documentObject['content']);
                }
                elseif (strpos($this->documentObject['content'], '[~') !== false) {
                    // if it's an internal docid tag, process it
                    $this->documentObject['content']= $this->rewriteUrls($this->documentObject['content']);
                }
                $this->sendRedirect($this->documentObject['content'], 0, '', 'HTTP/1.0 301 Moved Permanently');
            }

            // check if we should not hit this document
            if ($this->documentObject['donthit'] == 1) {
                $this->config['track_visitors']= 0;
            }

            // get the template and start parsing!
            if (!$this->documentObject['template'])
                $this->documentContent= "[*content*]"; // use blank template
            else {
                $sql= "SELECT * FROM " . $this->getFullTableName("site_templates") . " WHERE " . $this->getFullTableName("site_templates") . ".id = '" . $this->documentObject['template'] . "';";
                $result= $this->dbQuery($sql);
                $rowCount= $this->recordCount($result);
                if ($rowCount > 1) {
                    $this->messageQuit("Incorrect number of templates returned from database", $sql);
                }
                elseif ($rowCount == 1) {
                    $row= $this->fetchRow($result);
                    $this->documentContent= $row['content'];
                }
            }

            // invoke OnLoadWebDocument event
            $this->invokeEvent("OnLoadWebDocument");

            // Parse document source
            $this->documentContent= $this->parseDocumentSource($this->documentContent);

            // setup <base> tag for friendly urls
            //			if($this->config['friendly_urls']==1 && $this->config['use_alias_path']==1) {
            //				$this->regClientStartupHTMLBlock('<base href="'.$this->config['site_url'].'" />');
            //			}
        }
        register_shutdown_function(array (
            & $this,
            "postProcess"
        )); // tell PHP to call postProcess when it shuts down
        $this->outputContent();
        //$this->postProcess();
    }

    /***************************************************************************************/
    /* API functions																/
    /***************************************************************************************/

    function getParentIds($id, $height= 10, $parents= array ()) {
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
        if ($parentId && $height) {
            $parents= $parents + $this->getParentIds($parentId, $height, $parents);
        }
        return $parents;
    }

    function getChildIds($id, $depth= 10, $children= array ()) {
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
    function webAlert($msg, $url= "") {
        $msg= addslashes(mysql_escape_string($msg));
        if (substr(strtolower($url), 0, 11) == "javascript:") {
            $act= "__WebAlert();";
            $fnc= "function __WebAlert(){" . substr($url, 11) . "};";
        } else {
            $act= ($url ? "window.location.href='" . addslashes($url) . "';" : "");
        }
        $html= "<script>$fnc window.setTimeout(\"alert('$msg');$act\",100);</script>";
        if ($this->isFrontend($html))
            $this->regClientScript($html);
        else {
            echo $html;
        }
    }

    # Returns true if user has the currect permission
    function hasPermission($pm) {
        $state= false;
        $pms= $_SESSION['mgrPermissions'];
        if ($pms)
            $state= ($pms[$pm] == 1);
        return $state;
    }

    # Add an a alert message to the system event log
    function logEvent($evtid, $type, $msg, $source= 'Parser') {
        $msg= mysql_escape_string($msg);
        $source= mysql_escape_string($source);
        $evtid= intval($evtid);
        if ($type < 1) {
            $type= 1;
        }
        elseif ($type > 3) {
            $type= 3; // Types: 1 = information, 2 = warning, 3 = error
        }
        $sql= "INSERT INTO " . $this->getFullTableName("event_log") . " (eventid,type,createdon,source,description,user) " .
        "VALUES($evtid,$type," . time() . ",'$source','$msg','" . $this->getLoginUserID() . "')";
        $ds= @mysql_query($sql);
        if (!$ds) {
            echo "Error while inserting event log into database.";
            exit();
        }
    }

    # Returns true if parser is executed in backend (manager) mode
    function isBackend() {
        return $this->insideManager() ? true : false;
    }

    # Returns true if parser is executed in frontend mode
    function isFrontend() {
        return !$this->insideManager() ? true : false;
    }

    function getAllChildren($id= 0, $sort= 'menuindex', $dir= 'ASC', $fields= 'id, pagetitle, description, parent, alias, menutitle') {
        $tblsc= $this->getFullTableName("site_content");
        $tbldg= $this->getFullTableName("document_groups");
        // modify field names to use sc. table reference
        $fields= 'sc.' . implode(',sc.', preg_replace("/^\s/i", "", explode(',', $fields)));
        $sort= 'sc.' . implode(',sc.', preg_replace("/^\s/i", "", explode(',', $sort)));
        // get document groups for current user
        if ($docgrp= $this->getUserDocGroups())
            $docgrp= implode(",", $docgrp);
        // build query
        $access= ($this->isFrontend() ? "sc.privateweb=0" : "1='" . $_SESSION['mgrRole'] . "' OR sc.privatemgr=0") .
         (!$docgrp ? "" : " OR dg.document_group IN ($docgrp)");
        $sql= "SELECT DISTINCT $fields FROM $tblsc sc
              LEFT JOIN $tbldg dg on dg.document = sc.id
              WHERE sc.parent = '$id'
              AND ($access)
              GROUP BY sc.id
              ORDER BY $sort $dir;";
        $result= $this->dbQuery($sql);
        $resourceArray= array ();
        for ($i= 0; $i < @ $this->recordCount($result); $i++) {
            array_push($resourceArray, @ $this->fetchRow($result));
        }
        return $resourceArray;
    }

    function getActiveChildren($id= 0, $sort= 'menuindex', $dir= 'ASC', $fields= 'id, pagetitle, description, parent, alias, menutitle') {
        $tblsc= $this->getFullTableName("site_content");
        $tbldg= $this->getFullTableName("document_groups");

        // modify field names to use sc. table reference
        $fields= 'sc.' . implode(',sc.', preg_replace("/^\s/i", "", explode(',', $fields)));
        $sort= 'sc.' . implode(',sc.', preg_replace("/^\s/i", "", explode(',', $sort)));
        // get document groups for current user
        if ($docgrp= $this->getUserDocGroups())
            $docgrp= implode(",", $docgrp);
        // build query
        $access= ($this->isFrontend() ? "sc.privateweb=0" : "1='" . $_SESSION['mgrRole'] . "' OR sc.privatemgr=0") .
         (!$docgrp ? "" : " OR dg.document_group IN ($docgrp)");
        $sql= "SELECT DISTINCT $fields FROM $tblsc sc
              LEFT JOIN $tbldg dg on dg.document = sc.id
              WHERE sc.parent = '$id' AND sc.published=1 AND sc.deleted=0
              AND ($access)
              GROUP BY sc.id
              ORDER BY $sort $dir;";
        $result= $this->dbQuery($sql);
        $resourceArray= array ();
        for ($i= 0; $i < @ $this->recordCount($result); $i++) {
            array_push($resourceArray, @ $this->fetchRow($result));
        }
        return $resourceArray;
    }

    function getDocumentChildren($parentid= 0, $published= 1, $deleted= 0, $fields= "*", $where= '', $sort= "menuindex", $dir= "ASC", $limit= "") {
        $limit= ($limit != "") ? "LIMIT $limit" : "";
        $tblsc= $this->getFullTableName("site_content");
        $tbldg= $this->getFullTableName("document_groups");
        // modify field names to use sc. table reference
        $fields= 'sc.' . implode(',sc.', preg_replace("/^\s/i", "", explode(',', $fields)));
        $sort= ($sort == "") ? "" : 'sc.' . implode(',sc.', preg_replace("/^\s/i", "", explode(',', $sort)));
        if ($where != '')
            $where= 'AND ' . $where;
        // get document groups for current user
        if ($docgrp= $this->getUserDocGroups())
            $docgrp= implode(",", $docgrp);
        // build query
        $access= ($this->isFrontend() ? "sc.privateweb=0" : "1='" . $_SESSION['mgrRole'] . "' OR sc.privatemgr=0") .
         (!$docgrp ? "" : " OR dg.document_group IN ($docgrp)");
        $sql= "SELECT DISTINCT $fields
              FROM $tblsc sc
              LEFT JOIN $tbldg dg on dg.document = sc.id
              WHERE sc.parent = '$parentid' AND sc.published=$published AND sc.deleted=$deleted $where
              AND ($access)
              GROUP BY sc.id " .
         ($sort ? " ORDER BY $sort $dir " : "") . " $limit ";
        $result= $this->dbQuery($sql);
        $resourceArray= array ();
        for ($i= 0; $i < @ $this->recordCount($result); $i++) {
            array_push($resourceArray, @ $this->fetchRow($result));
        }
        return $resourceArray;
    }

    function getDocuments($ids= array (), $published= 1, $deleted= 0, $fields= "*", $where= '', $sort= "menuindex", $dir= "ASC", $limit= "") {
        if (count($ids) == 0) {
            return false;
        } else {
            $limit= ($limit != "") ? "LIMIT $limit" : ""; // LIMIT capabilities - rad14701
            $tblsc= $this->getFullTableName("site_content");
            $tbldg= $this->getFullTableName("document_groups");
            // modify field names to use sc. table reference
            $fields= 'sc.' . implode(',sc.', preg_replace("/^\s/i", "", explode(',', $fields)));
            $sort= ($sort == "") ? "" : 'sc.' . implode(',sc.', preg_replace("/^\s/i", "", explode(',', $sort)));
            if ($where != '')
                $where= 'AND ' . $where;
            // get document groups for current user
            if ($docgrp= $this->getUserDocGroups())
                $docgrp= implode(",", $docgrp);
            $access= ($this->isFrontend() ? "sc.privateweb=0" : "1='" . $_SESSION['mgrRole'] . "' OR sc.privatemgr=0") .
             (!$docgrp ? "" : " OR dg.document_group IN ($docgrp)");
            $sql= "SELECT DISTINCT $fields FROM $tblsc sc
                    LEFT JOIN $tbldg dg on dg.document = sc.id
                    WHERE (sc.id IN (" . join($ids, ",") . ") AND sc.published=$published AND sc.deleted=$deleted $where)
                    AND ($access)
                    GROUP BY sc.id " .
             ($sort ? " ORDER BY $sort $dir" : "") . " $limit ";
            $result= $this->dbQuery($sql);
            $resourceArray= array ();
            for ($i= 0; $i < @ $this->recordCount($result); $i++) {
                array_push($resourceArray, @ $this->fetchRow($result));
            }
            return $resourceArray;
        }
    }

    function getDocument($id= 0, $fields= "*", $published= 1, $deleted= 0) {
        if ($id == 0) {
            return false;
        } else {
            $tmpArr[]= $id;
            $docs= $this->getDocuments($tmpArr, $published, $deleted, $fields, "", "", "", 1);
            if ($docs != false) {
                return $docs[0];
            } else {
                return false;
            }
        }
    }

    function getPageInfo($pageid= -1, $active= 1, $fields= 'id, pagetitle, description, alias') {
        if ($pageid == 0) {
            return false;
        } else {
            $tblsc= $this->getFullTableName("site_content");
            $tbldg= $this->getFullTableName("document_groups");
            $activeSql= $active == 1 ? "AND sc.published=1 AND sc.deleted=0" : "";
            // modify field names to use sc. table reference
            $fields= 'sc.' . implode(',sc.', preg_replace("/^\s/i", "", explode(',', $fields)));
            // get document groups for current user
            if ($docgrp= $this->getUserDocGroups())
                $docgrp= implode(",", $docgrp);
            $access= ($this->isFrontend() ? "sc.privateweb=0" : "1='" . $_SESSION['mgrRole'] . "' OR sc.privatemgr=0") .
             (!$docgrp ? "" : " OR dg.document_group IN ($docgrp)");
            $sql= "SELECT $fields
                    FROM $tblsc sc
                    LEFT JOIN $tbldg dg on dg.document = sc.id
                    WHERE (sc.id=$pageid $activeSql)
                    AND ($access)
                    LIMIT 1 ";
            $result= $this->dbQuery($sql);
            $pageInfo= @ $this->fetchRow($result);
            return $pageInfo;
        }
    }

    function getParent($pid= -1, $active= 1, $fields= 'id, pagetitle, description, alias, parent') {
        if ($pid == -1) {
            $pid= $this->documentObject['parent'];
            return ($pid == 0) ? false : $this->getPageInfo($pid, $active, $fields);
        } else
            if ($pid == 0) {
                return false;
            } else {
                // first get the child document
                $child= $this->getPageInfo($pid, $active, "parent");
                // now return the child's parent
                $pid= ($child['parent']) ? $child['parent'] : 0;
                return ($pid == 0) ? false : $this->getPageInfo($pid, $active, $fields);
            }
    }

    function getSnippetId() {
        if ($this->currentSnippet) {
            $tbl= $this->getFullTableName("site_snippets");
            $rs= $this->dbQuery("SELECT id FROM $tbl WHERE name='" . mysql_escape_string($this->currentSnippet) . "' LIMIT 1");
            $row= @ $this->fetchRow($rs);
            if ($row['id'])
                return $row['id'];
        }
        return 0;
    }

    function getSnippetName() {
        return $this->currentSnippet;
    }

    function clearCache() {
        $basepath= $this->config["base_path"] . "assets/cache";
        if (@ $handle= opendir($basepath)) {
            $filesincache= 0;
            $deletedfilesincache= 0;
            while (false !== ($file= readdir($handle))) {
                if ($file != "." && $file != "..") {
                    $filesincache += 1;
                    if (preg_match("/\.pageCache/", $file)) {
                        $deletedfilesincache += 1;
                        unlink($basepath . "/" . $file);
                    }
                }
            }
            closedir($handle);
            return true;
        } else {
            return false;
        }
    }

    function makeUrl($id, $alias= '', $args= '', $scheme= '') {
        $url= '';
        $virtualDir= '';
        if (!is_numeric($id)) {
            $this->messageQuit('`' . $id . '` is not numeric and may not be passed to makeUrl()');
        }
        if ($args != '' && $this->config['friendly_urls'] == 1) {
            // add ? to $args if missing
            $c= substr($args, 0, 1);
            if (strpos($this->config['friendly_url_prefix'], '?') === false) {
                if ($c == '&')
                    $args= '?' . substr($args, 1);
                elseif ($c != '?') $args= '?' . $args;
            } else {
                if ($c == '?')
                    $args= '&' . substr($args, 1);
                elseif ($c != '&') $args= '&' . $args;
            }
        }
        elseif ($args != '') {
            // add & to $args if missing
            $c= substr($args, 0, 1);
            if ($c == '?')
                $args= '&' . substr($args, 1);
            elseif ($c != '&') $args= '&' . $args;
        }
        if ($this->config['friendly_urls'] == 1 && $alias != '') {
            $url= $this->config['friendly_url_prefix'] . $alias . $this->config['friendly_url_suffix'] . $args;
        }
        elseif ($this->config['friendly_urls'] == 1 && $alias == '') {
            $alias= $id;
            if ($this->config['friendly_alias_urls'] == 1) {
                $al= $this->aliasListing[$id];
                $alPath= !empty ($al['path']) ? $al['path'] . '/' : '';
                if ($al && $al['alias'])
                    $alias= $al['alias'];
            }
            $alias= $alPath . $this->config['friendly_url_prefix'] . $alias . $this->config['friendly_url_suffix'];
            $url= $alias . $args;
        } else {
            $url= 'index.php?id=' . $id . $args;
        }

        $host= $this->config['base_url'];
        // check if scheme argument has been set
        if ($scheme != '') {
            // for backward compatibility - check if the desired scheme is different than the current scheme
            if (is_numeric($scheme) && $scheme != $_SERVER['HTTPS']) {
                $scheme= ($_SERVER['HTTPS'] ? 'http' : 'https');
            }

            // to-do: check to make sure that $site_url incudes the url :port (e.g. :8080)
            $host= $scheme == 'full' ? $this->config['site_url'] : $scheme . '://' . $_SERVER['HTTP_HOST'] . $this->config['base_url'];
        }

        if ($this->config['xhtml_urls']) {
        	return preg_replace("/&(?!amp;)/","&amp;", $host . $virtualDir . $url);
        } else {
        	return $host . $virtualDir . $url;
        }
    }

    function getConfig($name= '') {
        if (!empty ($this->config[$name])) {
            return $this->config[$name];
        } else {
            return false;
        }
    }

    function getVersionData() {
        include $this->config["base_path"] . "manager/includes/version.inc.php";
        $v= array ();
        $v['code_name']= $code_name;
        $v['version']= $version;
        $v['small_version']= $small_version;
        $v['patch_level']= $patch_level;
        $v['full_appname']= $full_appname;
        return $v;
    }

    function makeList($array, $ulroot= 'root', $ulprefix= 'sub_', $type= '', $ordered= false, $tablevel= 0) {
        // first find out whether the value passed is an array
        if (!is_array($array)) {
            return "<ul><li>Bad list</li></ul>";
        }
        if (!empty ($type)) {
            $typestr= " style='list-style-type: $type'";
        } else {
            $typestr= "";
        }
        $tabs= "";
        for ($i= 0; $i < $tablevel; $i++) {
            $tabs .= "\t";
        }
        $listhtml= $ordered == true ? $tabs . "<ol class='$ulroot'$typestr>\n" : $tabs . "<ul class='$ulroot'$typestr>\n";
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $listhtml .= $tabs . "\t<li>" . $key . "\n" . $this->makeList($value, $ulprefix . $ulroot, $ulprefix, $type, $ordered, $tablevel +2) . $tabs . "\t</li>\n";
            } else {
                $listhtml .= $tabs . "\t<li>" . $value . "</li>\n";
            }
        }
        $listhtml .= $ordered == true ? $tabs . "</ol>\n" : $tabs . "</ul>\n";
        return $listhtml;
    }

    function userLoggedIn() {
        $userdetails= array ();
        if ($this->isFrontend() && isset ($_SESSION['webValidated'])) {
            // web user
            $userdetails['loggedIn']= true;
            $userdetails['id']= $_SESSION['webInternalKey'];
            $userdetails['username']= $_SESSION['webShortname'];
            $userdetails['usertype']= 'web'; // added by Raymond
            return $userdetails;
        } else
            if ($this->isBackend() && isset ($_SESSION['mgrValidated'])) {
                // manager user
                $userdetails['loggedIn']= true;
                $userdetails['id']= $_SESSION['mgrInternalKey'];
                $userdetails['username']= $_SESSION['mgrShortname'];
                $userdetails['usertype']= 'manager'; // added by Raymond
                return $userdetails;
            } else {
                return false;
            }
    }

    function getKeywords($id= 0) {
        if ($id == 0) {
            $id= $this->documentObject['id'];
        }
        $tblKeywords= $this->getFullTableName('site_keywords');
        $tblKeywordXref= $this->getFullTableName('keyword_xref');
        $sql= "SELECT keywords.keyword FROM " . $tblKeywords . " AS keywords INNER JOIN " . $tblKeywordXref . " AS xref ON keywords.id=xref.keyword_id WHERE xref.content_id = '$id'";
        $result= $this->dbQuery($sql);
        $limit= $this->recordCount($result);
        $keywords= array ();
        if ($limit > 0) {
            for ($i= 0; $i < $limit; $i++) {
                $row= $this->fetchRow($result);
                $keywords[]= $row['keyword'];
            }
        }
        return $keywords;
    }

    function getMETATags($id= 0) {
        if ($id == 0) {
            $id= $this->documentObject['id'];
        }
        $sql= "SELECT smt.* " .
        "FROM " . $this->getFullTableName("site_metatags") . " smt " .
        "INNER JOIN " . $this->getFullTableName("site_content_metatags") . " cmt ON cmt.metatag_id=smt.id " .
        "WHERE cmt.content_id = '$id'";
        $ds= $this->db->query($sql);
        $limit= $this->db->getRecordCount($ds);
        $metatags= array ();
        if ($limit > 0) {
            for ($i= 0; $i < $limit; $i++) {
                $row= $this->db->getRow($ds);
                $metatags[$row['name']]= array (
                    "tag" => $row['tag'],
                    "tagvalue" => $row['tagvalue'],
                    "http_equiv" => $row['http_equiv']
                );
            }
        }
        return $metatags;
    }

    function runSnippet($snippetName, $params= array ()) {
        if (isset ($this->snippetCache[$snippetName])) {
            $snippet= $this->snippetCache[$snippetName];
            $properties= $this->snippetCache[$snippetName . "Props"];
        } else { // not in cache so let's check the db
            $sql= "SELECT * FROM " . $this->getFullTableName("site_snippets") . " WHERE " . $this->getFullTableName("site_snippets") . ".name='" . mysql_escape_string($snippetName) . "';";
            $result= $this->dbQuery($sql);
            if ($this->recordCount($result) == 1) {
                $row= $this->fetchRow($result);
                $snippet= $this->snippetCache[$row['name']]= $row['snippet'];
                $properties= $this->snippetCache[$row['name'] . "Props"]= $row['properties'];
            } else {
                $snippet= $this->snippetCache[$snippetName]= "return false;";
                $properties= '';
            }
        }
        // load default params/properties
        $parameters= $this->parseProperties($properties);
        $parameters= array_merge($parameters, $params);
        // run snippet
        return $this->evalSnippet($snippet, $parameters);
    }

    function getChunk($chunkName) {
        $t= $this->chunkCache[$chunkName];
        return $t;
    }

    // deprecated
    function putChunk($chunkName) { // alias name >.<
        return $this->getChunk($chunkName);
    }

    function parseChunk($chunkName, $chunkArr, $prefix= "{", $suffix= "}") {
        if (!is_array($chunkArr)) {
            return false;
        }
        $chunk= $this->getChunk($chunkName);
        foreach ($chunkArr as $key => $value) {
            $chunk= str_replace($prefix . $key . $suffix, $value, $chunk);
        }
        return $chunk;
    }

    function getUserData() {
        include_once $this->config["base_path"] . "manager/includes/extenders/getUserData.extender.php";
        return $tmpArray;
    }

    #::::::::::::::::::::::::::::::::::::::::
    # Added By: Raymond Irving - MODx
    #

    function getDocumentChildrenTVars($parentid= 0, $tvidnames= array (), $published= 1, $docsort= "menuindex", $docsortdir= "ASC", $tvfields= "*", $tvsort= "rank", $tvsortdir= "ASC") {
        $docs= $this->getDocumentChildren($parentid, $published, 0, '*', '', $docsort, $docsortdir);
        if (!$docs)
            return false;
        else {
            $result= array ();
            // get user defined template variables
            $fields= ($tvfields == "") ? "tv.*" : 'tv.' . implode(',tv.', preg_replace("/^\s/i", "", explode(',', $tvfields)));
            $tvsort= ($tvsort == "") ? "" : 'tv.' . implode(',tv.', preg_replace("/^\s/i", "", explode(',', $tvsort)));
            if ($tvidnames == "*")
                $query= "tv.id<>0";
            else
                $query= (is_numeric($tvidnames[0]) ? "tv.id" : "tv.name") . " IN ('" . implode("','", $tvidnames) . "')";
            if ($docgrp= $this->getUserDocGroups())
                $docgrp= implode(",", $docgrp);

            $docCount= count($docs);
            for ($i= 0; $i < $docCount; $i++) {

                $tvs= array ();
                $docRow= $docs[$i];
                $docid= $docRow['id'];

                $sql= "SELECT $fields, IF(tvc.value!='',tvc.value,tv.default_text) as value ";
                $sql .= "FROM " . $this->getFullTableName('site_tmplvars') . " tv ";
                $sql .= "INNER JOIN " . $this->getFullTableName('site_tmplvar_templates')." tvtpl ON tvtpl.tmplvarid = tv.id ";
                $sql .= "LEFT JOIN " . $this->getFullTableName('site_tmplvar_contentvalues')." tvc ON tvc.tmplvarid=tv.id AND tvc.contentid = '" . $docid . "' ";
                $sql .= "WHERE " . $query . " AND tvtpl.templateid = " . $docRow['template'];
                if ($tvsort)
                    $sql .= " ORDER BY $tvsort $tvsortdir ";
                $rs= $this->dbQuery($sql);
                $limit= @ $this->recordCount($rs);
                for ($x= 0; $x < $limit; $x++) {
                    array_push($tvs, @ $this->fetchRow($rs));
                }

                // get default/built-in template variables
                ksort($docRow);
                foreach ($docRow as $key => $value) {
                    if ($tvidnames == "*" || in_array($key, $tvidnames))
                        array_push($tvs, array (
                            "name" => $key,
                            "value" => $value
                        ));
                }

                if (count($tvs))
                    array_push($result, $tvs);
            }
            return $result;
        }
    }

    function getDocumentChildrenTVarOutput($parentid= 0, $tvidnames= array (), $published= 1, $docsort= "menuindex", $docsortdir= "ASC") {
        $docs= $this->getDocumentChildren($parentid, $published, 0, '*', '', $docsort, $docsortdir);
        if (!$docs)
            return false;
        else {
            $result= array ();
            for ($i= 0; $i < count($docs); $i++) {
                $tvs= $this->getTemplateVarOutput($tvidnames, $docs[$i]["id"], $published);
                if ($tvs)
                    $result[$docs[$i]['id']]= $tvs; // Use docid as key - netnoise 2006/08/14
            }
            return $result;
        }
    }

    // Modified by Raymond for TV - Orig Modified by Apodigm - DocVars
    # returns a single TV record. $idnames - can be an id or name that belongs the template that the current document is using
    function getTemplateVar($idname= "", $fields= "*", $docid= "", $published= 1) {
        if ($idname == "") {
            return false;
        } else {
            $result= $this->getTemplateVars(array ($idname), $fields, $docid, $published, "", ""); //remove sorting for speed
            return ($result != false) ? $result[0] : false;
        }
    }

    # returns an array of TV records. $idnames - can be an id or name that belongs the template that the current document is using
    function getTemplateVars($idnames= array (), $fields= "*", $docid= "", $published= 1, $sort= "rank", $dir= "ASC") {
        if (($idnames != '*' && !is_array($idnames)) || count($idnames) == 0) {
            return false;
        } else {
            $result= array ();

            // get document record
            if ($docid == "") {
                $docid= $this->documentIdentifier;
                $docRow= $this->documentObject;
            } else {
                $docRow= $this->getDocument($docid, '*', $published);
                if (!$docRow)
                    return false;
            }

            // get user defined template variables
            $fields= ($fields == "") ? "tv.*" : 'tv.' . implode(',tv.', preg_replace("/^\s/i", "", explode(',', $fields)));
            $sort= ($sort == "") ? "" : 'tv.' . implode(',tv.', preg_replace("/^\s/i", "", explode(',', $sort)));
            if ($idnames == "*")
                $query= "tv.id<>0";
            else
                $query= (is_numeric($idnames[0]) ? "tv.id" : "tv.name") . " IN ('" . implode("','", $idnames) . "')";
            if ($docgrp= $this->getUserDocGroups())
                $docgrp= implode(",", $docgrp);
            $sql= "SELECT $fields, IF(tvc.value!='',tvc.value,tv.default_text) as value ";
            $sql .= "FROM " . $this->getFullTableName('site_tmplvars')." tv ";
            $sql .= "INNER JOIN " . $this->getFullTableName('site_tmplvar_templates')." tvtpl ON tvtpl.tmplvarid = tv.id ";
            $sql .= "LEFT JOIN " . $this->getFullTableName('site_tmplvar_contentvalues')." tvc ON tvc.tmplvarid=tv.id AND tvc.contentid = '" . $docid . "' ";
            $sql .= "WHERE " . $query . " AND tvtpl.templateid = " . $docRow['template'];
            if ($sort)
                $sql .= " ORDER BY $sort $dir ";
            $rs= $this->dbQuery($sql);
            for ($i= 0; $i < @ $this->recordCount($rs); $i++) {
                array_push($result, @ $this->fetchRow($rs));
            }

            // get default/built-in template variables
            ksort($docRow);
            foreach ($docRow as $key => $value) {
                if ($idnames == "*" || in_array($key, $idnames))
                    array_push($result, array (
                        "name" => $key,
                        "value" => $value
                    ));
            }

            return $result;
        }
    }

    # returns an associative array containing TV rendered output values. $idnames - can be an id or name that belongs the template that the current document is using
    function getTemplateVarOutput($idnames= array (), $docid= "", $published= 1) {
        if (count($idnames) == 0) {
            return false;
        } else {
            $output= array ();
            $vars= ($idnames == '*' || is_array($idnames)) ? $idnames : array ($idnames);
            $docid= intval($docid) ? intval($docid) : $this->documentIdentifier;
            $result= $this->getTemplateVars($vars, "*", $docid, $published, "", ""); // remove sort for speed
            if ($result == false)
                return false;
            else {
                $baspath= $this->config["base_path"] . "manager/includes";
                include_once $baspath . "/tmplvars.format.inc.php";
                include_once $baspath . "/tmplvars.commands.inc.php";
                for ($i= 0; $i < count($result); $i++) {
                    $row= $result[$i];
                    // to-do needs fixing when getting tvs from other pages
                    $replace_richtext= "";
                    $richtexteditor= "";
                    $w= "100%";
                    $h= "300";
                    $output[$row['name']]= getTVDisplayFormat($row['name'], $row['value'], $row['display'], $row['display_params'], $row['type'], $docid);
                }
                return $output;
            }
        }
    }

    # returns the full table name based on db settings
    function getFullTableName($tbl) {
        return $this->db->config['dbase'] . ".`" . $this->db->config['table_prefix'] . $tbl . "`";
    }

    # returns the physical path from the supplied virtual
    function mapPath($path, $filename= "") {
        if (!$path)
            $path= ".";
        if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
            // only on windows?
            $absPath= str_replace("\\", "/", realpath($path));
            return $absPath . ($absPath && $filename ? "/$filename" : "$filename");
        } else {
            // determine base path
            if (substr($path, 0, 1) == "/")
                $basePath= $_SERVER['DOCUMENT_ROOT'];
            else
                $basePath= dirname($_SERVER['SCRIPT_FILENAME']);
            // return absolute path
            $absPath= realpath("$basePath/$path");
            return $absPath . ($absPath && $filename ? "/$filename" : "$filename");
        }
    }

    # return placeholder value
    function getPlaceholder($name) {
        return $this->placeholders[$name];
    }

    # sets a value for a placeholder
    function setPlaceholder($name, $value) {
        $this->placeholders[$name]= $value;
    }

    # set arrays or object vars as placeholders
    function toPlaceholders($subject, $prefix= '') {
        if (is_object($subject)) {
            $subject= get_object_vars($subject);
        }
        if (is_array($subject)) {
            foreach ($subject as $key => $value) {
                $this->toPlaceholder($key, $value, $prefix);
            }
        }
    }

    function toPlaceholder($key, $value, $prefix= '') {
        if (is_array($value) || is_object($value)) {
            $this->toPlaceholders($value, "{$prefix}{$key}.");
        } else {
            $this->setPlaceholder("{$prefix}{$key}", $value);
        }
    }

    # returns the virtual relative path to the manager folder
    function getManagerPath() {
        global $base_url;
        $pth= $base_url . 'manager/';
        return $pth;
    }

    # returns the virtual relative path to the cache folder
    function getCachePath() {
        global $base_url;
        $pth= $base_url . 'assets/cache/';
        return $pth;
    }

    # sends a message to a user's message box
    function sendAlert($type, $to, $from, $subject, $msg, $private= 0) {
        $private= ($private) ? 1 : 0;
        if (!is_numeric($to)) {
            // Query for the To ID
            $sql= "SELECT id FROM " . $this->getFullTableName("manager_users") . " WHERE username='$to';";
            $rs= $this->dbQuery($sql);
            if ($this->recordCount($rs)) {
                $rs= $this->fetchRow($rs);
                $to= $rs['id'];
            }
        }
        if (!is_numeric($from)) {
            // Query for the From ID
            $sql= "SELECT id FROM " . $this->getFullTableName("manager_users") . " WHERE username='$from';";
            $rs= $this->dbQuery($sql);
            if ($this->recordCount($rs)) {
                $rs= $this->fetchRow($rs);
                $from= $rs['id'];
            }
        }
        // insert a new message into user_messages
        $sql= "INSERT INTO " . $this->getFullTableName("user_messages") . " ( id , type , subject , message , sender , recipient , private , postdate , messageread ) VALUES ( '', '$type', '$subject', '$msg', '$from', '$to', '$private', '" . time() . "', '0' );";
        $rs= $this->dbQuery($sql);
    }

    # Returns true, install or interact when inside manager
    // deprecated
    function insideManager() {
        $m= false;
        if (IN_MANAGER_MODE == 'true') {
            $m= true;
            if (SNIPPET_INTERACTIVE_MODE == 'true')
                $m= "interact";
            else
                if (SNIPPET_INSTALL_MODE == 'true')
                    $m= "install";
        }
        return $m;
    }

    # Returns current user id
    function getLoginUserID($context= '') {
        if ($context && isset ($_SESSION[$context . 'Validated'])) {
            return $_SESSION[$context . 'InternalKey'];
        }
        elseif ($this->isFrontend() && isset ($_SESSION['webValidated'])) {
            return $_SESSION['webInternalKey'];
        }
        elseif ($this->isBackend() && isset ($_SESSION['mgrValidated'])) {
            return $_SESSION['mgrInternalKey'];
        }
    }

    # Returns current user name
    function getLoginUserName() {
        if ($this->isFrontend() && isset ($_SESSION['webValidated'])) {
            return $_SESSION['webShortname'];
        }
        elseif ($this->isBackend() && isset ($_SESSION['mgrValidated'])) {
            return $_SESSION['mgrShortname'];
        }
    }

    # Returns current login user type - web or manager
    function getLoginUserType() {
        if ($this->isFrontend() && isset ($_SESSION['webValidated'])) {
            return 'web';
        }
        elseif ($this->isBackend() && isset ($_SESSION['mgrValidated'])) {
            return 'manager';
        } else {
            return '';
        }
    }

    # Returns a record for the manager user
    function getUserInfo($uid) {
        $sql= "
              SELECT mu.username, mu.password, mua.*
              FROM " . $this->getFullTableName("manager_users") . " mu
              INNER JOIN " . $this->getFullTableName("user_attributes") . " mua ON mua.internalkey=mu.id
              WHERE mu.id = '$uid'
              ";
        $rs= $this->dbQuery($sql);
        $limit= mysql_num_rows($rs);
        if ($limit == 1) {
            $row= $this->fetchRow($rs);
            if (!$row["usertype"])
                $row["usertype"]= "manager";
            return $row;
        }
    }

    # Returns a record for the web user
    function getWebUserInfo($uid) {
        $sql= "
              SELECT wu.username, wu.password, wua.*
              FROM " . $this->getFullTableName("web_users") . " wu
              INNER JOIN " . $this->getFullTableName("web_user_attributes") . " wua ON wua.internalkey=wu.id
              WHERE wu.id='$uid'
              ";
        $rs= $this->dbQuery($sql);
        $limit= mysql_num_rows($rs);
        if ($limit == 1) {
            $row= $this->fetchRow($rs);
            if (!$row["usertype"])
                $row["usertype"]= "web";
            return $row;
        }
    }

    # Returns an array of document groups that current user is assigned to.
    # This function will first return the web user doc groups when running from frontend otherwise it will return manager user's docgroup
    # Set $resolveIds to true to return the document group names
    function getUserDocGroups($resolveIds= false) {
        if ($this->isFrontend() && isset ($_SESSION['webDocgroups']) && isset ($_SESSION['webValidated'])) {
            $dg= $_SESSION['webDocgroups'];
            $dgn= isset ($_SESSION['webDocgrpNames']) ? $_SESSION['webDocgrpNames'] : false;
        } else
            if ($this->isBackend() && isset ($_SESSION['mgrDocgroups']) && isset ($_SESSION['mgrValidated'])) {
                $dg= $_SESSION['mgrDocgroups'];
                $dgn= $_SESSION['mgrDocgrpNames'];
            } else {
                $dg= '';
            }
        if (!$resolveIds)
            return $dg;
        else
            if (is_array($dgn))
                return $dgn;
            else
                if (is_array($dg)) {
                    // resolve ids to names
                    $dgn= array ();
                    $tbl= $this->getFullTableName("documentgroup_names");
                    $ds= $this->dbQuery("SELECT name FROM $tbl WHERE id IN (" . implode(",", $dg) . ")");
                    while ($row= $this->fetchRow($ds))
                        $dgn[count($dgn)]= $row['name'];
                    // cache docgroup names to session
                    if ($this->isFrontend())
                        $_SESSION['webDocgrpNames']= $dgn;
                    else
                        $_SESSION['mgrDocgrpNames']= $dgn;
                    return $dgn;
                }
    }
    function getDocGroups() {
        return $this->getUserDocGroups();
    } // deprecated

    # Change current web user's password - returns true if successful, oterhwise return error message
    function changeWebUserPassword($oldPwd, $newPwd) {
        $rt= false;
        if ($_SESSION["webValidated"] == 1) {
            $tbl= $this->getFullTableName("web_users");
            $ds= $this->dbQuery("SELECT * FROM $tbl WHERE id='" . $this->getLoginUserID() . "'");
            $limit= mysql_num_rows($ds);
            if ($limit == 1) {
                $row= $this->fetchRow($ds);
                if ($row["password"] == md5($oldPwd)) {
                    if (strlen($newPwd) < 6) {
                        return "Password is too short!";
                    }
                    elseif ($newPwd == "") {
                        return "You didn't specify a password for this user!";
                    } else {
                        $this->dbQuery("UPDATE $tbl SET password = md5('" . $newPwd . "') WHERE id='" . $this->getLoginUserID() . "'");
                        // invoke OnWebChangePassword event
                        $this->invokeEvent("OnWebChangePassword", array (
                            "userid" => $row["id"],
                            "username" => $row["username"],
                            "userpassword" => $newPwd
                        ));
                        return true;
                    }
                } else {
                    return "Incorrect password.";
                }
            }
        }
    }
    function changePassword($o, $n) {
        return changeWebUserPassword($o, $n);
    } // deprecated

    # returns true if the current web user is a member the specified groups
    function isMemberOfWebGroup($groupNames= array ()) {
        if (!is_array($groupNames))
            return false;
        // check cache
        $grpNames= isset ($_SESSION['webUserGroupNames']) ? $_SESSION['webUserGroupNames'] : false;
        if (!is_array($grpNames)) {
            $tbl= $this->getFullTableName("webgroup_names");
            $tbl2= $this->getFullTableName("web_groups");
            $sql= "SELECT wgn.name
                    FROM $tbl wgn
                    INNER JOIN $tbl2 wg ON wg.webgroup=wgn.id AND wg.webuser='" . $this->getLoginUserID() . "'";
            $grpNames= $this->db->getColumn("name", $sql);
            // save to cache
            $_SESSION['webUserGroupNames']= $grpNames;
        }
        foreach ($groupNames as $k => $v)
            if (in_array(trim($v), $grpNames))
                return true;
        return false;
    }

    # Registers Client-side CSS scripts - these scripts are loaded at inside the <head> tag
    function regClientCSS($src, $media='') {
        if (isset ($this->loadedjscripts[$src]) && $this->loadedjscripts[$src])
            return '';
        $this->loadedjscripts[$src]= true;
        if (strpos(strtolower($src), "<style") !== false || strpos(strtolower($src), "<link") !== false) {
            $this->sjscripts[count($this->sjscripts)]= $src;
        } else {
            $this->sjscripts[count($this->sjscripts)]= "\t" . '<link rel="stylesheet" type="text/css" href="'.$src.'" '.($media ? 'media="'.$media.'" ' : '').'/>';
        }
    }

    # Registers Startup Client-side JavaScript - these scripts are loaded at inside the <head> tag
    function regClientStartupScript($src, $plaintext= false) {
        if (!empty ($src) && !array_key_exists($src, $this->loadedjscripts)) {
            if ($this->loadedjscripts[$src])
                return '';

            $this->loadedjscripts[$src]= true;
            if ($plaintext == true)
                $this->sjscripts[count($this->sjscripts)]= $src;
            elseif (strpos(strtolower($src), "<script") !== false) $this->sjscripts[count($this->sjscripts)]= $src;
            else
                $this->sjscripts[count($this->sjscripts)]= "\t" . '<script type="text/javascript" src="' . $src . '"></script>';
        }
    }

    # Registers Client-side JavaScript 	- these scripts are loaded at the end of the page
    function regClientScript($src, $plaintext= false) {
        if ($this->loadedjscripts[$src])
            return '';
        $this->loadedjscripts[$src]= true;
        if ($plaintext == true)
            $this->jscripts[count($this->jscripts)]= $src;
        elseif (strpos(strtolower($src), "<script") !== false) $this->jscripts[count($this->jscripts)]= $src;
        else
            $this->jscripts[count($this->jscripts)]= "\t" . '<script type="text/javascript" src="' . $src . '"></script>';
    }

    # Registers Client-side Startup HTML block
    function regClientStartupHTMLBlock($html) {
        $this->regClientStartupScript($html, true);
    }

    # Registers Client-side HTML block
    function regClientHTMLBlock($html) {
        $this->regClientScript($html, true);
    }

    # Remove unwanted html tags and snippet, settings and tags
    function stripTags($html, $allowed= "") {
        $t= strip_tags($html, $allowed);
        $t= preg_replace('~\[\*(.*?)\*\]~', "", $t); //tv
        $t= preg_replace('~\[\[(.*?)\]\]~', "", $t); //snippet
        $t= preg_replace('~\[\!(.*?)\!\]~', "", $t); //snippet
        $t= preg_replace('~\[\((.*?)\)\]~', "", $t); //settings
        $t= preg_replace('~\[\+(.*?)\+\]~', "", $t); //placeholders
        $t= preg_replace('~{{(.*?)}}~', "", $t); //chunks
        return $t;
    }

    # add an event listner to a plugin - only for use within the current execution cycle
    function addEventListener($evtName, $pluginName) {
        if (!$evtName || !$pluginName)
            return false;
        $el= $this->pluginEvent[$evtName];
        if (empty ($el))
            $el= $this->pluginEvent[$evtName]= array ();
        return array_push($el, $pluginName); // return index
    }

    # remove event listner - only for use within the current execution cycle
    function removeEventListener($evtName) {
        if (!$evtName)
            return false;
        unset ($this->pluginEvent[$evtName]);
    }

    # remove all event listners - only for use within the current execution cycle
    function removeAllEventListener() {
        unset ($this->pluginEvent);
        $this->pluginEvent= array ();
    }

    # invoke an event. $extParams - hash array: name=>value
    function invokeEvent($evtName, $extParams= array ()) {
        if (!$evtName)
            return false;
        if (!isset ($this->pluginEvent[$evtName]))
            return false;
        $el= $this->pluginEvent[$evtName];
        $results= array ();
        $numEvents= count($el);
        if ($numEvents > 0)
            for ($i= 0; $i < $numEvents; $i++) { // start for loop
                $pluginName= $el[$i];
                // reset event object
                $e= & $this->Event;
                $e->_resetEventObject();
                $e->name= $evtName;
                $e->activePlugin= $pluginName;

                // get plugin code
                if (isset ($this->pluginCache[$pluginName])) {
                    $pluginCode= $this->pluginCache[$pluginName];
                    $pluginProperties= $this->pluginCache[$pluginName . "Props"];
                } else {
                    $sql= "SELECT * FROM " . $this->getFullTableName("site_plugins") . " WHERE name='" . $pluginName . "' AND disabled=0;";
                    $result= $this->dbQuery($sql);
                    if ($this->recordCount($result) == 1) {
                        $row= $this->fetchRow($result);
                        $pluginCode= $this->pluginCache[$row['name']]= $row['plugincode'];
                        $pluginProperties= $this->pluginCache[$row['name'] . "Props"]= $row['properties'];
                    } else {
                        $pluginCode= $this->pluginCache[$pluginName]= "return false;";
                        $pluginProperties= '';
                    }
                }

                // load default params/properties
                $parameter= $this->parseProperties($pluginProperties);
                if (!empty ($extParams))
                    $parameter= array_merge($parameter, $extParams);

                // eval plugin
                $this->evalPlugin($pluginCode, $parameter);
                if ($e->_output != "")
                    $results[]= $e->_output;
                if ($e->_propagate != true)
                    break;
            }
        $e->activePlugin= "";
        return $results;
    }

    # parses a resource property string and returns the result as an array
    function parseProperties($propertyString) {
        $parameter= array ();
        if (!empty ($propertyString)) {
            $tmpParams= explode("&", $propertyString);
            for ($x= 0; $x < count($tmpParams); $x++) {
                if (strpos($tmpParams[$x], '=', 0)) {
                    $pTmp= explode("=", $tmpParams[$x]);
                    $pvTmp= explode(";", trim($pTmp[1]));
                    if ($pvTmp[1] == 'list' && $pvTmp[3] != "")
                        $parameter[trim($pTmp[0])]= $pvTmp[3]; //list default
                    else
                        if ($pvTmp[1] != 'list' && $pvTmp[2] != "")
                            $parameter[trim($pTmp[0])]= $pvTmp[2];
                }
            }
        }
        return $parameter;
    }

    /*############################################
      Etomite_dbFunctions.php
      New database functions for Etomite CMS
    Author: Ralph A. Dahlgren - rad14701@yahoo.com
    Etomite ID: rad14701
    See documentation for usage details
    ############################################*/
    function getIntTableRows($fields= "*", $from= "", $where= "", $sort= "", $dir= "ASC", $limit= "") {
        // function to get rows from ANY internal database table
        if ($from == "") {
            return false;
        } else {
            $where= ($where != "") ? "WHERE $where" : "";
            $sort= ($sort != "") ? "ORDER BY $sort $dir" : "";
            $limit= ($limit != "") ? "LIMIT $limit" : "";
            $tbl= $this->getFullTableName($from);
            $sql= "SELECT $fields FROM $tbl $where $sort $limit;";
            $result= $this->dbQuery($sql);
            $resourceArray= array ();
            for ($i= 0; $i < @ $this->recordCount($result); $i++) {
                array_push($resourceArray, @ $this->fetchRow($result));
            }
            return $resourceArray;
        }
    }

    function putIntTableRow($fields= "", $into= "") {
        // function to put a row into ANY internal database table
        if (($fields == "") || ($into == "")) {
            return false;
        } else {
            $tbl= $this->getFullTableName($into);
            $sql= "INSERT INTO $tbl SET ";
            foreach ($fields as $key => $value) {
                $sql .= $key . "=";
                if (is_numeric($value))
                    $sql .= $value . ",";
                else
                    $sql .= "'" . $value . "',";
            }
            $sql= rtrim($sql, ",");
            $sql .= ";";
            $result= $this->dbQuery($sql);
            return $result;
        }
    }

    function updIntTableRow($fields= "", $into= "", $where= "", $sort= "", $dir= "ASC", $limit= "") {
        // function to update a row into ANY internal database table
        if (($fields == "") || ($into == "")) {
            return false;
        } else {
            $where= ($where != "") ? "WHERE $where" : "";
            $sort= ($sort != "") ? "ORDER BY $sort $dir" : "";
            $limit= ($limit != "") ? "LIMIT $limit" : "";
            $tbl= $this->getFullTableName($into);
            $sql= "UPDATE $tbl SET ";
            foreach ($fields as $key => $value) {
                $sql .= $key . "=";
                if (is_numeric($value))
                    $sql .= $value . ",";
                else
                    $sql .= "'" . $value . "',";
            }
            $sql= rtrim($sql, ",");
            $sql .= " $where $sort $limit;";
            $result= $this->dbQuery($sql);
            return $result;
        }
    }

    function getExtTableRows($host= "", $user= "", $pass= "", $dbase= "", $fields= "*", $from= "", $where= "", $sort= "", $dir= "ASC", $limit= "") {
        // function to get table rows from an external MySQL database
        if (($host == "") || ($user == "") || ($pass == "") || ($dbase == "") || ($from == "")) {
            return false;
        } else {
            $where= ($where != "") ? "WHERE  $where" : "";
            $sort= ($sort != "") ? "ORDER BY $sort $dir" : "";
            $limit= ($limit != "") ? "LIMIT $limit" : "";
            $tbl= $dbase . "." . $from;
            $this->dbExtConnect($host, $user, $pass, $dbase);
            $sql= "SELECT $fields FROM $tbl $where $sort $limit;";
            $result= $this->dbQuery($sql);
            $resourceArray= array ();
            for ($i= 0; $i < @ $this->recordCount($result); $i++) {
                array_push($resourceArray, @ $this->fetchRow($result));
            }
            return $resourceArray;
        }
    }

    function putExtTableRow($host= "", $user= "", $pass= "", $dbase= "", $fields= "", $into= "") {
        // function to put a row into an external database table
        if (($host == "") || ($user == "") || ($pass == "") || ($dbase == "") || ($fields == "") || ($into == "")) {
            return false;
        } else {
            $this->dbExtConnect($host, $user, $pass, $dbase);
            $tbl= $dbase . "." . $into;
            $sql= "INSERT INTO $tbl SET ";
            foreach ($fields as $key => $value) {
                $sql .= $key . "=";
                if (is_numeric($value))
                    $sql .= $value . ",";
                else
                    $sql .= "'" . $value . "',";
            }
            $sql= rtrim($sql, ",");
            $sql .= ";";
            $result= $this->dbQuery($sql);
            return $result;
        }
    }

    function updExtTableRow($host= "", $user= "", $pass= "", $dbase= "", $fields= "", $into= "", $where= "", $sort= "", $dir= "ASC", $limit= "") {
        // function to update a row into an external database table
        if (($fields == "") || ($into == "")) {
            return false;
        } else {
            $this->dbExtConnect($host, $user, $pass, $dbase);
            $tbl= $dbase . "." . $into;
            $where= ($where != "") ? "WHERE $where" : "";
            $sort= ($sort != "") ? "ORDER BY $sort $dir" : "";
            $limit= ($limit != "") ? "LIMIT $limit" : "";
            $sql= "UPDATE $tbl SET ";
            foreach ($fields as $key => $value) {
                $sql .= $key . "=";
                if (is_numeric($value))
                    $sql .= $value . ",";
                else
                    $sql .= "'" . $value . "',";
            }
            $sql= rtrim($sql, ",");
            $sql .= " $where $sort $limit;";
            $result= $this->dbQuery($sql);
            return $result;
        }
    }

    function dbExtConnect($host, $user, $pass, $dbase) {
        // function to connect to external database
        $tstart= $this->getMicroTime();
        if (@ !$this->rs= mysql_connect($host, $user, $pass)) {
            $this->messageQuit("Failed to create connection to the $dbase database!");
        } else {
            mysql_select_db($dbase);
            $tend= $this->getMicroTime();
            $totaltime= $tend - $tstart;
            if ($this->dumpSQL) {
                $this->queryCode .= "<fieldset style='text-align:left'><legend>Database connection</legend>" . sprintf("Database connection to %s was created in %2.4f s", $dbase, $totaltime) . "</fieldset><br />";
            }
            $this->queryTime= $this->queryTime + $totaltime;
        }
    }

    function getFormVars($method= "", $prefix= "", $trim= "", $REQUEST_METHOD) {
        //  function to retrieve form results into an associative array
        $results= array ();
        $method= strtoupper($method);
        if ($method == "")
            $method= $REQUEST_METHOD;
        if ($method == "POST")
            $method= & $_POST;
        elseif ($method == "GET") $method= & $_GET;
        else
            return false;
        reset($method);
        foreach ($method as $key => $value) {
            if (($prefix != "") && (substr($key, 0, strlen($prefix)) == $prefix)) {
                if ($trim) {
                    $pieces= explode($prefix, $key, 2);
                    $key= $pieces[1];
                    $results[$key]= $value;
                } else
                    $results[$key]= $value;
            }
            elseif ($prefix == "") $results[$key]= $value;
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
        if ($nr == 0 || ($nr == 8 && $this->stopOnNotice == false)) {
            return true;
        }
        if (is_readable($file)) {
            $source= file($file);
            $source= htmlspecialchars($source[$line -1]);
        } else {
            $source= "";
        } //Error $nr in $file at $line: <div><code>$source</code></div>
        $this->messageQuit("PHP Parse Error", '', true, $nr, $file, $source, $text, $line);
    }

    function messageQuit($msg= 'unspecified error', $query= '', $is_error= true, $nr= '', $file= '', $source= '', $text= '', $line= '') {

        $version= isset ($GLOBALS['version']) ? $GLOBALS['version'] : '';
        $code_name= isset ($GLOBALS['code_name']) ? $GLOBALS['code_name'] : '';
        $parsedMessageString= "
              <html><head><title>MODx Content Manager $version &raquo; $code_name</title>
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
        if ($is_error) {
            $parsedMessageString .= "<h3 style='color:red'>&laquo; MODx Parse Error &raquo;</h3>
                    <table border='0' cellpadding='1' cellspacing='0'>
                    <tr><td colspan='3'>MODx encountered the following error while attempting to parse the requested resource:</td></tr>
                    <tr><td colspan='3'><b style='color:red;'>&laquo; $msg &raquo;</b></td></tr>";
        } else {
            $parsedMessageString .= "<h3 style='color:#003399'>&laquo; MODx Debug/ stop message &raquo;</h3>
                    <table border='0' cellpadding='1' cellspacing='0'>
                    <tr><td colspan='3'>The MODx parser recieved the following debug/ stop message:</td></tr>
                    <tr><td colspan='3'><b style='color:#003399;'>&laquo; $msg &raquo;</b></td></tr>";
        }

        if (!empty ($query)) {
            $parsedMessageString .= "<tr><td colspan='3'><b style='color:#999;font-size: 9px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SQL:&nbsp;<span id='sqlHolder'>$query</span></b>
                    <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:copyToClip();' style='color:#821517;font-size: 9px; text-decoration: none'>[Copy SQL to ClipBoard]</a><textarea id='holdtext' style='display:none;'></textarea></td></tr>";
        }

        if ($text != '') {

            $errortype= array (
                E_ERROR => "Error",
                E_WARNING => "Warning",
                E_PARSE => "Parsing Error",
                E_NOTICE => "Notice",
                E_CORE_ERROR => "Core Error",
                E_CORE_WARNING => "Core Warning",
                E_COMPILE_ERROR => "Compile Error",
                E_COMPILE_WARNING => "Compile Warning",
                E_USER_ERROR => "User Error",
                E_USER_WARNING => "User Warning",
                E_USER_NOTICE => "User Notice",

            );

            $parsedMessageString .= "<tr><td>&nbsp;</td></tr><tr><td colspan='3'><b>PHP error debug</b></td></tr>";

            $parsedMessageString .= "<tr><td valign='top'>&nbsp;&nbsp;Error: </td>";
            $parsedMessageString .= "<td colspan='2'>$text</td><td>&nbsp;</td>";
            $parsedMessageString .= "</tr>";

            $parsedMessageString .= "<tr><td valign='top'>&nbsp;&nbsp;Error type/ Nr.: </td>";
            $parsedMessageString .= "<td colspan='2'>" . $errortype[$nr] . " - $nr</b></td><td>&nbsp;</td>";
            $parsedMessageString .= "</tr>";

            $parsedMessageString .= "<tr><td>&nbsp;&nbsp;File: </td>";
            $parsedMessageString .= "<td colspan='2'>$file</td><td>&nbsp;</td>";
            $parsedMessageString .= "</tr>";

            $parsedMessageString .= "<tr><td>&nbsp;&nbsp;Line: </td>";
            $parsedMessageString .= "<td colspan='2'>$line</td><td>&nbsp;</td>";
            $parsedMessageString .= "</tr>";
            if ($source != '') {
                $parsedMessageString .= "<tr><td valign='top'>&nbsp;&nbsp;Line $line source: </td>";
                $parsedMessageString .= "<td colspan='2'>$source</td><td>&nbsp;</td>";
                $parsedMessageString .= "</tr>";
            }
        }

        $parsedMessageString .= "<tr><td>&nbsp;</td></tr><tr><td colspan='3'><b>Parser timing</b></td></tr>";

        $parsedMessageString .= "<tr><td>&nbsp;&nbsp;MySQL: </td>";
        $parsedMessageString .= "<td><i>[^qt^]</i></td><td>(<i>[^q^] Requests</i>)</td>";
        $parsedMessageString .= "</tr>";

        $parsedMessageString .= "<tr><td>&nbsp;&nbsp;PHP: </td>";
        $parsedMessageString .= "<td><i>[^p^]</i></td><td>&nbsp;</td>";
        $parsedMessageString .= "</tr>";

        $parsedMessageString .= "<tr><td>&nbsp;&nbsp;Total: </td>";
        $parsedMessageString .= "<td><i>[^t^]</i></td><td>&nbsp;</td>";
        $parsedMessageString .= "</tr>";

        $parsedMessageString .= "</table>";
        $parsedMessageString .= "</body></html>";

        $totalTime= ($this->getMicroTime() - $this->tstart);
        $queryTime= $this->queryTime;
        $phpTime= $totalTime - $queryTime;
        $queries= isset ($this->executedQueries) ? $this->executedQueries : 0;
        $queryTime= sprintf("%2.4f s", $queryTime);
        $totalTime= sprintf("%2.4f s", $totalTime);
        $phpTime= sprintf("%2.4f s", $phpTime);

        $parsedMessageString= str_replace("[^q^]", $queries, $parsedMessageString);
        $parsedMessageString= str_replace("[^qt^]", $queryTime, $parsedMessageString);
        $parsedMessageString= str_replace("[^p^]", $phpTime, $parsedMessageString);
        $parsedMessageString= str_replace("[^t^]", $totalTime, $parsedMessageString);

        // Display error
        echo $parsedMessageString;
        ob_end_flush();

        // Log error
        $this->logEvent(0, 3, $parsedMessageString, $source= 'Parser');

        // Make sure and die!
        exit();
    }

    function getRegisteredClientScripts() {
        return implode("\n", $this->jscripts);
    }

    function getRegisteredClientStartupScripts() {
        return implode("\n", $this->sjscripts);
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

    function SystemEvent($name= "") {
        $this->_resetEventObject();
        $this->name= $name;
    }

    // used for displaying a message to the user
    function alert($msg) {
        global $SystemAlertMsgQueque;
        if ($msg == "")
            return;
        if (is_array($SystemAlertMsgQueque)) {
            if ($this->name && $this->activePlugin)
                $title= "<div><b>" . $this->activePlugin . "</b> - <span style='color:maroon;'>" . $this->name . "</span></div>";
            $SystemAlertMsgQueque[]= "$title<div style='margin-left:10px;margin-top:3px;'>$msg</div>";
        }
    }

    // used for rendering an out on the screen
    function output($msg) {
        $this->_output .= $msg;
    }

    function stopPropagation() {
        $_propagate= false;
    }

    function _resetEventObject() {
        unset ($this->returnedValues);
        $this->name= "";
        $this->_output= "";
        $this->_propagate= true;
        $this->activated= false;
    }
}
?>