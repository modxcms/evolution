<?php
// cache & synchronise class

class synccache{
	var $cachePath;
	var $showReport;
	var $deletedfiles = array();

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
		global $dbase, $table_prefix;
		$sql = "SELECT id, alias, parent FROM $dbase.`".$table_prefix."site_content` WHERE id = '$id'";
		$qh = mysql_query($sql);
		if ($qh && mysql_num_rows($qh) > 0)	{
			$row = mysql_fetch_assoc($qh);
			$path = ($row['alias'] ? $row['alias'] : $row['id']) . ($path != '' ? '/' : '') . $path;
			mysql_free_result( $qh );
			return $this->getParents($row['parent'], $path);
		}
		return $path;
	}
   
	function emptyCache() {
		if(!isset($this->cachePath)) {
			echo "Cache path not set.";
			exit;
		}
		$filesincache = 0;
		$deletedfilesincache = 0;
		if (function_exists('glob')) {
			// New and improved!
			$files = glob(realpath($this->cachePath).'/*');
			$filesincache = count($files);
			$deletedfiles = array();
			while ($file = array_shift($files)) {
				$name = basename($file);
				if (preg_match('/\.pageCache/',$name) && !in_array($name, $deletedfiles)) {
					$deletedfilesincache++;
					$deletedfiles[] = $name;
					unlink($file);
				}
			}
		} else {
			// Old way of doing it (no glob function available)
			if ($handle = opendir($this->cachePath)) {
				// Initialize deleted per round counter
				$deletedThisRound = 1;
				while ($deletedThisRound){
					if(!$handle) $handle = opendir($this->cachePath);
					$deletedThisRound = 0;
					while (false !== ($file = readdir($handle))) { 
						if ($file != "." && $file != "..") { 
							$filesincache += 1;
							if ( preg_match("/\.pageCache/", $file) && (!is_array($deletedfiles) || !array_search($file,$deletedfiles)) ) {
								$deletedfilesincache += 1;
								$deletedThisRound++;
								$deletedfiles[] = $file;
								unlink($this->cachePath.$file);
							} // End if
						} // End if
					} // End while
					closedir($handle); 
					$handle = '';
				} // End while ($deletedThisRound)
			}
		}

/****************************************************************************/
/*	BUILD CACHE FILES														*/
/****************************************************************************/

		
		global $modx;
		global $dbase, $table_prefix;

		$tmpPHP = "<?php\n";

		// SETTINGS & DOCUMENT LISTINGS CACHE

		// get settings
		$sql = "SELECT * FROM $dbase.`".$table_prefix."system_settings`"; 
		$rs = mysql_query($sql);
		$limit_tmp = mysql_num_rows($rs);
		$config = array();
		$tmpPHP .= '$c=&$this->config;'."\n";
		while(list($key,$value) = mysql_fetch_row($rs)) {
			$tmpPHP .= '$c[\''.$key.'\']'.' = "'.$this->escapeDoubleQuotes($value).'"'.";\n";
			$config[$key] = $value;
		}

		// get aliases modx: support for alias path
		$tmpPath = '';
		$tmpPHP .= '$this->aliasListing = array();' . "\n";
		$tmpPHP .= '$a = &$this->aliasListing;' . "\n";
		$tmpPHP .= '$d = &$this->documentListing;' . "\n";
		$tmpPHP .= '$m = &$this->documentMap;' . "\n";
		$sql = "SELECT IF(alias='', id, alias) AS alias, id, contentType, parent FROM $dbase.`".$table_prefix."site_content` ORDER BY parent, menuindex";
		$rs = mysql_query($sql);
		$limit_tmp = mysql_num_rows($rs);
		for ($i_tmp=0; $i_tmp<$limit_tmp; $i_tmp++) { 
			$tmp1 = mysql_fetch_assoc($rs);
			if ($config['friendly_urls'] == 1 && $config['use_alias_path'] == 1) {
				$tmpPath = $this->getParents($tmp1['parent']);
				$alias= (strlen($tmpPath) > 0 ? "$tmpPath/" : '').$tmp1['alias'];
				$alias= mysql_escape_string($alias);
				$tmpPHP .= '$d[\''.$alias.'\']'." = ".$tmp1['id'].";\n";
			}
			else {
				$tmpPHP .= '$d[\''.mysql_escape_string($tmp1['alias']).'\']'." = ".$tmp1['id'].";\n";
			}
			$tmpPHP .= '$a[' . $tmp1['id'] . ']'." = array('id' => ".$tmp1['id'].", 'alias' => '".mysql_escape_string($tmp1['alias'])."', 'path' => '" . mysql_escape_string($tmpPath). "');\n";
			$tmpPHP .= '$m[]'." = array('".$tmp1['parent']."' => '".$tmp1['id']."');\n";
		}
		
				
		// get content types
		$sql = "SELECT id, contentType FROM $dbase.`".$table_prefix."site_content`"; 
		$rs = mysql_query($sql);
		$limit_tmp = mysql_num_rows($rs);
		$tmpPHP .= '$c = &$this->contentTypes;' . "\n";
		for ($i_tmp=0; $i_tmp<$limit_tmp; $i_tmp++) { 
		   $tmp1 = mysql_fetch_assoc($rs); 
		   $tmpPHP .= '$c['.$tmp1['id'].']'." = '".$tmp1['contentType']."';\n";
		}

		// WRITE Chunks to cache file
		$sql = "SELECT * FROM $dbase.`".$table_prefix."site_htmlsnippets`"; 
		$rs = mysql_query($sql);
		$limit_tmp = mysql_num_rows($rs);
		$tmpPHP .= '$c = &$this->chunkCache;' . "\n";
		for ($i_tmp=0; $i_tmp<$limit_tmp; $i_tmp++) { 
		   $tmp1 = mysql_fetch_assoc($rs); 
		   $tmpPHP .= '$c[\''.mysql_escape_string($tmp1['name']).'\']'." = '".$this->escapeSingleQuotes($tmp1['snippet'])."';\n";
		}

		// WRITE snippets to cache file
		$sql = "SELECT ss.*,sm.properties as `sharedproperties` ".
				"FROM $dbase.`".$table_prefix."site_snippets` ss ". 
				"LEFT JOIN $dbase.`".$table_prefix."site_modules` sm on sm.guid=ss.moduleguid "; 
		$rs = mysql_query($sql);
		$limit_tmp = mysql_num_rows($rs);
		$tmpPHP .= '$s = &$this->snippetCache;' . "\n";
		for ($i_tmp=0; $i_tmp<$limit_tmp; $i_tmp++) { 
		   $tmp1 = mysql_fetch_assoc($rs); 
		   $tmpPHP .= '$s[\''.mysql_escape_string($tmp1['name']).'\']'." = '".$this->escapeSingleQuotes($tmp1['snippet'])."';\n";
		   // Raymond: save snippet properties to cache
		   if ($tmp1['properties']!=""||$tmp1['sharedproperties']!="") $tmpPHP .= '$s[\''.$tmp1['name'].'Props\']'." = '".$this->escapeSingleQuotes($tmp1['properties']." ".$tmp1['sharedproperties'])."';\n";
		   // End mod
		}
		
		// WRITE plugins to cache file
		$sql = "SELECT sp.*,sm.properties as 'sharedproperties' ".
				"FROM $dbase.`".$table_prefix."site_plugins` sp " .
				"LEFT JOIN $dbase.`".$table_prefix."site_modules` sm on sm.guid=sp.moduleguid " .
				"WHERE sp.disabled=0"; 
		$rs = mysql_query($sql);
		$limit_tmp = mysql_num_rows($rs);
		$tmpPHP .= '$p = &$this->pluginCache;' . "\n";
		for ($i_tmp=0; $i_tmp<$limit_tmp; $i_tmp++) { 
		   $tmp1 = mysql_fetch_assoc($rs); 
		   $tmpPHP .= '$p[\''.mysql_escape_string($tmp1['name']).'\']'." = '".$this->escapeSingleQuotes($tmp1['plugincode'])."';\n";
		   if ($tmp1['properties']!=""||$tmp1['sharedproperties']!="") $tmpPHP .= '$p[\''.$tmp1['name'].'Props\']'." = '".$this->escapeSingleQuotes($tmp1['properties']." ".$tmp1['sharedproperties'])."';\n";
		}


		// WRITE system event triggers
		$sql = "
			SELECT sysevt.name as 'evtname', pe.pluginid, plugs.name
			FROM $dbase.`".$table_prefix."system_eventnames` sysevt
			INNER JOIN $dbase.`".$table_prefix."site_plugin_events` pe ON pe.evtid = sysevt.id
			INNER JOIN $dbase.`".$table_prefix."site_plugins` plugs ON plugs.id = pe.pluginid
			WHERE plugs.disabled=0
			ORDER BY sysevt.name,pe.priority
		";
		$events = array();
		$rs = mysql_query($sql);
		$limit_tmp = mysql_num_rows($rs);
		$tmpPHP .= '$e = &$this->pluginEvent;' . "\n";
		for ($i=0; $i<$limit_tmp; $i++) { 
			$evt = mysql_fetch_assoc($rs); 
			if(!$events[$evt['evtname']]) $events[$evt['evtname']] = array();
			$events[$evt['evtname']][] = $evt['name'];
		}
		foreach($events as $evtname => $pluginnames) {
			$tmpPHP .= '$e[\''.$evtname.'\'] = array(\''.implode("','",$pluginnames)."');\n";
		}
		
		// close and write the file
		$tmpPHP .= "?>";		
		$filename = $this->cachePath.'siteCache.idx.php';
		$somecontent = $tmpPHP;
		
		// invoke OnBeforeCacheUpdate event
		if ($modx) $modx->invokeEvent("OnBeforeCacheUpdate");
				
		if (!$handle = fopen($filename, 'w')) {
			 echo "Cannot open file ($filename)";
			 exit;
		}
		
		// Write $somecontent to our opened file.
		if (fwrite($handle, $somecontent) === FALSE) {
		   echo "Cannot write main MODx cache file! Make sure the assets/cache directory is writable!";
		   exit;
		}
		fclose($handle);

		// invoke OnCacheUpdate event
		if ($modx) $modx->invokeEvent("OnCacheUpdate");

/****************************************************************************/
/*	END OF BUILD CACHE FILES												*/
/*	PUBLISH TIME FILE														*/
/****************************************************************************/

		// update publish time file
		$timesArr = array();
		$sql = "SELECT MIN(pub_date) AS minpub FROM $dbase.`".$table_prefix."site_content` WHERE pub_date>".time();
		if(@!$result = mysql_query($sql)) {
			echo "Couldn't determine next publish event!";
		}

		$tmpRow = mysql_fetch_assoc($result);
		$minpub = $tmpRow['minpub'];
		if($minpub!=NULL) {
			$timesArr[] = $minpub;
		}
		
		$sql = "SELECT MIN(unpub_date) AS minunpub FROM $dbase.`".$table_prefix."site_content` WHERE unpub_date>".time();
		if(@!$result = mysql_query($sql)) {
			echo "Couldn't determine next unpublish event!";
		}
		$tmpRow = mysql_fetch_assoc($result);
		$minunpub = $tmpRow['minunpub'];
		if($minunpub!=NULL) {
			$timesArr[] = $minunpub;
		}

		if(count($timesArr)>0) {
			$nextevent = min($timesArr);
		} else {
			$nextevent = 0;			
		}
		
		// write the file
		$filename = $this->cachePath.'/sitePublishing.idx.php';
		$somecontent = "<?php \$cacheRefreshTime=$nextevent; ?>";
		
		if (!$handle = fopen($filename, 'w')) {
			 echo "Cannot open file ($filename)";
			 exit;
		}
		
		// Write $somecontent to our opened file.
		if (fwrite($handle, $somecontent) === FALSE) {
		   echo "Cannot write publishing info file! Make sure the assets/cache directory is writable!";
		   exit;
		}

		fclose($handle);


/****************************************************************************/
/*	END OF PUBLISH TIME FILE												*/
/****************************************************************************/

		// finished cache stuff.
		if($this->showReport==true) { 
		global $_lang;
			printf($_lang["refresh_cache"], $filesincache, $deletedfilesincache);
			$limit = count($deletedfiles);
			if($limit > 0) {
				echo "<p />".$_lang['cache_files_deleted']."<ul>";
				for($i=0;$i<$limit; $i++) {
					echo "<li>".$deletedfiles[$i]."</li>";
				}
				echo "</ul>";
			}
		}	
	}
}
?>
