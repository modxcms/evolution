<?php
$this->old = new OldFunctions();
class OldFunctions {
    
    function dbConnect()                 {global $modx;       $modx->db->connect();$modx->rs = $modx->db->conn;}
    function dbQuery($sql)               {global $modx;return $modx->db->query($sql);}
    function recordCount($rs)            {global $modx;return $modx->db->getRecordCount($rs);}
    function fetchRow($rs,$mode='assoc') {global $modx;return $modx->db->getRow($rs, $mode);}
    function affectedRows($rs)           {global $modx;return $modx->db->getAffectedRows($rs);}
    function insertId($rs)               {global $modx;return $modx->db->getInsertId($rs);}
    function dbClose()                   {global $modx;       $modx->db->disconnect();}
    
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

    
    function getUserData() {
    	global $modx;
        include $modx->config["base_path"] . "manager/includes/extenders/getUserData.extender.php";
        return $tmpArray;
    }
    
    # Returns true, install or interact when inside manager
    // deprecated
    function insideManager() {
        $m= false;
        if (defined('IN_MANAGER_MODE') && IN_MANAGER_MODE == 'true') {
            $m= true;
            if (defined('SNIPPET_INTERACTIVE_MODE') && SNIPPET_INTERACTIVE_MODE == 'true')
                $m= "interact";
            else
                if (defined('SNIPPET_INSTALL_MODE') && SNIPPET_INSTALL_MODE == 'true')
                    $m= "install";
        }
        return $m;
    }

    // deprecated
    function putChunk($chunkName) { // alias name >.<
    	global $modx;
        return $modx->getChunk($chunkName);
    }

    function getDocGroups() {
    	global $modx;
        return $modx->getUserDocGroups();
    } // deprecated

    function changePassword($o, $n) {
        return changeWebUserPassword($o, $n);
    } // deprecated
    
    function mergeDocumentMETATags($template) {
    	global $modx;
        if ($modx->documentObject['haskeywords'] == 1) {
            // insert keywords
            $keywords = $modx->getKeywords();
            if (is_array($keywords) && count($keywords) > 0) {
	            $keywords = implode(", ", $keywords);
	            $metas= "\t<meta name=\"keywords\" content=\"$keywords\" />\n";
            }

	    // Don't process when cached
	    $modx->documentObject['haskeywords'] = '0';
        }
        if ($modx->documentObject['hasmetatags'] == 1) {
            // insert meta tags
            $tags= $modx->getMETATags();
            foreach ($tags as $n => $col) {
                $tag= strtolower($col['tag']);
                $tagvalue= $col['tagvalue'];
                $tagstyle= $col['http_equiv'] ? 'http-equiv' : 'name';
                $metas .= "\t<meta $tagstyle=\"$tag\" content=\"$tagvalue\" />\n";
            }

	    // Don't process when cached
	    $modx->documentObject['hasmetatags'] = '0';
        }
	if ($metas) $template = preg_replace("/(<head>)/i", "\\1\n\t" . trim($metas), $template);
        return $template;
    }
    
    function getMETATags($id= 0) {
    	global $modx;
        if ($id == 0) {
            $id= $modx->documentObject['id'];
        }
        $sql= "SELECT smt.* " .
        "FROM " . $modx->getFullTableName("site_metatags") . " smt " .
        "INNER JOIN " . $modx->getFullTableName("site_content_metatags") . " cmt ON cmt.metatag_id=smt.id " .
        "WHERE cmt.content_id = '$id'";
        $ds= $modx->db->query($sql);
        $limit= $modx->db->getRecordCount($ds);
        $metatags= array ();
        if ($limit > 0) {
            for ($i= 0; $i < $limit; $i++) {
                $row= $modx->db->getRow($ds);
                $metatags[$row['name']]= array (
                    "tag" => $row['tag'],
                    "tagvalue" => $row['tagvalue'],
                    "http_equiv" => $row['http_equiv']
                );
            }
        }
        return $metatags;
    }
    
    function userLoggedIn() {
    	global $modx;
        $userdetails= array ();
        if ($modx->isFrontend() && isset ($_SESSION['webValidated'])) {
            // web user
            $userdetails['loggedIn']= true;
            $userdetails['id']= $_SESSION['webInternalKey'];
            $userdetails['username']= $_SESSION['webShortname'];
            $userdetails['usertype']= 'web'; // added by Raymond
            return $userdetails;
        } else
            if ($modx->isBackend() && isset ($_SESSION['mgrValidated'])) {
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
    	global $modx;
        if ($id == 0) {
            $id= $modx->documentObject['id'];
        }
        $tblKeywords= $modx->getFullTableName('site_keywords');
        $tblKeywordXref= $modx->getFullTableName('keyword_xref');
        $sql= "SELECT keywords.keyword FROM " . $tblKeywords . " AS keywords INNER JOIN " . $tblKeywordXref . " AS xref ON keywords.id=xref.keyword_id WHERE xref.content_id = '$id'";
        $result= $modx->db->query($sql);
        $limit= $modx->db->getRecordCount($result);
        $keywords= array ();
        if ($limit > 0) {
            for ($i= 0; $i < $limit; $i++) {
                $row= $modx->db->getRow($result);
                $keywords[]= $row['keyword'];
            }
        }
        return $keywords;
    }
    
    function makeFriendlyURL($pre, $suff, $alias, $isfolder=0) {
    	global $modx;
        $Alias = explode('/',$alias);
        $alias = array_pop($Alias);
        $dir = implode('/', $Alias);
        unset($Alias);
        if($modx->config['make_folders']==='1' && $isfolder==1) $suff = '/';
        return ($dir != '' ? "$dir/" : '') . $pre . $alias . $suff;
    }

}