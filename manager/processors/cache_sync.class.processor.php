<?php
// cache & synchronise class

class synccache{
    var $cachePath;
    var $showReport;
    var $deletedfiles = array();
    var $aliases = array();
    var $parents = array();
    var $aliasVisible = array();
	

    function setCachepath($path) {
        $this->cachePath = $path;
    }

    function setReport($bool) {
        $this->showReport = $bool;
    }

    function escapeDoubleQuotes($s) {
        $q1 = array("\\","\"","\r","\n","\$");
        $q2 = array("\\\\","\\\"","\\r","\\n","\\$");
        return str_replace($q1,$q2,$s);
    }

    function escapeSingleQuotes($s) {
        $q1 = array("\\","'");
        $q2 = array("\\\\","\\'");
        return str_replace($q1,$q2,$s);
    }

    function getParents($id, $path = '') { // modx:returns child's parent
        global $modx;
        if(empty($this->aliases)) {
            $qh = $modx->db->select('id, IF(alias=\'\', id, alias) AS alias, parent, alias_visible', $modx->getFullTableName('site_content'));
                while ($row = $modx->db->getRow($qh)) {
                    $this->aliases[$row['id']] = $row['alias'];
                    $this->parents[$row['id']] = $row['parent'];
					$this->aliasVisible[$row['id']] = $row['alias_visible'];
                }
        }
        if (isset($this->aliases[$id])) {
            $path = ($this->aliasVisible[$id] == 1 ? $this->aliases[$id] . ($path != '' ? '/' : '') . $path : $path);
            return $this->getParents($this->parents[$id], $path);
        }
        return $path;
    }

    function emptyCache($modx = null) {
        if(is_a($modx, 'DocumentParser') === false || get_class($modx) !== 'DocumentParser') {
            $modx = $GLOBALS['modx'];
        }
        if(!isset($this->cachePath)) {
            $modx->messageQuit("Cache path not set.");
        }
        $filesincache = 0;
        $deletedfilesincache = 0;

            // New and improved!
            $files = glob(realpath($this->cachePath).'/*');
            $filesincache = count($files);
            $deletedfiles = array();
            while ($file = array_shift($files)) {
                $name = basename($file);
                if (preg_match('/\.pageCache/',$name) && !in_array($name, $deletedfiles)) {
                    $deletedfilesincache++;
                    $deletedfiles[] = $name;
                    @unlink($file);
                }
            }

        $this->buildCache($modx);

/****************************************************************************/
/*  PUBLISH TIME FILE                                                       */
/****************************************************************************/

        // update publish time file
        $timesArr = array();
        $result = $modx->db->select('MIN(pub_date) AS minpub', $modx->getFullTableName('site_content'), 'pub_date>'.(time() + $modx->config['server_offset_time']))
        if($minpub = $modx->db->getValue($result)) {
            $timesArr[] = $minpub;
        }

        $result = $modx->db->select('MIN(unpub_date) AS minunpub', $modx->getFullTableName('site_content'), 'unpub_date>'.(time() + $modx->config['server_offset_time']));
        if($minunpub = $modx->db->getValue($result)) {
            $timesArr[] = $minunpub;
        }

        if(count($timesArr)>0) {
            $nextevent = min($timesArr);
        } else {
            $nextevent = 0;
        }

        // write the file
        $filename = $this->cachePath.'/sitePublishing.idx.php';
        $somecontent = '<?php $cacheRefreshTime='.$nextevent.'; ?>';

        if (!$handle = fopen($filename, 'w')) {
             echo 'Cannot open file ('.$filename.')';
             exit;
        }

        // Write $somecontent to our opened file.
        if (fwrite($handle, $somecontent) === FALSE) {
           echo 'Cannot write publishing info file! Make sure the assets/cache directory is writable!';
           exit;
        }

        fclose($handle);


/****************************************************************************/
/*  END OF PUBLISH TIME FILE                                                */
/****************************************************************************/

        // finished cache stuff.
        if($this->showReport==true) {
        global $_lang;
            printf($_lang['refresh_cache'], $filesincache, $deletedfilesincache);
            $limit = count($deletedfiles);
            if($limit > 0) {
                echo '<p>'.$_lang['cache_files_deleted'].'</p><ul>';
                for($i=0;$i<$limit; $i++) {
                    echo '<li>',$deletedfiles[$i],'</li>';
                }
                echo '</ul>';
            }
        }
    }

    /**
     * build siteCache file
     * @param  DocumentParser $modx
     * @return boolean success
     */
    function buildCache($modx) {
        $tmpPHP = "<?php\n";

        // SETTINGS & DOCUMENT LISTINGS CACHE

        // get settings
        $rs = $modx->db->select('*', $modx->getFullTableName('system_settings'));
        $config = array();
        $tmpPHP .= '$c=&$this->config;'."\n";
        while(list($key,$value) = $modx->db->getRow($rs,'num')) {
            $tmpPHP .= '$c[\''.$key.'\']'.' = "'.$this->escapeDoubleQuotes($value)."\";\n";
            $config[$key] = $value;
        }

        // get aliases modx: support for alias path
        $tmpPath = '';
        $tmpPHP .= '$this->aliasListing = array();' . "\n";
        $tmpPHP .= '$a = &$this->aliasListing;' . "\n";
        $tmpPHP .= '$d = &$this->documentListing;' . "\n";
        $tmpPHP .= '$m = &$this->documentMap;' . "\n";
        $rs = $modx->db->select('IF(alias=\'\', id, alias) AS alias, id, parent, isfolder', $modx->getFullTableName('site_content'), 'deleted=0', 'parent, menuindex');
        while ($tmp1 = $modx->db->getRow($rs)) {
            if ($config['friendly_urls'] == 1 && $config['use_alias_path'] == 1) {
                $tmpPath = $this->getParents($tmp1['parent']);
                $alias= (strlen($tmpPath) > 0 ? "$tmpPath/" : '').$tmp1['alias'];
                $alias= $modx->db->escape($alias);
                $tmpPHP .= '$d[\''.$alias.'\']'." = ".$tmp1['id'].";\n";
            }
            else {
                $tmpPHP .= '$d[\''.$modx->db->escape($tmp1['alias']).'\']'." = ".$tmp1['id'].";\n";
            }
            $tmpPHP .= '$a[' . $tmp1['id'] . ']'." = array('id' => ".$tmp1['id'].", 'alias' => '".$modx->db->escape($tmp1['alias'])."', 'path' => '" . $modx->db->escape($tmpPath)."', 'parent' => " . $tmp1['parent']. ", 'isfolder' => " . $modx->db->escape($tmp1['isfolder']) . ");\n";
            $tmpPHP .= '$m[]'." = array('".$tmp1['parent']."' => '".$tmp1['id']."');\n";
        }


        // get content types
        $rs = $modx->db->select('id, contentType', $modx->getFullTableName('site_content'), "contentType != 'text/html'");
        $tmpPHP .= '$c = &$this->contentTypes;' . "\n";
        while ($tmp1 = $modx->db->getRow($rs)) {
           $tmpPHP .= '$c['.$tmp1['id'].']'." = '".$tmp1['contentType']."';\n";
        }

        // WRITE Chunks to cache file
        $rs = $modx->db->select('*', $modx->getFullTableName('site_htmlsnippets'));
        $tmpPHP .= '$c = &$this->chunkCache;' . "\n";
        while ($tmp1 = $modx->db->getRow($rs)) {
           $tmpPHP .= '$c[\''.$modx->db->escape($tmp1['name']).'\']'." = '".$this->escapeSingleQuotes($tmp1['snippet'])."';\n";
        }

        // WRITE snippets to cache file
        $rs = $modx->db->select(
			'ss.*, sm.properties as sharedproperties',
			$modx->getFullTableName('site_snippets').' ss
				LEFT JOIN '.$modx->getFullTableName('site_modules').' sm on sm.guid=ss.moduleguid'
			);
        $tmpPHP .= '$s = &$this->snippetCache;' . "\n";
        while ($tmp1 = $modx->db->getRow($rs)) {
           $tmpPHP .= '$s[\''.$modx->db->escape($tmp1['name']).'\']'." = '".$this->escapeSingleQuotes($tmp1['snippet'])."';\n";
           // Raymond: save snippet properties to cache
           if ($tmp1['properties']!=""||$tmp1['sharedproperties']!="") $tmpPHP .= '$s[\''.$tmp1['name'].'Props\']'." = '".$this->escapeSingleQuotes($tmp1['properties']." ".$tmp1['sharedproperties'])."';\n";
           // End mod
        }

        // WRITE plugins to cache file
        $rs = $modx->db->select(
			'sp.*, sm.properties as sharedproperties',
			$modx->getFullTableName('site_plugins').' sp
				LEFT JOIN '.$modx->getFullTableName('site_modules').' sm on sm.guid=sp.moduleguid',
			'sp.disabled=0');
        $tmpPHP .= '$p = &$this->pluginCache;' . "\n";
        while ($tmp1 = $modx->db->getRow($rs)) {
           $tmpPHP .= '$p[\''.$modx->db->escape($tmp1['name']).'\']'." = '".$this->escapeSingleQuotes($tmp1['plugincode'])."';\n";
           if ($tmp1['properties']!=''||$tmp1['sharedproperties']!='') $tmpPHP .= '$p[\''.$tmp1['name'].'Props\']'." = '".$this->escapeSingleQuotes($tmp1['properties'].' '.$tmp1['sharedproperties'])."';\n";
        }


        // WRITE system event triggers
        $events = array();
        $rs = $modx->db->select(
			'sysevt.name as evtname, pe.pluginid, plugs.name',
			$modx->getFullTableName('system_eventnames').' sysevt
				INNER JOIN '.$modx->getFullTableName('site_plugin_events').' pe ON pe.evtid = sysevt.id
				INNER JOIN '.$modx->getFullTableName('site_plugins').' plugs ON plugs.id = pe.pluginid',
			'plugs.disabled=0',
			'sysevt.name,pe.priority'
			);
        $tmpPHP .= '$e = &$this->pluginEvent;' . "\n";
        while ($evt = $modx->db->getRow($rs)) {
            if(!$events[$evt['evtname']]) $events[$evt['evtname']] = array();
            $events[$evt['evtname']][] = $evt['name'];
        }
        foreach($events as $evtname => $pluginnames) {
            $tmpPHP .= '$e[\''.$evtname.'\'] = array(\''.implode("','",$this->escapeSingleQuotes($pluginnames))."');\n";
        }

        // close and write the file
        $tmpPHP .= "\n";
        $filename = $this->cachePath.'siteCache.idx.php';
        $somecontent = $tmpPHP;

        // invoke OnBeforeCacheUpdate event
        if ($modx) $modx->invokeEvent('OnBeforeCacheUpdate');

        if (!$handle = fopen($filename, 'w')) {
             echo 'Cannot open file (',$filename,')';
             exit;
        }

		if(!is_file($this->cachePath . '/.htaccess')) {
			file_put_contents($this->cachePath . '/.htaccess', "order deny,allow\ndeny from all\n");
		}

        // Write $somecontent to our opened file.
        if (fwrite($handle, $somecontent) === FALSE) {
           echo 'Cannot write main MODX cache file! Make sure the assets/cache directory is writable!';
           exit;
        }
        fclose($handle);

        // invoke OnCacheUpdate event
        if ($modx) $modx->invokeEvent('OnCacheUpdate');

        return true;
    }
}
?>