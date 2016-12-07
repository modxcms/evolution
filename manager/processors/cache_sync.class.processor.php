<?php
// cache & synchronise class
if(!class_exists('synccache')) {
	class synccache
	{
		var $cachePath;
		var $showReport;
		var $deletedfiles = array();
		var $aliases = array();
		var $parents = array();
		var $aliasVisible = array();


		function __construct() {
		}
		
		function setCachepath($path)
		{
			$this->cachePath = $path;
		}

		function setReport($bool)
		{
			$this->showReport = $bool;
		}

		function escapeDoubleQuotes($s)
		{
			$q1 = array("\\", "\"", "\r", "\n", "\$");
			$q2 = array("\\\\", "\\\"", "\\r", "\\n", "\\$");
			return str_replace($q1, $q2, $s);
		}

		function escapeSingleQuotes($s)
		{
			if($s=='') return $s;
			$q1 = array("\\", "'");
			$q2 = array("\\\\", "\\'");
			return str_replace($q1, $q2, $s);
		}

		function getParents($id, $path = '')
		{ // modx:returns child's parent
			global $modx;
			if (empty($this->aliases)) {
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

		function emptyCache($modx = null)
		{
			if (is_a($modx, 'DocumentParser') === false || get_class($modx) !== 'DocumentParser') {
				$modx = $GLOBALS['modx'];
			}
			if (!isset($this->cachePath)) {
				$modx->messageQuit("Cache path not set.");
			}
			$filesincache = 0;
			$deletedfilesincache = 0;

			// New and improved!
			$files = glob(realpath($this->cachePath) . '/*');
			$filesincache = count($files);
			$deletedfiles = array();
			while ($file = array_shift($files)) {
				$name = basename($file);
				if (preg_match('/\.pageCache/', $name) && !in_array($name, $deletedfiles)) {
					$deletedfilesincache++;
					$deletedfiles[] = $name;
					@unlink($file);
					clearstatcache();
                }
            }

        $this->buildCache($modx);

		$this->publishTimeConfig();

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

	public function publishTimeConfig($cacheRefreshTime='')
	{

		$cacheRefreshTimeFromDB = $this->getCacheRefreshTime();
		if(!preg_match('@^[0-9]+$]@',$cacheRefreshTime) || $cacheRefreshTimeFromDB < $cacheRefreshTime)
			$cacheRefreshTime = $cacheRefreshTimeFromDB;


		// write the file
		$content = array();
		$content[] = '<?php';
		$content[] = sprintf('$recent_update = %s;'   , $_SERVER['REQUEST_TIME']);
		$content[] = sprintf('$cacheRefreshTime = %s;', $cacheRefreshTime);

		$filename = $this->cachePath.'/sitePublishing.idx.php';
		if (!$handle = fopen($filename, 'w')) {
			echo 'Cannot open file ('.$filename.')';
			exit;
		}

		// Write $somecontent to our opened file.
		if (fwrite($handle, implode("\n",$content)) === FALSE) {
			echo 'Cannot write publishing info file! Make sure the assets/cache directory is writable!';
			exit;
		}
	}

	public function getCacheRefreshTime()
	{
		global $modx;

		// update publish time file
		$timesArr = array();
		$current_time = $_SERVER['REQUEST_TIME'] + $modx->config['server_offset_time'];

		$result = $modx->db->select('MIN(pub_date) AS minpub', $modx->getFullTableName('site_content'), 'pub_date>'.$current_time);
		if(!$result) echo "Couldn't determine next publish event!";

		$minpub = $modx->db->getValue($result);
		if($minpub!=NULL)
			$timesArr[] = $minpub;

		$result = $modx->db->select('MIN(unpub_date) AS minunpub', $modx->getFullTableName('site_content'), 'unpub_date>'.$current_time);
		if(!$result) echo "Couldn't determine next unpublish event!";

		$minunpub = $modx->db->getValue($result);
		if($minunpub!=NULL)
			$timesArr[] = $minunpub;

		if(isset($this->cacheRefreshTime) && !empty($this->cacheRefreshTime))
			$timesArr[] = $this->cacheRefreshTime;

		if(count($timesArr)>0) $cacheRefreshTime = min($timesArr);
		else                   $cacheRefreshTime = 0;
		return $cacheRefreshTime;
	}

    /**
     * build siteCache file
     * @param  DocumentParser $modx
     * @return boolean success
     */
    public function buildCache($modx) {
        $tmpPHP = "<?php\n";

        // SETTINGS & DOCUMENT LISTINGS CACHE

        // get settings
        $rs = $modx->db->select('*', $modx->getFullTableName('system_settings'));
        $config = array();
			$tmpPHP .= '$c=&$this->config;';
        while(list($key,$value) = $modx->db->getRow($rs,'num')) {
				$tmpPHP .= '$c[\'' . $this->escapeSingleQuotes($key) . '\']' . '="' . $this->escapeDoubleQuotes($value) . "\";";
            $config[$key] = $value;
        }

        // get aliases modx: support for alias path
        $tmpPath = '';
        $tmpPHP .= '$this->aliasListing=array();';
	$tmpPHP .= '$a=&$this->aliasListing;';
	$tmpPHP .= '$d=&$this->documentListing;';
	$tmpPHP .= '$m=&$this->documentMap;';
        if ($config['aliaslistingfolder'] == 1) {
            $rs = $modx->db->select('IF(alias=\'\', id, alias) AS alias, id, parent, isfolder, alias_visible', $modx->getFullTableName('site_content'), 'deleted=0 and isfolder=1', 'parent, menuindex');
        }else{
            $rs = $modx->db->select('IF(alias=\'\', id, alias) AS alias, id, parent, isfolder, alias_visible', $modx->getFullTableName('site_content'), 'deleted=0', 'parent, menuindex');
        }
        while ($tmp1 = $modx->db->getRow($rs)) {
            if ($config['friendly_urls'] == 1 && $config['use_alias_path'] == 1) {
                $tmpPath = $this->getParents($tmp1['parent']);
                $alias= (strlen($tmpPath) > 0 ? "$tmpPath/" : '').$tmp1['alias'];
                $tmpPHP .= '$d[\'' . $this->escapeSingleQuotes($alias) . '\']' . " = " . $tmp1['id'] . ";";
            } else {
                $tmpPHP .= '$d[\'' . $this->escapeSingleQuotes($tmp1['alias']) . '\']' . " = " . $tmp1['id'] . ";";
            }
            $tmpPHP .= '$a[' . $tmp1['id'] . ']' . " = array('id' => " . $tmp1['id'] . ", 'alias' => '" . $this->escapeSingleQuotes($tmp1['alias']) . "', 'path' => '" . $this->escapeSingleQuotes($tmpPath) . "', 'parent' => " . $tmp1['parent'] . ", 'alias_visible' => " . $tmp1['alias_visible'] . ", 'isfolder' => " . $tmp1['isfolder'] . ");";
            $tmpPHP .= '$m[]'." = array('".$tmp1['parent']."' => '".$tmp1['id']."');";
        }

        // get content types
        $rs = $modx->db->select('id, contentType', $modx->getFullTableName('site_content'), "contentType != 'text/html'");
        $tmpPHP .= '$c = &$this->contentTypes;';
        while ($tmp1 = $modx->db->getRow($rs)) {
            $tmpPHP .= '$c[' . $tmp1['id'] . ']' . " = '" . $this->escapeSingleQuotes($tmp1['contentType']) . "';";
        }

        // WRITE Chunks to cache file
        $rs = $modx->db->select('*', $modx->getFullTableName('site_htmlsnippets'));
        $tmpPHP .= '$c = &$this->chunkCache;';
        while ($tmp1 = $modx->db->getRow($rs)) {
				/** without trim */
            $tmpPHP .= '$c[\'' . $this->escapeSingleQuotes($tmp1['name']) . '\']' . " = '" . $this->escapeSingleQuotes($tmp1['snippet']) . "';";
        }

        // WRITE snippets to cache file
        $rs = $modx->db->select(
			'ss.*, sm.properties as sharedproperties',
			'[+prefix+]site_snippets ss LEFT JOIN [+prefix+]site_modules sm on sm.guid=ss.moduleguid'
			);
			$tmpPHP .= '$s=&$this->snippetCache;';
			while ($row = $modx->db->getRow($rs)) {
				$name = $this->escapeSingleQuotes($row['name']);
				$code = trim($row['snippet']);
				if($modx->config['minifyphp_incache'])
					$code = $this->php_strip_whitespace($code);
				$code = $this->escapeSingleQuotes($code);
				$properties       = $modx->parseProperties($row['properties']);
				$sharedproperties = $modx->parseProperties($row['sharedproperties']);
				$properties = array_merge($sharedproperties,$properties);
				$tmpPHP .= sprintf("\$s['%s']='%s';", $name, $code);
				if (0<count($properties)) {
    				$properties = json_encode($properties);
    				$properties = $this->escapeSingleQuotes($properties);
					$tmpPHP .= sprintf("\$s['%sProps']='%s';", $name, $properties);
				}
			}

			// WRITE plugins to cache file
			$rs = $modx->db->select(
				'sp.*, sm.properties as sharedproperties',
				'[+prefix+]site_plugins sp LEFT JOIN [+prefix+]site_modules sm on sm.guid=sp.moduleguid',
				'sp.disabled=0');
			$tmpPHP .= '$p=&$this->pluginCache;';
			while ($row = $modx->db->getRow($rs)) {
				$name = $this->escapeSingleQuotes($row['name']);
				$code = trim($row['plugincode']);
				if($modx->config['minifyphp_incache'])
					$code = $this->php_strip_whitespace($code);
				$tmpPHP .= sprintf("\$p['%s']='%s';", $name, $this->escapeSingleQuotes($code));
				if ($row['properties'] != '' || $row['sharedproperties'] != '') {
					$k = $name;
					$v = $this->escapeSingleQuotes($row['properties'] . ' ' . $row['sharedproperties']);
					$tmpPHP .= sprintf("\$p['%sProps']='%s';", $k, $v);
				}
			}

			// WRITE system event triggers
			$events = array();
			$rs = $modx->db->select(
				'sysevt.name as evtname, event.pluginid, plugin.name',
				'[+prefix+]system_eventnames sysevt
				INNER JOIN [+prefix+]site_plugin_events event ON event.evtid=sysevt.id
				INNER JOIN [+prefix+]site_plugins plugin ON plugin.id=event.pluginid',
				'plugin.disabled=0',
				'sysevt.name, event.priority'
			);
			$tmpPHP .= '$e = &$this->pluginEvent;';
			while ($evt = $modx->db->getRow($rs)) {
				if (!isset($events[$evt['evtname']])) {
					$events[$evt['evtname']] = array();
				}
				$events[$evt['evtname']][] = $evt['name'];
			}
			foreach ($events as $evtname => $pluginnames) {
				$tmpPHP .= '$e[\'' . $this->escapeSingleQuotes($evtname) . '\']=array(\'' . implode("','", $this->escapeSingleQuotes($pluginnames)) . "');";
			}

			// close and write the file
			$filename = $this->cachePath . 'siteCache.idx.php';
			$somecontent = $tmpPHP;

			// invoke OnBeforeCacheUpdate event
			if ($modx) $modx->invokeEvent('OnBeforeCacheUpdate');

			if (!$handle = fopen($filename, 'w')) {
				echo 'Cannot open file (', $filename, ')';
				exit;
			}

			if (!is_file($this->cachePath . '/.htaccess')) {
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
		
        // ref : http://php.net/manual/en/tokenizer.examples.php
        function php_strip_whitespace($source) {
            
            $source = trim($source);
            if(substr($source,0,5)!=='<?php') $source = '<?php ' . $source;
            
            $tokens = token_get_all($source);
            $_ = '';
            $prev_token = 0;
            $chars = explode(' ', '( ) ; , = { } ? :');
            foreach ($tokens as $i=>$token) {
                if (is_string($token)) {
                    if(in_array($token,array('=',':')))
                        $_ = trim($_);
                    elseif(in_array($token,array('(','{')) && in_array($prev_token,array(T_IF,T_ELSE,T_ELSEIF)))
                        $_ = trim($_);
                    $_ .= $token;
                    continue;
                }
                
                list($type, $text) = $token;
                
                switch ($type) {
                    case T_COMMENT    :
                    case T_DOC_COMMENT:
                        break;
                    case T_WHITESPACE :
                        $_ = trim($_);
                        $lastChar = substr($_,-1);
                        if( !in_array($lastChar,$chars ) ) {// ,320,327,288,284,289
                            if(!in_array($prev_token,array(T_FOREACH,T_WHILE,T_FOR,T_BOOLEAN_AND,T_BOOLEAN_OR,T_DOUBLE_ARROW)))
                                $_ .= ' ';
                        }
                        break;
                    case T_IS_EQUAL :
                    case T_IS_IDENTICAL :
                    case T_IS_NOT_EQUAL :
                    case T_DOUBLE_ARROW :
                    case T_BOOLEAN_AND :
                    case T_BOOLEAN_OR :
                        $prev_token=$type;
                        $_ = trim($_);
                        $_ .= $text;
                        break;
                    default:
                        $prev_token=$type;
                        $_ .= $text;
                }
            }
            $source = preg_replace('@^<\?php@', '', $_);
            $source = trim($source);
            return $source;
        }
	}
}
