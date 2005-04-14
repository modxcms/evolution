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
	
	function getParents($id, $path = '') { // modx:returns child's parent
		global $dbase, $table_prefix;
		$sql = "SELECT id, alias, parent FROM $dbase.".$table_prefix."site_content WHERE id = '$id'";
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
		if ($handle = opendir($this->cachePath)) {
			while (false !== ($file = readdir($handle))) { 
				if ($file != "." && $file != "..") { 
					$filesincache += 1;
					if (preg_match ("/\.pageCache/", $file)) {
						$deletedfilesincache += 1;
						$deletedfiles[] = $file;
						unlink($this->cachePath.$file);
					}
				} 
			}
			closedir($handle); 
		}

/****************************************************************************/
/*	BUILD CACHE FILES														*/
/****************************************************************************/

		
		global $modx;
		global $dbase, $table_prefix;

		$tmpPHP = "<?php\n";

		// SETTINGS & DOCUMENT LISTINGS CACHE

		// get settings
		$sql = "SELECT * FROM $dbase.".$table_prefix."system_settings"; 
		$rs = mysql_query($sql);
		$limit_tmp = mysql_num_rows($rs);
		$config = array();
		while(list($key,$value) = mysql_fetch_row($rs)) {
			$tmpPHP .= '$this->config[\''.$key.'\']'.' = "'.mysql_escape_string($value).'"'.";\n";
			$config[$key] = $value;
		}

		// get aliases modx: support for alias path
		$tmpPath = '';
		$tmpPHP .= '$this->aliasListing = array();' . "\n";
		$sql = "SELECT alias, id, contentType, parent FROM $dbase.".$table_prefix."site_content WHERE alias IS NOT NULL AND alias <> ''"; 
		$rs = mysql_query($sql);
		$limit_tmp = mysql_num_rows($rs);
		for ($i_tmp=0; $i_tmp<$limit_tmp; $i_tmp++) { 
			$tmp1 = mysql_fetch_assoc($rs); 
			if ($config['friendly_urls'] == 1 && $config['use_alias_path'] == 1) {
				$tmpPath = $this->getParents($tmp1['parent']);
				$alias = (strlen($tmpPath) > 0 ? "$tmpPath/" : '').$tmp1[alias];
				$tmpPHP .= '$this->documentListing[\''.mysql_escape_string($alias).'\']'." = ".$tmp1['id'].";\n";
			}
			else {
				$tmpPHP .= '$this->documentListing[\''.mysql_escape_string($tmp1['alias']).'\']'." = ".$tmp1['id'].";\n";
			}
			$tmpPHP .= '$this->aliasListing[' . $tmp1['id'] . ']'." = array('id' => ".$tmp1['id'].", 'alias' => '".mysql_escape_string($tmp1['alias'])."', 'path' => '" . mysql_escape_string($tmpPath). "');\n";
		}
		
				
		// get content types
		$sql = "SELECT id, contentType FROM $dbase.".$table_prefix."site_content"; 
		$rs = mysql_query($sql);
		$limit_tmp = mysql_num_rows($rs);
		for ($i_tmp=0; $i_tmp<$limit_tmp; $i_tmp++) { 
		   $tmp1 = mysql_fetch_assoc($rs); 
		   $tmpPHP .= '$this->contentTypes['.$tmp1['id'].']'." = '".$tmp1['contentType']."';\n";
		}

		// WRITE Chunks to cache file
		$sql = "SELECT * FROM $dbase.".$table_prefix."site_htmlsnippets"; 
		$rs = mysql_query($sql);
		$limit_tmp = mysql_num_rows($rs);
		for ($i_tmp=0; $i_tmp<$limit_tmp; $i_tmp++) { 
		   $tmp1 = mysql_fetch_assoc($rs); 
		   $tmpPHP .= '$this->chunkCache[\''.mysql_escape_string($tmp1['name']).'\']'." = '".base64_encode($tmp1['snippet'])."';\n";
		}

		// WRITE snippets to cache file
		$sql = "SELECT * FROM $dbase.".$table_prefix."site_snippets"; 
		$rs = mysql_query($sql);
		$limit_tmp = mysql_num_rows($rs);
		for ($i_tmp=0; $i_tmp<$limit_tmp; $i_tmp++) { 
		   $tmp1 = mysql_fetch_assoc($rs); 
		   $tmpPHP .= '$this->snippetCache[\''.mysql_escape_string($tmp1['name']).'\']'." = '".base64_encode($tmp1['snippet'])."';\n";
		   // Raymond: save snippet propeties to cache
		   if ($tmp1['properties']!="") $tmpPHP .= '$this->snippetCache[\''.$tmp1['name'].'Props\']'." = '".base64_encode($tmp1['properties'])."';\n";
		   // End mod
		}
		

		// WRITE plugins to cache file
		$sql = "SELECT * FROM $dbase.".$table_prefix."site_plugins WHERE disabled=0"; 
		$rs = mysql_query($sql);
		$limit_tmp = mysql_num_rows($rs);
		for ($i_tmp=0; $i_tmp<$limit_tmp; $i_tmp++) { 
		   $tmp1 = mysql_fetch_assoc($rs); 
		   $tmpPHP .= '$this->pluginCache[\''.mysql_escape_string($tmp1['name']).'\']'." = '".base64_encode($tmp1['plugincode'])."';\n";
		   if ($tmp1['properties']!="") $tmpPHP .= '$this->pluginCache[\''.$tmp1['name'].'Props\']'." = '".base64_encode($tmp1['properties'])."';\n";
		}


		// WRITE system event triggers
		$sql = "
			SELECT sysevt.name as 'evtname', pe.pluginid, plugs.name 
			FROM $dbase.".$table_prefix."system_eventnames sysevt 
			INNER JOIN $dbase.".$table_prefix."site_plugin_events pe ON pe.evtid = sysevt.id 
			INNER JOIN $dbase.".$table_prefix."site_plugins plugs ON plugs.id = pe.pluginid
			WHERE plugs.disabled=0 
			ORDER BY sysevt.name;
		";
		$events = array();
		$rs = mysql_query($sql);
		$limit_tmp = mysql_num_rows($rs);
		for ($i=0; $i<$limit_tmp; $i++) { 
			$evt = mysql_fetch_assoc($rs); 
			if(!$events[$evt['evtname']]) $events[$evt['evtname']] = array();
			$events[$evt['evtname']][] = $evt['name'];
		}
		foreach($events as $evtname => $pluginnames) {
			$tmpPHP .= '$this->pluginEvent[\''.$evtname.'\'] = array(\''.implode("','",$pluginnames)."');\n";
		}
		
		// close and write the file
		$tmpPHP .= "?>";		
		$filename = '../assets/cache/siteCache.idx';
		$somecontent = $tmpPHP;
		
		// invoke OnBeforeCacheUpdate event
		if ($modx) $modx->invokeEvent("OnBeforeCacheUpdate");
				
		if (!$handle = fopen($filename, 'w')) {
			 echo "Cannot open file ($filename)";
			 exit;
		}
		
		// Write $somecontent to our opened file.
		if (fwrite($handle, $somecontent) === FALSE) {
		   echo "Cannot write main Etomite cache file! Make sure the assets/cache directory is writable!";
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
		$sql = "SELECT MIN(pub_date) AS minpub FROM $dbase.".$table_prefix."site_content WHERE pub_date>".time();
		if(@!$result = mysql_query($sql)) {
			echo "Couldn't determine next publish event!";
		}

		$tmpRow = mysql_fetch_assoc($result);
		$minpub = $tmpRow['minpub'];
		if($minpub!=NULL) {
			$timesArr[] = $minpub;
		}
		
		$sql = "SELECT MIN(unpub_date) AS minunpub FROM $dbase.".$table_prefix."site_content WHERE unpub_date>".time();
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
		$filename = '../assets/cache/sitePublishing.idx';
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